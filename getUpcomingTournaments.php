<?php
//Get upcoming tournaments based on selected games
if(!isset($conn)){
    include_once "./dbConn.php";
}
$tournaments = [];
if(isset($_POST['gameOptions'])){
    $gameTitles = [];
    foreach($_POST['gameOptions'] as $gameOption){
        array_push($gameTitles, $gameOption);
    }
    $sql = "SELECT t1.tournament_Name, t1.start_date, t1.num_players_registered AS registered, t1.owner, t1.totalPrize, t2.title FROM tournament t1 INNER JOIN game t2 ON t2.game_ID = t1.gameID WHERE t1.start_date > DATE(NOW()) AND t2.title = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $sql) == false){
        echo "Error in preparing sql statement";
    }
    else{
        for($i = 0; $i < sizeof($gameTitles); $i++){
            if(mysqli_stmt_bind_param($stmt, 's', $gameTitles[$i]) == false){
                echo "Error in binding parameters";
            }
            else{
                if(mysqli_execute($stmt) == false){
                    echo "Error in running query";
                }
                else{
                    $result = mysqli_stmt_get_result($stmt);
                    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                        array_push($tournaments, $row);
                    }
                }
            }
        }
    }
}
mysqli_close($conn);
?>