<?php

module_load_include('inc', 'lobbywatch', 'lobbywatch.daten');


function _lobbywatch_visual_page_title($prefix, $suffix, $table, $title = '') {
//   df("_lobbywatch_page_title $prefix, $suffix, $table, $id");
//   df($prefix . _lobbywatch_fetch_anzeige_name($table, $id) . $suffix . ' title: '. $title, '_lobbywatch_page_title');
//   require_once '123';
  $id = _lobbywatch_visual_get_parameter_id();
  if ($table == 'parlamentarier') {
    $title = _lobbywatch_fetch_parlamentarier_title($table, $id);
  } elseif ($table == 'organsation') {
    $title = _lobbywatch_fetch_organisation_title($table, $id);
  } else {
    $title = $prefix . _lobbywatch_fetch_anzeige_name($table, $id) . $suffix;
  }
  // Workaround, title is otherwise empty, quite a strange thing
  drupal_set_title($title);
  return $title;
}

function _lobbywatch_visual_get_parameter_id() {
  if (key_exists('id', $_GET)) {
//     dpm($_GET['id'], 'id');
    return intval($_GET['id']);
  } else {
    drupal_not_found();
  }
}

function _lobbywatch_visual_parlamentarier_id($name='', $check_unpublished = true) {

//   dpm($id, '_lobbywatch_visual_parlamentarier_id');

//   $url_name = _lobbywatch_anzeige_name_for_url('parlamentarier', $id);
  $id = _lobbywatch_visual_get_parameter_id();
  $table = 'parlamentarier';
  $name = _lobbywatch_fetch_anzeige_name($table, $id);
//   $options = array('query' => array('id' => $id));
//   drupal_goto("daten/grafik/parlamentarier", $options);


  libraries_load('d3.drupal');
  drupal_add_js(drupal_get_path('module', 'lobbywatch_visual') . '/js/parlamentarier_view.js');

  //$markup = _ibexutils_include2string(drupal_get_path('module', 'lobbywatch_visual') . '/parlamentarier_view.inc');

  $markup = '';

  $markup .= '<p class="admin">EXPERIMENTELL!</p>';
  $markup .= '
<form id="mainForm">
  <div class="headerRow">
     <table>
        <tr>
           <td>
              <a id="lnkParlamentOverview" href="parlament.html">
                 <img style="border-style: none;" alt="Parlament" src="/sites/all/modules/lobbywatch/lobbywatch_visual/images//bundeshaus_symbol.gif" title="Parlament Übersicht" /></a>
           </td>
           <td>
              <h2 id="lblSubjectDetail"></h2>
              <br />
              <h5 id="lblSubjectDetail2"></h5>
           </td>
        </tr>
        <tr>
           <td style="">
              <div class="filterCheckbox" title="Filtereinstellungen aktivieren/deaktivieren">
                 <input type="checkbox" value="None" id="chkFilterOnOff" name="" checked="checked" />
                 <label for="chkFilterOnOff">Filter</label>
              </div>
           </td>
           <td>
              <table>
                 <tr >
                    <td><h5>Interessenbindungen:</h5></td>
                    <td id="cellCriteriaOptionsDeep" style="padding:0px 10px 0px 4px;"></td>
                    <td><h5>Funktion:</h5></td>
                    <td id="cellCriteriaOptionsFunction" style="padding:0px 10px 0px 4px;"></td>
                 </tr>
                 <tr>
                    <td><h5>Branche:</h5></td>
                    <td id="cellFilterOptionsBranche" style="padding:0px 10px 0px 4px;"></td>
                    <td><h5>Kommission:</h5></td>
                    <td id="cellFilterOptionsKommission" style="padding:0px 10px 0px 4px;"></td>
                 </tr>
              </table>
           </td>
        </tr>
     </table>'
     . "<p><a href='/de/daten/$table/$id'>$name Textansicht</a></p>" . '
  </div>

  <div id="chart" class="divChart">
  </div>
</form>
';

  $build = [];
  $build['terms'] = array(
    '#weight' => -5,
    '#prefix' => '<div class="lobbywatch">',
    '#markup' => $markup,
    '#suffix' => '</div>',
  );

    return $build;
}

function _lobbywatch_visual_organisation_id($name='', $check_unpublished = true) {

  $id = _lobbywatch_visual_get_parameter_id();
  $table = 'organisation';
  $name = _lobbywatch_fetch_anzeige_name($table, $id);
  //   $options = array('query' => array('id' => $id));
//   drupal_goto("daten/grafik/organisation", $options);

  libraries_load('d3.drupal');
  drupal_add_js(drupal_get_path('module', 'lobbywatch_visual') . '/js/organisation_view.js');

  //$markup = _ibexutils_include2string(drupal_get_path('module', 'lobbywatch_visual') . '/parlamentarier_view.inc');

  $markup = '';

  $markup .= '<p class="admin">EXPERIMENTELL!</p>';
  $markup .= '
<div class="headerRow">
  <table>
     <tr>
        <td>
           <a id="lnkParlamentOverview" href="ParlamentOverview.html"><img style="border-style: none;" alt="Parlament" src="/sites/all/modules/lobbywatch/lobbywatch_visual/images/bundeshaus_symbol.gif" title="Parlament Übersicht" /></a>
        </td>
        <td>
           <h2 id="lblSubjectDetail"></h2>
           <br />
           <h5 id="lblSubjectDetail2"></h5>
        </td>
     </tr>
  </table>'
     . "<p><a href='/de/daten/$table/$id'>$name Textansicht</a></p>" . '
</div>

<div id="chart" class="divChart">
</div>
';

  $build = [];
  $build['terms'] = array(
    '#weight' => -5,
    '#prefix' => '<div class="lobbywatch">',
    '#markup' => $markup,
    '#suffix' => '</div>',
  );

    return $build;
}
