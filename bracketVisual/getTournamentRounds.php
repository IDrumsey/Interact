<?php
if(!isset($conn)){
    include_once "../dbConn.php";
}
if(isset($_GET['tournamentName'])){
    //Get data for each match of rounds which have completed matches
    $rounds = [];
    $currRoundMatches = [];
    $currRound = 0;
    $lastRoundNum = 1;
    $numResults = 0;
    $sql = "SELECT t1.start_date, t1.start_time, t1.end_time, t2.round_number, t4.team_Name AS 'team1Name', t5.team_Name AS 'team2Name', t1.complete_status, t1.match_winner, t1.match_loser FROM tournamentmatch t1 INNER JOIN tournament_round t2 ON t2.round_id = t1.round_id INNER JOIN tournament t3 ON t3.tournament_ID = t2.tournament_id INNER JOIN teamdetails t4 ON t4.team_ID = t1.team1_ID INNER JOIN teamdetails t5 ON t5.team_ID = t1.team2_ID WHERE t3.tournament_Name = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $sql) == false){
        echo "Error in preparing sql statement";
    }
    else{
            if(mysqli_stmt_bind_param($stmt, 's', $_GET['tournamentName']) == false){
                echo "Error in binding parameters";
            }
            else{
                if(mysqli_execute($stmt) == false){
                    echo "Error in running query";
                }
                else{
                    $result = mysqli_stmt_get_result($stmt);
                    $numResults = mysqli_num_rows($result);
                    if($numResults > 0){
                        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                            if(!isset($rounds[$row['round_number']])){
                                $rounds[$row['round_number']] = [];
                            }
                            array_push($rounds[$row['round_number']], $row);
                            $lastRoundNum = $row['round_number'];
                        }
                    }
                }
            }
    }
}
//Get tournament status
$sql = "SELECT t1.status FROM tournament t1 WHERE t1.tournament_Name = ?";
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $sql) == false){
        echo "Error in preparing sql statement";
    }
    else{
            if(mysqli_stmt_bind_param($stmt, 's', $_GET['tournamentName']) == false){
                echo "Error in binding parameters";
            }
            else{
                if(mysqli_execute($stmt) == false){
                    echo "Error in running query";
                }
                else{
                    $result = mysqli_stmt_get_result($stmt);
                    $tournament_status = mysqli_fetch_array($result, MYSQLI_ASSOC);
                }
            }
    }
mysqli_close($conn);
?>