<?php
class Shorthand extends AppModel {
	
	public function getList() {
		$ret = array();
		$result = $this->find('all');
		foreach($result as $key => $short) {
			$ret[ $short['Shorthand']['shorthand'] ] = $short['Shorthand']['meaning'];
		}
		return $ret;
	}
	
}
?>