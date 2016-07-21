<?php

class LixRating extends Rating {
	protected $url = 'http://www.ideosity.com/ourblog/post/ideosphere-blog/2010/01/14/readability-tests-and-formulas#LIX';
	protected $short_name = 'LIX';
	protected $full_name = 'Laesbarhedsindex';
	protected $scale_min = 0;
	protected $scale_max = 100;
	
	protected $need_params = array('long_words', 'words', 'sentences');
	
	public function getRatingNum() {
		parent::getRatingNum();
		
		if ($this->parameters['sentences'] == 0 || $this->parameters['words'] == 0) {
			return 0;
		}
		
		return ( $this->parameters['words'] / $this->parameters['sentences'] ) + 100 * ( $this->parameters['long_words'] / $this->parameters['words'] );
	}
	
	public function getRatingText() {
		$num = $this->getRatingNum();
		if ($num <= 24) {
			return "very easy";
		} else if ($num <=34) {
			return "easy";
		} else if ($num <=44) {
			return "standard";
		} else if ($num <=54) {
			return "difficult";
		} else {
			return "very difficult";
		}
	}
}
?>