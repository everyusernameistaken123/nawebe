
<h1>Statistiken</h1>

<?php echo $this->element('statistics_menu'); ?>

<div class="row-fluid">
<div class="span10 offset1">
	
	<h3><?php echo $headline; ?></h3>
	
	<table>
	<thead>
		<tr>
			<th>Titel</th>
			<th>Seiten gecrawlt</th>
			<th>Datum / Letztes Update</th>
			<th>Avg. Wörterbuch</th>
			<th>Avg. Füllwörter</th>
		</tr>
	</thead>
	<tbody>
	<?php
	foreach($domains as $domain) {
		echo '<tr>';
		echo '<td><a href="'.$this->Html->Url(array('controller'=>'website','action'=>'show_domain',$domain['Domain']['id'])).'">';
		echo 	$domain['Domain']['domain'].'</a></td>';
		echo '<td>'.$domain['Domain']['sites'].'</td>';
		echo '<td>'.$domain['Domain']['modified'].'</td>';
		echo '<td class="'.$class_dict.'">'.$domain['Domain']['avg_dictionary'].'%</td>';
		echo '<td class="'.$class_filler.'">'.$domain['Domain']['avg_fillers'].'%</td>';
		echo '</tr>';
	}
	?>
	</tbody>
	</table>
</div>
</div>
