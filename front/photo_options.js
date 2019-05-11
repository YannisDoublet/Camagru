// Declare an interval to move stickers.


let intervalId;

// TOGGLE HTML FUNCTION

// Toggle active class on stickers and untoggle it.

function toggleActive(event) {
    let stickers = document.getElementsByClassName('stickers_img');
    let selected = document.getElementById('selected');
    let take = document.getElementById('take_photo');
    let move_key = document.getElementsByClassName('move_sticker');
    for (let i = 0; i < stickers.length; i++) {
        if (i === parseInt(event.srcElement.id)) {
            if (event.srcElement.classList.contains('active')) {
                continue;
            }
            else {
                let img = new Image();
                img.onload = function () {
                    selected.style.top = 512 / 2 - this.height / 2 + "px";
                    selected.style.left = 770 / 2 - this.width / 2 + "px";
                };
                img.src = event.srcElement.src;
                selected.src = event.srcElement.src;
                event.srcElement.classList.add('active');
                take.disabled = false;
                for (let j = 0; j < move_key.length; j++) {
                    if (move_key[j].classList.contains('none')) {
                        move_key[j].classList.remove('none');
                    }
                }
            }
        } else {
            stickers[i].classList.remove('active');
        }
    }
}

// Toggle stickers by clicking on the bar.

function toggleStickers() {
    let stickers = document.getElementById('stickers');
    if (!stickers.classList.contains('toggle')) {
        stickers.classList.add('toggle');
    } else {
        stickers.classList.remove('toggle');
    }
}

// Toggle old photos by clicking the bar.

function toggleOld() {
    let old = document.getElementById('old');
    if (!old.classList.contains('toggle')) {
        old.classList.add('toggle');
    } else {
        old.classList.remove('toggle');
    }
}

// Toggle take photo button, title and post button and untoggle them.

function toggleUtils() {
    let take = document.getElementById('take_photo');
    let title = document.getElementById('title');
    let post = document.getElementById('post');

    if (!take.classList.contains('none')) {
        take.classList.add('none');
        title.classList.remove('none');
        post.classList.remove('none');
    } else {
        take.classList.remove('none');
        title.classList.add('none');
        post.classList.add('none');
    }
}

// Toggle post a picture element and untoggle it.

function togglePost() {
    let body = document.body;
    let post = document.getElementById('post_picture');
    if (post.classList.contains('hidden')) {
        post.classList.remove('hidden');
        post.classList.add('visible');
        body.classList.add('no_scroll');
    } else {
        post.classList.remove('visible');
        post.classList.add('hidden');
        body.classList.remove('no_scroll');
    }
}

// MOVING STICKERS FUNCTION

// Repeat movestickers function with interval when left mouse click is hold.

function mousedownfunc(evt) {
    intervalId = setInterval(moveStickers, 15, evt);
}

// Clear interval on left mouse click release.

function mouseupfunc() {
    clearInterval(intervalId);
}

// Move stickers

function moveStickers(evt) {
    let selected = document.getElementById('selected');

    let img = new Image();
    img.onload = function () {
        if (evt.srcElement.id === 'move_left') {
            if (parseInt(selected.style.left) > 0)
                selected.style.left = parseInt(selected.style.left) - 5 + "px";
        } else if (evt.srcElement.id === 'move_up') {
            if (parseInt(selected.style.top) > 0)
                selected.style.top = parseInt(selected.style.top) - 5 + "px";
        } else if (evt.srcElement.id === 'move_right') {
            if (parseInt(selected.style.left) + this.width < 765)
                selected.style.left = parseInt(selected.style.left) + 5 + "px";
        } else if (evt.srcElement.id === 'move_down') {
            if (parseInt(selected.style.top) + this.height < 512)
                selected.style.top = parseInt(selected.style.top) + 5 + "px";
        }
    };
    img.src = selected.src;
}

// UPDATE CONTENT FUNCTION ON POST A PICTURE ELEMENT

// Update and show old photos with AJAX

function show_old_photo() {
    const form = new XMLHttpRequest();
    form.onreadystatechange = () => {
        if (form.readyState === 4) {
            if (form.status === 200) {
                if (form.responseText) {
                    let post = JSON.parse(form.responseText);
                    for (let i = post.length - 1; i > -1; i--) {
                        document.getElementById('old').insertAdjacentHTML('beforeend',
                            '<img id="'+ post[i]['img_id'] +'" class="stickers_img" src="../pictures/'+ post[i]['img_id'] +'.jpg" onclick="Erase_picture(event)">');
                    }
                } else {
                    console.log('Ajax error !');
                }
            }
        }
    };
    form.open('POST', 'back/update_content.php', true);
    form.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    form.send("update=" + 'old');
}

// Reset all settings on post a picture element.

function    resetSettings() {
    let selected = document.getElementsByClassName('stickers_img active');
    let move = document.getElementsByClassName('move_sticker');
    let take = document.getElementById('take_photo');
    let title = document.getElementById('title');
    const video = document.getElementById('video');
    const hdConstraints = {
        video: {width: 770, height: 515}
    };

    document.getElementById('webcam').classList.remove('none', 'no_cursor');
    document.getElementById('upload').classList.remove('none');
    document.getElementById('selected').removeAttribute('src');
    document.getElementById('upload_label').classList.remove('none');
    document.getElementById('picture').classList.add('none');
    document.getElementById('utils').classList.add('none');
    document.getElementById('stickers_bar').classList.add('none');
    document.getElementById('old_pic').classList.add('none');
    document.getElementById('post').classList.add('none');
    title.classList.add('none');
    title.value = "";
    take.classList.remove('none');
    take.disabled = true;
    navigator.mediaDevices.getUserMedia(hdConstraints).then((stream) => {
        video.srcObject = stream;
        stream.getVideoTracks()[0].stop();
    });
    selected[0].classList.remove('active');
    for (let i = 0; i < move.length; i++) {
        move[i].classList.add('none');
    }
}

// PICTURE MANAGEMENT

// Get the confirmation to erase the picture.

function getConfirmation() {
    let retVal = confirm("Do you want to erase this picture ?");
    return retVal === true;
}

// Erase a picture after a confirmation by clicking on it.

function    Erase_picture(event) {
    let img_id = event.srcElement.src.replace('http://'+ location.host +'/pictures/', '').replace('.jpg', '');
    if (img_id === event.srcElement.id) {
        if (getConfirmation()) {
            const form = new XMLHttpRequest();
            form.onreadystatechange = () => {
                if (form.readyState === 4) {
                    if (form.status === 200) {
                        if (form.responseText) {
                            let post = JSON.parse(form.responseText);
                            event.srcElement.remove();
                            if (document.getElementById(post['id'])) {
                                document.getElementById(post['id']).remove();
                            }
                            if (location.pathname === "/index.php") {
                                setTimeout(update_counter, 1);
                            } else if (location.pathname === "/profile.php") {
                                setTimeout(update_profile_counters, 1);
                            }
                        }
                    }
                }
            };
            form.open('POST', 'back/update_content.php', true);
            form.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            form.send("update=" + 'erase' + "&id=" + event.srcElement.src);
        }
    }
}

