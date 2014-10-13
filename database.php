<?php

$db = new PDO('mysql:host=127.0.0.1;dbname=bingo', "bingo", "bingoradioen",
              array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
?>