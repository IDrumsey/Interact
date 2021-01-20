<?php
$docPath = $_SERVER['DOCUMENT_ROOT'];
include_once $docPath . "/Interact/getUserProfile.php";
include_once $docPath . "/Interact/getColorPreferences.php";

//new php style
include_once 'main.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./backbone.css" />
    <link rel="stylesheet" href="./dashboard.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <title>Dashboard</title>
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
        <div class="title">
          <h1 id="titleText">DASHBOARD</h1>
        </div>
        <div id="profile">
        </div>
      </div>
      <div id="body">
        <div id="upcomingGames">
          <div class="title"><h2 class="sectionTitle">Upcoming Games</h2></div>
          <div class="list"></div>
        </div>
        <div id="invitations">
          <div class="title"><h2 class="sectionTitle">Invitations</h2></div>
          <div class="list"></div>
        </div>
        <div id="options">
          <?php echo "<a href='./teams.homepage.php?player=" . $_SESSION['user'] . "' id='teams' class='option'><i class='fa fa-group'></i></a>"; ?>
          <a href="play.home.php" id='play' class='option'><i class="fa fa-gamepad"></i></a>
        </div>
        <div id="history">
          <div class="title"><h2 class="sectionTitle">History</h2></div>
          <div class="list"></div>
        </div>
      </div>
      <div id="foot">
        <div id="signOut">
          <a href="./signOut.php"><i class="fa fa-sign-out fa-flip-horizontal footBtn"></i></a>
        </div>
        <div id="vault"><h1 class="footBtn">Vault</h1></div>
        <div id="settings"><i class="fa fa-cog footBtn" id="settingsBtn"></i></div>
      </div>
    </div>
  </body>
  <script src="/Interact/base.js"></script>
  <script src="/Interact/main.js"></script>
  <script type="text/javascript">
  var userName = "<?php echo $_SESSION['user']; ?>";
  var primaryColor = <?php echo json_encode($_SESSION['primaryColor']); ?>;
  var secondaryColor = <?php echo json_encode($_SESSION['secondaryColor']); ?>;
  var tertiaryColor = <?php echo json_encode($_SESSION['tertiaryColor']); ?>;
  var objList = [['id', 'titleText', 'secondary', 'text-shadow', null], ['id', 'container', 'primary', 'background', {type: 'linear-gradient', direction: 'to bottom'}], ['class', 'sectionTitle', 'secondary', 'text-shadow', null], ['class', 'footBtn', 'secondary', 'text-shadow', null]];
  </script>
</html>
<?php
mysqli_close($conn);
?>