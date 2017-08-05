<?php
    session_start();
    require_once "requests/config.php";
    
    /*ini_set("session.cookie_httponly", 1);
    ini_set("session.use_only_cookies", 1);*/
    

    if (isset($_SESSION['loginUser'])) {
        header("Location: /home");
    }

    $GoldenGators = new ClashofClans;
    $GoldenGators->refreshData();

    if (isset($_POST['loginCaller'])) {
        if ((!empty($_POST['callerUsername'])) && (!empty($_POST['callerPassword']))) {
            if ($GCaller->count("members", ["username" => $_POST['callerUsername']]) > 0) {
                $username = $_POST['callerUsername'];
                $password = $_POST['callerPassword'];

                $salt = $GCaller->get("members", "salt", ["username" => $_POST['callerUsername']]);
                $adminPassword = $GCaller->get("members", "password", ["username" => $_POST['callerUsername']]);
                if (hash("sha256", $password . $salt) == $adminPassword) {
                    $_SESSION['loginUser'] = $_POST['callerUsername'];
                    $_SESSION['userTag'] = $GCaller->get("members", "playerTag", ["username" => $_POST['callerUsername']]);
                    
                    header("Location: /home");
                } else {
                    echo "
                    <div id='error' class='alert' style='background-color: #AA3939;'>
                        <h4 style='color: #fff;'>ERROR: Incorrect Credentials</h4>
                        <p style='color: #fff;'>You have entered an invalid credential! Please try again.</p>
                    </div>";
                }
            } elseif ($GCaller->count("members", ["playerTag" => $_POST['callerUsername']]) > 0) {
                $username = strtoupper($_POST['callerUsername']);
                $password = $_POST['callerPassword'];

                $salt = $GCaller->get("members", "salt", ["playerTag" => $_POST['callerUsername']]);
                $adminPassword = $GCaller->get("members", "password", ["playerTag" => $_POST['callerUsername']]);
                if (hash("sha256", $password . $salt) == $adminPassword) {
                    $_SESSION['loginUser'] = $GCaller->get("members", "username", ["playerTag" => $username]);
                    $_SESSION['userTag'] = $GCaller->get("members", "playerTag", ["playerTag" => $username]);
                    header("Location: /home");
                } else {
                    echo "
                    <div id='error' class='alert' style='background-color: #AA3939;'>
                        <h4 style='color: #fff;'>ERROR: Incorrect Credentials</h4>
                        <p style='color: #fff;'>You have entered an invalid credential! Please try again. (Case Sensitive)</p>
                    </div>";
                }
            } else {
                echo "
                <div id='error' class='alert' style='background-color: #AA3939;'>
                    <h4 style='color: #fff;'>ERROR: Account does not exist!</h4>
                    <p style='color: #fff;'>Please create an account to login. If problem persists contact leadership.</p>
                </div>";
            }
       } else {
            echo "
            <div id='error' class='alert' style='background-color: #AA3939;'>
                <h4 style='color: #fff;'>ERROR: Empty Fields</h4>
                <p style='color: #fff;'>All fields must be filled in to log in!</p>
            </div>";
       }
    }

    if (isset($_POST['registerCaller'])) {
        $error = false;
        $inputs = ["callerUsername", "callerPassword", "callerPasswordConfirm"];
        foreach ($inputs as $input) {
            if (empty($_POST[$input])) {
                echo "
                <div id='error' class='alert' style='background-color: #AA3939;'>
                    <h4 style='color: #fff;'>ERROR: Empty Fields</h4>
                    <p style='color: #fff;'>All fields must be filled in to signup!</p>
                </div>";
                $error = true;
                break;
            }
        }

        if ($_POST['callerPassword'] != $_POST['callerPasswordConfirm']) {
            $error = true;
            echo "
                <div id='error' class='alert' style='background-color: #AA3939;'>
                    <h4 style='color: #fff;'>ERROR: Not Match</h4>
                    <p style='color: #fff;'>Password and password confirm did not match!</p>
                </div>";
        }

        if ($error == false) {
            $tag = htmlentities($_POST['callerUsername']);
            if ($GCaller->count("members", ["playerTag" => $tag]) == 0) {
                $proceed = true;

                if ($proceed == true) {
                    $member = true;
                    #HASH Generation
                    $password = $_POST['callerPassword'];
                    define("MAX_LENGTH", 16);
                    $intermediateSalt = md5(uniqid(rand(), true));
                    $salt = substr($intermediateSalt, 0, MAX_LENGTH);
                    $passwordHash = hash("sha256", $password . $salt);

                    /* Broken in Localhost */
                    $tag = ClashUtils::convertToTag($tag);
                    $GCaller->insert("members", [
                        "playerTag" => $tag,
                        "username" => $GoldenGators->getPlayer('#' . $tag)->name,
                        "password" => $passwordHash,
                        "salt" => $salt
                    ]);
                    echo "
                    <div id='error' class='alert' style='background-color: #32CD32;'>
                        <h4 style='color: #fff;'>SUCCESS: Successfully created</h4>
                        <p style='color: #fff;'>Account has been successfully created!</p>
                    </div>";
                } else {
                    echo "
                    <div id='error' class='alert' style='background-color: #AA3939;'>
                        <h4 style='color: #fff;'>ERROR: Not a Golden Gators member</h4>
                        <p style='color: #fff;'>This player tag does not correspond to any Golden Gators member.</p>
                    </div>";
                }
            } else {
                echo "
                <div id='error' class='alert' style='background-color: #AA3939;'>
                    <h4 style='color: #fff;'>ERROR: Account already exists!</h4>
                    <p style='color: #fff;'>This player tag has already been used. Please contact leadership.</p>
                </div>";
            }
        }
    }

    $members = $GCaller->select("members", "username");
    $mini = $GoldenData->select('miniacc', 'playerTag');

    $players = $GoldenGators->getPlayers();
    
    #echo GGClashUtil::convertToId("#2RPR890Q8");
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Golden Caller</title>

        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="/assets/css/goldengators.css">
        <link rel="stylesheet" href="/assets/css/bootstrap.min.css">

        <link rel="stylesheet" href="/assets/css/AdminLTE.min.css">
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <a href="login.php" style='font-weight: bold; font-size: smaller; font-family: Clash; color: #fff'><b style='color: yellow;'>Golden</b> Caller</a>
            </div>
            <!-- /.login-logo -->
            <div class="login-box-body">
                <div id="loginForm">
                    <form role="form" method="POST">
                        <div class="form-group has-feedback">
                            <input name='callerUsername' type="text" class="form-control" placeholder="Username/Player Tag">
                            <span class="glyphicon glyphicon-user form-control-feedback"></span>
                        </div>
                        <div class="form-group has-feedback">
                            <input name='callerPassword' type="password" class="form-control" placeholder="Password">
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <button name='loginCaller' type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <button id='goRegister' class="btn btn-primary btn-block btn-flat">Register</button>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div id="regForm">
                    <form role="form" method="POST">
                        <div class="form-group has-feedback">
                            <label class="sr-only" for="callerUsername">Player Tag: </label>
                            <div class='input-group'>
                                <span class="input-group-addon" id="playerTag">Username</span>
                                <select class="form-control" name="callerUsername">
                                    <?php
                                        foreach ($GoldenGators->getPlayers() as $player) {
                                            if (!in_array($player->name, $members)) {
                                                if (!in_array(str_replace("#", "", $player->tag), $mini)) {
                                                    echo "<option value='{$player->id}'>{$player->name}</option>";
                                                }
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group has-feedback">
                            <input name='callerPassword' type="password" class="form-control" placeholder="Password">
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        </div>
                        <div class="form-group has-feedback">
                            <input name='callerPasswordConfirm' type="password" class="form-control" placeholder="Confirm Password">
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <button id='goLogin' class="btn btn-primary btn-block btn-flat">Back</button>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <button name='registerCaller' type="submit" class="btn btn-primary btn-block btn-flat">Register</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.login-box -->

        <!-- jQuery 2.1.4 -->
        <script src="assets/js/jquery-1.11.3.min.js"></script>
        <!-- Bootstrap 3.3.5 -->
        <script src="assets/js/bootstrap.min.js"></script>

        <script src="assets/js/jquery.backstretch.min.js"></script>
        <script>

        $(function() {
            $("#regForm").hide();

            $("#goRegister").on("click", function(e) {
                e.preventDefault();
                $("#regForm").show();
                $("#loginForm").hide();
            });

            $("#goLogin").on("click", function(e) {
                e.preventDefault();
                $("#regForm").hide();
                $("#loginForm").show();
            });
            $.backstretch("http://i.imgur.com/FlCF3d7.jpg");
        });
        </script>
    </body>
</html>


