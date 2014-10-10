<?php
// <html>
// 	<head>
// 		<title>Bingo</title>
// 		<meta charset="utf-8" />
// 		<style>
// 			table {
// 				border-collapse: collapse;
// 			}

// 			td {
// 				width: 2em;
// 				border: 1px solid black;
// 			}

// 			.trekt {
// 				background-color: green;
// 				color: white;
// 			}
// 		</style>
// 	</head>
// 	<body>
?>
<?php
header('Content-Type: text/html; charset=utf-8');
require_once "Blokk.php";
require_once "Omgang.php";
require_once "Hefte.php";

// $blokk = new Blokk(1);
// $blokk->lagBlokk();

// $omgang = new Omgang(1, "V", 37, 7000);
// foreach(range(0, 60) as $i) {
// 	$omgang->trekk();
// }

// $validert = $omgang->validerBlokk($blokk);

// if (!$validert['harVunnet']) {
// 	echo '<p>Vant ikke</p>';
// } else {
// 	echo $omgang->lagBlokkHTML($blokk, $validert['vinnerTallene']);
// 	echo '<p>Vinnertall: ' . $validert['vinnerTall'] . '</p>';
// }

// $blokker = [];
// $i = 0;
// foreach(range(0, 4) as $omgang) {
// 	$blokker[$omgang] = [];
// 	foreach(range(0, 5) as $blokk) {
// 		$kontrollnr = 10000 + $i;
// 		$blokker[$omgang][$blokk] = new Blokk($kontrollnr);
// 		$blokker[$omgang][$blokk]->lagBlokk();
// 		$i++;
// 	}
// }
// unset($i);

// $hefte = new Hefte($blokker, "V", 12345);

// foreach(range(0, 5) as $blokk) {
// 	$kontrollnr = 10000 + $i;
// 	$blokker[$blokk] = new Blokk($kontrollnr);
// 	$blokker[$blokk]->lagBlokk();
// 	$i++;
// }

// $hefte = new Hefte([$blokker], "P", 12346);
// echo $hefte->lagLatex();

// $blokk = new Blokk(1);
// $blokk->lagBlokk();

// $blokk = new Blokk(99999); // faktisk nummer fra db
// $blokk->lagBlokkFraTekst(";.;;14;;.;;34;;40;;54;;.;;.;;80;-;.;;17;;23;;35;;.;;.;;61;;70;;82;-;.;;18;;.;;.;;48;;.;;.;");
// print_r($blokk->hentBlokk());

$blokk = new Blokk(1);
$blokk->lagBlokk();
print_r($blokk->hentBlokk());

