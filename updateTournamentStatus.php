<?php
if(!isset($conn)){
    include_once "./dbConn.php";
}
$tStatus = $_GET['tournamentStatus'];
$tournamentName = $_GET['tournamentName'];
$sql = "UPDATE tournament SET tournament.status = ? WHERE tournament.tournament_Name = ?;";
$stmt = mysqli_stmt_init($conn);
if(mysqli_stmt_prepare($stmt, $sql) == false){
    echo "Error in preparing sql statement";
}
else{
    if(mysqli_stmt_bind_param($stmt, 'ss', $tStatus, $tournamentName) == false){
        echo "Error in binding parameters";
    }
    else{
        if(mysqli_execute($stmt) == false){
            echo "Error in running query";
        }
        else{
            mysqli_stmt_close($stmt);
            header("Location: bracketVisual/bracket.php?tournamentName=" . $tournamentName);
        }
    }
}
?>