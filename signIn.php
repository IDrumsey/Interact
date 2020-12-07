<?php
    include_once './dbConn.php';
    $sql = "SELECT * FROM users WHERE username = ? AND pass = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        echo "Query failed to prep.";
    }
    else {
        mysqli_stmt_bind_param($stmt, "ss", $_POST['user'], hash("sha256"
        , $_POST['pass']));
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_store_result($stmt);
        $numRows = mysqli_stmt_num_rows($stmt);
        mysqli_stmt_close($stmt);
        if($numRows != 1) {
            header("Location: ./signIn.html?signIn=fail");
        }
        else {
            session_start();
            $_SESSION['user'] = $_POST['user'];
            header("Location: ./dashboard.php?player=" . $_SESSION['user']);
        }
    }
    mysqli_close($conn);
?>