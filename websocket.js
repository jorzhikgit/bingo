var server = require('http').createServer(handler);
var mysql = require("mysql");
var io = require('socket.io').listen(server);

// variable with all the connected sockets
var connectionsArray = [];

var port = 4000;

var POLLING_INTERVAL = 5000;
var pollingTimer;

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
    connection.query("SELECT number FROM drawing WHERE picked = 1 ORDER BY `when`", function (err, rows, fields) {
        if (err) { return; }

        drawnNumbers = rows[0].drawnNumbers.split(";");
	
        numbers.forEach(function (drawnNumber) {
            number = drawnNumber;
        });

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

var pollingLoop = function() {
    var query = connection.query("SELECT number FROM drawing WHERE picked = 1 ORDER BY `when`");

    numbers = [];
    
    query
	.on('error', function(err) {
	    console.log(err);
	    updateSockets(err);
	})
	.on('result', function(number) {
	    numbers.push(number.number);
	})
	.on('end', function() {
	    if (connectionsArray.length) {
		pollingTimer = setTimeout(pollingLoop, POLLING_INTERVAL);
		updateSockets({numbers:numbers});
	    }
	});
};

var updateSockets = function(data) {
    connectionsArray.forEach(function(tmpSocket) {
	console.log("duh");
	//tmpSocket.volatile.emit('numbers', data.numbers);
    });
};

io.sockets.on('connection', function (socket) {

    if (!connectionsArray.length) {
	pollingLoop();
    }

    socket
	.on('disconnect', function() {
	    var socketIndex = connectionsArray.indexOf(socket);
	    if (socketIndex >= 0) {
		connectionsArray.splice(socketIndex, 1);
	    }
	})
	.on('requestNumbers', function (data) {

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
    
    connectionsArray.push(socket);

});

console.log("Server started on port " + port)
