<?php

#session start
session_start();

#dependencies included via composer autoload
require '../vendor/autoload.php';

#load config
$conf = \Noodlehaus\Config::load(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'settings.php');

#encoding
require 'encoding.php';

#timezone
require 'timezone.php';

#Database Configuration
require 'database.php';

#SLIM instantiate
require 'app.php';