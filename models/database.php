<?php
ini_set('display_errors', 1); 
error_reporting(-1); 

require_once 'vendor/autoload.php';

$cfg = ActiveRecord\Config::instance();
$cfg->set_connections([
    'development' => 'pgsql://postgres:m7u1f8a9@192.168.0.3/gept',
#    'test' => 'mysql://username:password@localhost/test_database_name',
#    'production' => 'mysql://username:password@localhost/production_database_name'
]);
$cfg->set_default_connection('development'); 



