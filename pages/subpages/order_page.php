<?php

  require_once 'config/connect.php';
  require_once 'config/query_case.php';


  $case = $_GET['case'];
  $order = $_GET['order'];
  $u_id = $_SESSION['user_id'];
  $file_type = '';
  
    if(isset($_GET['notification_id'])){
    $note_id = $_GET['notification_id'];
    $query = "UPDATE tb_notifications SET comment_status = 1 WHERE comment_id = $note_id";
    $result_notify = $conn->query($query);
}


  $sql_all_person = "SELECT a.personal_id, a.case_id, a.f_name_arm, a.f_name_eng, a.l_name_arm, a.l_name_eng, a.m_name_arm, a.m_name_eng, a.b_day, a.b_month, a.b_year, a.sex, a.citizenship, a.previous_residence, a.citizen_adr, a.residence_adr, a.departure_from_citizen, a.departure_from_residence, a.arrival_date, a.doc_num, a.etnicity, a.religion,  a.preferred_traslator_sex, a.preferred_interviewer_sex, a.invalid, a.pregnant, a.seriously_ill, a.trafficking_victim, a.violence_victim, a.comment, a.illegal_border, a.transfer_moj, a.deport_prescurator, a.role, b.der, t.checkin_id, t.checkin_date, t.checkout_date, t.doss_id, t.status AS CHSTATUS
    FROM tb_person a 
    INNER JOIN tb_role b ON b.role_id = a.role
    LEFT JOIN tb_checkin t ON a.personal_id = t.personal_id
    WHERE a.case_id = $case";
  
  $result_all_person = $conn->query($sql_all_person);
  

  $sql_count_person = "SELECT COUNT(personal_id)  AS PSUM  FROM tb_person WHERE case_id = $case";
  $result_person_for_sum = $conn->query($sql_count_person);

  if ($result_person_for_sum->num_rows > 0) {
    $row_sum = $result_person_for_sum->fetch_assoc();
    $count_person = $row_sum['PSUM'];
  }



  $sql_files_order = "SELECT a.order_process_id, a.order_id, a.order_from, a.order_to, a.process_date, a.order_status, a.order_actual, a.order_comment, b.order_id, b.case_id, b.order_status, b.date, e.f_name AS SENDER_NAME, e.l_name AS SENDER_L_NAME, f.f_name AS REC_NAME, f.l_name AS REC_L_NAME, g.file_name, g.file_type, h.order_status_id, h.order_status_arm 
    FROM tb_order_process a
    INNER JOIN tb_orders b ON a.order_id = b.order_id
    INNER JOIN users e ON a.order_from = e.id
    INNER JOIN users f ON a.order_to = f.id
    INNER JOIN tb_order_process_status h ON a.order_status = h.order_status_id
    LEFT JOIN files g ON a.order_process_id = g.order_process_id 
    WHERE a.order_id = $order ORDER BY a.order_process_id DESC";
    $res_list_file = $conn->query($sql_files_order);







$sql_general_order = "SELECT a.order_id, a.case_id, a.order_status, a.date AS ORDER_DATE, b.case_status, c.case_status AS CASE_STATUS, d.order_process_id, d.order_status AS PROCESS_STATUS, d.order_actual, e.order_status_arm, d.order_from, d.order_to, f.f_name AS SENDER_F_NAME, f.l_name AS SENDER_L_NAME, d.process_date, d.order_comment 
FROM tb_orders a 
INNER JOIN tb_case b ON a.case_id = b.case_id 
INNER JOIN tb_case_status c ON b.case_status = c.case_status_id 
INNER JOIN tb_order_process d ON a.order_id = d.order_id 
LEFT JOIN tb_order_process_status e ON d.order_status = e.order_status_id 
INNER JOIN users f ON d.order_from = f.id


WHERE d.order_actual = 1 AND a.order_id = $order";
 $res_sql_general_order = $conn->query($sql_general_order);

 if ($res_sql_general_order->num_rows > 0) {
   $row_order           = $res_sql_general_order->fetch_assoc();
   $case_status         = $row_order['CASE_STATUS'];
   $order_date          = date('d.m.Y', strtotime($row_order['ORDER_DATE']));
   $order_status        = $row_order['order_status_arm'];
   $order_status_id     = $row_order['PROCESS_STATUS'];
   $sender_name         = $row_order['SENDER_F_NAME'] .' '. $row_order['SENDER_L_NAME'];
   $function_date       = date('d.m.Y' , strtotime($row_order['process_date']));
   $order_comment1      = $row_order['order_comment'];
 }




$sql_main_order = "SELECT
    a.order_process_id,
    a.order_id,
    a.order_from,
    a.order_to,
    a.process_date,
    a.order_status AS PROCESS_STATUS,
    a.order_actual,
    a.order_comment AS M_COMMENT,
    b.order_id,
    b.case_id,
    b.order_status,
    b.date AS MORDER_DATE,
    c.personal_id,
    c.f_name_arm,
    c.l_name_arm,
    c.m_name_arm,
    c.sex,
    c.role,
    c.citizenship,
    c.b_day,
    c.b_month,
    c.b_year,
    d.country_arm AS M_CITIZENSHIP,
    h.order_status_id,
    h.order_status_arm
FROM
    tb_order_process a
INNER JOIN tb_orders b ON
    a.order_id = b.order_id
INNER JOIN tb_person c ON
    b.case_id = c.case_id
INNER JOIN tb_country d ON
    c.citizenship = d.country_id
INNER JOIN tb_order_process_status h ON
    a.order_status = h.order_status_id
WHERE
    b.order_id = $order AND c.role = 1";

$result_main_order = $conn->query($sql_main_order);


  
?>

<input type="hidden" name="user" id="user_id" value="<?php echo $u_id ?>">

<div class="btn_area"> 
  <div class="row">  


    <div class="dropdown ml-2">
      <button class="btn btn-primary btn-sm">Գործառույթներ</button>
      <div class="dropdown-content">
                    <?php 
                        if($_SESSION['role'] === 'admin' || ($_SESSION['role'] === 'devhead' && $order_status_id == 1) || ($_SESSION['role'] === 'devhead' && $order_status_id == 4))
                        {                    
                    ?> 
                        <a href="#" class="approve_order" order_id="<?php echo $order ?>" order_case="<?php echo $case ?>"  ><i class="fas fa-file-signature first_menu"></i> Հաստատել</a>
                       
                    <?php
                        }
                    ?>

                    <?php 
                        if($_SESSION['role'] === 'admin' || ($_SESSION['role'] === 'dorm' ))
                        {                    
                    ?> 
                        <a href="#" class="report_on" order_id="<?php echo $order ?>" order_case="<?php echo $case ?>" > <i class="far fa-file first_menu"></i>  Պատասխանել</a>
                    <?php
                        }
                    ?>
                   <!--  <?php 
                        if($_SESSION['role'] === 'admin' || ($_SESSION['role'] === 'operator' ))
                        {                    
                    ?> 
                        <a href="#" class="create_answer" order_id="<?php echo $order ?>" order_case="<?php echo $case ?>" > <i class="far fa-file first_menu"></i>  Պատրաստել ելից գրություն</a>
                    <?php
                        }
                    ?> -->
   
      </div>
    </div>
</div>
</div>


        
<div class="case_area">

<div class="tab">
  <button class="tablinks" onclick="openCity(event, 'functions')" id="defaultOpen"> Գործառույթներ</button>
  <button class="tablinks" onclick="openCity(event, 'referal')"> Ուղեգիր</button>
  <button class="tablinks" onclick="openCity(event, 'letters')"> Նամականի</button>
  

</div>




<div id="letters" class="tabcontent">
  <div class="col-md-12">
      <h5 class="sub_title" style="margin-top: 5px;"> Նամականի </h5> 
      
      <table class="table table-stripped table-bordered">
        <tr>
            <th width="10%" class="label_pers_page">ամսաթիվ</th>
            <th width="20%" class="label_pers_page">կարգավիճակ</th>
            <th width="15%" class="label_pers_page">հեղինակ</th>
            <th width="15%" class="label_pers_page">ստացող</th>
            <th width="30%" class="label_pers_page">հաղորդագրություն</th>
            
        </tr>
       
        <?php 
        while($row_files = $res_list_file->fetch_assoc()){
          $msg_date      = date('d.m.Y', strtotime ($row_files['process_date']));
          $msg_status    = $row_files['order_status_arm'];
          $msg_author    = $row_files['SENDER_NAME'] .' '. $row_files['SENDER_L_NAME'];
          $msg_receiver  = $row_files['REC_NAME'] . ' '. $row_files['REC_L_NAME'];
          $msg_msg       = $row_files['order_comment'];
    
        ?>
        <tr>
            <td><?php echo $msg_date ?> </td>
            <td><?php echo $msg_status ?> </td>
            <td><?php echo $msg_author ?> </td>
            <td><?php echo $msg_receiver ?> </td>
            <td><?php echo $msg_msg ?> </td>
           
        </tr>
        <?php
      } 
      ?>
      </table>

    </div> <!-- closing 1st column -->
</div>






<div id="functions" class="tabcontent">
  <div class="col-md-12">
  
    <div class="row">
  <div class="col-md-8">
    <div class="row">
      
      <div class="col-md-4">
        <label class="label_pers_page">Ումից</label>
        <input type="text" class="form-control" value="<?php echo $sender_name ?>" readonly>
      </div>
      <div class="col-md-3">
        <label class="label_pers_page">Գործառույթի ամսաթիվ</label>
        <input type="text" class="form-control" value="<?php echo $function_date?>" readonly>
      </div>

      <div class="col-md-12">
        <label class="label_pers_page">Հաղորդագրություն</label>
       <input type="text" class="form-control" name="msg_order" value="<?php echo $order_comment1 ?>" readonly="" />

      </div>
   
    </div>
  </div>

   <div class="col-md-4">
      <h5 class="sub_title" style="margin-top: 5px;">Գործի մասին </h5> 
      
      <table class="table">
        <tr>
          <th class="label_pers_page">Գործ #</th>
          <td><?php echo $case ?></td>
        </tr>
        <tr>
          <th class="label_pers_page">Ուղեգիր #</th>
          <td><?php echo $order ?> </td>
        </tr>
        <tr>
          <th class="label_pers_page">Գործի կարգավիճակ</th>
          <td><?php echo $case_status ?></td>
        </tr>
        <tr>
          <th class="label_pers_page">Ուղեգրի կարգավիճակ</th>
          <td><?php echo $order_status ?> </td>
        </tr>
        <tr>
          <th class="label_pers_page">Ուղեգրի ամսաթիվ</th>
          <td><?php echo $order_date?> </td>
        </tr>
        
      </table>
      </div> <!-- closing div col-md-4 -->
    

  </div> <!-- closing 1st row -->

  <div class="row">
     <h5 class="sub_title" style="margin-top: 5px;"> Ընտանիքի կազմը</h5>
                  <table class="table table-stripped table-bordered">
                    <tr>
                      <th class="role">...</th>
                      <th class="role">դերը</th>
                      <th class="fam_members">ա․ա․հ․</th>
                      <th class="role">սեռ</th>
                      <th class="role">տարիք</th>
                      <th class="role">հաշմանդամ</th>
                      <th class="role">հիվանդ</th>
                      <th class="role">հղի</th>
                      <th class="role">թրաֆ. զոհ</th>
                      <th class="role">բռն. զոհ</th>
                      <th class="role">տեղավորման <br> կարգավիճակ</th>
                     </tr>
       
                       <?php 
                       while ($row_all = $result_all_person->fetch_assoc()) {
                        
                        $sex = '';
                        if($row_all['sex'] =='1'){
                          $sex = 'արական';
                        }
                        else {
                          $sex = 'իգական';
                        }

                      $invalid = '';
                      if($row_all['invalid'] == '1'){
                        $invalid = 'checked';
                      }
                    
                      $pregnant = '';
                      if ($row_all['pregnant'] == '1') {
                        $pregnant = 'checked';
                      }

                      $ill = '';
                      if ($row_all['seriously_ill'] == '1'){
                        $ill = 'checked';
                      }

                      $trafficking_victim = '';
                      if ($row_all['trafficking_victim'] == '1'){
                        $trafficking_victim = 'checked';
                      }

                      $violence_victim = '';
                      if ($row_all['violence_victim'] == '1'){
                        $violence_victim = 'checked';
                      }

                      $curent_year = date("Y");
                      $b_year = $row_all['b_year'];
                      if ($b_year != "0000")
                      {
                      $age = $curent_year - $b_year;
                      }
                      else {
                        $age = 'անհայտ';
                      }

                      $show_checkin = '0';
                      $checked_in = '';
                      if ($row_all['CHSTATUS'] == 1) {
                        $checked_in = 'checked';
                        $show_checkin = '1';
                      }

                      $check_outed = '0';
                      if ($row_all['checkout_date']) {
                        $check_outed = '1';
                      }

                        ?>

                     <tr>
                     <td>
                      <?php 
                        if($_SESSION['role'] === 'admin' || ($_SESSION['role'] === 'dorm' && $show_checkin == 0 && $check_outed == 0))
                        {                    
                    ?> 
                            <a href="#" class="check_in_person" pers_count="<?php echo $count_person  ?>" modalid="<?php echo $row_all['personal_id'] ?>" check_id="<?php echo $order ?>" case_id= <?php echo $case ?>  > Տեղավորել</a>
                            
                    <?php
                        }
                       if($_SESSION['role'] === 'admin' || ($_SESSION['role'] === 'dorm' && $show_checkin == 1 ))
                        {                    
                    ?> 
                            <a href="#" class="check_out_person" modalid="<?php echo $row_all['personal_id'] ?>" check_id="<?php echo $order ?>" case_id= <?php echo $case ?>  > Դուրս գրել</a>
                            
                    <?php
                        }
                    ?>

                      
                    </td>
                      <td class="family_members_td"><?= $row_all['der'] ?></td>
                      <td class="family_members_td"><?= $row_all['l_name_arm'] .' '. $row_all['f_name_arm'] ?></td>
                      <td class="family_members_td"><?php echo $sex ?></td>
                      <td class="family_members_td"><?php echo $age ?></td>
                      <td class="family_members_td"><input type="checkbox" class="form-check-input" name="invalid" <?php echo $invalid ?> onclick="return false;"> </td>
                      <td class="family_members_td"><input type="checkbox" class="form-check-input" name="pregnant"  <?php echo $ill?> onclick="return false;"></td>
                      <td class="family_members_td"><input type="checkbox" class="form-check-input" name="ill"  <?php echo $pregnant?> onclick="return false;"> </td>
                      <td class="family_members_td"><input type="checkbox" class="form-check-input" name="trafiking" <?php echo $trafficking_victim?> onclick="return false;"></td>
                      <td class="family_members_td"><input type="checkbox"  class="form-check-input" name="violence_victim" <?php echo $violence_victim?> onclick="return false;"></td>
                       <td class="family_members_td"><input type="checkbox"  class="form-check-input" name="violence_victim" <?php echo $checked_in?> onclick="return false;"></td>
                    </tr>
                     <?php } ?>
                   </table>
  </div> <!-- closing 2nd row -->
  
  </div> <!-- closing md-12 -->
   
  

</div>


<div id="referal" class="tabcontent">

   <div class="col-md-12">
    <?php 
      if ($result_main_order->num_rows > 0) {
          $row_main_order = $result_main_order->fetch_assoc();
          $main_order_id                = $row_main_order['order_id'];    
          $main_order_date              = date('d.m.Y', strtotime($row_main_order['MORDER_DATE']));
          $main_order_asylum_seeker     = $row_main_order['f_name_arm'] . ' ' .$row_main_order['l_name_arm'];        
          $main_order_citizenship       = $row_main_order['M_CITIZENSHIP'];     
          $main_order_comment           = $row_main_order['M_COMMENT'];   
          $main_order_bday              = $row_main_order['b_day'].'.'. $row_main_order['b_month'].'.'. $row_main_order['b_year'];

}
    ?>
  <div class="row"> 
    <div class="col-md-12">  
        <div class="gerb_area">
          <div class="gerb_vertical-center">
              <img src="includes/images/gerb.png" width="60" height="60">
          </div>
        </div>
          
          <h5 class="order_ms_title" >Մ Ի Գ Ր Ա Ց Ի Ո Ն    Ծ Ա Ռ Ա Յ Ո Ւ Թ Յ Ո Ւ Ն</h5>

          <div class="order_date_num_line">
           
              <p class="order_date"><?php echo $main_order_date ?>թ.</p> 
              <p class="order_num"> N <?php echo $main_order_id ?> </p> 
                     
          </div> 

       
          <div>
          <h5 class="order_title" style="margin-top: 5px;">Ո Ւ Ղ Ե Գ Ի Ր</h5> 
          <h5 class="order_title" style="margin-top: 5px;">ԱՊԱՍՏԱՆ ՀԱՅՑՈՂՆԵՐԻՆ ԺԱՄԱՆԱԿԱՎՈՐ ՏԵՂԱՎՈՐՄԱՆ ԿԵՆՏՐՈՆՈՒՄ ՏԵՂԱՎՈՐԵԼՈՒ</h5> 
          </div>
          <hr>

          <div class="order_body">
           <span class="order_labels">Տրվում է`</span> 
           <span class="main_text"><?php echo $main_order_asylum_seeker ?>-ին</span>
          </div>
       
          <div class="order_body">
           <span class="order_labels">ծնված`</span> 
           <span class="main_text"><?php echo $main_order_bday ?>-ին</span>
          </div>

          <div class="order_body">
           <span class="order_labels">քաղաքացիությունը`</span> 
           <span class="main_text"><?php echo $main_order_citizenship ?></span>
          </div>
          
          <?php 
              $sql_family_members_main_order = "SELECT f_name_arm, l_name_arm, m_name_arm, b_year, sex FROM tb_person WHERE case_id = $case AND role != 1";
              $result_family_main_order = $conn->query($sql_family_members_main_order);
              $family_block = '';

              if ($result_family_main_order->num_rows > 0) {
              $family_block = '
                <div class="order_body">
                <span class="order_free_text">առ այն, որ նա և նրա ընտանիքի ներքոնշյալ անդամները` </span>
                </div>   

                <table class="table table-bordered" style="width: 90%; margin-left: 5%;">
            
            <tr>
              <th class="label_family_1" width="70%">ա.ա.հ.</th>
              <th class="label_family_1" width="15%">սեռ</th>
              <th class="label_family_1" width="15%">տարիք</th>
            </tr>';
               while ($row_main_order_family = $result_family_main_order->fetch_assoc()) {
                $full_name                   = $row_main_order_family['f_name_arm'] .' '. $row_main_order_family['l_name_arm'];
                $sex                         = $row_main_order_family['sex'];
                $sex_text = '';
                if ($sex == 1) {
                  $sex_text = 'արական';
                }
                else {
                  $sex_text = 'իգական';
                }

                $curent_year = date('Y');
                $age = $curent_year - $row_main_order_family['b_year'];
              
              $family_block.='
                <tr>
                  <td>'.$full_name.'</td>
                  <td>'.$sex_text.'</td>
                  <td>'.$age.'</td>
                </tr>
                </table>
              <div class="order_body">
              <span class="order_free_text">ուղարկվում են բնակության ապաստան հայցողների ժամանակավոր տեղավորման կենտրոն` հետևյալ հասցեով` Մոլդովական 29/1 </span>
              </div>

              ';  
              }   
              }
              else {
                $family_block = '
                  <div class="order_body">
                <span class="order_free_text">առ այն, որ նա ուղարկվում է բնակության ապաստան հայցողների ժամանակավոր տեղավորման կենտրոն` հետևյալ հասցեով` Մոլդովական 29/1</span>
                </div>   
                ';
              }

              echo $family_block;
          ?>
          <br>
          <div class="order_body">
            <span class="order_free_text">Միգրացիոն ծառայության Ապաստանի և իրավական ապահովման բաժնի պետ </span>
          </div>

        </div> <!-- closing div col-md-8 -->      
      
      </div>  <!-- closing div row--> 
         
    </div> <!--close 2nd col-md-12-->

</div>



</div>

 

 

<!-- Modal approve order -->
<div class="modal fade" id="order_approve_head" >
  
</div>

  
<!-- Modal check in -->
<div class="modal fade" id="checkin" >
  
</div>

<!-- Modal report done in -->
<div class="modal fade" id="report_done" >
  
</div>

<!-- Modal asign -->
<div class="modal fade" id="asign_order_modal" >
  
</div>

<!-- Modal create answer -->
<div class="modal fade" id="create_cencel_order_modal" >
  
</div>

<!-- Modal create answer -->
<div class="modal fade" id="check_out_modal" >
  
</div>


  <script>
$(document).ready(function(){
  $(".create_answer").click(function(){

     var order_id = $(this).attr('order_id');
     var case_id  = $(this).attr('order_case');

     $.ajax({
                 url:"config/config.php",
                 method:"POST",
                 data:{create_cencel:order_id, case_id_report:case_id},
                 success:function(data)
                 {  
                    console.log(case_id, order_id);
                    $('#create_cencel_order_modal').html(data);
                    $("#create_cencel_order_modal").modal({backdrop: "static"});
                  
                 } 
             });
  }); 

  $(".order_asign").click(function(){

     var order_id = $(this).attr('order_id');
     var case_id  = $(this).attr('order_case');

     $.ajax({
                 url:"config/config.php",
                 method:"POST",
                 data:{asign_order:order_id, case_id_report:case_id},
                 success:function(data)
                 {  
                    console.log(case_id, order_id);
                    $('#asign_order_modal').html(data);
                    $("#asign_order_modal").modal({backdrop: "static"});
                  
                 } 
             });
  }); 


  $(".report_on").click(function(){

     var order_id = $(this).attr('order_id');
     var case_id  = $(this).attr('order_case');

     $.ajax({
                 url:"config/config.php",
                 method:"POST",
                 data:{report_order:order_id, case_id_report:case_id},
                 success:function(data)
                 {  
                   // console.log(case_id, order_id);
                    $('#report_done').html(data);
                    $("#report_done").modal({backdrop: "static"});
                  
                 } 
             });
  }); 


  $(".approve_order").click(function(){

     var order_id = $(this).attr('order_id');
     var case_id  = $(this).attr('order_case');

     $.ajax({
                 url:"config/config.php",
                 method:"POST",
                 data:{dev_approve_order:order_id, case_id_order:case_id},
                 success:function(data)
                 {  
                   //console.log(order_id, case_id);
                     $('#order_approve_head').html(data);
                     $("#order_approve_head").modal({backdrop: "static"});
                  
                 } 
             });
  }); 



  $(".check_in_person").click(function(){
     var order_id = $(this).attr('check_id');
     var chekin_pers = $(this).attr('modalid');
     var case_id = $(this).attr('case_id');  
     var pers_num = $(this).attr('pers_count');

     $.ajax({
                 url:"config/config.php",
                 method:"POST",
                 data:{chekin_pers:chekin_pers, order_id:order_id, case_id:case_id, pers_num:pers_num},
                 success:function(data)
                 {  
                  console.log(pers_num);
                   $('#checkin').html(data);
                   $("#checkin").modal({backdrop: "static"});
                  
                 } 
             });
  }); 

   $(".check_out_person").click(function(){
     var order_id = $(this).attr('check_id');
     var chekin_pers = $(this).attr('modalid');
     var case_id = $(this).attr('case_id');  

     $.ajax({
                 url:"config/config.php",
                 method:"POST",
                 data:{check_out_pers:chekin_pers, order_id:order_id, case_id:case_id},
                 success:function(data)
                 {  
                  //console.log(order_id, chekin_pers, case_id);
                   $('#check_out_modal').html(data);
                   $("#check_out_modal").modal({backdrop: "static"});
                  
                 } 
             });
  }); 
 


  $(".check_out").click(function(){
   //$("#coi_answer").modal({backdrop: "static"});
     var order_id = $(this).attr('order_id');
    

     $.ajax({
                 url:"config/config.php",
                 method:"POST",
                 data:{cencel_order:order_id},
                 success:function(data)
                 {  
                   //console.log(order_id);
                     $('#cancel_order').html(data);
                     $("#cancel_order").modal({backdrop: "static"});
                  
                 } 
             });
  }); 

$(document).on("change", ".custom-file-input",function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});


  
 });
</script>

<script>
  function openCity(evt, cityName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}
// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();

</script>