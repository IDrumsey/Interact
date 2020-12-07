<?php
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
    <link rel="stylesheet" href="./teams.create.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <title>Teams - Create</title>
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
        <h1 id="headTitle">CREATE TEAM</h1>
      </div>
      <div id="body">
        <h5 id="pageDesc" class="highlightText">Team Settings</h5>
        <form
          action="./teams.createNew.php"
          id="teamInfoBasic"
          method="POST"
          enctype="multipart/form-data"
        >
          <div id="teamName">
            <h2 class="highlightText">Team Name</h2>
            <input type="text" name="teamName" id="teamNameIn" />
          </div>
          <div id="teamLogo">
            <h2 class="highlightText">Team Logo</h2>
            <input type="file" name="teamLogo" id="teamLogoIn" />
          </div>
        </form>
        <div id="continue">
          <h1 id="continueBtn">Continue</h1>
        </div>
      </div>
      <div id="foot">
        <div id="back" onclick="history.back();">
          <i class="fa fa-arrow-left footBtn"></i>
        </div>
        <div id="vault"><h1 class="footBtn">Vault</h1></div>
        <div id="settings"><i class="fa fa-cog footBtn" id="settingsBtn"></i></div>
      </div>
    </div>
  </body>
  <script src="./newTeam.js"></script>
  <script src="/Interact/base.js"></script>
  <script type="text/javascript">
  var primaryColor = <?php echo json_encode($_SESSION['primaryColor']); ?>;
  var secondaryColor = <?php echo json_encode($_SESSION['secondaryColor']); ?>;
  var tertiaryColor = <?php echo json_encode($_SESSION['tertiaryColor']); ?>;
  var objList = [['id', 'headTitle', 'secondary', 'text-shadow', null], ['id', 'container', 'primary', 'background', {type: 'linear-gradient', direction: 'to bottom'}], ['id', 'continueBtn', 'secondary', 'text-shadow', null], ['id', 'continue', 'primary', 'background', {type: 'solid'}], ['class', 'footBtn', 'secondary', 'text-shadow', null], ['class', 'highlightText', 'secondary', 'text-shadow', null]];
  </script>
</html>
