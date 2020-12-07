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
    <link rel="stylesheet" href="./tournament.create.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <title>New Tournament</title>
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
        <h1 id="titleText">NEW TOURNAMENT</h1>
      </div>
      <div id="body">
        <form action="createNewTournament.php" id="newTournament" method="POST">
          <div id="tournamentTitle">
            <h2 class="highlightText">Title</h2>
            <input
              type='text'
              name='tournamentTitle'
              id='tournamentName'
              onfocus="setOutlineColor(this);"
            />
          </div>
          <div id="prize">
            <div id="prizeOptions">
              <div>
                <input
                  type="radio"
                  name="prizeOption"
                  id="totalAmount"
                  value="totalAmount"
                />
                <label for="totalAmount" class="highlightText">Prize Total</label>
              </div>
              <div>
                <input
                  type="radio"
                  name="prizeOption"
                  id="entryFee"
                  value="entryFee"
                />
                <label for="entryFee" class="highlightText">Entry Fee</label>
              </div>
              <div>
                <input type="radio" name="prizeOption" id="free" value="free" />
                <label for="free" class="highlightText">Free to Join</label>
              </div>
            </div>
            <div id="setTotalAmount">
              <h2 class="highlightText">Amount</h2>
              <input
                type="text"
                name="prizeAmount"
                onfocus="setOutlineColor(this, '#0000ff');"
              />
            </div>
          </div>
          <div id="startDate">
            <h2 class="highlightText">Start Date</h2>
            <input
              type="date"
              name="startDate"
              onfocus="setOutlineColor(this, '#0000ff');"
            />
          </div>
          <div id="endDate">
            <div id="endDateOption">
              <input
                type="checkbox"
                name="setEndDateOption"
                id="setEndDateOption"
              />
              <label for="setEndDateOption" class="highlightText">Set End Date?</label>
            </div>
            <div id="setEndDate">
              <h2 class="highlightText">End Date</h2>
              <input
                type="date"
                name="endDate"
                onfocus="setOutlineColor(this, '#0000ff');"
              />
            </div>
          </div>
          <div id="bracket">
            <h2 class="highlightText">Tournament Bracket Type</h2>
            <div id="bracketOptions">
              <div class="bracketOption">
                <input
                  type="radio"
                  name="bracketType"
                  id="roundRobin"
                  value="Round Robin"
                />
                <label for="roundRobin" class="highlightText">Round Robin</label>
              </div>
              <div class="bracketOption">
                <input
                  type="radio"
                  name="bracketType"
                  id="doubleRoundRobin"
                  value="Double Round Robin"
                />
                <label for="doubleRoundRobin" class="highlightText">Double Round Robin</label>
              </div>
              <div class="bracketOption">
                <input
                  type="radio"
                  name="bracketType"
                  id="singleElim"
                  value="Single Elimination"
                />
                <label for="singleElim" class="highlightText">Single Elimination</label>
              </div>
              <div class="bracketOption">
                <input
                  type="radio"
                  name="bracketType"
                  id="doubleElim"
                  value="Double Elimination"
                />
                <label for="doubleElim" class="highlightText">Double Elimination</label>
              </div>
            </div>
          </div>
          <div id="prizeDistribution">
            <h2 class="highlightText">How to Distribute the Winnings</h2>
            <div id="prizeDistributionOptions">
              <div class="prizeDistributionOption">
                <input
                  type="radio"
                  name="prizeDistributionType"
                  id="everyoneAWinner"
                  value="everyoneAWinner"
                />
                <label for="everyoneAWinner" class="highlightText">Everyone's a Winner</label>
              </div>
              <div class="prizeDistributionOption">
                <input
                  type="radio"
                  name="prizeDistributionType"
                  id="topThree"
                  value="topThree"
                />
                <label for="topThree" class="highlightText">1st, 2nd, 3rd</label>
              </div>
              <div class="prizeDistributionOption">
                <input
                  type="radio"
                  name="prizeDistributionType"
                  id="winnerOnly"
                  value="winnerOnly"
                />
                <label for="winnerOnly" class="highlightText">Winner Takes All</label>
              </div>
            </div>
          </div>
          <div id="game">
            <h2 class="highlightText">Game</h2>
            <div id="gameOptions">
              <div class="gameOption">
                <input
                  type="radio"
                  name="gameChoices"
                  id="Overwatch"
                  value="Overwatch"
                />
                <label for="Overwatch" class="highlightText">Overwatch</label>
              </div>
            </div>
          </div>
          <div id="grouping">
            <h2 class="highlightText">Grouping Style</h2>
            <div id="groupingOptions">
              <div class="groupingOption">
                <input
                  type="radio"
                  name="groupingChoices"
                  id="team"
                  value="team"
                />
                <label for="team" class="highlightText">Team</label>
              </div>
              <div class="groupingOption">
                <input
                  type="radio"
                  name="groupingChoices"
                  id="individual"
                  value="individual"
                />
                <label for="individual" class="highlightText">Individual</label>
              </div>
            </div>
          </div>
        </form>
        <div id="create"><h1 class="highlightText">Create</h1></div>
      </div>
      <div id="foot">
        <a id="back" href="play.home.php">
          <i class="fa fa-arrow-left footBtn"></i>
        </a>
        <div id="vault"><h1 class="footBtn">Vault</h1></div>
        <div id="settingsBtn"><i class="fa fa-cog footBtn"></i></div>
      </div>
    </div>
  </body>
  <script src="newTournament.js"></script>
  <script src="/Interact/base.js"></script>
  <script type="text/javascript">
  var primaryColor = <?php echo json_encode($_SESSION['primaryColor']); ?>;
  var secondaryColor = <?php echo json_encode($_SESSION['secondaryColor']); ?>;
  var tertiaryColor = <?php echo json_encode($_SESSION['tertiaryColor']); ?>;
  var objList = [['id', 'titleText', 'secondary', 'text-shadow', null], ['id', 'container', 'primary', 'background', {type: 'linear-gradient', direction: 'to bottom'}], ['class', 'footBtn', 'secondary', 'text-shadow', null], ['class', 'highlightText', 'secondary', 'text-shadow', null]];
  </script>
</html>
