<?php
require_once("goldendata.php");
class ClanSeeker {
    private static $apiBase = 'http://107.170.97.36/';

    // Returns a map containing the specified clan's details.
    public static function getClanDetails($id) {
        $queryURL = self::$apiBase . 'clan_details/?id=' . $id;
        return json_decode(file_get_contents($queryURL), true);
    }

    // Returns a map representing fetched JSON data for the user.
    public static function getPlayerDetails($id) {
        $queryURL = self::$apiBase . 'player_village/?id=' . $id;
        return json_decode(file_get_contents($queryURL), true);
    }

}

//Player Village
class PlayerVillage {
    public $name;
    public $id;

    public $level;
    public $league;
    public $trophies;

    public $troopsGiven;
    public $troopsReceived;
    public $warStars;
    public $village;
}

// Struct storing player data, abstraction safety layer.
class PlayerData {
    public $name;
    public $id;

    public $level;
    public $league;
    public $trophies;

    public $clanRole;
    public $troopsGiven;
    public $troopsReceived;
}

// Struct storing basic clan stats.
class ClanInfo {
    public $name;
    public $id;
    public $description;

    public $score;
    public $playerCount;
    public $trophiesRequired;

    public $warsWon;
    public $warsTied;
    public $warsLost;
}

class PlayerParser {
    private $playerData;

    public function __construct($playerData) {
        $this->playerData = $playerData;
    }

    public function getPlayerDetail() {
        $villageRef = $this->playerData["player"]["avatar"];

        $res = new PlayerVillage();
        $res->name = $villageRef["userName"];
        $res->id = $villageRef["userId"];

        $res->level = $villageRef["level"];
        $res->trophies = $villageRef["trophies"];

        $res->troopsGiven = $villageRef["givenTroops"];
        $res->troopsReceived = $villageRef["receivedTroops"];
        $res->village = $this->playerData["player"]["village"];

        $res->achievementsProgress = $villageRef["achievementsProgress"];
        #$res->warStars = 
        $res->troops = $villageRef["troopsLevels"];

        return $res;
    }
}
// Struct used to parse through a clan's JSON data.
class ClanParser {

    private $clanData;

    public function __construct($clanData) {
        $this->clanData = $clanData;
    }

    public function getPlayerCount() {
        return $this->clanData["clan"]['playerCount'];
    }

    // Gets a player from their zero-based index, returning the info as a PlayerData structure.
    public function getPlayer($index) {
        $playerRef = $this->clanData['clan']['players'][$index]['avatar'];

        $res = new PlayerData();
        $res->name = $playerRef['userName'];
        $res->id = $playerRef['userId'];

        $res->level = $playerRef['level'];
        $res->league = $playerRef['league'];
        $res->trophies = $playerRef['trophies'];

        $res->clanRole = $playerRef['clanRole'];
        $res->troopsGiven = $playerRef['givenTroops'];
        $res->troopsReceived = $playerRef['receivedTroops'];

        return $res;
    }

    public function getClanInfo() {
        $res = new ClanInfo();
        $clanRef = $this->clanData["clan"];
        $res->name = $clanRef["name"];
        $res->id = $clanRef['id'];
        $res->description = $clanRef['description'];

        $res->score = $clanRef['score'];
        $res->playerCount = $clanRef['playerCount'];
        $res->trophiesRequired = $clanRef['requiredTrophies'];
        $res->level = $clanRef["level"];
        
        $res->status = $clanRef["status"];
        $res->warsWon = $clanRef["warsWon"];
        $res->warsTied = $clanRef["warsTied"];
        $res->warsLost = $clanRef["warsLost"];

        return $res;
    }

}

// Specific GoldenGators Clash utilities
class GGClashUtil {

    static $leagueList = array('NONE', 'BRONZE_III', 'BRONZE_II', 'BRONZE_I', 'SILVER_III',
                               'SILVER_II', 'SILVER_I', 'GOLD_III', 'GOLD_II', 'GOLD_I',
                               'CRYSTAL_III', 'CRYSTAL_II', 'CRYSTAL_I', 'MASTER_III',
                               'MASTER_II', 'MASTER_I', 'CHAMPION_III', 'CHAMPION_II',
                               'CHAMPION_I', 'TITAN_III', 'TITAN_II', 'TITAN_I', 'LEGEND');

    // Accepts a ClanSeeker league-string and matches it to the appropriate img class.
    static function matchLeagueClass($leagueStr) {
        $league = null;
        switch ($leagueStr) {
            case "NONE": $league = "https://api-assets.clashofclans.com/leagues/72/e--YMyIexEQQhE4imLoJcwhYn6Uy8KqlgyY3_kFV6t4.png"; break;
            case "BRONZE_III": $league = "https://api-assets.clashofclans.com/leagues/72/uUJDLEdAh7Lwf6YOHmXfNM586ZlEvMju54bTlt2u6EE.png"; break;
            case "BRONZE_II": $league = "https://api-assets.clashofclans.com/leagues/72/U2acNDRaR5rQDu4Z6pQKaGcjWm9dkSnHMAPZCXrHPB4.png"; break;
            case "BRONZE_I": $league = "https://api-assets.clashofclans.com/leagues/72/SZIXZHZxfHTmgseKCH6T5hvMQ3JQM-Js2QfpC9A3ya0.png"; break;
            case "SILVER_III": $league = "https://api-assets.clashofclans.com/leagues/72/QcFBfoArnafaXCnB5OfI7vESpQEBuvWtzOyLq8gJzVc.png"; break;
            case "SILVER_II": $league = "https://api-assets.clashofclans.com/leagues/72/8OhXcwDJkenBH2kPH73eXftFOpHHRF-b32n0yrTqC44.png"; break;
            case "SILVER_I": $league = "https://api-assets.clashofclans.com/leagues/72/nvrBLvCK10elRHmD1g9w5UU1flDRMhYAojMB2UbYfPs.png"; break;
            case "GOLD_III": $league = "https://api-assets.clashofclans.com/leagues/72/vd4Lhz5b2I1P0cLH25B6q63JN3Wt1j2NTMhOYpMPQ4M.png"; break;
            case "GOLD_II": $league = "https://api-assets.clashofclans.com/leagues/72/Y6CveuHmPM_oiOic2Yet0rYL9AFRYW0WA0u2e44-YbM.png"; break;
            case "GOLD_I": $league = "https://api-assets.clashofclans.com/leagues/72/CorhMY9ZmQvqXTZ4VYVuUgPNGSHsO0cEXEL5WYRmB2Y.png"; break;
            case "CRYSTAL_III": $league = "https://api-assets.clashofclans.com/leagues/72/Hyqco7bHh0Q81xB8mSF_ZhjKnKcTmJ9QEq9QGlsxiKE.png"; break;
            case "CRYSTAL_II": $league = "https://api-assets.clashofclans.com/leagues/72/jhP36EhAA9n1ADafdQtCP-ztEAQjoRpY7cT8sU7SW8A.png"; break;
            case "CRYSTAL_I": $league = "https://api-assets.clashofclans.com/leagues/72/kSfTyNNVSvogX3dMvpFUTt72VW74w6vEsEFuuOV4osQ.png"; break;
            case "MASTER_III": $league = "https://api-assets.clashofclans.com/leagues/72/pSXfKvBKSgtvfOY3xKkgFaRQi0WcE28s3X35ywbIluY.png"; break;
            case "MASTER_II": $league = "https://api-assets.clashofclans.com/leagues/72/4wtS1stWZQ-1VJ5HaCuDPfdhTWjeZs_jPar_YPzK6Lg.png"; break;
            case "MASTER_I": $league = "https://api-assets.clashofclans.com/leagues/72/olUfFb1wscIH8hqECAdWbdB6jPm9R8zzEyHIzyBgRXc.png"; break;
            case "CHAMPION_III": $league = "https://api-assets.clashofclans.com/leagues/72/JmmTbspV86xBigM7OP5_SjsEDPuE7oXjZC9aOy8xO3s.png"; break;
            case "CHAMPION_II": $league = "https://api-assets.clashofclans.com/leagues/72/kLWSSyq7vJiRiCantiKCoFuSJOxief6R1ky6AyfB8q0.png"; break;
            case "CHAMPION_I": $league = "https://api-assets.clashofclans.com/leagues/72/9v_04LHmd1LWq7IoY45dAdGhrBkrc2ZFMZVhe23PdCE.png"; break;
            case "TITAN_III": $league = "https://api-assets.clashofclans.com/leagues/72/L-HrwYpFbDwWjdmhJQiZiTRa_zXPPOgUTdmbsaq4meo.png"; break;
            case "TITAN_II": $league = "https://api-assets.clashofclans.com/leagues/72/llpWocHlOoFliwyaEx5Z6dmoZG4u4NmxwpF-Jg7su7Q.png"; break;
            case "TITAN_I": $league = "https://api-assets.clashofclans.com/leagues/72/qVCZmeYH0lS7Gaa6YoB7LrNly7bfw7fV_d4Vp2CU-gk.png"; break;
            case "LEGEND": $league = "https://api-assets.clashofclans.com/leagues/72/R2zmhyqQ0_lKcDR5EyghXCxgyC9mm_mVMIjAbmGoZtw.png"; break;
        }

        return $league;
    }

    static function normalizeClanRole($clanRole) {
        return ucwords( str_replace("_", "-", strtolower($clanRole)) );
    }

}

// Main GoldenGators class
class GoldenGators {
	public $kingdom = [
        "Black Badgers"     => "PYU2UUC8",
        "Blue Bearcats"     => "9GUC0VC2",
        "Golden Gators"     => "99VY9JR8",
        "Green Goats"       => "PL2YQGL0",
        "Maroon Monkeys"    => "9QV8GGC9",
        "Orange Otters"     => "Y2Q29R0P",
        "Onyx Orcas"        => "LJJGVQUG",
        "Olympus 300"       => "899UGPPC",
        "Purple Pigs"       => "P2GR9QUJ",
        "Silver Sharks"     => "P9J90Q8U",
        "Violet Vipers"     => "YYP28U90",
        "White Whales"      => "2CC802RY",
        "Yellow Yaks"       => "PGU280PU"
    ];

    private $clanId = 274879258597;
    private $clanMembers = array();
    private $clanInfo = null;
    private $playerCount;            // ( Also available from $clanInfo->playerCount )

    public function setClanId($id, $convert = true) {
    	if ($convert == false) {
        	$this->clanId = $id;
    	} else {
    		$this->clanId = $this::convertToId($id);
    	}
    }

    public function getClanId($id) {
        return $this->clanId;
    }

    public function getPlayerCount() {
        return $this->playerCount;
    }

    // Tag to ID Conversion
    public function convertToId($player_tag) {
        $IDCHARS = str_split('0289PYLQGRJCUV');
        $id = 0;
        $player_tag = preg_replace('/[^' . preg_quote(implode('', $IDCHARS), '/') . ']/i', '', $player_tag);
        
        $fEnd = strlen($player_tag) - 1;
        for ($x = 0; $x <= $fEnd; $x++) {
            $id += array_search($player_tag[$x], $IDCHARS) * pow(count($IDCHARS), ($fEnd - $x));
        }
        
        return (($id & 0xff) * pow(2, 32)) + (($id & 0x7fffffff00) >> 8);
    }

    public function convertToTag($player_id) {
        $IDCHARS = str_split('0289PYLQGRJCUV');
        $tag = '';
        $player_id = (($player_id & 0x7fffffff) << 8) + intval($player_id / pow(2, 32));

        while ($player_id >= count($IDCHARS)) {
            $tag = $IDCHARS[$player_id % count($IDCHARS)] . $tag;
            $player_id = intval($player_id / count($IDCHARS));
        }
        $tag = $IDCHARS[$player_id] . $tag;

        return '#' . $tag;
    }

    // Basic snapshot function
    public function refreshData() {
        $clanData = ClanSeeker::getClanDetails($this->clanId);
        $parser = new ClanParser($clanData);

        $this->clanInfo = $parser->getClanInfo();

        $this->clanMembers = array();
        $this->playerCount = $parser->getPlayerCount();
        for ($x = 0; $x < $this->playerCount; $x++) {
            array_push($this->clanMembers, $parser->getPlayer($x));
        }
    }

    public function printMembers() {
        $playerNum = 1;

        foreach ($this->clanMembers as $curPlayer) {
        	$GoldenData->get("members", "warstars", ["tag" => $curPlayer->id]);
            echo "<tr>";
            echo "<td style='font-family: helvetica; text-align: center; padding-top: 32px; font-weight: bold; color: #000;'>" . $playerNum . "</td>";
            echo "<td><p style='text-align: center;'><img width='64px' height='64px' src='" . GGClashUtil::matchLeagueClass($curPlayer->league) . "'/></p></td>";
            echo "<td style='text-align: center; padding-top: 32px;'>$curPlayer->level</td>";
            echo "<td style='text-align: center; padding-top: 32px;'>$curPlayer->name <br> <span style='font-family: helvetica; font-weight: normal;'>" . GGClashUtil::normalizeClanRole($curPlayer->clanRole) . "</span></td>";
            echo "<td style='text-align: center; padding-top: 32px;'><div class='well' style='margin-bottom: 0; padding: 8px;'>$curPlayer->troopsGiven</div></td>";
            echo "<td style='text-align: center; padding-top: 32px;'><div class='well' style='margin-bottom: 0; padding: 8px;'>$curPlayer->troopsReceived</div></td>";
            echo "<td style='text-align: center; padding-top: 32px;'>$warStars</td>";
            echo "<td style='text-align: center; padding-top: 32px;'><span class='score'>$curPlayer->trophies</span></td>";
            echo "</tr>";

            $playerNum++;
        }
    }

    public function getClanInfo() {
        return $this->clanInfo;
    }

    public function getPlayers() {
        return $this->clanMembers;
    }

    public function getVillage($id) {
        $village = ClanSeeker::getPlayerDetails($id);
        $parser = new PlayerParser($village);
        $result = $parser->getPlayerDetail();

        return $result;
    }
}
?>