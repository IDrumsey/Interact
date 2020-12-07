<?php
session_start();
include_once 'dbConn.php';
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
    k : Failed to bind params
    l :
    m :
    n :
    o :
    p : 
*/
$teamName = $_POST['teamName'];
$errorCode = "";
$user_teams = [];
$sql = "INSERT INTO teamdetails (team_Name, numWins, numLosses, logo_set) VALUES (?, 0, 0, 0);";
$stmt = mysqli_stmt_init($conn);
if(mysqli_stmt_prepare($stmt, $sql) == false){
    $errorCode .= "i4";
}
else{
    if(mysqli_stmt_bind_param($stmt, 's', $teamName) == false){
        $errorCode .= "k4";
    }
    else{
        if(mysqli_execute($stmt) == false){
            $errorCode .= "j4";
        }
        else{
            $memberRank = "owner";
            //associate new team with current user
            $sql = "INSERT INTO teamassociations (userID, teamID, teamassociations.rank) VALUES ((SELECT id FROM users WHERE username = ?), (SELECT team_ID FROM teamdetails WHERE teamdetails.team_Name = ?), ?);";
            $stmt = mysqli_stmt_init($conn);
            if(mysqli_stmt_prepare($stmt, $sql) == false){
                $errorCode .= "i5";
            }
            else{
                if(mysqli_stmt_bind_param($stmt, 'sss', $_SESSION['user'], $teamName, $memberRank) == false){
                    $errorCode .= "k5";
                }
                else{
                    if(mysqli_execute($stmt) == false){
                        $errorCode .= "j5";
                    }
                    else{
                        //Upload team logo
                        if($_FILES['teamLogo']['tmp_name'] != "") {
                            //set file vars to validate file type
                            $fileName = $_FILES['teamLogo']['name'];
                            $fileTempName = $_FILES['teamLogo']['tmp_name'];
                            $fileSize = $_FILES['teamLogo']['size'];
                            $fileError = $_FILES['teamLogo']['error'];
                            $fileType = $_FILES['teamLogo']['type'];
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
                                        //Get team id
                                        $sql = "SELECT team_ID FROM teamdetails WHERE teamdetails.team_Name = ?;";
                                        //Statement #2
                                        $stmt = mysqli_stmt_init($conn);
                                        if(!mysqli_stmt_prepare($stmt, $sql)) {
                                            $errorCode .= 'i2';
                                        }
                                        else {
                                                mysqli_stmt_bind_param($stmt, "s", $teamName);
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
                                        $profileImageDestination = './teams/profileImages/' . $userID . '.' . $fileExtFormatted;
                                        $fileUpload = move_uploaded_file($fileTempName, $profileImageDestination);
                                        if($fileUpload){
                                            //update team profile image set status
                                            $sql = "UPDATE teamdetails SET logo_set = 1 WHERE team_Name = ?;";
                                            //Statement #3
                                            $stmt = mysqli_stmt_init($conn);
                                            if(!mysqli_stmt_prepare($stmt, $sql)) {
                                                $errorCode .= 'i3';
                                            }
                                            else {
                                                    mysqli_stmt_bind_param($stmt, "s", $teamName);
                                                    if(mysqli_stmt_execute($stmt) == false){
                                                        //Failed to update image status
                                                        $errorCode .= 'j3';
                                                    }
                                                    else{
                                                        //Successfully updated image status
                                                        header('Location: ./teams.create.addPlayer.php?team=' . $teamName);
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
                    }
                }
            }
        }
    }
}
if($errorCode != ""){
    header('Location: ./teams.create.php?error=' . $errorCode);
}
mysqli_close($conn);
?>