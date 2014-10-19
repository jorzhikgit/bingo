<?php

require_once "Ticket.php";
require_once "Round.php";

class Request {
    static public function gamestatus($db) {
        // uses the following tables: games, rounds, drawing, employees

        $sql = ("SELECT g.id, g.date, g.jackpot_number, g.jackpot, " .
                "em1.name AS producer, em2.name AS presenter " .
                "FROM games g " .
                "JOIN employees em1 ON g.producer = em1.id " .
                "JOIN employees em2 ON g.presenter = em2.id " .
                "WHERE g.date = CURDATE() " .
                "LIMIT 1");

        $game = $db->query($sql)->fetch(PDO::FETCH_ASSOC);

        if (empty($game)) {
            return json_encode(["status" => true, "gamestatus" => "notStarted"]);
        }

        // game started, let's find a round
        $sql = "SELECT * FROM rounds WHERE game = :game ORDER BY id DESC LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":game", $game['id']);
        $stmt->execute();
        $round = $stmt->fetch(PDO::FETCH_ASSOC);

        $producer = $game["producer"];
        $presenter = $game["presenter"];

        if (!$round) {
            return json_encode(["status" => true, "gamestatus" => "noRound", 
                                "jackpot" => $game['jackpot'], 
                                "jackpotNumber" => $game['jackpot_number'],
                                "producer" => $producer, "presenter" => $presenter]);
        }

        // round started, find the drawn numbers
        $sql = ("SELECT drawing.number FROM drawing WHERE " .
                "drawing.timestamp IS NOT NULL ORDER BY drawing.timestamp");
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
                            "rows" => (int)$round['current_row'], "producer" => $producer, 
                            "presenter" => $presenter]);
    }

    static public function startGame($db) {
         // uses the following tables: employees, games, drawing

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
        $stmt->bindValue(":presenter", $presenter, PDO::PARAM_INT);
        $stmt->bindValue(":producer", $producer, PDO::PARAM_INT);
        $stmt->bindValue(":jackpotNumber", $jackpotNumber, PDO::PARAM_INT);
        $stmt->bindValue(":jackpot", $jackpot, PDO::PARAM_INT);
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

        if (!($_GET['type'] == "R" || $_GET['type'] == "P")) {
            return json_encode(["status" => false, "error" => "Invalid type"]);
        }

         // reset the numbers and save old ones
        $sql = "SELECT drawing.number FROM drawing WHERE drawing.timestamp IS NOT NULL ORDER BY drawing.timestamp";
        $drawnNumbers = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        $previousNumbers = [];
        foreach ($drawnNumbers as $number) {
            $previousNumbers[] = $number['number'];
        }
        $previousNumbers = implode(";", $previousNumbers);

        $sql = "SELECT rounds.id FROM rounds ORDER BY rounds.id DESC LIMIT 1";
        $previousRound = $db->query($sql)->fetchColumn(0);

        $sql = "UPDATE rounds SET numbers = :numbers WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":numbers", $previousNumbers, PDO::PARAM_STR);
        $stmt->bindValue(":id", $previousRound, PDO::PARAM_INT);
        $stmt->execute();

        $sql = "UPDATE drawing SET drawing.timestamp = NULL";
        $db->exec($sql);

        $sql = "SELECT games.id FROM games ORDER BY id DESC LIMIT 1";
        $game = $db->query($sql)->fetchColumn(0);

        $sql = ("INSERT INTO rounds (game, type, name, current_row) " .
                "VALUES (:game, :type, :name, 1)");
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":game", $game, PDO::PARAM_INT);
        $stmt->bindParam(":type", $_GET['type'], PDO::PARAM_STR);
        $stmt->bindParam(":name", $_GET['name'], PDO::PARAM_INT);
        $stmt->execute();
        $round = $db->lastInsertId();

        if ($stmt->rowCount() != 1) {
            return json_encode(["status" => false, "error" => "Database error"]);
        }

        // let the producer's websockets know
        file_get_contents('http://localhost:4000/newRound');

        if (isset($_GET['winners'])) {
            // requester has opted for a list of winners

            $sql = "SELECT winners.name, places.place, winners.price FROM " .
                "winners WHERE :round";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(":round", $round, PDO::PARAM_INT);
            $stmt->execute();

            return json_encode(["status" => true, 
                "winners" => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
        }

        return json_encode(["status" => true]);
    }

    static public function draw($db) {
        // uses the following tables: drawing

        // get old numbers
        $sql = "SELECT drawing.number FROM drawing WHERE drawing.timestamp IS NOT NULL ORDER BY drawing.timestamp";
        $drawn = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        // select new number
        $sql = "SELECT drawing.number from drawing WHERE drawing.timestamp IS NULL ORDER BY rand() LIMIT 1";
        $number = $db->query($sql)->fetchColumn(0);

        // save it
        $sql = "UPDATE drawing SET drawing.timestamp = NOW() " .
            "WHERE drawing.number = :number";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":number", $number, PDO::PARAM_INT);
        $stmt->execute();

        // getting the numbers
        $numbers = [];
        foreach ($drawn as $drawnNumber) {
            $numbers[] = (int)$drawnNumber['number'];
        }

        // adding the newly drawn number
        $numbers[] = $number;

        // let the producer's websockets know
        file_get_contents('http://localhost:4000/newNumber');

        return json_encode(["status" => true, "numbers" => $numbers]);
    }

    static public function newRow($db) {
        $sql = "SELECT rounds.id, rounds.current_row FROM rounds ORDER BY id DESC LIMIT 1";
        $round = $db->query($sql)->fetch(PDO::FETCH_ASSOC);

        if ($round['current_row'] >= 3) {
            return json_encode(["status" => false, "error" => "Max number of rows exeeded."]);
        }

        $row = $round['current_row'] + 1;

        $sql = "UPDATE rounds SET current_row = :row WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":row", $row, PDO::PARAM_INT);
        $stmt->bindValue(":id", $round['id'], PDO::PARAM_INT);
        $stmt->execute();

        return json_encode(["status" => true, "rows" => $row]);
    }

    static public function verify($db) {
        if ($_GET['verification'] <= 10000 && $_GET['verification'] >= 99999) {
            return json_decode(["status" => false, "error" => "Invalid verification"]);
        }

        $ticket = new Ticket((int)$_GET['verification'], $db);
        $round = new Round($db);

        $verified = $round->validateTicket($ticket);

        $rows = $verified['winningRow'];
        $number = $verified['winningNumbers'][$row];

        $html = $round->generateHTML($ticket, $verified['numbers']);

        return json_encode(['status' => true, 'won' => $verified['win'], 'html' => $html, 'number' => $number]);
    }

    static public function getWinners($db) {
        $round = new Round($db);
        $id =$round->getId();
        $sql = "SELECT winners.id, winners.price, players.name, places.place FROM winners " .
            "INNER JOIN players ON players.id = winners.player INNER JOIN places ON " .
            "places.id = players.place WHERE round = :round AND winners.row = :row";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(":round", $id, PDO::PARAM_INT);
        $stmt->bindValue(":row", $round->getRows(), PDO::PARAM_INT);
        $stmt->execute();

        return json_encode(['status' => true, 'winners' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    }

    static public function saveWinners($db) {
        if (is_array($_POST['winners'])) {
            $sql = "UPDATE winners SET winners.price = :price, leftToPay = :price WHERE winners.id = :id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(":price", $price, PDO::PARAM_INT);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            foreach ($_POST['winners'] as $winner) {
                $price = $winner['price'];
                $id = $winner['id'];
                $stmt->execute();
            }
        }

        $sql = "SELECT players.id, places.place FROM players INNER JOIN places ON " .
            "places.id = players.place WHERE players.name = :name LIMIT 1";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(":name", $_POST['winner']['name'], PDO::PARAM_STR);
        $stmt->execute();
        $winner = $stmt->fetch(PDO::FETCH_ASSOC);

        $sql = "SELECT places.id FROM places WHERE place = :place";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":place", $_POST['winner']['place'], PDO::PARAM_STR);
        $stmt->execute();
        $place = $stmt->fetchColumn(0);

        if ($place == false) {
            // place doesn't exist
            $sql = "INSERT INTO places (place) VALUES(:place)";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(":place", $_POST['winner']['place'], PDO::PARAM_STR);
            $stmt->execute();
            $place = $db->lastInsertId();
        }

        if ($winner['place'] != $_POST['winner']['place']) {
            // update the place
            $sql = "UPDATE players SET place = :place WHERE id = :id";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(":place", $place, PDO::PARAM_INT);
            $stmt->bindValue(":id", $winner['id'], PDO::PARAM_INT);
            $stmt->execute();
        }

        if ($winner === false) {
            // winner doesn't exist
            $sql = "INSERT INTO winners (name, place) VALUES(:name, :place)";
            $stmt = $db->prepare(':name', $_POST['winner']['name'], PDO::PARAM_STR);
            $stmt->prepare(':place', $place, PDO::PARAM_INT);
            $stmt->execute();
            $winner = $db->lastInsertId();
        }

        $round = new Round($db);
        $rows = $round->getRows();

        $sql = "INSERT INTO winners (player, ticket, date, round, leftToPay, price, status, row) " .
            "VALUES(:player, :ticket, NOW(), :round, :price, :price, 5, :row)";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(":player", $winner['id'], PDO::PARAM_INT);
        $stmt->bindValue(":ticket", $_POST['winner']['verification'], PDO::PARAM_INT);
        $stmt->bindValue(":round", $round->getId(), PDO::PARAM_INT);
        $stmt->bindValue(":price", $_POST['winner']['price'], PDO::PARAM_INT);
        $stmt->bindValue(":row", $rows, PDO::PARAM_INT);
        $stmt->execute();

        return json_encode(['status' => true]);
    }

    static public function getName($db) {
        $sql = "SELECT players.name FROM players WHERE name LIKE :term";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":term", "%" .  $_GET['term'] . "%", PDO::PARAM_STR);
        $stmt->execute();
        $players = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($players as $player) {
            $result[] = $player['name'];
        }

        return json_encode($result);
    }

    static public function getPlace($db) {
        if (isset($_GET['term'])) {
            $sql = "SELECT places.place FROM places WHERE place LIKE :term";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(":term", "%" .  $_GET['term'] . "%", PDO::PARAM_STR);
            $stmt->execute();
            $places = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $result = [];
            foreach ($places as $place) {
                $result[] = $place['place'];
            }

            return json_encode($result);
        } elseif (isset($_GET['name'])) {
            $sql = "SELECT places.place FROM places INNER JOIN players ON " .
                "players.place = places.id WHERE players.name = :name LIMIT 1";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(":name", $_GET['name'], PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchColumn(0);

            return json_encode(['status' => true, 'place' => $result]);
        }
    }
}