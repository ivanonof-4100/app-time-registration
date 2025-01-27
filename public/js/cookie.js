"use strict";
// Cookie functions
function doesCookieExists(p_name) {
    return document.cookie.split(';').some(item => item.trim().startsWith(p_name));
}

function setCookie(p_cname, p_cvalue, p_exdays) {
    const expireDate = new Date();
    expireDate.setTime(expireDate.getTime() + (p_exdays * (24 * 60 * 60 * 1000)));
    document.cookie = sprintf("%s=%s;expires=%s;path=%s;SameSite=%s;", p_cname, p_cvalue, expireDate.toUTCString(), '/', 'Strict');
}

function getCookie(p_cname) {
    var name = p_cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) === 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function eraseCookie(name) {
    setCookie(name, "", -1);
}

function isCookiesEnabled() {
    setCookie("testing", "Hello", 1);
    if (readCookie("testing") != null) {
        eraseCookie("testing");
        return true;
    } else {
        return false;
    }
}