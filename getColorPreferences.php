<?php
if(session_id() == ""){
    session_start();
}
if(!isset($conn)){
    include_once "./dbConn.php";
}
$sql = "SELECT primary_color, secondary_color, tertiary_color FROM users WHERE username = ?;";
$stmt = mysqli_stmt_init($conn);
if(mysqli_stmt_prepare($stmt, $sql) == false){
    echo "Error in preparing sql statement";
}
else{
    if(mysqli_stmt_bind_param($stmt, 's', $_SESSION['user']) == false){
        echo "Error in binding parameters";
    }
    else{
        if(mysqli_execute($stmt) == false){
            echo "Error in running query";
        }
        else{
            $result = mysqli_stmt_get_result($stmt);
            $colorPrefs = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $_SESSION['primaryColor'] = $colorPrefs['primary_color'];
            $_SESSION['secondaryColor'] = $colorPrefs['secondary_color'];
            $_SESSION['tertiaryColor'] = $colorPrefs['tertiary_color'];
        }
    }
}
mysqli_stmt_close($stmt);
?>