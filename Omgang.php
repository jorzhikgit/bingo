<?php
require_once "Blokk.php";

class Omgang {
	public function __construct($navn, $type, $lykketall, $lykkepott, $tall = [], $tidligereTall = [], $rader = 1, $vinnere = []) {
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

		if (empty($tall)) {
			$this->tall = range(1, 90);
			shuffle($this->tall);
		} else {
			$this->tall = $tall;
		}
		
		$this->tidligereTall = $tidligereTall;
		$this->vinnere = $vinnere;
		$this->rader = $rader;
	}

	public function trekk() {
		$tall = array_pop($this->tall);
		if (is_null($tall) || $tall === "") {
			return null;
		}

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

	public function hentTallene() {
		return $this->tall;
	}

	public function hentTidligereTall() {
		return $this->tidligereTall;
	}

	public function hentVinnere() {
		return $this->vinnere;
	}

	public function hentRader() {
		return $this->rader;
	}

	public function hentNavn() {
		return $this->navn;
	}

	public function hentLykkepott() {
		return $this->lykkepott;
	}

	public function hentLykketall() {
		return $this->lykketall;
	}

	public function hentFraDb($db) {
		$sql = "SELECT * FROM omganger ORDER BY omgangid DESC LIMIT 1";
		$data = $db->query($sql)->fetch(PDO::FETCH_ASSOC);

		if (empty($data['tidligereTall']) || is_null($data['tidligereTall']) || $data['tidligereTall'] === '') {
			$this->tidligereTall = [];
		} else {
			$this->tidligereTall = explode(';', $data['tidligereTall']);
		}

		if ((empty($data['tall']) || is_null($data['tall']) || $data['tall'] === '') && empty($this->tidligereTall)) {
			$this->tall = range(1, 90);
			shuffle($this->tall);
		} else {
			$this->tall = explode(';', $data['tall']);
		}

		$this->navn = $data['navn'];
		$this->type = $data['type'];
		$this->rader = $data['antallRader'];
		$this->lykketall = 88; // FIXME
		$this->lykkepott = 7000; // FIXME
		$this->vinnere = []; // FIXME

		return $data['omgangid'];
	}
}