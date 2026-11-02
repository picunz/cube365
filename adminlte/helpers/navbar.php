 
  
  
           
<div class="container-fluid position-relative" style="min-height:12vh;">
    <ul class="navbar-nav position-absolute" style="left:0; top:0.5rem;">
        <li class="nav-item"> 
            <a class="nav-link" id="menu-link" data-lte-toggle___="sidebar___" href="#" role="button"> 
                <i class="bi bi-list text-white"></i> 
            </a> 
        </li>
    </ul>

    <div class="d-flex flex-column align-items-center justify-content-center w-100 text-center" style="min-height:12vh;">
        <div class="mb-2">
            <img src="adminlte/helpers/imgs/cube.png" alt="Cube Logo" style="width:70px;" class="brand-image">
        </div>

        <nav class="m-2 overflow-x-auto custom-scrollbar mx-auto" style="max-width:100%;" aria-label="Component categories">
            <div id="tab-list" class="flex gap-2 p-2 rounded-2xl mx-auto w-fit min-w-max neu-inset" role="tablist" aria-orientation="horizontal">
                <button type="button" role="tab" id="Tab-reports" aria-selected="true" aria-controls="panel-basics" tabindex="0" class="tab-btn neu-focus px-6 py-3 rounded-xl text-sm font-medium transition-all" data-tab="basics">
                    Reports
                </button>
                <button type="button" role="tab" id="Tab-tasks" aria-selected="false" aria-controls="panel-forms" tabindex="-1" class="tab-btn neu-focus px-6 py-3 rounded-xl text-sm font-medium transition-all" data-tab="forms">
                    Tasks
                </button>
                <button type="button" role="tab" id="Tab-settings" aria-selected="false" aria-controls="panel-forms" tabindex="-1" class="tab-btn neu-focus px-6 py-3 rounded-xl text-sm font-medium transition-all" data-tab="forms">
                    Settings
                </button>
                
            </div>
        </nav>
    </div>
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


 
    $(document).on('click', '#Tab-reports', function() {

        $(this).attr('aria-selected', 'true').attr('tabindex', '0');

        $('#Tab-settings').attr('aria-selected', 'false').attr('tabindex', '-1');
        $('#Tab-tasks').attr('aria-selected', 'false').attr('tabindex', '-1');
        
        $(".app-main").load("./viewArchive.html");
    });

    $(document).on('click', '#Tab-settings', function() {

        $(this).attr('aria-selected', 'true').attr('tabindex', '0');

        $('#Tab-reports').attr('aria-selected', 'false').attr('tabindex', '-1');
        $('#Tab-tasks').attr('aria-selected', 'false').attr('tabindex', '-1');
     
     
        $(".app-main").load("./viewConfigure.html");
    });

    $(document).on('click', '#Tab-tasks', function() {

        $(this).attr('aria-selected', 'true').attr('tabindex', '0');

        $('#Tab-reports').attr('aria-selected', 'false').attr('tabindex', '-1');
        $('#Tab-settings').attr('aria-selected', 'false').attr('tabindex', '-1');
     
        $(".app-main").load("./viewHistoryTasks.html");
    });

</script>
