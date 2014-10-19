<?php

require_once("Request.php");
require_once("database.php");

header('Content-Type: application/json; charset=utf-8');
$action = $_GET['action'];

if (!method_exists(new Request, $action)) {
	echo json_encode(['status' => false, 'error' => 'Unkown API call.']);
	exit();
}

echo Request::$action($db);
exit();

?>