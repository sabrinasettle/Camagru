<?php
    session_start();
    if (isset($_SESSION['user_logged_in']))
    {
        header('Location: ../index.php');
    }

    if (isset($_GET['key'])) {
        require '../config/database.php';

        $conn = connect_mysql();
        $key = $_GET['key'];
        $query = mysqli_query($conn, "SELECT id FROM users WHERE verif_key = '$key'");
        if ($query){
            $vertified = mysqli_query($conn, "UPDATE users SET is_verified = 1 WHERE verif_key = '$key'");
            if ($vertified){
                echo "<script>alert('Account is Verified!')</script>";
            }
            $null_key = mysqli_query($conn, "UPDATE users SET verif_key = NULL WHERE verif_key = '$key'");
        }
        else{
            echo "<script>alert('Please sign up to recieve an email')</script>";
        }
    }

?>


<?php $page_title = 'Camagru - Account Verified';require('header.inc.php')?>
<p> Thanks for verifying your account, to access all sharing features please login </p>
<div class="w3-container w3-padding signup w3-display-middle w3-half">
</div>
