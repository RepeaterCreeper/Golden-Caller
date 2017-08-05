<?php
    $i = 1;
?>
<div class="box box-solid box-warning">
    <div class="box-header">
        <h3 class="box-title">Kingdom Leaderboard</h3>
    </div>
           
    <div class="box-body">
        <div id="kingdomLeaderboard_wrapper" style="overflow-y: scroll" class="dataTables_wrapper form-inline dt-bootstrap">
            <div class="row">
                <div class="col-sm-12">
                    <table id="kingdomLeaderboard" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                        <thead>
                            <tr role="row">
                                <th class="sorting" tabindex="0" aria-controls="kingdomLeaderboard" rowspan="1" colspan="1" style="width: 160px;" aria-sort="ascending">#</th>
                                <th class="sorting" tabindex="0" aria-controls="kingdomLeaderboard" rowspan="1" colspan="1" style="width: 160px;" aria-sort="ascending">Player Name</th>
                                <th class="sorting" tabindex="0" aria-controls="kingdomLeaderboard" rowspan="1" colspan="1" style="width: 160px;" aria-sort="ascending">T. Donated</th>
                                <th class="sorting" tabindex="0" aria-controls="kingdomLeaderboard" rowspan="1" colspan="1" style="width: 160px;" aria-sort="ascending">T. Received</th>
                                <th class="sorting" tabindex="0" aria-controls="kingdomLeaderboard" rowspan="1" colspan="1" style="width: 160px;" aria-sort="ascending">Ratio</th>
                                <th class="sorting" tabindex="0" aria-controls="kingdomLeaderboard" rowspan="1" colspan="1" style="width: 160px;" aria-sort="ascending">Trophies</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                                foreach ($GoldenData->select('kingdomplayerleaderboard', '*', ["ORDER" => "trophy DESC", "LIMIT" => 100]) as $player) {
                                    echo "<tr role='row'>";
                                        echo "<td>$i</td>";
                                        echo "<td><img width='32' height='32' src='" . $player['clanBadge'] . "'/> {$player['name']}</td>";
                                        echo "<td>{$player['donation']}</td>";
                                        echo "<td>{$player['donationReceived']}</td>";
                                        echo "<td>" . ClashUtils::donationRatio($player['donation'], $player['donationReceived']) . "</td>";
                                        echo "<td>{$player['trophy']}</td>";
                                    echo "</tr>";
                                    $i++;
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>