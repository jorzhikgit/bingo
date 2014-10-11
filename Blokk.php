<?php

class Blokk {
	public function __construct($kontrollnr) {
		$this->kontrollnr = $kontrollnr; // TODO: Valider
		$this->blokk = [];
	}

	public function lagBlokk() {
		// TODO: Rengjør! Stygt som faen inni denne metoden...
		// Krav: 1) Tall skal ikke gjentas. 2) Maks tre tall per tier-gruppe.
		// 3) Tier-gruppene skal sorteres i hver sin kolonne. 4) 15 tall per blokk,
		// jevnt fordelt på radene.

		// Lages på nytt....

		$tallbinge = [];
		$tiere = [0, 0, 0, 0, 0, 0, 0, 0, 0];
		$i = 0;
		while ($i < 15 ) {
			// lager alle 15 tallene for ei blokk
			$tall = mt_rand(1, 90);
			
			$tier = ($tall == 90) ? 8 : floor($tall / 10);
			if ($tiere[$tier] == 3) {
				// maks tre tall per kolonne
				continue;
			}

			if(!in_array($tall, $tallbinge)) {
				// sjekker at tallet ikke allerede finnes i blokka
				$tallbinge[] = $tall;
				$i++;
				$tiere[$tier]++;
			}
		}

		sort($tallbinge);

		foreach ($tiere as $tier => $antall) {
			if ($antall == 3) {
				$this->blokk[0][$tier] = array_shift($tallbinge);
				$this->blokk[1][$tier] = array_shift($tallbinge);
				$this->blokk[2][$tier] = array_shift($tallbinge);
			} elseif ($antall == 2) {
				$tallene = [true, true, false];
				shuffle($tallene);

				foreach ($tallene as $rad => $tall) {
					if ($tall) {
						$this->blokk[$rad][$tier] = array_shift($tallbinge);
					} else {
						$this->blokk[$rad][$tier] = null;
					}
				}
			} elseif ($antall == 1) {
				$tallene = [true, false, false];
				shuffle($tallene);

				foreach ($tallene as $rad => $tall) {
					if ($tall) {
						$this->blokk[$rad][$tier] = array_shift($tallbinge);
					} else {
						$this->blokk[$rad][$tier] = null;
					}
				}
			} elseif ($antall == 0) {
				$this->blokk[0][$tier] = null;
				$this->blokk[1][$tier] = null;
				$this->blokk[2][$tier] = null;
			}
		}

	}

	public function harTall($tall) {
		for ($rad = 0; $rad < 3; $rad++) {
			for ($posisjon = 0; $posisjon < 9; $posisjon++) {
				if ($this->blokk[$rad][$posisjon] == $tall) {
					return ["rad" => $rad, "posisjon" => $posisjon];
				}
			}
		}
		return null;
	}

	public function hentBlokk() {
		return $this->blokk;
	}

	public function hentTekstBlokk() {
	  $result = "";
	  $rows = [];
	  for ($i = 0; $i < count($this->blokk); $i++) {
	    if ($i % 3 == 0) {
	      if (count($rows) > 0) {
		$result .= implode("-", $rows);
	      }
	      $rows = [];
	    }
	    array_push($rows, implode(';', $this->blokk[$i]));
	  }
	  $result .= implode("-", $rows);

	  return $result;
	}

	public function lagBlokkFraTekst($tekst) {
		$blokk = [];
		$count = 0;

		foreach (explode('-', $tekst) as $rad) {
		  $blokk[$count] = [];
		  foreach (explode(';', $rad) as $kolonne) {
		    array_push($blokk[$count], $kolonne);
		  }
		  $count += 1;
		}

		$this->blokk = $blokk;
	}

	public function hentKontrollnr() {
		return $this->kontrollnr;
	}
}