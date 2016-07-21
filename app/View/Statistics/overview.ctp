
<h1>Statistiken</h1>

<?php echo $this->element('statistics_menu'); ?>

<div class="row-fluid">
<div class="span10 offset1">
	
	<h3>Übersicht</h3>
	<p>In der Datenbank befinden sich:</p>
	<p>
		<b><?php echo $website_count; ?></b> gecrawlte Websites
	</p>
	<p>
		von <b><?php echo $domain_count; ?></b> Domains.
	</p>
	
	<p>
		Das Wörterbuch umfasst <b><?php echo $words_count; ?></b> Einträge.
	</p>
</div>
</div>
