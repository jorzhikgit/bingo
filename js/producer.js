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
            for (var i = 0; i < numbers.length; i++) {
                addNumber(numbers[i]);
            }
        });

        socket.on("number", function (number) {
            addNumber(number);
        });

        socket.on("round", function (data) {
            $("#jackpotNumber").text("Lykketall: " + data.jackpotNumber);
            $("#jackpot").text("Lykkepott: kr. " + data.jackpot + ",-");
            $("#rows").text("Antall rader: " + data.rows);
        });
    });

    function addNumber(number) {
        var count = parseInt($("#count").text());
        $("#count").text(count + 1);

        $("#number-" + number).text(number);
        $("#numberIs").text(number);

        var glue = ($("#drawn").text() == "") ? "" : ", ";
        $("#drawn").prepend(number + glue);
    }
});