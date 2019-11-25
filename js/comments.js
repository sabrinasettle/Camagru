function comment_img(post_id, user) {

	if (document.getElementById(`comment_box-${post_id}`).value === '' || 
		document.getElementById(`comment_box-${post_id}`).value.replace(/\s/g, '') === '') {
		return "no";
	}
	if (window.XMLHttpRequest) {
		xmlhttp = new XMLHttpRequest();
	} else {
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			//jquery show the comment after post
			$(`#comments-${post_id}`).append(
				`<h6><b>${user}</b>  ${this.responseText}</h6>`
			);
			document.getElementById(`comment_box-${post_id}`).value = "";
		}
	};



	xmlhttp.open("POST", "../incs/post.php", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	let comm = document.getElementById(`comment_box-${post_id}`).value;
	xmlhttp.send("comment=submit&comment_box=" + comm + "&post_id=" + post_id);
}