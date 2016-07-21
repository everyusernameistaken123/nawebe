URL;Titel;Datum;Kodierung;Wörter;Wörterbuch;Füllwörter;<?php
foreach($readability_indizes as $index) {
	echo $index.';';
}
echo "\r\n";

foreach($sites as $site) {
	echo '"'.$site['Website']['url'].'";';
	echo '"'.$site['Website']['title'].'";';
	echo '"'.$site['Website']['date_crawled'].'";';
	echo '"'.$site['Website']['detected_encoding'].'";';
	
	if ($site['Website']['date_rated'] != null) {
		echo $site['Website']['words'].';';
		echo $site['Website']['rating_dictionary'].';';
		echo $site['Website']['fillers'].';';
	} else {
		echo ';';
		echo ';';
		echo ';';
	}
	foreach($readability_indizes as $index) {
		$found = false;
		foreach($site['WebsiteReadability'] as $indexdata) {
			if ($indexdata['readability_code'] == $index) {
				echo $indexdata['result_num'].';';
				$found = true;
			}
		}
		if (!$found) {
			echo '"--";';
		}
	}
	
	echo "\r\n";
}
?>