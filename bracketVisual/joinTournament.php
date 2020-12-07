<?php
$documentPath = $_SERVER['DOCUMENT_ROOT'];
include_once $documentPath . "/Interact/getTournamentGroupingStyle.php";
$tournamentName = $_GET['tournamentName'];
if($groupingStyle == "team"){
    header("Location: /Interact/bracketVisual/joinTournamentTeamSelect.php?tournamentName=" . $tournamentName);
}
else{
    header("Location: /Interact/newPlayerTournamentAssociation.php?tournamentName=" . $tournamentName);
}
?>