
<h1>
Alle Seiten der Domain<br/>
<?php echo $domain['Domain']['domain']; ?>
</h1>
<p>
<a href="<?php echo $this->Html->Url(array('action'=>'index'));?>" class="btn">
	<span class="fa fa-chevron-left"></span>
	Zurück
</a>
<a href="<?php echo $this->Html->Url(array('action'=>'show_domain',$domain['Domain']['id'],'?'=>array('download'=>'csv')));?>" class="btn" title="Tabelle als CSV herunter laden">
	<span class="fa fa-download"></span>
	Download CSV
</a>
</p>

<div class="row-fluid">
<div class="span10 offset1">
	<table>
	<thead>
		<tr>
			<th><?php echo $this->Paginator->sort('title','Titel'); ?></th>
			<th><?php echo $this->Paginator->sort('date_crawled','Datum'); ?></th>
			<th>Kodierung
				<a href="#" onclick="return showHelp('encoding');">
					<span class="fa fa-info-circle"></span>
				</a>
			</th>
			<th><?php echo $this->Paginator->sort('words','Wörter'); ?></th>
			<th><?php echo $this->Paginator->sort('rating_dictionary','Wörterbuch'); ?>
				<a href="#" onclick="return showHelp('dictionary');">
					<span class="fa fa-info-circle"></span>
				</a>
			</th>
			<th><?php echo $this->Paginator->sort('rating_fillers','Füllwörter'); ?>
				<a href="#" onclick="return showHelp('fillers');">
					<span class="fa fa-info-circle"></span>
				</a>
			</th>
			<?php foreach($readability_indizes as $index) {
				echo '<th>'.$index;
				echo '<a href="#" onclick="return showHelp(\''.$index.'\');">';
				echo '	<span class="fa fa-info-circle"></span>';
				echo '</a></th>';
			}?>
			<th style="border-left:1px solid #000;min-width:90px;"></th>
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
		echo '<td>';
		if ($site['Website']['given_encoding'] == $site['Website']['detected_encoding']) {
			if ($site['Website']['given_encoding'] != null) {
				echo $site['Website']['given_encoding'].'<br/>';
			}
			echo '<span style="color:#0A0;" class="fa fa-check-circle"></span>';
		} else {
			echo 'Server: '.$site['Website']['given_encoding'].',<br/>';
			echo 'Inhalt: '.$site['Website']['detected_encoding'].'<br/>';
			echo '<span style="color:#AA0;" class="fa fa-warning"></span>';
		}
		echo '</td>';
		if ($site['Website']['date_rated'] != null) {
			echo '<td>'.$site['Website']['words'].'</td>';
			echo '<td>'.$site['Website']['rating_dictionary'].'%</td>';
			echo '<td title="'.$site['Website']['fillers'].'">'.$site['Website']['rating_fillers'].'%</td>';
		} else {
			echo '<td colspan="3">noch nicht ermittelt<br/>';
			//echo '<a href="'.$this->Html->Url(array('controller'=>'Rating','action'=>'rate',$site['Website']['id'])).'" class="btn">Jetzt berechnen!</a>';
			echo '</td>';
		}
		foreach($readability_indizes as $index) {
			$found = false;
			foreach($site['WebsiteReadability'] as $indexdata) {
				if ($indexdata['readability_code'] == $index) {
					echo '<td>'.$indexdata['result_txt'].'<br/>';
					echo '('.$indexdata['result_num'].')</td>';
					$found = true;
				}
			}
			if (!$found) {
				echo '<td>--</td>';
			}
		}
		echo '<td><a target="_blank" href="'.$this->Html->Url(array('controller'=>'Rating','action'=>'rate',$site['Website']['id'],'?'=>array('verbose_output' => 1))).'" class="btn">';
		echo '	<span class="fa fa-refresh fa-fw" title="Bewertung neu errechnen bzw. visualisieren"></span>';
		echo '</a>';
		/*
		if ($site['Website']['date_crawled'] != date('j.n.Y')) {
			echo '<a href="'.$this->Html->Url(array('controller'=>'Crawler','action'=>'crawlWebsites',0,$site['Website']['id'])).'" class="btn">';
			echo '	<span class="fa fa-download fa-fw" title="URL neu abrufen / laden"></span>';
			echo '</a>';
		}
		*/
		echo '</td>';
		echo '</tr>';
	}?>
	</tbody>
	</table>
</div>
</div>

<script>
function showHelp(type) {
	switch(type) {
	case 'dictionary':
		showModal('Wörterbuch-Bewertung','Die Zahl gibt an, wieviel Prozent der gefundenen Wörter auf der Website im Wörterbuch zu finden sind.<br/>Bitte zu berücksichtigen, dass das Wörterbuch nicht <i>alle</i> Wörter kennt.');
		break;
	case 'fillers':
		showModal('Wörterbuch-Bewertung','Die Prozentzahl bezieht ist ein nach eigener Formel errechneter Faktor. Der zugrunde liegende Wert, also wieviel Füllwörter gefunden wurden, wird angezeigt, wenn man mit der Maus über die Prozentzahl fährt<br/>Für nähere Informationen siehe <a href="<?php echo $this->Html->Url(array('controller'=>'pages','action'=>'faq','#'=>'dictionary')); ?>">HIER</a>');
		break;
	case 'ARI':
		showModal('Automated Readability Index','Gibt die Lesbarkeit in Schulstufen des US-amerikanischen Schulsystems an. Er ist an sich für englische Texte ausgelegt, aber gibt dennoch eine grobe Orientierung.<br/><a href="https://en.wikipedia.org/wiki/Automated_readability_index" target="_blank">Nähere Infos</a>');
		break;
	case 'LIX':
		showModal('Laesbarhedsindex','Ursprünglich aus Schweden stammend, ist dieser Index unter anderem auch auf Deutsch anwendbar.<br/><a href="http://www.ideosity.com/ourblog/post/ideosphere-blog/2010/01/14/readability-tests-and-formulas#LIX" target="_blank">Weitere Infos</a>');
		break;
	case 'CLI':
		showModal('...','(nur testweise angezeigt)');
		break;
	case 'encoding':
		showModal('Kodierung','Erkannte Kodierung der Website. Wenn die Angaben des Servers vom tatsächlichen Inhalt abweichen, dann erscheint ein Warnsymbol.');
		break;
	default:
		showModal('Nicht verfügbar','Sorry, Hilfe zu diesem Thema ist noch nicht verfügbar');
	}
	return false;
}
</script>