<?php

  require_once 'config/connect.php';
  require_once 'config/query_case.php';


  $case = $_GET['case'];
  $coi = $_GET['coi'];
  $u_id = $_SESSION['user_id'];


  if(isset($_GET['notification_id'])){
    $note_id = $_GET['notification_id'];
    $query = "UPDATE tb_notifications SET comment_status = 1 WHERE comment_id = $note_id";
    $result_notify = $conn->query($query);
}

    $sql_coi_list = "SELECT * FROM tb_coi a INNER JOIN users b ON a.from_officer = b.id INNER JOIN tb_country c ON a.coi_state = c.country_id WHERE case_id = $case";
    $result_list = $conn->query($sql_coi_list);

    $sql_coi = "SELECT * FROM tb_coi a INNER JOIN users b ON a.from_officer = b.id INNER JOIN tb_country c ON a.coi_state = c.country_id LEFT JOIN tb_notifications d ON a.coi_id = d.coi_id  WHERE a.coi_id = $coi";
    $result_coi = $conn->query($sql_coi);
    if ($result_coi->num_rows > 0) 
                          {
                        $row = $result_coi->fetch_assoc(); 
                        $coi_num = $row['coi_id'];
                        $from = $row['from_officer'];
                        $from_name = $row['f_name'] . ' ' . $row['l_name'];
                        $request_date = $row['request_date'];
                        $request_deadline = $row['request_deadline'];
                        $description = $row['description'];
                        $request_text = $row['request_text'];
                        $coi_state = $row['coi_state'];
                        $country = $row['country_arm'];
                        $sms = $row['comment_text'];
                        $request_type = '';
                        if($row['request_type'] == 1){
                          $request_type = 'առաջնային';
                        }
                        else {
                          $request_type = 'կրկնակի';
                        }
                        } 

?>

<input type="hidden" name="user" id="user_id" value="<?php echo $u_id ?>">

  <div class="btn_area"> 
  <div class="row">  


<div class="dropdown ml-2">
  <button class="btn btn-primary btn-sm">Գործառույթներ</button>
  <div class="dropdown-content">
                    <?php 
                        if($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'coispec')
                        {                    
                    ?> 
                            <a href="#" id="answer_coi" coi_id="<?php echo $coi_num?>" >Պատասխանել</a>
                            
                    <?php
                        }
                    ?>

                   
   
  </div>
</div>
</div>
</div>


        
<div class="case_area">
<div class="tab">
  <button class="tablinks" onclick="openCity(event, 'functions')" id="defaultOpen">ԾԵՏ հարցում</button>
  <button class="tablinks" onclick="openCity(event, 'family')">Ընտանիքի կազմը</button>
  <button class="tablinks" onclick="openCity(event, 'coi')">Հարցումների պատմություն </button>
</div>












<div id="functions" class="tabcontent">
  
  
    <div class="col-md-12">
      
      <h5 class="sub_title" style="margin-top: 5px;">Հարցման մանրամասներ </h5> 
        <div class="row">
            <div class="col-md-3">
              <label class="label_pers_page">Ուղարկող</label>
              <input class="form-control form-control-sm" value="<?php echo $from_name ?>" readonly>
            </div>
            <div class="col-md-3">
              <label class="label_pers_page">Ծագման երկիր</label>
              <input type="text" name="request_date" class="form-control form-control-sm" value="<?php echo $country ?>" readonly>
            </div>
            <div class="col-md-2">
              <label class="label_pers_page">Հարցման ամսաթիվ</label>
              <input type="date" name="request_date" class="form-control form-control-sm" value="<?= $row['request_date'] ?>" readonly>
            </div>
            <div class="col-md-2">
              <label class="label_pers_page">Վերջնաժամկետ</label>
              <input type="date" name="request_date" class="form-control form-control-sm" value="<?= $row['request_deadline'] ?>" readonly>
            </div>
            <div class="col-md-2">
              <label class="label_pers_page">Հարցման տեսակ</label>
              <input type="text" name="request_date" class="form-control form-control-sm" value="<?php echo $request_type ?>" readonly>
            </div>
            
        </div> 

          <div class="row">
            <div class="col-md-6">
            <label class="label_pers_page">Նկարագրություն </label>
            <textarea class="form-control" rows="12" readonly="readonly"><?php echo $description ?></textarea>
            </div>
            <div class="col-md-6">
            <label class="label_pers_page">Հարցադրումներ </label>
            <textarea class="form-control" rows="12" readonly="readonly"><?php echo $request_text ?></textarea>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <label class="label_pers_page">Հաղորդագրություն</label>
              <textarea class="form-control" rows="2" readonly="readonly"><?php echo $sms ?> </textarea>
            </div>
          </div>
      
      
      
     
    </div> <!--close 2nd col-md-12-->
  

</div>





<?php 
  $sql_all_person = "SELECT a.personal_id, a.case_id, a.f_name_arm, a.f_name_eng, a.l_name_arm, a.l_name_eng, a.m_name_arm, a.m_name_eng, a.b_day, a.b_month, a.b_year, a.sex, a.citizenship, a.previous_residence, a.citizen_adr, a.residence_adr, a.departure_from_citizen, a.departure_from_residence, a.arrival_date, a.doc_num, a.etnicity, a.religion,  a.preferred_traslator_sex, a.preferred_interviewer_sex, a.invalid, a.pregnant, a.seriously_ill, a.trafficking_victim, a.violence_victim, a.comment, a.illegal_border, a.wanted_moj, a.wanted_court, a.role, b.der 
FROM tb_person a 
INNER JOIN tb_role b ON b.role_id = a.role

WHERE a.case_id = $case";
  
  $result_all_person = $conn->query($sql_all_person);
  


?>

<div id="family" class="tabcontent">
  <div class="col-md-12">
  <div class="row">
    <h5 class="sub_title" style="margin-top: 5px;">Ընտանիքի կազմը</h5>
    <table class="table table-stripped table-bordered">
      <tr>
        <th class="role">անձ. #</th>
        <th class="role">դերը</th>
        <th class="fam_members">ա․ա․հ․</th>
        <th class="role">սեռ</th>
        <th class="role">տարիք</th>
        <th class="role">հաշմանդամ</th>
        <th class="role">հիվանդ</th>
        <th class="role">հղի</th>
        <th class="role">թրաֆ. զոհ</th>
        <th class="role">բռն. զոհ</th>
       </tr>
       
       <?php 
       while ($row_all = mysqli_fetch_array($result_all_person)) {
        
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
        ?>

       <tr>
       <td><button type="button" class="btn btn-primary btn-sm pers_modal"  modalid="<?php echo $row_all['personal_id'] ?>">
    Open modal </button></td>
        <td class="family_members_td"><?= $row_all['der'] ?></td>
        <td class="family_members_td"><?= $row_all['l_name_arm'] .' '. $row_all['f_name_arm'] ?></td>
        
        <td class="family_members_td"><?php echo $sex ?></td>
        <td class="family_members_td"><?php echo $age ?></td>
        <td class="family_members_td"><input type="checkbox" class="form-check-input" name="invalid" <?php echo $invalid ?> onclick="return false;"> </td>
        <td class="family_members_td"><input type="checkbox" class="form-check-input" name="pregnant"  <?php echo $ill?> onclick="return false;"></td>
        <td class="family_members_td"><input type="checkbox" class="form-check-input" name="ill"  <?php echo $pregnant?> onclick="return false;"> </td>
        <td class="family_members_td"> <input type="checkbox" class="form-check-input" name="trafiking" <?php echo $trafficking_victim?> onclick="return false;"></td>
        <td class="family_members_td"><input type="checkbox"  class="form-check-input" name="violence_victim" <?php echo $violence_victim?> onclick="return false;"></td>
      </tr>
    <?php } ?>
    </table>
    </div>
  </div>
</div>  
 
<div id="coi" class="tabcontent">
 <div class="col-md-12">
  <div class="row">
    <h5 class="sub_title" style="margin-top: 5px;">ԾԵՏ հարցումների պատմություն </h5> 
    <?php 
     $msg = '
      <div class="col-md-12" style="align-text: center;">
        <br>
        <h5> ԾԵՏ հարցումներ չեն կատարվել</h5>
      </div>
     ';
      if ($result_list->num_rows > 0) {
    ?>
    <table class="table table-stripped table-bordered">
      <tr>
        <th class="coi_table" width="10%">Կարգավիճակ</th>
        <th class="coi_table" width="15%">ԾԵՏ մասնագետ</th>
        <th class="coi_table" width="15%">Հարցման ամսաթիվ</th>
        <th class="coi_table" width="15%">Վերջնաժամկետ</th>
        <th class="coi_table" width="15%">Պատասխանի ամսաթիվ</th>
        <th class="coi_table">Կից նյութ</th>
      </tr>
        <?php 
       
          while ($row_coi = mysqli_fetch_array($result_list)) {
          $coi_request_date = date("d.m.Y", strtotime($row_coi['request_date']));
          $coi_deadlie_date = date("d.m.Y", strtotime($row_coi['request_deadline']));
          
          if (empty($row_coi['response_date'])) {
            $coi_response_date = "";  
          }
          else {
          $coi_response_date = date("d.m.Y", strtotime($row_coi['response_date']));}
          
          $coi_status = '';
          if ($row_coi['coi_status'] == '0') {
            $coi_status = 'ընթացիկ';
          }
          else {
            $coi_status = 'ավարտված';
          }

          $attach_coi = $row_coi['file_name'];
        
        ?>
      <tr>
        <td><?php echo $coi_status ?></td>
        <td><?= $row_coi['f_name'] .' '. $row_coi['l_name'] ?></td>
        <td><?php echo $coi_request_date ?></td>
        <td><?php echo $coi_deadlie_date ?></td>
        <td><?php echo $coi_response_date ?></td>
        <td><a href="uploads/<?= $row_coi['case_id'].'/'.$row_coi['file_name'] ?>" download> <i class="fa fa-download" aria-hidden="true"></i>  <?= $row_coi['file_name'] ?>  </a></td>
      </tr>

      <?php
}
}
else {
  echo $msg;
}
      ?>
    </table>
    </div>
  </div>
</div>  



 <!-- The Modal personal info-->
  <div class="modal fade" id="myModal" >
         
       
  </div>
  
<!-- Modal coi response -->
<div class="modal fade" id="coi_answer" >
  
</div>





  <script>
$(document).ready(function(){
  $("#answer_coi").click(function(){
   //$("#coi_answer").modal({backdrop: "static"});
     var coi_num = $(this).attr('coi_id');
       

     $.ajax({
                 url:"config/config.php",
                 method:"POST",
                 data:{coi_answer:coi_num},
                 success:function(data)
                 {  
                    $('#coi_answer').html(data);
                    $("#coi_answer").modal({backdrop: "static"});
                  
                 } 
             });
  }); 
 

$(".pers_modal").click(function(){
    // $("#myModal").modal({backdrop: "static"});
    var pers_id = $(this).attr('modalid');
    $.ajax({
                url:"config/config.php",
                method:"POST",
                data:{person_modal:pers_id},
                success:function(data)
                {  

                   $('#myModal').html(data);
                   $("#myModal").modal({backdrop: "static"});
                    
                } 
            });
      });

});


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

   $(document).ready(function(){
   // Country dependent ajax
   $(document).on("change","#select_marz",function(){
   var marzId = $(this).val();

   if (marzId) {
    $.ajax({
      url :"pages/subpages/action.php",
    type:"POST",
    cache:false,
    data:{marzId:marzId},
    success:function(data){
      $("#select_community").html(data);
      $('#select_setl').html('<option value="">Նշե՛ք համայնքը</option>');
  }
    });
   }else{
  $('#select_community').html('<option value="">Նշե՛ք մարզը</option>');
    $('#select_setl').html('<option value="">Ընտրե՛ք բնակավայրը</option>');
   }
});

// state dependent ajax
 $(document).on("change","#select_community", function(){
   var bnakId = $(this).val();

  if (bnakId) {
           $.ajax({
    url :"pages/subpages/action.php",
    type:"POST",
    cache:false,
    data:{bnakId:bnakId},
          success:function(data){
       $("#select_setl").html(data);
         }
     });
  }else{
           $('#select_setl').html('<option value="">Նշե՛ք համայնքը/option>');
  } 
     });
 });
</script>