<?php
    include_once './dbConn.php';
    $pd = hash("sha256", $_POST['pass']);
    $sql = "SELECT * FROM users WHERE username = ? AND pass = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        echo "Query failed to prep.";
    }
    else {
        mysqli_stmt_bind_param($stmt, "ss", $_POST['user'], $pd);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_store_result($stmt);
        $numRows = mysqli_stmt_num_rows($stmt);
        mysqli_stmt_close($stmt);
        if($numRows != 1) {
            header("Location: ./signIn.php?signIn=fail");
        }
        else {
            var_dump($_GET);
            session_start();
            $_SESSION['user'] = $_POST['user'];
            if(isset($_GET['redirect'])){
                echo $_GET['redirect'];
                if($_GET['redirect'] == 1){
                    //redirect to time availability selection page
                    header("Location: findTime/pickTimes.php?team1=" . $_GET['team1'] . "&team2=" . $_GET['team2'] . "&round=" . $_GET['round'] . "&tournament=" . $_GET['tournament']);
                }
            }
            else{
                header("Location: ./dashboard.php?player=" . $_SESSION['user']);
            }
        }
    }
    mysqli_close($conn);
?>