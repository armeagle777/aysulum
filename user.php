<?php
session_start();
if(!isset($_SESSION['username'])  || ($_SESSION['role']!=="admin" && $_SESSION['role']!=="operator" && $_SESSION['role']!=="archiver" && $_SESSION['role']!=="statist" && $_SESSION['role']!=="viewer" && $_SESSION['role']!=="lawyer" && $_SESSION['role'] !== 'nss'&& $_SESSION['role'] !== 'fin' && $_SESSION['role'] !== 'secretary' && $_SESSION['role'] !== 'dorm' && $_SESSION['role'] !== 'police'&& $_SESSION['role']!=="officer" && $_SESSION['role']!=="devhead" && $_SESSION['role']!=="coispec" && $_SESSION['role']!=="head" && $_SESSION['role']!=="general")){
    header("location:index.php");
}
$us = $_SESSION['username'];
$f_name = $_SESSION['user_fName'];
$l_name = $_SESSION['user_lName'];
$u_id = $_SESSION['user_id'];

//get page
if(isset($_GET['page']) && file_exists('pages/'.$_GET['page'].'.php')){
    $page=htmlspecialchars($_GET['page']);
}else{
    $page='home';
}
?>

<!DOCTYPE html>
    <html>
        <head>
            <title>ՄԾ հաշվառման համակարգ</title>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="shortcut icon" href="includes/images/favicon.ico" type="image/x-icon">
            <meta name="theme-color" content="#999999" />
            <!--CSS  -->
            <link rel="stylesheet" href="includes/css/style.css">
            <link rel="stylesheet" href="pages/bungie.css">
<!--            <link rel="stylesheet" href="includes/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">-->

            <script>
                //<![CDATA[
                (window.jQuery) || document.write('<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">');
                //]]>
            </script>
            <link rel="stylesheet"  href="includes/css/datatables.min.css">
            <link rel="stylesheet" type="text/css" href="includes/css/scheme_css.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" integrity="sha512-yVvxUQV0QESBt1SyZbNJMAwyKvFTLMyXSyBHDO4BG5t7k/Lw34tyqlSDlKIrIENIzCl+RVUNjmCPG+V/GMesRw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
            <link rel="stylesheet" type="text/css" href="includes/css/case_page.css">
            <!--JS  -->
            <script src="includes/js/jquery-3.6.0.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js" integrity="sha512-2rNj2KJ+D8s1ceNasTIex6z4HWyOnEYLVC3FigGOmyQCZc2eBXKgOxQmo3oKLHyfcj53uz4QMsRCWNbLd32Q1g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
           
            <script src="includes/js/bootstrap.min.js"></script>
            <script src="includes/js/fontawesome.js"></script>
            <script src="includes/js/datatables.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha512-rMGGF4wg1R73ehtnxXBt5mbUfN9JUJwbk21KMlnLZDJh7BkPmeovBuddZCENJddHYYMkCh9hPFnPmS9sspki8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
            <script src="https://use.fontawesome.com/5c6f99dda3.js"></script>
            
            
            
        <!-- DataTables CSS library -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css"/>


        
        <!-- DataTables JS library -->
        <script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

        <!-- Old Datatable scripts  -->
        <script  src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>
        <script  src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.flash.min.js"></script>
        <script  src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script  src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script  src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script  src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script> 
        </head>
        <body>
            <div class="topnav1" id="myTopnav1">  
                <?php 
                    if($_SESSION['role'] !== 'archiver')
                    {
                ?>
                    <a href="?page=cases"  <?php if($page=="cases"){ ?> class="active" <?php } ?>><i class="fa fa-home" aria-hidden="true"></i> Գործեր</a> 
                    <a href="?page=calendar"  <?php if($page=="calendar"){ ?> class="active" <?php } ?>><i class="fa fa-calendar" aria-hidden="true"></i> Օրացույց</a> 
                    <a href="?page=statics"  <?php if($page=="statics"){ ?> class="active" <?php } ?>><i class="fa fa-table" aria-hidden="true"></i> Վիճակագրություն</a>
                <?php
                    }
                ?>          
                <a href="?page=archive"  <?php if($page=="archive"){ ?> class="active" <?php } ?>><i class="fa fa-archive" aria-hidden="true"></i> Արխիվ</a> 
                <?php 
                    if($_SESSION['role'] !== 'archiver')
                    {
                ?>
                    <a href="?page=search" <?php if($page=="search"){ ?> class="active" <?php } ?>><i class="fa fa-search" aria-hidden="true"></i> Որոնում</a> 
                <?php
                    }
                ?>               
                <div class="dropdown2">
                    <button class="dropbtn2"><span><i class="fa fa-caret-down"></i> <i class="far fa-user"></i> <?php echo $f_name . ' ' . $l_name ?></span></button>
                    <div class="dropdown-content2">
                        <?php 
                            if($_SESSION['role'] === 'admin')
                            {
                        ?>
                            <a href="?page=users" ><i class="fa fa-user mr-1" aria-hidden="true"></i>Օգտատեր</a>
                        <?php
                            }
                        ?>
                        <a href="?page=faq" id="faq" ><i class="fa fa-question-circle" aria-hidden="true"></i>FAQ</a>
                        <a href="config/logout.php" id="log_out" ><i class="fa fa-sign-out" aria-hidden="true"></i>Ելք</a>
                    </div>
                </div> 
                <div class="dropdown_notify">                    
                    <button class="dropbtn_n"> <i class="fas fa-bell ringN"></i> <span class="label label-pill label-danger count"></span></button>
                    <div class="dropdown-contentN">           
                    </div>  
                </div> 
                <a href="javascript:void(0);" style="font-size:15px;" class="icon1" onclick="myFunction()">&#9776;</a>
            </div>
            <div class="admin_container">
                <?php include_once('pages/'.$page.'.php'); ?>
            </div>
        </body>
    </html>
    <script>     
        $(document).ready(function(){
	        load_unseen_notification();
            function load_unseen_notification(view = '')
            {
                var user = '<?php echo $u_id;?>'
                $.ajax({
                    url:"fetch.php",
                    method:"POST",
                    data:{view:view, user:user},
                    dataType:"json",
                    success:function(data)
                    {  
                       $('.dropdown-contentN').html(data.notification);
                       if (data.unseen_notification > 0) 
                       {
                         $('.count').html(data.unseen_notification);
                       }  
                    }
                });
            }

			setInterval(function(){
            load_unseen_notification();

	        },5000);



            $(".dropdown1").hover(function()
            {
                $("#fafadown2").toggleClass("fa-caret-down").toggleClass("fa-caret-up");
            })
            function myFunction()
            {
                var x = document.getElementById("myTopnav1");
                if (x.className === "topnav1")
                {
                    x.className += " responsive";
                } else {
                    x.className = "topnav1";
                }
            }
            setInterval(() => {
                update_last_activity();
            }, 5000);

            function update_last_activity()
            {
                $.ajax({
                    url:"config/config.php",
                    method:"POST",
                    data:{updateActivity:1},
                    success:function(data)
                    {  
                        
                    } 
                });
            }
        })
    </script>