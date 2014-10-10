<?php

class Side {
	public function __construct($kontrollnr) {
		if ($kontrollnr >= 10000 && $kontrollnr <= 99999) {
			$this->kontrollnr = $kontrollnr;
		} else {
			throw new Exception("Ugyldig kontrollnummer for side");
		}

		$this->blokker = [];
	}

	public function lagSide() {
		$tallbinge = range(1, 90);
		shuffle($tallbinge);

		$blokker = [];
		foreach (range(0, 5) as $blokknr) {
			$blokker[] = [];
			
			foreach (range(0, 2) as $radnr) {
				$rad = [null, null, null, null, null, null, null, null, null];
				foreach (range(0, 4) as $tallnr) {
					$tallValgt = false;
					while (!$tallValgt) {
						$tall = array_shift($tallbinge);
						$tier = floor($tall / 10);

						if (is_null($rad[$tier])) {
							$rad[$tier] = $tall;
							$tallValgt = true;
						} else {
							$tallbinge[] = $tall;
						}
					}
				}

				$blokker[$blokknr][$radnr] = $rad;
			}
		}
		$this->blokker = $blokker;
	}

	public function hentSide() {
		return $this->blokker;
	}
}