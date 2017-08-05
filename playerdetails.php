<?php
	require_once "requests/config.php";
	$GoldenGators = new ClashofClans;
	session_start();
	
	if (isset($_GET['modal-body'])) {
		/*$res = GGClashUtil::advancedGetExpiration($GCaller->get("calls", "expiration", ["AND" => ["playerTag" => $_GET['player'], "callNum" => $_GET['callNum']]]), false);
        $res = $res > 0 ? $res : "EXPIRED";*/
		$rValue = $GCaller->get("calls", "notes", ["AND" => ["warTag" => $GoldenGators->getWar("enemyTag"), "playerTag" => $_GET['player'], "callNum" => $_GET['callNum']]]);
		$pValue = $GCaller->get("calls", "permission", ["AND" => ["warTag" => $GoldenGators->getWar("enemyTag"), "playerTag" => $_GET['player'], "callNum" => $_GET['callNum']]]);
		$textReview = null;
		$stars = null;

		$permission = null;
		switch ($GCaller->get("calls", "stars", ["AND" => ["warTag" => $GoldenGators->getWar("enemyTag"), "playerTag" => $_GET['player'], "callNum" => $_GET['callNum']]])) {
			case '9': $stars = "<label style='cursor: pointer;' class='label label-warning' onclick=\"playerResult('{$_GET['player']}', {$_GET['callNum']})\">Press to Set</label>"; break;
			case '0': $stars = "<label style='cursor: pointer;' class='label label-primary' onclick=\"playerResult('{$_GET['player']}', {$_GET['callNum']})\"><img src='assets/img/0star.png'/></label>"; break;
			case '1': $stars = "<label style='cursor: pointer;' class='label label-primary' onclick=\"playerResult('{$_GET['player']}', {$_GET['callNum']})\"><img src='assets/img/1star.png'/></label>"; break;
			case '2': $stars = "<label style='cursor: pointer;' class='label label-primary' onclick=\"playerResult('{$_GET['player']}', {$_GET['callNum']})\"><img src='assets/img/2star.png'/></label>"; break;
			case '3': $stars = "<label style='cursor: pointer;' class='label label-primary' onclick=\"playerResult('{$_GET['player']}', {$_GET['callNum']})\"><img src='assets/img/3star.png'/></label>"; break;
		}
		if (($_SESSION['userTag'] != $_GET['player']) && (!in_array($_SESSION['userTag'], $GoldenGators->admins)) && ($GoldenData->count('miniacc', ["AND" => ["ownerTag" => $_SESSION['userTag'], "playerTag" => $_GET['player']]]) == 0)) {
			$textReview = "<textarea style='width: 100%; height: 256px;' disabled>$rValue</textarea>";
		} else {
			$textReview = "<textarea id='review' onblur=\"save('{$_GET['player']}', 'review', {$_GET['callNum']})\" data-uid='{$_GET['player']}' style='width: 100%; height: 256px;'>$rValue</textarea>";
			$permission = "<textarea id='permissions' onblur=\"save('{$_GET['player']}', 'permissions', {$_GET['callNum']})\" style='width: 100%; height: 256px;' data-uid='{$_GET['player']}'>{$pValue}</textarea>";
		}
		$main = "
        <div class='box box-danger'>
        	<h5 style='font-family: Clash; margin: 8px 0 0 8px'>Attack Note</h5>
        	<div class='box-body'>
        		$textReview
          	</div>
        </div>
        <div class='box box-warning'>
        	<h5 style='font-family: Clash; margin: 8px 0 0 8px'>Stars</h5>
        	<div class='box-body'>
        		$stars
        	</div>
        </div>";

        $extra = "
        <div class='box box-warning'>
        	<h5 style='font-family: Clash; margin: 8px 0 0 8px'>Permission Nodes:</h5>
        	<div class='box-body'>
        		$permission
        	</div>
        </div>
        ";

        echo $main . $extra;
	} else {
		if (($_SESSION['userTag'] == $_GET['player']) || (in_array($_SESSION['userTag'], $GoldenGators->admins))) {
			echo "<button class='btn btn-danger' onclick=\"deleteCall('" . $_GET['player'] . "', '{$_GET['status']}', {$_GET['callNum']})\">Delete Call</button>";
		} elseif ($GoldenData->get('miniacc', "*", ["AND" => ["ownerTag" => $_SESSION['userTag'], "playerTag" => $_GET['player']]])) {
			echo "<button class='btn btn-danger' onclick=\"deleteCall('" . $_GET['player'] . "', '{$_GET['status']}', {$_GET['callNum']})\">Delete Call</button>";

		} else {
			echo "<button class='btn btn-primary' data-dismiss='modal'>Close</button>";
		}
	}
?>