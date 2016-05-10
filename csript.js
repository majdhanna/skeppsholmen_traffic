function startTimer(display) {
    setInterval(function () {
        var today = new Date();
        var h = today.getHours();
        var m = today.getMinutes();
        var s = today.getSeconds();
        m = checkTime(m);
        s = checkTime(s);

        display.textContent = h + ":" + m + ":" + s;
    }, 1000);
}

window.onload = function () {
        display = document.querySelector('#time');
    startTimer(display);
};





