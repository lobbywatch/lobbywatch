function initInactivityTimeout(inactivityTimeout) {
    var idleCounter = 0;

    function resetIdleCounter() {
        idleCounter = 0;
    }

    document.onclick = function() {
        resetIdleCounter();
    };

    document.onmousemove = function() {
        resetIdleCounter();
    };

    document.onkeypress = function() {
        resetIdleCounter();
    };

    window.setInterval(checkIdleTime, 1000);

    function checkIdleTime() {
        idleCounter++;
        if (idleCounter >= inactivityTimeout) {
            window.location.href = "login.php?operation=logout&inactivity_timeout_expired=1";
        }
    }
}

