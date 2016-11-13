#!/bin/bash

PHP=/opt/lampp/bin/php

## https://www.sqlmaestro.com/products/mysql/phpgenerator/help/02_04_00_style_sheets_internals/
## https://github.com/dotless/dotless/wiki/Using-.less
## http://lesscss.org/features/#features-overview-feature
#WINE="wine"
#WINE_LESS=~/.wine/drive_c/Program\ Files\ \(x86\)/Common\ Files/SQL\ Maestro\ Group/DotLess/dotless.Compiler.exe

# diff -urw --exclude=".git" --exclude="*.bak" ../lobbydev_wo_afterburner/ . > afterburner_changes.diff

clean="true";
#fast="--exclude-from $(readlink -m ./rsync-fast-exclude)"
#absolute_path=$(readlink -m /home/nohsib/dvc/../bop)
# Ref: http://stackoverflow.com/questions/7069682/how-to-get-arguments-with-flags-in-bash-script
while test $# -gt 0; do
        case "$1" in
                -h|--help)
                        echo "afterburner"
                        echo " "
                        echo "$0 [options]"
                        echo " "
                        echo "options:"
                        echo "-n, --no-clean            Do not clean files"
                        exit 0
                        ;;
                -n|--no-cealn)
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

echo -e "<?php\n\$build_date = '$NOW';\n\$build_date_short = '$NOW_SHORT';" > $root_dir/common/build_date.php;

# Also in deploy.sh
# VERSION=$(git describe --abbrev=0 --tags)
# echo -e "<?php\n\$version = '$VERSION';" >  $root_dir/common/version.php;
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
    (cat "$file.bak"; echo -e "\n") \
    | dos2unix \
    | perl -0 -p -e's/(\s*\?>\s*)$/\n/s' \
    | perl -0 -p -e's/\s*$/\n/s' \
    > "$file";
  done
fi

for file in $dir/*.php
do
  if [[ $file == public_html/bearbeitung/parlamentarier_preview.php ]] || [[ $file == public_html/bearbeitung/anteil.php ]]; then
    echo "Skip $file"
    continue
  fi
  echo "Process $file";
  mv "$file" "$file.bak";
  # Read file, process regex and write file
  # MIGR add_more_navigation_links(\$result); see inside
  cat "$file.bak" \
  | perl -p -e's/\$editColumn->SetAllowSetToDefault\(true\);/\$editColumn->SetAllowSetToDefault(false); \/*afterburner*\/ /g' \
  | perl -p -e's/^(\s*)(GetApplication\(\)->SetMainPage\(\$Page\);)/\1\2\n\1before_render\(\$Page\); \/*afterburner*\/ /' \
  | perl -p -e's/CanLoginAsGuest\(\)\s*\{\s*return true;\s*\}/CanLoginAsGuest\(\) \{ return false;  \/*afterburner*\/ \}/g' \
  | perl -p -e's/'\''guest'\''\s*=>\s*new\s+DataSourceSecurityInfo\(\s*true/'\''guest'\'' => new DataSourceSecurityInfo\(GetApplication\(\)->GetOperation\(\) === '\''rss'\'' \/*afterburner*\/ /g' \
  | perl -0 -p -e's/(?<=CreateRssGenerator\(\)).*?(?=\})/ \{\n            return setupRSS\(\$this, \$this->dataset\); \/*afterburner*\/ \n        /sg' \
  | perl -p -e's/\$env_dir/'\'' \. \$GLOBALS["env_dir"] \/*afterburner*\/  \. '\''/g' \
  | perl -p -e's/\$env(?!_dir)/'\'' \. \$GLOBALS["env"] \/*afterburner*\/  \. '\''/g' \
  | perl -p -e's/\$rel_files_url/'\'' \. \$GLOBALS["rel_files_url"] \/*afterburner*\/  \. '\''/g' \
  | perl -p -e's/\$public_files_dir_rel/'\'' \. \$GLOBALS["public_files_dir_rel"] \/*afterburner*\/  \. '\''/g' \
  | perl -p -e's/\$public_files_dir_abs/'\'' \. \$GLOBALS["public_files_dir_abs"] \/*afterburner*\/  \. '\''/g' \
  | perl -p -e's/\$public_files_dir/'\'' \. \$GLOBALS["public_files_dir"] \/*afterburner*\/  \. '\''/g' \
  | perl -p -e's/\$private_files_dir/'\'' \. \$GLOBALS["private_files_dir"] \/*afterburner*\/  \. '\''/g' \
  | perl -p -e's/\$build_date:/'\'' \. \$GLOBALS["build_date"] \/*afterburner*\/  \. '\''/g' \
  | perl -p -e's/\$build_date/'\'' \. \$GLOBALS["build_date"] \/*afterburner*\/  \. '\''/g' \
  | perl -p -e's/\$build_date_short/'\'' \. \$GLOBALS["build_date_short"] \/*afterburner*\/  \. '\''/g' \
  | perl -p -e's/\$deploy_date:/'\'' \. \$GLOBALS["deploy_date"] \/*afterburner*\/  \. '\''/g' \
  | perl -p -e's/\$deploy_date/'\'' \. \$GLOBALS["deploy_date"] \/*afterburner*\/  \. '\''/g' \
  | perl -p -e's/\$deploy_date_short/'\'' \. \$GLOBALS["deploy_date_short"] \/*afterburner*\/  \. '\''/g' \
  | perl -p -e's/\$import_date_wsparlamentch/'\'' \. \$GLOBALS["import_date_wsparlamentch"] \/*afterburner*\/  \. '\''/g' \
  | perl -p -e's/\$import_date_wsparlamentch_short/'\'' \. \$GLOBALS["import_date_wsparlamentch_short"] \/*afterburner*\/  \. '\''/g' \
  | perl -p -e's/\$build_secs/'\'' \. _custom_page_build_secs() \/*afterburner*\/  \. '\''/g' \
  | perl -p -e's/\$version/'\'' \. \$GLOBALS["version"] \/*afterburner*\/  \. '\''/g' \
  | perl -p -e's/\$edit_general_hint/'\'' \. \$GLOBALS["edit_general_hint"] \/*afterburner*\/  \. '\''/g' \
  | perl -p -e's/\$edit_header_message/'\'' \. \$GLOBALS["edit_header_message"] \/*afterburner*\/  \. '\''/g' \
  | perl -p -e's%'\''\[getCustomPagesHeader\(\)\]'\''%getCustomPagesHeader\(\)%g' \
  | perl -p -e's%'\''\[getCustomPagesFooter\(\)\]'\''%getCustomPagesFooter\(\)%g' \
  | perl -p -e's/<a id="plugin-edit-remarksbox.*?<\/a>//g' \
  | perl -p -e's/<img src="img\/icons\/external_link.gif" alt="\(externer Link\)" title="\(externer Link\)" class="icon" height="14" width="15">//g' \
  | perl -p -e's/<img src="img\/icons\/external_link.gif" alt="\(externer Link\)" title="\(externer Link\)" class="icon" width="15" height="14">//g' \
  | perl -p -e's%(src=")([^"]+)"%\1'\'' . util_data_uri('\''\2'\'') . '\''"%g' \
  | perl -p -e's/^((\s*)\$this->userIdentityStorage->ClearUserIdentity\(\);)/\2session_unset(); \/\/ Afterburned\n\1/g' \
  | perl -p -e's/(^\s*)(\$result->AddPage\(new PageLink\(\$this->GetLocalizerCaptions\(\)->GetMessageString\('\''AdminPage'\''\), '\''phpgen_admin.php'\'', \$this->GetLocalizerCaptions\(\)->GetMessageString\('\''AdminPage'\''\), false, false, '\''Admin area'\''\)\);)/\1\2\n\1\}\n\n            \/\/ MIGR add_more_navigation_links(\$result); \/\/ Afterburned\n\1\{/g' \
  | perl -p -e's/(DownloadHTTPHandler\(\$this->dataset, '\''(datei)'\'')/PrivateFile\1/g' \
  | perl -p -e's/(<\?php)/\1\n\/\/ Processed by afterburner.sh\n\n/' \
  > "$file";
done
# // session_unset(): Ref: http://stackoverflow.com/questions/520237/how-do-i-expire-a-php-session-after-30-minutes

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
  | perl -p -e's/(case OPERATION_DELETE_SELECTED:)/\1\n            case OPERATION_INPUT_FINISHED_SELECTED: \/\/ Afterburner\n            case OPERATION_DE_INPUT_FINISHED_SELECTED: \/\/ Afterburner\n            case OPERATION_CONTROLLED_SELECTED: \/\/ Afterburner\n            case OPERATION_DE_CONTROLLED_SELECTED: \/\/ Afterburner\n            case OPERATION_AUTHORIZATION_SENT_SELECTED: \/\/ Afterburner\n            case OPERATION_DE_AUTHORIZATION_SENT_SELECTED: \/\/ Afterburner\n            case OPERATION_AUTHORIZE_SELECTED: \/\/ Afterburner\n            case OPERATION_DE_AUTHORIZE_SELECTED: \/\/ Afterburner\n            case OPERATION_RELEASE_SELECTED: \/\/ Afterburner\n            case OPERATION_DE_RELEASE_SELECTED: \/\/ Afterburner/' \
  | perl -p -e's/(<\?php)/\1\n\/\/ Processed by afterburner.sh\n\n/' \
  > "$file";
done


for file in $dir/components/grid/columns/abstract_view_column.php
do
  echo "Process $file";
  mv "$file" "$file.bak";
  # Read file, process regex and write file
  cat "$file.bak" \
  | perl -p -e's%^(\s*)(public function GetCaption\(\))%\1public function SetCaption\(\$caption\) \/\/ Afterburner\n\1\{\n\1\1\$this->caption = \$caption;\n\1\}\n\n\1\2%' \
  | perl -p -e's/(<\?php)/\1\n\/\/ Processed by afterburner.sh\n\n/' \
  > "$file";
done

for file in $dir/components/editors/text.php
do
  echo "Process $file";
  mv "$file" "$file.bak";
  # Read file, process regex and write file
  cat "$file.bak" \
  | perl -0 -p -e's%public function getEditorName\(\).*\{.*return '\''text'\'';.*?\}%public function getEditorName\(\)\n    \{\n        return '\''../custom_templates/editors/text'\''; \/\/ Afterburner\n    \}%s' \
  | perl -p -e's/(<\?php)/\1\n\/\/ Processed by afterburner.sh\n\n/' \
  > "$file";
done
#  | perl -p -e's%^\s*return '\''text'\'';$%    return '\''../custom_templates/editors/text'\'';%' \

for file in $dir/components/lang.php
do
  echo "Process $file";
  mv "$file" "$file.bak";
  # Read file, process regex and write file
  cat "$file.bak" \
  | perl -p -e's/Musetr/Muster/' \
  | perl -p -e's/(<\?php)/\1\n\/\/ Processed by afterburner.sh\n\n/' \
  > "$file";
done

#for file in $dir/components/grid/columns.php
#do
#  echo "Process $file";
#  mv "$file" "$file.bak";
#  # Read file, process regex and write file
#  cat "$file.bak" \
#  | perl -p -e's/(\$this->fullTextWindowHandlerName = \$value;)/\$this->fullTextWindowHandlerName = clean_non_ascii(\$this->GetDataset()->GetName()) . "-" . \$value; \/\/ Afterburner/' \
#  | perl -p -e's/(<\?php)/\1\n\/\/ Processed by afterburner.sh\n\n/' \
#  > "$file";
#done
#
#for file in $dir/components/common.php
#do
#  echo "Process $file";
#  mv "$file" "$file.bak";
#  # Read file, process regex and write file
#  cat "$file.bak" \
#  | perl -0 -p -e's/(class ShowTextBlobHandler extends HTTPHandler\n{)/\1\n    public function GetName()\n    {\n      \$new_name = clean_non_ascii(\$this->dataset->GetName() . "-" . parent::GetName());\n      \/\/df(\$new_name, "ShowTextBlobHandler.GetName()");\n      return \$new_name;\n    }\n \/\/ Afterburner\n/s' \
#  | perl -p -e's/(<\?php)/\1\n\/\/ Processed by afterburner.sh\n\n/' \
#  > "$file";
#done

for file in $dir/components/grid/grid.php
do
  echo "Process $file";
  mv "$file" "$file.bak";
  # Read file, process regex and write file
  cat "$file.bak" \
  | perl -p -e's%^((\s*)OPERATION_DELETE_SELECTED => '\''DeleteSelectedGridState'\'',)%\1\n\2OPERATION_INPUT_FINISHED_SELECTED => '\''InputFinishedSelectedGridState'\'', \/\/ Afterburner\n\2OPERATION_DE_INPUT_FINISHED_SELECTED => '\''DeInputFinishedSelectedGridState'\'', \/\/ Afterburner\n\2OPERATION_CONTROLLED_SELECTED => '\''ControlledSelectedGridState'\'', \/\/ Afterburner\n\2OPERATION_DE_CONTROLLED_SELECTED => '\''DeControlledSelectedGridState'\'', \/\/ Afterburner\n\2OPERATION_AUTHORIZATION_SENT_SELECTED => '\''AuthorizationSentSelectedGridState'\'', \/\/ Afterburner\n\2OPERATION_DE_AUTHORIZATION_SENT_SELECTED => '\''DeAuthorizationSentSelectedGridState'\'', \/\/ Afterburner\n\2OPERATION_AUTHORIZE_SELECTED => '\''AuthorizeSelectedGridState'\'', \/\/ Afterburner\n\2OPERATION_DE_AUTHORIZE_SELECTED => '\''DeAuthorizeSelectedGridState'\'', \/\/ Afterburner\n\2OPERATION_RELEASE_SELECTED => '\''ReleaseSelectedGridState'\'', \/\/ Afterburner\n\2OPERATION_DE_RELEASE_SELECTED => '\''DeReleaseSelectedGridState'\'', \/\/ Afterburner\n\2OPERATION_SET_IMRATBIS_SELECTED => '\''SetImRatBisSelectedGridState'\'', \/\/ Afterburner\n\2OPERATION_CLEAR_IMRATBIS_SELECTED => '\''ClearImRatBisSelectedGridState'\'', \/\/ Afterburner%' \
  | perl -p -e's/(function GetAllowDeleteSelected)/function GetAllowInputFinishedSelected() { \/\/ Afterburner\n      \$columns = \$this->GetEditColumns(); \/\/ Afterburner\n      return \$this->GetAllowDeleteSelected() && is_column_present(\$columns,'\''eingabe_abgeschlossen_datum'\'') && is_column_present(\$columns,'\''eingabe_abgeschlossen_visa'\''); \/\/ Afterburner\n    }\n\n    function GetAllowControlledSelected() { \/\/ Afterburner\n      \$columns = \$this->GetEditColumns(); \/\/ Afterburner\n      return \$this->GetAllowDeleteSelected() && is_column_present(\$columns,'\''kontrolliert_datum'\'') && is_column_present(\$columns,'\''kontrolliert_visa'\'') && isFullWorkflowUser(); \/\/ Afterburner\n    }\n\n    function GetAllowAuthorizationSentSelected() { \/\/ Afterburner\n      \$columns = \$this->GetEditColumns(); \/\/ Afterburner\n      return \$this->GetAllowDeleteSelected() && is_column_present(\$columns,'\''autorisierung_verschickt_datum'\'') && is_column_present(\$columns,'\''autorisierung_verschickt_visa'\'') && isFullWorkflowUser(); \/\/ Afterburner\n    }\n\n    function GetAllowAuthorizeSelected() { \/\/ Afterburner\n      \$columns = \$this->GetEditColumns(); \/\/ Afterburner\n      return \$this->GetAllowDeleteSelected() && is_column_present(\$columns,'\''autorisiert_datum'\'') && is_column_present(\$columns,'\''autorisiert_visa'\'') \/* && is_column_present(\$columns,'\''autorisierung_verschickt_datum'\'') *\/ && isFullWorkflowUser(); \/\/ Afterburner\n    }\n\n    function GetAllowReleaseSelected() { \/\/ Afterburner\n      \$columns = \$this->GetEditColumns();
      return \$this->GetAllowDeleteSelected() && is_column_present(\$columns,'\''freigabe_datum'\'') && is_column_present(\$columns,'\''freigabe_visa'\'') && isFullWorkflowUser(); \/\/ Afterburner\n    }\n\n    function GetAllowImRatBisSelected() { \/\/ Afterburner\n      \$columns = \$this->GetEditColumns();
      return \$this->GetAllowDeleteSelected() && is_column_present(\$columns,'\''im_rat_bis'\''); \/\/ Afterburner\n    }\n\n    \1/' \
  | perl -p -e's%('\''RefreshButton'\'' => \$this->GetShowUpdateLink\(\),)%\1\n                '\''InputFinishedSelectedButton'\'' => \$this->GetAllowInputFinishedSelected(), \/\/ Afterburner\n                '\''ControlledSelectedButton'\'' => \$this->GetAllowControlledSelected(), \/\/ Afterburner\n                '\''AuthorizationSentSelectedButton'\'' => \$this->GetAllowAuthorizationSentSelected(), \/\/ Afterburner\n                '\''AuthorizeSelectedButton'\'' => \$this->GetAllowAuthorizeSelected(), \/\/ Afterburner\n                '\''ReleaseSelectedButton'\'' => \$this->GetAllowReleaseSelected(), \/\/ Afterburner\n                '\''ImRatBisSelectedButton'\'' => \$this->GetAllowImRatBisSelected(), \/\/ Afterburner\n%' \
  | perl -p -e's/(<\?php)/\1\n\/\/ Processed by afterburner.sh\n\n/' \
  > "$file";
done
#  | perl -p -e's/(case OPERATION_DELETE_SELECTED:)/case OPERATION_INPUT_FINISHED_SELECTED: \/\/ Afterburner\n                \$this->gridState = new InputFinishedSelectedGridState(\$this); \/\/ Afterburner\n                break;\n            case OPERATION_DE_INPUT_FINISHED_SELECTED: \/\/ Afterburner\n                \$this->gridState = new DeInputFinishedSelectedGridState(\$this);
#                break;\n            case OPERATION_CONTROLLED_SELECTED: \/\/ Afterburner\n                \$this->gridState = new ControlledSelectedGridState(\$this); \/\/ Afterburner\n                break;\n            case OPERATION_DE_CONTROLLED_SELECTED: \/\/ Afterburner\n                \$this->gridState = new DeControlledSelectedGridState(\$this);
#                break;\n            case OPERATION_AUTHORIZATION_SENT_SELECTED: \/\/ Afterburner\n                \$this->gridState = new AuthorizationSentSelectedGridState(\$this); \/\/ Afterburner\n                break;\n            case OPERATION_DE_AUTHORIZATION_SENT_SELECTED: \/\/ Afterburner\n                \$this->gridState = new DeAuthorizationSentSelectedGridState(\$this);
#                break;\n            case OPERATION_AUTHORIZE_SELECTED: \/\/ Afterburner\n                \$this->gridState = new AuthorizeSelectedGridState(\$this); \/\/ Afterburner\n                break;\n            case OPERATION_DE_AUTHORIZE_SELECTED: \/\/ Afterburner\n                \$this->gridState = new DeAuthorizeSelectedGridState(\$this);
#                break;\n            case OPERATION_RELEASE_SELECTED: \/\/ Afterburner\n                \$this->gridState = new ReleaseSelectedGridState(\$this); \/\/ Afterburner\n                break;
#            case OPERATION_DE_RELEASE_SELECTED: \/\/ Afterburner\n                \$this->gridState = new DeReleaseSelectedGridState(\$this); \/\/ Afterburner\n                break;\n            case OPERATION_SET_IMRATBIS_SELECTED: \/\/ Afterburner\n                \$this->gridState = new SetImRatBisSelectedGridState(\$this); \/\/ Afterburner\n                break;
#            case OPERATION_CLEAR_IMRATBIS_SELECTED: \/\/ Afterburner\n                \$this->gridState = new ClearImRatBisSelectedGridState(\$this); \/\/ Afterburner\n                break;\n            \1/' \
#             OPERATION_DELETE_SELECTED => 'DeleteSelectedGridState',

# 'RefreshButton' => $this->GetShowUpdateLink(),
#   | perl -p -e's/('\''SetDefaultCheckBoxName'\'' => \$column->GetFieldName\(\) \. '\''_def'\'')/\1,\n                '\''Hint'\'' => isset(\$GLOBALS['\''customParams'\'']['\''Hints'\''][\$column->GetFieldName()]) ? \$GLOBALS['\''customParams'\'']['\''Hints'\''][\$column->GetFieldName()] : null, \/\/ Afterburner/' \
#  | perl -p -e's/('\''SetDefaultCheckBoxName'\'' => \$column->GetFieldName\(\) \. '\''_def'\'')/\1,\n                '\''Hint'\'' => \$GLOBALS['\''customParams'\'']['\''Hints'\''][\$column->GetFieldName()], \/\/ Afterburner/' \
# \n        '\''Hint'\'' => $GLOBALS['\''customParams'\'']['\''Hints'\''][$column-X>GetFieldName()], \/\/ Afterburner/

# 'Grid' => $this->Render($page->GetGrid()),
# for file in $dir/components/renderers/edit_renderer.php $dir/components/renderers/insert_renderer.php
# do
#   echo "Process $file";
#   mv "$file" "$file.bak";
#   # Read file, process regex and write file
#   cat "$file.bak" \
#   | perl -p -e's/\$this->Render\(\$page->GetGrid\(\)\)/\$this->Render(\$page->GetGrid(), true, true, \$GLOBALS['\''customParams'\''])  \/* Afterburner *\//' \
#   | perl -p -e's/(<\?php)/\1\n\/\/ Processed by afterburner.sh\n\n/' \
#   > "$file";
# done

# MIGR custom.hints start
#for file in $dir/components/js/pgui.insert-page-main.js $dir/components/js/pgui.edit-page-main.js
#do
#  echo "Process $file";
#  mv "$file" "$file.bak";
#  # Read file, process regex and write file
#  cat "$file.bak" \
#  | perl -p -e's/(?<=require\('\''pgui.forms'\''\))/,\n        \/\/ Afterburner\n        hints       = require('\''..\/templates\/custom_templates\/js\/custom.hints'\'')/' \
#  > "$file";
#done
# MIGR custom.hints end

for file in $dir/components/js/pgui.grid.js
do
  echo "Process $file";
  mv "$file" "$file.bak";
  # Read file, process regex and write file
  cat "$file.bak" \
  | perl -p -e's%('\''pgui.selection-handler'\'',)%// \1  // Afterburner\n    '\''custom/pgui.selection-handler.ops'\'', // Afterburner%' \
  > "$file";
done

for file in $dir/components/js/main.js
do
  echo "Process $file";
  mv "$file" "$file.bak";
  # Read file, process regex and write file
  cat "$file.bak" \
  | perl -p -e's%(\s*)('\''|")(bootstrap)\2,%\1\2\3\2,\n\1\2custom/custom-require\2, // Afterburner%' \
  > "$file";
done

for file in $dir/components/js/pgui.editors.js
do
  echo "Process $file";
  mv "$file" "$file.bak";
  # Read file, process regex and write file
  cat "$file.bak" \
  | perl -p -e's%(\s*)(var editorName = \$item\.data\('\''editor'\''\);)%\1\2\n\1editorName = editorName.replace(/\\.\\.\\/custom_templates\\/editors\\//, '\'''\''); // Afterburner%' \
  | perl -p -e's%(\s*)(return editorNames\[name\];)%\1var editorName = name.replace(/\\.\\.\\/custom_templates\\/editors\\//, '\'''\''); // Afterburner\n\1return editorNames[editorName]; // Afterburner%' \
  > "$file";
done

for file in $dir/components/advanced_search_page.php
do
  echo "Process $file";
  mv "$file" "$file.bak";
  # Read file, process regex and write file
  cat "$file.bak" \
  | perl -p -e's/protected function SetApplyNotOperator/\/*afterburner*\/ public function SetApplyNotOperator/' \
  | perl -p -e's/protected function SetFilterIndex/\/*afterburner*\/ public function SetFilterIndex/' \
  | perl -p -e's/private function SaveSearchValuesToSession/\/*afterburner*\/ public function SaveSearchValuesToSession/' \
  | perl -p -e's/(\s*public function GetTarget\(\))/    public function getName() { \/*afterburner*\/\n      return \$this->name;\n    }\n\n\1/' \
  | perl -p -e's/(<\?php)/\1\n\/\/ Processed by afterburner.sh\n\n/' \
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
  | perl -p -e's/^(function GetPageInfos\(\))/\1 { \/\/ Afterburned\n    \$pageInfos = generatedGetPageInfos\(\); \/\/ Afterburned\n    \$pageInfos = customGetPageInfos\(\$pageInfos\); \/\/ Afterburned\n    return \$pageInfos\; \/\/ Afterburned\n}\n\nfunction generatedGetPageInfos\(\) \/\/ Afterburned/g' \
  > "$file";
done

# Only for hardcoded auth
for file in $dir/authorization.php
do
  echo "Process $file";
  mv "$file" "$file.bak";
  cat "$file.bak" \
  | perl -0 -p -e's/\$users = array.*?;/\/\/ Custom modification: Use \$users form settings.php/s' \
  > "$file";
done

# Ref: http://stackoverflow.com/questions/2179520/whats-the-best-way-to-do-user-authentication-in-php
for file in $dir/components/security/security_info.php
do
  echo "Process $file";
  mv "$file" "$file.bak";
  cat "$file.bak" \
  | perl -p -e's/^(\s*\$currentUser = null;)$/\/\/\1 Afterburned/' \
  | perl -0 -p -e's/global \$currentUser;\s*\$currentUser = \$userName;/\$_SESSION['\''user'\''] = \$userName; \/*afterburner*\/ /s' \
  | perl -p -e's/^(\s*global \$currentUser;)$/\/\/\1 Afterburned/' \
  | perl -0 -p -e's/isset\(\$currentUser\)\s*\)\s*return \$currentUser;/isset(\$_SESSION['\''user'\''])) \/\/ Afterburned\n     return \$_SESSION['\''user'\'']; \/\/ Afterburned/' \
  > "$file";
done

for file in $dir/components/security/user_self_management.php
do
  echo "Process $file";
  mv "$file" "$file.bak";
  cat "$file.bak" \
  | perl -p -e's/^((\s*)\$this->ChangePassword\(\$newPassword\);)$/\2checkPasswordStrength(\$newPassword); \/\/ Afterburned\n\1/' \
  > "$file";
done

for file in $dir/components/security/user_identity_cookie_storage.php
do
  echo "Process $file";
  mv "$file" "$file.bak";
  cat "$file.bak" \
  | perl -p -e's%(setcookie)% // afterburned \1%' \
  > "$file";
done

for file in $dir/components/editors/multilevel_selection.php
do
  echo "Process $file";
  mv "$file" "$file.bak";
  cat "$file.bak" \
  | perl -p -e's%20\) \{%50\) \{ // Afterburned%' \
  > "$file";
done

for file in $dir/components/security/user_management_request_handler.php
do
  echo "Process $file";
  mv "$file" "$file.bak";
  cat "$file.bak" \
  | perl -p -e's/^((\s*)return array\('\''id'\'' => \$userId, '\''name'\'' => \$userName, '\''password'\'' => '\''\*\*\*\*\*\*'\''\);)$/\n\2\$userId = \$userId == null || \$userId == '\'''\'' ? getDBConnection()->ExecScalarSQL('\''SELECT MAX(id) FROM user;'\'') : \$userId; \/\/ Afterburned\n\n\1\n/' \
  > "$file";
done

for file in $dir/database_engine/mysql_engine.php $dir/database_engine/commands.php
do
  echo "Process $file";
  mv "$file" "$file.bak";
  cat "$file.bak" \
| perl -p -e's/'\''UPPER\(%s\) LIKE UPPER\(%s\)'\''/'\''%s LIKE %s'\'' \/*afterburner: default is case insensitive (utf8_general_ci), no need for UPPER function which stops indexes in MySQL*\//' \
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

# MIGR aggregated.js.gz begin
#for file in $dir/components/templates/common/layout.tpl
#do
#  echo "Process $file";
#  mv "$file" "$file.bak";
#  cat "$file.bak" \
#  | perl -p -e's%(<link rel="stylesheet" type="text/css" href="components/css/main.css" />)%<!-- \1 afterburner -->\n<link rel="stylesheet" type="text/css" href="components/css/aggregated.css.gz?v=1" /> <!-- afterburner -->%is' \
#  | perl -p -e's%(<link rel="stylesheet" type="text/css" href="components/css/user.css" />)%<!-- \1 afterburner -->%is' \
#  | perl -p -e's%(<script src="components/js/.+"></script>)%<!-- \1 afterburner -->%is' \
#  | perl -p -e's%(<script type="text/javascript" src="components/js/require-config.js"></script>)%<!-- \1 afterburner -->%is' \
#  | perl -p -e's%(<script type="text/javascript"(.*)src="components/js/require.js"></script>)%<!-- \1 afterburner -->\n        <script \2 src="components/js/aggregated.js.gz?v=1"></script>\n        <script type="text/javascript" src="components/js/custom\.js?v=2"></script>%is' \
#  | perl -p -e's%(<script type="text/javascript" src="components/js/p.*\.js"></script>)%<!-- \1 afterburner -->%is' \
#  > "$file";
#done
# MIGR aggregated.js.gz end

#   | perl -p -e's%(<script type="text/javascript" src="components/js/user\.js"></script>)%    \1%is' \
#    <script src="components/js/jquery/jquery.min.js"></script>
#    <script src="components/js/libs/amplify.store.js"></script>
#    <script src="components/js/bootstrap/bootstrap.js"></script>
#
#    <script type="text/javascript" src="components/js/require-config.js"></script>

#    {if $JavaScriptMain}
#        <script type="text/javascript" data-main="{$JavaScriptMain}" src="components/js/require.js"></script>
#    {else}
#        <script type="text/javascript" src="components/js/require.js"></script>
#    {/if}

#<script type="text/javascript" src="components/js/pg.user_management_api.js"></script>
#<script type="text/javascript" src="components/js/pgui.change_password_dialog.js"></script>
#<script type="text/javascript" src="components/js/pgui.password_dialog_utils.js"></script>
#<script type="text/javascript" src="components/js/pgui.self_change_password.js"></script>

# MIGROK not required any more
#for file in $dir/components/templates/editors/text.tpl
#do
#  echo "Process $file";
#  mv "$file" "$file.bak";
#  cat "$file.bak" \
#  | perl -0 -p -e's%(getSuffix\(\)\}</span>\s*\{/if\})%\1\n{if \$TextEdit->GetHTMLValue()|strpos:'\''http'\''===0}<!-- Check starts with http --> <!-- afterburner -->\n    <br><a href="{\$TextEdit->GetHTMLValue()}" target="_blank">Follow link: {\$TextEdit->GetHTMLValue()}</a><!-- afterburner -->\n{/if}<!-- afterburner -->\n{if \$TextEdit->GetHTMLValue()|strpos:'\''CHE-'\''===0}<!-- Check starts with CHE- --> <!-- afterburner -->\n    <br><a href="http://zefix.ch/WebServices/Zefix/Zefix.asmx/SearchFirm?id={\$TextEdit->GetHTMLValue()}" target="_blank">Follow link: {\$TextEdit->GetHTMLValue()}</a><!-- afterburner -->\n{/if}<!-- afterburner -->%ms' \
#  > "$file";
#done

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
  cat "$file.bak" \
  | perl -p -e's%('\''|")(forms/field_label.tpl)\1%\1custom_templates/\2\1%g' \
  | perl -p -e's%('\''|")(forms/form_fields.tpl)\1%\1custom_templates/\2\1%g' \
  | perl -p -e's%('\''|")(forms/actions_edit.tpl)\1%\1custom_templates/\2\1%g' \
  | perl -p -e's%('\''|")(list/grid_toolbar.tpl)\1%\1custom_templates/\2\1%g' \
  | perl -p -e's%('\''|")(list/grid_column_header.tpl)\1%\1custom_templates/\2\1%g' \
  > "$file";
done

#less_dir=$dir/components/assets/less/
#all_less_files=`find $less_dir -name "*.less"`;
#for file in $all_less_files
#do
#  echo "Process $file";
#  mv "$file" "$file.bak";
#  cat "$file.bak" \
#  | perl -p -e's%XXX@import \(optional\) "user\.less";%@import "user\.less";%g' \
#  | perl -p -e's%XXX(@import \(optional\) "user\.less";)%\1\n\@import "custom/custom\.less";%g' \
#  > "$file";
#done

## Overwrite main.css since generator did not include custom/custom.less
## Calling less directly works without problem
## https://www.sqlmaestro.com/products/mysql/phpgenerator/help/02_04_00_style_sheets_internals/
## https://github.com/dotless/dotless/wiki/Using-.less
## http://lesscss.org/features/#features-overview-feature
#assets_dir=$dir/components/assets
#mv $assets_dir/css/main.css $assets_dir/css/main.css.bak
#echo $WINE "$WINE_LESS -r $assets_dir/less/main.less $assets_dir/css/main.css"
#$WINE "$WINE_LESS" -r $assets_dir/less/main.less $assets_dir/css/main.css
./gen_css.sh

# MIGR aggregated.js begin
#echo "Aggregate JS"
#cat $dir/components/js/jquery/jquery.min.js $dir/components/js/libs/amplify.store.js $dir/components/js/bootstrap/bootstrap.js $dir/components/js/require-config.js $dir/components/js/require.js $dir/components/js/pg.user_management_api.js $dir/components/js/pgui.change_password_dialog.js $dir/components/js/pgui.password_dialog_utils.js $dir/components/js/pgui.self_change_password.js > $dir/components/js/aggregated.js
## parameter -k for keeping original file
#gzip -9 -f -k $dir/components/js/aggregated.js

# MIGR aggregated.css
## Instead of import custom.css, copy it, avoids a HTTP request
#echo "Aggregate CSS"
#cd public_html/bearbeitung/components/css
##cp public_html/bearbeitung/components/css/custom.css public_html/bearbeitung/components/css/user.css
#../../../../data_image_css_converter.sh custom.css > user.css
#cat main.css user.css > aggregated_raw.css
#$PHP -f ../../../../minify_css.php aggregated_raw.css > aggregated.css
## parameter -k for keeping original file
#gzip -9 -f -k aggregated.css
#cd -

./gen_js.sh

# MIGR jslang.php > jslang.js disabled
## We support currently only 1 language, avoid PHP call and create static file
#echo "jslang.php > jslang.js"
#cd public_html/bearbeitung/
#$PHP -f components/js/jslang.php > components/js/jslang.js
#cd -
#
#for file in $dir/components/js/pgui.localizer.js
#do
#  echo "Process $file";
#  mv "$file" "$file.bak";
#  cat "$file.bak" \
#  | perl -0 -p -e's/jslang\.php/jslang.js/s' \
#  > "$file";
#done
# MIGR aggregated.js end

for file in lobbywatch_bearbeitung.pgtm
do
  echo "Process $file";
  cat "$file" \
  | perl -p -e's/(login\s*=\s*)".*?"/\1""/ig' \
  | perl -p -e's/( password\s*=\s*)".*?"/\1"hidden"/ig' \
  | perl -p -e's/(database\s*=\s*)".*?"/\1""/ig' \
  > "lobbywatch_bearbeitung_public.pgtm";
done

# revert user_identity_cookie_storage.php
git co public_html/bearbeitung/components/security/user_identity_cookie_storage.php

git st

# Sed: http://www.grymoire.com/Unix/Sed.html
# Perl: http://petdance.com/perl/command-line-options.pdf

# ( Prog1; Prog2; Prog3; ...  ) | ProgN
# ( Prog1 & Prog2 & Prog3 & ... ) | ProgN
