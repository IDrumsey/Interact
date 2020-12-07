<?php
    session_start();
    include_once './dbConn.php';
    /*Setup errorCodes
    client codes
    a : Profile image not selected
    b : File extension not allowed
    c : File error - Not exactly sure what this entails
    d : File size is too large
    e :
    f :
    g :
    h : 
    dev codes
    i : Failed to prep a statement followed by associated stmt number
    j : SQL error followed by associated stmt number
    k :
    l :
    m :
    n :
    o :
    p : 
    */
    $errorCode = "";
    //insert games into db
    $sql = "INSERT INTO usergameassociations (user_ID, game_ID) VALUES ((SELECT id FROM users WHERE username = ?), (SELECT game_ID FROM game WHERE title = ?));";
    //Statement #1
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        $errorCode .= 'i1';
    }
    else {
        for($i = 0; $i < sizeof($_POST['games']); $i++){
            mysqli_stmt_bind_param($stmt, "ss", $_SESSION['user'], $_POST['games'][$i]);
            if(mysqli_stmt_execute($stmt) == false){
                $errorCode .= 'j1';
            break;
            }
        }
        mysqli_stmt_close($stmt);
    }
    //insert profile pic in file structure
    if($_FILES['profileImage']['tmp_name'] != "") {
        //set file vars to validate file type
        $fileName = $_FILES['profileImage']['name'];
        $fileTempName = $_FILES['profileImage']['tmp_name'];
        $fileSize = $_FILES['profileImage']['size'];
        $fileError = $_FILES['profileImage']['error'];
        $fileType = $_FILES['profileImage']['type'];
        $fileExt = explode('.', $fileName);
        $fileExtFormatted = strtolower(end($fileExt));
        $extensionsAllowed = array('jpg', 'jpeg', 'png');
        //Check extension
        if(in_array($fileExtFormatted, $extensionsAllowed)){
            //ext allowed
            //Check for errors
            if($fileError === 0){
                //No errors
                //Check file size
                if($fileSize < 1000000){
                    //Upload file
                    //Get user id
                    $sql = "SELECT id FROM users WHERE username = ?;";
                    //Statement #2
                    $stmt = mysqli_stmt_init($conn);
                    if(!mysqli_stmt_prepare($stmt, $sql)) {
                        $errorCode .= 'i2';
                    }
                    else {
                            mysqli_stmt_bind_param($stmt, "s", $_SESSION['user']);
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
                    $profileImageDestination = './users/profileImages/' . $userID . '.' . $fileExtFormatted;
                    $fileUpload = move_uploaded_file($fileTempName, $profileImageDestination);
                    if($fileUpload){
                        //update user profile image set status
                        $sql = "UPDATE users SET profileImageSet = 1 WHERE username = ?;";
                        //Statement #3
                        $stmt = mysqli_stmt_init($conn);
                        if(!mysqli_stmt_prepare($stmt, $sql)) {
                            $errorCode .= 'i3';
                        }
                        else {
                                mysqli_stmt_bind_param($stmt, "s", $_SESSION['user']);
                                if(mysqli_stmt_execute($stmt) == false){
                                    //Failed to update image status
                                    $errorCode .= 'j3';
                                }
                                else{
                                    //Successfully updated image status
                                    header('Location: ./dashboard.php?fileUpload=success&player=' . $_SESSION['user']);
                                }
                            mysqli_stmt_close($stmt);
                        }
                    }
                }
                else {
                    //file too large
                    $errorCode .= 'd';
                }
            }
            else{
                //Error occured
                $errorCode .= 'c';
            }
        }
        else{
            //ext not allowed
            $errorCode .= 'b';
        }
    }
    else{
        //Profile image not selected
        $errorCode .= 'a';
    }
    if($errorCode != ""){
        header('Location: ./welcome.php?errorCodes=' . $errorCode);
    }
    mysqli_close($conn);
?>