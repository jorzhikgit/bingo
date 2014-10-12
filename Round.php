<?php
require_once "Ticket.php";

class Round {
    public function __construct($name = 1, $type = "R", $jackpotNumber = 0, $jackpot = 0, $numbers = [], $drawnNumbers = [], $rows = 1, $winners = []) {
        if ($name >= 1 && $name <= 5) {
            $this->name = $name;
        } elseif ($type == "P") {
            $this->name = "Pausespill";
        } else {
            throw new Exception("Invalid round name.");
        }

        if($type == "P" || $type == "R") {
            $this->type = $type;
        } else {
            throw new Exception("Invalid type of round.");
        }

        if ($jackpotNumber >= 1 && $jackpotNumber <= 90) {
            $this->jackpotNumber = $jackpotNumber;
        } else {
            throw new Exception("The jackpot number must be between 1 and 90.");
        }

        if ($jackpot >= 1000 && $jackpot <= 20000) {
            $this->jackpot = $jackpot;
        } else {
            throw new Exception("The must be between 1000 and 20 000.");
        }

        if (empty($numbers)) {
            $this->numbers = range(1, 90);
            shuffle($this->numbers);
        } else {
            $this->numbers = $numbers;
        }
        
        $this->drawnNumbers = $drawnNumbers;
        $this->winners = $winners;
        $this->rows = $rows;
    }

    public function draw() {
        $number = array_pop($this->numbers);
        if (is_null($number) || $number === "") {
            return null;
        }

        $this->drawnNumbers[] = $number;

        return $number;
    }

    public function validateTicket(Ticket $ticket) {
        $numbers = [];
        $rows = [0, 0, 0];
        $winningNumber = 0;

        foreach ($this->drawnNumbers as $number) {
            $hasNumber = $ticket->hasNumber($number);

            if(!is_null($hasNumber)) {
                $numbers[] = $hasNumber;
                $rows[$hasNumber["row"]]++;
                $winningNumber = $number;
            }
        }

        $winningRows = 0;
        foreach ($rows as $row) {
            if ($row == 5) {
                $winningRows++;
            }
        }

        if ($winningRows >= $this->rows) {
            $haveWon = true;
        } else {
            $haveWon = false;
        }

        if ($haveWon) {
            return ["haveWon" => true, "numbers" => $numbers, 
                "number" => $winningNumber];
        }

        return ["haveWon" => false, "numbers" => $numbers, "number" => null];

    }

    public function newRound() {
        if ($this->rows >= 3) {
            return false;
        }
        
        $this->rows++;
        return true;
    }

    public function generateHTML(Ticket $ticket, array $winningNumbers) {
        $combined = [];
        $ticket = $ticket->getTicket();
        for ($row= 0; $row < count($ticket); $row++) {
            $combined[$row] = [];
            for ($number = 0; $number < count($ticket[$row]); $number++) {
                if($ticket[$row][$number] == "") {
                    $combined[$row][$number] = [" ", true];
                } else {
                    $combined[$row][$number] = [$ticket[$row][$number], false];
                }

            }
        }


        foreach ($winningNumbers as $number) {
            $combined[$number['row']][$number['position']][1] = true;
        }

        $html = "<table>\n";

        foreach ($combined as $row) {
            $html .= "<tr>\n";
            foreach ($row as $position) {
                if($position[1]) {
                    $html .= sprintf('<td class="%s">%s</td>%s', "trekt", 
                        $position[0], "\n");
                } else {
                    $html .= sprintf("<td>%s</td>\n", $position[0]);
                }
            }

            $html .= "</tr>\n";
        }

        $html .= "</table>\n";
        return $html;
    }

    public function getNumbers() {
        return $this->numbers;
    }

    public function getdrawnNumbers() {
        return $this->drawnNumbers;
    }

    public function getWinners() {
        return $this->winners;
    }

    public function getRows() {
        return $this->rows;
    }

    public function getName() {
        return $this->name;
    }

    public function getJackpot() {
        return $this->jackpot;
    }

    public function getjackpotNumber() {
        return $this->jackpotNumber;
    }

    public function fromDb($db) {
        $sql = "SELECT * FROM rounds ORDER BY roundId DESC LIMIT 1";
        $round = $db->query($sql)->fetch(PDO::FETCH_ASSOC);

        if (empty($round['drawnNumbers']) || is_null($round['drawnNumbers']) || $round['drawnNumbers'] === '') {
            $this->drawnNumbers = [];
        } else {
            $this->drawnNumbers = explode(';', $round['drawnNumbers']);
        }

        if ((empty($round['numbers']) || is_null($round['numbers']) || $round['numbers'] === '') && empty($this->drawnNumbers)) {
            $this->numbers = range(1, 90);
            shuffle($this->numbers);
        } else {
            $this->numbers = explode(';', $round['tall']);
        }

        $sql = "SELECT nights.jackpotNumber, nights.jackpot FROM nights WHERE nightId = :nightId";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":nightId", $round['nightId'], PDO::PARAM_INT);
        $stmt->execute();
        $night = $stmt->fetch(PDO::FETCH_ASSOC);
        $jackpotNumber = $night['jackpotNumber'];
        $jackpot = $night['jackpot'];

        $stmt = $db->prepare("SELECT winners.winnerId, customers.name, places.place, " .
            "winners.price FROM winners " . 
            "INNER JOIN customers ON winners.customerId = customers.customerId " .
            "INNER JOIN places ON customers.placeId = places.placeId " .
            "WHERE winners.roundId = :roundId AND " .
            "winners.rows = :rows ORDER BY winners.winnerId");
        $stmt->bindParam(":omgangid", $round['roundId'], PDO::PARAM_INT);
        $stmt->bindParam(":rows", $round['rows'], PDO::PARAM_INT);
        $stmt->execute();
        $winners = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->name = $round['name'];
        $this->type = $round['type'];
        $this->rows = $round['rows'];
        $this->jackpotNumber = $jackpotNumber;
        $this->jackpot = $jackpot;
        $this->winners = $winners;

        return $round['roundId'];
    }
}