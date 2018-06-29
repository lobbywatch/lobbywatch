<?
// Upgrage Todos UPG

/*
TODO check custom files for changes
TODO Check utils.createLoadingModalDialog(localizer.getString('Deleting')).modal();
TODO Remove calls to $this->RenderText()
TODO Test application features
TODO Check UID WS Call from Button in Organisation Edit

Done:
* Remove UPG todo tags
* AJAX broken: Quick search
* AJAX broken: Detail records with +
* Update custom_templates
* Afterburner which necessary, clean up
* SelectedOperationGridState migration
* export lobbywatch.sql
* Migrate Sources to UTF-8
* Find latin-1 files
* Delete all public_html/bearbeitung files and detect unnecessary files
* remove convert_utf8() calls

## 2nd Prio
* Improve and update security
* Remove ^M in generated *.js files
* remove trailing whitespace in generated code, https://stackoverflow.com/questions/9532340/how-do-i-remove-trailing-whitespace-using-a-regular-expression
* Use public_html/bearbeitung/components/common_utils.php
* improve custom/custom_page.php

*/

/*

## Custom features added to PHPGen framework

* Comments on edit forms
* Bulk Ops
* Enhanced comments on grid header
* Call UID WS
* Marked fields on edit/insert form
* Custom main.css
* Custom main.js
* Preview parlamentarier
* Default filter out retired parlamentarier
* Improved security [rework]

*/
