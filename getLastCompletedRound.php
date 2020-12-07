<?php
    if(!isset($conn)){
        include_once "dbConn.php";
    }
    //Get last round
    $lastRoundNum = 0;
    $tournamentName = $_GET['tournamentName'];
    $sql = "SELECT t1.*, t3.bracket_name FROM tournament_round t1 INNER JOIN tournament t2 ON t2.tournament_ID = t1.tournament_id INNER JOIN bracket_type t3 ON t2.bracket_type_ID = t3.bracket_ID WHERE t2.tournament_Name = ? AND t1.status = 'complete';";
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $sql) == false){
        echo "Error in preparing sql statement";
    }
    else{
        if(mysqli_stmt_bind_param($stmt, 's', $tournamentName) == false){
            echo "Error in binding parameters";
        }
        else{
            if(mysqli_execute($stmt) == false){
                echo "Error in running query";
            }
            else{
                $result = mysqli_stmt_get_result($stmt);
                $resultRow;
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                    $resultRow = $row;
                    if($row['round_number'] > $lastRoundNum){
                        $lastRoundNum = $row['round_number'];
                    }
                }
                $bracketStyle = $resultRow['bracket_name'];
            }
        }
    }
    mysqli_stmt_close($stmt);
    //Get last round matches
    $lastRoundMatches = [];
    $sql = "SELECT t1.*, t4.team_Name AS 'teamAName', t5.team_Name AS 'teamBName', t6.player_points AS 'teamAPoints', t7.player_points AS 'teamBPoints', t6.player_losses AS 'teamALosses', t7.player_losses AS 'teamBLosses' FROM tournamentmatch t1 INNER JOIN tournament_round t2 ON t2.round_id = t1.round_id INNER JOIN tournament t3 ON t3.tournament_ID = t2.tournament_id INNER JOIN teamdetails t4 ON t4.team_ID = t1.team1_ID INNER JOIN teamdetails t5 ON t5.team_ID = t1.team2_ID INNER JOIN round_stats t6 ON t6.round_id = t1.round_id AND t6.team_id = t1.team1_ID INNER JOIN round_stats t7 ON t7.round_id = t1.round_id AND t7.team_id = t1.team1_ID WHERE t2.round_number = ? AND t3.tournament_Name = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $sql) == false){
        echo "Error in preparing sql statement";
    }
    else{
        if(mysqli_stmt_bind_param($stmt, 'is', $lastRoundNum, $tournamentName) == false){
            echo "Error in binding parameters";
        }
        else{
            if(mysqli_execute($stmt) == false){
                echo "Error in running query";
            }
            else{
                $result = mysqli_stmt_get_result($stmt);
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                    array_push($lastRoundMatches, $row);
                }
            }
        }
    }
    mysqli_stmt_close($stmt);
?>