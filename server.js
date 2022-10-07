

const express = require('express');
const app = express();

const server = require('http').createServer(app);


const io = require('socket.io')(server, {
    cors: { origin: "*"}
});


const Redis = require("ioredis");
const redis = new Redis();

redis.subscribe('sendChatToServer', function() {
    console.log('subscribed to sendChatToServer');
});



io.on('connection', (socket) => {
    console.log('connection');

    socket.on('sendChatToServer', (message) => {
        console.log(message);
        // io.sockets.emit('sendChatToClient', message);
        socket.broadcast.emit('sendChatToClient', message);

        const axios = require('axios');

        const res = axios.get('./messageUrl').then(function (response){
            console.log(response.status);

        });

    });


    socket.on('disconnect', (socket) => {
        console.log(socket.id);
    });
});

server.listen(3000, () => {
    console.log('Server is running');
});

