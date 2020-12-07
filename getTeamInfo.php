<?php
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
            }
        }
}
mysqli_close($conn);
?>