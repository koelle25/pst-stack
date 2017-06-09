<?php

#Database Configuration
$serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
$serviceContainer->checkVersion('2.0.0-dev');
$serviceContainer->setAdapterClass($conf['app.namespace'], 'mysql');
$manager = new \Propel\Runtime\Connection\ConnectionManagerSingle();
$manager->setConfiguration([
    'dsn' => 'mysql:host=' . $conf['app.db.host'] . ';port=' . $conf['app.db.port'] . ';dbname=' . $conf['app.db.name'],
    'user' => $conf['app.db.username'],
    'password' => $conf['app.db.password'],
    'settings' =>
        [
            'charset' => $conf['app.db.charset'],
            'queries' => [],
        ],
    'classname' => '\\Propel\\Runtime\\Connection\\ConnectionWrapper',
]);
$manager->setName($conf['app.namespace']);
$serviceContainer->setConnectionManager($conf['app.namespace'], $manager);
$serviceContainer->setDefaultDatasource($conf['app.namespace']);
# End of Propel Database