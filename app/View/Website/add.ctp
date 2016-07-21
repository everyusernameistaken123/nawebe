
<h1>Website eintragen</h1>
<p>
<a href="<?php echo $this->Html->Url(array('action'=>'index'));?>" class="btn">
	<span class="fa fa-chevron-left"></span>
	Zurück
</a>
</p>

<p>Bitte die Startseite (Homepage) angeben. Unterseiten werden automatisch erkannt.</p>
<p>
<a href="<?php echo $this->Html->Url(array('controller'=>'pages','action'=>'faq','#'=>'howto')); ?>">
	Mehr Informationen
</a>
</p>

<div class="row-fluid">
	<div class="span8 offset2">
	<?php
		echo $this->Form->create('Website',array('class'=>'form-horizontal'));
		echo $this->Form->input('url',array('class'=>'input-large'));
		echo $this->Form->input('Eintragen',array('type'=>'submit','label'=>false));
		echo $this->Form->end();
	?>
	</div>
</div>

<?php if (isset($feedback)) {
	echo '<p class="alert">'.$feedback.'</p>';
}?>