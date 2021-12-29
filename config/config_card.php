<?php
session_start();
if(!isset($_SESSION['username'])  || ($_SESSION['role']!=="admin" && $_SESSION['role']!=="operator" && $_SESSION['role']!=="statist" && $_SESSION['role']!=="viewer" && $_SESSION['role']!=="lawyer" && $_SESSION['role']!=="officer" && $_SESSION['role']!=="devhead" && $_SESSION['role']!=="coispec" && $_SESSION['role']!=="head" && $_SESSION['role']!=="police" && $_SESSION['role']!=="nss")){
  header("location: ../index.php");
}
require('connect.php');
date_default_timezone_set('Asia/Yerevan');

//insert education modal

if(isset($_POST['person_edu']))
{
  $modal_edu = '';
  $personal_id = $_POST['person_edu'];
  $case_id = $_POST['person_case'];

  $sql_edu_lvl = "SELECT * FROM tb_edu_lvl";
  $result_edu_lvl = $conn->query($sql_edu_lvl);

  $opt_edu_lvl = '';
  $opt_edu_lvl = '<select name="select_edu_lvl" id="select_edu_lvl" class="form-control form-control-sm">';
  $opt_edu_lvl.= '<option selected disabled hidden>Ընտրե՛ք կրթության մակարդակը</option>';
  while ($row_edu = mysqli_fetch_array($result_edu_lvl)) {  
  $opt_edu_lvl = $opt_edu_lvl."<option value=$row_edu[lvl_id]> $row_edu[edu_lvl]</option>";}

  $opt_edu_lvl.="</select>";



$modal_edu = '<div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Ավելացնել կրթական հաստատություն</h5>
          <button type="button" class="close" data-dismiss="modal">×</button>
          
        </div>
        <div class="modal-body">
        <form method="POST" action="config/config_card.php" id="education">
         
         <input type="hidden" value="'.$personal_id.'" name="person"/>
         <input type="hidden" value="'.$case_id.'" name="case_id"/>

          <div class="col-md-12">
            <div class="row">
              <div class="col-md-6">
                <label class="label_pers_page">Ուսման սկիզբ</label>
                <input type="text" class="form-control form-control-sm" name="edu_start" placeholder="օր.ամիս.տարի *" required />
              </div>
              <div class="col-md-6">
                <label class="label_pers_page">Ուսման ավարտ</label>
                <input type="text" class="form-control form-control-sm" name="edu_end" placeholder="օր.ամիս.տարի *" />
              </div>
              <div class=col-md-6>
                <label class="label_pers_page">կրթության մակարդակ</label>
                '.$opt_edu_lvl.'
              </div>
              <div class="col-md-6">
                <label class="label_pers_page">Մասնագիտություն</label>
                <input type="text" class="form-control form-control-sm" name="edu_specialization" />
              </div>
              <div class="col-md-12">
                <label class="label_pers_page">Կրթական հաստատություն</label>
                <input type="text" class="form-control form-control-sm" name="edu_institute" />
              </div>
            </div>
          </div>

    


          <div class="col-md-12" style="color: red; font-size: 0.85em;">
          <br />
          <p>* Ուսման սկզբի և ավարտի ճշգրիտ ամսաթվերը անհայտ լինելու դեպքում կարելի է լրացնել միայն տարեթիվը </p>
          </div>
         
        </div> 
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">ՉԵՂԱՐԿԵԼ</button>
          
          <input type="submit" name="save_edu" class="btn btn-success" form="education" value="Ավելացնել">
        </div>
      </div>
      </form>
    </div>
    ';

     echo $modal_edu;

}

//insert edu process

if(isset($_POST['save_edu']))

{

$personal_id = $_POST['person'];
$edu_start = $_POST['edu_start'];
$edu_end = $_POST['edu_end'];
$edu_lvl = $_POST['select_edu_lvl'];
$specialization = $_POST['edu_specialization'];
$institution = $_POST['edu_institute'];
$case_id = $_POST['case_id'];

$sql_add_edu = "INSERT INTO `tb_education`(`specialization`, `institution`, `edu_lvl`, `start_year`, `end_year`, `personal_id`) VALUES ('$specialization', '$institution', '$edu_lvl', '$edu_start', '$edu_end', '$personal_id')";


if ($conn->query($sql_add_edu) === TRUE) {
               header('location: ../user.php?page=cases&homepage=person_page&person='.$personal_id.'&case='.$case_id.'#menu2'); 
              }
              else 
              {
              echo "Error: " . $sql_add_edu . "<br>" . $conn->error;  
              }  



}

#########################

//delete edu modal

if(isset($_POST['edu_id_delete']))
{
  $modal_edu_delete = '';
  $edu_id = $_POST['edu_id_delete'];
  $personal_id = $_POST['person'];
  $case_id = $_POST['person_c'];  



$modal_edu_delete = '<div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Հեռացնել կրթության մասին գրառումը</h5>
          <button type="button" class="close" data-dismiss="modal">×</button>
           



        </div>
        <div class="modal-body">
        <form method="POST" action="config/config_card.php" id="delete_education">
         
         <input type="hidden" value="'.$edu_id.'" name="delete_edu_id"/>
          <input type="hidden" value="'.$personal_id.'" name="person"/>
         <input type="hidden" value="'.$case_id.'" name="case_id"/>
         

              <div class="icon-box" align="center">
          <i class="fa fa-trash-alt" style="color: #f15e5e; font-size: 50px; display: inline-block; margin-top: 13px;"></i>
        </div>            
       
          <div class="col-md-12" style="color: red; font-size: 0.9em;">
          <br />
          <p>* Գործողությունը անդառնալի է։ Հաստատելու դեպքում կրթության վերաբերյալ գրառումը կհեռացվի տվյալների շտեմարանից։ </p>
          </div>
         
        </div> 
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">ՉԵՂԱՐԿԵԼ</button>
          
          <input type="submit" name="delete_edu" class="btn btn-danger" form="delete_education" value="ՀԱՍՏԱՏԵԼ">
        </div>
      </div>
      </form>
    </div>
    ';

     echo $modal_edu_delete;

}

//delete edu process

if(isset($_POST['delete_edu']))

{

$personal_id = $_POST['person'];
$case_id = $_POST['case_id'];
$edu_id = $_POST['delete_edu_id'];

$sql_delete_edu = "DELETE FROM tb_education WHERE edu_id = $edu_id";


if ($conn->query($sql_delete_edu) === TRUE) {
               header('location: ../user.php?page=cases&homepage=person_page&person='.$personal_id.'&case='.$case_id.'#menu2'); 
              }
              else 
              {
              echo "Error: " . $sql_delete_edu . "<br>" . $conn->error;  
              }  



}

###############
// edit education modal


if(isset($_POST['edu_id_edit']))
{
  $modal_edu_edit = '';
  $edu_id = $_POST['edu_id_edit'];
  $personal_id = $_POST['person_edit'];
  $case_id = $_POST['person_case_edit'];  

  $query_edu = "SELECT * FROM tb_education WHERE edu_id = $edu_id";
  $result_edu = $conn->query($query_edu);

  if($result_edu->num_rows > 0) {
    $row_edu = $result_edu->fetch_assoc(); 
    $start_date = $row_edu['start_year'];
    $end_date = $row_edu['end_year'];
    $specialization = $row_edu['specialization'];
    $institute = $row_edu['institution']; 
    $edu_level = $row_edu['edu_lvl'];
  


  $sql_edu_edit = "SELECT * FROM tb_education WHERE edu_id = $edu_id";
  $result_sql_edu_edit = $conn->query($sql_edu_edit);

      $query_edu_lvl = "SELECT * FROM tb_edu_lvl";
      $res_edu_lvl = mysqli_query($conn, $query_edu_lvl);
      $opt_edu_lvl = '<select name="select_edu_lvl" id="select_edu_lvl" class="form-control form-control-sm">';
      while($row = mysqli_fetch_array($res_edu_lvl)) {

      if($row['lvl_id'] == $edu_level)
      {
      $opt_edu_lvl.= "<option selected=\"selected\" value=".$row['lvl_id'].">".$row['edu_lvl']."</option>";
      }
      else
      {
      $opt_edu_lvl.= "<option value=".$row['lvl_id'].">".$row['edu_lvl']."</option>";
      }
      }
      $opt_edu_lvl.="</select>";
}

$modal_edu_edit = '<div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Խմբագրել կրթության մասին գրառումը</h5>
          <button type="button" class="close" data-dismiss="modal">×</button>
           



        </div>
        <div class="modal-body">
        <form method="POST" action="config/config_card.php" id="edit_education">
         
         <input type="hidden" value="'.$edu_id.'" name="edit_edu_id"/>
         <input type="hidden" value="'.$personal_id.'" name="person"/>
         <input type="hidden" value="'.$case_id.'" name="case_id"/>
         

             <div class="col-md-12">
              <div class="row">
              <div class="col-md-6">
                <label class="label_pers_page">Ուսման սկիզբ</label>
                <input type="text" class="form-control form-control-sm" name="edu_start" placeholder="օր.ամիս.տարի *" required value="'.$start_date.'"/>
              </div>
              <div class="col-md-6">
                <label class="label_pers_page">Ուսման ավարտ</label>
                <input type="text" class="form-control form-control-sm" name="edu_end" placeholder="օր.ամիս.տարի *"  value="'.$end_date.'"/>
              </div>
              <div class=col-md-6>
                <label class="label_pers_page">կրթության մակարդակ</label>
                '.$opt_edu_lvl.'
              </div>
              <div class="col-md-6">
                <label class="label_pers_page">Մասնագիտություն</label>
                <input type="text" class="form-control form-control-sm" name="edu_specialization"  value="'.$specialization.'" />
              </div>
              <div class="col-md-12">
                <label class="label_pers_page">Կրթական հաստատություն</label>
                <input type="text" class="form-control form-control-sm" name="edu_institute" value="'.$institute.'"/>
              </div>
            </div>
             </div>
         
        </div> 
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">ՉԵՂԱՐԿԵԼ</button>
          
          <input type="submit" name="edit_edu" class="btn btn-success" form="edit_education" value="ՀԱՍՏԱՏԵԼ">
        </div>
      </div>
      </form>
    </div>
    ';

     echo $modal_edu_edit;

}

//edit edu process

if(isset($_POST['edit_edu']))

{

$personal_id = $_POST['person'];
$case_id = $_POST['case_id'];
$edu_id = $_POST['edit_edu_id'];
$edu_start = $_POST['edu_start'];
$edu_end = $_POST['edu_end'];
$edu_lvl = $_POST['select_edu_lvl'];
$specialization = $_POST['edu_specialization'];
$institution = $_POST['edu_institute'];



$sql_update_edu = "UPDATE `tb_education` SET 
`specialization`='$specialization',
`institution`='$institution',
`edu_lvl`='$edu_lvl',
`start_year`='$edu_start',
`end_year`='$edu_end' 
WHERE edu_id = $edu_id";


if ($conn->query($sql_update_edu) === TRUE) {
              header('location: ../user.php?page=cases&homepage=person_page&person='.$personal_id.'&case='.$case_id.'#menu2'); 
              }
              else 
              {
              echo "Error: " . $sql_update_edu . "<br>" . $conn->error;  
              }  



}







##########
// create card modal

  if(isset($_POST['personal_id']))
{
  $modal_id_print='';
  $personal_id = $_POST['personal_id'];
  
  $sql_person = "SELECT a.personal_id, a.case_id, a.f_name_arm, a.l_name_arm, a.b_day, a.b_month, a.b_year, a.sex, a.citizenship, a.image, b.case_id, b.RA_marz, b.RA_community, b.RA_settlement, b.RA_street, b.RA_building, b.RA_apartment, b.application_date, c.country_id, c.country_arm, d.ADM1_ARM as MARZ, e.ADM3_ARM AS COMMUNITY, f.ADM4_ARM AS SETTLEMENT FROM tb_person a 
    INNER JOIN tb_case b ON a.case_id = b.case_id
    INNER JOIN tb_country c ON a.citizenship = c.country_id
    INNER JOIN tb_marz d ON b.RA_marz = d.marz_id
    INNER JOIN tb_arm_com e ON b.RA_community = e.community_id
    INNER JOIN tb_settlement f ON b.RA_settlement = f.settlement_id
    WHERE a.personal_id = $personal_id";

  $resuld_person = $conn->query($sql_person);

 
  
   if ($resuld_person->num_rows > 0) 
{
      $row = $resuld_person->fetch_assoc();  
      $fname = $row['f_name_arm'];
      $lname = $row['l_name_arm'];
      $image = $row['image'];
      $show_image = "uploads/" . $row['case_id'] ."/". $row['personal_id'] ."/". $row['image'];
      $sex = $row['sex'];
      
      $gender = '';

      if($sex == 1){
        $gender = 'Ա';
      }
      if($sex == 2){
        $gender = 'Ի';
      }

      $bday = $row['b_day'];
      $bmonth = $row['b_month'];
      $byear = $row['b_year'];

      $birth_day = $bday . '.' . $bmonth . '.' . $byear;

      $asylum_date =  date("d.m.Y", strtotime($row['application_date']));

      $citizenship = $row['country_arm'];

      $marz = $row['MARZ'];
      $community = $row['COMMUNITY'];
      $bnakavayr = $row['SETTLEMENT'];
      $street = $row['RA_street'];
      $building = $row['RA_building'];
      $apprt = $row['RA_apartment'];

      $full_adr = $community . ' ' .  $bnakavayr . ' ' . $street . ' ' . $building . ' ' . $apprt ;

      $serial = 'AA';
      $card_num = '';
      $asylum_year = date('y', strtotime($asylum_date));
      if ($personal_id <100) {
        $zeroes   = '00';
        $card_num = $asylum_year.$zeroes.$personal_id;
      }
      else
      {
        $zeroes   = '0';
        $card_num = $asylum_year.$zeroes.$personal_id;
      }  
      



  $sql_card = "SELECT * FROM tb_cards WHERE personal_id = $personal_id";
  $result_card_history = $conn->query($sql_card);


  if($result_card_history->num_rows > 0)
    {
      $sql_card.=" AND actual_card = 1";
      $result_actual_card = $conn->query($sql_card);

      if ($result_actual_card->num_rows > 0) 
        {
              
              $row_actual_card = $result_actual_card->fetch_assoc();
              $serial = $row_actual_card['serial'];
              $serial++;
        }
      else
      {
        $serial++;
      }  
    }
  
 }     
  
$modal_id_print.='<div class="modal-dialog modal-xl">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Պատրաստել վկայական</h5>
          <button type="button" class="close" data-dismiss="modal">×</button>
          
        </div>
        <div class="modal-body">
        <form method="POST" action="config/config_card.php" id="print_id" enctype="multipart/form-data">
         
          <input type="hidden" class="form-control form-control-sm" name="person" value="'.$personal_id.'" >

         <div class="row">
          <div class="col-md-12">
            <div class="row">

                <div class="col-md-4" style="border:solid; ">

                            <div style="margin: auto; width: 70%; border: 3px solid green; padding: 10px; ">
                              <img src="'.$show_image.'" style="display: block; margin-left: auto; margin-right: auto; width: 100%;" >
                              
                              
                            </div>

                             <div style="margin: auto; width: 70%; border: 3px solid green; padding: 10px; margin-top: 3%;">
                              <div class="row">

                              <div class="col-md-5">
                              <input type="text" class="form-control form-control-sm" name="seria" value="'.$serial.'" >
                              </div>

                              <div class="col-md-7">
                              <input type="text" class="form-control form-control-sm" name="card_num" value="'.$card_num.'" >
                              </div>
                              </div>
                            </div>


                 </div>

                <div class="col-md-8" style="border:solid red;">
                          
                          
                          
                          
                         
                            <div class="row">
                                  <div class="col-md-6">
                                    <label> Ազգանուն </label>
                                    <input type="text" value="'.$lname.'" class="form-control form-control-sm" readonly>
                                  </div>

                                  <div class="col-md-6">
                                    <label> Անուն </label>
                                    <input type="text" value="'.$fname.'" class="form-control form-control-sm" readonly>
                                  </div>

                                  <div class="col-md-6">
                                   <label> Սեռ </label>
                                   <input type="text" value="'.$gender.'" class="form-control form-control-sm" readonly>
                                  </div>

                                  <div class="col-md-6">
                                    <label> Քաղաքացիություն </label>
                                   <input type="text" value="'.$citizenship.'" class="form-control form-control-sm" readonly>
                                  </div>

                                  <div class="col-md-6">
                                   <label> Ծնվել է </label>
                                   <input type="text" value="'.$birth_day.'" class="form-control form-control-sm" readonly>
                                  </div>

                                  <div class="col-md-6">
                                    <label> Հայցել է ապաստան </label>
                                   <input type="text" value="'.$asylum_date.'" class="form-control form-control-sm" readonly>
                                  </div>

                                   <div class="col-md-12">
                                    <label> Հասցե </label>
                                   <input type="text" value="'.$full_adr.'" class="form-control form-control-sm" readonly>
                                  </div>

                                   <div class="col-md-6">
                                    <label> Տրվել է </label>
                                    <input type="date" class="form-control form-control-sm" name="issued" required>
                                  </div>

                                  <div class="col-md-6">
                                    <label> Վավերական է </label>
                                    <input type="date" class="form-control form-control-sm" name="valid" required>
                                  </div>
                            <div>
                  </div>
            
            </div>
     
            </div>
         </div>



         </div>
        </div> 
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Ո՛Չ</button>
          <input type="submit" name="save_card" class="btn btn-success" form="print_id" value="Պահպանել">
        </div>
      </div>
      </form>
    </div>
    ';

     echo $modal_id_print;
}

if(isset($_POST['save_card'])){

  $pers_id = $_POST['person'];
  $serial = $_POST['seria'];
  $card_number = $_POST['card_num'];
  $issued = $_POST['issued'];
  $valid = $_POST['valid'];
  
  $solt = '1986';
  $bar_num = $pers_id * $solt;
  $bar = $serial.$bar_num;

  $query_cards = "SELECT * FROM tb_cards WHERE personal_id = $pers_id";
  $res_curds   = $conn->query($query_cards);

   if($res_curds->num_rows > 0) {
    $row_card = $res_curds->fetch_assoc(); 

    $sql_update_card_actual = "UPDATE tb_cards SET actual_card = 0 WHERE personal_id = $pers_id";
    $res_update             = $conn->query($sql_update_card_actual);
  }


  $sql_save_card = "INSERT INTO `tb_cards`(`serial`, `card_number`, `personal_id`, `issued`, `valid`, `bar`, `actual_card`) VALUES ('$serial', '$card_number', '$pers_id', '$issued', '$valid', '$bar', '1')";

  

    if ($conn->query($sql_save_card) === TRUE) {
              header('location: ../user.php?page=cases&homepage=person_page&person='.$pers_id.'&case='.$case_id.'#menu3'); 
              }
              else 
              {
              echo "Error: " . $sql_save_card . "<br>" . $conn->error;  
              }  


  }



################
//insert new job modal


  if(isset($_POST['person_job']))
{
  $modal_job = '';
  $personal_id = $_POST['person_job'];
  $case_id = $_POST['person_case_job'];

  


$modal_job = '<div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Ավելացնել աշխատանքային գործունեություն</h5>
          <button type="button" class="close" data-dismiss="modal">×</button>
          
        </div>
        <div class="modal-body">
        <form method="POST" action="config/config_card.php" id="employment">
         
         <input type="text" value="'.$personal_id.'" name="person" hidden/>
         <input type="text" value="'.$case_id.'" name="case_id" hidden/>

          <div class="col-md-12">
            <div class="row">
              <div class="col-md-6">
                <label class="label_pers_page">Աշխատանքի սկիզբ</label>
                <input type="text" class="form-control form-control-sm" name="job_start" placeholder="օր.ամիս.տարի *" required />
              </div>
              <div class="col-md-6">
                <label class="label_pers_page">Աշխատանքի ավարտ</label>
                <input type="text" class="form-control form-control-sm" name="job_end" placeholder="օր.ամիս.տարի *" />
              </div>
              <div class="col-md-6">
                <label class="label_pers_page">Պաշտոն</label>
                <input type="text" class="form-control form-control-sm" name="job_specialization" />
              </div>
              <div class="col-md-6">
                <label class="label_pers_page">Կազմակերպություն / հաստատություն</label>
                <input type="text" class="form-control form-control-sm" name="job_institute" />
              </div>
            </div>
          </div>

    


          <div class="col-md-12" style="color: red; font-size: 0.85em;">
          <br />
          <p>* Աշխատանքային գործունեության սկզբի և ավարտի ճշգրիտ ամսաթվերը անհայտ լինելու դեպքում կարելի է լրացնել միայն տարեթիվը </p>
          </div>
         
        </div> 
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">ՉԵՂԱՐԿԵԼ</button>
          
          <input type="submit" name="save_job" class="btn btn-success" form="employment" value="Ավելացնել">
        </div>
      </div>
      </form>
    </div>
    ';

     echo $modal_job;

}

//insert edu process

if(isset($_POST['save_job']))

{

$personal_id = $_POST['person'];
$job_start = $_POST['job_start'];
$job_end = $_POST['job_end'];
$specialization = $_POST['job_specialization'];
$institution = $_POST['job_institute'];
$case_id = $_POST['case_id'];

$sql_add_job = "INSERT INTO `tb_employment`(`start_date`, `end_date`, `occupation`, `organization`, `personal_id`) VALUES ('$job_start', NULLIF('$job_end', ''), '$specialization', '$institution', '$personal_id')";


if ($conn->query($sql_add_job) === TRUE) {
              header('location: ../user.php?page=cases&homepage=person_page&person='.$personal_id.'&case='.$case_id.'#menu2'); 
              }
              else 
              {
              echo "Error: " . $sql_add_job . "<br>" . $conn->error;  
              }  



}

#########################

// edit job modal


if(isset($_POST['job_id_edit']))
{
  $modal_job_edit = '';
  $emp_id = $_POST['job_id_edit'];
  $personal_id = $_POST['person_edit'];
  $case_id = $_POST['person_case_edit'];  

  $query_job = "SELECT * FROM tb_employment WHERE employment_id = $emp_id";
  $result_job = $conn->query($query_job);

  if($result_job->num_rows > 0) {
    $row_job = $result_job->fetch_assoc(); 
    $start_date = $row_job['start_date'];
    $end_date = $row_job['end_date'];
    $specialization = $row_job['occupation'];
    $institute = $row_job['organization']; 
  

  
}

$modal_job_edit = '<div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Խմբագրել աշխատանքային գործունեությունը</h5>
          <button type="button" class="close" data-dismiss="modal">×</button>
           



        </div>
        <div class="modal-body">
        <form method="POST" action="config/config_card.php" id="edit_employment">
         
         <input type="text" value="'.$emp_id.'" name="edit_job_id"/>
         <input type="text" value="'.$personal_id.'" name="person"/>
         <input type="text" value="'.$case_id.'" name="case_id"/>
         

             <div class="col-md-12">
              <div class="row">
              <div class="col-md-6">
                <label class="label_pers_page">Աշխատանքի սկիզբ</label>
                <input type="text" class="form-control form-control-sm" name="job_start" placeholder="օր.ամիս.տարի *" required value="'.$start_date.'"/>
              </div>
              <div class="col-md-6">
                <label class="label_pers_page">Աշխատանքի ավարտ</label>
                <input type="text" class="form-control form-control-sm" name="job_end" placeholder="օր.ամիս.տարի *"  value="'.$end_date.'"/>
              </div>
              <div class="col-md-6">
                <label class="label_pers_page">Պաշտոն</label>
                <input type="text" class="form-control form-control-sm" name="job_specialization"  value="'.$specialization.'" />
              </div>
              <div class="col-md-6">
                <label class="label_pers_page">Կազմակերպություն || հաստատություն</label>
                <input type="text" class="form-control form-control-sm" name="job_institute" value="'.$institute.'"/>
              </div>
            </div>
             </div>
         
        </div> 
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">ՉԵՂԱՐԿԵԼ</button>
          
          <input type="submit" name="edit_job" class="btn btn-success" form="edit_employment" value="ՀԱՍՏԱՏԵԼ">
        </div>
      </div>
      </form>
    </div>
    ';

     echo $modal_job_edit;

}

//edit job process

if(isset($_POST['edit_job']))

{

$personal_id = $_POST['person'];
$case_id = $_POST['case_id'];
$job_id = $_POST['edit_job_id'];
$job_start = $_POST['job_start'];
$job_end = $_POST['job_end'];
$specialization = $_POST['job_specialization'];
$institution = $_POST['job_institute'];



$sql_update_job = "UPDATE `tb_employment` SET `start_date`='$job_start',`end_date`='$job_end',`occupation`='$specialization',`organization`='$institution',`personal_id`='$personal_id' WHERE `employment_id` = $job_id";


if ($conn->query($sql_update_job) === TRUE) {
              header('location: ../user.php?page=cases&homepage=person_page&person='.$personal_id.'&case='.$case_id.'#menu2'); 
              }
              else 
              {
              echo "Error: " . $sql_update_job . "<br>" . $conn->error;  
              }  



}


#######################
//delete job modal

if(isset($_POST['job_id_delete']))
{
  $modal_job_delete = '';
  $job_id = $_POST['job_id_delete'];
  $personal_id = $_POST['person'];
  $case_id = $_POST['person_c'];  



$modal_job_delete = '<div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Հեռացնել աշխատանքի մասին գրառումը</h5>
          <button type="button" class="close" data-dismiss="modal">×</button>
           



        </div>
        <div class="modal-body">
        <form method="POST" action="config/config_card.php" id="delete_employment">
         
         <input type="hidden" value="'.$job_id.'" name="delete_job_id"/>
         <input type="hidden" value="'.$personal_id.'" name="person"/>
         <input type="hidden" value="'.$case_id.'" name="case_id"/>
         

              <div class="icon-box" align="center">
          <i class="fa fa-trash-alt" style="color: #f15e5e; font-size: 50px; display: inline-block; margin-top: 13px;"></i>
        </div>            
       
          <div class="col-md-12" style="color: red; font-size: 0.9em;">
          <br />
          <p>* Գործողությունը անդառնալի է։ Հաստատելու դեպքում աշխատանքային գործունեության վերաբերյալ գրառումը կհեռացվի տվյալների շտեմարանից։ </p>
          </div>
         
        </div> 
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">ՉԵՂԱՐԿԵԼ</button>
          
          <input type="submit" name="delete_job" class="btn btn-danger" form="delete_employment" value="ՀԱՍՏԱՏԵԼ">
        </div>
      </div>
      </form>
    </div>
    ';

     echo $modal_job_delete;

}

//delete job process

if(isset($_POST['delete_job']))

{

$personal_id = $_POST['person'];
$case_id = $_POST['case_id'];
$job_id = $_POST['delete_job_id'];

$sql_delete_edu = "DELETE FROM `tb_employment` WHERE employment_id = $job_id";


if ($conn->query($sql_delete_edu) === TRUE) {
               header('location: ../user.php?page=cases&homepage=person_page&person='.$personal_id.'&case='.$case_id.'#menu2'); 
              }
              else 
              {
              echo "Error: " . $sql_delete_edu . "<br>" . $conn->error;  
              }  



}

###############




#####################
$conn -> close();
exit;
?>