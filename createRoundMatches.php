<?php
if(!isset($conn)){
    include_once "dbConn.php";
}
$tournamentName = $_GET['tournamentName'];
/*
Available params: tournament name
check if there's more than one player in the bracket
check bracket type
round robin - player has points
single elim - player has single loss
double elim - player has two losses
double round robin - player has points
check which match has highest round num
check if round num same as tournament num rounds - means tournament over
set winning teams and losing teams
if round robin - 
combine winning and losing and order by points
create matches using all teams
if elim - 
check losing teams for max num losses - out of tournament
choose at random two teams from each player pool and create matches
*/
//get bracket type
$bracketType;
$numRounds;
$sql = "SELECT t1.bracket_name, t2.num_rounds FROM bracket_type t1 INNER JOIN tournament t2 ON t2.bracket_type_ID = t1.bracket_ID WHERE t2.tournament_Name = ?;";
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
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $bracketType = $row['bracket_name'];
            $numRounds = $row['num_rounds'];
        }
    }
}
var_dump($bracketType);
var_dump($numRounds);
//get prevRoundNum
$prevRoundNum = 0;
$sql = "SELECT t1.round_number FROM tournament_round t1 INNER JOIN tournament t2 ON t2.tournament_ID = t1.tournament_id WHERE t2.tournament_Name = ?";
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
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                if($row['round_number'] > $prevRoundNum){
                    $prevRoundNum = $row['round_number'];
                }
            }
        }
    }
}
var_dump($prevRoundNum);
//get matches
$prevRoundMatches = [];
$sql = "SELECT t1.* FROM tournamentmatch t1 INNER JOIN tournament_round t2 ON t2.round_id = t1.round_id WHERE t2.round_number = ?";
$stmt = mysqli_stmt_init($conn);
if(mysqli_stmt_prepare($stmt, $sql) == false){
    echo "Error in preparing sql statement";
}
else{
    if(mysqli_stmt_bind_param($stmt, 'i', $prevRoundNum) == false){
        echo "Error in binding parameters";
    }
    else{
        if(mysqli_execute($stmt) == false){
            echo "Error in running query";
        }
        else{
            $result = mysqli_stmt_get_result($stmt);
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                array_push($prevRoundMatches, $row);
            }
        }
    }
}
print("<pre>" . print_r($prevRoundMatches, true) . "</pre>");
//Get teams
$playingTeams = getTeams($conn);
//init if round 1
if($prevRoundNum == 0){
    echo "Fill in";
}
print("<pre>" . print_r($playingTeams, true) . "</pre>");
//bracket type check if tournament over
if($bracketType == "roundRobin" || $bracketType == "doubleRoundRobin"){
    //check prev round num vs num rounds
    if($numRounds == $prevRoundNum){
        //tournament over - send to results
    }
    else{
        //get new round Num matches
    }
}
function getTeams($conn){
    $teams = [];
    $sql = "SELECT t3.Name FROM tournament_team_association t1 INNER JOIN tournament t2 ON t2.tournament_id = t1.tournament_ID INNER JOIN teamdetails t3 ON t3.team_ID = t1.team_id WHERE t2.tournament_Name = ?;";
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
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                    array_push($teams, $row);
                }
            }
        }
    }
    return $teams;
}
function getNumMatches(){
    $newNumMatches;
    if($bracketType == "roundRobin" || $bracketType == "doubleRoundRobin"){
        $newNumMatches = sizeof($prevRoundMatches);
    }
    else if($bracketType == "singleElim"){
        $newNumMatches = sizeof($prevRoundMatches) / 2;
    }
    else if($bracketType == "doubleElim"){
        echo "Fill in";
    }
}
//if elim separate winners and losers
mysqli_close($conn);
?>