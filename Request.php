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

    static public function startGame($db) {
         // uses the following tables: employees, games

        // get the employee id
        $sql = "SELECT employees.id FROM employees WHERE name = :name LIMIT 1";
        $stmt = $db->prepare($sql);
        
        $stmt->bindValue(":name", $_GET['producer'], PDO::PARAM_STR);
        $stmt->execute();
        $producer = (int)$stmt->fetchColumn(0);

        $stmt->bindValue(":name", $_GET['presenter'], PDO::PARAM_STR);
        $stmt->execute();
        $presenter = (int)$stmt->fetchColumn(0);

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

        // fetch game data
        $sql = ("SELECT games.jackpot, games.got_jackpot " .
                "FROM games ORDER BY date DESC LIMIT 1");

        $previousGame = $db->query($sql)->fetch(PDO::FETCH_ASSOC);

        if (empty($previousGame) or $previousGame['got_jackpot']) {
            // first game ever
            $jackpot = 5000;
        }
        elseif ($previousGame["jackpot"] == 20000) {
            $jackpot = $previousGame["jackpot"];
        }
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

        $term = '%' . $_GET['term'] . '%';

        $sql = "SELECT employees.name FROM employees WHERE name LIKE :term";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":term", $term, PDO::PARAM_STR);
        $stmt->execute();

        $array = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $data) {
            $array[] = $data['name'];
        }

        return self::json($array);
    }
}