#!/bin/bash

# Abort on errors
set -e

PHP=/opt/lampp/bin/php

# diff -urw --exclude=".git" --exclude="*.bak" ../lobbydev_wo_afterburner/ . > afterburner_changes.diff

# TODO use \ only where necessary http://stackoverflow.com/questions/1455988/commenting-in-bash-script

clean="true";
# Ref: http://stackoverflow.com/questions/7069682/how-to-get-arguments-with-flags-in-bash-script
for i in "$@" ; do
      case $i in
                -h|--help)
                        echo "afterburner"
                        echo " "
                        echo "$0 [options]"
                        echo " "
                        echo "options:"
                        echo "-n, --noclean            Do not clean files"
                        exit 0
                        ;;
                -n|--noclean)
                        shift
                        no_clean="false"
                        ;;
                *)
                        break
                        ;;
        esac
done


root_dir=public_html
dir=$root_dir/bearbeitung
auswertung=$root_dir/auswertung

NOW=$(date +"%d.%m.%Y %H:%M");
NOW_SHORT=$(date +"%d.%m.%Y");

echo -e "<?php\n\$build_date = '$NOW';\n\$build_date_short = '$NOW_SHORT';\n\$build_last_commit = '`git rev-parse HEAD`';" > $root_dir/custom/build.php;

./set_lobbywatch_version.sh $root_dir

rm -rf $dir/templates_c/*

all_php_files=`find $dir -name "*.php"`;
#all_php_files='';

# MIGR encoding problem with String.php
if [[ "$clean" = "true" ]] ; then
  for file in $all_php_files
  do
    if [[ $file == public_html/bearbeitung/libs/phpoffice/PHPExcel/Shared/String.php ]]; then
     echo "Skip $file"
     continue
    fi
    echo "Clean $file";
    mv "$file" "$file.bak";
    # Read file, process regex and write file
    (cat "$file.bak"; echo -e "\n") |
     dos2unix |
     perl -0 -p -e's/(\s*\?>\s*)$/\n/s' |
     perl -0 -p -e's/\s*$/\n/s' \
    > "$file";
  done
fi

for file in $dir/*.php
do
  if [[ $file != public_html/bearbeitung/parlamentarier_anhang.php ]] && [[ $file != public_html/bearbeitung/parlamentarier_transparenz.php ]] && ( [[ $file == public_html/bearbeitung/parlamentarier_*.php ]] || [[ $file == public_html/bearbeitung/anteil.php ]] ); then
    echo "Skip $file"
    continue
  fi
  echo "Process $file";
  mv "$file" "$file.bak";
  # Read file, process regex and write file
  # MIGR add_more_navigation_links(\$result); see inside
  cat "$file.bak" |
   perl -p -e's/\$editColumn->SetAllowSetToDefault\(true\);/\$editColumn->SetAllowSetToDefault(false); \/*afterburner*\/ /g' |
   perl -p -e's/^(\s*)(GetApplication\(\)->SetMainPage\(\$Page\);)/\1\2\n\1before_render\(\$Page\); \/*afterburner*\/ /' |
   perl -p -e's/CanLoginAsGuest\(\)\s*\{\s*return true;\s*\}/CanLoginAsGuest\(\) \{ return false;  \/*afterburner*\/ \}/g' |
   perl -p -e's/'\''guest'\''\s*=>\s*new\s+DataSourceSecurityInfo\(\s*true/'\''guest'\'' => new DataSourceSecurityInfo\(GetApplication\(\)->GetOperation\(\) === '\''rss'\'' \/*afterburner*\/ /g' |
   perl -0 -p -e's/(?<=CreateRssGenerator\(\)).*?(?=\})/ \{\n            return setupRSS\(\$this, \$this->dataset\); \/*afterburner*\/ \n        /sg' |
   perl -p -e's/\$env_dir/'\'' \. \$GLOBALS["env_dir"] \/*afterburner*\/  \. '\''/g' |
   perl -p -e's/\$env(?!_dir)/'\'' \. \$GLOBALS["env"] \/*afterburner*\/  \. '\''/g' |
   perl -p -e's/\$rel_files_url/'\'' \. \$GLOBALS["rel_files_url"] \/*afterburner*\/  \. '\''/g' |
   perl -p -e's/\$public_files_dir_rel/'\'' \. \$GLOBALS["public_files_dir_rel"] \/*afterburner*\/  \. '\''/g' |
   perl -p -e's/\$public_files_dir_abs/'\'' \. \$GLOBALS["public_files_dir_abs"] \/*afterburner*\/  \. '\''/g' |
   perl -p -e's/\$public_files_dir/'\'' \. \$GLOBALS["public_files_dir"] \/*afterburner*\/  \. '\''/g' |
   perl -p -e's/\$private_files_dir/'\'' \. \$GLOBALS["private_files_dir"] \/*afterburner*\/  \. '\''/g' |
   perl -p -e's/\$build_date:/'\'' \. \$GLOBALS["build_date"] \/*afterburner*\/  \. '\''/g' |
   perl -p -e's/\$build_date/'\'' \. \$GLOBALS["build_date"] \/*afterburner*\/  \. '\''/g' |
   perl -p -e's/\$build_date_short/'\'' \. \$GLOBALS["build_date_short"] \/*afterburner*\/  \. '\''/g' |
   perl -p -e's/\$deploy_date:/'\'' \. \$GLOBALS["deploy_date"] \/*afterburner*\/  \. '\''/g' |
   perl -p -e's/\$deploy_date/'\'' \. \$GLOBALS["deploy_date"] \/*afterburner*\/  \. '\''/g' |
   perl -p -e's/\$deploy_date_short/'\'' \. \$GLOBALS["deploy_date_short"] \/*afterburner*\/  \. '\''/g' |
   perl -p -e's/\$import_date_wsparlamentch/'\'' \. \$GLOBALS["import_date_wsparlamentch"] \/*afterburner*\/  \. '\''/g' |
   perl -p -e's/\$import_date_wsparlamentch_short/'\'' \. \$GLOBALS["import_date_wsparlamentch_short"] \/*afterburner*\/  \. '\''/g' |
   perl -p -e's/\$build_secs/'\'' \. _custom_page_build_secs() \/*afterburner*\/  \. '\''/g' |
   perl -p -e's/\$version/'\'' \. \$GLOBALS["version"] \/*afterburner*\/  \. '\''/g' |
   perl -p -e's/\$edit_general_hint/'\'' \. \$GLOBALS["edit_general_hint"] \/*afterburner*\/  \. '\''/g' |
   perl -p -e's/\$edit_header_message/'\'' \. \$GLOBALS["edit_header_message"] \/*afterburner*\/  \. '\''/g' |
   perl -p -e's%'\''\[getCustomPagesHeader\(\)\]'\''%getCustomPagesHeader\(\)%g' |
   perl -p -e's%'\''\[getCustomPagesFooter\(\)\]'\''%getCustomPagesFooter\(\)%g' |
   perl -p -e's/<a id="plugin-edit-remarksbox.*?<\/a>//g' |
   perl -p -e's/<img src="img\/icons\/external_link.gif" alt="\(externer Link\)" title="\(externer Link\)" class="icon" height="14" width="15">//g' |
   perl -p -e's/<img src="img\/icons\/external_link.gif" alt="\(externer Link\)" title="\(externer Link\)" class="icon" width="15" height="14">//g' |
   perl -p -e's%(src=")([^"]+)"%\1'\'' . util_data_uri('\''\2'\'') . '\''"%g' |
   perl -p -e's/(^\s*)(\$result->AddPage\(new PageLink\(\$this->GetLocalizerCaptions\(\)->GetMessageString\('\''AdminPage'\''\), '\''phpgen_admin.php'\'', \$this->GetLocalizerCaptions\(\)->GetMessageString\('\''AdminPage'\''\), false, false, '\''Admin area'\''\)\);)/\1\2\n\1\}\n\n            \/\/ MIGR add_more_navigation_links(\$result); \/\/ Afterburned\n\1\{/g' |
   perl -p -e's/(DownloadHTTPHandler\(\$this->dataset, '\''(datei)'\'')/PrivateFile\1/g' |
   perl -p -e's%(\s*)\$editor = new TextAreaEdit\('\''notizen_edit'\'', \d+, \d+\);%$&\n$1\$editor->setPlaceholder\(getNotizenPlaceholder\(\)\); \/\/ Afterburned%g' |
   perl -p -e's/(<\?php)/\1\n\/\/ Processed by afterburner.sh\n\n/' \
  > "$file";
done

for file in $dir/components/page/page.php
do
  echo "Process $file";
  mv "$file" "$file.bak";
  # Read file, process regex and write file
  cat "$file.bak" |
   perl -p -e's/(case OPERATION_DELETE_SELECTED:)/\1\n            case OPERATION_INPUT_FINISHED_SELECTED: \/\/ Afterburner\n            case OPERATION_DE_INPUT_FINISHED_SELECTED: \/\/ Afterburner\n            case OPERATION_CONTROLLED_SELECTED: \/\/ Afterburner\n            case OPERATION_DE_CONTROLLED_SELECTED: \/\/ Afterburner\n            case OPERATION_AUTHORIZATION_SENT_SELECTED: \/\/ Afterburner\n            case OPERATION_DE_AUTHORIZATION_SENT_SELECTED: \/\/ Afterburner\n            case OPERATION_AUTHORIZE_SELECTED: \/\/ Afterburner\n            case OPERATION_DE_AUTHORIZE_SELECTED: \/\/ Afterburner\n            case OPERATION_RELEASE_SELECTED: \/\/ Afterburner\n            case OPERATION_DE_RELEASE_SELECTED: \/\/ Afterburner/' |
   perl -p -e's/(<\?php)/\1\n\/\/ Processed by afterburner.sh/' \
  > "$file";
done

for file in $dir/components/grid/grid.php
do
  echo "Process $file";
  mv "$file" "$file.bak";
  # Read file, process regex and write file
  cat "$file.bak" |
   perl -p -e's|('\''/grid_states/grid_states.php'\'';\s*)$|\1require_once dirname\(__FILE__\) . "/../../../custom/custom_grid_states.php"; // Processed by afterburner.sh\n|' |
   perl -p -e's%^((\s*)OPERATION_DELETE_SELECTED => '\''DeleteSelectedGridState'\'',)%\1\n\2OPERATION_INPUT_FINISHED_SELECTED => '\''InputFinishedSelectedGridState'\'', \/\/ Afterburner\n\2OPERATION_DE_INPUT_FINISHED_SELECTED => '\''DeInputFinishedSelectedGridState'\'', \/\/ Afterburner\n\2OPERATION_CONTROLLED_SELECTED => '\''ControlledSelectedGridState'\'', \/\/ Afterburner\n\2OPERATION_DE_CONTROLLED_SELECTED => '\''DeControlledSelectedGridState'\'', \/\/ Afterburner\n\2OPERATION_AUTHORIZATION_SENT_SELECTED => '\''AuthorizationSentSelectedGridState'\'', \/\/ Afterburner\n\2OPERATION_DE_AUTHORIZATION_SENT_SELECTED => '\''DeAuthorizationSentSelectedGridState'\'', \/\/ Afterburner\n\2OPERATION_AUTHORIZE_SELECTED => '\''AuthorizeSelectedGridState'\'', \/\/ Afterburner\n\2OPERATION_DE_AUTHORIZE_SELECTED => '\''DeAuthorizeSelectedGridState'\'', \/\/ Afterburner\n\2OPERATION_RELEASE_SELECTED => '\''ReleaseSelectedGridState'\'', \/\/ Afterburner\n\2OPERATION_DE_RELEASE_SELECTED => '\''DeReleaseSelectedGridState'\'', \/\/ Afterburner\n\2OPERATION_SET_IMRATBIS_SELECTED => '\''SetImRatBisSelectedGridState'\'', \/\/ Afterburner\n\2OPERATION_CLEAR_IMRATBIS_SELECTED => '\''ClearImRatBisSelectedGridState'\'', \/\/ Afterburner\n\2OPERATION_SET_EHRENAMTLICH_SELECTED => '\''SetEhrenamtlichSelectedGridState'\'', \/\/ Afterburner\n\2OPERATION_SET_ZAHLEND_SELECTED => '\''SetZahlendSelectedGridState'\'', \/\/ Afterburner\n\2OPERATION_SET_BEZAHLT_SELECTED => '\''SetBezahltSelectedGridState'\'', \/\/ Afterburner\n\2OPERATION_CREATE_VERGUETUNGSTRANSPARENZLISTE => '\''CreateVerguetungstransparenzliste'\'', \/\/ Afterburner%' |
   perl -p -e's/(function GetAllowDeleteSelected)/function GetAllowInputFinishedSelected() { \/\/ Afterburner\n      \$columns = \$this->GetEditColumns(); \/\/ Afterburner\n      \$datasetName = preg_replace('\''\/[`]\/i'\'', '\'''\'', \$this->GetDataset()->GetName()); \/\/ Afterburner\n      return (\$this->getMultiEditAllowed()) && is_column_present(\$columns,'\''eingabe_abgeschlossen_datum'\'') && is_column_present(\$columns,'\''eingabe_abgeschlossen_visa'\''); \/\/ Afterburner\n    }\n\n    function GetAllowControlledSelected() { \/\/ Afterburner\n      \$columns = \$this->GetEditColumns(); \/\/ Afterburner\n      \$datasetName = preg_replace('\''\/[`]\/i'\'', '\'''\'', \$this->GetDataset()->GetName()); \/\/ Afterburner\n      return (\$this->getMultiEditAllowed()) && is_column_present(\$columns,'\''kontrolliert_datum'\'') && is_column_present(\$columns,'\''kontrolliert_visa'\'') && isFullWorkflowUser(); \/\/ Afterburner\n    }\n\n    function GetAllowAuthorizationSentSelected() { \/\/ Afterburner\n      \$columns = \$this->GetEditColumns(); \/\/ Afterburner\n      return \$this->GetAllowDeleteSelected() && is_column_present(\$columns,'\''autorisierung_verschickt_datum'\'') && is_column_present(\$columns,'\''autorisierung_verschickt_visa'\'') && isFullWorkflowUser(); \/\/ Afterburner\n    }\n\n    function GetAllowAuthorizeSelected() { \/\/ Afterburner\n      \$columns = \$this->GetEditColumns(); \/\/ Afterburner\n      return \$this->GetAllowDeleteSelected() && is_column_present(\$columns,'\''autorisiert_datum'\'') && is_column_present(\$columns,'\''autorisiert_visa'\'') \/* && is_column_present(\$columns,'\''autorisierung_verschickt_datum'\'') && isFullWorkflowUser() *\/; \/\/ Afterburner\n    }\n\n    function GetAllowReleaseSelected() { \/\/ Afterburner\n      \$columns = \$this->GetEditColumns();\n      \$datasetName = preg_replace('\''\/[`]\/i'\'', '\'''\'', \$this->GetDataset()->GetName()); \/\/ Afterburner\n      return (\$this->getMultiEditAllowed()) && is_column_present(\$columns,'\''freigabe_datum'\'') && is_column_present(\$columns,'\''freigabe_visa'\'') && isFullWorkflowUser(); \/\/ Afterburner\n    }\n\n    function GetAllowImRatBisSelected() { \/\/ Afterburner\n      \$columns = \$this->GetEditColumns();\n      return \$this->GetAllowDeleteSelected() && is_column_present(\$columns,'\''im_rat_bis'\''); \/\/ Afterburner\n    }\n\n    function GetAllowEhrenamtlichSelected() { \/\/ Afterburner\n      \$columns = \$this->GetEditColumns(); \/\/ Afterburner\n      \$datasetName = preg_replace('\''\/[`]\/i'\'', '\'''\'', \$this->GetDataset()->GetName()); \/\/ Afterburner\n      return \$this->GetAllowDeleteSelected() && (\$datasetName == "interessenbindung" || \$datasetName == "mandat"); \/\/ Afterburner\n    }\n\n    function GetAllowBezahltSelected() { \/\/ Afterburner\n      \$columns = \$this->GetEditColumns(); \/\/ Afterburner\n      \$datasetName = preg_replace('\''\/[`]\/i'\'', '\'''\'', \$this->GetDataset()->GetName()); \/\/ Afterburner\n      return \$this->GetAllowDeleteSelected() && (\$datasetName == "interessenbindung" || \$datasetName == "mandat"); \/\/ Afterburner\n    }\n\n    function GetAllowZahlendSelected() { \/\/ Afterburner\n      \$columns = \$this->GetEditColumns(); \/\/ Afterburner\n      \$datasetName = preg_replace('\''\/[`]\/i'\'', '\'''\'', \$this->GetDataset()->GetName()); \/\/ Afterburner\n      return \$this->GetAllowDeleteSelected() && (\$datasetName == "interessenbindung" || \$datasetName == "mandat"); \/\/ Afterburner\n    }\n\n    function GetAllowCreateVerguetungstransparenzliste() { \/\/ Afterburner\n      \$columns = \$this->GetEditColumns(); \/\/ Afterburner\n      \$datasetName = preg_replace('\''\/[`]\/i'\'', '\'''\'', \$this->GetDataset()->GetName()); \/\/ Afterburner\n      return \$this->GetAllowDeleteSelected() && (\$datasetName == "parlamentarier"); \/\/ Afterburner\n    }\n\n    \1/' |
   perl -p -e's%('\''RefreshButton'\'' => \$this->GetShowUpdateLink\(\),)%\1\n                '\''InputFinishedSelectedButton'\'' => \$this->GetAllowInputFinishedSelected(), \/\/ Afterburner\n                '\''ControlledSelectedButton'\'' => \$this->GetAllowControlledSelected(), \/\/ Afterburner\n                '\''AuthorizationSentSelectedButton'\'' => \$this->GetAllowAuthorizationSentSelected(), \/\/ Afterburner\n                '\''AuthorizeSelectedButton'\'' => \$this->GetAllowAuthorizeSelected(), \/\/ Afterburner\n                '\''ReleaseSelectedButton'\'' => \$this->GetAllowReleaseSelected(), \/\/ Afterburner\n                '\''ImRatBisSelectedButton'\'' => \$this->GetAllowImRatBisSelected(), \/\/ Afterburner\n                '\''EhrenamtlichSelectedButton'\'' => \$this->GetAllowEhrenamtlichSelected(), \/\/ Afterburner\n                '\''BezahltSelectedButton'\'' => \$this->GetAllowBezahltSelected(), \/\/ Afterburner\n                '\''ZahlendSelectedButton'\'' => \$this->GetAllowZahlendSelected(), \/\/ Afterburner\n                '\''CreateVerguetungstransparenzListButton'\'' => \$this->GetAllowCreateVerguetungstransparenzliste(), \/\/ Afterburner\n%' |
   perl -p -e's|(\$cellCssStyles = '\'''\'';)|\$cellCssStyles = \[\];  // Processed by afterburner.sh|' |
   perl -p -e's/(<\?php)/\1\n\/\/ Processed by afterburner.sh\n\n/' \
  > "$file";
done

for file in $dir/components/editors/text.php
do
  echo "Process $file";
  mv "$file" "$file.bak";
  # Read file, process regex and write file
  cat "$file.bak" |
   perl -0 -p -e's%public function getEditorName\(\).*\{.*return '\''text'\'';.*?\}%public function getEditorName\(\)\n    \{\n        return '\''../custom_templates/editors/text'\''; \/\/ Afterburner\n    \}%s' |
   perl -p -e's/(<\?php)/\1\n\/\/ Processed by afterburner.sh\n\n/' \
  > "$file";
done

for file in $dir/components/js/pgui.grid.js
do
  echo "Process $file";
  mv "$file" "$file.bak";
  # Read file, process regex and write file
  cat "$file.bak" |
   perl -p -e's%('\''pgui.selection-handler'\'',)%// \1  // Afterburner\n    '\''custom/pgui.selection-handler.ops'\'', // Afterburner%' \
  > "$file";
done

for file in $dir/components/js/main.js
do
  echo "Process $file";
  mv "$file" "$file.bak";
  # Read file, process regex and write file
  cat "$file.bak" |
   perl -p -e's%(\s*)('\''|")(bootstrap)\2,%\1\2\3\2,\n\1\2custom/custom-require\2, // Afterburner%' \
  > "$file";
done

for file in $dir/components/js/pgui.editors.js
do
  echo "Process $file";
  mv "$file" "$file.bak";
  # Read file, process regex and write file
  cat "$file.bak" |
   perl -p -e's%(\s*)(var editorName = \$item\.data\('\''editor'\''\);)%\1\2\n\1editorName = editorName.replace(/\\.\\.\\/custom_templates\\/editors\\//, '\'''\''); // Afterburner%' |
   perl -p -e's%(\s*)(return editorNames\[name\];)%\1var editorName = name.replace(/\\.\\.\\/custom_templates\\/editors\\//, '\'''\''); // Afterburner\n\1return editorNames[editorName]; // Afterburner%' \
  > "$file";
done

for file in $dir/components/rss_feed_generator.php
do
  echo "Process $file";
  mv "$file" "$file.bak";
  # Read file, process regex and write file
  cat "$file.bak" |
   perl -p -e's/StringUtils::EscapeXmlString/htmlspecialchars/' |
   perl -p -e's/(<\?php)/\1\n\/\/ Processed by afterburner.sh\n\n/' \
  > "$file";
done

for file in $dir/components/renderers/rss_renderer.php
do
  echo "Process $file";
  mv "$file" "$file.bak";
  # Read file, process regex and write file
  cat "$file.bak" |
   perl -p -e's/(?<=GetRssGenerator\(\);)/\n        header\("Content-Type: application\/rss+xml;charset= utf-8 "\);/' |
   perl -p -e's/(<\?php)/\1\n\/\/ Processed by afterburner.sh\n\n/' \
  > "$file";
done

for file in $dir/phpgen_settings.php
do
  echo "Process $file";
  mv "$file" "$file.bak";
  cat "$file.bak" |
   perl -p -e's/(<\?php)/\1\n\/\/ Processed by afterburner.sh\n\nrequire_once dirname(__FILE__) . "\/..\/settings\/settings.php";\nrequire_once dirname(__FILE__) . "\/\.\.\/custom\/custom.php";\nrequire_once dirname(__FILE__) . "\/..\/custom\/build.php";\nrequire_once dirname(__FILE__) . "\/..\/common\/utils.php";/' |
   perl -0 -p -e's/(?<=GetGlobalConnectionOptions\(\)).*?(?=\})/\{\n    \/\/ Custom modification: Use \$db_connection from settings.php\n    global \$db_connection;\n    return \$db_connection;\n/s' |
   perl -p -e's/(\/\/\s*?)(?=defineXXX)//' |
   perl -p -e's/(\/\/\s*?)(?=error_reportingXXX)//' |
   perl -p -e's/(\/\/\s*?)(?=ini_setXXX)//' |
   perl -p -e's/(SystemUtils::SetTimeZoneIfNeed)\('\''[^'\'']+'\''\);/\1\('\''Europe\/Zurich'\''\);/' |
   perl -p -e's/^(function GetPageInfos\(\))/\1 { \/\/ Afterburned\n    \$pageInfos = generatedGetPageInfos\(\); \/\/ Afterburned\n    \$pageInfos = customGetPageInfos\(\$pageInfos\); \/\/ Afterburned\n    return \$pageInfos\; \/\/ Afterburned\n}\n\nfunction generatedGetPageInfos\(\) \/\/ Afterburned/g' \
  > "$file";
done

for file in $dir/components/security/user_self_management.php
do
  echo "Process $file";
  mv "$file" "$file.bak";
  cat "$file.bak" |
   perl -p -e's/^((\s*)\$this->ChangePassword\(\$newPassword\);)$/\2checkPasswordStrength(\$newPassword); \/\/ Afterburned\n\1/' \
  > "$file";
done

for file in $dir/components/security/user_management_request_handler.php
do
  echo "Process $file";
  mv "$file" "$file.bak";
  cat "$file.bak" |
   perl -p -e's/^((\s*)return array\('\''id'\'' => \$userId, '\''name'\'' => \$userName, '\''password'\'' => '\''\*\*\*\*\*\*'\''\);)$/\n\2\$userId = \$userId == null || \$userId == '\'''\'' ? getDBConnection()->ExecScalarSQL('\''SELECT MAX(id) FROM user;'\'') : \$userId; \/\/ Afterburned\n\n\1\n/' \
  > "$file";
done

for file in $dir/database_engine/mysql_engine.php $dir/database_engine/commands.php
do
  echo "Process $file";
  mv "$file" "$file.bak";
  cat "$file.bak" |
 perl -p -e's/'\''UPPER\(%s\) LIKE UPPER\(%s\)'\''/'\''%s LIKE %s'\'' \/*afterburner: default is case insensitive (utf8mb4_unicode_ci), no need for UPPER function which stops indexes in MySQL*\//' \
  > "$file";
done

templates_dir=$dir/components/templates
all_tpl_files=`find $templates_dir -name "*.tpl"`;

for file in $all_tpl_files
do
  if [[ $file == public_html/bearbeitung/components/templates/custom_templates/old/* ]]; then
   echo "Skip $file"
   continue
  fi
  echo "Process $file";
  mv "$file" "$file.bak";
  cat "$file.bak" |
   perl -p -e's%('\''|")(forms/field_label.tpl)\1%\1custom_templates/\2\1%g' |
   perl -p -e's%('\''|")(forms/form_footer.tpl)\1%\1custom_templates/\2\1%g' |
   perl -p -e's%('\''|")(forms/actions_edit.tpl)\1%\1custom_templates/\2\1%g' |
   perl -p -e's%('\''|")(list/grid_toolbar.tpl)\1%\1custom_templates/\2\1%g' |
   perl -p -e's%('\''|")(list/grid_column_header.tpl)\1%\1custom_templates/\2\1%g' \
  > "$file";
done


./gen_css.sh

./gen_js.sh

for file in lobbywatch_bearbeitung.pgtm
do
  echo "Process $file";
  cat "$file" |
   perl -p -e's/(login\s*=\s*)".*?"/\1""/ig' |
   perl -p -e's/( password\s*=\s*)".*?"/\1"hidden"/ig' |
   perl -p -e's/(database\s*=\s*)".*?"/\1""/ig' \
  > "lobbywatch_bearbeitung_public.pgtm";
done

git st

# Sed: http://www.grymoire.com/Unix/Sed.html
# Perl: http://petdance.com/perl/command-line-options.pdf

# ( Prog1; Prog2; Prog3; ...  ) | ProgN
# ( Prog1 & Prog2 & Prog3 & ... ) | ProgN
