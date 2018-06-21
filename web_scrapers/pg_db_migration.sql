ALTER TABLE organisation ADD updated_by_import timestamp NULL;
ALTER TABLE organisation_log ADD updated_by_import timestamp AFTER updated_date NULL;


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

