<?php 

    session_start();

    // The email is sent to the owner of the post rather than the current user unlike the other emails sent
    function notify_email_like($user, $conn){
        
        $user_info = mysqli_query($conn, "SELECT email FROM users WHERE id = $user AND get_emails IS TRUE");
        if ($user_info){
            $user_row = $user_info->fetch_row();
            $email = $user_row[0];
            $subject = 'Camagru Notification';
            $body = '<h2>Camagru Notification</h2>
            <p>Someone liked your photo </p>';
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-Type:text/html;charset=UTF-8"."\r\n";
            $headers .= "From: <camagru.us@gmail.com>"."\r\n";
            $send = mail($email, $subject, $body, $headers);
        }
    }

    function notify_email_comment($user, $conn){
        
        $user_info = mysqli_query($conn, "SELECT email FROM users WHERE id = $user AND get_emails IS TRUE");
        if ($user_info){
            $user_row = $user_info->fetch_row();
            $email = $user_row[0];
            $subject = 'Camagru Notification';
            $body = '<h2>Camagru Notification</h2>
            <p>Someone commented on your photo </p>';
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-Type:text/html;charset=UTF-8"."\r\n";
            $headers .= "From: <camagru.us@gmail.com>"."\r\n"; 
            $send = mail($email, $subject, $body, $headers);
        }
    }

    function submit_comment($user_comment, $post_id) {
        require_once("../config/database.php");
        $conn = connect_mysql();
        $user = $_SESSION['user_logged_in'];
        $res = mysqli_query($conn, "SELECT id FROM users WHERE user_name = '$user'");
        $res_row = $res->fetch_row();
        $comment = mysqli_query($conn, "INSERT INTO comments(user_id, post_id, comment) VALUES($res_row[0], $post_id, '$user_comment')");
        //get user id from the post
        $user_info = mysqli_query($conn, "SELECT user_id FROM posts WHERE post_id = $post_id");
        $user_row = $user_info->fetch_row();
        $user = $user_row[0];
        if ($user){
            notify_email_comment($user, $conn);
        }
    }

    function likes($post_id) {
        require_once("../config/database.php");
        $conn = connect_mysql();
        $like = mysqli_query($conn, "UPDATE posts SET likes = likes + 1 WHERE post_id = $post_id");
        if ($like)
        {
            $updater = mysqli_query($conn, "SELECT likes FROM posts WHERE post_id = $post_id");
            $update_row = $updater->fetch_row();
            //this echo is what the reponse text returns in the likes() in like_image.js
            echo $update_row[0];
            //get user id from the post
            $user_info = mysqli_query($conn, "SELECT user_id FROM posts WHERE post_id = $post_id");
            $user_row = $user_info->fetch_row();
            $user = $user_row[0];
            if ($user){
                notify_email_like($user, $conn);
            }
        }
    }

    //only allows for the current user to access the like and comment functions
    if (isset($_SESSION['user_logged_in'])) {
        if (isset($_POST['id'])){
            likes($_POST['id']);
        }
        else if (isset($_POST['post_id'])) {
            //prevent from injection
            $user_comment = htmlspecialchars($_POST['comment_box']);
            echo $user_comment;
            submit_comment($user_comment, $_POST['post_id']);
        }
    }
    else {
        echo "<script>alert('Please login or sign up')</script>";
        echo "<script>window.open('../login.php','_self')</script>";
    }

?>