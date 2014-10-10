$(document).ready(function () {
	$(".noJs").hide();

	var forrigeTall = 0;
	var antallTrekt = 0;

	$("#trekk").click(function (e) {
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
	});

	$("#kontroller").click(function (e) {
		e.preventDefault();

		var kontrollnr = $("#kontrollnr").val();
		$.getJSON("json.php?valg=kontroll&kontrollnr=" + kontrollnr, function (data) {
			if (data.status) {
				if (data.vant) {
					$("#sjekk").html(data.html).prepend("<p>Vinnertall: " + 
					data.vinnertall + ".</p>").modal({clickClose: false});
				} else {
					$("#sjekk").html(data.html).prepend("<p>Har " + 
						"<strong>IKKE</strong> bingo.</p>")
						.modal({clickClose: false});
				}
			}
		});
	});

	function nyttTall(tall) {
		$("#tall-" + tall).text(tall);
		$("#talletEr").text(tall);

		var separator = ($("#trekt").text() == "") ? "" : ", ";
		$("#trekt").prepend(tall + separator);

		antallTrekt++;
		$("#antall").text(antallTrekt);
	}
});