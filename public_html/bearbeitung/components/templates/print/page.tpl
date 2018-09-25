<!DOCTYPE html>
<html{if $Page->GetPageDirection() != null} dir="{$Page->GetPageDirection()}"{/if}>
    <head>
        <title>{$Page->GetTitle()}</title>
        <meta http-equiv="content-type" content="text/html{if $Page->GetContentEncoding() != null}; charset={$Page->GetContentEncoding()}{/if}">
        <link rel="stylesheet" href="components/assets/css/print.css">
        <link rel="stylesheet" href="components/assets/css/user_print.css">
</head>
<body style="background-color:white" id="print-{$Page->GetPageId()}">
    <h1>{$Page->GetTitle()}</h1>
    {$Grid}
</body>
</html>