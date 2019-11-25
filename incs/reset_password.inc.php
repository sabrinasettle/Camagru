<?php

    require_once("../config/database.php");

    if(isset($_POST['Reset'])) {

        $conn = connect_mysql();
        $email = strip_tags($_POST['email']);
        //find the email of the user
        $result = mysqli_query($conn, "SELECT email FROM users WHERE email='$email'");
        
        if (!$result){
            echo "<script>alert('Email not regonized')</script>";
        }else{
            $key = bin2hex(random_bytes(50));
            //Update the users table to have randomized string in the pssd_key to transfer by GET
            $db_change = mysqli_query($conn, "UPDATE users SET psswd_key = '$key' WHERE email='$email'");

            if (!$db_change){
                echo "<script>alert('Enable to send email!')</script>";
            }else{
                send_password_reset_email($email, $key);
            }
            
        }
        
    }

    function send_password_reset_email($email, $key) {

        $subject = 'Camagru Password Reset';
        $body = '<h2>Create a new paassword</h2>
        <p>To change your password please follow this link:</p>
        <a href="http://localhost:3000/incs/new_password.inc.php?key='.$key.'">Change Password</a>';
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-Type:text/html;charset=UTF-8"."\r\n";
        $headers .= "From: <camagru.us@gmail.com>"."\r\n";
        $send = mail($email, $subject, $body, $headers);
        if ($send)
        {
            echo "<script>alert('Please check for the password reset email we have sent to your email address.')</script>";
            echo "<script>window.open('login.php','_self')</script>";   
        }
        else {
            echo "<script>alert('Failed to send email!')</script>";
            echo "<script>window.open('sign_up.php','_self')</script>";
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
        <a href="../../login.php">Click here to go back</a><br/><br/>
           <input type="text" name="email" placeholder="Your Email" required="required" value="" /> <br/>
           <br>
           <input type="submit" name="Reset"/>
            <br>
        </form>
    </body>
</html>