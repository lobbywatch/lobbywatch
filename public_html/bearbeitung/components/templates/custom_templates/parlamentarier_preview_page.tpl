{capture assign="ContentBlock"}

{* http://jquerytools.org/demos/tabs/index.html *}
{*<script src="http://cdn.jquerytools.org/1.2.7/full/jquery.tools.min.js"></script>*}
<script src="components/templates/custom_templates/js/jquery.tools.tabs.min.js"></script>

<!-- the tabs -->
<ul class="css-tabs">
  <li><a href="#preview">Vorschau</a></li>
  <li><a href="#email-parlam">Autorisierungs-E-Mail Parlamentarier</a></li>
  {if isset($Zutrittsberechtigter0.Id)}<li><a href="#email-zb0">Autorisierungs-E-Mail Zutrittsberechtiger 1</a></li>{/if}
  {if isset($Zutrittsberechtigter1.Id)}<li><a href="#email-zb1">Autorisierungs-E-Mail Zutrittsberechtiger 2</a></li>{/if}
</ul>
 
<!-- tab "panes" -->
<div class="css-panes">

  <!-- First tab content.-->
  <div {*class="row-fluid"*}>

    {*<div class="span2"></div>*}

    <div class="row-fluid" {*class="span8"*}>

        <div class="preview">
            <h3>{$Parlamentarier.Title}</h3>
            
                        <div class="container-fluid">

              <div class="row-fluid">
                  <div class="btn-toolbar">

                      <div class="btn-group">
                          <a id="btn-eingabe-abgeschlossen-parlam" class="btn" href="#">
                              <i class="pg-icon-input-finished-selected"></i>
                              Eingabe abgeschlossen
                          </a>
                          <a id="btn-kontrolliert-parlam" class="btn" href="#">
                              <i class="pg-icon-controlled-selected"></i>
                              Kontrolliert
                          </a>
                      </div>
                               
                      <div class="btn-group">
                          <a class="btn" href="parlamentarier.php?operation=edit&pk0={$Parlamentarier.Id}">Bearbeiten</a>
                      </div>
          
                      <div class="btn-group">
                          <a class="btn" href="parlamentarier.php?operation=view&pk0={$Parlamentarier.Id}">Ansehen</a>
                      </div>
                      
                      <div class="btn-group">
                          <a class="btn btn-primary" href="parlamentarier.php?operation=return">{$Captions->GetMessageString('BackToList')}</a>
                      </div>
                      
                  </div>
              </div>
            </div> {*container-fluid*}

            <div {*style="padding-left: 20px;"*} class="span8">
              {$Parlamentarier.State}
            </div>

            <div {*style="padding-left: 20px;"*} class="preview-content span8">
                {$Parlamentarier.Preview}
            </div>

            <div class="span8">
            <hr>
            <h2>Parlament.ch</h2>
              <p>Importiert mit Webservice von ws.parlament.ch, Stand {$Parlamentarier.import_date_wsparlamentch}, siehe <a href="http://www.parlament.ch/d/suche/seiten/biografie.aspx?biografie_id={$Parlamentarier.parlament_biografie_id}">{$Parlamentarier.parlamentarier_name} ({$Parlamentarier.parlament_biografie_id})</a></p>
              <h3>Parlament.ch Interessenbindungen</h3>
              <div>{$Parlamentarier.parlament_interessenbindungen}</div>
              <h3>Ämter</h3>
              <div>{$Parlamentarier.aemter}</div>
              <h3>Weitere Ämter</h3>
              <div>{$Parlamentarier.weitere aemter}</div>
            </div>

        </div>
    </div>
  </div> <!-- end first tab content.-->
  
  <!-- Second tab content.-->
  <div {*class="row-fluid"*}>
    
    <div id="opsParlam" class="hide">
      <div class="pg-row-selected-sim">
        <input name="rec0" type="checkbox" checked>
        <input name="rec0_pk0" value="{$Parlamentarier.Id}" type="hidden">
      </div>
    </div>

    {*<div class="span2"></div>*}

    <div class="row-fluid" {*class="span8"*}>

        <div class="email">
            <h3>{$Parlamentarier.EmailTitle}</h3>
            
            <div class="container-fluid">

              <div class="row-fluid">
                  <div class="btn-toolbar">
          
                      <p><small>1. E-Mail selektieren/kopieren, 2. E-Mail öffnen, 3. E-Mail-Text einfügen und 4. &quot;Autorisierungsanfrage verschickt&quot; setzen.</small></p>
                  
                      <div class="btn-group">
                          <a id="email-select-button-parlam" class="btn" href="#" title="NEU! Ab Firefox 43 und Chrome 42 wird der selektierte Text direkt in die Zwischenablage kopiert.">
                              E-Mail kopieren
                          </a>
                      </div>
          
                      <div class="btn-group">
                          <a id="email-open-parlam" class="btn" href="#">
                              E-Mail öffnen
                          </a>
                      </div>
          
                      <div class="btn-group">
                          <a id="email-sent-parlam" class="btn" href="#">
                              <i class="pg-icon-authorization-sent-selected"></i>
                              Autorisierungsanfrage verschickt
                          </a>
                      </div>
          
          {*        </div>
             </div>
                      
              <div class="row-fluid">
                  <div class="btn-toolbar">
          *}
                      
                      <div class="btn-group">
                          <a class="btn" href="parlamentarier.php?operation=edit&pk0={$Parlamentarier.Id}">Bearbeiten</a>
                      </div>
          
                      <div class="btn-group">
                          <a class="btn" href="parlamentarier.php?operation=view&pk0={$Parlamentarier.Id}">Ansehen</a>
                      </div>
                      
                      <div class="btn-group">
                          <a class="btn btn-primary" href="parlamentarier.php?operation=return">{$Captions->GetMessageString('BackToList')}</a>
                      </div>
                      
                  </div>
              </div>
            </div> {*container-fluid*}
            
            {* <button id="email-select-button">E-Mail selektieren</button>
            <button id="email-open-parlam">E-Mail öffnen</button>
            <button id="email-sent-parlam">Autorisierungsanfrage verschickt</button> *} 

            <div {*style="padding-left: 20px;"*} class="span8">
              {$Parlamentarier.State}
            </div>

            <div id="email-content-parlam" {*style="padding-left: 20px;"*} class="email-content span8">{$Parlamentarier.EmailText}
            </div>

        </div>
    </div>
  </div>  <!-- end second tab content.-->

  <!-- Third tab content.-->
  <div {*class="row-fluid"*}>
    
    <div id="opsZb0" class="hide">
      <div class="pg-row-selected-sim">
        <input name="rec0" type="checkbox" checked>
        <input name="rec0_pk0" value="{$Zutrittsberechtigter0.Id}" type="hidden">
      </div>
    </div>

    {*<div class="span2"></div>*}

    <div class="row-fluid" {*class="span8"*}>

        <div class="email">
            <h3>{$Zutrittsberechtigter0.EmailTitle}</h3>
            
            <div class="container-fluid">

              <div class="row-fluid">
                  <div class="btn-toolbar">
          
                      <p><small>1. E-Mail selektieren/kopieren, 2. E-Mail öffnen, 3. E-Mail-Text einfügen und 4. &quot;Autorisierungsanfrage verschickt&quot; setzen.</small></p>
                  
                      <div class="btn-group">
                          <a id="email-select-button-zb0" class="btn" href="#" title="NEU! Ab Firefox 43 und Chrome 42 wird der selektierte Text direkt in die Zwischenablage kopiert.">
                              E-Mail kopieren
                          </a>
                      </div>
          
                      <div class="btn-group">
                          <a id="email-open-zb0" class="btn" href="#">
                              E-Mail öffnen
                          </a>
                      </div>
          
                      <div class="btn-group">
                          <a id="email-sent-zb0" class="btn" href="#">
                              <i class="pg-icon-authorization-sent-selected"></i>
                              Autorisierungsanfrage verschickt
                          </a>
                      </div>
          
          {*        </div>
             </div>
                      
              <div class="row-fluid">
                  <div class="btn-toolbar">
          *}
                      
                      <div class="btn-group">
                          <a class="btn" href="person.php?operation=edit&pk0={$Zutrittsberechtigter0.Id}">Bearbeiten</a>
                      </div>
          
                      <div class="btn-group">
                          <a class="btn" href="person.php?operation=view&pk0={$Zutrittsberechtigter0.Id}">Ansehen</a>
                      </div>
                      
                      <div class="btn-group">
                          <a class="btn btn-primary" href="parlamentarier.php?operation=return">{$Captions->GetMessageString('BackToList')}</a>
                      </div>
                      
                  </div>
              </div>
            </div> {*container-fluid*}
            
            {* <button id="email-select-button">E-Mail selektieren</button>
            <button id="email-open-zb0">E-Mail öffnen</button>
            <button id="email-sent-zb0">Autorisierungsanfrage verschickt</button> *} 

            <div {*style="padding-left: 20px;"*} class="span8">
              {$Zutrittsberechtigter0.State}
            </div>

            <div id="email-content-zb0" {*style="padding-left: 20px;"*} class="email-content span8">{$Zutrittsberechtigter0.EmailText}
            </div>

        </div>
    </div>
  </div>  <!-- end third tab content.-->

  <!-- Fourth tab content.-->
  <div {*class="row-fluid"*}>
    
    <div id="opsZb1" class="hide">
      <div class="pg-row-selected-sim">
        <input name="rec0" type="checkbox" checked>
        <input name="rec0_pk0" value="{$Zutrittsberechtigter1.Id}" type="hidden">
      </div>
    </div>

    {*<div class="span2"></div>*}

    <div class="row-fluid" {*class="span8"*}>

        <div class="email">
            <h3>{$Zutrittsberechtigter1.EmailTitle}</h3>
            
            <div class="container-fluid">

              <div class="row-fluid">
                  <div class="btn-toolbar">
          
                      <p><small>1. E-Mail selektieren/kopieren, 2. E-Mail öffnen, 3. E-Mail-Text einfügen und 4. &quot;Autorisierungsanfrage verschickt&quot; setzen.</small></p>
                  
                      <div class="btn-group">
                          <a id="email-select-button-zb1" class="btn" href="#" title="NEU! Ab Firefox 43 und Chrome 42 wird der selektierte Text direkt in die Zwischenablage kopiert.">
                              E-Mail kopieren
                          </a>
                      </div>
          
                      <div class="btn-group">
                          <a id="email-open-zb1" class="btn" href="#">
                              E-Mail öffnen
                          </a>
                      </div>
          
                      <div class="btn-group">
                          <a id="email-sent-zb1" class="btn" href="#">
                              <i class="pg-icon-authorization-sent-selected"></i>
                              Autorisierungsanfrage verschickt
                          </a>
                      </div>
          
          {*        </div>
             </div>
                      
              <div class="row-fluid">
                  <div class="btn-toolbar">
          *}
                      
                      <div class="btn-group">
                          <a class="btn" href="person.php?operation=edit&pk0={$Zutrittsberechtigter1.Id}">Bearbeiten</a>
                      </div>
          
                      <div class="btn-group">
                          <a class="btn" href="person.php?operation=view&pk0={$Zutrittsberechtigter1.Id}">Ansehen</a>
                      </div>
                      
                      <div class="btn-group">
                          <a class="btn btn-primary" href="parlamentarier.php?operation=return">{$Captions->GetMessageString('BackToList')}</a>
                      </div>
                      
                  </div>
              </div>
            </div> {*container-fluid*}
            
            {* <button id="email-select-button">E-Mail selektieren</button>
            <button id="email-open-zb1">E-Mail öffnen</button>
            <button id="email-sent-zb1">Autorisierungsanfrage verschickt</button> *} 

            <div {*style="padding-left: 20px;"*} class="span8">
              {$Zutrittsberechtigter1.State}
            </div>

            <div id="email-content-zb1" {*style="padding-left: 20px;"*} class="email-content span8">{$Zutrittsberechtigter1.EmailText}
            </div>

        </div>
    </div>
  </div>  <!-- end fourth tab content.-->

</div><!-- tab "panes" -->

<script type="text/javascript">{literal}
  //http://stackoverflow.com/questions/985272/jquery-selecting-text-in-an-element-akin-to-highlighting-with-your-mouse
  function selectText(element) {
      var doc = document
          , text = doc.getElementById(element)
          , range, selection
      ;    
      if (doc.body.createTextRange) { //ms
          range = doc.body.createTextRange();
          range.moveToElementText(text);
          range.select();
      } else if (window.getSelection) { //all others
          selection = window.getSelection();        
          range = doc.createRange();
          range.selectNodeContents(text);
          selection.removeAllRanges();
          selection.addRange(range);
      }
  }
  
  jQuery.fn.selectText = function(){
      var doc = document
          , element = this[0]
          , range, selection
      ;
      if (doc.body.createTextRange) {
          range = document.body.createTextRange();
          range.moveToElementText(element);
          range.select();
      } else if (window.getSelection) {
          selection = window.getSelection();        
          range = document.createRange();
          range.selectNodeContents(element);
          selection.removeAllRanges();
          selection.addRange(range);
      }
  };
  
  // Adapted from pgui.grid.ops.js
  function operateRowAsSelected(action, operation, date, ids) {
    var selectedRows = $(ids)
        .find('.pg-row-selected-sim')
        .filter(function() {
            return true;
            //return $(this).find('input[type=checkbox]').prop('checked') ? true : false;
        });
    var $form = $('<form>')
        .addClass('hide')
        .attr('method', 'post')
        .attr('action', action)
        .append($('<input name="operation" value="' + operation + '">'))
        .append(
            $('<input name="recordCount">')
                .attr('value', $(ids).find('.pg-row-selected-sim').length))
        .appendTo($('body'));

    selectedRows.each(function() {
        $(this).find('input').clone().appendTo($form);
    });
    $form.append($('<input name="date" value="' + date + '">'));
    $form.submit();
  }
  
  function countSelectedRows(ids) {
    var selectedRows = $(ids)
      .find('.pg-row-selected-sim')
      .filter(function() {
          return true;
         //return $(this).find('input[type=checkbox]').prop('checked') ? true : false;
      });
    return selectedRows.length;
  }

  // perform JavaScript after the document is scriptable.
  $(function() {
      // setup ul.tabs to work as tabs for each div directly under div.panes
      $(".css-tabs:first").tabs(".css-panes:first > div", { history: true });
      
      //$(".email-content").dblclick(function() {
      //  selectText('email-content');
      //});

      $("#email-select-button-parlam").click(function() {
        //selectText('email-content');
        $('#email-content-parlam').selectText();
        // Copy to clipboard, ref: https://developer.mozilla.org/en-US/docs/Web/API/Document/execCommand
        document.execCommand("copy", false, null);
        return false;
      });

      $("#email-select-button-zb0").click(function() {
        //selectText('email-content');
        $('#email-content-zb0').selectText();
        // Copy to clipboard, ref: https://developer.mozilla.org/en-US/docs/Web/API/Document/execCommand
        document.execCommand("copy", false, null);
        return false;
      });

      $("#email-select-button-zb1").click(function() {
        //selectText('email-content');
        $('#email-content-zb1').selectText();
        // Copy to clipboard, ref: https://developer.mozilla.org/en-US/docs/Web/API/Document/execCommand
        document.execCommand("copy", false, null);
        return false;
      });

      $("#email-open-parlam").click(function() {
        window.open("{/literal}{$Parlamentarier.MailTo}{literal}", 'Mailer');
        return false;
      });

      $("#email-open-zb0").click(function() {
        window.open("{/literal}{$Zutrittsberechtigter0.MailTo}{literal}", 'Mailer');
        return false;
      });

      $("#email-open-zb1").click(function() {
        window.open("{/literal}{$Zutrittsberechtigter1.MailTo}{literal}", 'Mailer');
        return false;
      });

      $("#email-sent-parlam").click(function() {
        require(['bootbox.min'], function() {
    
          var ids = '#opsParlam';
          var nRows = countSelectedRows(ids);
          bootbox.animate(false);
          bootbox.confirm( 'Parlamentarier-Autorisierungsanfrage verschickt? (n=' + nRows + ')' /*localizer.getString('DeleteSelectedRecordsQuestion')*/, function(confirmed) {
              if (confirmed) {
                //self.operateSelectRows('sndsel');
                operateRowAsSelected('parlamentarier.php', 'sndsel', '', ids);
              }
          });
        });
        return false;
      });
      
      $("#email-sent-zb0").click(function() {
        require(['bootbox.min'], function() {
    
          var ids = '#opsZb0';
          var nRows = countSelectedRows(ids);
          bootbox.animate(false);
          bootbox.confirm( 'Zutrittsberechtiger-Autorisierungsanfrage verschickt? (n=' + nRows + ')' /*localizer.getString('DeleteSelectedRecordsQuestion')*/, function(confirmed) {
              if (confirmed) {
                //self.operateSelectRows('sndsel');
                operateRowAsSelected('person.php', 'sndsel', '', ids);
              }
          });
        });
        return false;
      });
      
      $("#email-sent-zb1").click(function() {
        require(['bootbox.min'], function() {
    
          var ids = '#opsZb1';
          var nRows = countSelectedRows(ids);
          bootbox.animate(false);
          bootbox.confirm( 'Zutrittsberechtiger-Autorisierungsanfrage verschickt? (n=' + nRows + ')' /*localizer.getString('DeleteSelectedRecordsQuestion')*/, function(confirmed) {
              if (confirmed) {
                //self.operateSelectRows('sndsel');
                operateRowAsSelected('person.php', 'sndsel', '', ids);
              }
          });
        });
        return false;
      });
      
      $("#btn-eingabe-abgeschlossen-parlam").click(function() {
        require(['bootbox.min'], function() {
    
          var ids = '#opsParlam';
          var nRows = countSelectedRows(ids);
          bootbox.animate(false);
          bootbox.confirm( 'Eingabe abgeschlossen? (n=' + nRows + ')' /*localizer.getString('DeleteSelectedRecordsQuestion')*/, function(confirmed) {
              if (confirmed) {
                //self.operateSelectRows('sndsel');
                operateRowAsSelected('parlamentarier.php', 'finsel', '', ids);
              }
          });
        });
        return false;
      });
      
      $("#btn-kontrolliert-parlam").click(function() {
        require(['bootbox.min'], function() {
    
          var ids = '#opsParlam';
          var nRows = countSelectedRows(ids);
          bootbox.animate(false);
          bootbox.confirm( 'Eingabe kontrolliert? (n=' + nRows + ')' /*localizer.getString('DeleteSelectedRecordsQuestion')*/, function(confirmed) {
              if (confirmed) {
                //self.operateSelectRows('sndsel');
                operateRowAsSelected('parlamentarier.php', 'consel', '', ids);
              }
          });
        });
        return false;
      });


  });
{/literal}</script>


{/capture}

{capture assign="SideBar"}
  {include file="page_list.tpl" List=$PageList}
{/capture}

{include file="common/layout.tpl"}
