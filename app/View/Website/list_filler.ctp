
<h1>Füllwörter</h1>

<div class="row-fluid">
<div class="span8 offset2">
	<p>
	Die folgenden Phrasen bzw. Wörter werden bei der Bewertung als Qualitäts-Minderung gewertet.
	</p>
	<table>
	<thead>
		<tr>
			<th>Eintrag</th>
			<th>Typ</th>
		</tr>
	</thead>
	<tbody>
	<?php
	foreach($fillers as $filler) {
		echo '<tr>';
		echo '<td>'.$filler['Filler']['phrase'].'</td>';
		echo '<td>'.$filler['Filler']['type'].'</td>';
		echo '</tr>';
	}
	?>
	</tbody>
	</table>
</div>
</div>
