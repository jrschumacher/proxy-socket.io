# Proxy-Socket.IO

Proxy-Socket.IO is a Node.JS server that makes WebSockets and realtime possible for non Node.JS applications throught the use of this simple Socket.IO server. It is pretty simple right now...

## How to Install

    cd ./proxy-socket.io; npm install;

## How to use

First, start proxy-socket.io server

```
node server.js 8080
```

Then send some commands via client side code:

```html
<script src="http://localhost:8080/socket.io.js"></script>
<script>
  var socket = io.connect('http://localhost:8080');
  socket.on('connect', function(){
    socket.emit('proxy', {
      host: 'localhost',
      path: '/proxy-socket.io/demo/php/app.php/api/do-this'
    }, function(response) {
      console.log(response);
    });
  });
</script>
```

The proxy-socket.io will act as a websockt proxy to your application server and pushing JSON messages back and forth.

For more thorough examples, look at the `demos/` directory.

## License 

(The MIT License)

Copyright (c) 2012 Ryan Schumacher &lt;ryan@38pages.com&gt;

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
'Software'), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED 'AS IS', WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.