// FUNCTION TO UPDATE WITH AJAX PROFILE PAGE.

// Click outside to untoggle post page.

document.getElementById("post_picture").addEventListener('click', (evt) => {
    if (evt.target.classList.contains('post_container')) {
        togglePost()
    }
});

// Update profile content with AJAX.

function    update_profile_content() {
    const form = new XMLHttpRequest();
    form.onreadystatechange = () => {
        if (form.readyState === 4) {
            if (form.status === 200) {
                if (form.responseText) {
                    let post = JSON.parse(form.responseText);
                    console.log(post);
                    if (parseInt(post.length) === 0) {
                        document.getElementById('content_card').insertAdjacentHTML('beforeend',
                            '<p style="color: black; font-size: 40px; align-items: center">Be the first to post on Share !</p>\n');
                    }
                    for (let i = post.length - 1; i > -1; i--) {
                        document.getElementById('content_card').insertAdjacentHTML('beforeend',
                            '<div id="'+ post[i]['id'] +'" class="card">\n' +
                            '  <div class="card_picture-info">\n' +
                            '    <div class="picture_poster">\n' +
                            '       <img class="profile_pic_card" src="users/'+ post[i]['user'] +'/profile_pic.jpg">\n' +
                            '       <p>'+ post[i]['title'] + ' by ' + post[i]['user'] + '</p>\n' +
                            '    </div>\n' +
                            '    <div class="picture_date">\n' +
                            '        <i class="far fa-calendar-alt"></i>\n' +
                            '        <p class="date">' + post[i]['creation_time'] + '</p>\n' +
                            '    </div>\n' +
                            '   </div>\n' +
                            '    <div class="card_picture">\n' +
                            '        <img src="/pictures/' + post[i]['img_id'] + '.jpg">\n' +
                            '    </div>\n' +
                            '    <div class="card_picture-rating">\n' +
                            '       <div class="picture_likes">\n' +
                            '           <p id="likes">' + post[i]['likes'] + '</p>\n' +
                            '           <i id="' + post[i]['img_id'] + '" class="far fa-heart ' + post[i]['liked']+ '" onclick="post_likes(event)"></i>\n' +
                            '       </div>\n' +
                            '           <p class="comment_number">' + post[i]['comments'] + ' comments</p>\n' +
                            '    </div>\n' +
                            '    <div id="comment_section" class="card_picture-comments">\n' +
                            '    </div>\n' +
                            '    <input id="comment_bar" class="comment_bar" type="text" placeholder="Comment..." onchange=post_comments(event)>\n' +
                            ' </div>');
                    }
                    update_comments();
                } else {
                    console.log('Ajax error !');
                }
            }
        }
    };
    form.open('POST', 'back/update_content.php', true);
    form.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    form.send("update=" + "profile");
}

// Update profile's page counters.

function    update_profile_counters() {
    const req = new XMLHttpRequest();

    req.onreadystatechange = () => {
        if (req.readyState === 4) {
            if (req.status === 200) {
                if (req.responseText) {
                    let res = JSON.parse(req.responseText);
                    let counter = document.getElementsByClassName('total_content');
                    // console.log(counter);
                    for (let i = 0; i < res.length; i++) {
                        console.log(res[i]);
                        counter[i].innerHTML = res[i];
                    }
                    console.log(res);
                } else {
                    console.log('Ajax error !');
                }
            }
        }
    };
    req.open('POST', 'back/update_content.php', true);
    req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    req.send("update=" + 'profile_counters');
}

// Update comments section with AJAX.

function    update_comments() {
    const req = new XMLHttpRequest();
    let card = document.getElementsByClassName('card');

    req.onreadystatechange = () => {
        if (req.readyState === 4) {
            if (req.status === 200) {
                if (req.responseText) {
                    let comments = JSON.parse(req.responseText);
                    for (let i = 0; i < card.length; i++) {
                        let id = card[i].childNodes[5].childNodes[1].childNodes[3].id;
                        for (let j = 0; j < comments.length; j++) {
                            if (id === comments[j]['img_id']) {
                                card[i].childNodes[7].insertAdjacentHTML('beforeend',
                                    '<div id="' + j + '" class="comment">' +
                                    '<div class="comment_content">' +
                                    '<p class="comment_author">' + comments[j]['user'] + '</p>' +
                                    '<p id="comment_text" class="comment_text">' + comments[j]['comments'] + '</p>' +
                                    '</div>' +
                                    '</div>');
                            }
                        }
                    }
                }
            }
        }
    };
    req.open('POST', 'back/update_content.php', true);
    req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    req.send("update=" + 'comments');
}

// Post a like on a photo with AJAX.

function    post_likes(event) {
    const  req = new XMLHttpRequest();

    req.onreadystatechange = () => {
        if (req.readyState === 4) {
            if (req.status === 200) {
                if (req.responseText) {
                    if (req.responseText === "1") {
                        if (event.srcElement.classList.contains('isLiked')) {
                            event.srcElement.classList.remove('isLiked');
                        }
                        event.srcElement.classList.add('isLiked');
                        let like = parseInt(event.srcElement.parentNode.firstChild.nextSibling.innerHTML) + 1;
                        event.srcElement.parentNode.firstChild.nextSibling.innerHTML = like.toString();
                    } else if (req.responseText === "0") {
                        if (!(event.srcElement.classList.contains('isLiked'))) {
                            event.srcElement.classList.add('isLiked');
                        }
                        let like = parseInt(event.srcElement.parentNode.firstChild.nextSibling.innerHTML) - 1;
                        event.srcElement.parentNode.firstChild.nextSibling.innerHTML = like.toString();
                        event.srcElement.classList.remove('isLiked');
                    }
                    update_profile_counters();
                } else {
                    console.log('Ajax error !');
                }
            }
        }
    };
    req.open('POST', 'back/update_content.php', true);
    req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    req.send("update=" + 'likes' + "&id=" + event.srcElement.id);
}

// Reset all comments on photos

function    reset_comments() {
    let card = document.querySelectorAll('#comment_section');

    for (let i = 0; i < card.length; i++) {
        card[i].innerHTML = "";
    }
}

// Post a comment on a photo with AJAX.

function    post_comments(event) {
    let req = new XMLHttpRequest();
    let img_src = event.srcElement.parentElement.childNodes[3].childNodes[1].src;
    let comments = event.srcElement.parentElement.childNodes[5].childNodes[3].innerHTML;

    req.onreadystatechange = () => {
        if (req.readyState === 4) {
            if (req.status === 200) {
                event.srcElement.parentElement.childNodes[5].childNodes[3].innerHTML = parseInt(comments) + 1 + " comments";
                event.srcElement.value = "";
                reset_comments();
                update_comments();
                update_profile_counters();
            }
        }
    };
    req.open('POST', 'back/update_content.php', true);
    req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    req.send("post=" + 'comments' + "&value=" + event.srcElement.value + "&src=" + img_src);
}

// Upload a new profile picture.

function    upload_profile_pic(event) {
    console.log('salut');
    let imgObj = new Image();
    let canvas = document.createElement('canvas');
    canvas.width = 150;
    canvas.height = 150;
    imgObj.src = window.URL.createObjectURL(event.target.files[0]);
    imgObj.onload = () => {
        if(imgObj.height / imgObj.width >= canvas.height / canvas.width)
        {
            let heightObj = canvas.height * imgObj.width / canvas.width;
            canvas.getContext('2d').drawImage(imgObj,
                0, (imgObj.height - heightObj) / 2, imgObj.width, heightObj,
                0, 0,
                canvas.width, canvas.height);
        }
        else
        {
            let widthObj = canvas.width * imgObj.height / canvas.height;
            canvas.getContext('2d').drawImage(imgObj,
                (imgObj.width - widthObj) / 2, 0, widthObj, imgObj.height,
                0, 0,
                canvas.width, canvas.height);
        }
        let img = canvas.toDataURL('image/png');
        ajax_req(img, "profile_pic");
    };
}

// Upload a new banner picture.

function    upload_banner_pic(event) {
    console.log('wtf');
    let imgObj = new Image();
    let canvas = document.createElement('canvas');
    imgObj.src = window.URL.createObjectURL(event.target.files[0]);
    imgObj.onload = () => {
        canvas.width = 2305;
        canvas.height = 210;
        if(imgObj.height / imgObj.width >= canvas.height / canvas.width)
        {
            let heightObj = canvas.height * imgObj.width / canvas.width;
            canvas.getContext('2d').drawImage(imgObj,
                0, (imgObj.height - heightObj) / 2, imgObj.width, heightObj,
                0, 0,
                canvas.width, canvas.height);
        }
        else
        {
            let widthObj = canvas.width * imgObj.height / canvas.height;
            canvas.getContext('2d').drawImage(imgObj,
                (imgObj.width - widthObj) / 2, 0, widthObj, imgObj.height,
                0, 0,
                canvas.width, canvas.height);
        }
        let img = canvas.toDataURL('image/png');
        ajax_req(img, "banner");
    };
}

// Ajax request to change profile and banner picture.

function ajax_req(img, picture) {
    let form = new XMLHttpRequest();
    form.open('POST', 'back/account_changes.php', true);
    form.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    form.send("b64=" + img + "&pic=" + picture);
    form.onreadystatechange = () => {
        if (form.readyState === 4) {
            if (form.status === 200) {
                let d = new Date();
                if (picture === "profile_pic") {
                    let picture = document.getElementById('profile_picture');
                    let picture_card = document.getElementsByClassName('profile_pic_card');
                    let img = picture.src;
                    picture.setAttribute('src', img + '?t=' + d.getTime());
                    for (let i = 0; i < picture_card.length; i++)
                        picture_card[i].setAttribute('src', img + '?t=' + d.getTime());
                } else if (picture === "banner") {
                    let regex = /(?<=")(.*)(?=")/;
                    let picture = document.getElementById('banner');
                    let img = picture.style.background;
                    let url = img.match(regex);
                    img = img.replace(regex, url[0] + '?t=' + d.getTime());
                    picture.style.background = img;
                }
            } else {
                console.log('Ajax error !');
            }
        }
    };
}

// Execute functions.

update_profile_content();
update_profile_counters();