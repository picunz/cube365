
function getMyRaces(userId, callback)
{
      
    var fdata = new FormData();
    fdata.append("Action", "GetList");
    fdata.append("UserId", userId);
    fdata.append("Table", "Races");

    fetch("./dal/generic.php", { method:"post", body:fdata })
        .then(res => res.json())
        .then(data => callback(data)); 
}

function getMyNotifications(userId, callback)
{
    var fdata = new FormData();
    fdata.append("Action", "GetList");
    fdata.append("UserId", userId);
    fdata.append("Table", "Notifications");

    fetch("./dal/generic.php", { method:"post", body:fdata })
        .then(res => res.json())
        .then(data => callback(data)); 
}