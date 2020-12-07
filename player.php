<?php
include_once "./getUserProfile.php";
include_once "./getUserGames.php";
include_once "./getUserTeams.php";
if(isset($conn)){
  mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./backbone.css" />
    <link rel="stylesheet" href="./player.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <title>Player Profile</title>
  </head>
  <body>
    <div id="container">
      <div id="head">
        <h1><?php echo $_GET['player']; ?></h1>
      </div>
      <div id="body">
        <div id="profileLogoWrapper">
          <?php echo"<img src='users/profileImages/" . $userID . ".jpg' id='profileLogo' alt='profileLogo' />"; ?>
        </div>
        <div id="games">
          <h2 class="title">Played Games</h2>
          <div id="gameList">
            <?php
            for($i = 0; $i < sizeof($games); $i++){
              echo "<div class='game'>
              <div class='gameBackground'></div>
              <div class='gameTitle'><h2>" . $games[$i]['title'] . "</h2></div>
            </div>";
            }
            ?>
          </div>
        </div>
        <div id="teams">
          <h2 class="title">Teams</h2>
          <div id="teamList">
            <?php
            for($i = 0; $i < sizeof($user_teams); $i++){
              echo "<div class='team'>
              <div class='teamLogoWrapper'>
                <img src='teams/profileImages/" . $user_teams[$i]['teamID'] . ".jpg' class='teamLogo' alt='teamLogo' />
              </div>
              <a href='team.php?team=" . $user_teams[$i]['team_Name'] . "'><div class='teamName'><h2>" . $user_teams[$i]['team_Name'] . "</h2></div></a>
              <div class='more'><i class='fa fa-arrow-right'></i></div>
            </div>";
            }
            ?>
          </div>
        </div>
      </div>
      <div id="foot">
      <div id="back">
          <i class="fa fa-arrow-left" onclick="history.back();"></i>
        </div>
        <div id="vault"><h1>Vault</h1></div>
        <div id="settings"><i class="fa fa-cog"></i></div></div>
    </div>
  </body>
</html>
