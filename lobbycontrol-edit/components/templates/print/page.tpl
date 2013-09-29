<html{if $Page->GetPageDirection() != null} dir="{$Page->GetPageDirection()}"{/if}>
    <head>
        <title>{$Page->GetCaption()}</title>
        <meta http-equiv="content-type" content="text/html{if $Page->GetContentEncoding() != null}; charset={$Page->GetContentEncoding()}{/if}">
    </head>
<style>
img
{ldelim}
    border-width: 0px;
{rdelim}
body
{ldelim}
    font-family: Verdana;
{rdelim}
table
{ldelim}
    border-collapse: collapse;
{rdelim}
td
{ldelim}
    font-size: 11;
    padding: 5px;
    margin: 0px;
    border-width: 1px;
    border-style: solid;
    border-color: #000000;
{rdelim}
@media print
{ldelim}
    a.pdf
    {ldelim}
        display:none
    {rdelim}
{rdelim}

</style>
<body style="background-color:white">
    <h1>{$Page->GetCaption()}</h1>

{$Grid}
</body>
</html>