<?php

   session_start();

   require_once("../config/database.php");
   $conn = connect_mysql();
   $img_id = $_POST['img_id'];

   echo $img_id;

   $delete = mysqli_query($conn, "DELETE FROM posts WHERE post_id = $img_id");
   if (!$delete){
        echo "<script>alert('Did not delete post!')</script>";
   }

?>