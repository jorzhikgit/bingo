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
            $("#jackpotNumber").text("Lykketall: " + data.jackpot_number);
            $("#jackpot").text("Lykkepott: kr. " + data.jackpot + ",-");
            $("#rows").text("Antall rader: " + data.current_row);
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