<?php
if(!isset($conn)){
    include_once "./dbConn.php";
}
if(session_id() == ""){
    session_start();
}

function getTournamentID($roundID, $conn){
    $sql = "SELECT tournament_id FROM tournament_round WHERE round_id = ?";
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $sql) == false){
        echo "Error in preparing sql statement";
    }
    else{
        if(mysqli_stmt_bind_param($stmt, 'i', $roundID) == false){
            echo "Error in binding parameters";
        }
        else{
            if(mysqli_execute($stmt) == false){
                echo "Error in running query";
            }
            else{
                $result = mysqli_stmt_get_result($stmt);
                $numRows = mysqli_num_rows($result);
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                return $row['tournament_id'];
            }
        }
    }
    mysqli_stmt_close($stmt);
}


    $tID = getTournamentID(37, $conn);
    echo $tID;
?>