<?php
class Website extends AppModel {
	public $hasMany = array('WebsiteReadability');
	public $belongsTo = array('Domain');
	
	public function beforeSave() {
		if (!$this->id) { //Only for insert
			if ($this->data[$this->name]['url'] == '') {
				return false;
			}
			
			$index = strpos($this->data[$this->name]['url'], '#');
			if ($index > 0) {
				$this->data[$this->name]['url'] = substr($this->data[$this->name]['url'], 0, $index-1);
			}
			
			if (strlen($this->data[$this->name]['url']) > 256) {
				//URL too complex
				return false;
			}
			
			$existing = $this->find('first', array(
					'conditions' => array(
							'url' => $this->data[$this->name]['url'],
					)
			));
			
			if (count($existing) != 0) {
				return false;
			}
		}
		return true;
	}
	
}
?>