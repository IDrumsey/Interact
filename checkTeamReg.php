<?php
if(!isset($conn)){
    include_once "./dbConn.php";
}

//Check if all team members for this team are registered

function getTeamID($team, $conn) {
    //Check if num players on team matches that of those associated to the tournament

    //get team id
    $sql = "SELECT team_ID from teamdetails WHERE team_Name = ?";
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $sql) == false){
        echo "Error in preparing sql statement";
    }
    else{
        if(mysqli_stmt_bind_param($stmt, 's', $team) == false){
            echo "Error in binding parameters";
        }
        else{
            if(mysqli_execute($stmt) == false){
                echo "Error in running query";
            }
            else{
                $result = mysqli_stmt_get_result($stmt);
                $test = mysqli_fetch_array($result, MYSQLI_ASSOC);
                $team_id = $test['team_ID'];
                mysqli_stmt_close($stmt);
            }
        }
    }
    return $team_id;
}

function getTeamPlayers($team_id, $conn){
    //get all players
    $team_players = [];
    $sql = "SELECT userID FROM teamassociations WHERE teamID = ?";
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $sql) == false){
        echo "Error in preparing sql statement";
    }
    else{
        if(mysqli_stmt_bind_param($stmt, 'i', $team_id) == false){
            echo "Error in binding parameters";
        }
        else{
            if(mysqli_execute($stmt) == false){
                echo "Error in running query";
            }
            else{
                $result = mysqli_stmt_get_result($stmt);
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                    array_push($team_players, $row);
                }
                mysqli_stmt_close($stmt);
            }
        }
    }
    return $team_players;
}

function getTournamentID($tournament_name, $conn){
    $sql = "SELECT tournament_ID from tournament WHERE tournament_Name = ?";
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $sql) == false){
        echo "Error in preparing sql statement";
    }
    else{
        if(mysqli_stmt_bind_param($stmt, 's', $tournament_name) == false){
            echo "Error in binding parameters";
        }
        else{
            if(mysqli_execute($stmt) == false){
                echo "Error in running query";
            }
            else{
                $result = mysqli_stmt_get_result($stmt);
                $test = mysqli_fetch_array($result, MYSQLI_ASSOC);
                $tournament_id = $test['tournament_ID'];
                mysqli_stmt_close($stmt);
            }
        }
    }
    return $tournament_id;
}

function getRegisteredPlayers($tournament_id, $team_id, $conn){
    $reg_players = [];
    $sql = "SELECT user_id FROM tournament_player_association WHERE team_id = ? AND tournament_id = ?";
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $sql) == false){
        echo "Error in preparing sql statement";
    }
    else{
        if(mysqli_stmt_bind_param($stmt, 'ii', $team_id, $tournament_id) == false){
            echo "Error in binding parameters";
        }
        else{
            if(mysqli_execute($stmt) == false){
                echo "Error in running query";
            }
            else{
                $result = mysqli_stmt_get_result($stmt);
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                    array_push($reg_players, $row['user_id']);
                }
                mysqli_stmt_close($stmt);
            }
        }
    }
    return $reg_players;
}

function compare($a, $b){
    if($a == $b) return 1;
    else return 0;
}

function incRegTeams($tournament_id, $conn){
    $sql = "UPDATE tournament SET num_teams_registered = num_teams_registered + 1 WHERE tournament_ID = ?";
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $sql) == false){
        echo "Error in preparing sql statement";
    }
    else{
        if(mysqli_stmt_bind_param($stmt, 'i', $tournament_id) == false){
            echo "Error in binding parameters";
        }
        else{
            if(mysqli_execute($stmt) == false){
                echo "Error in running query";
            }
            else{
                mysqli_stmt_close($stmt);
            }
        }
    }
}


//test
$team_id = getTeamID('team1', $conn);
$team_players = getTeamPlayers($team_id, $conn);
$tournament_id = getTournamentID('test tournament 1', $conn);
$reg_players = getRegisteredPlayers($tournament_id, $team_id, $conn);

if(compare(sizeof($team_players), sizeof($reg_players))) {
    echo "All players reg";
    //add 1 to teams reg
    incRegTeams($tournament_id, $conn);
}
else {
    echo "Not all players reg";
}


mysqli_close($conn);
?>