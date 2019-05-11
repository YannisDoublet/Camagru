// FUNCTION TO TRIGGER ALERT IN SIGN-IN/SIGN-UP PAGE.

// $_GET Superglobal in Javascript.

function $_GET(param) {
    var vars = {};
    window.location.href.replace( location.hash, '' ).replace(
        /[?&]+([^=&]+)=?([^&]*)?/gi,
        function( m, key, value ) {
            vars[key] = value !== undefined ? value : '';
        }
    );
    return vars;
}

// Fetch param from $_GET Superglobal.

let param = $_GET(window.location.href);

// Find the right error or success message to trigger.

if (typeof param.error !== "undefined" || typeof param.account !== "undefined" || typeof param.recover !== "undefined"
    || typeof param.email !== "undefined") {
    if (param.error && param.error.length > 0)
        setAlert("error", "");
    else if (param.account && param.account.length > 0)
        setAlert("success", "");
    else if (param.recover && param.recover.length > 0)
        setAlert("error", " recover");
    else if (param.email && param.email.length > 0)
        setAlert("error", " email");
}

// Set an error or a success alert.

function setAlert(key, forms) {
    console.log('alert-box ' + key + ' ' + forms);
    let alert = document.getElementById('alert-box ' + key + forms);
    alert.classList.remove('invisible');
}
