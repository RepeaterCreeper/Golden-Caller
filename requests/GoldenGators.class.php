<?php
    require_once "config.php";
	require_once "Data.class.php";
    require_once "Utility.class.php";

	class APIFetcher {
		private static $_apiToken = "Hidden";
		private static $_apiBase = "https://api.clashofclans.com/v1/";

		public static function queryAPI($url) {
           $in = curl_init();
           
           $params = array("Authorization: Bearer " . self::$_apiToken, "Accept: application/json");
           
           curl_setopt($in, CURLOPT_RETURNTRANSFER, true);
           curl_setopt($in, CURLOPT_HTTPHEADER, $params);
           curl_setopt($in, CURLOPT_URL, self::$_apiBase . $url);
           curl_setopt($in, CURLOPT_SSL_VERIFYPEER, false);

           $result = curl_exec($in);
           curl_close($in);
          
           return $result;
        }

        /**
         * Grabs the clan details based on $id.
         *
         * @param string id
         * @return string; response from API
         */
        public static function getClanDetails($id) {
            $queryUrl = 'clans/%23' . $id;
            return json_decode(APIFetcher::queryAPI($queryUrl), true);
        }
	}

    class ClashofClans {
        private $clanId = "99VY9JR8";
        private $clanInfo = null;
        private $warInfo  = null;
        private $playerCount;

        public $clanMembers = [];
        public $clanMembersName = [];

        public $inWar;

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

        public function __construct() {
            global $GCaller;
            $this->warInfo = $GCaller->get('warlog', '*', ["status[!]" => 'complete']);
            if ($this->warInfo) {
                $this->inWar = true;
            } else {
                $this->inWar = false;
            }
        }
        /**
         * Used for retrieving new data based upon the clanID set
         *
         * Note: You must call this function whenever setting new clanID
         */
        public function refreshData() {
            $clanData = APIFetcher::getClanDetails($this->clanId);
            $parser = new InfoParser($clanData);

            $this->clanInfo = $parser->getClanInfo();

            $this->clanMembers = array();
            $this->playerCount = $parser->getPlayerCount();
            for ($x = 0; $x < $this->playerCount; $x++) {
                array_push($this->clanMembers, $parser->getPlayer($x));
            }

            foreach ($this->clanMembers as $member) {
                array_push($this->clanMembersName, $member->name);
            }
        }

        /**
         * Functions that retrieve and set clan/player data
         */

        /**
         * Sets the clan id to be retrieved;
         * 
         * @param string id;
         */
        public function setClanId($id) {
            $this->clanId = $id;
        }

        /**
         * returns the current id being used;
         *
         * @return string; clanId currently set.
         */
        public function getClanId() {
            return $this->clanId;
        }

        /**
         * returns the player count of the current clan
         *
         * @return string playerCount;
         */
        public function getPlayerCount() {
            return $this->playerCount;
        }

        /**
         * Retrieve data for a specific player inside the clan.
         */
        public function getPlayer($name) {
            $i = 0;
            $res = "Not Found!";
            foreach ($this->clanMembers as $member){
                if (strpos($name, "#") === false) {
                    if ($member->name == $name) {
                        $res = $this->clanMembers[$i];
                    } else {
                        $i++;
                    }
                } else {
                    if ($member->tag == $name) {
                        $res = $this->clanMembers[$i];
                    } else {
                        $i++;
                    }
                }
            }

            return $res;
        }

        public function getWar($data) {
            if ($this->warInfo[$data]) {
                return $this->warInfo[$data];
            } else {
                return false;
            }
        }
        /**
         * Main functions used for mass retrieve
         */

        /**
         * Used for retrieving current clan info using clan structure.
         *
         * @return array clanInfo;
         */
        public function getClanInfo() {
            return $this->clanInfo;
        }

        public function getWarInfo() {
            return $this->warInfo;
        }

        public function getClan($id, $members = false) {
            $clanData = APIFetcher::getClanDetails($id);
            $parser = new InfoParser($clanData);

            $clanMembers = [];

            if ($members == true) {
                for ($x = 0; $x < $parser->getPlayerCount(); $x++) {
                    array_push($clanMembers, $parser->getPlayer($x));
                }
                $result = $clanMembers;
            } else {
                $result = $parser->getClanInfo();
            }

            return $result;
        }

        /*public function getClanPlayers($id) {
            $clanData = APIFetcher::getClanDetails($id);
            $parser = new InfoParser($clanData);

            $clanMembers = [];
            $playerCount = $parser->getPlayerCount();
            for ($x = 0; $x < $playerCount; $x++) {
                arraY_push($clanMembers, $parser->getPlayer($x));
            }

            return $clanMembers;
        }*/
        /**
         * Used for retrieving current clan's players' info using player structure;
         *
         * @return array clanMembers;
         */
        public function getPlayers() {
            return $this->clanMembers;
        }
    }
?>