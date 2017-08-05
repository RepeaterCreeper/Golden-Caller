<?php
	require_once("config.php");
	
	echo $GCaller->get('pagegen', 'numId', ['id' => 1]);
?>