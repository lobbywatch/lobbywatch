#!/bin/bash

#genfile=lobbycontrol_bearbeitung_gen.pgtm

for file in lobbycontrol_bearbeitung.pgtm
do
  echo "Process $file";
  cat "$file" \
  | perl -p -e's/(<(InlineEditColumn|InlineInsertColumn|Column) fieldName="(created_visa|created_date|updated_visa|updated_date)")/\1 readOnly="true" /i' \
  | perl -p -e's/(readOnly="true"\s*)+/readOnly="true" /i' \
  | perl -p -e's/(<.*?readOnly="true".*?)readOnly="true"/\1/i' \
  > "lobbycontrol_bearbeitung_gen.pgtm";
done

wine "C:\Program Files\SQL Maestro Group\PHP Generator for MySQL\MyPHPGenerator.exe" "Z:\home\rkurmann\dev\web\lobbycontrol\lobbydev\lobbycontrol_bearbeitung_gen.pgtm" -output "Z:\home\rkurmann\dev\web\lobbycontrol\lobbydev\public_html\bearbeitung" -generate
