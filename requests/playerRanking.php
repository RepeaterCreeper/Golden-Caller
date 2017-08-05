<?php
	require_once "config.php";
	$ranks = ["trophyRank", "donationRank", "ratioRank"];
	foreach ($ranks as $rank) {
		$i = 1;
		$order;
		switch ($rank) {
			case "trophyRank": $order = "trophy"; break;
			case "donationRank": $order = "donation"; break;
			case "ratioRank": $order = "ratioRank"; break;
		}
		foreach ($GoldenData->select("kingdomplayerleaderboard", "*", ["ORDER" => "$order DESC"]) as $leaderboard) {
			$GoldenData->update("kingdomplayerleaderboard", [
				$rank 	=> $i
			], ["tag" => $leaderboard['tag']]);
			$i++;
		}
	}
?>