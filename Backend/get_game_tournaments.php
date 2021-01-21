<?php
    include_once '../main.php';

    //Get game name
    $game_name = $_POST['game'];

    //Get all game tournaments
    $tournaments = get_game_tournaments($game_name, $conn);

    print_r(json_encode($tournaments));
?>