-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Erstellungszeit: 17. Jul 2016 um 18:07
-- Server-Version: 5.1.73-log
-- PHP-Version: 5.5.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Datenbank: `crawler`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `brand` varchar(64) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `brands`
--

INSERT INTO `brands` (`id`, `brand`) VALUES
(1, 'Toyota'),
(2, 'Volkswagen'),
(3, 'Hyundai'),
(4, 'Ford'),
(5, 'Nissan'),
(6, 'Fiat'),
(7, 'Honda'),
(8, 'Suzuki'),
(9, 'Renault'),
(10, 'BMW'),
(11, 'Daimler'),
(12, 'Mazda'),
(13, 'Mitsubishi'),
(14, 'Volvo'),
(15, 'Google'),
(16, 'Nokia'),
(17, 'Samsung'),
(18, 'Microsoft'),
(19, 'Apple'),
(20, 'Disney'),
(21, 'Intel'),
(22, 'Mercedes'),
(23, 'Adidas'),
(24, 'Aldi'),
(25, 'Aral'),
(26, 'Audi'),
(27, 'BASF'),
(28, 'Bayer'),
(29, 'Bosch'),
(30, 'Brabus'),
(31, 'Commerzbank'),
(32, 'Chrysler'),
(33, 'DHL'),
(34, 'Haribo'),
(35, 'Knorr'),
(36, 'Krombacher'),
(37, 'Lidl'),
(38, 'Liebherr'),
(39, 'Lufthansa'),
(40, 'Miele'),
(41, 'Nivea'),
(42, 'Opel'),
(43, 'Osram'),
(44, 'Persil'),
(45, 'Porsche'),
(46, 'Reebok'),
(47, 'ReWe'),
(48, 'Schwarzkopf'),
(49, 'Siemens'),
(50, 'Edeka'),
(51, 'Shell'),
(52, 'Exxon'),
(53, 'Würth'),
(54, 'Vodafone'),
(55, 'McDonald'),
(56, 'Facebook'),
(57, 'Twitter'),
(58, 'IBM'),
(59, 'Marlboro'),
(60, 'Nestle'),
(61, 'Starbucks'),
(62, 'Huawei'),
(63, 'Pepsi'),
(64, 'Cisco'),
(65, 'Deloitte'),
(66, 'Santander'),
(67, 'Boeing'),
(68, 'Hitachi'),
(69, 'FedEx'),
(70, 'Panasonic'),
(71, 'Airbus'),
(72, 'Zara'),
(73, 'Generali'),
(74, 'Petronas'),
(75, 'Dell'),
(76, 'Chevrolet'),
(77, 'eBay'),
(78, 'XBox'),
(79, 'Danone'),
(80, 'Philips'),
(81, 'Caterpillar'),
(82, 'Hilton'),
(83, 'MasterCard'),
(84, 'Emirates'),
(85, 'Playstation'),
(86, 'Gillette'),
(87, 'BBC'),
(88, 'Canon'),
(89, 'Toshiba'),
(90, 'Pampers'),
(91, 'Nescafé'),
(92, 'NETFLIX'),
(93, 'Garnier'),
(94, 'Gazprom'),
(95, 'Youtube'),
(96, 'Lexus'),
(97, 'Adobe'),
(98, 'Yahoo'),
(99, 'Petrobras'),
(100, 'Marriott'),
(101, 'Gucci'),
(102, 'ABB'),
(103, 'Halifax'),
(104, 'Esso'),
(105, 'Lego'),
(106, 'Unilever'),
(107, 'Ferrari'),
(108, 'Lamborghini'),
(109, 'Maserati'),
(110, 'Citroën'),
(111, 'Heineken'),
(112, 'Lenovo'),
(113, 'Fresenius'),
(114, 'Schlumberger'),
(115, 'Fujitsu'),
(116, 'Xerox'),
(117, 'Subaru'),
(118, 'Dove'),
(119, 'Lipton'),
(120, 'Prada'),
(121, 'Jeep'),
(122, 'Colgate'),
(123, 'Sprite'),
(124, 'Fanta'),
(125, 'Fujifilm'),
(126, 'UniCredit'),
(127, 'MTV'),
(128, 'Novartis'),
(129, 'Interwetten'),
(130, 'Ruefa'),
(131, 'Neuroth'),
(132, 'Quantas'),
(133, 'Eclipse'),
(134, 'Bipa'),
(135, 'Beurer'),
(136, 'Calzedonia'),
(137, 'Kika'),
(138, 'Leiner'),
(139, 'Möbelix'),
(140, 'Hofer'),
(141, 'Billa'),
(142, 'Telering'),
(143, 'Fleischmann'),
(144, 'Grohe'),
(145, 'Junkers'),
(146, 'Lockheed'),
(147, 'Voith'),
(148, 'Atmel'),
(149, 'asics'),
(150, 'Bébé'),
(151, 'Segway'),
(152, 'HSBC'),
(153, 'Raffaello'),
(154, 'Easybank'),
(155, 'Mömax'),
(156, 'Chevron'),
(157, 'Reebock'),
(158, 'Bentley'),
(159, 'Bugatti'),
(160, 'Dacia'),
(161, 'Lancia'),
(162, 'Peugeot'),
(163, 'Saab'),
(164, 'Seat'),
(165, 'Acer'),
(166, 'Benq'),
(167, 'Logitech'),
(168, 'HTC'),
(169, 'Motorola'),
(170, 'Yamaha'),
(171, 'Sennheiser'),
(172, 'Scania'),
(173, 'Milka'),
(174, 'Toblerone'),
(175, 'Toffifee'),
(176, 'Ferrero'),
(177, 'Bulgari'),
(178, 'Swatch'),
(179, 'Lindt'),
(180, 'Palmers'),
(181, 'Pirelli'),
(182, 'Bridgestone'),
(183, 'Michelin'),
(184, 'Smirnoff'),
(185, 'Eristoff'),
(186, 'Bacardi'),
(187, 'NEC'),
(188, 'AEG'),
(189, 'AMG'),
(190, 'Martini'),
(191, 'McLaren'),
(192, 'Skype'),
(193, 'Nike'),
(194, 'Manner'),
(195, 'UBS'),
(196, 'Bawag'),
(197, 'Strabag'),
(198, 'Epson'),
(199, 'Benetton'),
(200, 'Agip'),
(201, 'Goodyear'),
(202, 'Oracle'),
(203, 'SAP'),
(204, 'Technogym'),
(205, 'Sanyo'),
(206, 'Olivetti'),
(207, 'CNN'),
(208, 'Castrol'),
(209, 'Jägermeister'),
(210, 'Pioneer'),
(211, 'AMD'),
(212, 'Etihad'),
(213, 'Kaspersky'),
(214, 'Medion'),
(215, 'Parmalat'),
(216, 'JVC'),
(217, 'Agfa'),
(218, 'Geox'),
(219, 'Casio'),
(220, 'ADAC'),
(221, 'Wienerberger'),
(222, 'Bösendorfer'),
(223, 'Magna'),
(224, 'Pfanner'),
(225, 'Swarovski'),
(226, 'Raiffeisen'),
(227, 'Uniqa'),
(228, 'Andritz'),
(229, 'OMV'),
(230, 'Kapsch'),
(231, 'Gardena'),
(232, 'Grundig'),
(233, 'Loewe'),
(234, 'Orion'),
(235, 'Oetker'),
(236, 'Kaufland'),
(237, 'Storck'),
(238, 'Tchibo'),
(239, 'Capgemini'),
(240, 'Lacoste'),
(241, 'Sodexo'),
(242, 'Ubisoft'),
(243, 'Vauxhall'),
(244, 'Märklin'),
(245, 'Roco'),
(246, 'ZDF'),
(247, 'Pontiac'),
(248, 'Hervis'),
(249, 'Stiegl'),
(250, 'Iveco'),
(251, 'Nintendo'),
(265, 'Desigual'),
(253, 'dpd'),
(254, 'Varta'),
(255, 'Duracell'),
(256, 'UEFA'),
(257, 'Continental'),
(258, 'yesss'),
(259, 'Gösser'),
(260, 'Sharp'),
(261, 'Silva'),
(262, 'Römerquelle'),
(263, 'Schweppes'),
(264, 'ÖAMTC'),
(266, 'Burberry'),
(267, 'Converse'),
(268, 'Voest'),
(269, 'Breitling'),
(270, 'Rolex'),
(271, 'Blaguss'),
(272, 'Kellogg');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `domains`
--

CREATE TABLE `domains` (
  `id` int(11) NOT NULL,
  `domain` varchar(128) NOT NULL,
  `sites` int(11) NOT NULL,
  `modified` datetime NOT NULL,
  `avg_dictionary` float NOT NULL,
  `avg_fillers` float NOT NULL,
  `blacklist` tinyint(4) NOT NULL DEFAULT '0',
  `ending` varchar(6) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fillers`
--

CREATE TABLE `fillers` (
  `id` int(11) NOT NULL,
  `phrase` varchar(32) NOT NULL,
  `type` enum('word','phrase') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `fillers`
--

INSERT INTO `fillers` (`id`, `phrase`, `type`) VALUES
(1, 'ähh', 'word'),
(2, 'eh', 'word'),
(3, 'ehm', 'word'),
(4, 'mmmm', 'word'),
(5, 'öh', 'word'),
(6, 'hmm', 'word'),
(7, 'ähm', 'word'),
(8, 'an und für sich', 'phrase'),
(9, 'das ist keine Frage', 'phrase'),
(10, 'das ist gar keine Frage', 'phrase'),
(11, 'eben', 'word'),
(43, 'abermals', 'word'),
(13, 'es ist so, daß', 'phrase'),
(14, 'es ist so, dass', 'phrase'),
(15, 'gewissermaßen', 'word'),
(16, 'ich denke', 'phrase'),
(17, 'ich meine', 'phrase'),
(18, 'ich für meinen Teil', 'phrase'),
(19, 'ich sag mal', 'phrase'),
(20, 'ich würde sagen', 'phrase'),
(21, 'ich würde meinen', 'phrase'),
(22, 'ich würde glauben', 'phrase'),
(23, 'im Grunde', 'phrase'),
(24, 'in der Tat', 'phrase'),
(25, 'in gewisser Weise', 'phrase'),
(26, 'natürlich', 'word'),
(27, 'selbstverständlich', 'word'),
(28, 'praktisch', 'word'),
(29, 'sozusagen', 'word'),
(30, 'sprich', 'word'),
(31, 'unter Umständen', 'phrase'),
(32, 'wenn Sie so wollen', 'phrase'),
(33, 'wenn man so will', 'phrase'),
(34, 'wenn Sie mich fragen', 'phrase'),
(35, 'wie gesagt', 'phrase'),
(36, 'wissen sie', 'phrase'),
(37, 'weißt du', 'phrase'),
(38, 'ja freilich', 'phrase'),
(39, 'wieder einmal', 'phrase'),
(40, 'doch', 'word'),
(41, 'vielleicht', 'word'),
(42, 'bloß', 'word'),
(44, 'allemal', 'word'),
(45, 'allem Anschein nach', 'phrase'),
(46, 'an und für sich', 'phrase'),
(47, 'auf alle Fälle ', 'phrase'),
(48, 'außerdem', 'word'),
(49, 'beinahe', 'word'),
(50, 'bloß', 'word'),
(51, 'dann und wann', 'phrase'),
(52, 'eigentlich', 'word'),
(53, 'man könnte sagen', 'phrase'),
(54, 'mehr oder weniger ', 'phrase'),
(55, 'meines Erachtens', 'phrase'),
(56, 'sagen wir mal', 'phrase'),
(57, 'uh', 'word');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `known_words`
--

CREATE TABLE `known_words` (
  `id` int(11) NOT NULL,
  `word` varchar(64) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `shorthands`
--

CREATE TABLE `shorthands` (
  `id` int(11) NOT NULL,
  `shorthand` varchar(16) NOT NULL,
  `meaning` varchar(64) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `shorthands`
--

INSERT INTO `shorthands` (`id`, `shorthand`, `meaning`) VALUES
(1, 'Abb.', 'Abbildung'),
(2, 'Abf.', 'Abfahrt'),
(3, 'Abg.', 'Abgeordneter'),
(4, 'abg.', 'abgeändert'),
(5, 'Abh.', 'Abhandlung'),
(6, 'Abk.', 'Abkürzung'),
(7, 'abk.', 'abkürzen'),
(8, 'Abs.', 'Absender'),
(9, 'Abw.', 'Abwärme'),
(10, 'Abz.', 'Abzahlung'),
(11, 'Adir.', 'Amtsdirektor'),
(12, 'Adj.', 'Adjektiv'),
(13, 'adj.', 'adjektivisch'),
(14, 'Adr.', 'Adresse'),
(15, 'Adv.', 'Adverb'),
(16, 'adv.', 'adverbial'),
(17, 'Afr.', 'Afrika'),
(18, 'Ag.', 'Agentur'),
(19, 'agg.', 'Aggregat'),
(20, 'Aggr.', 'Aggregat'),
(21, 'Ahg.', 'Anhänger'),
(22, 'Anh.', 'Anhang'),
(23, 'Akad.', 'Akademie'),
(24, 'akad.', 'akademisch'),
(25, 'Akk.', 'Akkusativ'),
(26, 'Alg.', 'Algebra'),
(27, 'allg.', 'allgemein'),
(28, 'alph.', 'alphabetisch'),
(29, 'altgr.', 'altgriechisch'),
(30, 'Am.', 'Amateur'),
(31, 'Amp.', 'Ampere'),
(32, 'amtl.', 'amtlich'),
(33, 'An.', 'Analyse'),
(34, 'anat.', 'anatomisch'),
(35, 'anerk.', 'anerkannt'),
(36, 'Anf.', 'Anfang'),
(37, 'Anfr.', 'Anfrage'),
(38, 'Ang.', 'Angabe'),
(39, 'angekl.', 'angeklagt'),
(40, 'Angel.', 'Angelegenheit'),
(41, 'Angest.', 'Angestellte'),
(42, 'angew.', 'angewandt'),
(43, 'Ank.', 'Ankunft'),
(44, 'Anl.', 'Anlage'),
(45, 'anl.', 'anlässlich'),
(46, 'Anm.', 'Anmerkung'),
(47, 'Ann.', 'Annahme'),
(48, 'ann.', 'annoncieren'),
(49, 'anon.', 'anonym'),
(50, 'Anord.', 'Anordnung'),
(51, 'Anp.', 'Anpassung'),
(52, 'ANr.', 'Aktennummer'),
(53, 'Ans.', 'Ansage'),
(54, 'Ansch.', 'Anschaffung'),
(55, 'anschl.', 'anschließend'),
(56, 'Anschr.', 'Anschrift'),
(57, 'Anspr.', 'Ansprache'),
(58, 'Antiq.', 'Antiquitäten'),
(59, 'Antr.', 'Antrag'),
(60, 'Antw.', 'Antwort'),
(61, 'Anz.', 'Anzahl'),
(62, 'apl.', 'außerplanmäßig'),
(63, 'App.', 'Apparat'),
(64, 'Apr.', 'April'),
(65, 'apr.', 'apropos'),
(66, 'Aq.', 'Aquarell'),
(67, 'Arbf.', 'Arbeitsfeld'),
(68, 'Arbg.', 'Arbeitgeber'),
(69, 'Arbn.', 'Arbeitnehmer'),
(70, 'Arch.', 'Archäologie, -loge'),
(71, 'arr.', 'arrangieren'),
(72, 'Art.', 'Artikel'),
(73, 'Artt.', 'Artikel'),
(74, 'Asp.', 'Aspekt'),
(75, 'Assist.', 'Assistent'),
(76, 'Astrol.', 'Astrologie'),
(77, 'astron.', 'astronomisch'),
(78, 'asym.', 'asymmetrisch'),
(79, 'asymp.', 'asymptotisch'),
(80, 'At.', 'Atelier'),
(81, 'Atl.', 'Atlantik'),
(82, 'Atm.', 'Atmosphäre'),
(83, 'Attr.', 'Attraktion'),
(84, 'Aufb.', 'Aufbewahrung'),
(85, 'Aufbew.', 'Aufbewahrung'),
(86, 'Aufg.', 'Aufgabe'),
(87, 'Aufkl.', 'Aufklärung'),
(88, 'Aufl.', 'Auflage'),
(89, 'Ausg.', 'Ausgabe'),
(90, 'ausschl.', 'ausschließlich'),
(91, 'Az.', 'Aktenzeichen'),
(92, 'Änd.', 'Änderung'),
(93, 'Äq.', 'Äquator'),
(94, 'ärztl.', 'ärztlich'),
(95, 'ästh.', 'ästhetisch'),
(96, 'äth.', 'ätherisch'),
(97, 'Ba.', 'Bachelor'),
(98, 'Bakk.', 'Bachelor'),
(99, 'Bd.', 'Band'),
(100, 'Bde.', 'Bände'),
(101, 'bes.', 'besonders'),
(102, 'Betr.', 'Betreff'),
(103, 'bez.', 'bezahlt'),
(104, 'Bez.', 'Bezeichnung'),
(105, 'Bhf.', 'Bahnhof'),
(106, 'Bil.', 'Billion'),
(107, 'Bl.', 'Blatt'),
(108, 'brosch.', 'broschiert'),
(109, 'Bsp.', 'Beispiel'),
(110, 'bspw.', 'beispielsweise'),
(111, 'bzgl.', 'bezüglich'),
(112, 'bzw.', 'beziehungsweise'),
(113, 'ca.', 'circa'),
(114, 'DDr.', 'Doktor'),
(115, 'desgl.', 'desgleichen'),
(116, 'dgl.', 'dergleichen'),
(117, 'Dipl.', 'Diplom'),
(118, 'Dr.', 'Doktor'),
(119, 'Ing.', 'Ingenieur'),
(120, 'jur.', 'juristisch'),
(121, 'med.', 'Medizin'),
(122, 'dent.', 'Zahnheilkunde'),
(123, 'vet.', 'Tierheilkunde'),
(124, 'phil.', 'Philosophie'),
(125, 'nat.', 'Naturwissenschaften'),
(126, 'pol.', 'Staatswissenschaften'),
(127, 'theol.', 'Theologie'),
(128, 'dt.', 'deutsch'),
(129, 'dtsch.', 'deutsch'),
(130, 'dto.', 'dito'),
(131, 'Dtz.', 'Dutzend'),
(132, 'Dtzd.', 'Dutzend'),
(133, 'ebd.', 'ebenda'),
(134, 'Ed.', 'Edition'),
(135, 'ehem.', 'ehemals'),
(136, 'eig.', 'eigentlich'),
(137, 'eigtl.', 'eigentlich'),
(138, 'einschl.', 'einschließlich'),
(139, 'entspr.', 'entsprechend'),
(140, 'erg.', 'ergänze'),
(141, 'etc.', 'und so weiter'),
(142, 'ev.', 'evangelisch'),
(143, 'evtl.', 'eventuell'),
(144, 'exkl.', 'exklusive'),
(145, 'Expl.', 'Exemplar'),
(146, 'Exz.', 'Exzellenz'),
(147, 'Fa.', 'Firma'),
(148, 'Fam.', 'Familie'),
(149, 'ff.', 'folgende'),
(150, 'Forts.', 'Fortsetzung'),
(151, 'Fr.', 'Frau'),
(152, 'frdl.', 'freundlich'),
(153, 'Frhr.', 'Freiherr'),
(154, 'Frl.', 'Fräulein'),
(155, 'frz.', 'französisch'),
(156, 'Gbf.', 'Güterbahnhof'),
(157, 'geb.', 'geboren'),
(158, 'Gebr.', 'Gebrüder'),
(159, 'gegr.', 'gegründet'),
(160, 'geh.', 'geheftet'),
(161, 'gek.', 'gekürzt'),
(162, 'Ges.', 'Gesellschaft'),
(163, 'ges.', 'gesetzlich'),
(164, 'gesch.', 'geschieden'),
(165, 'Geschw.', 'Geschwindigkeit'),
(166, 'gest.', 'gestorben'),
(167, 'Gew.', 'Gewicht'),
(168, 'gez.', 'gezeichnet'),
(169, 'ggf.', 'gegebenenfalls'),
(170, 'Gr.', 'Größe'),
(171, 'Hbf.', 'Hauptbahnhof'),
(172, 'hg.', 'herausgegeben'),
(173, 'hL.', 'herrschende Lehre'),
(174, 'hl.', 'heilig'),
(175, 'Hr.', 'Herr'),
(176, 'Hrn.', 'Herrn'),
(177, 'Hrsg.', 'Herausgeber'),
(178, 'Hs.', 'Handschrift'),
(179, 'allg.', 'allgemeinen'),
(180, 'Allg.', 'Allgemeinen'),
(181, 'id.', 'idem'),
(182, 'Ing.', 'Ingenieur'),
(183, 'Inh.', 'Inhaber'),
(184, 'inkl.', 'inklusive'),
(185, 'Jb.', 'Jahrbuch'),
(186, 'Jg.', 'Jahrgang'),
(187, 'Jh.', 'Jahrhundert'),
(188, 'Jkr.', 'Junker'),
(189, 'jr.', 'Junior'),
(190, 'jun.', 'Junior'),
(191, 'Kap.', 'Kapitel'),
(192, 'kath.', 'katholisch'),
(193, 'Kfm.', 'Kaufmann'),
(194, 'kfm.', 'kaufmännisch'),
(195, 'kgl.', 'königlich'),
(196, 'Kl.', 'Klasse'),
(197, 'Komp.', 'Kompanie'),
(198, 'Kr.', 'Kreis'),
(199, 'Kto.', 'Konto'),
(200, 'led.', 'ledig'),
(201, 'lfd.', 'laufend'),
(202, 'Lfg.', 'Lieferung'),
(203, 'Lfrg.', 'Lieferung'),
(204, 'lt.', 'laut'),
(205, 'Ltn.', 'Leutnant'),
(206, 'math.', 'mathematisch'),
(207, 'Min.', 'Minute'),
(208, 'Mio.', 'Millionen'),
(209, 'Mill.', 'Millionen'),
(210, 'möbl.', 'möbliert'),
(211, 'Mrd.', 'Milliarden'),
(212, 'Md.', 'Milliarden'),
(213, 'Mia.', 'Milliarden'),
(214, 'Ms.', 'Manuskript'),
(215, 'Mskr.', 'Manuskript'),
(216, 'mtl.', 'monatlich'),
(217, 'MwSt.', 'Mehrwertsteuer'),
(218, 'Mz.', 'Mehrzahl'),
(219, 'Nachf.', 'Nachfolger'),
(220, 'Nchf.', 'Nachfolger'),
(221, 'Chr.', 'Christus'),
(222, 'nachm.', 'nachmittags'),
(223, 'Nds.', 'Niedersachsen'),
(224, 'Nr.', 'Nummer'),
(225, 'No.', 'Nummer'),
(226, 'Nrn.', 'Nummern'),
(227, 'Nos.', 'Nummern'),
(228, 'Pfd.', 'Pfund'),
(229, 'pp.', 'per procura'),
(230, 'ppa.', 'per procura'),
(231, 'Pfr.', 'Pfarrer'),
(232, 'Pkt.', 'Punkt'),
(233, 'Prof.', 'Professor'),
(234, 'Prov.', 'Provinz'),
(235, 'ps.', 'Postskriptum'),
(236, 'rk.', 'römisch-katholisch'),
(237, 'rd.', 'rund'),
(238, 'Reg.', 'Regierung'),
(239, 'Rel.', 'Religion'),
(240, 'resp.', 'respektive'),
(241, 'Sa.', 'Summe'),
(242, 'sen.', 'senior'),
(243, 'Spk.', 'Sparkasse'),
(244, 'spec.', 'Species'),
(245, 'St.', 'Sankt'),
(246, 'Skt.', 'Sankt'),
(247, 'St.', 'Stunde'),
(248, 'Std.', 'Stunde'),
(249, 'Str.', 'Straße'),
(250, 'stud.', 'Student'),
(251, 'svw.', 'so viel wie'),
(252, 'Tel.', 'Telefon'),
(253, 'Tsd.', 'Tausend'),
(254, 'Univ.', 'Universität'),
(255, 'urspr.', 'ursprünglich'),
(256, 'usf.', 'und so fort'),
(257, 'usw.', 'und so weiter'),
(258, 'Verf.', 'Verfasser'),
(259, 'Vf.', 'Verfasser'),
(260, 'verh.', 'verheiratet'),
(261, 'Verl.', 'Verlag'),
(262, 'Vers.', 'Versicherung'),
(263, 'vers.', 'versichert'),
(264, 'verw.', 'verwitwet'),
(265, 'vgl.', 'vergleiche'),
(266, 'vorm.', 'vormals'),
(267, 'Vors.', 'Vorsitzender'),
(268, 'Wwe.', 'Witwe'),
(269, 'Wwr.', 'Witwer'),
(270, 'Wz.', 'Warenzeichen'),
(272, 'zz.', 'zurzeit'),
(273, 'zzt.', 'zurzeit'),
(274, 'zz.', 'zu zeigen'),
(275, 'Ztg.', 'Zeitung'),
(276, 'Ztr.', 'Zentner'),
(277, 'Ztschr.', 'Zeitschrift'),
(278, 'zus.', 'zusammen'),
(279, 'zw.', 'zwischen'),
(280, 'zzgl.', 'zuzüglich'),
(281, 'Feb.', 'Februar'),
(282, 'Apr.', 'April'),
(283, 'Jun.', 'Juni'),
(284, 'Jul.', 'Juli'),
(285, 'Aug.', 'August'),
(286, 'Sept.', 'September'),
(287, 'Sep.', 'September'),
(288, 'Okt.', 'Oktober'),
(289, 'Nov.', 'November'),
(290, 'Dez.', 'Dezember'),
(291, 'Evang.', 'Evangelisch');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `unknown_words`
--

CREATE TABLE `unknown_words` (
  `id` int(11) NOT NULL,
  `word` varchar(128) NOT NULL,
  `checked` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `websites`
--

CREATE TABLE `websites` (
  `id` int(11) NOT NULL,
  `title` text,
  `url` varchar(256) NOT NULL,
  `domain_id` int(11) DEFAULT NULL,
  `date_crawled` datetime DEFAULT NULL,
  `date_rated` datetime DEFAULT NULL,
  `sourcecode` mediumtext,
  `rating_dictionary` float NOT NULL DEFAULT '0',
  `rating_fillers` float NOT NULL DEFAULT '0',
  `words` int(11) NOT NULL DEFAULT '0',
  `fillers` int(11) NOT NULL DEFAULT '0',
  `checksum` varchar(32) DEFAULT NULL,
  `given_encoding` varchar(16) DEFAULT NULL,
  `detected_encoding` varchar(16) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `website_readabilities`
--

CREATE TABLE `website_readabilities` (
  `website_id` int(11) NOT NULL,
  `readability_code` varchar(4) NOT NULL,
  `result_num` float NOT NULL,
  `result_txt` varchar(32) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `brand` (`brand`);

--
-- Indizes für die Tabelle `domains`
--
ALTER TABLE `domains`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `domain` (`domain`),
  ADD KEY `ending` (`ending`);

--
-- Indizes für die Tabelle `fillers`
--
ALTER TABLE `fillers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `word` (`phrase`);

--
-- Indizes für die Tabelle `known_words`
--
ALTER TABLE `known_words`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `word_2` (`word`);

--
-- Indizes für die Tabelle `shorthands`
--
ALTER TABLE `shorthands`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `unknown_words`
--
ALTER TABLE `unknown_words`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `word` (`word`);

--
-- Indizes für die Tabelle `websites`
--
ALTER TABLE `websites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `url` (`url`),
  ADD KEY `checksum` (`checksum`);

--
-- Indizes für die Tabelle `website_readabilities`
--
ALTER TABLE `website_readabilities`
  ADD PRIMARY KEY (`website_id`,`readability_code`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=274;
--
-- AUTO_INCREMENT für Tabelle `domains`
--
ALTER TABLE `domains`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8704;
--
-- AUTO_INCREMENT für Tabelle `fillers`
--
ALTER TABLE `fillers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;
--
-- AUTO_INCREMENT für Tabelle `known_words`
--
ALTER TABLE `known_words`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=344460;
--
-- AUTO_INCREMENT für Tabelle `shorthands`
--
ALTER TABLE `shorthands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=293;
--
-- AUTO_INCREMENT für Tabelle `unknown_words`
--
ALTER TABLE `unknown_words`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1024165;
--
-- AUTO_INCREMENT für Tabelle `websites`
--
ALTER TABLE `websites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2532307;
