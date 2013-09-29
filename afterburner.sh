#!/bin/bash

for file in lobbycontrol-edit/*.php
do
  echo "Process $file";
  mv "$file" "$file.bak";
  # Read file, process regex and write file
  (cat "$file.bak"; echo -e "\n") \
  | perl -p -e"s/\$this->Set(ExportToExcel|ExportToWord|ExportToXml|ExportToCsv|PrinterFriendly|AdvancedSearch|FilterRow)Available\(false\);/$this->Set\1Available(true);/g" \
  | perl -p -e"s/\$this->Set(VisualEffects)Enabled\(false\);/$this->Set\1Enabled(true);/g" \
  | perl -p -e"s/\$result->SetAllowDeleteSelected\(false\);/$result->SetAllowDeleteSelected(true);/g" \
  | perl -p -e"s/(<\?php)/\1\n\/\/ Processed by afterburner.sh/" \
  > "$file";
done

for file in lobbycontrol-edit/components/templates/common/layout.tpl
do
  echo "Process $file";
  mv "$file" "$file.bak";
  (cat "$file.bak"; echo -e "\n") \
  | perl -p -e"s/(<\/head>)/    <link rel=\"shortcut icon\" href=\"favicon.png\" type=\"image\/png\" \/>\n\1/g" \
  > "$file";
done

# Sed: http://www.grymoire.com/Unix/Sed.html
# Perl: http://petdance.com/perl/command-line-options.pdf

# ( Prog1; Prog2; Prog3; ...  ) | ProgN
# ( Prog1 & Prog2 & Prog3 & ... ) | ProgN
