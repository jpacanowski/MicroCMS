startTime();

function checkTime(i)
{
    if (i < 10) {
        i = "0" + i;
    }
    return i;
}

function startTime()
{
    var today = new Date();
    var h = today.getHours();
    var m = today.getMinutes();
    var s = today.getSeconds();
    
    // add a zero in front of numbers<10
    h = checkTime(h);
    m = checkTime(m);
    s = checkTime(s);
    
    document.getElementById('clock').innerHTML = h + ":" + m;// + ":" + s;
    
    t = setTimeout(function () {
        startTime()
    }, 500);
}