<?php
if(isset($_GET['team'])){
  include_once "./getTeamInfo.php";
}
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
    <script src="https://kit.fontawesome.com/62aaa623b9.js" crossorigin="anonymous"></script>
    <title><?php echo $_GET['team']; ?></title>
  </head>
  <body>
    <div id="container">
      <div id="head">
        <h1>TEAMS</h1>
      </div>
      <div id="body">
        <div id="teamInfo">
          <h1 id="teamName"><?php echo $_GET['team']; ?></h1>
          <h1 id="winLossRatio">1:1</h1>
        </div>
        <div id="teamMembers">
          <h2 id="membersTitle">Players</h2>
          <div id="memberList">
            <?php
            for($i = 0; $i < sizeof($teamPlayers); $i++){
              echo "<div class='teamMember'>
            <div class='playerLogoWrapper'>
              <img src='./users/profileImages/" . $teamPlayers[$i]['id'] . ".jpg' class='playerLogo'>
            </div>
            <a href='player.php?player=" . $teamPlayers[$i]['username'] . "'>
              <h2>" . $teamPlayers[$i]['username'] . "</h2>
            </a>
            <div class='memberRank'><h2>" . $teamPlayers[$i]['rank'] . "</h2></div>
          </div>";
            }
            ?>
          </div>
        </div>
        <div id="history">
          <h2>History</h2>
          <div id="historyList">
            <?php
              for($i = 0; $i < sizeof($history); $i++){
                //Get winning team
                $winningTeam = ($history[$i]['teamA Name'] == $history[$i]['match_winner']) ? 1 : 2;
                echo "<div class='historyItem'>
                <div class='matchDate'><h2>" . $history[$i]['start_date'] . "</h2></div>
                <div class='matchTime'><h2>" . $history[$i]['start_time'] . "</h2></div>
                <div class='team1'>";
                if($winningTeam == 1){
                  echo "<i class='fas fa-crown'></i>";
                }
                echo "<h2>" . $history[$i]['teamA Name'] . "</h2></div>
                <div class='separator'><h2>vs</h2></div>
                <div class='team2'>";
                if($winningTeam == 2){
                  echo "<i class='fas fa-crown'></i>";
                }
                echo "<h2>" . $history[$i]['teamB Name'] . "</h2></div>
              </div>";
              }
              ?>
          </div>
        </div>
        <div id="options">
          <?php echo "<a href='./teams.edit.php?team=" . $_GET['team'] . "' id='editTeamBtn'>
              <h2>Edit Team</h2>
          </a>"; ?>
          <?php echo "<a href='./teams.create.addPlayer.php?team=" . $_GET['team'] . "' id='invPlayer'>
              <h2>Invite</h2>
          </a>"; ?>
        </div>
      </div>
      <div id="foot">
        <div id="back">
          <i class="fa fa-arrow-left" onclick="history.back();"></i>
        </div>
        <div id="vault"><h1>Vault</h1></div>
        <div id="settings"><i class="fa fa-cog"></i></div>
      </div>
    </div>
    <script src="main.js"></script>
  </body>
</html>
