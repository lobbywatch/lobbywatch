-- Die SP heisst im PDF auf Französisch abgekürzt PSS und nicht PS, drum:
UPDATE `partei`
SET `abkuerzung_fr` = 'PSS'
WHERE `id` = 3;

-- Die MCG (https://de.wikipedia.org/wiki/Mouvement_citoyens_genevois) heisst in der datenbank MCR. Sie nennt sich scheinbar selbst aber MCG, darum:
UPDATE `partei`
SET `abkuerzung` = 'MCG'
WHERE `id` = 9;

-- Die PdA heisst auf französisch PdT, drum:
UPDATE `partei`
SET `abkuerzung_fr` = 'PdT'
WHERE `id` = 13;

-- Der Mensch heisst Bernhard, nicht Bernard (Siehe http://www.sgv-usam.ch/verband/geschaeftsstelle/team.html)
UPDATE `person`
SET `vorname` = 'Bernhard'
WHERE `id` = 207;

-- Der Mensch wird im PDF als "Ruedi" ausgewiesen, drum kommt hier analog zu anderen der Spitzname dazu (vorher war nur "Rudolf")
UPDATE `person`
SET `vorname` = 'Rudolf "Ruedi"'
WHERE `id` = 121;

-- Der Mensch wird im PDF als "Tim" ausgewiesen, drum kommt hier analog zu anderen der Spitzname dazu (vorher war nur "Timotheos")
UPDATE `person`
SET `vorname` = 'Timotheos "Tim"'
WHERE `id` = 269;
