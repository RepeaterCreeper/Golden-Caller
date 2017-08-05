<?php
	require_once "config.php";

	$GoldenGators = new ClashofClans;
	$GoldenGators->refreshData();

	$GoldenData->query("TRUNCATE kingdomplayerleaderboard");
	$i = 0;
	foreach ($GoldenGators->kingdom as $kingdom => $kingdomTag) {
		foreach ($GoldenGators->getClan($kingdomTag, true) as $player) {
			$GoldenData->insert("kingdomplayerleaderboard", [
				"tag" 			=> $player->tag,
				"name"			=> $player->name,
				"clan"			=> $kingdomTag,
				"clanBadge"		=> $GoldenGators->getClan($kingdomTag)->badge,
				"donation"		=> $player->donations,
				"donationReceived" => $player->donationsReceived,
				"donationRatio" => ClashUtils::donationRatio($player->donations, $player->donationsReceived),
				"trophy"		=> $player->trophies
			]);
		}
	}
?>