<?php
class CrawlerController extends Controller {
	public $uses = array('KnownWord', 'Website', 'Domain');
	
	public function crawlWebsites($limit = 10, $id = null, $slave = false) {
		if ($id == null) {
			
			$urls = $this->Website->find('all',array(
					'conditions'=>array('date_crawled is null'),
					'limit' => $limit,
					//'order' => 'rand()'
					'order'=>'Website.id'
			));
			
			//Just to "refresh" all crawled data -> DEBUG
			
			//SELECT * FROM `websites` WHERE date_crawled is not null and date_rated is null order by id
			
			/*
			$urls = $this->Website->find('all',array(
					'conditions'=>array("date_rated < '2016-07-01'"),
					'limit' => $limit,
					'order' => 'rand()'
			));
			*/
		} else {
			$urls = $this->Website->findAllById($id);
		}
		
		//TODO
		/*
		//Irgendwas müssen wir tun, damit "name=Shneor's Tarte..." richtig verarbeitet wird
		//Aber dazu müssten wir die URL komplett zerlegen und nur die "Parameter" encodieren... mühsam...
		//[2016-07-17] Kann php parse_url das nicht für uns zerlegen?
		$url = "http://hausbrot.at/Store/Product/Browse?name=Shneor's Tarte - Zitronencreme&productid=724&date=2015.12.23&returnUrl=/Store/Index/Browse?ProductGroupId=33";
		//$url = "http://forum.motorsportmanager.de/viewtopic.php?f=42&t=3152";
		$parts = parse_url ( $url );
		$parts['query'] = rawurlencode($parts['query']);
		$url = http_build_url($parts);
		debug($url);
		$result = $this->curl_loadUrl( $url, 'hausbrot.at' );
		debug($result);
		die();
		*/
		
		foreach($urls as $url) {
			echo "<br/>URL: ".$url['Website']['url'];
			$domain = parse_url($url['Website']['url'], PHP_URL_HOST);
			
			$domainObject = $this->Domain->findByDomain($domain);
			if ($domainObject != null) {
				if ($domainObject['Domain']['blacklist'] == 1) {
					CakeLog::write('debug','drop '.$url['Website']['url'].' because of blacklist');
					$this->Website->id = $url['Website']['id'];
					$this->Website->delete();
					continue;
				}
			}
			
			$new_count = 0;
			$rel_nofollow_count = 0;
			
			$result = $this->curl_loadUrl( $url['Website']['url'], $domain );
			
			if ($result['http_code'] >= 400) {
				CakeLog::write('debug','drop '.$url['Website']['url'].' because of http-response '.$result['http_code']);
				$this->Website->id = $url['Website']['id'];
				$this->Website->delete();
				continue;
			}
			
			$header = strtolower($result['header']);
			$html = $result['content'];
			$url['Website']['sourcecode'] = $html;
		
			if ($url['Website']['url'] != $result['effective_url']) {
				//Wir wurden weiter geleitet
				$old_url = $url['Website']['url'];
				$url['Website']['url'] = $result['effective_url'];
				
				$existing = $this->Website->find('first',array('conditions'=>array(
						'url'=>$result['effective_url'],
						'Website.id != '.$url['Website']['id']
				)));
				if (!empty($existing)) {
					CakeLog::write('debug','drop '.$old_url.' because of already existing as '.$url['Website']['url'].' / '.$existing['Website']['id']);
					$this->Website->id = $url['Website']['id'];
					$this->Website->delete();
					continue;
				}
				
				$domain = parse_url($url['Website']['url'], PHP_URL_HOST);
				$domainObject = $this->Domain->findByDomain($domain);
			}
			
			$domain_ending = null;
			for($i = strlen($domain) - 1; $i > 0; $i--) {
				if (substr($domain, $i, 1) == '.') {
					$domain_ending = substr($domain, $i+1);
					break;
				}
			}
			
			if ($domainObject != null) {
				$this->Domain->id = $domainObject ['Domain']['id'];
				
				//Nochmal schauen wegen möglicher Weiterleitung
				if ($domainObject['Domain']['blacklist'] == 1) {
					CakeLog::write('debug','drop '.$url['Website']['url'].' because of blacklist');
					$this->Website->id = $url['Website']['id'];
					$this->Website->delete();
					continue;
				}
			}
			
			$doc = new DOMDocument();
			@$doc->loadHTML($html);
			
			echo '<br/>ENC: '.$doc->actualEncoding;
			
			$xpath = new DOMXPath($doc);
			$xpath->registerNamespace( 'xml', 'http://www.w3.org/1999/xhtml' );
			
			//MIME-Typen wie pdf usw. ignorieren (DEBUG - Billiglösung ;) )
			if (strpos($header, 'content-type: text') === false) {
				CakeLog::write('debug','drop '.$url['Website']['url'].' because of Content-Type (no text)');
				echo "<br/>Content-Type ist nicht text/* -> Abbruch";
				
				CakeLog::write('debug',$url['Website']['url']);
				CakeLog::write('debug',$header);
				$this->Website->id = $url['Website']['id'];
				$this->Website->delete();
				continue;
			}
			
			//Nur weiter machen, wenn auch Deutsch irgendwo angegeben ist!
			$ok = $this->checkLanguage($domain_ending, $xpath, $header, $url);
			if (!$ok) {
				continue;
			}
			
			$robots = $this->getRobots($xpath, $url);
			
			//Seiten-Daten
			if ($robots['index']) {
				if ($domainObject == null) {
					//Domain erst erstellen, wenn:
					// -> Sprache passt und
					// -> Seite indiziert wird
					
					//TODO: "www.domain.com" und "domain.com" zusammen legen
					$this->Domain->create();
					$this->Domain->save(array(
							'domain' => $domain,
							'ending' => $domain_ending
					));
				}
				
				//Von UTF-8 abweichende Encodings feststellen
				$given_encoding = $this->getEncoding($xpath, $header);
				$given_encoding = strtoupper(trim($given_encoding));
				if ($given_encoding == 'UTF8') {
					$given_encoding = 'UTF-8';
				}
				$detected_encoding = mb_detect_encoding($html);
				if ($given_encoding != 'UTF-8') {
					//Wenn heruntergeladene Website nicht in UTF-8, dann hier konvertieren!
					//(Sonst wird Sourcecode falsch abgespeichert)
					
					//Das war wohl gedacht, um lästige Fragezeichen zu vermeiden bei der Konvertierung
					//Leider hat das in anderen Fällen Probleme gemacht, also weg damit
					//ini_set('mbstring.substitute_character', " ");
					//$html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
					
					$new_html = iconv($given_encoding, "UTF-8", $html);
					
					if (!$new_html) {
						//Hier konnte etwas nicht konvertiert werden und das is schuld der Website!
						
						//Beispiel heute.at: Server sagt ISO 8859, inhalt aber tatsächlich UTF-8
						if ($detected_encoding != $given_encoding) {
							echo "<br/>Kodierung-Problem: ".$detected_encoding.' - '.$given_encoding;
						}
					} else {
						$html = $new_html;
					}
					
					//erster versuch: "Illegal character encoding specified"
					//mb_convert_encoding($html, 'UTF-8', $encoding);
				}
				
				$title = $xpath->query("//title");
				$title = $title->item(0)->nodeValue;
				
				$hash = $this->calcHTMLHash($doc);
				
				$this->Website->id = $url['Website']['id'];
				
				try {
					$this->Website->save(array(
							'sourcecode' => $html,
							'url' => $url['Website']['url'],
							'domain_id' => $this->Domain->id,
							'date_crawled' => date('Y-m-d H:i:s'),
							'title' => $title,
							'checksum' => $hash,
							'detected_encoding' => $detected_encoding,
							'given_encoding' => $given_encoding,
					));
				} catch(PDOException $e) {
					//Duplicate Entry xxx for URL -> löschen
					//Komisch dass das vorkommt, wo eigentlich vorher schon "already existing" geprüft wird?
					CakeLog::write('debug','drop '.$url['Website']['url'].' because of already existing');
					$this->Website->id = $url['Website']['id'];
					$this->Website->delete();
					continue;
				}
				$url['Website']['domain_id'] = $this->Domain->id;
				
				$result = $this->Website->query('select count(*) from websites where domain_id = '.$this->Domain->id);
				if ($result[0][0]['count(*)'] >= 100) {
					CakeLog::write('debug','Crawled more than 100 URLs of domain '.$domain);
					$this->Website->query("delete from websites where url like '%".$domain."%' and date_crawled is null");
					$robots['follow'] = false;
				}
			}
			
			//Weitere Links raussuchen
			if ($robots['follow']) {
				$links = $xpath->query("//a");
				
				foreach ($links as $node) {
					$attr = $node->attributes;
					
					//rel="nofollow" Attribut berücksichtigen!
					//(Beobachtet auf http://www.meinestadt.de/ )
					$rel = $attr->getNamedItem('rel');
					if ($rel != null) {
						$rel_value = $attr->getNamedItem('href')->nodeValue;
						if ($rel_value == 'nofollow') {
							$rel_nofollow_count++;
							continue;
						}
					}
					
					$href = $attr->getNamedItem('href');
					if ($href != null) {
						$new_url = $attr->getNamedItem('href')->nodeValue;
						$new_domain = parse_url($new_url, PHP_URL_HOST);
						$blacklisted = $this->Domain->find('first',array('conditions'=>array(
								'blacklist' => 1,
								'domain' => $new_domain
						)));
						if ($blacklisted == null) {
							//DEBUG: Heraus finden, ob nicht bereits mehr als 100 Seiten gecrawlt wurden?
							//Lösungsgedanke: Trotzdem neue seite aufnehmen, um Daten aktuell zu halten)
							//echo "<br/>$new_url";
							$new_url = strtolower($new_url);
							if (substr($new_url,0,1) != '#' && substr($new_url,0,11) != 'javascript:' && substr($new_url,0,7) != 'mailto:' && substr($new_url,0,4) != 'tel:') {
								$new_url = $this->rel2abs($new_url, $url['Website']['url']);
								//echo "<br/> :: $new_url";
								$this->Website->create();
								try {
									$this->Website->save(array('url' => $new_url));
									$new_count ++;
								} catch (Exception $e) {
									//Das sollte eigentlich schon von Website->save abgefangen werden, oder?
								}
							}
						}
					}
				}
			}
			
			CakeLog::write('debug','Downloaded content ('.(strlen($html) / 1000)."kb) and found $new_count new urls (robots: index ".$robots['index'].", follow ".$robots['follow'].", rel_nofollow ".$rel_nofollow_count.")");
			echo "<br/>Downloaded content (".(strlen($html) / 1000)."kb) and found $new_count new urls (robots: index ".$robots['index'].", follow ".$robots['follow'].", rel_nofollow ".$rel_nofollow_count.")";
			
			if ($robots['index'] && !$slave) {
				App::import('Controller','Rating');
				$ratingController = new RatingController();
				
				$ratingController->rate( $url['Website']['id'], true, $url );
			}
		}
		
		if ($slave) {
			return $html;
		}
		
		if ($id > 0) {
			//DEBUG - redirect klappt ned? Wegen vorigem echo-output?
			//$this->redirect(array('controller'=>);
			$link = Router::url(array('controller'=>'Website','action'=>'show_domain',$urls[0]['Website']['domain_id']));
			echo '<br/><a href="'.$link.'">Zurück</a>';
		}
		
		$this->_stop();
	}
	
	private function curl_loadUrl($url, $domain, $timeout = 120) {
		$curl = curl_init();
	
		curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => $url,
				CURLOPT_USERAGENT => 'NaWeBe - see http://einfach-denken.at/crawler/pages/faq (still testing, please do not rely on the results)',
				CURLOPT_HTTPHEADER => array(
						'Accept: text/html,application/xhtml+xml,application/xml;q=0.9',
						'Accept-Language: de-DE,de;'),
				CURLOPT_HEADER => 1,
				CURLOPT_FOLLOWLOCATION => true,
				//CURLOPT_PROXY => $proxy,
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_CONNECTTIMEOUT => 0,
				CURLOPT_TIMEOUT => $timeout,
				CURLOPT_COOKIEFILE => 'cookies/'.$domain.'.txt',
				CURLOPT_COOKIEJAR => 'cookies/'.$domain.'.txt'
		));
		
		$result = curl_exec($curl);
			
		$effective_url = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);
		$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			
		$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
		$header = substr($result, 0, $header_size);
		$body = substr($result, $header_size);
		
		curl_close($curl);
		
		return array(
				'header' => $header,
				'content' => $body,
				'effective_url' => $effective_url,
				'http_code' => $http_code
		);
	}
	
	private function rel2abs($rel, $base) {
		//Vereinheitlichung von / am Ende
		if (substr($rel, -1, 1) == '/') {
			$rel = substr($rel, 0, strlen($rel) - 1);
		}
		
		$index = strpos($base, '?');
		if ($index > 0) {
			$base = substr($base, 0, $index);
		}
		if (!@parse_url($rel)) {
			return '';
		}
	    /* return if already absolute URL */
	    if (parse_url($rel, PHP_URL_SCHEME) != '') return $rel;
	
	    /* queries and anchors */
	    if ($rel[0]=='#' || $rel[0]=='?') return $base.$rel;
	
	    /* parse base URL and convert to local variables:
	       $scheme, $host, $path */
	    extract(parse_url($base));
	
	    //Sonderfall, auf Wikipedia bemerkt:
	    //URLs wie //en.wikipedia.org/wiki/Toyota
	    if (substr($rel, 0, 2) == '//') {
	    	return $scheme .':'. $rel;
	    }
	    
	    /* remove non-directory element from path */
	    $path = preg_replace('#/[^/]*$#', '', $path);
	
	    /* destroy path if relative url points to root */
	    if ($rel[0] == '/') $path = '';
	
	    /* dirty absolute URL */
	    $abs = "$host$path/$rel";
	
	    /* replace '//' or '/./' or '/foo/../' with '/' */
	    $re = array('#(/\.?/)#', '#/(?!\.\.)[^/]+/\.\./#');
	    for($n=1; $n>0; $abs=preg_replace($re, '/', $abs, -1, $n)) {}
	
	    /* absolute URL is ready! */
	    return $scheme.'://'.$abs;
	}
	
	private function checkLanguage($domain_ending, $xpath, $header, $url) {
		$ok = false;
		if ($domain_ending == 'de' || $domain_ending == 'at') {
			//Domain-Endung .de oder .at
			$ok = true;
		} else {
			//Meta-Tags / Attribute
			$lang_meta = '';
			$xpaths = array(
					"//meta[translate(@name,'CONTENTLANGUAGE','contentlanguage')='content-language']/@content",
					"//meta[translate(@http-equiv,'CONTENTLANGUAGE','contentlanguage')='content-language']/@content",
					"//meta[translate(@name,'LANGUAGE','language')='language']/@content",
					"/html/@lang",
					"/html/@xml:lang", //DEBUG - glaube das klappt ned :(
					"//meta[translate(@itemprop,'INLANGUAGE','inlanguage')='inLanguage']/@content",
					"//meta[@property='og:locale']/@content"
			);
			//Verrückte Angabe in div, gefunden auf: https://tools.wmflabs.org/geohack/geohack.php
			//<div id="mw-content-text" lang="de" dir="ltr" class="mw-content-ltr">
			// -> Ist laut W3C zulässig, aber wir wollen nur Seiten, die "komplett" in Deutsch sind
			foreach($xpaths as $path) {
				$a = $xpath->query($path);
				if (!empty($a)) {
					//echo "<br/>$path: ".$a->item(0)->nodeValue;
					$lang_meta .= '-'.$a->item(0)->nodeValue;
				}
			}
		
			//HTTP_Header: Content-Language auslesen
			preg_match("/content.language:(.)*/",$header,$matches);
			foreach($matches as $header_content_type) {
				$lang_meta .= '-'.substr($charset, $index + 1);
			}
			
			//Anmerkung: Wenn überhaupt keine Sprache angegeben ist, dann wird sie ebenfalls als "nicht deutsch" angesehen
			//(bei .net oder sonstigen Endungen können wir das ja nicht wissen -> Betreiber Schuld)
			if (strpos($lang_meta, 'de') > 0) {
				$ok = true;
			}
		}
		if (!$ok) {
			CakeLog::write('debug','drop '.$url['Website']['url'].' because of language');
			$this->Website->id = $url['Website']['id'];
			$this->Website->delete();
		}
		return $ok;
	}
	
	private function getEncoding($xpath, $header) {
		//Wir halten uns da dran ;)
		//http://www.w3.org/International/questions/qa-html-encoding-declarations
		
		//HTTP-Header
		$http_encoding = null;
		preg_match("/content.type:(.)*/",$header,$matches);
		foreach($matches as $header_content_type) {
			preg_match("/charset=(.)*(;|$)/",$header_content_type,$matches2);
			foreach($matches2 as $charset) {
				$index = strpos($charset,'=');
				if ($index > 0) {
					$http_encoding = substr($charset, $index + 1);
					break 2;
				}
			}
		}
		echo "<br/>HTTP-Encoding: ".$http_encoding;
		
		//HTML-Meta-Tags
		$xpaths = array(
				"//meta[translate(@http-equiv,'CONTENTTYPE','contenttype')='content-type']/@content",
				"//meta[@charset]/@charset"
		);
		$html_encoding = null;
		foreach($xpaths as $path) {
			$a = $xpath->query($path);
			if (!empty($a) && $html_encoding == null) {
				$charset = $a->item(0)->nodeValue;
				$index = strpos($charset,'=');
				if ($index > 0) {
					$html_encoding = substr($charset, $index + 1);
				} else {
					$html_encoding = $charset;
				}
			}
		}
		
		echo "<br/>HTML-Encoding: ".$html_encoding;
		
		$encoding = $html_encoding;
		if ($encoding == null) {
			$encoding = $http_encoding;
		}
		return $encoding;
	}
	
	private function getRobots($xpath, $url) {
		//Berücksichtige <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
		$robots_follow = true;
		$robots_index = true;
		$robots = $xpath->query("//meta[@name='robots']/@content");
		if (!empty($robots)) {
			$instructions = strtolower('-'.$robots->item(0)->nodeValue.'-');
			if (strpos($instructions, 'noindex') > 0) {
				CakeLog::write('debug','drop '.$url['Website']['url'].' because of robots-noindex');
				$this->Website->id = $url['Website']['id'];
				$this->Website->delete();
				$robots_index = false;
			}
			if (strpos($instructions, 'nofollow') > 0) {
				$robots_follow = false;
			}
		}
		return array(
			'follow' => $robots_follow,
			'index' => $robots_index
		);
	}
	
	public function calcHTMLHash($doc = null, $website = null, $id = null) {
		if ($id > 0) {
			$this->Website->id = $id;
			$website = $this->Website->read();
		}
		if ($doc == 0) {
			if ($website['Website']['sourcecode'] == '') {
				return null;
			}
			$doc = new DOMDocument();
			@$doc->loadHTML( $website['Website']['sourcecode'] );
			if ($doc == null) {
				return null;
			}
		}
		
		//Einfache Checksumme
		//Würde zB erkennen wenn .../index.html und .../index.htm auf denselben Inhalt führen
		//$md5_simple = md5( $website['Website']['sourcecode'] );
		//echo "<br/>simple: ".$md5_simple;
		
		//Text-Checksumme
		//Lässt sich zwar nicht von leicht geänderten Attributen beeinflussen,
		//Dafür von zB Zeit-Angaben auf der Website...
		//$md5_text = md5( $doc->documentElement->nodeValue );
		//echo "<br/>text: ".$md5_text;
		
		//DOM-Checksumme
		//soll die "Struktur" bewerten, also auch wenn andere Attribute im DOM stehen
		//(zB andere Links, weil andere session-ID oder so) wollen wir es erkennen
		$domtree = $this->traverseDOMNode($doc->documentElement);
		$md5_dom = md5( $domtree );
		//echo "<br/>$domtree";
		//echo "<br/>dom: ".$md5_dom;
		
		return $md5_dom;
	}
	
	private function traverseDOMNode(DOMNode $domNode) {
		$return = '';
		foreach ($domNode->childNodes as $node) {
	        $return .= $node->nodeName;
	        if ($node->nodeType == XML_TEXT_NODE) {
	        	//$return .= strlen($node->nodeValue);
	        }
	        if($node->hasChildNodes()) {
	        	$return .= $this->traverseDOMNode($node);
	        }
		}
		return $return;
	}
	
}

//Self-coded version of:
//http://php.net/manual/de/function.http-build-url.php
function http_build_url($parts) {
	$ret = $parts['scheme'].'://'.$parts['host'].$parts['path'].'?'.$parts['query'];
	return $ret;
}
?>