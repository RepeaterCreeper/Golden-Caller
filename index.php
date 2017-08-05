<?php
    session_start();

    require_once "requests/config.php";
    require_once "assets/class/pushpad-php/init.php";
    use phpFastCache\CacheManager;

    $cache = CacheManager::getInstance("memcached");
    $cache = CacheManager::getInstance("redis");
    $cache = CacheManager::Files();

    ini_set("session.cookie_httponly", 1);
    ini_set("session.use_only_cookies", 1);
    
    $GoldenGators = new ClashofClans;
    $GoldenGators->refreshData();
    
    if (!isset($_SESSION['loginUser'])) {
        header("Location: login");
    } else if (isset($_GET['logout'])) {
        session_destroy();
        session_unset($_SESSION['loginUser']);
        $cache->clean();
        header("Location: login");
    }

    $war = $GoldenGators->inWar ? "Golden Gators vs " . $GoldenGators->getWar("enemyName") : "Not In War!";

    if (!isset($_SESSION['userDetails'])) {
        $_SESSION['userDetails'] = $GoldenGators->getPlayer('#' . $_SESSION['userTag']);
    }
    /* CHART DATA */
    $keywords = ["dates", "trophy", "donation", "donationReceived"];
    $rankKeys = ["trophyRank", "donationRank", "ratioRank"];

    foreach ($keywords as $data) {
        if ($data == "dates") {
            if (!$cache->isExisting($data)) {
                $cache->set("dates", $GoldenData->select('history', 'date', ['playerTag' => '#' . $_SESSION['userTag']]), 86400);
            }
        }
        if (!$cache->isExisting($data)) {
            $a;
            switch ($data) {
                case "trophy": $a = "trophy"; break;
                case "donation": $a = "donations"; break;
                case "donationReceived": $a = "donationsReceived"; break;
            }
            $cache->set($data, array_map('intval', $GoldenData->select('history', $a, ['playerTag' => '#' . $_SESSION['userTag']])), 86400);
        }
    }
    foreach ($rankKeys as $key) {
        $k;
        switch ($key) {
            case "trophyRank": $k = "RANKTrophy"; break;
            case "donationRank": $k = "RANKDonation"; break;
            case "ratioRank": $k = "RANKRatio"; break;
        }
        if (!$cache->isExisting($key)) {
            $cache->set($k, $GoldenData->get("kingdomplayerleaderboard", $key, ["tag" => '#' . $_SESSION['userTag']]), 86400);
        }
    }


    $trophy             = $cache->get("trophy");
    $donation           = $cache->get("donation");
    $donationReceived   = $cache->get("donationReceived");

    $RANKTrophy         = $cache->get("RANKTrophy");
    $RANKDonation       = $cache->get("RANKDonation");
    $RANKRatio          = $cache->get("RANKRatio");
    $RANKTotal          = $GoldenData->count("kingdomplayerleaderboard");

    $DATE               = $cache->get("dates");

    $wSize              = $GoldenGators->inWar ? $GoldenGators->getWar("warsize") : 0;
    $page = isset($_GET['page']) ? $_GET['page'] : "home";
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Golden Caller</title>

        <meta name="author" content="RepeaterCreeper">
        <meta name="keywords" content="caller, clash of clans, golden gators caller, golden caller, clash caller, RepeaterCreeper">
        <meta name="description" content="Official reservation system for kingdom clan Golden Gators.">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

        <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="/assets/css/AdminLTE.min.css">
        <link rel="stylesheet" href="/assets/css/goldengators.css">

        <link rel="stylesheet" href="/assets/css/skins/skin-red.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">


        <link rel="author" href="https://plus.google.com/117395245681162319337"/>
    </head>
    <body class="hold-transition skin-red sidebar-mini">
        <div class="wrapper">
            <!-- Main Header -->
            <header class="main-header">
            <!-- Logo -->
                <a href="/home" class="logo">
                    <span class="logo-mini" style='font-family: Clash; font-size: smaller;'><b style='color: yellow;'>G</b>C</span>
                    <span class="logo-lg" style="font-family: Clash;"><b style='color: yellow; font-family:'>Golden</b> Caller</span>
                </a>

            <!-- Header Navbar -->
                <nav class="navbar navbar-static-top" role="navigation">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </a>
                </nav>
            </header>
            
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="main-sidebar">

            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">

                <!-- Sidebar Menu -->
                <ul class="sidebar-menu" style='font-family: Clash;'>
                    <li class="header">Main</li>
                    <li data-page='home' class="active"><a href="/home"><i class="fa fa-dashboard"></i> <span style='font-size: x-small;'>Overview</span></a></li>
                    <li data-page='profile'><a href="/profile"><i class="fa fa-user"></i> <span style="font-size: x-small;">Profile</span></a></li>
                    <li data-page='caller'><a href="/caller"><i class="fa fa-star"></i> <span style="font-size: x-small;">Caller</span></a></li>
                    <li data-page='leaderboard'><a href="/leaderboard"><i class="fa fa-list-alt"></i> <span style="font-size: x-small;">Leaderboard</span></a></li>
                    <li data-page='changelog'><a href="/changelog"><i class="fa fa-book"></i> <span style="font-size: x-small;">Changelog</span></a></li>
                    <li class="header">Action</li>
                    <li><a href="/logout"><i class="fa fa-sign-out"></i><span style="font-size: x-small;">Logout</span></a></li>
                </ul><!-- /.sidebar-menu -->
            </section>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Main content -->
            <section class="content">
                <?php
                    if (isset($_GET['page'])) {
                        if (file_exists("pages/" . $_GET['page'] . ".inc")) {
                            include("pages/" . $_GET['page'] . ".inc");
                        } else {
                            $notFound = $_GET['page'] = "404" . ".inc";
                            include("pages/" . $notFound);
                        }
                    } else {
                        include("pages/home.inc");
                    }

                ?>
              <!-- /.box -->
            </section><!-- /.content -->
        </div><!-- /.content-wrapper -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="pull-right hidden-xs">
                <code>Current Build: v2.0</code> Created by RepeaterCreeper
            </div>
            <!-- Default to the left -->
            <strong>Copyright &copy; 2016 <a href="#">Golden Gators</a>.</strong> All rights reserved.
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Create the tabs -->
            <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
                <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
                <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
            </ul>
            <!-- Tab panes -->
        </aside><!-- /.control-sidebar -->
        <!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
        <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->

    <div id="warResult" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Set Results</h4>
                </div>
                <div class="modal-body">
                    <!-- Contents Here -->
                </div>
                <div class="modal-footer">
                    <!-- Controls Here -->
                </div>
            </div>
        </div>
    </div>

    <div id="playerDetail" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <!-- Contents Here -->
                </div>
                <div class="modal-footer">
                    
                </div>
            </div>
        </div>
    </div>
    <!-- REQUIRED JS SCRIPTS -->
    <script src="/assets/js/jquery-1.11.3.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="/assets/js/bootstrap-notify.js"></script>
    <script src="/assets/js/app.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.6/Chart.js"></script>
    <script>
        var pageVar = "<?php echo $page; ?>";
        var pageGenID = <?php echo $GCaller->get('pagegen', 'numId', ['id' => 1]);?>

        var updatedValues = function () {
            //alert('DEBUG: Updated VALUE');
            $.getJSON("requests/newData.php", function(data){
                var warSize = <?php echo $wSize; ?>;
                for(var i = 0; i < warSize; i++) {
                    $("#pendingCall" + i).empty();
                }

                $.each(data, function (index, info) {
                    var a = info.notes;
                    var b = info.permission;
                    var x = '<?php echo Date("Y-m-d H:i:s"); ?>';
                    var c = null;
                    
                    var expiryTime = new Date(info.expiration);
                    var currentTime = new Date(x);
                    var diff = expiryTime.getTime() - currentTime.getTime();    // subtract numerically
                    diffDate = new Date(diff);  // construct a new date based on the millis above (it will only hold significant portions)

                    if (diff < 0) { // if we're negative, the current time is past our expiry, so reflect that...
                        
                    } else {
                        var h, m;
                        //alert(diff);
                        var days,
                            hours,
                            minutes;
                        
                        diff /= 1000;
                        
                        /*days = Math.floor(diff / (60 * 60 * 24));
                        diff -= (60 * 60 * 24) * days;*/
                        
                        hours = Math.floor(diff / (60 * 60));
                        diff -= (60 * 60) * hours;
                        
                        minutes = Math.floor(diff / (60));
                        diff -= (60) * minutes;
                        
                        h = (hours <= 9) ? " 0" + hours + "H" : hours + "H";
                        m = (minutes <= 9) ? " 0" + minutes + "M": " " + minutes + "M";
                        
                        c = h + m;
                    }

                    var z = null;
                    //alert(info.stars);


                    var notes = a.length > 0 ? "<i class='fa fa-file'></i>" : "";
                    var permission = b.length > 0 ? "<i class='fa fa-warning'></i>" : "";
                    if ((c == null) && (info.stars == 9)) {
                        $("#pendingCall" + info.callNum).append("<button data-uid='" + info.playerTag + "' onclick=\"loadPlayer('" + info.playerTag+ "', '" + info.status + "', "+ info.callNum +")\"class='btn btn-danger disabled'>" + info.username  + " " + permission + " " + notes + "<br><label class='label label-danger'>EXPIRED</label>" + "</button>");
                    } else {
                        if (info.stars <= 3) {
                            $("#pendingCall" + info.callNum).append("<button data-uid='" + info.playerTag+ "' onclick=\"loadPlayer('" + info.playerTag+ "', '" + info.status + "', "+ info.callNum +")\"class='btn btn-primary outline'>" + info.username + " " + permission + " " + notes + "<br> " + "<img src='assets/img/" + info.stars + "star.png'/>" + "</button>");
                        } else {
                            $("#pendingCall" + info.callNum).append("<button data-uid='" + info.playerTag+ "' onclick=\"loadPlayer('" + info.playerTag+ "', '" + info.status + "', "+ info.callNum +")\"class='btn btn-primary outline'>" + info.username + " " + permission + " " + notes + "<br> " + "<label class='label label-danger'>" + c + "</label>" + "</button>");
                        }
                    }
                });
            });
        }

        var processUpdate = function( response ) {
            if ( pageGenID < response ) {
                updatedValues();
                //alert('DEBUG: Update processed');
                pageGenID = response;
            }
        }

        var checkUpdates = function() {
            serverPoll = setInterval( function()
            {
                //alert('DEBUG: Poll started');
                setTimeout(function() {
                $.get('requests/refresh.php', 
                    { lastupdate: 1 }, 
                    processUpdate, 'html');
                }), 5000;
            }, 3000 )
        };

        $(document).ready(function() {
            checkUpdates();
            $("#kingdomLeaderboard").DataTable();
            if (pageVar != "") {
                $(".active").removeClass("active");
                $("ul li[data-page='" + pageVar + "']").toggleClass('active');
            }

            var DATATrophy      = <?php echo json_encode($trophy) ?>;
            var DATADonations   = <?php echo json_encode($donation) ?>;
            var DATAReceived    = <?php echo json_encode($donationReceived) ?>;
            var DATES = <?php echo json_encode($DATE) ?>;

            var trophyContext = document.getElementById("trophyData");
            var donationsContext = document.getElementById("donationsData");

            var trophyData = new Chart(trophyContext, {
                type: 'line',
                data: {
                    labels: DATES,
                    datasets: [{
                        label: 'Trophy',
                        backgroundColor: "rgba(155, 89, 182, 0.5)",
                        borderColor: "rgb(155, 89, 182)",
                        data: DATATrophy,
                        fill: false,
                        borderWidth: 1,
                        tension: 0.2
                    }]
                },
                options: {
                    animation: false,
                    scales: {                        
                        xAxes: [{
                            display: false
                        }]
                    }
                }
            });

            var donationsData = new Chart(donationsContext, {
                type: 'line',
                data: {
                    labels: DATES,
                    datasets: [{
                        label: 'Troops Donated',
                        backgroundColor: "rgba(201, 63, 63, 0.5)",
                        borderColor: "rgb(201, 63, 63)",
                        data: DATADonations,
                        fill: false,
                        borderWidth: 1
                    }, {
                        label: 'Troops Received',
                        backgroundColor: "rgba(34, 139, 34, 0.5)",
                        borderColor: "rgb(34, 139, 34)",
                        fill: false,
                        data: DATAReceived,
                        borderWidth: 1,
                        tension: 0.2
                    }]
                },
                options: {
                    animation: false,
                    scales: {
                        xAxes: [{
                            display: false
                        }]
                    }
                }
            });
        
        });
    </script>
    <!-- Optionally, you can add Slimscroll and FastClick plugins.
         Both of these plugins are recommended to enhance the
         user experience. Slimscroll is required when using the
         fixed layout. -->
    </body>
</html>