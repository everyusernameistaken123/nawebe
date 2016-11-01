<?php
class WebsiteController extends Controller {
	public $uses = array('Website', 'Domain', 'Filler');
	
	public function index() {
		$result = $this->Domain->find('list',array(
				'fields'=>array('ending'),
				'conditions'=>array('blacklist'=>0, 'sites > 0', 'ending not in ("at","de","ch")'),
				'group' => 'ending',
				'order' => 'ending'
		));
		$endings = array('at'=>'at','ch'=>'ch','de'=>'de','---'=>'---');
		foreach($result as $ending) {
			$endings[ $ending ] = $ending;
		}
		
		$ending = $this->request->query('ending');
		$start = $this->request->query('start');
		$search = $this->request->query('search');
		$this->set('selected_ending', $ending);
		$this->set('selected_start', $start);
		$this->set('searchstring', $search);
		$conditions = array('blacklist' => 0, 'sites > 0');
		
		if ($ending != '') {
			if ($this->Session->read('current_ending') != $ending) {
				$this->params->params['named']['page'] = 1; //Reset to start with page 1
			}
			$conditions += array('ending' => $ending);
		}
		if ($start != '') {
			if ($this->Session->read('current_start') != $start) {
				$this->params->params['named']['page'] = 1; //Reset to start with page 1
			}
			
			if ($start == '0') {
				$conditions = array_merge($conditions,array("(domain REGEXP '^[0-9]' or domain REGEXP '^www\.[0-9]')"));
			} else {
				$conditions = array_merge($conditions,array("(domain like '".$start."%' or domain like 'www.".$start."%')"));
			}
		}
		if ($search != '') {
			if ($this->Session->read('current_search') != $search) {
				$this->params->params['named']['page'] = 1; //Reset to start with page 1
			}
			
			if (strlen($search) >= 3) {
				$conditions = array_merge($conditions,array("(domain like '%".$search."%')"));
			} else {
				//TODO: Fehlermeldung wenn zu wenig Zeichen als Suchbegriff
			}
		}
		$this->Session->write('current_ending', $ending);
		$this->Session->write('current_start', $start);
		$this->Session->write('current_search', $search);
		
		if ($ending == '' && $start == '' && strlen($search) < 3) {
			$this->redirect(array('?'=>array('ending'=>'at')));
		}
		
		$this->paginate = array('Domain' => array(
				'limit' => 100,
				'conditions' => $conditions,
				'order'=>'domain asc'
		));
		$domains = $this->paginate('Domain');
		
		$this->set('domains',$domains);
		$this->set('endings',$endings);
	}
	
	public function show_domain($domain_id = null) {
		$domain = $this->Domain->findById($domain_id);
		if ($domain == null) {
			throw new NotFoundException('Domain ID '.$domain_id.' not found');
		}
		
		$this->Website->unbindModel(array('belongsTo'=>array('Domain')));
		
		//DEBUG: Sortieren nach Readability-Index wär cool, aber mit cake nicht so leicht :( 
		$this->paginate = array('Website' => array(
				'maxLimit' => 250,
				'limit' => 250, //Default is 20, we need ~100
				'conditions' => array(
						'domain_id' => $domain_id,
						'date_crawled is not null'
				)
		));
		$sites = $this->paginate('Website');
		
		foreach($sites as $key => $site) {
			$site['Website']['rating_fillers'] = number_format($site['Website']['rating_fillers'], 2);
			$site['Website']['rating_dictionary'] = number_format($site['Website']['rating_dictionary'], 2);
			
			$date = new DateTime($site['Website']['date_crawled']);
			$site['Website']['date_crawled'] = $date->format('d.m.Y');
			
			$sites[$key] = $site;
		}
		
		//DEBUG - CLI ist irgendwie "viel zu hoch", das macht keinen Sinn den anzuzeigen
		$this->set('readability_indizes',array('ARI','LIX'));
		$this->set('sites', $sites);
		
		$this->set('domain',$domain);
		
		$download = $this->request->query('download');
		if (strtolower($download) == 'csv') {
			$this->layout = false;
			$this->view = 'show_domain_csv';
			$this->response->type('csv');
			$this->response->download('nawebe-'.date('Y-m-d-H-i-s').'-'.$domain['Domain']['domain'].'.csv');
		}
	}
	
	public function add() {
		if ($this->request->is('post')) {
			$url = trim($this->request->data['Website']['url']);
			$pattern = "/^[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,6}/";
			if (!preg_match($pattern, $url)) {
				$this->set('feedback','Fehler, URL nicht in gültigem Domain-Format');
			} else {
				if (substr($url,0,7) != 'http://' || substr($url,0,8) != 'https://') {
					$url = 'http://'.$url;
				}
				
				//Möglichst niedrige freie ID finden (damit crawler sofort startet)
				//Thanks http://stackoverflow.com/questions/5016907/mysql-find-smallest-unique-id-available
				$result = $this->Website->query("SELECT MIN(t1.ID + 1) AS nextID
					FROM websites t1
					LEFT JOIN websites t2
					ON t1.ID + 1 = t2.ID
					WHERE t2.ID IS NULL");
				$this->request->data['Website']['id'] = $result[0][0]['nextID'];
				
				Cakelog::write('debug','New Website entered by user: '.$this->request->data['Website']['url']);
				
				$this->Website->create();
				try {
					$this->Website->save( $this->request->data );
					
					$this->set('feedback','Erfolgreich');
				} catch (Exception $e) {
					$this->set('feedback','Fehler, möglicherweise ist die URL bereits bekannt.');
				}
			}
		}
	}
	
	public function listFiller() {
		$fillers = $this->Filler->find('all',array('order' => array('type', 'phrase')));
		$this->set('fillers', $fillers);
	}
}
?>