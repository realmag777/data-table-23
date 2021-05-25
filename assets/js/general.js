'use strict';
var touch_start_x = 0;//for any touch operations

Element.prototype.appendBefore = function (element) {
    element.parentNode.insertBefore(this, element);
}, false;


Element.prototype.appendAfter = function (element) {
    element.parentNode.insertBefore(this, element.nextSibling);
}, false;


function message(message_txt, type = 'notice', duration = 0) {
    if (duration === 0) {
        duration = 1777;
    }

    //***

    let container = null;
    if (!document.querySelectorAll('#growls').length) {
        container = document.createElement('div');
        container.setAttribute('id', 'growls');
        container.className = 'default';
        document.querySelector('body').appendChild(container);
    } else {
        container = document.getElementById('growls');
    }

    //***

    let id = 'm-' + Math.random().toString(36).substring(7);
    let wrapper = document.createElement('div');
    wrapper.className = 'growl growl-large growl-' + type;
    wrapper.setAttribute('id', id);
    let title = document.createElement('div');
    title.className = 'growl-title';
    let title_text = '';
    switch (type) {
        case 'warning':
            title_text = 'Warning';
            break;
        case 'error':
            title_text = 'Error';
            break;
        default:
            title_text = 'Notice';
            break;
    }

    title.innerHTML = title_text;
    let message = document.createElement('div');
    message.className = 'growl-message';
    message.innerHTML = message_txt;
    //***

    //wrapper.appendChild(close);
    wrapper.appendChild(title);
    wrapper.appendChild(message);
    container.innerHTML = '';
    container.appendChild(wrapper);
    wrapper.addEventListener('click', function (e) {
        e.stopPropagation();
        this.remove();
        return false;
    });
    if (duration !== -1) {
        setTimeout(function () {
            wrapper.style.opacity = 0;
            setTimeout(function () {
                wrapper.remove();
            }, 777);
        }, duration);
}

}


