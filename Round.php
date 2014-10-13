<?php

class Round {
    public function __construct($db) {
        $this->db = $db;
        $this->fromDb($db);
    }

    public function draw() {
        if (empty($this->numbers)) {
            return null;
        }
        $number = array_pop($this->numbers);
        $this->drawnNumbers[] = $number;
        return $number;
    }

    public function validateTicket(Ticket $ticket) {
        // this is all the numbers that has been matched for the ticket
        $numbers = [[], [], []];

        // this is the count of the different rows
        $rows = [0, 0, 0];
        
        // this is the array of the numbers that completed the different rows
        $winningNumbers = [0, 0, 0];

        // the number of rows completed
        $completedRows = 0;
        
        foreach ($this->drawnNumbers as $number) {
            $row = $ticket->getNumberRow($number);

            // number is located in a row in the ticket
            if (!is_null($row)) {
                $numbers[$row][] = $number;

                if (++$rows[$row] == 5) {
                    $winningNumbers[$completedRows] = $number;
                    $completedRows++;
                }
            }
        }
        
        if ($completedRows >= $this->rows) {
            return ["win" => True,
                    "numbers" => $numbers,
                    "winningNumbers" => $winningNumbers];
        }
        else {
            return ["win" => False,
                    "numbers" => $numbers,
                    "winningNumbers" => $winningNumbers];
        }
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
                }
                else {
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
                if ($position[1]) {
                    $html .= sprintf('<td class="%s">%s</td>%s', "trekt", 
                        $position[0], "\n");
                }
                else {
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

    public function getId() {
        return $this->id;
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

    public function getJackpotNumber() {
        return $this->jackpotNumber;
    }

    private function fromDb($db) {
        // to be cleaned up with new db schema

        $sql = "SELECT * FROM rounds ORDER BY id DESC LIMIT 1";
        $round = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
        
        $sql = "SELECT drawing.number FROM drawing WHERE drawing.timestamp IS NOT NULL ORDER BY drawing.timestamp"
        $drawnNumbers = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        $numbers = [];
        foreach ($drawnNumbers as $number) {
            $numbers[] = (int)$number['number'];
        }

        $sql = "SELECT game.jackpotNumber, game.jackpot FROM game WHERE id = :game";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":game", $round['game'], PDO::PARAM_INT);
        $stmt->execute();
        $game = $stmt->fetch(PDO::FETCH_ASSOC);
        $jackpotNumber = $game['jackpotNumber'];
        $jackpot = $game['jackpot'];

        $stmt = $db->prepare("SELECT winners.id, players.name, places.place, " .
            "winners.price FROM winners " . 
            "INNER JOIN players ON winners.player = players.id " .
            "INNER JOIN places ON players.place = places.id " .
            "WHERE winners.round = :round AND " .
            "winners.row = :row ORDER BY winners.id");
        $stmt->bindParam(":round", $round['id'], PDO::PARAM_INT);
        $stmt->bindParam(":rows", $round['row'], PDO::PARAM_INT);
        $stmt->execute();
        $winners = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->name = $round['name'];
        $this->type = $round['type'];
        $this->rows = $round['rows'];
        $this->jackpotNumber = $jackpotNumber;
        $this->jackpot = $jackpot;
        $this->winners = $winners;
        $this->id = $round['id'];
    }

    public function save() {

    }
}