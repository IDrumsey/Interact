<?php
    $server = "localhost";
    $userN = "root";
    $passW = "WK7aCs51PU7l9dzY";
    $db = "Interact";

    $conn = mysqli_connect($server, $userN, $passW, $db);

    if(mysqli_connect_errno()){
        echo "Couldn't connect to db : " . mysqli_connect_error();
        exit();
    }
?>