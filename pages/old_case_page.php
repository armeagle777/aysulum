<?php
$case_id = $_GET['old_case'];
 require_once 'config/connect.php';

	$marz 		='';		
    $community 	='';		
    $satl		='';
    $ms_decision_id  = '';
    $final_decision_id = '';		
$sql_all_old_case = "SELECT a.old_case_id, a.application_date, a.citizenship,  c.country_arm, d.ms_decision, d.ms_decision_date, d.final_decision, d.final_decision_date, e.decision_type AS MS_DECISION_TEXT, f.decision_type AS FINAL_DECISION_TEXT, a.RA_address, a.unaccompanied_child, a.separated_child, a.single_parent, a.prefered_language, a.contact_tel, a.comment, a.marz_id, a.community_id, a.bnak_id, a.building, a.apartment, d.ms_decision, d.final_decision
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
      $ms_decision_date     = $rowold_case['ms_decision_date'];
      $contact 				= $rowold_case['contact_tel'];
      $comment_case 		= $rowold_case['comment'];
      $language_prefered    = $rowold_case['prefered_language'];
      $ms_decision_id 		= $rowold_case['ms_decision'];
      $final_decision_id    = $rowold_case['final_decision'];
      $final_decision_date  = $rowold_case['final_decision_date'];
      $address_armenia      = $rowold_case['RA_address'];
      $building 			= $rowold_case['building'];
      $apartment 			= $rowold_case['apartment'];
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
      if(!empty($rowold_case['marz_id'])){
      $marz 				= $rowold_case['marz_id'];
      }
      $community 			= $rowold_case['community_id'];
      $satl					= $rowold_case['bnak_id'];

}
  	  $query_marz = "SELECT * FROM tb_marz";
      $marzz = mysqli_query($conn, $query_marz);
      $optmarz = '<select name="select_marz" id="select_marz" class="form-control form-control-sm">
      <option selected disabled hidden>Ընտրե՛ք մարզը </option>';
      while($row1 = mysqli_fetch_array($marzz)) {

      if($row1['marz_id'] == $marz)
      {
      $optmarz.= "<option selected=\"selected\" value=".$row1['marz_id'].">".$row1['ADM1_ARM']."</option>";
      }
      else
      {
      $optmarz.= "<option value=".$row1['marz_id'].">".$row1['ADM1_ARM']."</option>";
      }
      }
      $optmarz.="</select>";

      $query_community = "SELECT * FROM tb_arm_com";
      $communit = mysqli_query($conn, $query_community);
      $optcom = '<select name="select_community" id="select_community" class="form-control form-control-sm">
       <option selected disabled hidden>Ընտրե՛ք համայնքը </option>';
      while($row2 = mysqli_fetch_array($communit)) {

      if($row2['community_id'] == $community)
      {
      $optcom.= "<option selected=\"selected\" value=".$row2['community_id'].">".$row2['ADM3_ARM']."</option>";
      }
      else
      {
      $optcom.= "<option value=".$row2['community_id'].">".$row2['ADM3_ARM']."</option>";
      }
      }
      $optcom.="</select>";

      $query_settl = "SELECT * FROM tb_settlement";
      $settl = mysqli_query($conn, $query_settl);
      $optsettl = '<select name="select_setl" id="select_setl" class="form-control form-control-sm">
       <option selected disabled hidden>Ընտրե՛ք բնակավայրը </option>';
      while($row3 = mysqli_fetch_array($settl)) {

      if($row3['settlement_id'] == $satl)
      {
      $optsettl.= "<option selected=\"selected\" value=".$row3['settlement_id'].">".$row3['ADM4_ARM']."</option>";
      }
      else
      {
      $optsettl.= "
     
      <option value=".$row3['settlement_id'].">".$row3['ADM4_ARM']."</option>";
      }
      }
      $optsettl.="</select>";


      $query_ms_decsion = "SELECT * FROM tb_decision_types";
      $res_ms_decision  = mysqli_query($conn, $query_ms_decsion);
      $optmsdec = '<select name="select_msdec" id="select_msdec" class="form-control form-control-sm">
       <option selected disabled hidden>Ընտրե՛ք որոշումը </option>';
      while($row3 = mysqli_fetch_array($res_ms_decision)) {

      if($row3['decision_type_id'] == $ms_decision_id)
      {
      $optmsdec.= "<option selected=\"selected\" value=".$row3['decision_type_id'].">".$row3['decision_type']."</option>";
      }
      else
      {
      $optmsdec.= "
     
      <option value=".$row3['decision_type_id'].">".$row3['decision_type']."</option>";
      }
      }
      $optmsdec.="</select>";


      $query_final_decsion = "SELECT * FROM tb_decision_types";
      $res_final_decision  = mysqli_query($conn, $query_final_decsion);
      $optfinaldec = '<select name="select_msdec" id="select_msdec" class="form-control form-control-sm">
       <option selected disabled hidden>Ընտրե՛ք որոշումը </option>';
      while($row3 = mysqli_fetch_array($res_final_decision)) {

      if($row3['decision_type_id'] == $final_decision_id)
      {
      $optfinaldec.= "<option selected=\"selected\" value=".$row3['decision_type_id'].">".$row3['decision_type']."</option>";
      }
      else
      {
      $optfinaldec.= "
     
      <option value=".$row3['decision_type_id'].">".$row3['decision_type']."</option>";
      }
      }
      $optfinaldec.="</select>";

      $sql_family = "SELECT a.old_person_id, a.old_case_id, a.f_name_arm, a.l_name_arm, a.sex, a.b_day, a.b_month, a.b_year, a.role, a.card_num, a.doc_num, b.der FROM old_case_person a 
                    INNER JOIN tb_role b ON a.role = b.role_id 
                    WHERE a.old_case_id = $case_id";
      $result_sql_family = $conn->query($sql_family);  

      $sql_files = "SELECT a.old_file_id, a.old_case_id, a.uploaded_on, a.uploaded_by, a.old_person_id, a.filename, a.file_path, a.file_type, b.file_type AS TYPETEXT, c.f_name, c.l_name
                    FROM tb_old_files a 
                    INNER JOIN tb_file_type b ON a.file_type = b.file_type_id
                    INNER JOIN users c ON a.uploaded_by = c.id
                    WHERE a.old_case_id = $case_id";
      $result_files = $conn->query($sql_files);

?>

<div class="case_area mt-5">
 
 <div class="tab">
  <button class="tablinks" onclick="openCity(event, 'functions')" id="defaultOpen"> Գործի մասին </button>
  <button class="tablinks" onclick="openCity(event, 'decisions')"> Ընդունված որոշումներ </button>
  <button class="tablinks" onclick="openCity(event, 'person')"> Ընդգրկված անձինք </button>
  <button class="tablinks" onclick="openCity(event, 'attach_file')"> Կցել փաստաթուղթ </button>
  
</div>

<div id="attach_file" class="tabcontent">
  <div class="row">
    <div class="col-md-4"> <!-- attach file div -->
      <form method="POST" action="config/config_old.php" enctype="multipart/form-data">
        <input type="hidden" name="case_num" id="case_num" value="<?php echo $case_id?>">
        <div class="col-md-12 mt-3">
          <select class="form-control" name="file_person_case" id="file_type_select">
            <option value="" selected disabled hidden>Նշե՛ք տիպը</option>
            <option value="1">Գործի վերաբերյալ</option>
            <option value="2">Անձի վերաբերյալ</option>
          </select>  
        </div>
        <div class="col-md-12">
          <div id="dropdown_container" >
                    
          </div>  

          <div class="form-group custom-file mt-2">
                  <input type="file" name="file" class="custom-file-input" id="customFile" required="required" />
                  <label class="custom-file-label" for="customFile">Ընտրե՛ք ֆայլը</label>
          </div>

        </div>

        <div class="col-md-12 mt-2">
          <input type="submit" class="btn btn-success" name="save_doc" value="ՎԵՐԲԵՌՆԵԼ">
        </div>

      </form>
    </div>

    <div class="col-md-8"> <!-- attached files div -->
      <h5 class="sub_title mt-3">Կցված փաստաթղթեր</h5>
        <table class="table">
          <tr class="label_pers_page">
            <th>տիպ</th>
            <th>տեսակ</th>
            <th>վերբեռնվել է</th>
            <th>վերբեռնող</th>
            <th>ֆայլ</th>
          </tr>
          <?php 
          while ($row = $result_files->fetch_assoc()) {
            $type = 'Գործի մասին';
            if(!empty($row['old_person_id'])){
              $type = 'Անձի վերաբերյալ';
            }
            $doc_type = $row['TYPETEXT'];
            $upload_date = date('d.m.Y', strtotime($row['uploaded_on']));
            $uploader    = $row['f_name'] .' '. $row['l_name'];
            $file_name   = $row['filename'];
            $filepath    = $row['file_path'];
          
          ?>


          <tr >
            <td><?php echo $type ?></td>
            <td><?php echo $doc_type ?></td>
            <td><?php echo $upload_date ?></td>
            <td><?php echo $uploader ?></td>
            <td><a href="<?php echo $filepath ?>" download><?php echo $file_name ?></a></td>
          </tr>

        <?php } ?>
        </table>
    </div>
  </div>
</div>

<div id="functions" class="tabcontent">
  <h5 class="sub_title" style="margin-top: 5px; margin-bottom: 5px;"> Գործի մասին </h5>
  <div class="row">
          <div class="col-md-2">
              <label class="label_pers_page">Գործ #</label>
              <input type="text" class="form-control form-control-sm" value="<?php echo $case_id?>" readonly />
          </div>

          <div class="col-md-2">
              <label class="label_pers_page">Դիմումի ամսաթիվ </label>
              <input type="text" class="form-control form-control-sm" value="<?php echo $application_date?>" readonly />
          </div>

          <div class="col-md-2">
              <label class="label_pers_page"> Քաղաքացիությունը</label>
              <input type="text" class="form-control form-control-sm" value="<?php echo $citizenship ?>" readonly />
          </div>

          <div class="col-md-2">
              <label class="label_pers_page">Նշե՛ք մարզը</label>
              <?php echo $optmarz?>   
          </div>

          <div class="col-md-2">
              <label class="label_pers_page">Նշե՛ք համայնքը</label>
              <?php echo $optcom?>
          </div>

          <div class="col-md-2">
              <label class="label_pers_page">Նշե՛ք բնակավայրը</label>
              <?php echo $optsettl?>
          </div>
        
          <div class="col-md-2">
              <label class="label_pers_page">Առանց ուղեկցողի երեխա</label>
              <input type="checkbox" class="form-control form-control-sm" name="unaccompanied_child" <?php $chk_unaccompanied_child ?> />
          </div>

          <div class="col-md-2">
              <label class="label_pers_page">Ընտանիքից անջատված երեխա</label>
              <input type="checkbox" class="form-control form-control-sm" name="separated_child" <?php $chk_separated_child ?> />
          </div>

          <div class="col-md-2">
              <label class="label_pers_page">Միայնակ ծնող</label>
              <input type="checkbox" class="form-control form-control-sm" name="single_parent" <?php $chk_single_parent ?> />
          </div>

          <div class="col-md-2">
              <label class="label_pers_page"> Փողոց  </label>
              <input type="text" class="form-control form-control-sm" value="<?php echo $address_armenia ?>" name = "street_name"  />
          </div>

          <div class="col-md-2">
              <label class="label_pers_page"> Տուն  </label>
              <input type="text" class="form-control form-control-sm" value="<?php echo $apartment ?>" name = "building"  />
          </div>

          <div class="col-md-2">
              <label class="label_pers_page"> Բնակարան  </label>
              <input type="text" class="form-control form-control-sm" value="<?php echo $apartment ?>" name = "apertment"  />
          </div>

           <div class="col-md-6">
              <label class="label_pers_page"> Հեռախոսահամար </label>
              <input type="text" class="form-control form-control-sm" value="<?php echo $contact ?>" name = "contact"  />
           </div>

           <div class="col-md-6">
              <label class="label_pers_page"> Նախընտրելի լեզու </label>
              <input type="text" class="form-control form-control-sm" value="<?php echo $language_prefered ?>" name = "pref_language"  />
           </div>
          
          <div class="col-md-12">
            <label class="label_pers_page">Լրացուցիչ մեկնաբանություն</label>
            <textarea class="form-control" rows="3" name="comment_box"><?php echo $comment ?></textarea>
          </div>
  </div> <!-- closing row -->
          <div class="row" style=" justify-content: right;  align-items: right; margin-right:10px;">
              <input type="submit" class="btn btn-success" name="save_changes"  value="ՊԱՀՊԱՆԵԼ ՓՈՓՈԽՈՒԹՅՈՒՆՆԵՐԸ" />            
          </div>
</div>	<!-- closing 1st tab -->

<div id="decisions" class="tabcontent">
            <h5 class="sub_title" style="margin-top: 5px; margin-bottom: 5px;"> Ընդունված որոշումներ </h5>
            <div class="row">
                    <div class="col-md-6">
                        <label class="label_pers_page">ՄԾ որոշում</label>
                        <?php echo $optmsdec ?>
                    </div>

                    <div class="col-md-6">
                        <label class="label_pers_page">ՄԾ որոշման ամսաթիվ</label>
                        <input type="date" class="form-control form-control-sm" name="ms_decision_date" value="<?php echo $ms_decision_date ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="label_pers_page">Վերջնական որոշում</label>
                        <?php echo $optfinaldec ?>
                    </div>

                    <div class="col-md-6">
                        <label class="label_pers_page">վերջնական որոշման ամսաթիվ</label>
                        <input type="date" class="form-control form-control-sm" name="ms_decision_date" value="<?php echo $final_decision_date ?>">
                    </div>


                    
            </div>  

                <div class="row" style=" justify-content: right;  align-items: right; margin-right:10px;">
                    <input type="submit" class="btn btn-success" name="save_changes"  value="ՊԱՀՊԱՆԵԼ ՓՈՓՈԽՈՒԹՅՈՒՆՆԵՐԸ" />            
                </div>
</div>

<div id="person" class="tabcontent">
<h5 class="sub_title" style="margin-top: 5px; margin-bottom: 5px;"> Դիմումում ընդգրկված անձինք </h5>
    <div class="col-md-12">
        <table class="table">
             <tr style=" font-size: 0.8em; color: #324157; text-align: center; vertical-align: middle;">
                    <th>անձնական #</th>
                    <th>դերը </th>
                    <th>ա.ա.հ </th>
                    <th>սեռը</th>
                    <th>ծննդյան ամսաթիվ </th>
                    <th>անձնագրի #</th>
                    <th>վկայականի #</th>
                    <th><i class="fas fa-ellipsis-h"></i></th>

            </tr>
            <?php 
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
            ?>
                <tr style="font-size: 1em; color:#324157; text-align: center;">
                    <td><?php echo $personal_id ?></td>
                    <td><?php echo $role ?></td>
                    <td><?php echo $full_name ?></td>
                    <td><?php echo $sex ?></td>
                    <td><?php echo $birthday ?></td>
                    <td><?php echo $passport ?></td>
                    <td><?php echo $id_num ?></td>
                    <td><a href="#" class="edit_old_person" old_person_id="<?php echo $personal_id?>" ><i class="far fa-eye ml-3" style="color: blue; font-size: 1.22em;"></i></a></td>
                </tr>
            <?php } ?>    
        </table>
    </div>
</div>  


</div> <!-- closing case_area -->


<div class="modal fade" id="old_case_person">  

</div>



<script>

  $(".edit_old_person").click(function(){
   
   var person_id = $(this).attr('old_person_id');
  
    $.ajax({
                url:"config/config_old.php",
                method:"POST",
                data:{edit_person_old:person_id},
                success:function(data)
                {  
                  console.log(person_id);

                   $('#old_case_person').html(data);
                   $("#old_case_person").modal({backdrop: "static"});
                    
                } 
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
		 $("#select_marz").on("change",function(){
   var marzId = $(this).val();
   //console.log(marzId);
   if (marzId) {
    $.ajax({
      url :"pages/subpages/action.php",
      type:"POST",
      cache:false,
      data:{marzId:marzId},
      success:function(data)
      {
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
 $("#select_community").on("change", function(){
   var bnakId = $(this).val();
   console.log(bnakId);
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


    var loadFile = function(event) {
                    var image = document.getElementById('output');
                    image.src = URL.createObjectURL(event.target.files[0]);
                };

$("#upload_file").on("click", function(event)
  {
    var file_type=$("#file_type_select").val();    
    if(!file_type )
    {
      alert("<Ֆայլի տիպը> պարտադիր դաշտ է");
      event.preventDefault()
      return;
    }else if(file_type == 1)
    {
      var case_file_types = $("#case_file_types").val();
      if(!case_file_types)
      {
        alert("<Ֆայլի տեսակը> պարտադիր դաշտ է");
        event.preventDefault()
        return;
      }
    }else
    {
      var case_file_types = $("#case_file_types").val();
      var select_member = $("#select_member").val();
      if(!case_file_types || !select_member)
      {
        alert("<Ֆայլի տեսակը> և <ընտանիքի անդամը> պարտադիր լրացման դաշտ են");
        event.preventDefault()
        return;
      }
    }
  })


$("#file_type_select").on("change", function(){
    var file_type_select=$("#file_type_select").val();
    var my_case = $("#case_num").val();
    $.ajax(
      {
        url:"config/config_old.php",
        method:"POST",
        data:{file_type_select,my_case},
        success:function(data)
        { 
            $('#dropdown_container').html(data);
            
        } 
      });
  })


</script>