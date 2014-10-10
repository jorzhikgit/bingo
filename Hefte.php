<?php
require_once "Blokk.php";

class Hefte {
	public function __construct($blokker = [ [] ], $type = "V", $salgsnr) {
		$this->blokker = $blokker;
		if($type == "P" || $type == "V") {
			$this->type = $type;
		} else {
			throw new Exception("Ugyldig heftetype.");
		}

		if(!is_numeric($salgsnr)) {
			throw new Exception("Ugyldig salgsnummer på hefte.");
		}

		$this->salgsnr = $salgsnr;
	}

	public function leggTilBlokk(Blokk $blokk, $omgang) {
		$this->blokker[$omgang][] = $blokk;
	}

	public function lagLatex() {
		require_once "latexMal.php";
		$latex = sprintf($latex, $this->salgsnr);

		foreach ($this->blokker as $omgangnr => $omgang) {
			$navn = ($this->type == "P") ? "Pausespill" : "Spilleomgang " . ($omgangnr + 1);
			$latex .= sprintf('\section*{%s}%s', $navn, "\n");

			
			foreach ($omgang as $blokk) {
				$latex .= sprintf('  \begin{center} %s', "\n");
				$latex .= sprintf('    \addvbuffer[0mm 5mm]{\begin{tabular}{ | m{2.5mm} | m{2.5mm} | m{2.5mm} | m{2.5mm} | m{2.5mm} | m{2.5mm} | m{2.5mm} | m{2.5mm} | m{2.5mm} |} %s', "\n");
				$latex .= sprintf('      \hline %s', "\n");

				$kontrollnr = $blokk->hentKontrollnr();
				$blokk = $blokk->hentBlokk();
				$linjer = [];
				foreach($blokk as $radnr => $rad) {
					$linjer[$radnr] = "";
					foreach ($rad as $tall) {
						if (is_null($tall)) {
							$linjer[$radnr] .= "~ & ";
						} else {
							$linjer[$radnr] .= $tall . " & ";
						}
						
					}
				}

				foreach($linjer as $linje) {
					$linje = substr($linje, 0, strlen($linje) -3);

					$latex .= sprintf('%s \\\\ \hline %s', $linje, "\n");
				}

				$latex .= sprintf('     \multicolumn{9}{|l|}{Kontrollnummer: %d} \\\\%s', $kontrollnr, "\n");
				$latex .= sprintf('      \hline %s', "\n");
				$latex .= sprintf('    \end{tabular}} %s', "\n");
				$latex .= sprintf('  \end{center} %s', "\n");
				$latex .= sprintf('%s', "\n");
			}
		
			$latex .= sprintf('  \begin{textblock}{0.75}(0.25,0.9)%s', "\n");
			$latex .= sprintf('    \includegraphics[width=70mm]{logo.png}%s', "\n");
			$latex .= sprintf('  \end{textblock}%s', "\n");
			$latex .= sprintf('\newpage %s', "\n");

		}

		$latex = substr($latex, 0, strlen($latex) -10); // fjerne siste tomme side
		$latex .= '\end{document}';

		return $latex;
	}
}