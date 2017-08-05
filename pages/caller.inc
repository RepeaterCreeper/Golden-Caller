<?php
    $userTag = $_SESSION['userTag'];
    
    if (isset($_POST['addCall'])) {
        if (in_array($_SESSION['userTag'], $GoldenGators->admins)) {

            $expTime = null;
            if ($GoldenGators->getWar("status") == 'prep') {
                $n = $GoldenGators->getWar("preparationDay");
                $timer = $GoldenGators->getWar("timer");
                $a = strtotime($timer) - strtotime("now");
                $b = strtotime("{$n}") - strtotime("now");
                $c = $a + $b;
                $expTime = strtotime("+{$c} seconds");
            } else {
                $expTime = (strtotime($GoldenGators->getWar("timer")));
            }
            
            $explode = explode("-", $_POST['adminPlayerD']);
            $GCaller->insert("calls", [
                "username"      => $explode[1],
                "playerTag"     => $explode[0],
                "callNum"       => $_POST['callNum'],
                "warTag"        => $GoldenGators->getWar("enemyTag"),
                "expiration"    => date("Y-m-d H:i:s", $expTime)
            ]);

            $GCaller->update("pagegen", ["numId[+]" => 1], ['id' => 1]);

            header("Location: /caller");
        }
    }

    if (isset($_POST['addMiniCall'])) {
        $playerTag = $_POST['playerMini'];
        if ($GoldenData->get('miniacc', '*', ["AND" => ["ownerTag" => $_SESSION['userTag'], "playerTag" => $_POST['playerMini']]])) {

            $expTime = null;
            if ($GoldenGators->getWar("status") == 'prep') {
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
                "username"      => $GoldenGators->getPlayer("#" . $playerTag)->name,
                "playerTag"     => $playerTag,
                "callNum"       => $_POST['callNum'],
                "warTag"        => $GoldenGators->getWar("enemyTag"),
                "expiration"    => date("Y-m-d H:i:s", $expTime)
            ]);

            $GCaller->update("pagegen", ["numId[+]" => 1], ['id' => 1]);
        }
    }
?>
<div class="row">
    <div class="col-md-12" <?php if (!in_array($_SESSION['userTag'], $GoldenGators->admins)) { echo "hidden"; }?>>
        <div class="box box-solid box-danger">
            <div class="box-header">
                <h3 class="box-title">Admin Call Dialog</h3>
            </div>
            <div class="box-body">
            <form method='POST'>
                <div class="form-group">
                    <label for="adminPlayerD" class="col-sm-2 control-label">Players: </label>
                    <select name="adminPlayerD" id="adminPlayerD" class="form-control">
                        <?php
                            $Members = $GCaller->select('members', ["username", "playerTag"]);
                            array_multisort($Members, SORT_ASC, SORT_REGULAR);

                            foreach ($Members as $member) {
                                $d = str_replace("'", " ", $member['username']);
                                echo "<option value='{$member['playerTag']}-{$d}'>{$member['username']}</option>";
                            }
                            
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="callNum" class='col-sm-2 control-label'>Call Num: </label>
                    <select class='form-control' name="callNum" id="callNum">
                    <?php 
                        for ($i = 0; $i < $GoldenGators->getWar("warsize"); $i++) {
                            echo "<option value={$i}>" . ($i + 1) . "</option>";
                        }
                    ?>
                    </select>
                </div>
                <div class="form-group">
                    <button name='addCall' type='submit' class='btn btn-primary'>Add Call</button>
                </div>
                </form>
            </div>

        </div>
    </div>

    <div class="col-md-6" <?php if ($GoldenData->count("miniacc", ["ownerTag" => $_SESSION['userTag']]) <= 0) { echo "hidden"; }?>>
        <div class="box box-solid box-danger">
            <div class="box-header">
                <h3 class="box-title">Mini Call</h3>
            </div>
            <div class="box-body">
            <form method='POST'>
                <div class="form-group">
                    <label for="playerMini" class="col-sm-2 control-label">Players: </label>
                    <select name="playerMini" id="playerMini" class="form-control">
                        <?php
                            foreach ($GoldenData->select('miniacc', "*", ["ownerTag" => $_SESSION['userTag']]) as $mini) {
                                echo "<option value='{$mini['playerTag']}'>{$mini['playerName']}</option>";
                            }
                            
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="callNum" class='col-sm-2 control-label'>Call Num: </label>
                    <select class='form-control' name="callNum" id="callNum">
                    <?php 
                        for ($i = 0; $i < $GoldenGators->getWar("warsize"); $i++) {
                            echo "<option value={$i}>" . ($i + 1) . "</option>";
                        }
                    ?>
                    </select>
                </div>
                <div class="form-group">
                    <button name='addMiniCall' type='submit' class='btn btn-primary'>Add Call</button>
                </div>
                </form>
            </div>

        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="box box-solid box-warning">
            <div class="box-header">
                <h3 class="box-title">Call Dialog</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <tbody>
                        <tr>
                            <th>#</th>
                            <th>Target</th>
                            <th>Controls</th>
                        </tr>
                        <?php
                            $calls = array();
                            if ($GoldenGators->getWar("warsize") != null) {
                                for ($i = 0; $i < $GoldenGators->getWar("warsize"); $i++) {
                                    $targetC = $GCaller->get("calltarget", "townhall", ["AND" => ['warTag' => $GoldenGators->getWar("enemyTag"), 'callNum' => $i]]);
                                    $target = null;
                                    $tget = null;
                                    $hide = null;
                                    if ($targetC) {
                                        switch($targetC) {
                                            case 6: $tget = "<select onchange='targetDesignate({$i})' class='form-control' id='target'><option value='hide'>Hide</option><option value='6' selected>th6</option><option value='7'>th7</option><option value='8'>th8</option><option value='9'>th9</option><option value='10'>th10</option></select>"; break;
                                            case 7: $tget = "<select onchange='targetDesignate({$i})' class='form-control' id='target'><option value='hide'>Hide</option><option value='6'>th6</option><option value='7' selected>th7</option><option value='8'>th8</option><option value='9'>th9</option><option value='10'>th10</option></select>"; break;
                                            case 8: $tget = "<select onchange='targetDesignate({$i})' class='form-control' id='target'><option value='hide'>Hide</option><option value='6'>th6</option><option value='7'>th7</option><option value='8' selected>th8</option><option value='9'>th9</option><option value='10'>th10</option></select>"; break;
                                            case 9: $tget = "<select onchange='targetDesignate({$i})' class='form-control' id='target'><option value='hide'>Hide</option><option value='6'>th6</option><option value='7'>th7</option><option value='8'>th8</option><option value='9' selected>th9</option><option value='10'>th10</option></select>"; break;
                                            case 10: $tget = "<select onchange='targetDesignate({$i})' class='form-control' id='target'><option value='hide'>Hide</option><option value='6'>th6</option><option value='7'>th7</option><option value='8'>th8</option><option value='9'>th9</option><option value='10' selected>th10</option></select>"; break;
                                            case 'hide': $tget = "<select onchange='targetDesignate({$i})' class='form-control' id='target'><option value='hide' selected>Hide</option><option value='6'>th6</option><option value='7'>th7</option><option value='8'>th8</option><option value='9'>th9</option><option value='10'>th10</option></select>"; break;
                                            default: $tget = "<select onchange='targetDesignate({$i})' class='form-control' id='target'><option disabled selected='selected'>TH:</option><option value='hide'>Hide</option><option value='6'>th6</option><option value='7'>th7</option><option value='8'>th8</option><option value='9'>th9</option><option value='10'>th10</option></select>"; break;
                                        }
                                        
                                        if (in_array($_SESSION['userTag'], $GoldenGators->admins)) {
                                           $target = $tget;
                                        } else {
                                            if ($targetC == 'hide') {
                                                $hide = true;
                                            } else {
                                                $targetC = ClashUtils::convertTH($targetC);
                                                $target = strlen($targetC) <= 0 ? "" : "<img style='margin-left: 8px;' src='{$targetC}'/>";
                                            }
                                        }
                                    } else {
                                        switch($targetC) {
                                            case 6: $tget = "<select onchange='targetDesignate({$i})' class='form-control' id='target'><option value='hide'>Hide</option><option value='6' selected>th6</option><option value='7'>th7</option><option value='8'>th8</option><option value='9'>th9</option><option value='10'>th10</option></select>"; break;
                                            case 7: $tget = "<select onchange='targetDesignate({$i})' class='form-control' id='target'><option value='hide'>Hide</option><option value='6'>th6</option><option value='7' selected>th7</option><option value='8'>th8</option><option value='9'>th9</option><option value='10'>th10</option></select>"; break;
                                            case 8: $tget = "<select onchange='targetDesignate({$i})' class='form-control' id='target'><option value='hide'>Hide</option><option value='6'>th6</option><option value='7'>th7</option><option value='8' selected>th8</option><option value='9'>th9</option><option value='10'>th10</option></select>"; break;
                                            case 9: $tget = "<select onchange='targetDesignate({$i})' class='form-control' id='target'><option value='hide'>Hide</option><option value='6'>th6</option><option value='7'>th7</option><option value='8'>th8</option><option value='9' selected>th9</option><option value='10'>th10</option></select>"; break;
                                            case 10: $tget = "<select onchange='targetDesignate({$i})' class='form-control' id='target'><option value='hide'>Hide</option><option value='6'>th6</option><option value='7'>th7</option><option value='8'>th8</option><option value='9'>th9</option><option value='10' selected>th10</option></select>"; break;
                                            case 'hide': $tget = "<select onchange='targetDesignate({$i})' class='form-control' id='target'><option value='hide' selected>Hide</option><option value='6'>th6</option><option value='7'>th7</option><option value='8'>th8</option><option value='9'>th9</option><option value='10'>th10</option></select>"; break;
                                            default: $tget = "<select onchange='targetDesignate({$i})' class='form-control' id='target'><option disabled selected='selected'>TH:</option><option value='hide'>Hide</option><option value='6'>th6</option><option value='7'>th7</option><option value='8'>th8</option><option value='9'>th9</option><option value='10'>th10</option></select>"; break;
                                        }

                                        if (in_array($_SESSION['userTag'], $GoldenGators->admins)) {
                                            $target = $tget;
                                        } else {
                                            if ($targetC == 'hide') {
                                                $hide = true;
                                            } else {
                                                $targetC = ClashUtils::convertTH($targetC);
                                                $target = strlen($targetC) <= 0 ? "" : "<img style='margin-left: 8px;' src='{$targetC}'/>";
                                            }
                                        }
                                    }

                                    if ($hide) {
                                        echo "<tr hidden>";
                                    } else {
                                        echo "<tr>";
                                    }
                                        $calls = $GCaller->select("calls", "*", ["AND" => ["callNum" => $i, "warTag" => $GoldenGators->getWar("enemyTag")]], ["ORDER" => 'status ASC']);
                                        if ($GCaller->get("calls", "stars", ["AND" => ["warTag" => $GoldenGators->getWar("enemyTag"), "callNum" => $i, "stars" => 3]])) {
                                            echo "<td class='bg-red col-md-1'>";
                                                echo $i + 1 . $target;
                                            echo "</td>";
                                        } else {
                                            echo "<td class='bg-green col-md-1'>";
                                                echo $i + 1 . $target;
                                            echo "</td>";
                                        }
                                        echo "<td id='pendingCall{$i}' class='col-md-10'>";
                                            foreach ($calls as $caller) {
                                                $resvisual = null;
                                                
                                                date_default_timezone_get("America/New York");
                                                $globalStart = strtotime('now');
                                                $t = ClashUtils::advancedGetExpiration($caller['expiration'], true);
                                                $t = $t > 0 ? $t : "EXPIRED";

                                                switch($caller['stars']) {
                                                    case 0: $resVisual = "<img class='fResult' src='assets/img/0star.png'/>"; break;
                                                    case 1: $resVisual = "<img class='fResult' src='assets/img/1star.png'/>"; break;
                                                    case 2: $resVisual = "<img class='fResult' src='assets/img/2star.png'/>"; break;
                                                    case 3: $resVisual = "<img class='fResult' src='assets/img/3star.png'/>"; break;
                                                    default: $resVisual = "<label class='fResult label label-danger'>{$t}</label>"; break;
                                                }
                                                $note = strlen($caller['notes']) > 0 ? "<i class='fa fa-file'></i>" : null;
                                                $permission = strlen($caller['permission']) > 0 ? "<i class='fa fa-warning'></i>" : null;
                                                if ($caller['status'] == 'EXPIRED') {
                                                    echo "<button class='btn btn-danger disabled' data-uid={$caller['playerTag']} onclick=\"loadPlayer('{$caller['playerTag']}', '{$caller['status']}', {$i})\">" . $caller['username'] . " {$note} {$permission} <br>{$resVisual}</button>";
                                                } else {
                                                    echo "<button class='btn btn-primary outline' data-uid={$caller['playerTag']} onclick=\"loadPlayer('{$caller['playerTag']}', '{$caller['status']}', {$i})\">" . $caller['username'] . " {$note} {$permission}<br>{$resVisual}</button>";
                                                }
                                            }
                                        echo "</td>";
                                        echo "<td class='col-md-1'>";
                                            echo "<button onclick=\"append_call({$i})\" class='btn btn-success'>+</button>";
                                        echo "</td>";
                                    echo "</tr>";
                                }
                            }
                        ?>
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
          </div>
    </div>
</div>

<script>
    function loadPlayer(id, status, num) {
        $("#playerDetail .modal-body").load('playerdetails.php?player=' + id + '&callNum=' + num + '&modal-body', function() {
            $("#playerDetail .modal-footer").load('playerdetails.php?player=' + id + '&callNum=' + num + "&status=" + status);
            $("#playerDetail .modal-title").text("Player Details");
            $('#playerDetail').modal('show');
        });
    }

    function laModel(num) {
        $("#playerDetail .modal-body").load('playerentry.php?callNum=' + num + '&modal-body', function() {
            $("#playerDetail .modal-title").text("Admin Entry:");
            $("#playerDetail .modal-footer").load('playerentry.php');
            $('#playerDetail').modal('show');
        });
    }

    function deleteCall(name, status, num) {
        $.post("/requests/api.php?delete", {status: status, id: name, num: num}, function(data) {
            $("#pendingCall" + num).prev().removeClass("bg-red");
            $("#pendingCall" + num).prev().addClass("bg-green");
            $("#pendingCall" + num + " button[data-uid='" + name + "']").remove();
            if (data == '001') {
                alert("ERROR: Invalid Permission!");
            }
            $("#playerDetail").modal('hide');
        });
    }

    function append_call(num, mini) {
        var name = "<?php echo $_SESSION['loginUser']; ?>";
        var playerTag = "<?php echo $_SESSION['userTag']; ?>";

        $.post("/requests/api.php?add", {name: name, playerTag: playerTag, num: num}, function(data, status) {
            //Create jQuery object from the response HTML.
            var $response=$(data);

            //Query the jQuery object for the values
            var a = $response.filter('#status').text();
            var b =  $response.filter('#result').html();
            
            if (status === 'success') {
                if (a == 'success') {
                    $("#pendingCall" + num).append("<button data-uid=" + playerTag + " onclick=\"loadPlayer('" + playerTag+ "', 'ACTIVE', " + num + ")\" class='btn btn-primary outline'>" + name + "<br>" + b + "</button>");
                } else if(a == 'error') {
                    alert(b);
                } else if(a == 'mini'){
                    $("#playerDetail .modal-body").load('mini.php?modal-body', function() {
                        $("#playerDetail .modal-footer").load('mini.php?callNum=' + num);
                        $("#playerDetail .modal-title").text("Mini Call");
                        $('#playerDetail').modal('show');
                    });
                } else {
                    alert("An error occured! Contact @RepeaterCreeper");
                }
                
            }
        })
    }

    function saveResult(tag, num, val) {
        if ($('#pendingCall' + num + " button[data-uid='" + tag + "'] .fResult").length) {
            $('#pendingCall' + num + " button[data-uid='" + tag + "'] .fResult").replaceWith("<img class='fResult' src='assets/img/" + val + "star.png'/>");
        } else {
            $('#pendingCall' + num + " button[data-uid='" + tag + "'] label").replaceWith("<img class='fResult' src='assets/img/" + val + "star.png'/>");
        }

        $.notify({
            icon: 'fa fa-star',
            message: "Your stars have been successfully updated!"

        },{
            type: 'info',
            timer: 500,
            z_index: 9999,
        });

        $("#warResult").modal("hide");

        $.post("/requests/api.php?setResult", {pID: tag, call: num, res: val});
    }

    function targetDesignate(num) {
        var val = $("#pendingCall" + num).prev().find('#target').val();
        $.post("/requests/api.php?designate", {cn: num, townhall: val});
    }
    
    function save(pID, field, num) {
        var delta = $("#" + field + "[data-uid='" + pID + "']");
        var value = delta.val();

        if (field == 'review') {
            $.post("/requests/api.php?setNote", {id: pID, note: value, num: num});
        } else if (field == 'permissions') {
            $.post("/requests/api.php?setPerm", {id: pID, permission: value, num: num});
        }
    }

    function playerResult(tag, num) {
        $("#warResult .modal-body").load('playerResults.php?player=' + tag + '&callNum=' + num + '&modal-body', function() {
            $("#playerDetail").modal("hide");
            $('#warResult').modal('show');
        });
    }
</script>