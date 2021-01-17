<?php
    include_once $_SERVER['DOCUMENT_ROOT'] . '/Interact/main.php';

    //Get user id
    $user_id = query_user_id($_SESSION['user'], $conn);
    //get user
    $user = get_user_info($user_id, $conn);
    //get user tournaments
    $user_tournaments = query_user_tournaments($user_id, $conn);

    //add user id
    $user->user_id = $user_id;

    //get user invitations
    $user_invitations = query_user_invitations($user_id, $conn);

    //Combine into one response
    $res = [];
    $res['user'] = $user;
    $res['tournaments'] = $user_tournaments;
    $res['invitations'] = $user_invitations;

    print_r(json_encode($res));
?>