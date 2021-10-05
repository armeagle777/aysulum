<?php 

require('connect.php');
if(isset($_POST['update_person']))
{
      $personal_id = $_POST['personal_id'];
      $arrival_date = $_POST['arrival_date'];
      $select_role = $_POST['select_role'];
      $bday = $_POST['bday'];
      $bmonth = $_POST['bmonth'];
      $byear = $_POST['byear'];
      $select_sex = $_POST['select_sex'];
      $f_name_arm = $_POST['f_name_arm'];
      $l_name_arm = $_POST['l_name_arm'];
      $m_name_arm = $_POST['m_name_arm'];
      $f_name_eng = $_POST['f_name_eng'];
      $l_name_eng  = $_POST['l_name_eng'];
      $m_name_eng  = $_POST['m_name_eng'];
      $select_religion  = $_POST['select_religion'];
      $select_etnic  = $_POST['select_etnic'];
      $doc_num  = $_POST['doc_num'];
      $select_state  = $_POST['select_state'];
      $select_prev  = $_POST['select_prev'];
      $adr_citizen  = $_POST['adr_citizen'];
      $adr_res  = $_POST['adr_res'];
      $citizen_departure_date  = $_POST['citizen_departure_date'];
      $prev_departure_date  = $_POST['prev_departure_date'];
     
     
     $illegal = '0';
     if(isset($_POST['illegal'])){
      $illegal = '1';
      }
     
     $wanted_prosecutor = '0';
     if(isset($_POST['wanted_prosecutor'])){
      $wanted_prosecutor = '1';
      }

     $wanted_court = '0';
     if(isset($_POST['wanted_court'])){
      $wanted_court = '1';
      }

     $invalid = '0';
     if(isset($_POST['invalid'])){
      $invalid = '1';
      }

     $pregnant = '0';
     if(isset($_POST['pregnant'])){
      $pregnant = '1';
      }

     $ill = '0';
     if(isset($_POST['ill'])){
      $ill = '1';
      }

     $trafficking_victim = '0';
     if(isset($_POST['trafficking_victim'])){
      $trafficking_victim = '1';
      }

    $violence_victim = '0';
      if(isset($_POST['violence_victim'])){
      $violence_victim = '1';
      }

     $select_translator_sex = $_POST['select_translator_sex'];
     
     $select_inter_sex = $_POST['select_inter_sex'];

    
    $sql_update_person = "UPDATE `tb_person` SET 
    
    `f_name_arm`='f_name_arm',
    `f_name_eng`='$f_name_eng',
    `l_name_arm`='$l_name_arm',
    `l_name_eng`='$l_name_eng',
    `m_name_arm`='$m_name_arm',
    `m_name_eng`='$m_name_eng',
    `b_day`='$bday',
    `b_month`='$bmonth',
    `b_year`='$byear',
    `sex`='$select_sex',
    `citizenship`='select_state',
    `previous_residence`='$select_prev',
    `citizen_adr`= 'adr_citizen';
    `residence_adr`='$adr_res',
    `departure_from_citizen`= '$citizen_departure_date',
    `departure_from_residence`= '$prev_departure_date',
    `arrival_date`= '$arrival_date',
    `doc_num`='$doc_num',
    `etnicity`='$select_etnic',
    `religion`= '$select_religion',
    `preferred_traslator_sex`='$select_translator_sex',
    `preferred_interviewer_sex`='$select_inter_sex',
    `invalid`='$invalid',
    `pregnant`='$pregnant',
    `seriously_ill`='$ill',
    `trafficking_victim`='$trafficking_victim',
    `violence_victim`='$violence_victim',
    `illegal_border`='$illegal',
    `wanted_moj`='$wanted_prosecutor',
    `wanted_court`= '$wanted_court',
    `role`= '$select_role'
    WHERE personal_id = $personal_id";

    if ($conn->query($sql_update_person) === TRUE) {
      echo "done";
    }
    else {
      echo "Error: " . $sql_update_person . "<br>" . $conn->error;
    }
}

if (isset($_POST['change_doss'])) {
    $checkin_id      = $_POST['change_doss'];
    $person          = $_POST['pers_id'];
    $case_id         = $_POST['case_id'];
    $current_doss_id = $_POST['from_doss']; 



  $sql_person_to_checkin = "SELECT f_name_arm, l_name_arm, m_name_arm, sex, count(personal_id) AS PNUM FROM tb_person WHERE personal_id = $person";
  $result_person_to_checkin = $conn->query($sql_person_to_checkin);

        if ($result_person_to_checkin->num_rows > 0) 
        {
          $row_person_to_checkin = $result_person_to_checkin->fetch_assoc();
          $fname      = $row_person_to_checkin['f_name_arm'];
          $lname      = $row_person_to_checkin['l_name_arm'];
          $mname      = $row_person_to_checkin['m_name_arm'];
          $gender     = $row_person_to_checkin['sex'];
          $full_name  = $fname .' '. $lname;

          $gender_text = '';
          if ($gender == 1) {
            $gender_text = 'արական';
          }
          if ($gender == 2) {
            $gender_text = 'իգական';
          }
        }

  $sql_free_doss = "SELECT * FROM tb_doss a INNER JOIN tb_drooms b ON a.room_num = b.room_num WHERE a.doss_status = 0 AND (b.room_sex = 0 OR b.room_sex = $gender)";
  $result_free_doss = $conn->query($sql_free_doss);

  $opt_free_doss = '<select name="select_free_doss" id="select_free_doss" class="form-control">
                                    <option selected disabled hidden>Ընտրե՛ք սենյակը և մահճակալը </option>';
      while($row5 = $result_free_doss->fetch_assoc())
      {
        $opt_free_doss.= "<option value=".$row5['doss_id'].">".$row5['room_num']. ' ' .$row5['doss_type']. "</option>";
      }
      $opt_free_doss.= '</select>';
    




    $change_doss_modal = '';

    $change_doss_modal = '<div class="modal-dialog modal-xl">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" style="color: #324157;"><i class="fas fa-reply"></i> Փոփոխել կացարանի բնակչի սենյակը</h5>
          <button type="button" class="close" data-dismiss="modal">×</button>
        </div>
        <div class="modal-body">
        <form method="POST" action="config/config_update.php" id="redev">
          <div class="row">
            <div class="col-md-4">
              <label class="label_pers_page">Տեղափոխվող բնակչի ա.ա.հ.</label>
              <input type="text" class="form-control" value="'.$full_name.'" />
            </div>
            <div class="col-md-4">
              <label class="label_pers_page">Տեղափոխվող բնակչի սեռը</label>
              <input type="text" class="form-control" value="'.$gender_text.'" />
            </div>
            <div class="col-md-4">
              <label class="label_pers_page"> Ընտրեք սենյակը և տեղը </label>
              '.$opt_free_doss.'
            </div>
          </div>

        
            

          
          <input type="hidden" value="'.$checkin_id.'"  name="check_id" />
          <input type="hidden" value="'.$current_doss_id.'"  name="current_doss" />
          <input type="hidden" value="'.$gender.'" name="sex_id" />
           
        
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">ՉԵՂԱՐԿԵԼ</button>   
          <input type="submit" name="change_doss_save" class="btn btn-success" form="redev" value="Տեղափոխել">
        </div>
      </div>
      </form>
    </div>';

    echo $change_doss_modal;
}

if(isset($_POST['change_doss_save'])){
$chekin_id          = $_POST['check_id'];
$doss_id            = $_POST['select_free_doss'];
$current_doss_id    = $_POST['current_doss'];
$checkin_sex        = $_POST['sex_id'];

$new_room_id        = '';
$room_sex           = '';
$current_room_num   = '';

//finding new room id 
$sql_new_room       = "SELECT * FROM tb_drooms a INNER JOIN tb_doss b ON a.room_num = b.room_num WHERE b.doss_id = $doss_id";
$result_new_room    = $conn->query($sql_new_room);

if ($result_new_room->num_rows > 0) {
  $row_new_room        = $result_new_room->fetch_assoc();
  $new_room_id         = $row_new_room['room_id'];
}

//finding current room id, room_num and room_sex
$sql_find_room_sex     = "SELECT * FROM tb_drooms a INNER JOIN tb_doss b ON b.room_num = a.room_num WHERE b.doss_id = $current_doss_id";
$result_find_room_sex  = $conn->query($sql_find_room_sex);

  if($result_find_room_sex->num_rows > 0){
    $row_find_room_sex = $result_find_room_sex->fetch_assoc();
    $room_sex          = $row_find_room_sex['room_sex'];
    $current_room_num  = $row_find_room_sex['room_num'];

  }

//update room sex if current lodger lieved alone and moved out 

$sql_find_room_sex     = "SELECT * FROM tb_drooms a INNER JOIN tb_doss b ON b.room_num = a.room_num WHERE b.doss_id = $current_doss_id";
$result_find_room_sex  = $conn->query($sql_find_room_sex);

  if($result_find_room_sex->num_rows > 0){
    $row_find_room_sex = $result_find_room_sex->fetch_assoc();
    $room_sex          = $row_find_room_sex['room_sex'];
    $current_room_num  = $row_find_room_sex['room_num'];

 

//update room sex if current lodger lieved alone and moved out 
$sql_check_room_checks      = "SELECT * FROM tb_doss WHERE room_num = $current_room_num AND doss_sex != 0";
$result_check_room_checks   = $conn->query($sql_check_room_checks);

  if($result_check_room_checks->num_rows < 2){
    $row_check_room_checks  = $result_check_room_checks->fetch_assoc();
    $current_room_num        = $row_check_room_checks['room_num'];
  

     $update_room_sex        = "UPDATE tb_drooms SET room_sex = '0' WHERE room_num = $current_room_num";
     $result_update_room_sex = $conn->query($update_room_sex);
  }
 }


$update_current_doss        = "UPDATE tb_doss SET doss_status = 0, doss_sex = 0 WHERE doss_id = $current_doss_id";
      if ($conn->query($update_current_doss) === TRUE) {

        $update_chekin = "UPDATE `tb_checkin` SET `doss_id` = '$doss_id' WHERE `checkin_id` = $chekin_id";

        if ($conn->query($update_chekin) === TRUE) {
          $sql_update_doss_status = "UPDATE tb_doss SET doss_status = 1, doss_sex = $checkin_sex WHERE doss_id = $doss_id";
          if($conn->query($sql_update_doss_status) === TRUE){

        
              $update_room_sex = "UPDATE tb_drooms SET room_sex = $checkin_sex WHERE room_id = $new_room_id";
                if ($conn->query($update_room_sex) === TRUE) {
                  header('location: ../user.php?page=cases&homepage=rooms');
                }
                else
                {
                    echo "Error: " . $update_room_sex . "<br>" . $conn->error; 
                }  
          }
          else
          {
             echo "Error: " . $sql_update_doss_status . "<br>" . $conn->error;  
          }
        }
        else
        {
          echo "Error: " . $insert_chekin . "<br>" . $conn->error;  
        }
      }
       else
        {
          echo "Error: " . $update_current_doss . "<br>" . $conn->error;  
        }  
}

#######################

//room info modal

if(isset($_POST['room_modal'])) {
  $room_num = $_POST['room_modal'];
  $room_info_modal = '';

  $sql_room_info = "SELECT a.room_id, a.room_num, a.floor, a.type, a.capacity, a.room_sex, b.doss_id, b.doss, b.doss_status, b.doss_type, b.doss_sex, c.checkin_id, c.checkin_date, c.personal_id, c.order_id, c.status, c.doss_id, d.f_name_arm, d.l_name_arm, d.m_name_arm, d.b_day, d.b_month, d.b_year, d.sex, d.citizenship, d.religion, d.etnicity, d.invalid, d.pregnant, d.seriously_ill, d.trafficking_victim, d.violence_victim, d.illegal_border, d.transfer_moj, d.deport_prescurator, d.role, e.country_arm AS CITIZENSHIP, f.religion_arm AS RELIGION, g.etnic_eng AS ETNICS, h.der
FROM tb_drooms a 
INNER JOIN tb_doss b on a.room_num = b.room_num 
INNER JOIN tb_checkin c ON c.doss_id = b.doss_id 
INNER JOIN tb_person d ON d.personal_id = c.personal_id
INNER JOIN tb_country e ON d.citizenship = e.country_id
INNER JOIN tb_religions f ON d.religion  = f.religion_id
INNER JOIN tb_etnics g ON d.etnicity = g.etnic_id
INNER JOIN tb_role h ON d.role = h.role_id

WHERE c.status = 1 AND a.room_num = $room_num";

$result_room_info = $conn->query($sql_room_info);


$room_info_modal.= '<div class="modal-dialog modal-xl">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"> <i class="fas fa-building"></i>  Սենյակում բնակվողների վերաբերյալ տեղեկատվություն</h5>
          <button type="button" class="close" data-dismiss="modal">×</button>
          
        </div>

        <div class="modal-body">
       
         <table class="table table-bordered">
          <tr>
            <th class="label_pers_page">մահճակալ</th>
            <th class="label_pers_page">տեղավորման ամսաթիվ</th>
            <th class="label_pers_page">ա.ա.հ.</th>
            <th class="label_pers_page">սեռ</th>
            <th class="label_pers_page">տարիք</th>
            <th class="label_pers_page">քաղաքացիություն</th>
            <th class="label_pers_page">ազգություն</th>
            <th class="label_pers_page">կրոն</th>
          </tr>
          </thead>
          <tbody>';

          while($row_person = $result_room_info->fetch_assoc())
          {
            $bed              = $row_person['doss_type'];
            $checkin_date     = date('d.m.Y', strtotime($row_person['checkin_date']));
            $full_name        = $row_person['f_name_arm'] .' '. $row_person['l_name_arm'];
            $sex              = '';

            if($row_person['sex'] == 1){
               $sex   = 'արական'; 
            }
            if($row_person['sex'] == 2){
               $sex   = 'իգական'; 
            }

            $current_year     = date('Y');
            $age              = $current_year - $row_person['b_year'];

            $citizenship      = $row_person['CITIZENSHIP'];
            $etnics           = $row_person['ETNICS'];
            $religion         = $row_person['RELIGION'];



$room_info_modal.='<tr>
                    <td>'.$bed.'</td>
                    <td>'.$checkin_date.'</td>
                    <td>'.$full_name.'</td>
                    <td>'.$sex.'</td>
                    <td>'.$age.'</td>
                    <td>'.$citizenship.'</td>
                    <td>'.$etnics.'</td>
                    <td>'.$religion.'</td>
                  </tr>';            


          }

 $room_info_modal.='</tbody>
                  </table>
           
        </div> 
        <div class="modal-footer">
         <button type="button" class="btn btn-secondary" data-dismiss="modal">ՓԱԿԵԼ</button>
        </div>
      </div>
    </div>'; 

    echo $room_info_modal;
}

?>