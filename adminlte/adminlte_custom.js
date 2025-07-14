// type: success or error

var audioPlayer = null;

function showToastMessage(message, type)
{
    var Toast = Swal.mixin({
        toast: true,
        position: 'bottom-end',
        showConfirmButton: false,
        timer: 4000
    });

    Toast.fire({
        icon: type,
        title: message
    })
}

function sidebarOpen()
{
     
    $("body").addClass('sidebar-open');
    $("body").removeClass('sidebar-close');

    $("body").removeClass('sidebar-collapse');

    //$("body").toggle('sidebar-collapse');
}

function sidebarCollapse()
{
    $("body").addClass('sidebar-close');
    $("body").removeClass('sidebar-open');

    $("body").addClass('sidebar-collapse');
}

function setImageElement(elImg, pathfilename)
{
    if (elImg.prop('tagName') == 'DIV') {
        elImg.css('background-image', "url('" + pathfilename + "')");
    }
    else if (elImg.prop('tagName') == 'IMG') {
        elImg.attr("src", pathfilename);
    }
}

function getProfileImage(elImg)
{
     
    //return null;
    var name = localStorage["name"];
    var surname = localStorage["surname"];

    if (name == undefined || surname == undefined) {
         
        console.log("no name set");
        setImageElement(elImg, "imgs/account.png");
        return;
    }

    var fullname = name + " " + surname;
    var dottedFullname = fullname.replace(/ /g, '.');
    fileName = 'profile_' + dottedFullname + '_' + localStorage["userId"] + '.png?no-cache=' + Date.now();

    // get resoure path
    var pathfile = 'resources/' + fileName;

    console.log("search for profile " + pathfile);
    fetch(pathfile, { cache: 'no-store' })
        .then(response => {
            if (response.ok) 
            {
                 
                setImageElement(elImg, ('resources/' + fileName));
            } 
            else 
            {
                setImageElement(elImg, "imgs/account.png");
            }
        });
       
   
}


function storageUserAndHashAndRole(name, surname, id, hash, role)
{
    localStorage['name'] = name
    localStorage['surname'] = surname;
    localStorage['userId'] = id;
    localStorage['Hash'] = hash;
    localStorage['Role'] = role;
}


function uploadFileToServer(fileInfo, description, raceId, tagUpload, index, callback)
{
     
    
    //var parent = $("#Carousel_" + tagUpload).find('.carousel-inner');
    //var index = parent.children().length + 1;

    var ext = fileInfo.name.split('.').pop();
    var userFullName = localStorage["name"] + "." + localStorage["surname"];

    var date = CompressDateTimeForFile(new Date());

    var fileName = date + "_" + "R" + String(raceId) + "_" + userFullName + "_" + tagUpload  + "_" + "I" + index + "." +  ext;

    //var raceId = (localStorage["RaceId"]==undefined) ? 0 : localStorage["RaceId"];

    var fdata = new FormData();
    fdata.append("Action", "UploadResource");
    fdata.append('FileName', fileName);
    fdata.append("FileInfo", fileInfo);
    fdata.append("Index", Number(index));
    fdata.append("Description", description);
    fdata.append("RaceId", raceId);
    fdata.append("UserFullName", userFullName);
    fdata.append("UserId", localStorage["userId"]);
    fdata.append("Type", tagUpload);

    fetch("./dal/upload.php", { method:"post", body:fdata })
        .then(res => res.json())
        .then(data => callback(data, fileInfo, description, tagUpload));
}

function resetMainClass()
{
    $('.app-main').attr('class', 'main app-main');
}

function waitingStart()
{
    $("#loader-wrapper").show();
}

function waitingSttop()
{
    $("#loader-wrapper").hide();
}

// open street map

function setMapInElementId(elementId, coordStr, zoom)
{
    if (coordStr != "-") {
        var coord = JSON.parse(coordStr);
        var lat = coord.lat;
        var lon = coord.lon;

         

        var mapx = L.map(elementId, {
            center: [lat, lon],
            zoom: zoom,          
            zoomControl: false 
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: zoom, 
            
        }).addTo(mapx);

        mapx.invalidateSize();
    }
    else
    {
      $(elementId).append('<img src=imgs/not-available.png class="photo-not-available" style="width:50%;">');
    }
}