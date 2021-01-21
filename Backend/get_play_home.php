<?php
    include_once '../main.php';

    //Get game options
    $games = query_available_games($conn);

    print_r(json_encode($games));
?>