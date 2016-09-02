<?php
class BackendController extends Controller {
	public $uses = array('Website', 'KnownWord', 'UnknownWord', 'Domain');
	
	public function index() {
		
	}
	
	public function duplicates($page = 0) {
		$limit = ($page*100).',100';
		$duplicate_ids = $this->Website->query("SELECT w1.id, w2.id
				FROM `websites` w1 join websites w2 on w1.checksum = w2.checksum
				WHERE
					w1.words = w2.words AND
					w1.id != w2.id AND
   					w1.checksum is not null
				LIMIT $limit");
		
		$done = array();
		$duplicates = array();
		
		$this->Website->recursive = -1;
		foreach($duplicate_ids as $data) {
			if (!$done[ $data['w2']['id'] ]) {
				$done[ $data['w1']['id'] ] = true;
				$w1 = $this->Website->findById($data['w1']['id']);
				$w2 = $this->Website->findById($data['w2']['id']);
				
				$duplicates[] = array(
						'w1' => $w1,
						'w2' => $w2,
				);
			}
		}
		$this->set('duplicates',$duplicates);
		
		$this->set('page',$page);
	}
	
	public function deleteWebsite($id = null) {
		//Gehört eigentlich in WebsiteController, aber wir haben keine Zugriffsrechte und bevor da jemand lustig wird...
		$this->Website->id = $id;
		$this->Website->delete();
		echo "OK";
		$this->_stop();
	}
	
	public function manageUnknown($page = 0) {
		$unknown = $this->UnknownWord->find('all',array(
				'conditions' => array('checked'=>0),
				'limit' => 100,
				'offset' => $page*100,
				'order' => 'id asc'
		));
		
		$this->set('unknown',$unknown);
	}
	
	//für ajax
	public function processUnknown($id = null, $confirm = false) {
		$this->UnknownWord->id = $id;
		$unknown = $this->UnknownWord->read();
		if ($unknown == null) {
			echo 'not found';
			$this->_stop();
		} else {
			if ($confirm) {
				$this->KnownWord->create();
				$this->KnownWord->save(array(
						'word' => $unknown['UnknownWord']['word']
				));
				$this->UnknownWord->delete();
			} else {
				$this->UnknownWord->saveField('checked', 1);
			}
		}
		echo 'OK';
		$this->_stop();
	}
	
	//Domain-site-count updaten
	//2016-06-19: Gibt doch schon einige Domains, wo site-count nicht passt
	//Update ist ein Cronjob, kann nicht "schnell" als SQL-Query gemacht werden
	public function updateDomainSiteCount($start_id = 1) {
		
		$domains = $this->Domain->find('all',array('conditions'=>array('blacklist'=>0,'id >= '.$start_id), 'limit'=>20));
		
		foreach($domains as $domain) {
			$id = $domain['Domain']['id'];
			
			//old, just site-count
			//$count = $this->Website->find('count',array('conditions'=>array('domain_id' => $id)));
			//new, site-count with avg
			$avg = $this->Website->find('all',array(
					'fields' => array(
							'avg(rating_dictionary) as avg_dict',
							'avg(rating_fillers) as avg_fill',
							'count(*) as c'
					),
					'conditions' => array('domain_id' => $id)
			));
			
			if ($avg[0][0]['c'] != $domain['Domain']['sites']) {
				$this->Domain->id = $id;
				$this->Domain->save(array(
						'avg_dictionary' => $avg[0][0]['avg_dict'],
						'avg_fillers' => $avg[0][0]['avg_fill'],
						'sites' => $avg[0][0]['c']
				));
				echo '<br/>'.$id.' / '.$domain['Domain']['domain'].' sites: '.$domain['Domain']['sites'].' => '.$avg[0][0]['c'];
			}
		}
		echo '<br/><a href="'.$id.'">continue</a>';
		
		$this->_stop();
	}
}
?>