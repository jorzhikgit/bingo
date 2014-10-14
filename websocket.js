var server = require('http').createServer(handler);
var mysql = require("mysql");
var io = require('socket.io').listen(server);

var port = 4000;

var connection = mysql.createConnection({
    host: 'localhost',
    user: 'bingo',
    password: 'bingoradioen',
    database: 'bingo'
});

connection.connect();
server.listen(port);

function handler(req, res) {
    if (req.url == "/newNumber") {
        sendNumber();
    } else if (req.url == "/newRound") {
        newRound();
    }
    
    res.writeHead(200);
    res.end();
};

function sendNumber() {
    var number = 0;
    connection.query("SELECT number FROM drawing WHERE " +
        "drawing.timestamp IS NOT NULL ORDER BY `timestamp`", function (err, rows, fields) {
        if (err) { return; }

        numbers = [];
        rows.forEach(function (row) {
            numbers.push(row.number);
        });

        io.sockets.emit('numbers', numbers);
    });
}

function newRound() {
    var sql = "SELECT rounds.rows, g.jackpot, g.jackpot_number FROM rounds " + 
    "JOIN games g ON rounds.game = g.id " +
    "ORDER BY rounds.id DESC LIMIT 1"
    connection.query(sql, function (err, rows, fields) {
    
    if (err) { return; }
   
    io.sockets.emit('round', rows[0]);
    
    });
}

io.sockets.on('connection', function (socket) {
    socket.on('requestNumbers', function (data) {
        sendNumber();
    })
    .on("init", function(data) {
        newRound();
    });
});

console.log("Server started on port " + port)
