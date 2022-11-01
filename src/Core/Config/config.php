<?php

// Definindo Timezone e Memory Limit
ini_set('memory_limit', '8M');
date_default_timezone_set('America/Recife');
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'portuguese');

define('GTG_VERSION', '1.3.9');

// Carregando Arquivos
require_once(realpath(dirname(__FILE__) . '/monolog.php'));
require_once(realpath(dirname(__FILE__) . '/date_utils.php'));
require_once(realpath(dirname(__FILE__) . '/utils.php'));