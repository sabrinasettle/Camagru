<?php $page_title = 'Profile';require('header.inc.php') ?>


<?php
    if (!isset($_SESSION['user_logged_in']))
    {
        header('Location: ../login.php');
    }
    
    function images(){
        require("../config/database.php");
        $i = intval(0);
        $conn = connect_mysql();
        // find the current user at the present moment
        $user = $_SESSION['user_logged_in'];
        $user_info = $conn->query("SELECT id FROM users WHERE user_name = '$user'");
        $user_row = $user_info->fetch_row();
        $user_id = $user_row[0];

        //show all of the posts BUT ONLY those from the current user
        $posts = $conn->query("SELECT * FROM `posts` WHERE user_id = $user_id ORDER BY posts.published_at DESC");
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
            

            echo "<div class='w3-card w3-margin w3-padding w3-border w3-border-grey' id='pp-$post_id'>";
                echo "<div class='image_box'>";

                    echo "<div class='user'>";
                    echo "<div class='w3-panel w3-border-bottom w3-border-top w3-border-black'>";
                    echo "<h3><b>$username</b></h3>"; 
                    echo "</div>";
                    echo "</div>";
                    echo "<div class='cell'><img src=".$data." alt=".$data." /></div>";
                    
                    echo "<div class='caption-s'<h4><b>$username</b> $caption</h4></div>";
                    echo "<div class='stuff'><h6 id='like_section-".$post_id."'> $num_of_likes likes</h6></div>";
                    
                    
                    echo "<br><br><button class='w3-button w3-white w3-border w3-round-large w3-hover-red' onclick='delete_img({$post_id})'>Delete Image?</button>";
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
        display: flex;
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
        <script src="../js/delete.js"></script>
    </head>
    <body>
        <div>
            <h1> ITS ME THE USER <?php echo $_SESSION['user_logged_in']?> </h1>
            <?php if(isset($_SESSION['user_logged_in'])):?>
            <p> Change your preferences here: </p>
                <a href="user_settings.inc.php?" class="w3-bar-item w3-button w3-hover-light-gray w3-padding-medium">Settings</a>
                <?php images(); ?>
            <?php endif?>
        </div>
    </body>
</html>
<?php require('footer.inc.php')?>