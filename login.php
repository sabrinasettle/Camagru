<?php
    session_start();

    require 'config/database.php';

    function auth($user_name, $passwd)
    {
        /*database info.*/
        $conn = connect_mysql();
        /*finding match users account*/
        $hashed_passwd = hash("whirlpool", $passwd);
        $login_query = mysqli_query($conn, "SELECT * FROM `users`");
        $flag = 1;
        $is_ver = 1;
        while ($row = mysqli_fetch_array($login_query)) {
            if ($row['user_name'] == $user_name && $row['passwd'] == $hashed_passwd && $row['is_verified'] == $is_ver) {
                return (TRUE);
            }
        }
        return (FALSE);
        mysqli_close($conn);
    }

    if (isset($_POST['user_name']) && $_POST['passwd'] && auth($_POST['user_name'], $_POST['passwd']) == TRUE)
    {
        $_SESSION['user_logged_in'] = $_POST['user_name'];
        echo ('Logged In!');
        // sends a script to my html output, just adds it in and changes the location
        header("Location: http://localhost:3000/index.php");
    }
?>

<?php $page_title = 'Camagru - Login';require('incs/header.inc.php') ?>
<html>
    <head>
        <title>Camagru</title>
    </head>
    <body>
        <form class="w3-container w3-card-4 w3-border w3-border-gray w3-margin" id="img_form" action="" method="post" enctype="multipart/form-data">
        <h2>Login Page</h2>
        <a href="index.php">Click here to go back</a><br/><br/>
           <input type="text" name="user_name" placeholder="Username" required="required" value="" /> <br/>
           <input type="password" name="passwd" placeholder="Enter Password" required="required" value="" /> <br/>
           <br>
            <a href="incs/reset_password.inc.php">Forgot Password?</a>
           <br>
           <input type="submit" value="Login"/>
            <br>
        </form>
    </body>
</html>