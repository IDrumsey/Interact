<?php
if(session_id() == ""){
    session_start();
}
if(!isset($conn)){
    include_once "dbConn.php";
}
if(isset($userPlayer)){
    $playerName = $_SESSION['user'];
}
else{
    $playerName = $_GET['player'];
}
$user_teams = [];
$sql = "SELECT t1.teamID, t3.team_Name FROM teamassociations t1 INNER JOIN users ON t1.userID = users.id INNER JOIN teamdetails t3 ON t3.team_ID = t1.teamID WHERE users.username = ?;";
$stmt = mysqli_stmt_init($conn);
if(mysqli_stmt_prepare($stmt, $sql) == false){
    echo "Error in preparing sql statement";
}
else{
    if(mysqli_stmt_bind_param($stmt, 's', $playerName) == false){
        echo "Error in binding parameters";
    }
    else{
        if(mysqli_execute($stmt) == false){
            echo "Error in running query";
        }
        else{
            $result = mysqli_stmt_get_result($stmt);
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                array_push($user_teams, $row);
            }
        }
    }
}
?>