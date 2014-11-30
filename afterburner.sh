#!/bin/bash

PHP=/opt/lampp/bin/php

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

all_files=`find $dir -name "*.php"`;
#all_files='';

if [[ "$clean" = "true" ]] ; then
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
fi

for file in $dir/*.php
do
  if [[ $file == public_html/bearbeitung/parlamentarier_preview.php ]]; then
    echo "Skip $file"
    continue
  fi
  echo "Process $file";
  mv "$file" "$file.bak";
  # Read file, process regex and write file
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
  | perl -p -e's/\$build_date:\$/'\'' \. \$GLOBALS["build_date"] \/*afterburner*\/  \. '\''/g' \
  | perl -p -e's/\$build_date/'\'' \. \$GLOBALS["build_date"] \/*afterburner*\/  \. '\''/g' \
  | perl -p -e's/\$build_date_short/'\'' \. \$GLOBALS["build_date_short"] \/*afterburner*\/  \. '\''/g' \
  | perl -p -e's/\$deploy_date:\$/'\'' \. \$GLOBALS["deploy_date"] \/*afterburner*\/  \. '\''/g' \
  | perl -p -e's/\$deploy_date/'\'' \. \$GLOBALS["deploy_date"] \/*afterburner*\/  \. '\''/g' \
  | perl -p -e's/\$deploy_date_short/'\'' \. \$GLOBALS["deploy_date_short"] \/*afterburner*\/  \. '\''/g' \
  | perl -p -e's/\$build_secs/'\'' \. _custom_page_build_secs() \/*afterburner*\/  \. '\''/g' \
  | perl -p -e's/\$version/'\'' \. \$GLOBALS["version"] \/*afterburner*\/  \. '\''/g' \
  | perl -p -e's/\$edit_general_hint/'\'' \. \$GLOBALS["edit_general_hint"] \/*afterburner*\/  \. '\''/g' \
  | perl -p -e's/\$edit_header_message/'\'' \. \$GLOBALS["edit_header_message"] \/*afterburner*\/  \. '\''/g' \
  | perl -p -e's/<a id="plugin-edit-remarksbox.*?<\/a>//g' \
  | perl -p -e's/<img src="img\/icons\/external_link.gif" alt="\(externer Link\)" title="\(externer Link\)" class="icon" height="14" width="15">//g' \
  | perl -p -e's/<img src="img\/icons\/external_link.gif" alt="\(externer Link\)" title="\(externer Link\)" class="icon" width="15" height="14">//g' \
  | perl -p -e's%(src=")([^"]+)"%\1'\'' . util_data_uri('\''\2'\'') . '\''"%g' \
  | perl -p -e's/^((\s*)\$this->userIdentityStorage->ClearUserIdentity\(\);)/\2session_unset(); \/\/ Afterburned\n\1/g' \
  | perl -p -e's/(^\s*)(\$result->AddPage\(new PageLink\(\$this->GetLocalizerCaptions\(\)->GetMessageString\(.AdminPage.\), .phpgen_admin\.php., \$this->GetLocalizerCaptions\(\)->GetMessageString\(.AdminPage.\), false, true\)\);)/\1\2\n\n            add_more_navigation_links(\$result); \/\/ Afterburned/g' \
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
  | perl -p -e's/(case OPERATION_DELETE_SELECTED:)/case OPERATION_INPUT_FINISHED_SELECTED: \/\/ Afterburner\n                \$this->gridState = new InputFinishedSelectedGridState(\$this); \/\/ Afterburner\n                break;\n            case OPERATION_DE_INPUT_FINISHED_SELECTED: \/\/ Afterburner\n                \$this->gridState = new DeInputFinishedSelectedGridState(\$this);
                break;\n            case OPERATION_CONTROLLED_SELECTED: \/\/ Afterburner\n                \$this->gridState = new ControlledSelectedGridState(\$this); \/\/ Afterburner\n                break;\n            case OPERATION_DE_CONTROLLED_SELECTED: \/\/ Afterburner\n                \$this->gridState = new DeControlledSelectedGridState(\$this);
                break;\n            case OPERATION_AUTHORIZATION_SENT_SELECTED: \/\/ Afterburner\n                \$this->gridState = new AuthorizationSentSelectedGridState(\$this); \/\/ Afterburner\n                break;\n            case OPERATION_DE_AUTHORIZATION_SENT_SELECTED: \/\/ Afterburner\n                \$this->gridState = new DeAuthorizationSentSelectedGridState(\$this);
                break;\n            case OPERATION_AUTHORIZE_SELECTED: \/\/ Afterburner\n                \$this->gridState = new AuthorizeSelectedGridState(\$this); \/\/ Afterburner\n                break;\n            case OPERATION_DE_AUTHORIZE_SELECTED: \/\/ Afterburner\n                \$this->gridState = new DeAuthorizeSelectedGridState(\$this);
                break;\n            case OPERATION_RELEASE_SELECTED: \/\/ Afterburner\n                \$this->gridState = new ReleaseSelectedGridState(\$this); \/\/ Afterburner\n                break;
            case OPERATION_DE_RELEASE_SELECTED: \/\/ Afterburner\n                \$this->gridState = new DeReleaseSelectedGridState(\$this); \/\/ Afterburner\n                break;\n            case OPERATION_SET_IMRATBIS_SELECTED: \/\/ Afterburner\n                \$this->gridState = new SetImRatBisSelectedGridState(\$this); \/\/ Afterburner\n                break;
            case OPERATION_CLEAR_IMRATBIS_SELECTED: \/\/ Afterburner\n                \$this->gridState = new ClearImRatBisSelectedGridState(\$this); \/\/ Afterburner\n                break;\n            \1/' \
| perl -p -e's/(function GetAllowDeleteSelected)/function GetAllowInputFinishedSelected() { \/\/ Afterburner\n      \$columns = \$this->GetEditColumns(); \/\/ Afterburner\n      return \$this->GetAllowDeleteSelected() && is_column_present(\$columns,'\''eingabe_abgeschlossen_datum'\'') && is_column_present(\$columns,'\''eingabe_abgeschlossen_visa'\''); \/\/ Afterburner\n    }\n\n    function GetAllowControlledSelected() { \/\/ Afterburner\n      \$columns = \$this->GetEditColumns(); \/\/ Afterburner\n      return \$this->GetAllowDeleteSelected() && is_column_present(\$columns,'\''kontrolliert_datum'\'') && is_column_present(\$columns,'\''kontrolliert_visa'\'') && isFullWorkflowUser(); \/\/ Afterburner\n    }\n\n    function GetAllowAuthorizationSentSelected() { \/\/ Afterburner\n      \$columns = \$this->GetEditColumns(); \/\/ Afterburner\n      return \$this->GetAllowDeleteSelected() && is_column_present(\$columns,'\''autorisierung_verschickt_datum'\'') && is_column_present(\$columns,'\''autorisierung_verschickt_visa'\'') && isFullWorkflowUser(); \/\/ Afterburner\n    }\n\n    function GetAllowAuthorizeSelected() { \/\/ Afterburner\n      \$columns = \$this->GetEditColumns(); \/\/ Afterburner\n      return \$this->GetAllowDeleteSelected() && is_column_present(\$columns,'\''autorisiert_datum'\'') && is_column_present(\$columns,'\''autorisiert_visa'\'') \/* && is_column_present(\$columns,'\''autorisierung_verschickt_datum'\'') *\/ && isFullWorkflowUser(); \/\/ Afterburner\n    }\n\n    function GetAllowReleaseSelected() { \/\/ Afterburner\n      \$columns = \$this->GetEditColumns();
      return \$this->GetAllowDeleteSelected() && is_column_present(\$columns,'\''freigabe_datum'\'') && is_column_present(\$columns,'\''freigabe_visa'\'') && isFullWorkflowUser(); \/\/ Afterburner\n    }\n\n    function GetAllowImRatBisSelected() { \/\/ Afterburner\n      \$columns = \$this->GetEditColumns();
      return \$this->GetAllowDeleteSelected() && is_column_present(\$columns,'\''im_rat_bis'\''); \/\/ Afterburner\n    }\n\n    \1/' \
  | perl -p -e's/('\''DeleteSelectedButton'\'' => \$this->GetAllowDeleteSelected\(\))/\1,\n                '\''InputFinishedSelectedButton'\'' => \$this->GetAllowInputFinishedSelected(), \/\/ Afterburner\n                '\''ControlledSelectedButton'\'' => \$this->GetAllowControlledSelected(), \/\/ Afterburner\n                '\''AuthorizationSentSelectedButton'\'' => \$this->GetAllowAuthorizationSentSelected(), \/\/ Afterburner\n                '\''AuthorizeSelectedButton'\'' => \$this->GetAllowAuthorizeSelected(), \/\/ Afterburner\n                '\''ReleaseSelectedButton'\'' => \$this->GetAllowReleaseSelected(), \/\/ Afterburner\n                '\''ImRatBisSelectedButton'\'' => \$this->GetAllowImRatBisSelected(), \/\/ Afterburner\n/' \
  | perl -p -e's/(<\?php)/\1\n\/\/ Processed by afterburner.sh\n\n/' \
  > "$file";
done
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

for file in $dir/components/js/pgui.insert-page-main.js $dir/components/js/pgui.edit-page-main.js
do
  echo "Process $file";
  mv "$file" "$file.bak";
  # Read file, process regex and write file
  cat "$file.bak" \
  | perl -p -e's/(?<=require\('\''pgui.forms'\''\))/,\n        \/\/ Afterburner\n        hints       = require('\''..\/templates\/custom_templates\/js\/custom.hints'\'')/' \
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

for file in $dir/components/templates/common/layout.tpl
do
  echo "Process $file";
  mv "$file" "$file.bak";
  cat "$file.bak" \
  | perl -p -e's%(<link rel="stylesheet" type="text/css" href="components/css/main.css" />)%<!-- \1 afterburner -->\n<link rel="stylesheet" type="text/css" href="components/css/aggregated.css.gz" /> <!-- afterburner -->%is' \
  | perl -p -e's%(<link rel="stylesheet" type="text/css" href="components/css/user.css" />)%<!-- \1 afterburner -->%is' \
  | perl -p -e's%(<script src="components/js/.+"></script>)%<!-- \1 afterburner -->%is' \
  | perl -p -e's%(<script type="text/javascript" src="components/js/require-config.js"></script>)%<!-- \1 afterburner -->%is' \
  | perl -p -e's%(<script type="text/javascript"(.*)src="components/js/require.js"></script>)%<!-- \1 afterburner -->\n<script \2 src="components/js/aggregated.js.gz"></script>%is' \
  | perl -p -e's%(<script type="text/javascript" src="components/js/p.*\.js"></script>)%<!-- \1 afterburner -->%is' \
  > "$file";
done

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

echo "Aggregate JS"
cat $dir/components/js/jquery/jquery.min.js $dir/components/js/libs/amplify.store.js $dir/components/js/bootstrap/bootstrap.js $dir/components/js/require-config.js $dir/components/js/require.js $dir/components/js/pg.user_management_api.js $dir/components/js/pgui.change_password_dialog.js $dir/components/js/pgui.password_dialog_utils.js $dir/components/js/pgui.self_change_password.js > $dir/components/js/aggregated.js
# parameter -k for keeping original file
gzip -9 -f -k $dir/components/js/aggregated.js

# Instead of import custom.css, copy it, avoids a HTTP request
echo "Aggregate CSS"
cd public_html/bearbeitung/components/css
#cp public_html/bearbeitung/components/css/custom.css public_html/bearbeitung/components/css/user.css
../../../../data_image_css_converter.sh custom.css > user.css
cat main.css user.css > aggregated_raw.css
$PHP -f ../../../../minify_css.php aggregated_raw.css > aggregated.css
# parameter -k for keeping original file
gzip -9 -f -k aggregated.css
cd -

# We support currently only 1 language, avoid PHP call and create static file
echo "jslang.php > jslang.js"
cd public_html/bearbeitung/
$PHP -f components/js/jslang.php > components/js/jslang.js
cd -

for file in $dir/components/js/pgui.localizer.js
do
  echo "Process $file";
  mv "$file" "$file.bak";
  cat "$file.bak" \
  | perl -0 -p -e's/jslang\.php/jslang.js/s' \
  > "$file";
done

for file in lobbywatch_bearbeitung.pgtm
do
  echo "Process $file";
  cat "$file" \
  | perl -p -e's/(login\s*=\s*)".*?"/\1""/ig' \
  | perl -p -e's/( password\s*=\s*)".*?"/\1"hidden"/ig' \
  | perl -p -e's/(database\s*=\s*)".*?"/\1""/ig' \
  > "lobbywatch_bearbeitung_public.pgtm";
done

git st

# Sed: http://www.grymoire.com/Unix/Sed.html
# Perl: http://petdance.com/perl/command-line-options.pdf

# ( Prog1; Prog2; Prog3; ...  ) | ProgN
# ( Prog1 & Prog2 & Prog3 & ... ) | ProgN
