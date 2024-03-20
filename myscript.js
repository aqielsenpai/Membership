function savepref() {
    var email = document.getElementById('idemail').value;
    var password = document.getElementById('idpassword').value;
    var cb = document.getElementById('idcheckbox').checked;
    if (email == "" && password == "") {
        alert("please enter your email/password");
        document.getElementById('idcheckbox').checked = false;
    } else {
        if (cb == true) {
            setCookie('cemail', email, 30);
            setCookie('cpassword', password, 30);
            setCookie('ccb', cb, 30);
            alert("prefference stored");
        } else {
            setCookie('cemail', '', 30);
            setCookie('cpassword', '', 30);
            setCookie('ccb', cb, 30);
            alert("prefference removed");
            document.getElementById('idemail').value = "";
            document.getElementById('idpassword').value = "";

        }
    }
}

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function loadPref() {
    let email = getCookie("cemail");
    let password = getCookie("cpassword");
    let cb = getCookie("ccb");

    if (cb) {
        document.getElementById('idemail').value = email;
        document.getElementById('idpassword').value = password;
        document.getElementById('idcheckbox').checked = true;
    } else {
        document.getElementById('idemail').value = "";
        document.getElementById('idpassword').value = "";
        document.getElementById('idcheckbox').checked = false;
    }

}

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}