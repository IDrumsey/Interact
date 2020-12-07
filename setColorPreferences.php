<?php
if(session_id() == ""){
    session_start();
}
if(!isset($conn)){
    include_once "./dbConn.php";
}
$sql = "UPDATE users SET primary_color = ?, secondary_color = ?, tertiary_color = ? WHERE username = ?;";
$stmt = mysqli_stmt_init($conn);
if(mysqli_stmt_prepare($stmt, $sql) == false){
    echo "Error in preparing sql statement";
}
else{
    if(mysqli_stmt_bind_param($stmt, 'ssss', $_GET['primaryColor'], $_GET['secondaryColor'], $_GET['tertiaryColor'], $_SESSION['user']) == false){
        echo "Error in binding parameters";
    }
    else{
        if(mysqli_execute($stmt) == false){
            echo "Error in running query";
        }
        else{
            $_SESSION['primaryColor'] = $_GET['primaryColor'];
            $_SESSION['secondaryColor'] = $_GET['secondaryColor'];
            $_SESSION['tertiaryColor'] = $_GET['tertiaryColor'];
            header("Location: dashboard.php?player=" . $_SESSION['user']);
        }
    }
}
mysqli_close($conn);
?>