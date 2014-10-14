<?php
require_once "../l18n.php";
l18n::set_path('../lang/');
header('Content-Type: application/javascript; charset=utf-8');
?>
var port = 4000;

$(document).ready(function () {
    var socket = null;

    $(".noJs").hide();
    $("#left").css("height", $(document).height() - $("#drawn").outerHeight(true));

    $.getScript("http://" + document.domain + ":" + port + "/socket.io/socket.io.js").done(function () {
        socket = io("http://" + document.domain + ":" + port);

        socket.emit("init", true);
        socket.emit("requestNumbers", 0);

        socket.on("numbers", function (numbers) {
        addNumbers(numbers);
        });

        socket.on("round", function (data) {
        console.log(data);
            $("#jackpotNumber").text("<?php __('producer.jackpotNumber', 'Jackpot number'); ?>: " + data.jackpot_number);
            $("#jackpot").text("<?php __('producer.jackpot', 'Jackpot'); ?>: <?php __('producer.currency', '$'); ?>" + data.jackpot + ",-");
            $("#rows").text("<?php __('producer.currentRows', 'Number of rows'); ?>: " + data.current_row);
        });
    });

    function addNumbers(numbers) {
        $("#count").text(numbers.length);
        $("#numberIs").text(numbers[numbers.length - 1]);
        $("#drawn").text(numbers.reverse().join(", "));

        numbers.forEach(function(number) {
            $("#number-" + number).text(number);
        });
    }
});