<?php

ini_set('display_errors', 0);
ini_set('display_startup_errors', 1);
ini_set('ignore_repeated_source', true);
ini_set('log_errors', true);
error_reporting( E_ALL );

set_error_handler(array('Src\Core\Exceptions\ErrorHandler', 'control'), E_ALL);
register_shutdown_function(array('Src\Core\Exceptions\ErrorHandler', 'shutdown'));