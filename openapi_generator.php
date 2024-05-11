<?php

require_once dirname(__FILE__) . '/public_html/common/utils.php';

$workflow_tables_overwritten = Constants::$workflow_tables;
$workflow_tables_overwritten['interessengruppe'] = 'Lobbygruppe (Interessengruppe)';

$tables = [];
$tables = array_merge($tables, array_map(function ($table, $name) { return ['id' => $table, 'name' => $name, 'type' => 'flat', 'call_type' => 'table', 'idCall' => true, 'listCall' => true, 'searchCall' => true]; }, array_keys($workflow_tables_overwritten), $workflow_tables_overwritten));

$tables = array_merge($tables, array_map(function ($table, $name) { return ['id' => $table, 'name' => $name, 'type' => 'flat', 'call_type' => 'relation', 'idCall' => false, 'listCall' => true, 'searchCall' => false]; }, array_keys(Constants::getAllEnrichedRelations()), Constants::getAllEnrichedRelations()));

$tables[] = ['id' => 'parlamentarier', 'name' => 'aggregierter Parlamentarier', 'type' => 'aggregated', 'call_type' => 'table', 'idCall' => true, 'listCall' => false, 'searchCall' => false];
$tables[] = ['id' => 'zutrittsberechtigung', 'name' => 'aggregierter Gast', 'type' => 'aggregated', 'call_type' => 'table', 'idCall' => true, 'listCall' => false, 'searchCall' => false];
$tables[] = ['id' => 'organisation', 'name' => 'aggregierte Organisation', 'type' => 'aggregated', 'call_type' => 'table', 'idCall' => true, 'listCall' => false, 'searchCall' => false];
$tables[] = ['id' => 'interessengruppe', 'name' => 'aggregierte Lobbygruppe (Interessengruppe)', 'type' => 'aggregated', 'call_type' => 'table', 'idCall' => true, 'listCall' => false, 'searchCall' => false];
$tables[] = ['id' => 'branche', 'name' => 'aggregierte Branche', 'type' => 'aggregated', 'call_type' => 'table', 'idCall' => true, 'listCall' => false, 'searchCall' => false];
$tables[] = ['id' => 'parlament-partei', 'name' => 'aggregierte Parlament-Partei', 'type' => 'aggregated', 'call_type' => 'query', 'idCall' => false, 'listCall' => true, 'searchCall' => false];

$ref = '$ref';
foreach($tables as $table) {
  $table_id = $table['id'];
  $table_id_camel = ucfirst($table_id);
  $type = $table['type'];
  $type_camel = ucfirst($type);
  $name = $table['name'];
  $name_camel = ucfirst($name);
  $call_type = $table['call_type'];
  $call_type_camel = ucfirst($table['call_type']);

  if ($table['idCall']) {
    print("
  /$call_type/$table_id/$type/id/{id}:
    get:
      operationId: get{$type_camel}{$table_id_camel}Id
      summary: Get $name by ID
      description: Return a single $name
      tags:
        - By ID
        - $type_camel
        - $name_camel
        - $call_type_camel
      parameters:
        - $ref: '#/components/parameters/idParam'
        - $ref: '#/components/parameters/limitParam'
        - $ref: '#/components/parameters/filterSimpleParam'
        - $ref: '#/components/parameters/filterListParam'
        - $ref: '#/components/parameters/likeParam'
        - $ref: '#/components/parameters/selectFieldsParam'
        - $ref: '#/components/parameters/langParam'
        - $ref: '#/components/parameters/includeUnpublishedParam'
        - $ref: '#/components/parameters/includeInactiveParam'
        - $ref: '#/components/parameters/includeConfidentialDataParam'
        - $ref: '#/components/parameters/includeMetaDataParam'
      responses:
        200:
          description: Returns $name
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/GenericIdData'
              examples:
                Successful:
                  $ref: '#/components/examples/Successful'
                Error:
                  $ref: '#/components/examples/Error'
        404:
          $ref: '#/components/responses/NotFound'
"
    );
  }
if ($table['listCall']) {
  print("
  /$call_type/$table_id/$type/list:
    get:
      operationId: get{$type_camel}{$table_id_camel}List
      summary: Get list of $name
      description: Return a list of $name
      tags:
        - List
        - $type_camel
        - $name_camel
        - $call_type_camel
      parameters:
      - $ref: '#/components/parameters/limitParam'
      - $ref: '#/components/parameters/filterSimpleParam'
      - $ref: '#/components/parameters/filterListParam'
      - $ref: '#/components/parameters/likeParam'
      - $ref: '#/components/parameters/selectFieldsParam'
      - $ref: '#/components/parameters/langParam'
      - $ref: '#/components/parameters/includeUnpublishedParam'
      - $ref: '#/components/parameters/includeInactiveParam'
      - $ref: '#/components/parameters/includeConfidentialDataParam'
      - $ref: '#/components/parameters/includeMetaDataParam'
    responses:
        200:
          description: Returns list of $name
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/GenericListData'
              examples:
                Successful:
                  $ref: '#/components/examples/Successful'
                Error:
                  $ref: '#/components/examples/Error'
"
    );
  }

if ($table['searchCall']) {
  print("
  /$call_type/$table_id/$type/list/{str}:
    get:
      operationId: get{$type_camel}{$table_id_camel}List
      summary: Get list of $name
      description: Return a list of $name
      tags:
        - Search
        - $type_camel
        - $name_camel
        - $call_type_camel
      parameters:
        - $ref: '#/components/parameters/searchStrParam'
        - $ref: '#/components/parameters/limitParam'
        - $ref: '#/components/parameters/filterSimpleParam'
        - $ref: '#/components/parameters/filterListParam'
        - $ref: '#/components/parameters/likeParam'
        - $ref: '#/components/parameters/selectFieldsParam'
        - $ref: '#/components/parameters/langParam'
        - $ref: '#/components/parameters/includeUnpublishedParam'
        - $ref: '#/components/parameters/includeInactiveParam'
        - $ref: '#/components/parameters/includeConfidentialDataParam'
        - $ref: '#/components/parameters/includeMetaDataParam'
      responses:
        200:
          description: Returns list of $name whose name contain the search string
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/GenericListData'
              examples:
                Successful:
                  $ref: '#/components/examples/Successful'
                Error:
                  $ref: '#/components/examples/Error'
"
    );
  }
  print("\n");
}
