<?php

class Request {
    static private function traverseEncode($array) {
        $result = [];
        foreach ($array as $key => $value) {
            if (gettype($value) == "array") {
                $result[$key] = traverseEncode($value);
            }
            elseif (gettype($value) == "string") {
                $result[$key] = utf8_encode($value);
            }
            else {
                $result[$key] = $value;
            }
        }
        return $result;
    }

    static private function json($array) {
        return json_encode(self::traverseEncode($array));
    }

    static public function gamestatus($db) {
        // uses the following tables: games, rounds, drawing

        $sql = "SELECT * FROM games WHERE date = CURDATE() LIMIT 1";
        $game = $db->query($sql)->fetch(PDO::FETCH_ASSOC);

        if ($game === false) {
            return self::json(["status" => true, "gamestatus" => "notStarted"]);
        }

        // game started, let's find a round
        $sql = "SELECT * FROM rounds ORDER BY id DESC WHERE game = :game LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":game", $game['id']);
        $stmt->execute();
        $round = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($round === false) {
            return self::json(["status" => true, "gamestatus" => "noRound", 
                "jackpot" => $game['jackpot'], "jackpotNumber" => $game['jackpot_number']]);
        }

        // round started. Output data
        $numbers = explode(";", $round['numbers']); // FIXME wrong table
        // select number from drawing where picked = 1 order by when;

        return self::json(["status" => true, "gamestatus" => "round", 
                "jackpot" => $game['jackpot'], "jackpotNumber" => $game['jackpot_number'], 
                "type" => $round['type'], "name" => $round['name'], "drawnNumbers" => $numbers, 
                "rows" => $round['rows']]);
    }

    // here stuff should happen! :)
    static public function startRound($db) {
    }


    static public function startGame($db) {
         // uses the following tables: employees, games

        // prepare the SQL to look up employees
        $sql = "SELECT employees.id FROM employees WHERE name = :name LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":name", $person, PDO::PARAM_STR);

        $person = $_GET['producer'];
        $stmt->execute();
        $producer = $stmt->fetchColumn(0);
        
        $person = $_GET['presenter'];
        $stmt->execute();
        $presenter = $stmt->fetchColumn(0);

        if (empty($presenter) or empty($producer)) {
            $sql = "INSERT INTO employees (name) VALUES (:name)";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(":name", $employee, PDO::PARAM_STR);
            
            if (empty($producer)) {
                $employee = $_GET["producer"];
                $stmt->execute();
                $producer = $db->lastInsertId();
            }
            
            if (empty($presenter)) {
                $employee = $_GET["presenter"];
                $stmt->execute();
                $presenter = $db->lastInsertId();
            }
        }

        // fetch game data
        $sql = ("SELECT games.jackpot, games.got_jackpot " .
                "FROM games ORDER BY date DESC LIMIT 1");

        $previousGame = $db->query($sql)->fetch(PDO::FETCH_ASSOC);

        // first game or jackpot in previous game, so start again
        if (empty($previousGame) or $previousGame['got_jackpot']) {
            $jackpot = 5000;
        }
        // jackpot is already at max
        elseif ($previousGame["jackpot"] == 20000) {
            $jackpot = $previousGame["jackpot"];
        }
        // increase the jackpot
        else {
            $jackpot = $previousGame['jackpot'] + 500;
        }

        $jackpotNumber = mt_rand(1, 90);

        $sql = ("INSERT INTO games (date, presenter, producer, " .
                "jackpot_number, jackpot) " .
                "VALUES (CURDATE(), :presenter, :producer, :jackpotNumber, " .
                ":jackpot)");

        $stmt = $db->prepare($sql);
        $stmt->bindValue(":presenter", $presenter);
        $stmt->bindValue(":producer", $producer);
        $stmt->bindValue(":jackpotNumber", $jackpotNumber);
        $stmt->bindValue(":jackpot", $jackpot);
        $stmt->execute();
        
        return self::json(["status" => true,
                           "jackpot" => $jackpot,
                           "jackpotNumber" => $jackpotNumber]);
    }

    static public function getEmployee($db) {
        // uses the following tables: employees

        $sql = "SELECT employees.name FROM employees WHERE name LIKE :term";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":term", "%" . $_GET["term"] . "%", PDO::PARAM_STR);
        $stmt->execute();

        $employees = [];

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $data) {
            $employees[] = $data['name'];
        }

        // json needs utf8-encoded strings, so make sure the database supports it!
        return json_encode($employees);
    }
}