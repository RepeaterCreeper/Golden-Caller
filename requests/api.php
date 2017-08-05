<?php
	/*require_once($_SERVER["DOCUMENT_ROOT"] . "/assets/class/goldendata.php");
	require_once($_SERVER["DOCUMENT_ROOT"] . "/assets/class/goldengators.php");*/
	
	require_once("config.php");
	
	$GoldenGators = new ClashofClans;
	session_start();
	/**
	 * Player Action
	 */

	/**
	 * Handles deleting calls
	 *
	 * @var DELETE
	 */
	if (isset($_GET["delete"])) {
		if (($_POST['id'] != $_SESSION['userTag']) && (!in_array($_SESSION['userTag'], $GoldenGators->admins))) {
			if ($GoldenData->get('miniacc', "*", ["AND" => ["ownerTag" => $_SESSION['userTag'], "playerTag" => $_POST['id']]])) {
				$GCaller->delete("calls", ["AND" => ["status" => $_POST['status'], "playerTag" => $_POST['id'], "callNum" => $_POST['num']]]);
				$GCaller->update("pagegen", ["numId[+]" => 1], ['id' => 1]);
			} else {
				echo "001";
			}
		} else {
			$GCaller->delete("calls", ["AND" => ["status" => $_POST['status'], "playerTag" => $_POST['id'], "callNum" => $_POST['num']]]);
			$GCaller->update("pagegen", ["numId[+]" => 1], ['id' => 1]);
		}
	}

	/**
	 * Handles adding calls
	 */
	if (isset($_GET["add"])) {
		if (!in_array($_SESSION['userTag'], $GoldenGators->admins)) {
			if (($GCaller->count("calls", ["AND" => ["warTag" => $GoldenGators->getWar("enemyTag"), "status" => 'ACTIVE', "playerTag" => $_SESSION['userTag']]])) <= 1) {
				if ($GCaller->count("calls", ["AND" => ["warTag" => $GoldenGators->getWar("enemyTag"), "stars[!]" => 9, "playerTag" => $_POST['playerTag'], "callNum" => $_POST['num']]]) + ($GCaller->count("calls", ["AND" => ["warTag" => $GoldenGators->getWar("enemyTag"), "status" => "ACTIVE", "playerTag" => $_POST['playerTag'], "callNum" => $_POST['num']]])) != 1) {
					$expTime = null;
					if ($GCaller->get("warlog", "status", ["enemyTag" => $GoldenGators->getWar("enemyTag")]) == 'prep') {
						$n = $GoldenGators->getWar("preparationDay");
						$timer = $GoldenGators->getWar("timer");
						$a = strtotime($timer) - strtotime("now");
						$b = strtotime("{$n}") - strtotime("now");
						$c = $a + $b;
						$expTime = strtotime("+{$c} seconds");
					} else {
						$expTime = (strtotime($GoldenGators->getWar("timer")));
					}

					$GCaller->insert("calls", [
						"username"		=> $_POST["name"],
						"playerTag"		=> $_POST["playerTag"],
						"callNum"		=> $_POST['num'],
						"warTag"		=> $GoldenGators->getWar("enemyTag"),
						"expiration"	=> date("Y-m-d H:i:s", $expTime)
					]);
					
					$resVisual = null;
					foreach ($GCaller->select('calls', '*', ['AND' => ['warTag' => $GoldenGators->getWar("enemyTag"), 'playerTag' => $_POST['playerTag'], 'callNum' => $_POST['num']]]) as $player) {
						global $resVisual;
					    $t = ClashUtils::getExpiration($_POST['playerTag'], $_POST['num']);
					    $expTime = sprintf('%02dH %02dM', ($t / 3600), ($t / 60 % 60));
					    $expRes = (($t / 3600) + ($t / 60 % 60)) > 0 ? $expTime : 0;

			        	$resVisual = "<label class='label label-danger'>$expRes</label>";

			            $note = strlen($player['notes']) > 0 ? "<i class='fa fa-file'></i>" : null;
			            $permission = strlen($player['permission']) > 0 ? "<i class='fa fa-warning'></i>" : null;
			               
			            echo "<button class='btn btn-primary' data-uid={$player['playerTag']} onclick=\"loadPlayer('{$player['playerTag']}', '{$player['status']}', {$_POST['num']})\">" . $player['username'] . " {$note} {$permission}<br>{$resVisual}</button>";
		        	}
					echo '<span id="status">success</span>';
					echo "<span id='result'><label class='label label-danger'>{$expTime}</label></span>";
					$GCaller->update("pagegen", ["numId[+]" => 1], ['id' => 1]);
				} else {
					echo '<span id="status">error</span>';
					echo "<span id='result'>Error: You already have a call for this number!</span>";
				}
			} else {
				echo '<span id="status">error</span>';
				echo "<span id='result'>Error: You already have 2 entry!</span>";
			}
		} else {
			//UNLIMITED CALL
			$expTime = null;
			if ($GCaller->get("warlog", "status", ["enemyTag" => $GoldenGators->getWar("enemyTag")]) == 'prep') {
				$n = $GoldenGators->getWar("preparationDay");
				$timer = $GoldenGators->getWar("timer");
				$a = strtotime($timer) - strtotime("now");
				$b = strtotime("{$n}") - strtotime("now");
				$c = $a + $b;
				$expTime = strtotime("+{$c} seconds");
			} else {
				$expTime = (strtotime($GoldenGators->getWar("timer")));
			}

			$GCaller->insert("calls", [
				"username"		=> $_POST["name"],
				"playerTag"		=> $_POST["playerTag"],
				"callNum"		=> $_POST['num'],
				"warTag"		=> $GoldenGators->getWar("enemyTag"),
				"expiration"	=> date("Y-m-d H:i:s", $expTime)
			]);
			
			$resVisual = null;
			foreach ($GCaller->select('calls', '*', ['AND' => ['warTag' => $GoldenGators->getWar("enemyTag"), 'playerTag' => $_POST['playerTag'], 'callNum' => $_POST['num']]]) as $player) {
				global $resVisual;
	        	date_default_timezone_get("America/New York");
			    $globalStart = strtotime('now');
			    $t = ClashUtils::getExpiration($_POST['playerTag'], $_POST['num']);
			    $expTime = sprintf('%02dH %02dM', ($t / 3600), ($t / 60 % 60));
			    $expRes = (($t / 3600) + ($t / 60 % 60)) > 0 ? $expTime : 0;

	        	switch($player['stars']) {
	        		case 0: $resVisual = "<img src='assets/img/0star.png'/>"; break;
	        		case 1: $resVisual = "<img src='assets/img/1star.png'/>"; break;
	        		case 2: $resVisual = "<img src='assets/img/2star.png'/>"; break;
	        		case 3: $resVisual = "<img src='assets/img/3star.png'/>"; break;
	        		default: $resVisual = "<label class='label label-danger'>$expRes</label>"; break;
	        	}
	            $note = strlen($player['notes']) > 0 ? "<i class='fa fa-file'></i>" : null;
	            $permission = strlen($player['permission']) > 0 ? "<i class='fa fa-warning'></i>" : null;
	            if ($player['status'] == 'EXPIRED') {
	                echo "<button class='btn btn-danger disabled' data-uid={$player['playerTag']} onclick=\"loadPlayer('{$player['playerTag']}', '{$player['status']}', {$_POST['num']})\">" . $player['username'] . " {$note} {$permission} <br>{$resVisual}</button>";
	            } else {
	                echo "<button class='btn btn-primary' data-uid={$player['playerTag']} onclick=\"loadPlayer('{$player['playerTag']}', '{$player['status']}', {$_POST['num']})\">" . $player['username'] . " {$note} {$permission}<br>{$resVisual}</button>";
	            }
	    	}
			echo '<span id="status">success</span>';
			echo "<span id='result'>{$resVisual}</span>";
			$GCaller->update("pagegen", ["numId[+]" => 1], ['id' => 1]);
		}
	}

	/**
	 * Save notes and permission
	 */
	if (isset($_GET["setPerm"])) {
		$perms = htmlentities($_POST['permission']);
		if (($_SESSION['userTag'] != $_POST['id']) && (!in_array($_SESSION['userTag'], $GoldenGators->admins)) && ($GoldenData->count('miniacc', ["AND" => ["ownerTag" => $_SESSION['userTag'], "playerTag" => $_POST['id']]]) == 0)) {
			echo "ERROR: Invalid Permission!";
		} else {
			$GCaller->update("calls", ["permission" => $perms], ["AND" => ["playerTag" => $_POST['id'], "callNum" => $_POST['num']]]);
			$GCaller->update("pagegen", ["numId[+]" => 1], ['id' => 1]);
			echo "success";
		}
	} elseif (isset($_GET["setNote"])) {
		$notes = htmlentities($_POST['note']);
		if ($notes != $GCaller->get("calls", "notes", ["AND" => ["playerTag" => $_POST['id'], "callNum" => $_POST['num']]])) {
			if (($_SESSION['userTag'] != $_POST['id']) && (!in_array($_SESSION['userTag'], $GoldenGators->admins)) && ($GoldenData->count('miniacc', ["AND" => ["ownerTag" => $_SESSION['userTag'], "playerTag" => $_POST['id']]]) == 0)) {
				echo "ERROR: Invalid Permission!";
			} else {
				$GCaller->update("calls", ["notes" => $notes], ["AND" => ["playerTag" => $_POST['id'], "callNum" => $_POST['num']]]);
				$GCaller->update("pagegen", ["numId[+]" => 1], ['id' => 1]);
				echo "success";
			}
		}
	}

	/**
	 * Handles star results set by user
	 */
	if (isset($_GET["setResult"])) {
		if (($_SESSION['userTag'] != $_POST['pID']) && (!in_array($_SESSION['userTag'], $GoldenGators->admins)) && ($GoldenData->count('miniacc', ["AND" => ["ownerTag" => $_SESSION['userTag'], "playerTag" => $_POST['pID']]]) == 0)) {
			alert("ERROR: Invalid Permissions!");
		} else {
			$GCaller->update("calls", ["stars" => $_POST['res']], ["AND" => ["playerTag" => $_POST['pID'], "callNum" => $_POST['call']]]);

			$GCaller->update("pagegen", ["numId[+]" => 1], ['id' => 1]);
			echo "success";
		}
	}
	/**
	 * Designates Townhall
	 */
	if (isset($_GET["designate"])) {
		if ((in_array($_SESSION['userTag'], $GoldenGators->admins)) && ($GCaller->count("calltarget", ["callNum" => $_POST['cn']]) <= 0)) {
			$warTag = $GCaller->get("warlog", "enemyTag", ["status[!]" => 'complete']);
			$callNum = $_POST['cn'];
			$townhall = $_POST['townhall'];
			$GCaller->insert("calltarget", [
				"warTag" =>  $warTag,
				"callNum" => $callNum,
				"townhall" => $townhall
			]);
		} else {
			$GCaller->update("calltarget", [
				"warTag"	=> $GCaller->get("warlog", "enemyTag", ["status[!]" => 'complete']),
				"callNum"	=> $_POST['cn'],
				"townhall"	=> $_POST['townhall']
			], ["callNum" => $_POST['cn']]);
		}
	}

	
	/**
	 *	Server Action
	 */
	if (isset($_GET["checkUpdates"])) {
		echo $GCaller->get('pagegen', 'numId', ['id' => 1]);
	}

	if (isset($_GET["newData"])) {
		$res = json_encode($GCaller->select("calls", "*", ["warTag" => $GoldenGators->getWar("enemyTag"), "ORDER" => 'status ASC']));

		$new_value = $res;

		if (isset($_SESSION['callerUpdate']) && $_SESSION['callerUpdate'] != $new_value) {
			echo $res;
			
		}

		$_SESSION['callerUpdate'] = $new_value;
	}
?>