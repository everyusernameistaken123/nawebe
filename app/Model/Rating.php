<?php
class Rating extends AppModel {
	public $useTable = false;
	
	protected $url = 'http://www.ideosity.com/ourblog/post/ideosphere-blog/2010/01/14/readability-tests-and-formulas';
	protected $short_name = 'none';
	protected $full_name = 'none';
	protected $scale_min = 0;
	protected $scale_max = 100;
	
	protected $parameters = array();
	protected $need_params = array();
	
	public function setParameter($name, $value) {
		$this->parameters[ $name ] = $value;
	}
	public function getParameters() {
		return $this->parameters;
	}
	
	public function getRatingNum() {
		foreach($this->need_params as $name) {
			if ($this->parameters[ $name ] === null) {
				throw new InvalidArgumentException('Need parameter "'.$name.'"');
			}
		}
		return 0;
	}
	public function getRatingText() {
		return 'general';
	}
	
	public function getShortName() {
		return $this->short_name;
	}
	public function getFullName() {
		return $this->full_name;
	}
	public function getScaleMin() {
		return $this->scale_min;
	}
	public function getScaleMax() {
		return $this->scale_max;
	}
}
?>