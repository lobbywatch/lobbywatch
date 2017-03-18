-- Die SP heisst im PDF auf Französisch abgekürzt PSS und nicht PS, drum:

UPDATE `csvimsne_lobbywatch`.`partei`
SET `abkuerzung_fr` = 'PSS'
WHERE `id` = 3;

-- Die MCG (https://de.wikipedia.org/wiki/Mouvement_citoyens_genevois) heisst in der datenbank MCR. Sie nennt sich scheinbar selbst aber MCG, darum:

UPDATE `csvimsne_lobbywatch`.`partei`
SET `abkuerzung` = 'MCG'
WHERE `id` = 9;

-- Die PdA heisst auf französisch PdT, drum:

UPDATE `csvimsne_lobbywatch`.`partei`
SET `abkuerzung_fr` = 'PdT'
WHERE `id` = 13;
