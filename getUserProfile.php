<?php
  if(session_id() == ""){
    session_start();
  }
  if(!isset($_SESSION['user'])){
    header("Location: ./signOut.php");
  }
  include_once "./dbConn.php";
  $profileImageSet = false;
  $profileImageSetSQL = "SELECT profileImageSet FROM users WHERE username = ?;";
  $profileImageStmt = mysqli_stmt_init($conn);
  if(!mysqli_stmt_prepare($profileImageStmt, $profileImageSetSQL)){
    echo "Couldn't prepare profile image check query";
  }
  else{
    mysqli_stmt_bind_param($profileImageStmt, 's', $_GET['player']);
    if(mysqli_stmt_execute($profileImageStmt) == false){
      echo "Failed to run SQL query on DB";
    }
    else{
      $profileImageResult = mysqli_stmt_get_result($profileImageStmt);
      $profileImageSetRow = mysqli_fetch_array($profileImageResult, MYSQLI_NUM);
      if(mysqli_num_rows($profileImageResult) > 0){
        if($profileImageSetRow[0] == 1){
          $profileImageSet = true;
        }
      }
    }
  }
  mysqli_stmt_close($profileImageStmt);
  if($profileImageSet == true){
    //get user id
    $sql = "SELECT id FROM users WHERE username = ?;";
    //Statement #2
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        $errorCode .= 'i2';
    }
    else {
            mysqli_stmt_bind_param($stmt, "s", $_GET['player']);
            if(mysqli_stmt_execute($stmt) == false){
                $errorCode .= 'j2';
            }
            else{
                $uIdResult = mysqli_stmt_get_result($stmt);
                $row = mysqli_fetch_array($uIdResult, MYSQLI_NUM);
                    $userID = $row[0];
            }
        mysqli_stmt_close($stmt);
    }
    if(isset($userID)){
      $profile_image = "users/profileImages/" . $userID . ".jpg";
    }
  }
?>