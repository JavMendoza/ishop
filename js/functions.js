/**
 * Realiza una petición de Ajax.
 * @param {Object} options - Objeto de configuración:
 *              + method: {string} GET, POST, PUT, DELETE.
 *              + url: {string} El recurso a peticionar.
 *              + [data]: {string} los datos a enviar.
 *              + [isJSON]: {boolean} Si la respuesta es un JSON.
 *              + success: {function} El callback por éxito.
 *              + error: {function} El callback por error.
 */
function ajax(options) {
    var xhr = new XMLHttpRequest();
    var sendBody = null;
    var o = options;

    if (o.method.toUpperCase() == "POST" || o.method.toUpperCase() == "PUT") {
        sendBody = o.data;
    } else if (o.method.toUpperCase() == "GET" || o.method.toUpperCase() == "DELETE") {
        if (o.data != null) {
            o.url += '?' + o.data;
        }
    }

    xhr.open(o.method, o.url);

    xhr.addEventListener('readystatechange', function() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                var rta;
                if (o.isJSON === true || xhr.getResponseHeader('Content-Type') == 'application/json') {
                    rta = JSON.parse(xhr.responseText);
                } else {
                    rta = xhr.responseText;
                }
                o.success(rta);
            } else {
                o.error(xhr,xhr.status);
            }
        }
    }, false);

    if ((o.method.toUpperCase() == "POST" || o.method.toUpperCase() == "PUT") && typeof sendBody !== "object") {
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    }
    xhr.send(sendBody);
}

function capitalize(s)
{
    return s && s[0].toUpperCase() + s.slice(1);
}

function $(elem_in, element) {
    return elem_in.querySelector(element);
}

function $$(elem_in, element) {
    return elem_in.querySelectorAll(element);
}

function $$$(id) {
    return document.getElementById(id);
}

function create(element) {
    return document.createElement(element);
}

function append(elem_in, elem_who) {
    return elem_in.appendChild(elem_who);
}

function remove(elem_in, elem_who) {
    return elem_in.removeChild(elem_who);
}
HTMLElement.prototype.removeClass = function(remove) {
    var newClassName = "";
    var i;
    var classes = this.className.split(" ");
    for (i = 0; i < classes.length; i++) {
        if (classes[i] !== remove) {
            newClassName += classes[i] + " ";
        }
    }
    if (newClassName.trim) {
        newClassName = newClassName.trim();
    }
    this.className = newClassName;
};