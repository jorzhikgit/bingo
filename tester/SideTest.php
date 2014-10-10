<?php

class SideTest extends PHPUnit_Framework_TestCase {
	public function testRadsum() {
		// Tester at hver rad har fem tall

		//$a = new Side(10000);
		//$a->lagSide();

		//$s = $a->hentSide();

		$s = unserialize("a:6:{i:0;a:3:{i:0;a:9:{i:0;N;i:1;i:19;i:2;i:26;i:3;i:34;i:4;N;i:5;i:50;i:6;N;i:7;N;i:8;i:87;}i:1;a:9:{i:0;i:9;i:1;N;i:2;N;i:3;N;i:4;i:46;i:5;N;i:6;i:63;i:7;i:76;i:8;i:89;}i:2;a:9:{i:0;i:4;i:1;i:13;i:2;i:25;i:3;N;i:4;N;i:5;N;i:6;N;i:7;i:72;i:8;i:86;}}i:1;a:3:{i:0;a:9:{i:0;N;i:1;i:16;i:2;i:20;i:3;i:37;i:4;i:45;i:5;N;i:6;i:69;i:7;N;i:8;N;}i:1;a:9:{i:0;N;i:1;N;i:2;i:28;i:3;i:39;i:4;i:41;i:5;i:56;i:6;i:68;i:7;N;i:8;N;}i:2;a:9:{i:0;i:5;i:1;i:11;i:2;N;i:3;i:36;i:4;i:42;i:5;N;i:6;i:67;i:7;N;i:8;N;}}i:2;a:3:{i:0;a:9:{i:0;N;i:1;N;i:2;i:21;i:3;N;i:4;i:40;i:5;i:53;i:6;N;i:7;i:71;i:8;i:81;}i:1;a:9:{i:0;i:3;i:1;i:18;i:2;N;i:3;N;i:4;N;i:5;i:54;i:6;N;i:7;i:75;i:8;i:83;}i:2;a:10:{i:0;N;i:1;i:12;i:2;N;i:3;N;i:4;i:48;i:5;i:57;i:6;i:61;i:7;N;i:8;N;i:9;i:90;}}i:3;a:3:{i:0;a:9:{i:0;i:8;i:1;N;i:2;N;i:3;i:32;i:4;i:49;i:5;i:52;i:6;N;i:7;i:70;i:8;N;}i:1;a:9:{i:0;N;i:1;N;i:2;i:22;i:3;N;i:4;i:47;i:5;i:51;i:6;N;i:7;i:79;i:8;i:84;}i:2;a:9:{i:0;i:2;i:1;i:10;i:2;i:24;i:3;N;i:4;N;i:5;i:55;i:6;N;i:7;N;i:8;i:88;}}i:4;a:3:{i:0;a:9:{i:0;N;i:1;i:17;i:2;i:27;i:3;i:35;i:4;N;i:5;N;i:6;i:62;i:7;N;i:8;i:82;}i:1;a:9:{i:0;i:6;i:1;i:14;i:2;N;i:3;i:31;i:4;N;i:5;N;i:6;i:66;i:7;N;i:8;i:80;}i:2;a:9:{i:0;N;i:1;i:15;i:2;i:29;i:3;i:38;i:4;N;i:5;i:58;i:6;N;i:7;i:78;i:8;N;}}i:5;a:3:{i:0;a:9:{i:0;N;i:1;N;i:2;i:23;i:3;i:33;i:4;N;i:5;N;i:6;i:65;i:7;i:77;i:8;i:85;}i:1;a:9:{i:0;i:7;i:1;N;i:2;N;i:3;i:30;i:4;i:44;i:5;N;i:6;i:64;i:7;i:73;i:8;N;}i:2;a:9:{i:0;i:1;i:1;N;i:2;N;i:3;N;i:4;i:43;i:5;i:59;i:6;i:60;i:7;i:74;i:8;N;}}}");

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

	public function testKolonnesum() {
		// Tester at hver kolonne nedover arket har hver tier-gruppe

		//$a = new Side(10000);
		//$a->lagSide();

		//$s = $a->hentSide();

		$s = unserialize("a:6:{i:0;a:3:{i:0;a:9:{i:0;N;i:1;i:19;i:2;i:26;i:3;i:34;i:4;N;i:5;i:50;i:6;N;i:7;N;i:8;i:87;}i:1;a:9:{i:0;i:9;i:1;N;i:2;N;i:3;N;i:4;i:46;i:5;N;i:6;i:63;i:7;i:76;i:8;i:89;}i:2;a:9:{i:0;i:4;i:1;i:13;i:2;i:25;i:3;N;i:4;N;i:5;N;i:6;N;i:7;i:72;i:8;i:86;}}i:1;a:3:{i:0;a:9:{i:0;N;i:1;i:16;i:2;i:20;i:3;i:37;i:4;i:45;i:5;N;i:6;i:69;i:7;N;i:8;N;}i:1;a:9:{i:0;N;i:1;N;i:2;i:28;i:3;i:39;i:4;i:41;i:5;i:56;i:6;i:68;i:7;N;i:8;N;}i:2;a:9:{i:0;i:5;i:1;i:11;i:2;N;i:3;i:36;i:4;i:42;i:5;N;i:6;i:67;i:7;N;i:8;N;}}i:2;a:3:{i:0;a:9:{i:0;N;i:1;N;i:2;i:21;i:3;N;i:4;i:40;i:5;i:53;i:6;N;i:7;i:71;i:8;i:81;}i:1;a:9:{i:0;i:3;i:1;i:18;i:2;N;i:3;N;i:4;N;i:5;i:54;i:6;N;i:7;i:75;i:8;i:83;}i:2;a:10:{i:0;N;i:1;i:12;i:2;N;i:3;N;i:4;i:48;i:5;i:57;i:6;i:61;i:7;N;i:8;N;i:9;i:90;}}i:3;a:3:{i:0;a:9:{i:0;i:8;i:1;N;i:2;N;i:3;i:32;i:4;i:49;i:5;i:52;i:6;N;i:7;i:70;i:8;N;}i:1;a:9:{i:0;N;i:1;N;i:2;i:22;i:3;N;i:4;i:47;i:5;i:51;i:6;N;i:7;i:79;i:8;i:84;}i:2;a:9:{i:0;i:2;i:1;i:10;i:2;i:24;i:3;N;i:4;N;i:5;i:55;i:6;N;i:7;N;i:8;i:88;}}i:4;a:3:{i:0;a:9:{i:0;N;i:1;i:17;i:2;i:27;i:3;i:35;i:4;N;i:5;N;i:6;i:62;i:7;N;i:8;i:82;}i:1;a:9:{i:0;i:6;i:1;i:14;i:2;N;i:3;i:31;i:4;N;i:5;N;i:6;i:66;i:7;N;i:8;i:80;}i:2;a:9:{i:0;N;i:1;i:15;i:2;i:29;i:3;i:38;i:4;N;i:5;i:58;i:6;N;i:7;i:78;i:8;N;}}i:5;a:3:{i:0;a:9:{i:0;N;i:1;N;i:2;i:23;i:3;i:33;i:4;N;i:5;N;i:6;i:65;i:7;i:77;i:8;i:85;}i:1;a:9:{i:0;i:7;i:1;N;i:2;N;i:3;i:30;i:4;i:44;i:5;N;i:6;i:64;i:7;i:73;i:8;N;}i:2;a:9:{i:0;i:1;i:1;N;i:2;N;i:3;N;i:4;i:43;i:5;i:59;i:6;i:60;i:7;i:74;i:8;N;}}}");

		$tiere = [0, 0, 0, 0, 0, 0, 0, 0, 0];

		foreach($s as $blokknr => $blokk) {
			foreach ($blokk as $radnr => $rad) {
				foreach ($rad as $nummer) {
					if (!is_null($nummer)) {
						$tier = ($nummer == 90) ? 8 : floor($nummer / 10);
						$tiere[$tier]++;
					}
				}
			}
		}

		foreach ($tiere as $tier => $antall) {
			if ($tier == 8) {
				// 90 er en del av 80-erne
				$this->assertEquals(11, $antall);
			} elseif ($tier == 0) {
				// 0 ikke et tall
				$this->assertEquals(9, $antall);
			} else {
				$this->assertEquals(10, $antall);
			}
		}
	}

	public function testForUnikeTall() {
		// Tester at hver side ikke gjentar tall

		//$a->lagSide();

		//$s = $a->hentSide();

		$s = unserialize("a:6:{i:0;a:3:{i:0;a:9:{i:0;N;i:1;i:19;i:2;i:26;i:3;i:34;i:4;N;i:5;i:50;i:6;N;i:7;N;i:8;i:87;}i:1;a:9:{i:0;i:9;i:1;N;i:2;N;i:3;N;i:4;i:46;i:5;N;i:6;i:63;i:7;i:76;i:8;i:89;}i:2;a:9:{i:0;i:4;i:1;i:13;i:2;i:25;i:3;N;i:4;N;i:5;N;i:6;N;i:7;i:72;i:8;i:86;}}i:1;a:3:{i:0;a:9:{i:0;N;i:1;i:16;i:2;i:20;i:3;i:37;i:4;i:45;i:5;N;i:6;i:69;i:7;N;i:8;N;}i:1;a:9:{i:0;N;i:1;N;i:2;i:28;i:3;i:39;i:4;i:41;i:5;i:56;i:6;i:68;i:7;N;i:8;N;}i:2;a:9:{i:0;i:5;i:1;i:11;i:2;N;i:3;i:36;i:4;i:42;i:5;N;i:6;i:67;i:7;N;i:8;N;}}i:2;a:3:{i:0;a:9:{i:0;N;i:1;N;i:2;i:21;i:3;N;i:4;i:40;i:5;i:53;i:6;N;i:7;i:71;i:8;i:81;}i:1;a:9:{i:0;i:3;i:1;i:18;i:2;N;i:3;N;i:4;N;i:5;i:54;i:6;N;i:7;i:75;i:8;i:83;}i:2;a:10:{i:0;N;i:1;i:12;i:2;N;i:3;N;i:4;i:48;i:5;i:57;i:6;i:61;i:7;N;i:8;N;i:9;i:90;}}i:3;a:3:{i:0;a:9:{i:0;i:8;i:1;N;i:2;N;i:3;i:32;i:4;i:49;i:5;i:52;i:6;N;i:7;i:70;i:8;N;}i:1;a:9:{i:0;N;i:1;N;i:2;i:22;i:3;N;i:4;i:47;i:5;i:51;i:6;N;i:7;i:79;i:8;i:84;}i:2;a:9:{i:0;i:2;i:1;i:10;i:2;i:24;i:3;N;i:4;N;i:5;i:55;i:6;N;i:7;N;i:8;i:88;}}i:4;a:3:{i:0;a:9:{i:0;N;i:1;i:17;i:2;i:27;i:3;i:35;i:4;N;i:5;N;i:6;i:62;i:7;N;i:8;i:82;}i:1;a:9:{i:0;i:6;i:1;i:14;i:2;N;i:3;i:31;i:4;N;i:5;N;i:6;i:66;i:7;N;i:8;i:80;}i:2;a:9:{i:0;N;i:1;i:15;i:2;i:29;i:3;i:38;i:4;N;i:5;i:58;i:6;N;i:7;i:78;i:8;N;}}i:5;a:3:{i:0;a:9:{i:0;N;i:1;N;i:2;i:23;i:3;i:33;i:4;N;i:5;N;i:6;i:65;i:7;i:77;i:8;i:85;}i:1;a:9:{i:0;i:7;i:1;N;i:2;N;i:3;i:30;i:4;i:44;i:5;N;i:6;i:64;i:7;i:73;i:8;N;}i:2;a:9:{i:0;i:1;i:1;N;i:2;N;i:3;N;i:4;i:43;i:5;i:59;i:6;i:60;i:7;i:74;i:8;N;}}}");

		$tall = [];

		foreach($s as $blokknr => $blokk) {
			foreach ($blokk as $radnr => $rad) {
				foreach ($rad as $nummer) {
					if (!is_null($nummer)) {
						$tall[] = $nummer;
					}
				}
			}
		}

		$erUnik = count($tall) === count(array_unique($tall, SORT_NUMERIC));
		$this->assertTrue($erUnik);
	}

	public function testTallomraade() {
		// Tester at hver side har tallene 1 - 90

		//$a->lagSide();

		//$s = $a->hentSide();

		$s = unserialize("a:6:{i:0;a:3:{i:0;a:9:{i:0;N;i:1;i:19;i:2;i:26;i:3;i:34;i:4;N;i:5;i:50;i:6;N;i:7;N;i:8;i:87;}i:1;a:9:{i:0;i:9;i:1;N;i:2;N;i:3;N;i:4;i:46;i:5;N;i:6;i:63;i:7;i:76;i:8;i:89;}i:2;a:9:{i:0;i:4;i:1;i:13;i:2;i:25;i:3;N;i:4;N;i:5;N;i:6;N;i:7;i:72;i:8;i:86;}}i:1;a:3:{i:0;a:9:{i:0;N;i:1;i:16;i:2;i:20;i:3;i:37;i:4;i:45;i:5;N;i:6;i:69;i:7;N;i:8;N;}i:1;a:9:{i:0;N;i:1;N;i:2;i:28;i:3;i:39;i:4;i:41;i:5;i:56;i:6;i:68;i:7;N;i:8;N;}i:2;a:9:{i:0;i:5;i:1;i:11;i:2;N;i:3;i:36;i:4;i:42;i:5;N;i:6;i:67;i:7;N;i:8;N;}}i:2;a:3:{i:0;a:9:{i:0;N;i:1;N;i:2;i:21;i:3;N;i:4;i:40;i:5;i:53;i:6;N;i:7;i:71;i:8;i:81;}i:1;a:9:{i:0;i:3;i:1;i:18;i:2;N;i:3;N;i:4;N;i:5;i:54;i:6;N;i:7;i:75;i:8;i:83;}i:2;a:10:{i:0;N;i:1;i:12;i:2;N;i:3;N;i:4;i:48;i:5;i:57;i:6;i:61;i:7;N;i:8;N;i:9;i:90;}}i:3;a:3:{i:0;a:9:{i:0;i:8;i:1;N;i:2;N;i:3;i:32;i:4;i:49;i:5;i:52;i:6;N;i:7;i:70;i:8;N;}i:1;a:9:{i:0;N;i:1;N;i:2;i:22;i:3;N;i:4;i:47;i:5;i:51;i:6;N;i:7;i:79;i:8;i:84;}i:2;a:9:{i:0;i:2;i:1;i:10;i:2;i:24;i:3;N;i:4;N;i:5;i:55;i:6;N;i:7;N;i:8;i:88;}}i:4;a:3:{i:0;a:9:{i:0;N;i:1;i:17;i:2;i:27;i:3;i:35;i:4;N;i:5;N;i:6;i:62;i:7;N;i:8;i:82;}i:1;a:9:{i:0;i:6;i:1;i:14;i:2;N;i:3;i:31;i:4;N;i:5;N;i:6;i:66;i:7;N;i:8;i:80;}i:2;a:9:{i:0;N;i:1;i:15;i:2;i:29;i:3;i:38;i:4;N;i:5;i:58;i:6;N;i:7;i:78;i:8;N;}}i:5;a:3:{i:0;a:9:{i:0;N;i:1;N;i:2;i:23;i:3;i:33;i:4;N;i:5;N;i:6;i:65;i:7;i:77;i:8;i:85;}i:1;a:9:{i:0;i:7;i:1;N;i:2;N;i:3;i:30;i:4;i:44;i:5;N;i:6;i:64;i:7;i:73;i:8;N;}i:2;a:9:{i:0;i:1;i:1;N;i:2;N;i:3;N;i:4;i:43;i:5;i:59;i:6;i:60;i:7;i:74;i:8;N;}}}");
		
		$tall = [];

		foreach($s as $blokknr => $blokk) {
			foreach ($blokk as $radnr => $rad) {
				foreach ($rad as $nummer) {
					if (!is_null($nummer)) {
						$tall[] = $nummer;
					}
				}
			}
		}

		sort($tall);
		foreach ($tall as $forventet => $tall) {
			$this->assertEquals($forventet + 1, $tall);
		}

	}
}