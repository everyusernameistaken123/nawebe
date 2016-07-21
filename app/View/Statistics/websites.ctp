
<h1>Statistiken</h1>

<?php echo $this->element('statistics_menu'); ?>

<div class="row-fluid">
<div class="span10 offset1">
	
	<h3><?php echo $headline; ?></h3>
	
	<table>
	<thead>
		<tr>
			<th>Titel</th>
			<th>Datum</th>
			<th>Wörter</th>
			<th>Wörterbuch</th>
			<th>Füllwörter</th>
			<th>
				Lesbarkeit <?php echo $selected_index; ?>
			</th>
			<th>
			
			</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach($sites as $site) {
		echo '<tr>';
		echo '<td><a href="'.$site['Website']['url'].'" target="_blank">';
		echo 	$site['Website']['title'];
		echo 	'&nbsp;<span class="fa fa-external-link"></span>';
		echo '</a><br/>';
		echo '<span style="font-size:80%;color:#888;">'.$site['Website']['url'].'</span>';
		echo '</td>';
		echo '<td>'.$site['Website']['date_crawled'].'</td>';
		
		if ($site['Website']['date_rated'] != null) {
			echo '<td>'.$site['Website']['words'].'</td>';
			echo '<td>'.$site['Website']['rating_dictionary'].'%</td>';
			echo '<td title="'.$site['Website']['fillers'].'">'.$site['Website']['rating_fillers'].'%</td>';
		} else {
			echo '<td colspan="3">noch nicht ermittelt<br/>';
			//echo '<a href="'.$this->Html->Url(array('controller'=>'Rating','action'=>'rate',$site['Website']['id'])).'" class="btn">Jetzt berechnen!</a>';
			echo '</td>';
		}
		echo '<td class="'.$class_read.'">'.$site['WebsiteReadability']['result_txt'].'<br/>';
		echo '('.$site['WebsiteReadability']['result_num'].')</td>';
		echo '<td><a class="btn" href="'.$this->Html->Url(array('controller'=>'Website','action'=>'show_domain',$site['Website']['domain_id'])).'">';
		echo '<span class="fa fa-search" title="Zeige Domain"></span></a></td>';
		echo '</tr>';
	}?>
	</tbody>
	</table>
</div>
</div>
