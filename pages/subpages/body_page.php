<?php

  require_once 'config/connect.php';
  require_once 'config/query_case.php';


  $case = $_GET['case'];
  $request_id = $_GET['request'];
  $u_id = $_SESSION['user_id'];
  $u_role = $_SESSION['role'];
  $request_process_id = '';
  
  if($u_role == 'nss'){
    $body = '1';
  }
  if($u_role == 'police'){
    $body = '2';
  }

  if(isset($_GET['notification_id'])){
    $note_id = $_GET['notification_id'];
    $query = "UPDATE tb_notifications SET comment_status = 1 WHERE comment_id = $note_id";
    $result_notify = $conn->query($query);
}

if(isset($_GET['request'])){
    $make_request_read = "UPDATE tb_request_process SET request_read = 1 WHERE request_id = $request_id";
    $result_make_read = $conn->query($make_request_read);
}

$find_request_process_id = "SELECT * FROM tb_request_process a WHERE a.request_id = $request_id AND request_actual = 1";
$result_find_request_process_id = $conn->query($find_request_process_id);

   if($result_find_request_process_id->num_rows > 0){
    $row_request_process_id = $result_find_request_process_id->fetch_assoc();
    $request_process_id = $row_request_process_id['request_process_id'];
   }

  $sql_request = "SELECT a.request_id, a.case_id, a.author, a.body, a.request_date, a.request_status, b.request_process_id, b.request_id, b.request_user_to, b.request_actual, b.process_date, b.process_date, b.process_status, b.process_comment AS PROC_SMS, c.request_process_status_id, c.request_process_status, d.file_name, d.request_process_id, d.file_type, e.file_type, f.personal_id, f.f_name_arm, f.l_name_arm, f.m_name_arm, f.f_name_eng, f.l_name_eng, f.m_name_eng, f.b_day, f.b_month, f.b_year, f.sex, f.arrival_date, f.doc_num, f.etnicity, f.religion, f.invalid, f.pregnant, f.seriously_ill, f.trafficking_victim, f.violence_victim, f.illegal_border, f.transfer_moj, f.deport_prescurator, f.citizenship, f.role, g.country_arm, h.f_name AS AUTOR_NAME, h.l_name AS AUTOR_LNAME, i.body AS AUTORITY, i.body_id, j.religion_arm, k.etnic_eng 
FROM tb_request_out a 
INNER JOIN tb_request_process b ON a.request_id = b.request_id
INNER JOIN tb_request_process_status c ON c.request_process_status_id = b.process_status
INNER JOIN files d ON d.case_id = a.case_id
LEFT JOIN tb_file_type e ON e.file_type_id = d.file_type
INNER JOIN tb_person f ON f.case_id = a.case_id
INNER JOIN tb_country g ON g.country_id = f.citizenship
INNER JOIN users h ON h.id = a.author
INNER JOIN tb_request_bodies i ON i.body_id = a.body
INNER JOIN tb_religions j ON f.religion = j.religion_id
INNER JOIN tb_etnics k ON f.etnicity = k.etnic_id


WHERE f.role = 1 AND a.case_id = $case AND a.request_id = $request_id AND d.request_process_id = $request_process_id AND b.request_actual = 1";

$result_request = $conn->query($sql_request);

if($result_request->num_rows > 0){
  $row_request = $result_request->fetch_assoc();

  $full_name_arm          = $row_request['f_name_arm'] .' '. $row_request['l_name_arm'];
  $full_name_eng          = $row_request['f_name_eng'] .' '. $row_request['l_name_eng'];
  $citizenship            = $row_request['country_arm'];
  $doc_num                = $row_request['doc_num'];
  $bday                   = $row_request['b_day'] .'.'. $row_request['b_month'] .'.'.$row_request['b_year'];
  $sex_text               = '';
    if($row_request['sex'] == 1){
      $sex_text           = 'արական';
    }
    if($row_request['sex'] == 2){
      $sex_text           = 'իգական';
    }
  $etnics                 = $row_request['etnic_eng'];
  $religion               = $row_request['religion_arm'];
  $arrival_date           = '';
    if($row_request['arrival_date'] == 0){
      $arrival_date           = 'տվյալ չկա';
    }
    else{
      $arrival_date           = date('d.m.Y', strtotime($row_request['arrival_date']));
    }
  
  $deport_moj             = '';
    if ($row_request['transfer_moj'] == '1') {
        $deport_moj = 'checked';
      }

  $deport_prosecutor      = '';
    if ($row_request['deport_prescurator'] == '1') {
        $deport_prosecutor = 'checked';
      }
  $illegal_border         = '';
    if ($row_request['illegal_border'] == '1') {
        $illegal_border = 'checked';
      }
  $invalid                = '';
    if ($row_request['invalid'] == '1') {
        $invalid = 'checked';
      }
  $pregnant               = '';
    if ($row_request['pregnant'] == '1') {
        $pregnant = 'checked';
      }
  $seriously_ill          = '';
    if ($row_request['seriously_ill'] == '1') {
        $seriously_ill = 'checked';
      }
  $trafficking_victim     = '';
    if ($row_request['trafficking_victim'] == '1') {
        $trafficking_victim = 'checked';
      }
  $violence_victim        = '';
    if ($row_request['violence_victim'] == '1') {
        $violence_victim = 'checked';
      }  
  $requet_id              = $row_request['request_id'];
  $request_process_date   = date('d.m.Y', strtotime($row_request['process_date']));
  $request_proc_status    = $row_request['request_process_status'];
  $request_proc_status_id = $row_request['process_status'];
  $request_sms            = $row_request['PROC_SMS'];
  $body_text              = $row_request['AUTORITY'];
  $filename               = $row_request['file_name'];
  $request_status         = $row_request['request_status'];

}  

      $select_files = "SELECT * FROM files a INNER JOIN tb_file_type b ON a.file_type = b.file_type_id WHERE a.case_id = $case AND b.file_filter = 1";
      $result_file = $conn->query($select_files);

       $sql_personal_files = "SELECT a.id, a.file_name, a.uploaded_on, a.file_type, a.uploader, a.case_id, a.person_id, b.file_type AS FILE_TEXT, c.f_name_arm, c.l_name_arm 
        FROM files a 
        LEFT JOIN tb_file_type b ON a.file_type = b.file_type_id
        LEFT JOIN tb_person c ON a.person_id = c.personal_id 
        WHERE a.case_id = $case AND b.file_filter = 2";
    $result_personal_files = $conn->query($sql_personal_files);

       $sql_out_members = "SELECT a.member_id, a.case_id, a.f_name_arm, a.f_name_eng, a.l_name_arm, a.l_name_eng, a.m_name_arm, a.m_name_eng, a.b_day, a.b_month, a.b_year, a.sex, a.citizenship, a.residence, a.role, b.country_arm AS CITIZEN, c.country_arm AS RES, d.der AS DER FROM tb_members a LEFT JOIN tb_country b ON a.citizenship = b.country_id LEFT JOIN tb_country c ON a.residence = c.country_id INNER JOIN tb_role d ON a.role = d.role_id WHERE a.case_id = $case"; 

    $result_out_members = $conn->query($sql_out_members);

?>

<input type="hidden" name="user" id="user_id" value="<?php echo $u_id ?>">

  <div class="btn_area"> 
  <div class="row">  


<div class="dropdown ml-2">
  <button class="btn btn-primary btn-sm">Գործառույթներ</button>
  <div class="dropdown-content">
                    <?php 
                        if(($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'police' || $_SESSION['role'] === 'nss') && $request_status != '1')
                        {                    
                    ?> 
                            <a href="#" id="answer_response" request_id="<?php echo $request_id?>" request_process_id="<?= $row_request['request_process_id'] ?>" case_id="<?php echo $case ?>">Պատասխանել</a>
                            
                    <?php
                        }
                    ?>
                    <?php 
                        if($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'devhead')
                        {                    
                    ?> 
                            <a href="#" id="approve_request" request_id="<?php echo $request_id?>" rec_body="<?= $row_request['body_id'] ?>" case_id="<?php echo $case ?>" >Հաստատել</a>
                            
                    <?php
                        }
                    ?>

                     <?php 
                        if(($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'devhead' || $_SESSION['role'] === 'officer' || $_SESSION['role'] === 'coispec' || $_SESSION['role'] === 'lawyer') && $request_proc_status_id == '4')
                        {                    
                    ?> 
                            <a href="user.php?page=cases&homepage=case_page&case=<?php echo $case?>" > Գործի էջ</a>
                            
                    <?php
                        }
                    ?>

                   
   
  </div>
</div>
</div>
</div>


        
<div class="case_area">
<div class="tab">
  <button class="tablinks" onclick="openCity(event, 'functions')" id="defaultOpen">Հարցում</button>
  <button class="tablinks" onclick="openCity(event, 'family')">Ընտանիքի կազմը</button>
  <button class="tablinks" onclick="openCity(event, 'files')">Կցված ֆայլեր</button>
  <button class="tablinks" onclick="openCity(event, 'coi')">Հարցումների պատմություն</button>
</div>


<div id="coi" class="tabcontent">
  <div class="col-md-12">
    <div class="row">
        
        <h5 class="sub_title" style="margin-top: 5px;">Հարցումների պատմություն </h5>

        <?php 

       
        $sql_request = "SELECT a.request_process_id, a.request_id, a.user_from, a.request_user_to, a.request_actual, a.process_date, a.process_status, a.process_comment, b.case_id, b.author, b.body, b.request_date, b.request_status, c.body_id, c.body, d.request_process_status_id, d.request_process_status, e.file_name, e.file_type, f.file_type 
FROM tb_request_process a 
INNER JOIN tb_request_out b ON a.request_id = b.request_id 
INNER JOIN tb_request_bodies c ON b.body = c.body_id
INNER JOIN tb_request_process_status d ON a.process_status = d.request_process_status_id
INNER JOIN files e ON a.request_process_id = e.request_process_id
INNER JOIN tb_file_type f ON e.file_type = f.file_type_id
WHERE b.case_id = $case AND b.body = $body AND a.process_status NOT IN (1,2)";
    $result_request = $conn->query($sql_request);
     $msg = '
      <div class="col-md-12" style="align-text: center;">
        <br>
        <h5> Հարցումներ չեն կատարվել</h5>
      </div>
     ';
    if ($result_request->num_rows > 0) {
    ?>
    <table class="table table-stripped table-bordered">
      <tr>
        <th class="coi_table" width="15%">Կարգավիճակ</th>
        <th class="coi_table" width="15%">Մարմին</th>
        <th class="coi_table" width="15%">Հարցման ամսաթիվ</th>
        <th class="coi_table" width="15%">Գործառույթի ամսաթիվ</th>
        <th class="coi_table" width="15%">Փաստաթղթի տեսակ</th>
        <th class="coi_table">Կից նյութ</th>
      </tr>
        <?php 
       
          while ($row_request = mysqli_fetch_array($result_request)) {
          $request_date = date("d.m.Y", strtotime($row_request['request_date']));
                    
         
          $response_date = date("d.m.Y", strtotime($row_request['process_date']));
          
          $request_status = $row_request['request_process_status'];
         

          $body = $row_request['body'];
          
         
          $file_type = $row_request['file_type'];
          $attach = $row_request['file_name'];
        
        ?>
      <tr>
        <td><?php echo $request_status ?></td>
        <td><?php echo $body ?></td>
        <td><?php echo $request_date ?></td>
        <td><?php echo $response_date ?></td>
        <td><?php echo $file_type ?>
        <td><a href="uploads/<?= $row['case_id'].'/'.'requests/'.$row_request['file_name'] ?>" download> <i class="fa fa-download" aria-hidden="true"></i> <?php echo $attach ?> </a></td>
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


<div id="files" class="tabcontent">
  <div class="col-md-12">
  <div class="row">
    <div class="col-md-6">
     <h5 class="sub_title" style="margin-top: 5px;">Գործին առնչվող փաստաթղթեր </h5>  
                 <table class="table">
                  <thead>
                   <tr>
                     <th class="table_a2">տեսակ</th>
                     <th class="table_a2">վերբեռնման ամսաթիվ</th>
                     <th class="table_a2">ներբեռնել</th>
                     <th class="table_a2">...</th>
                   </tr>
                   </thead>
                   
                   <?php
                   while ($row_files = mysqli_fetch_array($result_file)) {
                    $file_type = $row_files['file_type'];
                    $uploaded = $row_files['uploaded_on'];
                    $uploaded = date("d.m.Y", strtotime($row_files['uploaded_on']));
                    $file_name = $row_files['file_name'];
                    $case_id = $row_files['case_id'];
                    $file_id = $row_files['id'];
                   ?>
                   <tr>
                     <td><?php echo $file_type ?></td>
                     <td><?php echo $uploaded ?></td>
                     <td><a href="uploads/<?= $row_files['case_id'].'/'.$row_files['file_name'] ?>" download> <i class="fa fa-download" aria-hidden="true"></i>  <?php echo $file_name ?>  </a></td>
                     <td> <a href="#" class="delete_file" modal_id="<?php echo $case_id ?>" delete_id="<?php echo $file_id ?>" > <i class="fas fa-trash-alt"></i></a></td>
                   </tr>
               
                <?php } ?>
            </table>
            
    </div> <!--close col-md-6-->
    <div class="col-md-6">
      <h5 class="sub_title" style="margin-top: 5px;">Անձանց առնչվող փաստաթղթեր</h5>
                    <table class="table" id="person_file_table">
                        <thead>
                            <tr>
                                <th class="table_a2">ա.ա.հ.</th>
                                <th class="table_a2">տիպ</th>
                                <th class="table_a2">փաստաթուղթ</th>
                                <th class="table_a2">...</th>
                            </tr>
                        </thead>
                        
                            <?php 
                            while($row_person_file =  $result_personal_files->fetch_assoc()){
                                $file_id   = $row_person_file['id'];
                                $full_name = $row_person_file['f_name_arm'] .' '. $row_person_file['l_name_arm'];
                                $file_type = $row_person_file['FILE_TEXT'];
                                $file_name = $row_person_file['file_name'];
                                $case_id   = $row_person_file['case_id'];
                                $pers_id   = $row_person_file['person_id'];
                            ?>
                            <tr>
                                <td><?php echo $full_name ?>  </td>
                                <td><?php echo $file_type ?>  </td>
                                <td><a href="uploads/<?= $case_id.'/'. $pers_id .'/'.$file_name ?>" download> <i class="fa fa-download" aria-hidden="true"></i>  <?php echo $file_name ?>  </a>  </td>
                                <td><a href="#" class="delete_file" modal_id="<?php echo $case_id ?>" delete_id="<?php echo $file_id ?>" > <i class="fas fa-trash-alt"></i></a> </td>
                            </tr>    
                            <?php } ?>
                    </table>
         
    </div><!--close col-md-6-->
  </div> <!--close row-->
</div> <!--close col-md-12-->
</div>  








<div id="functions" class="tabcontent">
    
    <div class="col-md-12">
  
    <div class="row">
  <div class="col-md-8">
    <h5 class="sub_title" style="margin-top: 5px;">Գլխավոր դիմումատու </h5>
    <div class="row">
      <div class="col-md-6">
        <label class="label_pers_page">Ապաստան հայցողի ա.ա.հ. (հայատառ)</label>
        <input type="text" class="form-control" value="<?php echo $full_name_arm  ?>" readonly>
      </div>
      <div class="col-md-6">
        <label class="label_pers_page">Ապաստան հայցողի ա.ա.հ. (լատինատառ)</label>
        <input type="text" class="form-control" value="<?php echo $full_name_eng ?>" readonly>
      </div>
      <div class="col-md-6">
        <label class="label_pers_page">Քաղաքացիությունը</label>
        <input type="text" class="form-control" value="<?php echo $citizenship ?>" readonly>
      </div>
      <div class="col-md-3">
        <label class="label_pers_page">Անձնագրի #</label>
        <input type="text" class="form-control" value="<?php echo $doc_num ?>" readonly>
      </div>
      <div class="col-md-3">
        <label class="label_pers_page">Ծննդյան ամսաթիվը</label>
        <input type="text" class="form-control" value="<?php echo $bday ?>" readonly>
      </div>
      <div class="col-md-3">
        <label class="label_pers_page">Սեռը</label>
        <input type="text" class="form-control" value="<?php echo $sex_text ?>" readonly>
      </div>
      <div class="col-md-3">
        <label class="label_pers_page">Ազգությունը</label>
        <input type="text" class="form-control" value="<?php echo $etnics ?>" readonly>
      </div>
      <div class="col-md-3">
        <label class="label_pers_page">Կրոնը</label>
        <input type="text" class="form-control" value="<?php echo $religion ?>" readonly>
      </div>
      <div class="col-md-3">
        <label class="label_pers_page">ՀՀ ժամանման ամսաթիվը</label>
        <input type="text" class="form-control" value="<?php echo $arrival_date ?>" readonly>
      </div>
    </div>

    <h5 class="sub_title" style="margin-top: 2px;">Հատուկ նշումներ </h5>   
    <div class="row">
      <div class="col-md-3" style="text-align:center;">
        <label class="label_pers_page">Ապօրինի սահմանահատում</label>
        <input type="checkbox" name="invalid" <?php echo $illegal_border ?> class="form-control" onclick="return false;">
      </div>
      <div class="col-md-3" style="text-align:center;">
        <label class="label_pers_page">Արտահանձ. (Դատախազ.)</label>
        <input type="checkbox" name="invalid" <?php echo $deport_prosecutor ?> class="form-control" onclick="return false;">
      </div>
      <div class="col-md-3" style="text-align:center;">
        <label class="label_pers_page">Փոխանցում (Արդնախ.)</label>
       <input type="checkbox" name="invalid" <?php echo $deport_moj ?> class="form-control" onclick="return false;">
      </div>
      <div class="col-md-3" style="text-align:center;">
        <label class="label_pers_page">ՔԿՀ-ից</label>
        <input type="checkbox" name="invalid"  class="form-control" onclick="return false;">
      </div>
    </div>
    <h5 class="sub_title" style="margin-top: 1px;">Հատուկ կարիքներ </h5>
    <table class="table">
          <tr>
            <th class="special_needs_person">Հաշմանդամ</th>
            <th class="special_needs_person">Հղի կին</th>
            <th class="special_needs_person">Ծանր հիվանդ</th>
            <th class="special_needs_person">Թրաֆիքինգի զոհ</th>
            <th class="special_needs_person">Բռնության զոհ</th>
          </tr>
          <tr>
            <td><input type="checkbox" name="invalid" <?php echo $invalid ?> class="form-control" onclick="return false;"></td>
            <td><input type="checkbox" name="invalid" <?php echo $pregnant ?> class="form-control" onclick="return false;"></td>
            <td><input type="checkbox" name="invalid" <?php echo $seriously_ill ?> class="form-control" onclick="return false;"></td>
            <td><input type="checkbox" name="invalid" <?php echo $trafficking_victim ?> class="form-control" onclick="return false;"></td>
            <td><input type="checkbox" name="invalid" <?php echo $violence_victim ?> class="form-control" onclick="return false;"></td>
          </tr>          
        </table>
  </div>

  

   <div class="col-md-4">
      <h5 class="sub_title" style="margin-top: 5px;">Գործի մասին </h5> 
      
      <table class="table">
        <tr>
          <th class="label_pers_page">Գործ #</th>
          <td><?php echo $case ?></td>
        </tr>
        <tr>
          <th class="label_pers_page">Հարցում #</th>
          <td><?php echo $request_id ?> </td>
        </tr>
        <tr>
          <th class="label_pers_page">Գործի կարգավիճակ</th>
          <td><?php echo $case_status_text ?></td>
        </tr>
        <tr>
          <th class="label_pers_page">Հարցման կարգավիճակ</th>
          <td><?php echo $request_proc_status ?> </td>
        </tr>
        <tr>
          <th class="label_pers_page">Հարցման ամսաթիվ</th>
          <td><?php echo $request_process_date ?> </td>
        </tr>
        <tr>
          <th class="label_pers_page">Գերատեսչություն</th>
          <td><?php echo $body_text ?> </td>
        </tr>
        
      </table>

      <div class="col-md-12">
        <label class="label_pers_page">Հաղորդագրություն</label>
        <textarea class="form-control" rows="2" readonly="readonly"><?php echo $request_sms ?></textarea>
       
      </div>

      <h5 class="sub_title" style="margin-top: 5px;">Գրություն</h5>
      <a href="uploads/<?php echo $case.'/requests/'.$filename ?>" class="form-control form-control-sm" download ><?php echo $filename?></a>
              
      </div> <!-- closing div col-md-4 -->
    

  </div> <!-- closing 1st row -->

  
  
  </div> <!-- closing md-12 -->

  
    
  

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
        <th class="role">ավելին</th>
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
        
          <a href="#" class="pers_modal"  modal_id="<?php echo $row_all['personal_id'] ?>"> <i class="far fa-edit" style="color: green; font-size: 1.5em;"></i> </a>

        </td>
      </tr>
    <?php } ?>
    </table>
    </div>

     <br />
    <hr />
    <br />
    <div class="row">
      <h5 class="sub_title" style="margin-top: 5px;">Ընտանիքի անդամներ որոնք հայցում ընդգրկված չեն </h5> 

    


      <table class="table table-stripped table-bordered">
        <tr>
            <th class="role">դերը</th>
            <th class="role">անուն</th>
            <th class="role">ազգանուն</th>
            <th class="role">հայրանուն</th>
            <th class="role">ծննդյան ամսաթիվ</th>
            <th class="role">քաղաքացիությունը</th>
            <th class="role">բնակության երկիր</th>
            <th class="role"><i class="fas fa-ellipsis-h"></i></th>
        </tr>
          <?php 
        while ($row_out_member = mysqli_fetch_array($result_out_members)) {

            $omrole = $row_out_member['DER'];
            $omfname = $row_out_member['f_name_arm'];
            $omlname = $row_out_member['l_name_arm'];
            $ommname = $row_out_member['m_name_arm'];
            $ombday  = $row_out_member['b_day'] . '.' . $row_out_member['b_month'] . '.' . $row_out_member['b_year'];
            $omcitizen = $row_out_member['CITIZEN'];
            $omres = $row_out_member['RES'];
      ?>
        <tr>
            <td><?php echo $omrole ?></td>
            <td><?php echo $omfname ?></td>
            <td><?php echo $omlname ?></td>
            <td><?php echo $ommname ?></td>
            <td><?php echo $ombday ?></td>
            <td><?php echo $omcitizen ?></td>
            <td><?php echo $omres ?></td>
            <td style="text-align: center;">
                                
              <a href="#" class="view_mo" mo_id="<?= $row_out_member['member_id']?>" mo_case="<?= $row_out_member['case_id']?>" ><i class="far fa-eye ml-3" style="color: blue; font-size: 1.5em;"></i></a>
             
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
  
<!-- Modal request response -->
<div class="modal fade" id="request_answer" >
  
</div>

<!-- Modal request approve -->
<div class="modal fade" id="request_approve" >
  
</div>

<div class="modal fade" id="om_edit">
  
</div>




  <script>
$(document).ready(function(){
     $(".view_mo").click(function(){
    var pers_id = $(this).attr('mo_id');
    var case_id = $(this).attr('mo_case');
    $.ajax({
                url:"config/config.php",
                method:"POST",
                data:{mo_view_info:pers_id, case_mo:case_id},
                success:function(data)
                {  
                   //console.log(pers_id);
                   $('#om_edit').html(data);
                   $("#om_edit").modal({backdrop: "static"});
                    
                } 
            });
      });


    $("#approve_request").click(function(){
    var request_id   = $(this).attr('request_id');
    var request_body = $(this).attr('rec_body');
    var case_id      = $(this).attr('case_id');    

     $.ajax({
                 url:"config/config.php",
                 method:"POST",
                 data:{request_appr:request_id, request_body:request_body, case_id:case_id},
                 success:function(data)
                 {  
                    console.log(request_body);
                    $('#request_approve').html(data);
                    $("#request_approve").modal({backdrop: "static"});
                  
                 } 
             });
  }); 




  $("#answer_response").click(function(){
    var request = $(this).attr('request_id');
    var req_proc_id = $(this).attr('request_process_id');   
    var case_id      = $(this).attr('case_id');

     $.ajax({
                 url:"config/config.php",
                 method:"POST",
                 data:{request:request, request_process:req_proc_id, case_id:case_id},
                 success:function(data)
                 {  
                    $('#request_answer').html(data);
                    $("#request_answer").modal({backdrop: "static"});
                  
                 } 
             });
  }); 
 

$(".pers_modal").click(function(){
    
    var pers_id = $(this).attr('modal_id');
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