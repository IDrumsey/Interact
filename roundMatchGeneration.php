<?php
if(!isset($conn)){
    include_once "dbConn.php";
}
//Classes
class Round {
    public $roundNumber;
    public $tournamentName;
    public $players;
    public $bracketStyle;
    public $matches;
    public $brackets;

    public function __construct($roundNumber, $tournamentName, $bracketStyle, $players){
        $this->roundNumber      = $roundNumber;
        $this->tournamentName   = $tournamentName;
        $this->bracketStyle     = $bracketStyle;
        $this->matches          = [];
        $this->brackets         = [];
        $this->players          = $players;
    }

    public function logPlayers(){
        prettyPrint($this->players);
    }

    public function addMatch($newMatch){
        array_push($this->matches, $newMatch);
    }

    public function findGroup($groupTitle){
        for($i = 0; $i < sizeof($this->players); $i++){
            if($this->players[$i]->groupName == $groupTitle){
                return $this->players[$i];
            }
        }
    }

    public function addBracket($newBracket){
        array_push($this->brackets, $newBracket);
    }

    public function extractPlayers(){
        for($w = 0; $w < sizeof($this->matches); $w++){
            array_push($this->players, $this->matches[$w]->teamA);
            array_push($this->players, $this->matches[$w]->teamB);
        }
    }

    public function setGroupWinLoss(){
        for($i = 0; $i < sizeof($this->matches); $i++){
            $this->matches[$i]->setGroupMatchStatus();
        }
    }
}

class Bracket {
    public $bracketName;
    public $players;

    public function __construct($bracketName, $players){
        $this->bracketName  = $bracketName;
        $this->players      = $players;
    }

    public function logPlayers(){
        prettyPrint($this->players);
    }

    public function addPlayer($newPlayer){
        array_push($this->players, $newPlayer);
    }
}

class Group {
    public $groupName;
    public $points;
    public $numLosses;
    public $matchStatus;

    public function __construct($groupName, $points, $numLosses, $matchStatus){
        $this->groupName    = $groupName;
        $this->points       = $points;
        $this->numLosses    = $numLosses;
        $this->matchStatus  = $matchStatus;
    }

    public function logGroupDetails(){
        prettyPrint($this->groupName);
        prettyPrint($this->points);
        prettyPrint($this->numWins);
        prettyPrint($this->numLosses);
        prettyPrint($this->matchStatus);
    }

    public function setMatchStatus($status){
        $this->matchStatus = $status;
    }
}

class Match {
    public $teamA;
    public $teamB;
    public $matchStatus;
    public $matchWinner;

    public function __construct($teamA, $teamB, $matchStatus){
        $this->teamA    = $teamA;
        $this->teamB    = $teamB;
        $this->matchStatus = $matchStatus;
    }

    public function setWinner($winner){
        $this->matchWinner = $winner;
    }

    public function setGroupMatchStatus(){
        if($this->matchWinner == "teamA"){
            $this->teamA->setMatchStatus("Winner");
            $this->teamB->setMatchStatus("Loser");
        }
        else{
            $this->teamB->setMatchStatus("Winner");
            $this->teamA->setMatchStatus("Loser");
        }
    }
}



//Prereqs
$roundNumber = 0;
$tournamentName = $_GET['tournamentName'];

include_once "getTournamentBracketStyle.php";

//Create last round
$lastRound = new Round($roundNumber - 1, $tournamentName, $bracketStyle, []);

if($roundNumber == 0){
    //Get all teams or players and push into array
    include_once "getTournamentTeams.php";

    //create round groups
    $nextRoundGroups = [];
    for($i = 0; $i < sizeof($teams); $i++){
        $tmpTeamName = $teams[$i]['team_Name'];
        $tmpTeamPoints = 0;
        $tmpTeamLosses = 0;
        //Create groups
        $tmpGroup = new Group($tmpTeamName, $tmpTeamPoints, $tmpTeamLosses, "pending");
        array_push($nextRoundGroups, $tmpGroup);
    }

    //create next round
    $nextRound = new Round($roundNumber, $tournamentName, $bracketStyle, $nextRoundGroups);

    //Separate groups into brackets
    splitBrackets($nextRound);

    //Order brackets by points
    orderBrackets($nextRound);

    //Create new matches
    createMatches($nextRound);

    //Check if winner
    if(sizeof($nextRound->matches) == 0){
        echo "Winner";
        //set round complete and tournament as complete
    }

    //push info
    addRound($nextRound, $conn);

    //Set tournament status
    header("Location: updateTournamentStatus.php?tournamentName=" . $tournamentName . "&tournamentStatus=Started");
}
else{
    //Get last round info
    include_once "getLastCompletedRound.php";

    $roundNumber = $lastRoundNum;

    //Set team or player points
    for($i = 0; $i < sizeof($lastRoundMatches); $i++){
        $teamAName = $lastRoundMatches[$i]['teamAName'];
        $teamBName = $lastRoundMatches[$i]['teamBName'];
        //Create Groups
        $tmpGroupA = new Group($teamAName, $lastRoundMatches[$i]['teamAPoints'], $lastRoundMatches[$i]['teamALosses'], "pending");
        $tmpGroupB = new Group($teamBName, $lastRoundMatches[$i]['teamBPoints'], $lastRoundMatches[$i]['teamBLosses'], "pending");
        //create match
        $tmpMatch = new Match($tmpGroupA, $tmpGroupB, $lastRoundMatches[$i]['complete_status']);
        //Set match winner
        $tmpMatch->setWinner(getMatchWinner($lastRoundMatches[$i]));
        $lastRound->addMatch($tmpMatch);
    }

    //Set round players
    $lastRound->extractPlayers();

    //set last round group match status
    $lastRound->setGroupWinLoss();

    //Form next round groups with updated points and losses
    $nextRoundGroups = setCurrRoundGroups($lastRound->players, $roundNumber);

    //create next round
    $nextRound = new Round($roundNumber, $tournamentName, $bracketStyle, $nextRoundGroups);
    
    //Separate groups into brackets
    splitBrackets($nextRound);

    //Order brackets by points
    orderBrackets($nextRound);

    //Create new matches
    createMatches($nextRound);

    //Check if winner
    if(sizeof($nextRound->matches) == 0){
        echo "Winner";
        //set round complete and tournament as complete
    }

    //push info
    addRound($nextRound, $conn);

    header("Location: bracketVisual/bracket.php?tournamentName=" . $tournamentName);
}

function getMatchWinner($matchRaw){
    if($matchRaw['match_winner'] == $matchRaw['team1_ID']){
        return 'teamA';
    }
    else {
        return 'teamB';
    }
}

function setCurrRoundGroups($groupsList, $roundNumber){
    $currRoundGroups = [];
    if($roundNumber == 0){
        //Set preset values
        for($i = 0; $i < sizeof($groupsList); $i++){
            $tmpGroup = new Group($groupsList[$i], 0, 0, "pending");
            array_push($groupsList, $tmpGroup);
        }
    }
    else{
        for($i = 0; $i < sizeof($groupsList); $i++){
            if($groupsList[$i]->matchStatus == "Winner"){
                $pointsChange = 2;
                $matchesLostChange = 0;
            }
            else{
                $pointsChange = 0;
                $matchesLostChange = 1;
            }
            //Make copy of group
            $tempTeam = $groupsList[$i];
            $currRoundTempGroup = new Group($tempTeam->groupName, $tempTeam->points + $pointsChange, $tempTeam->numLosses + $matchesLostChange, "pending");
            array_push($currRoundGroups, $currRoundTempGroup);
        }
    }
    return $currRoundGroups;
}


//Split brackets
function splitBrackets(&$currRound){
    if($currRound->bracketStyle == "Single Elimination"){
        $winningBracketPlayers = [];
        for($i = 0; $i < sizeof($currRound->players); $i++){
            if($currRound->players[$i]->numLosses < 1){
                array_push($winningBracketPlayers, $currRound->players[$i]);
            }
        }
        $bracketMain = new Bracket("Winning", $winningBracketPlayers);
        $currRound->addBracket($bracketMain);
    }
    else if($currRound->bracketStyle == "Double Elimination"){
        $winningBracketPlayers = [];
        $losingBracketPlayers = [];
        for($i = 0; $i < sizeof($currRound->players); $i++){
            if($currRound->players[$i]->numLosses < 1){
                array_push($winningBracketPlayers, $currRound->players[$i]);
            }
            else if($currRound->players[$i]->numLosses == 1){
                array_push($losingBracketPlayers, $currRound->players[$i]);
            }
        }
        $winningBracket = new Bracket("Winning", $winningBracketPlayers);
        $losingBracket = new Bracket("Losing", $losingBracketPlayers);
        $currRound->addBracket($winningBracket);
        $currRound->addBracket($losingBracket);
    }
    else{
        $bracketMainPlayers = [];
        for($i = 0; $i < sizeof($currRound->players); $i++){
            array_push($bracketMainPlayers, $currRound->players[$i]);
        }
        $bracketMain = new Bracket("Main", $bracketMainPlayers);
        $currRound->addBracket($bracketMain);
    }
}


//Order all the round's brackets
function orderBrackets($currRound){
    for($i = 0; $i < sizeof($currRound->brackets); $i++){
        orderGroupsByPoints($currRound->brackets[$i]->players);
    }
}

//form new round matches
function createMatches($currRound){
    for($i = 0; $i < sizeof($currRound->brackets); $i++){
        formMatches($currRound->brackets[$i]->players, $currRound->matches);
    }
}



function orderGroupsByPoints(&$arrayToOrder){
    for($i = 0; $i < sizeof($arrayToOrder); $i++){
        for($j = 0; $j < sizeof($arrayToOrder); $j++){
            if($arrayToOrder[$i]->points < $arrayToOrder[$j]->points){
                //switch
                $tempHolder = $arrayToOrder[$i];
                $arrayToOrder[$i] = $arrayToOrder[$j];
                $arrayToOrder[$j] = $tempHolder;
            }
        }
    }
}

function formMatches($groups, &$roundMatches){
    //Check if more than 1 group
    if(sizeof($groups) > 1){
        //hold match groups
        $matchGroups = [];
        //indicate curr group selection
        for($c = 1; $c <= sizeof($groups); $c++){
            array_push($matchGroups, $groups[$c-1]);
            //Check if need to create match and push
            if($c % 2 == 0 && $c != 0){
                //Form match and push
                $tempGroupA = $matchGroups[0];
                $tempGroupB = $matchGroups[1];
                $tempRoundMatch = new Match($tempGroupA, $tempGroupB, "pending");
                array_push($roundMatches, $tempRoundMatch);
                //clear match groups
                $matchGroups = [];
            }
        }
    }
}

function addRound($round, $conn){
    $numMatches = sizeof($round->matches);
    $roundStatus = "incomplete";

    $sql = "INSERT INTO tournament_round (num_matches, tournament_round.status, tournament_id, round_number) VALUES (?, ?, (SELECT tournament_ID FROM tournament WHERE tournament_Name = ?), ?);";
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $sql) == false){
        echo "Error in preparing sql statement";
    }
    else{
        if(mysqli_stmt_bind_param($stmt, 'issi', $numMatches, $roundStatus, $round->tournamentName, $round->roundNumber) == false){
            echo "Error in binding parameters";
        }
        else{
            if(mysqli_execute($stmt) == false){
                echo "Error in running query";
            }
            else{
                //Set round id
                $roundID = mysqli_insert_id($conn);
               //close statement
               mysqli_stmt_close($stmt);
               //add all matches
               //get match info
               $complete_Status = "incomplete";
               $sql = "INSERT INTO tournamentmatch (team1_ID, team2_ID, round_id, complete_status) VALUES ((SELECT team_ID FROM teamdetails WHERE team_Name = ?), (SELECT team_ID FROM teamdetails WHERE team_Name = ?), ?, ?);";
                $stmt = mysqli_stmt_init($conn);
                if(mysqli_stmt_prepare($stmt, $sql) == false){
                    echo "Error in preparing sql statement";
                }
                else{
                    for($i = 0; $i < sizeof($round->matches); $i++){
                        if(mysqli_stmt_bind_param($stmt, 'ssis', $round->matches[$i]->teamA->groupName, $round->matches[$i]->teamB->groupName, $roundID, $complete_Status) == false){
                            echo "Error in binding parameters";
                        }
                        else{
                            if(mysqli_execute($stmt) == false){
                                echo "Error in running query test";
                            }
                        }
                    }
                    mysqli_stmt_close($stmt);
               }
            }
        }
    }
}

//helpers
function comparePoints($groupA, $groupB){
    if($groupA->points > $groupB){
        return $groupA;
    }
    else{
        return $groupB;
    }
}

function prettyPrint($variableToPrint){
    print("<pre>" . print_r($variableToPrint, true) . "</pre>");
}
?>