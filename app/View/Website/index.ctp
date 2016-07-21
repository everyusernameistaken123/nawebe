
<h1>Alle Domains</h1>
<p>
<a href="<?php echo $this->Html->Url(array('action'=>'add'));?>" class="btn">
	<span class="fa fa-plus"></span>
	Website eintragen
</a>
</p>

<div class="row-fluid">
	<div class="span4">
	<?php
		echo $this->Form->create('Domain',array('class'=>'form-inline'));
		echo $this->Form->input('domain_ending',array(
				'empty'=>true,
				'class'=>'input-small',
				'options' => $endings,
				'value'=>$selected_ending,
				'onchange'=>'submitEnding();',
				'label'=>'Endung'
		));
		echo $this->Form->end();
	?>
	</div>
	<div class="span4">
	<?php
		$characters = array('0'=>'0-9');
		for($ascii = 65; $ascii < 65 + 26; $ascii++) {
			$characters[ chr($ascii) ] = chr($ascii);
		}
		echo $this->Form->create('Domain',array('class'=>'form-inline'));
		echo $this->Form->input('start_letter',array(
				'empty'=>true,
				'class'=>'input-small',
				'options' => $characters,
				'value'=>$selected_start,
				'onchange'=>'submitStart();',
				'label'=>'Anfangs-Buchstabe'
		));
		echo $this->Form->end();
	?>
	</div>
	<div class="span4">
	<?php
		echo $this->Form->create('Domain',array('class'=>'form-inline', 'onsubmit'=>'return submitSearch();',));
		echo $this->Form->input('search',array(
				'empty'=>true,
				'class'=>'input-small',
				'value'=>$searchstring,
				'label'=>'Suchbegriff'
		));
		echo $this->Form->end();
	?>
	</div>
</div>

<div class="row-fluid">
<div class="span10 offset1">
	
	<?php echo $this->Paginator->numbers(array('first' => 2, 'last' => 2)); ?>
	
	<table>
	<thead>
		<tr>
			<th><?php echo $this->Paginator->sort('domain','Titel'); ?></th>
			<th><?php echo $this->Paginator->sort('sites','Seiten gecrawlt'); ?></th>
			<th><?php echo $this->Paginator->sort('modified','Datum / Letztes Update'); ?></th>
			<th><?php echo $this->Paginator->sort('avg_dictionary','Avg. Wörterbuch'); ?></th>
			<th><?php echo $this->Paginator->sort('avg_fillers','Avg. Füllwörter'); ?></th>
		</tr>
	</thead>
	<tbody>
	<?php
	$total_sites = 0;
	foreach($domains as $domain) {
		//$total_sites += $domain['Domain']['sites'];
		echo '<tr>';
		echo '<td><a href="'.$this->Html->Url(array('action'=>'show_domain',$domain['Domain']['id'])).'">';
		echo 	$domain['Domain']['domain'].'</a></td>';
		echo '<td>'.$domain['Domain']['sites'].'</td>';
		echo '<td>'.$domain['Domain']['modified'].'</td>';
		echo '<td>'.$domain['Domain']['avg_dictionary'].'%</td>';
		echo '<td>'.$domain['Domain']['avg_fillers'].'%</td>';
		echo '</tr>';
	}
	//echo '<tr><td>Gesamt:</td><td>'.$total_sites.'</td><td colspan="3"></td></tr>';
	?>
	</tbody>
	</table>
	
	<?php echo $this->Paginator->numbers(array('first' => 2, 'last' => 2)); ?>
	
</div>
</div>

<script>
function submitEnding() {
	value = $('#DomainDomainEnding').val();
	window.location = "?ending="+value;
}
function submitStart() {
	value = $('#DomainStartLetter').val();
	window.location = "?start="+value;
}
function submitSearch() {
	value = $('#DomainSearch').val();
	window.location = "?search="+value;
	return false;
}
</script>
