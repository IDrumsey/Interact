<?php
if(isset($_POST['playerName'])){
  include_once "./findPlayers.php";
}
if(isset($_GET['team'])){
  $teamName = $_GET['team'];
  include_once "getTeamInfo.php";
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./backbone.css" />
    <link rel="stylesheet" href="./teams.create.addPlayer.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <title>New Team - Add Players</title>
  </head>
  <body>
    <div id="container">
      <div id="head">
        <h1>INVITE</h1>
      </div>
      <div id="body">
        <h2 id="pageDesc">Team Settings</h2>
        <div id="playerSearch">
          <h2 id="searchTitle">Invite Players</h2>
          <div id="playerSearchWrapper">
            <div id="searchBar">
              <?php echo "<form action='./teams.create.addPlayer.php?team=" . $teamName . "' method='POST' id='newPlayerSearch'>";?>
                <input type="text" name="playerName" />
              </form>
              <div id="searchBtn">
                <i class="fa fa-search" id="searchBtnActual"></i>
              </div>
            </div>
            <div id="playerSearchResultsList">
              <?php
              if(isset($players)){
                for($i = 0; $i < sizeof($players); $i++){
                  echo "<div class='player'>
                  <div class='playerLogoWrapper'>
                    <img src='./users/profileImages/" . $players[$i]['id'] . ".jpg' class='playerLogo'>
                  </div>
                  <div class='playerName'>
                    <h2>" . $players[$i]['username'] . "</h2>
                  </div>
                  <div class='inv'><a href='./teamPlayerInvite.php?team=" . $teamName . "&player=" . $players[$i]['username'] . "'><i class='fa fa-envelope-o'></i></a></div>
                </div>";
                }
              }
              ?>
            </div>
          </div>
        </div>
        <div id="teamMembers">
          <h2 id="membersTitle">Players</h2>
          <div id="memberList">
            <?php
            for($i = 0; $i < sizeof($teamPlayers); $i++){
              if($teamPlayers[$i]['profileImageSet'] == 1){
                $profileImagePath = "users/profileImages/" . $teamPlayers[$i]['id'] . ".jpg";
              }
              echo "<div class='teamMember'>
              <div class='playerLogoWrapper'>
                <img class='playerLogo' src='" . $profileImagePath . "' alt='Profile Image'</img>
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
        echo "<a href='./teams.create.review.php?team=" . $_GET['team'] . "' id='finishBtn'>
          <div id='finish'>
            <h1>Finish</h1>
          </div>
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
  <script src="./newTeam.addPlayers.js"></script>
</html>
