<?php

$router = $di->getRouter();

// Define your routes here
$router->add('/login', array( 
   'controller' => 'users', 
   'action' => 'login', 
));
  $router->add('/twitter', array( 
   'controller' => 'twitter', 
   'action' => 'index', 
));

   $router->add('/test', array( 
   'controller' => 'demo', 
   'action' => 'index', 
));

$router->handle();
