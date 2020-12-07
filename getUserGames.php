<?php
$games = [];
$sql = "SELECT t1.title FROM game t1 INNER JOIN usergameassociations t2 ON t2.game_ID = t1.game_ID INNER JOIN users t3 ON t3.id = t2.user_ID WHERE t3.username = ?;";
$stmt = mysqli_stmt_init($conn);
if(mysqli_stmt_prepare($stmt, $sql) == false){
    echo "Error in preparing sql statement";
}
else{
        if(mysqli_stmt_bind_param($stmt, 's', $_SESSION['user']) == false){
            echo "Error in binding parameters";
        }
        else{
            if(mysqli_execute($stmt) == false){
                echo "Error in running query";
            }
            else{
                $result = mysqli_stmt_get_result($stmt);
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                    array_push($games, $row);
                }
            }
        }
}
?>