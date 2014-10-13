<?php

class Request {
    static public function gamestatus($db) {
        // uses the following tables: games, rounds, drawing, employees

        $sql = "SELECT * FROM games WHERE date = CURDATE() LIMIT 1";
        $game = $db->query($sql)->fetch(PDO::FETCH_ASSOC);

        if ($game === false) {
            return json_encode(["status" => true, "gamestatus" => "notStarted"]);
        }

        // game started, let's find a round
        $sql = "SELECT * FROM rounds WHERE game = :game ORDER BY id DESC LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":game", $game['id']);
        $stmt->execute();
        $round = $stmt->fetch(PDO::FETCH_ASSOC);

        // fetch producer
        $sql = "SELECT employees.name FROM games INNER JOIN employees " .
            "ON games.producer = employees.id WHERE games.id = :game LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":game", $game['id']);
        $stmt->execute();
        $producer = $stmt->fetchColumn(0);

        // fetch presenter
        $sql = "SELECT employees.name FROM games INNER JOIN employees " .
            "ON games.presenter = employees.id WHERE games.id = :game LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":game", $game['id']);
        $stmt->execute();
        $presenter = $stmt->fetchColumn(0);

        if ($round === false) {
            return json_encode(["status" => true, "gamestatus" => "noRound", 
                "jackpot" => $game['jackpot'], 
                "jackpotNumber" => $game['jackpot_number'],
                "producer" => $producer, "presenter" => $presenter]);
        }

        // round started. Output data
        $sql = "SELECT drawing.number FROM drawing WHERE " .
            "picked = 1 ORDER BY drawing.when";
        $drawn = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        $numbers = [];
        foreach($drawn as $number) {
            $numbers[] = (int)$number['number'];
        }

        return json_encode(["status" => true, "gamestatus" => "round", 
                "jackpot" => (int)$game['jackpot'], 
                "jackpotNumber" => (int)$game['jackpot_number'], 
                "type" => $round['type'], "name" => (int)$round['name'], 
                "numbers" => $numbers, 
                "rows" => (int)$round['rows'], "producer" => $producer, 
                "presenter" => $presenter]);
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
        
        return json_encode(["status" => true,
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

    static public function newRound($db) {
        // uses the following tables: rounds, games, winners, places

        if (!($_GET['type'] == "R" || $_GET['type'] == "R")) {
            return json_encode(["status" => false, "error" => "Invalid type"]);
        }

        $sql = "SELECT games.id FROM games ORDER BY id DESC LIMIT 1";
        $game = $db->query($sql)->fetchColumn(0);


        $sql = "INSERT INTO rounds (started, game, type, name, rows) " .
            "VALUES (NOW(), :game, :type, :name, :rows)";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":game", $game, PDO::PARAM_INT);
        $stmt->bindValue(":type", $_GET['type'], PDO::PARAM_STR);
        $stmt->bindValue(":name", $_GET['name'], PDO::PARAM_INT);
        $stmt->bindValue(":rows", $_GET['rows'], PDO::PARAM_INT);
        $stmt->execute();
        $round = $db->lastInsertId();

        if ($stmt->rowCount() != 1) {
            return json_encode(["status" => false, "error" => "Database error"]);
        }

        if (isset($_GET['winners'])) {
            // requester has opted for a list of winners
            $sql = "SELECT winners.name, places.place, winners.price FROM " .
                "winners WHERE :round";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(":round", $round, PDO::PARAM_INT);
            $stmt->execute();

            json_encode(["status" => true, 
                "winners" => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
        }

        return json_encode(["status" => true]);

    }
}