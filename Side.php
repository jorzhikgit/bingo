<?php
require_once "Blokk.php";

class Side {
	public function __construct($kontrollnr) {
		if ($kontrollnr >= 10000 && $kontrollnr <= 99999) {
			$this->kontrollnr = $kontrollnr;
		} else {
			throw new Exception("Ugyldig kontrollnummer for side");
		}

		$this->blokker = [];
	}

	public function hentSide() {
		$resultat = [];
		foreach($this->blokker as $blokk) {
			$resultat[] = $blokk->hentBlokk();
		}
		return $resultat;
	}

	public function leggTilBlokk(Blokk $blokk) {
		$this->blokker[] = $blokk;
	}

	public function hentKontrollnumre() {
		$resultat = [];
		foreach($this->blokker as $blokk) {
			$resultat[] = $blokk->hentKontrollnr();
		}
		return $resultat;
	}
}