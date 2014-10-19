<?php
require_once "l18n.php";
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php __('drawing.title', 'Drawing'); ?> | Bingo</title>
	<meta charset="utf-8" />
	<link rel="stylesheet" type="text/css" href="css/jquery.modal.css" />
	<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/themes/smoothness/jquery-ui.css" />
	<link rel="stylesheet" type="text/css" href="css/drawing.css" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js"></script>
	<script src="js/jquery.modal.min.js"></script> <!-- from: https://github.com/kylefox/jquery-modal -->
	<script src="js/drawing.php"></script>
</head>
<body>
	<div class="noJs"><strong><?php __('drawing.js', 'You must have JavaScript turned on in order to use this application.'); ?></strong></div>
	<div id="drawn"></div>
	
	<div id="left">
		<h2 id="name"></h2>
		<div id="jackpotNumber"></div>
		<div id="jackpot"></div>
		<div id="countWrapper"><p><?php __('drawing.count', 'Number of draws'); ?>: <span id="count">0</span></p></div>
		<div id="rows"></div>
		<div id="studio"></div>
		<div><a href="#" class="button" id="newRound"><?php __('drawing.newRound', 'New round'); ?></a></div>
		<div><a href="#" class="button" id="newRow"><?php __('drawing.newRow', 'Increment row'); ?></a></div>
	</div>

	<div id="right" class="cf">
		<div id="verifyWrapper"><p><input type="text" id="verificationCode" /><a href="#" class="button" id="verify"><?php __('drawing.verify', 'Verify ticket'); ?></a></p></div>

		<table id="drawnTable">	
			<tr>
				<td id="number-1" class="number"></td>
				<td id="number-11" class="number"></td>
				<td id="number-21" class="number"></td>
				<td id="number-31" class="number"></td>
				<td id="number-41" class="number"></td>
				<td id="number-51" class="number"></td>
				<td id="number-61" class="number"></td>
				<td id="number-71" class="number"></td>
				<td id="number-81" class="number"></td>
			</tr>
			<tr>
				<td id="number-2" class="number"></td>
				<td id="number-12" class="number"></td>
				<td id="number-22" class="number"></td>
				<td id="number-32" class="number"></td>
				<td id="number-42" class="number"></td>
				<td id="number-52" class="number"></td>
				<td id="number-62" class="number"></td>
				<td id="number-72" class="number"></td>
				<td id="number-82" class="number"></td>
			</tr>
			<tr>
				<td id="number-3" class="number"></td>
				<td id="number-13" class="number"></td>
				<td id="number-23" class="number"></td>
				<td id="number-33" class="number"></td>
				<td id="number-43" class="number"></td>
				<td id="number-53" class="number"></td>
				<td id="number-63" class="number"></td>
				<td id="number-73" class="number"></td>
				<td id="number-83" class="number"></td>
			</tr>
			<tr>
				<td id="number-4" class="number"></td>
				<td id="number-14" class="number"></td>
				<td id="number-24" class="number"></td>
				<td id="number-34" class="number"></td>
				<td id="number-44" class="number"></td>
				<td id="number-54" class="number"></td>
				<td id="number-64" class="number"></td>
				<td id="number-74" class="number"></td>
				<td id="number-84" class="number"></td>
			</tr>
			<tr>
				<td id="number-5" class="number"></td>
				<td id="number-15" class="number"></td>
				<td id="number-25" class="number"></td>
				<td id="number-35" class="number"></td>
				<td id="number-45" class="number"></td>
				<td id="number-55" class="number"></td>
				<td id="number-65" class="number"></td>
				<td id="number-75" class="number"></td>
				<td id="number-85" class="number"></td>
			</tr>
			<tr>
				<td id="number-6" class="number"></td>
				<td id="number-16" class="number"></td>
				<td id="number-26" class="number"></td>
				<td id="number-36" class="number"></td>
				<td id="number-46" class="number"></td>
				<td id="number-56" class="number"></td>
				<td id="number-66" class="number"></td>
				<td id="number-76" class="number"></td>
				<td id="number-86" class="number"></td>
			</tr>
			<tr>
				<td id="number-7" class="number"></td>
				<td id="number-17" class="number"></td>
				<td id="number-27" class="number"></td>
				<td id="number-37" class="number"></td>
				<td id="number-47" class="number"></td>
				<td id="number-57" class="number"></td>
				<td id="number-67" class="number"></td>
				<td id="number-77" class="number"></td>
				<td id="number-87" class="number"></td>
			</tr>
			<tr>
				<td id="number-8" class="number"></td>
				<td id="number-18" class="number"></td>
				<td id="number-28" class="number"></td>
				<td id="number-38" class="number"></td>
				<td id="number-48" class="number"></td>
				<td id="number-58" class="number"></td>
				<td id="number-68" class="number"></td>
				<td id="number-78" class="number"></td>
				<td id="number-88" class="number"></td>
			</tr>
			<tr>
				<td id="number-9" class="number"></td>
				<td id="number-19" class="number"></td>
				<td id="number-29" class="number"></td>
				<td id="number-39" class="number"></td>
				<td id="number-49" class="number"></td>
				<td id="number-59" class="number"></td>
				<td id="number-69" class="number"></td>
				<td id="number-79" class="number"></td>
				<td id="number-89" class="number"></td>
			</tr>
			<tr>
				<td id="number-10" class="number"></td>
				<td id="number-20" class="number"></td>
				<td id="number-30" class="number"></td>
				<td id="number-40" class="number"></td>
				<td id="number-50" class="number"></td>
				<td id="number-60" class="number"></td>
				<td id="number-70" class="number"></td>
				<td id="number-80" class="number"></td>
				<td id="number-90" class="number"></td>
			</tr>
		</table>

		<div id="drawing">
			<div id="numberIsWrapper"><p id="numberIs"></p></div>
			<a href="#" id="draw" class="button"><?php __('drawing.draw', 'Draw number'); ?></a>
		</div>
	</div>
	<div id="modal" class="modal"></div>
</body>
</html>
