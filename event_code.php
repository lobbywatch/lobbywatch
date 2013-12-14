<?php

// Parlamentarier_Anhang

  function BeforeDeleteRecord($page, &$rowData, &$cancel, &$message, $tableName)
  {
    $target = $rowData['datei'];
    $result = -2;
    if (FileUtils::FileExists($target))
      $result = FileUtils::RemoveFile($target);

    $message = "Delete file '$target'. Result $result";
  }

  function BeforeInsertRecord($page, &$rowData, &$cancel, &$message, $tableName)
  {
    $file = $rowData['datei'];

    $path_parts = pathinfo($file);

    $finfo_mime = new finfo(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
    $finfo_encoding = new finfo(FILEINFO_MIME_ENCODING); // return mime encoding

    $rowData['dateiname'] = $path_parts['filename'];
    if (isset($path_parts['extension'])) {
      $rowData['dateierweiterung'] = $path_parts['extension'];
    }
    $rowData['dateiname_voll'] = $path_parts['basename'];
    $rowData['mime_type'] = $finfo_mime->file($file);
    $rowData['encoding'] = $finfo_encoding->file($file);
  }

// Parlamentarier

function parlamentarierGrid_BeforeUpdateRecord($page, &$rowData, &$cancel, &$message, $tableName)
{
  parlamentarier_remove_old_photo($page, $rowData, $cancel, $message, $tableName);
  parlamentarier_update_photo_metadata($page, $rowData, $cancel, $message, $tableName);
}
function parlamentarierGrid_BeforeDeleteRecord($page, &$rowData, &$cancel, &$message, $tableName)
{
  $target = $rowData['photo'];
  $result = -2;
  if (FileUtils::FileExists($target))
    $result = FileUtils::RemoveFile($target);

  $message = "Delete file '$target'. Result $result";
}
function parlamentarierGrid_BeforeInsertRecord($page, &$rowData, &$cancel, &$message, $tableName)
{
  parlamentarier_update_photo_metadata($page, $rowData, $cancel, $message, $tableName);
}

