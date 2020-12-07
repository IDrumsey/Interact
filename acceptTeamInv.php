<?php
session_start();
include_once "./dbConn.php";
$memberRank = "member";
$sql = "INSERT INTO teamassociations (userID, teamID, teamassociations.rank) VALUES((SELECT id FROM users WHERE username = ?), (SELECT team_id from teamdetails WHERE teamdetails.team_Name = ?), ?);";
$stmt = mysqli_stmt_init($conn);
if(mysqli_stmt_prepare($stmt, $sql) == false){
    echo "Error in preparing sql statement";
}
else{
        if(mysqli_stmt_bind_param($stmt, 'sss', $_SESSION['user'], $_GET['team'], $memberRank) == false){
            echo "Error in binding parameters";
        }
        else{
            if(mysqli_execute($stmt) == false){
                echo "Error in running query";
            }
            else{
                header("Location: ./deleteTeamInv.php?team=" . $_GET['team']);
            }
        }
}
mysqli_close($conn);
?>