<?php
//$This file will contain all db queries needed for website in the form of functions to reduce includes and condense the amount of files and inconsistencies throughout.

/*
    Files covered - 

        bracket.php - frontend
        getTournamentRounds.php - get match details for all matches in a tournament (1), get tournament status (1);

*/

/*
    Queries needed - 
    (1) Get tournament info given name or id
            (a) Get name, total-prize, game, bracket-style, players-registered, teams-registered, start-date, end-date, prize-ditribution-method, entry-fee-style, status, owner

            (b) Get all round ids associated with tournament

            (c) Get round-number, status for each round in tournament
            (d) Get all match id's for each round in a tournament
            (e) Get team-a, team-b, start-date, start-time, end-time, and status for each match for each round for a given tournament



    json

        tournament {
            name: 
            total-prize:
            game: 
            bracket-style: 
            players-registered:
            teams-registered:
            start-date:
            end-date: 
            prize-distribution-method:
            entry-fee-style:
            status:
            owner:

            round {
                round-number:
                status: 
                match {
                    team-a:
                    team-b:
                    start-date:
                    start-time:
                    end-time:
                    status:
                }
            }
        }


    json 

        team {
            name:
            wins:
            losses:
            logo-status:
            user : {
                username:
                profile-logo-status:
            }
        }

*/


//include connection
if(!isset($conn)){
    include_once "dbConn.php";
}


//start user session
if(session_id() == ""){
    session_start();
}


//Classes

class Tournament {
    public $name; 
    public $total_prize;
    public $game; 
    public $bracket_style; 
    public $start_date;
    public $end_date; 
    public $prize_distribution_method;
    public $entry_fee_style;
    public $status;
    public $owner;
    public $grouping_style;
    public $rounds = [];
    public $registered_players = [];
    public $registered_teams = [];

    function __construct(){
        
    }

    //Setters
    function set_tournament_name($name){
        $this->name = $name;
    }
    function set_tournament_total_prize($total_prize){
        $this->total_prize = $total_prize;
    }
    function set_tournament_game($game){
        $this->game = $game;
    }
    function set_tournament_bracket_style($bracket_style){
        $this->bracket_style = $bracket_style;
    }
    function set_tournament_start_date($date){
        $this->start_date = $date;
    }
    function set_tournament_end_date($date){
        $this->end_date = $date;
    }
    function set_tournament_prize_distribution_method($method){
        $this->prize_distribution_method = $method;
    }
    function set_tournament_entry_fee_style($style){
        $this->entry_fee_style = $style;
    }
    function set_tournament_status($status){
        $this->status = $status;
    }
    function set_tournament_owner($owner){
        $this->owner = $owner;
    }
    function set_tournament_grouping_style($style){
        $this->grouping_style = $style;
    }

    //Adders
    function add_tournament_round($round){
        array_push($this->rounds, $round);
    }
    function add_tournament_registered_player($player_id){
        array_push($this->registered_players, $player_id);
    }
    function add_tournament_registered_team($team_id){
        array_push($this->registered_teams, $team_id);
    }

    //Getters
    function get_tournament_name(){
        return $this->name;
    }
    function get_tournament_total_prize(){
        return $this->total_prize;
    }
    function get_tournament_game(){
        return $this->game;
    }
    function get_tournament_bracket_style(){
        return $this->bracket_style;
    }
    function get_tournament_start_date(){
        return $this->start_date;
    }
    function get_tournament_end_date(){
        return $this->end_date;
    }
    function get_tournament_prize_distribution_method(){
        return $this->prize_distribution_method;
    }
    function get_tournament_entry_fee_style(){
        return $this->entry_fee_style;
    }
    function get_tournament_status(){
        return $this->status;
    }
    function get_tournament_owner(){
        return $this->owner;
    }
    function get_tournament_teams(){
        return $this->registered_teams;
    }
    function get_tournament_rounds(){
        return $this->rounds;
    }
    function get_tournament_players_registered(){
        return $this->registered_players;
    }
    function get_tournament_grouping_style(){
        return $this->grouping_style;
    }

    //Other
        function init($name, $total_prize, $game, $bracket_style, $start_date, $end_date, $prize_distribution_method, $entry_fee_style, $status, $owner){
            $this->set_tournament_name($name);
            $this->set_tournament_total_prize($total_prize);
            $this->set_tournament_game($game);
            $this->set_tournament_bracket_style($bracket_style);
            $this->set_tournament_start_date($start_date);
            $this->set_tournament_end_date($end_date);
            $this->set_tournament_prize_distribution_method($prize_distribution_method);
            $this->set_tournament_entry_fee_style($entry_fee_style);
            $this->set_tournament_status($status);
            $this->set_tournament_owner($owner);
        }
}



class Round {
    public $round_number;
    public $status;
    public $matches = [];

    function __construct(){
        
    }

    //Setters
        function set_round_number($round_number){
            $this->round_number = $round_number;
        }
        function set_round_status($status){
            $this->status = $status;
        }

    //Adders
        function add_round_match($match){
            array_push($this->matches, $match);
        }

    //Getters
        function get_round_number(){
            return $this->round_number;
        }
        function get_round_status(){
            return $this->status;
        }
        function get_round_matches(){
            return $this->matches;
        }

    //Other
        function init($round_number, $status){
            $this->set_round_number($round_number);
            $this->set_round_status($status);
        }
}



class Match {
    public $team_a;
    public $team_b;
    public $start_date;
    public $start_time;
    public $end_time;
    public $status;

    function __construct(){
        
    }

    //Setters
        function set_match_team_a($team){
            $this->team_a = $team;
        }
        function set_match_team_b($team){
            $this->team_b = $team;
        }
        function set_match_start_date($date){
            $this->start_date = $date;
        }
        function set_match_start_time($time){
            $this->start_time = $time;
        }
        function set_match_end_time($time){
            $this->end_time = $time;
        }
        function set_match_status($status){
            $this->status = $status;
        }


    
    //Getters
        function get_match_team_a(){
            return $this->team_a;
        }
        function get_match_team_b(){
            return $this->team_b;
        }
        function get_match_start_date(){
            return $this->start_date;
        }
        function get_match_start_time(){
            return $this->start_time;
        }
        function get_match_end_time(){
            return $this->end_time;
        }
        function get_match_status(){
            return $this->status;
        }

    //Other
        function init($team_a, $team_b, $start_date, $start_time, $end_time, $status){
            $this->set_match_team_a($team_a);
            $this->set_match_team_b($team_b);
            $this->set_match_start_date($start_date);
            $this->set_match_start_time($start_time);
            $this->set_match_end_time($end_time);
            $this->set_match_status($status);
        }
}



class Team {
    public $name;
    public $wins;
    public $losses;
    public $logo_status;
    public $id;
    public $users = [];
    public $matches = [];

    function __construct(){
        
    }

    //Setters
        function set_team_name($name){
            $this->name = $name;
        }
        function set_team_wins($num_wins){
            $this->wins = $num_wins;
        }
        function set_team_losses($num_losses){
            $this->losses = $num_losses;
        }
        function set_team_logo_status($status){
            $this->logo_status = $status;
        }
        function set_team_id($id){
            $this->id = $id;
        }



    //Adders
        function add_team_user($user){
            array_push($this->users, $user);
        }

        function add_team_match($match){
            array_push($this->matches, $match);
        }

        function add_team_matches($matches){
            $this->matches = array_merge($this->matches, $matches);
        }



    //Getters
        function get_team_name(){
            return $this->name;
        }
        function get_team_wins(){
            return $this->wins;
        }
        function get_team_losses(){
            return $this->losses;
        }
        function get_team_logo_status(){
            return $this->logo_status;
        }
        function get_team_users(){
            return $this->users;
        }
        /* function get_team_matches(){
            return $this->matches;
        } */

    //Other
        function init($team_name, $wins, $losses, $logo_status){
            $this->set_team_name($team_name);
            $this->set_team_wins($wins);
            $this->set_team_losses($losses);
            $this->set_team_logo_status($logo_status);
        }

}



class User {
    public $id;
    public $username;
    public $logo_status;

    function __construct(){
        
    }

    //Setters
        function set_user_username($username){
            $this->username = $username;
        }
        function set_user_logo_status($status){
            $this->logo_status = $status;
        }
        function set_user_id($id){
            $this->id = $id;
        }


    //Getters
        function get_user_username(){
            return $this->username;
        }
        function get_user_logo_status(){
            return $this->logo_status;
        }
        function get_user_id(){
            return $this->id;
        }

    //Other
        function init($username, $logo_status, $id){
            $this->set_user_username($username);
            $this->set_user_logo_status($logo_status);
            $this->set_user_id($id);
        }
}

class Team_Member extends User {
    public $rank;

    //Setters
    function set_member_rank($rank){
        $this->rank = $rank;
    }

    //Getters
    function get_member_rank(){
        return $this->rank;
    }

    //Other
        function init_member($username, $logo_status, $id, $rank){
            init($username, $logo_status, $id);
            $this->set_member_rank($rank);
        }
}







function get_tournament_info($tournament_id, $conn){
    //create new tournament

    $tournament = new Tournament();

    //run necessary queries

    $tournament_info = query_tournament($tournament_id, $conn);

    //Get tournament rounds
    $tournament_rounds = query_tournament_rounds($tournament_id, $conn);

    for($i = 0; $i < sizeof($tournament_rounds); $i++){
        $tournament->add_tournament_round(get_round_info($tournament_rounds[$i], $conn));
    }

    //Get players registered
    $registered_players = query_tournament_registered_players($tournament_id, $conn);
    $registered_teams = query_tournament_registered_teams($tournament_id, $conn);

    for($i = 0; $i < sizeof($registered_players); $i++){
        $tournament->add_tournament_registered_player($registered_players[$i]);
    }

    for($i = 0; $i < sizeof($registered_teams); $i++){
        $tournament->add_tournament_registered_team($registered_teams[$i]);
    }

    //set obj props
    $tournament->set_tournament_name($tournament_info['tournament_Name']);
    $tournament->set_tournament_total_prize($tournament_info['prize']);
    $tournament->set_tournament_start_date($tournament_info['start_date']);
    $tournament->set_tournament_end_date($tournament_info['end_date']);
    $tournament->set_tournament_status($tournament_info['status']);
    $tournament->set_tournament_grouping_style($tournament_info['grouping_style']);
    $tournament->set_tournament_entry_fee_style($tournament_info['join_Prize_Type']);
    $tournament->set_tournament_owner($tournament_info['owner']);
    $tournament->set_tournament_prize_distribution_method($tournament_info['method_name']);
    $tournament->set_tournament_bracket_style($tournament_info['bracket_name']);
    $tournament->set_tournament_game($tournament_info['title']);


    return $tournament;
}


function get_round_info($round_id, $conn){
    //create new round

    $round = new Round();

    //run necessary queries

    $round_info = query_round($round_id, $conn);
    $round_matches = query_round_matches($round_id, $conn);

    //get matches
    for($i = 0; $i < sizeof($round_matches); $i++){
        $round->add_round_match(get_match_info($round_matches[$i], $conn));
    }


    //set obj props
    $round->set_round_number($round_info['round_number']);
    $round->set_round_status($round_info['status']);

    return $round;
}


function get_user_info($user_id, $team_member, $conn){
    //create new user

    if(!$team_member){
        $user = new User();
    }
    else{
        $user = new Team_Member();
    }

    //run necessary queries

    //get username and logo_status

    $user_info = query_user($user_id, $conn);
    
    $user_username = $user_info['username'];
    $user_logo_status = $user_info['profileImageSet'];


    
    //set obj props

    $user->set_user_username($user_username);
    $user->set_user_logo_status($user_logo_status);
    $user->set_user_id($user_id);

    return $user;
}

function get_team_info($team_id, $get_matches, $conn){
    //create new team
    $team = new Team();

    //run necessary queries

    //get general team info
    $team_info = query_team($team_id, $conn);

    $team_name = $team_info['team_Name'];
    $team_wins = $team_info['numWins'];
    $team_losses = $team_info['numLosses'];
    $team_logo_status = $team_info['logo_set'];

    //get all team members

    $team_members = query_team_players($team_id, $conn);

    //Get member info
    for($i = 0; $i < sizeof($team_members); $i++){
        $team->add_team_user(get_user_info($team_members[$i], true, $conn));
        //Get member rank
        $team->users[$i]->set_member_rank(query_user_team_rank($team->users[$i]->id, $team_id, $conn));
    }

    //Get team matches
    if($get_matches)
    {
        $team_matches = query_team_matches($team_id, $conn);
        $team->add_team_matches($team_matches);
    }

    //set obj props

    $team->set_team_name($team_name);
    $team->set_team_wins($team_wins);
    $team->set_team_losses($team_losses);
    $team->set_team_wins($team_wins);
    $team->set_team_logo_status($team_logo_status);
    $team->set_team_id($team_id);

    return $team;
}


function get_match_info($match_id, $conn){
    //create new match
    $match = new Match();


    //run necessary queries
    $match_info = query_match($match_id, $conn);

    //Instead of create a bunch of temp vars, just use the assoc array in setters

    //Set teams
    $teamA = get_team_info($match_info['team1_ID'], false, $conn);
    $teamB = get_team_info($match_info['team2_ID'], false, $conn);

    //set props
    $match->set_match_start_date($match_info['start_date']);
    $match->set_match_start_time($match_info['start_time']);
    $match->set_match_end_time($match_info['end_time']);
    $match->set_match_status($match_info['complete_status']);
    $match->set_match_team_a($teamA);
    $match->set_match_team_b($teamB);

    return $match;
}









//USER/TEAM QUERIES

function query_user($id, $conn){
    //Gets username and logo_status
    $sql = "SELECT username, profileImageSet FROM users WHERE id = ?";
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $sql) == false){
        echo "Error in preparing sql statement";
    }
    else{
            if(mysqli_stmt_bind_param($stmt, 'i', $id) == false){
                echo "Error in binding parameters";
            }
            else{
                if(mysqli_execute($stmt) == false){
                    echo "Error in running query";
                }
                else{
                    $result = mysqli_stmt_get_result($stmt);
                    $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    return $user;
                }
            }
    }
mysqli_close($conn);
}

function query_team($id, $conn){
    //Gets team name, wins, losses, logo status
    $sql = "SELECT team_Name, numWins, numLosses, logo_set FROM teamdetails WHERE team_ID = ?";
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $sql) == false){
        echo "Error in preparing sql statement";
    }
    else{
            if(mysqli_stmt_bind_param($stmt, 'i', $id) == false){
                echo "Error in binding parameters";
            }
            else{
                if(mysqli_execute($stmt) == false){
                    echo "Error in running query";
                }
                else{
                    $result = mysqli_stmt_get_result($stmt);
                    $team = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    return $team;
                }
            }
    }
mysqli_close($conn);
}

function query_team_players($team_id, $conn){
    //Gets all id's of players on team specified
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
                    array_push($team_players, $row['userID']);
                }
                return $team_players;
            }
        }
    }
mysqli_close($conn);
}

function query_player_teams($player_id, $conn){
    //Gets all id's of teams of player specified
    $player_teams = [];
    $sql = "SELECT teamID FROM teamassociations WHERE userID = ?";
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $sql) == false){
        echo "Error in preparing sql statement";
    }
    else{
        if(mysqli_stmt_bind_param($stmt, 'i', $player_id) == false){
            echo "Error in binding parameters";
        }
        else{
            if(mysqli_execute($stmt) == false){
                echo "Error in running query";
            }
            else{
                $result = mysqli_stmt_get_result($stmt);
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                    array_push($player_teams, $row['teamID']);
                }
                return $player_teams;
            }
        }
    }
mysqli_close($conn);
}

function query_user_id($username, $conn){
    //Gets user id given username
    $player_teams = [];
    $sql = "SELECT id FROM users WHERE username = ?";
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $sql) == false){
        echo "Error in preparing sql statement";
    }
    else{
        if(mysqli_stmt_bind_param($stmt, 's', $username) == false){
            echo "Error in binding parameters";
        }
        else{
            if(mysqli_execute($stmt) == false){
                echo "Error in running query";
            }
            else{
                $result = mysqli_stmt_get_result($stmt);
                return(mysqli_fetch_array($result, MYSQLI_ASSOC)['id']);
            }
        }
    }
mysqli_close($conn);
}

function query_user_tournament_ids($user_id, $conn){
    //Gets all tournament id's of user given
    $tournaments = [];
    $sql = "SELECT tournament_id FROM tournament_player_association WHERE user_id = ?";
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $sql) == false){
        echo "Error in preparing sql statement";
    }
    else{
        if(mysqli_stmt_bind_param($stmt, 'i', $user_id) == false){
            echo "Error in binding parameters";
        }
        else{
            if(mysqli_execute($stmt) == false){
                echo "Error in running query";
            }
            else{
                $result = mysqli_stmt_get_result($stmt);
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                    array_push($tournaments, $row['tournament_id']);
                }
                return $tournaments;
            }
        }
    }
mysqli_close($conn);
}

function query_user_tournaments($user_id, $conn){
    //Get user tournament id's
    $user_tournament_ids = query_user_tournament_ids($user_id, $conn);

    $user_tournaments = [];


    //create tournaments for each id
    for($t = 0; $t < sizeof($user_tournament_ids); $t++){
        $tournament = get_tournament_info($user_tournament_ids[$t], $conn);
        array_push($user_tournaments, $tournament);
    }

    return $user_tournaments;
}

function query_user_invitations($user_id, $conn){
    //Gets all invitations for given user
    $invitations = [];
    $sql = "SELECT invitor, invitations.type, tournament_ID, match_ID, team_ID FROM invitations WHERE invited = ?";
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $sql) == false){
        echo "Error in preparing sql statement";
    }
    else{
        if(mysqli_stmt_bind_param($stmt, 'i', $user_id) == false){
            echo "Error in binding parameters";
        }
        else{
            if(mysqli_execute($stmt) == false){
                echo "Error in running query";
            }
            else{
                $result = mysqli_stmt_get_result($stmt);
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                    $invitation['type'] = $row['type'];
                    //Get invitor info
                    $invitor = get_user_info($row['invitor'], false, $conn);
                    $invitation['invitor'] = $invitor;

                    //Get tournament, team, or match info
                    switch($row['type']){
                        case 'Tournament':
                            //Get tournament info
                            $tournament = get_tournament_info($row['tournament_ID'], $conn);
                            $invitation['tournament'] = $tournament;
                            
                            //Get joining team info
                            $joining_team = get_team_info($row['team_ID'], false, $conn);
                            $invitation['joining_team'] = $joining_team;
                            break;
                        case 'Team':
                            //Get team info
                            $team = get_team_info($row['team_ID'], false, $conn);
                            $invitation['team'] = $team;
                            break;
                        case 'Match':
                            //Get match info
                            $match = get_match_info($row['match_ID'], $conn);
                            $invitation['match'] = $match;
                            break;
                        }
                    
                    array_push($invitations, $invitation);
                }
                return $invitations;
            }
        }
    }
mysqli_close($conn);
}

function query_team_id($team_name, $conn){
    //Gets team id given team name
    $sql = "SELECT team_ID FROM teamdetails WHERE team_Name = ?";
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $sql) == false){
        echo "Error in preparing sql statement";
    }
    else{
        if(mysqli_stmt_bind_param($stmt, 's', $team_name) == false){
            echo "Error in binding parameters";
        }
        else{
            if(mysqli_execute($stmt) == false){
                echo "Error in running query";
            }
            else{
                $result = mysqli_stmt_get_result($stmt);
                return(mysqli_fetch_array($result, MYSQLI_ASSOC)['team_ID']);
            }
        }
    }
mysqli_close($conn);
}

function query_user_team_rank($user_id, $team_id, $conn){
    //Gets the rank of a team member given the user and the team
    $sql = "SELECT rank FROM teamassociations WHERE userID = ? and teamID = ?";
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $sql) == false){
        echo "Error in preparing sql statement";
    }
    else{
        if(mysqli_stmt_bind_param($stmt, 'ii', $user_id, $team_id) == false){
            echo "Error in binding parameters";
        }
        else{
            if(mysqli_execute($stmt) == false){
                echo "Error in running query";
            }
            else{
                $result = mysqli_stmt_get_result($stmt);
                return(mysqli_fetch_array($result, MYSQLI_ASSOC)['rank']);
            }
        }
    }
mysqli_close($conn);
}

function query_team_matches($team_id, $conn){
    //Gets all the match ids of a team
    $matches = [];
    $sql = "SELECT id FROM tournamentmatch WHERE team1_ID = ? OR team2_ID = ?";
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $sql) == false){
        echo "Error in preparing sql statement";
    }
    else{
        if(mysqli_stmt_bind_param($stmt, 'ii', $team_id, $team_id) == false){
            echo "Error in binding parameters";
        }
        else{
            if(mysqli_execute($stmt) == false){
                echo "Error in running query";
            }
            else{
                $result = mysqli_stmt_get_result($stmt);
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                    array_push($matches, get_match_info($row['id'], $conn));
                }
                return $matches;
            }
        }
    }
mysqli_close($conn);
}


//TOURNAMENT QUERIES

function query_match($match_id, $conn){
    //Gets general match info
    $sql = "SELECT start_date, start_time, end_time, team1_ID, team2_ID, complete_status, match_winner, match_loser FROM tournamentmatch WHERE id = ?";
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $sql) == false){
        echo "Error in preparing sql statement";
    }
    else{
        if(mysqli_stmt_bind_param($stmt, 'i', $match_id) == false){
            echo "Error in binding parameters";
        }
        else{
            if(mysqli_execute($stmt) == false){
                echo "Error in running query";
            }
            else{
                $result = mysqli_stmt_get_result($stmt);
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                return $row;
            }
        }
    }
mysqli_close($conn);
}

function query_round($round_id, $conn){
    //Gets general match info
    $sql = "SELECT round_number, status FROM tournament_round WHERE round_id = ?";
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $sql) == false){
        echo "Error in preparing sql statement";
    }
    else{
        if(mysqli_stmt_bind_param($stmt, 'i', $round_id) == false){
            echo "Error in binding parameters";
        }
        else{
            if(mysqli_execute($stmt) == false){
                echo "Error in running query";
            }
            else{
                $result = mysqli_stmt_get_result($stmt);
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                return $row;
            }
        }
    }
mysqli_close($conn);
}

function query_round_matches($round_id, $conn){
    //Gets match id's for specified round
    $round_matches = [];
    $sql = "SELECT id FROM tournamentmatch WHERE round_id = ?";
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $sql) == false){
        echo "Error in preparing sql statement";
    }
    else{
        if(mysqli_stmt_bind_param($stmt, 'i', $round_id) == false){
            echo "Error in binding parameters";
        }
        else{
            if(mysqli_execute($stmt) == false){
                echo "Error in running query";
            }
            else{
                $result = mysqli_stmt_get_result($stmt);
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                    array_push($round_matches, $row['id']);
                }
                return $round_matches;
            }
        }
    }
mysqli_close($conn);
}

function query_tournament($tournament_id, $conn){
    //Gets general tournament info
    
    $sql = "SELECT t1.tournament_Name, t1.totalPrize AS prize, t1.num_players_registered AS registered_players, t1.num_teams_registered AS registered_teams, t1.start_date, t1.end_date, t1.join_Prize_Type, t1.status, t1.grouping_style, t1.owner, t2.title, t3.bracket_name, t4.method_name FROM tournament t1 INNER JOIN game t2 ON t2.game_ID = t1.gameID INNER JOIN bracket_type t3 ON t3.bracket_ID = t1.bracket_type_ID INNER JOIN prize_distribution_method t4 ON t4.method_id = t1.prize_distribution_ID WHERE tournament_ID = ?";
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
                $result = mysqli_stmt_get_result($stmt);
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                return $row;
            }
        }
    }
mysqli_close($conn);
}

function query_tournament_rounds($tournament_id, $conn){
    //Gets round id's for specified tournament
    $tournament_rounds = [];
    $sql = "SELECT round_id FROM tournament_round WHERE tournament_id = ?";
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
                $result = mysqli_stmt_get_result($stmt);
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                    array_push($tournament_rounds, $row['round_id']);
                }
                return $tournament_rounds;
            }
        }
    }
mysqli_close($conn);
}

function query_tournament_registered_teams($tournament_id, $conn){
    //Gets registered teams for specified tournament
    $registered_teams = [];
    $sql = "SELECT team_id FROM tournament_team_association WHERE tournament_id = ?";
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
                $result = mysqli_stmt_get_result($stmt);
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                    array_push($registered_teams, $row['team_id']);
                }
                return $registered_teams;
            }
        }
    }
    mysqli_close($conn);
}

function query_tournament_registered_players($tournament_id, $conn){
    //Gets registered teams for specified tournament
    $registered_players = [];
    $sql = "SELECT user_id FROM tournament_player_association WHERE tournament_id = ?";
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
                $result = mysqli_stmt_get_result($stmt);
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                    array_push($registered_players, $row['user_id']);
                }
                return $registered_players;
            }
        }
    }
    mysqli_close($conn);
}

function query_tournament_name($tournament_id, $conn){
    //Gets tournament name given tournament id
    $sql = "SELECT tournament_Name FROM tournament WHERE tournament_ID = ?";
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $sql) == false){
        echo "Error in preparing sql statement";
    }
    else{
        if(mysqli_stmt_bind_param($stmt, 's', $tournament_id) == false){
            echo "Error in binding parameters";
        }
        else{
            if(mysqli_execute($stmt) == false){
                echo "Error in running query";
            }
            else{
                $result = mysqli_stmt_get_result($stmt);
                return(mysqli_fetch_array($result, MYSQLI_ASSOC)['tournament_Name']);
            }
        }
    }
    mysqli_close($conn);
}

//Tests