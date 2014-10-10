<?php
require_once "Blokk.php";

class Omgang {
	public function __construct($navn, $type, $lykketall, $lykkepott) {
		if ($navn >= 1 && $navn <= 5) {
			$this->navn = $navn;
		} elseif ($type == "P") {
			$this->navn = "Pausespill";
		} else {
			throw new Exception("Ugyldig navn på omgang.");
		}

		if($type == "P" || $type == "V") {
			$this->type = $type;
		} else {
			throw new Exception("Ugyldig omgangtype.");
		}

		if ($lykketall >= 1 && $lykketall <= 90) {
			$this->lykketall = $lykketall;
		} else {
			throw new Exception("Lykketallet må være mellom 1 og 90.");
		}

		if ($lykkepott >= 1000 && $lykkepott <= 20000) {
			$this->lykkepott = $lykkepott;
		} else {
			throw new Exception("Lykkepotten må være mellom 1000 og 20 000 kr.");
		}

		$this->tall = range(1, 90);
		shuffle($this->tall);

		$this->tidligereTall = [];
		$this->vinnere = [];
		$this->rader = 1;
	}

	public function trekk() {
		$tall = array_pop($this->tall);
		$this->tidligereTall[] = $tall;

		return $tall;
	}

	public function validerBlokk(Blokk $blokk) {
		$tall = [];
		$rader = [0, 0, 0];

		foreach ($this->tidligereTall as $trekt) {
			$harTall = $blokk->harTall($trekt);

			if(!is_null($harTall)) {
				$tall[] = $harTall;
				$rader[$harTall["rad"]]++;
				$vinnerTall = $trekt;
			}
		}

		$harVunnet = false;
		foreach ($rader as $rad) {
			if ($rad == 5) {
				$harVunnet = true;
			}
		}

		if ($harVunnet) {
			return ["harVunnet" => true, "vinnerTallene" => $tall, 
				"vinnerTall" => $vinnerTall];
		}

		return ["harVunnet" => false, "vinnerTallene" => $tall, "vinnerTall" => null];

	}

	public function nyRunde() {
		$this->rader++;
	}

	public function lagBlokkHTML(Blokk $blokk, array $vinnerTallene) {
		$kombinert = [];
		$blokk = $blokk->hentBlokk();
		for ($rad= 0; $rad < count($blokk); $rad++) {
			$kombinert[$rad] = [];
			for ($tall = 0; $tall < count($blokk[$rad]); $tall++) {
				if(is_null($blokk[$rad][$tall])) {
					$kombinert[$rad][$tall] = [" ", true];
				} else {
					$kombinert[$rad][$tall] = [$blokk[$rad][$tall], false];
				}

			}
		}


		foreach ($vinnerTallene as $vinnerTall) {
			$kombinert[$vinnerTall['rad']][$vinnerTall['posisjon']][1] = true;
		}

		$html = "<table>\n";

		foreach ($kombinert as $rad) {
			$html .= "<tr>\n";
			foreach ($rad as $posisjon) {
				if($posisjon[1]) {
					$html .= sprintf('<td class="%s">%s</td>%s', "trekt", 
						$posisjon[0], "\n");
				} else {
					$html .= sprintf("<td>%s</td>\n", $posisjon[0]);
				}
			}

			$html .= "</tr>\n";
		}

		$html .= "</table>\n";
		return $html;
	}
}