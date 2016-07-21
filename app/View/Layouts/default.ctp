
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $this->fetch('title'); ?>
	</title>
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('frontend');
		echo $this->Html->css('bootstrap.min.css');
		echo $this->Html->css('font-awesome.min.css');
		
		echo $this->Html->script('jquery-1.11.3-min');
		echo $this->Html->script('bootstrap.min.js');
		echo $this->Html->script('custom.js');
		

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
</head>
<body>
	<div id="container">
		<div id="header">
			<div class="row-fluid">
				<div class="span4 title">
				<a href="<?php echo $this->Html->Url(array('controller'=>'Website','action'=>'index')); ?>">
					<h2>NaWeBe</h2>
					<p>Namenlose Website Bewertung</p>
				</a>
				</div>
				<div class="span6">
				<ul class="nav">
					<li><a class="btn" href="<?php echo $this->Html->Url(array('controller'=>'Website','action'=>'index')); ?>">Startseite</a></li>
					<li><a class="btn" href="<?php echo $this->Html->Url(array('controller'=>'pages','action'=>'display','faq')); ?>">Infos / FAQ</a></li>
					<li><a class="btn" href="<?php echo $this->Html->Url(array('controller'=>'statistics','action'=>'overview')); ?>">Statistiken</a></li>
				</ul>
				</div>
			</div>
			
		</div>
		<div id="content">

			<?php echo $this->Flash->render(); ?>

			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer">
			<span class="fa fa-copyright"></span>&nbsp; 2015 - 2016 | 
			<a href="<?php echo $this->Html->Url(array('controller'=>'pages','action'=>'display','imprint')); ?>">Impressum / Kontakt</a>
		</div>
	</div>
	<?php //echo $this->element('sql_dump'); ?>
</body>
</html>
