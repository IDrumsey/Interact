<?php
session_start();
if(!isset($_SESSION['user'])){
  header("Location: ./signOut.php");
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./backbone.css" />
    <link rel="stylesheet" href="./welcome.css" />
    <title>Welcome</title>
  </head>
  <body>
    <div id="container">
      <div id="head">
        <div id="headContent">
          <div id="welcomeText"><h1>WELCOME</h1></div>
          <div id="playerName"><h1><?php echo $_SESSION['user']; ?></h1></div>
        </div>
      </div>
      <div id="body">
        <div id="popup"></div>
        <form
          action="./userGames.php"
          id="newPlayerForm"
          method="POST"
          enctype="multipart/form-data"
        >
          <div id="iconSelection">
            <h1>CHOOSE AN ICON</h1>
            <div id="preview">
              <input type="file" name="profileImage" id="profileImageSelect" />
            </div>
          </div>
          <div id="gameSelection">
            <h1>GAMES</h1>
            <select name="games[]" id="games" multiple>
              <option value="Overwatch">Overwatch</option>
              <option value="Overwatch">Overwatch</option>
              <option value="Overwatch">Overwatch</option>
              <option value="Overwatch">Overwatch</option>
            </select>
          </div>
        </form>
      </div>
      <div id="foot">
        <div id="continueBtn"><h1>Continue</h1></div>
      </div>
    </div>
  </body>
  <script src="./welcome.js"></script>
</html>
