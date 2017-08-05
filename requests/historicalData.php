<?php
	require_once "config.php";

	$GoldenGators = new ClashofClans;
	$GoldenGators->refreshData();
	

	foreach ($GoldenGators->getPlayers() as $player) {
		$GoldenData->insert('history', [
			"playerTag"		=> $player->tag,
			"username" 		=> $player->name,
			"trophy" 		=> $player->trophies,
			"donations"		=> $player->donations,
			"donationsReceived" => $player->donationsReceived,
			"date" => date('Y-m-d')
		]);
	}
?>