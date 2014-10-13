<?php

$db = new PDO('mysql:host=localhost;dbname=bingo', "bingo", "bingoradioen",
              array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
?>