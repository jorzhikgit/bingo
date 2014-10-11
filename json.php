<?php

require_once "database.php";
require_once "Omgang.php";
require_once "Blokk.php";

$lykketall = 88; // FIXME: hent fra db
$lykkepott = 7000;
$vinnere = [];

function send($array) {
	header('Content-Type: application/json');
	echo json_encode($array);
	exit();
}

if ($_GET['valg'] == "trekk") {
	if (!is_numeric($_GET['forrige'])) {
		send(["status" => false, "error" => "Du må angi forrige tall."]);
	}

	$omgang = new Omgang(1, "V", 88, 7000); // feilaktig med vilje
	$omgangid = $omgang->hentFraDb($db);

	$tidligereTall = $omgang->hentTidligereTall();
	
	if (!empty($tidligereTall)) {
		$sisteTallPosisjon = array_search($_GET['forrige'], $tidligereTall);
		$tallene = array_slice($tidligereTall, $sisteTallPosisjon);
	}

	if ($tallene[0] == $_GET['forrige']) {
		array_shift($tallene);
	}

	$trekt = $omgang->trekk();
	if (is_null($trekt) && !empty($tallene)) {
		send(["status" => true, "tallene" => $tallene]);
	} elseif (is_null($trekt)) {
		send(["status" => false, "error" => "Ikke  mer å trekke."]);
	}

	$tallene[] = $trekt;

	$dbTall = implode(';', $omgang->hentTallene());
	$dbTidligereTall = (empty($omgang->hentTidligereTall())) ? null : implode(";", $omgang->hentTidligereTall());

	$stmt = $db->prepare("UPDATE omganger SET tall = :tall, tidligereTall = :tidligereTall WHERE omgangid = :omgangid");
	$stmt->bindParam(":tall", $dbTall, PDO::PARAM_STR);
	$stmt->bindParam(":tidligereTall", $dbTidligereTall, PDO::PARAM_STR);
	$stmt->bindParam(":omgangid", $omgangid, PDO::PARAM_INT);
	$stmt->execute();

	send(["status" => true, "tallene" => $tallene]);
} elseif ($_GET['valg'] == "kontroll") {
	$kontrollnr = $_GET['kontrollnr'];
	if ($kontrollnr <= 10000 && $kontrollnr >= 99999) {
		send(["status" => false, "error" => "Ugyldig kontrollnummer."]);
	}

	$omgang = new Omgang(1, "V", 88, 7000); // feilaktig med vilje
	$omgangid = $omgang->hentFraDb($db);

	$blokk = new Blokk($kontrollnr);

	$stmt = $db->prepare("SELECT blokker.bong FROM blokker WHERE kontrollnr = :kontrollnr");
	$stmt->bindParam(":kontrollnr", $kontrollnr, PDO::PARAM_INT);
	$stmt->execute();
	$tekst = $stmt->fetchColumn(0);

	$blokk->lagBlokkFraTekst($tekst);
	$validert = $omgang->validerBlokk($blokk);
	$html = $omgang->lagBlokkHTML($blokk, $validert['vinnerTallene']);

	if (!$validert['harVunnet']) {
		send(["status" => true, "vant" => false, "html" => $html, "vinnertall" => 0]);
	} else {
		send(["status" => true, "vant" => true, "html" => $html, "vinnertall" =>
			$validert['vinnerTall']]);
	}
}


