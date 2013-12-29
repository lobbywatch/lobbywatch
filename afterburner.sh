#!/bin/bash

root_dir=public_html
dir=$root_dir/bearbeitung
auswertung=$root_dir/auswertung

NOW=$(date +"%d.%m.%Y %H:%M");
NOW_SHORT=$(date +"%d.%m.%Y");

echo -e "<?php\n\$build_date = '$NOW';\n\$build_date_short = '$NOW_SHORT';" > $root_dir/common/build_date.php;

# Also in deploy.sh
VERSION=$(git describe --abbrev=0 --tags)
echo -e "<?php\n\$version = '$VERSION';" >  $root_dir/common/version.php;

rm -rf $dir/templates_c/*

all_files=`find $dir -name "*.php"`;
#all_files='';

for file in $all_files
do
  echo "Clean $file";
  mv "$file" "$file.bak";
  # Read file, process regex and write file
  (cat "$file.bak"; echo -e "\n") \
  | dos2unix \
  | perl -0 -p -e's/(\s*\?>\s*)$/\n/s' \
  | perl -0 -p -e's/\s*$/\n/s' \
  > "$file";
done

for file in $dir/*.php
do
  echo "Process $file";
  mv "$file" "$file.bak";
  # Read file, process regex and write file
  cat "$file.bak" \
  | perl -p -e's/^(\s*)(GetApplication\(\)->SetMainPage\(\$Page\);)/\1\2\n\1before_render\(\$Page\);/' \
  | perl -p -e's/CanLoginAsGuest\(\)\s*\{\s*return true;\s*\}/CanLoginAsGuest\(\) \{ return false; \}/g' \
  | perl -p -e's/'\''guest'\''\s*=>\s*new\s+DataSourceSecurityInfo\(\s*true/'\''guest'\'' => new DataSourceSecurityInfo\(GetApplication\(\)->GetOperation\(\) === '\''rss'\''/g' \
  | perl -0 -p -e's/(?<=CreateRssGenerator\(\)).*?(?=\})/ \{\n            return setupRSS\(\$this, \$this->dataset\);\n        /sg' \
  | perl -p -e's/\$env_dir/'\'' \. \$GLOBALS["env_dir"] \. '\''/g' \
  | perl -p -e's/\$env(?!_dir)/'\'' \. \$GLOBALS["env"] \. '\''/g' \
  | perl -p -e's/\$public_files_dir/'\'' \. \$GLOBALS["public_files_dir"] \. '\''/g' \
  | perl -p -e's/\$private_files_dir/'\'' \. \$GLOBALS["private_files_dir"] \. '\''/g' \
  | perl -p -e's/\$build_date:\$/'\'' \. \$GLOBALS["build_date"] \. '\''/g' \
  | perl -p -e's/\$deploy_date:\$/'\'' \. \$GLOBALS["deploy_date"] \. '\''/g' \
  | perl -p -e's/\$version/'\'' \. \$GLOBALS["version"] \. '\''/g' \
  | perl -p -e's/\$edit_general_hint/'\'' \. \$GLOBALS["edit_general_hint"] \. '\''/g' \
  | perl -p -e's/\$edit_header_message/'\'' \. \$GLOBALS["edit_header_message"] \. '\''/g' \
  | perl -p -e's/<a id="plugin-edit-remarksbox.*?<\/a>//g' \
  | perl -p -e's/<img src="img\/icons\/external_link.gif" alt="\(externer Link\)" title="\(externer Link\)" class="icon" height="14" width="15">//g' \
  | perl -p -e's/<img src="img\/icons\/external_link.gif" alt="\(externer Link\)" title="\(externer Link\)" class="icon" width="15" height="14">//g' \
  | perl -p -e's/(<\?php)/\1\n\/\/ Processed by afterburner.sh\n\n/' \
  > "$file";
done
# RSS
#   | perl -p -e's/CanLoginAsGuest\(\)\s*\{\s*return true;\s*\}/CanLoginAsGuest\(\) \{ return false; \}/g' \
#   | perl -p -e's/'\''guest'\''\s*=>\s*new\s+DataSourceSecurityInfo\(\s*true/'\''guest'\'' => new DataSourceSecurityInfo\(GetApplication\(\)->GetOperation\(\) === '\''rss'\''/g' \
#   | perl -0 -p -e's/(?<=CreateRssGenerator\(\)).*?(?=\})/ \{\n            return setupRSS\(\$this, \$this->dataset\);\n        /s' \

# before pro version
#   | perl -p -e's/\$this->Set(ExportToExcel|ExportToWord|ExportToXml|ExportToCsv|PrinterFriendly|AdvancedSearch|FilterRow)Available\(false\);/\$this->Set\1Available(true);/g' \
#   | perl -p -e's/\$this->Set(VisualEffects)Enabled\(false\);/\$this->Set\1Enabled(true);/g' \
#   | perl -p -e's/(?<=\$result->SetUseImagesForActions\(\)false/true/g' \
#   | perl -p -e's/\$result->SetAllowDeleteSelected\(false\);/\$result->SetAllowDeleteSelected(true);/g' \
#   | perl -p -e's/MyConnectionFactory(?=\(\))/MyPDOConnectionFactory/g' \

# end before pro version
#   | perl -0 -p -e's/(\s*\?>\s*)$//s' \

# $root_dir/*.php $dir/*.php $auswertung/*.php
# for file in "" # XXX
# do
#   echo "Process $file";
#   mv "$file" "$file.bak";
#   # Read file, process regex and write file
#   (cat "$file.bak"; echo -e "\n") \
#   | perl -p -e"s/(?<=\\\$build_date:).*?\\\$/ $NOW\\\$/" \
#   > "$file";
# done

# for file in $dir/components/page.php
# do
#   echo "Process $file";
#   mv "$file" "$file.bak";
#   # Read file, process regex and write file
#   cat "$file.bak" \
#   | perl -0 -p -e's/(abstract class Page implements IPage, IVariableContainer)\s*?{/\1\n\{\n    public function getRawCaption\(\) \{dcXXX\("Get " . \$this->raw_caption\);return \$this->raw_caption;\}\n    protected \$raw_caption;/s' \
#   | perl -p -e's/(?<=\$this->caption = \$value;)/\n        \$this->raw_caption = \$value;dcXXX\("Set " . \$this->raw_caption\);/' \
#   | perl -p -e's/(<\?php)/\1\n\/\/ Processed by afterburner.sh\n\n/' \
#   > "$file";
# done

for file in $dir/components/page.php
do
  echo "Process $file";
  mv "$file" "$file.bak";
  # Read file, process regex and write file
  cat "$file.bak" \
  | perl -p -e's/(case OPERATION_DELETE_SELECTED:)/\1\n            case OPERATION_AUTHORIZE_SELECTED: \/\/ Afterburner\n            case OPERATION_DE_AUTHORIZE_SELECTED: \/\/ Afterburner\n            case OPERATION_RELEASE_SELECTED: \/\/ Afterburner\n            case OPERATION_DE_RELEASE_SELECTED: \/\/ Afterburner/' \
  | perl -p -e's/(<\?php)/\1\n\/\/ Processed by afterburner.sh\n\n/' \
  > "$file";
done

for file in $dir/components/grid/grid.php
do
  echo "Process $file";
  mv "$file" "$file.bak";
  # Read file, process regex and write file
  cat "$file.bak" \
  | perl -p -e's/('\''SetDefaultCheckBoxName'\'' => \$column->GetFieldName\(\) \. '\''_def'\'')/\1,\n                '\''Hint'\'' => isset(\$GLOBALS['\''customParams'\'']['\''Hints'\''][\$column->GetFieldName()]) ? \$GLOBALS['\''customParams'\'']['\''Hints'\''][\$column->GetFieldName()] : null, \/\/ Afterburner/' \
  | perl -p -e's/(case OPERATION_DELETE_SELECTED:)/case OPERATION_AUTHORIZE_SELECTED: \/\/ Afterburner\n                \$this->gridState = new AuthorizeSelectedGridState(\$this); \/\/ Afterburner\n                break;\n            case OPERATION_DE_AUTHORIZE_SELECTED: \/\/ Afterburner\n                \$this->gridState = new DeAuthorizeSelectedGridState(\$this);
                break;\n            case OPERATION_RELEASE_SELECTED: \/\/ Afterburner\n                \$this->gridState = new ReleaseSelectedGridState(\$this); \/\/ Afterburner\n                break;
            case OPERATION_DE_RELEASE_SELECTED: \/\/ Afterburner\n                \$this->gridState = new DeReleaseSelectedGridState(\$this); \/\/ Afterburner\n                break;\n            case OPERATION_SET_IMRATBIS_SELECTED: \/\/ Afterburner\n                \$this->gridState = new SetImRatBisSelectedGridState(\$this); \/\/ Afterburner\n                break;
            case OPERATION_CLEAR_IMRATBIS_SELECTED: \/\/ Afterburner\n                \$this->gridState = new ClearImRatBisSelectedGridState(\$this); \/\/ Afterburner\n                break;\n            \1/' \
  | perl -p -e's/(function GetAllowDeleteSelected)/function GetAllowAuthorizeSelected() { \/\/ Afterburner\n      \$columns = \$this->GetEditColumns(); \/\/ Afterburner\n      return \$this->GetAllowDeleteSelected() && is_column_present(\$columns,'\''autorisiert_datum'\'') && is_column_present(\$columns,'\''autorisiert_visa'\''); \/\/ Afterburner\n    }\n\n    function GetAllowReleaseSelected() { \/\/ Afterburner\n      \$columns = \$this->GetEditColumns();
      return \$this->GetAllowDeleteSelected() && is_column_present(\$columns,'\''freigabe_datum'\'') && is_column_present(\$columns,'\''freigabe_visa'\''); \/\/ Afterburner\n    }\n\n    function GetAllowImRatBisSelected() { \/\/ Afterburner\n      \$columns = \$this->GetEditColumns();
      return \$this->GetAllowDeleteSelected() && is_column_present(\$columns,'\''im_rat_bis'\''); \/\/ Afterburner\n    }\n    \1/' \
  | perl -p -e's/('\''DeleteSelectedButton'\'' => \$this->GetAllowDeleteSelected\(\))/\1,\n                '\''AuthorizeSelectedButton'\'' => \$this->GetAllowAuthorizeSelected(), \/\/ Afterburner\n                '\''ReleaseSelectedButton'\'' => \$this->GetAllowReleaseSelected(), \/\/ Afterburner\n                '\''ImRatBisSelectedButton'\'' => \$this->GetAllowImRatBisSelected(), \/\/ Afterburner\n/' \
  | perl -p -e's/(<\?php)/\1\n\/\/ Processed by afterburner.sh\n\n/' \
  > "$file";
done
#  | perl -p -e's/('\''SetDefaultCheckBoxName'\'' => \$column->GetFieldName\(\) \. '\''_def'\'')/\1,\n                '\''Hint'\'' => \$GLOBALS['\''customParams'\'']['\''Hints'\''][\$column->GetFieldName()], \/\/ Afterburner/' \
# \n        '\''Hint'\'' => $GLOBALS['\''customParams'\'']['\''Hints'\''][$column-X>GetFieldName()], \/\/ Afterburner/

# 'Grid' => $this->Render($page->GetGrid()),
for file in $dir/components/renderers/edit_renderer.php $dir/components/renderers/insert_renderer.php
do
  echo "Process $file";
  mv "$file" "$file.bak";
  # Read file, process regex and write file
  cat "$file.bak" \
  | perl -p -e's/\$this->Render\(\$page->GetGrid\(\)\)/\$this->Render(\$page->GetGrid(), true, true, \$GLOBALS['\''customParams'\''])  \/* Afterburner *\//' \
  | perl -p -e's/(<\?php)/\1\n\/\/ Processed by afterburner.sh\n\n/' \
  > "$file";
done

for file in $dir/components/js/pgui.insert-page-main.js $dir/components/js/pgui.edit-page-main.js
do
  echo "Process $file";
  mv "$file" "$file.bak";
  # Read file, process regex and write file
  cat "$file.bak" \
  | perl -p -e's/(?<=require\('\''pgui.forms'\''\))/,\n        \/\/ Afterburner\n        hints       = require('\''..\/templates\/custom_templates\/js\/custom.hints'\'')/' \
  > "$file";
done


for file in $dir/components/rss_feed_generator.php
do
  echo "Process $file";
  mv "$file" "$file.bak";
  # Read file, process regex and write file
  cat "$file.bak" \
  | perl -p -e's/StringUtils::EscapeXmlString/htmlspecialchars/' \
  | perl -p -e's/(<\?php)/\1\n\/\/ Processed by afterburner.sh\n\n/' \
  > "$file";
done

for file in $dir/components/renderers/rss_renderer.php
do
  echo "Process $file";
  mv "$file" "$file.bak";
  # Read file, process regex and write file
  cat "$file.bak" \
  | perl -p -e's/(?<=GetRssGenerator\(\);)/\n        header\("Content-Type: application\/rss+xml;charset= utf-8 "\);/' \
  | perl -p -e's/(<\?php)/\1\n\/\/ Processed by afterburner.sh\n\n/' \
  > "$file";
done

for file in $dir/phpgen_settings.php
do
  echo "Process $file";
  mv "$file" "$file.bak";
  cat "$file.bak" \
  | perl -p -e's/(<\?php)/\1\n\/\/ Processed by afterburner.sh\n\nrequire_once dirname(__FILE__) . "\/..\/settings\/settings.php";\nrequire_once dirname(__FILE__) . "\/\.\.\/custom\/custom.php";\nrequire_once dirname(__FILE__) . "\/..\/common\/build_date.php";\nrequire_once dirname(__FILE__) . "\/..\/common\/utils.php";/' \
  | perl -0 -p -e's/(?<=GetGlobalConnectionOptions\(\)).*?(?=\})/\{\n    \/\/ Custom modification: Use \$db_connection from settings.php\n    global \$db_connection;\n    return \$db_connection;\n/s' \
  | perl -p -e's/(\/\/\s*?)(?=defineXXX)//' \
  | perl -p -e's/(\/\/\s*?)(?=error_reportingXXX)//' \
  | perl -p -e's/(\/\/\s*?)(?=ini_setXXX)//' \
  | perl -p -e's/Handler\(\$page, \$rowData/Handler\(\$page, &\$rowData/g' \
  > "$file";
done

for file in $dir/authorization.php
do
  echo "Process $file";
  mv "$file" "$file.bak";
  cat "$file.bak" \
  | perl -0 -p -e's/\$users = array.*?;/\/\/ Custom modification: Use \$users form settings.php/s' \
  > "$file";
done

for file in *.pgtm
do
  echo "Process $file";
  mv "$file" "$file.bak";
  cat "$file.bak" \
  | perl -0 -p -e's/^(<Project)/<?xml version="1.0" encoding="ISO-8859-1"?>\n\1/is' \
  > "$file";
done

for file in lobbywatch_bearbeitung.pgtm
do
  echo "Process $file";
  cat "$file" \
  | perl -p -e's/(login\s*=\s*)".*?"/\1""/ig' \
  | perl -p -e's/(password\s*=\s*)".*?"/\1"hidden"/ig' \
  | perl -p -e's/(database\s*=\s*)".*?"/\1""/ig' \
  > "lobbywatch_bearbeitung_public.pgtm";
done

git st

# Sed: http://www.grymoire.com/Unix/Sed.html
# Perl: http://petdance.com/perl/command-line-options.pdf

# ( Prog1; Prog2; Prog3; ...  ) | ProgN
# ( Prog1 & Prog2 & Prog3 & ... ) | ProgN
