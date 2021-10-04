<?php
session_start();
if(!isset($_SESSION['username'])  || ($_SESSION['role']!=="admin" && $_SESSION['role']!=="archiver"&& $_SESSION['role']!=="operator" && $_SESSION['role']!=="statist" && $_SESSION['role']!=="viewer" && $_SESSION['role']!=="lawyer" && $_SESSION['role'] !== 'nss'&& $_SESSION['role'] !== 'fin' && $_SESSION['role'] !== 'secretary' && $_SESSION['role'] !== 'dorm' && $_SESSION['role'] !== 'police'&& $_SESSION['role']!=="officer" && $_SESSION['role']!=="devhead" && $_SESSION['role']!=="coispec" && $_SESSION['role']!=="head")){
  exit;
}
require('connect.php');
date_default_timezone_set('Asia/Yerevan');


if(isset($_POST['old_case_id']))
{
$case_id = $_POST['old_case_id'];

$sql_all_old_case = "SELECT a.old_case_id, a.application_date, a.citizenship,  c.country_arm, d.ms_decision, d.ms_decision_date, d.final_decision, d.final_decision_date, e.decision_type AS MS_DECISION_TEXT, f.decision_type AS FINAL_DECISION_TEXT, a.RA_address, a.unaccompanied_child, a.separated_child, a.single_parent, a.prefered_language, a.contact_tel, a.comment
            FROM old_cases a 
            INNER JOIN tb_country c ON a.citizenship = c.country_id
            INNER JOIN old_case_decisions d ON a.old_case_id = d.old_case_id
            INNER JOIN tb_decision_types e ON d.ms_decision = e.decision_type_id
            LEFT JOIN tb_decision_types f ON d.final_decision = f.decision_type_id
            WHERE a.old_case_id = $case_id";
$result_sql_all_old_case = $conn->query($sql_all_old_case); 
    
    if($result_sql_all_old_case->num_rows > 0){
      $rowold_case          = $result_sql_all_old_case->fetch_assoc();
      $application_date     = date('d.m.Y', strtotime($rowold_case['application_date']));
      $citizenship          = $rowold_case['country_arm'];
      $ms_decision          = $rowold_case['MS_DECISION_TEXT'];
      $final_decision       = $rowold_case['FINAL_DECISION_TEXT'];
      $ms_decision_date     = date('d.m.Y', strtotime($rowold_case['ms_decision_date']));

      $final_decision_date  = '';
      if(!empty($rowold_case['final_decision_date'])){
        $final_decision_date  =   date('d.m.Y', strtotime($rowold_case['final_decision_date']));
      }

      $address_armenia      = $rowold_case['RA_address'];
      $unaccompanied_child  = $rowold_case['unaccompanied_child'];

          $chk_unaccompanied_child = '';
          if ($unaccompanied_child == '1') {
            $chk_unaccompanied_child = 'checked';
          }

      $separated_child      = $rowold_case['separated_child'];

          $chk_separated_child = '';
          if ($separated_child == '1') {
            $chk_separated_child = 'checked';
          }

      $single_parent        = $rowold_case['single_parent'];

        $chk_single_parent = '';
        if ($single_parent == '1') {
          $chk_single_parent = 'checked';
        }
      $prefered_language    = $rowold_case['prefered_language'];
      $contact_tel          = $rowold_case['contact_tel'];
      $comment              = $rowold_case['comment'];  

      $sql_family = "SELECT a.old_person_id, a.old_case_id, a.f_name_arm, a.l_name_arm, a.sex, a.b_day, a.b_month, a.b_year, a.role, a.card_num, a.doc_num, b.der FROM old_case_person a INNER JOIN tb_role b ON a.role = b.role_id WHERE a.old_case_id = $case_id";
      $result_sql_family = $conn->query($sql_family);  

    }           

$modal_old = "";



$modal_old.='<div class="modal-dialog modal-xl">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Նշանակել կատարող</h4>
          <button type="button" class="close" data-dismiss="modal">×</button>
          
        </div>
        <div class="modal-body">
        <form method="POST" action="config/config.php" id="old_case">
              <h5 class="sub_title" style="margin-top: 5px;"> Գործի մասին </h5>
              <div class="row">

                <div class="col-md-2">
                    <label class="label_pers_page">Գործ #</label>
                    <input type="text" class="form-control form-control-sm" value="'.$case_id.'" readonly />
                </div>

                <div class="col-md-2">
                    <label class="label_pers_page">Դիմումի ամսաթիվ </label>
                    <input type="text" class="form-control form-control-sm" value="'.$application_date.'" readonly />
                </div>

                <div class="col-md-2">
                    <label class="label_pers_page"> Քաղաքացիությունը</label>
                    <input type="text" class="form-control form-control-sm" value="'.$citizenship.'" readonly />
                </div>

                <div class="col-md-6">
                    <label class="label_pers_page"> Բնակության հասցեն ՀՀ-ում </label>
                    <input type="text" class="form-control form-control-sm" value="'.$address_armenia.'" readonly />
                </div>



                           </div> 
              
                 <h5 class="sub_title" style="margin-top: 5px;"> Հատուկ նշումներ </h5>
                 <div class="row">
                 <div class = "col-md-12">
                <table class="table">
                  <tr>
                    <th class="b_table_modal">Առանց ուղեկցողի երեխա</th>
                    <th class="b_table_modal">Ընտանիքից անջատված երեխա</th>
                    <th class="b_table_modal">Միայնակ ծնող</th>
                  </tr>
                  <tr>
                    <td><input type="checkbox" class="form-control form-control-sm" name="unaccompanied_child" '.$chk_unaccompanied_child.'> </td>
                    <td><input type="checkbox" class="form-control form-control-sm" name="separated_child" '.$chk_separated_child.'> </td>
                    <td><input type="checkbox" class="form-control form-control-sm" name="single_parent" '.$chk_single_parent.'> </td>
              
                  </tr>
                </table>
              </div>
              </div>

  

              <h5 class="sub_title" style="margin-top: 5px;"> Ընդունված որոշումներ </h5>
              <div class="row">
                <div class="col-md-3">
                    <label class="label_pers_page"> ՄԾ որոշում</label>
                    <input type="text" class="form-control form-control-sm" value="'.$ms_decision.'" readonly />
                </div>
                <div class="col-md-3">
                    <label class="label_pers_page"> ՄԾ որոշման ամսաթիվ</label>
                    <input type="text" class="form-control form-control-sm" value="'.$ms_decision_date.'" readonly />
                </div>
                 <div class="col-md-3">
                    <label class="label_pers_page"> Վերջնական որոշում</label>
                    <input type="text" class="form-control form-control-sm" value="'.$final_decision.'" readonly />
                </div>
                <div class="col-md-3">
                    <label class="label_pers_page"> Վերջնական որոշման ամսաթիվ</label>
                    <input type="text" class="form-control form-control-sm" value="'.$final_decision_date.'" readonly />
                </div>
              </div>

              <h5 class="sub_title" style="margin-top: 5px;"> Ընտանիքի կազմը </h5>
              <div class="row">
              <div class="col-md-12">
                <table class="table">
                  <tr style=" font-size: 0.8em; color: #324157; text-align: center; vertical-align: middle;">
                    <th>Անձնական #</th>
                    <th>Դերը </th>
                    <th>ա.ա.հ </th>
                    <th>սեռը</th>
                    <th>ծննդյան ամսաթիվ </th>
                    <th>անձնագրի #</th>
                    <th>վկայականի #</th>
                  </tr>';
                  while($row = $result_sql_family -> fetch_assoc()){
                      $personal_id = $row['old_person_id'];
                      $role        = $row['der'];
                      $full_name   = $row['f_name_arm'] . ' ' . $row['l_name_arm'];
                      $sex         = '';
                      if ($row['sex'] == '1') {
                        $sex = 'արական';
                      }
                      if ($row['sex'] == '2') {
                        $sex = 'իգական';
                      }
                      else{
                        $sex = 'անհայտ';
                      }

                      $b_day = '';
                      $b_month = '';
                      $b_year = '';

                      if(!empty($row['b_day'])){
                        $b_day = $row['b_day'];
                      }
                      else{
                        $b_day = '00';
                      }

                       if(!empty($row['b_month'])){
                        $b_month = $row['b_month'];
                      }
                      else {
                        $b_month = '00';
                      }

                       if(!empty($row['b_year'])){
                        $b_year = $row['b_year'];
                      }
                      else{
                        $b_year = '00';
                      }

                      $birthday = $b_day .'.'. $b_month .'.'. $b_year;

                      $passport = $row['doc_num'];
                      $id_num   = $row['card_num'];

             $modal_old.='<tr style="font-size: 1em; color:#324157; text-align: center;">
                    <td>'.$personal_id.'</td>
                    <td>'.$role.'</td>
                    <td>'.$full_name.'</td>
                    <td>'.$sex.'</td>
                    <td>'.$birthday.'</td>
                    <td>'.$passport.'</td>
                    <td>'.$id_num.'</td>
                  </tr>';
                }

               $modal_old.='</table>  
              </div>
              </div>


     
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">ՓԱԿԵԼ</button>
          <input type="submit" name="save_a_sign" class="btn btn-primary" form="asign_save" value="ՊԱՀՊԱՆԵԼ">
        </div>
      </div>
      </form>
    </div>';

     echo $modal_old;
  }


  if(isset($_POST['edit_person_old'])){
    $old_person_id = $_POST['edit_person_old'];
    $modal_person  = '';

    $sql_old_person = "SELECT `old_person_id`, `old_case_id`, `f_name_arm`, `l_name_arm`, `f_name_eng`, `l_name_eng`, `sex`, `citizenship_id`, `p_name_arm`, `p_name_eng`, `b_day`, `b_month`, `b_year`, `role`, `card_num`, `doc_num`, `etnicity`, `religion`, `invalid`, `pregnant`, `seriously_ill`, `trafficking_victim`, `violence_victim`, `comment`, `illegal_border`, `transfer_moj`, `deport_prescurator`, `prison`, `image` FROM `old_case_person` WHERE old_person_id = $old_person_id";
    $result_old_person = $conn->query($sql_old_person);

    if($result_old_person->num_rows > 0){
      $row = $result_old_person->fetch_assoc();
       $old_person_id=$row['old_person_id'];
       $old_case_id=$row['old_case_id'];
       $f_name_arm=$row['f_name_arm'];
       $l_name_arm=$row['l_name_arm']; 
       $p_name_arm=$row['p_name_arm'];
       $f_name_eng=$row['f_name_eng']; 
       $l_name_eng=$row['l_name_eng'];
       $p_name_eng=$row['p_name_eng'];
       $sex=$row['sex'];
       $b_day=$row['b_day'];
       $b_month=$row['b_month'];
       $b_year=$row['b_year'];
       $role_id=$row['role'];
       $card_num=$row['card_num'];
       $doc_num=$row['doc_num'];
       $etnicity_id=$row['etnicity'];
       $religion_id=$row['religion'];
       $invalid=$row['invalid'];
       $pregnant=$row['pregnant'];
       $seriously_ill=$row['seriously_ill']; 
       $trafficking_victim=$row['trafficking_victim'];
       $violence_victim=$row['violence_victim'];
       $comment=$row['comment'];
       $illegal_border=$row['illegal_border'];
       $transfer_moj=$row['transfer_moj']; 
       $deport_prescurator=$row['deport_prescurator']; 
       $prison=$row['prison']; 
       $image=$row['image']; 
       $citizenship_id = $row['citizenship_id'];
        $show_image = "uploads/" . $old_case_id ."/". $old_person_id."/".  $row['image'];

      $query_religion = "SELECT * FROM tb_religions";
      $religion2 = mysqli_query($conn, $query_religion);
      $optpreligion = '<select name="select_religion" id="select_religion" class="form-control form-control-sm">';
       $optpreligion.= "<option> -- Նշված չէ -- </option>";
      while($row3 = mysqli_fetch_array($religion2)) {

      if($row3['religion_id'] == $religion_id)
      {
      $optpreligion.= "<option selected=\"selected\" value=".$row3['religion_id'].">".$row3['religion_arm']."</option>";
      }
      else
      {
      $optpreligion.= "<option value=".$row3['religion_id'].">".$row3['religion_arm']."</option>";
      }
      }
      $optpreligion.="</select>";

      $query_etnic = "SELECT * FROM tb_etnics";
      $etnicity2 = mysqli_query($conn, $query_etnic);
      $opt_etnic  =  '<select name="select_etnic" id="select_etnic" class="form-control form-control-sm">';
      $opt_etnic .= '<option> -- Նշված չէ -- </option>';
      while($row4 = mysqli_fetch_array($etnicity2)) {

      if($row4['etnic_id'] == $etnicity_id)
      {
      $opt_etnic.= "<option selected=\"selected\" value=".$row4['etnic_id'].">".$row4['etnic_eng']."</option>";
      }
      else
      {
      $opt_etnic.= "<option value=".$row4['etnic_id'].">".$row4['etnic_eng']."</option>";
      }
      }
      $opt_etnic.="</select>";

      $query_role = "SELECT * FROM tb_role";
      $role2 = mysqli_query($conn, $query_role);
      $opt_role = '<select name="select_role" id="select_role" class="form-control form-control-sm">';
      $opt_role.= "<option> -- Նշված չէ -- </option>";
      while($row5 = mysqli_fetch_array($role2)) {

      if($row5['role_id'] == $role_id)
      {
      $opt_role.= "<option selected=\"selected\" value=".$row5['role_id'].">".$row5['der']."</option>";
      }
      else
      {
      $opt_role.= "<option value=".$row5['role_id'].">".$row5['der']."</option>";
      }
      }
      $opt_role.="</select>";

      $opt_sex = '<select name="select_mo_gender" id="select_mo_gender" class="form-control form-control-sm">';

        if ($sex == 1) {
          $opt_sex.= "<option selected=\"selected\" value='1'>"."արական"."</option>".
                     "<option value='2'>".'իգական'."</option>";

        }
        elseif ($sex == 2) {
          $opt_sex.= "<option selected=\"selected\" value='2'>"."իգական"."</option>".
                     "<option value='1'>".'արական'."</option>";

        }
        else 
        {
          $opt_sex.= "<option selected=\"selected\" disabled=\"disabled\" hidden=\"hidden\">"."Նշե՛ք սեռը"."</option>".
                "<option value='1'>"."արական"."</option>".
                "<option value='2'>"."իգական"."</option>";

        }
        $opt_sex.= '</select>';

        $query_citizenship = "SELECT * FROM tb_country";
      $state = mysqli_query($conn, $query_citizenship);
      $optcitizen = '<select name="select_state" id="select_state" class="form-control form-control-sm">';
      $optcitizen.= '<option selected disabled hidden>Ընրել երկիրը</option>';
      while($row1 = mysqli_fetch_array($state))
      {
        if($row1['country_id'] == $citizenship_id)
        {
        $optcitizen.= "<option selected=\"selected\" value=".$row1['country_id'].">".$row1['country_arm']."</option>";
        }
        else
        {
        $optcitizen.= "<option value=".$row1['country_id'].">".$row1['country_arm']."</option>";
        }
      }
      $optcitizen.="</select>";

    }

    $modal_person = '<div class="modal-dialog modal-xl">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Անձնական տվյալների խմբագրիչ<h4>
          <button type="button" class="close" data-dismiss="modal">×</button>
          
        </div>
        <div class="modal-body">
            
          <ul class="nav nav-tabs">
            <li class="nav-item">
              <a class="nav-link active" data-toggle="tab" href="#person_info">Անձնական տվյալներ</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#menu2">Նույնականացում</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#menu1">Կցված ֆայլեր</a>
            </li>
          </ul>
        
            <div class="tab-content">
                <div id="menu1" class="tab-pane fade"><br>
                   dddsegfg
                </div> 

                <div id="menu2" class="tab-pane fade"><br>
                   <div class="row">
                      <h5 class="sub_title">Նույնականացում ՀՀ բնակչության պետական ռեգիստրի հետ</h5>
                   </div>
                </div> 

               <div id="person_info" class="tab-pane active"><br>
                    <div class="row">
                      <div class="col-md-3" >
                            <div class="photo">   
                                  <p><img id="output" width="180px" height="218px" src="'.$show_image.'"/></p>
                                  <label for="photo_upload" style="cursor: pointer;" id="pPhoto_label"><i class="fas fa-edit"></i></label>        
                                  <p><input type="file" name="file" id="photo_upload" accept="image/*" id="file"  onchange="loadFile(event)" style="display: none;"></p>
                            </div> 
                    </div> 
                    <div class="col-md-9" >
                            <h5 class="sub_title">Ընդհանուր տեղեկություններ</h5>  
                             <div class="row">
                                <div class="col-md-4">
                                  <label class="label_pers_page">Գործ #</label>
                                  <input type="text" class="form-control form-control-sm" name="case_id" value="'.$old_case_id.'" >
                                </div> 
                                <div class="col-md-4">
                                  <label class="label_pers_page">Անձնական #</label>
                                  <input type="text" class="form-control form-control-sm" name="personal_id" value="'.$old_person_id.'">
                                </div> 
                                <div class="col-md-4">
                                   <label class="label_pers_page">Դերը</label>
                                   '. $opt_role .'
                                </div>
                             </div> <!--close row-->

                          <h5 class="sub_title">Անձնական տվյալներ</h5>
                             <div class="row">
                                <div class="col-md-4">
                                      <label class="label_pers_page">ծննդյան ամսաթիվ</label>                
                                      <div class="form-inline">
                                          <input type="number" class="form-control form-control-sm col-md-3 mr-2" min="00" minlength="2" max="31" placeholder="օր" name="bday" onchange="if(parseInt(this.value,10)<10>1)this.value="0"+this.value;" value="'.$b_day.'">
                                          <input type="number" class="form-control form-control-sm col-md-4 mr-2" min="00" minlength="2" max="12" placeholder="ամիս" name="bmonth" onchange="if(parseInt(this.value,10)<10>1)this.value="0"+this.value;" value="'.$b_month.'">
                                          <input type="number" class="form-control form-control-sm col-md-4" min="0000" max="2100" placeholder="տարի" name="byear" required="required" value="'.$b_year.'">
                                      </div>
                                </div>
                                <div class="col-md-4">
                                      <label class="label_pers_page">սեռ</label>
                                      '. $opt_sex .'
                                </div>
                                <div class="col-md-4">
                                    <label class="label_pers_page">Քաղաքացիություն</label>
                                    '. $optcitizen .'
                                </div>

                                <div class="col-md-4">
                                  <label class="label_pers_page">անուն (հայատառ)</label>
                                  <input type="text" class="form-control form-control-sm" name="f_name_arm1" value="'. $f_name_arm. '">
                                </div>
                                <div class="col-md-4">
                                  <label class="label_pers_page">ազգանուն (հայատառ)</label>
                                  <input type="text" class="form-control form-control-sm" name="l_name_arm" value="'.$l_name_arm.'">
                                </div>
                                <div class="col-md-4">
                                  <label class="label_pers_page">հայրանուն (հայատառ)</label>
                                  <input type="text" class="form-control form-control-sm" name="l_name_arm" value="'.$p_name_arm.'">
                                </div>
                                

                                <div class="col-md-4">
                                  <label class="label_pers_page">անուն (լատինատառ)</label>
                                  <input type="text" class="form-control form-control-sm" name="f_name_eng" value="'.$f_name_eng.'">
                                </div>
                                <div class="col-md-4">
                                  <label class="label_pers_page">ազգանուն (լատինատառ)</label>
                                  <input type="text" class="form-control form-control-sm" name="l_name_eng" value="'.$l_name_eng.'">
                                </div>
                                <div class="col-md-4">
                                  <label class="label_pers_page">հայրանուն (լատինատառ)</label>
                                  <input type="text" class="form-control form-control-sm" name="l_name_eng" value="'.$p_name_eng.'">
                                </div>
                               
                             </div>  <!--close row-->


                    </div> <!--close col-md 9-->

                   </div><!--close row-->

                   <div class="row">
                      <div class="col-md-3">
                        <label class="label_pers_page">կրոն</label>
                        '. $optpreligion .'
                      </div>
                      <div class="col-md-3">
                        <label class="label_pers_page">ազգություն</label>
                        '. $opt_etnic .'
                      </div>
                      <div class="col-md-3">
                        <label class="label_pers_page">անձնագիր</label>
                        <input type="text" class="form-control form-control-sm" name="doc_num" value="'.$doc_num.'">
                      </div>
                

                   <div class="col-md-6">
                      <h5 class="sub_title" style="margin-top: 10px;">Հատուկ նշումներ</h5> 
                        <table class="table">
                          <tr>
                            <th class="table_a">Ապօրինի սահմանահատում</th>
                            <td><input type="checkbox" class="form-control form-control-sm" name="illegal" '. $illegal_border .'></td>
                          </tr>                   
                          <tr>
                            <th class="table_a">Արտահանձնում (Դատախազություն)</th>
                            <td><input type="checkbox" class="form-control form-control-sm" name="deport_prescurator" '. $deport_prescurator .'></td>
                          </tr>                  
                          <tr>
                            <th class="table_a">Փոխանցում (Արդարադատ)</th>
                            <td><input type="checkbox" class="form-control form-control-sm" name="transfer_moj" '. $transfer_moj .'></td>
                          </tr>                 
                          <tr>
                            <th class="table_a">ՔԿՀ</th>
                            <td> <input type="checkbox" class="form-control form-control-sm" name="prison" '. $prison .' ></td>
                          </tr> 
                        </table> 
                   </div>
                   <div class="col-md-6">
                      <h5 class="sub_title" style="margin-top: 10px;">Հատուկ կարիքներ</h5>
                      <table class="table">
                        <tr>
                          <th class="table_a">Հաշմանդամ</th>
                          <td><input type="checkbox" class="form-control form-control-sm" name="invalid" '. $invalid .'></td>
                        </tr>  
                        <tr>
                          <th class="table_a">Հղի կին</th>
                          <td><input type="checkbox" class="form-control form-control-sm" name="pregnant" '. $pregnant .'></td>
                        </tr>   
                        <tr>
                          <th class="table_a">Ծանր հիվանդ</th>
                          <td><input type="checkbox" class="form-control form-control-sm" name="ill" '. $seriously_ill .'></td>
                        </tr>
                        <tr>
                          <th class="table_a">Թրաֆիկինգի զոհ</th>
                          <td><input type="checkbox" class="form-control form-control-sm" name="trafficking_victim" '. $trafficking_victim .'></td>
                        </tr> 
                        <tr>
                          <th class="table_a">Բռնության զոհ</th>
                          <td><input type="checkbox" class="form-control form-control-sm" name="violence_victim" '. $violence_victim .'></td>
                        </tr>
                                     
                      </table>
                    </div> 
                  </div>
               </div>


            </div>


            
        
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">ՓԱԿԵԼ</button>
          <input type="submit" name="save_a_sign" class="btn btn-primary" form="asign_save" value="ՊԱՀՊԱՆԵԼ">
        </div>
      </div>
      </form>
    </div>';



    echo $modal_person;

  }
  


?>