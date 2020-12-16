<?php
$docPath = $_SERVER['DOCUMENT_ROOT'];
include_once $docPath . "/Interact/getUserProfile.php";
include_once $docPath . "/Interact/getColorPreferences.php";
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
        <?php echo "<div onclick='redir(1);' class='spinEl'> <img src='" . $profile_image . "' alt='Profile Picture' id='profilePic'></div>"; ?>
        </div>
      </div>
      <div id="body">
        <div id="upcomingGames">
          <div class="title"><h2 class="sectionTitle">Upcoming Games</h2></div>
          <div class="list">
            <?php
              //Get all upcoming user involved games
              $sql = "SELECT tournamentmatch.start_time, t1.team_Name AS 'team1', t2.team_Name AS 'team2' FROM tournamentmatch INNER JOIN teamdetails t1 ON t1.team_ID = tournamentmatch.team1_ID INNER JOIN teamdetails t2 ON t2.team_ID = tournamentmatch.team2_ID INNER JOIN teamassociations ON t1.team_ID = teamassociations.teamID OR t2.team_ID = teamassociations.teamID INNER JOIN users ON users.id = teamassociations.userID WHERE users.username = ?;";
              $sqlStmt = mysqli_stmt_init($conn);
              if(mysqli_stmt_prepare($sqlStmt, $sql) == false){
                echo "failed to prep statement";
              }
              else{
                if(mysqli_stmt_bind_param($sqlStmt, 's', $_SESSION['user']) == false){
                  echo "failed to bind params to statement";
                }
                else{
                  if(mysqli_stmt_execute($sqlStmt) == false){
                    echo "failed to run query";
                  }
                  else{
                    $results = mysqli_stmt_get_result($sqlStmt);
                    if(mysqli_num_rows($results) > 0){
                      while($row = mysqli_fetch_assoc($results)){
                        echo "<div class='upcomingGame'>
                        <div class='time'><h3>" . $row['start_time'] . "</h3></div>
                        <div class='teams'>
                          <div class='teamA'><h3>" . $row['team1'] . "</h3></div>
                          <div class='separator'><h3>vs</h3></div>
                          <div class='teamB'><h3>" . $row['team2'] . "</h3></div>
                        </div>
                        <div class='more'><i class='fa fa-arrow-right'></i></div>
                      </div>";
                      }
                    }
                  }
                }
              }
              mysqli_stmt_close($sqlStmt);
            ?>
          </div>
        </div>
        <div id="invitations">
          <div class="title"><h2 class="sectionTitle">Invitations</h2></div>
          <div class="list">
            <?php
              //Get all invitations
              $sql = "SELECT t1.*, t2.username, t3.team_Name AS 'teamName' FROM invitations t1 INNER JOIN users t2 ON t2.id = t1.invitor INNER JOIN teamdetails t3 ON t3.team_ID = t1.team_ID WHERE invited = (SELECT id FROM users WHERE username = ?) AND t1.type = 'Team';";
              $sqlStmt = mysqli_stmt_init($conn);
              if(mysqli_stmt_prepare($sqlStmt, $sql) == false){
                echo "failed to prep statement";
              }
              else{
                if(mysqli_stmt_bind_param($sqlStmt, 's', $_SESSION['user']) == false){
                  echo "failed to bind params to statement";
                }
                else{
                  if(mysqli_stmt_execute($sqlStmt) == false){
                    echo "failed to run query";
                  }
                  else{
                    $results = mysqli_stmt_get_result($sqlStmt);
                    if(mysqli_num_rows($results) > 0){
                      while($row = mysqli_fetch_assoc($results)){
                        $nextPage = "acceptTeamInv.php?team=" . $row['teamName'] . "&player=" . $_SESSION['user'];
                        echo "<div class='invitation'>
                        <div class='invitor'><h3>" . $row['username'] . "</h3></div>
                        <div class='type'><h3>" . $row['type'] . "</h3></div>
                        <div class='options'>
                          <div class='accept'><a href='" . $nextPage . "'><i class='fa fa-check'></i></a></div>
                          <div class='reject'><i class='fa fa-times'></i></div>
                        </div>
                        </div>";
                      }
                    }
                  }
                }
              }
              mysqli_stmt_close($sqlStmt);
              $sql = "SELECT t1.*, t2.username, t3.team_Name AS 'teamName', t4.tournament_Name FROM invitations t1 INNER JOIN users t2 ON t2.id = t1.invitor INNER JOIN teamdetails t3 ON t3.team_ID = t1.team_ID INNER JOIN tournament t4 ON t4.tournament_ID = t1.tournament_ID WHERE invited = (SELECT id FROM users WHERE username = ?) AND t1.type = 'Tournament';";
              $sqlStmt = mysqli_stmt_init($conn);
              if(mysqli_stmt_prepare($sqlStmt, $sql) == false){
                echo "failed to prep statement";
              }
              else{
                if(mysqli_stmt_bind_param($sqlStmt, 's', $_SESSION['user']) == false){
                  echo "failed to bind params to statement";
                }
                else{
                  if(mysqli_stmt_execute($sqlStmt) == false){
                    echo "failed to run query";
                  }
                  else{
                    $results = mysqli_stmt_get_result($sqlStmt);
                    if(mysqli_num_rows($results) > 0){
                      while($row = mysqli_fetch_assoc($results)){
                        $nextPage = "newPlayerTournamentAssociation.php?team=" . $row['teamName'] . "&player=" . $_SESSION['user'] . "&tournamentName=" . $row['tournament_Name'];
                        echo "<div class='invitation'>
                        <div class='invitor'><h3>" . $row['username'] . "</h3></div>
                        <div class='type'><h3>" . $row['type'] . "</h3></div>
                        <div class='options'>
                          <div class='accept'><a href='" . $nextPage . "'><i class='fa fa-check'></i></a></div>
                          <div class='reject'><i class='fa fa-times'></i></div>
                        </div>
                        </div>";
                      }
                    }
                  }
                }
              }
              mysqli_stmt_close($sqlStmt);
            ?>
          </div>
        </div>
        <div id="options">
          <?php echo "<a href='./teams.homepage.php?player=" . $_SESSION['user'] . "' id='teams'><h2>TEAMS</h2></a>"; ?>
          <a href="play.home.php" id='play'><h2>PLAY</h2></a>
        </div>
        <div id="history">
          <div class="title"><h2 class="sectionTitle">History</h2></div>
          <div class="list">
          <?php 
              //Get all past played matches
              $sqlStmt = "SELECT t1.start_date, start_time, t4.team_Name AS 'teamAName', t5.team_Name AS 'teamBName', end_time FROM tournamentmatch t1 INNER JOIN teamassociations t2 ON t2.teamID = t1.team1_ID OR t2.teamID = t1.team2_ID INNER JOIN users t3 ON t3.id = t2.userID INNER JOIN teamdetails t4 ON t4.team_ID = t1.team1_ID INNER JOIN teamdetails t5 ON t5.team_ID = t1.team2_ID WHERE (t1.start_date < DATE(NOW()) OR (t1.start_date = DATE(NOW()) AND t1.end_time < TIME(NOW()))) AND t3.username = ?;";
              $upcomingStmt = mysqli_stmt_init($conn);
                if(mysqli_stmt_prepare($upcomingStmt, $sqlStmt) == true){
                  if(mysqli_stmt_bind_param($upcomingStmt, 's', $_SESSION['user']) == true){
                    if(mysqli_stmt_execute($upcomingStmt) == true){
                      $result = mysqli_stmt_get_result($upcomingStmt);
                      if(mysqli_num_rows($result) > 0){
                        while($row = mysqli_fetch_assoc($result)){
                          echo "<div class='game'>
                          <div class='teams'>
                            <div class='teamA'><h3>" . $row['teamAName'] . "</h3></div>
                            <div class='separator'><h3>vs</h3></div>
                            <div class='teamB'><h3>" . $row['teamBName'] . "</h3></div>
                          </div>
                          <div class='date'><h3>" . $row['start_date'] . "</h3></div>
                          <div class='time'><h3>" . $row['start_time'] . " - " . $row['end_time'] . "</h3></div>
                        </div>";
                        }
                      }
                    }
                  }
                }
                mysqli_stmt_close($upcomingStmt);
            ?>
          </div>
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
  <script src="/Interact/spin.js"></script>
  <script type="text/javascript">
  setListeners('spinEl');
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