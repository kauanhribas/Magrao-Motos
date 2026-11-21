<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home</title>

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Bootstrap JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" defer></script>

        <!-- Styles do CSS -->
        <link href="assets/css/styles.css" rel="stylesheet"/>

        <?php
            define("root_path", __DIR__ . "/");
        
            $documentRoot = realpath($_SERVER['DOCUMENT_ROOT']);
            $projectPath = realpath(root_path);

            $relativePath = str_replace(
                str_replace('\\', '/', $documentRoot),
                '',
                str_replace('\\', '/', $projectPath)
            );

            define('root_url', '/' . trim($relativePath, '/') . '/');
        ?>

    </head>

    <body>
