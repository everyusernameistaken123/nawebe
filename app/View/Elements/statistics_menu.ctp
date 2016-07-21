<div class="row-fluid">
	<div class="span2">
		Allgemein<br/>
		<a href="<?php echo $this->Html->Url(array('action'=>'index')); ?>" class="btn btn-mini">
			Überblick
		</a>
	</div>
	<div class="span6">
		Domains<br/>
		<a href="<?php echo $this->Html->Url(array('action'=>'domains','topdict')); ?>" class="btn btn-mini">
			Top 10 Wörterbuch
		</a>
		<a href="<?php echo $this->Html->Url(array('action'=>'domains','topfiller')); ?>" class="btn btn-mini">
			Top 10 Füllwörter
		</a>
		<a href="<?php echo $this->Html->Url(array('action'=>'domains','lowdict')); ?>" class="btn btn-mini">
			Niedrigste 10 Wörterbuch
		</a>
	
		<a href="<?php echo $this->Html->Url(array('action'=>'domains','lowfiller')); ?>" class="btn btn-mini">
			Niedrigste 10 Füllwörter
		</a>
	</div>
	<div class="span4">
		Lesbarkeit<br/>
		<a href="<?php echo $this->Html->Url(array('action'=>'websites','easiest','ARI')); ?>" class="btn btn-mini">
			ARI Top 10 leicht
		</a>
		<a href="<?php echo $this->Html->Url(array('action'=>'websites','hardest','ARI')); ?>" class="btn btn-mini">
			ARI Top 10 schwer
		</a>
		<br/>
		<a href="<?php echo $this->Html->Url(array('action'=>'websites','easiest','LIX')); ?>" class="btn btn-mini">
			LIX Top 10 leicht
		</a>
		<a href="<?php echo $this->Html->Url(array('action'=>'websites','hardest','LIX')); ?>" class="btn btn-mini">
			LIX Top 10 schwer
		</a>
	</div>
</div>