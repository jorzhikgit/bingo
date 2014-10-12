var server = require('http').createServer(handler);
var mysql = require("mysql");
var io = require('socket.io')(server);

var port = 4000;

var connection = mysql.createConnection({
    host: '192.168.1.124',
    user: 'bingo',
    password: 'q6SvBQmQO3lnAH',
    database: 'bingo'
});

connection.connect();
server.listen(port);

function handler(req, res) {
    if (req.body && req.body.action == "newNumber") {
        gotNumber();
    } else if (req.body && req.body.action == "newRound") {
        newRound();
    }

    res.writeHead(200);
    res.end();
};

 function gotNumber() {
    var number = 0;
    connection.query("SELECT rounds.drawnNumbers FROM " +
        "rounds ORDER BY roundId DESC LIMIT 1", function (err, rows, fields) {
            if (err) { return; }
            drawnNumbers = rows[0].drawnNumbers.split(";");

        numbers.forEach(function (drawnNumber) {
            number = drawnNumber;
        });

        io.sockets.emit('number', number);
    });
}

function newRound() {
    connection.query("SELECT rounds.rows, nights.jackpot, nights.jackpotNumber FROM rounds " + 
        "INNER JOIN nights ON rounds.nightId = nights.nightId " +
        "ORDER BY rounds.roundId DESC LIMIT 1", function (err, rows, fields) {

        if (err) { return; }

        io.sockets.emit('round', rows[0]);
    });
}

io.on('connection', function (socket) {
    socket.on('requestNumbers', function (data) {
        connection.query("SELECT rounds.drawnNumbers FROM " +
            "rounds ORDER BY roundId DESC LIMIT 1", function (err, rows, fields) {

            if(err) { return; }

            var numbers = [];
            var isCollecting = (data === 0) ? true : false;
            drawnNumbers = rows[0].drawnNumbers.split(";");
            drawnNumbers.forEach(function (drawnNumber) {
                if (drawnNumber == data) {
                    isCollecting = true;
                }

                if (isCollecting) {
                    numbers.push(drawnNumber);
                }
            });

            socket.emit('numbers', numbers);
        });
    });

    socket.on("init", function (data) {
        newRound();
    });
});

console.log("Server started on port " + port)