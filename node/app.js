// getting reference to http module, socket.io module and redis module
var http = require('http'), 
io = require('socket.io'), 
redis = require('redis'), 

// create redis client
rc = redis.createClient();

// create http server to listen on for incoming socket connection requests
server = http.createServer(function(req, res){
    res.writeHead(200, {
        'Content-Type': 'text/html'
    }); 
    res.end('<h1>Connected</h1>');
});

// server is listening on port 8000
server.listen(8000);

// socket io is listening on the server
var socketio = io.listen(server);

// console that server is listening
console.log('server is running on port: 8000');

// subscribe with redis on the chat channel
rc.on("connect", function() {
    console.log('rc is connected');
    rc.subscribe("chat");
});

// when a new message comes on the chat channel console.log it
rc.on("message", function (channel, message) {
    console.log("Message: " + message);
})