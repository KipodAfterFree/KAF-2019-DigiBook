function load() {
    view("update");
    if (!cookie_has("id")) {
        newSID();
    } else {
        content();
    }
}

function next() {
    api("scripts/backend/digibook/digibook.php", "digibook", "next", {id: cookie_pull("id")}, (success, result, error) => {
        if (success) {
            get("error").innerText = result;
        } else {
            get("error").innerText = error;
        }
        content();
    });
}

function prev() {
    api("scripts/backend/digibook/digibook.php", "digibook", "previous", {id: cookie_pull("id")}, (success, result, error) => {
        if (success) {
            get("error").innerText = result;
        } else {
            get("error").innerText = error;
        }
        content();
    });
}

function content() {
    api("scripts/backend/digibook/digibook.php", "digibook", "read", {id: cookie_pull("id")}, (success, result, error) => {
        if (success)
            get("content").innerText = result;
        else
            newSID();
    });
}

function newSID() {
    api("scripts/backend/digibook/digibook.php", "digibook", "", {}, (success, result, error) => {
        if (!success) cookie_push("id", error);
        content();
    });
}

function cookie_has(name) {
    return cookie_pull(name) !== undefined;
}

function cookie_pull(name) {
    name += "=";
    const cookies = document.cookie.split(';');
    for (let i = 0; i < cookies.length; i++) {
        let cookie = cookies[i];
        while (cookie.charAt(0) === ' ') {
            cookie = cookie.substring(1);
        }
        if (cookie.indexOf(name) === 0) {
            return decodeURIComponent(cookie.substring(name.length, cookie.length));
        }
    }
    return undefined;
}

function cookie_push(name, value) {
    const date = new Date();
    date.setTime(date.getTime() + (365 * 24 * 60 * 60 * 1000));
    document.cookie = name + "=" + encodeURIComponent(value) + ";expires=" + date.toUTCString() + ";domain=" + window.location.hostname + ";path=/";
}