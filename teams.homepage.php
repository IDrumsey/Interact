<?php
include_once "./getUserTeamInfo.php";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./backbone.css" />
    <link rel="stylesheet" href="./teams.homepage.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <title>Teams - Home</title>
  </head>
  <body>
    <div id="container">
      <div id="settingsMenu">
        <div id="settingsMenuOffset">
          <div id="settingsHeader">
            <?php
              echo "<h2>" . $_SESSION['user'] . " Settings</h2>";
            ?>
          </div>
            <form action="/Interact/setColorPreferences.php" id="pickColorPreferences" method="GET">
              <?php
                echo "<div class='colorChoice'><label for='primaryColor'><h2>Primary</h2></label><input type='color' name='primaryColor' value='" . $_SESSION['primaryColor'] . "' onchange='updateColorPreferences();'></div>";
                echo "<div class='colorChoice'><label for='secondaryColor'><h2>Secondary</h2></label><input type='color' name='secondaryColor' value='" . $_SESSION['secondaryColor'] . "' onchange='updateColorPreferences();'></div>";
                echo "<div class='colorChoice'><label for='tertiaryColor'><h2>Tertiary</h2></label><input type='color' name='tertiaryColor' value='" . $_SESSION['tertiaryColor'] . "' onchange='updateColorPreferences();'></div>";
              ?>
            </form>
        </div>
      </div>
      <div id="head">
        <h1 id="headTitle">Teams</h1>
      </div>
      <div id="body">
        <div id="teams">
          <h2 id="teamListTitle">My Teams</h2>
          <div id="teamList">
            <?php
            for($t = 0; $t < sizeof($allTeamInfo); $t++){
              echo "<div class='team'>
              <div class='teamLogoWrapper'>
              <img src='/Interact/teams/profileImages/" . $allTeamInfo[$t]['team_ID'] . ".jpg' class='teamLogo'>
              </div>
              <a href='./team.php?team=" . $allTeamInfo[$t]['team_Name'] . "'>
              <div class='teamName'><h4>" . $allTeamInfo[$t]['team_Name'] . "</h4></div>
              </a>
              <div class='teamWon'><h4>Won : " . $allTeamInfo[$t]['numWins'] . "</h4></div>
              <div class='teamLost'><h4>Lost : " . $allTeamInfo[$t]['numLosses'] . "</h4></div>
              <div class='more'><a href='./team.php?team=" . $allTeamInfo[$t]['team_Name'] . "'><i class='fa fa-arrow-right'></i></a></div>
            </div>";
            }
            ?>
          </div>
        </div>
        <div id="createTeam">
          <a href="./teams.create.php"><h2 id="createTeamBtn">Create Team</h2></a>
        </div>
      </div>
      <div id="foot">
        <div id="back">
          <?php echo "<a href='./dashboard.php?player=" . $_SESSION['user'] . "'><i class='fa fa-arrow-left footBtn'></i></a>"; ?>
        </div>
        <div id="vault"><h1 class="footBtn">Vault</h1></div>
        <div id="settingsBtn"><i class="fa fa-cog footBtn"></i></div>
      </div>
    </div>
  </body>
  <script src="/Interact/base.js"></script>
  <script type="text/javascript">
  var primaryColor = <?php echo json_encode($_SESSION['primaryColor']); ?>;
  var secondaryColor = <?php echo json_encode($_SESSION['secondaryColor']); ?>;
  var tertiaryColor = <?php echo json_encode($_SESSION['tertiaryColor']); ?>;
  var objList = [['id', 'headTitle', 'secondary', 'text-shadow', null], ['id', 'container', 'primary', 'background', {type: 'linear-gradient', direction: 'to bottom'}], ['id', 'createTeamBtn', 'secondary', 'text-shadow', null], ['id', 'teamListTitle', 'secondary', 'text-shadow', null], ['id', 'createTeam', 'primary', 'background', {type: 'solid'}], ['class', 'footBtn', 'secondary', 'text-shadow', null]];
  </script>
</html>
