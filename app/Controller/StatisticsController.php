<?php
class StatisticsController extends Controller {
	public $uses = array('Website', 'Domain', 'KnownWord', 'UnknownWord');
	
	public function index() {
		$this->redirect('overview');
	}
	
	public function overview() {
		$domain_count = $this->Domain->find('count',array(
				'conditions'=>array('blacklist'=>0, 'sites > 0')
		));
		
		$website_count = $this->Website->find('count',array(
				'conditions'=>array('date_crawled is not null')
		));
		
		$words_count = $this->KnownWord->find('count');
		
		$this->set('domain_count',$domain_count);
		$this->set('website_count',$website_count);
		$this->set('words_count',$words_count);
	}
	
	public function domains($show = 'topdict') {
		switch($show) {
			case 'topdict':
				$order = array('avg_dictionary' => 'desc');
				$headline = 'Top 10 Wörterbuch-Ranking';
				$class_dict = 'alert';
				break;
			case 'topfiller':
				$order = array('avg_fillers' => 'desc');
				$headline = 'Top 10 Füllwörter-Ranking';
				$class_filler = 'alert';
				break;
			case 'lowdict':
				$order = array('avg_dictionary' => 'asc');
				$headline = 'Flop 10 Wörterbuch-Ranking';
				$class_dict = 'alert';
				break;
			case 'lowfiller':
				$order = array('avg_fillers' => 'asc');
				$headline = 'Flop 10 Füllwörter-Ranking';
				$class_filler = 'alert';
				break;
			
			default:
				$headline = 'Nicht erkannte Eingabe';
		}
		
		$conditions = array('blacklist' => 0, 'sites > 0');
		
		$this->paginate = array('Domain' => array(
				'limit' => 50,
				'order' => $order,
				'conditions' => $conditions
		));
		$domains = $this->paginate('Domain');
		
		$this->set('domains', $domains);
		$this->set('headline', $headline);
		$this->set('class_dict', $class_dict);
		$this->set('class_filler', $class_filler);
	}
	
	public function websites($show = 'easiest', $index = 'ARI') {
		if (!in_array($index, array('ARI','CLI','LIX'))) {
			$index = 'ARI';
		}
		switch($show) {
			
			case 'easiest':
				$order = array('WebsiteReadability.result_num' => 'asc');
				$headline = 'Die 10 am leichtesten zu lesende Seiten';
				break;
			case 'hardest':
				$order = array('WebsiteReadability.result_num' => 'desc');
				$headline = 'Die 10 am schwersten zu lesende Seiten';
				break;
			default:
				$headline = 'Nicht erkannte Eingabe';
		}
	
		$conditions = array('date_crawled is not null', 'WebsiteReadability.readability_code'=>$index);
	
		$websites = $this->Website->find('all',array(
				'fields'=>array('Website.*', 'WebsiteReadability.*'),
				'limit' => 50,
				'conditions' => $conditions,
				'order'=>$order,
				'joins' => array(
			        array(
			            'table' => 'website_readabilities',
			            'alias' => 'WebsiteReadability',
			            'type' => 'LEFT',
			            'conditions' => array(
			                'WebsiteReadability.website_id = Website.id'
			            )
			        )
			    ),
		));
		
		$this->set('sites', $websites);
		$this->set('headline', $headline);
		$this->set('class_read', 'alert');
		$this->set('selected_index', $index);
	}
	
	public function encoding() {
		$this->Website->unbindModel(array('hasMany'=>array('WebsiteReadability')));
		
		$enc_data = $this->Website->find('all',array(
				'fields'=>array('count(*) as c','given_encoding'),
				'conditions'=>array('date_crawled is not null'),
				'group'=>array('given_encoding'),
				'order'=>'c desc'
		));
		
		$this->set('enc_data',$enc_data);
	}
}
?>