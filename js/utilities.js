  

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

    fetch("./api/generic.php", { method: "post", body: data })
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

  

function ConvertTime2HM(time)
{
    var timeHM = time.split(':')[0] + ':' + time.split(':')[1];
    return timeHM;
}

 
 

function getLocalUserName()
{
    return localStorage["techName"] + " " + localStorage["techSurname"];
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

    return barGraph;
}

function getCurrentStatus(deviceId, onGetCurrentStatusCallback)
{
    var fdata = new FormData();
    fdata.append("Action", "GetList");
    fdata.append("DeviceId", deviceId);
    fdata.append("Table", "Status");

    
    fetch("./api/generic.php", { method:"post", body:fdata })
        .then(res => res.json())
        .then(data => {
                
            if (onGetCurrentStatusCallback) {
                onGetCurrentStatusCallback(data);
            }
        }); 
        
}