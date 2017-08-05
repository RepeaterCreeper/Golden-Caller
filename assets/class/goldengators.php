<?php
require_once("goldendata.php");

class GGClashUtil {

    static function normalizeClanRole($clanRole) {
        return ucwords( str_replace("_", "-", strtolower($clanRole)) );
    }

    static function getExpiration($playerTag, $num) {
        global $GCaller;
        date_default_timezone_get("America/New York");
        $timecountdownend = strtotime($GCaller->get("calls", "expiration", ["AND" => ["status" => 'ACTIVE', "callNum" => $num, "playerTag" => $playerTag]]));
        #$timecountdownstart = strtotime("-8 hour");
        $timecountdownstart = strtotime("now");
        $timeleft = $timecountdownend - $timecountdownstart;
        
        return $timeleft;
    }

    static function advancedGetExpiration($date, $format = true) {
        global $GCaller;
        date_default_timezone_get("America/New York");
        $timecountdownend = strtotime($date);
        #$timecountdownstart = strtotime("-8 hour");
        $timecountdownstart = strtotime("now");
        $timeleft = $timecountdownend - $timecountdownstart;
        $expTime = sprintf('%02dH %02dM', ($timeleft / 3600), ($timeleft / 60 % 60));
        $expRes = (($timeleft / 3600) + ($timeleft / 60 % 60)) > 0 ? $expTime : 0;
        return $expRes;
    }

    static function convertTH($th) {
        switch ($th){
            case '7': $res = "assets/img/Town_hall7.png"; break;
            case '8': $res = "assets/img/Town_hall8.png"; break;
            case '9': $res = "assets/img/Town_hall9.png"; break;
        }

        return $res;
    }
    // Tag to ID Conversion
    static function convertToId($player_tag) {
        $IDCHARS = str_split('0289PYLQGRJCUV');
        $id = 0;
        $player_tag = preg_replace('/[^' . preg_quote(implode('', $IDCHARS), '/') . ']/i', '', $player_tag);
        
        $fEnd = strlen($player_tag) - 1;
        for ($x = 0; $x <= $fEnd; $x++) {
            $id += array_search($player_tag[$x], $IDCHARS) * pow(count($IDCHARS), ($fEnd - $x));
        }
        
        return (($id & 0xff) * pow(2, 32)) + (($id & 0x7fffffff00) >> 8);
    }

    static function convertToTag($player_id) {
        $IDCHARS = str_split('0289PYLQGRJCUV');
        $tag = '';
        $player_id = (($player_id & 0x7fffffff) << 8) + intval($player_id / pow(2, 32));

        while ($player_id >= count($IDCHARS)) {
            $tag = $IDCHARS[$player_id % count($IDCHARS)] . $tag;
            $player_id = intval($player_id / count($IDCHARS));
        }
        $tag = $IDCHARS[$player_id] . $tag;

        return $tag;
    }

    static function clean($string) {
       $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
       $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

       return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
    }

    static function getWar() {
        global $GCaller;
        $currentWar = $GCaller->get("warlog", "enemyTag", ["status[!]" => 'complete']) ? $GCaller->get("warlog", "enemyTag", ["status[!]" => 'complete']) : null;
        $warSize = $GCaller->get("warlog", "warsize", ["status[!]" => 'complete']) ? $GCaller->get("warlog", "warsize", ["status[!]" => 'complete']) : null;

        return [$currentWar, $warSize];
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

    public $admins = [
        "Y2PVUQ8L", #Princess A
        "V0UR8RL0", #Ironman
        "29UP9R0LP", #Ruff Drum
        "GU0JCJ00", #ZeroCool
        "28JVGV92L" #RepeaterCreeper

    ];

    public $currentWar;
    public $warSize;
    
    public function __construct() {
        global $GCaller;
        $this->currentWar = $GCaller->get("warlog", "enemyTag", ["status[!]" => 'complete']) ? $GCaller->get("warlog", "enemyTag", ["status[!]" => 'complete']) : null;
        $this->warSize = $GCaller->get("warlog", "warsize", ["status[!]" => 'complete']) ? $GCaller->get("warlog", "warsize", ["status[!]" => 'complete']) : null;
    }
}
?>