<?php
if(session_id() == ""){
    session_start();
}
//Get tournament info
if(!isset($conn)){
    include_once "./dbConn.php";
}
//If adding player
if(isset($_GET['player'])){
$sql = "INSERT INTO tournament_player_association (user_id, tournament_id) VALUES ((SELECT id FROM users WHERE username = ?), ?)";
}
//If adding team
if(isset($_GET['team'])){
$sql = "INSERT INTO tournament_player_association (team_id, tournament_id) VALUES ((SELECT team_ID FROM teamdetails WHERE team.team_Name = ?), ?)";
}
$bindError = 0;
$stmt = mysqli_stmt_init($conn);
if(mysqli_stmt_prepare($stmt, $sql) == false){
    echo "Error in preparing sql statement";
}
else{
    if(isset($_GET['player'])){
        if(mysqli_stmt_bind_param($stmt, 'si', $_GET['player'], $_GET['t']) == false){
            echo "Error in binding parameters";
            $bindError = 1;
        }
    }
    else if(isset($_GET['team'])){
        if(mysqli_stmt_bind_param($stmt, 'si', $_GET['team'], $_GET['t']) == false){
            echo "Error in binding parameters";
            $bindError = 1;
        }
    }
    if($bindError == 0){
        if(mysqli_execute($stmt) == false){
            echo "Error in running query";
            if(isset($_GET['find'])){
                header("Location: tournament.create.php?error=a");
            }
            else{
                header("Location: tournament.create.php?error=b");
            }
        }
        else{
            if(isset($_GET['find'])){
                header("Location: play.home.php?newTournament=success");
            }
            else{
                header("Location: play.home.php?newTournament=success");
            }
        }
    }
}
mysqli_close($conn);
?>