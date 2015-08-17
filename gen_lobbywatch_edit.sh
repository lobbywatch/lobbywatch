#!/bin/bash

#genfile=lobbywatch_bearbeitung_gen.pgtm

cp -a lobbywatch_bearbeitung.old.old.pgtm lobbywatch_bearbeitung.old.old.old.pgtm

cp -a lobbywatch_bearbeitung.old.pgtm lobbywatch_bearbeitung.old.old.pgtm

cp -a lobbywatch_bearbeitung.bak.pgtm lobbywatch_bearbeitung.old.pgtm

cp -a lobbywatch_bearbeitung.pgtm lobbywatch_bearbeitung.bak.pgtm

for file in lobbywatch_bearbeitung.pgtm
do
  echo "Process $file";
  cat "$file" \
  | perl -p -e's/(<(InlineEditColumn|InlineInsertColumn|Column) fieldName="(created_visa|created_date|updated_visa|updated_date)")/\1 readOnly="true" /i' \
  | perl -p -e's/(readOnly="true"\s*)+/readOnly="true" /i' \
  | perl -p -e's/(<.*?readOnly="true".*?)readOnly="true"/\1/i' \
  > "lobbywatch_bearbeitung_gen.pgtm";
done

wine "C:\Program Files (x86)\SQL Maestro Group\PHP Generator for MySQL Professional 14.10.0.6\MyPHPGeneratorPro.exe" "lobbywatch_bearbeitung_gen.pgtm" -output "public_html\bearbeitung" -generate
