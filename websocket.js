var server = require('http').createServer(handler);
var mysql = require("mysql");
var io = require('socket.io').listen(server);

// variable with all the connected sockets
var connectionsArray = [];

var port = 4000;

var drawnNumbers = [];

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
        gotNumber();
    } else if (req.url == "/newRound") {
        newRound();
    }
    
    res.writeHead(200);
    res.end();
};

function gotNumber() {
    var number = 0;
    connection.query("SELECT number FROM drawing WHERE picked = 1 ORDER BY `when`", function (err, rows, fields) {
        if (err) { return; }

        drawnNumbers = [];
        rows.forEach(function (row) {
            drawnNumbers.push(row['number']);
        });

        var number = drawnNumbers[drawnNumbers.length - 1];

        io.sockets.emit('number', number);
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

            connection.query("SELECT number FROM drawing WHERE picked = 1 ORDER BY `when`", function (err, rows, fields) {
        
        if(err) { return; }
        
        var numbers = [];
        var isCollecting = (data === 0) ? true : false;
        
        rows.forEach(function (row) {
            if (row == data) {
                isCollecting = true;
            }
            
            if (isCollecting) {
                numbers.push(row.number);
            }
        });
        socket.emit('numbers', numbers);
            })
    })
    .on("init", function(data) {
        newRound();
    });
});

console.log("Server started on port " + port)
