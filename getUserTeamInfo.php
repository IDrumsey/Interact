<?php
include_once "getUserTeams.php";
if(session_id() == ""){
    session_start();
}
if(!isset($conn)){
    include_once "dbConn.php";
}
$sql = "SELECT teamdetails.team_Name, numWins, numLosses, logo_set, team_ID FROM teamdetails WHERE team_ID = ?";
$stmt = mysqli_stmt_init($conn);
$allTeamInfo = [];
if(mysqli_stmt_prepare($stmt, $sql) == false){
    echo "Error in preparing sql statement";
}
else{
    for($i = 0; $i < sizeof($user_teams); $i++){
        if(mysqli_stmt_bind_param($stmt, 'i', $user_teams[$i]['teamID']) == false){
            echo "Error in binding parameters";
        }
        else{
            if(mysqli_execute($stmt) == false){
                echo "Error in running query";
            }
            else{
                $result = mysqli_stmt_get_result($stmt);
                $team_info = mysqli_fetch_array($result, MYSQLI_ASSOC);
                array_push($allTeamInfo, $team_info);
            }
        }
    }
}
mysqli_close($conn);
?>