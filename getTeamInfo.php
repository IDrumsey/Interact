<?php
//Gets wins and losses, all players, and history
if(!isset($conn)){
    include_once "./dbConn.php";
}
//Get team win loss ratio
$sql = "SELECT numWins, numLosses FROM teamdetails WHERE teamdetails.team_Name = ?";
$stmt = mysqli_stmt_init($conn);
if(mysqli_stmt_prepare($stmt, $sql) == false){
    echo "Error in preparing sql statement";
}
else{
        if(mysqli_stmt_bind_param($stmt, 's', $_GET['team']) == false){
            echo "Error in binding parameters";
        }
        else{
            if(mysqli_execute($stmt) == false){
                echo "Error in running query";
            }
            else{
                $result = mysqli_stmt_get_result($stmt);
                $winLoss = mysqli_fetch_array($result, MYSQLI_ASSOC);
                mysqli_stmt_close($stmt);
            }
        }
}
//Get all players
$teamPlayers = [];
$sql = "SELECT t1.username, t1.id, t1.profileImageSet, t2.rank FROM users t1 INNER JOIN teamassociations t2 ON t2.userID = t1.id WHERE t2.teamID = (SELECT team_ID FROM teamdetails WHERE teamdetails.team_Name = ?);";
$stmt = mysqli_stmt_init($conn);
if(mysqli_stmt_prepare($stmt, $sql) == false){
    echo "Error in preparing sql statement";
}
else{
        if(mysqli_stmt_bind_param($stmt, 's', $_GET['team']) == false){
            echo "Error in binding parameters";
        }
        else{
            if(mysqli_execute($stmt) == false){
                echo "Error in running query";
            }
            else{
                $result = mysqli_stmt_get_result($stmt);
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                    array_push($teamPlayers, $row);
                }
                mysqli_stmt_close($stmt);
            }
        }
}
//Get history
$history = [];
$sql = "SELECT t1.start_date, t1.start_time, t1.end_time, t1.match_winner, t2.team_Name AS 'teamA Name', t2.numWins AS 'teamA Wins', t2.numLosses AS 'teamA Losses', t2.logo_set AS 'teamA Logo', t3.team_Name AS 'teamB Name', t3.numWins AS 'teamB Wins', t3.numLosses AS 'teamB Losses', t3.logo_set AS 'teamB Logo' FROM tournamentmatch t1 INNER JOIN teamdetails t2 ON t2.team_ID = t1.team1_ID INNER JOIN teamdetails t3 ON t3.team_ID = t1.team2_ID WHERE t1.complete_status = 'complete' AND (t2.team_Name = ? OR t3.team_Name = ?)";
$stmt = mysqli_stmt_init($conn);
if(mysqli_stmt_prepare($stmt, $sql) == false){
    echo "Error in preparing sql statement";
}
else{
        if(mysqli_stmt_bind_param($stmt, 'ss', $_GET['team'], $_GET['team']) == false){
            echo "Error in binding parameters";
        }
        else{
            if(mysqli_execute($stmt) == false){
                echo "Error in running query";
            }
            else{
                $result = mysqli_stmt_get_result($stmt);
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                    array_push($history, $row);
                }
                mysqli_stmt_close($stmt);
            }
        }
}
mysqli_close($conn);
?>