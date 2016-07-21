<?php

//TODO: Das können wir uns sparen...
//EInfach: http://corpora2.informatik.uni-leipzig.de/download.html
//Mit Copyright-Verweis: © Copyright  Abteilung Automatische Sprachverarbeitung, Universität Leipzig.
//Zusätzlich: http://asvdoku.informatik.uni-leipzig.de/corpora/index.php?id=corpus-database
// -> Levenshtein-Distance? Irgendwann mal ;)

//wortschatz.uni-leipzig.de - Anbindung

class WortschatzController extends Controller {
	public $uses = array('KnownWord', 'UnknownWord');
	
	//http://wortschatz.uni-leipzig.de/axis/servlet/ServiceOverviewServlet

	public function lookup($unknown_id = null, $search_string = null) {
		if ($unknown_id > 0) {
			$this->UnknownWord->id = $unknown_id;
			$unknown = $this->UnknownWord->read();
			if ($unknown == null) {
				throw new NotFoundException('Datenbank-Eintrag '.$unknown_id.' nicht gefunden');
			}
			$search_string = $unknown['UnknownWord']['word'];
		} else if ($search_string != '') {
			$unknown = $this->UnknownWord->findByWord($search_string);
		}
		
		$client = new SoapClient("Frequencies.wsdl",
						array('login' => "anonymous",
                       		'password' => "anonymous"
						
		));
		
		//http://web.archive.org/web/20090418233940/http://blog.klassifikator.de/2009/03/php-implementierung-des-wortschatz-webservice-der-uni-leipzig/
		$wort= array(
				'dataRow' => array('Wort',$search_string)
		);
		$dataVectors=array($wort);
		$parameters=array(
				'dataVectors' => $dataVectors
		);
		$request = array(
				'objRequestParameters' => array(
						'corpus' => 'de',
						'parameters' => $parameters
				)
		);
		debug($request);
		$response = $client->execute($request);
		debug($response);
		if (is_soap_fault($result)) {
			CakeLog::write('debug','wortschatz-Error: '.$result->faultstring);
			return false;
		}
		
		$return = $response->executeReturn->result->dataVectors->dataRow;
		debug($return);
		if ($return != null) {
			//TODO - Warum sind die Resultate vom Web-Interface der Uni abweichend?
			$frequency = $return[0];
			echo "Frequenz: ".$frequency;
			if ($frequency > 10) {
				CakeLog::write('debug','wortschatz-Lookup '.$search_string.', Frenquenz: '.$frequency.' -> existiert');
				//echo '<br/>'.$search_string." existiert :) ";
				$this->KnownWord->create();
				$this->KnownWord->save(array('word' => $search_string));
				if ($unknown != null) {
					$this->UnknownWord->id = $unknown['UnknownWord']['id'];
					$this->UnknownWord->delete();
				}
				return true;
			} else {
				CakeLog::write('debug','wortschatz-Lookup '.$search_string.', Frenquenz: '.$frequency.' -> zu wenig');
				if ($unknown != null) {
					$this->UnknownWord->id = $unknown['UnknownWord']['id'];
					$this->UnknownWord->saveField('checked', 1);
				}
			}
		} else {
			CakeLog::write('debug','wortschatz-Lookup '.$search_string.', keine Antwort?');
			if ($unknown != null) {
				$this->UnknownWord->id = $unknown['UnknownWord']['id'];
				$this->UnknownWord->saveField('checked', 42); //TODO
			}
		}
		return false;
	}
	
	public function cronjob($limit = 10) {
		$result = $this->UnknownWord->find('all',array(
				'conditions' => array('checked'=>0),
				'limit' => $limit
		));
		foreach($result as $entry) {
			$this->lookup($entry['UnknownWord']['id']);
			sleep(2);
		}
		$this->_stop();
	}
}
?>