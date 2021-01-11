<?php
include_once "getUpcomingTournaments.php";
if(session_id() == ""){
  session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./backbone.css" />
    <link rel="stylesheet" href="./play.home.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <title>Play</title>
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
        <h1 id="titleText">PLAY</h1>
      </div>
      <div id="body">
        <div id="upcomingTournaments">
          <div id="upcomingHead">
          <h2 id="tournamentListTitle" class="highlightText">Upcoming Tournaments</h2>
          <form action="" id="filterGames" method="POST">
            <select name="gameOptions[]" id="gameOptions" class="fillPrim" multiple>
              <option value="Overwatch" class="highlightText">Overwatch</option>
            </select>
          </form>
          </div>
          <div id="searchBtn"><h2 class="highlightText fillPrim"><i class="fa fa-search"></i></h2></div>
          <div id="tournamentList" class="fillPrim">
            <?php
            for($i = 0; $i < sizeof($tournaments); $i++){
              echo "<a class='tournament' href='./bracketVisual/bracket.php?tournamentName=" . $tournaments[$i]['tournament_Name'] . "'>
              <i class='fa fa-trophy'></i><div class='gameTitle'><h2>" . $tournaments[$i]['title'] . "</h2></div><div class='startDate'><h2>" . $tournaments[$i]['start_date'] . "</h2></div>
              <div class='tournamentName'><h2>" . $tournaments[$i]['tournament_Name'] . "</h2></div>
              <div class='numTeams'><h2>Registered Teams : " . $tournaments[$i]['teams_registered'] . "</h2></div>
              <div class='owner'><h2>Created By : " . $tournaments[$i]['owner'] . "</h2></div>
              </a>";
            }
            ?>
          </div>
        </div>
        <div id="options">
          <a href="./tournament.create.php"
            ><div id="create" class="fillPrim"><h2 class="highlightText">Create</h2></div></a
          >
        </div>
      </div>
      <div id="foot">
        <?php
        echo "<a href='dashboard.php?player=" . $_SESSION['user'] . "' id='back'>
          <i class='fa fa-arrow-left highlightText'></i>
        </a>";
        ?>
        <div id="vault"><h1 class="footBtn">Vault</h1></div>
        <div id="settings"><i class="fa fa-cog footBtn" id="settingsBtn"></i></div>
      </div>
    </div>
  </body>
  <script src="./play.home.js"></script>
  <script src="/Interact/base.js"></script>
  <script type="text/javascript">
  var primaryColor = <?php echo json_encode($_SESSION['primaryColor']); ?>;
  var secondaryColor = <?php echo json_encode($_SESSION['secondaryColor']); ?>;
  var tertiaryColor = <?php echo json_encode($_SESSION['tertiaryColor']); ?>;
  var objList = [['id', 'titleText', 'secondary', 'text-shadow', null], ['id', 'container', 'primary', 'background', {type: 'linear-gradient', direction: 'to bottom'}], ['class', 'footBtn', 'secondary', 'text-shadow', null], ['class', 'highlightText', 'secondary', 'text-shadow', null], ['class', 'fillPrim', 'primary', 'background', {type: 'solid'}]];
  </script>
</html>
