<?php
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Router;
error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

try {

    /**
     * The FactoryDefault Dependency Injector automatically registers
     * the services that provide a full stack framework.
     */
    $di = new FactoryDefault();

    /**
     * Handle routes
     */
    include APP_PATH . '/config/router.php';

    /**
     * Read services
     */
    include APP_PATH . '/config/services.php';

    /**
     * Get config service for use in inline setup below
     */
    $config = $di->getConfig();


    /**
     * Include Autoloader
     */
    include APP_PATH . '/config/loader.php';

    /**
     * Include MongoDb Client
     */
    include APP_PATH . '/vendor/autoload.php';

     /**
     * Include FireBase PHP SDK
     */
    include APP_PATH . '/firebase/autoload.php';


  
    $application = new \Phalcon\Mvc\Application($di);

    echo str_replace(["\n","\r","\t"], '', $application->handle()->getContent());


    //Mongo DB connection

 
  

} catch (\Exception $e) {
    echo $e->getMessage() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}
