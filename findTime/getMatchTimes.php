<?php
    if(!isset($conn)){
        include_once "../dbConn.php";
    }
    $match_id = $_POST['match'];
    $players = [];
    $sql = "SELECT * FROM player_match_times WHERE match_ID = ? ORDER BY player_ID ASC";
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $sql) == false){
        echo "Error in preparing sql statement";
    }
    else{
        if(mysqli_stmt_bind_param($stmt, 's', $match_id) == false){
            echo "Error in binding parameters";
        }
        else{
            if(mysqli_execute($stmt) == false){
                echo "Error in running query";
            }
            else{
                $result = mysqli_stmt_get_result($stmt);
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                    array_push($players, $row);
                }
            }
        }
    }
    echo json_encode($players);
    mysqli_stmt_close($stmt);
?>