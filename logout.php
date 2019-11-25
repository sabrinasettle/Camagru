<?php

session_start();
 
unset($_SESSION['user']);
unset($_SESSION['user_logged_in']);
 

session_destroy();
 
// Redirect to the main page which then of course redirects to the feed
header("location: index.php");

?>