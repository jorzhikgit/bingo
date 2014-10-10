<?php
require_once("Side.php");

class SideTest extends PHPUnit_Framework_TestCase {
	public function testRader() {
		$a = new Side(10000);
		$a->lagSide();

		$s = $a->hentSide();

		foreach($s as $blokknr => $blokk) {
			foreach ($blokk as $radnr => $rad) {
				$antall = 0;
				foreach ($rad as $nummer) {
					if (!is_null($nummer)) {
						$antall++;
					}
				}
				$this->assertEquals(5, $antall);
			}
		}
	}
}