<?php
// DEBUG mode
$DEBUG = True;

// BASE info
$BASE_URI = 'http://localhost:8000/';
$BASE_DIR = __DIR__ . '/';
$DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];
$REQ_URI = $_SERVER['REQUEST_URI'];
$METHOD = $_SERVER['REQUEST_METHOD'];

// PHP files directories
$TEMP = $BASE_DIR . 'php/templates/';
$CLASS = $BASE_DIR . 'php/classes/';
$INC = $BASE_DIR . 'php/includes/';

// STATIC files directories
$CSS = $BASE_URI . 'assets/css/';
$JS = $BASE_URI . 'assets/js/';
$IMG = $BASE_URI . 'assets/images/';

// DATABASE Connection detailes based on $DEBUG mode
$DB = ($DEBUG) ? ['host' => 'localhost', 'user' => 'root', 'pass' => '', 'name' => 'logger'] : ['host' => 'localhost', 'user' => 'root', 'pass' => '', 'name' => 'logger'];

// For direct access redirects:
if ($_SERVER['SCRIPT_NAME'] == '/config.php') header("Location: " . $BASE_URI);


// INCLUDE required files
require $CLASS . 'dbh.class.php';
require $CLASS . 'users.class.php';
require $CLASS . 'token.class.php';
require $CLASS . 'logger.class.php';