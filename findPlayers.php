<?php
if(!isset($conn)){
    include_once "./dbConn.php";
}
$players = [];
$playerSearch = $_POST['playerName'];
$playerNameSearch = '%' . $playerSearch . '%';
$sql = "SELECT username, id, profileImageSet FROM users WHERE username LIKE ?;";
$stmt = mysqli_stmt_init($conn);
if(mysqli_stmt_prepare($stmt, $sql) == false){
    echo "Error in preparing sql statement";
}
else{
    if(mysqli_stmt_bind_param($stmt, 's', $playerNameSearch) == false){
        echo "Error in binding parameters";
    }
    else{
        if(mysqli_execute($stmt) == false){
            echo "Error in running query";
        }
        else{
            $result = mysqli_stmt_get_result($stmt);
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                array_push($players, $row);
            }
        }
    }
}
mysqli_stmt_close($stmt);
?>