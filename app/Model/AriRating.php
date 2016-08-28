<?php

class AriRating extends Rating {
	protected $url = 'https://en.wikipedia.org/wiki/Automated_readability_index';
	protected $short_name = 'ARI';
	protected $full_name = 'Automated Readability Index';
	protected $scale_min = -2;
	protected $scale_max = 34;
	
	protected $need_params = array('characters', 'words', 'sentences');
	
	public function getRatingNum() {
		parent::getRatingNum();
		
		if ($this->parameters['sentences'] == 0 || $this->parameters['words'] == 0) {
			return 0;
		}
		
		$value = 4.71 * ($this->parameters['characters'] / $this->parameters['words']) + 0.5 * ($this->parameters['words'] / $this->parameters['sentences']) - 21.43;
		return $this->applyMinMax($value);
	}
	
	public function getRatingText() {
		return number_format(6 + $this->getRatingNum(), 1).' years';
	}
}
?>