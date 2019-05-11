// FUNCTION TO SHOW AND UPDATE SETTINGS PAGE.

// $_GET Superglobal in Javascript.

function $_GET(param) {
    let vars = {};
    window.location.href.replace( location.hash, '' ).replace(
        /[?&]+([^=&]+)=?([^&]*)?/gi,
        function( m, key, value ) {
            vars[key] = value !== undefined ? value : '';
        }
    );
    return vars;
}

// Fetch param for $_GET Superglobal function.

let param = $_GET(window.location.href);

// Find the right alert to trigger.

if (typeof param.error !== "undefined" || typeof param.success !== "undefined") {
    if (param.error && param.error.length > 0)
        setAlert("error", "");
    else if (param.success && param.success.length > 0)
        setAlert("success", "");
}

// Set an alert message or a success message.

function setAlert(key, forms) {
    console.log('alert-box ' + key + ' ' + forms);
    let alert = document.getElementById('alert-box ' + key + forms);
    alert.classList.remove('invisible');
}

// Toggle information dropdown.

function    toggleInfo() {
    let info = document.getElementById('info');
    let reset = document.getElementById('reset');
    let pref = document.getElementById('pref');
    let del = document.getElementById('delete');

    if (info.classList.contains('invisible')) {
        info.classList.remove('invisible');
        info.classList.add('visible');
        reset.classList.remove('visible');
        reset.classList.add('invisible');
        pref.classList.remove('visible');
        pref.classList.add('invisible');
        del.classList.remove('visible');
        del.classList.add('invisible');
    } else {
        info.classList.remove('visible');
        info.classList.add('invisible');
    }
}

// Toggle reset your password dropdown.

function    toggleReset() {
    let info = document.getElementById('info');
    let reset = document.getElementById('reset');
    let pref = document.getElementById('pref');
    let del = document.getElementById('delete');

    if (reset.classList.contains('invisible')) {
        reset.classList.remove('invisible');
        reset.classList.add('visible');
        info.classList.remove('visible');
        info.classList.add('invisible');
        pref.classList.remove('visible');
        pref.classList.add('invisible');
        del.classList.remove('visible');
        del.classList.add('invisible');
    } else {
        reset.classList.remove('visible');
        reset.classList.add('invisible');
    }
}

// Toggle preferences dropdown.

function    togglePreferences() {
    let info = document.getElementById('info');
    let reset = document.getElementById('reset');
    let pref = document.getElementById('pref');
    let del = document.getElementById('delete');

    if (pref.classList.contains('invisible')) {
        pref.classList.remove('invisible');
        pref.classList.add('visible');
        info.classList.remove('visible');
        info.classList.add('invisible');
        reset.classList.remove('visible');
        reset.classList.add('invisible');
        del.classList.remove('visible');
        del.classList.add('invisible');
    } else {
        pref.classList.remove('visible');
        pref.classList.add('invisible');
    }
}

// Toggle delete dropdown.

function    toggleDelete() {
    let del = document.getElementById('delete');
    let pref = document.getElementById('pref');
    let info = document.getElementById('info');
    let reset = document.getElementById('reset');

    if (del.classList.contains('invisible')) {
        del.classList.remove('invisible');
        del.classList.add('visible');
        info.classList.remove('visible');
        info.classList.add('invisible');
        reset.classList.remove('visible');
        reset.classList.add('invisible');
        pref.classList.remove('visible');
        pref.classList.add('invisible');
    } else {
        del.classList.remove('visible');
        del.classList.add('invisible');
    }
}