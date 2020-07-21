<?php

use App\LogService;

require_once __DIR__ . '/../vendor/autoload.php';

$message = LogService::createFile()->generateData()->writeFile();
var_dump($message);