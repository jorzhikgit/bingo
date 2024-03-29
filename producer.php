<?php
require_once "l18n.php";
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php __('producer.title', 'Producer'); ?> | Bingo</title>
	<meta charset="utf-8" />
	<link rel="stylesheet" type="text/css" href="css/producer.css" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="js/producer.php"></script>
</head>
<body>
	<div class="noJs"><strong><?php __('producer.js', 'You must have JavaScript turned on in order to use this application.'); ?></strong></div>
	<div id="drawn"></div>
	
	<div id="left">
		<div id="jackpotNumber"></div>
		<div id="jackpot"></div>
		<div id="countWrapper"><p><?php __('producer.count', 'Number drawn'); ?>: <span id="count">0</span></p></div>
		<div id="rows"></div>
	</div>

	<div id="right" class="cf">
		<table id="drawnTable">	
			<tr>
				<td id="number-1"></td>
				<td id="number-11"></td>
				<td id="number-21"></td>
				<td id="number-31"></td>
				<td id="number-41"></td>
				<td id="number-51"></td>
				<td id="number-61"></td>
				<td id="number-71"></td>
				<td id="number-81"></td>
			</tr>
			<tr>
				<td id="number-2"></td>
				<td id="number-12"></td>
				<td id="number-22"></td>
				<td id="number-32"></td>
				<td id="number-42"></td>
				<td id="number-52"></td>
				<td id="number-62"></td>
				<td id="number-72"></td>
				<td id="number-82"></td>
			</tr>
			<tr>
				<td id="number-3"></td>
				<td id="number-13"></td>
				<td id="number-23"></td>
				<td id="number-33"></td>
				<td id="number-43"></td>
				<td id="number-53"></td>
				<td id="number-63"></td>
				<td id="number-73"></td>
				<td id="number-83"></td>
			</tr>
			<tr>
				<td id="number-4"></td>
				<td id="number-14"></td>
				<td id="number-24"></td>
				<td id="number-34"></td>
				<td id="number-44"></td>
				<td id="number-54"></td>
				<td id="number-64"></td>
				<td id="number-74"></td>
				<td id="number-84"></td>
			</tr>
			<tr>
				<td id="number-5"></td>
				<td id="number-15"></td>
				<td id="number-25"></td>
				<td id="number-35"></td>
				<td id="number-45"></td>
				<td id="number-55"></td>
				<td id="number-65"></td>
				<td id="number-75"></td>
				<td id="number-85"></td>
			</tr>
			<tr>
				<td id="number-6"></td>
				<td id="number-16"></td>
				<td id="number-26"></td>
				<td id="number-36"></td>
				<td id="number-46"></td>
				<td id="number-56"></td>
				<td id="number-66"></td>
				<td id="number-76"></td>
				<td id="number-86"></td>
			</tr>
			<tr>
				<td id="number-7"></td>
				<td id="number-17"></td>
				<td id="number-27"></td>
				<td id="number-37"></td>
				<td id="number-47"></td>
				<td id="number-57"></td>
				<td id="number-67"></td>
				<td id="number-77"></td>
				<td id="number-87"></td>
			</tr>
			<tr>
				<td id="number-8"></td>
				<td id="number-18"></td>
				<td id="number-28"></td>
				<td id="number-38"></td>
				<td id="number-48"></td>
				<td id="number-58"></td>
				<td id="number-68"></td>
				<td id="number-78"></td>
				<td id="number-88"></td>
			</tr>
			<tr>
				<td id="number-9"></td>
				<td id="number-19"></td>
				<td id="number-29"></td>
				<td id="number-39"></td>
				<td id="number-49"></td>
				<td id="number-59"></td>
				<td id="number-69"></td>
				<td id="number-79"></td>
				<td id="number-89"></td>
			</tr>
			<tr>
				<td id="number-10"></td>
				<td id="number-20"></td>
				<td id="number-30"></td>
				<td id="number-40"></td>
				<td id="number-50"></td>
				<td id="number-60"></td>
				<td id="number-70"></td>
				<td id="number-80"></td>
				<td id="number-90"></td>
			</tr>
		</table>

		<div id="drawing">
			<div id="numberIsWrapper"><p id="numberIs"></p></div>
		</div>
	</div>
</body>
</html>