<?php

class RoundTest extends PHPUnit_Framework_TestCase {
	public function __construct() {
		$numbers = explode(";", "22;38;2;60;61;53;45;8;82;31;11;33;72;68;1;85;32;65;51");
		$drawnNumbers = explode(";", "70;76;79;56;75;20;71;10;13;64;89;19;83;58;40;69;41;5;59;48;90;7;4;80;37;67;14;86;27;52;57;35;77;47;81;30;3;55;34;84;42;88;21;78;16;43;73;39;46;29;23;44;50;87;9;12;6;15;49;18;66;17;74;63;62;28;36;26;24;54;25");
		$winners = [];
		$this->round = new Round(1, "R", 88, 5000, 2, $numbers, $drawnNumbers, $winners);
	}

	public function testWinningTicketValidation() {
		$ticket = new Ticket(10000);
		$round = $this->round;

		$ticket->fromString("1;11;;;46;50;;;81-9;;20;35;;52;;70;-;;21;;48;;63;76;87");
		$validated = $round->validateTicket($ticket);

		$this->assertTrue($validated['win']);
	}

	public function testFailingTicketValidation() {
		$ticket = new Ticket(10001);
		$round = $this->round;

		$ticket->fromString(";17;;31;40;;60;75;-;;25;38;43;56;;77;-5;;;39;;58;66;;86");
		$validated = $round->validateTicket($ticket);

		$this->assertFalse($validated['win']);
	}

	public function testDrawing() {
		$this->assertEquals(51, $this->round->draw());
		$this->assertEquals(65, $this->round->draw());
		$this->assertEquals(32, $this->round->draw());
		$this->assertEquals(85, $this->round->draw());
	}
}