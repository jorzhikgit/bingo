$(document).ready(function () {
	$(".noJs").hide();

	var lastNumber = 0;
	var verification = 0;

	function init() {
		$.getJSON("json.php?action=gamestatus", function (data) {
			if (data.status) {
				switch (data.gamestatus) {
					case "notStarted":
						$("#modal").html('<h2>Ikke startet</h2><div>' +
							'<label>Avvikler: <input type="text" id="presenter">' +
							'</label><br /><label>Produsent: ' +
							'<input type="text" id="producer"></label><p>' +
							'<a href="#" class="button" id="startGame">' +
							'Start spill!</a></p>').modal({clickClose: false});
						autocompleteStartGame();
						bindStartGame();
						break;

					case "noRound":
						$("#modal").html('<h2>Starte omgang?</h2>' +
							'<p>Lykketall: ' + data.jackpotNumber + ' - Lykkepott: ' +
							data.jackpot + '</p><p>' +
							'<a href="#" class="button" id="startRound">' +
							'Ja!</a></p>').modal({clickClose: false});
						bindStartRound();
						break;

					case "round":
						for (var i = 0; i < data.numbers.length; i++) {
							var number = data.numebrs[i];
							if (number == "") { break; }
							lastNumber = number;
							newNumber(number);
						}

						$("#jackpotNumber").text("Lykketall: " + data.jackpotNumber);
						$("#jackpot").text("Lykkepott: kr. " + data.jackpot + ",-");
						$("#rows").text("Antall rader: " + 
							data.rows);
						break;
				}
			}
		});
	}

	init();
	$("#left").css("height", $(document).height() - $("#drawn").outerHeight(true));


	function drawNumber(e) {
		e.preventDefault();

		$.getJSON("json.php?action=draw&last=" + forrigeTall, function (data) {
			if (data.status) {
				for (var i = 0; i < data.tallene.length; i++) {
					var tall = data.tallene[i];
					forrigeTall = tall;
					nyttTall(tall);
				}
			}
		});
	}

	$("#draw").click(function (e) {
		drawNumber(e);
	});

	$("#newRound").click(function (e) {
		e.preventDefault();

		$("#modal").html('<h2>Sikker på du vil starte ny omgang?</h2>' + 
			'<a href="#" class="button" id="yesRound" style="margin-right:0.5em;">Ja</a>' +
			'<a href="#" class="button" id="noRound">Nei</a>').modal({clickClose: false});
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
				var win = "<p>Spiller har bingo. " + 
					"Vinnertall: " + data.number + ".</p>";
				var noWin = "<p>Har <strong>IKKE</strong> bingo.</p>";
				var prependHTML = (data.won) ? won : noWin;

				$("#modal").html(data.html).prepend(prependHTML)
					.modal({clickClose: false})
					.append('<p>Kontrollnummer: ' + verification + '</p>')
					.append('<a href="#" rel="modal:close" class="button">OK</a>')

				if(data.won) {
					$("#modal").append('<a href="#" id="registerWinner" class="button">Registrér vinner</a>');
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
					$("#modal").html('<a href="#close-modal" rel="modal:close" class="close-modal ">Close</a><h2>Vinnerregistrering</h2>');
					for (var i = 0; i < winners.length; i++) {
						$("#modal").append('<div><p>Vinner: ' +
						winners[i].navn + ', ' + winners[i].sted + '<br /><label>Beløp:' + 
						'<input type="text" class="winner" id="winner-' + winners[i].vinnerid + 
						'" value="' + winners[i].utbetaling + '" /></label></div>');
					}

					$("#modal").append('<div><br /><label>Navn: <input type="text" id="winnerName" /></label><br />' + 
						'<label>Sted: <input type="text" id="winnerPlace" /></label><br />' +
						'<label>Beløp: <input type="text" id="winnerPrice" /></label></div>')

					autocompleteWinners();

					$("#modal").append('<div><a href="#" id="saveWinner" class="button">Lagre</a><a href="#" id="cancelWinner" class="button">Avbryt</a></div>');
					bindWinnerSaving();
					$.modal.resize();
				} else {
					$("#modal").html('<a href="#close-modal" rel="modal:close" class="close-modal ">Close</a><h2>En feil oppsto! Manuell registrering</h2>');
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
			$(".winners").each(function () {
				var winnnerId = $(this).attr("id").split("-")[1];
				var price = $(this).val();
				winners.push({
					winnerId: winnnerId,
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
					$("#modal").append("<h3>Feil: Begynn manuell registrering</h3>");
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

	function newNumber(number) {
		$("#number-" + number).text(number);
		$("#numberIs").text(number);

		var separator = ($("#drawn").text() == "") ? "" : ", ";
		$("#drawn").prepend(number + separator);

		$("#count").text(countDrawn());
	}

	function bindStartGame() {
		$("#startGame").click(function (e) {
			e.preventDefault();

			var producer = encodeURIComponent($("#producer").val());
			var presenter = encodeURIComponent($("#presenter").val());

			console.log("json.php?action=startGame&producer=" + producer +
				"&presenter=" + presenter);

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

			$.getJSON("json.php?action=startRound" +
				"&rows=1&type=V&name=1", function (data) {
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
					if (data.name == 3) {
						var type = "P";
						var navn = 0;
					} else {
						var type = "R";
						var navn = data.name + 1;
					}
					$.getJSON("json.php?action=newRound" +
						"&rows=" + (parseInt(data.antallRader) + 1) +
						"&type=" + type + "&winners=1&name=" + navn,
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
		$("#modal").html('<h2>Vinnere fra forrige omgang</h2>' +
			'<ul id="winners"></ul>' +
			'<a href="#" rel="modal:close" class="button">OK</a>');

		$("#modal").on($.modal.CLOSE, function () {
			location.reload();
		});

		for (var i = 0; i < winners.length; i++) {
			$("#winners").append('<li>' + winners[i].navn + ", " + winners[i].sted + 
				": Kroner " + winners[i].utbetaling + ",-</li>");
		}

		$.modal.resize();
	}
});