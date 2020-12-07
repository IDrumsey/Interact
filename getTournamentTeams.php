<?php
if(!isset($conn)){
    include_once "./dbConn.php";
}
$tournamentName = $_GET['tournamentName'];
$teams = [];
$sql = "SELECT t1.team_Name FROM teamdetails t1 INNER JOIN tournament_team_association t2 ON t2.team_id = t1.team_ID INNER JOIN tournament t3 ON t3.tournament_ID = t2.tournament_id WHERE t3.tournament_Name = ?;";
$stmt = mysqli_stmt_init($conn);
if(mysqli_stmt_prepare($stmt, $sql) == false){
    echo "Error in preparing sql statement";
}
else{
    if(mysqli_stmt_bind_param($stmt, 's', $tournamentName) == false){
        echo "Error in binding parameters";
    }
    else{
        if(mysqli_execute($stmt) == false){
            echo "Error in running query";
        }
        else{
            $result = mysqli_stmt_get_result($stmt);
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                array_push($teams, $row);
            }
        }
    }
}
mysqli_stmt_close($stmt);
?>