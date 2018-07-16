USE lobbywat_lobbywatch;
SET @initial_import = NOW();

--Import-Datum als Feld auf organisation setzen
ALTER TABLE organisation ADD COLUMN updated_by_import timestamp NULL AFTER notizen;
ALTER TABLE organisation_log ADD COLUMN updated_by_import timestamp NULL AFTER notizen;

--Import-Datum als Feld auf organisation setzen
ALTER TABLE interessenbindung ADD COLUMN updated_by_import timestamp NULL AFTER notizen;
ALTER TABLE interessenbindung_log ADD COLUMN updated_by_import timestamp NULL AFTER notizen;

--Sekretariat auf Organisation. TODO: Im GUI anzeigen
--Maximale Länge des Sekretariat-Felds im PDF-Import ist 252 Zeichen.
ALTER TABLE organisation ADD COLUMN sekretariat varchar(500) NULL AFTER beschreibung;
ALTER TABLE organisation_log ADD COLUMN sekretariat varchar(500) NULL after beschreibung;

--Namen von Organisationen an Namen im PDF anpassen
UPDATE organisation SET name_de = 'Parlamentarische Gruppe für Bildung, Forschung und Innovation' WHERE name_de = 'Parlamentarische Gruppe Bildung, Forschung und Innovation';
UPDATE organisation SET name_de = 'Parlamentarische Gruppe Drogenpolitik' WHERE name_de = 'Parlamentarische Gruppe für Drogenpolitik';
UPDATE organisation SET name_de = 'Parlamentarische Gruppe ePower - ICT für die Schweiz' WHERE name_de = 'Parlamentarische Gruppe ePower';
UPDATE organisation SET name_de = 'Parlamentarische Gruppe für Ernährungssouveränität' WHERE name_de = 'Parlamentarische Gruppe Ernährungssouveränität';
UPDATE organisation SET name_de = 'Parlamentarische Gruppe Gesundheitspolitik der Bundesversammlung' WHERE name_de = 'Parlamentarische Gruppe Gesundheitspolitik';
UPDATE organisation SET name_de = 'Parlamentarische Gruppe Glasfasernetz Schweiz' WHERE name_de = 'Parlamentarische Gruppe Glasfasernetz';
UPDATE organisation SET name_de = 'Parlamentarische Gruppe Hauptstadtregion Schweiz' WHERE name_de = 'Parlamentarische Gruppe Hauptstadtregion';
UPDATE organisation SET name_de = 'Parlamentarische Gruppe "Internationales Genf"' WHERE name_de = 'Parlamentarische Gruppe Internationales Genf';
UPDATE organisation SET name_de = 'Parlamentarische Gruppe für Jagd und Biodiversität' WHERE name_de = 'Parlamentarische Gruppe Jagd und Biodiversität';
UPDATE organisation SET name_de = 'Parlamentarische Gruppe Medien und Kommerzielle Kommunikation' WHERE name_de = 'Parlamentarische Gruppe Medien und Kommerzielle Kommunikation (GMK)';
UPDATE organisation SET name_de = 'Parlamentarische Gruppe Musik PGM' WHERE name_de = 'Parlamentarische Gruppe Musik';
UPDATE organisation SET name_de = 'Parlamentarische Gruppe für öffentlich-private Partnerschaften PPP' WHERE name_de = 'Parlamentarische Gruppe für öffentlich-private Partnerschaften';
UPDATE organisation SET name_de = 'Parlamentarische Gruppe Rock/Pop im Bundeshaus' WHERE name_de = 'Parlamentarische Gruppe  Rock/Pop im Bundeshaus';
UPDATE organisation SET name_de = 'Parlamentarische Freundschaftsgruppe Schweiz - Katalonien' WHERE name_de = 'Parlamentarische Freundschaftsgruppe Schweiz–Katalonien';
UPDATE organisation SET name_de = 'Parlamentarische Freundschaftsgruppe Schweiz - Ukraine' WHERE name_de = 'Parlamentarische Gruppe Schweiz - Ukraine';
UPDATE organisation SET name_de = 'Parlamentarische Gruppe Naher Osten' WHERE name_de = 'Parlamentarische Gruppe Schweiz - Naher Osten';
UPDATE organisation SET name_de = 'Parlamentarische Gruppe Green Cross Schweiz' WHERE name_de = 'Grünes Kreuz Schweiz, Parlamentarische Gruppe';

--Doppelte Interessenbindungen löschen
--Query zur Analyse: 
--select i1.id as InteressenbindungId, organisation.name_de as ParlamentarischeGruppe, CONCAT(parlamentarier.vorname, ' ', parlamentarier.nachname) as Parlamentarier_in, i1.von, i1.bis, count(interessenbindung_jahr.id) as InteressenbindungJahre from interessenbindung i1 inner join interessenbindung i2 inner join organisation on i1.organisation_id = organisation.id inner join parlamentarier on i1.parlamentarier_id = parlamentarier.id left outer join interessenbindung_jahr on interessenbindung_jahr.interessenbindung_id = i1.id where i1.parlamentarier_id = i2.parlamentarier_id and i1.organisation_id = i2.organisation_id and i1.id <> i2.id and organisation.rechtsform = "Parlamentarische Gruppe" group by i1.id, organisation.name_de, parlamentarier.vorname, parlamentarier.nachname, i1.von, i1.bis order by organisation.name_de, parlamentarier.nachname;
DELETE FROM interessenbindung_jahr WHERE id = 679;
DELETE FROM interessenbindung WHERE id IN (5186, 5673, 5675, 5686, 2195, 4526, 4823);

--Auf allen bestehenden Interessenbindungen mit parlamentarischen Gruppen ein udpate_by_import datum setzen,
--damit sie vom Script gemanaged werden
UPDATE interessenbindung ib
INNER JOIN organisation org ON ib.organisation_id = org.id 
SET org.updated_by_import = @initial_import 
WHERE org.rechtsform = 'Parlamentarische Gruppe'




