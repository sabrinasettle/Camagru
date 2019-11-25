function likes(post_id) {
	if (window.XMLHttpRequest) {
		xmlhttp = new XMLHttpRequest();
	} else {
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			document.getElementById(`like_section-${post_id}`).innerHTML = 
			`${this.responseText} likes`;
		}
	};
	//reponse text comes from here, thus creating the realtime likes
	xmlhttp.open("POST", "../incs/post.php", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send("like=submit&id=" + post_id);
}