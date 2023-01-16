<?php

setlocale( LC_ALL, 'pt_BR.utf-8', 'pt_BR', 'Portuguese_Brazil');
date_default_timezone_set('America/Recife');

define('GTG_VERSION', '1.4.0');

require_once(realpath(dirname(__FILE__) . '/monolog.php'));
require_once(realpath(dirname(__FILE__) . '/date_utils.php'));
require_once(realpath(dirname(__FILE__) . '/utils.php'));