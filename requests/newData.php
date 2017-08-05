<?php
	require_once("config.php");
	session_start();

	$GoldenGators = new ClashofClans;
	$res = json_encode($GCaller->select("calls", "*", ["warTag" => $GoldenGators->getWar('enemyTag'), "ORDER" => 'status ASC']));

	$new_value = $res;

	if (isset($_SESSION['callerUpdate']) && $_SESSION['callerUpdate'] != $new_value) {
		echo $res;
		
	}

	$_SESSION['callerUpdate'] = $new_value;
?>