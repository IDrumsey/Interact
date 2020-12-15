<?php
  $red = '/Interact/signInProcess.php';
  for($i = 0; $i < sizeof($_GET); $i++){
    if(isset($_GET['redirect'])){
      $redirect = $_GET['redirect'];
    }
    if(isset($_GET['team1'])){
      $team1 = $_GET['team1'];
    }
    if(isset($_GET['team2'])){
      $team2 = $_GET['team2'];
    }
    if(isset($_GET['round'])){
      $round = $_GET['round'];
    }
    if(isset($_GET['tournament'])){
      $tournament = $_GET['tournament'];
    }
  }
  if(isset($_GET['redirect'])){
    $red = $red . '?redirect=' . $redirect . '&team1=' . $team1 . '&team2=' . $team2 . '&round=' . $round . '&tournament=' . $tournament;
    $red = str_replace(' ', '%20', $red);
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./signIn.css" />
    <link rel="stylesheet" href="./backbone.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <title>Sign In</title>
  </head>
  <body>
    <div id="container">
      <div id="head">
        <h1>SIGN IN</h1>
      </div>
      <div id="body">
          <form id="signInForm" method="POST" action=<?php echo $red; ?>>
            <div class="inputField username">
              <h4 class="Title" id="userTitle">Username</h4>
              <input type="text" id="user" name="user" class="fi"/>
            </div>
            <div class="inputField">
              <h4 class="Title" id="passTitle">Password</h4>
              <input type="password" id="pass" name="pass" class="fi"/>
            </div>
          </form>
          <div id="register">
            <h4 id="title">Not a Player?</h4>
            <a href="./register.html"
              ><div id="registerBtn">
                <h4>Register</h4>
              </div></a
            >
          </div>
          <div id="options">
            <div class="btnWrap" id="backBtn">
              <div class="btnBorderTOP"></div>
              <div class="btnBorderBOTTOM"></div>
              <div class="btnBorderLEFT"></div>
              <div class="btnBorderRIGHT"></div>
              <div class="btn" onclick="history.back();">
                <i class="fa fa-chevron-left"></i>
              </div>
            </div>
            <div class="btnWrap" id="signInBtn">
              <div class="btnBorderTOP"></div>
              <div class="btnBorderBOTTOM"></div>
              <div class="btnBorderLEFT"></div>
              <div class="btnBorderRIGHT"></div>
              <div class="btn">
              <i class="fa fa-sign-in"></i>
              </div>
            </div>
          </div>
      </div>
      <div id="foot">
        <div></div>
        <div id="vault"><h2>Vault</h2></div>
        <div id="settings"><i class="fa fa-cog"></i></div>
      </div>
    </div>
    <script src="./logIn.js"></script>
    <script src="test.js"></script>
  </body>
</html>
