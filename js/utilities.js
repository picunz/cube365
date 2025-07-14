 
const isPhoneNumber = string => [...string].every(c => '0123456789+-'.includes(c));


const isToday = (someDate) => {
    const today = new Date()
    return someDate.getDate() == today.getDate() &&
           someDate.getMonth() == today.getMonth() &&
           someDate.getFullYear() == today.getFullYear()
}

let months = 
[   
    "Gennaio",
    "Febbraio",
    "Marzo",
    "Aprile",
    "Maggio",
    "Giugno",
    "Luglio",
    "Agosto",
    "Settembre",
    "Ottobre",
    "Novembre",
    "Dicembre"
];

 
  
function removeSpecialChars(str)
{
    return str.replace(/[^\w\s]/gi, '');
}

function InvalidSection()
{
    localStorage.removeItem("SessionValid");
}

function getMobileOperatingSystem() 
{
    var userAgent = navigator.userAgent || navigator.vendor || window.opera;

    console.log('getMobileOperatingSystem::userAgent ' + userAgent);

    if (/windows phone/i.test(userAgent)) {
        return "Windows Phone";
    }
    if (/android/i.test(userAgent)) {
        return "Android";
    }
    if (/iPad/i.test(userAgent) && !window.MSStream) {
        return "iOS-iPad";
    }
    if (/iPhone/i.test(userAgent) && !window.MSStream) {
        return "iOS-iPhone";
    }
    if (/iPod/i.test(userAgent) && !window.MSStream) {
        return "iOS-iPod";
    }
    return "unknown";
}
 
function formValidationBootstrap()
{
    var forms = document.querySelectorAll('.needs-validation')

    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
}

function convertStringDateDayToDate(strDateDay)
{
    const year = parseInt(strDateDay.substring(0, 4), 10);
    const month = parseInt(strDateDay.substring(4, 6), 10) - 1; // Mesi da 0 (Gennaio) a 11 (Dicembre)
    const day = parseInt(strDateDay.substring(6, 8), 10);

    const date = new Date(year, month, day);
    return date;
}

function convertStringDateDayToFormattedDate(strDateDay)
{
    const formattedDate = strDateDay.replace(/(\d{4})(\d{2})(\d{2})/, "$1-$2-$3");

    const date = new Date(formattedDate);

    return date.toDateString();
}

function convertDateToYYYYMMDD(date)
{
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0'); // getMonth() restituisce 0-11
    const day = String(date.getDate()).padStart(2, '0');

    const yyyymmdd = `${year}${month}${day}`;
    return yyyymmdd;
}



function ConvertToIsoDate(date) 
{
    
    var t = date.toLocaleTimeString('it-IT', {
        timeZone: 'Europe/Rome',
    });
    var lPos = t.lastIndexOf(":");
    t =  t.slice(0, lPos);

    if (t=="00:00") {
        date.setHours(3);
    }
    var dt = date.toISOString();
    var d = dt.split('T')[0];
    
    return d + "T" + t;
}

function ConvertToIsoDateD(date) 
{
    var isoDateTime = ConvertToIsoDate(date);
    return isoDateTime.substring(0, isoDateTime.lastIndexOf('T'));
}

function ConvertToTimeHM(time)
{
    if (time == null)
    {
        return "-";
    }
    if (time.split(":").length == 3)
    {
        return time.substr(0, time.lastIndexOf(":"));
    }
    return time;
}

function CompressDateTimeForFile(dateTime)
{
    var year = dateTime.getFullYear();
    var month = dateTime.getMonth() + 1;
    month = ("0" + month).slice(-2);
    var date = dateTime.getUTCDate();
    date = ("0" + date).slice(-2);
    var hour = dateTime.getHours();
    hour = ("0" + hour).slice(-2);
    var minutes = dateTime.getMinutes();
    minutes = ("0" + minutes).slice(-2);
    var seconds = dateTime.getSeconds();
    seconds = ("0" + seconds).slice(-2);

    return year + month + date + "_" + hour + minutes + seconds;
}
    
function createDbLog(data)
{ 
    var jData = JSON.stringify(data);
    var info = new FormData();
    info.append("Action", "CreateLog");
    info.append("data", jData);

    var action = (data.Action != undefined) ? data.Action : "-";
    info.append("_action", action);

    var opCode = localStorage["OperationCodePage"];
    info.append("_opCode", opCode);

    var reportId = (data.ReportId != undefined) ? data.ReportId : 0;
    info.append("_reportId", reportId);
    
    var techId = localStorage["userId"];
    techId = (techId != undefined) ? techId : 0;
    info.append("_techId", techId);
    
    var fullname = localStorage["techName"] + " " + localStorage["techSurname"];
    fullname = (fullname.length > 4) ? fullname : "-";
    info.append("_fullName", fullname);
    
    var activityId = localStorage["Id"];
    activityId = (activityId != undefined) ? activityId : 0;
    info.append("_activityId", activityId);
    
    var result = (data.Result != undefined) ? data.Result : 0;
    if (typeof result == "object") {
        result = "-";
    }
    if (data.ExceptionMessage != undefined && data.ExceptionMessage.length > 0)
    {
        result = "-1";
    } 

    info.append("_result", result);
    
    fetch("logs.php", { method: "post", body: info })
        .then(res => res.json())
        .then(data => onCreateDbLog(data));  
}

function onCreateDbLog(data)
{
    var dbg = 0;test
}


function logExternalFile(message)
{
    var userFullName = localStorage["name"] + "_" + localStorage["surname"];

     
    var messageLog = message;

    if (typeof message == 'object')
    {
        messageLog = JSON.stringify(message);
    }

    var data = new FormData();
    data.append("Action", "LogForUser");
    data.append("userFullName", userFullName);
    data.append("message", messageLog);

    fetch("./dal/generic.php", { method: "post", body: data })
        .then(res => res.json())
        .then(data => onLogExternalFile(data));
}

function onLogExternalFile(data)
{
    if (data.Result == false)
    {
        console.log(data);
    }
}

function setGoogleMapsLinkToCtrl(ctrl, address)
{
    ctrl.attr("href", "http://maps.google.com/?q=" + address);
    ctrl.attr("target", "_blank");
}



function createGUID() 
{
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'
        .replace(/[xy]/g, function (c) {
            const r = Math.random() * 16 | 0,
                v = c == 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });   
}

function ConvertTime2HM(time)
{
    var timeHM = time.split(':')[0] + ':' + time.split(':')[1];
    return timeHM;
}

function showHelp()
{  
    $(".card-help").show();

    $(".card-help").animate({top:'50px'}, 'slow', function() {
        //$(this).animate({width:'1000px', height:'300px'}, 'slow');
        $(this).animate({width:'1000px', height:'auto'}, 'slow');
    });
}
  
function closeHelp(obj)
{  
    var card = $(obj).closest('.card');

    card.animate({top:'0px'}, 'fast', function() {

        card.animate({width:'100px', height:'100px'}, 'fast', function() {
        card.hide();
        location.reload();
        });
    }); 
}
 

function getLocalUserName()
{
    return localStorage["techName"] + " " + localStorage["techSurname"];
}

function ChangeFileExtension(filename, ext)
{
    return filename.substr(0, filename.lastIndexOf(".")) + ext;
}

function GetFileExtension(filename)
{
    return filename.split('.').pop();
}
  
  

function DownloadByUrl(url) 
{
    const a = document.createElement('a')
    a.href = url
    a.download = url.split('/').pop()
    document.body.appendChild(a)
    a.click()
    document.body.removeChild(a)
}
 
// time utilities

let _startTime;

function startTime()
{
    _startTime = new Date();
}

function getElapsedSeconds()
{
    var endTime = new Date();
    var timeDiff = endTime - _startTime; //in ms
    // strip the ms
    timeDiff /= 1000;

    // get seconds 
    var seconds = Math.round(timeDiff);
    return seconds
}


/******************** sales ***************/

function loadScript(url)
{
    return new Promise((resolve,reject) => {

    var script = document.createElement("script");
    script.type = "text/javascript";
    script.src = url;
    script.async = false;
    document.head.appendChild(script);

    script.onload = function () {
        resolve("onLoadScript " + url); 
    };

    script.onerror = function () {
        console.error("onLoadScript " + url);
        reject("error")
    };
    });
}

//***  Logs ***/

function customLog()
{
    var _privateLog = console.log;
    var _privateWarn = console.warn;
    var _privateError = console.error;
    
    console.log = function (message) {
    
        logExternalFile(message);
            
        _privateLog.apply(console, arguments);
    }
    console.warn = function (message) {
    
        logExternalFile(message);
        
        _privateWarn.apply(console, arguments);
    }
    console.error = function (message) {
    
        logExternalFile(message);
        
        _privateError.apply(console, arguments);
    }
}


function secondsToTime(seconds) 
{
    let h = Math.floor(seconds / 3600);
    let m = Math.floor((seconds % 3600) / 60);
    
    let hh = String(h).padStart(2, '0');
    let mm = String(m).padStart(2, '0');

    return `${hh}h:${mm}m`;
}


function showDailyStats(data)
{
   
    const createInputTask = (id, jData, side, totalSec) => {

        let sideObject = jData.find(item => item.Side === side);

        var color = '#009bca';
        var task = sideObject.Name;
        var type = sideObject.Type;

        var duration="";

       /*  if (fulltask == "-:-")
        {
            color = 'gray';
            task = '<i class="fa fa-xmark"></i>';
            type = '<i class="fa fa-xmark"></i>';
            duration = "-";
        }
        else { */
            duration = secondsToTime(sideObject.CounterSec);    
        //}
        
        var percent = (sideObject.CounterSec/totalSec) *100;
        return `
            <li class="p-1 mb-1" id="${id}">
                <div class="info-text">
                    <p class="task">${sideObject.Name}</p>
                    <p class="type">${sideObject.Type}<span style="float:right;"">${side}</span></p>
                </div>
                <div class="bar-wrap">
                    <span class="bar-fill no-bar-fill-animation px-2" style="width: ${percent}%;background-color:${color};">${duration}<span style="float:right;""></span></span>                         
                </div>
            </li> `;
    };

    var jData = JSON.parse(data);

    var totalSecs = jData.reduce((sum, item) => sum + item.CounterSec, 0);

    
    //var element = $(".bar-graph");

    //element.children().remove();

    //element.append('<ul class="bar-graph"></ul>');
    
    var refresh = $('<a class="btn btn-success btn-refresh-day"><i class="fa fa-refresh"></i>Refresh</a>');
    var barGraph = $('<ul class="bar-graph"></ul>');

    barGraph.append(refresh);

    $.each(jData, function(index, item) {
        console.log("Oggetto #" + index);
        console.log("Name:", item.Name);
        console.log("Side:", item.Side);
        console.log("CounterSec:", item.CounterSec);

        var objFace = createInputTask(0, jData, item.Side, totalSecs);
        barGraph.append(objFace);
    });
/*
    var objL = createInputTask(0, jData, 'LEFT',  totalSecs);
    barGraph.append(objL);

    var objR = createInputTask(1, jData, 'RIGHT', totalSecs);
    barGraph.append(objR);

    var objF = createInputTask(2, jData, 'FRONT', totalSecs);
    barGraph.append(objF);

    var objB = createInputTask(3, jData, 'BACK', totalSecs);
    barGraph.append(objB);

    var objT = createInputTask(5, jData, 'BASE', totalSecs);
    barGraph.append(objT);

    var objO = createInputTask(4, jData, 'TOP', totalSecs);
    barGraph.append(objO);
*/
    return barGraph;
}