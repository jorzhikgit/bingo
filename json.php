<?php

require_once "database.php";
require_once "Omgang.php";
require_once "Blokk.php";

$lykketall = 88; // FIXME: hent fra db
$lykkepott = 7000;
$vinnere = [];

function traverseEncode($array) {
	$result = [];
	foreach ($array as $key => $value) {
		if (gettype($value) == "array") {
			$result[$key] = traverseEncode($value);
		} else {
			$result[$key] = utf8_encode($value);
		}
	}
	return $result;
}

function traverseDecode($array) {
	$result = [];
	foreach ($array as $key => $value) {
		if (gettype($value) == "array") {
			$result[$key] = traverseDecode($value);
		} else {
			$result[$key] = utf8_decode($value);
		}
	}
	return $result;
}

function send($array) {
	$array = traverseEncode($array); // utf-8 fiks

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

	$stmt = $db->prepare("UPDATE omganger SET tall = :tall, tidligereTall = " .
		":tidligereTall WHERE omgangid = :omgangid");
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
} elseif ($_GET['valg'] == "hentVinnere") {
	$sql = "SELECT omganger.omgangid FROM omganger ORDER BY omgangid DESC LIMIT 1";
	$omgangid = $db->query($sql)->fetchColumn(0);

	$stmt = $db->prepare("SELECT vinnere.vinnerid, kunder.navn, steder.sted, vinnere.utbetaling FROM vinnere " . 
		"INNER JOIN kunder ON vinnere.kundeid = kunder.kundeid " .
		"INNER JOIN steder ON kunder.stedid = steder.stedid " .
		"WHERE vinnere.omgangid = :omgangid ORDER BY vinnere.vinnerid");

	$stmt->bindParam(":omgangid", $omgangid, PDO::PARAM_INT);
	$stmt->execute();

	send(["status" => true, "vinnere" => $stmt->fetchAll(PDO::FETCH_ASSOC)]);

} elseif ($_GET['valg'] == "finnNavn") {
	$term = '%' . utf8_decode($_GET['term']) . '%';

	$sql = "SELECT kunder.navn FROM kunder WHERE navn LIKE :term";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(":term", $term, PDO::PARAM_STR);
	$stmt->execute();

	$array = [];
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $data) {
		$array[] = utf8_encode($data['navn']);
	}

	header('Content-Type: application/json');
	echo json_encode($array);
	exit();
} elseif ($_GET['valg'] == "finnSted" && isset($_GET['term'])) {
	$term = '%' . utf8_decode($_GET['term']) . '%';

	$sql = "SELECT steder.sted FROM steder WHERE sted LIKE :term";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(":term", $term, PDO::PARAM_STR);
	$stmt->execute();

	$array = [];
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $data) {
		$array[] = utf8_encode($data['sted']);
	}

	header('Content-Type: application/json');
	echo json_encode($array);
	exit();
} elseif ($_GET['valg'] == "finnSted" && isset($_GET['navn'])) {
	$sql = "SELECT steder.sted FROM steder INNER JOIN kunder ON " .
		"kunder.stedid = steder.stedid WHERE kunder.navn = :navn";

	$stmt = $db->prepare($sql);
	$stmt->bindParam(":navn", utf8_decode($_GET['navn']), PDO::PARAM_STR);
	$stmt->execute();

	send(["status" => true, "sted" => $stmt->fetchColumn(0)]);
} elseif ($_GET['valg'] == "lagreVinnere") {
	$vinnere = $_POST['vinnere'];

	$stmt = $db->prepare("UPDATE vinnere SET utbetaling = :utbetaling, " .
		"igjenUtbetale = :igjenUtbetale WHERE vinnerid = :vinnerid");
	foreach($vinnere as $vinner) {
		$stmt->bindParam(":utbetaling", $vinner['utbetaling'], PDO::PARAM_INT);
		$stmt->bindParam(":igjenUtbetale", $vinner['utbetaling'], PDO::PARAM_INT);
		$stmt->bindParam(":vinnerid", $vinner['vinnerid'], PDO::PARAM_INT);
		$stmt->execute();
	}

	$vinner = traverseDecode($_POST['vinner']);

	$sql = "SELECT omganger.omgangid FROM omganger ORDER BY omgangid DESC LIMIT 1";
	$omgangid = $db->query($sql)->fetchColumn(0);

	$stmt = $db->prepare("SELECT kunder.kundeid FROM kunder INNER JOIN steder " .
		"ON kunder.stedid = steder.stedid WHERE kunder.navn = :navn AND " .
		"steder.sted = :sted");
	$stmt->bindParam(":navn", $vinner['navn'], PDO::PARAM_STR);
	$stmt->bindParam(":sted", $vinner['sted'], PDO::PARAM_STR);
	$stmt->execute();
	$kundeid = $stmt->fetchColumn(0);

	if ($kundeid === false) {
		// vi har ingen slik kunde - opprett
		
		$stmt = $db->prepare("SELECT steder.stedid FROM steder WHERE " .
			"sted = :sted");
		$stmt->bindParam(":sted", $vinner['sted'], PDO::PARAM_STR);
		$stmt->execute();
		$stedid = $stmt->fetchColumn(0);

		if ($stedid === false) {
			// stedet finnes ikke - opprett
			$stmt = $db->prepare("INSERT INTO steder (sted) VALUES (:sted)");
			$stmt->bindParam(":sted", $vinner['sted'], PDO::PARAM_STR);
			$stmt->execute();
			$stedid = $db->lastInsertId();
		}

		$stmt = $db->prepare("INSERT INTO kunder (navn, stedid) VALUES " .
			"(:navn, :stedid)");
		$stmt->bindParam(":navn", $vinner['navn'], PDO::PARAM_STR);
		$stmt->bindParam(":stedid", $stedid, PDO::PARAM_INT);
		$stmt->execute();
		$kundeid = $db->lastInsertId();
	}

	$stmt = $db->prepare("INSERT INTO vinnere (kundeid, kontrollnr, dato, omgangid, " .
		"igjenUtbetale, utbetaling, statusid) VALUES (:kundeid, :kontrollnr, NOW(), " .
		":omgangid, :igjenUtbetale, :utbetaling, 5)");
	$stmt->bindParam(":kundeid", $kundeid, PDO::PARAM_INT);
	$stmt->bindParam(":kontrollnr", $vinner['kontrollnr'], PDO::PARAM_INT);
	$stmt->bindParam(":omgangid", $omgangid, PDO::PARAM_INT);
	$stmt->bindParam(":igjenUtbetale", $vinner['utbetaling'], PDO::PARAM_INT);
	$stmt->bindParam(":utbetaling", $vinner['utbetaling'], PDO::PARAM_INT);
	$stmt->execute();

	send(["status" => true]);
}
