<?php
class KnownWord extends AppModel {
	public function beforeSave() {
		if ($this->data[$this->name]['word'] == '') {
			return false;
		}
		
		$existing = $this->find('first', array(
				'conditions' => array(
						'word' => $this->data[$this->name]['word'],
				)
		));
		
		if (count($existing) != 0) {
			return false;
		}
		
	}
}
?>