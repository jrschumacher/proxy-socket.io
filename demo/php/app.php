<?php

  require_once('lib/uvic.php');
  
  use MiMViC as app;
  
  app\get('/', function() {
    
    app\render('views/main.php');
    
  });
  
  app\get('/api/do-this', function(){
    $result = array(
      'command' => array(
        'broadcast' => array(
          'send' => true,
          'callback' => 'broadcastCallback'
        )
      ),
      'message' => array(
        'a' => 1,
        'b' => array(1, 2, 3),
        'c' => 'string'
      )
    );
    print json_encode($result);
  });
  
  app\start();

?>