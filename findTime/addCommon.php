<?php
if(!isset($conn)){
    include_once "../dbConn.php";
}
$sql = "UPDATE tournamentmatch SET start_time = ?, end_time = ? WHERE id = ?";
$stmt = mysqli_stmt_init($conn);
if(mysqli_stmt_prepare($stmt, $sql) == false){
    echo "Error in preparing sql statement";
}
else{
    if(mysqli_stmt_bind_param($stmt, 'ssi', $_POST['start'], $_POST['end'], $_POST['match']) == false){
        echo "Error in binding parameters";
    }
    else{
        if(mysqli_execute($stmt) == false){
            echo "Error in running query";
        }
        else{
            echo "Worked";
        }
    }
}
mysqli_stmt_close($stmt);
?>