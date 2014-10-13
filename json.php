<?php

require_once("Request.php");
require_once("database.php");
require_once("config.php");

header('Content-Type: application/json; charset=utf-8');
$action = $_GET['action'];
echo Request::$action($db);
exit();

?>