<?
// Upgrage Todos UPG

/*
TODO Update custom_templates
TODO SelectedOperationGridState migration
TODO Afterburner which necessary, clean up
TODO check custom files for changes
TODO Check utils.createLoadingModalDialog(localizer.getString('Deleting')).modal();
TODO export lobbywatch.sql
TODO improve custom/custom_page.php
TODO Remove calls to $this->RenderText()

Done:
* Migrate Sources to UTF-8
* Find latin-1 files
* Delete all public_html/bearbeitung files and detect unnecessary files
* remove convert_utf8() calls

## 2nd Prio
* Remove ^M in generated *.js files
* remove trailing whitespace in generated code, https://stackoverflow.com/questions/9532340/how-do-i-remove-trailing-whitespace-using-a-regular-expression
* Use public_html/bearbeitung/components/common_utils.php

*/

/*

## Custom features added to PHPGen framework

* Comments on edit forms
* Bulk Ops [rework]
* Enhanced comments on grid header
* Call UID WS
* Marked fields on edit/insert form
* Main.css
* Main.js
* Preview
* Default filter out retired parlamentarier
* Improved security [rework]

*/
