<?php

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
    
    function str_validity($usrnam, $eml) {
        if  (empty($usrnam) || empty($eml)) {
            echo "<script>alert('Please fill in all fields!')</script>";
            echo "<script>window.open('sign_up.php','_self')</script>";
            exit();
        }
        if (!preg_match("/^[a-zA-Z0-9]*$/", $usrnam) || strlen($usrnam) > 15 || strlen($usrnam) < 1) {
            echo "<script>alert('Please make sure your username only consists of either uppercase letters, lowercase letters or number characters and that it is bigger than 1 character and smaller than 15 characters.')</script>";
            echo "<script>window.open('sign_up.php','_self')</script>";
            exit();
        }
    }
    
    function send_email($email, $key) {

        $subject = 'Camagru Email Verification';
        $body = '<h2>Verify your email</h2>
        <p>Thank you for registering to Camagru, to verify your email adress please follow this link:</p>
        <a href="http://localhost:3000/incs/verify_user.inc.php?key='.$key.'">Verify Email</a>';
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-Type:text/html;charset=UTF-8"."\r\n";
        $headers .= "From: <camagru.us@gmail.com>"."\r\n";
        $send = mail($email, $subject, $body, $headers);
        if ($send)
        {
            echo "<script>alert('Please activate your account with the verification link we have sent to your email address.')</script>";
            echo "<script>window.open('login.php','_self')</script>";   
        }
        else {
            echo "<script>alert('Failed to send email!')</script>";
            echo "<script>window.open('sign_up.php','_self')</script>";
        }
    }
?>

<?php
    session_start();
    if (isset($_SESSION['user_logged_in'])!="") {
        header("Location: index.php");
    }
    require_once 'config/database.php';

    if(isset($_POST['btn-signup'])) {

        $conn = connect_mysql();
        $uname = trim(htmlspecialchars($_POST['user_name']));
        $email = trim(htmlspecialchars($_POST['email']));
        $upass = trim(htmlspecialchars($_POST['passwd']));
        $ver = 0;

        password_complexity($upass);
        str_validity($uname, $email);
        
        $key = hash('whirlpool', $uname);

        $check_username = $conn->query("SELECT user_name FROM users WHERE user_name='$uname'");
        $count = $check_username->num_rows;
        
        if ($count==0) {
        
            $hashed_password = hash('whirlpool',$upass); // this function works only in PHP 5.5 or latest version
            $query = mysqli_query($conn, "INSERT INTO users(user_name, email, passwd, is_verified, verif_key, get_emails) VALUES('$uname','$email','$hashed_password','$ver','$key', 1)");
            
            if ($query) {
                send_email($email, $key);
                echo "<script>window.open('login.php','_self')</script>";
            } else {
                echo "<script>alert('Failed to sign you up! Please try again')</script>";
                echo "<script>window.open('sign_up.php','_self')</script>";
            }
        } else {
            echo "<script>alert('Username already taken!')</script>";
            echo "<script>window.open('sign_up.php','_self')</script>";
        }
        mysqli_close($conn);
}


?>
<?php $page_title = 'Camagru - Sign Up';require('incs/header.inc.php') ?>
<html>
    <head>
        <title>Camagru</title>
    </head>
    <body>
        <form class="w3-container w3-card-4 w3-border w3-border-gray w3-margin-top" id="img_form" action="" method="post" enctype="multipart/form-data">
        <h2>Sign Up for Camagru</h2>
        <a href="index.php">Back to the Main Page</a><br/><br/>
           Username: <input type="text" name="user_name" required="required" /> <br/>
           Email: <input type="email" name="email" required="required" /> <br/>
           Password: <input type="password" name="passwd" required="required" /> <br/>
            <button type="submit" class="btn btn-default" name="btn-signup">
                <span class="glyphicon glyphicon-log-in"></span> &nbsp; Create Account
            </button>
            <p>Already have an account? <a href="login.php">Login here</a>.</p> 
        </form>
    </body>
</html>





