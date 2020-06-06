#!/bin/bash

#genfile=lobbywatch_bearbeitung_gen.pgtm

cp -a lobbywatch_bearbeitung.old.old.pgtm lobbywatch_bearbeitung.old.old.old.pgtm

cp -a lobbywatch_bearbeitung.old.pgtm lobbywatch_bearbeitung.old.old.pgtm

cp -a lobbywatch_bearbeitung.bak.pgtm lobbywatch_bearbeitung.old.pgtm

# Abort on errors
set -e

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

# winepath
# exiftool -ProductVersion ~/.wine/drive_c/Program\ Files\ \(x86\)/SQL\ Maestro\ Group/PHP\ Generator\ for\ MySQL\ Professional\ 20.5.0.1/MyPHPGeneratorPro.exe

dir="public_html/bearbeitung"

[ -d "$dir/libs" ] && rm -r "$dir/libs"

phpgen_version=$(head -n1 lobbywatch_bearbeitung.pgtm | grep -oP 'version="\K(.+?)(?=")')

wine "C:\Program Files (x86)\SQL Maestro Group\PHP Generator for MySQL Professional 20.5.0.1\MyPHPGeneratorPro.exe" "lobbywatch_bearbeitung_gen.pgtm" -output "$(winepath -w $dir)" -generate

echo -e "<?php\nconst GENERATOR_VERSION = '$phpgen_version';\n\$generator_version = GENERATOR_VERSION;" >public_html/custom/generator_version.php
