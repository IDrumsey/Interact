<?php
    include_once '../main.php';

    $team_name = $_GET['team'];

    $team = get_team_info(query_team_id($team_name, $conn), true, $conn);

    print_r(json_encode($team));
?>