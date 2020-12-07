<?php
session_start();
include_once "./dbConn.php";
if(isset($_GET['team']) && isset($_GET['player'])){
    $sql = "INSERT INTO invitations (invitor, invitations.type, invited, team_ID, invitations.style) VALUES ((SELECT id FROM users WHERE username = ?), 'Team', (SELECT id FROM users WHERE username = ?), (SELECT team_ID FROM teamdetails WHERE teamdetails.team_Name = ?), 'Team');";
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $sql) == false){
        echo "Error in preparing sql statement";
    }
    else{
            if(mysqli_stmt_bind_param($stmt, 'sss', $_SESSION['user'], $_GET['player'], $_GET['team']) == false){
                echo "Error in binding parameters";
            }
            else{
                if(mysqli_execute($stmt) == false){
                    echo "Error in running query";
                }
                else{
                    header("Location: ./teams.create.addPlayer.php?team=" . $_GET['team']);
                }
            }
    }
}
mysqli_close($conn);
?>