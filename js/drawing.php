<?php
require_once "../l18n.php";
l18n::set_path('../lang/');
header('Content-Type: application/javascript; charset=utf-8');
?>
$(document).ready(function () {
    $(".noJs").hide();

    var lastNumber = 0;
    var verification = 0;
    var drawingInProgess = false;

    function init() {
        $.getJSON("json.php?action=gamestatus", function (data) {
            if (data.status) {
                switch (data.gamestatus) {
                    case "notStarted":
                        $("#modal").html('<h2><?php __('drawing.notStarted', 'Not started'); ?></h2><div>' +
                            '<label><?php __('drawing.presenter', 'Presenter'); ?>: <input type="text" id="presenter">' +
                            '</label><br /><label><?php __('drawing.producer', 'Producer'); ?>: ' +
                            '<input type="text" id="producer"></label><p>' +
                            '<a href="#" class="button" id="startGame">' +
                            '<?php __('drawing.startGame', 'Start game'); ?></a></p>').modal({clickClose: false});
                        autocompleteStartGame();
                        bindStartGame();
                        break;

                    case "noRound":
                        $("#modal").html('<h2><?php __('drawing.startRound', 'Start round'); ?>?</h2>' +
                            '<p><?php __('drawing.jackpotNumber', 'Jackpot number'); ?>: ' + data.jackpotNumber + ' - <?php __('drawing.jackpot', 'Jackpot'); ?>: ' +
                            data.jackpot + '</p><p><?php __('drawing.presenter', 'Presenter'); ?>: ' + data.presenter +
                            '<br /><?php __('drawing.producer', 'Producer'); ?>: ' + data.producer + '</p><p>' +
                            '<a href="#" class="button" id="startRound">' +
                            '<?php __('drawing.yes', 'Yes'); ?></a></p>').modal({clickClose: false});
                        bindStartRound();
                        break;

                    case "round":
                        newNumbers(data.numbers);

                        $("#jackpotNumber").text("<?php __('drawing.jackpotNumber', 'Jackpot number'); ?>: " + data.jackpotNumber);
                        $("#jackpot").text("<?php __('drawing.jackpot', 'Jackpot'); ?>: <?php __('drawing.currency', '$'); ?>" + data.jackpot + ",-");
                        $("#rows").text("<?php __('drawing.currentRows', 'Number of rows'); ?>: " + 
                            data.rows);
                        $("#studio").html("<?php __('drawing.presenter', 'Presenter'); ?>: " + data.presenter + 
                            "<br /><?php __('drawing.producer', 'Producer'); ?>: " + data.producer);

                        var name = (data.type == "P") ? "<?php __('drawing.pauseRound', 'Pause round'); ?>" : "<?php __('drawing.regularRound', 'Round'); ?> " 
                            + data.name;
                        $("#name").text(name);
                        break;
                }
            }
        });
    }

    init();
    $("#left").css("height", $(document).height() - $("#drawn").outerHeight(true));


    function drawNumber(e) {
        e.preventDefault();

        if (drawingInProgess) { return; }
        drawingInProgess = true;
        $.getJSON("json.php?action=draw", function (data) {
            if (data.status) {
                newNumbers(data.numbers);
                drawingInProgess = false;
            } else {
                drawingInProgess = false;
            }
        });
    }

    $("#draw").click(function (e) {
        drawNumber(e);
    });

    $("#newRow").click(function (e) {
        e.preventDefault();

        $.getJSON("json.php?action=newRow", function (data) {
            if (data.status) {
                $("#rows").text("<?php __('drawing.currentRows', 'Number of rows'); ?>: " + 
                        data.rows);
            }
        });
    });

    $("#newRound").click(function (e) {
        e.preventDefault();

        $("#modal").html('<h2>Sikker på du vil starte ny omgang?</h2>' + 
            '<a href="#" class="button" id="yesRound" style="margin-right:0.5em;"><?php __('drawing.yes', 'Yes'); ?></a>' +
            '<a href="#" class="button" id="noRound"><?php __('drawing.no', 'No'); ?></a>').modal({clickClose: false});
        bindNewRound();
    });

    $(document).keyup(function (e) {
        var hasFocus = $("#verificationCode").is(":focus");
        if (e.keyCode == 13 && !hasFocus) {
            drawNumber(e);
        }
    });

    function verifyTicket(e) {
        e.preventDefault();

        verification = $("#verificationCode").val();
        $("#verificationCode").val(""); // reset
        $.getJSON("json.php?action=verify&verification=" + verification, function (data) {
            if (data.status) {
                var win = "<p><?php __('drawing.hasBingo', 'Player has bingo'); ?>. " + 
                    "<?php __('drawing.wonOn', 'Won on'); ?>: " + data.number + ".</p>";
                var noWin = "<p><?php __('drawing.noBingo', 'Has <strong>NOT</strong> bingo'); ?>.</p>";
                var prependHTML = (data.won) ? win : noWin;

                $("#modal").html(data.html).prepend(prependHTML)
                    .modal({clickClose: false})
                    .append('<p><?php __('drawing.code', 'Verification code'); ?>: ' + verification + '</p>')
                    .append('<a href="#" rel="modal:close" class="button">OK</a>');

                if(data.won) {
                    $("#modal").append('<a href="#" id="registerWinner" class="button"><?php __('drawing.registerWinner', 'Register winner'); ?></a>');
                    bindWinnerRegistration();
                }
            }
        });
    }

    $("#verify").click(function (e) {
        verifyTicket(e);    
    });

    $("#verificationCode").keyup(function (e) {
        if (e.keyCode == 13) {
            verifyTicket(e);
        }
    });

    function bindWinnerRegistration() {
        $("#registerWinner").click(function (e) {
            e.preventDefault();

            $.getJSON('json.php?action=getWinners', function (data) {
                if (data.status) {
                    var winners = data.winners;
                    $("#modal").html('<a href="#close-modal" rel="modal:close" class="close-modal ">Close</a><h2><?php __('drawing.winnerRegistration', 'Winner registration'); ?></h2>');
                    for (var i = 0; i < winners.length; i++) {
                        $("#modal").append('<div><p><?php __('drawing.winner', 'Winner'); ?>: ' +
                        winners[i].name + ', ' + winners[i].place + '<br /><label><?php __('drawing.price', 'Price'); ?>:' + 
                        '<input type="text" class="winner" id="winner-' + winners[i].id + 
                        '" value="' + winners[i].price + '" /></label></div>');
                    }

                    $("#modal").append('<div><br /><label><?php __('drawing.name', 'Name'); ?>: <input type="text" id="winnerName" /></label><br />' + 
                        '<label><?php __('drawing.place', 'Place'); ?>: <input type="text" id="winnerPlace" /></label><br />' +
                        '<label><?php __('drawing.price', 'Winner'); ?>: <input type="text" id="winnerPrice" /></label></div>')

                    autocompleteWinners();

                    $("#modal").append('<div><a href="#" id="saveWinner" class="button"><?php __('drawing.save', 'Save'); ?></a><a href="#" id="cancelWinner" class="button"><?php __('drawing.cancel', 'Cancel'); ?></a></div>');
                    bindWinnerSaving();
                    $.modal.resize();
                } else {
                    $("#modal").html('<a href="#close-modal" rel="modal:close" class="close-modal ">Close</a><h2><?php __('drawing.errorSavingWInners', 'An error occured! Please start manual registration'); ?></h2>');
                }
            });
        });
    }

    function autocompleteWinners() {
        $("#winnerName").autocomplete({
            source: function (req, res) {
                var term = encodeURIComponent(req.term);
                $.getJSON("json.php?action=getName&term=" + term, function (data) {
                    res(data);
                });
            },
            select: function (event, ui) {
                if (ui.item) {
                    $('#winnerName').val(ui.item.value);
                }

                getPlace(ui.item.value);
            }
        });

        $("#winnerPlace").autocomplete({
            source: function (req, res) {
                var term = encodeURIComponent(req.term);
                $.getJSON("json.php?action=getPlace&term=" + term, function (data) {
                    res(data);
                });
            }
        });
    }

    function autocompleteStartGame() {
        $("#presenter").autocomplete({
            source: function (req, res) {
                var term = encodeURIComponent(req.term);
                $.getJSON("json.php?action=getEmployee&term=" + term, function (data) {
                    res(data);
                });
            }
        });

        $("#producer").autocomplete({
            source: function (req, res) {
                var term = encodeURIComponent(req.term);
                $.getJSON("json.php?action=getEmployee&term=" + term, function (data) {
                    res(data);
                });
            }
        });
    }

    function getPlace(name) {
        $.getJSON("json.php?action=getPlace&name=" + encodeURIComponent(name), function (data) {
            if (data.status) {
                $("#winnerPlace").val(data.place);
            }
        });
    }

    function bindWinnerSaving() {
        $("#saveWinner").click(function (e) {
            e.preventDefault();

            var winners = [];
            $(".winner").each(function () {
                var id = $(this).attr("id").split("-")[1];
                var price = $(this).val();
                winners.push({
                    id: id,
                    price: price
                });
            });

            var winner = {};
            winner.name = $("#winnerName").val();
            winner.price = $("#winnerPrice").val();
            winner.place = $("#winnerPlace").val();
            winner.verification = verification;

            $.post("json.php?action=saveWinners", {
                winners: winners,
                winner: winner
            }, function (data) {
                if (data.status) {
                    $.modal.close();
                } else {
                    $("#modal").append("<h3><?php __('drawing.errorSavingWInners', 'An error occured! Please start manual registration'); ?></h3>");
                }
            }, "json");
        });

        $("#cancelWinner").click(function (e) {
            e.preventDefault();

            $.modal.close();
        });
    }

    function countDrawn() {
        var count = 0;
        $("#drawnTable td").each(function () {
            if ($(this).text() != "") {
                count++;
            }
        });
        return count;
    }

    function newNumbers(numbers) {
        $("#count").text(numbers.length);

        $(".number").text("");
        $("#drawn").text("");
        for (var i = 0; i < numbers.length; i++) {
            $("#number-" + numbers[i]).text(numbers[i]);
            lastNumber = numbers[i];

            var separator = ($("#drawn").text() == "") ? "" : ", ";
            $("#drawn").prepend(numbers[i] + separator);
        }

        $("#numberIs").text(lastNumber);
    }

    function bindStartGame() {
        $("#startGame").click(function (e) {
            e.preventDefault();

            var producer = encodeURIComponent($("#producer").val());
            var presenter = encodeURIComponent($("#presenter").val());

            $.getJSON("json.php?action=startGame&producer=" + producer +
                "&presenter=" + presenter, function (data) {
                if (data.status) {
                    $.modal.close();
                    init();
                }
            });
        });
    }

    function bindStartRound() {
        $("#startRound").click(function (e) {
            e.preventDefault();

            $.getJSON("json.php?action=newRound" +
                "&rows=1&type=R&name=1", function (data) {
                if (data.status) {
                    $.modal.close();
                    init();
                }
            });
        });
    }

    function bindNewRound() {
        $("#yesRound").click(function (e) {
            e.preventDefault();

            $.getJSON("json.php?action=gamestatus", function (data) {
                if (data.status) {
                    if (parseInt(data.name) == 3) {
                        var type = "P";
                        var name = 0;
                    } else {
                        var type = "R";
                        var name = parseInt(data.name) + 1;
                    }
                    $.getJSON("json.php?action=newRound" +
                        "&type=" + type + "&winners=1&name=" + name,
                        function (data) {
                            if (data.status) {
                                listWinners(data.winners);
                            }
                        });
                }
            })
        });

        $("#noRound").click(function (e) {
            e.preventDefault();
            $.modal.close();
        });
    }

    function listWinners(winners) {
        $("#modal").html('<h2><?php __('drawing.winners', 'Winners from previous round'); ?></h2>' +
            '<ul id="winners"></ul>' +
            '<a href="#" rel="modal:close" class="button">OK</a>');

        $("#modal").on($.modal.CLOSE, function () {
            location.reload();
        });

        for (var i = 0; i < winners.length; i++) {
            $("#winners").append('<li>' + winners[i].name + ", " + 
                winners[i].place + 
                ": <?php __('drawing.currency', '$'); ?>" + winners[i].price + ",-</li>");
        }

        $.modal.resize();
    }
});