<?php
if(session_id() == ""){
    session_start();
}
if(!isset($conn)){
    include_once "./dbConn.php";
}
$tournamentName = $_GET['tournamentName'];
if(isset($_GET['team'])){
    $sql = "INSERT INTO tournament_player_association (tournament_player_association.user_ID, tournament_id, team_id) VALUES ((SELECT id FROM users WHERE username = ?), (SELECT tournament_ID FROM tournament where tournament_Name = ?), (SELECT team_ID FROM teamdetails WHERE team_Name = ?));";
}
else{
    $sql = "INSERT INTO tournament_player_association (tournament_player_association.user_ID, tournament_id) VALUES ((SELECT id FROM users WHERE username = ?), (SELECT tournament_ID FROM tournament where tournament_Name = ?));";
}
$stmt = mysqli_stmt_init($conn);
if(mysqli_stmt_prepare($stmt, $sql) == false){
    echo "Error in preparing sql statement";
}
else{
    $bindError = false;
    if(isset($_GET['team'])){
        if(mysqli_stmt_bind_param($stmt, 'sss', $_SESSION['user'], $tournamentName, $_GET['team']) == false){
            echo "Error in binding parameters";
            $bindError = true;
        }
    }
    else{
        if(mysqli_stmt_bind_param($stmt, 'ss', $_SESSION['user'], $tournamentName) == false){
            echo "Error in binding parameters";
            $bindError = true;
        }
    }
    if($bindError == false){
        if(mysqli_execute($stmt) == false){
            echo "Error in running query";
        }
        else{
            if(isset($_GET['team'])){
                header("Location: /Interact/removeInvitation.php?invited=" . $_SESSION['user'] . "&tournament=" . $tournamentName);
            }
            else{
                header("Location: /Interact/bracketVisual/bracket.php?tournamentName=" . $tournamentName);
            }
        }
    }
}
mysqli_close($conn);
?>