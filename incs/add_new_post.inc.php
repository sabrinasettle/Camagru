<?php
	session_start();
	require('../config/database.php');

	if (!isset($_SESSION['user_logged_in']))
	{
		header('Location: ../login.php');
	}

	$conn = connect_mysql();
	
	$image = mysqli_connect($conn,'SELECT image FROM posts');
	
	
	//checks if we have the varible submit then
	if (filter_has_var(INPUT_POST, 'submit')){
		//Store and validate inputs
		$caption = htmlspecialchars($_POST['caption']);

		$image = "";
		$dir = "../posts/";
		$file = $dir.basename($_FILES["image"]["name"]);
		$uploadable = 1;
		$postable = 0;
		$imgFileType = strtolower(pathinfo($file, PATHINFO_EXTENSION));

		if (strlen($caption) > 255) {
			echo "<script>alert('Image Caption must be less than 255 characters!')</script>";
			$postable = 0;
		}

		if (isset($_FILES["image"]["name"])){
			$check = getimagesize($_FILES["image"]["tmp_name"]);
			if ($check !== false){
				$uploadable = 1;
			}else{
				echo "<script>alert('Please upload only image files!')</script>";
				$uploadable = 0;
			}
		}

		if ($_FILES["image"]["size"] > 5000000) {
			echo "<script>alert('File is too large!')</script>";
			$uploadable = 0;
		}

		if($imgFileType != "jpg" && $imgFileType != "png" && $imgFileType != "jpeg" && $imgFileType != "gif" ) {
			echo "<script>alert('Only JPG, JPEG, PNG & GIF files are allowed.!')</script>";
			$uploadable = 0;
		}

		
		if ($uploadable == 1){
			if (move_uploaded_file($_FILES["image"]["tmp_name"], $file)){
				$image = "../posts/". $_FILES["image"]["name"];
				$postable = 1;
			}
		}
		
		if ($postable)
		{
				$conn = connect_mysql();

				$user = $_SESSION['user_logged_in'];
				$res = mysqli_query($conn, "SELECT id FROM users WHERE user_name = '$user'");
				$res_row = $res->fetch_row();
				$sql = "INSERT INTO posts(user_id, image, body, likes) VALUES($res_row[0], '$image', '$caption', 0)";
				$insert = mysqli_query($conn, $sql);
		}
	}
?>

<?php $page_title = 'Camagru - Create New';require('header.inc.php')?>
<div class="w3-container w3-display-topmiddle w3-half">
	<br class="w3-hide-medium w3-hide-small hideme">
	<br class="w3-hide-medium w3-hide-small hideme">
	<div>
		<form class="w3-container w3-card-4 w3-border w3-border-gray w3-margin-top" id="img_form" action="" method="post" enctype="multipart/form-data">
			<div class='w3-panel w3-border-bottom w3-border-top w3-border-black'>
                <h2 class="w3-text-Black">Add New Post</h2>
            </div>
			<p>
				<p><label class="w3-text-black "></label></p>
				<div class="w3-center" style="display:none" id="preview_div">
					<div id="stickPre">
						<img id="preview" style="max-width:100%" class="w3-image">
						<p style="position:relative"><canvas id="preview_canvas" class="3-border w3-border-red w3-image"></canvas></p>
					</div>
					<button type="button" id="add_stickers_btn" class="w3-input w3-hover-gray w3-padding-medium w3-white w3-border" onclick="addStickers()">Add Stickers</button>
					<button type="button" id="clear_stickers_btn" class="w3-input w3-hover-gray w3-padding-medium w3-white w3-border" style="display:none"onclick="clearStickers()">Clear Stickers</button>
					<div id="add_stickers" style="display:none">
                        <img src="../stickers/100_sticker.png" alt="" onclick="addSticker1_(this)">
						<img src="../stickers/emoji_sticker.png" alt="" onclick="addSticker2_(this)">
						<img src="../stickers/omg_sticker.png" alt="" onclick="addSticker3_(this)">
					</div>
				</div>
				<input class="w3-border w3-white w3-input w3-hover-gray w3-text-balck" name="image" id="image_up" type="file" required onchange="previewUpload(event)">
				<p class="w3-text-black w3-center" id="or">Or</p>
				<button type="button" id="webcam_btn" class="w3-input w3-hover-gray w3-padding-medium w3-white w3-border" onclick="open_webcam()">Use Webcam</button>
				<div class="w3-center" id="webcam" style="display: none">
					<div id="wc_img" class="w3-margin"></div>
					<video id="video" class="w3-border w3-border-black w3-image">Stream Not Available...</video>
					<canvas id="canvas" class="3-border w3-border-black w3-image"></canvas>
					<div id="stickers">
						<img src="../stickers/100_sticker.png" alt="" onclick="addSticker1(this)">
						<img src="../stickers/emoji_sticker.png" alt="" onclick="addSticker2(this)">
						<img src="../stickers/omg_sticker.png" alt="" onclick="addSticker3(this)">
					</div>
					<button type="button" id="photo_btn" class="w3-input w3-hover-gray w3-padding-medium w3-black w3-border">Take Photo</button>
					<button type="button" id="clear_btn" class="w3-input w3-hover-gray w3-padding-medium w3-black w3-border" style="display: none">Clear</button>
				</div>
				<button type="button" id="back_btn" class="w3-input w3-hover-gray w3-padding-medium w3-black w3-border" style="display: none" onclick="go_back()">Back</button>
			</p>
			<p>
				<div class='w3-panel w3-border-bottom w3-border-black'>
					<label class="w3-text-black"><b>Caption</b></label>
            	</div>
					<textarea class="w3-input w3-border w3-border-gray w3-white" name="caption" id="caption" placeholder="Caption"><?php echo isset($_POST['caption']) ? $caption : ''; ?></textarea>
			</p>
			<p>
				<input type="submit" name="submit" id="submit_input" value="Post" class="w3-button w3-hover-gray w3-padding-medium w3-white w3-border">
			</p>
			
		</form>
		
		<br class="w3-hide-medium w3-hide-small hideme">
	</div>
</div>


<script src="../js/camera.js"></script>
<?php //require('inc/footer.inc.php')?>