 
 
  
  
           
<div class="container-fluid" style="height:12vh;">
    <ul class="col navbar-nav">
        <li class="nav-item"> 
            <a class="nav-link" id="menu-link" data-lte-toggle___="sidebar___" href="#" role="button"> 
                <i class="bi bi-list text-white"></i> 
            </a> 
        </li>
    </ul>

    <ul class="col navbar-nav">
        <!-- 
        <li class="nav-item d-none d-md-block"> <a href="#" class="nav-link">Home3</a> </li>
        <li class="nav-item d-none d-md-block"> <a href="#" class="nav-link">Contact2</a> </li>
         -->
        <li class="nav-item mx-auto"> 
            <img src="adminlte/helpers/imgs/cube365_logo.png" alt="Cube Logo" class="brand-image">
        </li>    
        
    </ul>
    <ul class="col navbar-nav">
         
        <li class="nav-item ms-auto link-notification" style="cursor:pointer;display:none;"> 
            <i class="fa fa-xl fa-bell text-white"></i> 
        </li>

        <li class="nav-item ms-auto link-home" style="display:none;cursor:pointer;"> 
            <i class="fa fa-xl fa-home text-white"></i> 
        </li>

        <!--
        <li class="nav-item ms-auto"> 
            <div class="form-check form-switch nav-link">
                <input class="form-check-input" type="checkbox" value="dark" id="darkswitch">
            </div>
        </li>
        -->    
        <!--
        <li class="nav-item"> 
            <a class="nav-link text-white" href="#" data-lte-toggle="fullscreen"> 
            <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i> 
            <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none;"></i> 
            </a> 
        </li>
        -->
    </ul>
</div>
<!-- 

<div id="PasswordLogin" style="position:absolute; width:100%;" class="row"></div>
     -->
 
<script>
  
    $("#darkswitch").on('change', function() {
       
        var mode = $(this).val();
        if (mode == 'dark') {
            $('[data-bs-theme="dark"]').attr('data-bs-theme', 'light');
            $(this).val('light');
        }
        else {
            $('[data-bs-theme="light"]').attr('data-bs-theme', 'dark');
            $(this).val('dark');
        }
    }); 

    $(".link-home").on('click', function() {

        console.log("back to home navbar.php");

        $(".app-main").load("./main.html");
    });

    $("#menu-link").on('click', function(){

        console.log('menu click');
        if ($("body").hasClass('sidebar-open')) {
            sidebarCollapse();
        }
        else if ($("body").hasClass('sidebar-close')) {
            sidebarOpen();
        }
        else { 
             sidebarOpen();
        }
    });


    $(document).ready(function() {
        console.log("navbar.php ready()");
    });
 

</script>
