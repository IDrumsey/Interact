<?php
session_start();
include_once "./dbConn.php";
$sql = "DELETE FROM invitations WHERE team_ID = (SELECT team_ID FROM teamdetails WHERE team_Name = ?) AND invited = (SELECT id FROM users WHERE username = ?);";
$stmt = mysqli_stmt_init($conn);
if(mysqli_stmt_prepare($stmt, $sql) == false){
    echo "Error in preparing sql statement";
}
else{
        if(mysqli_stmt_bind_param($stmt, 'ss', $_GET['team'], $_SESSION['user']) == false){
            echo "Error in binding parameters";
        }
        else{
            if(mysqli_execute($stmt) == false){
                echo "Error in running query";
            }
            else{
                header("Location: ./dashboard.php?teamJoin=" . $_GET['team'] . "&player=" . $_SESSION['user']);
            }
        }
}
mysqli_close($conn);
?>