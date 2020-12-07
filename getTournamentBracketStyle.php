<?php
if(!isset($conn)){
    include_once "./dbConn.php";
}
$tournamentName = $_GET['tournamentName'];
$sql = "SELECT t1.bracket_name FROM bracket_type t1 INNER JOIN tournament t2 ON t2.bracket_type_ID = t1.bracket_ID WHERE t2.tournament_Name = ?;";
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
            $bracketStyle = $row['bracket_name'];
        }
    }
}
mysqli_stmt_close($stmt);
?>