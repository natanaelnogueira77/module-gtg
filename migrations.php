<?php 

require_once __DIR__ . '/vendor/autoload.php';

use Src\Program;
Program::applyMigrations(isset($argv) && isset($argv[1]) ? $argv[1] : null);