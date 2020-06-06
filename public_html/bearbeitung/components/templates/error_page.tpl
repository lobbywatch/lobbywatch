{assign var="ErrorTitle" value=$Captions->getMessageString('Error')};

{capture assign="ErrorContent"}
    <p>{$Captions->GetMessageString('CriticalErrorSuggestions')}</p>
    <h3>{$Captions->GetMessageString('ErrorDetails')}:</h3>
    <p style="text-indent: 20px">{$Message}</p>
{/capture}

{include file="common/error.tpl"}
