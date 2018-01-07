<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    [
        $config->application->controllersDir,
        $config->application->modelsDir,
        $config->application->libraryDir
    ]
)->register();

$loader->registerNamespaces(
    array(
       "TwitterApi"    => 'app/library',
       "TwitterSearchApi"    => 'app/library',
       'Phalcon' => 'app/incubator/Library/Phalcon/'

      
    )
);

$loader->register();
