// FUNCTION TO UPDATE WITH AJAX INDEX.


// Infinite scroll.

let content = document.documentElement;
let scroll = 0;

window.addEventListener('scroll', () => {
    if (content.scrollHeight - content.offsetHeight <= content.scrollTop) {
        scroll += 4;
        reset_comments();
        update_content(scroll);
    }
});
// Update index page content with AJAX.

function    update_content(scroll) {
    console.log(scroll);
    let content = document.querySelector('#content_card');
    const form = new XMLHttpRequest();
    form.onreadystatechange = () => {
        if (form.readyState === 4) {
            if (form.status === 200) {
                if (form.responseText) {
                    let post = JSON.parse(form.responseText);
                    if (parseInt(post.length) > 0) {
                        const no_pic_message = document.getElementById('message');
                        if (no_pic_message) {
                            no_pic_message.style.display = 'none';
                        }
                    }
                    console.log(post);
                    console.log(post.length);
                    for (let i = 0; i < post.length; i++) {
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
    form.send("update=" + 'index' + "&scroll=" + scroll);
}

// Update counters with AJAX.

function    update_counter() {
    const req = new XMLHttpRequest();

    req.onreadystatechange = () => {
        if (req.readyState === 4) {
            if (req.status === 200) {
                if (req.responseText) {
                    let res = JSON.parse(req.responseText);
                    let counter = document.getElementsByClassName('total_content');
                    for (let i = 0; i < res.length; i++) {
                        counter[i].innerHTML = res[i];
                    }
                } else {
                    console.log('Ajax error !');
                }
            }
        }
    };
    req.open('POST', 'back/update_content.php', true);
    req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    req.send("update=" + 'counter');
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
                update_counter();
            }
        }
    };
    req.open('POST', 'back/update_content.php', true);
    req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    req.send("post=" + 'comments' + "&value=" + event.srcElement.value + "&src=" + img_src);
}

// Execute functions.

update_content(scroll);
update_counter();