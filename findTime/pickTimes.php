<?php
    session_start();
    //redirect if session isn't running
    $tmpTeam1 = "Test Team 5";
    $tmpTeam2 = "Test Team 6";
    $tmpRound = 0;
    $tmpTournament = "Test tournament 2";
    if(!isset($_SESSION['user'])){
        header("Location: ../signIn.php?redirect=1&team1=" . $tmpTeam1 . "&team2=" . $tmpTeam2 . "&round=" . tmpRound . "&tournament=" . $tmpTournament);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>Home</title>
</head>
<body>
    <div id="container">
        <div id="popup">
            <div id="msg"><h1>Message</h1></div>
            <div id="acknowledge"><h1>OK</h1></div>
        </div>
        <div id="wrapMain">
            <div id="mainHead">
                <h1>Select your available times</h1>
            </div>
            <div id="mainBody"></div>
            <div id="mainFoot">
                <div id="continue">
                    <h1>Continue</h1>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="index.js"></script>
</html>