// Need JSON2.js for JSON parsing

// Connect to Socket.io: change the url (same as application)
// and port of the Socket.io proxy server
var socket = io.connect('http://localhost:8080');

// On connection
socket.on('connection', function(msg){
  //...
});

/**
 * Proxy callbacks
 *
 * Useful to store callbacks which are related to socket.io
 * obviously this doesn't have to be how its done, just
 * change it below in the Socket.on('message', ...);
 */
socket.proxyCallbacks = {
  broadcastCallback: function() {
    //...
  }
  //...
};

/**
 * Broadcast listener
 *
 * The listener for Socket.io broadcasts. Will execute a
 * callback if callback is sent. Again this is just an
 * example of what you can do.
 */
socket.on('message', function(msg){
  var msg = JSON.parse(msg);
  if(typeof msg === 'object') {
    var callback = this.proxyCallbacks[msg.callback];
    if(typeof callback === 'function') {
      callback.call(msg.response);
    }
  }
});
