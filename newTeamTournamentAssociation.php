<?php
if(session_id() == ""){
    session_start();
}
if(!isset($conn)){
    include_once "./dbConn.php";
}
$tournamentName = $_GET['tournamentName'];
$teamName = $_GET['teamName'];
$sql = "INSERT INTO tournament_team_association (tournament_team_association.team_id, tournament_id) VALUES ((SELECT team_ID FROM teamdetails WHERE team_Name = ?), (SELECT tournament_ID FROM tournament WHERE tournament_Name = ?));";
$stmt = mysqli_stmt_init($conn);
if(mysqli_stmt_prepare($stmt, $sql) == false){
    echo "Error in preparing sql statement";
}
else{
    if(mysqli_stmt_bind_param($stmt, 'ss', $teamName, $tournamentName) == false){
        echo "Error in binding parameters";
    }
    else{
        if(mysqli_execute($stmt) == false){
            echo "Error in running query";
        }
        else{
            mysqli_stmt_close($stmt);
            //Get Players on team
            $sql = "SELECT t3.username FROM teamassociations t1 INNER JOIN teamdetails t2 ON t2.team_ID = t1.teamID INNER JOIN users t3 ON t3.id = t1.userID WHERE t2.team_Name = ? AND t3.username != ?;";
            $stmt = mysqli_stmt_init($conn);
            if(mysqli_stmt_prepare($stmt, $sql) == false){
                echo "Error in preparing sql statement";
            }
            else{
                if(mysqli_stmt_bind_param($stmt, 'ss', $teamName, $_SESSION['user']) == false){
                    echo "Error in binding parameters";
                }
                else{
                    if(mysqli_execute($stmt) == false){
                        echo "Error in running query";
                    }
                    else{
                        $teamPlayers = [];
                        //set Players
                        $result = mysqli_stmt_get_result($stmt);
                        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                            array_push($teamPlayers, $row);
                        }
                        mysqli_stmt_close($stmt);
                        //Send Invitations
                        $sql = "INSERT INTO invitations (invitor, invitations.type, invited, tournament_ID, team_ID) VALUES ((SELECT id FROM users WHERE username = ?), 'Tournament', (SELECT id FROM users WHERE username = ?), (SELECT tournament_ID FROM tournament WHERE tournament_Name = ?), (SELECT team_ID FROM teamdetails WHERE team_Name = ?));";
                        $stmt = mysqli_stmt_init($conn);
                        if(mysqli_stmt_prepare($stmt, $sql) == false){
                            echo "Error in preparing sql statement";
                        }
                        else{
                            for($i = 0; $i < sizeof($teamPlayers); $i++){
                                if(mysqli_stmt_bind_param($stmt, 'ssss', $_SESSION['user'], $teamPlayers[$i]['username'], $tournamentName, $teamName) == false){
                                    echo "Error in binding parameters";
                                }
                                else{
                                    if(mysqli_execute($stmt) == false){
                                        echo "Error in running query";
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
mysqli_stmt_close($stmt);
//Redirect
header("Location: /Interact/newPlayerTournamentAssociation.php?tournamentName=" . $tournamentName . "&team=" . $teamName);
mysqli_close($conn);
?>