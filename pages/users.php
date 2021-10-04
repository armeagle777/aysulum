<?php
    if(!isset($_SESSION['username']) || $_SESSION['role']!=="admin" ){
        header("location: ../index.php");
    }

    include('config/connect.php');
    date_default_timezone_set('Asia/Yerevan');
    $usersTable='<table class="table  table-bordered table-hover" id="user_table">
                    <thead>
                        <th>id</th>
                        <th>Անուն</th>
                        <th>Ազգանուն</th>
                        <th>տեսակ</th>
                        <th>login</th>
                        <th>մուտք</th>
                        <th>կարգավիճակ</th>
                        <th>․․․</th>
                    </thead>
                    <tbody>';
    $query=mysqli_query($conn,"select * from users ");
    while($row=mysqli_fetch_array($query))
    {
        $status='';
        $datetime =  getdate()[0];
        if($row['last_activity'] > ($datetime - 30))
        {
            $status = '<i class="fas fa-user ml-2 onlineStatus"></i>';
        }elseif($row['last_activity'] >= 1619684656 && $row['last_activity'] <  ($datetime - 30))
        {
            $status=date("Y-m-d H:i:s",$row['last_activity']);
        }else
        {            
            $status ='';
        }

        $userStatus='';
        $userType='';
        if($row["user_type"]==='admin')
        {
            $userType='Ադմինիստրատոր';
        }
        if($row["user_type"]==='operator')
        {
            $userType='Մուտքագրող';
        }
        if($row["user_type"]==='officer')
        {
            $userType='Գործ վարող';
        }
        if($row["user_type"]==='coispec')
        {
            $userType='ԾԵՏ մասնագետ';
        }
        if($row["user_type"]==='lawyer')
        {
            $userType='Իրավաբան';
        }
        if($row["user_type"]==='head')
        {
            $userType='ՄԾ պետ';
        }
        if($row["user_type"]==='devhead')
        {
            $userType='Բաժնի պետ';
        }
        if($row["user_type"]==='statist')
        {
            $userType='Վերլուծաբան';
        }
        if($row["user_type"]==='viewer')
        {
            $userType='ՄԱԿ աշխատակից';
        }
        if($row["user_status"]== 1)
        {
            $userStatus=' checked';
        }

        $usersTable.='<tr>
                        <td>'.$row["id"].'</td>
                        <td>'.$row["f_name"].'</td>
                        <td>'.$row["l_name"].'</td>
                        <td>'.$userType.'</td>
                        <td>'.$row["username"].'</td>
                        <td>'.$status.'</td>
                        <td>
                            <label class="switch">
                                <input class="status_toggler" toggleId="'.$row["id"].'" type="checkbox" '.$userStatus.'>
                                <span class="slider"></span>
                            </label>
                        </td>
                        <td><button editId="'.$row["id"].'"  class="btn btn-warning edit_button"><i class="fas fa-edit"></i>Խմբագրել</button></td>
                    </tr>';
    }
    $usersTable.= '</tbody>
                </table>';

?>

<div class="new_case">
    <div id="successMsg">
        <p class="userSuccess">Գրանցումը հաջողությամբ կատարված է</p>
    </div>
    <div style="margin:auto; padding:auto;">
        <p class="title">Ապաստան հայցողների հաշվառման համակարգի օգտատերեր</p>
        <span class="pull-left">
        <a href="#addnew" data-toggle="modal" class="btn btn-primary"><span class="glyphicon glyphicon-plus">
        </span>&#43 Նոր օգտատեր</a></span>
        <?php  echo $usersTable; ?>
    </div>
</div>
<div class="modal fade" id="addnew" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title text-center" id="myModalLabel">Ավելացնել օգտատեր</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <form method="" id="newUserForm" >
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <label class="control-label" style="position:relative; top:7px;">Անուն</label>
                        <input type="text" class="form-control" id="firstname"  required>
                    </div>
                    <div class="row">
                        <label class="control-label" style="position:relative; top:7px;">Ազգանուն:</label>
                        <input type="text" class="form-control" id="lastname" required>
                    </div>
                    <div class="row">
                        <label class="control-label" style="position:relative; top:7px;">Տեսակ</label>
                        <select class="form-control" name="user_type" id="user_type">
                            <option value="">Ընտրեք տեսակը</option>
                            <option value="admin">Ադմինիստրատոր</option>
                            <option value="operator">Մուտքագրող</option>
                            <option value="officer">Գործ վարող</option>
                            <option value="coispec">ԾԵՏ մասսնագետ</option>
                            <option value="lawyer">Իրավաբան</option>
                            <option value="devhead">Բաժնի պետ</option>
                            <option value="statist">Վերլուծաբան</option>
                            <option value="viewer">ՄԱԿ աշխատակից</option>
                            <option value="head">ՄԾ պետ</option>
                            <option value="nss">ԱԱԾ</option>
                            <option value="police">Ոստիկանություն</option>
                            <option value="dorm">Հատուկ կացարան</option>
                            <option value="fin">Ֆին բաժին</option>
                            <option value="secretary">Գլխավոր քարտուղար</option>
                        </select>
                    </div>
                    <div class="row">
                        <label class="control-label" style="position:relative; top:5px;">Մուտքանուն<span id='usernameError'>* Մուտքանունը կրկնվում է</span></label>
                        <input type="text" class="form-control" id="user_login" required>
                    </div>
                    <div class="row">
                        <label class="control-label" style="position:relative; top:7px;">Գաղտնաբառ</label>
                        <input type="text" class="form-control" id="pass" required>
                    </div>
                </div> 
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glypicon-remove"></span> Cancel</button>
                <button type="button" class="btn btn-primary" name="new_person" id="newUserSubmit"><span class="glyphicon glyphicon-floppy-disk"></span> ՊԱՀՊԱՆԵԼ</button>
            </div>
        </form>
    </div>
  </div>
</div>
<script>
    $(document).ready(function()
    { 
        //Edit User Status
        $(document.body).on("change",".status_toggler", function(){
            var toggleId = $(this).attr("toggleId");
            var toggleValue=$(this).prop("checked") ? 1 : 0;
            $.ajax({
                url:"config/config.php",
                method:"POST",
                data:{editUserStatus:toggleId,newStatus:toggleValue},
                success:function(data)
                {  
                    console.log('Status changed');                    
                } 
            });
        })
        //Edit button onClick          
        $(document.body).on( 'click', '.edit_button', function (){
            var UserId = $(this).attr("editId");
            $.ajax({
                url:"config/config.php",
                method:"POST",
                data:{editUserId:UserId},
                success:function(data)
                {  
                    $('#addnew').html(data);
                    $('#addnew').modal("show");
                    
                } 
            });           
        });
        $(document.body).on("keyup",":text",function(){
            $(this).removeClass("error");
        })
        $("#user_type").change(function(){
            if($("#user_type").val() !== ''){
                $(this).removeClass("error");
            }            
        })
        //New User registration
        $("#newUserSubmit").click(function(){
            var fname=$("#firstname").val();
            var lname=$("#lastname").val();
            var userType=$("#user_type").val();
            var login = $("#user_login").val();
            var pass=$("#pass").val();
            if(fname ==='' || lname==='' || login==='' || pass === '' || userType=== '' )
            {
                if(fname==='')
                {
                    $("#firstname").addClass("error");
                }
                if(lname==='')
                {
                    $("#lastname").addClass("error");
                }
                if(login==='')
                {
                    $("#user_login").addClass("error");
                }
                if(pass==='')
                {
                    $("#pass").addClass("error");
                }
                if(userType==='')
                {
                    $("#user_type").addClass("error");
                }
                return;
            }
            $.ajax({
                url:"config/config.php",
                method:"POST",
                data:{new_user:1,fname:fname,lname:lname,userType:userType,login:login,pass:pass},
                success:function(data)
                {    
                    if(data==='success')
                    {
                        $(':input','#newUserForm')
                        .not(':button, :submit, :reset, :hidden')
                        .val('')
                        .prop('checked', false)
                        .prop('selected', false);
                        $('#addnew').modal("hide");
                        $("#successMsg").show();
                        setTimeout(() => {
                            $("#successMsg").hide();
                            location.reload();
                        }, 2000);
                    }
                    if(data==='change')
                    {
                        $("#usernameError").show();
                        setTimeout(() => {
                            $("#usernameError").hide();
                        }, 2000);
                    }
                    
                } 
            });
            
        })
        //Editing user info
        $(document.body).on("click","#userEditSubmit",function(){
            var uid=$(this).attr('uid');
            var fname=$("#firstname").val();
            var lname=$("#lastname").val();
            var userType=$("#user_type").val();
            var login = $("#user_login").val();
            var pass=$("#pass").val() ? $("#pass").val() : '';
            if(fname ==='' || lname==='' || login===''  )
            {
                if(fname==='')
                {
                    $("#firstname").addClass("error");
                }
                if(lname==='')
                {
                    $("#lastname").addClass("error");
                }
                if(login==='')
                {
                    $("#user_login").addClass("error");
                }
                return;
            }
            $.ajax({
                url:"config/config.php",
                method:"POST",
                data:{edit_user:1,uid:uid,fname:fname,lname:lname,userType:userType,login:login,pass:pass},
                success:function(data)
                {    
                    console.log(data);
                    if(data==='success')
                    {
                        $(':input','#newUserForm')
                        .not(':button, :submit, :reset, :hidden')
                        .val('')
                        .prop('checked', false)
                        .prop('selected', false);
                        $('#addnew').modal("hide");
                        $("#successMsg").show();
                        setTimeout(() => {
                            $("#successMsg").hide();
                            location.reload();
                        }, 2000);
                    }
                    if(data==='change')
                    {
                        $("#usernameError").show();
                        setTimeout(() => {
                            $("#usernameError").hide();
                        }, 2000);
                    }
                    
                } 
            });
            
        })
        $('#user_table').DataTable({
                        paging: true,
                        searching: true,
                        ordering:  true,
                        bInfo : false,
                        lengthChange: false
        });
        $(document.body).on('change','#editUserMarz',function(){
            console.log('changed');
        })
        $(document.body).on('change','#user_type',  function()
        {
            var userType=$(this).val();
            if (userType === 'marzpetaran')      
            {
                $("#user_marz_select").show();
                $("#userEditMarz").show();
                
            }else  
            {
                $("#user_marz_select").hide();
                $("#userEditMarz").hide();
            }
        });
    })
</script>