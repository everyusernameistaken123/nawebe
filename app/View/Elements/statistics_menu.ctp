<div class="row-fluid">
	<div class="span3">
		Allgemein<br/>
		<a href="<?php echo $this->Html->Url(array('action'=>'index')); ?>" class="btn btn-mini">
			Überblick
		</a>
		<a href="<?php echo $this->Html->Url(array('action'=>'encoding')); ?>" class="btn btn-mini">
			Kodierung
		</a>
	</div>
	<div class="span4">
		Domains<br/>
		<a href="<?php echo $this->Html->Url(array('action'=>'domains','topdict')); ?>" class="btn btn-mini">
			Top 50 Wörterbuch
		</a>
		<a href="<?php echo $this->Html->Url(array('action'=>'domains','topfiller')); ?>" class="btn btn-mini">
			Top 50 Füllwörter
		</a>
		<br/>
		<a href="<?php echo $this->Html->Url(array('action'=>'domains','lowdict')); ?>" class="btn btn-mini">
			Niedrigste 50 Wörterbuch
		</a>
	
		<a href="<?php echo $this->Html->Url(array('action'=>'domains','lowfiller')); ?>" class="btn btn-mini">
			Niedrigste 50 Füllwörter
		</a>
	</div>
	<div class="span4">
		Lesbarkeit<br/>
		<a href="<?php echo $this->Html->Url(array('action'=>'websites','easiest','ARI')); ?>" class="btn btn-mini">
			ARI Top 50 leicht
		</a>
		<a href="<?php echo $this->Html->Url(array('action'=>'websites','hardest','ARI')); ?>" class="btn btn-mini">
			ARI Top 50 schwer
		</a>
		<br/>
		<a href="<?php echo $this->Html->Url(array('action'=>'websites','easiest','LIX')); ?>" class="btn btn-mini">
			LIX Top 50 leicht
		</a>
		<a href="<?php echo $this->Html->Url(array('action'=>'websites','hardest','LIX')); ?>" class="btn btn-mini">
			LIX Top 50 schwer
		</a>
	</div>
</div>