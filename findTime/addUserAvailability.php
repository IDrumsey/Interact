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
    //check if all match users have submitted times
    $team1Players = getPlayers($_GET['team1'], $conn);
    $team2Players = getPlayers($_GET['team2'], $conn);

    $playersAccounted = getNumPlayers($match_id, $conn);

    if(sizeof($playersAccounted) == sizeof(array_merge($team1Players, $team2Players))){
        echo "Finding match time";
        //Redirect to find time alg
        header("Location: /Interact/findTime/converge.html?match=" . $match_id);
    }

    function getPlayers($teamName, $conn){
        $teamPlayers = [];
        $sql = "SELECT t1.id FROM users t1 INNER JOIN teamassociations t2 ON t2.userID = t1.id WHERE t2.teamID = (SELECT team_ID FROM teamdetails WHERE teamdetails.team_Name = ?);";
        $stmt = mysqli_stmt_init($conn);
        if(mysqli_stmt_prepare($stmt, $sql) == false){
            echo "Error in preparing sql statement";
        }
        else{
                if(mysqli_stmt_bind_param($stmt, 's', $teamName) == false){
                    echo "Error in binding parameters";
                }
                else{
                    if(mysqli_execute($stmt) == false){
                        echo "Error in running query";
                    }
                    else{
                        $result = mysqli_stmt_get_result($stmt);
                        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                            array_push($teamPlayers, $row);
                        }
                    }
                }
        }
        return $teamPlayers;
    }

    function getNumPlayers($match, $conn){
        $players = [];
        $sql = "SELECT * FROM player_match_times WHERE match_ID = ?";
        $stmt = mysqli_stmt_init($conn);
        if(mysqli_stmt_prepare($stmt, $sql) == false){
            echo "Error in preparing sql statement";
        }
        else{
            if(mysqli_stmt_bind_param($stmt, 's', $match) == false){
                echo "Error in binding parameters";
            }
            else{
                if(mysqli_execute($stmt) == false){
                    echo "Error in running query";
                }
                else{
                    $result = mysqli_stmt_get_result($stmt);
                    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                        $tmp = inArr($row['player_ID'], $players);
                        if($tmp == 0){
                            array_push($players, $row['player_ID']);
                        }
                    }
                }
            }
        }
        mysqli_stmt_close($stmt);
        return $players;
    }

    function inArr($val, $arr){
        for($c = 0; $c < sizeof($arr); $c++){
            if($arr[$c] == $val){
                return 1;
            }
        }
        return 0;
    }
?>