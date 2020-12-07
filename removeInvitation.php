<?php
if(!isset($conn)){
    include_once "./dbConn.php";
}
$invited = $_GET['invited'];
if(isset($_GET['tournament'])){
    $tournamentName = $_GET['tournament'];
    $sql = "DELETE FROM invitations WHERE invited = (SELECT id FROM users WHERE username = ?) AND tournament_ID = (SELECT tournament_ID FROM tournament WHERE tournament_Name = ?);";
}
$stmt = mysqli_stmt_init($conn);
if(mysqli_stmt_prepare($stmt, $sql) == false){
    echo "Error in preparing sql statement";
}
else{
    if(mysqli_stmt_bind_param($stmt, 'ss', $invited, $tournamentName) == false){
        echo "Error in binding parameters";
    }
    else{
        if(mysqli_execute($stmt) == false){
            echo "Error in running query";
        }
        else{
            header("Location: /Interact/bracketVisual/bracket.php?tournamentName=" . $tournamentName);
        }
    }
}
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>