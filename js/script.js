$(document).ready(function () {
	$(".noJs").hide();

	var forrigeTall = 0;
	var kontrollnr = 0;

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

				$("#sjekk").html(data.html).prepend(prependHTML)
					.modal({clickClose: false})
					.append('<p>Kontrollnummer: ' + kontrollnr + '</p>')
					.append('<a href="#" rel="modal:close" class="button">OK</a>')

				if(data.vant) {
					$("#sjekk").append('<a href="#" id="registrerVinner" class="button">Registrér vinner</a>');
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
					$("#sjekk").html('<a href="#close-modal" rel="modal:close" class="close-modal ">Close</a><h2>Vinnerregistrering</h2>');
					for (var i = 0; i < vinnere.length; i++) {
						$("#sjekk").append('<div><p>Vinner: ' +
						vinnere[i].navn + ', ' + vinnere[i].sted + '<br /><label>Beløp:' + 
						'<input type="text" class="vinner" id="vinner-' + vinnere[i].vinnerid + 
						'" value="' + vinnere[i].utbetaling + '" /></label></div>');
					}

					$("#sjekk").append('<div><br /><label>Navn: <input type="text" id="vinnerNavn" /></label><br />' + 
						'<label>Sted: <input type="text" id="vinnerSted" /></label><br />' +
						'<label>Beløp: <input type="text" id="vinnerUtbetaling" /></label></div>')

					autofullfoer();

					$("#sjekk").append('<div><a href="#" id="lagreVinner" class="button">Lagre</a><a href="#" id="avbrytVinner" class="button">Avbryt</a></div>');
					bindVinnerlagring();
					$.modal.resize();
				} else {
					$("#sjekk").html('<a href="#close-modal" rel="modal:close" class="close-modal ">Close</a><h2>En feil oppsto! Manuell registrering</h2>');
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
					$("#sjekk").append("<h3>Feil: Begynn manuell registrering</h3>");
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
});