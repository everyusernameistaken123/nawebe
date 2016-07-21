<?php

class CliRating extends Rating {
	protected $url = 'https://en.wikipedia.org/wiki/Coleman–Liau_index';
	protected $short_name = 'CLI';
	protected $full_name = 'Coleman–Liau Index';
	protected $scale_min = 1; //TODO - besondere Vorsicht, im Extremfall kann der Index negativ werden!
	protected $scale_max = 20;
	
	protected $need_params = array('characters', 'words', 'sentences');
	
	public function getRatingNum() {
		parent::getRatingNum();
		
		//Test from wikipedia-article
		/*
		$this->parameters = array(
				'characters' => 639,
				'words' => 119,
				'sentences' => 5
		);
		*/
		
		return 0.0588 * ($this->parameters['characters'] * 100 / $this->parameters['words']) - 0.296 * ($this->parameters['sentences'] * 100 / $this->parameters['words']) - 15.8;
	}
	
	public function getRatingText() {
		return number_format(6 + $this->getRatingNum(), 1).' years';
	}
}
?>