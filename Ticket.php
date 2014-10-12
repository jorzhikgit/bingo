<?php

class Ticket {
    public function __construct($verification) {
        if ($verification <= 10000 && $verification >= 99999) {
            throw new Exception("Invalid verification for ticket");
        }

        $this->verification = $verification;
        $this->ticket = [];
    }

    public function hasNumber($number) {
        for ($row = 0; $row < 3; $row++) {
            for ($position = 0; $position < 9; $position++) {
                if ($this->blokk[$row][$position] == $number) {
                    return ["row" => $row, "position" => $position];
                }
            }
        }
        return null;
    }

    public function getTicket() {
        return $this->ticket;
    }

    public function toText() {
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

    public function fromText($text) {
        $ticket = [];
        $count = 0;

        foreach (explode('-', $text) as $row) {
          $ticket[$count] = [];
          foreach (explode(';', $row) as $column) {
            array_push($ticket[$count], $column);
          }
          $count += 1;
        }

        $this->ticket = $ticket;
    }

    public function getVerification() {
        return $this->verification;
    }
}