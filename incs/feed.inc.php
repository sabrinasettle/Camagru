<?php $page_title = 'Camagru';require('header.inc.php') ?>
<?php

    function show_comments($conn, $post_id){
        // to show the comment we need the user_id of the comment when it was posted at along with the data itself
        $comments = $conn->query("SELECT * FROM comments INNER JOIN users ON users.id = comments.user_id WHERE post_id = $post_id ORDER BY comments.posted_at ASC ");
        $i = intval(0);
        
        while ($i < $comments->num_rows)
        {
            $comment_row = $comments->fetch_row();
            
            $comment = $comment_row[3];
            
            $user_id = $comment_row[1];
            $uname_obj = $conn->query("SELECT user_name FROM users WHERE id='$user_id'");
            $uname_obj_row = $uname_obj->fetch_row();
            $username = $uname_obj_row[0];
            echo "<div class='w3-panel w3-border-left w3-border-black'>";
                echo "<h6><b>$username</b>  $comment</h6>";
            echo "</div>";
            $i++;
        }
    }

    function feed(){
        require("../config/database.php");
        $i = intval(0);
        $conn = connect_mysql();
        // get everyones feed
        $posts = $conn->query("SELECT * FROM `posts` ORDER BY posts.published_at DESC ");
        $user = $_SESSION['user_logged_in'];
        while ($i < $posts->num_rows)
        {
            $post_row = $posts->fetch_row();
            
            $post_id = $post_row[0];
            $data = $post_row[2];
            $caption = $post_row[3];
            $num_of_likes = $post_row[5];
            
            $user_id = $post_row[1];
            $uname_obj = $conn->query("SELECT user_name FROM users WHERE id='$user_id'");
            $uname_obj_row = $uname_obj->fetch_row();
            $username = $uname_obj_row[0];

            echo "<div class='w3-card w3-margin w3-padding w3-border w3-border-grey'>";

            echo "<div class='image_box'>";

                echo "<div class='user'>";
                    echo "<div class='w3-panel w3-border-bottom w3-border-top w3-border-black'>";
                        echo "<h3><b>$username</b></h3>"; 
                    echo "</div>";
                echo "</div>";
                echo "<div class='cell'><img src=".$data." alt=".$data." /></div>";
                if ($user){
                    echo "<div class='caption-s'<h4><b>$username</b> $caption</h4>  <button class='w3-button w3-border w3-round-xxlarge w3-hover-red' onclick='likes({$post_row['0']})'>Like</button></div>";
                    echo "<div class='stuff'><h6 id='like_section-".$post_id."'> $num_of_likes likes</h6></div>";
                    echo "<div class='comments' id='comments-{$post_id}'>";
                    show_comments($conn, $post_id);
                    echo "</div>";
                    
                    echo "<div class='c_b'>";
                    echo "<textarea name='comment_box' id='comment_box-{$post_id}' class='form-control' cols='30vw' rows='1'></textarea> ";
                    echo "</div>";
                   
                    echo "<button class='w3-button w3-white w3-border w3-round-large w3-hover-gray' onclick='comment_img({$post_row['0']}, `{$user}`)'>Post a Comment</button> ";
                }else{
                    echo "<div class='caption-s'<h4><b>$username</b> $caption</h4></div>";
                }
                    echo "</div>";
            echo "</div>";
            
            $i++;
        }
    }
?>

<style>

    h3 {
        border: 2px black;
    }
    
    h6 {
        width: 100vw;
    }

    img {
        margin: auto;
        max-width: 50vw;
        max-height: 100%;
    }

    .user {
        width: 205px;
    }
    
    .cell {
        -webkit-box-flex: 1;
        -moz-box-flex: 1;
        box-flex: 1;
        -webkit-flex: 1 1 auto;
        /* display: flex; */
        align-items: center;
        box-sizing: content-box;
    }

    .stuff{
        width: 100vw;
        margin: 1px;
    }

    .comments h6{
        width: 50vw;
    }

    .caption-s{
        margin: 1px;
        width: 50vw;
        word-wrap: break-word;
        padding: 7px;
    }
    .image_box{
        width: 50%;
        margin-left: auto;
        margin-right: auto;
        padding-bottom: 20px;
        padding-right: 50vw;
    }

    .c_b{
        padding-top: 15px;
        border: 1px black;
    }
</style>

<html>
    <head>
        <script src="../js/comments.js"></script>
        <script src="../js/like_image.js"></script>
    </head>
        </body>
            <?php feed(); ?>
        <body>
</html>

