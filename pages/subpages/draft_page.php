<?php

  require_once 'config/connect.php';
  require_once 'config/query_case.php';


  $case = $_GET['case'];
  $draft_id = $_GET['draft'];
  $u_id = $_SESSION['user_id'];
  $u_role = $_SESSION['role'];


  $sql_drafts = "SELECT a.draft_id, a.case_id, a.draft_file, a.autor, a.uploaded, a.deadline, a.receiver, a.draft_comment, a.actual, CONCAT(b.f_name, ' ', b.l_name) AS AUTOR_NAME, CONCAT(c.f_name, ' ', c.l_name) AS RECEIVER_NAME, e.sign_status 
  FROM tb_draft a 
  INNER JOIN users b ON a.autor = b.id 
  INNER JOIN users c ON a.receiver = c.id 
  INNER JOIN tb_process e ON a.case_id = e. case_id WHERE a.draft_id = $draft_id AND e.actual = 1";

  if(isset($_GET['notification_id'])){
    $note_id = $_GET['notification_id'];
    $query = "UPDATE tb_notifications SET comment_status = 1 WHERE comment_id = $note_id";
    $result_notify = $conn->query($query);
}

    $sql_draft_list = "SELECT a.draft_id, a.case_id, a.draft_file, a.autor, a.uploaded, a.deadline, a.receiver, a.draft_comment, a.actual, CONCAT(b.f_name, ' ', b.l_name) AS AUTOR_NAME, CONCAT(c.f_name, ' ', c.l_name) AS RECEIVER_NAME, e.comment_text FROM tb_draft a INNER JOIN users b ON a.autor = b.id INNER JOIN users c ON a.receiver = c.id LEFT JOIN tb_notifications e ON a.draft_id = e.draft_id WHERE a.case_id = $case";
    $result_list = $conn->query($sql_draft_list);

    
    $result_draft = $conn->query($sql_drafts);
    if ($result_draft->num_rows > 0) 
                        {
                        $row = $result_draft->fetch_assoc(); 
                        $draft_num = $row['draft_id'];
                        $from = $row['autor'];
                        $from_name = $row['AUTOR_NAME'];
                        $to = $row['receiver'];
                        $to_name = $row['RECEIVER_NAME'];
                        $draft_date = date("Y-m-d", strtotime($row['uploaded']));
                        $draft_deadline = date("Y-m-d", strtotime($row['deadline']));
                        $request_text = $row['draft_comment'];
                        $file_name = $row['draft_file'];
                        $sign_status = $row['sign_status'];
                                                
                        } 

?>

<input type="hidden" name="user" id="user_id" value="<?php echo $u_id ?>">

  <div class="btn_area"> 
  <div class="row">  


<div class="dropdown ml-2">
  <button class="btn btn-primary btn-sm">Գործառույթներ</button>
  <div class="dropdown-content">
                    <?php 
                        if(($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'viewer' || $_SESSION['role'] === 'devhead' || $_SESSION['role'] === 'head' || $_SESSION['role'] === 'officer' || $_SESSION['role'] === 'coispec' || $_SESSION['role'] === 'lawyer') && ($sign_status !== '14' && $sign_status == '8'))
                        {                    
                    ?> 
                            <a href="#" id="draft" modal_id="<?php echo $case ?>" modal_case="<?php echo $u_id ?>"> <i class="fas fa-paper-plane"></i> Պատասխանել </a>
                    <?php
                        }
                    ?>

                     <?php 
                        if(($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'viewer' || $_SESSION['role'] === 'devhead' || $_SESSION['role'] === 'head' || $_SESSION['role'] === 'officer' || $_SESSION['role'] === 'coispec' || $_SESSION['role'] === 'lawyer') && $sign_status == '14' )
                        {                    
                    ?> 

                    <a href="user.php?page=cases&homepage=case_page&case=<?php echo $case?>">Գործի էջ</a>

                       <?php
                        }
                    ?>
                   
                   
   
  </div>
</div>
  
</div>


</div>


        
<div class="case_area">
<div class="tab">
  <button class="tablinks" onclick="openCity(event, 'functions')" id="defaultOpen">Որոշման նախագիծ</button>
  <button class="tablinks" onclick="openCity(event, 'family')">Ընտանիքի կազմը</button>
  <button class="tablinks" onclick="openCity(event, 'files')">Նախագծի պատմություն</button>
</div>






<div id="functions" class="tabcontent">
  
  
    <div class="col-md-12" style="margin-top: 5px;">
      
     
        <div class="row">
            <div class="col-md-2">
              <label class="label_pers_page">Ուղարկող</label>
              <input class="form-control form-control-sm" value="<?php echo $from_name ?>" readonly>
            </div>
            
            <div class="col-md-2">
              <label class="label_pers_page">Նախագծի ամսաթիվ</label>
              <input type="date" name="request_date" class="form-control form-control-sm" value="<?php echo $draft_date ?>" readonly>
              

            </div>
           
           <!-- <div class="col-md-2">
              <label class="label_pers_page">Գործի վերջնաժամկետ</label>
              <input type="date" name="request_date" class="form-control form-control-sm" value="<?php echo $draft_deadline ?>" readonly>
            </div> -->

            <div class="col-md-2">
              <label class="label_pers_page">Իմ վերջնաժամկետ</label>
              <input type="date" name="request_date" class="form-control form-control-sm" value="<?php echo $draft_deadline ?>" readonly>
            </div>
            
        </div> 

          <div class="row">
          <div class="col-md-12">
            <textarea class="form-control" rows="12" readonly="readonly"><?php echo $request_text ?></textarea>
          </div>
          </div>
          
          <div class="row">
          <div class="col-md-12">
            
            <a href="uploads/draft/<?= $row['case_id'].'/'.$row['draft_file'] ?>" class="form-control"><?php echo $file_name ?></a>
          </div>
          </div>
          
      
     
    </div> <!--close 2nd col-md-12-->
  

</div>

<div id="files" class="tabcontent">
  <h5 class="sub_title" style="margin-top: 5px;">Նախագծի շրջանառման պատմություն</h5>
  <div class="row">
            <div class="col-md-12">
              <table class="table">
                <tr>
                  <th class="table_drafts">Վերբեռնման ամսաթիվ</th>
                  <th class="table_drafts">Հեղինակ</th>
                  <th class="table_drafts">Ստացող</th>
                  <th class="table_drafts">նախագիծ</th>
                  <th class="table_drafts">Հաղորդագրություն</th>
                </tr>
<?php 
  while($row_draft = mysqli_fetch_array($result_list)){

    $uploaded =  date("Y-m-d", strtotime($row_draft['uploaded']));
    $autor = $row_draft['AUTOR_NAME'];
    $reciver = $row_draft['RECEIVER_NAME'];
    $draft_file = $row_draft['draft_file'];
    $draft_comment = $row_draft['draft_comment'];

?>
                <tr>
                  <td><?php echo $uploaded ?></td>
                  <td><?php echo $autor ?></td>
                  <td><?php echo $reciver ?></td>
                  <td><a href="uploads/draft/<?= $row_draft['case_id'].'/'.$row_draft['draft_file'] ?>"><?php echo $draft_file ?></a></td>
                   <td><?php echo $draft_comment ?></td>
                </tr>
              <?php } ?>
              </table>
            </div>
          </div>
</div>  



<?php 
  $sql_all_person = "SELECT a.personal_id, a.case_id, a.f_name_arm, a.f_name_eng, a.l_name_arm, a.l_name_eng, a.m_name_arm, a.m_name_eng, a.b_day, a.b_month, a.b_year, a.sex, a.citizenship, a.previous_residence, a.citizen_adr, a.residence_adr, a.departure_from_citizen, a.departure_from_residence, a.arrival_date, a.doc_num, a.etnicity, a.religion,  a.preferred_traslator_sex, a.preferred_interviewer_sex, a.invalid, a.pregnant, a.seriously_ill, a.trafficking_victim, a.violence_victim, a.comment, a.illegal_border, a.transfer_moj, a.deport_prescurator, a.role, b.der 
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
        <th class="role">դերը</th>
        <th class="fam_members">ա․ա․հ․</th>
        <th class="role">սեռ</th>
        <th class="role">տարիք</th>
        <th class="role">հաշմանդամ</th>
        <th class="role">հիվանդ</th>
        <th class="role">հղի</th>
        <th class="role">թրաֆ. զոհ</th>
        <th class="role">բռն. զոհ</th>
         <th class="role">...</th>
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
        <td class="family_members_td"><?= $row_all['der'] ?></td>
        <td class="family_members_td"><?= $row_all['l_name_arm'] .' '. $row_all['f_name_arm'] ?></td>   
        <td class="family_members_td"><?php echo $sex ?></td>
        <td class="family_members_td"><?php echo $age ?></td>
        <td class="family_members_td"><input type="checkbox" class="form-check-input" name="invalid" <?php echo $invalid ?> onclick="return false;"> </td>
        <td class="family_members_td"><input type="checkbox" class="form-check-input" name="pregnant"  <?php echo $ill?> onclick="return false;"></td>
        <td class="family_members_td"><input type="checkbox" class="form-check-input" name="ill"  <?php echo $pregnant?> onclick="return false;"> </td>
        <td class="family_members_td"> <input type="checkbox" class="form-check-input" name="trafiking" <?php echo $trafficking_victim?> onclick="return false;"></td>
        <td class="family_members_td"><input type="checkbox"  class="form-check-input" name="violence_victim" <?php echo $violence_victim ?> onclick="return false;"></td>
        <td style="text-align: center;">
        
          <a href="#" class="pers_modal"  modalid="<?php echo $row_all['personal_id'] ?>"> <i class="far fa-edit" style="color: green; font-size: 1.5em;"></i> </a>

        </td>
      </tr>
    <?php } ?>
    </table>
    </div>
  </div>
</div>  
</div>

 




 <!-- The Modal personal info-->
  <div class="modal fade" id="myModal" >
         
       
  </div>
  
<!-- Modal coi response -->
<div class="modal fade" id="draft_modal" >
  
</div>





  <script>
$(document).ready(function(){
  $("#draft").click(function(){
  
     var case_id = $(this).attr('modal_id');
     var sender  = $(this).attr('modal_case');  
    
     $.ajax({
                 url:"config/config.php",
                 method:"POST",
                 data:{case_draft:case_id, sender:sender},
                 success:function(data)
                 {  
                   console.log(case_id);
                    $('#draft_modal').html(data);
                    $("#draft_modal").modal({backdrop: "static"});
                  
                 } 
             });
  }); 
 

$(".pers_modal").click(function(){
    
    var pers_id = $(this).attr('modalid');
    $.ajax({
                url:"config/config.php",
                method:"POST",
                data:{person_modal:pers_id, pers_id: pers_id},
                success:function(data)
                {  
                  console.log(pers_id);
                   $('#myModal').html(data);
                   $("#myModal").modal({backdrop: "static"});
                    
                } 
            });
      });

$(document).on("change", ".custom-file-input",function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
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