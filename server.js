var http = require('http')
    , url = require('url')
    , io = require('socket.io').listen(parseInt(process.argv[2]));

var serialize = function(obj, prefix) {
  var str = [];
  for(var p in obj) {
    var k = prefix ? prefix + "[" + p + "]" : p, v = obj[p];
    str.push(typeof v == "object" ?
      serialize(v, k) :
      encodeURIComponent(k) + "=" + encodeURIComponent(v));
  }
  return str.join("&");
}

// socket.io
io.configure(function (){
  io.set('authorization', function (handshakeData, callback) {
    callback(null, true); // error first callback style
  });
});

io.sockets.on('connection', function(socket){

  // Proxy request
  socket.on('proxy', function(settings, callback){
    var header
        , host = settings.host ? settings.host : socket.handshake.headers.host.replace(/\:\d+$/, '')
        , cookie = socket.handshake.headers.cookie
        , path = typeof settings.path === 'string' ? settings.path : '/'
        , type = typeof settings.type === 'string' ? settings.type.toUpperCase() : 'GET'
        , data = settings.data ? settings.data : ''
        , contentType = settings.contentType ? settings.contentType : 'application/x-www-form-urlencoded';

    if(typeof data !== 'string') {
      data = typeof data === 'object' ? serialize(data) : '';
    }

    // Build headers
    headers = {
      'host': host 
      , 'Content-Length': data.length
      , 'Content-Type': contentType
      , 'Cookie': cookie
    };

    // Create proxy request
    var request = http.createClient(80, host).request(type, path, headers);

    // On the response
    request.on('response', function (response) {
      if(response.statusCode == 200) {
        response.setEncoding('utf8');
        response.on('data', function (chunk) {
          var result = JSON.parse(chunk)
              , command = {}
              , message = {};
              
          if(typeof result.command === 'object') {
            command = result.command;
          }
          
          if(result.message !== undefined && result.message !== null) {
            message = result.message;
          } 
          
          // Send callback response
          if(typeof callback === 'function') {
            callback(message);
          }

          /** Execute commands **/
          // Broadcast message
          if(command.broadcast && command.broadcast.send === true) {
            var callback = 'broadcastCallback';
                
            if(typeof command.broadcast.callback === 'string') {
              callback = command.broadcast.callback;
            }
            
            if(command.broadcast.message !== undefined && command.broadcast.message !== null) {
              message = command.broadcast.message;
            }
            
            socket.json.broadcast.send(
              JSON.stringify({
                callback: callback,
                message: message
              })
            );
          }
        });
      }
    });

    request.write(data);
    request.end();
  });
});
