<?php
if(session_id() == ""){
  session_start();
}
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
    <title>New Team - Add Players</title>
  </head>
  <body>
    <div id="container">
      <div id="head">
        <h1>REVIEW</h1>
      </div>
      <div id="body">
        <div id="teamInfo">
          <?php
          echo "<h1 id='teamName'>" . $_GET['team'] . "</h1>
          <h1 id='winLossRatio'>" . $winLoss['numWins'] . " vs " . $winLoss['numLosses'] . "</h1>";
          ?>
        </div>
        <div id="teamMembers">
          <h2 id="membersTitle">Players</h2>
          <div id="memberList">
            <?php
            for($i = 0; $i < sizeof($teamPlayers); $i++){
              if($teamPlayers[$i]["profileImageSet"] == 1){
                $profileImgPath = "users/profileImages/" . $teamPlayers[$i]["id"] . ".jpg";
              }
              echo "<div class='teamMember'>
              <div class='playerLogoWrapper'>
                <img class='playerLogo' src='" . $profileImgPath . "' alt=''</img>
              </div>
              <div class='playerName'>
                <h2>" . $teamPlayers[$i]['username'] . "</h2>
              </div>
              <div class='memberRank'><h2>" . $teamPlayers[$i]['rank'] . "</h2></div>
            </div>";
            }
            ?>
          </div>
        </div>
        <?php
        echo "<a href='./teams.homepage.php?player=" . $_SESSION['user'] . "' id='finishBtn'>
          <h1>Done</h1>
          </a>";
        ?>
      </div>
      <div id="foot">
        <div id="back" onclick="history.back();">
          <i class="fa fa-arrow-left"></i>
        </div>
        <div id="vault"><h1>Vault</h1></div>
        <div id="settings"><i class="fa fa-cog"></i></div>
      </div>
    </div>
  </body>
</html>
