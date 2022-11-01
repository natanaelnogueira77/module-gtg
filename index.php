<?php

if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'on' && $_SERVER['HTTP_HOST'] != 'localhost') {
    header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit();
}

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/src/Core/Config/config.php';
require __DIR__ . '/src/routes.php';