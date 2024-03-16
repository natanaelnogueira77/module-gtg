<?php 

$this->layout("layouts/main", ['layout' => $layout]); 

$this->insert('widgets/sections/login-form', ['widget' => $loginFormSection]);

$this->start('scripts'); 
$this->insert('scripts/sections/login-form.js', ['widget' => $loginFormSection]);
$this->end();