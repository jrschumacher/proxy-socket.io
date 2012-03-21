
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>PHP Socket.io Demo</title>
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le styles -->
    <link rel="stylesheet" href="http://twitter.github.com/bootstrap/1.4.0/bootstrap.min.css">
    <style type="text/css">
      /* Override some defaults */
      html, body {
        background-color: #eee;
      }
      body {
        padding-top: 40px; /* 40px to make the container go all the way to the bottom of the topbar */
      }
      .container > footer p {
        text-align: center; /* center align it with the container */
      }
      .container {
        width: 820px; /* downsize our container to make the content feel a bit tighter and more cohesive. NOTE: this removes two full columns from the grid, meaning you only go to 14 columns and not 16. */
      }

      /* The white background content wrapper */
      .content {
        background-color: #fff;
        padding: 20px;
        margin: 0 -20px; /* negative indent the amount of the padding to maintain the grid system */
        -webkit-border-radius: 0 0 6px 6px;
           -moz-border-radius: 0 0 6px 6px;
                border-radius: 0 0 6px 6px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.15);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.15);
                box-shadow: 0 1px 2px rgba(0,0,0,.15);
      }

      /* Page header tweaks */
      .page-header {
        background-color: #f5f5f5;
        padding: 20px 20px 10px;
        margin: -20px -20px 20px;
      }

      /* Styles you shouldn't keep as they are for displaying this base example only */
      .content .span10,
      .content .span4 {
        min-height: 500px;
      }
      /* Give a quick and non-cross-browser friendly divider */
      .content .span4 {
        margin-left: 0;
        padding-left: 19px;
        border-left: 1px solid #eee;
      }

      .topbar .btn {
        border: 0;
      }

    </style>

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="images/favicon.ico">
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png">
  </head>

  <body>

    <div class="topbar">
      <div class="fill">
        <div class="container">
          <a class="brand" href="#">PHP - Socket.io Demo</a>
          <ul class="nav">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
          </ul>
        </div>
      </div>
    </div>

    <div class="container">

      <div class="content">
        <div class="page-header">
          <div class="alert-message warning">
            <p><strong>Connecting to Server</strong> We are connecting you to the server...</p>
          </div>
          <h1>PHP - Socket.io Demo <small>Supporting text or tagline</small></h1>
        </div>
        <div class="row">
          <div class="span10">
            <h2>Response Content</h2>
            <pre id="response-content"></pre>
          </div>
          <div id="actions" class="span4">
            <h3>Actions</h3>
            <a id="api-call-1" class="btn disabled">API call #1</a><br><br>
            <a id="api-call-2" class="btn disabled">API call #2</a><br><br>
            <a id="api-call-3" class="btn disabled">API call #3</a><br><br>
            <a id="api-call-4" class="btn disabled">API call #4</a>
          </div>
        </div>
      </div>

      <footer>
        <p>&copy; Company 2011</p>
      </footer>

    </div> <!-- /container -->
    <script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
    <script src="js/json2.js"></script>
    <script src="//localhost:8080/socket.io/socket.io.js" type="text/javascript"></script>
    <script type="text/javascript">
      (function($){
        var connected_timmer = null;
        
        $('.page-header .alert-message .close').live('click', function(){
          connected_timmer = null;
          $(this).parent().slideUp();
        });
        
        $(document).ready(function(){
          // Start the socket
          var socket = io.connect('http://localhost:8080');
          socket.on('connect', function(msg){
            $('.page-header .alert-message').fadeOut(function(){
              $(this).removeClass('warning').addClass('success').html('<a class="close" href="#">×</a><strong>Connected!</strong> Enjoy').fadeIn(function(){
                $('#actions .btn.disabled').removeClass('disabled');
                var $scope = $(this);
                connected_timmer = setTimeout(function(){
                  $scope.slideUp();
                }, 1500);
              });
            });
          });
          
          socket.proxyCallbacks = {
            broadcastCallback: function() {
              $('#response-content').text('Broadcast Recieved:\n' + JSON.stringify(this));
            }
          };
          
          socket.on('message', function(response){
            var response = $.parseJSON(response);
            console.log(response);
            if(typeof response === 'object') {
              var callback = this.proxyCallbacks[response.callback];
              if(typeof callback === 'function') {
                callback.call(response.message);
              }
            }
          });
          
          $('#api-call-1').click(function(){
            $('#response-content').text('Emit proxy message...\n');
            socket.emit('proxy', {
              host: '38pages.ahoyyoha.com',
              path: '/proxy-socket.io/demo/php/app.php/api/do-this'
            });
          });
          
          $('#api-call-2').click(function(){
            $('#response-content').text('Emit proxy message...\n');
            socket.emit('proxy', {
              host: '38pages.ahoyyoha.com',
              path: '/proxy-socket.io/demo/php/app.php/api/do-this'
            }, function(response) {
              $('#response-content').text('Emit Response Recieved:\n' + JSON.stringify(response));
            });
          });
        });
      })(jQuery);
    </script>

  </body>
</html>
