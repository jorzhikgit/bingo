<?php

class TicketTest extends PHPUnit_Framework_TestCase {
	public function testRowSum() {
		// Tests if each row has five numbers

		$ticket = new Ticket(10000); // bogus
		$text = explode("\n", shell_exec("python bingo.py"))[0];
		$ticket->fromString($text);

		foreach ($ticket->getTicket() as $row) {
			$count = 0;
			foreach ($row as $number) {
				if ($number != "") {
					$count++;
				}
			}
			$this->assertEquals(5, $count);
		}
	}

	public function testColumnSum() {
		// Tests if each column only has it's designated numbers

		$ticket = new Ticket(10000); // bogus
		$text = explode("\n", shell_exec("python bingo.py"))[0];
		$ticket->fromString($text);

		$tens = [0, 0, 0, 0, 0, 0, 0, 0, 0];

		foreach ($ticket->getTicket() as $row) {
			foreach ($row as $number) {
				if ($number != "") {
					$ten = ($number == 90) ? 8 : floor($number / 10);
					$tens[$ten]++;
				}
			}
		}

		foreach ($tens as $count) {
			$this->assertLessThanOrEqual(3, $count);
		}
	}

	public function testUniqueness() {
		// Tests for uniqueness

		$ticket = new Ticket(10000); // bogus
		$text = explode("\n", shell_exec("python bingo.py"))[0];
		$ticket->fromString($text);

		$numbers = [];

		foreach ($ticket->getTicket() as $row) {
			foreach ($row as $number) {
				if ($number != "") {
					$numbers[] = $number;
				}
			}
		}

		$isUnique = count($numbers) === count(array_unique($numbers, SORT_NUMERIC));
		$this->assertTrue($isUnique);
	}
}