<?php 

$this->layout("layouts/main", ['layout' => $layout]); 

$this->insert('widgets/sections/reset-password', ['widget' => $resetPasswordSection]);

$this->start('scripts'); 
$this->insert('scripts/sections/reset-password.js', ['widget' => $resetPasswordSection]);
$this->end();