
<h1>Statistiken</h1>

<?php echo $this->element('statistics_menu'); ?>

<div class="row-fluid">
<div class="span10 offset1">
	
	<h3>Übermittelte Kodierungs-Information in HTTP- und HTML-Headern</h3>
	
	<table>
	<thead>
		<tr>
			<th>Encoding</th>
			<th>Anzahl</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach($enc_data as $encoding) {
		echo '<tr>';
		echo '<td>'.$encoding['Website']['given_encoding'].'</td>';
		echo '<td>'.$encoding[0]['c'].'</td>';
		echo '</tr>';
	}?>
	</tbody>
	</table>
</div>
</div>
