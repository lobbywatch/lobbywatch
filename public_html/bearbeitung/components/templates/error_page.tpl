{capture assign="ContentBlock"}

<div class="row-fluid">

    <div class="span2"></div>

        <div class="span8">

        <div class="alert alert-error">
            <h3>{$Captions->GetMessageString('Error')}</h3>

            {$Captions->GetMessageString('CriticalErrorSuggestions')}

            <br /><br />

            <h4>{$Captions->GetMessageString('ErrorDetails')}:</h4>

            <div style="padding-left: 20px;">
                {$Message}
            </div>

        </div>
    </div>
</div>

{/capture}


{include file="common/layout.tpl"}
