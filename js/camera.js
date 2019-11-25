const imup = document.getElementById("image_up");
const video = document.getElementById("video");
const canvas = document.getElementById("canvas");
const photoBtn = document.getElementById("photo_btn");
const clearBtn = document.getElementById("clear_btn");
const inputBtn = document.getElementById('submit_input');
const screenshot = document.getElementById('screenshot');
const imgForm = document.getElementById('img_form');

let webcam;
let width = 500;
let height = 0;
let streaming = false;
let pic_taken = false;

video.addEventListener('canplay', function(e){
	if (!streaming){
	height = video.videoHeight / (video.videoWidth / width);

	video.setAttribute('width', width);
	video.setAttribute('height', height);
	canvas.setAttribute('width', width);
	canvas.setAttribute('height', height);

	streaming = true;
	}
}, false);

photoBtn.addEventListener('click', function(e){
	capturePhoto();
	e.preventDefault();
}, false);

clearBtn.addEventListener('click', function(e){
	clearPicture();
	e.preventDefault();
}, false);


let image;
const context = canvas.getContext('2d');
const stickers = document.getElementById('stickers');

function capturePhoto(){
	pic_taken = true;
	if (width && height){
		canvas.width = width;
		canvas.height = height;
		stickers.style.display = 'none';

		context.drawImage(video, 0, 0, width, height);
		if (sticker_1)
			context.drawImage(sticker_1, 0, 0, 100, 100);
		if (sticker_2)
			context.drawImage(sticker_2, 200, 0, 100, 100);
		if (sticker_3)
			context.drawImage(sticker_3, 400, 0, 100, 100);
		
		const img = document.createElement('img');
		const imgUrl = canvas.toDataURL('image/png');
		//for testing
		// console.log(imgUrl);
		// console.log(img);

		img.classList.add('wc');
		img.classList.add('w3-border');
		img.classList.add('w3-border-black');
		img.classList.add('w3-image');

		img.setAttribute('src', imgUrl);

		image = imgUrl;
		
		clearBtn.style.display = 'block';
	}
}

function submitForm(e){
	e.preventDefault();

	//webcam photo
	if (pic_taken){
		let params = "&caption="+document.getElementById('caption').value+"&image="+image;

		let xhr;
		if (window.XMLHttpRequest) {
			xhr = new XMLHttpRequest();
		} else {
			xhr = new ActiveXObject("Microsoft.XMLHTTP");
		}

		xhr.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				clearPicture();
				alert('Image Uploaded!');
				$("#caption").val(''); 
			}
		};
		//opens the webcam post file
		xhr.open('POST', '../incs/post_webcam.php', true); //where the image goes to be posted
		xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		xhr.onload = function(){
			if (this.status == 200){
				//logs the image data
				// console.log(this.responseText);
			}
		};
		xhr.send(params);
	}

	//uploaded image with stickers
	if (addingStickers == true && using_webcam == false){
		prev_canv.width = output.width;
		prev_canv.height = output.height;
		context2.drawImage(output, 0, 0, output.width, output.height);
		if (sticker_1)
			context2.drawImage(sticker_1, 0, output.height-110, 100, 100);
		if (sticker_2)
			context2.drawImage(sticker_2, 100, output.height-110, 100, 100);
		if (sticker_3)
			context2.drawImage(sticker_3, 200, output.height-110, 100, 100);
		let image2 = prev_canv.toDataURL('image/png');

		let params = "&caption="+document.getElementById('caption').value+"&image="+image2;

		let xhr = new XMLHttpRequest();
		//opens the webcam post file
		xhr.open('POST', '../incs/post_webcam.php', true); //where the image goes to be posted
		xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		//reloads to the home page on success
		xhr.onload = function(){
			if (this.status == 200){
				//logs the image data
				// console.log(this.responseText);
				window.location.href = '/'
			}
		};

		xhr.send(params);
	}
	else{
		window.location.href = '/'
	}
}


//clears all the choices of sticker and photo to restart with the webcam
function clearPicture (){
	$('#photo_btn').attr("disabled", true);
	$('#submit_input').attr("disabled", true);
	pic_taken=false;
	photoBtn.style.display = 'block';
	clearBtn.style.display = 'none';
	video.style.display = 'inline-block';
	canvas.style.display = 'inline-block';
	stickers.style.display = 'block';
	sticker_1 = null;
	sticker_2 = null;
	sticker_3 = null;

	context.clearRect(0, 0, canvas.width, canvas.height);
}

let using_webcam = false;
//opens webcam from the main page of the camera page
function open_webcam(){
	using_webcam = true;
	imup.style.display = "none";
	imup.value = '';
	output.src= '';
	clearStickers();
	document.getElementById("or").style.display = "none";
	document.getElementById("webcam_btn").style.display = "none";
	document.getElementById("preview_div").style.display = "none";
	document.getElementById("back_btn").style.display = "block";
	document.getElementById("webcam").style.display = "block";
	canvas.style.display = 'inline-block';
	video.style.display = 'inline-block';
	stickers.style.display = 'block';

	navigator.mediaDevices.getUserMedia({video: true, audio: false})
	.then(function(stream){
		video.srcObject = stream;
		webcam = stream;
		video.play()
	})
	.catch(function(error){
		// console.log('Error: '+error);
	})

	imgForm.addEventListener('submit', submitForm);
	imup.required = false;
}

//the choice to go back at any time when taking a photo
function go_back(){
	using_webcam = false;
	imup.style.display = "block";
	document.getElementById("or").style.display = "block";
	document.getElementById("webcam_btn").style.display = "block";
	if (imageUploaded)
		document.getElementById("preview_div").style.display = "block";
	document.getElementById("back_btn").style.display = "none";
	document.getElementById("webcam").style.display = "none";
	stickerBtn.style.display = "none";
	document.getElementById('stickPre').style.display='none';

	if (webcam)
		webcam.getTracks().forEach(function(track) {
			track.stop();
		});

	imgForm.removeEventListener('submit', submitForm);
	imup.required = true;
}

//stickersssss
let sticker_1;
let sticker_2;
let sticker_3;
	
$('#photo_btn').attr("disabled", true);
$('#submit_input').attr("disabled", true);

function addSticker1(element){
	sticker_1 = element;
	$('#photo_btn').removeAttr("disabled");
	$('#submit_input').removeAttr("disabled");
	context.drawImage(sticker_1, 0, 0, 100, 100);
}

function addSticker2(element){
	sticker_2 = element;
	$('#photo_btn').removeAttr("disabled");
	$('#submit_input').removeAttr("disabled");
	context.drawImage(sticker_2, 200, 0, 100, 100);
}

function addSticker3(element){
	sticker_3 = element;
	$('#photo_btn').removeAttr("disabled");
	$('#submit_input').removeAttr("disabled");
	context.drawImage(sticker_3, 400, 0, 100, 100);
}

let imageUploaded = false;

let output = document.getElementById('preview');
let prev_canv = document.getElementById('preview_canvas');
let context2 = prev_canv.getContext('2d');

//preview of the upload
function previewUpload(event) {
		let reader = new FileReader();
		reader.onload = function()
		{
		 document.getElementById('preview_div').style.display = 'block';
		 output.src = reader.result;
		}
		prev_canv.width = output.width;
		reader.readAsDataURL(event.target.files[0]);
		clearStickers();
		
		imageUploaded = true;
}

let stickerBtn = document.getElementById('add_stickers_btn');
let clearStickerBtn = document.getElementById('clear_stickers_btn');
let addingStickers = false;

// ading stickers
function addStickers(){
	prev_canv.width=output.width;
	stickerBtn.style.display = 'none';
	clearStickerBtn.style.display = 'block';
	addingStickers = true;
	imgForm.addEventListener('submit', submitForm);

	document.getElementById('add_stickers').style.display = 'block';
}

//and clear them
function clearStickers(){
	clearStickerBtn.style.display = 'none';
	stickerBtn.style.display = 'block';
	context2.clearRect(0, 0, canvas.width, canvas.height);
	addingStickers = true;
	imgForm.removeEventListener('submit', submitForm);
	document.getElementById('stickPre').style.display='block';

	document.getElementById('add_stickers').style.display = 'none';
}

//adding stickers again
function addSticker1_(element){
	sticker_1 = element;
	$('#submit_input').removeAttr("disabled");
	context2.drawImage(sticker_1, 0, 0, 100, 100);
}

function addSticker2_(element){
	sticker_2 = element;
	$('#submit_input').removeAttr("disabled");
	context2.drawImage(sticker_2, 100, 0, 100, 100);
}

function addSticker3_(element){
	sticker_3 = element;
	$('#submit_input').removeAttr("disabled");
	context2.drawImage(sticker_3, 200, 0, 100, 100);
}