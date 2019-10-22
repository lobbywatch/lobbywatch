#!/bin/bash

set -e

. common.sh

docker restart orientdb
docker exec -it orientdb bin/console.sh drop database plocal:/orientdb/databases/lw_test admin admin
docker exec -it orientdb bin/console.sh create database plocal:/orientdb/databases/lw_test admin admin PLOCAL GRAPH

echo -e "\n\nImport 'partei' with 'export/node_partei.etl.json'"; docker exec -it orientdb /orientdb/bin/oetl.sh /import/export/node_partei.etl.json
echo -e "\n\nImport 'branche' with 'export/node_branche.etl.json'"; docker exec -it orientdb /orientdb/bin/oetl.sh /import/export/node_branche.etl.json
echo -e "\n\nImport 'interessengruppe' with 'export/node_interessengruppe.etl.json'"; docker exec -it orientdb /orientdb/bin/oetl.sh /import/export/node_interessengruppe.etl.json
echo -e "\n\nImport 'interessenraum' with 'export/node_interessenraum.etl.json'"; docker exec -it orientdb /orientdb/bin/oetl.sh /import/export/node_interessenraum.etl.json
echo -e "\n\nImport 'kommission' with 'export/node_kommission.etl.json'"; docker exec -it orientdb /orientdb/bin/oetl.sh /import/export/node_kommission.etl.json
echo -e "\n\nImport 'organisation' with 'export/node_organisation.etl.json'"; docker exec -it orientdb /orientdb/bin/oetl.sh /import/export/node_organisation.etl.json
echo -e "\n\nImport 'organisation_jahr' with 'export/node_organisation_jahr.etl.json'"; docker exec -it orientdb /orientdb/bin/oetl.sh /import/export/node_organisation_jahr.etl.json
echo -e "\n\nImport 'parlamentarier' with 'export/node_parlamentarier.etl.json'"; docker exec -it orientdb /orientdb/bin/oetl.sh /import/export/node_parlamentarier.etl.json
echo -e "\n\nImport 'fraktion' with 'export/node_fraktion.etl.json'"; docker exec -it orientdb /orientdb/bin/oetl.sh /import/export/node_fraktion.etl.json
echo -e "\n\nImport 'rat' with 'export/node_rat.etl.json'"; docker exec -it orientdb /orientdb/bin/oetl.sh /import/export/node_rat.etl.json
echo -e "\n\nImport 'kanton' with 'export/node_kanton.etl.json'"; docker exec -it orientdb /orientdb/bin/oetl.sh /import/export/node_kanton.etl.json
echo -e "\n\nImport 'kanton_jahr' with 'export/node_kanton_jahr.etl.json'"; docker exec -it orientdb /orientdb/bin/oetl.sh /import/export/node_kanton_jahr.etl.json
echo -e "\n\nImport 'person' with 'export/node_person.etl.json'"; docker exec -it orientdb /orientdb/bin/oetl.sh /import/export/node_person.etl.json
echo -e "\n\nImport 'interessenbindung' with 'export/relationship_interessenbindung.etl.json'"; docker exec -it orientdb /orientdb/bin/oetl.sh /import/export/relationship_interessenbindung.etl.json
echo -e "\n\nImport 'interessenbindung_jahr' with 'export/relationship_interessenbindung_jahr.etl.json'"; docker exec -it orientdb /orientdb/bin/oetl.sh /import/export/relationship_interessenbindung_jahr.etl.json
echo -e "\n\nImport 'in_kommission' with 'export/relationship_in_kommission.etl.json'"; docker exec -it orientdb /orientdb/bin/oetl.sh /import/export/relationship_in_kommission.etl.json
echo -e "\n\nImport 'mandat' with 'export/relationship_mandat.etl.json'"; docker exec -it orientdb /orientdb/bin/oetl.sh /import/export/relationship_mandat.etl.json
echo -e "\n\nImport 'mandat_jahr' with 'export/relationship_mandat_jahr.etl.json'"; docker exec -it orientdb /orientdb/bin/oetl.sh /import/export/relationship_mandat_jahr.etl.json
echo -e "\n\nImport 'organisation_beziehung' with 'export/relationship_organisation_beziehung.etl.json'"; docker exec -it orientdb /orientdb/bin/oetl.sh /import/export/relationship_organisation_beziehung.etl.json
echo -e "\n\nImport 'zutrittsberechtigung' with 'export/relationship_zutrittsberechtigung.etl.json'"; docker exec -it orientdb /orientdb/bin/oetl.sh /import/export/relationship_zutrittsberechtigung.etl.json
echo -e "\n\nImport 'parlamentarier_partei' with 'export/relationship_parlamentarier_partei.etl.json'"; docker exec -it orientdb /orientdb/bin/oetl.sh /import/export/relationship_parlamentarier_partei.etl.json
echo -e "\n\nImport 'parlamentarier_fraktion' with 'export/relationship_parlamentarier_fraktion.etl.json'"; docker exec -it orientdb /orientdb/bin/oetl.sh /import/export/relationship_parlamentarier_fraktion.etl.json
echo -e "\n\nImport 'parlamentarier_rat' with 'export/relationship_parlamentarier_rat.etl.json'"; docker exec -it orientdb /orientdb/bin/oetl.sh /import/export/relationship_parlamentarier_rat.etl.json
echo -e "\n\nImport 'parlamentarier_kanton' with 'export/relationship_parlamentarier_kanton.etl.json'"; docker exec -it orientdb /orientdb/bin/oetl.sh /import/export/relationship_parlamentarier_kanton.etl.json
echo -e "\n\nImport 'organisation_interessengruppe' with 'export/relationship_organisation_interessengruppe.etl.json'"; docker exec -it orientdb /orientdb/bin/oetl.sh /import/export/relationship_organisation_interessengruppe.etl.json
echo -e "\n\nImport 'organisation_interessengruppe2' with 'export/relationship_organisation_interessengruppe2.etl.json'"; docker exec -it orientdb /orientdb/bin/oetl.sh /import/export/relationship_organisation_interessengruppe2.etl.json
echo -e "\n\nImport 'organisation_interessengruppe3' with 'export/relationship_organisation_interessengruppe3.etl.json'"; docker exec -it orientdb /orientdb/bin/oetl.sh /import/export/relationship_organisation_interessengruppe3.etl.json
echo -e "\n\nImport 'organisation_interessenraum' with 'export/relationship_organisation_interessenraum.etl.json'"; docker exec -it orientdb /orientdb/bin/oetl.sh /import/export/relationship_organisation_interessenraum.etl.json
echo -e "\n\nImport 'organisation_jahr' with 'export/relationship_organisation_jahr.etl.json'"; docker exec -it orientdb /orientdb/bin/oetl.sh /import/export/relationship_organisation_jahr.etl.json
echo -e "\n\nImport 'kanton_jahr' with 'export/relationship_kanton_jahr.etl.json'"; docker exec -it orientdb /orientdb/bin/oetl.sh /import/export/relationship_kanton_jahr.etl.json
echo -e "\n\nImport 'interessengruppe_branche' with 'export/relationship_interessengruppe_branche.etl.json'"; docker exec -it orientdb /orientdb/bin/oetl.sh /import/export/relationship_interessengruppe_branche.etl.json
echo -e "\n\nImport 'branche_kommission' with 'export/relationship_branche_kommission.etl.json'"; docker exec -it orientdb /orientdb/bin/oetl.sh /import/export/relationship_branche_kommission.etl.json
echo -e "\n\nImport 'branche_kommission2' with 'export/relationship_branche_kommission2.etl.json'"; docker exec -it orientdb /orientdb/bin/oetl.sh /import/export/relationship_branche_kommission2.etl.json

echo -e"\n\n"
