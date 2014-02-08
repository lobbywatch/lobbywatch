{capture assign="ContentBlock"}

{* http://jquerytools.org/demos/tabs/index.html *}
{*<script src="http://cdn.jquerytools.org/1.2.7/full/jquery.tools.min.js"></script>*}
<script src="components/templates/custom_templates/js/jquery.tools.min.js"></script>

<!-- the tabs -->
<ul class="css-tabs">
  <li><a href="#preview">Vorschau</a></li>
  <li><a href="#email">Autorisierungs-E-Mail</a></li>
</ul>
 
<!-- tab "panes" -->
<div class="css-panes">
  <!-- First tab content.-->
  <div class="row-fluid">

    {*<div class="span2"></div>*}

    <div class="span8">

        <div class="preview">
            <h3>{$Parlamentarier.Title}</h3>

            <div style="padding-left: 20px;">
                {$Parlamentarier.Preview}
            </div>

        </div>
    </div>
  </div>
  <!-- Second tab content.-->
  <div class="row-fluid">

    {*<div class="span2"></div>*}

    <div class="span8">

        <div class="email">
            <h3>{$Parlamentarier.EmailTitle}</h3>

            <div style="padding-left: 20px;">
                {$Parlamentarier.EmailText}
            </div>

        </div>
    </div>
  </div>
</div>

<script type="text/javascript">{literal}
  // perform JavaScript after the document is scriptable.
  $(function() {
      // setup ul.tabs to work as tabs for each div directly under div.panes
      $(".css-tabs:first").tabs(".css-panes:first > div", { history: true });
  });
{/literal}</script>


{/capture}

{capture assign="SideBar"}
  {include file="page_list.tpl" List=$PageList}
{/capture}

{include file="common/layout.tpl"}
