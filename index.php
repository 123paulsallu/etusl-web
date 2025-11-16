<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ETUSL Web</title>
    <link rel="stylesheet" href="styles/logo.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            width: 100vw;
            max-width: 100vw;
            overflow-x: hidden;
        }
        body {
            font-family: 'Italiana', 'Quicksand', Arial, sans-serif;
            min-height: 100vh;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    <!-- Top Header Component -->
    <?php include __DIR__ . '/components/TopHeaderComponent.html'; ?>
    <!-- Header Component -->
    <?php include __DIR__ . '/components/HeaderComponent.html'; ?>
</body>
</html>
