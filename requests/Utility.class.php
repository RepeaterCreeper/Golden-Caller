<?php
	class ClashUtils {
		/**
		 * Turns the current clan role to a clash standards.
		 *
		 * @return string; modified clan role;
		 */
		static function normalizeClanRole($clanRole) {
			$clanRole;
			switch ($clanRole) {
				case "coLeader": $clanRole = "Co-Leader"; break;
				case "admin": $clanRole = "Elder"; break;
				case "leader": $clanRole = "Leader"; break;
				case "member": $clanRole = "Member"; break;
			}

			return $clanRole;
	    }

	    static function donationRatio($donated, $received) {
	    	$ratio;
	    	if (($donated == 0) || ($received == 0)) {
	    		$ratio = 0;
	    	} else {
	    		$ratio = $donated / $received;
	    	}

	    	return round($ratio, 2);
	    }
	    /**
	     * Formats the clan type to a much recognizable one.
	     *
	     * @return string clanType;
	     */
	    static function normalizeClanType($clanType) {
	    	$clanType;
	    	switch ($clanType) {
	    		case "open": $clanType = "Open"; break;
	    		case "inviteOnly": $clanType = "Invite Only"; break;
	    		case "closed": $clanType = "Closed"; break;
	    	}

	    	return $clanType;
	    }

	    /* Golden Gators */
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
	        $timecountdownstart = strtotime("now");
	        if ($format == true) {
	        	$timecountdownend = strtotime($date);
	        	$timeleft = $timecountdownend - $timecountdownstart;
	        	$expTime = sprintf('%02dH %02dM', ($timeleft / 3600), ($timeleft / 60 % 60));
	        	$expRes = (($timeleft / 3600) + ($timeleft / 60 % 60)) > 0 ? $expTime : 0;
	        	$res = $expRes;
	        } else {
	        	$timecountdownend = strtotime($date);
	        	$timeleft = $timecountdownend - $timecountdownstart;
	        	$res = $timeleft;
	        }

	        return $res;
	    }

	    static function convertTH($th) {
	        switch ($th){
	        	case '6': $res = "assets/img/Town_hall6.png"; break;
	            case '7': $res = "assets/img/Town_hall7.png"; break;
	            case '8': $res = "assets/img/Town_hall8.png"; break;
	            case '9': $res = "assets/img/Town_hall9.png"; break;
	            case '10': $res = "assets/img/Town_hall10.png"; break;
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

	    static function isMini($owner, $mini) {
	    	global $GoldenData;
	    	if ($GoldenData->get('miniacc', "*", ["AND" => ["playerTag" => $mini, "ownerTag" => $owner]])) {
	    		return true;
	    	} else {
	    		return false;
	    	}
	    }
	}
?>