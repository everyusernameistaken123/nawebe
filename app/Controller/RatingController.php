<?php
class RatingController extends Controller {
	public $uses = array(
			'Website', 'Domain',
			'Filler', 'WebsiteReadability',
			'KnownWord', 'UnknownWord',
			'Shorthand', 'Brand'
	);
	
	public function rate($id = null, $slave = false, $data = null) {
		if (!$slave) {
			$verbose_debug = $this->request->query('verbose_output');
			$timing_output = $this->request->query('timing_output');
			$use_dictionary = $this->request->query('use_dictionary');
			$replace_shorthands = $this->request->query('replace_shorthands');
		}
		
		if (!isset($timing_output)) {
			$timing_output = false;
		}
		if (!isset($use_dictionary)) {
			$use_dictionary = true;
		}
		if (!isset($replace_shorthands)) {
			$replace_shorthands = true;
		}
		
		if ($replace_shorthands) {
			//Macht die Sache recht Rechenzeit-lastig, für jedes Fragment 280 str_replace...
			$shorthands = $this->Shorthand->getList();
		}
		
		if ($id == null) {
			//Um es als Cronjob zu benutzen
			//DEBUG: zum "refreshen" umprogrammiert, aber mittlerweile haben wir keinen sourcecode mehr in der DB... Also deprecated
			$sites = array();
			/*
			$sites = $this->Website->find('all',array(
					'fields' => array('id'),
					'conditions'=>array(
							//'date_rated is null',
							"date_rated < '2016-01-25'",
							"sourcecode != ''"
					),
					'limit' => 10,
					'order' => 'id'
			));
			*/
			foreach($sites as $site) {
				echo "<br/>#### Website ID ".$site['Website']['id'];
				$this->rate($site['Website']['id'], true);
			}
			
			$this->_stop();
		}
		
		App::import('Model','Rating');
		
		$this->Website->id = $id;
		if ($data == null) {
			if (!$this->Website->exists()) {
				throw new NotFoundException('Website ID '.$id.' not found');
			}
			$data = $this->Website->read();
		}
		$source = $data['Website']['sourcecode'];
		
		if ($source == '') {
			App::import('Controller','Crawler');
			$crawlerController = new CrawlerController();
			
			$source = $crawlerController->crawlWebsites( 0, $id, true );
			
			//throw new NotFoundException('Sourcecode for Website ID '.$id.' not available. Please call Crawler/crawlWebsites/0/'.$id);
		}
		
		if (!isset($verbose_debug)) {
			$verbose_debug = false;
		} else if ($verbose_debug) {
			$style_spans = array(
					'nav' => '<span style="background-color:#DDD;color:#999;">',
					'sentence' => '<span style="background-color:#CFC;">',
					'single_word' => '<span style="color:#999;">',
					'known' => '<span style="color:#080;">',
					'unknown' => '<span style="color:#B44;">',
					'brand' => '<span style="color:#008;">'
			);
			echo '<hr>';
			echo 'Legende:<br/>';
			echo $style_spans['nav'].'Navigations-Menü</span><br/>';
			echo $style_spans['sentence'].'Als Satz erkannt</span><br/>';
			echo $style_spans['single_word'].'Einzelne Wörter</span><br/>';
			echo $style_spans['known'].'im Wörterbuch</span><br/>';
			echo $style_spans['unknown'].'nicht im Wörterbuch</span><br/>';
			echo $style_spans['brand'].'Markenname / Eigenname - Wird nicht als Wörterbuch gewertet</span><br/>';
			echo '<hr>';
		}
		
		$total_time_start = microtime(true);
		
		$this->WebsiteReadability->query('delete from website_readabilities where website_id = '.$id);
		
		$filler_words = $this->Filler->find('list',array('conditions'=>array('type'=>'word')));
		$filler_phrases = $this->Filler->find('list',array('conditions'=>array('type'=>'phrase')));
		
		//Website-Inhalt als DOM laden
		$doc = new DOMDocument();
		//You saved my life: http://stackoverflow.com/a/3304473
		$source = mb_convert_encoding($source, 'HTML-ENTITIES', "UTF-8");
		$loadHTML_time_start = microtime(true);
		@$doc->loadHTML($source);
		if ($timing_output) {
			echo "<br/>[Timing] LoadHTML in ".((microtime(true) - $loadHTML_time_start)*1000)."ms";
		}
		
		$count_words = 0;
		$count_long_words = 0;
		$long_word_threshold = 6;
		$count_sentences = 0;
		$count_known_words = 0;
		$count_dict_words = 0;
		$count_fillers = 0;
		$count_characters = 0;
		
		$xpath = new DOMXPath($doc);
		
		//"Störende" HTML-Tags entfernen
		$brs = $xpath->query("/html/body//br");
		foreach ($brs as $node) {
			$node->parentNode->replaceChild(
       			$doc->createTextNode(' '),
       			$node
    		);
		}
		$scripts = $xpath->query("/html/body//script | /html/body//style");
		foreach ($scripts as $node) {
			$node->parentNode->removeChild($node);
		}
		$navigation_queries = array(
				"/html/body//nav | /html/body//menu", //HTML5
				"/html/body//*[@role='navigaton']", //ARIA
				"/html/body//*[@id='nav' or @id='menu' or @id='navigation']",
				"/html/body//*[contains(@class , 'nav') or contains(@class , 'menu') or contains(@class , 'navigation')]"
		);
		foreach($navigation_queries as $query) {
			$navigations = $xpath->query($query);
			foreach ($navigations as $node) {
				if ($verbose_debug) {
					echo '<br/>'.$style_spans['nav'].$node->nodeValue.'</span>';
				}
				$node->parentNode->removeChild($node);
			}
		}
		
		//Gesamten Text
		
		//Hier kann man wohl viele verschiedene XPaths verwenden, zB nur Texte in <p> usw.
		
		//$textnodes = $xpath->query('//text()');
		$textnodes = $xpath->query("/html/body//*[text()]"); // and not(*)
		//$textnodes = $xpath->query("/html/body//*[not(name()='script') and not(name()='style')]/text()");
		$foundnodes = array();
		
		//Strukturen wie <ul><li>X</li><li>Y</li></ul> werden zu "XY" (ohne Trennzeichen)
		//das ist bei einem Menü nicht OK, aber zB bei <span><i>Ha</i><u>llo</u></span> ist es gut.
		//Lösungsansatz: Block-Elemente auch als "Satz" auffassen
		
		$block_elements = array("p","ul","ol","pre","dl","div",
				"h1","h2","h3","h4","h5","h6",
				"blockquote","form","table","fieldset",
				"address","video","header","footer", //HTML5
				"article","section","aside", //HTML5
				"li","option","dd", //Achtung: Keine offiziellen Block-Elemente, aber wir wollen dass deren Inhalt als eigener Satz erfasst wird
				"tr","th","td" //
				//TODO: dt auch?
		);
		
		//DEBUG: Früher oder später auch CSS-display überprüfen? Ob "display: block" usw angegeben ist?
		
		$xPath_first_time = microtime(true);
		
		foreach ($textnodes as $node) {
			//echo "<br/>single-text: ".$node->nodeValue;
			while(true && $node != null) {
				
				$temp = $node->nodeValue;
				//Die folgenden 3 Zeilen waren mal nötig, jetzt nicht mehr?
				//DEBUG - Gut aufpassen dass es keine Probleme mit Umlauten gibt ohne denen..
				//$temp = html_entity_decode($temp);
				//$temp = preg_replace_callback("/(&#[0-9]+;)/", function($m) { return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES"); }, $temp);
				//$temp = trim($temp);
				
				if (preg_match("/\.|\!|\?/",$temp) || in_array(strtolower($node->nodeName), $block_elements)) {
					//echo "<br/>Satzzeichen oder Block Element in ".$node->nodeName;
					$path = $node->getNodePath();
					if (!in_array($path, $foundnodes)) {
						$foundnodes[] = $node->getNodePath();
					}
					break;
				} else {
					$node = $node->parentNode;
				}
			}
		}
		
		if ($timing_output) {
			echo "<br/>[Timing] XPath-Eval in ".((microtime(true) - $xPath_first_time)*1000)."ms";
		}
		
		rsort($foundnodes);
		$processSentences_time = microtime(true);
		
		foreach($foundnodes as $node_path) {
			//echo "<br/>XPath: ".$node_path;
			$result = $xpath->query($node_path);
			$node = $result->item(0);
			$temp = $node->nodeValue;
			
			//"tiefere" Nodes im DOM-Tree die hier abgearbeitet werden, sollen rausgenommen werden
			//zB: <div><p>Das ist Satz 1.</p>und das Satz 2.</div>
			//im ersten schritt wird durch die rsort-Sortierung <p> angeschaut,
			//wenn man dann zu <div> kommt, nicht Inhalt von p nochmal analysieren
			if ($node->parentNode != null) {
				//DEBUG - kann NULL sein... Denkfehler? Oder einfach ignorieren?
				//zB bei Website ID 2 (Führugnspersonal-Thread vom MSM)
				//Oder nur entfernen, wenn parent garantiert leer oder so?
				$node->parentNode->removeChild($node);
			}
			
			
			//Abkürzungen
			if ($replace_shorthands) {
				foreach($shorthands as $abk => $replace) {
					//$debug = $temp;
					$temp = str_replace(' '.$abk, ' '.$replace, $temp);
					//if ($verbose_debug && $temp != $debug) {
						//echo "<br/>Abkürzung ersetzt: ".$abk;
					//}
				}
			}
			
			//Datums-Angaben wie DD.MM.YYYY
			//Datums-Angaben mit Schrägstrich oder Bindestrich sind uns ja wurscht oder? Wegen Sätzen?
			$regex_date = '/([0-9]{2}\.[0-9]{2}\.[0-9]{2,4})/';
			$debug = $temp;
			$temp = preg_replace($regex_date, 'Datum', $temp);
			$verbose_date_output = '';
			if ($verbose_debug && $temp != $debug) {
				$verbose_date_output = " (Datumsangabe ersetzt)";
			}
			
			//URLs
			//http://www.regexguru.com/2008/11/detecting-urls-in-a-block-of-text/
			//$regex_url = "#(((https?|ftp|file)://|www\.|ftp\.)[a-zA-Z0-9\+&@\#/%?=~_|$!:,\.]*[a-zA-Z0-9\+&@\#/%=~_|$] | ((mailto:)?[a-zA-Z0-9\._%\+-]+@[a-zA-Z0-9\._%-]+\.[a-zA-Z]{2,4}))#";
			//regex-guru, etwas modifiziert von mir
			$regex_url = "#((https?|ftp|file)?://|www\.|ftp\.)?[a-zA-Z0-9]+\.[a-zA-Z0-9]+(\.[a-zA-Z0-9]+|(:[0-9])|/[a-zA-Z0-9\+\.&@\#/%=~_]*)#";
			//https://mathiasbynens.be/demo/url-regex
			//$regex_url = "@(https?|ftp)://(-\.)?([^\s/?\.#-]+\.?)+(/[^\s]*)?$@iS";
			$temp = preg_replace($regex_url, 'URL', $temp);
			
			//Nochmal Satzzeichen checken, da eventuell gefundene Satzzeichen im ersten Durchlauf von zuvor bearbeiteteten (und somit entfernten) Nodes stammen
			//Jetzt nicht mehr machen, da wir auch Block-Elemente betachten
			//if (preg_match("/\.|\!|\?/",$temp)) {
			//if (preg_match("/\.|\!|\?/",$temp) || in_array(strtolower($node->nodeName), $block_elements)) {
				if ($verbose_debug) {
					//echo '<br/>found '.$temp;
					//echo ' ('.mb_detect_encoding($temp).')';
				}
				
				//"Satz" (Kann auch nur ein Wort sein, zB bei Abkürzungen)
				$sentences = $this->getSentences($temp);
    			foreach($sentences as $sentence) {
    				//echo $sentence;
    				$words = $this->getWords($sentence);
    				
    				if (count($words) > 1) {
    					if ($verbose_debug) {
    						echo '<br/>'.$style_spans['sentence'];
    					}
    					$count_sentences ++;
    				} else {
    					if ($verbose_debug && count($words) > 0) {
	    					echo "<br/>".$style_spans['single_word'].$sentence.'</span>';
	    				}
    				}
    				
    				foreach($words as $word) {
    					//Einzelnes Wort
    					
    					if (strlen($word) > 128) {
    						//Das ist kein Wort, sondern ne Wurscht...
    						continue;
    					}
    					
    					//Wenn kein "richtiger Satz" dann nur im Wörterbuch nachschauen,
    					//aber NICHT für Readability-Berechnung verwenden!
    					if (count($words) > 1) {
	    					$count_words ++;
	    					if (strlen($word) >= $long_word_threshold) {
	    						$count_long_words ++;
	    					}
	    					$count_characters += strlen($word);
    					}
    					$count_dict_words ++;
    					
    					//<<<< 1 >>>> Ist es im Wörterbuch?
    					//Darf "übersprungen" werden, weil es viel zeit in Anspruch nimmt
    					//(zB um einfach nur Readability-Indizes zu aktualisieren)
    					if ($use_dictionary) {
	    					$search_word_start = microtime(true);
	    					$isKnown = $this->KnownWord->findByWord($word);
	    					$search_word_total += (microtime(true) - $search_word_start);
	    					if ($isKnown != null) {
	    						$use_style = 'known';
	    						$count_known_words ++;
	    					} else {
	    						$use_style = 'unknown';
	    						
	    						//unbekannt
	    						
	    						$brand = $this->Brand->findByBrand($word);
	    						if ($brand == null) {
	    						
		    						$unknown = $this->UnknownWord->findByWord($word);
		    						if ($unknown == null) {
		    							try{
			    							$this->UnknownWord->create();
			    							$this->UnknownWord->save(array('word' => $word));
		    							} catch (Exception $e) {
		    								//2016-4-12: Hier trat ein "Duplicate entry '' for key 'word'" auf
		    								//Sollte zwar eigentlich gar nicht sein (leeres Wort!?) aber naja, Fehler abfangen
		    							}
		    						} else {
		    							$this->UnknownWord->id = $unknown['UnknownWord']['id'];
		    						}
		    						
	    						} else {
	    							$use_style = 'brand';
	    						}
	    						//Zusammenhang zu Website herstellen damit Rating angepasst werden kann
	    						//(Falls Wort doch existieren sollte)
	    						//DEBUG: Bevor wir diese unzähligen Datensätze sinnvoll nutzen, ist es ja einfacher, hin und wieder das Rating für alle neu zu berechnen...
	    						/*
	    						$this->WebsiteUnknownWord->create();
	    						$this->WebsiteUnknownWord->save(array(
	    								'website_id' => $id,
	    								'unknown_word_id' => $this->UnknownWord->id
	    						));
	    						*/
	    					}
    					} else {
    						$use_style = 'known';
    					}
    					
    					if ($verbose_debug && count($words) > 1) {
    						echo '&nbsp;'.$style_spans[$use_style].$word.'</span>';
    					}
    					
    					//<<<< 2a >>>> Einzelne Füllwörter
    					if (in_array($word, $filler_words)) {
    						//echo '<br/>filler "'.$word.'" in '.$sentence;
    						$count_fillers ++;
    					}
    				}
    				
    				if ($verbose_debug) {
    					echo '</span>';
    					echo $verbose_date_output;
    				}
    				
    				//<<<< 2b >>>> Füll-Phrasen im gesamten Satz suchen
    				foreach($filler_phrases as $filler) {
    					if (strpos($sentence, $filler) !== false) {
    						//echo '<br/>filler "'.$filler.'" in '.$sentence;
    						$count_fillers ++;
    					}
    				}
    			}
    		/*
			} else {
				//Gut zum debuggen, ob eh "sinnvolle Sätze" extrahiert wurden bzw. was "veroren" geht
				if ($verbose_debug) {
					echo "<br/>ignore ".$temp;
				}
			}
			*/
		}
		
		if ($timing_output) {
			echo "<br/>[Timing] Analyse words in ".((microtime(true) - $processSentences_time)*1000)."ms";
			echo "<br/>".($search_word_total*1000)." just for Dictionary-Lookup";
		}
		
		$update_datebase_time = microtime(true);
		
		if ($count_dict_words > 0) {
			$rating_dictionary = ($count_known_words / $count_dict_words) * 100;
			$rating_filler = 100 - (($count_fillers / $count_dict_words) * 1000) * 10;
		}
		if ($rating_filler < 0) { $rating_filler = 0; }
		
		$this->Website->id = $id;
		//Von irgendwo vorher steht da noch ne falsche URL drin -.- 
		if (!empty($this->Website->data)) {
			unset($this->Website->data['Website']['url']);
		}
		if ($use_dictionary) {
			$this->Website->saveField('rating_dictionary', $rating_dictionary);
		}
		
		App::import('Controller','Crawler');
		$crawler = new CrawlerController();
		$hash = $crawler->calcHTMLHash($doc);
		
		$this->Website->save(array(
				'rating_fillers' => $rating_filler, //$count_fillers,
				'fillers' => $count_fillers,
				'words' => $count_dict_words,
				'date_rated' => date('Y-m-d H:i:s'),
				'checksum' => $hash,
				'sourcecode' => null //saves a lot of database-space
		));
		
		//Domain-Avg-Rating updaten
		$this->Website->unbindModel(array('hasMany' => array('WebsiteReadability')));
		$avg = $this->Website->find('all',array(
			'fields' => array(
					'avg(rating_dictionary) as avg_dict',
					'avg(rating_fillers) as avg_fill',
					'count(*) as c'
			),
			'conditions' => array('domain_id' => $data['Website']['domain_id'])
		));
		//debug($avg);
		
		$this->Domain->id = $data['Website']['domain_id'];
		$this->Domain->save(array(
				'avg_dictionary' => $avg[0][0]['avg_dict'],
				'avg_fillers' => $avg[0][0]['avg_fill'],
				'sites' => $avg[0][0]['c']
		));
		
		if ($timing_output) {
			echo "<br/>[Timing] DB-Update in ".((microtime(true) - $update_datebase_time)*1000)."ms";
		}
		
		//CakeLog::write('rating','Website ID '.$data['Website']['id']);
		//CakeLog::write('rating','Sätze: '.$count_sentences." ( mit $count_fillers 'Fillern')");
		//CakeLog::write('rating',$count_known_words.' von '.$count_dict_words.' words gefunden');
		echo '<hr>';
		echo "<br/>Sätze: ".$count_sentences;
		echo "<br/>( mit $count_fillers 'Fillern')";
		if ($use_dictionary) {
			echo "<br/>".$count_known_words.' von '.$count_dict_words.' words gefunden';
		}
		
		//<<<< 3 >>>> Readability / Lesbarkeit
		
		//Problem: Für die bekannten "Flesch-Reading-Ease" oder "Wiener Sachtextformel" braucht man Silbentrennung / -Erkennung :(
		//https://github.com/vanderlee/phpSyllable/
		
		//Flesch-Reading-Ease
		//$fre = 180 - asl - (58.5 * asw);
		
		//Daher hier der "Automated Readability Index" verwendet, obwohl der für englische Sätze ist...
		App::import('Model','AriRating');
		$ariClass = new AriRating();
		//Anderer Versuch mit "Laesbarhedsindex" aus Schweden, auch für Deutsch geeignet
		App::import('Model','LixRating');
		$lixClass = new LixRating();
		//Laut Wikipedia der letzte Index, der ohne Silbenzählung auskommt
		//App::import('Model','CliRating');
		//$cliClass = new CliRating();
		
		$rating_algorithms = array($ariClass, $lixClass);
		
		try {
			//Variablen für Rating / Indizes
			foreach($rating_algorithms as $ratingClass) {
				$ratingClass->setParameter('words', $count_words);
				$ratingClass->setParameter('long_words', $count_long_words);
				$ratingClass->setParameter('sentences', $count_sentences);
				$ratingClass->setParameter('characters', $count_characters);
				
				$num = $ratingClass->getRatingNum();
				$txt = $ratingClass->getRatingText();
				$code = $ratingClass->getShortName();
				CakeLog::write('rating',"Lesbarkeit $code: $num / $txt");
				echo "<br/>Lesbarkeit $code: $num / $txt";
				
				$this->WebsiteReadability->create();
				$this->WebsiteReadability->save(array(
						'website_id' => $id,
						'readability_code' => $code,
						'result_num' => $num,
						'result_txt' => $txt
				));
			}
			
		} catch (InvalidArgumentException $e) {
			CakeLog::write('debug','Rating-Error on Website '.$id.': '.$e->getMessage());
			echo '<br/>Rating-Error: '.$e->getMessage();
		}
		
		if ($slave) {
			return;
		} else {
			$this->_stop();
		}
	}
	
	private function getSentences($txt) {
		//So gehts schneller ;)
		$result = mb_split("\.|\!|\?", $txt );
		
		/*
		$sentence = '';
		$result = array();
		$punct = array('.','!','?');
		
		for ($i = 0; $i<mb_strlen($txt); $i++) {
			$char = mb_substr($txt, $i, 1);
			if (in_array($char, $punct)) {
				$result[] = $sentence;
				$sentence = '';
			} else {
				$sentence .= $char;
			}
		}*/
		//$result[] = $sentence;
		
		return $result;
	}
	
	private function getWords($txt) {
		$word = '';
		$result = array();
		
		//DEBUG: Punkt und Apostroph werden als Wort-Trennzeichen gewertet... Wäre zu diskutieren, aber sollte OK sein
		$punct = array(' ',',','-',';','/','(',')','[',']','{','}','|','~','#','@','%','$','*','&','=','>','<','+',':','"',"'","\n","\r",chr(9));
		$filter = array('1','2','3','4','5','6','7','8','9','0');
		$utf8_filter = array(
				169, //Copyright
				174, //"Registered"-R
				8364, //€-Zeichen
				8482, //Trademark-sign
				183, //"middle dot", gefunden auf wiktionary zur Silbentrennung
				171 //&laquo
		);
		$utf8_punct = array(
				160, //&nbsp;
				187, //&raquo
				707, //Auf heute.at gefunden, ähnlich einem >
				//http://www.utf8-chartable.de/unicode-utf8-table.pl
				//"General Punctation"
				8208,8209,
				8210,8211,8212,8213,8214,8215,8216,8217,8218,8219,
				8220,8221,8222,8223,8224,8225,8226,8227,8228,8229,
				8230,8231,
				8249,8250
		);
		
		for ($i = 0; $i<mb_strlen($txt); $i++) {
			$char = mb_substr($txt, $i, 1, 'UTF-8');
			
			if (in_array($char, $punct)) {
				//Zeichen gefunden, dass Wörter trennt
				if (mb_strlen($word) > 1) {
					$result[] = $word;
				}
				$word = '';
			} else {
				if (!in_array($char,$filter,false)) {
					$utf8_code = $this->utf8_ord($char);
					if ($utf8_code > 128) {
						//DEBUG
						//echo "<br/>".$char.' - '.$this->utf8_ord($char);
					}
					if (in_array($utf8_code, $utf8_filter)) {
						//Zeichen einfach überspringen
					} else if (in_array($utf8_code, $utf8_punct)) {
						//Zeichen gefunden, dass Wörter trennt
						if (mb_strlen($word) > 1) {
							$result[] = $word;
						}
						$word = '';
					} else {
						$word .= $char;
					}
					
				}
			}
		}
		
		if (mb_strlen($word) > 1) {
			$result[] = $word;
		}
		return $result;
	}
	
	private function utf8_ord($char) {
		//Hopefully it's faster without check for each character...
	    //if (mb_check_encoding($char, 'UTF-8')) {
	    	$ret = mb_convert_encoding($char, 'UTF-32BE', 'UTF-8');
	        return hexdec(bin2hex($ret));
	    //} else {
	    //	return ord($char);
	    //}
	}
}
?>