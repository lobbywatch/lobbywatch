<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=8, IE=9, IE=10">
    <link rel="stylesheet" type="text/css" href="components/css/main.css" />
</head>
<body>

    <table class="pgui-grid grid" style="width: auto">
        <thead>
            <tr class="header"><th >{$Viewer->GetCaption()}</th></tr>
        </thead>
        <tr class="even">
            <td class="even" style="padding: 10px; text-align: left">
                <p align="justify">{$Viewer->GetValue($Renderer)}</p>
            </td>
        </tr>
    </table>
    <div style="margin: 8px; text-align: right;">
        <a href="#" class="btn btn-primary" onClick="window.close(); return false;">{$Captions->GetMessageString('CloseWindow')}</a>
    </div>



</body>
</html>
