<?php
    $intervals = json_decode(file_get_contents("php://input"));
    if(!isset($conn)){
        include_once "../dbConn.php";
    }
    if(session_id() == ""){
        session_start();
    }
    
    $err = 0;
    
    //Get match id
    $sql = "SELECT t1.id FROM tournamentmatch t1 INNER JOIN tournament_round t2 ON t2.round_id = t1.round_ID INNER JOIN tournament t3 ON t3.tournament_ID = t2.tournament_id WHERE (t1.team1_ID = (SELECT team_ID FROM teamdetails WHERE teamdetails.team_Name = ?) OR t1.team2_ID = (SELECT team_ID FROM teamdetails WHERE teamdetails.team_Name = ?)) AND (t1.team1_ID = (SELECT team_ID FROM teamdetails WHERE teamdetails.team_Name = ?) OR t1.team2_ID = (SELECT team_ID FROM teamdetails WHERE teamdetails.team_Name = ?)) AND t2.round_number = ? AND t3.tournament_Name = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $sql) == false){
        echo "Error in preparing sql statement";
    }
    else{
        if(mysqli_stmt_bind_param($stmt, 'ssssis', $_GET['team1'], $_GET['team1'], $_GET['team2'], $_GET['team2'], $_GET['round'], $_GET['tournament']) == false){
            echo "Error in binding parameters";
        }
        else{
            if(mysqli_execute($stmt) == false){
                echo "Error in running query";
            }
            else{
                $result = mysqli_stmt_get_result($stmt);
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                $match_id = $row['id'];
            }
        }
    }
    mysqli_stmt_close($stmt);
    
    $sql = "INSERT INTO player_match_times (player_ID, match_ID, match_date, start_time, end_time) VALUES ((SELECT id FROM users WHERE username = ?), ?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $sql) == false){
        echo "Error in preparing sql statement";
    }
    else{
        for($i = 0; $i < sizeof($intervals); $i++){
            //Loop through time ints
            $date = $intervals[$i]->date;
            $day = $intervals[$i]->times;
            for($j = 0; $j < sizeof($day); $j++){
                $startTime = $day[$j]->start;
                $endTime = $day[$j]->end;
                if(mysqli_stmt_bind_param($stmt, 'sisss', $_SESSION['user'], $match_id, $date, $startTime, $endTime) == false){
                    echo "Error in binding parameters";
                }
                else{
                    if(mysqli_execute($stmt) == false){
                        $err = 1;
                    }
                }
            }
        }
    }
    mysqli_stmt_close($stmt);
    if($err == 1){
        echo "Error";
    }
    else{
        echo "Success";
    }
?>