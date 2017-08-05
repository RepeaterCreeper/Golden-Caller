<?php
	class ClanInfo {
		public $tag;
		public $name;

		public $location;
		public $badge;

		public $clanLevel;
		public $clanPoints;
		public $members;
		public $requiredTrophies;
		public $warWinStreak;
		public $warWins;
		public $warLosses;

		public $type;
		public $warFrequency;
		public $description;

		public $warLogPrivate;
	}

	class PlayerInfo {
		public $id;

        public $tag;
        public $name;

        public $expLevel;
        public $league;
        public $trophies;

        public $role;
        public $clanRank;
        public $previousClanRank;

        public $donations;
        public $donationsReceived;
    }

    class WarInfo {
    	public $tag;
    	public $name;

    	public $allyStar;
    	public $enemyStar;
    	public $warSize;
    	public $timer;

    	public $preparationExp;
    	public $warExp;

    	public $status;
    }

	/**
	 * Structure for parsing the data returned by the Clash API;
	 */
	class InfoParser {

	    private $clanData;

	    public function __construct($clanData) {
	        $this->clanData = $clanData;
	    }

	    public function getPlayerCount() {
	        return $this->clanData['members'];
	    }

	    public function getPlayer($index) {
	        $playerRef = $this->clanData['memberList'][$index];

	        $res = new PlayerInfo();
	        $res->id 				= ClashUtils::convertToId($playerRef["tag"]);

        	$res->tag 				= $playerRef["tag"];
        	$res->name 				= $playerRef["name"];

        	$res->expLevel 			= $playerRef["expLevel"];
        	$res->league 			= $playerRef["league"]["iconUrls"]["small"];
        	$res->trophies 			= $playerRef["trophies"];

        	$res->role 				= ClashUtils::normalizeClanRole($playerRef["role"]);
        	$res->clanRank 			= $playerRef["clanRank"];
        	$res->previousClanRank 	= $playerRef["previousClanRank"];

        	$res->donations 		= $playerRef["donations"];
        	$res->donationsReceived = $playerRef["donationsReceived"];

	        return $res;
	    }

	    public function getClanInfo() {
	    	$clanRef = $this->clanData;
	        $res = new ClanInfo();

	        $res->tag 		 		= $clanRef["tag"];
	        $res->name 		 		= $clanRef["name"];

	        $res->location   		= $clanRef["location"]["id"];
	        $res->badge 	 		= $clanRef["badgeUrls"]["small"];

	        $res->clanLevel  		= $clanRef["clanLevel"];
	        $res->clanPoints 		= $clanRef["clanPoints"];
	        $res->members    		= $clanRef["members"];
	        $res->requiredTrophies  = $clanRef["requiredTrophies"];
	        $res->warWinStreak 		= $clanRef["warWinStreak"];
	        $res->warWins 			= $clanRef["warWins"];
	        #$res->warLosses 		= $clanRef["warLosses"];

	        $res->warFrequency		= $clanRef["warFrequency"];
	        $res->type 				= ClashUtils::normalizeClanType($clanRef["type"]);
	        $res->description 		= $clanRef["description"];

	        $res->warLogPrivate 	= $clanRef["isWarLogPublic"];

        	return $res;
    	}
	}
?>