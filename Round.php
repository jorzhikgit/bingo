<?php

class Round {
    public function __construct($db, $id = null) {
        $this->db = $db;
        $this->fromDb($db, $id);
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

        // the last row with a valid number
        $winningRow = 0;
        
        foreach ($this->drawnNumbers as $number) {
            $row = $ticket->getNumberRow($number);

            // number is located in a row in the ticket
            if (!is_null($row)) {
                $numbers[$row][] = $number;
                $winningRow = $row;

                if (++$rows[$row] == 5) {
                    $winningNumbers[$completedRows] = $number;
                    $completedRows++;
                }
            }
        }
        
        if ($completedRows >= $this->rows) {
            return ["win" => True,
                    "numbers" => $numbers,
                    "winningNumbers" => $winningNumbers,
                    "winningRow" => $winningRow];
        }
        else {
            return ["win" => False,
                    "numbers" => $numbers,
                    "winningNumbers" => $winningNumbers,
                    "winningRow" => $winningRow];
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
        for ($row = 0; $row < count($ticket); $row++) {
            $combined[$row] = [];
            for ($number = 0; $number < count($ticket[$row]); $number++) {
                if($ticket[$row][$number] == 0) {
                    $combined[$row][$number] = [" ", true];
                } elseif (in_array($ticket[$row][$number], $winningNumbers[$row])) {
                    $combined[$row][$number] = [$ticket[$row][$number], true];
                }
                else {
                    $combined[$row][$number] = [$ticket[$row][$number], false];
                }
            }
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

    private function fromDb($db, $id) {
        // to be cleaned up with new db schema

        if (!is_null($id)) {
            $sql = "SELECT * FROM rounds WHERE id = :id";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            $round = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $sql = "SELECT * FROM rounds ORDER BY id DESC LIMIT 1";
            $round = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
        }
        
        $sql = "SELECT drawing.number FROM drawing WHERE drawing.timestamp IS NOT NULL ORDER BY drawing.timestamp";
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
        $stmt->bindParam(":row", $round['current_row'], PDO::PARAM_INT);
        $stmt->execute();
        $winners = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $db->prepare("SELECT count(rounds.id)  FROM rounds WHERE game = :game AND type = 'R'");
        $stmt->bindValue(":game", $round['game']);
        $stmt->execute();
        $name = $stmt->fetchColumn(0);

        $this->name = $name;
        $this->type = $round['type'];
        $this->rows = $round['current_row'];
        $this->jackpotNumber = $jackpotNumber;
        $this->jackpot = $jackpot;
        $this->winners = $winners;
        $this->id = $round['id'];
        $this->drawnNumbers = $numbers;
    }

    public function save() {
        $sql = "UPDATE round SET  WHERE id = :id";
    }
}