<?php

$router = $di->getRouter();

// Define your routes here

  $router->add('/start', array( 
   'controller' => 'twitter', 
   'action' => 'index', 
));

   $router->add('/test', array( 
   'controller' => 'demo', 
   'action' => 'index', 
));

$router->handle();
