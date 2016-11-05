<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=8, IE=9, IE=10">
    <link rel="stylesheet" type="text/css" href="components/assets/css/main.css">
</head>
<body style="padding-top: 0;">

    <div class="container">

        <h1>{$Viewer->GetCaption()}</h1>

        {$Viewer->GetValue($Renderer)}

    </div>

</body>
</html>