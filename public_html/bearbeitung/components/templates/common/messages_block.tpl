{if isset($GridMessages.MessageDisplayTime)}
    {assign var=displayTime value=$GridMessages.MessageDisplayTime}
{else}
    {assign var=displayTime value=null}
{/if}

{include file='common/messages.tpl' type='danger' messages=$GridMessages.ErrorMessages}
{include file='common/messages.tpl' type='success' messages=$GridMessages.Messages}
