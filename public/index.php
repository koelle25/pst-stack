<?php

//Application Bootstrap file
require dirname(__DIR__) . DIRECTORY_SEPARATOR.'bootstrap/bootstrap.php';

//All routes
require dirname(__DIR__).DIRECTORY_SEPARATOR.'routes/routes.php';

//Run the application
$app->run();