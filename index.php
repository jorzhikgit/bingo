<?php
require_once "l18n.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>index | Bingo</title>
    <meta charset="utf-8" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <style>
        body {
            margin: 1em auto;
            font-size: 16px;
            width: 30em;
            font-family: 'Open Sans', sans-serif;
        }

        h1 {
            text-align: center;
        }

        ul {
            list-style-type: none;
            padding: 0;
            width: 24em;
            margin: 0 auto;
        }

        li {
            float: left;
            margin: 2em;
            display: block;
            width: 10em;
        }

        li:first-child {
            margin-left: 0;
        }

        li:last-child {
            margin-right: 0;
        }

        a:link, a:visited {
            text-decoration: none;
            color: white;
            background-color: #0074d9;
            padding: 1em 2em;
            border-radius: 0.5em;
        }

        a:hover, a:active {
            background-color: #001f3f;
        }
    </style>
</head>
</html>
<body>
    <h1><?php __('index.iAm', 'I am a...'); ?></h1>
    <ul>
        <li><a href="drawing.php"><?php __('index.presenter', 'Presenter'); ?></a></li>
        <li><a href="producer.php"><?php __('index.producer', 'Producer'); ?></a></li>
    </ul>
</body>
</html>