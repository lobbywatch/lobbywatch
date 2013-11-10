#!/bin/bash

dir=public_html/bearbeitung

for file in $dir/*.php
do
  echo "Process $file";
  mv "$file" "$file.bak";
  # Read file, process regex and write file
  (cat "$file.bak"; echo -e "\n") \
  | perl -p -e's/\$this->Set(ExportToExcel|ExportToWord|ExportToXml|ExportToCsv|PrinterFriendly|AdvancedSearch|FilterRow)Available\(false\);/\$this->Set\1Available(true);/g' \
  | perl -p -e's/\$this->Set(VisualEffects)Enabled\(false\);/\$this->Set\1Enabled(true);/g' \
  | perl -p -e's/\$result->SetAllowDeleteSelected\(false\);/\$result->SetAllowDeleteSelected(true);/g' \
  | perl -p -e's/(\s*\?>\s*)//m' \
  | perl -p -e's/(<\?php)/\1\n\/\/ Processed by afterburner.sh/' \
  > "$file";
done

for file in $dir/components/templates/common/layout.tpl
do
  echo "Process $file";
  mv "$file" "$file.bak";
  (cat "$file.bak"; echo -e "\n") \
  | perl -p -e's/(<\/head>)/    <link rel="shortcut icon" href="favicon.png" type="image\/png" \/>\n\1/g' \
  > "$file";
done

for file in $dir/phpgen_settings.php
do
  echo "Process $file";
  mv "$file" "$file.bak";
  (cat "$file.bak"; echo -e "\n") \
  | perl -p -e's/(<\?php)/\1\n\/\/ Processed by afterburner.sh\n\nrequire_once "..\/common\/settings.php";\nrequire_once "custom\/custom.php";/' \
  | perl -0 -p -e's/(?<=GetGlobalConnectionOptions\(\)).*?(?=\})/ {\n    \/\/ Custom modification: Use \$db_connection from settings.php\n    global \$db_connection;\n    return \$db_connection;\n/s' \
  > "$file";
done

for file in $dir/authorization.php
do
  echo "Process $file";
  mv "$file" "$file.bak";
  (cat "$file.bak"; echo -e "\n") \
  | perl -0 -p -e's/\$users = array.*?;/\/\/ Custom modification: Use \$users form settings.php/s' \
  > "$file";
done

for file in lobbycontrol_bearbeitung.pgtm
do
  echo "Process $file";
  (cat "$file"; echo -e "\n") \
  | perl -p -e's/(login\s*=\s*)".*?"/\1""/ig' \
  | perl -p -e's/(password\s*=\s*)".*?"/\1"hidden"/ig' \
  | perl -p -e's/(database\s*=\s*)".*?"/\1""/ig' \
  > "lobbycontrol_bearbeitung_public.pgtm";
done

# Sed: http://www.grymoire.com/Unix/Sed.html
# Perl: http://petdance.com/perl/command-line-options.pdf

# ( Prog1; Prog2; Prog3; ...  ) | ProgN
# ( Prog1 & Prog2 & Prog3 & ... ) | ProgN
