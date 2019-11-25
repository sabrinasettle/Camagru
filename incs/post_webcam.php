<?php
	if (isset($_POST['image']) && isset($_POST['caption']))
	{
			require('../config/database.php');
			session_start();
            $conn = connect_mysql();

            $caption = htmlspecialchars($_POST['caption']);
			
			$data = $_POST['image'];
			$data = str_replace('data:image/png;base64,', '', $data);
			$data = str_replace(' ', '+', $data);
			$data = base64_decode($data);

			$filename = md5(date('Y-m-d H:i:s:u'));
			
			$image = '../posts/'.$filename.'.png';
			file_put_contents('../posts/'.$filename.'.png', $data);
			
			
			$user = $_SESSION['user_logged_in'];
			$res = mysqli_query($conn, "SELECT id FROM users WHERE user_name = '$user'");
			$res_row = $res->fetch_row();
			$sql = "INSERT INTO posts(user_id, image, body, likes) VALUES($res_row[0], '$image', '$caption', 0)";
			$insert = mysqli_query($conn, $sql);
			
	}
?>