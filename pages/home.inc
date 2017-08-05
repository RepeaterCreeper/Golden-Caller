<?php
    $prepTime = ClashUtils::advancedGetExpiration($GoldenGators->getWar('preparationDay'), false);
    $warTime  = ClashUtils::advancedGetExpiration($GoldenGators->getWar('warDay'), false);

    $time = null;
    $status = null;
    if ($prepTime < 0) {
        if ($warTime > 0) {
            $time = ClashUtils::advancedGetExpiration($GoldenGators->getWar('warDay'));
            $status = "<label style='font-size: small'>WAR DAY</label>";
            $GCaller->update('warlog', ['status' => 'war'], ['enemyTag' => $GoldenGators->getWar('enemyTag')]);
        } else {
            $time = "War has finished!";
            $GCaller->update('warlog', ['status' => 'complete'], ['enemyTag' => $GoldenGators->getWar('enemyTag')]);
        }
    } else {
        $time = ClashUtils::advancedGetExpiration($GoldenGators->getWar('preparationDay'));
        $status = "<label style='font-size: small'>PREP DAY</label>";

        $GCaller->update('warlog', ['status' => 'prep'], ['enemyTag' => $GoldenGators->getWar('enemyTag')]);
    }

    if (isset($_POST['startWar'])) {
        if ($GCaller->count("warlog", ["status[!]" => 'complete']) <= 0) {
            if ((!empty($_POST['enemyTag'])) && (isset($_POST['warSize'])) && (isset($_POST['timerSettings']))) {
                    $parse = strtoupper($_POST['enemyTag']);
                    $parse = str_replace("O", "0", $parse);
                    $parse = str_replace("#", "", $parse);
                    $enemyTag = $_POST['enemyTag'];
                    $warSize = $_POST['warSize'];
                    $pTime = "+22 hours 59 minutes";
                    $wTime = "+46 hours 59 minutes";
                    $timer = "+" . "{$_POST['timerSettings']}" . " hours";
                    $enemyName = $GoldenGators->getClan($parse)->name;
                    $GCaller->insert("warlog",[
                        "enemyName" => $enemyName,
                        "enemyTag" => $enemyTag,
                        "warsize" => $warSize,
                        "preparationDay" => date("Y-m-d H:i:s", strtotime($pTime)),
                        "warDay" => date("Y-m-d H:i:s", strtotime($wTime)),
                        "timer" => $timer
                    ]);
                
            }
        }
    }

    if (isset($_POST['addMini'])) {
        $playerTag = ClashUtils::clean($_POST['miniPlayerTag']);
        if (($GoldenData->count('members', ['playerTag' => $playerTag]) + $GCaller->count('miniacc', ['playerTag' => $playerTag])) > 0) {
            echo "<div class='alert alert-danger alert-dismissible'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                <h4><i class='icon fa fa-ban'></i> Error!</h4>
                This account is already being used! If this is your account contact Leadership!
              </div>";
        } else {
            $GoldenData->insert('miniacc', [
                'ownerTag' => $_SESSION['userTag'], 
                'playerTag' => $playerTag, 
                'playerName' => $GoldenGators->getPlayer('#' . $playerTag)->name
            ]);
            echo "<div class='alert alert-success alert-dismissible'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                <h4><i class='icon fa fa-check'></i> Success:</h4>
                Mini has been successfully added!
              </div>";

        }
    }
?>
<div class="row">
    <div class="col-md-4 col-sm-6">
        <div class="info-box bg-red">
            <span class="info-box-icon"><i class="ion ion-ios-stopwatch-outline"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Time Remaining</span>
                <span class="info-box-number"><?= $time; ?></span>
                <span class="progress-description">
                    <?= $status; ?>
                </span>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-xs-12">
        <div class="box box-warning">
            <div class="box-header with-border">
                <i class="fa fa-cubes"></i>
                <h3 class="box-title">Leaderboard - Avg. Stars</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table class="table table-hover">
                    <tbody>
                        <tr>
                            <th>Position</th>
                            <th>Name</th>
                            <th>Avg. Star</th>
                        </tr>
                        <?php
                            $i = 1;
                            foreach ($GCaller->select("members", ["averageStars", "username"], ["ORDER" => "averageStars DESC", "LIMIT" => 3]) as $leader) {
                                $dec = null;
                                switch ($i) {
                                    case 1: $dec = "num gold"; break;
                                    case 2: $dec = "num silver"; break;
                                    case 3: $dec = "num bronze"; break;
                                }
                                echo "
                                <tr>
                                    <td class='{$dec}'>$i</td>
                                    <td>{$leader['username']}</td>
                                    <td>{$leader['averageStars']}</td>
                                </tr>
                                ";
                                $i++;
                            }
                        ?>
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
          </div>
    </div>

    <div class="col-md-6 col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <i class="fa fa-cubes"></i>
                <h3 class="box-title">Leaderboard - Total Stars</h3>
            </div>
            <div class="box-body">
                <table class="table table-hover">
                    <tbody>
                        <tr>
                            <th>Position</th>
                            <th>Name</th>
                            <th>Total Stars</th>
                        </tr>
                        <?php
                            $i = 1;
                            foreach ($GCaller->select("members", ["totalStars", "username"], ["ORDER" => "totalStars DESC", "LIMIT" => 3]) as $leader) {
                                $dec = null;
                                switch ($i) {
                                    case 1: $dec = "num gold"; break;
                                    case 2: $dec = "num silver"; break;
                                    case 3: $dec = "num bronze"; break;
                                }
                                echo "
                                <tr>
                                    <td class='{$dec}'>$i</td>
                                    <td>{$leader['username']}</td>
                                    <td>{$leader['totalStars']}</td>
                                </tr>
                                ";
                                $i++;
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php

    if (in_array($_SESSION['userTag'], $GoldenGators->admins)) {
        if (!$GoldenGators->inWar) {
            include("templates/adminControl.template");
        } else {
            include("templates/adminControlW.template");
        }
    } else {
        echo "<div class='row'>
                <div class='col-md-12'>
                    <div class='box box-danger'>
                        <div class='box-header with-border'>
                            <i class='fa fa-cog'></i>
                            <h3 class='box-title'>Control Panel</h3>
                        </div>
                        <div class='box-body'>
                            <div class='row'>
                                <div class='col-md-6'>
                                    <div class='box box-warning'>
                                        <div class='box-header with-border'>
                                            Unavailable
                                        </div>
                                        <div class='box-body'>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class='col-md-6'>
                                    <div class='box box-warning'>
                                        <div class='box-header with-border'>
                                            Mini Accounts
                                        </div>
                                        <div class='box-body'>
                                            <div style='margin: 16px;'><ol>";
                                            if ($GoldenData->count('miniacc', ["ownerTag" => $_SESSION['userTag']]) > 0) {
                                                foreach ($GoldenData->select('miniacc', '*', ['ownerTag' => $_SESSION['userTag']]) as $mini) {
                                                    echo "<li>";
                                                        echo $mini['playerName'];
                                                    echo "</li>";
                                                }
                                            } else {
                                                echo "<li>No Mini Account</li>";
                                            }

                                                echo "</ol>
                                                <hr>
                                                <form method='POST' class='form-horizontal'>
                                                    <div class='box-body'>
                                                        <div class='form-group'>
                                                            <label for='miniPlayerTag' class='col-sm-3 control-label'>Player Tag:</label>
                                                            <div class='col-sm-9'>
                                                                <input type='text' class='form-control' id='miniPlayerTag' name='miniPlayerTag' placeholder='#'>
                                                            </div>
                                                        </div>
                                                        <button class='btn btn-primary' type='submit' name='addMini'>Add Mini</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </body>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>";
    }

?>