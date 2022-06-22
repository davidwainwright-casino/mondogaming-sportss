var express = require('express');  
var app = express();
var fs = require('fs'); 

var options = {
    key: fs.readFileSync('/etc/letsencrypt/live/cricmarkets.com/privkey.pem'),
    cert: fs.readFileSync('/etc/letsencrypt/live/cricmarkets.com/fullchain.pem'),
    ca: fs.readFileSync('/etc/letsencrypt/live/cricmarkets.com/chain.pem')
};

var https = require('https');
var server = https.createServer(options, app);
 
//var server = require('https').createServer(app);  
var io = require('socket.io')(server);

app.get('/broadcast_changes',function(req,res,next){
    io.emit('data_updated',{status:'success'});
    next();
});

app.use(express.static(__dirname + '/node_modules'));  

app.get('/broadcast_changes',function(req,res){
	res.sendFile(__dirname + '/broadcast.html');
});


app.get('/', function(req, res,next) {  
    res.sendFile(__dirname + '/index.html');
});


io.on('connection', function(client) {
    console.log('Client connected...');
    client.on('join', function(data) {
        client.on('messages', function(data) {
            client.emit('broad', data);
            client.broadcast.emit('broad',data);
        });
    });
});

server.listen(4200);
