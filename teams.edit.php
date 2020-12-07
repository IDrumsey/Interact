<?php
include_once "getTeamInfo.php";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./backbone.css" />
    <link rel="stylesheet" href="./teams.create.review.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <title>Edit Team</title>
  </head>
  <body>
    <div id="container">
      <div id="head">
        <h1>EDIT</h1>
      </div>
      <div id="body">
        <div id="teamInfo">
          <h1 id="teamName">
            <?php
            echo $_GET['team'];
            ?>
          </h1>
          <?php
          echo "<h1 id='winLossRatio'>" . $winLoss['numWins'] . ":" . $winLoss['numLosses'] . "</h1>";
          ?>
        </div>
        <div id="teamMembers">
          <h2 id="membersTitle">Players</h2>
          <div id="memberList">
            <?php
            for($i = 0; $i < sizeof($teamPlayers); $i++){
              echo "<div class='teamMember'>
              <div class='playerLogoWrapper'>
                <img class='playerLogo' src='./users/profileImages/" . $teamPlayers[$i]['id'] . ".jpg' alt='Profile Image'>
              </div>
              <div class='playerName'>
                <h2>" . $teamPlayers[$i]['username'] . "</h2>
              </div>
              <div class='memberRank'><h2>Rank</h2></div>
            </div>";
            }
            ?>
          </div>
        </div>
        <?php
        echo "<a href='./team.php?team=" . $_GET['team'] . "' id='finishBtn'>
          <h1>Done</h1>
        </a>";
        ?>
      </div>
      <div id="foot">
        <div id="back">
          <i class="fa fa-arrow-left" onclick="history.back();"></i>
        </div>
        <div id="vault"><h1>Vault</h1></div>
        <div id="settings"><i class="fa fa-cog"></i></div>
      </div>
    </div>
  </body>
</html>
