<?php
if(session_id() == ""){
    session_start();
}
//Get tournament info
if(!isset($conn)){
    include_once "./dbConn.php";
}
if(session_id() == ""){
    session_start();
}
$tournamentStatus = "pending";
//If setting total prize amount
$sql = "INSERT INTO tournament (totalPrize, bracket_type_ID, num_players_registered, tournament.start_date, tournament.end_date, prize_distribution_ID, tournament_Name, join_Prize_Type, gameID, tournament.status, grouping_style, tournament.owner) VALUES (?, (SELECT bracket_ID FROM bracket_type WHERE bracket_name = ?), 0, ?, ?, (SELECT method_id FROM prize_distribution_method WHERE method_name = ?), ?, ?, (SELECT game_ID FROM game WHERE game.title = ?), ?, ?, ?);";
//If setting entry fee amount
$stmt = mysqli_stmt_init($conn);
if(mysqli_stmt_prepare($stmt, $sql) == false){
    echo "Error in preparing sql statement";
}
else{
    echo $_POST['prizeDistributionType'];
        if(mysqli_stmt_bind_param($stmt, 'issssssssss', intval($_POST['prizeAmount']), $_POST['bracketType'], $_POST['startDate'], $_POST['endDate'], $_POST['prizeDistributionType'], $_POST['tournamentTitle'], $_POST['prizeOption'], $_POST['gameChoices'], $tournamentStatus, $_POST['groupingChoices'], $_SESSION['user']) == false){
            echo "Error in binding parameters";
        }
        else{
            if(mysqli_execute($stmt) == false){
                echo "Error in running query";
                header("Location: tournament.create.php?newTournament=fail");
            }
            else{
                $rowID = mysqli_insert_id($conn);
                header("Location: newTournamentAssociation.php?player=" . $_SESSION['user'] . "&t=" . $rowID);
            }
        }
}
mysqli_close($conn);
?>