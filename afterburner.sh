#!/bin/bash

dir=public_html/bearbeitung

rm -rf $dir/templates_c/*

all_files=`find $dir -name "*.php"`;
all_files='';

for file in $all_files
do
  echo "Clean $file";
  mv "$file" "$file.bak";
  # Read file, process regex and write file
  (cat "$file.bak"; echo -e "\n") \
  | perl -0 -p -e's/(\s*\?>\s*)$/\n/s' \
  | dos2unix \
  > "$file";
done

for file in $dir/*.php
do
  echo "Process $file";
  mv "$file" "$file.bak";
  # Read file, process regex and write file
  (cat "$file.bak"; echo -e "\n") \
  | perl -p -e's/\$this->Set(ExportToExcel|ExportToWord|ExportToXml|ExportToCsv|PrinterFriendly|AdvancedSearch|FilterRow)Available\(false\);/\$this->Set\1Available(true);/g' \
  | perl -p -e's/\$this->Set(VisualEffects)Enabled\(false\);/\$this->Set\1Enabled(true);/g' \
  | perl -p -e's/\$result->SetAllowDeleteSelected\(false\);/\$result->SetAllowDeleteSelected(true);/g' \
  | perl -0 -p -e's/(\s*\?>\s*)$//s' \
  | perl -p -e's/MyConnectionFactory(?=\(\))/MyPDOConnectionFactory/g' \
  | perl -0 -p -e's/(?<=CreateRssGenerator\(\)).*?(?=\})/ \{\n            return setupRSS\(\$this, \$this->dataset\);\n        /s' \
  | perl -p -e's/(<\?php)/\1\n\/\/ Processed by afterburner.sh\n\n/' \
  > "$file";
done

# require_once "components\/dataset_rss_generator.php";

for file in $dir/components/page.php
do
  echo "Process $file";
  mv "$file" "$file.bak";
  # Read file, process regex and write file
  (cat "$file.bak"; echo -e "\n") \
  | perl -0 -p -e's/(abstract class Page implements IPage, IVariableContainer)\s*?{/\1\n\{\n    public function getRawCaption\(\) \{dcXXX\("Get " . \$this->raw_caption\);return \$this->raw_caption;\}\n    protected \$raw_caption;/s' \
  | perl -p -e's/(?<=\$this->caption = \$value;)/\n        \$this->raw_caption = \$value;dcXXX\("Set " . \$this->raw_caption\);/' \
  | perl -p -e's/(<\?php)/\1\n\/\/ Processed by afterburner.sh\n\n/' \
  > "$file";
done

for file in $dir/components/rss_feed_generator.php
do
  echo "Process $file";
  mv "$file" "$file.bak";
  # Read file, process regex and write file
  (cat "$file.bak"; echo -e "\n") \
  | perl -p -e's/StringUtils::EscapeXmlString/htmlspecialchars/' \
  | perl -p -e's/(<\?php)/\1\n\/\/ Processed by afterburner.sh\n\n/' \
  > "$file";
done

for file in $dir/components/renderers/rss_renderer.php
do
  echo "Process $file";
  mv "$file" "$file.bak";
  # Read file, process regex and write file
  (cat "$file.bak"; echo -e "\n") \
  | perl -p -e's/(?<=GetRssGenerator\(\);)/\n        header\("Content-Type: application\/rss+xml;charset= utf-8 "\);/' \
  | perl -p -e's/(<\?php)/\1\n\/\/ Processed by afterburner.sh\n\n/' \
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
  | perl -p -e's/(<\?php)/\1\n\/\/ Processed by afterburner.sh\n\nrequire_once dirname(__FILE__) . "\/..\/common\/settings.php";\nrequire_once dirname(__FILE__) . "\/custom\/custom.php";/' \
  | perl -0 -p -e's/(?<=GetGlobalConnectionOptions\(\)).*?(?=\})/\{\n    \/\/ Custom modification: Use \$db_connection from settings.php\n    global \$db_connection;\n    return \$db_connection;\n/s' \
  | perl -p -e's/(\/\/\s*?)(?=defineXXX)//' \
  | perl -p -e's/(\/\/\s*?)(?=error_reporting)//' \
  | perl -p -e's/(\/\/\s*?)(?=ini_set)//' \
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
