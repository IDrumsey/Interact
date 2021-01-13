<?php
    if(!isset($conn)){
        include_once "../dbConn.php";
    }
    if(session_id() == ""){
        session_start();
    }

    //Check if user has authorization to submit winner
    //get team user is associated with in the tournament


    //set winner and loser
    if($_POST['winningTeam'] == $_POST['teamA']){
        $winningTeam = $_POST['teamA'];
        $losingTeam = $_POST['teamB'];
    }
    else{
        $winningTeam = $_POST['teamB'];
        $losingTeam = $_POST['teamA'];
    }

    //get team A name
    $sql = "SELECT team_ID FROM teamdetails WHERE team_Name = ?";
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $sql) == false){
        echo "Error in preparing sql statement";
    }
    else{
        if(mysqli_stmt_bind_param($stmt, 's', $_POST['teamA']) == false){
            echo "Error in binding parameters";
        }
        else{
            if(mysqli_execute($stmt) == false){
                echo "Error in running query";
            }
            else{
                $result = mysqli_stmt_get_result($stmt);
                $teamAID = mysqli_fetch_array($result, MYSQLI_ASSOC)['team_ID'];

            }
        }
    }
    mysqli_stmt_close($stmt);

    //get team B name
    $sql = "SELECT team_ID FROM teamdetails WHERE team_Name = ?";
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $sql) == false){
        echo "Error in preparing sql statement";
    }
    else{
        if(mysqli_stmt_bind_param($stmt, 's', $_POST['teamB']) == false){
            echo "Error in binding parameters";
        }
        else{
            if(mysqli_execute($stmt) == false){
                echo "Error in running query";
            }
            else{
                $result = mysqli_stmt_get_result($stmt);
                $teamBID = mysqli_fetch_array($result, MYSQLI_ASSOC)['team_ID'];
            }
        }
    }
    mysqli_stmt_close($stmt);

    //get round id
    $sql = "SELECT t1.round_id, t1.tournament_id FROM tournament_round t1 INNER JOIN tournament t2 ON t2.tournament_id = t1.tournament_ID WHERE t1.round_number = ? AND t2.tournament_Name = ?";
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $sql) == false){
        echo "Error in preparing sql statement";
    }
    else{
        if(mysqli_stmt_bind_param($stmt, 'is', $_POST['round'], $_POST['tournament']) == false){
            echo "Error in binding parameters";
        }
        else{
            if(mysqli_execute($stmt) == false){
                echo "Error in running query";
            }
            else{
                $result = mysqli_stmt_get_result($stmt);
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                $roundID = $row['round_id'];
                $tournamentID = $row['tournament_id'];
            }
        }
    }
    mysqli_stmt_close($stmt);    

    //get match id
    $sql = "SELECT id FROM tournamentmatch WHERE (team1_ID = ? AND team2_ID = ?) OR (team1_ID = ? AND team2_ID = ?) AND round_ID = ?";
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $sql) == false){
        echo "Error in preparing sql statement";
    }
    else{
        if(mysqli_stmt_bind_param($stmt, 'iiiii', $teamAID, $teamBID, $teamAID, $_POST['teamB'], $roundID) == false){
            echo "Error in binding parameters";
        }
        else{
            if(mysqli_execute($stmt) == false){
                echo "Error in running query";
            }
            else{
                $result = mysqli_stmt_get_result($stmt);
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                $matchID = $row['id'];
            }
        }
    }
    mysqli_stmt_close($stmt);


    //set winning team id
    //check if a is winner
    if($winningTeam == $_POST['teamA']){
        $winningID = $teamAID;
    }
    else if($winningTeam == $_POST['teamB']){
        $winningID = $teamBID;
    }


    //get user id 
    $sql = "SELECT id FROM users WHERE username = ?";
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $sql) == false){
        echo "Error in preparing sql statement";
    }
    else{
        if(mysqli_stmt_bind_param($stmt, 's', $_SESSION['user']) == false){
            echo "Error in binding parameters";
        }
        else{
            if(mysqli_execute($stmt) == false){
                echo "Error in running query";
            }
            else{
                $result = mysqli_stmt_get_result($stmt);
                $userID = mysqli_fetch_array($result, MYSQLI_ASSOC)['id'];
            }
        }
    }
    mysqli_stmt_close($stmt);

    //Get synonomous submissions
    $otherSubs = [];
    $sql = "SELECT * FROM matchwinner WHERE match_ID = ? AND winner_ID = ?";
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $sql) == false){
        echo "Error in preparing sql statement";
    }
    else{
        if(mysqli_stmt_bind_param($stmt, 'ii', $matchID, $winningID) == false){
            echo "Error in binding parameters";
        }
        else{
            if(mysqli_execute($stmt) == false){
                echo "Error in running query";
            }
            else{
                $result = mysqli_stmt_get_result($stmt);
                $numRows = mysqli_num_rows($result);
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                    array_push($otherSubs, $row);
                }
            }
        }
    }
    mysqli_stmt_close($stmt);

    $userSubTeamA = 0;
    $userSubTeamB = 0;



    $sql = "SELECT team_id FROM tournament_player_association WHERE tournament_id = ? AND user_id = ?";
        $stmt = mysqli_stmt_init($conn);
        if(mysqli_stmt_prepare($stmt, $sql) == false){
            echo "Error in preparing sql statement";
        }
    else{
        //check if at least one submission from each team
        for($i = 0; $i < sizeof($otherSubs); $i++){
            $tmpUserID = $otherSubs[$i]['user_ID'];
            //check which team user belongs to
            if(mysqli_stmt_bind_param($stmt, 'ii', $tournamentID, $userID) == false){
                echo "Error in binding parameters";
            }
            else{
                if(mysqli_execute($stmt) == false){
                    echo "Error in running query";
                }
                else{
                    $result = mysqli_stmt_get_result($stmt);
                    $numRows = mysqli_num_rows($result);
                    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                        if($row['team_id'] == $teamAID){
                            $userSubTeamA++;
                        }
                        if($row['team_id'] == $teamBID){
                            $userSubTeamB++;
                        }
                    }
                }
            }
        }
    }
    mysqli_stmt_close($stmt);

    $numSubs = $userSubTeamA + $userSubTeamB;

    if($numSubs == 0){
        //insert sub
        echo "Code: A";
        addSub($conn, $matchID, $winningID, $userID);
    }
    else if($userSubTeamA > 0 && $userSubTeamB > 0){
        echo "enough subs for both teams";
        //check matches
        $same = true;
        $prevWinner = $otherSubs[0]['winner_ID'];
        for($i = 1; $i < sizeof($otherSubs); $i++){
            if($otherSubs[$i]['winner_ID'] != $prevWinner){
                $same = false;
                break;
            }
        }
        //Check curr submission winner
        if($winningID != $prevWinner){
            $same = false;
        }
        if($same){
            //all submissions the same : Set verified winner
            echo "Code: B";
            addSub($conn, $matchID, $winningID, $userID);
        }
        else {
            echo "Code: C";

            //Notify not all winner submissions are the same
            
            //Give option to reset all submissions or change answer
        }
    }
    else{
        echo "Code: A";
    }

    function addSub($conn, $matchID, $winnerID, $userID){
        $sql = "INSERT INTO matchwinner (match_ID, winner_ID, user_ID) VALUES (?,?,?)";
        $stmt = mysqli_stmt_init($conn);
        if(mysqli_stmt_prepare($stmt, $sql) == false){
            echo "Error in preparing sql statement";
        }
        else{
            if(mysqli_stmt_bind_param($stmt, 'iii', $matchID, $winnerID, $userID) == false){
                echo "Error in binding parameters";
            }
            else{
                if(mysqli_execute($stmt) == false){
                    echo "Error in running query";
                }
                else{
                    echo "Done Inserting";
                }
            }
        }
        mysqli_stmt_close($stmt);
    }

    function addVerified($conn, $matchID, $winnerID){
        $sql = "INSERT INTO verified_match_winners (match_ID, winning_team_ID) VALUES (?,?)";
        $stmt = mysqli_stmt_init($conn);
        if(mysqli_stmt_prepare($stmt, $sql) == false){
            echo "Error in preparing sql statement";
        }
        else{
            if(mysqli_stmt_bind_param($stmt, 'ii', $matchID, $winnerID) == false){
                echo "Error in binding parameters";
            }
            else{
                if(mysqli_execute($stmt) == false){
                    return null;
                }
                else{
                    echo "Added Verified User";
                }
            }
        }
        mysqli_stmt_close($stmt);
    }

    function getTournamentID($roundID, $conn){
        $sql = "SELECT tournament_id FROM tournament_round WHERE round_id = ?";
        $stmt = mysqli_stmt_init($conn);
        if(mysqli_stmt_prepare($stmt, $sql) == false){
            echo "Error in preparing sql statement";
        }
        else{
            if(mysqli_stmt_bind_param($stmt, 'i', $roundID) == false){
                echo "Error in binding parameters";
            }
            else{
                if(mysqli_execute($stmt) == false){
                    echo "Error in running query";
                }
                else{
                    $result = mysqli_stmt_get_result($stmt);
                    $numRows = mysqli_num_rows($result);
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    return $row['tournament_id'];
                }
            }
        }
        mysqli_stmt_close($stmt);
    }
    
?>