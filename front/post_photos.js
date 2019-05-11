// Check if the user has getUserMedia.

function hasGetUserMedia() {
    return !!(navigator.mediaDevices &&
        navigator.mediaDevices.getUserMedia);
}

// Toggle the webcam and stream it on video tag.

function toggleWebcam() {
    let upload = document.getElementById('upload');
    let webcam = document.getElementById('webcam');
    let utils = document.getElementById('utils');
    let sticker = document.getElementById('stickers_bar');
    let pic = document.getElementById('old_pic');

    if (webcam.classList.contains('no_cursor')) {
        return;
    }
    webcam.classList.add('no_cursor');
    upload.classList.add('none');
    utils.classList.remove('none');
    sticker.classList.remove('none');
    pic.classList.remove('none');
    show_old_photo();
    if (!hasGetUserMedia()) {
        console.log('GetUserMedia not supported...');
    } else {
        const hdConstraints = {
            video: {width: 770, height: 515}
        };
        const video = document.getElementById('video');
        navigator.mediaDevices.getUserMedia(hdConstraints).then((stream) => {
            video.srcObject = stream;
            if (video.classList.contains('none')) {
                video.classList.remove('none');
            }
        });
    }
}

// Take a picture.

function photo() {
    const video = document.getElementById('video');
    const canvas = document.createElement('canvas');
    const picture = document.getElementById('picture');
    let webcam = document.getElementById('webcam');

    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);
    video.classList.add('none');
    picture.src = canvas.toDataURL('image/png');
    picture.classList.remove('none');
    webcam.classList.add('none');
    toggleUtils();
}

// Upload an image from your computer.

function upload_img(event) {
    let webcam = document.getElementById('webcam');
    let upload = document.getElementById('upload');
    let imgObj = new Image();
    let canvas = document.createElement('canvas');
    let utils = document.getElementById('utils');
    let sticker = document.getElementById('stickers_bar');
    let pic = document.getElementById('old_pic');
    let label = document.getElementById('upload_label');
    let picture = document.getElementById('picture');

    webcam.classList.add('none');
    canvas.width = 770;
    canvas.height = 515;
    imgObj.src = window.URL.createObjectURL(event.target.files[0]);
    imgObj.onload = () => {
        if (imgObj.height / imgObj.width >= canvas.height / canvas.width) {
            let heightObj = canvas.height * imgObj.width / canvas.width;
            canvas.getContext('2d').drawImage(imgObj,
                0, (imgObj.height - heightObj) / 2, imgObj.width, heightObj,
                0, 0,
                canvas.width, canvas.height);
        }
        else {
            let widthObj = canvas.width * imgObj.height / canvas.height;
            canvas.getContext('2d').drawImage(imgObj,
                (imgObj.width - widthObj) / 2, 0, widthObj, imgObj.height,
                0, 0,
                canvas.width, canvas.height);
        }
        picture.src = canvas.toDataURL('image/png');
        picture.classList.remove('none');
        label.classList.add('none');
        upload.classList.add('none');
        utils.classList.remove('none');
        sticker.classList.remove('none');
        pic.classList.remove('none');
        toggleUtils();
        show_old_photo();
    };
}

// Submit a picture with AJAX and update content and reset settings.

function submit() {
    const picture = document.getElementById('picture');
    const stickers = document.getElementById('selected');
    const title = document.getElementById('title');

    let form = new XMLHttpRequest();
    form.onreadystatechange = () => {
        if (form.readyState === 4) {
            if (form.status === 200) {
                if (form.responseText) {
                    let content = document.getElementById('content_card');
                    let old_photo = document.getElementById('old');
                    while (content.firstChild) {
                        content.removeChild(content.firstChild);
                    }
                    while (old_photo.firstChild) {
                        old_photo.removeChild(old_photo.firstChild);
                    }
                    if (location.pathname === "/index.php") {
                        update_content(0);
                        update_counter();
                    } else if (location.pathname === "/profile.php") {
                        update_profile_content();
                        update_profile_counters();
                    }
                    resetSettings();
                } else if (parseInt(form.responseText.length) === 0) {
                    alert('You can\'t post a picture !');
                }
            }
        }
    };
    form.open('POST', 'back/picture.php', true);
    form.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    form.send("b64=" + picture.src + "&stickers=" + stickers.src + '&top=' + stickers.style.top +
        '&left=' + stickers.style.left + "&title=" + title.value);
}
