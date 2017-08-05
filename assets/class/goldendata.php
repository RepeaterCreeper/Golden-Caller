<?php
    require_once("medoo.php");
    /* STRUCTURE OF SQL */
    
    # SQL Information here, but hidden.

    //
    $currentWar = $GCaller->get("warlog", "enemyTag", ["status[!]" => 'complete']) ? $GCaller->get("warlog", "enemyTag", ["status[!]" => 'complete']) : null;
?>