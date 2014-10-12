$(document).ready(function () {
	$(".noJs").hide();

	var forrigeTall = 0;
	var kontrollnr = 0;

	function init() {
		$.getJSON("json.php?valg=spillstatus", function (data) {
			if (data.status) {
				switch (data.spillstatus) {
					case "ikkeStartet":
						$("#modal").html('<h2>Ikke startet</h2><p>' +
							'<a href="#" class="button" id="startSpill">' +
							'Start spill!</a></p>').modal({clickClose: false});
						bindStartSpill();
						break;

					case "ingenOmgang":
						$("#modal").html('<h2>Starte omgang?</h2>' +
							'<p>Lykketall: ' + data.lykketall + ' - Lykkepott: ' +
							data.lykkepott + '</p><p>' +
							'<a href="#" class="button" id="startOmgang">' +
							'Ja!</a></p>').modal({clickClose: false});
						bindStartOmgang();
						break;

					case "omgang":
						for (var i = 0; i < data.tall.length; i++) {
							var tall = data.tall[i];
							if (tall == "") { break; }
							forrigeTall = tall;
							nyttTall(tall);
						}

						$("#lykketall").text("Lykketall: " + data.lykketall);
						$("#lykkepott").text("Lykkepott: " + data.lykkepott + ",- kr");
						$("#antallRader").text("Antall rader: " + 
							data.antallRader);
						break;
				}
			}
		});
	}

	init();
	$("#venstre").css("height", $(document).height() - $("#trekt").outerHeight(true));


	function trekkTall(e) {
		e.preventDefault();

		$.getJSON("json.php?valg=trekk&forrige=" + forrigeTall, function (data) {
			if (data.status) {
				for (var i = 0; i < data.tallene.length; i++) {
					var tall = data.tallene[i];
					forrigeTall = tall;
					nyttTall(tall);
				}
			}
		});
	}

	$("#trekk").click(function (e) {
		trekkTall(e);
	});

	$("#nyOmgang").click(function (e) {
		e.preventDefault();

		$("#modal").html('<h2>Sikker på du vil starte ny omgang?</h2>' + 
			'<a href="#" class="button" id="jaOmgang" style="margin-right:0.5em;">Ja</a>' +
			'<a href="#" class="button" id="neiOmgang">Nei</a>').modal({clickClose: false});
		bindNyOmgang();
	});

	$(document).keyup(function (e) {
		var hasFocus = $("#kontrollnr").is(":focus");
		if (e.keyCode == 13 && !hasFocus) {
			trekkTall(e);
		}
	});

	function kontrollerBlokk(e) {
		e.preventDefault();

		kontrollnr = $("#kontrollnr").val();
		$("#kontrollnr").val(""); // reset
		$.getJSON("json.php?valg=kontroll&kontrollnr=" + kontrollnr, function (data) {
			if (data.status) {
				var vant = "<p>Spiller har bingo. " + 
					"Vinnertall: " + data.vinnertall + ".</p>";
				var vantIkke = "<p>Har <strong>IKKE</strong> bingo.</p>";
				var prependHTML = (data.vant) ? vant : vantIkke;

				$("#modal").html(data.html).prepend(prependHTML)
					.modal({clickClose: false})
					.append('<p>Kontrollnummer: ' + kontrollnr + '</p>')
					.append('<a href="#" rel="modal:close" class="button">OK</a>')

				if(data.vant) {
					$("#modal").append('<a href="#" id="registrerVinner" class="button">Registrér vinner</a>');
					bindVinnerregistreing();
				}
			}
		});
	}

	$("#kontroller").click(function (e) {
		kontrollerBlokk(e);	
	});

	$("#kontrollnr").keyup(function (e) {
		if (e.keyCode == 13) {
			kontrollerBlokk(e);
		}
	});

	function bindVinnerregistreing() {
		$("#registrerVinner").click(function (e) {
			e.preventDefault();

			$.getJSON('json.php?valg=hentVinnere', function (data) {
				if (data.status) {
					var vinnere = data.vinnere;
					$("#modal").html('<a href="#close-modal" rel="modal:close" class="close-modal ">Close</a><h2>Vinnerregistrering</h2>');
					for (var i = 0; i < vinnere.length; i++) {
						$("#modal").append('<div><p>Vinner: ' +
						vinnere[i].navn + ', ' + vinnere[i].sted + '<br /><label>Beløp:' + 
						'<input type="text" class="vinner" id="vinner-' + vinnere[i].vinnerid + 
						'" value="' + vinnere[i].utbetaling + '" /></label></div>');
					}

					$("#modal").append('<div><br /><label>Navn: <input type="text" id="vinnerNavn" /></label><br />' + 
						'<label>Sted: <input type="text" id="vinnerSted" /></label><br />' +
						'<label>Beløp: <input type="text" id="vinnerUtbetaling" /></label></div>')

					autofullfoer();

					$("#modal").append('<div><a href="#" id="lagreVinner" class="button">Lagre</a><a href="#" id="avbrytVinner" class="button">Avbryt</a></div>');
					bindVinnerlagring();
					$.modal.resize();
				} else {
					$("#modal").html('<a href="#close-modal" rel="modal:close" class="close-modal ">Close</a><h2>En feil oppsto! Manuell registrering</h2>');
				}
			});
		});
	}

	function autofullfoer() {
		$("#vinnerNavn").autocomplete({
			source: function (req, res) {
				var term = encodeURIComponent(req.term);
				$.getJSON("json.php?valg=finnNavn&term=" + term, function (data) {
					res(data);
				});
			},
			select: function (event, ui) {
				if (ui.item) {
					$('#vinnerNavn').val(ui.item.value);
				}

				finnSted(ui.item.value);
			}
		});

		$("#vinnerSted").autocomplete({
			source: function (req, res) {
				var term = encodeURIComponent(req.term);
				$.getJSON("json.php?valg=finnSted&term=" + term, function (data) {
					res(data);
				});
			}
		});
	}

	function finnSted(navn) {
		$.getJSON("json.php?valg=finnSted&navn=" + encodeURIComponent(navn), function (data) {
			if (data.status) {
				$("#vinnerSted").val(data.sted);
			}
		});
	}

	function bindVinnerlagring() {
		$("#lagreVinner").click(function (e) {
			e.preventDefault();

			var vinnere = [];
			$(".vinner").each(function () {
				var vinnerid = $(this).attr("id").split("-")[1];
				var utbetaling = $(this).val();
				vinnere.push({
					vinnerid: vinnerid,
					utbetaling: utbetaling
				});
			});

			var vinner = {};
			vinner.navn = $("#vinnerNavn").val();
			vinner.utbetaling = $("#vinnerUtbetaling").val();
			vinner.sted = $("#vinnerSted").val();
			vinner.kontrollnr = kontrollnr;

			$.post("json.php?valg=lagreVinnere", {
				vinnere: vinnere,
				vinner: vinner
			}, function (data) {
				if (data.status) {
					$.modal.close();
				} else {
					$("#modal").append("<h3>Feil: Begynn manuell registrering</h3>");
				}
			}, "json");
		});

		$("#avbrytVinner").click(function (e) {
			e.preventDefault();

			$.modal.close();
		});
	}

	function antallTrekt() {
		var antall = 0;
		$("#trektTabell td").each(function () {
			if ($(this).text() != "") {
				antall++;
			}
		});
		return antall;
	}

	function nyttTall(tall) {
		$("#tall-" + tall).text(tall);
		$("#talletEr").text(tall);

		var separator = ($("#trekt").text() == "") ? "" : ", ";
		$("#trekt").prepend(tall + separator);

		$("#antall").text(antallTrekt());
	}

	function bindStartSpill() {
		$("#startSpill").click(function (e) {
			e.preventDefault();

			$.getJSON("json.php?valg=startSpill", function (data) {
				if (data.status) {
					$.modal.close();
					init();
				}
			});
		});
	}

	function bindStartOmgang() {
		$("#startOmgang").click(function (e) {
			e.preventDefault();

			$.getJSON("json.php?valg=startOmgang" +
				"&antallRader=1&type=V&navn=1", function (data) {
				if (data.status) {
					$.modal.close();
					init();
				}
			});
		});
	}

	function bindNyOmgang() {
		$("#jaOmgang").click(function (e) {
			e.preventDefault();

			$.getJSON("json.php?valg=spillstatus", function (data) {
				if (data.status) {
					if (data.navn == 3) {
						var type = "P";
						var navn = 0;
					} else {
						var type = "V";
						var navn = data.navn + 1;
					}
					$.getJSON("json.php?valg=startOmgang" +
						"&antallRader=" + (parseInt(data.antallRader) + 1) +
						"&type=" + type + "&vinnere=1&navn=" + navn,
						function (data) {
							if (data.status) {
								listVinnere(data.vinnere);
							}
						});
				}
			})
		});

		$("#neiOmgang").click(function (e) {
			e.preventDefault();
			$.modal.close();
		});
	}

	function listVinnere(vinnere) {
		$("#modal").html('<h2>Vinnere fra forrige omgang</h2>' +
			'<ul id="vinnere"></ul>' +
			'<a href="#" rel="modal:close" class="button">OK</a>');

		$("#modal").on($.modal.CLOSE, function () {
			location.reload();
		});

		for (var i = 0; i < vinnere.length; i++) {
			$("#vinnere").append('<li>' + vinnere[i].navn + ", " + vinnere[i].sted + 
				": Kroner " + vinnere[i].utbetaling + ",-</li>");
		}

		$.modal.resize();
	}
});