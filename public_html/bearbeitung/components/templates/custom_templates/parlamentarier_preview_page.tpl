{capture assign="ContentBlock"}

<div class="row-fluid">

    {*<div class="span2"></div>*}

        <div class="span8">

        <div class="">
            <h3>{$Parlamentarier.Title}</h3>

            <div style="padding-left: 20px;">
                {$Parlamentarier.Preview}
            </div>

        </div>
    </div>
</div>

{/capture}

{capture assign="SideBar"}
  {include file="page_list.tpl" List=$PageList}
{/capture}

{include file="common/layout.tpl"}
