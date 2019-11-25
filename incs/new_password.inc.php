<?php

    require_once("../config/database.php");

    function password_complexity($passwd) {
        // 8 chars long is a min for a complex password
        if (strlen($passwd) < 8) {
            echo "<script>alert('Please make sure your password is 8 characters or longer.')</script>";
            exit();
        }
        //checks the complexity of those 8 chars
        $hasUpper = preg_match('/[A-Z]/', $passwd);
        $hasLower = preg_match('/[a-z]/', $passwd);
        $hasDigit = preg_match('/[0-9]/', $passwd);
        $hasSpecial = preg_match('/[\W]+/', $passwd);
        if (!$hasUpper || !$hasLower || !$hasDigit || !$hasSpecial) {
            echo "<script>alert('Please make sure your password has lowercase letters, uppercase letters, and at least one digit and one special character.')</script>";
            exit();
        }
    }

    function change_password($key, $new_password, $conn){
        password_complexity($new_password);
        //needs to pass the complexity then
        $hashed_password = hash('whirlpool',$new_password);
        mysqli_query($conn, "UPDATE users SET passwd = '$hashed_password' WHERE psswd_key='$key'");
    }
    
    $key = $_GET['key'];
    if (!$key){
        echo "<script>alert('Please check for the password reset email we have sent to your email address.')</script>";
    }
    
    if($key) {
        $conn = connect_mysql();
        if (isset($_POST['Submit'])){   
            //gets the password
            $new_password = strip_tags($_POST['passwd']);
            //then 
            change_password($key, $new_password, $conn);
        }
    }

?>

<?php $page_title = 'Camagru';require('header.inc.php') ?>
<html>
    <head>
        <title>Camagru</title>
    </head>
    <body>
        <form class="w3-container w3-card-4 w3-border w3-border-gray w3-margin" id="img_form" action="" method="post" enctype="multipart/form-data">
            <input type="password" name="passwd" placeholder="Enter New Password" required="required" value="" /> <br/>
            <br>
            <input type="submit" name="Submit"/>
            <br>
        </form>
    </body>
</html>