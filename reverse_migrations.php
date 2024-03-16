<?php 

require_once __DIR__ . '/vendor/autoload.php';

use Src\Program;
Program::reverseMigrations(isset($argv) && isset($argv[1]) ? $argv[1] : null);