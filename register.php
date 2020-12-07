<?php
    /*Error Codes
    a: email not set
    b: username not set
    c: password not set
    d: password confirm not set
    e: passwords don't match
    f: email not valid
    g: username not long enough
    h: password doesn't contain a number
    i: password not long enough
    j: password doesn't have a special character
    */
    //Set vars
    $error_code = "";
    $email = $_POST['email'];
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    $passRep = $_POST['passRep'];
    // Check if set and not empty
    if(!isset($email) || empty($email)){
        $error_code .= 'a';
    }
    if(!isset($user) || empty($user)){
         $error_code .= 'b';
    }
    if(!isset($pass) || empty($pass)){
       $error_code .= 'c';
    }
    if(!isset($passRep) || empty($passRep)){
        $error_code .= 'd';
    }
    //Sanitize input
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $user = filter_var($user, FILTER_SANITIZE_STRING);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);
    $passRep = filter_var($passRep, FILTER_SANITIZE_STRING);
    //Check passwords match
    if($_POST['pass'] != $_POST['passRep']){
        $error_code .= 'e';
    }
    //Validate email
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error_code .= 'f';
    }
    //Check username long enough
    if(strlen($user) < 8){
        $error_code .= 'g';
    }
    //Check pass long enough
    if(strlen($pass) < 8){
        $error_code .= 'i';
    }
    //Check if pass contains number
    $pass_array = str_split($pass, 1);
    $found = false;
    foreach($pass_array as $num){
        if(is_numeric($num)){
            $found = true;
        break;
        }
    }
    if($found == false){
        $error_code .= 'h';
    }
    //Check pass contains special character
    $found_spec = false;
    foreach($pass_array as $test){
        if($test == '!' || $test == '#' || $test == '$' || $test == '%' || $test == '^' || $test == '&' || $test == '*' || $test == '(' || $test == ')'){
            $found_spec = true;
        break;
        }
    }
    if($found_spec == false){
        $error_code .= 'j';
    }
    //hash password
    $pass = hash("sha256", $pass);                           
    //Check if there are any errors
    if($error_code != ""){
        header("Location: ./register.html?registration=fail&errorCode=" . $error_code);
    }
    else{
        //Connect to db
        include_once './dbConn.php';
        //Set query
        $primColor = "#000000";
        $secondaryColor = "#ffffff";
        $tertiaryColor = "#000000";
        $sql = "INSERT INTO users (username, pass, email, primary_color, secondary_color, tertiary_color) VALUES (?, ?, ?, ?, ?, ?);";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)) {
            echo "Query failed to prep.";
        }
        else {
            session_start();
            $_SESSION['user'] = $user;
            mysqli_stmt_bind_param($stmt, "ssssss", $user, $pass, $email, $primColor, $secondaryColor, $tertiaryColor);
            mysqli_stmt_execute($stmt);
            header("Location: ./welcome.php");
        }
        mysqli_close($conn);
    }
?>