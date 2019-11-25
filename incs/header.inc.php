
<?php
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title><?php echo $page_title?></title>
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"> 
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	
</head>
<body>
<div id="header" class=" ">
	<div class="w3-bar w3-gray w3-card">
		<a href="/index.php" class="w3-bar-item w3-button w3-hover-light-gray w3-padding-medium ">Camagru</a>
		<?php if(!isset($_SESSION['user_logged_in'])):?>
			<a href="../sign_up.php" class="w3-bar-item w3-button w3-hover-light-gray w3-padding-medium w3-right">Sign Up</a>
			<a href="../login.php" class="w3-bar-item w3-button w3-hover-light-gray w3-padding-medium w3-right">Login</a>
		<?php else:?>
		<form method="post" action="./../logout.php">
			<input type="submit" class="w3-bar-item w3-button w3-hover-light-gray w3-padding-medium w3-right" value="Logout">
		</form>
		<a href="add_new_post.inc.php" class="w3-bar-item w3-button w3-hover-light-gray w3-padding-medium">&#10010;</a>
		<a href="profile.inc.php" class="w3-bar-item w3-button w3-hover-light-gray w3-padding-medium w3-right"> <?php echo $_SESSION['user_logged_in']?></a>
		<?php endif?>
	</div>
</div>
