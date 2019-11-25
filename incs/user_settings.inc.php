<?php $page_title = 'Camagru - Settings';require('header.inc.php') ?>

<?php
    session_start();
    $user = $_SESSION['user_logged_in'];
    require '../config/database.php';

    $conn = connect_mysql();

    if (!isset($_SESSION['user_logged_in']))
    {
        header('Location: ../login.php');
    }

    function uname_validity($usrnam) {
        if (!preg_match("/^[a-zA-Z0-9]*$/", $usrnam) || strlen($usrnam) >= 15 || strlen($usrnam) <= 1) {
            echo "<script>alert('Please make sure your username only consists of either uppercase letters, lowercase letters or number characters and that it is bigger than 1 character and smaller than 15 characters.')</script>";
            echo "<script>window.open('user_settings.inc.php','_self')</script>";
            exit();
        }
    }

    function password_complexity($pass) {
        // 8 chars long is a min for a complex password
        if (strlen($pass) < 8) {
            echo "<script>alert('Please make sure your password is 8 characters or longer.')</script>";
            echo "<script>window.open('user_settings.inc.php','_self')</script>";
            exit();
        }
        $containsLower = preg_match('/[a-z]/', $pass);
        $containsUpper = preg_match('/[A-Z]/', $pass);
        $containsDigit = preg_match('/[0-9]/', $pass);
        $containsSpecial = preg_match('/[\W]+/', $pass);
        if (!$containsUpper || !$containsLower || !$containsDigit || !$containsSpecial) {
            echo "<script>alert('Please make sure your password has an array of lowercase letters, uppercase letters, at least one digit and at least one special character.')</script>";
            echo "<script>window.open('user_settings.inc.php','_self')</script>";
            exit();
        }
    }

    function change_username($user, $conn){
        $new_uname = htmlspecialchars($_POST['user_name']);
        uname_validity($new_uname);
        $check_username = $conn->query("SELECT user_name FROM users WHERE user_name='$new_uname'");
        $count=$check_username->num_rows;
    
        if ($count==0) {
            mysqli_query($conn, "UPDATE users SET user_name = '$new_uname' WHERE user_name = '$user'");
        }
    }

    function change_password($user, $new_password, $conn){
        password_complexity($new_password);
        $hashed_password = hash('whirlpool',$new_password);
        mysqli_query($conn, "UPDATE users SET passwd = '$hashed_password' WHERE user_name = '$user'");
    }

    function change_email($user, $new_email, $conn){
        mysqli_query($conn, "UPDATE users SET email = '$new_email' WHERE user_name = '$user'");
    }

    // get_emails BOOLEAN
    function email_boolean_false($user, $conn){
        $false = mysqli_query($conn, "UPDATE users SET get_emails = 0 WHERE user_name = '$user' ");
        if ($false){
            echo "<script>alert('Changed Email Preferences')</script>";
        }
    }

    function email_boolean_true($user, $conn){
        $true = mysqli_query($conn, "UPDATE users SET get_emails = 1 WHERE user_name = '$user' ");
        if ($true){
            echo "<script>alert('Changed Email Preferences, will now recieve emails!')</script>";
        }
    }

    if(isset($_POST['btn-change-username'])) {
        change_username($user, $conn);
        $_SESSION['user_logged_in'] = $_POST['user_name'];
    }

    if(isset($_POST['btn-change-password'])){
        $new_password = htmlspecialchars($_POST['passwd']);
        change_password($user, $new_password, $conn);
    }

    if (isset($_POST['btn-change-email'])){
        $new_email = htmlspecialchars($_POST['email']);
        change_email($user, $new_email, $conn);
    }

    if (array_key_exists('btn-boolean-false',$_POST)){
        email_boolean_false($user, $conn);
    }

    if (array_key_exists('btn-boolean-true',$_POST)){
        email_boolean_true($user, $conn);
    }
?>

<html>
    <body>
        <div class="page">
        <p> ITS ME THE USER <?php echo $_SESSION['user_logged_in']?> </p>
            <div class="form con">
                <?php if(isset($_SESSION['user_logged_in'])):?>
                    <h2>Settings</h2>
                    <a href="profile.inc.php">Back to Your Profile</a><br/><br/>
                    <form action="" method="POST">
                    <input type="text" name="user_name" required="" /> <button type="submit" class="btn btn-default" name="btn-change-username">
                            <span class="glyphicon glyphicon-log-in"></span> &nbsp; Change Username
                        </button><br/>
                    </form>
                    <form action="" method="POST">
                    <input type="email" name="email"  /> <button type="submit" class="btn btn-default" name="btn-change-email">
                            <span class="glyphicon glyphicon-log-in"></span> &nbsp; Change Email
                        </button> <br/>
                    </form>
                    <form action="" method="POST">
                    <input type="password" name="passwd" required=""  /> <button type="submit" class="btn btn-default" name="btn-change-password">
                            <span class="glyphicon glyphicon-log-in"></span> &nbsp; Change Password
                        </button><br/>
                        <br>
                    </form>
                    <form method="post"> 
                        <?php // if the user and the user disable then show the reenable button?>
                        <p> Do you wish to recieve emails?
                        <br>
                        <button type="submit" class="btn btn-default" name="btn-boolean-false" value=>
                            <span class="glyphicon glyphicon-log-in"></span> &nbsp;Please do not send me any emails
                        </button>
                        <p>OR change it back here</p>
                        <button type="submit" class="btn btn-default" name="btn-boolean-true" value=>
                            <span class="glyphicon glyphicon-log-in"></span> &nbsp;I would like to recieve emails again
                        </button>
                    </form>
                <?php endif?>
            </div>
            <br>
        </div>
    </body>
</html>

