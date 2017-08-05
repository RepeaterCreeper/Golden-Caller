<?php
	/* Files Required */
	require_once "GoldenGators.class.php";
    require_once "vendor/autoload.php";
    require_once "medoo.php";
    
	/* Errors */
	
	/* Logics */
	
	/* Configurations */
	$GoldenData = new medoo([
        'database_type' => 'mysql',
        'database_name' => 'goldengators',
        'server' => 'localhost',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8'
    ]);

    $GCaller = new medoo([
        'database_type' => 'mysql',
        'database_name' => 'goldengators',
        'server' => 'localhost',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8'
    ]);

?>