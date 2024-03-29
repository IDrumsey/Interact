<?php
include_once "./getTournamentRounds.php";
//echo("<pre>" . print_r($rounds, true) . "</pre>");
$docPath = $_SERVER['DOCUMENT_ROOT'];
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <link rel="stylesheet" href="./bracket.css" />
    <script src="https://kit.fontawesome.com/62aaa623b9.js" crossorigin="anonymous"></script>
    <title>
      <?php
      echo $_GET['tournamentName'];
      ?>
    </title>
  </head>
  <body>
    <div id="container">
      <div id="head">
        <h2 id="tournamentName">
          <?php
          echo $_GET['tournamentName'];
          ?>
        </h2>
      </div>
      <?php
      if($tournament_status['status'] == "pending"){
        echo "<div class='body1'>";
      }
      else{
        echo "<div class='body1' style='display: block;'>";
      }
      ?>
      <?php
          if(sizeof($rounds) > 0){
            for($i = 0; $i < sizeof($rounds); $i++){
              echo "<div class='round'>
              <div class='roundNumber'><h2><a href='../match.html'>Round " . ($i + 1) . "</a></h2></div>
              <div class='matchList'>";
              for($j = 0; $j < sizeof($rounds[$i]); $j++){
                $currMatch = $rounds[$i][$j];
                $statusColor;
                $winningTeam = "NA";
                if($currMatch['complete_status'] == "incomplete"){
                  $statusColor = "ff0000";
                }
                else if($currMatch['complete_status'] == "complete"){
                  $statusColor = "0fbd5a";
                  if($currMatch['match_winner'] == "team1"){
                    $winningTeam = $currMatch['team1Name'];
                  }
                  else{
                    $winningTeam = $currMatch['team2Name'];
                  }
                }
                echo "<div class='matchWrapper'><div class='match' onclick='displayDetails(this)'><div class='match-bottom-border'></div><div class='matchDetails' style='box-shadow: 0px 0px 10px #" . $statusColor . ";'><div class='matchDetailsA'><div class='matchDate'><h2>" . $currMatch['start_date'] . "</h2></div><div class='matchTime'><h2>" . $currMatch['start_time'] . "</h2><h2>-</h2><h2>" . $currMatch['end_time'] . "</h2></div><div class='closeDetails'><i class='fa fa-times closeDets' onclick='closeDetails(this, event)'></i></div></div><div class='matchDetailsB'><div class='matchDetailsBTitle'><h2><i class='fas fa-crown'></i></h2></div><div class='matchDetailsBTeam'><h2>" . $winningTeam . "</h2></div></div></div><div class='player playerA'><h2 class='playerName'>" . $rounds[$i][$j]['team1Name'] . "</h2></div><div class='player playerB'><h2 class='playerName'>" . $rounds[$i][$j]['team2Name'] . "</h2></div>
              </div><div class='matchOptions'><i class='fa fa-ellipsis-h opSel'></i></div>
              <div class='selectWinner'>
                  <h2 class='selectWinnerTitle'>Select Match Winner</h2>
                  <i class='fa fa-times select-winner-close'></i>
                  <p class='selectWinnerDesc'>One representative from each team will submit the winner of this match</p>
                  <div class='winningTeam'>
                    <div class='teamA winnerOption'><i class='fas fa-crown'></i><h2>" . $rounds[$i][$j]['team1Name'] . "</h2></div>
                    <div class='teamB winnerOption'><h2>" . $rounds[$i][$j]['team2Name'] . "</h2><i class='fas fa-crown'></i></div>
                  </div>
                  <div class='subWinner'><h2>Submit</h2></div>
                </div></div>";
              }
              echo "</div></div>";
            }
          }
      echo "</div>";
      if($tournament_status['status'] == "pending"){
        echo "<div class='body2' style='display: block;'>";
      }
      else{
        echo "<div class='body2'>";
      }
        echo "<h2 id='pendingStatusTitle'>Tournament not yet started</h2><div id='joinBtn'><a href='./joinTournament.php?tournamentName=" . $_GET['tournamentName'] . "'><h2>Join</h2></a></div><div id='startBtn'><a href='../roundMatchGeneration.php?tournamentName=" . $_GET['tournamentName'] . "'><h2>Start Tournament</h2></a></div></div>";
      ?>
      <div id="foot">
        <div id="backBtn">
          <a href="/Interact/play.home.php"><i class="fa fa-arrow-left"></i></a>
        </div>
      </div>
    </div>
  </body>
  <script src="./bracket.js"></script>
</html>
