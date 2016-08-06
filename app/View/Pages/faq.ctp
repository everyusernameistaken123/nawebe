<div class="row-fluid">
<div class="span10 offset1">

<h1>FAQ</h1>

<p>Zu Abschnitt springen:</p>
<ul>
<li><a href="#general">Allgemein</a></li>
<li><a href="#howto">How-To</a></li>
<li><a href="#technical">Technische Details</a></li>
<li><a href="#dictionary">Details zum Wörterbuch</a></li>
<li><a href="#readability">Lesbarkeits-Indizes</a></li>
</ul>

<h2 id="general">Allgemeines</h2>

<p class="question">Was war der Ursprung dieses Projektes?</p>
<p class="answer">
Am Anfang stand &quot;nur&quot; der Wunsch / das Interesse, einen Web-Crawler zu entwickeln.<br/>
Es gibt bereits unzählige Crawler, Meta-Suchmaschinen und vieles mehr. Die meisten dieser Programme dienen dazu, finanziellen Profit zu generieren. (Werbe-Einschaltungen, gebührenpflichtige Mitgliedschaften, Verkauf von Daten etc.)<br/>
</p>

<p class="question">Was ist das Ziel / die Absicht hinter NaWeBe?</p>
<p class="answer">
Mit der &quot;Website Bewertung&quot; will ich einen nicht-kommerziellen Weg beschreiten. Die Algorithmen sind nicht darauf ausgelegt, Suchmaschinenoptimierung (SEO) zu betreiben. Es wird keine Werbung geben und alle Ergebnisse stehen frei zur Verfügung.<br/>
Es soll einfach ein &quot;Überblick für statistik-interessierte Personen&quot; geboten werden.
</p>

<p class="question">Nicht-Kommerziell ist nett, aber wie sieht es mit Open-Source aus?</p>
<p class="answer">
Seit Juli 2016 auf <a href="https://github.com/everyusernameistaken123/nawebe">Github</a> verfügbar.
</p>

<p class="question">Welche Technologien werden verwendet?</p>
<p class="answer">
Im Grunde handelt es sich um eine dynamische Website (wie unzählige andere), basierend auf php &amp; MySQL.<br/>
Da ich beruflich viel damit zu tun hatte, verwende ich den <a href="http://cakephp.org/">cakePHP-Framework</a>.
</p>

<p class="question">Welche Sprachen werden unterstützt?</p>
<p class="answer">
Vorläufig nur Deutsch.
</p>

<h2 id="howto">How-To</h2>

<p class="question">Wie funktioniert die &quot;Visualisierung&quot;?</p>
<p class="answer">
Mit dieser Funktion (<span class="fa fa-refresh"></span>) kann man sich detailliert anzeigen lassen, was der Crawler &quot;sieht&quot;.<br/>
Moderne Screen-Reader für Menschen mit Sehschwäche sind natürlich um einiges höher-entwickelt, jedoch ist das Prinzip dasselbe.<br/>
Damit ist leicht zu erkennen, welche Wörter nicht erkannt wurden. Oder allgemein gesagt: Wie die Bewertung zustande kommt.
</p>

<p class="question">Wie kann man eine neue Domain oder Website eintragen?</p>
<p class="answer">
Ganz einfach <a href="<?php echo $this->Html->url(array('controller'=>'website','action'=>'add'))?>">Hier</a>.<br/>
Die Seite wird so bald wie möglich besucht, jedoch kann es mehrere Wochen dauern, bis auch Unterseiten erfasst werden.
</p>

<h2 id="technical">Technische Details</h2>

<p class="question">Wird der Robots-Meta-Tag berücksichtigt?</p>
<p class="answer">
Ja, Der Crawler repektiert den Robots-Meta-Tag (noindex, nofollow).<br/>
HTML-Kommentare oder Javascript-Kommentare die ähnliches andeuten, werden nicht verarbeitet da sie nicht standardisiert sind. (Wurde während der Entwicklung im Quelltext mancher Seiten gefunden.)
</p>

<p class="question">Warum wird die robots.txt-Datei ignoriert?</p>
<p class="answer">
Meistens wird hier auf Web-Standards oder &quot;defacto-Standards&quot; verwiesen um Dinge zu rechtfertigen. Bei der robots.txt wird eine Ausnahme gemacht: &quot;Ordentliche&quot; Crawler respektieren diese Angaben, für &quot;böswillige&quot; Programme gibt diese Datei jedoch Hinweise wo genau die Daten sind, die eigentlich versteckt werden sollen. <a href="http://www.theregister.co.uk/2015/05/19/robotstxt/">Details (engl.)</a>.
</p>

<p class="question">Welche Serverlast wird durch den Crawler erzeugt?</p>
<p class="answer">
Seiten die von uns gecrawlt werden, sollten die &quot;Belastung&quot; praktisch nicht spüren. Das Crawling ist in vielen Hinsichten eingeschränkt:<br/>
Es werden nur max. 100 (Unter-)Seiten einer Domain abgerufen. (Wobei es keine Vorgaben gibt, welche Links bevorzugt werden)<br/>
Zwischen einzelnen Aufrufen liegen mindestens eine Sekunde, in den meisten Fällen mehr.
</p>

<p class="question">Wie aktuell sind die Daten?</p>
<p class="answer">
Grundsätzlich ist immer das Datum vermerkt, wann eine URL zuletzt besucht wurde.<br/>
Es ist noch nicht festgelegt, in welchem Intervall Seiten erneut besucht werden. Man kann jedoch davon ausgehen, dass es sich um sehr lange &quot;Zyklen&quot; handelt (mehrere Wochen).
</p>

<p class="question">Ich möchte, dass meine Website hier nicht gelistet wird, was kann ich tun?</p>
<p class="answer">
Es besteht natürlich die Möglichkeit, eine Website bzw. Domain aus der Bewertung auszunehmen.
<br/>Bitte schreiben Sie an: <span id="contact-mail">(Adresse durch Javascript gesch&uuml;tzt)</span>
<script>
decryptMail('nbjmAobxfcf/bu','contact-mail');
function decryptMail(input,target) {
	var ret='';
	for (var i=0;i<input.length;i++) {
		ret=ret + String.fromCharCode(input.charCodeAt(i) - 1);
	}
	document.getElementById(target).innerHTML=ret;
}
</script>
</p>

<p class="question">Wieso werden bekannte Dienste wie Google oder Facebook nicht erfasst?</p>
<p class="answer">
Die Datenbanken von größeren Internet-Dienstleistern sprengen nahezu das Vorstellungsvermögen, es ist für ein privat geführtes Projekt nicht tragbar, diese Seiten zu erfassen.<br/>
Davon abgesehen sind zum Beispiel viele Inhalte auf Facebook für Bots nicht einsehbar. Und viel &quot;user generated content&quot; ist Inhalt, der die Seite nicht angemessen repräsentiert.
</p>

<h2 id="dictionary">Details zum Wörterbuch</h2>

<p class="question">Woher stammen die Daten im Wörterbuch?</p>
<p class="answer">
Die Wörter-Datenbank ist auf verschiedenen Quellen zusammen getragen, darunter:<br/>
http://www.wh9.tu-dresden.de/~heinrich/dict/dict_ding_1.1/data/ger-eng.txt<br/>
www.netzmafia.de<br/>
http://corpora2.informatik.uni-leipzig.de/download.html.
</p>

<p class="question">Wieso sind so viele Wörter nicht erfasst?</p>
<p class="answer">
In der deutschen Sprache kann man praktisch pausenlos neue Wörter erschaffen. Man denke zum Beispiel an das Wort &quot;Hauptquartier&quot;. Je nach Kontext, kann man zu jeder Marke, zu jedem Geschäftszweig usw. ein Wort daraus bilden (Toyota-Hauptquartier, Maler-Hauptquartier, ...).<br/>
In solchen Fällen sind natürlich bei weitem nicht alle Möglichkeiten erfasst.<br/>
Beim manuellen Überprüfen der Begriffe fiel auf, dass auch &quot;neu entstandene&quot; Wörter nicht erfasst sind, man denke zum Beispiel an &quot;Deradikalisierung&quot;, &quot;Abgasskandal&quot;, uvm.
</p>

<p class="question">Was sind Füllwörter?</p>
<p class="answer">
<a href="<?php echo $this->Html->Url(array('controller'=>'Website', 'action'=>'listFiller')); ?>">siehe hier</a>
</p>

<p class="question">Wie funktioniert die Bewertung nach Füllwörtern?</p>
<p class="answer">
Hier kommt ein selbst angelegter Maßstab zum tragen:<br/>
Es wird einfach gezählt, wieviel Füllwörter pro 1000 Wörter auftreten.<br/>
1 pro 1000 (oder 0) ist dabei gut, sprich 100%<br/>
Mehr als 10 pro 1000 (bzw. 1%) werden als schlecht (0%) angesehen.
</p>

<h2 id="readability">Lesbarkeit-Indizes</h2>

<p class="question">Was ist ein Lesbarkeits-Index?</p>
<p class="answer">
Hier sei natürlich am Anfang der <a href="https://de.wikipedia.org/wiki/Lesbarkeitsindex">Wikipedia-Artikel</a> empfohlen.<br/>
Ein guter Überlick über verschiedene Formeln gibt es <a href="http://www.readabilityformulas.com/search/pages/Readability_Formulas/">hier</a>
</p>

<p class="question">Welche Indizes werden verwendet?</p>
<p class="answer">
Bei vielen Indizes wird die Anzahl der Silben im Text (zB pro Wort oder pro Satz) beachtet. Die Silben-Trennung ist in der computer-gestützten Verarbeitung nicht leicht, daher werden nur Indizes verwendet, die auf andere Kennzahlen (zB Anzahl der Buchstaben) beruhen.
</p>
<table>
<tr>
	<td>
		<a href="https://en.wikipedia.org/wiki/Automated_readability_index">
			ARI (Automated readability index)
		</a>
	</td>
	<td>
		Dieser Index wurde (wie viele andere) für die englische Sprache festgelegt. Die Anwendung auf deutsche Texte liefert daher nur bedingt brauchbare Ergebnisse. Der ursprüngliche Sinn ist es, die Schwierigkeit als Schulstufe auszudrücken - Hier wird die Schwierigkeit auf Lebensjahre umgerechnet.
	</td>
</tr>
<tr>
	<td>
		<a href="https://en.wikipedia.org/wiki/Automated_readability_index">
			LIX (Laesbarhedsindex)
		</a>
	</td>
	<td>
		Ursprünglich aus Schweden stammend, soll dieser Index für verschiedene westliche Sprachen (darunter auch Deutsch) geeignet sein. Die Ergebnisse liegen jedoch noch zu hoch in den meisten Fällen.
	</td>
</tr>
</table>


</div>
</div>