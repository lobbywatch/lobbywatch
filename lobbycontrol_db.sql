-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 28. Sep 2013 um 08:52
-- Server Version: 5.6.12
-- PHP-Version: 5.5.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `lobby_parlament`
--
CREATE DATABASE IF NOT EXISTS `lobby_parlament` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `lobby_parlament`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `interessenbindungen`
--

CREATE TABLE IF NOT EXISTS `interessenbindungen` (
  `id_interessen` int(11) NOT NULL AUTO_INCREMENT,
  `ib_description` varchar(255) NOT NULL,
  `ib_status` enum('d','n','g') NOT NULL DEFAULT 'd' COMMENT 'd=deklariert, n=nicht-deklariert,g=zutrittsberechtigung',
  `id_parlam` int(11) DEFAULT NULL COMMENT 'FK parlamentarier',
  `id_lobbytyp` int(11) DEFAULT NULL COMMENT 'FK lobbytypen',
  `id_lobbygroup` int(11) DEFAULT NULL COMMENT 'FK lobbygruppen',
  `id_lobbyorg` int(11) DEFAULT NULL COMMENT 'FK lobbyorganisationen',
  PRIMARY KEY (`id_interessen`),
  KEY `idx_parlam` (`id_parlam`),
  KEY `idx_lobbytyp` (`id_lobbytyp`),
  KEY `idx_lobbygroup` (`id_lobbygroup`),
  KEY `idx_lobbyorg` (`id_lobbyorg`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Eidgenössisches Parlament. Deklarierte Interessenbindungen' AUTO_INCREMENT=337 ;

--
-- RELATIONEN DER TABELLE `interessenbindungen`:
--   `id_lobbygroup`
--       `lobbygruppen` -> `id_lobbygroup`
--   `id_lobbyorg`
--       `lobbyorganisationen` -> `id_lobbyorg`
--   `id_lobbytyp`
--       `lobbytypen` -> `id_lobbytyp`
--   `id_parlam`
--       `parlamentarier` -> `id_parlam`
--

--
-- TRUNCATE Tabelle vor dem Einfügen `interessenbindungen`
--

TRUNCATE TABLE `interessenbindungen`;
--
-- Daten für Tabelle `interessenbindungen`
--

INSERT INTO `interessenbindungen` (`id_interessen`, `ib_description`, `ib_status`, `id_parlam`, `id_lobbytyp`, `id_lobbygroup`, `id_lobbyorg`) VALUES
(1, 'Robinvest AG, Männedorf AG  VR  P', 'd', 1, 9, NULL, 2),
(2, 'Bächtold Stiftung, Wilchingen  Stift. Sr.  M', 'd', 1, 8, NULL, 3),
(3, 'Schweizer Musikinsel Rheinau, Männedorf Stift. Sr.   P', 'd', 1, 8, NULL, 4),
(4, 'Fun & Business AG, Egerkingen ,VR ,Mitglied', 'd', 2, 12, NULL, 5),
(5, 'Bosec Consulting GmbH, Kestenholz,VR, Präsident', 'd', 2, 9, NULL, 6),
(6, 'Neu Bechburg, Oensingen, Stift.  SR, Mitglied', 'd', 2, 7, NULL, 7),
(7, 'Automobil Club der Schweiz (ACS) Mitte, Aarau, V, Mitglied', 'd', 2, 5, NULL, 8),
(8, 'Industrie- und Handelsverein Thal-Gäu-Bipperamt, Oensingen ,V,M', 'd', 2, 9, NULL, 9),
(9, 'Groupe de réflexion santé, Groupe Mutuel  , Beirat ,   M', 'd', 2, 1, 2, 10),
(10, 'Hüppi AG, Zürich,AG ,VR,  M', 'd', 3, 9, NULL, 11),
(11, 'comparis.ch, Zürich,AG,Bei.,M', 'd', 3, 9, NULL, 12),
(13, 'Alfred Flury Stiftung, Stift  Sr. P', 'd', 3, 1, 3, 13),
(14, 'Jugend ohne Drogen Ve.  V P', 'd', 3, 1, 3, 14),
(15, 'Fondazione per la promozione della formazione in medicina di famiglia Stift. SR P', 'd', 4, 1, 4, 15),
(16, 'Associazione aiuto medico al Centro America, Giubiasco Assoc. CD Co-pres.', 'd', 4, 13, NULL, 16),
(17, 'Associazione Scigué Assoc. C M', 'd', 4, 13, NULL, 348),
(18, 'Associazione Svizzera Inquilini Assoc.  - P', 'd', 4, 9, NULL, 17),
(19, 'Movimento dei Senza voce Assoc. C   M', 'd', 4, 2, NULL, 18),
(20, 'Nationale Arbeitsgemeinschaft Suchtpolitik (NAS-CPA), Zofingen Assoc. C P', 'd', 4, 1, 3, 19),
(21, 'Sindacato svizzero dei servizi pubblici (VPOD) Assoc. C   ', 'd', 4, 14, NULL, 21),
(22, 'Equam Stiftung Fond. CF   P', 'd', 5, 1, 4, 20),
(23, 'Forum Managed Care (FMC) Assoc. CD M', 'd', 5, 1, 3, 23),
(24, 'Fourchette Verte Ticino Assoc. CD P', 'd', 5, 12, NULL, 24),
(25, 'Public Health Schweiz Assoc. -  M', 'd', 5, 1, 3, 25),
(26, 'Schweizerischer Verband freier Berufe (SVFB) Assoc. CD VP', 'd', 5, 9, NULL, 26),
(27, 'Swiss Label   Assoc. CD   M', 'd', 5, 9, NULL, 27),
(28, 'Verbindung der Schweizer Ärztinnen und Ärzte (FMH) Assoc. CD VP', 'd', 5, 1, 4, 28),
(29, 'Ente Ospedaliero Cantonale (EOC) - CdA M', 'd', 5, 1, 5, 30),
(30, 'Swiss eHealth Forum (InfoSocietyDays) - - M', 'd', 5, 1, 3, 29),
(32, 'Abbundcenter Nordwestschweiz AG (ANW), Gelterkinden AG VR M', 'd', 6, 9, NULL, 31),
(33, 'Autobus AG Liestal (AAGL), Liestal AG VR M', 'd', 6, 5, NULL, 32),
(34, 'Elektra Baselland (EBL), Liestal Gen. VR M', 'd', 6, 3, NULL, 51),
(35, 'Raiffeisenbank Oberbaselbiet, Gelterkinden Gen. VR  P', 'd', 6, 9, NULL, 52),
(36, 'Erdgas Zürich AG, Altstetten AG VR M', 'd', 7, 3, NULL, 53),
(37, 'Stiftung Kinderschutz Schweiz Stift. Sr. P', 'd', 7, 2, NULL, 54),
(38, 'Stiftung Pestalozzianum Zürich Stift. Sr. M', 'd', 7, 7, NULL, 55),
(39, 'CASTAGNA, Beratungs- und Informationsstelle, Zürich Ve. Bei. M', 'd', 7, 2, NULL, 56),
(40, 'Pflegekinder-Aktion Schweiz, Zürich Ve. V M', 'd', 7, 2, NULL, 57),
(41, 'Pro Familia Schweiz, Bern Ve. V VP', 'd', 7, 2, NULL, 58),
(42, 'Basler Kantonalbank, Basel Anst. Br. M', 'd', 8, 9, NULL, 59),
(43, 'adoro consulting sa, Basel AG VR M', 'd', 8, 9, NULL, 60),
(44, 'aspero AG, Basel AG VR  M', 'd', 8, 9, NULL, 61),
(45, 'Dr. Sebastian Frehner Consulting, Basel EG - Gf.', 'd', 8, 9, NULL, 62),
(46, 'pro rabais.-, Basel Ve. V   M', 'd', 8, 9, NULL, 63),
(47, 'xundart AG, Wil (SG) AG   VR M', 'd', 9, 1, 4, 64),
(48, 'spo Patientenschutz, Zürich Stift. Sr. M', 'd', 9, 1, 6, 65),
(49, 'Stiftung sexuelle Gesundheit Schweiz  Stift. Sr. P', 'd', 9, 1, 3, 66),
(50, 'Gesellschaft Schweiz-Albanien Ve. - Bei.', 'd', 9, 13, NULL, 67),
(51, 'IG öfftentlicher Verkehr Ostschweiz Ve. V M', 'd', 9, 5, NULL, 68),
(52, 'IG pro Stadtbus, Wil (SG)   Ve. V P', 'd', 9, 5, NULL, 69),
(53, 'miva transportiert Hilfe Ve. V M', 'd', 9, 5, NULL, 70),
(54, 'umverkehR   Ve. - Co-Präs.', 'd', 9, 5, NULL, 71),
(55, 'Albert Grütter-Schlatter-Stiftung, Solothurn Stift. Sr. M', 'd', 10, 2, NULL, 72),
(56, 'Keradonum Stiftung Hornhautbank, Olten Stift. V M', 'd', 10, 1, 9, 74),
(57, 'Pro Senectute Kanton Solothurn, Solothurn Stift. Sr.P', 'd', 10, 2, NULL, 73),
(58, 'IGoeV Schweiz, Interessengemeinschaft öffentlicher Verkehr Ve. V P', 'd', 10, 5, NULL, 75),
(59, 'Interessengemeinschaft öffentliche Arbeitsplätze (IGöffA), Olten  Ve. V VP', 'd', 10, 14, NULL, 76),
(60, 'Komitee Pro Eppenberg Ve. ZA Co-Präs', 'd', 10, 5, NULL, 77),
(61, 'Lehrerinnen- und Lehrerverband Kanton Solothurn (LSO) Ve. - M', 'd', 10, 7, NULL, 78),
(62, 'Neue Europäische Bewegung Schweiz (nebs) Ve.  - M', 'd', 10, 13, NULL, 79),
(63, 'Palliative Care Netzwerk Kanton Solothurn, Solothurn Ve. V M', 'd', 10, 1, 4, 80),
(64, 'Pro Natura Schweiz und Solothurn Ve.  - M', 'd', 10, 4, NULL, 81),
(65, 'Pro Vebo / Pro Insos Ve. V M', 'd', 10, 2, NULL, 82),
(66, 'Schweizerischer Eisenbahn- und Verkehrspersonal-Verband (SEV) Ve. - M', 'd', 10, 5, NULL, 83),
(67, 'Spital Club Solothurn Ve. V P', 'd', 10, 1, 5, 84),
(68, 'Spitex Verband Kanton Solothurn Ve. V VP', 'd', 10, 1, 7, 85),
(69, 'Staatspersonalverband Kanton Solothurn Ve. - M', 'd', 10, 14, NULL, 86),
(70, 'Verband kleiner und mittlerer Bauern (VKMB) Ve. - M', 'd', 10, 15, NULL, 87),
(71, 'Vereinigung Solothurnischer Musikschulen, Solothurn Ve. - M', 'd', 10, 8, NULL, 88),
(72, 'Stoll, Hess und Partner AG, Bern AG VR M', 'd', 11, 9, NULL, 89),
(73, 'Gewa, Stiftung für berufliche Integration, Zollikofen   Stift. Sr. M', 'd', 11, 2, NULL, 90),
(74, 'Stiftung Wildstation Landshut, Utzenstorf Stift. Sr. M', 'd', 11, 4, NULL, 91),
(75, 'Berner Jägerverband (BEJV) Ve. V P', 'd', 11, 4, NULL, 92),
(76, 'Verband Bernischer Gemeinden Ve. V  P', 'd', 11, 14, NULL, 93),
(77, 'Aktion für vernünftige Energieplitik Schweiz (AVES)   - V M', 'd', 11, 3, NULL, 94),
(79, 'RehaClinic AG VR  M', 'd', 12, 1, 5, 96),
(80, 'Aargauische Kantonalbank Anst. Br. M', 'd', 12, 9, NULL, 95),
(81, 'Pro Senectute Kanton Aargau (Stiftungsversammlung) Stift. - M', 'd', 12, 2, NULL, 97),
(82, 'Schweizerische Stiftung für Klinische Krebsforschung Stift. Sr. M', 'd', 12, 1, 4, 98),
(83, 'Stiftung OL-Schweiz Stift. Sr. M', 'd', 12, 17, NULL, 99),
(84, 'Theraplus, Stiftung für Therapiebegleitung Stift. Sr. M', 'd', 12, 1, 3, 100),
(85, 'Verein Schweizerisches Netzwerk gesundheitsfördernder Spitäler und Dienste Ve. V P', 'd', 12, 1, 3, 101),
(86, 'Stiftung Brot für Alle Stift.   Sr. M', 'd', 13, 13, NULL, 102),
(87, 'Musikkollegium Winterthur   Ve. V P', 'd', 13, 8, NULL, 103),
(88, 'Vogelschutz / BirdLife Schweiz Ve. V  VP', 'd', 13, 4, NULL, 104),
(89, 'Pro Infirmis TG/SH  Ve.   V M', 'd', 14, 2, NULL, 105),
(90, 'Retraites populaires, Lausanne EtabPubl  CA M', 'd', 15, 9, NULL, 106),
(91, 'Caisse de pensions ECA-RP, Lausanne Fond. CF M', 'd', 15, 9, NULL, 107),
(92, 'EMS Mont-Calme, Lausanne Fond. CF M', 'd', 15, 1, 5, 108),
(93, 'Fondation d''Ethique Familiale, Lausanne Fond. CF P', 'd', 15, 1, 4, 109),
(94, 'ACS Vaud, Lausanne Assoc. C M', 'd', 15, 5, NULL, 110),
(95, 'Etablissement d''assurance contre l''incendie et le éléments naturels (ECA) du Canton de Vaud EtabPubl CA M', 'd', 16, 14, NULL, 111),
(96, 'fenaco, fédération de coopératives agricoles, Berne   SCoop CA VP', 'd', 16, 15, NULL, 113),
(97, 'Société d''agriculture et de laiterie de Bursins-Vinzel, Bursins SCoop C P', 'd', 16, 15, NULL, 112),
(98, 'Association "Relève PME", Paudex Assoc. C M', 'd', 16, 9, NULL, 114),
(99, 'Association du Centre Patronal, Paudex Assoc. - M', 'd', 16, 9, NULL, 115),
(100, 'Fédération Patronale Vaudoise Assoc.   C  M', 'd', 16, 9, NULL, 116),
(101, 'Union intercantonale de réassurance (UIR), Bern Assoc. CA M', 'd', 16, 9, NULL, 117),
(102, 'Raiffeisenbank Menzingen-Neuheim, Menzingen Gen. Vw. P', 'd', 17, 9, NULL, 118),
(103, 'Ausgleichskasse Verom, Schlieren Stift. V VP', 'd', 17, 9, NULL, 119),
(104, 'Agro-Marketing Suisse (AMS), Bern Ve. V VP', 'd', 17, 15, NULL, 120),
(105, 'Internetplattform Swissmip, Koppigen Ve. - P', 'd', 17, 15, NULL, 121),
(106, 'Musée Alpin Suisse, Berne Fond. CF M', 'd', 18, 8, NULL, 122),
(107, 'Oeuvre suisse d''entraide ouvrière (OSEO) Valais, Sion Assoc. C  P', 'd', 18, 13, NULL, 123),
(108, 'Pro Mente Sana Suisse romande, Genève Assoc. C   M', 'd', 18, 1, 3, 124),
(109, 'Universitätsspital Basel, Basel Anst. VR  M', 'd', 19, 1, 5, 125),
(110, 'Schweizerische Pfadistiftung Stift. Sr.  M', 'd', 19, 17, NULL, 126),
(111, 'Compasso - Berufliche Eingliederung - Informationsportal für Arbeitgeber Ve. V M', 'd', 19, 2, NULL, 127),
(112, 'IAMANEH Schweiz Ve. V  P', 'd', 19, 13, NULL, 128),
(113, 'Schweizerische Gesundheitsligen-Konferenz (GELIKO) Ve. V P', 'd', 19, 1, 3, 129),
(114, 'Schweizerischer Verband des Personals öffentliche Dienste (VPOD) Ve. - M', 'd', 19, 14, NULL, 130),
(115, 'WWF Region Basel Ve. V   VP', 'd', 19, 4, NULL, 131),
(116, 'RadioZürisee AG, Stäfa AG  VR M', 'd', 20, 18, NULL, 132),
(117, 'TopPharm Apotheke, Zürich AG VR M', 'd', 20, 1, 1, 133),
(118, 'Schweizerische Greina-Stiftung (SGS) zur erhaltung der alpinen Fliessgewässer, Zürich Stift. Sr. M', 'd', 20, 4, NULL, 134),
(119, 'Schweizerische Sportmittelschule Engelberg Stift. Bei. M', 'd', 20, 17, NULL, 135),
(120, 'Gewerbeverband Stadt Zürich Ve. - M', 'd', 20, 9, NULL, 136),
(121, 'Pro Natura, Basel Ve. - M', 'd', 20, 4, NULL, 137),
(122, 'Schweizer Polizei Informatik Kongress (SPIK) Ve. Bei. M', 'd', 20, 9, NULL, 138),
(123, 'Schweizerisches Rotes Kreuz Kanton Zürich Ve. - P', 'd', 20, 1, 3, 139),
(124, 'Wirtschaftsverband swisscleantech (politischer Beirat) Ve. Bei.  M', 'd', 20, 4, NULL, 140),
(125, 'Zürcher Frauenzentrale   Ve. -  M', 'd', 20, 9, NULL, 141),
(126, 'Fritz Heid AG, Thürnen AG  VR M', 'd', 21, 9, NULL, 142),
(127, 'Kistenfabrik + Holzhandels AG, Thürnen AG VR M', 'd', 21, 9, NULL, 143),
(128, 'Treff 44 AG, Thürnen AG  VR M', 'd', 21, 12, NULL, 144),
(129, 'Gewerbeverein Sissach und Umgebung Ve. V P', 'd', 21, 9, NULL, 145),
(130, 'Hauseigentümerverband (HEV) Sissach/Läufelfingen Ve. V M', 'd', 21, 9, NULL, 146),
(131, 'Liga Baselbieter Steuerzahler, Liestal  Ve. V  M', 'd', 21, 14, NULL, 147),
(132, 'KGIV der Wirtschaftskammer Basel-Landschaft, Liestal  - - M', 'd', 21, 9, NULL, 148),
(133, 'Wirtschaftsrat der Wirtschaftskammer Basel-Landschaft, Liestal   - -  M', 'd', 21, 9, NULL, 149),
(134, 'LifeWatch AG Neuhausen AG  VR M', 'd', 22, 1, 9, 150),
(135, 'tracker.ch AG  Bei. M', 'd', 22, 9, NULL, 151),
(136, 'Vergabungskommission der Young Kickers Foundation (beiratsähnliches Gremium) Komm. - M', 'd', 22, 17, NULL, 152),
(137, 'Stiftung Sportmuseum Schweiz, Basel  Stift. Sr. M', 'd', 22, 17, NULL, 153),
(138, 'FH SCHWEIZ – Dachverband Absolventinnen und Absolventen Fachhochschulen Ve. Bei. M', 'd', 22, 7, NULL, 154),
(139, 'Groupe Mutuel, Martigny  Ve. GL -', 'd', 22, 1, 2, 155),
(140, 'Schweizerischer Turnverband (STV), Aarau Ve. ZV M', 'd', 22, 17, NULL, 156),
(141, 'Swiss Olympic Verband, Ittigen (Exekutivrat) Ve. - M', 'd', 22, 17, NULL, 157),
(142, 'Arbeitsgruppe Gesundheitswesen, VIPS - - M', 'd', 22, 1, 3, 158),
(143, 'Groupe de réflexion santé, Groupe Mutuel - Bei. M', 'd', 22, 1, 2, 10),
(144, 'Lions Club, Winterthur - -   M', 'd', 22, 9, NULL, 159),
(145, 'Swisscup, Aarau - - P', 'd', 22, 17, NULL, 160),
(146, 'Berner Fachhochschule, Fachbereich Gesundheit Anst. - Bei.', 'd', 23, 1, NULL, 161),
(147, 'Kompetenzzentrum Sexualpädagogik und Schule Körp. Bei. M', 'd', 23, 7, NULL, 162),
(148, 'Mobilitäts Akademie AG Bei. M', 'd', 23, 5, NULL, 163),
(149, 'Centre de formation continue pour adultes handicapés, Fribourg Stift. Sr. P', 'd', 23, 7, NULL, 164),
(150, 'Fondation Le Tremplin, Fribourg Stift. V   M', 'd', 23, 1, 3, 165),
(151, 'Fondation Les Buissonnets Stift. Sr. P', 'd', 23, 1, 3, 166),
(152, 'Forschungsfonds der Universität Freiburg Stift. Sr. M', 'd', 23, 6, NULL, 167),
(153, 'Schweizerische Greina-Stiftung (SGS) zur Erhaltung der alpinen Fliessgewässer, Zürich Stift. Sr. M', 'd', 23, 4, NULL, 134),
(154, 'Schweizerisches Alpines Museum, Bern Stift. Pat. M', 'd', 23, 8, NULL, 122),
(155, 'Stiftung Schweiz Mobil Stift. Pat. M', 'd', 23, 5, NULL, 168),
(156, 'Stiftung Umweltbildung Schweiz Stift. A P', 'd', 23, 4, NULL, 169),
(157, 'Association des Amis du Conservatoire de Fribourg Ve. V P', 'd', 23, 8, NULL, 170),
(158, 'Dachverband Komplementärmedizin Schweiz Ve. V  M', 'd', 23, 1, 4, 171),
(159, 'Dachverband Schweizerischer Patientenstellen, Zürich Ve. V VP', 'd', 23, 1, 6, 172),
(160, 'Denknetz Schweiz Ve. V   M', 'd', 23, 6, NULL, 173),
(161, 'FH Schweiz - Dachverband Absolventinnen und Absolventen Fachhochschulen Ve. Bei. M', 'd', 23, 7, NULL, 154),
(162, 'Freiburger Gesundheitsligen Ve. V VP', 'd', 23, 1, 6, 174),
(163, 'Krebsliga des Kantons Freiburg / Ligue fribourgeoise contre le cancer Ve. V P', 'd', 23, 1, 6, 175),
(164, 'Nationale Informationsstelle für Kulturgüter-Erhaltung NIKE Ve. V M', 'd', 23, 8, NULL, 176),
(165, 'OUESTRAIL  Ve. A M', 'd', 23, 5, NULL, 177),
(166, 'Patientenstelle Freiburg-Westschweiz, Freiburg Ve.   V P', 'd', 23, 1, 6, 178),
(167, 'Pro Velo Schweiz Ve. V   P', 'd', 23, 5, NULL, 179),
(168, 'Schweizerische Alzheimervereinigung  Ve. V M', 'd', 23, 1, 3, 180),
(169, 'Schweizerische Gesellschaft für Gesundheitspolitik   Ve. V P', 'd', 23, 1, 3, 181),
(170, 'Schweizerische Vereinigung Hochspannung unter den Boden (HSUB) Ve. V P', 'd', 23, 3, NULL, 182),
(171, 'Büro Harmos der schweizerischen Erziehungsdirektorenkonferenz - Bei. M', 'd', 23, 7, NULL, 183),
(172, 'Kompetenzzentrum Sexualpädagogik der PH Luzern   - Bei. M', 'd', 23, 7, NULL, 185),
(173, 'Partners in Learning Leadership Forum  - - Bei.', 'd', 23, 7, NULL, 184),
(174, 'Swiss Cochrane working group - - Bei', 'd', 23, 1, 3, 186),
(175, 'Alliance "Non au nucléaire" , Zürich Assoc. C VP', 'd', 24, 3, NULL, 187),
(176, 'Sortir du nucléaire, Lausanne (Porte-parole) Assoc.  C M', 'd', 24, 3, NULL, 188),
(177, 'Beratende Komm. EAWAG (Eidg. Anstalt für Wasserversorg., Abwasserreinig. und Gewässerschutz) Komm. - M', 'd', 25, 4, NULL, 189),
(178, 'Allianz "Ja zur Initiative für den OeV" Ve. V M', 'd', 25, 5, NULL, 190),
(179, 'Aqua Viva, Schweizerische Aktionsgemeinschaft zum Schutze der Flüsse und Seen Ve. V  P', 'd', 25, 4, NULL, 191),
(180, 'Gesellschaft für das Weinbaumuseum am Zürichsee Ve. V M', 'd', 25, 8, NULL, 192),
(181, 'Rheinaubund, Schweizerische Arbeitsgemeinschaft für Natur und Heimat Ve. V Co-Präs.', 'd', 25, 4, NULL, 193),
(182, 'Schweizer Kaderorganisation SKO  Ve. V P', 'd', 25, 9, NULL, 194),
(183, 'Spitex Verband Kanton Zürich Ve. V M', 'd', 25, 1, 7, 195),
(184, 'Verein für Ingenieurbiologie Ve. V M', 'd', 25, 9, NULL, 196),
(185, 'Fachhochschulrat Nordwestschweiz (FHNW), Brugg Anst. - M', 'd', 26, 7, NULL, 197),
(186, 'Energiedienst AG (Sachverständiger Beirat), Laufenburg AG - Bei.', 'd', 26, 3, NULL, 198),
(187, 'Regionalplanungsgruppe Rohrdorferberg-Reusstal (Kommission Gemeindeverband) Komm. - P', 'd', 26, 14, NULL, 199),
(188, 'Verteilung Alkoholzehntel im Aargau (Kommission Kanton Aargau) Komm. - P', 'd', 26, 14, NULL, 200),
(189, 'Albert und Ida Nüssli-Stutz Stiftung, Mellingen Stift. Sr. P', 'd', 26, 8, NULL, 201),
(190, 'Ballenberg - Schweizerisches Freilichtmuseum für ländliche Kultur, Brienz Stift. Sr. M', 'd', 26, 8, NULL, 202),
(191, 'REHA Rheinfelden Stift. Sr. M', 'd', 26, 1, 5, 203),
(192, 'Schweizerische Stiftung für eine verantwortungsvolle Gentechnik (GEN SUISSE), Bern Stift.  Sr. VP', 'd', 26, 1, 3, 204),
(193, 'Stiftung 3 R   Stift. Sr. P', 'd', 26, 4, NULL, 205),
(194, 'Stiftung Öffentlichkeit und Gesellschaft   Stift. Sr. M', 'd', 26, 18, NULL, 206),
(195, 'Stiftung Regionales Blutspende-Zentrum Stift. Sr. M', 'd', 26, 1, 3, 207),
(196, 'Stiftung Vindonissapark  Stift. Sr. M', 'd', 26, 8, NULL, 208),
(197, 'Technopark Aargau Stift.   Sr. VP', 'd', 26, 9, NULL, 209),
(198, 'Forum Alter und Migration (Verband) Ve. V P', 'd', 26, 2, NULL, 210),
(199, 'Forum Vera   Ve. V M', 'd', 26, 3, NULL, 211),
(200, 'Gönnervereinigung Speranza Ve. V M', 'd', 26, 7, NULL, 212),
(201, 'Hauseigentümerverband (HEV) Sektion Baden/Brugg/Zurzach Ve. V M', 'd', 26, 9, NULL, 213),
(202, 'IG Musikinitiative Ve. - P', 'd', 26, 7, NULL, 214),
(204, 'Schweizer Feuilleton-Dienst Ve. - M', 'd', 27, 18, NULL, 216),
(205, 'Skilift Oberegg-St. Anton, Oberegg AG  VR M', 'd', 27, 12, NULL, 215),
(206, 'Pro Innerrhoden, Appenzell Stift. Sr. M', 'd', 27, 7, NULL, 217),
(207, 'Schweizer Jugend forscht Stift. Sr. M', 'd', 27, 6, NULL, 218),
(208, 'Stiftung Internat Gymnasium Appenzell (Aufsichtsrat) Stift. Sr. M', 'd', 27, 7, NULL, 219),
(209, 'Stiftung Kinderdorf Pestalozzi, Trogen   Stift. Sr. M', 'd', 27, 13, NULL, 220),
(211, 'FH Schweiz - Dachverband Absolventinnen und Absolventen Fachhochschulen - Bei. M', 'd', 27, 7, NULL, 154),
(212, 'Interstaatliche Erwachsenenmatura (Aufsichtsgremium) - - M', 'd', 27, 7, NULL, 221),
(213, 'Beirat Ökologie der Herzog Kull Group  AG Bei. M', 'd', 28, 3, NULL, 222),
(214, 'Herzog Kull Group (HKG) Aarau AG,  VR M', 'd', 28, 3, NULL, 223),
(215, 'Herzog Kull Group (HKG) Baden AG, Baden AG VR  M', 'd', 28, 3, NULL, 223),
(216, 'Herzog Kull Group (HKG) Consulting AG, Aarau AG  VR M', 'd', 28, 3, NULL, 223),
(217, 'seniorweb AG AG VR M', 'd', 28, 2, NULL, 224),
(218, 'Swiss Economy Forum, Advisory Board AG Bei. M', 'd', 28, 9, NULL, 225),
(219, 'Swiss Media Forum, Advisory Board AG Bei. M', 'd', 28, 18, NULL, 226),
(220, 'Stiftung Krebsregister Aargau, Aarau Stift. Sr. M', 'd', 28, 1, 3, 227),
(221, 'Freunde des Zentrums für Demokratie (ZDA), Aarau   Ve. V P', 'd', 28, 14, NULL, 228),
(222, 'Verein Cleantech Aargau  Ve. V P', 'd', 28, 4, NULL, 229),
(223, 'Wirtschaftsverband swisscleantech (politischer Beirat) Ve. Bei. M', 'd', 28, 4, NULL, 140),
(224, 'Advisory Board of the Center for Disability and Integration, Hochschule St. Gallen - Bei. M', 'd', 28, 2, NULL, 230),
(225, 'Institut für Wirtschaftsethik der Universität St. Gallen (Geschäftsleitender Ausschuss) -  A M', 'd', 28, 7, NULL, 231),
(226, 'SOH, Solothurner Spital AG, Solothurn  AG VR P', 'd', 29, 1, 5, 232),
(227, 'Stiftung Moriz und Elsa von Kuffner Stift. Sr. M', 'd', 29, 2, NULL, 233),
(228, 'Axpo Holding AG AG VR  M', 'd', 30, 3, NULL, 234),
(229, 'De Martin AG, Wängi AG   VR P', 'd', 30, 9, NULL, 235),
(230, 'KIBAG HOLDING AG, Zürich AG VR M', 'd', 30, 9, NULL, 236),
(231, 'Kartause Ittingen, Warth   Stift. Sr. P', 'd', 30, 12, NULL, 237),
(232, 'BDO AG (und Gruppengesellschaften)   AG VR  M', 'd', 31, 9, NULL, 238),
(233, 'CSS (und Gruppengesellschaften) AG   VR M', 'd', 31, 1, 2, 239),
(234, 'Emmi Gruppe (inkl. Stiftungen) AG VR P', 'd', 31, 15, NULL, 240),
(235, 'vbl Verkehrsbetriebe Luzern AG (und Gruppengesellschaften) AG VR P', 'd', 31, 5, NULL, 241),
(236, 'Stiftung BioPolis Entlebuch Stift. Sr. M', 'd', 31, 12, NULL, 242),
(237, 'Industrie- und Handelskammer Zentralschweiz Ve. V M', 'd', 31, 9, NULL, 243),
(238, 'Informationsdienst für den öffentlichen Verkehr (LITRA), Bern Ve. V M', 'd', 31, 5, NULL, 244),
(239, 'Kaufmännischer Verband, Luzern Ve.   Bei. M', 'd', 31, 9, NULL, 245),
(240, 'Axa Winterthur AG VR M', 'd', 32, 9, NULL, 246),
(241, 'Osiris Therapeutics Inc., Baltimore, USA AG VR M', 'd', 32, 1, 1, 247),
(242, 'Rahn AG, Zürich AG VR  M', 'd', 32, 1, 9, 248),
(243, 'Fritz-Gerber-Stiftung für begabte junge Menschen, Basel Stift. Sr.   M', 'd', 32, 7, NULL, 249),
(244, 'Sanitas Krankenkasse Stift. VR M', 'd', 32, 1, 2, 250),
(245, 'Stiftung Vita Parcours, Zürich Stift. Sr.  P', 'd', 32, 1, 3, 251),
(246, 'Swiss School of Public Health (SSPH+), Zürich (Schulrat) Stift. Sr. P', 'd', 32, 1, 3, 252),
(247, 'The Jerusalem Foundation, Zürich Stift.  Sr. P', 'd', 32, 8, NULL, 253),
(248, 'Krebsliga des Kantons Zürich Ve. V M', 'd', 32, 1, 3, 254),
(249, 'Arbeitsgruppe Gesundheitswesen, VIPS(Pharmafirmen)  - - M', 'd', 32, 1, 3, 255),
(251, 'St. Galler Stiftung für internationale Studien, St. Gallen SR M', 'd', 33, 7, NULL, 257),
(253, 'Pulita Putzteam GmbH, Reichenburg GmbH V P', 'd', 34, 9, NULL, 259),
(254, 'Schweizerische Stiftung für Klinische Krebsforschung Stift. Sr. M', 'd', 34, 1, 3, 98),
(255, 'Forum Gesundheit Schweiz - - Co-Präs.', 'd', 34, 1, 3, 261),
(256, 'Hôpitaux Universitaires de Genève, Genève EtabPubl CA Secr.', 'd', 35, 1, 5, 260),
(257, 'Association des Amis de la Fondation AGIR, Genève Fond. C M', 'd', 35, 1, 9, 262),
(258, 'ARGOS, Association d''aide aux personnes toxico-dépendantes, Genève Assoc. C P', 'd', 35, 1, 3, 264),
(259, 'Fédération suisse des sages-femmes (FSSF) Assoc. C P', 'd', 35, 1, 4, 263),
(260, 'Mobilité piétonne Suisse   Assoc. CC M', 'd', 35, 5, NULL, 265),
(261, 'Paul Grüninger-Stiftung  Stift. Sr. M', 'd', 36, 14, NULL, 266),
(262, 'Schweizerischer Gewerkschaftsbund  Ve. V P', 'd', 36, 9, NULL, 36),
(263, 'Interkantonale Rückversicherung (IRV), Bern Körp. VR P', 'd', 37, 9, NULL, 117),
(264, 'Editions D + P (Démocrate + Pays) Delémont AG VR   M', 'd', 37, 18, NULL, 267),
(265, 'Fiduconsult  AG VR M', 'd', 37, 9, NULL, 268),
(266, 'groupe-e, Freiburg AG VR M', 'd', 37, 3, NULL, 269),
(267, 'Imprimerie St-Paul, Freiburg AG VR M', 'd', 37, 9, NULL, 270),
(268, 'JPF Holding SA, Bulle AG   VR M', 'd', 37, 9, NULL, 271),
(269, 'Liebherr Machines Bulle SA AG VR M', 'd', 37, 9, NULL, 272),
(270, 'Bäuerliche Bürgschaftsgenossenschaft, Freiburg Gen. VR P', 'd', 37, 15, NULL, 273),
(271, 'Amis de la Fille-Dieu, Romont Ve. V P', 'd', 37, 8, NULL, 274),
(272, 'Cemsuisse, Verband der Schweizerischen Cementindustrie Ve. - P', 'd', 37, 9, NULL, 275),
(273, 'Schweizerische Pfadistiftung Stift. Sr. M', 'd', 38, 17, NULL, 126),
(274, 'Stiftung Comdays (Bieler Kommunikationstage), Biel   Stift. Sr. M', 'd', 38, NULL, NULL, 276),
(275, 'Eidgenössisches Turnfest Biel/Magglingen Ve. V P', 'd', 38, 17, NULL, 277),
(276, 'Jura & Drei-Seen-Land  Ve. V P', 'd', 38, 12, NULL, 278),
(277, 'Neue Helvetische Gesellschaft / Treffpunkt Schweiz Ve. V M', 'd', 38, 7, NULL, 279),
(278, 'Tour de Suisse, Biel Ve. V P', 'd', 38, 17, NULL, 280),
(279, 'Verein PPP (Public Private Partnership) Schweiz, Zürich Ve. V M', 'd', 38, 9, NULL, 281),
(281, 'Schweizerische Wettbewerbsvereinigung, Zürich Ve. V VP', 'n', 1, 9, NULL, 300),
(283, 'Aktion für vernünftige Energieplitik Schweiz (AVES)    - V M', 'n', 2, 3, NULL, 94),
(284, 'Aktion für vernünftige Energieplitik Schweiz (AVES)    - V M', 'n', 3, 3, NULL, 94),
(286, 'Schweizerische Akademie der medizinischen Wissenschaften (SAMW), Basel Stift. M ', 'n', 5, 1, 4, 302),
(287, 'Ricovero Malcantonese Fondazione Giovanni e Giuseppina Rossi, Croglio Stift. SR M', 'n', 5, 2, NULL, 303),
(288, 'Ospedale Malcantonese Fondazione Giuseppe Rossi, Croglio Stift. SR M', 'n', 5, 1, 5, 304),
(289, 'Fondazione Circolo Franchi Liberali e Filarmonica Liberale-Radicale Collina d''Oro, Collina d''Oro Stift. SR M', 'n', 5, 8, NULL, 305),
(290, 'Wohlfahrtsstiftung der Elektra Baselland, Liesta Stift. SR M', 'n', 6, 2, NULL, 306),
(291, 'Politcom, Agentur für politische Kommunikation und Public Affairs, Thomas de Courten, Liestal Einzelfirma Inhaber', 'n', 6, 9, NULL, 307),
(292, 'atelier politique Fehr, Winterthur Einzelfirma Inhaberin ', 'n', 7, 9, NULL, 308),
(293, 'Aktion für vernünftige Energieplitik Schweiz (AVES) V M', 'n', 12, 3, NULL, 94),
(294, 'Profil, Zürich (Pro Infirmis) Stift. SR M', 'n', 14, 1, 3, 309),
(295, 'Charlotte und Hans Haller Stiftung, Zürich Stift. SR M', 'n', 14, 1, 3, 310),
(296, 'Arbeitskreis Sicherheit und Wehrtechnik (asuw) Ve. M ', 'n', 16, 16, NULL, 311),
(297, 'Aktion für vernünftige Energieplitik Schweiz (AVES) Ve. M ', 'd', 16, 3, NULL, 94),
(298, 'Agro-Marketing Suisse (AMS), Bern Ve. V VP', 'n', 17, 15, NULL, 312),
(299, 'Verein Publikationen Spezialkulturen, Wädenswil Ve. M', 'n', 17, 15, NULL, 313),
(300, 'Genossenschaft Studentenhaus ALV, Zürich Gen. Qästor', 'n', 17, 2, NULL, 314),
(301, 'Hauseigentümerverband (HEV) Meilen und Umgebung Ve. M', 'n', 20, 9, NULL, 315),
(302, 'Aktion für vernünftige Energieplitik Schweiz (AVES)    - V M', 'n', 20, 3, NULL, 94),
(303, 'Stiftung Schweizer Volkskultur, Bubikon Stift. SR M', 'n', 26, 8, NULL, 316),
(305, 'Stiftung MyHandicap, Wil (SG) Stift. SR M', 'n', 28, 1, 3, 317),
(306, 'Schweizer Jugend forscht, Bern Stift. SR M', 'n', 28, 7, NULL, 218),
(307, 'HRS Holding AG, Frauenfeld AG VR P', 'n', 30, 9, NULL, 318),
(308, 'SCHMOLZ+BICKENBACH AG, Emmen AG VR M', 'n', 30, 9, NULL, 319),
(309, 'Spital Thurgau AG, Frauenfeld AG VR M', 'n', 30, 1, 5, 320),
(310, 'Roland Eberle Mercanda Consulting, Frauenfeld Einzelfirma Inhaber', 'n', 30, 9, NULL, 321),
(312, 'Careum Stiftung, Zürich Stift. SR M', 'n', 32, 1, 3, 322),
(313, 'AMIS Plus Stiftung, Zürich Stift. SR M', 'n', 32, 1, 4, 323),
(314, 'Dr. med. Ernst und Fanny Bachmann-Huber-Stiftung, Zürich Stift. SR M', 'n', 32, 7, NULL, 324),
(315, 'Foundation National Institute for Cancer Epidemiology and Registration (NICER), Zürich Stift. SR M', 'n', 32, 1, 4, 325),
(316, 'Pestalozzi-Stiftung für die Förderung der  Ausbildung Jugendlicher aus schweiz. Berggegenden, Zürich Stift. SR M', 'n', 32, 7, NULL, 326),
(317, 'Schweizerische Herzstiftung, Bern', 'n', 32, 1, 4, 327),
(318, 'Stiftung für Lungendiagnostik, Zürich Stift. SR M', 'n', 32, 1, 4, 328),
(319, 'Stiftung für Sucht- und Gesundheitsforschung, Zürich Stift. SR M', 'n', 32, 1, 4, 329),
(320, 'Aktion für vernünftige Energieplitik Schweiz (AVES) Ve. M', 'n', 32, 3, NULL, 94),
(321, 'Stiftung Walter Honegger zur Förderung der Krebsforschung, Zürich Stift. SR M', 'n', 32, 1, 4, 330),
(322, 'Verein "Forum Zürcher Gespräche", Zürich Ve. V M', 'n', 32, 7, NULL, 331),
(323, 'Adimosa  AG VR M', 'd', 33, 9, NULL, 332),
(325, 'NZZ-Mediengruppe AG, Zürich AG VR  M', 'd', 33, 18, NULL, 333),
(326, 'ASGA Pensionskasse, St. Gallen Gen. VR  M', 'd', 33, 9, NULL, NULL),
(327, 'Pensimo Anlagestiftung Stift. Sr. P', 'd', 33, 9, NULL, 335),
(328, 'Schweizerischer Arbeitgeberverband   Ve. V M', 'd', 33, 9, NULL, 336),
(329, 'Swiss Retail Federation Ve. V M', 'd', 33, 9, NULL, 337),
(330, 'Basler Leben AG, Basel, zeichnungsberechtigt', 'n', 34, 9, NULL, 338),
(331, 'Basler Versicherung AG, Basel, zeichnungsberechtigt', 'n', 34, 9, NULL, 339),
(332, 'SWISSAID, Schweizerische Stiftung für Entwicklungszusammenarbeit, Bern Stift. M', 'n', 37, 13, NULL, 340),
(333, 'Stiftung Allalin, Bern Stift. SR P', 'n', 38, 13, NULL, 341),
(334, 'Keradonum Stiftung Hornhautbank, Olten Stift. V M', 'd', 12, 1, 9, 74),
(335, 'Curaviva Ve. V P', 'n', 5, 2, NULL, 342),
(336, 'Intergenerika Ve. V P', 'n', 6, 1, 1, 343);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kommissionen`
--

CREATE TABLE IF NOT EXISTS `kommissionen` (
  `id_komm` int(11) NOT NULL AUTO_INCREMENT,
  `komm_kurz` varchar(15) NOT NULL,
  `komm_lang` varchar(120) NOT NULL,
  `komm_descript` text NOT NULL,
  PRIMARY KEY (`id_komm`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Liste der Legislativkommissionen' AUTO_INCREMENT=19 ;

--
-- TRUNCATE Tabelle vor dem Einfügen `kommissionen`
--

TRUNCATE TABLE `kommissionen`;
--
-- Daten für Tabelle `kommissionen`
--

INSERT INTO `kommissionen` (`id_komm`, `komm_kurz`, `komm_lang`, `komm_descript`) VALUES
(1, 'SGK-NR', 'Kommissionen für soziale Sicherheit und Gesundheit ', 'Sozialversicherungen;Altersvorsorge  (Arbeitslosenversicherung: nur im Ständerat);\r\nSozialhilfe;\r\nFamiliensozialpolitik;\r\nGesundheitswesen; Gesundheitspolitik; Gesundheitsförderung; Unfall und Krankheitsprävention;\r\nHeilmittel;Betäubungs- und Suchtmittel;\r\nLebensmittel (Schutz der Gesundheit) und Gifte'),
(2, 'SGK-SR', 'Kommissionen für soziale Sicherheit und Gesundheit ', 'Sozialversicherungen;Altersvorsorge  (Arbeitslosenversicherung: nur im Ständerat);\r\nSozialhilfe;\r\nFamiliensozialpolitik;\r\nGesundheitswesen; Gesundheitspolitik; Gesundheitsförderung; Unfall und Krankheitsprävention;\r\nHeilmittel;Betäubungs- und Suchtmittel;\r\nLebensmittel (Schutz der Gesundheit) und Gifte'),
(3, 'UREK-NR', 'Kommissionen für Umwelt, Raumplanung und Energie ', 'Umweltschutz; Klimapolitik und nachhaltige Entwicklung; Naturschutz, Heimatschutz,Gewässerschutz;Raumplanung und -entwicklung;\r\nEnergie und Energieversorung;\r\nWasser- und Forstwirtschaft;\r\nJagd, Fischerei '),
(4, 'UREK-SR', 'Kommissionen für Umwelt, Raumplanung und Energie ', 'Umweltschutz; Klimapolitik und nachhaltige Entwicklung; Naturschutz, Heimatschutz,Gewässerschutz;Raumplanung und -entwicklung;\r\nEnergie und Energieversorung;\r\nWasser- und Forstwirtschaft;\r\nJagd, Fischerei '),
(5, 'KVF-NR', 'Kommissionen für Verkehr und Fernmeldewesen', 'Verkehr (Schiene, Strasse, Zivilluftfahrt, Schifffahrt);\r\nTelekommunikation;\r\nService public (Grundversorgung und Marktregulation);\r\nMedien (Radio, Fernsehen, Internet);\r\nBundesnahe Betriebe (SBB, Post, Swisscom, skyguide, SRG) '),
(6, 'KVF-SR', 'Kommissionen für Verkehr und Fernmeldewesen', 'Verkehr (Schiene, Strasse, Zivilluftfahrt, Schifffahrt);\r\nTelekommunikation;\r\nService public (Grundversorgung und Marktregulation);\r\nMedien (Radio, Fernsehen, Internet);\r\nBundesnahe Betriebe (SBB, Post, Swisscom, skyguide, SRG) '),
(7, 'SiK-NR', 'Sicherheitspolitische Kommissionen ', 'Armee (inkl. Militärische Bauten);\r\nInnere Sicherheit und Sicherheitsverbund;\r\nTerrorismusbekämpfung; Polizeikoordination und polizeiliche Dienstleistungen;\r\nBevölkerungsschutz;\r\nZivildienst;\r\nSicherheits- und Friedenspolitik;\r\nMilitärische und zivile Friedensförderung im Bereich der Sicherheitspolitik;\r\nRüstungspolitik;\r\nWaffen;\r\nAbrüstung und Non- Proliferation;\r\nWirtschaftliche Landesversorgung;\r\nStrategische Führungsausbildung;Krisenmanagement des Bundes '),
(8, 'SiK-SR', 'Sicherheitspolitische Kommissionen ', 'Armee (inkl. Militärische Bauten);\r\nInnere Sicherheit und Sicherheitsverbund;\r\nTerrorismusbekämpfung; Polizeikoordination und polizeiliche Dienstleistungen;\r\nBevölkerungsschutz;\r\nZivildienst;\r\nSicherheits- und Friedenspolitik;\r\nMilitärische und zivile Friedensförderung im Bereich der Sicherheitspolitik;\r\nRüstungspolitik;\r\nWaffen;\r\nAbrüstung und Non- Proliferation;\r\nWirtschaftliche Landesversorgung;\r\nStrategische Führungsausbildung;Krisenmanagement des Bundes '),
(9, 'WBK-NR', 'Kommissionen für Wissenschaft, Bildung und Kultur', 'Wissenschaft;\r\nBildung (Bildungsförderung und –forschung, Aus- und Weiterbildung, usw.);\r\nForschung, Technologie und Innovation (Forschungs- und Innovationsförderung, Technologiefolgeabschätzung, Forschungsethik, usw.);\r\nSprachen und kulturelle Gemeinschaften (Förderung der Mehrsprachigkeit, Verständigung und Austausch, Sprachenfreiheit, ethnische Minderheiten);\r\nKultur und Kultureinrichtungen (inkl. Kulturelle Institutionen, Kulturerbe, Kulturgüterschutz und Kulturgütertransfer);\r\nSport;\r\nGeneration und Gesellschaften;\r\nKinder und Jugend;\r\nGleichstellungsfragen;\r\nTierschutz'),
(10, 'WBK-SR', 'Kommissionen für Wissenschaft, Bildung und Kultur', 'Wissenschaft;\r\nBildung (Bildungsförderung und –forschung, Aus- und Weiterbildung, usw.);\r\nForschung, Technologie und Innovation (Forschungs- und Innovationsförderung, Technologiefolgeabschätzung, Forschungsethik, usw.);\r\nSprachen und kulturelle Gemeinschaften (Förderung der Mehrsprachigkeit, Verständigung und Austausch, Sprachenfreiheit, ethnische Minderheiten);\r\nKultur und Kultureinrichtungen (inkl. Kulturelle Institutionen, Kulturerbe, Kulturgüterschutz und Kulturgütertransfer);\r\nSport;\r\nGeneration und Gesellschaften;\r\nKinder und Jugend;\r\nGleichstellungsfragen;\r\nTierschutz'),
(11, 'WAK-NR', 'Kommissionen für Wirtschaft und Abgaben', 'Volkswirtschaft; Konjunktur- und Währungspolitik;\r\nLandwirtschaft; Gewerbe und Dienstleistungen (u. a. Handel, Finanzwesen, Versicherungen, Tourismus);\r\nSteuern (nationales und internationales Steuerwesen, Zollwesen);\r\nWettbewerb (Binnenmarkt, Preise, Kartelle, unlauterer Wettbewerb u. a. unter den Aspekten der Konsumenteninformation, des Konsumkredits, der technischen Handelshemmnisse, der Produktsicherheit und -qualität sowie des öffentlichen Beschaffungswesens);\r\nFörderung des Wirtschaftsstandortes;\r\nArbeitsmarkt (inkl. Arbeitslosenversicherung, nur im Nationalrat);\r\nGeistiges Eigentum (Patentrecht, Markenschutz usw.) '),
(12, 'WAK-SR', 'Kommissionen für Wirtschaft und Abgaben', 'Volkswirtschaft; Konjunktur- und Währungspolitik;\r\nLandwirtschaft; Gewerbe und Dienstleistungen (u. a. Handel, Finanzwesen, Versicherungen, Tourismus);\r\nSteuern (nationales und internationales Steuerwesen, Zollwesen);\r\nWettbewerb (Binnenmarkt, Preise, Kartelle, unlauterer Wettbewerb u. a. unter den Aspekten der Konsumenteninformation, des Konsumkredits, der technischen Handelshemmnisse, der Produktsicherheit und -qualität sowie des öffentlichen Beschaffungswesens);\r\nFörderung des Wirtschaftsstandortes;\r\nArbeitsmarkt (inkl. Arbeitslosenversicherung, nur im Nationalrat);\r\nGeistiges Eigentum (Patentrecht, Markenschutz usw.) '),
(13, 'SPK-NR', 'Staatspolitische Kommissionen', 'Bundesversammlung und Bundesrat;\r\nGewaltenteilung, Kompetenzverteilung zwischen den Bundesbehörden\r\nBundesverwaltung und Bundespersonal;\r\nBeziehungen zwischen Bund und Kantonen (allg. und institutionelle Fragen, Gewährleistung der kantonalen Verfassungen);\r\nPolitische Rechte;\r\nRolle des Staates bei der Meinungsbildung;\r\nBürgerrecht;\r\nAusweisschriften;\r\nAusländerrecht;\r\nAsylrecht;\r\nDatenschutz;\r\nBundesstatistik;\r\nBeziehungen zwischen Staat und Religion  '),
(14, 'SPK-SR', 'Staatspolitische Kommissionen ', 'Bundesversammlung und Bundesrat;\r\nGewaltenteilung, Kompetenzverteilung zwischen den Bundesbehörden\r\nBundesverwaltung und Bundespersonal;\r\nBeziehungen zwischen Bund und Kantonen (allg. und institutionelle Fragen, Gewährleistung der kantonalen Verfassungen);\r\nPolitische Rechte;\r\nRolle des Staates bei der Meinungsbildung;\r\nBürgerrecht;\r\nAusweisschriften;\r\nAusländerrecht;\r\nAsylrecht;\r\nDatenschutz;\r\nBundesstatistik;\r\nBeziehungen zwischen Staat und Religion  '),
(15, 'RK-NR', 'Kommissionen für Rechtsfragen', 'Zivilgesetzbuch – Obligationenrecht – Zivilprozessordnung;\r\nUnlauterer Wettbewerb (vertragsrechtliche Aspekte, irreführende und vergleichende Werbung, missbräuchliche Allgemeine Geschäftsbedingungen);\r\nBodenrecht;\r\nVerfassungsgerichtsbarkeit;\r\nStrafrecht – Strafprozessordnung – Strafvollzug;\r\nGerichtsbehörden;\r\nGeldwäscherei;\r\nGeldspiele;\r\nEnteignung;\r\nSchuldbetreibung und Konkurs;\r\nInternationales Privatrecht;\r\nImmunität (nur im Ständerat); Amnestien (ausser Steueramnestien)   '),
(16, 'RK-SR', 'Kommissionen für Rechtsfragen', 'Zivilgesetzbuch – Obligationenrecht – Zivilprozessordnung;\r\nUnlauterer Wettbewerb (vertragsrechtliche Aspekte, irreführende und vergleichende Werbung, missbräuchliche Allgemeine Geschäftsbedingungen);\r\nBodenrecht;\r\nVerfassungsgerichtsbarkeit;\r\nStrafrecht – Strafprozessordnung – Strafvollzug;\r\nGerichtsbehörden;\r\nGeldwäscherei;\r\nGeldspiele;\r\nEnteignung;\r\nSchuldbetreibung und Konkurs;\r\nInternationales Privatrecht;\r\nImmunität (nur im Ständerat); Amnestien (ausser Steueramnestien)   '),
(17, 'APK-NR', 'Aussenpolitische Kommissionen ', 'Beziehungen zu anderen Staaten sowie zur Europäischen Union;\r\nBeziehungen zu den internationalen Organisationen und Konferenzen (mit Ausnahme jener parlamentarischen Versammlungen, zu denen eine eigene Delegation besteht [EFTA, OSZE, NATO, usw.]);\r\nEntwicklungszusammenarbeit und Zusammenarbeit mit den osteuropäischen Ländern;\r\nHumanitäre Hilfe;\r\nFörderung der Menschenrechte und zivile Friedensförderung im Bereich der Aussenpolitik;\r\nVölkerrecht;\r\nFreihandelspolitik;\r\nNeutralität;\r\nImageförderung der Schweiz im Ausland;\r\nSitzstaatspolitik;\r\nAuslandschweizer;\r\nDiplomatisches Aussennetz'),
(18, 'APK-SR', 'Aussenpolitische Kommissionen ', 'Beziehungen zu anderen Staaten sowie zur Europäischen Union;\r\nBeziehungen zu den internationalen Organisationen und Konferenzen (mit Ausnahme jener parlamentarischen Versammlungen, zu denen eine eigene Delegation besteht [EFTA, OSZE, NATO, usw.]);\r\nEntwicklungszusammenarbeit und Zusammenarbeit mit den osteuropäischen Ländern;\r\nHumanitäre Hilfe;\r\nFörderung der Menschenrechte und zivile Friedensförderung im Bereich der Aussenpolitik;\r\nVölkerrecht;\r\nFreihandelspolitik;\r\nNeutralität;\r\nImageförderung der Schweiz im Ausland;\r\nSitzstaatspolitik;\r\nAuslandschweizer;\r\nDiplomatisches Aussennetz');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lobbygruppen`
--

CREATE TABLE IF NOT EXISTS `lobbygruppen` (
  `id_lobbygroup` int(11) NOT NULL AUTO_INCREMENT,
  `lg_bezeichnung` varchar(255) NOT NULL,
  `lg_description` text NOT NULL,
  `id_lobbytyp` int(11) NOT NULL,
  PRIMARY KEY (`id_lobbygroup`),
  KEY `idx_lobbytyp` (`id_lobbytyp`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- RELATIONEN DER TABELLE `lobbygruppen`:
--   `id_lobbytyp`
--       `lobbytypen` -> `id_lobbytyp`
--

--
-- TRUNCATE Tabelle vor dem Einfügen `lobbygruppen`
--

TRUNCATE TABLE `lobbygruppen`;
--
-- Daten für Tabelle `lobbygruppen`
--

INSERT INTO `lobbygruppen` (`id_lobbygroup`, `lg_bezeichnung`, `lg_description`, `id_lobbytyp`) VALUES
(1, 'Pharma', 'Medikamentenforschung, Medikamentenvertrieb, Pharmafirmen, Apotheken', 1),
(2, 'Krankenkassen', 'Krankenkassen, Dachorganisationen KK, Unterorganisationen KK', 1),
(3, 'Gesundheit', 'Gesundheitsförderung, Drogenmissbrauch', 1),
(4, 'Arztmedizin', 'Ärzte, Arzmedizin, Berfusorganisationen Ärzte, medizinische Dienstleistungen, Physiotherapie, Hebammen', 1),
(5, 'Spitäler', 'Spitäler, Spitalorganisationen', 1),
(6, 'Patienten', 'Patientenorganisationen, Gesundheitskonsumenten', 1),
(7, 'Spitex', 'Spitex, Spitexverbände und -Organisationen', 1),
(8, 'Öffentliches Gesundheitswesen', 'Gesundheitsdirektoren, Kantone, öffentliche Gesundheitsorganisationen,Gesundheitsfinanzierer', 1),
(9, 'Medizinaltechniken', 'Forschung, Handel, technische Hilfsmittel im Bereich Medizinaltechnik', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lobbyorganisationen`
--

CREATE TABLE IF NOT EXISTS `lobbyorganisationen` (
  `id_lobbyorg` int(11) NOT NULL AUTO_INCREMENT,
  `lobbyname` varchar(255) NOT NULL,
  `lobbydescription` text NOT NULL,
  `lobbyorgtyp` set('EinzelOrganisation','DachOrganisation','MitgliedsOrganisation','LeistungsErbringer','dezidierteLobby') NOT NULL,
  `weblink` varchar(255) NOT NULL,
  `id_lobbytyp` int(11) DEFAULT NULL COMMENT 'FK lobbytyp',
  `id_lobbygroup` int(11) DEFAULT NULL COMMENT 'FK von lobbygruppen',
  `vernehmlassung` enum('immer','punktuell','nie') NOT NULL,
  `parlam_verbindung` set('einzel','mehrere','mitglied','exekutiv','kommission') NOT NULL,
  PRIMARY KEY (`id_lobbyorg`),
  KEY `idx_lobbytyp` (`id_lobbytyp`),
  KEY `idx_lobbygroup` (`id_lobbygroup`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Lobbyorganisationen: Beschreibung' AUTO_INCREMENT=349 ;

--
-- RELATIONEN DER TABELLE `lobbyorganisationen`:
--   `id_lobbygroup`
--       `lobbygruppen` -> `id_lobbygroup`
--   `id_lobbytyp`
--       `lobbytypen` -> `id_lobbytyp`
--

--
-- TRUNCATE Tabelle vor dem Einfügen `lobbyorganisationen`
--

TRUNCATE TABLE `lobbyorganisationen`;
--
-- Daten für Tabelle `lobbyorganisationen`
--

INSERT INTO `lobbyorganisationen` (`id_lobbyorg`, `lobbyname`, `lobbydescription`, `lobbyorgtyp`, `weblink`, `id_lobbytyp`, `id_lobbygroup`, `vernehmlassung`, `parlam_verbindung`) VALUES
(2, 'Robinvest AG, Männedorf ', 'Die Gesellschaft bezweckt Beratung, Erbringen von Dienstleistungen im Bereiche Unternehmensführung und Durchführung von Finanzgeschäften. Sie kann sämtliche Geschäfte tätigen, welche mit dem Gesellschaftszweck direkt oder indirekt in Zusammenhang stehen und sich auch an anderen Unternehmen beteiligen. Die Gesellschaft kann überdies Liegenschaften erwerben, verwalten und veräussern.', 'EinzelOrganisation', 'http://www.monetas.ch/htm/647/de/Firmendaten-ROBINVEST-AG.htm?subj=2050309', 9, NULL, 'nie', 'einzel,exekutiv'),
(3, 'Bächtold Stiftung, Wilchingen', 'Mithilfe bei der Finanzierung der Neuauflagen vergriffener Albert-Bächthold-Bücher        \r\n', 'EinzelOrganisation', 'http://www.moneyhouse.ch/fr/u/albert_bachtold_stiftung_CH-290.7.001.147-0.htm', 8, NULL, 'nie', 'einzel,exekutiv'),
(4, 'Schweizer Musikinsel Rheinau, Männedorf Stift.', 'Die Stiftung bezweckt: a) die Förderung des musikalischen Schaffens von Jugendlichen und Erwachsenen; b) die Mithilfe bei der Entwicklung der musikalischen und künstlerischen Fähigkeiten; c) die Bereitstellung geeigneter Infrastruktur als Übungsplattform für Konzertvorbereitungen, musikalische Wettbewerbe, künstlerischen Unterricht, Symposien und Seminarien. Zur Umsetzung des Stiftungszweckes betreibt die Stiftung insbesondere ein Zentrum auf der Insel des ehemaligen Klosters Rheinau mit Übungsräumlichkeiten, Unterkünften und Verpflegungsangeboten. Die Stiftung verfolgt keine Erwerbszwecke. Sie ist politisch und konfessionell neutral. Ihre Leistungen sind gemeinnützig.', 'EinzelOrganisation', 'http://www.moneyhouse.ch/u/schweizer_musikinsel_rheinau_CH-020.7.001.632-3.htm', 8, NULL, 'nie', 'einzel,exekutiv'),
(5, 'Fun & Business AG, Egerkingen ', 'Betrieb, Vermietung und Verpachtung jeglicher Art von Betrieben der Freizeitbranche wie Hotels, Restaurants, Bars, Dancings und Discos sowie Betrieb, Verwaltung und Vermietung von Konferenz- und Businessräumen. Kann im In- und Ausland andere Unternehmungen gründen, übernehmen oder sich daran beteiligen, Grundeigentum erwerben, verwalten, vermitteln und veräussern, Zweigniederlassungen errichten sowie alle Geschäfte tätigen und Verträge abschliessen, die geeignet sind, den Zweck der Gesellschaft zu fördern.', 'EinzelOrganisation', 'http://www.moneyhouse.ch/fr/u/fun_business_ag_CH-240.3.000.561-8.htm', 12, NULL, 'nie', 'einzel,exekutiv'),
(6, 'Bosec Consulting GmbH, Kestenholz', 'Unternehmensberatung sowie Handel mit und Vermittlung von Waren und Dienstleistungen aller Art; kann sich an andern Unternehmen beteiligen oder sich mit diesen zusammenschliessen sowie Grundstücke erwerben, verwalten und veräussern.', 'EinzelOrganisation', 'http://www.moneyhouse.ch/u/bosec_consulting_gmbh_CH-240.4.001.850-9.htm', 9, NULL, 'nie', 'einzel,exekutiv'),
(7, 'Neu Bechburg, Oensingen ', 'Bis 1975 war die Bechburg in Privatbesitz. Mit der Übergabe des Schlosses an eine Stiftung leitet seither ein Stiftungsrat die Geschicke der Burg.\r\nDieser ist vornehmlich aus Vertretern der Einwohner- und Bürgergemeinde Oensingen, dem Kanton Solothurn und dem Bund zusammen-gesetzt.\r\nDie Beschaffung der finanziellen Mittel für Renovation und Organisation der Vermietung sind seine Hauptaufgaben.', 'EinzelOrganisation', 'http://www.neu-bechburg.ch/start.asp', 7, NULL, 'nie', 'einzel,exekutiv'),
(8, 'Automobil Club der Schweiz (ACS) ', 'Der ACS, ein Verein im Sinne des ZGB, gegründet am 6. Dezember 1898 in Genf, bezweckt den Zusammenschluss der Automobilisten zur Wahrung der verkehrspolitischen, wirtschaftlichen, touristischen, sportlichen und aller weiteren mit dem Automobilismus zusammenhängenden Interessen wie Konsumenten- und Umweltschutz. Er widmet der Strassenverkehrs-gesetzgebung und ihrer Anwendung seine besondere Aufmerksamkeit. Er setzt sich ein für die Verkehrssicherheit auf der Strasse.\r\n\r\nDer Gesamtclub setzt sich aus den Mitgliedern seiner insgesamt 20 Sektionen zusammen, welche sich wiederum als selbständige Vereine organisieren.', 'DachOrganisation,LeistungsErbringer', 'http://www.acs.ch/ch-de/ACS-the-club/portrait/index.asp?navid=1', 5, NULL, 'punktuell', 'mehrere,exekutiv'),
(9, 'Industrie- und Handelsverein Thal-Gäu-Bipperamt, Oensingen ', 'Mitglieder des Industrie- und Handelsvereins Thal-Gäu-Bipperamt treten mit ihren Fähigkeiten und ihrer Überzeugung für ein freies Unternehmertum im Rahmen einer sozial-humanen Marktwirtschaft ein.Mitglied von Economie Suisse', 'DachOrganisation,MitgliedsOrganisation,dezidierteLobby', 'http://www.ihv-tgb.ch/index.php?id=18', 9, NULL, 'nie', 'einzel,exekutiv'),
(10, 'Groupe de réflexion santé, Groupe Mutuel  ', 'Unterorganisation des Versichererungsdienstleisters Groupe Mutuel.', 'dezidierteLobby', 'http://www.groupemutuel.ch/content/gm/de/accueil/groupe.html', 1, 2, 'punktuell', 'mehrere,mitglied,kommission'),
(11, 'Hüppi AG, Zürich', 'Der Schwerpunkt unserer Aktivitäten liegt im Bereich der Planung, Entwicklung, Herstellung und Bewirtschaftung von baulicher Infrastruktur.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.hueppi.ch/', 9, NULL, 'nie', 'einzel,exekutiv'),
(12, 'comparis.ch, Zürich', 'comparis.ch – eine Idee mit Erfolg\r\n\r\ncomparis.ch ist der führende Internet-Vergleichsdienst der Schweiz. Konsumenten können auf www.comparis.ch einfach und schnell Tarife und Leistungen von Krankenkassen, Versicherungen, Banken, Telecom-Anbietern, Immobilien, Autos und Motorrädern sowie Aktionen aus dem Detailhandel vergleichen. Dank der Comparis-Vergleiche und -Bewertungen können die Konsumenten direkt zum Anbieter mit dem besten Preis-Leistungs-Verhältnis wechseln.\r\n\r\ncomparis.ch wurde 1996 vom Ökonomen Richard Eisler gegründet. Seine Idee, Konsumenten im Internet einen raschen Prämienvergleich im unübersichtlichen Krankenkassenmarkt zu ermöglichen, hatte Erfolg: Seither haben über 500''000 Personen ihre Krankenkasse über comparis.ch gewechselt. Jedes Jahr sparen die Wechsler dadurch mehr als 300 Millionen Franken. Im März 2010 gewann Comparis die Silbermedaille in der Kategorie „Simply the Best“ des renommierten Schweizer Internet-Preises „Best of Swiss Web“. Anlässlich des 10-jährigen Jubiläums des Internetpreises wurden in dieser Spezialkategorie die besten Schweizer Websiten aller Zeiten juriert.\r\nSeit Juni 2000 ist comparis.ch eine Aktiengesellschaft mit Sitz in Zürich und zählt heute ca. 100 Mitarbeiterinnen und Mitarbeiter. ', 'EinzelOrganisation', 'www.comparis.ch', 9, NULL, 'nie', 'mehrere'),
(13, 'Alfred Flury Stiftung', 'Die Alfred Flury-Stiftung hat sich als politisch und konfessionell nicht gebundene Non-Profit-Organisation zum Ziel gesetzt, durch\r\n• Aufklärung\r\n• Information\r\n• Aktion\r\nzum Schutz unserer Jugendlichen und Kinder vor jugendgefährdenden Einflüssen beizutragen, wie zum Beispiel Drogen- oder Alkoholkonsum.', 'EinzelOrganisation', 'http://www.aktion-nodrugs.ch/', 1, 3, 'nie', 'einzel,exekutiv,kommission'),
(14, 'Jugend ohne Drogen ', 'Zum Verein Jugend ohne Drogen\r\n\r\nDer Verein Jugend ohne Drogen wurde im Januar 1994 in Zürich gegründet und ist ein gesamtschweizerischer Verein. Er nimmt zu aktuellen Fragen der Schweizer Drogenpolitik Stellung\r\n\r\nDer Vereinszweck umfasst die Verbreitung sachgerechter Information, die Förderung von Drogenprävention, die geeignet ist, Kinder und Jugendliche in ihrer Persönlichkeit zu stärken sowie die Förderung von Aktivitäten, die geeignet sind, Kinder und Jugendliche vor Rauschgiften zu schützen und den Entzug, die dauerhafte Entwöhnung Drogenabhängiger und deren Wiedereingliederung in die Gesellschaft zu gewährleisten.Der Verein Jugend ohne Drogen ist Mitglied im ''dachverband drogenabstinenz schweiz / www.drogenabstinenz.ch''. ', 'MitgliedsOrganisation', 'http://www.jod.ch/', 1, 3, 'nie', 'mehrere,exekutiv,kommission'),
(15, 'Fondazione per la promozione della formazione in medicina di famiglia ', 'Über uns\r\nDie gemeinnützige Stiftung zur Förderung der Weiterbildung in Hausarztmedizin (WHM) wurde am 13. November 2008 in Bern gegründet. Sie hat den Zweck, Projekte und Massnahmen zu unterstützen, welche die Weiterbildung und damit die Qualität der medizinischen Grundversorgung in Hausarztpraxen nachhaltig fördern, sowie die Kompetenz von angehenden Hausärzten verbessern. Zur Erfüllung dieses Zwecks entwickelt die Stiftung WHM geeignete Konzepte, organisiert auf die hausärztliche Tätigkeit ausgerichtete Weiterbildungsangebote, koordiniert die Weiterbildung in der ambulanten Grundversorgung und unterstützt diese finanziell.\r\n\r\n2009 übernahm die Stiftung WHM das beim Kollegium für Hausarztmedizin (KHM) angesiedelte Programm „Weiterbildung in Hausarztpraxen (Praxisassistenz)". Das KHM hatte das Praxisassistenz-Programm seit 1998 entwickelt und betreut, mit Unterstützung von FMH und VSAO. Im Rahmen des Programms subventioniert die Stiftung WHM die Löhne der Praxisassistenzärzte und organisiert Lehrpraktiker-Kurse sowie Praxisführungs-Kurse für Assistenzärzte. Nebst Administration des eigenen Praxisassistenz-Programms, stellt die Stiftung WHM ihre Dienstleistung im Administrationsbereich auch den kantonalen Programmen zur Verfügung. Weiterhin steht die Stiftung WHM Assistenzärzten und Lehrpraktikern betreffend Fragen im Bereich der Weiterbildung in Hausarztmedizin beratend zur Seite.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.whm-fmf.ch/', 1, 4, 'nie', 'einzel,exekutiv,kommission'),
(16, 'Associazione aiuto medico al Centro America', 'L’associazione di aiuto medico al Centro America AMCA fu creata nell’agosto 1985. Vi aderirono una trentina di medici, alcuni sindacati e varie associazioni terzomondiste. Inizialmente i progetti si limitarono al Nicaragua. Era il momento della massima offensiva dei Contras, finanziati e guidati dalla CIA. AMCA sostiene progetti sociosanitari in Nicaragua, in San Salvador, in Guatemala, nel Chiapas e a Cuba. A Managua, grazie all’azione padrinati, AMCA sostiene una scuola Barrilete de colores con duecento bambini in uno dei quartieri più poveri e permette di curare i bambini di famiglie povere del reparto di oncologia pediatrica La Mascota. ', 'EinzelOrganisation', 'http://www.amca.ch/', 13, NULL, 'nie', 'einzel,exekutiv'),
(17, 'Associazione Svizzera Inquilini ', 'Schweizerioscher Mieterinnen und Mieterverband', 'DachOrganisation', 'http://www.smv-asloca-asi.ch/', 9, NULL, 'punktuell', 'mehrere,exekutiv'),
(18, 'Movimento dei Senza voce ', 'Tessiner Zweig der Bewegung Sans Papier.Chi siamo\r\n\r\nMovimento dei Senza Voce (MdSV) è un’associazione apartitica e aconfessionale costituita ai sensi dell’art.60 e ss. del Codice Civile Svizzero.\r\n\r\nL’associazione ha come scopi quelli di sostenere la creazione e l’attuazione di una politica migratoria rispettosa dei diritti umani, di favorire attivamente la partecipazione dei migranti al tessuto sociale con il conseguimento di tutti i diritti sociali, civili e politici,  di chiedere la regolarizzazione di tutti i sans-papiers residenti in Svizzera, di promuovere l’assistenza giuridica e socio-sanitaria, di rivendicare per ottenere le strutture necessarie per i bisogni fondamentali di tutte le persone, svizzeri e stranieri, senza fissa dimora o con problemi di ordine sociale e di restare attenti alle nuove forme di disagio, discriminazione ed esclusione sociale.\r\n\r\nFondata nel 2001 l’Associazione fa parte del Movimento svizzero dei sans-papiers.', 'MitgliedsOrganisation', 'http://movimentodeisenzavoce.org', 2, NULL, 'nie', 'einzel,exekutiv'),
(19, 'Nationale Arbeitsgemeinschaft Suchtpolitik ', 'Über die NAS-CPA\r\n\r\nDie NAS-CPA wurde 1996 als Plattform für die suchtpolitische Diskussion verschiedener Organisationen gegründet. Mitglieder sind sowohl Organisationen, die sich fachlich mit dem Thema Sucht- und Drogenpolitik auseinandersetzen als auch solche, die in ihrem beruflichen oder gesellschaftlichen Engagement mit der Suchtthematik in Berührung kommen.', 'DachOrganisation,dezidierteLobby', 'http://www.nas-cpa.ch/startseite.html', 1, 3, 'punktuell', 'einzel,exekutiv,kommission'),
(20, 'Equam Stiftung ', 'Standards formulieren, Qualität prüfenund verbessern\r\nDie EQUAM Stiftung ist auf Initiative der folgenden Organisationen entstanden:\r\n\r\n SanaCare AG\r\n Medix Aerzte AG\r\n Stiftung HMO\r\n\r\nDiese drei Managed Care Organisationen verfolgten mit der Gründung der EQUAM Stiftung\r\nfolgende Ziele:\r\n\r\n1.die Struktur-, Prozess- und Ergebnisqualität von HMO-Zentren regelmässig zu prüfen\r\nund messbare Verbesserungen zu fördern;\r\n2.die Qualitätsstandards insbesondere aus ärztlicher Sicht mit Einbezug der Patientenschaft\r\nzu formulieren und ihre Einhaltung durch eine unabhängige Instanz kontrollieren zu lassen;\r\n3.ein entsprechendes Qualitätszertifikat zu schaffen, welches erteilt und auch wieder\r\nentzogen werden kann.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.equam.ch/', 1, 4, 'nie', 'einzel,exekutiv,kommission'),
(21, 'Sindacato svizzero dei servizi pubblici (VPOD)', 'Verband des Personals öffentlicher Dienste (VPOD)im Tessin. Mitgliedsorganisation des Schweizerischen Gewerkschaftsbundes (SGB)', 'DachOrganisation,MitgliedsOrganisation', 'http://www.vpod-ticino.ch/Pagine/Sindacato.php', 14, NULL, 'nie', 'mehrere,exekutiv'),
(22, 'Schweizerische Konferenz der kantonalen Gesundheitsdirektorinnen und -direktoren (GDK)', 'In der GDK sind die für das Gesundheitswesen zuständigen Regierungsmitglieder der Kantone in einem politischen Koordinationsorgan vereinigt. Zweck der Konferenz ist es, die Zusammenarbeit der 26 Kantone sowie zwischen diesen, dem Bund und mit wichtigen Organisationen des Gesundheitswesens zu fördern. Rechtlich und finanziell werden die Konferenz und ihr Zentralsekretariat durch die Kantone getragen.\r\n\r\nDie Entscheide der Konferenz haben für ihre Mitglieder und die Kantone den Stellenwert von Empfehlungen. Eine Ausnahme bilden die Regelungen zur Osteopathie. Die Konferenz ist auch als Gesprächsforum der Gesundheitsdirektorinnen und Gesundheitsdirektoren sowie als Ansprechpartnerin für die Bundesbehörden sowie für zahlreiche nationale Verbände und Institutionen von Bedeutung.', 'DachOrganisation,LeistungsErbringer', 'http://www.gdk-cds.ch/index.php?id=1', 1, 8, 'punktuell', ''),
(23, 'Forum Managed Care (FMC)', 'Das Forum Managed Care ist ein offener Verein für die Diskussion und Verbreitung innovativer Systeme im Schweizer Gesundheitswesen.\r\n\r\nEs vereinigt alle Institutionen und Akteure im Gesundheitswesen, die sich mit der Steuerung der Gesundheitsversorgung in qualitativer und ökonomischer Sicht befassen. Das Forum bildet die massgebliche Plattform für den Erfahrungsaustausch über Instrumente und Konzepte der gesteuerten Gesundheitsversorgung und fördert deren Bekanntheit, Akzeptanz und Verbreitung.  ', 'EinzelOrganisation', 'http://www.fmc.ch/', 1, 3, 'punktuell', 'einzel,exekutiv,kommission'),
(24, 'Fourchette Verte Ticino ', 'Ziele\r\nDie Ziele von Fourchette verte Schweiz beinhalten hauptsächlich die Strategie « Gesundheit für alle » (Gesundheit 21) von der WHO.\r\n\r\nFourchette verte engagiert sich vor allem, wenn es darum geht, Veränderungen beim Essverhalten und des Lebensstils zu fördern, und somit zur Vorbeugung von gewissen Erkrankungen beiträgt (Herz-Kreislauf Erkrankungen, einige Krebsleiden, Krankheiten in Verbindung mit Uebergewicht etc..)', 'MitgliedsOrganisation', 'http://www.fourchetteverte.ch/de/', 1, 3, 'nie', 'einzel,exekutiv,kommission'),
(25, 'Public Health Schweiz ', 'Public Health Schweiz tritt als nationale Dachorganisation für das Wachstum und die Entwicklung von Public Health und deren Umsetzung in die Praxis ein.\r\n \r\nPublic Health Schweiz stützt ihr Handeln auf wissenschaftlicher Basis ab und ist in einem europäischen und weltweiten Netzwerk verankert.\r\n \r\nPublic Health Schweiz fördert als nationales Forum den fachübergreifenden Austausch zwischen den für die Gesundheit der Bevölkerung der Schweiz tätigen Personen und Organisationen: Mit Konferenzen, dem Newsletter, verschiedenen Fachgruppen und spannenden Publikationen.\r\n', 'DachOrganisation,dezidierteLobby', 'http://www.public-health.ch/logicio/pmws/publichealth__home__de.html', 1, 3, 'punktuell', 'einzel,mitglied,kommission'),
(26, 'Schweizerischer Verband freier Berufe (SVFB)', 'Der Schweizerische Verband freier Berufe (SVFB) vertritt als Spitzenverband die gesellschaftspolitisch bedeutsame Gruppe der Angehörigen der freien Berufe sowie deren Standesorganisationen auf nationaler Ebene in allen Bereichen, in welchen gleichgerichtete Interessen bestehen.\r\n\r\nDer SVFB widmet sich in wirtschaftspolitischer Hinsicht der Förderung des Ansehens sowie der Wahrung der Interessen der freien Berufe.', 'DachOrganisation,dezidierteLobby', 'http://www.freieberufe.ch/index.cfm?parents_id=911', 9, NULL, 'punktuell', 'einzel,exekutiv'),
(27, 'Swiss Label  ', 'SWISS LABEL, die Gesellschaft zur Promotion von Schweizer Produkten und Dienstleistungen, ist ein Verein nach Artikel 60ff des Schweizerischen Zivilgesetzbuches. SWISS LABEL befasst sich im weitesten Sinne mit der Marken- und Labelpflege. Ihr Markenzeichen ist die Armbrust. ', 'DachOrganisation,dezidierteLobby', 'http://www.swisslabel.ch/de/', 9, NULL, 'punktuell', 'einzel,exekutiv'),
(28, 'Verbindung der Schweizer Ärztinnen und Ärzte (FMH)', 'Verbindung der Schweizer Ärztinnen und Ärzte FMH\r\n\r\nDie FMH ist der Berufsverband der Schweizer Ärzteschaft und die Dachorganisation der kantonalen und fachspezifischen Ärztegesellschaften. Ihr gehören über 35 000 Mitglieder an – was gut 95% der berufstätigen Ärztinnen und Ärzten der Schweiz entspricht.\r\n\r\n \r\n\r\nAls zentrale Akteurin im Gesundheitswesen setzt sich die FMH für ein qualitativ hochstehendes und finanzierbares Gesundheitssystem ein, auf das sich die Bevölkerung der Schweiz heute und auch in Zukunft verlassen kann. Sie engagiert sich auf politischer Ebene für eine nachhaltige medizinische Versorgung, die sich durch Qualität und Wirtschaftlichkeit auszeichnet. Und sie vertritt als Standesorganisation die Interessen ihrer Mitglieder gegenüber Behörden und Öffentlichkeit.', 'DachOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.fmh.ch/fmh.html', 1, 4, 'punktuell', 'mehrere,exekutiv,kommission'),
(29, 'Swiss eHealth Forum ', 'Über 1‘000 Interessierte aus Wirtschaft, Verwaltung und Gesundheitswesen nehmen jedes Jahr an den InfoSocietyDays in Bern teil. Der Kongress für Anwendungen der Informations- und Kommunikationstechnologien (ICT) behandelt mit den drei Foren Swiss eEconomy Forum, Swiss eGovernment Forum und Swiss eHealth Forum drei wichtige Kernthemen der Informationsgesellschaft. Der Fokus liegt auf Einsatz und Nutzen der ICT für Wirtschaft, Verwaltung und Gesundheitswesen.', 'EinzelOrganisation', 'http://www.infosocietydays.ch/eHealth', 1, 3, 'nie', 'einzel,kommission'),
(30, 'Ente Ospedaliero Cantonale (EOC)', 'Spitalverbund Tessin EOC l''ospedale multisito\r\n\r\nL''Ente Ospedaliero Cantonale si distingue per la qualità e la sicurezza delle cure facendo beneficiare i pazienti dei progressi medici e tecnologici di provata efficacia. Combina armoniosamente eccellenza medica e cure incentrate sulla relazione, ricerca avanzata e formazione di qualità. La multidisciplinarietà dell’offerta sanitaria è un grosso vantaggio per i pazienti che trovano in un’unica struttura specialisti di varie discipline, pronti ad intervenire per qualsiasi situazione.\r\n\r\nL''EOC è composto da 6 istituti:\r\nL''Ospedale Regionale di Lugano con le sedi Civico e Italiano\r\nL''Ospedale Regionale di Bellinzona e Valli con la sede San Giovanni a Bellinzona e le sedi di Faido e Acquarossa\r\nL''Ospedale Regionale di Mendrisio - Beata Vergine\r\nL''Ospedale Regionale di Locarno - la Carità\r\nL’Istituto Oncologico della Svizzera Italiana\r\nLa Clinica di Riabilitazione di Novaggio', 'LeistungsErbringer', 'http://www.eoc.ch/pagina.cfm?menu=2041&pid=2161', 1, 5, 'nie', 'exekutiv,kommission'),
(31, 'Abbundcenter Nordwestschweiz AG (ANW)', 'Willkommen im Kompetenzzentrum der Nordwestschweizer Zimmerei– und Holzbaubranche für innovative Holzbearbeitung. Wir bieten die rationellsten und wirtschaftlichsten Lösungen für Abbundarbeiten in der Nordwestschweiz:', 'EinzelOrganisation,LeistungsErbringer', 'http://www.abbundnw.ch/index.php?nodeId=node46b5eaeea5b18', 9, NULL, 'nie', 'einzel,exekutiv'),
(32, 'Autobus AG Liestal (AAGL), Liestal ', 'Verkehrsverbund ', 'EinzelOrganisation,LeistungsErbringer', 'http://www.aagl.ch/OEffentlicher-Verkehr-Reisen-Dienstleistungen.1.0.html', 5, NULL, 'nie', 'einzel,exekutiv'),
(33, 'Konferenz der Kantonsregierungen', 'Zusammenarbeit horizontal und vertikal fördern\r\n\r\nNach der Volksabstimmung über den EWR im Jahr 1992 suchten die Kantonsregierungen verstärkt nach Wegen, um die interkantonale Zusammenarbeit (horizontaler Föderalismus) und die Zusammenarbeit mit dem Bund (vertikaler Föderalismus) zu verbessern. Dies hat am 8. Oktober 1993 zur Gründung der Konferenz der Kantonsregierungen (KdK-Vereinbarung) geführt.\r\n\r\nZweck der KdK ist, die Zusammenarbeit unter den Kantonen in ihrem Zuständigkeitsbereich zu fördern und in kantonsrelevanten Angelegenheiten des Bundes die erforderliche Koordination und Information der Kantone sicherzustellen, insbesondere in Fragen\r\n\r\n- der Erneuerung und Weiterentwicklung des Föderalismus;\r\n- der Aufgabenteilung zwischen Bund und Kantonen;\r\n- der Willensbildung und Entscheidungsvorbereitung im Bund;\r\n- des Vollzugs von Bundesaufgaben durch die Kantone;\r\n- der Aussen- und Europapolitik.\r\n  \r\n  ', 'DachOrganisation,LeistungsErbringer', 'http://www.kdk.ch/int/kdk/de/kdk.html', 14, NULL, 'immer', ''),
(34, 'Fédération romande des consommateurs (FRC)', 'Konsumenten-Organisation der französischen Schweiz', 'EinzelOrganisation', 'http://frc.ch/', 9, NULL, 'immer', 'einzel'),
(35, 'Stiftung für Konsumentenschutz (SKS)', 'Die Stiftung für Konsumentenschutz setzt sich seit 1964 für die Anliegen der Konsumentinnen und Konsumenten ein. Die Geschäftsstelle befindet sich in Bern.\r\n', 'DachOrganisation', 'http://konsumentenschutz.ch/', 9, NULL, 'immer', 'mehrere,exekutiv'),
(36, 'Schweizerischer Gewerkschaftsbund (SGB/USS)', '', 'DachOrganisation', 'http://www.sgb.ch/', 9, NULL, 'immer', 'einzel,exekutiv'),
(37, 'Schweizerischer Gewerbeverband (SGV/USAM)', 'Der Schweizerische Gewerbeverband sgv vertritt die Interessen der kleinen und mittleren Unternehmen KMU in der Schweiz. Mitglieder des sgv sind die kantonalen Gewerbeverbände, Berufs- und Branchenverbände sowie die Organisationen der Gewerbeförderung.', 'DachOrganisation', 'http://www.sgb.ch/', 9, NULL, 'immer', 'mehrere,exekutiv'),
(38, 'Schweizerischer Arbeitgeberverband (SAGV)', 'Der Schweizerische Arbeitgeberverband ist der Dachverband der schweizerischen Arbeitgeberverbände. Zu seinen Mitgliedern gehören 35 Branchen-Arbeitgeberverbände, rund 40 regionale Arbeitgeberverbände sowie einige Einzelfirmen. Er vertritt rund 100‘000 Unternehmungen mit weit über einer Million Beschäftigten in der Schweiz. ', 'DachOrganisation', 'http://www.arbeitgeber.ch/', 9, NULL, 'immer', 'mehrere,exekutiv'),
(39, 'economiesuisse', 'Der grösste Dachver­band der Schwei­zer Wirt­schaft\r\neconomie­suisse vertritt die In­ter­es­sen der Wirt­schaft im politi­schen Pro­zess und setzt sich für optimale Rah­menbedingun­gen ein. Zu un­se­ren Mit­gliedern zäh­len 100 Bran­chenver­bän­de, 20 kanto­nale Handels­kammern sowie einige Einzelunternehmen. Insge­s­amt vertre­ten wir 100''000 Schweizer Unternehmen aus allen Branchen mit insgesamt 2 Millionen Arbeitsplätzen in der Schweiz. KMU und Grossunternehmen, export- und binnen­markt­orientier­te Betriebe: Im Dachver­band economie­suisse sind sie alle ver­eint.', 'DachOrganisation,dezidierteLobby', 'http://www.economiesuisse.ch/de/Seiten/_default.aspx', 9, NULL, 'immer', 'mehrere,exekutiv'),
(40, 'Konsumentenforum Schweiz (kf)', 'Das kf ist die unabhängige, liberale Schweizer Konsumentenorganisation und versteht sich als Kompetenzzentrum für Fragen des Konsums.\r\nDas kf ist ein Verein nach Art. 60 ff ZGB und Dachverband von eigenständigen Sektionen. Viele schweizerische Organisationen und Verbände sind beim kf Kollektivmitglied. Das kf vertritt so eine halbe Million Konsumentinnen und Konsumenten.', 'DachOrganisation', 'http://www.konsum.ch/ueber-uns', 9, NULL, 'immer', ''),
(41, 'Associazione Consumatrici della Svizzera Italiana (', 'Konsumenten-Organisation der italienischen Schweiz', 'EinzelOrganisation', 'http://www.acsi.ch/index.cfm?sezid=357', 9, NULL, 'immer', ''),
(42, 'Fédération des Entreprises Romandes (FER)', 'Dieser Verband hat im September 2003 die Fédération Romande des Syndicats Patronaux ersetzt. Er schliesst sieben westschweizerische berufliche und zwischenberufliche Verbände zusammen. Er zählt mehr als 35''000 angeschlossene Unternehmen mit Jahresbeiträgen, die 1 Mia 300 Mio übersteigen. ', 'DachOrganisation,dezidierteLobby', 'http://www.fer-valais-avs.ch/organisation/praesentation.php?langId=2&mainId=141', 9, NULL, 'immer', 'einzel,exekutiv'),
(43, 'Schweizerischer Gemeindeverband', 'Der Schweizerische Gemeindeverband (SGV) ist seit 1953 der politische Vertreter der Gemeinden auf Bundesebene. 70% aller Gemeinden sind Mitglied. Der SGV wird von einem 15-köpfigen Vorstand geführt, der sich aus Mitgliedern kommunaler Exekutiven und der eidgenössischen Räte zusammensetzt. Der Verband arbeitet eng mit den kantonalen Gemeindeorganisationen zusammen. Er ist Partner der Fachorganisation Kommunale Infrastruktur und der Schweizerischen Konferenz der Stadt- und Gemeindeschreiber. ', 'DachOrganisation,dezidierteLobby', 'http://www.chgemeinden.ch/de/1-verband/1-Portrait.php?navid=1', 14, NULL, 'immer', ''),
(44, 'Schweizerischer Städteverband (SSV)', 'Der Schweizerische Städteverband ist der Dienstleistungs- und Interessenverband, der die Interessen der Städte und städtischen Gemeinden in der Schweiz vertritt. Damit ist der Städteverband die Stimme der urbanen Schweiz, in der rund drei Viertel der Schweizer Bevölkerung wohnen und 84 % der Wirtschaftsleistung unseres Landes erbracht werden. Der Städteverband wurde 1897 gegründet und zählt heute 125 Mitglieder.', 'DachOrganisation,dezidierteLobby', 'http://staedteverband.ch/de/Info/Stadteverband', 14, NULL, 'immer', 'mehrere,exekutiv'),
(45, 'Schweizerische Arbeitsgemeinschaft für die Berggebiete (SAB)', 'Die SAB ist ein Verein und wurde 1943 gegründet. Mitglieder sind alle Bergkantone, Berggemeinden, landwirtschaftliche und Selbsthilfeorganisationen, Berggebietsregionen (IHG-Regionen) und weitere Körperschaften im Berggebiet sowie zahlreiche Einzelpersonen.\r\n\r\nUnsere wichtigsten Tätigkeiten sind:\r\n\r\n    Politische Interessevertretung für die Berggebiete und ländlichen Räume\r\n    Dienstleistungen für die Berggebiete und ländlichen Räume\r\n    Information der Öffentlichkeit über die Anliegen der Berggebiete und ländlichen Räume', 'DachOrganisation,dezidierteLobby', 'http://www.sab.ch/UEber-Uns.674.0.html', 9, NULL, 'immer', 'mehrere,exekutiv'),
(47, 'Schweiz. Bauernverband (SBV)', '60''000 Bauernfamilien\r\n25 Kantonale Bauernverbände,\r\n60 Dach-, Fachorganisationen\r\nLandwirtschaftskammer (100 Mitglieder)', 'DachOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.sbv-usp.ch/de/wer-wir-sind/organisation/', 15, NULL, 'immer', 'mehrere,exekutiv'),
(48, 'Schweizerische Bankiervereinigung (SBV)', 'Die Schweizerische Bankiervereinigung (SBVg) ist der Spitzenverband des Schweizer Finanzplatzes. Hauptzielsetzung der SBVg ist die Beibehaltung und Förderung optimaler Rahmenbedingungen im In- und Ausland für den Finanzplatz Schweiz.', 'DachOrganisation,dezidierteLobby', 'http://www.swissbanking.org/home/aboutus-link/portrait.htm', 9, NULL, 'punktuell', ''),
(49, 'Kaufmännischer Verband Schweiz (KV Schweiz)', 'Der KV Schweiz ist die grösste schweizerische Berufsorganisation der Angestellten in Büro und Verkauf sowie verwandter Berufe. Auf nationaler Ebene vertritt er die Interessen von rund 55''000 Mitgliedern in 45 Sektionen.\r\n', 'DachOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.kvschweiz.ch/Verband', 9, NULL, 'immer', 'einzel,exekutiv'),
(50, 'Travail.Suisse ', 'Travail.Suisse ist eine Organisation, welche die politischen Interessen der Arbeitnehmenden und Angestellten eigenständig, kompetent und glaubwürdig vertritt. Im Auftrag seiner Mitgliedsverbände nimmt Travail.Suisse Einfluss auf die Entscheidungsprozesse in den für die Arbeitnehmenden wichtigen Politikbereichen.', 'DachOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.travailsuisse.ch/', 9, NULL, 'immer', ''),
(51, 'EBL (Genossenschaft Elektra Baselland)', 'EBL ist ein bedeutendes Unternehmen aus Liestal BL, das mehr als 200 000 Menschen täglich mit Strom, Wärme und mit Dienstleistungen der Telekommunikation sicher bedient.\r\n \r\nUnd das seit über 110 Jahren.\r\n \r\nAls eine privatrechtliche, vollkommen unabhängige Genossenschaft verpflichtet sich das Unternehmen zu verantwortungsbewusstem Handeln für die nachhaltige Entwicklung unserer Umwelt und unserer Gesellschaft.  Durch die konstante Erweiterung der EBL Geschäftsfelder profitieren viele und nicht zuletzt die Kunden der EBL vom einzigartigen Angebot an innovativen und ökologischen Produkten und Dienstleistungen von EBL. ', 'DachOrganisation,LeistungsErbringer', 'http://www.ebl.ch/de/unternehmen/', 3, NULL, 'nie', 'einzel,exekutiv'),
(52, 'Raiffeisenbank Oberbaselbiet, Gelterkinden ', '', 'MitgliedsOrganisation', 'http://www.raiffeisen.ch/raiffeisen/internet/rb0027.nsf/sitzcode/0771/?OpenDocument', 9, NULL, 'nie', 'einzel,exekutiv'),
(53, 'Erdgas Zürich AG, Altstetten ', '', 'EinzelOrganisation,LeistungsErbringer', 'http://www.erdgaszuerich.ch/de/ueber-erdgas-zuerich.html', 3, NULL, 'nie', 'einzel,exekutiv'),
(54, 'Stiftung Kinderschutz Schweiz ', 'Als nationale Stiftung macht sich Kinderschutz Schweiz in allen Landesteilen dafür stark, dass die Kinder unserer Gesellschaft in Würde aufwachsen, ihre Rechte gewahrt werden und ihre Integrität geschützt wird. Als Grundlagen ihrer Arbeit dienen die UNO-Konvention über die Rechte des Kindes, die Bundesverfassung, die Konvention des Europarates zum Schutz der Menschenrechte und Grundfreiheiten sowie die Gesetzgebung von Bund und Kantonen.', 'EinzelOrganisation,dezidierteLobby', 'http://kinderschutz.ch/cmsn/', 2, NULL, 'nie', 'einzel,exekutiv'),
(55, 'Stiftung Pestalozzianum Zürich ', 'Die Stiftung Pestalozzianum ist ein Gemeinschaftswerk des Kantons Zürich, der Pädagogischen Hochschule Zürich und der vorgängigen Stiftung Pestalozzianum. Sie wurde in heutiger Form im Jahr 2003 gegründet. Die Vorgängerstiftung führte seit 1875 das «Pestalozzianum Zürich», ein Institut für Pädagogik, das 2002 in die Pädagogische Hochschule Zürich integriert worden ist.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.pestalozzianum.ch/de/', 7, NULL, 'nie', 'einzel,exekutiv'),
(56, 'CASTAGNA, Beratungs- und Informationsstelle, Zürich', 'Wir beraten weibliche Jugendliche, in der Kindheit sexuell ausgebeutete Frauen, nicht ausbeutende Eltern, Bezugspersonen von Betroffenen, Fachpersonen und Institutionen', 'EinzelOrganisation,LeistungsErbringer', 'http://www.castagna-zh.ch/cms/front_content.php?idcat=2', 2, NULL, 'nie', 'einzel,mitglied'),
(57, 'Pflegekinder-Aktion Schweiz, Zürich ', 'Seit 60 Jahren setzt sich die Pflegekinder-Aktion Schweiz für bessere Entwicklungschancen von Pflegekindern ein.\r\n\r\nIn der Schweiz leben rund 15''000 Pflegekinder. Die meisten kommen aus mehrfach belasteten Familienverhältnissen. Pflegekinder brauchen ein sicheres Zuhause und Erwachsene, auf die sie sich verlassen können.\r\n\r\nDie Pflegekinder-Aktion Schweiz setzt sich in der Öffentlichkeit für die Interessen von Kindern ein, die in einer Pflegefamilie leben, weil ihre Eltern nicht in angemessener Weise für sie sorgen können. Viele von ihnen haben traumatische Erfahrungen gemacht, Angst, Ohnmacht, Vernachlässigung oder Misshandlung erlebt. Mit ihren Aktivitäten will die Pflegekinder-Aktion Schweiz die Entwicklungschancen der Pflegekinder fördern.', 'EinzelOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.pflegekinder.ch/', 2, NULL, 'nie', 'einzel,exekutiv'),
(58, 'Pro Familia Schweiz', 'Wir sind die Dachorganisation von über 40 nationalen Mitgliederorganisationen und von kantonalen Pro Familia Sektionen, die sich für Familien, für Eltern einsetzen. Pro Familia Schweiz (PFS) vernetzt die Familien und Elternorganisationen, die kantonalen und regionalen Pro Familia Sektionen und trägt als Dachverband zur Stärkung der Stellung der Familien in Anerkennung deren Vielfalt bei.\r\n\r\nZweck des Dachverbandes Pro Familia Schweiz ist die Förderung der Familienpolitik in der Schweiz.\r\n\r\nDas Kompetenzzentrum von Pro Familia Schweiz begleitet Unternehmen und die Öffentliche Hand in der Gestaltung der Familienfreundlichkeit. Schwerpunktthema ist die Vereinbarkeit von Beruf und Familie. ', 'DachOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.profamilia.ch/portrait.html', 2, NULL, 'punktuell', 'einzel,exekutiv'),
(59, 'Basler Kantonalbank, Basel ', '', 'MitgliedsOrganisation,LeistungsErbringer', 'http://www.bkb.ch/', 9, NULL, 'nie', 'mitglied'),
(60, 'adoro consulting sa, Basel ', 'Die Gesellschaft bezweckt die Erbringung von Dienstleistungen im Bereich Unternehmensberatung, Treuhand, Steuern, Rechnungswesen und Buchführung, Revision sowie damit zusammenhängende Tätigkeiten. Sie kann Liegenschaften, Beteiligungen und Immaterialgüter erwerben, halten und veräussern sowie alle anderen Geschäfte tätigen, welche geeignet sind, die Entwicklung der Unternehmung zu fördern oder zu erleichtern. Sie kann Finanzierungen für sich und Dritte vornehmen, Darlehen gewähren, sowie Garantien und andere Sicherheiten stellen.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.moneyhouse.ch/u/adoro_consulting_sa_CH-270.3.014.405-3.htm', 9, NULL, 'nie', 'einzel,exekutiv'),
(61, 'aspero AG, Basel ', 'Versicherungen sind unser Metier. Als neutraler und unabhängiger Versicherungsbroker stellen wir uns voll in den Dienst unserer Kunden.\r\n\r\nWir beraten Sie neutral und auf Ihre persönlichen Bedürfnisse zugeschnitten in den Bereichen Krankenversicherungen, Sachversicherungen (wie z.B. Hausrat- und Privathaftpflicht- und Motorfahrzeugversicherungen etc.), Lebensversicherungen, Rechtsschutzversicherungen, Geschäftsversicherungen (wie z.B. Krankentaggeld, Unfallversicherungen etc.). Zudem bieten wir Ihnen auch persönlich zugeschnittene Vorsorgelösungen und Finanzierungen an', 'EinzelOrganisation,LeistungsErbringer', 'http://aspero.ch/', 9, NULL, 'nie', 'einzel,exekutiv'),
(62, 'Sebastian Frehner  Consulting AG', 'Erbringung von Dienstleistungen im Bereich Unternehmensberatung und ', 'EinzelOrganisation,LeistungsErbringer', 'http://www.moneyhouse.ch/u/dr_sebastian_frehner_consulting_CH-270.1.015.557-5.htm', 9, NULL, 'nie', 'einzel,exekutiv'),
(63, 'pro rabais.-, Basel ', '?????????', 'EinzelOrganisation', '', NULL, NULL, '', 'einzel'),
(64, 'xundart AG, Wil ', 'xundart steht für eine neue und optimierte Form der Zusammenarbeit zwischen Ärzteschaft, Krankenkassen und Patienten. Und für Qualität, Effizienz und Kostenbewusstsein. Die Beteiligten arbeiten partnerschaftlich und teilen die Verantwortung.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.xundart.ch/', 1, 3, 'nie', 'einzel,exekutiv,kommission'),
(65, 'Patientenschutz, Zürich (SPO)', 'Die Stiftung SPO Patientenschutz schützt und fördert die Patientenrechte im Gesundheitswesen wie etwa gegenüber Ärzten, Zahnärzten, Krankenkassen.\r\nSie setzt sich ein für Information und Beratung und ermöglicht den Patienten eine aktive, verantwortungsvolle Mitwirkung.', 'LeistungsErbringer,dezidierteLobby', 'http://www.spo.ch/index.php?option=com_frontpage&Itemid=1', 1, 6, 'punktuell', 'einzel,exekutiv,kommission'),
(66, 'Stiftung sexuelle Gesundheit Schweiz    ', '', 'DachOrganisation,LeistungsErbringer', 'http://www.plan-s.ch/spip.php?page=sommaire-de', 1, 3, 'punktuell', 'einzel,exekutiv,kommission'),
(67, 'Gesellschaft Schweiz-Albanien ', 'Wir verstehen uns als Transformationsstelle zwischen Politik, Verwaltung, Wirtschaft, Unternehmen und Investoren in und zwischen den beiden Ländern.\r\nWir stützen uns auf ein umfassendes und dicht gespanntes Netzwerk in der Schweiz und in Albanien.\r\nWir bauen auf die langjährige Erfahrung von Schweizer Institutionen der Entwicklungszusammenarbeit, Schweizer Unternehmen in Albanien und Albanischen Unternehmern und Bürgern in der Schweiz.\r\nWir arbeiten zusammen mit europäischen Unternehmen, die in Albanien, Kosova und Mazedonien bereits tätig sind.\r\nWer zuverlässige Informationen benötigt, Kontakte sucht oder Geschäfte anbahnen will, wendet sich an die Gesellschaft Schweiz-Albanien. ', 'EinzelOrganisation,dezidierteLobby', 'http://www.schweiz-albanien.ch/angebot/', 13, NULL, 'nie', 'einzel,mitglied'),
(68, 'IG öfftentlicher Verkehr Ostschweiz ', 'Die Interessengemeinschaft öffentlicher Verkehr Ostschweiz (IGöV) ist ein politisch neutraler Verein, dessen Mitglieder sich aktiv dafür einsetzen, dass die bestehenden guten Angebote im Bereich des öffentlichen Verkehrs weiter ausgebaut und bestehende Lücken geschlossen werden. Dem Verein gehören Benutzerinnen und Benutzer des öV, Angestellte von Transportunternehmungen und weitere Fachleute des öV sowie Politikerinnen und Politiker der verschiedenen Parteien an.', 'MitgliedsOrganisation,dezidierteLobby', 'http://www.igoev.ch/ostschweiz/', 5, NULL, 'nie', 'mitglied'),
(69, 'IG pro Stadtbus, Wil ', '', 'EinzelOrganisation', 'http://www.wilnet.ch/Default.aspx?Command=PrdtDetail&prdtName=901a9316-be90-4038-820a-51de52e0489f', 5, NULL, 'nie', 'einzel,exekutiv'),
(70, 'miva transportiert Hilfe Ve. V M', 'Als eines der ältesten Hilfswerke der Schweiz setzt sich miva seit 1932 für benachteiligte Menschen im Süden ein. Sie unterstützt die Finanzierung und professionelle Beschaffung von zweckmässigen Transport- und Kommunikationsmitteln für die Selbsthilfe. Berücksichtigt werden Partner an der Basis, die sich für die lokale Entwicklung sowie in sozialen und pastoralen Projekten engagieren. Ihre Aktivitäten basieren auf Nächstenliebe, gelebter Solidarität und Hilfe zur Selbsthilfe. ', 'EinzelOrganisation,LeistungsErbringer', 'http://www.miva.ch/de/ueber-uns/portraet.html', 13, NULL, 'nie', 'einzel,exekutiv'),
(71, 'umverkehR', 'umverkehR (ja, das grosse «R» ist Absicht!) ist eine Umweltorganisation mit gut 6000 Sympatisantinnen und Unterstützern. Unsere Mitglieder stammen grösstenteils aus der Schweiz, der Sitz des Vereins ist Zürich. Auf französisch heissen wir übrigens actif-trafiC und auf italienisch Straffico.', 'EinzelOrganisation,dezidierteLobby', 'http://www.umverkehr.ch/Wer-ist-umverkehR.html', 4, NULL, 'nie', 'mehrere,exekutiv'),
(72, 'Albert Grütter-Schlatter-Stiftung, Solothurn ', 'Unterstützung alleinstehender alter Leute.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.monetas.ch/htm/647/de/Firmendaten-Stiftung-Albert-Gr%C3%BCtter-Schlatter-zur-Unterst%C3%BCtzung-alleinstehender-alter-Leute.htm?subj=1315957', 2, NULL, 'nie', 'einzel,exekutiv'),
(73, 'Pro Senectute Kanton Solothurn', 'Stellen Sie sich den täglichen Herausforderungen auch im Alter. Pro Senectute leistet einen Beitrag, damit Seniorinnen und Senioren selbstbewusst und mit Freude älter werden können.\r\nMit umfassenden Beratungen, direkten Hilfeleistungen und vielseitigen Bildungsangeboten bieten wir älteren Menschen die Möglichkeit, aktiv und unabhängig zu bleiben. Unser Team besteht aus erfahrenen Mitarbeitenden, die ihre Aufgabe verantwortungsbewusst und mit Freude wahrnehmen. Mit ihrem Fachwissen über das Alter unterstützen sie die älteren ', 'MitgliedsOrganisation,LeistungsErbringer', 'http://www.so.pro-senectute.ch/d/index.cfm', 2, NULL, 'nie', 'mehrere,exekutiv'),
(74, 'Keradonum Stiftung Hornhautbank, Olten ', 'Bezweckt, in Olten eine Hornhautbank zu errichten und zu betreiben und alle mit der Hornhautmedizin in Zusammenhang stehenden Tätigkeiten auszuüben oder zu fördern, namentlich akquiriert sie Gewebe für die Augenmedizin, arbeitet diese auf und stellt sie für Transplantationszwecke entsprechend spezialisierten Institutionen zur Verfügung, leistet Aufklärung auf dem Gebiet der Hornhauttransplantation und sensibilisiert die Öffentlichkeit über die Notwendigkeit von Hornhautspenden, fördert die Forschung auf dem Gebiet der Hornhautchirurgie sowie die Zusammenarbeit mit Spitälern, Universitäten sowie Unternehmen im medizinischen und pharmazeutischen Bereich.', 'EinzelOrganisation,dezidierteLobby', 'http://www.monetas.ch/htm/647/de/?subj=2076091', 1, 9, 'nie', 'mehrere,exekutiv,kommission'),
(75, 'IGoeV Schweiz, Interessengemeinschaft öffentlicher Verkehr ', 'Die Interessengemeinschaft öffentlicher Verkehr Schweiz ist eine Kunden- und Fachleuteorganisation, die zu Ziel hat, in der Schweiz auch in Zukunft einen attraktiven öffentlichen Verkehr zu ermöglichen. Das hohe Niveau des öffentlichen Verkehrs in der Schweiz ist zu sichern und soll den Bedürfnissen der Kundschaft entsprechen.\r\n\r\nDie IGöV Schweiz besteht seit den siebziger Jahren.\r\n\r\nAcht regionale Sektionen – nämlich CITraP Genève, CITraP Vaud, IGöV Bern, IGöV Oberaargau, IGöV Zentralschweiz, IGöV Nordwestschweiz, IGöV Ostschweiz und der VöV Zürich setzen sich dafür ein, in ihren Regionen den öffentlichen Verkehr zu stärken. Insgesamt stehen mehr als 2000 Mitglieder hinter dieser Zielsetzung. ', 'EinzelOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.igoev.ch/index.php?page=15', 5, NULL, 'punktuell', 'mehrere,exekutiv'),
(76, 'Interessengemeinschaft öffentliche Arbeitsplätze (IGöffA), Olten    ', 'Der Verein setzt sich für einen attraktiven Service publique bei öffentlichen\r\nDienstleistungen ein, für flächendeckende Erbringung von Dienstleistungen für alle zu\r\nfairen Preisen und Arbeitsbedingungen.\r\nDer Verein fördert innovative Ideen zum Ausbau des Service publique\r\nDer Verein fördert die Bewusstseinsbildung über die Bedeutung der Arbeitsplätze\r\nin öffentlichen Dienstleistungen für die Region Olten und mobilisiert die Region zum\r\nErhalt und Ausbau dieser Arbeitsplätze.', 'EinzelOrganisation,dezidierteLobby', 'http://www.peterschafer.ch/db/daten/igoeffa_mitglieder.pdf', 14, NULL, 'nie', 'einzel,exekutiv'),
(77, 'Komitee Pro Eppenberg ', 'Pro Eppenberg Tunnel', 'EinzelOrganisation,dezidierteLobby', 'http://eppenbergtunnel.ch/', 5, NULL, 'nie', 'einzel,exekutiv'),
(78, 'Lehrerinnen- und Lehrerverband Kanton Solothurn (LSO)', 'Lehrerinnen und Lehrer Kt. Solothurn', 'MitgliedsOrganisation', 'http://www.lso.ch/cms/front_content.php?idcat=67&idart=174&lang=1', 7, NULL, 'nie', 'einzel,exekutiv'),
(79, 'Neue Europäische Bewegung Schweiz (nebs)', 'Unser Ziel: das europäische Stimmrecht erhalten\r\nDie Globalisierung stellt für alle Menschen eine Herausforderung dar. Die Europäerinnen und Europäer begegnen ihr in der Europäischen Union gemeinsam. Damit die Schweiz bei den Entscheidungen, die sie betreffen, mitbestimmen kann, muss sie das europäische Stimmrecht erhalten.\r\n\r\nUnsere Aufgabe: den Beitritt zu möglichst günstigen Bedingungen herbeiführen\r\nUm das europäische Stimmrecht zu erhalten, müssen wir Mitglied der Europäischen Union werden. Die Aufgabe der Nebs besteht darin, die Schweiz so vorzubereiten, dass sie zu optimalen wirtschaftlichen, sozialen und institutionellen Bedingungen Aktivmitglied der EU werden kann. Dieser Prozess muss heute eingeleitet werden. Die Nebs begleitet ihn aktiv.', 'EinzelOrganisation,dezidierteLobby', 'http://www.europa.ch/index.asp?Language=DE&page=page121', 13, NULL, 'punktuell', 'einzel,exekutiv'),
(80, 'Palliative Care Netzwerk Kanton Solothurn', 'Das Netzwerk bezweckt die Vernetzung sowie den Informations- und Erfahrungs-\r\n   \r\naustausch zwischen Personen und Institutionen, die sich im Kanton Solothurn\r\n   \r\nfür Palliative Care “als umfassende ärztliche, pflegerische, soziale, psychologische\r\n    \r\nund spirituelle Begleitung der Kranken und ihrer Angehörigen einsetzen.\r\n   \r\n    \r\n- die Information der Öffentlichkeit, der Fachwelt und der Politik über die\r\n   \r\nAnliegen von Palliative Care.\r\n   \r\n- die Förderung der Weiter- und Fortbildung auf dem Gebiet von Palliative Care.\r\n- die Förderung und Koordination eines bedarfsgerechten Leistungsangebotes.', 'EinzelOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.palliativecare-so.ch/', 1, 4, 'nie', 'einzel,exekutiv,kommission'),
(81, 'Pro Natura Schweiz und Solothurn', 'Pro Natura ist die führende Organisation für Naturschutz in der Schweiz. Als Anwältin und Meinungsmacherin für Naturschutz verteidigt sie engagiert und kompetent die Interessen der Natur und setzt sich für die Förderung und den Erhalt der einheimischen Tier- und Pflanzenwelt ein. Zu den Pioniertaten der 1909 gegründeten Organisation gehört die Schaffung des Schweizerischen Nationalparks. Heute betreut Pro Natura über 600 Naturschutzgebiete und ein Dutzend Naturschutzzentren in der ganzen Schweiz. Mit ihren Sektionen ist Pro Natura in allen Kantonen der Schweiz aktiv.', 'MitgliedsOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.pronatura-so.ch/', 4, NULL, 'nie', 'einzel,mitglied'),
(82, 'Pro Vebo / Pro Insos ', 'Der Zweck der VEBO Genossenschaft ist die Förderung der Eingliederung von Menschen mit Behinderung in unsere Gesellschaft». Die VEBO nimmt ihre vielfältigen sozialen Aufgaben in den folgenden fünf Produktbereichen wahr: Integrationsmassnahmen, Berufliche Massnahmen, Geschützte Arbeitsplätze, Wohnen, Tagesstätten', 'EinzelOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.vebo.ch/de/foerderung-eingliederung.html', 2, NULL, 'nie', 'einzel,exekutiv'),
(83, 'Schweizerischer Eisenbahn- und Verkehrspersonal-Verband (SEV)', 'Die Gewerkschaft des Verkehrspersonals SEV ist die grösste und stärkste Gewerkschaft im Bereich des öffentlichen Verkehrs (öV) und der Touristischen Bahnen. Sie organisiert das Personal in den Branchen Bahn, Bus/Nahverkehr, Schifffahrt, Touristische Bahnen sowie Luftverkehr. ', 'EinzelOrganisation,DachOrganisation,MitgliedsOrganisation,LeistungsErbringer', 'http://www.sev-online.ch/de/der-sev/sev.php', 5, NULL, 'punktuell', 'mehrere,exekutiv'),
(84, 'Spital Club Solothurn ', 'Zusatzversicherungen zur obligatorischen Krankenkasse', 'EinzelOrganisation,LeistungsErbringer', 'https://www.so-h.ch/spitalclub/angebot.html', 1, 5, 'nie', 'einzel,exekutiv,kommission'),
(85, 'Spitex Verband Kanton Solothurn ', 'Der Spitex Verband Kanton Solothurn SVKS ist der Dachverband von 39 gemeinnützigen, lokalen Spitex-Organisationen im Kanton Solothurn.\r\n\r\nEr vertritt als Arbeitgeber- und Fachverband die Interessen seiner Mitgliederorganisationen sowie der Klientinnen und Klienten bei Behörden, Versicherungen, Partnerorganisationen und in der Öffentlichkeit.', 'DachOrganisation,MitgliedsOrganisation,LeistungsErbringer', 'http://www.spitexso.ch/index.cfm/3F6C0897-F8E0-1700-BA518B941B9848FC/', 1, 7, 'nie', 'einzel,exekutiv,kommission'),
(86, 'Staatspersonalverband Kanton Solothurn ', '', 'EinzelOrganisation,LeistungsErbringer', 'http://www.staatspersonal.ch/home/', 14, NULL, 'nie', 'mehrere,exekutiv'),
(87, 'Verband kleiner und mittlerer Bauern (VKMB)', 'Kleinbauern Vereinigung', 'EinzelOrganisation,dezidierteLobby', 'http://www.kleinbauern.ch/index.php', 15, NULL, 'punktuell', 'einzel,mitglied'),
(88, 'Vereinigung Solothurnischer Musikschulen, Solothurn ', 'Die Vereinigung Solothurner Musikschulen umfasst möglichst alle öffentlichen Musikschulen im Kanton Solothurn.\r\nWir streben einen hohen Mitgliederanteil an. ', 'EinzelOrganisation,dezidierteLobby', 'http://www.solothurnermusikschulen.ch/cms/component/option,com_frontpage/Itemid,1/', 8, NULL, 'nie', 'einzel,mitglied'),
(89, 'Stoll, Hess und Partner AG, Bern ', 'Kommunikation, Public Relations', 'LeistungsErbringer', 'http://www.stpag.ch/frameset.html', 9, NULL, 'nie', 'einzel,exekutiv'),
(90, 'Gewa, Stiftung für berufliche Integration, Zollikofen    ', 'Die GEWA Stiftung für berufliche Integration ist ein sozialwirtschaftliches Unternehmen mit dem Ziel, Menschen, die aus psychischen Gründen besonders herausgefordert sind, beruflich zu integrieren. Unser Kernanliegen ist es, Menschen zu befähigen, ihren Platz in der Arbeitswelt zu finden. Um diesen Auftrag wahrzunehmen, vereint die GEWA unter ihrem Dach elf Betriebe verschiedener Branchen.', 'DachOrganisation', 'http://www.gewa.ch/', 2, NULL, 'nie', 'einzel,exekutiv'),
(91, 'Stiftung Wildstation Landshut', 'Wir kümmern uns um die Aufzucht oder die Genesung von schweizer Wildtieren. Die Stiftung Wildstation Landshut in Utzenstorf, Kanton Bern, liegt auf einem 16.000 qm2 grossen Gelände neben dem idyllischen Schloss Landshut.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.wildstation.ch/', 4, NULL, 'nie', 'einzel,exekutiv');
INSERT INTO `lobbyorganisationen` (`id_lobbyorg`, `lobbyname`, `lobbydescription`, `lobbyorgtyp`, `weblink`, `id_lobbytyp`, `id_lobbygroup`, `vernehmlassung`, `parlam_verbindung`) VALUES
(92, 'Berner Jägerverband (BEJV)', 'zur Erhaltung und Förderung der bernischen Patentjagd und einer weidgerechten Jagdausübung. Der Berner Jägerverband verurteilt jegliche Form von Wilderei;\r\nfür eine nachhaltige Bejagung der Wildbestände nach wildbiologischen Gesichtspunkten;\r\nzur Hege der jagdbaren, nichtjagdbaren und gefährdeten freilebenden Tierarten sowie zur Erhaltung und Wiederherstellung deren Lebensräume;', 'EinzelOrganisation,dezidierteLobby', 'http://www.bernerjagd.ch/willkommen-beim-bejv.html', 4, NULL, 'nie', 'einzel,exekutiv'),
(93, 'Verband Bernischer Gemeinden ', '', 'DachOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.begem.ch/index.php', 14, NULL, 'nie', 'einzel,exekutiv'),
(94, 'Aktion für vernünftige Energieplitik Schweiz (AVES) ', 'Die Ziele der AVES sind klar. Die AVES engagiert sich für eine sichere, ausreichende und volkswirtschaftlich optimale Energieversorgung der Schweiz, die gleichzeitig den Schutz von Mensch und Umwelt beachtet. Wir kämpfen für die Versorgungssicherheit in der Schweiz sowohl beim Strom wie bei den übrigen Energieträgern. Mit Nachdruck setzen wir uns dafür ein, dass unsere Gewerbebetriebe und Industrie auch weiterhin kostengünstig mit Energie versorgt werden können, da Hunderttausende von Arbeitsplätzen davon abhängig sind.\r\n\r\nExperimente lehnen wir ab. Den so genannten „Atomausstieg“ betrachten wir als wohlfeile Konzession an eine einseitig informierte Öffentlichkeit. Die ökologischen, wirtschaftlichen und sozialen Folgen wurden weder von der Mehrheit des Bundesrates noch des Parlamentes genügend bedacht. ', 'EinzelOrganisation,dezidierteLobby', 'http://www.aves.ch/ziele.htm', 3, NULL, 'punktuell', 'mehrere,exekutiv'),
(95, 'Aargauische Kantonalbank ', 'Kantonalbank', 'EinzelOrganisation,MitgliedsOrganisation,LeistungsErbringer', 'http://www.akb.ch/', 9, NULL, 'nie', 'einzel,mitglied'),
(96, 'RehaClinic AG ', 'RehaClinic betreibt als Unternehmen einer gemeinnützigen Stiftung an fünf Standorten (Bad Zurzach, Baden, Braunwald, Glarus, Zollikerberg) Rehabilitationskliniken für den stationären und ambulanten Aufenthalt seiner Patientinnen und Patienten sowie an ausgewählten Lagen umfassend ausgestattete ambulante Therapie- und Trainingszentren zur Rehabilitation und Prävention (Lenzburg, Wil, Winterthur, Zug, Zürich Flughafen, Basel).', 'EinzelOrganisation,MitgliedsOrganisation,LeistungsErbringer', 'http://www.reha-clinic.ch/cms/', 1, 5, 'nie', 'einzel,exekutiv,kommission'),
(97, 'Pro Senectute Kanton Aargau ', 'Mitgliedsorganisation von Pro Senectute Schweiz', 'MitgliedsOrganisation,LeistungsErbringer', 'http://www.ag.pro-senectute.ch/d/index.cfm?ID=16', 2, NULL, 'nie', 'einzel,mitglied'),
(98, 'Schweizerische Stiftung für Klinische Krebsforschung ', 'Die klinische Krebsforschung hat zum Ziel, neue Krebstherapien zu erforschen und bestehende Tumortherapien weiterzuentwickeln, um so die Heilungschancen von krebskranken Patienten und Patientinnen zu verbessern. Während die Pharmaindustrie hauptsächlich in der Erforschung und Entwicklung von neuen Medikamenten tätig ist, widmet sich die Klinische Krebsforschung der Frage, wie die Wirkung einer Krebstherapie mit bereits zugelassenen Medikamenten und anderen Therapieformen verbessert werden kann. ', 'EinzelOrganisation,LeistungsErbringer', 'http://www.klinische-krebsforschung.ch/default.htm', 1, 4, 'punktuell', 'mehrere,exekutiv,kommission'),
(99, 'Stiftung OL-Schweiz ', 'Sportorganisation zur Förderung des Orientierungslaufs', 'EinzelOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.stiftungolschweiz.ch/index2.html', 17, NULL, 'nie', 'einzel,exekutiv'),
(100, 'Theraplus, Stiftung für Therapiebegleitung ', 'Ein wesentliches Ziel ist die Verbesserung der Lebensqualität für Langzeitpatienten und ihre Angehörigen und die Entlastung aller in die Therapie involvierten Personen. THERAPLUS will mit ihrem Beratungsangebot die Lücke in Bezug auf Fragen zur individuellen und alltäglichen Lebenssituation von Langzeitpatienten schliessen, sowie eine höhere Eigenkompetenz und Therapietreue durch patientengerechte Information erzielen.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.theraplus.ch/Ueberuns', 1, 6, 'nie', 'einzel,exekutiv,kommission'),
(101, 'Verein Schweizerisches Netzwerk gesundheitsfördernder Spitäler und Dienste ', 'Netzwerk der WHO, welche die Gesundheitsförderung in den Spitälern vorantreiben soll.', 'EinzelOrganisation', 'http://www.healthhospitals.ch/de/hph-mfh-all-in-one/tgkeitsfelder-mainmenu-301.html', 1, 5, 'nie', 'einzel,mitglied,kommission'),
(102, 'Stiftung Brot für Alle ', 'Kirchliches Hilfswerk der Reformierten Kirchen der Schweiz', 'EinzelOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.brotfueralle.ch/en/deutsch/ueber-uns/geschichte/', 13, NULL, 'punktuell', 'einzel,exekutiv'),
(103, 'Musikkollegium Winterthur    ', 'Konzertveranstalter', 'EinzelOrganisation,LeistungsErbringer', 'http://www.musikkollegium.ch/', 8, NULL, 'nie', 'einzel,exekutiv'),
(104, 'Vogelschutz / BirdLife Schweiz ', 'Der Verband der über 61 000 Natur- und Vogelschützerinnen und -schützer in rund 450 lokalen Sektionen und 19 Kantonalverbänden und Landesorganisationen.\r\n\r\nDer SVS ist die schweizerische Naturschutzorganisation mit den Schwerpunkten Naturschutz in der Gemeinde, Schutz der Vögel und ihrer Lebensräume sowie internationale Zusammenarbeit im Naturschutz.\r\n', 'DachOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.birdlife.ch/content/nationale-ebene', 4, NULL, 'punktuell', 'einzel,exekutiv'),
(105, 'Pro Infirmis TG/SH', 'Sektion Pro Infirmis Schweiz. ist die grösste Fachorganisation für behinderte Menschen in der Schweiz. ', 'MitgliedsOrganisation,LeistungsErbringer', 'http://www.proinfirmis.ch/de/pro-infirmis/organisation.html', 2, NULL, 'nie', 'einzel,exekutiv,kommission'),
(106, 'Retraites populaires, Lausanne ', 'Fondée il y a plus de 100 ans, Retraites Populaires cultive des valeurs humaines fortes et un esprit de mutualité. Notre mission: être le spécialiste vaudois de l’assurance vie et de la prévoyance professionnelle. Notre détermination: demeurer toujours ce partenaire proche sur lequel vous pouvez compter. Acteur essentiel en matière de 2e et 3e pilier, comme dans l’immobilier, les prêts, la gestion d''institutions de prévoyance et la gestion de fonds, Retraites Populaires met au service des personnes, des entreprises et des institutions une plateforme de compétences et tout son savoir-faire.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.retraitespopulaires.ch/site-rp/jcms/pr1_9739/le-specialiste-vaudois-de-lassurance-vie-et-de-la-prevoyance-professionnelle', 9, NULL, 'nie', 'einzel,exekutiv'),
(107, 'Caisse de pensions ECA-RP, Lausanne ', 'prévoyance professionnelle en faveur des membres du personnel des Retraites Populaires et de l''Etablissement cantonal d''assurance contre l''incendie et les éléments naturels du canton de Vaud dans le cadre de la LPP et de ses dispositions d''exécution; prémunir ces personnes, ainsi que leurs proches et survivants, contre les conséquences économiques de la vieillesse, du décès et de l''invalidité.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.moneyhouse.ch/u/caisse_de_pensions_eca_rp_CH-550.0.166.762-2.htm', 9, NULL, 'nie', 'einzel,exekutiv'),
(108, 'EMS Mont-Calme, Lausanne ', 'Alters- und Pflegeheim', 'EinzelOrganisation,LeistungsErbringer', 'http://www.montcalme.ch/xml_1/internet/fr/application/d3/f36.cfm', 1, 5, 'nie', 'einzel,exekutiv,kommission'),
(109, 'Fondation d''Ethique Familiale, Lausanne ', 'Centre médico-psychologique spécialisé dans les consultations thérapeutiques et psycho-éducatives pour familles confrontées aux violences domestiques. Cet établissement médical ambulatoire est reconnu par la Santé Publique depuis le 1er janvier 2003. Il a été créé par la Fondation Ethique Familiale, fondée elle-même en 2002 (reconnue d''utilité publique et à but non lucratif, inscrite au registre du commerce, exonérée d''impôts).', 'EinzelOrganisation,LeistungsErbringer', 'http://www.cimi.ch/pages/cimi/def-cimi.htm', 1, 4, 'nie', 'einzel,exekutiv,kommission'),
(110, 'ACS Vaud, Lausanne ', 'Automobilclub der Schweiz, Sektion VD', 'MitgliedsOrganisation,LeistungsErbringer', 'http://www.acs.ch/ch-fr/sektionen/vaudoise/index.asp', 5, NULL, 'nie', 'einzel,exekutiv'),
(111, 'Etablissement d''assurance contre l''incendie et le éléments naturels (ECA) du Canton de Vaud ', 'Elementarschadenversicherung Kt. VD', 'EinzelOrganisation,LeistungsErbringer', 'http://www.eca-vaud.ch/', 14, NULL, 'nie', 'einzel,exekutiv'),
(112, 'Société d''agriculture et de laiterie de Bursins-Vinzel, Bursins ', 'Sauvegarde des intérêts professionnels de ses membres; mise en valeur de leur production laitière; achat ou location et mise à disposition de ses membres de matériel d''équipement et de toute fourniture agricole.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.monetas.ch/htm/647/de/Firmendaten-Soci%C3%A9t%C3%A9-coop%C3%A9rative-de-laiterie-et-d-agriculture-de-Bursins-Vinzel.htm?subj=1502631', 15, NULL, 'nie', 'einzel,exekutiv'),
(113, 'fenaco, fédération de coopératives agricoles, Berne    ', 'Die fenaco versorgt die Landwirte mit Produktionsmitteln, übernimmt deren Erzeugnisse, veredelt diese und vermarktet sie. Zudem betreibt die fenaco die Detailhandelsketten Volg und LANDI und verkauft AGROLA Brenn- und Treibstoffe.\r\n\r\nDie fenaco ist eine genossenschaftlich organisierte, moderne Selbsthilfeunternehmung der Schweizer Bauern. Sie verfolgt das übergeordnete Ziel, eine möglichst hohe Inlandproduktion von Lebensmitteln zu erhalten und dadurch die wirtschaftlichen Verhältnisse der Bauern zu fördern. In verbindlicher Partnerschaft mit den LANDI (landw. Genossenschaften) versorgt sie die Landwirte mit allen Produktionsmitteln (Sämereien, Futtermittel, Pflanzennahrung und anderes mehr) die sie benötigen, um qualitativ hochwertige Lebensmittel produzieren zu können. Gleichzeitig übernimmt die fenaco die Erzeugnisse der Bauern wie Saatgut, Getreide, Ölsaaten, Kartoffeln, Schlachtvieh, Eier, Mais, Gemüse, Obst und Weintrauben, veredelt diese und vermarktet sie. Zudem betreibt die fenaco die Detailhandelsketten Volg, LANDI, TopShop und Visavis verkauft unter der Marke AGROLA Heizöl, Diesel und Benzin.', 'DachOrganisation,LeistungsErbringer', 'http://www.fenaco.com/deu/default.shtml', 15, NULL, 'punktuell', 'einzel,exekutiv'),
(114, 'Association "Relève PME", Paudex ', 'KMU Next ist eine neutrale Stiftung für die Nachfolge in KMU-Betrieben und Mikrounternehmen. Sie verschafft Übergebern und Übernehmern das notwendige Rüstzeug für einen erfolgreichen Handwechsel.\r\n\r\nDie Online-Plattform von KMU Next funktioniert als Drehscheibe für wichtige Informationen zum Nachfolge-Prozess. KMU Next ist zugleich ein soziales Netzwerk, das Übergeber, Übernehmer und Berater miteinander in Kontakt bringt.', 'EinzelOrganisation,LeistungsErbringer', 'https://www.kmunext.ch/kmu/About_KMU_Next.html', 9, NULL, 'nie', 'exekutiv'),
(115, 'Association du Centre Patronal, Paudex ', 'A travers les mandats qui nous sont confiés, ou de par notre action au sein de l’économie, nous sommes en contact avec un grand nombre d''associations professionnelles et de groupements d’intérêts économiques. Vous trouvez ci-après la liste des associations et groupements\r\n\r\n    affiliés à la Fédération patronale vaudoise (FPV),\r\n    affiliés à la Chambre vaudoise des Arts et Métiers (CVAM),\r\n    qui ont confié un mandat au Centre Patronal (CP).\r\n', 'MitgliedsOrganisation', 'http://www.centrepatronal.ch/index.php?page=fr/pratique/associations', 9, NULL, 'nie', 'einzel,exekutiv'),
(116, 'Fédération Patronale Vaudoise ', '    gère des associations, conseille et renseigne les chefs d’entreprises\r\n    défend les intérêts de l’économie privée et du partenariat social\r\n    publie des guides juridiques et d’autres informations liées à l’économie et la politique\r\n    dispose de toutes les solutions pour satisfaire à vos obligations et améliorer votre prévoyance\r\n    propose un large programme de cours de formation continue et de perfectionnement professionnel\r\n    Rejoindre le site en français\r\n', 'DachOrganisation,dezidierteLobby', 'http://www.centrepatronal.ch/', 9, NULL, 'immer', 'einzel,exekutiv'),
(117, 'Union intercantonale de réassurance (UIR), Bern ', 'Übernahme, Vermittlung oder Gewährleistung der gewünschten Rückversicherungsdeckung seiner Mitglieder.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.moneyhouse.ch/u/interkantonaler_ruckversicherungsverband_CH-035.8.018.155-1.htm', 9, NULL, 'nie', 'mehrere,exekutiv'),
(118, 'Raiffeisenbank Menzingen-Neuheim, Menzingen ', '', '', 'http://www.raiffeisen.ch/raiffeisen/internet/rb0027.nsf/sitzcode/1457/?OpenDocument', 9, NULL, 'nie', 'einzel,exekutiv'),
(119, 'Ausgleichskasse Verom, Schlieren ', 'Ausgleichskasse für Pensionen, AHV etc.', 'DachOrganisation,LeistungsErbringer', 'http://www.verom.ch/deutsch/infos/ueber-uns/index.php', 9, NULL, 'nie', 'einzel,exekutiv'),
(120, 'Agro-Marketing Suisse (AMS), Bern ', 'Die AMS Agro-Marketing Suisse ist die Vereinigung der landwirtschaftlichen Branchenorganisationen der Schweiz. Sie fördert durch geeignete Informations- und Marketing-\r\nmassnahmen den Absatz einheimischer Nahrungsmittel.', '', 'http://www.agromarketingsuisse.ch/index.php?id=12&L=0', 15, NULL, 'nie', 'einzel,exekutiv'),
(121, 'Internetplattform Swissmip, Koppigen ', 'Die Tätigkeiten der SZG bezwecken im Bereich der Spezialkulturen (Gemüse, Obst, Schnittblumen etc.) inkl. Kartoffeln, die Förderung einer leistungsfähigen Produktion, die Förderung einer effizienten Marktausrichtung sowie die Unterstützung der Marktregelung.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.szg.ch/ueber-uns/organisation/', 15, NULL, 'nie', ''),
(122, 'Alpines Museum Bern', 'Alpines Museum Bern', '', 'http://www.alpinesmuseum.ch/de', 8, NULL, 'nie', 'einzel,exekutiv'),
(123, 'Oeuvre suisse d''entraide ouvrière (OSEO) Valais', 'Schweizerisches Arbeiterhilfswerk SAH', 'EinzelOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.oseo-vs.ch/oseo-buts.html', 13, NULL, 'punktuell', 'einzel,exekutiv'),
(124, 'Pro Mente Sana Suisse romande, Genève ', 'Pro Mente Sana soutient la cause des personnes qui souffrent d’une maladie ou d’un handicap psychique. Elle lutte pour leur intégration professionnelle et sociale et pour la garantie de leurs droits. Pro Mente Sana conseille, diffuse des informations variées et offre un espace qui permet le dialogue entre personnes concernées, proches et professionnels. Elle vise à promouvoir, dans l’opinion publique, une meilleure compréhension à l’égard des personnes souffrant d’une maladie psychique. Elle est active au niveau national et cantonal. Elle est neutre sur le plan politique et religieux.', 'MitgliedsOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.promentesana.org/wq_pages/fr/association/principes-directeurs.php', 1, 3, 'punktuell', 'einzel,exekutiv,kommission'),
(125, 'Universitätsspital Basel, Basel', '', 'EinzelOrganisation,MitgliedsOrganisation,LeistungsErbringer', 'http://www.unispital-basel.ch/', 1, 5, 'punktuell', 'einzel,exekutiv,kommission'),
(126, 'Schweizerische Pfadistiftung ', 'Die Pfadibewegung Schweiz bietet Kindern und Jugendlichen nicht nur ein attraktives Programm, sondern verfolgt im Rahmen ihrer Aktivitäten auch pädagogische Zielsetzungen: Durch vielfältige Erlebnisse sollen die heranwachsenden Jugendlichen befähigt werden sich ganzheitlich zu entfalten. Abseits der Schule und des Elternhauses erwerben sie Fähigkeiten, welche ihnen erlauben, sich aktiv in der Gesellschaft zu engagieren und ihre Zukunft verantwortungsbewusst zu gestalten. ', 'EinzelOrganisation,LeistungsErbringer', 'http://www5.scout.ch/de/das-ist-pfadi/paedagogisches', 17, NULL, 'nie', 'einzel,exekutiv'),
(127, 'Compasso - Berufliche Eingliederung - Informationsportal für Arbeitgeber ', 'Sie suchen Informationen zum Umgang mit Mitarbeitenden, die eine veränderte Leistungsfähigkeit zeigen? Sie wollen einen Menschen mit Handicap einstellen? Oder Sie haben Fragen zu möglichen Unterstützungsangeboten, wenn Sie Arbeitnehmende beschäftigen, die gesundheitlich beeinträchtigt sind? Willkommen auf dem Informationsportal „compasso“.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.compasso.ch/de/p90000948.html', 2, NULL, 'nie', 'einzel,exekutiv'),
(128, 'IAMANEH Schweiz ', 'IAMANEH Schweiz ist eine Entwicklungsorganisation, die sich für die Verbesserung, die Förderung und den Schutz der Gesundheit einsetzt.\r\n\r\nWir unterstützen Projekte in Westafrika, im Westbalkan und in Haiti.\r\n\r\nZielgruppe sind Frauen und Kinder, die wir dabei unterstützen, ihre Zukunft und Entwicklung eigenständig zu gestalten.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.iamaneh.ch/', 13, NULL, 'nie', 'einzel,exekutiv'),
(129, 'Schweizerische Gesundheitsligen-Konferenz ', 'Die Schweizerische Gesundheitsligen-Konferenz (GELIKO) vertritt die Interessen von Men­schen mit chronischen Krankheiten in der Gesundheits- und Sozialpolitik und kämpft gegen negative gesundheitliche, finanzielle und soziale Folgen von chronischen Krankheiten (Krebs, Rheuma, Diabetes, Lungenkrankheiten, Cystische Fibrose, Herz-Kreislauf-Leiden, HIV/Aids, Allergien etc.). Sie ist der Dachverband der gesamtschweizerisch tätigen gemeinnützigen Organisationen, die sich im Gesundheits- und Sozialwesen für die Prävention spezifischer Krankheiten einsetzen, Betroffene unterstützen oder sich allgemein für Krankheitsprävention und Gesundheitsförderung stark machen. ', 'DachOrganisation,dezidierteLobby', 'http://www.geliko.ch/', 1, 6, 'punktuell', 'einzel,exekutiv,kommission'),
(130, 'Schweizerischer Verband des Personals öffentliche Dienste (VPOD) ', '', 'MitgliedsOrganisation,LeistungsErbringer', 'http://www.vpod.ch/', 14, NULL, 'punktuell', 'mehrere,exekutiv'),
(131, 'WWF Region Basel ', 'Der WWF Region Basel wurde 1974 gegründet. Als eigenständiger Verein und als Sektion des WWF Schweiz für die Kantone Basel-Stadt und Basel-Landschaft ist er Teil des globalen WWF-Netzwerks.\r\nAus der Erkenntnis, dass für die Umwelt oft mehr auf kantonaler als auf eidgenössischer Ebene erreicht werden kann und die Zuständigkeit für die teilweise schleppende Umsetzung nationaler Umweltgesetze bei den Kantonen liegt, wurde die WWF Sektion Region Basel gegründet.', 'MitgliedsOrganisation,LeistungsErbringer', 'http://www.wwf-bs.ch/', 4, NULL, 'nie', 'einzel,exekutiv'),
(132, 'RadioZürisee AG, Stäfa', 'Lokalradio', 'EinzelOrganisation,LeistungsErbringer', 'http://www.zsm.ch/', 18, NULL, 'nie', 'einzel,exekutiv'),
(133, 'TopPharm Apotheke, Zürich ', 'Apotheke', 'EinzelOrganisation,MitgliedsOrganisation,LeistungsErbringer', 'http://www.paradeplatz.apotheke.ch/ueber.html', 1, 1, 'nie', 'einzel,exekutiv,kommission'),
(134, 'Schweizerische Greina-Stiftung (SGS) zur erhaltung der alpinen Fliessgewässer, Zürich ', 'Die Schweizerische Greina-Stiftung hat zum Ziel, sich für die nachhaltige Gestaltung und Aufwertung alpiner Fliessgewässer und Flusslandschaften einzusetzen und dabei insbesondere einer ökologischen Energienutzung ihre besondere Aufmerksamkeit zu schenken. Dieses Oberziel umfasst allgemein und zum Teil auch umfassend die Richtung.', 'EinzelOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.greina-stiftung.ch/', 4, NULL, 'punktuell', 'mehrere,exekutiv'),
(135, 'Schweizerische Sportmittelschule Engelberg ', 'Nationales Leistungszentrum für den alpinen Skisport', 'EinzelOrganisation,LeistungsErbringer', 'http://www.sportmittelschule.ch/', 17, NULL, 'nie', 'einzel,mitglied'),
(136, 'Gewerbeverband Stadt Zürich ', 'Gewerbeverband Stadt Zürich', 'DachOrganisation,MitgliedsOrganisation,dezidierteLobby', 'http://www.gewerbezuerich.ch/web/mitgliedschaft/index.php', 9, NULL, 'nie', 'einzel,exekutiv'),
(137, 'Pro Natura, Basel ', '', 'MitgliedsOrganisation,LeistungsErbringer', 'http://www.pronatura-bs.ch/', 4, NULL, 'nie', 'mitglied'),
(138, 'Schweizer Polizei Informatik Kongress (SPIK) ', 'SPIK ist die nationale Plattform für den Erfahrungsaustausch zu den Themen Polizeiinformatik und Bekämpfung von Cybercrime.\r\n\r\nSPIK richtet sich an Informatiker und Führungskräfte aller Polizeikorps ebenso wie an die IT-Industrie, die Wirtschaft und die Politik. Ziel des jährlich stattfindenden Kongresses ist es, die Involvierten mit neuen Ideen, Entwicklungen und Produkten vertraut zu machen.\r\n\r\nHinter dem Anlass steht der Verein Swiss Police ICT, dem Vertreter verschiedener Polizeikorps und Informatikfirmen angehören.\r\nEin politischer Beirat mit Vertretern der fünf Bundesratsparteien, einem Regierungsrat und einem Polizeikommandanten dient als politisches Konsultativ-Organ und Bindeglied zur Politik.', 'EinzelOrganisation,dezidierteLobby', 'http://www.spik.ch/', 9, NULL, 'nie', 'mitglied'),
(139, 'Schweizerisches Rotes Kreuz Kanton Zürich ', 'Das SRK Kanton Zürich ist einer der 24 Kantonalverbände des Schweizerischen Roten Kreuzes (SRK), der nationalen Rotkreuzgesellschaft. Das SRK wiederum ist Teil der weltweiten Rotkreuz- und Rothalbmondbewegung. Leitgedanke dieser Bewegung ist es, Menschen in Not zu helfen.', 'MitgliedsOrganisation', '', 1, 3, 'nie', 'einzel,exekutiv,kommission'),
(140, 'Wirtschaftsverband swisscleantech ', 'Der Wirtschaftsverband swisscleantech steht für eine nachhaltige und liberale Wirtschaftspolitik. Der Verband bündelt die Kräfte aller Unternehmen, welche eine Cleantech Ausrichtung der Schweiz aktiv unterstützen. Cleantech gilt dabei als Qualitätsmerkmal für ressourceneffizientes und emissionsarmes Wirtschaften - und hat für alle Branchen Relevanz. ', 'DachOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.swisscleantech.ch/verband/', 4, NULL, 'punktuell', 'mehrere,mitglied'),
(141, 'Zürcher Frauenzentrale    ', 'Die Zürcher Frauenzentrale ist ein gemeinnütziger, steuerbefreiter Verein und ein bedeutender Dachverband im Kanton Zürich, dem 140 Kollektivmitglieder und 1''050 Einzelmitglieder angeschlossen sind. Die Frauenzentrale ist parteipolitisch unabhängig und konfessionell neutral. ', 'DachOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.frauenzentrale.ch/zuerich/cms/front_content.php?idart=2', 9, NULL, 'punktuell', 'einzel,mitglied'),
(142, 'Fritz Heid AG, Thürnen ', 'Verwaltung von und Handel mit Immobilien sowie Handel mit Waren aller Art. Die Gesellschaft kann Planungs- und General- unternehmertätigkeiten ausüben, sich an anderen Unternehmungen beteiligen sowie Grundstücke, Patente und Lizenzen erwerben und veräussern.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.monetas.ch/htm/647/de/Firmendaten-Fritz-Heid-AG.htm?subj=1172899', 9, NULL, 'nie', 'einzel,exekutiv'),
(143, 'Kistenfabrik + Holzhandels AG, Thürnen ', 'Fabrikation von Kisten und Harassen aller Art, speziell Lager-, Transport-, Export- und Werkstattkisten, Fabrikation von Holzwolle, Handel in Holz aller Art. Die Gesellschaft kann ihre Tätigkeit auf verwandte Branchen ausdehnen oder sich an ähnlichen Unternehmen beteiligen sowie Liegenschaften kaufen, verkaufen und verwalten.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.moneyhouse.ch/u/kistenfabrik_und_holzhandels_ag_CH-280.3.910.209-4.htm', 9, NULL, 'nie', 'einzel,exekutiv'),
(144, 'Treff 44 AG, Thürnen ', 'Betrieb einer Gastwirtschaft und Hotelerie. Die Gesellschaft kann ihre Tätigkeit auf verwandte Branchen ausdehnen oder sich an ähnlichen Unternehmen beteiligen sowie Liegenschaften kaufen, verkaufen und verwalten.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.moneyhouse.ch/u/treff_44_ag_CH-280.3.917.292-4.htm', 12, NULL, 'nie', 'einzel,exekutiv'),
(145, 'Gewerbeverein Sissach und Umgebung ', 'Heute zählt unser Verein rund 200 Aktivmitglieder.\r\nWir nehmen nach wie vor die Interessen unserer Mitglieder war. Sei es in der Gemeinde oder in der Wirtschaftskammer Baselland. Daneben kommt aber auch das Gesellige nicht zu kurz. Wir präsentieren unsere Leistungsfähigkeit an unseren Gewerbeausstellungen.\r\nDie letzte fand im Mai 2010 statt und war ein grosser Erfolg.', 'MitgliedsOrganisation', 'http://www.gesi.ch/ueberuns/index.php', 9, NULL, 'nie', 'einzel,exekutiv'),
(146, 'Hauseigentümerverband (HEV) Sissach/Läufelfingen ', 'Hauseigentümer-Verband Regional', 'MitgliedsOrganisation,LeistungsErbringer', 'http://www.hev-sissach.ch/', 9, NULL, 'nie', 'einzel,exekutiv'),
(147, 'Liga Baselbieter Steuerzahler', 'Gegen erhöhte Staatsquoten, gegen Bürokratie, gegen Abgaben', 'EinzelOrganisation,dezidierteLobby', 'http://www.steuerzahler-bl.ch/ziele.html', 14, NULL, 'nie', 'einzel,exekutiv'),
(148, 'KGIV der Wirtschaftskammer Basel-Landschaft, Liestal ', 'Die «Konferenz der Gewerbe- und Industrievereine» ist eine ständige Fachkommission der Wirtschaftskammer Baselland. In der Fachkommission sind alle lokalen und regionalen KMU-Organisationen im Kanton Baselland, die der Wirtschaftskammer als Sektion angeschlossenen sind, vertreten.', 'DachOrganisation,MitgliedsOrganisation,dezidierteLobby', 'http://www.kmu.org/KGIV.1272.0.html', 9, NULL, 'nie', 'einzel,mitglied'),
(149, 'Wirtschaftsrat der Wirtschaftskammer Basel-Landschaft, Liestal    ', 'Der Wirtschaftsrat Baselland ist das Parlament der Baselbieter KMU-Wirtschaft. Er nimmt Stellung zu wirtschafts- und KMU-politisch relevanten Sachfragen, beschliesst Abstimmungsparolen und gibt Wahlempfehlungen ab.', 'DachOrganisation,MitgliedsOrganisation,dezidierteLobby', '', 9, NULL, 'nie', 'einzel,mitglied'),
(150, 'LifeWatch AG Neuhausen ', 'Halten von Beteiligungen an Gesellschaften, welche im Bereich der Forschung, Entwicklung, Herstellung, Verkauf und Vertrieb von Geräten in den Gebieten Elektronik, Computer und Engineering, insbesondere der Medizinalausrüstung, sowie der Erbringung von Dienstleistungen in diesen Gebieten tätig sind, einschliesslich der Telemedizin.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.moneyhouse.ch/u/lifewatch_ag_CH-290.3.013.906-4.htm', 1, 9, 'nie', 'einzel,exekutiv,kommission'),
(151, 'tracker.ch AG  ', 'entwickelt und vertreibt innovative weltweit funktionierende Internet Ortungs-Lösungen mittels GPS- und GPRS-Technologie. Die von Tracker.com entwickelten Lösungen sind für zahlreiche Anwendungen geeignet und zeichnen sich durch eine intuitive und kinderleichte Bedienung aus. Die Hauptanwendungsbereiche von Tracker.com-Lösungen sind Logistik und Sport, die Ortung von Gegenständen und Objekte sowie die Lokalisierung von Menschen und Tiere.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.tracker.com/chde/ueber-uns.aspx', 9, NULL, 'nie', 'einzel,mitglied'),
(152, 'Vergabungskommission der Young Kickers Foundation ', 'Sposoren-Gremium für jungs Fussballtalente', '', '', 17, NULL, 'nie', 'einzel,mitglied'),
(153, 'Stiftung Sportmuseum Schweiz, Basel    ', '', 'EinzelOrganisation', '', 17, NULL, 'nie', 'einzel,exekutiv'),
(154, 'FH SCHWEIZ – Dachverband Absolventinnen und Absolventen Fachhochschulen ', '', 'EinzelOrganisation,dezidierteLobby', 'http://www.fhschweiz.ch/content-n82-sD.html', 7, NULL, 'punktuell', 'mehrere,mitglied'),
(155, 'Groupe Mutuel, Martigny    ', 'Krankenversicherer', 'MitgliedsOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.groupemutuel.ch/content/gm/fr/accueil.html', 1, 2, 'punktuell', 'mehrere,exekutiv,kommission'),
(156, 'Schweizerischer Turnverband (STV), Aarau ', 'Der 1985 gegründete Schweizerische Turnverband STV ist mit seinen rund 385‘000 Mitgliedern nicht nur der grösste polysportive Verband der Schweiz, sondern auch der älteste. Mit seinen Vorgängerverbänden – dem Eidgenössischen Turnverein ETV (Gründungsjahr 1832) und dem Schweizerischen Frauenturnverband SFTV (Gründungsjahr 1908) – blickt er auf eine lange, in der Schweiz stark verwurzelte Tradition zurück.', 'DachOrganisation,LeistungsErbringer', 'http://www.stv-fsg.ch/verband/ueber-uns/', 17, NULL, 'punktuell', 'einzel,exekutiv'),
(157, 'Swiss Olympic Verband, Ittigen ', '', 'DachOrganisation', 'http://www.swissolympic.ch/desktopdefault.aspx/tabid-3505/', 17, NULL, 'punktuell', 'mehrere,exekutiv'),
(158, 'Arbeitsgruppe Gesundheitswesen', 'Arbeitsgruppe Gesundheitswesen der Vereinigung Pharmafirmen in der Schweiz (VIPS)', 'EinzelOrganisation,dezidierteLobby', 'http://www.fmc.ch/uploads/tx_userzsarchiv/CM_013-08.pdf', 1, 3, 'punktuell', 'einzel,mitglied,kommission'),
(159, 'Lions Club, Winterthur ', 'Spender Vereinigung', 'EinzelOrganisation', 'https://winterthur.lionsclub.ch/', 8, NULL, 'nie', 'einzel,mitglied'),
(160, 'Swisscup, Aarau ', 'Behinderten Sport', 'EinzelOrganisation,MitgliedsOrganisation,LeistungsErbringer', 'http://www.rollstuhltanz.ch/index.php?menuid=1', 17, NULL, 'nie', 'einzel,exekutiv'),
(161, 'Berner Fachhochschule, Fachbereich Gesundheit ', 'Der Fachbereich Gesundheit ist auf die Disziplinen Physiotherapie, Pflege, Ernährung und Diätetik sowie Hebamme ausgerichtet und bietet autonom die gesamte erweiterte Leistungspalette an - Studium, Weiterbildung, Dienstleistungen, angewandte Forschung und Entwicklung. ', '', 'http://www.gesundheit.bfh.ch/', 1, NULL, 'punktuell', 'einzel,mitglied,kommission'),
(162, 'Kompetenzzentrum Sexualpädagogik und Schule ', '    Auftrag | \r\n    Team | \r\n    Organisation | \r\n    Publikationen | \r\n    Öffentlichkeitsarbeit | \r\n    Archiv  | \r\n    Rechtliches\r\n\r\nAuftrag\r\n\r\nDas Bundesamt für Gesundheit BAG, Sektion Prävention und Promotion, unterstützt seit 2006 das Kompetenzzentrum Sexualpädagogik und Schule der Pädagogischen Hochschule Zentralschweiz PHZ mit zwei Verträgen und seit 2011 mit einer Subvention.\r\nDas Kompetenzzentrum Sexualpädagogik und Schule leistet einen Beitrag zum Nationalen Programm zur Prävention von HIV und anderen sexuell übertragbaren Infektionen (STI) NPHS 2011 - 2017.', 'EinzelOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.amorix.ch/kompetenzzentrum/auftrag/', 7, NULL, 'punktuell', 'einzel,mitglied'),
(163, 'Mobilitäts Akademie ', 'Über Verbandsgrenzen hinweg will die Mobilitätsakademie einen vorurteilsfreien Raum für kreatives Verkehrsdenken und -handeln schaffen. Mit ihrer Themenwahl und ihren Schwerpunktsetzungen stellt sie sich in den Dienst einer nachhaltigen Mobilität.  ', 'EinzelOrganisation', 'http://www.mobilityacademy.ch/', 5, NULL, 'nie', 'einzel,mitglied'),
(164, 'Centre de formation continue pour adultes handicapés, Fribourg ', 'Mis sur pied par Pro Infirmis en été 1987, le Centre de formation continue pour adultes handicapés mentaux du canton de Fribourg devient la Fondation pour la formation continue des personnes handicapées adultes en 1990. Elle est reconnue par l''Etat de Fribourg en décembre de cette même année.\r\n\r\nLa fondation a pour but d''offrir aux personnes en situation de handicap adultes domiciliées dans le canton de Fribourg une formation continue, organisée et institutionnalisée, qui tienne compte de leurs possibilités d''apprentissage, des aptitudes et des différents centres d''intérêts de chacune d''elles.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.cfc-bz.ch/Le%20centre.html', 7, NULL, 'nie', 'einzel,exekutiv'),
(165, 'Fondation Le Tremplin, Fribourg ', 'Le Tremplin a pour but la prise en charge de toute personne en difficultés, à la suite de problèmes liés à la toxicomanie, et ce, principalement en vue d''une réinsertion socioprofessionnelle.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.tremplin.ch/fr/index.php', 1, 3, 'nie', 'einzel,exekutiv,kommission'),
(166, 'Fondation Les Buissonnets ', 'Eingliederungsstätten für geistig Behinderte Kinder', 'EinzelOrganisation,LeistungsErbringer', 'http://www.lesbuissonnets.ch/index.php?lang=de', 1, 3, 'nie', 'einzel,exekutiv,kommission'),
(167, 'Forschungsfonds der Universität Freiburg ', 'Ziel des Forschungsfonds zum hundertjährigen Bestehen der Universität Freiburg ist die allgemeine Förderung der wissenschaftlichen Forschungsarbeit an der Universität Freiburg und die Unterstützung von spezifischen Forschungsprojekten, welche zur intellektuellen Ausstrahlung der Universität beitragen.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.unifr.ch/funding/de/fonds/', 6, NULL, 'nie', 'einzel,exekutiv'),
(168, 'Stiftung Schweiz Mobil ', 'Dank der ausgezeichneten Zusammenarbeit aller Partner konnte SchweizMobil im Frühling 2008 durch die Stiftung SchweizMobil (Nachfolgerin der Stiftung Veloland Schweiz) nach nur gut dreijähriger Umsetzungszeit erfolgreich lanciert werden. ', 'DachOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.schweizmobil.org/web/schweizmobil/de/home.html', 5, NULL, 'punktuell', 'einzel,mitglied'),
(169, 'Stiftung Umweltbildung Schweiz ', 'Die Stiftung Umweltbildung Schweiz ist das nationale Kompetenz- und Koordinationszentrum für Umweltbildung (UB) in der Schweiz. Sie engagiert sich seit 1994 zusammen mit ihren Partnerinnen und Partnern im Bildungssystem für die Anerkennung der Umweltbildung als unerlässlichen Teil guter Allgemeinbildung und als wesentlichen Bestandteil einer Bildung für Nachhaltige Entwicklung. ', 'EinzelOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.umweltbildung.ch/', 4, NULL, 'punktuell', 'einzel,exekutiv'),
(170, 'Association des Amis du Conservatoire de Fribourg ', 'apporter au Conservatoire de Fribourg (enseignement dans tout le canton à 5''000 élèves grâce à 200 professeurs) un appui moral en entretenant l''intérêt pour cette institution dans les milieux où elle doit être soutenue;\r\npatronner des manifestations telles que concerts et conférences, à Fribourg et dans le canton de Fribourg; ', 'EinzelOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.fr.ch/cof/fr/pub/en_savoir_plus/association_des_amis.htm', 8, NULL, 'nie', 'einzel,exekutiv'),
(171, 'Dachverband Komplementärmedizin Schweiz ', 'Volk und Stände haben am 17. Mai 2009 die Vorlage «Zukunft mit Komplementärmedizin» mit 67 Prozent JA Anteil gutgeheissen und damit die Komplementärmedizin in der Bundesverfassung festgeschrieben: «Bund und Kantone sorgen im Rahmen ihrer Zuständigkeiten für die Berücksichtigung der Komplementärmedizin.» Der Dachverband setzt sich dafür ein, dass diese in der Bundesverfassung festgeschriebene Bestimmung umgesetzt wird. ', 'DachOrganisation,dezidierteLobby', 'http://www.dakomed.ch/', 1, 4, 'punktuell', 'mehrere,exekutiv,kommission'),
(172, 'Dachverband Schweizerischer Patientenstellen, Zürich', '', 'DachOrganisation,dezidierteLobby', 'http://www.patientenstelle.ch/de', 1, 6, 'punktuell', 'einzel,exekutiv,kommission'),
(173, 'Denknetz Schweiz', 'Das Denknetz\r\n- fördert den Gedankenaustausch und die Zusammenarbeit zwischen WissenschafterInnen, politischen und gewerkschaftlichen AkteurInnen und Institutionen im In- und Ausland\r\n- macht Forschungsresultate für die politische Praxis fruchtbar und vermittelt Anregungen für Forschungsprojekte\r\n- entwickelt und setzt Impulse für die politische Orientierung zu aktuellen Themen, ohne jedoch selbst direkt in politische Auseinandersetzungen einzugreifen.', 'EinzelOrganisation', 'http://www.denknetz-online.ch/spip.php?page=denknetz&id_rubrique=42&design=1&lang=de', 6, NULL, 'nie', 'einzel,mitglied'),
(174, 'Freiburger Gesundheitsligen ', '    Unterstützung und Begleitung von Personen, die an Krebs, Diabetes oder einer Atemwegserkrankung leiden, damit sie so gut wie möglich mit der Krankheit und deren Konsequenzen im persönlichen, familiären, beruflichen und sozialen Bereich leben können.\r\n    Sensibilisierung der Risikogruppen für vorbeugende Massnahmen, um die Folgen dieser chronischen Krankheiten zu verringern.\r\n', 'DachOrganisation,LeistungsErbringer', 'http://www.liguessante-fr.ch/default_de.htm', 1, 6, 'nie', 'einzel,exekutiv,kommission'),
(175, 'Krebsliga des Kantons Freiburg ', '', 'MitgliedsOrganisation,LeistungsErbringer', 'http://www.liguecancer-fr.ch/de/', 1, 6, '', 'einzel,exekutiv,kommission'),
(176, 'Nationale Informationsstelle für Kulturgüter-Erhaltung ', 'Anlaufstelle und Informations-Drehscheibe: Die Nationale Informationsstelle für Kulturgüter-Erhaltung NIKE setzt sich für den Erhalt materieller Kulturgüter in der Schweiz ein.\r\nDie Tätigkeit der NIKE gründet auf den Schwerpunkten «Sensibilisierung», «Koordination» und «politische Arbeit».\r\n35 Fachverbände und Publikumsorganisationen bilden den Trägerverein der engagierten Non-Profit-Organisation.', 'DachOrganisation,dezidierteLobby', 'http://www.nike-kultur.ch/de/oeffentlichkeitsarbeit.html', 8, NULL, 'punktuell', 'einzel,exekutiv'),
(177, 'OUESTRAIL', 'Ehemaliges Lötschbergkomitee, heute Lobbyorganisation für den Westschweizer Bahnlinienverkehr', 'EinzelOrganisation,dezidierteLobby', 'http://www.ouestrail.ch/ouestrail/historique.html', 5, NULL, 'punktuell', 'einzel,exekutiv'),
(178, 'Patientenstelle Freiburg-Westschweiz, Freiburg ', '', 'MitgliedsOrganisation,LeistungsErbringer', 'http://www.federationdespatients.ch/', 1, 6, 'nie', 'einzel,exekutiv,kommission'),
(179, 'Pro Velo Schweiz ', 'Pro Velo Schweiz ist der nationale Dachverband der lokalen und regionalen Verbände für die Interessen der Velofahrenden ', 'LeistungsErbringer,dezidierteLobby', '', 5, NULL, 'punktuell', 'einzel,exekutiv'),
(180, 'Schweizerische Alzheimervereinigung    ', 'Die Schweizerische Alzheimervereinigung ist eine unabhängige, konfessionell und politisch neutrale, gemeinnützige Organisation. Sie setzt sich ein für eine Gesellschaft, in der die Menschen gleichwertig und gleich geschätzt miteinander leben. Sie ergreift Partei für Menschen, die an einer Demenzerkrankung leiden. Bei Demenzerkrankungen (Alzheimer oder andere Formen) treten Verluste des Erinnerungsvermögens zusammen mit anderen Funktionsstörungen des Gehirns auf. Dies führt zum Verlust der Selbständigkeit und hat schwerwiegende Folgen für die Betroffenen und ihr Umfeld.', 'DachOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.alz.ch/index.php/home.html', 1, 6, 'punktuell', 'einzel,exekutiv,kommission'),
(181, 'Schweizerische Gesellschaft für Gesundheitspolitik (SGGP)   ', 'Die SGGP\r\nwurde 1976 gegründet und hat sich seither als wichtigstes unabhängiges Forum in der schweizerischen Gesundheitspolitik etabliert. Die SGGP stellt allen gesundheitspolitisch interessierten Fachpersonen eine Plattform zur Verfügung. Sie dient als Netzwerk für Gesundheitsprofis, die über ihren Gartenzaun hinaus blicken und sich interdisziplinär austauschen möchten. Sie ist ein Think Tank und entwickelt laufend Ideen und Projekte zur Verbesserung des Gesundheitswesens.\r\n\r\nUnabhängig\r\nDie SGGP ist ein Verein und lebt von ihren rund 1100 Einzel- und Kollektivmitgliedern - finanziell wie auch inhaltlich. Sie bezieht keine Subventionen. Eventuelle Sponsoring-Partnerschaften bei Veranstaltungen und Publikationen erfolgen nach transparenten Kriterien. ', 'DachOrganisation,dezidierteLobby', 'http://www.sggp.ch/index-de.php?frameset=7', 1, 3, 'punktuell', 'einzel,exekutiv,kommission'),
(182, 'Schweizerische Vereinigung Hochspannung unter den Boden (HSUB) ', 'Das Ziel des Vereins ist es, darauf hinzuwirken dass für den Transport elektrischer Energie, speziell Hochspannungsleitungen eine möglichst moderne und umweltschonende Technik angewendet wird, wie z.B. die Verkabelung der Leitungen unter den Boden oder durchs Wasser.\r\n\r\nDer Verein bezweckt insbesondere\r\na) die Lebensqualität und die Gesundheit der Bevölkerung zu schützen\r\nb) die Qualität der Landschaft und Umwelt zu erhalten\r\nc) Gesundheitliche und psychische Schäden, die aus elektromagnetischer Strahlung resultieren auf ein Minimum zu reduzieren', 'EinzelOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.htst.ch/', 3, NULL, 'punktuell', 'einzel,exekutiv'),
(183, 'Büro Harmos der schweizerischen Erziehungsdirektorenkonferenz ', 'Die "Interkantonale Vereinbarung über die Harmonisierung der obligatorischen Schule" (HarmoS-Konkordat) ist ein neues schweizerisches Schulkonkordat. Das Konkordat harmonisiert erstmals national die Dauer und die wichtigsten Ziele der Bildungsstufen sowie deren Übergänge. Gleichzeitig werden die bisherigen nationalen Lösungen im Schulkonkordat von 1970 bezüglich Schuleintrittsalter und Schulpflicht aktualisiert.\r\nÜber den Beitritt zum Konkordat entscheidet jeder Kanton einzeln.', 'EinzelOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.edk.ch/dyn/11659.php', 7, NULL, 'punktuell', 'einzel,mitglied'),
(184, 'Partners in Learning Leadership Forum    ', '', 'EinzelOrganisation', '', 7, NULL, 'nie', 'einzel,mitglied'),
(185, 'Kompetenzzentrum Sexualpädagogik der PH Luzern    ', 'Die Pädagogische Hochschule Zentralschweiz (PHZ) führt in Zusammenarbeit mit der Hochschule Luzern, Soziale Arbeit das „Kompetenzzentrum Sexualpädagogik und Schule“ im Auftrag des Bundesamtes für Gesundheit BAG Sektion Promotion und Prävention. Das Kompetenzzentrum ist Teil des Programms «bildung + gesundheit Netzwerk Schweiz» des BAG.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.wbza.luzern.phz.ch/gesundheitsfoerderung/kompetenzzentrum-sexualpaedagogik/', 7, NULL, 'nie', 'einzel,mitglied'),
(186, 'Swiss Cochrane working group ', 'Cochrane Switzerland (Cochrane Suisse, Cochrane Schweiz, Cochrane Svizzera) est la branche suisse du Centre Cochrane Français. Sa coordination est assurée depuis Lausanne (Institut Universitaire de Médecine Sociale et Préventive).\r\n\r\nLe fonctionnement et les travaux de la Collaboration Cochrane ne sont pas financés par l''industrie pharmaceutique ou par toute autre source pouvant susciter un conflit d''intérêt.\r\nCochrane Suisse est financé par l''Institut universitaire de médecine sociale et préventive (IUMSP), Centre Hospitalier Universitaire Vaudois et Université de Lausanne, Bugnon 17, 1005 Lausanne.', 'EinzelOrganisation,LeistungsErbringer', 'http://swiss.cochrane.org/fr/accueil', 1, 3, 'nie', 'einzel,mitglied,kommission'),
(187, 'Alliance "Non au nucléaire" , Zürich ', 'Anti-AKW Plattform', 'EinzelOrganisation,dezidierteLobby', 'http://nein-zu-neuen-akw.ch/', 3, NULL, 'nie', 'einzel,mitglied'),
(188, 'Sortir du nucléaire, Lausanne ', '    la promotion de l’efficacité énergétique\r\n    la promotion des énergies renouvelables\r\n    l’interdiction de construction de toute nouvelle centrale nucléaire\r\n    la fermeture des installations nucléaires existante le plus tôt possible\r\n', 'EinzelOrganisation,dezidierteLobby', '', 3, NULL, 'nie', 'einzel,mitglied'),
(189, 'Beratende Komm. EAWAG (Eidg. Anstalt für Wasserversorg', '', '', 'http://www.eawag.ch/about/index', 4, NULL, 'punktuell', 'einzel,mitglied'),
(190, 'Allianz "Ja zur Initiative für den OeV"', 'VCS Iniitiative zum Ausbau des éffentlichen Verkehrs', 'DachOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.verkehrsclub.ch/de/online/medien/medienmitteilungen.html?tx_frpredakartikel_pi3_detail=7704', 5, NULL, 'punktuell', 'mehrere,exekutiv'),
(191, 'Aqua Viva, Schweizerische Aktionsgemeinschaft zum Schutze der Flüsse und Seen ', '', 'DachOrganisation,LeistungsErbringer,dezidierteLobby', '', 4, NULL, 'punktuell', 'einzel,mitglied'),
(192, 'Gesellschaft für das Weinbaumuseum am Zürichsee ', '', '', 'http://www.weinbaumuseum.ch/', 8, NULL, 'nie', 'einzel,exekutiv'),
(193, 'Rheinaubund, Schweizerische Arbeitsgemeinschaft für Natur und Heimat ', 'Der Rheinaubund ist eine gesamtschweizerische, unabhängige und nicht profitorientierte Gewässerschutzorganisation. Als Anwalt der Gewässer setzen wir uns für die Wiederherstellung und den Erhalt naturnaher Gewässer und Gewässerlandschaften ein. Die Expertinnen und Experten des Rheinaubundes engagieren sich in Zusammenarbeit mit den Behörden und Projektanten für die konsequente Umsetzung des Gewässerschutzgesetzes, der Auenverordnung und verwandter Erlasse. ', 'EinzelOrganisation,MitgliedsOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.rheinaubund.ch/ueber-uns/', 4, NULL, 'punktuell', 'einzel,exekutiv'),
(194, 'Schweizer Kaderorganisation SKO  ', 'Die Schweizer Kader Organisation SKO vertritt die wirtschaftlichen, politischen und gesellschaftlichen Interessen der Kader in der Schweiz. Sie offeriert Ihnen als Mitglied zahlreiche interessante Dienstleistungen, Veranstaltungen und exklusive Angebote.   ', 'EinzelOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.sko.ch/de/sko/', 9, NULL, 'punktuell', 'einzel,exekutiv'),
(195, 'Spitex Verband Kanton Zürich ', '', 'DachOrganisation,MitgliedsOrganisation,LeistungsErbringer', 'http://www.spitexzh.ch/verband/dienstleistungen/', 1, 7, 'nie', 'einzel,exekutiv,kommission'),
(196, 'Verein für Ingenieurbiologie ', 'Der Verein für Ingenieurbiologie will das Bauen mit Pflanzen fördern. Er versteht die Ingenieurbiologie als eine biologisch ausgerichtete Ingenieurtechnik im Erd- und Wasserbau. Ingenieurbiologische Bauweisen schützen Boden und Gestein vor Erosion und Rutschungen mit Hilfe von lebenden Pflanzen und Pflanzenteilen.', 'EinzelOrganisation', 'http://www.ingenieurbiologie.ch/site/index.cfm?id_art=70795&actMenuItemID=22715&vsprache/DE/Verein_fuer_Ingenieurbiologie.cfm', 4, NULL, 'nie', 'einzel,exekutiv'),
(197, 'Fachhochschulrat Nordwestschweiz (FHNW), Brugg ', 'Die FHNW basiert auf dem Staatsvertrag der Kantone Aargau, Basel-Landschaft, Basel-Stadt und Solothurn vom 27. Oktober/9. November 2004.\r\n\r\nDie FHNW entstand aus der Fusion der drei Fachhochschulen Aargau, beider Basel und Solothurn, der Pädagogischen Hochschule Solothurn, der Hochschule für Pädagogik und Soziale Arbeit beider Basel sowie den Musikhochschulen der Musik Akademie Basel.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.fhnw.ch/', 7, NULL, 'nie', 'einzel,mitglied'),
(198, 'Energiedienst AG (Sachverständiger Beirat), Laufenburg ', 'Rheinkraftwerk bei Laufenburg. Enerergieerzeugung, DE/CH geminsame Verwaltung und Verteilung von Energie.', 'EinzelOrganisation,LeistungsErbringer', '', 3, NULL, 'nie', 'mehrere,mitglied'),
(199, 'Regionalplanungsgruppe Rohrdorferberg-Reusstal ', 'Reginalplanungsgruppe AG', 'EinzelOrganisation,LeistungsErbringer', 'http://www.ag.ch/staatskalender/?rub=10581', 14, NULL, 'nie', 'einzel,exekutiv'),
(200, 'Verteilung Alkoholzehntel im Aargau ', 'Kantonale Kommission zur Verteilung des Alkoholzehntelanteils des Bundes', 'EinzelOrganisation,LeistungsErbringer', 'http://www.aargauerzeitung.ch/schweiz/kanton-greift-eltern-bei-erziehung-unter-die-arme-11142623', 14, NULL, 'nie', 'einzel,exekutiv'),
(201, 'Albert und Ida Nüssli-Stutz Stiftung', 'Förderung gemeinnütziger, wohltätiger, sozialer, erzieherischer, bilden-der, kultureller und künstlerischer Werke.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.moneyhouse.ch/u/albert_und_ida_nussli_stutz_stiftung_CH-400.7.010.078-3.htm', 8, NULL, 'nie', 'einzel,exekutiv'),
(202, 'Ballenberg - Schweizerisches Freilichtmuseum für ländliche Kultur, Brienz ', 'Freilichtmuseum für bäuerliche Kultur(Stiftung)', 'EinzelOrganisation,LeistungsErbringer', 'http://ballenberg.ch/de/Info/Portrait', 8, NULL, 'nie', 'einzel,exekutiv'),
(203, 'REHA Rheinfelden', 'Rehabilitationsklinik', 'EinzelOrganisation,MitgliedsOrganisation,LeistungsErbringer', 'http://www.reha-rheinfelden.ch/', 1, 5, 'nie', 'einzel,exekutiv,kommission'),
(204, 'Schweizerische Stiftung für eine verantwortungsvolle Gentechnik (GEN SUISSE), Bern', 'Seit 20 Jahren unterstützt die Stiftung Gen Suisse den Dialog zwischen Forschung, Politik, Öffentlichkeit und Schulen. Gegründet 1991 in Zeiten hitziger Diskussionen rund um die Gentechnik, hat Gen Suisse mittlerweile 20 Jahre Forschung und Forschungspolitik im Bereich der Life Sciences begleitet. Die Errungenschaften und Entdeckungen der Forschung brachten Diskussionen zu möglichen Risiken und ethischen Aspekten mit sich. Die Politik hielt Schritt, um den neuen Möglichkeiten der Forschung zu rechtlichen Grundlagen zu verhelfen.', 'EinzelOrganisation,dezidierteLobby', '', 1, 3, 'punktuell', 'mehrere,exekutiv,kommission'),
(205, 'Stiftung 3 R  ', 'Die Stiftung Forschung 3R bezweckt, die Forschung auf dem Gebiet der Alternativmethoden zu Tierversuchen durch Finanzierung von Forschungsprojekten zu fördern, und setzt sich für die Umsetzung und Verbreitung der 3R-Grundsätze ein. Sie unterstützt vordringlich Projekte zur Erforschung neuer Methoden oder zur Weiterentwicklung bekannter Methoden (Validierung von Methoden), welche im Sinne der 3 R (Reduce, Refine, Replace / Vermindern, Verbessern, Vermeiden) gegenüber der heutigen Tierversuchspraxis Verbesserungen versprechen. Die Forschungsprojekte werden nach periodisch festgesetzten Schwerpunkten für die Unterstützung ausgewählt.\r\nTräger der Stiftung\r\n\r\nDie Stiftung ist ein Gemeinschaftswerk der parlamentarischen Gruppe für Tierversuchsfragen (Öffentlichkeit), der Interpharma (Verband der forschenden pharmazeutischen Firmen der Schweiz mit den heutigen Mitgliedern Actelion Ltd., Merck Serono International SA, Novartis Pharma AG, F. Hoffmann-La Roche AG und den assoziierten Mitgliedern Bayer Schweiz AG, Cilag AG und Vifor AG) und des Fonds für versuchstierfreie Forschung – heute Stiftung Animalfree Research (Tierschutz). Sie besteht seit 1987 und steht unter der Aufsicht des Eidgenössischen Departements des Innern.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.forschung3r.ch/de/information/index.html', 4, NULL, 'punktuell', 'mehrere,exekutiv');
INSERT INTO `lobbyorganisationen` (`id_lobbyorg`, `lobbyname`, `lobbydescription`, `lobbyorgtyp`, `weblink`, `id_lobbytyp`, `id_lobbygroup`, `vernehmlassung`, `parlam_verbindung`) VALUES
(206, 'Stiftung Öffentlichkeit und Gesellschaft    ', 'Die gemeinnützige «Stiftung Öffentlichkeit und Gesellschaft» setzt sich für mehr Qualitätsbewusstsein und für höhere Qualitätsansprüche in der medienvermittelten Kommunikation auf Seiten des Publikums wie auf Seiten der Medien ein. Die Stiftung fördert die vergleichende Analyse der medienvermittelten Kommunikation und die Bereitstellung der Ergebnisse für die interessierte Öffentlichkeit und für Schulen.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.oeffentlichkeit.ch/index.html', 18, NULL, 'nie', 'einzel,exekutiv'),
(207, 'Stiftung Regionales Blutspende-Zentrum ', '', 'EinzelOrganisation', 'http://www.moneyhouse.ch/u/stiftung_regionales_blutspendezentrum_srk_aarau_CH-400.7.017.990-7.htm', 1, 3, 'nie', 'einzel,exekutiv,kommission'),
(208, 'Stiftung Vindonissapark    ', 'Die Stiftung fördert die Vermittlung von Archäologie, Geschichte und Kunstgeschichte im historischen Raum Vindonissa sowie der Zusammenhänge zwischen den Kulturgütern und den landschaftlichen Voraussetzungen im "Wasserschloss", wo Aare, Limmat und Reuss zusammenfliessen.\r\n\r\nSie bündelt und koordiniert die privaten und öffentlichen Kräfte, die sich ideell und finanziell für diesen Zweck einsetzen. Die Stiftung kann Dritte, die sich diesem Zweck widmen, unterstützen oder die Vermittlung selber betreiben, insbesondere auch entsprechende Institutionen führen', '', 'http://www.vindonissapark.ch/', 8, NULL, 'nie', 'einzel,exekutiv'),
(209, 'Technopark Aargau ', '', 'EinzelOrganisation,LeistungsErbringer', 'http://www.technopark-aargau.ch/1.aspx', 9, NULL, 'nie', 'einzel,exekutiv'),
(210, 'Forum Alter und Migration', 'Das Nationale Forum setzt sich für die Verbesserung der gesundheitlichen und sozialen Situation älterer Migrantinnen und Migranten in der Schweiz ein. Es hat sich zum Ziel gesetzt, die Rechte der älteren Migranten und Migrantinnen in der Schweiz zu stärken und den Respekt für die Leistungen dieser Generation zu fördern. mehr...', '', 'http://www.alter-migration.ch/', 2, NULL, 'nie', 'einzel,exekutiv'),
(211, 'Forum Vera    ', 'Forum VERA setzt sich – unabhängig vom weiteren Schicksal der Kernenergienutzung in der Schweiz – für eine technisch sichere Entsorgung radioaktiver Abfälle ein.\r\n\r\nDie rund 2500 Mitglieder von Forum VERA sind Persönlichkeiten aus Wissenschaft, Kultur und Politik. Darunter finden sich sowohl Gegner als auch Befürworter der Kernenergie. Die Mitgliedschaft ist offen für alle Interessierten.\r\n\r\nZiel des Vereins ist es, eine breite gesellschaftliche Abstützung der Entsorgung radioaktiver Abfälle sowie eine demokratische Übernahme der Verantwortung in diesem Bereich zu erreichen.', 'EinzelOrganisation,dezidierteLobby', 'http://www.forumvera.info/', 3, NULL, 'punktuell', 'einzel,exekutiv'),
(212, 'Gönnervereinigung Speranza ', 'Speranza schafft berufliche Perspektiven. Unzählige Jugendliche finden nach der obligatorischen Schulzeit den beruflichen Anschluss nicht oder werden nach abgeschlossener Berufslehre arbeitslos. Auch wer nach dem 50. Altersjahr seine Stelle verliert, hat geringe Chancen auf Wiedereingliederung in den Arbeitsmarkt. Unser oberstes Ziel ist die Ausbildung und die nachhaltige Integration von jungen und älteren Menschen in den Arbeitsmarkt. ', 'EinzelOrganisation,LeistungsErbringer', 'http://www.stiftungsperanza.ch/default.aspx?navid=57', 7, NULL, 'nie', 'einzel,exekutiv'),
(213, 'Hauseigentümerverband (HEV) Sektion Baden/Brugg/Zurzach', 'Sektion des Hauseigentümerverbandes (HEV)', 'MitgliedsOrganisation', 'http://www.hev-schweiz.ch/index.php?id=2929&kt=AG', 9, NULL, 'nie', 'einzel,exekutiv'),
(214, 'IG Musikinitiative ', 'Dass Musikunterricht die Entwicklung des Menschen positiv beeinflusst, ist unumstritten. Mit der verfassungsmässigen Verankerung eines zeitgemässen Musikunterrichts wollen wir diesen Wert für die Gesellschaft sichern. Die musizierende Jugend muss in Zukunft zu gutem Musikunterricht  Zugang haben – sowohl in der obligatorischen Schulzeit, als auch im ausserschulischen Bereich an Musikschulen.', 'EinzelOrganisation,dezidierteLobby', '', 7, NULL, 'nie', 'einzel,exekutiv'),
(215, 'Skilift Oberegg-St. Anton, Oberegg ', '', 'EinzelOrganisation,LeistungsErbringer', 'http://www.skilift-oberegg.ch/', 9, NULL, 'nie', 'einzel,exekutiv'),
(216, 'Schweizer Feuilleton-Dienst ', 'Sie interessieren sich für Kultur in der Schweiz, für Theater, Literatur, Kunst, Musik, für Kultur- oder Sprachenpolitik? Dann sind Sie bei uns am richtigen Ort. Als kultureller Informationsdienst schreibt der SFD tagesaktuell über Theater- und Tanzpremieren, über Kunstausstellungen und Konzerte in der ganzen Schweiz, und er bespricht jeweils die belletristischen Neuerscheinungen auf dem Deutschschweizer Büchermarkt. Kleine Häuser berücksichtigt er ebenso wie grosse, junge Autoren ebenso wie arrivierte. Randständige Kunst liegt dem SFD gleichermassen am Herzen wie der Mainstream.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.feuilletondienst.ch/', 18, NULL, 'nie', 'einzel,mitglied'),
(217, 'Pro Innerrhoden, Appenzell ', 'Die Stiftung Pro Innerrhoden fördert das einheimische kulturelle Schaffen des Kantons Appenzell I.Rh. sowie die entsprechenden Institutionen und Vereinigungen. Sie pflegt das kulturelle Erbe und unterstützt die Erhaltung und Wiederherstellung geschichtlicher und schutzwürdiger Kulturgüter. Die Stiftung Pro Innerrhoden betreibt und unterhält das Museum Appenzell und unterstützt zusammen mit den Bezirken und Schulgemeinden des inneren Landesteils die Volksbibliothek Appenzell.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.ai.ch/de/portrait/kulturbrauchtum/stiftpropass/', 8, NULL, 'nie', 'einzel,exekutiv'),
(218, 'Schweizer Jugend forscht ', 'Schweizer Jugend forscht organisiert Veranstaltungen für motivierte junge Menschen, die sich für Wissenschaft und Forschung interessieren. Wir legen Wert auf das selbständige Erfahren und Entdecken der Welt der Wissenschaften. ', 'EinzelOrganisation,LeistungsErbringer', 'http://www.sjf.ch/', 6, NULL, 'nie', 'mehrere,exekutiv'),
(219, 'Stiftung Internat Gymnasium Appenzell ', '', 'EinzelOrganisation,LeistungsErbringer', 'http://gymnasium2.ai.ch/~gym/internat.html', 7, NULL, 'nie', 'einzel,exekutiv'),
(220, 'Stiftung Kinderdorf Pestalozzi', 'Kinderhilfe-Organisation. Projekttätigkeit in der ganzen Welt', 'EinzelOrganisation,LeistungsErbringer', 'http://www.pestalozzi.ch/index.php?L=0', 13, NULL, 'punktuell', 'einzel,exekutiv'),
(221, 'Interstaatliche Erwachsenenmatura ', 'Interstaatliche Maturitätsschule für Erwachsene St.Gallen / Sargans c/o Kantonsschule Sargans. Infos über unser Ausbildungsangebot für Erwachsene.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.cylex-branchenbuch.ch/sargans/interstaatliche-maturit%C3%A4tsschule-f%C3%BCr-erwachsene-11302679.html', 7, NULL, 'nie', 'einzel,mitglied'),
(222, 'Beirat Ökologie der Herzog Kull Group    ', 'Um der immer grösser werdenden Verantwortung in allen Themen der Energiepolitik wie Ökologie,  Ökonomie und Energieeffizienz  gerecht zu werden, hat Herzog Kull Group neu einen Beirat zu diesen Themen geschaffen.\r\n\r\n \r\n\r\nDer Auftrag des Beirates ist die strategische Beratung der Gruppen-Geschäftsleitung und des Verwaltungsrates der HKG in den Disziplinen Energiewirtschaft, Politik, Stromeffizienz und Lichttechnik, Gebäudetechnik-Wissenschaft und Immobilien-Management.', 'EinzelOrganisation,dezidierteLobby', 'http://www.hkgroup.ch/unternehmen/beirat-energie-oekologie/', 3, NULL, 'nie', 'einzel,mitglied'),
(223, 'Herzog Kull Group (HKG) Aarau ', 'Herzog Kull Group wurde 1978 gegründet und hat sich in dieser Zeit zu einem der führenden und landesweiten Anbieter in den Bereichen Elektroengineering Gebäudetechnik und Energieberatung entwickelt. ', '', 'http://www.hkgroup.ch/unternehmen/', 3, NULL, 'nie', 'einzel,exekutiv'),
(224, 'seniorweb AG ', 'Seniorweb.ch ist die dreisprachige, interaktive Internetplattform für die Generation 50plus in der Schweiz. Sie wird gestaltet, betrieben von 100 Freiwilligen die für die Bedürfnisse ihrer Generation schreiben, organisieren und Netzwerke entwickeln. Die Internetplattform seniorweb.ch, die seit über 10 Jahren erfolgreich im Markt besteht, präsentiert sich modern und übersichtlich und enthält viele Angebote und neue Möglichkeiten für die Nutzerinnen und Nutzern . Sie bietet auch allen Altersorganisationen der Schweiz eine Plattform an, auf der diese ihre Mitglieder informieren können.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.seniorweb.ch/type/page/ueber-uns', 2, NULL, 'nie', 'einzel,exekutiv'),
(225, 'Swiss Economy Forum, Advisory Board', 'Die beiden Gründer und Geschäftsleiter des Swiss Economic Forum können auf die Unterstützung und das Netzwerk der Mitglieder des sogenannten Advisory Boards zurückgreifen. Das Advisory Board wird vom früheren Bundespräsidenten und UN-Sonderbeauftrage Adolf Ogi präsidiert. Im Board sind namhafte Persönlichkeiten aus Wirtschaft, Politik, Forschung und Medien vertreten:', 'EinzelOrganisation,MitgliedsOrganisation', 'http://www.swisseconomic.ch/about-sef/boards', 9, NULL, 'nie', 'einzel,mitglied'),
(226, 'Swiss Media Forum, Advisory Board ', 'Das SwissMediaForum bringt Opinion Leaders aus Medien, Wirtschaft und Politik zusammen - mit dem Ziel des Meinungsaustauschs und gegenseitiger Inspiration. Dies vor dem Hintergrund des fundamentalen Medienwandels, der die Geschäftsmodelle der Medienunternehmen verändert und die Schnittstellen zwischen Öffentlichkeit, Unternehmen, und Politik neu definiert.', 'EinzelOrganisation,dezidierteLobby', 'http://www.swissmediaforum.ch/advisory_board/', 18, NULL, 'nie', 'einzel,mitglied'),
(227, 'Stiftung Krebsregister Aargau', '', 'EinzelOrganisation,LeistungsErbringer', 'http://krebsregister-aargau.org/', 1, 3, 'nie', 'einzel,exekutiv,kommission'),
(228, 'Freunde des Zentrums für Demokratie (ZDA), Aarau    ', 'Der Verein "Freunde des ZDA" wurde am 2. April 2009 gegründet und hat den Zweck, die Entwicklung des ZDA zu fördern und dessen Verankerung in der Bevölkerung, Politik und Wirtschaft zu verstärken. Die Vereinsmitglieder werden laufend über die öffentlichen Veranstaltungen des ZDA informiert und zur Teilnahme eingeladen. Grundsätzlich können sämtliche natürlichen oder juristischen Personen dem Verein als Mitglied beitreten. ', 'EinzelOrganisation,dezidierteLobby', 'http://www.zdaarau.ch/de/freunde/index.php', 14, NULL, 'nie', 'einzel,exekutiv'),
(229, 'Verein Cleantech Aargau    ', 'Der Verein Cleantech Aargau ist ein Zusammenschluss von Personen, Unternehmungen, Organisationen und Institutionen, die sich für die Stärkung von Cleantech im Kanton Aargau aussprechen und gemeinsame Ziele verfolgen.', 'EinzelOrganisation,dezidierteLobby', 'http://www.cleantechaargau.ch/', 4, NULL, 'nie', 'einzel,exekutiv'),
(230, 'Advisory Board of the Center for Disability and Integration, Hochschule St. Gallen ', 'Das Center for Disability and Integration (CDI-HSG) ist ein interdisziplinäres Forschungscenter, in dem Betriebswirte, Volkswirte und Psychologen gemeinsam zur beruflichen Integration von Menschen mit Behinderung forschen.\r\n\r\nIm März 2009 nahm das Center for Disability and Integration (CDI-HSG) seine Tätigkeit an der Universität St.Gallen auf. Die Gründung des Centers wurde durch eine private Zuwendung ermöglicht. Das Center wurde gemeinsam vom Schweizerischen Institut für Empirische Wirtschaftsforschung SEW-HSG und dem Institut für Führung und Personalmanagement I.FPM-HSG gegründet. ', 'EinzelOrganisation', 'http://www.cdi.unisg.ch/de/CDIHSG/FachratAdvisoryBoard.aspx', 2, NULL, 'nie', 'einzel,mitglied'),
(231, 'Institut für Wirtschaftsethik der Universität St. Gallen ', 'Im Mittelpunkt unseres theoretischen und empirisch/praktischen Forschungsinteresses stehen Fragen der Realisierung von Unternehmensverantwortung. ', 'EinzelOrganisation,LeistungsErbringer', 'http://www.iwe.unisg.ch/Ueber+uns.aspx', 6, NULL, 'nie', 'einzel,exekutiv'),
(232, 'Solothurner Spital AG, Solothurn    ', 'Spitalvernund öffentlicher Spitäler Kt. SO', 'DachOrganisation,LeistungsErbringer', 'https://www.so-h.ch/', 1, 5, 'punktuell', 'einzel,exekutiv,kommission'),
(233, 'Stiftung Moriz und Elsa von Kuffner ', 'Die Stiftung unterstützt Studierende an Hochschulen und Fachhochschulen, vorab aus Berggebieten, schweizerische Sozialwerke aller Art, betagtes, krankes und sich in Ausbildung befindendes Krankenpflegepersonal, Familien und Einzelpersonen in Bedrängnis sowie Berggemeinden und Institutionen in Bergregionen.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.kuffner.ch/', 2, NULL, 'nie', 'einzel,exekutiv'),
(234, 'Axpo Holding AG ', 'Hier erfahren Sie alles über den Axpo Konzern und seine Ziele. Axpo ist das führende Energieunternehmen der Schweiz. Mit rund 4 000 Mitarbeitenden und dem klimafreundlichen Strommix hält die Axpo die Schweiz in Bewegung. Was wir alles tun, um auch in Zukunft die Ansprüche von Gesellschaft, Wirtschaft und Umwelt in Einklang zu bringen, erfahren Sie auf dieser Website.', 'DachOrganisation,MitgliedsOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.axpo.ch/', 3, NULL, 'punktuell', 'einzel,exekutiv'),
(235, 'De Martin AG, Wängi ', 'Metallverarbeitung', 'EinzelOrganisation,LeistungsErbringer', 'http://www.demartin.com/de/news.cfm', 9, NULL, 'nie', 'einzel,exekutiv'),
(236, 'KIBAG HOLDING AG, Zürich ', 'Bauunternehmung', 'EinzelOrganisation,LeistungsErbringer', 'http://www.kibag.ch/', 9, NULL, 'nie', 'einzel,exekutiv'),
(237, 'Kartause Ittingen, Warth', 'Tourismus, Gatronomie, Kultur', 'EinzelOrganisation,LeistungsErbringer', 'http://www.kartause.ch/de/home/?', 12, NULL, 'nie', 'einzel,exekutiv'),
(238, 'BDO AG ', 'BDO AG ist eine der führenden Wirtschaftsprüfungs-, Treuhand- und Beratungsgesellschaften der Schweiz. Mit 31 Niederlassungen verfügt BDO über das dichteste Filialnetz der Branche. Für grenzüberschreitende Aufgabenstellungen können wir auf das, finanziell von uns unabhängige, weltweite BDO Netzwerk zurückgreifen.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.bdo.ch/', 9, NULL, 'nie', 'einzel,exekutiv'),
(239, 'CSS  Group AG    ', 'Krankenversicherer und Tochtergesellschaften: Sanagate AG, Arcosana AG, INTRAS Assurance SA', 'DachOrganisation,LeistungsErbringer', 'https://www.css.ch/de/home.html', 1, 2, 'punktuell', 'einzel,exekutiv,kommission'),
(240, 'Emmi Gruppe (inkl. Stiftungen) AG', 'Landwirtschaftliche Produkte', 'DachOrganisation,LeistungsErbringer', 'http://group.emmi.com/', 15, NULL, 'punktuell', 'einzel,exekutiv'),
(241, 'Verkehrsbetriebe Luzern AG ', '', 'EinzelOrganisation,LeistungsErbringer', 'http://www.vbl.ch/', 5, NULL, 'nie', 'einzel,exekutiv'),
(242, 'Stiftung BioPolis Entlebuch ', 'Unesco Biosphäre Entebuch Angebote im Bereich Tourismus, Umwewlt, Umweltbildung', 'EinzelOrganisation,dezidierteLobby', 'http://www.biosphaere.ch/de.cfm/company/projects/offer-GesellschaftUBE-Projekte-341974.html', 12, NULL, 'nie', 'einzel,exekutiv'),
(243, 'Industrie- und Handelskammer Zentralschweiz ', 'Die Industrie- und Handelskammer Zentralschweiz IHZ vereinigt rund 600 Industrie-, Handels- und Dienstleistungsunternehmen in den Kantonen Luzern, Uri, Schwyz, Ob- und Nidwalden. ', 'DachOrganisation,MitgliedsOrganisation', 'http://www.hkz.ch/de/handelskammer/mitgliedschaft.php?navanchor=2110036', 9, NULL, 'nie', 'einzel,exekutiv'),
(244, 'Informationsdienst für den öffentlichen Verkehr (LITRA), Bern ', 'Als Informationsdienst für den öffentlichen Verkehr bedient die LITRA regelmassig die Medien mit Informationen über den Verkehr im allgemeinen und den öffentlichen Verkehr im besonderen. Darüber hinaus publiziert sie Chroniken, Jahresberichte, Statistiken, Broschüren sowie Stellungnahmen, Übersichten und Analysen zu aktuellen verkehrspolitischen Themen zuhanden der Medien, der politischen Behörden und der interessierten Öffentlichkeit. Als Verkehrsforum schafft die LITRA eine Plattform für die Präsentation und den Austausch von Ideen und Standpunkten, organisiert Veranstaltungen und erteilt Auskünfte und Beratungen in verkehrspolitischen und verkehrswirtschaftlichen Fragen. Sie wirkt bei der Schaffung guter Rahmenbedingungen mit.', 'DachOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.litra.ch/Wir_uber_uns.html', 5, NULL, 'punktuell', 'einzel,exekutiv'),
(245, 'Kaufmännischer Verband, Luzern ', 'Mitgliedsverband von KV CH', 'EinzelOrganisation,MitgliedsOrganisation', 'www.kvschweiz.ch/luzern', 9, NULL, 'nie', 'einzel,exekutiv'),
(246, 'Axa Winterthur ', 'Versicherungen', 'EinzelOrganisation,LeistungsErbringer', 'https://www.axa-winterthur.ch/de/Seiten/default.aspx', 9, NULL, 'nie', 'einzel,exekutiv'),
(247, 'Osiris Therapeutics Inc., Baltimore', 'Stammzellenforschung in der Medizin', 'MitgliedsOrganisation,LeistungsErbringer', 'http://www.osiris.com/index.php', 1, 9, 'nie', 'einzel,exekutiv,kommission'),
(248, 'Rahn AG, Zürich ', 'Kosmetik, Spezialitätenchemie', 'EinzelOrganisation,LeistungsErbringer', 'http://www.rahn-group.com/', 1, 9, 'nie', 'einzel,exekutiv,kommission'),
(249, 'Fritz-Gerber-Stiftung für begabte junge Menschen, Basel ', 'Unter dem Namen Fritz-Gerber-Stiftung für begabte junge Menschen besteht mit Sitz in Basel eine Stiftung im Sinne der Art. 80 und folgende des Schweizerischen Zivilgesetzbuches. Die Stiftung bezweckt auf ausschliesslich gemeinnütziger Basis die Förderung begabter junger Menschen mit ständigem Wohnsitz in der Schweiz durch Leistung finanzieller Beiträge zur Aus-, Fort- und Weiterbildung. Sie ist dort tätig, wo für die Erreichung des Stiftungszweckes keine öffentlichen Gelder zur Verfügung stehen.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.fritz-gerber-stiftung.ch/de/faktenziele.html', 7, NULL, 'nie', 'einzel,exekutiv'),
(250, 'Sanitas Krankenkasse ', 'Krankenversicherer', 'MitgliedsOrganisation,LeistungsErbringer', 'https://www.sanitas.com/de/index.html?gclid=CNDPn7akibACFcfP3wod427LMA', 1, 2, 'nie', 'einzel,exekutiv,kommission'),
(251, 'Stiftung Vita Parcours, Zürich ', 'Gesundheitsvorsorge ', 'EinzelOrganisation,LeistungsErbringer', 'http://www.zurichvitaparcours.ch/', 1, 3, 'nie', 'einzel,exekutiv,kommission'),
(252, 'Swiss School of Public Health (SSPH+), Zürich ', 'Die Stiftung Swiss School of Public Healthplus fördert und koordiniert auf nationaler Ebene die universitäre Weiterbildung und die damit verbundene Forschung in allen Bereichen von Public Health und Gesundheitsökonomie. Die SSPH+ wurde am 7. Juli 2005 durch eine Vereinbarung zwischen den Universitäten Basel, Bern, Genf, Lausanne, Zürich und der Università della Svizzera italiana gegründet. Im Januar 2008 wurde die SSPH+ in eine Stiftung der sechs Trägeruniversitäten umgewandelt. Im August 2008 wurde die Universität Neuchâtel als neue Partnerin in die Stiftung aufgenommen. ', 'DachOrganisation,dezidierteLobby', 'http://www.ssphplus.ch/spip.php?page=ssph_home&lang=de', 1, 3, 'nie', 'einzel,exekutiv,kommission'),
(253, 'The Jerusalem Foundation, Zürich ', 'Die Jerusalem Foundation ist sich des universalen Charakters der Stadt ebenso wie ihrer besonderen Stellung als Zentrum jüdischen Lebens bewusst. Daher setzt sie sich an vorderster Front dafür ein, dass Jerusalem sich weiter als eine moderne, offene und lebendige Stadt entwickelt, während ihr kulturhistorisches Erbe erhalten wird.\r\n\r\nOhne die Jerusalem Foundation wäre die Stadt nicht wiederzuerkennen: zahlreiche Gemeindezentren, Sportplätze, Parks und Kinderspielplätze, Büchereien, Theater, Museen sowie kulturelle und wissenschaftliche Einrichtungen gehen ebenso auf die Initiativen der Stiftung zurück wie eine große Zahl von Kindertageszentren, Seniorenheimen, Schulen und Neuerungen im Erziehungsbereich schlechthin. ', 'EinzelOrganisation,LeistungsErbringer', 'http://www.jerusalemfoundation.org/de/board_content.aspx?MID=751&CID=861&SID=863&ID=2218', 8, NULL, 'nie', 'einzel,exekutiv'),
(254, 'Krebsliga des Kantons Zürich ', 'Die Krebsliga des Kantons Zürich bietet Krebsbetroffenen und ihren Angehörigen einfach zugängliche Hilfestellung. Wir informieren, beraten und unterstützen. Und wir leisten mit der Unterstützung von ausgewählten Forschungsprojekten einen Beitrag zum Kampf gegen Krebs.', 'MitgliedsOrganisation,LeistungsErbringer', 'http://www.krebsliga-zh.ch/de/ueber_uns/', 1, 6, 'nie', 'einzel,exekutiv,kommission'),
(255, 'Arbeitsgruppe Gesundheitswesen, VIPS  (Pharmafirmen)', 'Die vips Vereinigung Pharmafirmen in der Schweiz wurde 1950 gegründet. Der Anteil ihrer Mitgliedfirmen am Pharmamarkt Schweiz beträgt rund 70%; die vips ist der grösste schweizerische Pharmaverband.\r\n\r\n\r\nMitgliedfirmen der vips sind schweizerische Niederlassungen von ausländischen Pharmaunternehmen und produzierende Schweizer Pharmaunternehmen sowie Vertriebsgesellschaften.\r\n\r\n\r\nZiel und Zweck der Vereinigung ist es, die Interessen ihrer Mitgliedfirmen zur Schaffung angemessener Rahmenbedingungen für deren Geschäftstätigkeit im politischen und wirtschaftlichen Umfeld zu vertreten, marktbezogene Dienstleistungen für die Mitgliedfirmen zu erbringen und den Dialog zwischen den vips-Mitgliedfirmen und den Branchenverbänden zu pflegen', 'DachOrganisation,dezidierteLobby', 'http://www.vips.ch/', 1, 1, 'punktuell', 'einzel,mitglied,kommission'),
(256, 'Schweizerisches Polizeiinstitut, Neuchâtel ', 'Das Schweizerische Polizei-Institut SPI\r\nist eine private Stiftung mit Sitz in Neuenburg, getragen von Bund, Kantonen und Gemeinden. \r\nDas Institut bietet in Zusammenarbeit mit den kantonalen und kommunalen Polizeikorps Dienstleistungen im Bereich der polizeilichen Kaderausbildung, Weiterbildung und Ausbildung der Spezialisten an. Es koordiniert und überwacht die Berufsprüfung und die höhere Fachprüfung. Zudem verlegt es Lehrmittel von Polizisten für die Polizisten und engagiert sich in der angewandten Forschung.\r\n\r\n  ', 'EinzelOrganisation,LeistungsErbringer', 'http://www.institut-police.ch/', 14, NULL, 'punktuell', 'einzel,exekutiv'),
(257, 'St. Galler Stiftung für internationale Studien, St. Gallen ', 'Die St. Galler Stiftung für Internationale Studien ist eine unabhängige Stiftung in St. Gallen, die sich auf internationale Projekte an der Schnittstelle von Wirtschaft, Gesellschaft und Politik fokussiert. Als Aufsichts- und Begleitorgan des International Students’ Committee (ISC) und des St. Gallen Symposiums begleitet sie die Arbeit des jährlich wechselnden studentischen Organisationskomitees. ', 'EinzelOrganisation,LeistungsErbringer', 'http://www.stgallen-symposium.org/de-ch/About/SSIS/Board-of-Trustees.aspx', 7, NULL, 'nie', 'einzel,exekutiv'),
(258, 'Sicherheitsverbund Schweiz (politische Leitung)', 'Der Konsultations- und Koordinationsmechanismus des Sicherheitsverbundes Schweiz (KKM SVS) ist geschaffen worden, um den sicherheitspolitischen Dialog zwischen Bund und Kantonen zu verbessern. Damit wurde ein Kernanliegen des sicherheitspolitischen Berichts 20101 des Bundesrates umgesetzt, an dessen Erarbeitung die Kantone beteiligt waren – konkret die Konferenz der Kantonalen Justiz- und Polizeidirektorinnen und -direktoren (KKJPD) und die Regierungskonferenz Militär, Zivilschutz, Feuerwehr (RK MZF).', 'EinzelOrganisation,LeistungsErbringer', 'http://www.news.admin.ch/NSBSubscriber/message/attachments/26018.pdf', 14, NULL, 'punktuell', 'einzel,mitglied'),
(259, 'Pulita Putzteam GmbH, Reichenburg ', 'Durchführung von Bau-und Gebäudeunterhaltsreinigungen', 'EinzelOrganisation,LeistungsErbringer', 'http://www.moneyhouse.ch/u/pulita_putzteam_gmbh_CH-320.4.048.232-8.htm', 9, NULL, 'nie', 'einzel,exekutiv'),
(260, 'Hôpitaux Universitaires de Genève, Genève ', 'Spitalverbund Universitätsspitäler GE', 'DachOrganisation,LeistungsErbringer', 'http://www.hug-ge.ch/', 1, 5, 'punktuell', 'einzel,exekutiv,kommission'),
(261, 'Forum Gesundheit Schweiz ', 'Das Forum Gesundheit Schweiz ist ein Zusammenschluss von Persönlichkeiten und Organisationen, die sich für ein qualitativ hoch stehendes und kosteneffizientes Gesundheitswesen mit wettbewerblichen Elementen einsetzen. Das Forum wurde im Frühjahr 2006 gegründet.\r\nUnsere Ziele\r\n\r\nDem Forum liegt ein qualitativ hoch stehendes, aber finanzierbares Gesundheitswesen am Herzen. Es will dazu beitragen, einen breit abgestützten Konsens über eine wirkungsvolle Eindämmung der Kostenexplosion im Gesundheitswesen zu schaffen, indem es marktwirtschaftliche Lösungen in die Diskussion einbringt.\r\n', 'DachOrganisation,dezidierteLobby', 'http://www.forumgesundheitschweiz.ch/index.php/das-forum.html', 1, 3, 'punktuell', 'einzel,exekutiv,kommission'),
(262, 'Association des Amis de la Fondation AGIR, Genève ', 'Organtransplantation: Financer et réaliser diverses actions en faveur du don et de la transplantation d''organes; aider les malades insuffisants rénaux de la région genevoise; soutenir le développement de projets humanitaires en lien avec la transplantation.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.monetas.ch/htm/647/de/Firmendaten-Association-des-Amis-de-la-Fondation-AGIR.htm?subj=2140702', 1, 6, 'nie', 'einzel,exekutiv,kommission'),
(263, 'Fédération suisse des sages-femmes (FSSF) ', 'Schweizerischer Hebammenverband', 'EinzelOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.hebamme.ch/', 1, 4, 'punktuell', 'einzel,exekutiv,kommission'),
(264, 'ARGOS, Association d''aide aux personnes toxico-dépendantes, Genève ', 'Suchtbekämpfung: Argos a pour but la création et la gestion de dispositifs destinés à la prise en charge de personnes toxicodépendantes.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.argos.ch/concept-d-intervention/', 1, 3, 'punktuell', 'einzel,exekutiv,kommission'),
(265, 'Mobilité piétonne Suisse  - Fussgängervereinigung CH   ', 'Seit über dreissig Jahren sind wir Vordenker für eine fussgängerfreundliche Verkehrgestaltung und mischen uns immer wieder in aktuelle Verkehrsdebatten ein.\r\n\r\nWir setzen uns dafür ein, dass FussgängerInnen in Städten und Gemeinden genügend Platz und Rechte haben. Als nationaler Fachverband nehmen wir Einfluss auf die Aktivitäten des Bundes. Gleichzeitig sind wir mit unseren Regionalgruppen auch lokal verankert und begleiten nach Möglichkeiten Verkehrsprojekte vor Ort.\r\n\r\nAls Fachverband richten wir uns an Behörden und Fachleute, Politikerinnen, Interessierte und Medien. ', 'EinzelOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.fussverkehr.ch/ueber-uns/', 5, NULL, 'punktuell', 'einzel,exekutiv'),
(266, 'Paul Grüninger-Stiftung    ', 'Rehabilitierung des ehemaligen Polizeichefs Grüninger, Förderung der Forschung etc.', 'EinzelOrganisation', 'http://www.paul-grueninger.ch/pagine/stiftung/foerderprogramm.html', 14, NULL, 'nie', 'einzel,exekutiv'),
(267, 'Editions D + P (Démocrate + Pays) Delémont ', 'Vom Verlag Editions D+P in Delsberg gedruckte regionale Informationszeitung, die 1993 aus der Fusion des liberal-radikalen "Le Démocrate" in Delsberg und des kath.-konservativen "Le Pays" in Pruntrut entstand, zwei Tageszeitungen, die sich lange politisch gegenübergestanden hatten. Mit einer Auflage von rund 22''000 Exemplaren 2009 ist Q. die wichtigste Tageszeitung für den Kt. Jura und den Berner Jura. Die Zeitung zählt mit der Druckerei etwa 100 Mitarbeitende.', '', '', 18, NULL, 'nie', 'einzel,exekutiv'),
(268, 'Fiduconsult  ', 'Depuis 1974, FIDUCONSULT propose son savoir-faire dans tous les domaines de la révision et de l''expertise comptable, fiscale et juridique. Treuhand: FIDUCONSULT réunit plusieurs sociétés affiliées présentes dans cinq cantons et sur sept sites, avec un centre décisionnel basé à Fribourg, à proximité des deux régions linguistiques.\r\n', 'EinzelOrganisation,LeistungsErbringer', 'http://www.fiduconsult.ch/', 9, NULL, 'nie', 'einzel,exekutiv'),
(269, 'groupe-e, Freiburg ', 'Nous sommes le numéro un de la distribution d’électricité en Suisse Elektrizitätsverbund: romande. Notre entreprise, dont le siège est à Granges-Paccot (FR), dessert une population de 460''000 personnes dans les cantons de Fribourg, Neuchâtel, Vaud et Berne. Elle fournit également de l’électricité à 12 partenaires-distributeurs.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.groupe-e.ch/groupe-e-sa', 3, NULL, 'punktuell', 'einzel,exekutiv'),
(270, 'Imprimerie St-Paul, Freiburg ', 'Notre entreprise, fondée en 1871, située à la frontière des langues, donne une grande importance au bilinguisme.\r\n\r\nDu premier contact à la livraison, avec efficacité, compétence et amabilité, les quelques\r\n200 collaborateurs de l’Imprimerie Saint-Paul s’engagent à réaliser vos projets de communication imprimée ou virtuelle.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.imprimerie-saint-paul.ch/presentation/index.php', 9, NULL, 'nie', 'einzel,exekutiv'),
(271, 'JPF Holding SA, Bulle ', 'Hoch- und Tiefbau', 'EinzelOrganisation,LeistungsErbringer', 'http://www.jpf.ch/de/index.php', 9, NULL, 'nie', 'einzel,exekutiv'),
(272, 'Liebherr Machines Bulle SA AG ', 'Dieselmotoren für Baumaschinen, Komponenten der Antriebstechnik', 'EinzelOrganisation,LeistungsErbringer', 'http://www.liebherr.com/de-DE/35275.wfw', 9, NULL, 'nie', 'einzel,exekutiv'),
(273, 'Bäuerliche Bürgschaftsgenossenschaft, Freiburg ', 'Bäuerliche Bürgschaftsgenossenschaft des Kantons Freiburg (Société paysanne de cautionnement du Canton de Fribourg), Route de Chantemerle 41, Granges-Paccot, Case postale 918, 1700 Fribourg. Vom Bund anerkannte bäuerliche Bürgschaftsgenossenschaft', 'EinzelOrganisation,LeistungsErbringer', '', 15, NULL, 'nie', 'einzel,exekutiv'),
(274, 'Amis de la Fille-Dieu, Romont ', 'Renovation der Klosterkirche', 'EinzelOrganisation', 'http://www.fille-dieu.ch/index.php/fr/les-amis-de-la-fille-dieu-11/', 8, NULL, 'nie', 'einzel,exekutiv'),
(275, 'Cemsuisse, Verband der Schweizerischen Cementindustrie ', 'cemsuisse, der Verband der Schweizerischen Cementindustrie, ist ein Partner des Parlaments, der eidgenössischen und kantonalen Behörden und Verwaltungen sowie der Wirtschaft und Wissenschaft. cemsuisse informiert über das Engagement der Industrie zugunsten einer nachhaltigen Zementproduktion und setzt sich ein für die Erhaltung des Produktionsstandortes Schweiz.\r\n\r\n', 'DachOrganisation,dezidierteLobby', 'http://www.cemsuisse.ch/cemsuisse/ueberuns/index.html?lang=de', 9, NULL, 'nie', 'einzel,exekutiv'),
(276, 'Stiftung Comdays (Bieler Kommunikationstage), Biel    ', 'Seit 2010 keine Wirkung mehr. Wirs aber im Stade de Bienne neu überlegt.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.comdays.ch/de/actu.php', 9, NULL, 'nie', 'einzel,exekutiv'),
(277, 'Eidgenössisches Turnfest Biel/Magglingen ', 'Vorbereitung Eidg.Turnfest im Juni 2013', 'LeistungsErbringer', 'http://www.etf-ffg2013.ch/', 17, NULL, 'nie', 'exekutiv'),
(278, 'Jura & Drei-Seen-Land', 'Tourismusförderung', 'EinzelOrganisation', 'http://www.juradreiseenland.ch/', 12, NULL, 'nie', 'einzel,exekutiv'),
(279, 'Neue Helvetische Gesellschaft ', 'Die Neue Helvetische Gesellschaft-Treffpunkt Schweiz (nachstehend NHG-TS) will den Zusammenhalt des Landes stärken, indem sie die Verständigung fördert, identitätsstiftende Ziele formuliert und beiträgt zur Beanwortung von wichtigen Fragen, die sich landesintern oder in der Beziehung zum Ausland stellen. Dieses Ziel will sie erreichen, indem sie namentlich: ', 'EinzelOrganisation,dezidierteLobby', 'http://www.dialoguesuisse.ch/index.php?option=com_content&view=article&id=35&Itemid=71&lang=de', 14, NULL, 'punktuell', 'einzel,exekutiv'),
(280, 'Tour de Suisse, Biel ', '', 'EinzelOrganisation', '', 17, NULL, 'nie', 'einzel,exekutiv'),
(281, 'Verein PPP (Public Private Partnership) Schweiz', 'Am 19. Mai 2006 gründeten Vertreter der Schweiz. Eidgenossenschaft, einzelner Kantone und Städte sowie der Wirtschaft den Verein PPP Schweiz.\r\n\r\nSeine Ziele sind:\r\n\r\n    PPP als mögliches Realisierungsmodell für die Erfüllung öffentlicher Aufgaben in der Schweiz zu verankern und die Standardisierung zu fördern.\r\n    PPP als Qualitätsstandard der Zusammenarbeit von Staat und Wirtschaft zu positionieren, der die Wahrnehmung der öffentlichen Interessen garantiert und zu einer Win-win-Situation für Staat und Wirtschaft führt und so nachhaltige Projekterfolge sichert.\r\n    Die öffentliche Hand zu motivieren, PPP als methodische Grundlage anzuwenden, um die immer komplexer werdenden Aufgabenstellungen zu überprüfen und zu optimieren.\r\n\r\n', 'EinzelOrganisation,LeistungsErbringer', '', 14, NULL, 'nie', 'einzel,mitglied'),
(282, 'Privatkliniken Schweiz (PKS)', 'Dachorganisation von über 180 Privatkliniken in der Schweiz', 'DachOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.privatehospitals.ch/content/organisation/organisation_d.asp?Path=9', 1, 5, 'punktuell', 'kommission'),
(283, 'Aktion Medienfreiheit', 'Lobbyorganisation für Medienfreiheit', 'EinzelOrganisation,dezidierteLobby', 'http://www.medienfreiheit.ch/ziele-und-vorstoesse/index.html', 18, NULL, 'nie', 'mehrere,exekutiv'),
(284, 'Cantone Ticino', 'Kanton Tessin', 'EinzelOrganisation,LeistungsErbringer', 'http://www3.ti.ch/index.php', 14, NULL, 'punktuell', 'einzel,mitglied'),
(285, 'Novartis International AG (Public Affairs)     ', 'Die Aufgabe von Novartis ist es, innovative Medikamente und Therapien zu entdecken, zu entwickeln und erfolgreich zu vermarkten, damit Krankheiten geheilt, Leiden gemildert und die Lebensqualität kranker Menschen verbessert werden können.\r\n\r\nNovartis ist ein in über 140 Ländern tätiges und weltweit führendes Gesundheitsunternehmen, das in der Schweiz zu Hause ist. Dabei sind wir als globales Unternehmen stolz, typisch schweizerische Werte wie hohe Qualität, Verlässlichkeit und Vertrauenswürdigkeit in die Welt hinauszutragen. ', 'MitgliedsOrganisation,dezidierteLobby', 'http://www.novartis.ch/media/contact.shtml', 1, 1, 'punktuell', 'kommission'),
(286, 'Kleinbauern Vereinigung (VKMB)', 'Vereinigung kleinerer und mittlerer Bauern', 'DachOrganisation,MitgliedsOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.kleinbauern.ch/index.php', 15, NULL, 'punktuell', 'einzel,exekutiv'),
(287, 'foraus.ch Forum für Aussenpolitik', 'Der unabhängige Think-Tank foraus - Forum Aussenpolitik - Forum de politique étrangère engagiert sich mit wissenschaftlich fundierten Diskussionsbeiträgen für eine konstruktive Schweizer Aussenpolitik. Die foraus-Mitglieder sind in zehn thematischen Arbeitsgruppen tätig, um aussenpolitische Herausforderungen zu analysieren und mit konkreten Lösungsvorschlägen einen informierten Dialog anzuregen. foraus wurde im Herbst 2009 in Bern gegründet und ist schweizweit aktiv.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.foraus.ch/de/', 13, NULL, 'nie', 'einzel,mitglied'),
(288, 'Interpharma', 'Interpharma ist der Verband der forschenden pharmazeutischen Firmen der Schweiz und wurde 1933 als Verein mit Sitz in Basel gegründet.\r\n\r\nInterpharma arbeitet eng mit allen Beteiligten im Gesundheitswesen zusammen, namentlich mit den Interessenvertretungen der forschenden pharmazeutischen Industrie im In- und Ausland.\r\n\r\nDie Kommunikationsstelle der Interpharma informiert die Öffentlichkeit über die Belange, welche für die forschende Pharma-Industrie in der Schweiz von Bedeutung sind sowie über den Pharmamarkt Schweiz, das Gesundheitswesen und die biomedizinische Forschung.', 'DachOrganisation,dezidierteLobby', 'http://www.interpharma.ch/de/Ueber-Interpharma.asp', 1, 1, 'punktuell', 'kommission'),
(289, 'Santésuisse', 'Wir sind die führende Branchenorganisation in der Schweizer Gesundheitspolitik.\r\nWir sind für die gesamte Branche der Krankenversicherer in allen Regionen der Schweiz repräsentativ.\r\nWir vertreten 7,2 Millionen Versicherte.\r\nWir haben eine grosse gesundheitspolitische Sachkompetenz.', 'DachOrganisation,dezidierteLobby', 'http://www.santesuisse.ch/de/dyn_output.html?content.void=3271&navid=121', 1, 2, 'punktuell', 'mehrere,exekutiv,kommission'),
(290, 'Schweiz. Aussenwirtschaftsförderung (Osec)  ', 'Die Osec, 1927 als nichtgewinnorientierter und halböffentlicher Verein in Lausanne gegründet, informiert, berät und begleitet Schweizer und Liechtensteiner KMU bei ihren internationalen Geschäftsvorhaben. Sie vernetzt Unternehmen, Wissensträger sowie private und öffentliche Organisationen weltweit und ermöglicht so eine schlagkräftige Aussenwirtschaftsförderung.\r\n\r\nNeben der Exportförderung kümmert sich die Osec seit anfangs 2008 auch um die nationale Standortpromotion der Schweiz im Ausland sowie um die Importförderung zugunsten ausgewählter Entwicklungs- und Transitionsländer.', 'DachOrganisation,LeistungsErbringer,dezidierteLobby', 'http://aboutus.osec.ch/de/content/wer-wir-sind', 13, NULL, 'punktuell', 'einzel,exekutiv'),
(291, 'Schweizer Obstverband    (SOV)', 'Verband der schweizerischen Obstproduzenten', 'DachOrganisation,MitgliedsOrganisation,dezidierteLobby', 'http://www.swissfruit.ch/m/mandanten/239/index.html', 15, NULL, 'punktuell', 'einzel,exekutiv'),
(292, 'Schweizerischer Versicherungsverband ', 'Der Schweizerische Versicherungsverband (SVV) ist die Dachorganisation der privaten Versicherungswirtschaft. Dem SVV sind kleine und grosse, national und international tätige Erst- und Rückversicherer angeschlossen.', 'DachOrganisation,dezidierteLobby', 'http://www.svv.ch/de/der-svv/portraet', 9, NULL, 'punktuell', 'einzel,exekutiv'),
(293, 'Schweizerisches Arbeitershilfswerk (SOLIDAR SUISSE)', 'Solidar Suisse setzt sich für eine sozial, politisch und ökonomisch gerechtere Gesellschaft ein: Mit über 50 Projekten in 12 Ländern und mit Kampagnen in der Schweiz. ', 'DachOrganisation,LeistungsErbringer', 'http://www.solidar.ch/portrait-solidar-suisse.html', 13, NULL, 'punktuell', 'mehrere,exekutiv'),
(294, 'Schweizerischer Fussballverband (SFV)', '', 'DachOrganisation,LeistungsErbringer', 'http://www.football.ch/de/start.aspx', 17, NULL, 'punktuell', 'einzel,exekutiv'),
(295, 'Médecins de famille Suisse', '', 'EinzelOrganisation', '', 1, 4, 'punktuell', 'einzel'),
(296, 'Die Schweizerische Post', '', 'EinzelOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.post.ch/', 14, NULL, 'punktuell', 'einzel,exekutiv'),
(297, 'Textilverband Schweiz (TVS)', 'Rund 200 Unternehmen der Textil- und Bekleidungsindustrie bündeln ihre Interessen im TVS Textilverband Schweiz. Der Verband engagiert sich für die übergeordneten Interessen der Mitgliedunternehmen, die auf Grund der historisch gewachsenen Strukturen unterschiedlichen Bereichen angehören. Allen gemeinsam ist das Anliegen, ihre hochwertigen Produkte und Dienstleistungen von einem starken Brand im nationalen sowie internationalen Markt verankert zu wissen. Swiss Textiles, diese Marke steht für innovative, hochwertige Produkte sowie Dienstleistungen und wird als Gütesiegel in der ganzen Welt geschätzt.', 'DachOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.swisstextiles.ch/cms/front_content.php?idcat=2&lang=1', 9, NULL, 'nie', 'einzel,exekutiv'),
(298, 'Gewerkschaft Unia', '', 'DachOrganisation,MitgliedsOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.unia.ch/', 9, NULL, 'punktuell', 'einzel,exekutiv'),
(299, 'Perron Campaigns', 'Public Relations und Kampagnen-Agentur', 'EinzelOrganisation,LeistungsErbringer', 'http://perroncampaigns.ch/about.php', 9, NULL, 'nie', ''),
(300, 'Schweizerische Wettbewerbsvereinigung, Zürich ', 'Bezweckt die Beratung und die Stellungnahme in Wettbewerbsfragen im privaten, gemischtwirtschaftlichen und öffentlichen Bereich, insbesondere inbezug auf das Kartellrecht und befasst sich weiter mit den Fragen der Arbeitgeber- und Arbeitnehmer-Koalitionen.', 'EinzelOrganisation', 'http://www.monetas.ch/htm/647/de/Firmendaten-Schweizerische-Wettbewerbsvereinigung.htm?subj=1734332', 9, NULL, 'nie', 'einzel,exekutiv'),
(302, 'Schweizerische Akademie der medizinischen Wissenschaften (SAMW), Basel', 'Die SAMW unterstützt eine hohe Qualität der Medizin in all ihren Facetten. Sie setzt sich ein für die Stärkung der Forschung und für den Transfer des Wissens in Aus-, Weiter- und Fortbildung und nimmt eine führende Rolle wahr in der umfassenden Reflexion über die Zukunft der Medizin. Im Sinne der Früherkennung antizipiert sie mögliche Entwicklungen und deren Auswirkungen auf Individuen, Gesellschaft und Medizin. Sie engagiert sich bei der Klärung ethischer Fragen im Zusammenhang mit neuen medizinischen Erkenntnissen, stellt ethische Richtlinien auf und setzt sich für deren Umsetzung ein. Die SAMW steht im Dialog mit der Gesellschaft: Sie nimmt Anliegen, Hinweise und Ängste aus der Bevölkerung auf, bemüht sich aktiv um Informationsvermittlung und steht für Experten- und Beratungstätigkeit zuhanden von Politik und Behörden zur Verfügung. Im Rahmen der Akademien der Wissenschaften Schweiz engagiert sich die SAMW in der Hochschul-, Wissenschafts- und Bildungspolitik; durch aktive Mitarbeit und Mitgliedschaft in verschiedenen internationalen Organisationen pflegt sie auch den internationalen Informations- und Erfahrungsaustausch.', 'MitgliedsOrganisation,LeistungsErbringer', 'http://www.samw.ch/de/Aktuell/News.html', 1, 4, 'punktuell', 'einzel,mitglied,kommission'),
(303, 'Ricovero Malcantonese Fondazione Giovanni e Giuseppina Rossi, Croglio', 'Creare e gerire un ricovero per i vecchi maschi e femmine dei circoli di Sessa, Magliasina, Breno, con preferenza per i domiciliati nei comuni dei tre circoli, poi gli attinenti di tali comuni domiciliati fuori dei tre circoli ed infine altre persone domiciliate nei comuni del Malcantone.   ', 'EinzelOrganisation,LeistungsErbringer', 'http://www.moneyhouse.ch/it/u/ricovero_malcantonese_fondazione_giovanni_e_giuseppina_rossi_CH-514.7.009.781-1.htm', 2, NULL, 'nie', 'einzel,exekutiv'),
(304, 'Ospedale Malcantonese Fondazione Giuseppe Rossi, Croglio', 'Privatklinik', 'EinzelOrganisation,MitgliedsOrganisation,LeistungsErbringer', 'http://www.oscam.ch/', 1, 5, 'nie', 'einzel,exekutiv,kommission'),
(305, 'Fondazione Circolo Franchi Liberali e Filarmonica Liberale-Radicale Collina d''Oro, Collina d''Oro', 'Kulturelle Stiftung und liberale Gesinnungspflege', 'EinzelOrganisation', 'http://www.monetas.ch/htm/647/de/Firmendaten-Fondazione-Circolo-Franchi-Liberali-e-Filarmonica-Liberale-Radicale-Collina-d-Oro.htm?subj=1954447', 8, NULL, 'nie', 'einzel,exekutiv'),
(306, 'Wohlfahrtsstiftung der Elektra Baselland, Liestal', 'Fürsorge für die Angestellten und Arbeiter der Elektra Baselland sowie deren Angehörige in Fällen von Alter, Krankheit, Invalidität, Tod und unverschuldeter Notlage.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.moneyhouse.ch/u/wohlfahrtsstiftung_der_elektra_baselland_CH-280.7.911.619-8.htm', 2, NULL, 'nie', 'einzel,exekutiv'),
(307, 'Politcom, Agentur für politische Kommunikation und Public Affairs, Thomas de Courten, Liestal', 'Betrieb einer Agentur für politische Kommunikation und Public Affairs; Dienstleistungen für Exponenten der Politik und der Wirtschaft; Konzeption, Entwicklung und Umsetzung von politischen Projekten; Coaching sowie administrative und fachliche Unterstützung von politischen Mandatsträgern; Erbringung von Dienstleistungen für Unternehmen, insbesondere KMU, in den Bereichen Strategie, Führung, Management, Werbung, Kommunikation und Öffentlichkeitsarbeit; Geschäftsführung bei Verbänden, Organisationen und Institutionen.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.monetas.ch/htm/655/de/SHAB-Publikationen-Politcom-Agentur-f%C3%BCr-politische-Kommunikation-und-Public-Affairs-Thomas-de-Courten.htm?subj=1855356', 9, NULL, 'nie', 'einzel,exekutiv'),
(308, 'atelier politique Fehr, Winterthur', 'Realisierung politischer Projekte; Schulung und Beratung von Personen und Institutionen in Politik und Wirtschaft; Publikationen (Reden, Referate, Artikel, Bücher); Vermittlung und Übernahme von Mandaten in Führungsorganen.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.atelierpolitique.ch/kontakt', 9, NULL, 'nie', 'einzel,exekutiv'),
(309, 'Profil, Zürich (Pro Infirmis)', 'Bezweckt, behinderte Menschen im schweizerischen Arbeitsmarkt zu integrieren', 'EinzelOrganisation,LeistungsErbringer', 'http://www.profil.proinfirmis.ch/organisation.php?sub=3', 1, 3, 'nie', 'einzel,exekutiv,kommission'),
(310, 'Charlotte und Hans Haller Stiftung, Zürich', 'Ermöglichen von Behandlungen, Ausbildungen, Erholungsaufenthalten oder von Anschaffungen von das Leben von Behinderten erleichternden oder angenehmer gestaltenden Geräten in Fällen, in denen Krankenkassen oder Sozialversicherungen nicht oder nur in ungenügendem Masse zu Leistungen verpflichtet werden können. ', 'EinzelOrganisation,LeistungsErbringer', 'http://www.moneyhouse.ch/u/charlotte_und_hans_haller_stiftung_CH-020.7.000.093-8.htm', 1, 3, 'nie', 'einzel,exekutiv,kommission'),
(311, 'Arbeitskreis Sicherheit und Wehrtechnik (asuw)', 'Der Arbeitskreis und seine Mitglieder wollen insbesondere:\r\n\r\n    mit geeigneter Aufklärung dazu beitragen, die wirtschaftliche und sicherheitspolitische Bedeutung einer adäquaten nationalen Wehrindustrie als Teil der Schweizer Industriebasis im Bewusstsein von Gesellschaft und Politik zu verankern;\r\n    die Schweizer Politik dazu anhalten, ausreichende industrielle Kapazität in der Schweiz zu erhalten und die Rahmenbedingungen so auszugestalten, dass wirtschaftliche Unternehmensführungen im Bereich Wehrtechnik weiterhin möglich sind;\r\n    sich für gesetzgeberische Rahmenbedingungen einsetzen, welche der Schweizer Wehrindustrie in staatlichem oder privatem Besitz die wirtschaftliche Existenz in der Schweiz ermöglichen. ', 'EinzelOrganisation,dezidierteLobby', 'http://www.asuw.ch/de/content/view/14/28/', 16, NULL, 'punktuell', 'mehrere,exekutiv'),
(312, 'Agro-Marketing Suisse (AMS), Bern', 'Der Verein bezweckt die Wahrung der Interessen der schweizerischen Land- und Ernährungswirtschaft im kommunikativen Bereich; er fördert die Koordination und Optimierung der Absatzförderungsaktivitäten seiner Mitglieder. Der Verein verfolgt keinen Erwerbszweck; er ist insbesondere weder in der Produktion, noch in der Verarbeitung, noch im Ein- oder Verkauf von Agrarprodukten oder für die Agrarproduktion erforderlichen Hilfswaren tätig; er ist jedoch berechtigt, im Rahmen gemeinsamer Marketing-Aktivitäten (z.B. an Messen) zu deren flankierenden Unterstützung Detailverkäufe zu tätigen.', 'DachOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.agromarketingsuisse.ch/fileadmin/data/pdf/2012/VS-Mitglieder_u__Geschaefsfuehrung.pdf', 15, NULL, 'nie', 'mehrere,exekutiv'),
(313, 'Verein Publikationen Spezialkulturen, Wädenswil ', 'Der Verein bezweckt die Förderung des Transfers von Wissen aus der landwirtschaftlichen Forschung und Extension, insbesondere durch die Forschungsanstalt Agroscope Changins-Wädenswil ACW, Standort Wädenswil sowie von branchenspezifischen Aktualitäten an die interessierten Kreise. Der Verein gibt Publikationen wie die Schweizerische Zeitschrift für Obst- und Weinbau und Merkblätter, Broschüren, Bücher etc. sowie weitere Publikationen von ACW heraus.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.agroscope.admin.ch/publikationen/02121/02155/', 15, NULL, 'nie', 'einzel,mitglied'),
(314, 'Genossenschaft Studentenhaus ALV, Zürich', 'Bau und Betrieb auf gemeinnütziger Grundlage eines Studentenhauses in Zürich und die Schaffung allfälliger weiterer Wohnmöglichkeiten für Studenten.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.alvhaus.ch/', 2, NULL, 'nie', 'einzel,mitglied'),
(315, 'Hauseigentüberverband (HEV)  Meilen und Umgebung', 'HEV Sektion Meilen', 'MitgliedsOrganisation,LeistungsErbringer', 'http://www.hev-meilen.ch/home/kurzportrait/', 9, NULL, 'nie', 'einzel,mitglied'),
(316, 'Stiftung Schweizer Volkskultur, Bubikon', 'Die Stiftung unterstützt Projekte zur Erhaltung und Förderung der schweizerischen Volkskultur im Sinne der Statuten der Schweizerischen Trachtenvereinigung. Die Stiftung hat gemeinnützigen Charakter und verfolgt keinerlei Erwerbszweck.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.ssvk.ch/', 8, NULL, 'nie', 'einzel,exekutiv'),
(317, 'Stiftung MyHandicap, Wil (SG)', 'Die Stiftung bezweckt die gemeinnützige Förderung bzw. Unterstützung von Menschen mit Behinderung; insbesondere bezweckt die Stiftung die gesellschaftliche Integration von, Massnahmen zur Erleichterung des Alltages von und den Informationsaustausch mit nicht-behinderten Menschen und unter behinderten Menschen und ihren Angehörigen in der Schweiz und in Europa.  ', 'EinzelOrganisation,LeistungsErbringer', 'http://www.myhandicap.ch/', 1, 3, 'nie', 'einzel,exekutiv,kommission'),
(318, 'HRS Holding AG, Frauenfeld', 'Halten und Verwaltung von Beteiligungen. Die Gesellschaft kann Zweigniederlassungen oder Betriebsstätten im In- und Ausland errichten, sich an anderen Firmen und Institutionen direkt oder indirekt beteiligen, Finanzdienstleistungen erbringen sowie Grundstücke und Immaterialgüterrechte erwerben, verwalten und veräussern.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.hrs.ch/fileadmin/Dokumente/Organisation/HRS_Gruppe_D.pdf', 9, NULL, 'nie', 'einzel,exekutiv'),
(319, 'SCHMOLZ+BICKENBACH AG, Emmen', 'Erwerb, Verwaltung und Veräusserung von Beteiligungen in allen Rechtsformen, insbesondere im Stahlbereich; Beteiligungen an Handels-, Industrie- und Dienstleistungsunternehmen sowie an Holdinggesellschaften im In- und Ausland; Erwerb, Belastung und Veräusserung von Grundeigentum.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.moneyhouse.ch/u/schmolzbickenbach_ag_CH-100.3.010.656-7.htm', 9, NULL, 'nie', 'einzel,exekutiv');
INSERT INTO `lobbyorganisationen` (`id_lobbyorg`, `lobbyname`, `lobbydescription`, `lobbyorgtyp`, `weblink`, `id_lobbytyp`, `id_lobbygroup`, `vernehmlassung`, `parlam_verbindung`) VALUES
(320, 'Spital Thurgau AG, Frauenfeld', 'Kantonsspital und Kliniken', '', 'http://www.stgag.ch/spital-thurgau-ag/ueber-uns/verwaltungsrat.html?Fsize=0.html.html.html', 1, 5, 'nie', 'einzel,exekutiv,kommission'),
(321, 'Roland Eberle Mercanda Consulting, Frauenfeld', 'Einzelfirma Unternehmensberatung', 'EinzelOrganisation,LeistungsErbringer', 'http://www.moneyhouse.ch/u/roland_eberle_mercanda_consulting_CH-440.1.025.762-3.htm', 9, NULL, 'nie', 'einzel,exekutiv'),
(322, 'Careum Stiftung, Zürich', '1882 als Stiftung Schwesternschule und Krankenhaus vom Roten Kreuz Zürich Fluntern gegründet, versteht sich die Careum Stiftung heute als Organisation, die durch gezielte Veranstaltungen den Dialog zwischen den verschiedenen Akteuren des Gesundheitswesens fördert und in Nachwuchstalente investiert, die die Gesundheitswelt der Zukunft denken. Der Stiftungszweck gemäss Statuten lautet: «Die Stiftung fördert die Bildung im Gesundheitswesen durch Innovation und Entwicklung.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.careum.ch/web/guest/home', 1, 3, 'nie', 'einzel,exekutiv,kommission'),
(323, 'AMIS Plus Stiftung, Zürich', 'Die Stiftung bezweckt das Erfassen und Verwalten von Daten, die der epidemiologischen Charakterisierung von Patienten mit Herzkreislaufleiden dienen, insbesondere statistische Daten über die Entstehung, das Auftreten, die Häufigkeit und der Verlauf der Herzkreislaufkrankheiten, Massnahmen zur Prävention und Früherkennung, das Erfassen von Abklärungs- und Behandlungsstrategien und die Erarbeitung von Interventions- und Optimierungsoptionen.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.amis-plus.ch/', 1, 4, 'nie', 'einzel,exekutiv,kommission'),
(324, 'Dr. med. Ernst und Fanny Bachmann-Huber-Stiftung, Zürich', 'Die Stiftung bezweckt die Entrichtung jährlicher Beiträge an die Universität Zürich und/oder an die Kantonsschule Zürichberg für zusätzliche Stipendien oder für besondere Anschaffungen. Die Stiftung verfolgt weder Erwerbs- noch Selbsthilfezwecke.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.moneyhouse.ch/u/dr_med_ernst_und_fanny_bachmann_huber_stiftung_CH-020.7.900.357-7.htm', 7, NULL, 'nie', 'einzel,exekutiv'),
(325, 'Foundation National Institute for Cancer Epidemiology and Registration (NICER), Zürich', 'Die Stiftung bezweckt die schweizweite Förderung und Unterstützung der bevölkerungsbezogenen Krebsregistrierung und der epidemiologischen Krebsforschung in der Schweiz.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.nicer.org/', 1, 4, 'nie', 'einzel,exekutiv,kommission'),
(326, 'Pestalozzi-Stiftung für die Förderung der Ausbildung Jugendlicher aus schweiz. Berggegenden, Zürich', 'Die Stiftung bezweckt die Förderung der Erziehung, Ausbildung und der beruflichen Weiterbildung von Kindern, Jugendlichen und jungen Erwachsenen aus Berg- und abgelegenen Landgebieten, wenn ihnen diese Möglichkeit ohne Hilfe von aussen nicht zugänglich wäre.  ', 'EinzelOrganisation,LeistungsErbringer', 'http://www.moneyhouse.ch/it/u/pestalozzi_stiftung_fur_die_forderung_der_ausbildung_jugendlicher_aus_schweizerischen_berggegenden_CH-020.7.903.071-9.htm', 7, NULL, 'nie', 'einzel,exekutiv'),
(327, 'Schweizerische Herzstiftung, Bern ', '', 'EinzelOrganisation,LeistungsErbringer', 'http://www.swissheart.ch/index.php?id=7&no_cache=1', 1, 4, 'punktuell', 'einzel,exekutiv,kommission'),
(328, 'Stiftung für Lungendiagnostik, Zürich', 'Die Stiftung bezweckt die Förderung der Früherfassung des Lungenkrebses und anderer Lungenkrankheiten in der Schweiz und im Ausland, das Angebot entsprechender Dienstleistungen sowie das Angebot und die Förderung von Massnahmen primärer, sekundärer und tertiärer Prävention im Zusammenhang mit der Lungengesundheit.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.lungendiagnostik.ch/', 1, 4, 'nie', 'einzel,exekutiv,kommission'),
(329, 'Stiftung für Sucht- und Gesundheitsforschung, Zürich', 'Die Stiftung führt ein Institut für Sucht- und Gesundheitsforschung (im folgenden Institut genannt) in Zürich, welches ausschliesslich gemeinnützige Zwecke verfolgt. Insbesondere bezweckt das Institut, a) die interdisziplinäre Forschung im Bereich von Abhängigkeitsformen, ihrer Entstehung und Behandlung, sowie in weiteren gesundheitlich relevanten Bereichen zu fördern durch Ausführung von Forschungsaufträgen sowie durch eigene Projekte, b) die wissenschaftliche Auswertung von Interventionen im Suchtmittelbereich sowie in weiteren Gesundheitsbereichen zu fördern, c) Projekt- und Institutionsberatungen sowie Projektbegutachtungen durchzuführen, d) Forschungsergebnisse für die präventive, therapeutische, sozialpädagogische und sozialpolitische Praxis zugänglich zu machen e) Forschungsergebnisse in universitäre und andere Ausbildungsgänge einzubringen sowie Lehr- und Weiterbildungsveranstaltungen durchzuführen. Die Stiftung ist parteipolitisch und konfessionell neutral.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.isgf.ch/index.php?id=9&no_cache=1', 1, 4, 'nie', 'einzel,exekutiv,kommission'),
(330, 'Stiftung Walter Honegger zur Förderung der Krebsforschung, Zürich', 'Bezweckt die Förderung der Krebsforschung.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.moneyhouse.ch/u/stiftung_walter_honegger_zur_forderung_der_krebsforschung_CH-020.7.904.273-3.htm', 1, 4, 'nie', 'einzel,exekutiv,kommission'),
(331, 'Verein "Forum Zürcher Gespräche", Zürich', 'Interdisziplinäres, grenzüberschreitendes und parteipolitisch unabhängiges Gesprächsforum für den Austausch von Informationen, Analysen, Konzepten und Meinungen, die mitbestimmend für die wirtschaftliche und politische Entwicklung sind. Das Forum soll die Diskussion zwischen Persönlichkeiten, die mit ihren unternehmerischen und politischen Aktivitäten die wirtschaftliche Zukunft der Schweiz mitgestalten, auf hohem Niveau fördern. Der Verein verfolgt bei seiner Tätigkeit keinen wirtschaftlichen Zweck.', 'EinzelOrganisation', 'http://www.moneyhouse.ch/u/verein_forum_zurcher_gesprache_CH-020.6.000.437-1.htm', 7, NULL, 'nie', 'einzel,exekutiv'),
(332, 'Adimosa AG', 'Zweck des Unternehmens ist die Ausübung des Fondsgeschäfts, einschliesslich die Organisation, die Verwaltung und der Vertrieb von Anlagefonds, die Vertretung ausländischer Anlagefonds sowie die Erbringung von Dienstleistungen im administrativen Bereich für Anlagefonds und anlagefondsähnliche Vermögen ', 'EinzelOrganisation,LeistungsErbringer', 'http://www.moneyhouse.ch/u/adimosa_ag_CH-020.3.006.651-1.htm', 9, NULL, 'nie', 'einzel,exekutiv'),
(333, 'NZZ-Mediengruppe AG, Zürich ', 'Neue Zürcher Zeitung', 'EinzelOrganisation,LeistungsErbringer', 'http://nzzmediengruppe.ch/unternehmen/verwaltungsrat/', 18, NULL, 'nie', 'einzel,exekutiv'),
(334, 'ASGA Pensionskasse, St. Gallen ', 'Pensionskasse', 'EinzelOrganisation,LeistungsErbringer', 'http://www.asga.ch/', 9, NULL, 'nie', 'einzel,exekutiv'),
(335, 'Pensimo Anlagestiftung', 'Die Anlagestiftung Pensimo ist eine von Pensionskassen gegründete und nach unternehmerischen Grundsätzen geführte Anlageorganisation für Immobilien. Sie bietet einer ausgewählten Anzahl schweizerischer Personalvorsorgeeinrichtungen eine qualitativ hochstehende und zukunftsorientierte Anlage ihrer Mittel in Immobilien und deren professionelle Bewirtschaftung an.', 'EinzelOrganisation,LeistungsErbringer', 'http://www.pensimo.ch/pub/ape/index.php', 9, NULL, 'nie', 'einzel,exekutiv'),
(336, 'Schweizerischer Arbeitgeberverband', 'Schweizerischer Arbeitgeberverband', 'DachOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.arbeitgeber.ch/index.php?option=com_content&view=category&layout=blog&id=20&Itemid=27&lang=de', 9, NULL, 'immer', 'mehrere,exekutiv'),
(337, 'Swiss Retail Federation  ', 'Die SWISS RETAIL FEDERATION ist eine Vereinigung von Mittel- und Grossbetrieben des schweizerischen Detailhandels (ehemals Verband der Schweizerischen Waren- und Kaufhäuser) und der vorgelagerten Stufen.\r\n\r\nDie SWISS RETAIL FEDERATION vereinigt unter ihrem Dach Warenhäuser, Fachmärkte, Fachgeschäfte, Verbrauchermärkte, Abholmärkte sowie selbständige Detaillisten, Food-Fachhändler und Kioske.\r\nDie Mitglieder der SWISS RETAIL FEDERATION erwirtschaften jährlich einen Umsatz von rund 12 Milliarden Franken und beschäftigen 40''000 Personen.', 'DachOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.swiss-retail.ch/', 9, NULL, 'punktuell', 'einzel,exekutiv'),
(338, 'Basler Leben AG, Basel', 'Zweck der Gesellschaft ist der Betrieb der Lebensversicherung und aller übrigen Versicherungszweige, welche eine Lebensversicherungsgesellschaft auf Grund der gesetzlichen Vorschriften betreiben kann sowie der Rückversicherung in diesen Versicherungszweigen. Die Gesellschaft kann sich ferner im In- und Ausland an anderen Unternehmungen beteiligen, solche gründen oder übernehmen oder mit ihnen fusionieren.', 'EinzelOrganisation,MitgliedsOrganisation,LeistungsErbringer', 'http://www.baloise.ch/de.html', 9, NULL, 'nie', 'einzel'),
(339, 'Basler Versicherung AG, Basel', 'Versicherungen', 'EinzelOrganisation,LeistungsErbringer', 'http://www.baloise.ch/de.html', 9, NULL, 'nie', 'einzel'),
(340, 'SWISSAID, Schweizerische Stiftung für Entwicklungszusammenarbeit, Bern   ', 'Die Stiftung fördert die Solidarität der schweizerischen Bevölkerung mit Benachteiligten in der Welt. Ihren Zweck erfüllt SWISSAID insbesondere durch die folgenden Tätigkeiten: Unterstützung von Entwicklungsprojekten und -programmen in Entwicklungsländern und Entwicklungsregionen, die die Selbsthilfe besonders benachteiligter Bevölkerungsgruppen stärken; Information der schweizerischen Öffentlichkeit über die Arbeit von SWISSAID, über Fragen der Entwicklung und über Ursachen der Unterentwicklung und Fehlentwicklung; Teilnahme an der entwicklungspolitischen Meinungs- und Entscheidungsbildung mit dem Ziel, die schweizerischen Beziehungen mit den Entwicklungsländern im Interesse der besonders Benachteiligten mitzugestalten; Zusammenarbeit mit privaten und öffentlichen Institutionen im Sinne des Stiftungszwecks. In ihrer Tätigkeit ist SWISSAID offen für neue Entwicklungsansätze insbesondere aus der Dritten Welt.   ', 'MitgliedsOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.swissaid.ch/', 13, NULL, 'punktuell', 'mehrere,mitglied'),
(341, 'Stiftung Allalin, Bern', 'Die Stiftung bezweckt die Pflege der Beziehungen in kultureller, historischer und politischer Hinsicht zwischen den Ländern der Arabischen Welt und der Schweiz als Land im Zentrum von Europa. Sie organisiert zu diesem Zweck in der Arabischen Welt und in der Schweiz Veranstaltungen und andere Plattformen der Begegnung, welche der gegenseitigen Information und dem Meinungsaustausch dienen und so zum besseren Verständnis der unterschiedlichen Kulturen, der Geschichte und den Gesellschaften beitragen sollen. Es besteht ein Zweckänderungsvorbehalt gemäss Art. 86a ZGB.  ', 'EinzelOrganisation', 'http://www.monetas.ch/htm/653/de/Aktuelle-Verantwortliche-Stiftung-Allalin-Allalin-Foundation.htm?subj=2227492', 13, NULL, 'nie', 'einzel,exekutiv'),
(342, 'Curaviva', 'CURAVIVA Schweiz - der nationale Dachverband der Heime und Institutionen\r\n\r\nAls Branchen- und Institutionenverband mit arbeitgeberpolitischer Ausrichtung vertritt CURAVIVA Schweiz die Interessen der Heime und sozialen Institutionen aus den Bereichen Menschen im Alter, Erwachsene Menschen mit Behinderung sowie Kinder und Jugendliche mit besonderen Bedürfnissen.', 'DachOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.curaviva.ch/index.cfm/CC67AC52-A777-9EFA-1F9440E75954180C/', 2, NULL, 'punktuell', 'mehrere,exekutiv,kommission'),
(343, 'Intergenerika', 'Wir wollen den qualitativ hochstehenden Generika-Markt in der Schweiz ausbauen und damit den Sparbeitrag an die Medikamentenkosten erhöhen.\r\n ', 'DachOrganisation,dezidierteLobby', 'http://www.intergenerika.ch/strategie/', 1, 1, 'punktuell', 'einzel,exekutiv,kommission'),
(344, 'Schweizerischer Gewerbeverband (sgv, usam)', 'Der Schweizerische Gewerbeverband sgv vertritt die Interessen der kleinen und mittleren Unternehmen KMU in der Schweiz. Mitglieder des sgv sind die kantonalen Gewerbeverbände, Berufs- und Branchenverbände sowie die Organisationen der Gewerbeförderung.', 'DachOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.sgv-usam.ch/verband.html', 9, NULL, 'punktuell', 'mehrere,exekutiv'),
(345, 'Hauseigentümerverband Kt. BL', 'Mitgliedsorganisation HEV Schweiz', 'MitgliedsOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.hev-bl.ch/', 9, NULL, 'nie', 'einzel'),
(346, 'H+ Spitäler der Schweiz', 'H+ ist die Spitzenorganisation der öffentlichen und privaten Schweizer Spitäler, Kliniken und Pflegeinstitutionen. Seit über 80 Jahren gestaltet H+ das schweizerische Gesundheitswesen aktiv mit. Als nationaler Verband nimmt H+ die Interessen der Mitglieder als Leistungserbringer und Arbeitgeber wahr. ', 'DachOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.hplus.ch/', 1, 5, 'punktuell', 'einzel,mitglied,kommission'),
(347, 'Schweizerischer Mieterinnen- und Mieterverband', 'Dachorganisationen sämtlicher kantonalen und regionalen MieterInnen-Verbände', 'DachOrganisation,LeistungsErbringer,dezidierteLobby', 'http://www.smv-asloca-asi.ch/', 9, NULL, 'punktuell', 'mehrere,exekutiv'),
(348, 'Associazione Scigué', 'Entwicklungshilfe-Organisation im Bildungsbereich. Der Name ist Sptzname des verstorbenen Gründers, Moreno Fibbioli.', '', 'NN', 13, NULL, 'nie', 'einzel,exekutiv');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lobbytypen`
--

CREATE TABLE IF NOT EXISTS `lobbytypen` (
  `id_lobbytyp` int(11) NOT NULL AUTO_INCREMENT,
  `lt_kategorie` varchar(255) NOT NULL COMMENT 'Energielobby, Gesundheitslobby etc',
  `lt_description` text NOT NULL,
  `factsheet` text NOT NULL,
  `lt_kommission` varchar(255) NOT NULL,
  PRIMARY KEY (`id_lobbytyp`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Lobbytypen und ständige Kommissionen' AUTO_INCREMENT=19 ;

--
-- TRUNCATE Tabelle vor dem Einfügen `lobbytypen`
--

TRUNCATE TABLE `lobbytypen`;
--
-- Daten für Tabelle `lobbytypen`
--

INSERT INTO `lobbytypen` (`id_lobbytyp`, `lt_kategorie`, `lt_description`, `factsheet`, `lt_kommission`) VALUES
(1, 'Gesundheit', 'Akteure im Gesundheitswesen', 'Wie werden die ca. 60 Milliarden jährlich aufgeteilt', 'SGK'),
(2, 'Soziale Sicherheit', '', '', 'SGK'),
(3, 'Energie', '', '', 'UREK'),
(4, 'Umwelt', '', '', 'UREK'),
(5, 'Verkehr', '', '', 'KVF'),
(6, 'Wissenschaft', '', '', 'WBK'),
(7, 'Bildung', '', '', 'WBK'),
(8, 'Kultur', '', '', 'WBK'),
(9, 'Wirtschaft', '', '', 'WAK'),
(10, 'Abgaben/Steuern', '', '', 'WAK'),
(11, 'Raumplanung', '', '', 'UREK'),
(12, 'Tourismus/Gatronomie', '', '', 'WAK?'),
(13, 'Aussenpolitik/Aussenwirtschaft', '', '', 'APK'),
(14, 'Staatspolitik/Staatswirtschaft', '', '', 'SPK'),
(15, 'Landwirtschaft', '', '', 'WAK'),
(16, 'Sicherheit/Militär', '', '', 'SiK'),
(17, 'Sport', '', '', 'WBK'),
(18, 'Medien', '', '', 'KVF');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `parlamentarier`
--

CREATE TABLE IF NOT EXISTS `parlamentarier` (
  `id_parlam` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `vorname` varchar(150) NOT NULL,
  `beruf` varchar(255) NOT NULL,
  `ratstyp` enum('NR','SR') NOT NULL,
  `kanton` varchar(2) NOT NULL,
  `partei` varchar(20) NOT NULL,
  `parteifunktion` varchar(255) NOT NULL DEFAULT 'Mitglied',
  `im_rat_seit` varchar(4) NOT NULL,
  `kommission` varchar(255) NOT NULL,
  `kleinbild` varchar(80) NOT NULL,
  `sitzplatz` int(11) NOT NULL,
  PRIMARY KEY (`id_parlam`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Eidgenössisches Parlament: Parlamentarier' AUTO_INCREMENT=39 ;

--
-- TRUNCATE Tabelle vor dem Einfügen `parlamentarier`
--

TRUNCATE TABLE `parlamentarier`;
--
-- Daten für Tabelle `parlamentarier`
--

INSERT INTO `parlamentarier` (`id_parlam`, `name`, `vorname`, `beruf`, `ratstyp`, `kanton`, `partei`, `parteifunktion`, `im_rat_seit`, `kommission`, `kleinbild`, `sitzplatz`) VALUES
(1, 'Blocher', 'Christoph', 'Unternehmer', 'NR', 'ZH', 'SVP ', 'Vizepräsident SVP CH', '2011', 'SGK-NR, SPK-NR', 'blocher-SVP-ZH-klein.jpg', 30),
(2, 'Borer', 'Roland.F', 'Unternehmer', 'NR', 'SO', 'SVP ', 'Mitglied', '1995', 'SGK-NR, SiK-NR', 'borer-SVP-SO-klein.jpg', 69),
(3, 'Bortoluzzi', 'Toni', 'Schreiner', 'NR', 'ZH', 'SVP ', 'Mitglied', '1995', 'SGK-NR, SiK-NR', 'bortoluzzi-SVP-ZH-klein.jpg', 110),
(4, 'Carobbio Guscetti', 'Marina', 'Arzt', 'NR', 'TI', 'SPS', 'Mitglied', '2007', 'SGK-NR, FK-NR, FinDel', 'carobbio-guscetti-SPS-TI-klein.jpg', 41),
(5, 'Cassis', 'Ignazio', 'Arzt', 'NR', 'TI', 'FDP ', 'Mitglied', '2007', 'SGK-NR, ', 'cassis-ignazio-FDP-TI-klein.jpg', 27),
(6, 'de Courten', 'Thomas', 'Selbständiger Unternehmer,Leiter Wirtschaftsförderung Baselland ', 'NR', 'BL', 'SVP ', 'Mitglied', '2011', 'SGK-NR', 'decourten-thomas-SVP-BL-klein.jpg', 161),
(7, 'Fehr', 'Jacqueline', 'Projektarbeiterin', 'NR', 'ZH', 'SPS ', 'Mitglied', '1998', 'SGK-NR, GPK-NR, NAD-V', 'fehr-jacqueline-SPS-ZH-klein.jpg', 4),
(8, 'Frehner', 'Sebastian', 'Jurist, Unternehmer', 'NR', 'BS', 'SVP ', 'Mitglied', '2010', 'SGK-NR', 'frehner-sebatian-SVP-BS-klein.jpg', 160),
(9, 'Gilli', 'Yvonne', 'Arzt', 'NR', 'SG', 'GPS ', 'Mitglied', '2011', 'SGK-NR, WBK-NR', 'gilli-yvonne-GPS-SG-klein.jpg', 152),
(10, 'Heim', 'Bea', 'Rhythmik- und Heilpädagogin    ', 'NR', 'SO', 'SPS ', 'Mitglied', '2003', 'SGK-NR, SPK-NR', 'heim-bea-SPS-SO-klein.jpg', 82),
(11, 'Hess', 'Lorenz', 'Gemeindepräsident, eidg. Dipl. PR-Berater, Unternehmer    ', 'NR', 'BE', 'BDP ', 'Mitglied', '2011', 'SGK-NR', 'hess-lorenz-BDP-BE-klein.jpg', 130),
(12, 'Humbel', 'Ruth', 'Beraterin Gesundheitswesen', 'NR', 'AG', 'CVP ', 'Mitglied', '2003', 'SPK-NR, SGK-NR', 'humbel-ruth-CVP-ZH-klein.jpg', 16),
(13, 'Ingold', 'Maja', 'Politikerin', 'NR', 'ZH', 'EVP ', 'Mitglied', '2010', 'SGK-NR', 'ingold-maja-EVP-ZH-klein.jpg', 89),
(14, 'Lohr', 'Christian', 'Journalist, Dozent versch. Fachhochschulen, Publizist    ', 'NR', 'TG', 'CVP ', 'Mitglied', '2011', 'SGK-NR', 'lohr-christian-CVP-TG-klein.jpg', 7),
(15, 'Moret', 'Isabelle', 'Avocate', 'NR', 'VD', 'FDP ', 'Mitglied', '2006', 'SGK-NR, SPK-NR', 'moret-isabelle-FDP-VD-klein.jpg', 26),
(16, 'Parmelin', 'Guy', 'Viticulteur, Agriculteur ', 'NR', 'VD', 'SVP', 'Vizepräsident SVP CH??', '2003', 'SGK-NR, UREK-NR', 'pamelin-guy-SVP-VD-klein.jpg', 103),
(17, 'Pezzatti', 'Bruno', 'Direktor des Schweizerischen Obstverbandes   ', 'NR', 'ZG', 'FDP', 'Mitglied', '2011', 'SGK-NR', 'pezzatti-brruno-FDP-ZH-klein.jpg', 97),
(18, 'Rossini', 'Stéphane', 'Professeur, HES-SO, ESSP Lausanne et Université de Genève    ', 'NR', 'VS', 'SPS ', 'Präsident GSK', '1999', 'SGK-NR', 'rossini-stephane-SPS-VS-klein.jpg', 39),
(19, 'Schenker', ' Silvia', 'Sozialarbeiterin', 'NR', 'BS', 'SPS', 'Mitglied', '2003', 'SGK-NR, SPK-NR', 'schenker-silvia-SPS-BS-klein.jpg', 42),
(20, 'Schmid-Federer', 'Barbara', 'Familienfrau, Unternehmerin    ', 'NR', 'ZH', 'CVP ', 'Mitglied', '2007', 'SGK-NR', 'schmid-federer-barbara-CVP-ZH-klein.jpg', 13),
(21, 'Schneeberger ', 'Daniela', 'Treuhänderin mit eidg. Fachausweis, Inhaberin und Geschäftsführerin, Steuerexpertin     ', 'NR', 'BL', 'FDP ', 'Mitglied', '2011', 'SGK-NR', 'schneeberger-daniela-FDP-BL-klein.jpg', 57),
(22, 'Stahl', 'Jürg', 'Mitglied der Geschäftsleitung, Leiter Service Center   ', 'NR', 'ZH', 'SVP ', 'Mitglied', '1999', 'SGK -R, WBK-NR', 'stahl-juerg-SVP-ZH-klein.jpg', 68),
(23, 'Steiert', 'Jean-François', 'Del. für interkant. Angelegenheiten der Waadtländer Erziehungsdirektion   ', 'NR', 'FR', 'SPS ', 'Mitglied', '2007', 'SGK-NR, WBK-NR', 'steiert-jean-francois-SPS-FR-klein.jpg', 78),
(24, 'van Singer', 'Christian', 'Physicien', 'NR', 'VD', 'GPS ', 'Mitglied', '2007', 'SGK-NR, SiK-NR', 'vansinger-christian-GPS-VD-klein.jpg', 149),
(25, 'Weibel', 'Thomas', 'dipl. Ing. ETH / SIA, Professor ', 'NR', 'ZH', 'GLP  ', 'Mitglied', '2007', 'SGK-NR, WBK-NR', 'weibel-thomas-GLP-ZH-klein.jpg', 91),
(26, 'Egerszegi-Obrist ', 'Christine ', 'Politikerin', 'SR', 'AG', 'FDP', 'Mitglied', '2007', 'SGK-SR, KVF-SR, SPK-SR, OSZE-V', 'egerszegi-obrist-christine-FDP-AG-klein.jpg', 22),
(27, 'Bischofberger', ' Ivo ', 'Rektor Gymnasium Appenzell ', 'SR', 'AI', 'CVP', 'Mitglied', '2007', 'APK-SR,WEK-SR, SGK-SR, RedK-V, UVEK-SR', 'bischofberger-ivo-CVP-AI-klein.jpg', 27),
(28, 'Bruderer Wyss ', 'Pascale ', 'Vizepräsidentin SP Schweiz', 'SR', 'AG', 'SPS', 'Mitglied', '2011', 'SGK-SR, SPK-SR,UREK-SR, DEL FL-V, DEL A-V', 'bruderer-wyss-pascale-SPS-AG-klein.jpg', 16),
(29, 'Diener Lenz ', 'Verena ', 'Mediatorin, Politikerin', 'SR', 'ZH', 'GLP', 'Mitglied', '2007', 'SPK-SR, SGK-SR, UREK-SR', 'diener-lenz-verena-GLP-ZH-klein.jpg', 39),
(30, 'Eberle ', 'Roland', 'Unternehmer', 'SR', 'TG', 'SVP', 'Mitglied', '2011', 'SGK-SR, APK-SR, UREK-SR, GK-V, DEL D -V', 'eberle-roland-SVP-TG-klein.jpg', 34),
(31, 'Graber ', 'Konrad ', 'dipl. Wirtschaftsprüfer, Partner, Mitglied VR BDO AG ', 'SR', 'LU', 'CVP', 'Mitglied', '2007', 'SGK-SR, KVF-SR, WAK-SR, BeK-V, EFTA/EP-V', 'graber-konrad-CVP-LU-klein.jpg', 26),
(32, 'Gutzwiller ', 'Felix ', 'Professor, Institutsdirektor', 'SR', 'ZH', 'FDP', 'Mitglied', '2007', 'APK-SR, WBK-SR, SGK-SR, IPU-V', 'gutzwiller-felix-FDP-ZH-klein.jpg', 9),
(33, 'Keller-Sutter', ' Karin ', 'Regierungsrätin, Vorsteherin Sicherheits- und Justizdepartement Kanton St. Gallen', 'SR', 'SG', 'FDP', 'Mitglied', '2011', 'WAK-SR, SGK-SR, APK-SR, EFTA/EP-V,Del A-V,Del Fl-V', 'keller-suter-karin-FDP-SG-klein.jpg', 20),
(34, 'Kuprecht ', 'Alex ', 'Relation Manager', 'SR', 'SZ', 'SVP', 'Mitglied', '2003', 'SiK-SR, SGK-SR, GPK-SR, OSZE-V, NATO-V,GPDel-V, del A-V, del FL-V ', 'kuprecht-alex-SVP-SZ-klein.jpg', 35),
(35, 'Maury Pasquier ', 'Liliane ', 'Politikerin, Hebamme', 'SR', 'GE', 'SPS', 'Mitglied', '2007', 'WBK-SR,APK-SR, SGK-SR, PVER, APF,Del F-V', 'maury-pasquier-liliane-SPS-GE-klein.jpg', 29),
(36, 'Rechsteiner', 'Paul ', 'Rechtsanwalt', 'SR', 'SG', 'SPS', 'Mitglied', '2011', 'SGK-SR, KVF-SR', 'rechsteiner-paul-SPS-SG-klein.jpg', 32),
(37, 'Schwaller ', 'Urs ', 'Rechtsanwalt', 'SR', 'FR', 'CVP', 'Mitglied', '2003', 'SGK-SR, SPK-SR,  FK-SR, FinDel-V, ERD-V', 'schwaller-urs-CVP-FR-klein.jpg', 42),
(38, 'Stöckli', 'Hans', 'Präsident Tourismus Jura & Drei-Seen-Land ', 'SR', 'BE', 'SPS', 'Mitglied', '2011', 'SGK-SR,FK-SR, SPK-SR,, BeK-V', 'stoeckli-hans-SPS-BE-klein.jpg', 18);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `zugangsberechtigungen`
--

CREATE TABLE IF NOT EXISTS `zugangsberechtigungen` (
  `id_zugang` int(11) NOT NULL AUTO_INCREMENT,
  `id_parlam` int(11) NOT NULL COMMENT 'FK parlamentarier',
  `berech_name` varchar(160) NOT NULL,
  `berech_vorname` varchar(160) NOT NULL,
  `berech_organisation` varchar(255) DEFAULT NULL,
  `id_lobbytyp` int(11) DEFAULT NULL COMMENT 'FK lobbytyp',
  `id_lobbygroup` int(11) DEFAULT NULL,
  `id_lobbyorg` int(11) DEFAULT NULL COMMENT 'FK lobbyorg',
  PRIMARY KEY (`id_zugang`),
  KEY `idx_parlam` (`id_parlam`),
  KEY `idx_lobbytyp` (`id_lobbytyp`),
  KEY `idx_lobbygroup` (`id_lobbygroup`),
  KEY `idx_lobbyorg` (`id_lobbyorg`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Eidgenössisches Parlament: Zugangsberechtigungen' AUTO_INCREMENT=63 ;

--
-- RELATIONEN DER TABELLE `zugangsberechtigungen`:
--   `id_lobbygroup`
--       `lobbygruppen` -> `id_lobbygroup`
--   `id_lobbyorg`
--       `lobbyorganisationen` -> `id_lobbyorg`
--   `id_lobbytyp`
--       `lobbytypen` -> `id_lobbytyp`
--   `id_parlam`
--       `parlamentarier` -> `id_parlam`
--

--
-- TRUNCATE Tabelle vor dem Einfügen `zugangsberechtigungen`
--

TRUNCATE TABLE `zugangsberechtigungen`;
--
-- Daten für Tabelle `zugangsberechtigungen`
--

INSERT INTO `zugangsberechtigungen` (`id_zugang`, `id_parlam`, `berech_name`, `berech_vorname`, `berech_organisation`, `id_lobbytyp`, `id_lobbygroup`, `id_lobbyorg`) VALUES
(1, 1, 'von Rohr', 'Chris', 'Persönlicher Mitarbeiter', NULL, NULL, NULL),
(2, 1, 'Blocher', 'Silvia', 'Ehefrau', NULL, NULL, NULL),
(3, 2, 'Herren', 'Erich', 'Persönlicher Mitarbeiter', NULL, NULL, NULL),
(4, 3, 'Martin', 'Urs', 'PrivatklinikenSchweiz ', 1, 5, 282),
(5, 3, 'Brunner', 'Roswitha', 'Aktion Medienfreiheit', 18, NULL, 283),
(6, 5, 'Chiari', 'Marciana', 'Cantone Ticino', 14, NULL, 284),
(7, 8, 'Thüring', 'Joël A.', 'Persönlicher Mitarbeiter', NULL, NULL, NULL),
(8, 8, 'Britt', 'Jean- Christophe', 'Novartis International AG (Public Affairs)     ', 1, 1, 285),
(9, 9, 'Küttel ', 'Barbara', 'Kleinbauern Vereinigung (VKMB)', 15, NULL, 286),
(10, 9, 'Alther', 'Rudolf', 'Gesellschaft Schweiz-Albanien    ', 13, NULL, 67),
(11, 13, 'Müller', 'Werner', 'SVS / BirdLife Schweiz  ', 4, NULL, 104),
(12, 13, 'Schneider', 'Martine', 'Gast', NULL, NULL, NULL),
(13, 14, 'Fasnacht', 'Hansjörg', 'Persönlicher Mitarbeiter', NULL, NULL, NULL),
(14, 15, 'Wuersch', 'Maxim', 'Collaborateur personnel ', NULL, NULL, NULL),
(15, 15, 'Rochel ', 'Johan', 'Collaborateur personnel / Reprêsentant de foraus (forum de politique êtrangère)         ', NULL, NULL, 287),
(16, 16, 'Cueni ', 'Thomas', 'Interpharma', 1, 1, 288),
(17, 16, 'Bastian', ' Luc', 'Santésuisse', 1, 2, 289),
(18, 17, 'Mühlethaler ', 'Jan', 'Schweiz. Aussenwirtschaftsförderung (Osec)   ', 13, NULL, 290),
(19, 17, 'Enggasser ', 'Josiane', 'Schweizer Obstverband  Landwirtschaft  ', 15, NULL, 291),
(20, 18, 'Ecoeur ', 'Yves', 'Oeuvre suisse d''entreaide ouvrière (OSEO-SAH)   ', 9, NULL, 293),
(21, 19, 'Tschirky ', 'Erich', 'Schweizerische Gesundheitsliga-Konferenz    ', 1, 3, 129),
(22, 20, 'Dürr ', 'Lucius', 'Schweizerischer Versicherungsverband   ', 9, NULL, 292),
(23, 20, 'Saxer ', 'Mark', 'Furrer, Hugi & Partner / Swiss Police (ICT/SPIK)', 14, NULL, 138),
(24, 21, 'Buser ', 'Christoph', 'Persönlicher Mitarbeiter', NULL, NULL, NULL),
(25, 21, 'Meier', ' Markus', 'Persönlicher Mitarbeiter  ', NULL, NULL, NULL),
(26, 22, 'Miescher ', 'Alex', 'SPORT / SFV', 17, NULL, 294),
(27, 22, 'Grichting ', 'Thomas', 'Groupe Mutuel', 1, 2, 155),
(28, 24, 'Friund ', 'Vinciane', 'Médecins de famille Suisse ', 1, 4, 295),
(29, 24, 'Michaud ', 'Gigon Sophie', 'Pro Natura', 4, NULL, 81),
(30, 25, 'Beglinger ', 'Nick', 'swisscleantech  ', 4, NULL, 140),
(31, 26, 'Leder ', 'Ruedi', 'Gast', NULL, NULL, NULL),
(32, 27, 'Taddei ', 'Marco', 'Schweizerischer Verband freier Berufe', 9, NULL, 26),
(33, 27, 'Bigler ', 'Hans-Ulrich', 'Schweizerischer Gewerbeverband (sgv)', 9, NULL, 37),
(34, 28, 'Rohner ', 'Petra', 'Persönliche Mitarbeiterin', NULL, NULL, NULL),
(35, 29, 'Kaufmann ', 'Ronny', 'Die Schweizerische Post', 14, NULL, 296),
(36, 30, '', '', NULL, NULL, NULL, NULL),
(37, 31, 'Wyss Graber ', 'Andrea', 'Gast', NULL, NULL, NULL),
(38, 32, 'Wilhelm', 'Kurt', 'Sanitas Krankenversicherung', 1, 2, 250),
(39, 32, 'Brogli ', 'Urs', 'FDP Gesunheitskommission', 1, 3, NULL),
(40, 33, 'Schweizer ', 'Thomas', 'Textilverband Schweiz', 9, NULL, 297),
(41, 33, 'Keller', 'Morten', 'Gast', NULL, NULL, NULL),
(42, 34, 'Brunner ', 'Susanne', 'Schweizerischer Versicherungsverband', 9, NULL, 292),
(43, 34, 'Pfenninger ', 'Urs', 'Santésuisse', 1, 2, 289),
(44, 35, 'Güttinger ', 'Doris', 'Fédération suisse des sages-femmes (SHV/FSSF)', 1, 4, 263),
(45, 35, 'Pasquier Maridat ', 'Marie', 'Persönliche Mitarbeiterin', NULL, NULL, NULL),
(46, 36, 'Werder ', 'Christina', 'Schweizerischer Gewerkschaftsbund', 9, NULL, 36),
(47, 36, 'Ambrosetti ', 'Renzo', 'Gewerkschaft Unia', 9, NULL, 298),
(48, 37, 'Spicher ', 'Georges', 'Verband der Schweizerischen Cementindustrie (cemsuisse)', 9, NULL, 275),
(49, 37, 'Weidmann ', 'Yves', 'Schweizerische Bankiervereinigung (SBVg)', 9, NULL, 48),
(50, 38, 'Perron ', 'Louis', 'Perron Campaigns', 9, NULL, 299),
(51, 5, 'Cortesi', 'Linda', 'Gast', NULL, NULL, NULL),
(52, 2, 'Hüssy', 'Hans-Rudolf', 'persönlicher Mitarbeiter', NULL, NULL, NULL),
(53, 6, 'Kläy', 'Dieter', 'Schweizerischer Gewerbeverband (sgv)', 9, NULL, 344),
(54, 6, 'Meier', 'Markus', 'Hauseigentümerverband Kt. BL', 9, NULL, 345),
(55, 7, 'Wetter', 'Miriam', 'Nationale Arbeitsgemeinschaft Suchtpolitik ', 1, NULL, 19),
(56, 7, 'Fischer', 'Eliane', 'Nationale Arbeitsgemeinschaft Suchtpolitik ', 1, NULL, 19),
(57, 10, 'Ramseier Rey', 'Bettina', 'Curaviva', 2, NULL, 342),
(58, 10, 'Wandeler', 'Elisabeth', 'Persönliche Mitarbeiterin', NULL, NULL, NULL),
(59, 19, 'Bienlein', 'Martin', 'H+  Spitäler der Schweiz', 1, NULL, 346),
(60, 23, 'Merkli', 'Christoph', 'Pro Velo Schweiz', 5, NULL, 179),
(61, 23, 'Ziltener', 'Erika', 'Dachverband Schweizerischer Patientenstellen', 1, NULL, 172),
(62, 4, 'Töngi', 'Michael', 'Schweizerischer Mieter- und Mieterinnenverband', 9, NULL, 347);

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `interessenbindungen`
--
ALTER TABLE `interessenbindungen`
  ADD CONSTRAINT `fk_ib_lobbyorg` FOREIGN KEY (`id_lobbyorg`) REFERENCES `lobbyorganisationen` (`id_lobbyorg`),
  ADD CONSTRAINT `fk_ib_lobbygroup` FOREIGN KEY (`id_lobbygroup`) REFERENCES `lobbygruppen` (`id_lobbygroup`),
  ADD CONSTRAINT `fk_ib_lobbytyp` FOREIGN KEY (`id_lobbytyp`) REFERENCES `lobbytypen` (`id_lobbytyp`),
  ADD CONSTRAINT `fk_ib_parlam` FOREIGN KEY (`id_parlam`) REFERENCES `parlamentarier` (`id_parlam`);

--
-- Constraints der Tabelle `lobbygruppen`
--
ALTER TABLE `lobbygruppen`
  ADD CONSTRAINT `fk_lg_lt` FOREIGN KEY (`id_lobbytyp`) REFERENCES `lobbytypen` (`id_lobbytyp`);

--
-- Constraints der Tabelle `lobbyorganisationen`
--
ALTER TABLE `lobbyorganisationen`
  ADD CONSTRAINT `fk_lo_lt` FOREIGN KEY (`id_lobbytyp`) REFERENCES `lobbytypen` (`id_lobbytyp`),
  ADD CONSTRAINT `fk_lo_lg` FOREIGN KEY (`id_lobbygroup`) REFERENCES `lobbygruppen` (`id_lobbygroup`);

--
-- Constraints der Tabelle `zugangsberechtigungen`
--
ALTER TABLE `zugangsberechtigungen`
  ADD CONSTRAINT `fk_zb_lo` FOREIGN KEY (`id_lobbyorg`) REFERENCES `lobbyorganisationen` (`id_lobbyorg`),
  ADD CONSTRAINT `fk_zb_lg` FOREIGN KEY (`id_lobbygroup`) REFERENCES `lobbygruppen` (`id_lobbygroup`),
  ADD CONSTRAINT `fk_zb_lt` FOREIGN KEY (`id_lobbytyp`) REFERENCES `lobbytypen` (`id_lobbytyp`),
  ADD CONSTRAINT `fk_zb_parlam` FOREIGN KEY (`id_parlam`) REFERENCES `parlamentarier` (`id_parlam`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
