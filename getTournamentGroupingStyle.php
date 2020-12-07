<?php
$documentPath = $_SERVER['DOCUMENT_ROOT'];
if(!isset($conn)){
    include_once $documentPath . "/Interact/dbConn.php";
}
$tournamentName = $_GET['tournamentName'];
$sql = "SELECT grouping_style FROM tournament WHERE tournament_Name = ?;";
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
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $groupingStyle = $row['grouping_style'];
        }
    }
}
mysqli_close($conn);
?>