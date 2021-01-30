<?php

// @todo alias für namensänderungen machen
class Constants {

  /** Data tables containing the workflow fields.
   */
  // i18n use getter for access and translate
  public static $workflow_tables = array(
    'branche' => 'Branche',
    'interessenbindung' => 'Interessenbindung',
    'interessenbindung_jahr' => 'Interessenbindungsvergütung',
    'interessengruppe' => 'Lobbygruppe',
    'in_kommission' => 'In Kommission',
    'kommission' => 'Kommission',
    'mandat' => 'Mandat',
    'mandat_jahr' => 'Mandatsvergütung',
    'organisation' => 'Organisation',
    'organisation_beziehung' => 'Organisation Beziehung',
    'organisation_jahr' => 'Organisationsjahr',
    'parlamentarier' => 'Parlamentarier',
    'parlamentarier_transparenz' => 'Parlamentariertransparenz',
    'partei' => 'Partei',
    'fraktion' => 'Fraktion',
    'rat' => 'Rat',
    'kanton' => 'Kanton',
    'kanton_jahr' => 'Kantonjahr',
    'zutrittsberechtigung' => 'Zutrittsberechtigter',
    'person' => 'Person',
    'wissensartikel_link' => 'Lobbypediaverknüpfung',
  );

  /**
   * Connector: kommission_id
   */
  public static $enriched_relations_kommission = array(
      'in_kommission_parlamentarier' => 'Parlamenterier einer Kommission',
  );

  /**
   * Connector: parlamentarier_id
   */
  public static $enriched_relations_parlamentarier = array(
      'in_kommission_liste' => 'Kommissionen für Parlamenterier',
      'interessenbindung_liste' => 'Interessenbindung eines Parlamenteriers',
      'interessenbindung_liste_indirekt' => 'Indirekte Interessenbindungen eines Parlamenteriers',
      'zutrittsberechtigung_mandate' => 'Mandate einer Zutrittsberechtigung (INNER JOIN)',
      'zutrittsberechtigung_mit_mandaten' => 'Mandate einer Zutrittsberechtigung (LFET JOIN)',
      'zutrittsberechtigung_mit_mandaten_indirekt' => 'Indirekte Mandate einer Zutrittsberechtigung (INNER JOIN)',
      'organisation_parlamentarier' => 'Parlamenterier, die eine Interessenbindung zu dieser Organisation haben',
      'organisation_parlamentarier_indirekt' => 'Parlamenterier, die eine indirekte Interessenbindung zu dieser Organisation haben',
      'organisation_parlamentarier_beide' => 'Parlamenterier, die eine Zutrittsberechtiung mit Mandant oder Interessenbindung zu dieser Organisation haben',
      'organisation_parlamentarier_beide_indirekt' => 'Parlamenterier, die eine indirekte Interessenbindung oder indirekte Zutrittsberechtiung mit Mandat zu dieser Organisation haben',
  );

  /**
   * Connector: zutrittsberechtigung_id
   */
  public static $enriched_relations_zutrittsberechtigung = array(
  );

  /**
   * Connector: organisation_id
   */
  public static $enriched_relations_organisation = array(
      'organisation_beziehung_arbeitet_fuer' => 'Organisationen für welche eine PR-Agentur arbeitet.',
      'organisation_beziehung_mitglied_von' => 'Organisationen, in welcher eine Organisation Mitglied ist',
      'organisation_beziehung_muttergesellschaft' => 'Muttergesellschaften',
      'organisation_parlamentarier' => 'Parlamenterier, die eine Interessenbindung zu dieser Organisation haben',
      'organisation_parlamentarier_indirekt' => 'Parlamenterier, die eine indirekte Interessenbindung zu dieser Organisation haben',
      'organisation_parlamentarier_beide' => 'Parlamenterier, die eine Zutrittsberechtiung mit Mandant oder Interessenbindung zu dieser Organisation haben',
      'organisation_parlamentarier_beide_indirekt' => 'Parlamenterier, die eine indirekte Interessenbindung oder indirekte Zutrittsberechtiung mit Mandat zu dieser Organisation haben',
  );

  /**
   * Connector: ziel_organisation_id
   */
  public static $enriched_relations_organisation_inverse = array(
      'organisation_beziehung_auftraggeber_fuer' => 'Organisationen, die eine PR-Firma beauftragt haben',
      'organisation_beziehung_mitglieder' => 'Mitgliedsorganisationen',
      'organisation_beziehung_tochtergesellschaften' => 'Tochtergesellschaften',
  );

  /**
   * Meta tables such as attachments or settings.
   */
  public static $meta_tables = array(
    'parlamentarier_anhang' => 'Parlamentarieranhang',
    'organisation_anhang' => 'Organisationsanhang',
    'zutrittsberechtigung_anhang' => 'Zutrittsberechtigunganhang',
    'settings' => 'Einstellungen',
    'settings_category' => 'Einstellungskategorien',
  );

  /** Internal fields that are confidential. */
  public static $intern_fields = array('notizen', 'updated_visa', 'created_visa', 'autorisiert_visa', 'freigabe_visa', 'eingabe_abgeschlossen_visa', 'kontrolliert_visa', 'symbol_abs', 'photo', 'ALT_kommission', 'ALT_parlam_verbindung', 'parlament_interessenbindungen');

  /** Internal fields that are confidential. Not 'freigabe_datum', 'freigabe_datum_unix' */
  public static $meta_fields = array('updated_date', 'updated_date_unix', 'created_date', 'created_date_unix', 'autorisiert_datum', 'autorisierung_verschickt_visa', 'autorisierung_verschickt_datum', 'autorisiert_datum_unix', 'eingabe_abgeschlossen_datum', 'eingabe_abgeschlossen_datum_unix', 'kontrolliert_datum', 'kontrolliert_datum_unix', 'refreshed_date');

  /**
   * table name => website alias
   */
  // public static $entities_web = array('branche' => 'branche', 'interessengruppe' => 'lobbygruppe', 'kommission' => 'kommission', 'organisation' => 'organisation', 'partei' => 'partei',);
  public static $entities_web = array('branche' => 'branche', 'interessengruppe' => 'lobbygruppe', 'organisation' => 'organisation',);

  public static $entities_web_old = array('kommission' => 'kommission', 'partei' => 'partei',);

  /** Parlamentarier, Zutrittsberechtigung
   * table name => website alias */
// TODO check zutrittsberechtigung or person
  public static $entities_special_web = array('parlamentarier' => 'parlamentarier', 'zutrittsberechtigung' => 'zutrittsberechtigter',);

  public static function getAllEntities() {
    return array_merge(Constants::$entities_web, Constants::$entities_special_web);
  }

  public static function getAllEnrichedRelations() {
    return array_merge(Constants::$enriched_relations_kommission, Constants::$enriched_relations_parlamentarier, Constants::$enriched_relations_zutrittsberechtigung, Constants::$enriched_relations_organisation, Constants::$enriched_relations_organisation_inverse);
  }

  public static function getPublicTables() {
    return array_merge($workflow_tables, getAllEnrichedRelations());
  }

  public static function getAllTables() {
    return array_merge(getPublicTables(), $meta_tables);
  }

  /**
   * Modification date for significant webpage changes, e.g if new information is shown.
   * This string will be parsed to a UNIX date with strtotime().
   * Structure: tableName => date string in YYYY-MM-DD or null
   */
  public static $page_changes_of_table = array('branche' => null, 'interessengruppe' => '2015-05-17', 'kommission' => null, 'organisation' => '2015-05-17', 'partei' => null,'parlamentarier' => null, 'zutrittsberechtigung' => null,);

}
