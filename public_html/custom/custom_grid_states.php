<?

$old_bearbeitung = preg_match("/old_bearbeitung/", $_SERVER['REQUEST_URI']);

require_once dirname(__FILE__) . "/../settings/settings.php";
require_once dirname(__FILE__) . "/../common/utils.php";

// MIGR workaround to support old_bearbeitung
if (!$old_bearbeitung) {
  require_once dirname(__FILE__) . '/../bearbeitung/components/grid/grid_states/grid_states.php';
//   require_once dirname(__FILE__) . '/../bearbeitung/components/common.php';
//   include_once dirname(__FILE__) . '/../bearbeitung/components/http_handler/abstract_http_handler.php';
} else {
//   require_once dirname(__FILE__) . '/../old_bearbeitung/components/grid/grid.php';
//   require_once dirname(__FILE__) . '/../old_bearbeitung/components/common.php';
//   include_once dirname(__FILE__) . '/../bearbeitung/components/http_handler/abstract_http_handler.php';
}

define('OPERATION_INPUT_FINISHED_SELECTED', 'finsel');
define('OPERATION_DE_INPUT_FINISHED_SELECTED', 'definsel');
define('OPERATION_CONTROLLED_SELECTED', 'consel');
define('OPERATION_DE_CONTROLLED_SELECTED', 'deconsel');
define('OPERATION_AUTHORIZATION_SENT_SELECTED', 'sndsel');
define('OPERATION_DE_AUTHORIZATION_SENT_SELECTED', 'desndsel');
define('OPERATION_AUTHORIZE_SELECTED', 'autsel');
define('OPERATION_DE_AUTHORIZE_SELECTED', 'deautsel');
define('OPERATION_RELEASE_SELECTED', 'relsel');
define('OPERATION_DE_RELEASE_SELECTED', 'derelsel');
define('OPERATION_SET_IMRATBIS_SELECTED', 'setimratbissel');
define('OPERATION_CLEAR_IMRATBIS_SELECTED', 'clearimratbissel');
define('OPERATION_SET_EHRENAMTLICH_SELECTED', 'setehrenamtlichsel');

// UPG MIGR refactor, adapt to new 16.9 framework code
abstract class SelectedOperationGridState extends GridState {
  protected $date;
  protected $text1;
  protected $text2;
  protected $text3;

  // MIGR quick and dirty restore of function since it does not exist anymore in 16.9
  protected function CanChangeData(&$rowValues, &$message) {
    return $this->DoCanChangeData($rowValues, $message);
  }

  protected function DoCanChangeData(&$rowValues, &$message) {
    $cancel = false;
    $messageDisplayTime = 0;
    // Grid_OnBeforeUpdateRecordHandler($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
    $this->grid->BeforeUpdateRecord->Fire ( array (
        $this->grid->GetPage (),
        &$rowValues,
        &$cancel,
        &$message,
        &$messageDisplayTime,
        $this->GetDataset ()->GetName ()
    ) );
    return ! $cancel;
  }
  protected function DoAfterChangeData($rowValues) {
    // Grid_OnAfterUpdateRecordHandler($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime) {
    $this->grid->AfterUpdateRecord->Fire ( array (
        $this->grid->GetPage (),
        &$rowValues,
        $this->GetDataset ()->GetName (),
        &$success,
        &$message,
        &$messageDisplayTime
    ) );
    if (!$success) {
      $this->handleError($message, $messageDisplayTime);
      return false;
    }

    $this->setGridMessage($message, $messageDisplayTime);
  }

  /**
   * @param string $errorMessage
   * @param int    $displayTime
   *
   * @return null
   */
  // MIGR Copied from abstract_commit_values_grid_state.php
  protected function handleError($errorMessage, $displayTime = 0)
  {
    $this->setGridErrorMessage($errorMessage, $displayTime);

    foreach ($this->getRealEditColumns() as $column) {
      $column->PrepareEditorControl();
    }

    $this->getDataset()->Close();
  }

  protected abstract function DoOperation();

  protected function isValidDate($date) {
    $date_array = date_parse($date);
    return preg_match('/^(0[1-9]|[12][0-9]|3[01])\.(0[1-9]|1[012])\.(20)\d\d$/', $date) && checkdate($date_array["month"], $date_array["day"], $date_array["year"]);
  }

  // Similar to globalOnBeforeUpdate
  protected function setUpdatedMetaData() {
    // df($this->grid->GetDataset()->GetFieldValueByName('id'));
    $userName = $this->grid->GetPage ()->GetEnvVar ( 'CURRENT_USER_NAME' );
    $datetime = $this->grid->GetPage ()->GetEnvVar ( 'CURRENT_DATETIME' );

    $this->grid->GetDataset ()->SetFieldValueByName ( 'updated_visa', $userName );
    $this->grid->GetDataset ()->SetFieldValueByName ( 'updated_date', $datetime );
  }

  public function ProcessMessages() {
    // df(GetApplication ()->GetPOSTValue ( 'recordCount' ), 'recCount');
    $primaryKeysArray = array ();
    // df($_POST, 'post');
    for($i = 0; $i < GetApplication ()->GetPOSTValue ( 'recordCount' ); $i ++) {
      if (GetApplication ()->IsPOSTValueSet ( 'rec' . $i )) {
        // TODO : move GetPrimaryKeyFieldNames function to private
        $primaryKeys = array ();
        $primaryKeyNames = $this->grid->GetDataset ()->GetPrimaryKeyFieldNames ();
        for($j = 0; $j < count ( $primaryKeyNames ); $j ++)
          $primaryKeys [] = GetApplication ()->GetPOSTValue ( 'rec' . $i . '_pk' . $j );
        $primaryKeysArray [] = $primaryKeys;
      }
    }

    // df($primaryKeysArray);

    $inlineInsertedRecordPrimaryKeyNames = GetApplication ()->GetSuperGlobals ()->GetPostVariablesIf ( create_function ( '$str', 'return StringUtils::StartsWith($str, \'inline_inserted_rec_\') && !StringUtils::Contains($str, \'pk\');' ) );

    // df($inlineInsertedRecordPrimaryKeyNames);

    foreach ( $inlineInsertedRecordPrimaryKeyNames as $name => $value ) {
      $primaryKeys = array ();
      $primaryKeyNames = $this->grid->GetDataset ()->GetPrimaryKeyFieldNames ();
      for($i = 0; $i < count ( $primaryKeyNames ); $i ++)
        $primaryKeys [] = GetApplication ()->GetSuperGlobals ()->GetPostValue ( $name . '_pk' . $i );
      $primaryKeysArray [] = $primaryKeys;
    }

    // df($primaryKeysArray);

    $input_date = GetApplication ()->GetPOSTValue ( 'date' );
//     df('Dates:');
//     df($input_date);
//     df($this->GetPage ()->GetEnvVar ( 'CURRENT_DATETIME' ));
    if ($this->isValidDate($input_date)) {
      $this->date = $input_date;
    } else { // includes empty date
      $this->date = $this->grid->GetPage()->GetEnvVar('CURRENT_DATETIME');
    }
//     df($this->date);
    $this->text1 = GetApplication ()->GetPOSTValue ( 'text1' );
    $this->text2 = GetApplication ()->GetPOSTValue ( 'text2' );
    $this->text3 = GetApplication ()->GetPOSTValue ( 'text3' );
//     df($this->text);

    foreach ( $primaryKeysArray as $primaryKeyValues ) {
      $this->grid->GetDataset ()->SetSingleRecordState ( $primaryKeyValues );
      $this->grid->GetDataset ()->Open ();
      $this->grid->GetDataset ()->Edit ();

      if ($this->grid->GetDataset ()->Next ()) {
        $message = '';

        $fieldValues = $this->grid->GetDataset ()->GetCurrentFieldValues ();
        if ($this->CanChangeData ( $fieldValues, $message )) {
          try {
            $this->DoOperation ();
            $this->setUpdatedMetaData();
            $this->grid->GetDataset ()->Post ();
            // Refetch field values as the may have changed
            $fieldValues = $this->grid->GetDataset ()->GetCurrentFieldValues ();
            $this->DoAfterChangeData ( $fieldValues );
          } catch ( Exception $e ) {
//             df($e, "Exception");
            $this->grid->GetDataset ()->SetAllRecordsState ();
//             $this->ChangeState ( OPERATION_VIEWALL );
            $this->ApplyState ( OPERATION_VIEWALL ); // ChangeState in PHP Gen 14 version
            $this->SetGridErrorMessage ( $e, 0 );
            return;
          }
        } else {
          $this->grid->GetDataset ()->SetAllRecordsState ();
          $this->ApplyState ( OPERATION_VIEWALL ); // ChangeState in PHP Gen 14 version
          $this->SetGridSimpleErrorMessage ( $message );
          return;
        }
      }
      $this->grid->GetDataset ()->Close ();
    }

    $this->ApplyState ( OPERATION_VIEWALL );
  }
}

class InputFinishedSelectedGridState extends SelectedOperationGridState {
  protected function DoOperation() {
    // df($this->grid->GetDataset()->GetFieldValueByName('id'));
    $userName = $this->grid->GetPage ()->GetEnvVar ( 'CURRENT_USER_NAME' );
    $datetime = $this->grid->GetPage ()->GetEnvVar ( 'CURRENT_DATETIME' );

    $this->grid->GetDataset ()->SetFieldValueByName ( 'eingabe_abgeschlossen_visa', $userName );
    $this->grid->GetDataset ()->SetFieldValueByName ( 'eingabe_abgeschlossen_datum', $datetime );
  }
}
class DeInputFinishedSelectedGridState extends SelectedOperationGridState {
  protected function DoOperation() {
    // df($this->grid->GetDataset()->GetFieldValueByName('id'));
    $this->grid->GetDataset ()->SetFieldValueByName ( 'eingabe_abgeschlossen_visa', null );
    $this->grid->GetDataset ()->SetFieldValueByName ( 'eingabe_abgeschlossen_datum', null );
      }
}

class ControlledSelectedGridState extends SelectedOperationGridState {
  protected function DoOperation() {
    // df($this->grid->GetDataset()->GetFieldValueByName('id'));
    $userName = $this->grid->GetPage ()->GetEnvVar ( 'CURRENT_USER_NAME' );
    $datetime = $this->grid->GetPage ()->GetEnvVar ( 'CURRENT_DATETIME' );

    $this->grid->GetDataset ()->SetFieldValueByName ( 'kontrolliert_visa', $userName );
    $this->grid->GetDataset ()->SetFieldValueByName ( 'kontrolliert_datum', $datetime );
  }
}
class DeControlledSelectedGridState extends SelectedOperationGridState {
  protected function DoOperation() {
    // df($this->grid->GetDataset()->GetFieldValueByName('id'));
    $this->grid->GetDataset ()->SetFieldValueByName ( 'kontrolliert_visa', null );
    $this->grid->GetDataset ()->SetFieldValueByName ( 'kontrolliert_datum', null );
  }
}

class AuthorizationSentSelectedGridState extends SelectedOperationGridState {
  protected function DoOperation() {
    // df($this->grid->GetDataset()->GetFieldValueByName('id'));
    $userName = $this->grid->GetPage ()->GetEnvVar ( 'CURRENT_USER_NAME' );
    $datetime = $this->grid->GetPage ()->GetEnvVar ( 'CURRENT_DATETIME' );

    $this->grid->GetDataset ()->SetFieldValueByName ( 'autorisierung_verschickt_visa', $userName );
    $this->grid->GetDataset ()->SetFieldValueByName ( 'autorisierung_verschickt_datum', $this->date );
  }
}
class DeAuthorizationSentSelectedGridState extends SelectedOperationGridState {
  protected function DoOperation() {
    // df($this->grid->GetDataset()->GetFieldValueByName('id'));
    $this->grid->GetDataset ()->SetFieldValueByName ( 'autorisierung_verschickt_visa', null );
    $this->grid->GetDataset ()->SetFieldValueByName ( 'autorisierung_verschickt_datum', null );
  }
}

class AuthorizeSelectedGridState extends SelectedOperationGridState {
  protected function DoOperation() {
    // df($this->grid->GetDataset()->GetFieldValueByName('id'));
    $userName = $this->grid->GetPage ()->GetEnvVar ( 'CURRENT_USER_NAME' );

    $this->grid->GetDataset ()->SetFieldValueByName ( 'autorisiert_visa', $userName );
    $this->grid->GetDataset ()->SetFieldValueByName ( 'autorisiert_datum', $this->date );
  }
}
class DeAuthorizeSelectedGridState extends SelectedOperationGridState {
  protected function DoOperation() {
    // df($this->grid->GetDataset()->GetFieldValueByName('id'));
    $this->grid->GetDataset ()->SetFieldValueByName ( 'autorisiert_visa', null );
    $this->grid->GetDataset ()->SetFieldValueByName ( 'autorisiert_datum', null );
  }
}

class ReleaseSelectedGridState extends SelectedOperationGridState {
  protected function DoOperation() {
    // df($this->grid->GetDataset()->GetFieldValueByName('id'));
    $userName = $this->grid->GetPage ()->GetEnvVar ( 'CURRENT_USER_NAME' );

    $this->grid->GetDataset ()->SetFieldValueByName ( 'freigabe_visa', $userName );
    $this->grid->GetDataset ()->SetFieldValueByName ( 'freigabe_datum', $this->date );
  }
}
class DeReleaseSelectedGridState extends SelectedOperationGridState {
  protected function DoOperation() {
    // df($this->grid->GetDataset()->GetFieldValueByName('id'));
    $this->grid->GetDataset ()->SetFieldValueByName ( 'freigabe_visa', null );
    $this->grid->GetDataset ()->SetFieldValueByName ( 'freigabe_datum', null );
  }
}

class SetImRatBisSelectedGridState extends SelectedOperationGridState {
  protected function DoOperation() {
    $this->grid->GetDataset ()->SetFieldValueByName ( 'im_rat_bis', $this->date );
  }
}
class ClearImRatBisSelectedGridState extends SelectedOperationGridState {
  protected function DoOperation() {
    $this->grid->GetDataset ()->SetFieldValueByName ( 'im_rat_bis', null );
  }
}
class SetEhrenamtlichSelectedGridState extends SelectedOperationGridState {
  protected function DoOperation() {
    $id = $this->grid->GetDataset()->GetFieldValueByName('id');
//     df($id, "SetEhrenamtlichSelectedGridState.DoOperation() id");
    $userName = $this->grid->GetPage ()->GetEnvVar ( 'CURRENT_USER_NAME' );
    $datetime = $this->grid->GetPage ()->GetEnvVar ( 'CURRENT_DATETIME' );
    $sql_date = "STR_TO_DATE('$datetime','%d-%m-%Y %T')";
    $table = preg_replace('/[`]/i', '', $this->grid->GetDataset()->GetName());
    $year = date("Y");
    $desc = !empty($this->text1) && $this->text1 != 'null' && $this->text1 != 'undefined' ? "'{$this->text1}'" : "'Ehrenamtlich'";
    $src = !empty($this->text2) && $this->text2 != 'null' && $this->text2 != 'undefined' ? "'{$this->text2}'" : 'NULL';
    $url = !empty($this->text3) && $this->text3 != 'null' && $this->text3 != 'undefined' ? "'{$this->text3}'" : 'NULL';
    $sql = "INSERT INTO ${table}_jahr (`${table}_id`, `jahr`, `verguetung`, `beschreibung`, `quelle_url`, `quelle_url_gueltig`, `quelle`, `notizen`, `created_visa`, `created_date`, `updated_visa`, `updated_date`) VALUES ($id, $year, '0', $desc, $url, NULL, $src, NULL, '$userName', $sql_date, '$userName', $sql_date);"; // CURRENT_TIMESTAMP
//     df($sql, "SQL");

    $eng_con = getDBConnection();
    $eng_con->ExecSQL($sql);
  }
}

