<?php
	if (isset($_GET['name'])) {
		echo $_GET['name'];
	}
?>
<div class="row">
    <div class="col-md-4 col-sm-6">
        <div class="info-box bg-red">
            <span class="info-box-icon"><i class="ion ion-trophy"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Trophy Rank</span>
                <span class="info-box-number" style="font-size: x-large;"><?= $RANKTrophy; ?> <span style="font-size: smaller">out of</span> <?= $RANKTotal ?></span>
                <span class="progress-description">
                    Kingdom Rank
                </span>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-sm-6">
        <div class="info-box bg-red">
            <span class="info-box-icon"><i class="ion ion-ios-paperplane-outline"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Donation Rank</span>
                <span class="info-box-number" style="font-size: x-large;"><?= $RANKDonation; ?> <span style="font-size: smaller">out of</span> <?= $RANKTotal ?></span>
                <span class="progress-description">
                    Kingdom Rank
                </span>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-sm-6">
        <div class="info-box bg-red">
            <span class="info-box-icon"><i class="ion ion-ios-shuffle"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Donation Ratio Rank</span>
                <span class="info-box-number" style="font-size: x-large;"><?= $RANKRatio; ?> <span style="font-size: smaller">out of</span> <?= $RANKTotal ?></span>
                <span class="progress-description">
                    Kingdom Rank
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row">
   <div class='col-md-6'>
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Trophy</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <canvas id="trophyData" width="400" height="400"></canvas>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
    <div class='col-md-6'>
        <div class="box box-default">
            <div class="box-header with-border">

                <h3 class="box-title">Donations Per Day</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <canvas id="donationsData" width="400" height="400"></canvas>
            </div> 
            <!-- /.box-body -->
        </div>
    </div>
</div>