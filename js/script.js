$(document).ready(function () {
	$(".noJs").hide();

	var forrigeTall = 0;

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

		var kontrollnr = $("#kontrollnr").val();
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
					.append('<a href="#" rel="modal:close" class="button ok">OK</a>');
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