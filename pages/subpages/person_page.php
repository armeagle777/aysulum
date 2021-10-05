<?php
require_once 'config/connect.php';


$personal_id = $_GET['person'];
$arrival_date = '';
$role_text  = '';
$f_name_arm   = '';
$l_name_arm   = '';
$m_name_arm   = '';
$f_name_eng   = '';
$l_name_eng  = '';
$m_name_eng  = '';
$religion_text  = '';
$etnicity_text  = '';
$doc_num  = '';
$nationality   = '';
$previous_state    = '';
$citizen_adr    = '';
$residence_adr    = '';
$departure_from_citizen    = '';
$departure_from_residence    = '';
$show_image    = 'includes/images/Blank-Profile.jpg';
$sex   = 0;
  $sql_main_person = "SELECT a.personal_id, a.case_id, a.f_name_arm AS anun, a.f_name_eng, a.l_name_arm, a.l_name_eng, a.m_name_arm, a.m_name_eng, a.image, a.b_day, a.b_month, a.b_year, a.sex, a.citizenship, a.previous_residence, a.citizen_adr, a.residence_adr, a.departure_from_citizen, a.departure_from_residence, a.arrival_date, a.doc_num, a.etnicity, a.religion, a.preferred_traslator_sex, a.preferred_interviewer_sex, a.invalid, a.pregnant, a.seriously_ill, a.trafficking_victim, a.violence_victim, a.comment, a.illegal_border, a.transfer_moj, a.deport_prescurator, a.role, a.prison, b.application_date, c.religion_arm, d.etnic_eng, e.country_arm AS NATION, f.country_arm AS STATE, g.der, a.person_status, a.ident, a.pnum, a.doc_type, a.document_num, a.doc_issued_date, a.doc_valid, a.doc_issued_by, a.bpr_community, a.bpr_bnakavayr, a.bpr_street, a.bpr_house, a.bpr_aprt 
    FROM tb_person a INNER JOIN tb_case b ON a.case_id = b.case_id LEFT JOIN tb_religions c ON a.religion = c.religion_id LEFT JOIN tb_etnics d ON a.etnicity = d.etnic_id LEFT JOIN tb_country e ON a.citizenship = e.country_id LEFT JOIN tb_country f ON a.previous_residence = f.country_id LEFT JOIN tb_role g ON a.role = g.role_id WHERE a.personal_id = $personal_id";
  
  $result_main_person = $conn->query($sql_main_person);
 $app_date ='';
  if ($result_main_person->num_rows > 0) 
{
      $row_p = $result_main_person->fetch_assoc();
      $case_id = $row_p['case_id'];
      $image = $row_p['image'];
      $show_image = "uploads/" . $row_p['case_id'] ."/". $personal_id ."/". $row_p['image'];
      $f_name_arm = $row_p['anun'];
      $f_name_eng = $row_p['f_name_eng'];
      $l_name_arm = $row_p['l_name_arm'];
      $l_name_eng = $row_p['l_name_eng'];
      $m_name_arm = $row_p['m_name_arm'];
      $m_name_eng = $row_p['m_name_eng'];
      $app_date = $row_p['application_date'];
      $b_day = $row_p['b_day'];
      $b_month = $row_p['b_month'];
      $b_year = $row_p['b_year'];
      $role = $row_p['role'];
      $role_text = $row_p['der'];
      $sex = $row_p['sex'];
      
      $person_status = $row_p['person_status'];
      $ident = $row_p['ident'];
      if($ident == '1'){
        $ident_chk = 'checked';
      }
      $pnum  = '';
      if(!empty($row_p['pnum'])) {
        $pnum = $row_p['pnum'];
      };
      
      $doc_type = $row_p['doc_type'];
      $document_num = $row_p['document_num'];
      $doc_issued_date = $row_p['doc_issued_date'];
      $doc_valid = $row_p['doc_valid'];
      $doc_issued_by = $row_p['doc_issued_by'];
      $bpr_community = $row_p['bpr_community'];
      $bpr_bnakavayr = $row_p['bpr_bnakavayr'];
      $bpt_street = $row_p['bpr_street'];
      $bpr_house  = $row_p['bpr_house'];
      $bpr_aprt   = $row_p['bpr_aprt'];            


      $doc_num = $row_p['doc_num'];
      $citizenship = $row_p['citizenship'];
      $nationality = $row_p['NATION'];
      $citizen_adr = $row_p['citizen_adr'];
      $departure_from_citizen = $row_p['departure_from_citizen'];
      $previous_residence = $row_p['previous_residence'];
      $previous_state     = $row_p['STATE'];
      $residence_adr = $row_p['residence_adr'];
      $departure_from_residence = $row_p['departure_from_residence'];
      $arrival_date = $row_p['arrival_date'];
      $etnicity = $row_p['etnicity'];
      $etnicity_text = $row_p['etnic_eng'];
      $religion = $row_p['religion'];
      $religion_text = $row_p['religion_arm'];
      $prison_1 = $row_p['prison'];

      $translator_sex   = $row_p['preferred_traslator_sex'];
      $interviewer_sex  = $row_p['preferred_interviewer_sex'];

      $view_interviewer_sex = '';
      if($interviewer_sex == 1){
        $view_interviewer_sex = '<span> <i class="fas fa-mars"></i> արական </span>';
      }
      if($interviewer_sex == 2){
        $view_interviewer_sex = '<span> <i class="fas fa-venus"></i> իգական </span>';
      }
      if($interviewer_sex == 3){
        $view_interviewer_sex = '<span> <i class="fas fa-venus-mars"></i> ցանկացած </span>';
      }

      $prison = '';
      if($prison_1 == 1){
        $prison  = 'checked';
      }


      $view_translator_sex = '';
      if($translator_sex == 1){
        $view_translator_sex = '<span> <i class="fas fa-mars"></i> արական </span>';
      }
      if($translator_sex == 2){
        $view_translator_sex = '<span> <i class="fas fa-venus"></i> իգական </span>';
      }
      if($translator_sex == 3){
        $view_translator_sex = '<span> <i class="fas fa-venus-mars"></i> ցանկացած </span>';
      }

      $invalid = '';
      if ($row_p['invalid'] == '1') {
        $invalid = 'checked';
      }

      $pregnant = '';
      if ($row_p['pregnant'] == '1') {
        $pregnant = 'checked';
      }

      $seriously_ill = '';
      if ($row_p['seriously_ill'] == '1') {
        $seriously_ill = 'checked';
      }

      $trafficking_victim = '';
      if ($row_p['trafficking_victim'] == '1') {
        $trafficking_victim = 'checked';
      }

      $violence_victim = '';
      if ($row_p['violence_victim'] == '1') {
        $violence_victim = 'checked';
      }

      $transfer_moj = '';
      if ($row_p['transfer_moj'] == '1') {
        $transfer_moj = 'checked';
      }

      $illegal_border = '';
      if ($row_p['illegal_border'] == '1') {
        $illegal_border = 'checked';
      }

      $deport_prescurator = '';
      if ($row_p['deport_prescurator'] == '1') {
        $deport_prescurator = 'checked';
      }

         

     
}
?>



    <style>
      input[type="radio"] {
    -ms-transform: scale(1.5); /* IE 9 */
    -webkit-transform: scale(1.5); /* Chrome, Safari, Opera */
    transform: scale(1.5);
}


    </style>
     
    <body>
  <form method="POST" action="config/config.php" enctype="multipart/form-data">    
 

  <!-- Nav tabs -->
<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link active" data-toggle="tab" href="#home">Անձնական տվյալներ</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#menu2">Կրթություն || Աշխատանքային գործունեություն</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#menu4">Վկայականներ</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#menu5">Նույնականացում</a>
  </li>
</ul>
 


     <!--  $person_status = $row_p['person_status'];
      $ident = $row_p['ident'];
      $pnum  = $row_p['pnum'];
      $doc_type = $row_p['doc_type'];
      $document_num = $row_p['document_num'];
      $doc_issued_date = $row_p['doc_issued_date'];
      $doc_valid = $row_p['doc_valid'];
      $doc_issued_by = $row_p['doc_issued_by'];
      $bpr_community = $row_p['bpr_community'];
      $bpr_bnakavayr = $row_p['bpr_bnakavayr'];
      $bpt_street = $row_p['bpr_street'];
      $bpr_house  = $row_p['bpr_house'];
      $bpr_aprt   = $row_p['bpr_aprt'];             -->





 <!-- Tab panes -->
  <div class="tab-content">
      <div id="menu5" class="tab-pane fade"><br>
        <div class="col-md-12">
          <div class="row">
             <div class="col-md-4">
               <label>Նույնականացված է</label>
               <input type="text" class="form-control" name="ident_status" >
             </div> 



          </div>
        </div>
      </div>

      <div id="home" class="tab-pane active"><br>
          <div class="col-md-12" style="margin-top: 10px; margin-bottom: 10px; padding-bottom: 5px; padding-top: 5px;">
        <div class="row">
          <div class="col-md-3">
            <div style="position:absolute; width:100%; height:100%;">
              <img src="<?php echo $show_image ?>" width="175" height="210" style="margin-left: auto; margin-right: auto;   display: block;">
            </div>
            
        
          </div> <!--close col-md-3-->
       
          <div class="col-md-9" >
            <h5 class="sub_title">Ընդհանուր տեղեկություններ</h5>  
            <div class="row">
              <div class="col-md-4">
                <label class="label_pers_page">Գործ #</label>
                <input type="text" class="form-control form-control-sm" name="case_id" value="<?php echo $case_id?>" readonly>
              </div> 
              <div class="col-md-4">
                <label class="label_pers_page">Դիմումի ամսաթիվ</label>
                <input type="text" class="form-control form-control-sm" name="application_date" value="<?php echo $app_date ?>" readonly>
              </div>
              <div class="col-md-4">
                <label class="label_pers_page">ՀՀ ժամանման ամսաթիվ</label>
                <input type="date" class="form-control form-control-sm" name="arrival_date" value="<?php echo $arrival_date ?>" readonly>
              </div> 
            </div>
            <h5 class="sub_title">Անձնական տվյալներ</h5>
            <div class="row">
              <div class="col-md-4">
                <label class="label_pers_page">Դերը</label>
                <input type="text" class="form-control form-control-sm" name="role_text" value="<?php echo $role_text ?>" readonly/> 

                 
           
                
              </div>
              <div class="col-md-4">
              <label class="label_pers_page">ծննդյան ամսաթիվ</label>
                <div class="form-inline">
              <input type="number" class="form-control form-control-sm col-md-3 mr-3" min="00" minlength="2" max="31" placeholder="օր" name="bday" onchange="if(parseInt(this.value,10)<10>1)this.value='0'+this.value;" value="<?php echo $b_day ?>" readonly>
              <input type="number" class="form-control form-control-sm col-md-3 mr-3" min="00" minlength="2" max="12" placeholder="ամիս" name="bmonth" onchange="if(parseInt(this.value,10)<10>1)this.value='0'+this.value;" value="<?php echo $b_month ?>" readonly>
              <input type="number" class="form-control form-control-sm col-md-5" min="0000" max="2100" placeholder="տարի" name="byear" required="required" value="<?php echo $b_year ?>" readonly>
              </div>
              </div>
              <div class="col-md-4">
                <label class="label_pers_page">սեռ</label> <br>
                <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="select_sex" id="inlineRadio1" value="1" required="required" <?= $sex == 1 ? "checked" : "" ?> >
            <label class="form-check-label" for="inlineRadio1">արական</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="select_sex" id="inlineRadio2" value="2" <?= $sex == 2 ? "checked" : "" ?>>
          <label class="form-check-label" for="inlineRadio2">իգական</label> 
        </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-2">
                <label class="label_pers_page">անուն (հայատառ)</label>
                <input type="text" class="form-control form-control-sm" name="f_name_arm" required="required" value="<?php echo $f_name_arm ?>" readonly>
              </div>
              <div class="col-md-2">
                <label class="label_pers_page">ազգանուն (հայատառ)</label>
                <input type="text" class="form-control form-control-sm" name="l_name_arm" required="required" value="<?php echo $l_name_arm ?>" readonly>
              </div>
              <div class="col-md-2">
                <label class="label_pers_page">հայրանուն (հայատառ)</label>
                <input type="text" class="form-control form-control-sm" name="m_name_arm" value="<?php echo $m_name_arm ?>" readonly>
              </div>
          
              <div class="col-md-2">
                <label class="label_pers_page">անուն (լատինատառ)</label>
                <input type="text" class="form-control form-control-sm" name="f_name_eng" required="required" value="<?php echo $f_name_eng ?>" readonly>
              </div>
              <div class="col-md-2">
                <label class="label_pers_page">ազգանուն (լատինատառ)</label>
                <input type="text" class="form-control form-control-sm" name="l_name_eng" required="required" value="<?php echo $l_name_eng ?>" readonly>
              </div>
              <div class="col-md-2">
                <label class="label_pers_page">հայրանուն (լատինատառ)</label>
                <input type="text" class="form-control form-control-sm" name="m_name_eng" value="<?php echo $m_name_eng ?>" readonly>
              </div>
            </div> <!--close eng row-->
    
            <div class="row">
              <div class="col-md-4">
                <label class="label_pers_page">կրոն</label>
                <input type="text" name="religion_text" class="form-control form-control-sm" value="<?php echo $religion_text ?>" readonly/> 
                  
   
              </div>
              <div class="col-md-4">
               <label class="label_pers_page">ազգություն</label>
               <input type="text" name="etnic_text" class="form-control form-control-sm" value="<?php echo $etnicity_text ?>" readonly /> 

                  


              </div>
              <div class="col-md-4">
                <label class="label_pers_page">անձնագիր</label>
                <input type="text" class="form-control form-control-sm" name="doc_num" value="<?php echo $doc_num ?>" readonly>
              </div>
            </div> <!--close personal row-->
          </div> <!--close general row-->
          
        </div> <!--close col-md-9-->
        </div> <!--close col-md-12-->

         <div class="col-md-12" style="margin-top: 5px;">
  <div class="row">
    <div class="col-md-3" style="text-align: center;">
      <a href="#" class="btn btn-primary col-md-8 pers_modal" modalid = <?php echo $personal_id ?>><i class="fas fa-user-edit mr-2"></i> Խմբագրել </a>      
      <a href="?page=cases&homepage=case_page&case=<?php echo $case_id ?>" class="btn btn-warning col-md-8 mt-2"><i class="fas fa-briefcase mr-2"></i> Գործի էջ </a>
      <a href="config/makepdf.php?case_id=<?php echo $personal_id;  ?>" class="btn btn-outline-danger col-md-8 mt-2 "><i class="fa fa-download mr-2"></i>PDF</a>

    </div>
    <div class="col-md-9">
       <h5 class="sub_title">Քաղաքացիություն || մշտական բնակության երկիր</h5>
          <div class="row">
            <div class="col-md-6">
                  <label class="label_pers_page">Քաղաքացիություն</label>
                  <input type="text" name="nationality" class="form-control form-control-sm" value="<?php echo $nationality?>" readonly /> 

                  
            </div>
            <div class="col-md-6">
                  <label class="label_pers_page">Նախկին մշտ․ բնակ․ երկիր</label>
                  <input type="text" name="prev_state" class="form-control form-control-sm" value="<?php echo $previous_state?>" readonly />                   

                  
            </div>
            <div class="col-md-6">
                  <label class="label_pers_page">Հասցեն քաղ․ երկրում</label>
                  <input type="text" class="form-control form-control-sm" name="adr_citizen" value="<?php echo $citizen_adr?>" readonly>
                </div>
                <div class="col-md-6">
                  <label class="label_pers_page">Հասցեն նախ․ բնակ․ երկրում</label>
                  <input type="text" class="form-control form-control-sm" name="adr_res" value="<?php echo $residence_adr?>" readonly>
                </div>
                <div class="col-md-6">
                  <label class="label_pers_page">Քաղ․ երկիրը լքելու ամսաթիվ</label>
                  <input type="text" class="form-control form-control-sm" name="citizen_departure_date" value="<?php echo $departure_from_citizen?>" readonly>
                </div>
                <div class="col-md-6">
                  <label class="label_pers_page">Բնակ․ երկիրը լքելու ամսաթիվ</label>
                  <input type="text" class="form-control form-control-sm" name="res_departure_date" value="<?php echo $departure_from_residence?>" readonly>
                </div>
            </div>
            <div class="row">
              <div class="col-md-6" style="margin-top: 5px;">
                <h5 class="sub_title" style="margin-top: 10px;">Հատուկ նշումներ</h5>
                <table class="table">
                  <tr>
                    <th class="pers_table">Անօրինական սահմանահատում</th>
                    <th class="pers_table">Արտահանձնում (Դատախազ.)</th>
                    <th class="pers_table">Փոխանցում (Արդարադատ.)</th>
                  </tr>
                  <tr>
                    <td><input type="checkbox" class="form-control form-control-sm" name="illegal" <?php echo $illegal_border?> onclick="return false;" /></td>
                    <td><input type="checkbox" class="form-control form-control-sm" name="wanted_prosecutor" <?php echo $deport_prescurator?> onclick="return false;" /></td>
                    <td><input type="checkbox" class="form-control form-control-sm" name="wanted_court" <?php echo $transfer_moj ?> onclick="return false;"/></td>
                  </tr>
                  <tr>
                    <th class="pers_table">Հաշմանդամ</th>
                    <th class="pers_table">Հղի կին</th>
                    <th class="pers_table">Ծանր հիվանդ</th>
                  </tr>
                  <tr>
                    <td><input type="checkbox" class="form-control form-control-sm" <?php echo $invalid ?> onclick="return false;" name="invalid"></td>
                    <td><input type="checkbox" class="form-control form-control-sm" <?php echo $pregnant ?> onclick="return false;" id="pregnant" name="pregnant"></td>
                    <td><input type="checkbox" class="form-control form-control-sm" <?php echo $seriously_ill ?> onclick="return false;" id="pregnant" name="ill"></td>
                  </tr>
                  <tr>
                    <th class="pers_table">Թրաֆիքինգի զոհ</th>
                    <th class="pers_table">Բռնության զոհ</th>
                    <th class="pers_table">ՔԿՀ</th>

                  </tr>
                  <tr>
                    <td><input type="checkbox" class="form-control form-control-sm" name="trafiking" <?php echo $trafficking_victim ?>  onclick="return false;"></td>
                    <td><input type="checkbox" class="form-control form-control-sm" name="violence_victim" <?php echo $violence_victim ?>  onclick="return false;" /></td>
                    <td><input type="checkbox" class="form-control form-control-sm" name="violence_victim" <?php echo $prison ?>  onclick="return false;" /></td>
                  </tr>
                </table>
              </div>
              <div class="col-md-6" style="margin-top: 5px;">
           
                <h5 class="sub_title" style="margin-top: 10px;">Հատուկ կարիքներ</h5>
                <table class="table">
                  <tr>
                    <th class="table_a">Նախընտրելի հարցազրուցավարի սեռը</th>
                     <th class="table_a">Նախընտրելի թարգմանչի սեռը</th>
                  </tr>
                  <tr>  
                    <td style="font-size: 1.0em; text-align: center;">
                      <?php echo $view_interviewer_sex ?>
                   </td>
                   <td style="font-size: 1.0em; text-align: center;">
                    <?php echo $view_translator_sex ?>
                   </td>
                  </tr> 
                                     
                </table>
        </div>
              
            </div> <!--close row -->
    </div>  
  </div>
 </div>
</div> <!--close tab -->
 
    
    <div id="menu2" class="tab-pane fade"><br>
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-6">
            <h5 class="sub_title" style="margin-top: 10px;">Կրթություն</h5>

            <?php 
            $sql_edu = "SELECT * FROM tb_education a LEFT JOIN tb_edu_lvl b ON a.edu_lvl = b.lvl_id WHERE a.personal_id = $personal_id";
            $result_edu = $conn->query($sql_edu);
            ?>

            <table class="table">
              <tr style="vertical-align: middle;">
                <th width="10%" class="label_pers_page">Սկիզբ</th>
                <th width="10%" class="label_pers_page">Ավարտ</th>
                <th width="20%" class="label_pers_page">Կրթության մակարդակ</th>
                <th width="20%" class="label_pers_page">Մասնագիտություն</th>
                <th width="20%" class="label_pers_page">Հաստատություն</th>
                <th width="10%"></th>
                <th width="10%"></th>
              </tr>

              <?php
              while($row_edu = $result_edu->fetch_assoc()){
              ?>

              <tr>
                <td><?= $row_edu['start_year'] ?></td>
                <td><?= $row_edu['end_year'] ?></td>
                <td><?= $row_edu['edu_lvl'] ?></td>
                <td><?= $row_edu['specialization'] ?></td>
                <td><?= $row_edu['institution'] ?></td>
                <td style="font-size: 1em; text-align: center;">
                  <a href="#" id="delete_edu" class="delete_edu btn btn-outline-danger btn-sm" person="<?php echo $personal_id?>" case_of_pers="<?php echo $case_id ?>" delete_id=<?= $row_edu['edu_id'] ?>  /> 
                    <i class="fas fa-trash-alt"></i>  </a> 
                </td>
                <td style="font-size: 1em; text-align: center;">    
                  <a href="#" id="edit_edu" class="edit_edu btn btn-outline-primary btn-sm"     person="<?php echo $personal_id?>" case_of_pers="<?php echo $case_id ?>" edit_id=<?= $row_edu['edu_id'] ?>  /> 
                    <i class="fas fa-pencil-alt"></i> 
                </a>


                </td>

              </tr>


              <?php 
              }
              ?>
            </table>
            <br>
            <div align="center">
              <a href="#" person="<?php echo $personal_id?>" case_of_pers="<?php echo $case_id ?>" id="add_edu" class="btn btn-success btn-md col-md-9" >Ավելացնել կրթական հաստատություն</a>
            </div>
          </div>


          <div class="col-md-6">
            <h5 class="sub_title" style="margin-top: 10px;">Աշխատանքային գործունեություն</h5>
            
             <?php 
            $sql_job = "SELECT * FROM tb_employment a WHERE a.personal_id = $personal_id";
            $result_job = $conn->query($sql_job);
            ?>

            <table class="table" >
              <tr>
                <th width="10%" class="label_pers_page">Սկիզբ</th>
                <th width="10%" class="label_pers_page">Ավարտ</th>
                <th width="30%" class="label_pers_page">Պաշտոն</th>
                <th width="30%" class="label_pers_page">Կազմակերպություն</th>
                <th width="10%"></th>
                <th width="10%"></th>
              </tr>

              <?php
              while($row_job = $result_job->fetch_assoc()){
              ?>

              <tr>
                <td><?= $row_job['start_date'] ?></td>
                <td><?= $row_job['end_date'] ?></td>
                <td><?= $row_job['occupation'] ?></td>
                <td><?= $row_job['organization'] ?></td>
             
                <td><a href="#" id="delete_job" class="delete_job btn btn-outline-danger btn-sm" person="<?php echo $personal_id?>" case_of_pers="<?php echo $case_id ?>" delete_id=<?= $row_job['employment_id'] ?>  > <i class="fas fa-trash-alt"></i>  </a> 
                </td>
                <td>  
                <a href="#" id="edit_job" class="edit_job btn btn-outline-primary btn-sm" person="<?php echo $personal_id?>" case_of_pers="<?php echo $case_id ?>" edit_id=<?= $row_job['employment_id'] ?>  > <i class="fas fa-pencil-alt"></i> </a>

                </td>

              </tr>


              <?php 
              }
              ?>
            </table>
            <br>
            <div align="center">
              <a href="#" person="<?php echo $personal_id?>" case_of_pers="<?php echo $case_id ?>" id="add_job" class="btn btn-success btn-md col-md-9" >Ավելացնել աշխատանքային գործունեություն</a>
            </div>


          </div>
        </div>
      </div>
    </div>


     <div id="menu4" class="tab-pane fade"><br>
      
      <?php 
        $sql_ids = "SELECT * FROM tb_cards WHERE personal_id = $personal_id";
        $result_cards = $conn->query($sql_ids);

        
      ?>

      <div class="col-md-12">
        <div class="row">
          <div class="col-md-6">
            <h5 class="sub_title" style="margin-top: 10px;">Վկայականներ</h5>

            <table class="table">
              <tr>
                <th>հ/հ</th>
                <th>սերիա</th>
                <th>համար</th>
                <th>տրամադրվել է</th>
                <th>վավեր է</th>
                <th>տպագրվել է</th>
                <th>գործող</th>
                <th>...</th>
              </tr>
              <?php

              while($row_id = $result_cards->fetch_assoc()) {

              ?>

                <tr> 
                   <td><?= $row_id['card_id'] ?></td> 
                   <td><?= $row_id['serial'] ?></td>
                   <td><?= $row_id['card_number'] ?></td>
                   <td><?= $row_id['issued'] ?></td>
                   <td><?= $row_id['valid'] ?></td>
                   <td><?= $row_id['printed'] ?></td>
                   <td><?= $row_id['actual_card'] ?></td>
                   <td><a href="config/makepdf.php?id_card=<?php echo $personal_id;?>&card_id=<?= $row_id['card_id']?>" class="btn btn-success btn-sm">PDF</a></td>
                </tr>

            <?php } 
            ?>

            </table>

            <div align="center">
                <a href="#" person="<?php echo $personal_id?>" class="btn btn-success btn-md col-md-9 add_card" >Ստեղծել քարտ</a>
            </div>
          </div>

          <div class="col-md-6">
          
          </div>


        </div>
        
      </div>

    </div>


  </div>

    </form>


  


<div class="modal fade" id="edu_modal"></div>
<div class="modal fade" id="job_modal"></div>
<div class="modal fade" id="edu_delete"></div>
<div class="modal fade" id="edu_edit"></div>
<div class="modal fade" id="job_edit"></div>
<div class="modal fade" id="job_delete"></div>

<div class="modal fade" id="myModal"></div>
<!-- Modal PRINT ID -->
<div class="modal fade" id="modal_print" role="dialog"></div>

<script>

   $("#select_religion").chosen();
   $("#select_role").chosen();
   $("#select_etnic").chosen();
  // $("#select_citizenship").chosen();
  // $("#select_residence").chosen();

  $('#inlineRadio1').change(function() {
        if($(this).is(":checked"))
        { 
            $('#pregnant').prop('disabled', true);
         
        }
   
    });
    $('#inlineRadio2').change(function() {
        if($(this).is(":checked"))
        { 
            $('#pregnant').prop('disabled', false);
         
        }
 
    });
</script>

<script>

  $(document).ready(function(){

     $(".add_card").click(function(){
  
     var personal_id = $(this).attr('person');
        
     $.ajax({
                 url:"config/config_card.php",
                 method:"POST",
                 data:{personal_id:personal_id},
                 success:function(data)
                 {  
                    //console.log(edu_id);
                    $('#modal_print').html(data);
                    $("#modal_print").modal({backdrop: "static"});
                  
                 } 
             });
  }); 


    $(".pers_modal").click(function(){
    // $("#myModal").modal({backdrop: "static"});
    var pers_id = $(this).attr('modalid');
    $.ajax({
                url:"config/config.php",
                method:"POST",
                data:{person_modal_1:pers_id, pers_id:pers_id},
                success:function(data)
                {  

                   $('#myModal').html(data);
                   $("#myModal").modal({backdrop: "static"});
                    
                } 
            });
      });




    $(".delete_edu").click(function(){
  
     var edu_id_delete = $(this).attr('delete_id');
     var person = $(this).attr('person');
     var person_c = $(this).attr('case_of_pers');
     
    
     $.ajax({
                 url:"config/config_card.php",
                 method:"POST",
                 data:{edu_id_delete:edu_id_delete, person:person, person_c:person_c},
                 success:function(data)
                 {  
                    //console.log(edu_id);
                    $('#edu_delete').html(data);
                    $("#edu_delete").modal({backdrop: "static"});
                  
                 } 
             });
  }); 


    $(".delete_job").click(function(){
  
     var job_id_delete = $(this).attr('delete_id');
     var person = $(this).attr('person');
     var person_c = $(this).attr('case_of_pers');
     
    
     $.ajax({
                 url:"config/config_card.php",
                 method:"POST",
                 data:{job_id_delete:job_id_delete, person:person, person_c:person_c},
                 success:function(data)
                 {  
                    //console.log(edu_id);
                    $('#job_delete').html(data);
                    $("#job_delete").modal({backdrop: "static"});
                  
                 } 
             });
  }); 


  $(".edit_edu").click(function(){
  
     var edu_id_edit = $(this).attr('edit_id');
     var person_edit = $(this).attr('person');
     var person_case_edit = $(this).attr('case_of_pers');
     
    
     $.ajax({
                 url:"config/config_card.php",
                 method:"POST",
                 data:{edu_id_edit:edu_id_edit, person_edit:person_edit, person_case_edit:person_case_edit},
                 success:function(data)
                 {  
                    console.log(edu_id_edit);
                    $('#edu_edit').html(data);
                    $("#edu_edit").modal({backdrop: "static"});
                  
                 } 
             });
  });   


$(".edit_job").click(function(){
  
     var job_id_edit = $(this).attr('edit_id');
     var person_edit = $(this).attr('person');
     var person_case_edit = $(this).attr('case_of_pers');
     
    
     $.ajax({
                 url:"config/config_card.php",
                 method:"POST",
                 data:{job_id_edit:job_id_edit, person_edit:person_edit, person_case_edit:person_case_edit},
                 success:function(data)
                 {  
                    console.log(job_id_edit);
                    $('#edu_edit').html(data);
                    $("#edu_edit").modal({backdrop: "static"});
                  
                 } 
             });
  });   





   $(".print_card").click(function(){
  
     var personal_id = $(this).attr('pers_id');
     
    
     $.ajax({
                 url:"config/config_card.php",
                 method:"POST",
                 data:{personal_id:personal_id},
                 success:function(data)
                 {  
                    //console.log(personal_id);
                    $('#modal_print').html(data);
                    $("#modal_print").modal({backdrop: "static"});
                  
                 } 
             });
  }); 

   $("#add_edu").click(function(){
  
     var person_edu = $(this).attr('person');
     var person_case = $(this).attr('case_of_pers');
     
    
     $.ajax({
                 url:"config/config_card.php",
                 method:"POST",
                 data:{person_edu:person_edu, person_case:person_case},
                 success:function(data)
                 {  
                    $('#edu_modal').html(data);
                    $("#edu_modal").modal({backdrop: "static"});
                  
                 } 
             });
  }); 

    $("#add_job").click(function(){
  
     var person_job = $(this).attr('person');
     var person_case_job = $(this).attr('case_of_pers');
     
    
     $.ajax({
                 url:"config/config_card.php",
                 method:"POST",
                 data:{person_job:person_job, person_case_job:person_case_job},
                 success:function(data)
                 {  
                    $('#job_modal').html(data);
                    $("#job_modal").modal({backdrop: "static"});
                  
                 } 
             });
  }); 

});

</script>

<script>
        var loadFile = function(event) {
          var image = document.getElementById('output');
          image.src = URL.createObjectURL(event.target.files[0]);
        };


    window.onload = function(){  

    var url = document.location.toString();
    if (url.match('#')) {
        $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
    }

    //Change hash for page-reload
    $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').on('shown', function (e) {
        window.location.hash = e.target.hash;
    }); 

    
} 
        </script>
</body>
</html>