<?php
	/* Files Required */
	require_once "GoldenGators.class.php";
    require_once "vendor/autoload.php";
    require_once "medoo.php";
    use phpFastCache\CacheManager;

    $cache = CacheManager::getInstance("memcached");
    $cache = CacheManager::getInstance("redis");
    $cache = CacheManager::Files();
    
	/* Errors */
	
	/* Logics */
	
	/* Configurations */
	
    # Information hidden

    /* Caching */

    /* Player Data */
    
?>