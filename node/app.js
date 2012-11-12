// getting reference to http module, socket.io module and redis module
var http = require('http'), 
io = require('socket.io'), 
redis = require('redis'), 

// create redis client
rc = redis.createClient();
redisClient = redis.createClient();

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

// opened sockets variable
var sockets = {};

// connect and store user socket with his username
socketio.on('connection', function(socket){
    socket.on('presence online', function (userId) {
        console.log('user ' + userId + ' is connected');
        sockets[userId] = socket;
    });
});

// subscribe with redis on the chat channel
rc.on("connect", function() {
    console.log('rc is connected');
    rc.subscribe("chat");
});

// when a new message comes on the chat channel console.log it
rc.on("message", function (channel, message) {
    console.log("message id " + message);
    message_id = 'message:' + message;
    redisClient.hgetall(message_id, function(err, reply){
        message = reply;
        thread_id = 'thread:' + message.thread_id + ':users';
        user_id = 'user:' + message.sender_id;
        redisClient.hgetall(user_id, function(err, reply){
            user = reply;
            messageObject = {
                senderName: user.name,
                senderAvatar: user.avatar,
                body: message.body,
                threadId: message.thread_id,
                time: message.time
            };
            console.log("message is " + messageObject);
            redisClient.smembers(thread_id, function(err, reply){
                users = reply;
                console.log("users are " + users);
                for(i = 0, count = users.length; i < count; i++){
                    if(users[i] != message.sender_id && users[i] in sockets){
                        console.log(users[i]);
                        sockets[users[i]].emit("message", messageObject);
                    }
                }
            });
        });    
    });
});