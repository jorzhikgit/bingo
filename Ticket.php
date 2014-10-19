<?php

class Ticket {
    public function __construct($verification, $db) {
        if (is_null($verification)) {
            return;
        }
        
        if ($verification <= 10000 && $verification >= 99999) {
            throw new Exception("Invalid verification for ticket");
        }

        $this->verification = $verification;

        $stmt = $db->prepare("SELECT ticket FROM tickets WHERE id = :id");
        $stmt->bindValue(":id", $verification, PDO::PARAM_INT);
        $stmt->execute();
        $this->fromString($stmt->fetchColumn(0));
    }

    public function getNumberRow($number) {
        try {
            list ($y, $x) = $this->getNumberPos($number);
            return $y;
        }
        catch (Exception $e) {
            return null;
        }
    }

    // this method will return the position of the number in the ticket (x and y)
    public function getNumberPos($number) {
        for ($y = 0; $y < 3; $y++) {
            for ($x = 0; $x < 9; $x++) {
                if ($this->ticket[$y][$x] == $number) {
                    return array($y, $x);
                }
            }
        }
        return null;
    }

    public function getTicket() {
        return $this->ticket;
    }

    public function toString() {
        $result = "";
        $rows = [];
        for ($i = 0; $i < count($this->ticket); $i++) {
            if ($i % 3 == 0) {
                if (count($rows) > 0) {
            $result .= implode("-", $rows);
                }
                $rows = [];
            }
            array_push($rows, implode(';', $this->ticket[$i]));
        }
        $result .= implode("-", $rows);

        return $result;
    }

    public function fromString($string) {
        $ticket = [];
        $count = 0;

        foreach (explode('-', $string) as $row) {
          $ticket[$count] = [];
          foreach (explode(';', $row) as $column) {
            array_push($ticket[$count], (int)$column);
          }
          $count += 1;
        }

        $this->ticket = $ticket;
    }

    public function getVerification() {
        return $this->verification;
    }
}