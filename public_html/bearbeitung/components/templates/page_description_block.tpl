{if $Description}
<div class="well description">
    <a href="#" class="close" onclick="$(this).closest('.well').hide(); return false;"><span aria-hidden="true">&times;</span></a>
    {$Description}
</div>
{/if}