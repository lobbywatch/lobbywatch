--Die SP heisst im PDF auf Französisch abgekürzt PSS und nicht PS, drum:

UPDATE `csvimsne_lobbywatch`.`partei`
SET `abkuerzung_fr` = 'PSS'
WHERE `id` = 3;

--Die MCG (https://de.wikipedia.org/wiki/Mouvement_citoyens_genevois) heisst in der datenbank MCR. Sie nennt sich scheinbar selbst aber MCG, darum:

UPDATE `csvimsne_lobbywatch`.`partei`
SET `abkuerzung` = 'MCG'
WHERE `id` = 9;

--Die PdA heisst auf französisch PdT, drum:

UPDATE `csvimsne_lobbywatch`.`partei`
SET `abkuerzung_fr` = 'PdT'
WHERE `id` = 13;

-- Jean Christophe Schwaab -> Vorname ist nicht "Jean Christoph", sondern "Christoph" ist zweiter_vorname
UPDATE `csvimsne_lobbywatch`.`parlamentarier`
SET `vorname` = 'Jean'
WHERE `id` = 189;

UPDATE `csvimsne_lobbywatch`.`parlamentarier`
SET `zweiter_vorname` = 'Christophe'
WHERE `id` = 189;

-- Tiana Angelina Moser -> Vorname und Zweiter Vorname trennen
UPDATE `csvimsne_lobbywatch`.`parlamentarier`
SET `vorname` = 'Tiana'
WHERE `id` = 147;

UPDATE `csvimsne_lobbywatch`.`parlamentarier`
SET `zweiter_vorname` = 'Angelina'
WHERE `id` = 147;


-- Kathy Riklin heisst gemäss admin.ch Kathy zum Vornamen (siehe https://www.parlament.ch/en/biografie?CouncillorId=502)
UPDATE `csvimsne_lobbywatch`.`parlamentarier`
SET `vorname` = 'Kathy'
WHERE `id` = 176;
