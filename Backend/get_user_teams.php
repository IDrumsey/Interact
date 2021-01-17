<?php
    include_once '../main.php';

    //Get team id's for this user
    $player_team_ids = query_player_teams(query_user_id($_SESSION['user'], $conn), $conn);

    //For each team, get the team info

    $teams = [];
    for($i = 0; $i < sizeof($player_team_ids); $i++){
        array_push($teams, get_team_info($player_team_ids[$i], $conn));
    }

    echo json_encode($teams);
    
?>