function delete_img(img_id) {

    if (window.confirm("are you sure you wanna delete that image?"))
    {
        // this is true, then
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //jquery
                //empty avoids leaking
                $(`#pp-${img_id}`).empty();
                $(`#pp-${img_id}`).remove();
            }
        };
        xmlhttp.open("POST", "../incs/delete.inc.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("img_id=" + img_id);
    }
}