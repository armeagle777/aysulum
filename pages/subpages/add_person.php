<?php
require_once 'config/connect.php';

$case_id = $_GET['case'];


$query_case = "SELECT * from tb_case WHERE case_id = $case_id";
$result = $conn->query($query_case);

if ($result->num_rows > 0) 
{
	$row = $result->fetch_assoc();  
	$application_date = $row["application_date"];
  $special_check    = $row['special'];
}


$query_religia = "SELECT * FROM tb_religions";
$religia = $conn->query($query_religia);
$opt_religia = '';
while ($row_religia = mysqli_fetch_array($religia)) {  
$opt_religia = $opt_religia."<option value=$row_religia[religion_id]> $row_religia[religion_arm]</option>";}

$opt_role = '<option value="0">Ընտրե՛ք դերը</option>';

$sql_familyHead="SELECT * FROM tb_person WHERE case_id = $case_id";
$result_familyHead=$conn->query($sql_familyHead);
if($result_familyHead ->num_rows < 1 )
{
  $opt_role='<option value="1">Գլխավոր</option>';
}else
{
  $query_role = "SELECT * FROM tb_role WHERE role_id != 1";
  $role = $conn->query($query_role);
  while ($row_role = mysqli_fetch_array($role)) 
  {  
    $opt_role.="<option value='".$row_role['role_id']."' >".$row_role['der']." </option>";
  }
}

$query_etnic = "SELECT * FROM tb_etnics ORDER BY etnic_eng ASC";
$etnic = $conn->query($query_etnic);
$opt_etnic = '';
while ($row_etnic = mysqli_fetch_array($etnic)) {  
$opt_etnic = $opt_etnic."<option value=$row_etnic[etnic_id]> $row_etnic[etnic_eng]</option>";}

$query_citizenship	= "SELECT * FROM tb_country";
$citizenship = $conn->query($query_citizenship);
$opt_citizenship = '';
while ($row_citizenship = mysqli_fetch_array($citizenship)) {  
$opt_citizenship = $opt_citizenship."<option value=$row_citizenship[country_id]> $row_citizenship[country_arm]</option>";}


$query_residency = "SELECT * FROM tb_country";
$residency = $conn->query($query_residency);
$opt_residency = '';
while ($row_residency = mysqli_fetch_array($residency)) {  
$opt_residency = $opt_residency."<option value=$row_residency[country_id]> $row_residency[country_arm]</option>";}

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
  <div class="btn_area"> 
  <div class="row">
    <div class="left_btns">
      <h5>Ապաստան հայցողի տվյալների մուտքագրում</h5>
    </div>   
    <div class="right_btns">  
      
      <button name="save_person" id="save_person" class="btn btn-success btn-sm r_btns" type="submit">ՊԱՀՊԱՆԵԼ</button>
      <button name="new_person" class="btn btn-success btn-sm r_btns" type="submit">ՆՈՐ ԱՆՁ</button>
      <a href="user.php?page=cases&homepage=case_page&case=<?php echo $case_id ?>" name="cancel" class="btn btn-secondary btn-sm r_btns">ՉԵՂԱՐԿԵԼ</a>
    </div>  
  </div>
  </div>

 
   <div class="col-md-12" style="margin-top: 10px; margin-bottom: 10px; padding-bottom: 5px; padding-top: 5px;">
        <div class="row">
          <div class="col-md-2" >
            <div class="photo">			
              <p><img id="output" width="180px" height="218px" /></p>
              <label for="photo_upload" style="cursor: pointer;" id="pPhoto_label"><i class="fas fa-edit"></i></label>				
              <p><input type="file" name="file" id="photo_upload" accept="image/*" id="file"  onchange="loadFile(event)" style="display: none;"></p>
            </div>
          </div>
          <div class="col-md-10" >
            <h5 class="sub_title">Ընդհանուր տեղեկություններ</h5>  
            <div class="row">
              <div class="col-md-4">
                <label class="label_pers_page">Գործ #</label>
                <input type="text" class="form-control form-control-sm" name="case_id" value="<?php echo $case_id?>" readonly>
              </div> 
              <div class="col-md-4">
                <label class="label_pers_page">Դիմումի ամսաթիվ</label>
                <input type="text" class="form-control form-control-sm" name="application_date" value="<?php echo $application_date?>" readonly>
              </div>
              <div class="col-md-4">
                <label class="label_pers_page">ՀՀ ժամանման ամսաթիվ</label>
                <input type="date" class="form-control form-control-sm" name="arrival_date">
              </div> 
            </div>
            <h5 class="sub_title">Անձնական տվյալներ</h5>
            <div class="row">
              <div class="col-md-4">
                <label class="label_pers_page">Դերը</label>
                <select class="form-control form-control-sm"  id="select_role" name="select_role" >                     
                  <?php echo $opt_role?>
                </select>                
              </div>
              <div class="col-md-4">
                <label class="label_pers_page">ծննդյան ամսաթիվ</label>
                <div class="form-inline">
                  <input type="number" class="form-control form-control-sm col-md-3 mr-3" min="00" minlength="2" max="31" placeholder="օր" name="bday" onchange="if(parseInt(this.value,10)<10>1)this.value='0'+this.value;">
                  <input type="number" class="form-control form-control-sm col-md-3 mr-3" min="00" minlength="2" max="12" placeholder="ամիս" name="bmonth" onchange="if(parseInt(this.value,10)<10>1)this.value='0'+this.value;">
                  <input type="number" class="form-control form-control-sm col-md-5" min="0000" max="2100" placeholder="տարի" name="byear" required="required">
                </div>
              </div>
              <div class="col-md-4">
                <label class="label_pers_page">սեռ</label> <br>
                <div class="form-check form-check-inline">
 					<input class="form-check-input" type="radio" name="select_sex" id="inlineRadio1" value="1" required="required">
  					<label class="form-check-label" for="inlineRadio1">արական</label>
				</div>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="select_sex" id="inlineRadio2" value="2">
					<label class="form-check-label" for="inlineRadio2">իգական</label>
				</div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-2">
                <label class="label_pers_page">անուն<span class="err_msg" id="fname_am_error">գրել հայատառ, և=եվ</span></label>
                <input type="text" class="form-control form-control-sm" name="f_name_arm" id="f_name_arm" placeholder="հայատառ" required="required">
              </div>
              <div class="col-md-2">
                <label class="label_pers_page">ազգանուն <span class="err_msg" id="lname_am_error">գրել հայատառ, և=եվ</span></label>
                <input type="text" class="form-control form-control-sm" name="l_name_arm" id="l_name_arm" placeholder="հայատառ" required="required">
              </div>
              <div class="col-md-2">
                <label class="label_pers_page">հայրանուն <span class="err_msg" id="mname_am_error">գրել հայատառ, և=եվ</span></label>
                <input type="text" class="form-control form-control-sm" name="m_name_arm" id="m_name_arm" placeholder="հայատառ">
              </div>
          
              <div class="col-md-2">
                <label class="label_pers_page">անուն<span class="err_msg" id="fname_eng_error">գրել լատինատառ</span></label>
                <input type="text" class="form-control form-control-sm" name="f_name_eng" id="f_name_eng" placeholder="լատինատառ" required="required">
              </div>
              <div class="col-md-2">
                <label class="label_pers_page">ազգանուն<span class="err_msg" id="lname_eng_error">գրել լատինատառ</span></label>
                <input type="text" class="form-control form-control-sm" name="l_name_eng" id="l_name_eng" placeholder="լատինատառ" required="required">
              </div>
              <div class="col-md-2">
                <label class="label_pers_page">հայրանուն<span class="err_msg" id="mname_eng_error">գրել լատինատառ</span></label>
                <input type="text" class="form-control form-control-sm" name="m_name_eng" id="m_name_eng" placeholder="լատինատառ">
              </div>
            </div> <!--close eng row-->
    
            <div class="row">
              <div class="col-md-4">
                <label class="label_pers_page">կրոն</label>
                 <select class="form-control form-control-sm" id="select_religion" name="select_religion" required="required">
                	<option value="0">Ընտրե՛ք կրոնը</option>
                	<?php echo $opt_religia?>
                </select>
              </div>
              <div class="col-md-4">
                <label class="label_pers_page">ազգություն</label>
                <select class="form-control form-control-sm" id="select_etnic" name="select_etnic">
                	<option value="0">Ընտրե՛ք ազգությունը</option>
                	<?php echo $opt_etnic?>
                </select>

              </div>
              <div class="col-md-4">
                <label class="label_pers_page">անձնագիր</label>
                <input type="text" class="form-control form-control-sm" name="doc_num">
              </div>
            </div>
          </div>          
        </div> 
        </div>

        <div class="col-md-12" style="margin-top: 5px;">
          <div class="row">
              <div class="col-md-6">
              <h5 class="sub_title">Քաղաքացիություն || մշտական բնակության երկիր</h5>
                <div class="row">
                <div class="col-md-6">
                  <label class="label_pers_page">Քաղաքացիություն</label>
                  <select class="form-control form-control-sm" id="select_citizenship" name="select_citizenship" >
                	<option value="0">Ընտրե՛ք քաղաքացիությունը</option>
                	<?php echo $opt_citizenship?>
                	</select>

                </div>
                <div class="col-md-6">
                  <label class="label_pers_page">Նախկին մշտ․ բնակ․ երկիր</label>
                  <select class="form-control form-control-sm" id="select_residence" name="select_residence">
                	<option value="0">Ընտրե՛ք բնակության երկիրը</option>
                	<?php echo $opt_residency?>
                	</select>
                </div>
                <div class="col-md-6">
                  <label class="label_pers_page">Հասցեն քաղ․ երկրում</label>
                  <input type="text" class="form-control form-control-sm" name="adr_citizen">
                </div>
                <div class="col-md-6">
                  <label class="label_pers_page">Հասցեն նախ․ բնակ․ երկրում</label>
                  <input type="text" class="form-control form-control-sm" name="adr_res">
                </div>
                <div class="col-md-6">
                  <label class="label_pers_page">Քաղ․ երկիրը լքելու ամսաթիվ</label>
                  <input type="text" class="form-control form-control-sm" name="citizen_departure_date">
                </div>
                <div class="col-md-6">
                  <label class="label_pers_page">Բնակ․ երկիրը լքելու ամսաթիվ</label>
                  <input type="text" class="form-control form-control-sm" name="res_departure_date">
                </div>
                </div>
              <h5 class="sub_title" style="margin-top: 10px;">Հատուկ նշումներ</h5>
                <table class="table">
                  <tr>
                    <th class="b_table">Ապօրինի սահմանահատում</th>
                    <th class="b_table">Արտահանձնում (Դատախազ․)</th>
                    <th class="b_table">Փոխանցում (Արդարադատ.)</th>
                    <th class="b_table">ՔԿՀ</th>

                  </tr>
                  <tr>
                    <td><input type="checkbox" class="form-control form-control-sm" name="illegal"></td>
                    <td><input type="checkbox" class="form-control form-control-sm" name="deport_prosecutor"></td>
                    <td><input type="checkbox" class="form-control form-control-sm" name="transfer_moj"></td>
                    <td><input type="checkbox" class="form-control form-control-sm" name="prison"></td>
                  </tr>
                </table>

              <h5 class="sub_title" style="margin-top: 10px;">Լրացուցիչ նշումներ</h5> 
              <textarea class="form-control form-control-sm" rows="5" name="comment_2"></textarea>
              </div>
              <div class="col-md-6">
                <h5 class="sub_title">Հատուկ կարիքներ</h5>
                <table class="table">
                  <tr>
                    <th class="table_a">Հաշմանդամ</th>
                    <td><input type="checkbox" class="form-control form-control-sm" name="invalid"></td>
                  </tr>  
                  <tr>
                    <th class="table_a">Հղի կին</th>
                    <td><input type="checkbox" class="form-control form-control-sm" id="pregnant" name="pregnant"></td>
                  </tr>   
                  <tr>
                    <th class="table_a">Ծանր հիվանդ</th>
                    <td><input type="checkbox" class="form-control form-control-sm" name="ill"></td>
                  </tr>
                  <tr>
                    <th class="table_a">Թրաֆիկինգի զոհ</th>
                    <td><input type="checkbox" class="form-control form-control-sm" name="trafiking"></td>
                  </tr> 
                  <tr>
                    <th class="table_a">Բռնության զոհ</th>
                    <td><input type="checkbox" class="form-control form-control-sm" name="violence_victim"></td>
                  </tr>
                  <tr>
                    <th class="table_a">Նախընտրելի հարցազրուցավարի սեռը</th>
                    <td style="font-size: 1.0em; text-align: center;">
                    <div class="form-check form-check-inline" >
 						<input class="form-check-input" type="radio" name="interview_sex" id="interview_sex1" value="1" required="required">
  						<label class="form-check-label" for="interview_sex1">արական</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" id="interview_sex2" name="interview_sex"  value="2">
						<label class="form-check-label" for="interview_sex2">իգական</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="interview_sex" id="interview_sex3"  value="3">
						<label class="form-check-label" for="interview_sex3">ցանկացած</label>
					</div>
                   </td>
                  </tr> 
                  <tr>
                    <th class="table_a">Նախընտրելի թարգմանչի սեռը</th>
                    <td style="font-size: 1.0em; text-align: center;">
                    <div class="form-check form-check-inline" >
 						<input class="form-check-input" type="radio" name="translator_sex" id="translator_sex1" value="1" required="required">
  						<label class="form-check-label" for="translator_sex1">արական</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="translator_sex"  id="translator_sex2" value="2">
						<label class="form-check-label" for="translator_sex2">իգական</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="translator_sex" id="translator_sex3" value="3">
						<label class="form-check-label" for="translator_sex3">ցանկացած</label>
					</div>
                   </td>
                  </tr>                          
                </table>
              </div> 
          </div>
        </div><!--close col-md-12-->

    </form>

<script>
  document.addEventListener("DOMContentLoaded", function()
  {
    var elements = document.getElementsByTagName("INPUT");
    for (var i = 0; i < elements.length; i++)
    {
        elements[i].oninvalid = function(e) {
            e.target.setCustomValidity("");
            if (!e.target.validity.valid) {
                e.target.setCustomValidity("Պարտադիր լրացման դաշտ է");
            }
        };
        elements[i].oninput = function(e) {
            e.target.setCustomValidity("");
        };
    }
  })


  $("#save_person").on("click", function(e)
  {
    let err_msg='';
    var files = document.getElementById('photo_upload').files.length;
    let select_role = $("#select_role").val();
    let select_religion = $("#select_religion").val();
    let select_etnic = $("#select_etnic").val();
    let select_citizenship = $("#select_citizenship").val();
    let f_name_arm = $("#f_name_arm").val().trim();
    let l_name_arm = $("#l_name_arm").val().trim();
    let m_name_arm = $("#m_name_arm").val().trim();
    let f_name_eng = $("#f_name_eng").val().trim();
    let l_name_eng = $("#l_name_eng").val().trim();
    let m_name_eng = $("#m_name_eng").val().trim();
    $("#fname_am_error").css("visibility", "hidden");
    $("#lname_am_error").css("visibility", "hidden");
    $("#mname_am_error").css("visibility", "hidden");
    $("#fname_eng_error").css("visibility", "hidden");
    $("#lname_eng_error").css("visibility", "hidden");
    $("#mname_eng_error").css("visibility", "hidden");
    for(let i = 0; i< f_name_arm.length; i++)
    {
      if((f_name_arm.charCodeAt(i)<1329 && f_name_arm.charCodeAt(i) != 32) || f_name_arm.charCodeAt(i)> 1414 || (f_name_arm.charCodeAt(i)>1366 && f_name_arm.charCodeAt(i)<1377))
      {
        $("#fname_am_error").css("visibility", "visible");
        e.preventDefault();
      }
    }
    for(let i = 0; i< l_name_arm.length; i++)
    {
      if((l_name_arm.charCodeAt(i)<1329 && l_name_arm.charCodeAt(i) != 32) || l_name_arm.charCodeAt(i)> 1414 || (l_name_arm.charCodeAt(i)>1366 && l_name_arm.charCodeAt(i)<1377))
      {
        $("#lname_am_error").css("visibility", "visible");
        e.preventDefault();
      }
    }
    for(let i = 0; i< m_name_arm.length; i++)
    {
      if((m_name_arm.charCodeAt(i)<1329 && m_name_arm.charCodeAt(i) != 32) || m_name_arm.charCodeAt(i)> 1414 || (m_name_arm.charCodeAt(i)>1366 && m_name_arm.charCodeAt(i)<1377))
      {
        $("#mname_am_error").css("visibility", "visible");
        e.preventDefault();
      }
    }
    for(let i = 0; i< f_name_eng.length; i++)
    {
      if((f_name_eng.charCodeAt(i)<65 && f_name_eng.charCodeAt(i) != 32) || f_name_eng.charCodeAt(i)> 122 || (f_name_eng.charCodeAt(i)>90 && f_name_eng.charCodeAt(i)<97))
      {
        $("#fname_eng_error").css("visibility", "visible");
        e.preventDefault();
      }
    }
    for(let i = 0; i< l_name_eng.length; i++)
    {
      if((l_name_eng.charCodeAt(i)<65 && l_name_eng.charCodeAt(i) != 32) || l_name_eng.charCodeAt(i)> 122 || (l_name_eng.charCodeAt(i)>90 && l_name_eng.charCodeAt(i)<97))
      {
        $("#lname_eng_error").css("visibility", "visible");
        e.preventDefault();
      }
    }
    for(let i = 0; i< m_name_eng.length; i++)
    {
      if((m_name_eng.charCodeAt(i)<65 && m_name_eng.charCodeAt(i) != 32) || m_name_eng.charCodeAt(i)> 122 || (m_name_eng.charCodeAt(i)>90 && m_name_eng.charCodeAt(i)<97))
      {
        $("#mname_eng_error").css("visibility", "visible");
        e.preventDefault();
      }
    }
    
    if( files>=1)
    {
      var file = document.getElementById('photo_upload').files[0];
      var fileType = file["type"];
      var imageUploaded=fileType.search('image');
      if(imageUploaded < 0)
      {
        e.preventDefault();
        err_msg+='Սխալ ֆայլ է ընտրված որպես նկար';
        alert(err_msg);
      }
    }
    if(select_role==0 ||  select_citizenship==0 || files<1)
    {
      e.preventDefault();
      if(select_role==0)
      {
        err_msg+='դերը, ';
      }
      if(select_citizenship==0)
      {
        err_msg+='քաղաքացիությունը, ';
      }
      if(files<1)
      {
        err_msg+='դիմումատուի նկարը ';
      }
      err_msg+=' ընտրված չեն։';
      alert(err_msg);
    }
  })
	$("#select_religion").chosen();
	$("#select_role").chosen();
	$("#select_etnic").chosen();
	$("#select_citizenship").chosen();
	$("#select_residence").chosen();

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
				var loadFile = function(event) {
					var image = document.getElementById('output');
					image.src = URL.createObjectURL(event.target.files[0]);
				};
				</script>
</body>
</html>