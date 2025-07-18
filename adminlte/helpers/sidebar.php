<style>  
.imgProfile {
      
      /* margin-left: 20px; */
      border:1px solid blue;
    
      width: 300px;
      height: 300px;
      object-fit: cover;
      border: 2px solid #ccc;
      border-radius: 50%;
      cursor: pointer;
 
      background-size: contain;
      background-repeat: no-repeat;
      background-position: center;
      
      z-index:1;
  }
</style>         
            <div class="sidebar-brand    mt-3 pt-3">
                <!-- <img src ='imgs/account.png' class="me-auto" style="height:100%;">
                <p class="my-2">Nome Cognome</p> -->

                
                <div class="col image">
                    <img id="imgProfile" src="" class="img-circle elevation-2" alt="-" style="width:2.5rem;border-radius:50%;">
                </div>
                <div class="col info">
                    <a id="infoname" href="#" class="d-block text-decoration-none text-white">-</a>
                </div>
            

                

				<a href="#" class="brand-link mb-auto">
					
				</a> 
			</div>
            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">

                        <li class="nav-item"> 
                            <a id="profile" href="#" class="nav-link active"> <i class="nav-icon bi bi-person-circle text-white"></i>
                                <p>Profilo</p>
                            </a>
                        </li>
                        <li class="nav-item"> 
                            <a id="notifications" href="#" class="nav-link"> <i class="nav-icon bi bi-calendar-check text-white"></i>
                                <p>Notifiche</p>
                            </a>
                        </li>

                        <li class="nav-item"> 
                            <a id="status" href="#" class="nav-link"> <i class="nav-icon bi bi-box-fill text-white"></i>
                                <p>Stato</p>
                            </a>
                        </li>

                        <li class="nav-item"> 
                            <a id="settings" href="#" class="nav-link"> <i class="nav-icon bi bi-gear text-white"></i>
                                <p>Settings</p>
                            </a>
                        </li>
                        
                          
                        <hr>
                        <li class="nav-item"> 
                            <a id="logout" href="#" class="nav-link "> <i class="nav-icon bi bi-box-arrow-right text-white"></i>
                                <p>Logout</p>
                            </a>
                        </li>   
                       
                    </ul>
                </nav>
            </div>
       
            <script>

                $("#profile").on('click', function () {

                    sidebarCollapse();

                    $(".app-main").load("./viewProfile.html"); 
                });

                $("#notifications").on('click', function () {

                    sidebarCollapse();
                    $(".app-main").load("./viewNotifications.html");
                });

                $("#status").on('click', function () {

                    sidebarCollapse();
                    $(".app-main").load("./viewStatus.html");
                });

                $("#settings").on('click', function () {

                    sidebarCollapse();
                    $(".app-main").load("./viewSettings.html");
                });

                

                $("#logout").on('click', function () {

                    localStorage.clear();
                    $(".app-main").load("./adminlte/helpers/loginPage.html");
                });

                $(document).ready(function () {
                
                       
                    getProfileImage($("#imgProfile"));

                    var fullname = localStorage["name"] + " " + localStorage["surname"];
                    $("#infoname").text(fullname);
                });

                $("#installAppLink").on('click', function() {
                
                    $('#a2hs').modal('show');
                });

                
                 
                // use for iphone
                var webAppByDm = window.matchMedia('(display-mode: standalone)').matches;
                var webAppBySafP = window.navigator.standalone === true
                console.log("is webApp (displaymode:standalone): " + webAppByDm);
                console.log("is webApp (navigator.standalone): " + webAppBySafP);

                if (webAppByDm || webAppBySafP) {
                    $("#installAppLink").hide();
                }
                else {
                    $("#installAppLink").show();
                }
 

            </script>
