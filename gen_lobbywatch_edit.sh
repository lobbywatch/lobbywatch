#!/bin/bash

#genfile=lobbywatch_bearbeitung_gen.pgtm

for file in lobbywatch_bearbeitung.pgtm
do
  echo "Process $file";
  cat "$file" \
  | perl -p -e's/(<(InlineEditColumn|InlineInsertColumn|Column) fieldName="(created_visa|created_date|updated_visa|updated_date)")/\1 readOnly="true" /i' \
  | perl -p -e's/(readOnly="true"\s*)+/readOnly="true" /i' \
  | perl -p -e's/(<.*?readOnly="true".*?)readOnly="true"/\1/i' \
  > "lobbywatch_bearbeitung_gen.pgtm";
done

wine "C:\Program Files\SQL Maestro Group\PHP Generator for MySQL Professional 12.8.15\MyPHPGeneratorPro.exe" "Z:\home\rkurmann\dev\web\lobbywatch\lobbydev\lobbywatch_bearbeitung_gen.pgtm" -output "Z:\home\rkurmann\dev\web\lobbywatch\lobbydev\public_html\bearbeitung" -generate
