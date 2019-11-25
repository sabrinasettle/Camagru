<?php
    require('database.php');
    
    //Connect to DB
    $conn = connectDB($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);
    if (!$conn){
        die("Could not connect: ".mysqli_connect_error());
    }
    // echo ('Successfully Connected!<br>');

    mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS camagru");
    mysqli_close($conn);

    // $conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);
    $conn = connect_mysql();
    $i = mysqli_query($conn,"CREATE TABLE IF NOT EXISTS users(id INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY, user_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, passwd VARCHAR(255) NOT NULL, registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, is_verified TINYINT(1) DEFAULT 0, UNIQUE(id, user_name), verif_key VARCHAR(255), psswd_key VARCHAR(255)), get_emails BOOLEAN");
    if (!$i)
    {
        echo("Did not create the users table");
    }
    $j = mysqli_query($conn,"CREATE TABLE IF NOT EXISTS posts(post_id INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY, user_id INT(9) UNSIGNED NOT NULL, image LONGTEXT, body TEXT, published_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, likes BIGINT, FOREIGN KEY(user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE)");
    if (!$j)
    {
        echo("Did not create the posts table");
    }
    $k = mysqli_query($conn,"CREATE TABLE IF NOT EXISTS comments(comment_id INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY,user_id INT(9) UNSIGNED NOT NULL,post_id INT(9) UNSIGNED NOT NULL, comment TEXT, posted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY (user_id) REFERENCES users(id), FOREIGN KEY (post_id) REFERENCES posts(post_id) ON UPDATE CASCADE ON DELETE CASCADE)");
    if (!$k)
    {
        echo("Did not create the comments table");
    }
    $j = mysqli_query($conn,"CREATE TABLE IF NOT EXISTS likes(like_id INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY, user_id INT(9) UNSIGNED NOT NULL, post_id INT(9) UNSIGNED NOT NULL, FOREIGN KEY (user_id) REFERENCES users(id), FOREIGN KEY (post_id) REFERENCES posts(post_id) ON UPDATE CASCADE ON DELETE CASCADE)"); 
    if (!$k)
    {
        echo("Did not create the likes table");
    }
    
    
    // a set of logic to not create a admin user everytime the index.php runs :p
    $res = mysqli_query($conn, "SELECT * FROM `users` WHERE user_name LIKE 'admin'");
    if (!$res->num_rows)
    {
        $pas = hash("whirlpool", "admin");
        $sql_statement = "INSERT INTO users (user_name, email, passwd, is_verified, get_emails) VALUES ('admin', 'ssettle93@gmail.com', '$pas', 1, 1)";
        mysqli_query($conn, $sql_statement);
         
        // echo ('Admin added to User table!<br>');
    }

    mysqli_close($conn);

?>

