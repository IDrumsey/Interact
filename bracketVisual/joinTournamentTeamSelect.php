<?php
if(session_id() == ""){
  session_start();
}
$userPlayer = true;
include_once "../getUserTeamInfo.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <link rel="stylesheet" href="./joinTournamentTeamSelect.css">
    <title>Select Your Team</title>
</head>
<body>
    <div id="container">
    <div id="teamListTitle"><h2>Select Your Team</h2></div>
        <div id="body">
            <div id="teamList">
                <?php
                for($t = 0; $t < sizeof($allTeamInfo); $t++){
                  echo "<div class='team'>
                  <div class='teamLogoWrapper'>
                    <img src='/Interact/teams/profileImages/" . $allTeamInfo[$t]['team_ID'] . ".jpg' class='teamLogo'>
                  </div>
                  <a href='/Interact/newTeamTournamentAssociation.php?tournamentName=" . $_GET['tournamentName'] . "&teamName=" . $allTeamInfo[$t]['team_Name'] . "'>
                  <div class='teamName'><h4>" . $allTeamInfo[$t]['team_Name'] . "</h4></div>
                  </a>
                  <div class='teamWon'><h4>Won : " . $allTeamInfo[$t]['numWins'] . "</h4></div>
                  <div class='teamLost'><h4>Lost : " . $allTeamInfo[$t]['numLosses'] . "</h4></div>
                  <div class='more'><i class='fa fa-arrow-right'></i></div>
                </div>";
                }
                ?>
              </div>
              <div id="foot">
                <div id="backBtn">
                    <?php
                      echo "<a href='./bracket.php?tournamentName=" . $_GET['tournamentName'] . "'><i class='fa fa-arrow-left'></i></a>";
                    ?>
                </div>
              </div>
        </div>
    </div>
</body>
</html>