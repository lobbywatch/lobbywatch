<html{if $Page->GetPageDirection() != null} dir="{$Page->GetPageDirection()}"{/if}>
    <head>
        <title>{$Page->GetTitle()}</title>
        <meta http-equiv="content-type" content="text/html{if $Page->GetContentEncoding() != null}; charset={$Page->GetContentEncoding()}{/if}">
    </head>
<style>
{literal}
img {
    border-width: 0px;
}
body {
    font-family: Verdana;
}
table {
    border-collapse: collapse;
}
td {
    font-size: 11;
    padding: 5px;
    margin: 0px;
    border-width: 1px;
    border-style: solid;
    border-color: #000000;
}
@media print {
    a.pdf {
        display:none
    }
}
{/literal}
</style>
<body style="background-color:white">
    <h1>{$Page->GetTitle()}</h1>

{$MasterGrid}
<br>
<div style="padding-left: 20px;">
{$Grid}
</div>
</body>
</html>