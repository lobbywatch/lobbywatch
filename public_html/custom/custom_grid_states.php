<?php

require_once dirname(__FILE__) . "/../settings/settings.php";
require_once dirname(__FILE__) . "/../common/utils.php";

require_once dirname(__FILE__) . '/../bearbeitung/components/grid/grid_states/grid_states.php';

define('OPERATION_INPUT_FINISHED_SELECTED', 'finsel');
define('OPERATION_DE_INPUT_FINISHED_SELECTED', 'definsel');
define('OPERATION_CONTROLLED_SELECTED', 'consel');
define('OPERATION_DE_CONTROLLED_SELECTED', 'deconsel');
define('OPERATION_AUTHORIZATION_SENT_SELECTED', 'sndsel');
define('OPERATION_DE_AUTHORIZATION_SENT_SELECTED', 'desndsel');
define('OPERATION_AUTHORIZATION_REMINDER_SENT_SELECTED', 'sndremindersel');
define('OPERATION_DE_AUTHORIZATION_REMINDER_SENT_SELECTED', 'desndremindersel');
define('OPERATION_CREATE_VERGUETUNGSTRANSPARENZLISTE', 'create-verguetungstransparenzliste');
define('OPERATION_COPY_INTERESSENBINDUNGSVERGUETUNGEN', 'copy-interessenbindungsverguetungen');
define('OPERATION_AUTHORIZE_SELECTED', 'autsel');
define('OPERATION_DE_AUTHORIZE_SELECTED', 'deautsel');
define('OPERATION_RELEASE_SELECTED', 'relsel');
define('OPERATION_DE_RELEASE_SELECTED', 'derelsel');
define('OPERATION_SET_IMRATBIS_SELECTED', 'setimratbissel');
define('OPERATION_CLEAR_IMRATBIS_SELECTED', 'clearimratbissel');
define('OPERATION_SET_EHRENAMTLICH_SELECTED', 'setehrenamtlichsel');
define('OPERATION_SET_ZAHLEND_SELECTED', 'setzahlendsel');
define('OPERATION_SET_BEZAHLT_SELECTED', 'setbezahltsel');

// Adapted from CommitMultiEditGridState, CommitEditedValuesGridState and DeleteSelectedGridState
abstract class AbstractCommitEditSelectedOperationValuesGridState extends CommitMultiEditGridState {

  protected $date;
  protected $text1;
  protected $text2;
  protected $text3;

  protected $userName;
  protected $transactionDateTime;

    protected abstract function DoOperation($rowValues);

    public function ProcessMessages() {
        // TODO $primaryKeyValuesSet = ArrayWrapper::createPostWrapper()->getValue('keys', []);
        $primaryKeys = $this->getPrimaryKeys();

        // TODO $this->getDataset()->applyFilterBasedOnPrimaryKeyValuesSet($primaryKeyValuesSet);

        $this->getSelectionOperationMetadata();
        $this->getSelectionOperationParametersFromPost();

        foreach ($primaryKeys as $primaryKeyValues) {
            $this->getDataset()->SetSingleRecordState($primaryKeyValues);

            $this->getDataset()->Open();
            if ($this->getDataset()->Next()) {
                $this->CheckRLSEditGrant();
                $rowValues = $this->getDataset()->GetCurrentFieldValues();
                $this->getDataset()->Edit();

                $this->DoOperation($rowValues);
                $this->setDBUpdatedMetaData();

                // Commits values and calls events
                $success = $this->doProcessMessages($rowValues);
                if (!$success) {
                  break;
                }
            }

            $this->getDataset()->Close();
        }

        $this->ApplyState(OPERATION_VIEWALL);
    }

    // Adapted from DeleteSelectedGridState
    protected function handleError($message, $displayTime = 0) {
        $this->getDataset()->SetAllRecordsState();
        $this->ChangeState(OPERATION_VIEWALL);
        $this->setGridErrorMessage($message, $displayTime);
    }

    // Adapted form DeleteSelectedGridState
    private function getPrimaryKeys() {
        $primaryKeysArray = [];

        $i = 0;
        while (GetApplication()->IsPOSTValueSet('rec' . $i)) {
            $primaryKeys = [];
            $primaryKeyNames = $this->getDataset()->GetPrimaryKeyFieldNames();
            for ($j = 0; $j < count($primaryKeyNames); $j++)
                $primaryKeys[] = GetApplication()->GetPOSTValue('rec' . $i . '_pk' . $j);
            $primaryKeysArray[] = $primaryKeys;
            $i++;
        }

        return $primaryKeysArray;
    }

    protected function getSelectionOperationMetadata() {
      $this->userName = $this->grid->GetPage()->GetEnvVar('CURRENT_USER_NAME');
      $this->transactionDateTime = $this->grid->GetPage()->GetEnvVar('CURRENT_DATETIME');
    }

    protected function getSelectionOperationParametersFromPost() {
      $input_date = GetApplication()->GetPOSTValue('date');
  //     df('Dates:');
  //     df($input_date);
  //     df($this->GetPage()->GetEnvVar('CURRENT_DATETIME'));
      if ($this->isValidDate($input_date)) {
        $this->date = $input_date;
      } else { // includes empty date
        $this->date = $this->transactionDateTime;
      }
  //     df($this->date);
      $this->text1 = GetApplication()->GetPOSTValue('text1');
      $this->text2 = GetApplication()->GetPOSTValue('text2');
      $this->text3 = GetApplication()->GetPOSTValue('text3');
    }

    // Similar to globalOnBeforeUpdate
    protected function setDBUpdatedMetaData() {
      // df($this->grid->GetDataset()->GetFieldValueByName('id'));
      $this->grid->GetDataset()->SetFieldValueByName('updated_visa', $this->userName);
      $this->grid->GetDataset()->SetFieldValueByName('updated_date', $this->transactionDateTime);
    }

    protected function isValidDate($date) {
      $date_array = date_parse($date);
      return preg_match('/^(0[1-9]|[12][0-9]|3[01])\.(0[1-9]|1[012])\.(20)\d\d$/', $date) && checkdate($date_array["month"], $date_array["day"], $date_array["year"]);
    }

}


class InputFinishedSelectedGridState extends AbstractCommitEditSelectedOperationValuesGridState {
  protected function DoOperation($rowValues) {
    // df($this->grid->GetDataset()->GetFieldValueByName('id'));
    $this->grid->GetDataset()->SetFieldValueByName('eingabe_abgeschlossen_visa', $this->userName);
    $this->grid->GetDataset()->SetFieldValueByName('eingabe_abgeschlossen_datum', $this->transactionDateTime);
  }
}

class DeInputFinishedSelectedGridState extends AbstractCommitEditSelectedOperationValuesGridState {
  protected function DoOperation($rowValues) {
    // df($this->grid->GetDataset()->GetFieldValueByName('id'));
    $this->grid->GetDataset()->SetFieldValueByName('eingabe_abgeschlossen_visa', null);
    $this->grid->GetDataset()->SetFieldValueByName('eingabe_abgeschlossen_datum', null);
  }
}

class ControlledSelectedGridState extends AbstractCommitEditSelectedOperationValuesGridState {
  protected function DoOperation($rowValues) {
    // df($this->grid->GetDataset()->GetFieldValueByName('id'));
    $this->grid->GetDataset()->SetFieldValueByName('kontrolliert_visa', $this->userName);
    $this->grid->GetDataset()->SetFieldValueByName('kontrolliert_datum', $this->transactionDateTime);
  }
}

class DeControlledSelectedGridState extends AbstractCommitEditSelectedOperationValuesGridState {
  protected function DoOperation($rowValues) {
    // df($this->grid->GetDataset()->GetFieldValueByName('id'));
    $this->grid->GetDataset()->SetFieldValueByName('kontrolliert_visa', null);
    $this->grid->GetDataset()->SetFieldValueByName('kontrolliert_datum', null);
  }
}

class AuthorizationSentSelectedGridState extends AbstractCommitEditSelectedOperationValuesGridState {
  protected function DoOperation($rowValues) {
    // df($this->grid->GetDataset()->GetFieldValueByName('id'));
    $this->grid->GetDataset()->SetFieldValueByName('autorisierung_verschickt_visa', $this->userName);
    $this->grid->GetDataset()->SetFieldValueByName('autorisierung_verschickt_datum', $this->date);
  }
}

class DeAuthorizationSentSelectedGridState extends AbstractCommitEditSelectedOperationValuesGridState {
  protected function DoOperation($rowValues) {
    // df($this->grid->GetDataset()->GetFieldValueByName('id'));
    $this->grid->GetDataset()->SetFieldValueByName('autorisierung_verschickt_visa', null);
    $this->grid->GetDataset()->SetFieldValueByName('autorisierung_verschickt_datum', null);
  }
}

class AuthorizationReminderSentSelectedGridState extends AbstractCommitEditSelectedOperationValuesGridState {
  protected function DoOperation($rowValues) {
    // df($this->grid->GetDataset()->GetFieldValueByName('id'));
    $this->grid->GetDataset()->SetFieldValueByName('autorisierung_reminder_verschickt_visa', $this->userName);
    $this->grid->GetDataset()->SetFieldValueByName('autorisierung_reminder_verschickt_datum', $this->date);
  }
}

class DeAuthorizationReminderSentSelectedGridState extends AbstractCommitEditSelectedOperationValuesGridState {
  protected function DoOperation($rowValues) {
    // df($this->grid->GetDataset()->GetFieldValueByName('id'));
    $this->grid->GetDataset()->SetFieldValueByName('autorisierung_reminder_verschickt_visa', null);
    $this->grid->GetDataset()->SetFieldValueByName('autorisierung_reminder_verschickt_datum', null);
  }
}

class AuthorizeSelectedGridState extends AbstractCommitEditSelectedOperationValuesGridState {
  protected function DoOperation($rowValues) {
    // df($this->grid->GetDataset()->GetFieldValueByName('id'));
    $this->grid->GetDataset()->SetFieldValueByName('autorisiert_visa', $this->userName);
    $this->grid->GetDataset()->SetFieldValueByName('autorisiert_datum', $this->date);
  }
}

class DeAuthorizeSelectedGridState extends AbstractCommitEditSelectedOperationValuesGridState {
  protected function DoOperation($rowValues) {
    // df($this->grid->GetDataset()->GetFieldValueByName('id'));
    $this->grid->GetDataset()->SetFieldValueByName('autorisiert_visa', null);
    $this->grid->GetDataset()->SetFieldValueByName('autorisiert_datum', null);
  }
}

class ReleaseSelectedGridState extends AbstractCommitEditSelectedOperationValuesGridState {
  protected function DoOperation($rowValues) {
    // df($this->grid->GetDataset()->GetFieldValueByName('id'));
    $this->grid->GetDataset()->SetFieldValueByName('freigabe_visa', $this->userName);
    $this->grid->GetDataset()->SetFieldValueByName('freigabe_datum', $this->date);
  }
}

class DeReleaseSelectedGridState extends AbstractCommitEditSelectedOperationValuesGridState {
  protected function DoOperation($rowValues) {
    // df($this->grid->GetDataset()->GetFieldValueByName('id'));
    $this->grid->GetDataset()->SetFieldValueByName('freigabe_visa', null);
    $this->grid->GetDataset()->SetFieldValueByName('freigabe_datum', null);
  }
}

class SetImRatBisSelectedGridState extends AbstractCommitEditSelectedOperationValuesGridState {
  protected function DoOperation($rowValues) {
    $this->grid->GetDataset()->SetFieldValueByName('im_rat_bis', $this->date);
  }
}

class ClearImRatBisSelectedGridState extends AbstractCommitEditSelectedOperationValuesGridState {
  protected function DoOperation($rowValues) {
    $this->grid->GetDataset()->SetFieldValueByName('im_rat_bis', null);
  }
}

class SetEhrenamtlichSelectedGridState extends AbstractCommitEditSelectedOperationValuesGridState {
  protected function DoOperation($rowValues) {
    $id = $this->grid->GetDataset()->GetFieldValueByName('id');
//     df($id, "SetEhrenamtlichSelectedGridState.DoOperation($rowValues) id");
    $sql_date = "STR_TO_DATE('$this->transactionDateTime','%d-%m-%Y %T')";
    $table_raw = preg_replace('/[`]/i', '', $this->grid->GetDataset()->GetName());
    $table = preg_replace('/^vf_/i', '', $table_raw);
    $year = getRechercheJahrFromSettings();
    $desc = !empty($this->text1) && $this->text1 != 'null' && $this->text1 != 'undefined' ? "'{$this->text1}'" : "'Ehrenamtlich'";
    $src = !empty($this->text2) && $this->text2 != 'null' && $this->text2 != 'undefined' ? "'{$this->text2}'" : 'NULL';
    $url = !empty($this->text3) && $this->text3 != 'null' && $this->text3 != 'undefined' ? "'{$this->text3}'" : 'NULL';

    // Quick and dirty solution to fill another table
    $sql = "INSERT INTO {$table}_jahr (`{$table}_id`, `jahr`, `verguetung`, `beschreibung`, `quelle_url`, `quelle_url_gueltig`, `quelle`, `notizen`, `created_visa`, `created_date`, `updated_visa`, `updated_date`) VALUES ($id, $year, 0, $desc, $url, NULL, $src, NULL, '$this->userName', $sql_date, '$this->userName', $sql_date);"; // CURRENT_TIMESTAMP
//     df($sql, "SQL");

    $eng_con = getDBConnection();
    $eng_con->ExecSQL($sql);
  }
}

class SetBezahltSelectedGridState extends AbstractCommitEditSelectedOperationValuesGridState {
  protected function DoOperation($rowValues) {
    $id = $this->grid->GetDataset()->GetFieldValueByName('id');
    // df($id, "SetBezahltSelectedGridState.DoOperation($rowValues) id");
    $sql_date = "STR_TO_DATE('$this->transactionDateTime','%d-%m-%Y %T')";
    $table_raw = preg_replace('/[`]/i', '', $this->grid->GetDataset()->GetName());
    $table = preg_replace('/^vf_/i', '', $table_raw);
    $year = getRechercheJahrFromSettings();
    df($this->text1, '$this->text1');
    $desc = !empty($this->text1) && $this->text1 != 'null' && $this->text1 != 'undefined' ? "'{$this->text1}'" : "'Bezahlt'";
    $src = !empty($this->text2) && $this->text2 != 'null' && $this->text2 != 'undefined' ? "'{$this->text2}'" : 'NULL';
    $url = !empty($this->text3) && $this->text3 != 'null' && $this->text3 != 'undefined' ? "'{$this->text3}'" : 'NULL';

    // Quick and dirty solution to fill another table
    $sql = "INSERT INTO {$table}_jahr (`{$table}_id`, `jahr`, `verguetung`, `beschreibung`, `quelle_url`, `quelle_url_gueltig`, `quelle`, `notizen`, `created_visa`, `created_date`, `updated_visa`, `updated_date`) VALUES ($id, $year, 1, $desc, $url, NULL, $src, NULL, '$this->userName', $sql_date, '$this->userName', $sql_date);"; // CURRENT_TIMESTAMP
//     df($sql, "SQL");

    $eng_con = getDBConnection();
    $eng_con->ExecSQL($sql);
  }
}

class SetZahlendSelectedGridState extends AbstractCommitEditSelectedOperationValuesGridState {
  protected function DoOperation($rowValues) {
    $id = $this->grid->GetDataset()->GetFieldValueByName('id');
//     df($id, "SetZahlendSelectedGridState.DoOperation($rowValues) id");
    $sql_date = "STR_TO_DATE('$this->transactionDateTime','%d-%m-%Y %T')";
    $table_raw = preg_replace('/[`]/i', '', $this->grid->GetDataset()->GetName());
    $table = preg_replace('/^vf_/i', '', $table_raw);
    $year = getRechercheJahrFromSettings();
    $desc = !empty($this->text1) && $this->text1 != 'null' && $this->text1 != 'undefined' ? "'{$this->text1}'" : "'Bezahlendes Mitglied'";
    $src = !empty($this->text2) && $this->text2 != 'null' && $this->text2 != 'undefined' ? "'{$this->text2}'" : 'NULL';
    $url = !empty($this->text3) && $this->text3 != 'null' && $this->text3 != 'undefined' ? "'{$this->text3}'" : 'NULL';

    // Quick and dirty solution to fill another table
    $sql = "INSERT INTO {$table}_jahr (`{$table}_id`, `jahr`, `verguetung`, `beschreibung`, `quelle_url`, `quelle_url_gueltig`, `quelle`, `notizen`, `created_visa`, `created_date`, `updated_visa`, `updated_date`) VALUES ($id, $year, -1, $desc, $url, NULL, $src, NULL, '$this->userName', $sql_date, '$this->userName', $sql_date);"; // CURRENT_TIMESTAMP
//     df($sql, "SQL");

    $eng_con = getDBConnection();
    $eng_con->ExecSQL($sql);
  }
}

// A hack: Disable functionaly of parent class. Being a subclass is wrong, but the easiest way
class CreateVerguetungstransparenzliste extends AbstractCommitEditSelectedOperationValuesGridState {

    // A hack: Disable functionaly of parent class. Being a subclass is wrong, but the easiest way
    // See AbstractCommitEditSelectedOperationValuesGridState.ProcessMessages()
    public function ProcessMessages() {
        $this->getSelectionOperationMetadata();
        $this->getSelectionOperationParametersFromPost();

        $this->DoOperation(null);
        $this->ApplyState(OPERATION_VIEWALL);
    }

  protected function DoOperation($rowValues) {
    $sql_date = "STR_TO_DATE('$this->transactionDateTime','%d-%m-%Y %T')";
    $date = date('d-m-Y', strtotime($this->date));
    $stichdatum = "STR_TO_DATE('$date','%d-%m-%Y')";

    // Quick and dirty solution to fill another table
    $sql = "INSERT INTO `parlamentarier_transparenz` (parlamentarier_id, stichdatum, created_visa, updated_visa, created_date, updated_date)
SELECT id, $stichdatum, '$this->userName', '$this->userName', $sql_date, $sql_date
FROM parlamentarier
WHERE (parlamentarier.im_rat_bis IS NULL OR parlamentarier.im_rat_bis > NOW())
ORDER BY nachname, vorname, id;";
//     df($sql, "SQL");

    $eng_con = getDBConnection();
    $eng_con->ExecSQL($sql);
  }
}

// A hack: Disable functionaly of parent class. Being a subclass is wrong, but the easiest way
class CopyInteressenbindungsverguetungen extends AbstractCommitEditSelectedOperationValuesGridState {

    // A hack: Disable functionaly of parent class. Being a subclass is wrong, but the easiest way
    // See AbstractCommitEditSelectedOperationValuesGridState.ProcessMessages()
    public function ProcessMessages() {
        $this->getSelectionOperationMetadata();
        $this->getSelectionOperationParametersFromPost();

        $this->DoOperation(null);
        $this->ApplyState(OPERATION_VIEWALL);
    }

  protected function DoOperation($rowValues) {
    $sql_date = "STR_TO_DATE('$this->transactionDateTime','%d-%m-%Y %T')";
    $date = date('d-m-Y', strtotime($this->date));

    // This SQL statement can copy from several older years, currently it only copies from the last year

    // Quick and dirty solution to fill another table
    $sql = "INSERT INTO `interessenbindung_jahr` (`interessenbindung_id`, `verguetung`, `jahr`, `beschreibung`, `quelle_url`, `quelle`, `notizen`, `eingabe_abgeschlossen_datum`, `eingabe_abgeschlossen_visa`, `freigabe_visa`, `freigabe_datum`, `created_visa`, `created_date`, `updated_visa`)
    SELECT
    interessenbindung_jahr.interessenbindung_id
    , interessenbindung_jahr.verguetung
    , YEAR( $sql_date ) as jahr
    , interessenbindung_jahr.beschreibung
    , interessenbindung_jahr.quelle_url
    , interessenbindung_jahr.quelle
    , CONCAT_WS('\n\n', CONCAT(DATE_FORMAT($sql_date,'%d.%m.%Y'), '/script/$this->userName: Kopiert von Jahr ', interessenbindung_jahr.jahr, ', id=', interessenbindung_jahr.id), interessenbindung_jahr.notizen) as notizen
    , $sql_date as eingabe_abgeschlossen_datum
    , '$this->userName' as eingabe_abgeschlossen_visa
    ,  CONCAT(SUBSTRING(REPLACE(REPLACE(interessenbindung_jahr.freigabe_visa, '*', ''), '+', ''), 1, 9), '+') as freigabe_visa
    , interessenbindung_jahr.freigabe_datum
    , CONCAT(SUBSTRING(REPLACE(REPLACE(interessenbindung_jahr.created_visa, '*', ''), '+', ''), 1, 9), '+') as created_visa
    , $sql_date as created_date
    , '$this->userName' as updated_visa
    FROM interessenbindung_jahr
    JOIN interessenbindung ON interessenbindung.id = interessenbindung_jahr.interessenbindung_id AND (interessenbindung.bis IS NULL OR interessenbindung.bis >= $sql_date)
    JOIN parlamentarier ON parlamentarier.id = interessenbindung.parlamentarier_id AND (parlamentarier.im_rat_bis IS NULL OR parlamentarier.im_rat_bis > $sql_date)
    WHERE interessenbindung_jahr.jahr = (SELECT MAX(ijn.jahr)
        FROM interessenbindung_jahr ijn
        WHERE ijn.interessenbindung_id = interessenbindung_jahr.interessenbindung_id
        AND ijn.jahr >= YEAR($sql_date) - 1
        GROUP BY ijn.interessenbindung_id)
    AND interessenbindung_jahr.jahr < YEAR($sql_date)
    ORDER BY `interessenbindung_jahr`.`jahr`  DESC, `interessenbindung_jahr`.`id` DESC;";
//     df($sql, "SQL");

    $eng_con = getDBConnection();
    $eng_con->ExecSQL($sql);
  }
}

function getFilterDataFromGlobals_handleFavoriteFilters($grid, SuperGlobals $superGlobals, $id, $default) {
  $expires = time() + 3600 * 24 * 365 * 5;
  for ($i = 1; $i <= 5; $i++) {
    $favorite_filter_name = $grid->getId() . "_{$id}_favorite-filter-$i";
    if ($superGlobals->isPostValueSet("save-favorite-filter-$i")) {
      // $superGlobals->setSessionVariable($favorite_filter_name, $superGlobals->getSessionVariableDef($grid->getId() . $id, $default));
      // $superGlobals->setSessionVariable($favorite_filter_name . "_name", $superGlobals->GetPostValueDef("save-favorite-filter-$i-name", null));
      setcookie($favorite_filter_name, SystemUtils::ToJSON($superGlobals->getSessionVariableDef($grid->getId() . $id, $default)), $expires);
      setcookie($favorite_filter_name . "_name", $superGlobals->GetPostValueDef("save-favorite-filter-$i-name", null), $expires);
    } elseif ($superGlobals->isPostValueSet("restore-favorite-filter-$i")) {
      return SystemUtils::FromJSON($_COOKIE[$favorite_filter_name], true) ?? $default;
      // return $superGlobals->getSessionVariableDef($favorite_filter_name, $default);
    }
  }
  return null;
}
