<?php
require_once '../config/connect.php';

$case_id = $_GET['case'];


$query_case = "SELECT * from tb_case WHERE case_id = $case_id";
$result = $conn->query($query_case);

if ($result->num_rows > 0) 
{
	$row = $result->fetch_assoc();  
	$application_date = $row["application_date"];
}


$query_religia = "SELECT * FROM tb_religions";
$religia = $conn->query($query_religia);
$opt_religia = '';
while ($row_religia = mysqli_fetch_array($religia)) {  
$opt_religia = $opt_religia."<option value=$row_religia[religion_id]> $row_religia[religion_arm]</option>";}

$query_role = "SELECT * FROM tb_role";
$role = $conn->query($query_role);
$opt_role = '';
while ($row_role = mysqli_fetch_array($role)) {  
$opt_role = $opt_role."<option value=$row_role[role_id]> $row_role[der]</option>";}

$query_etnic = "SELECT * FROM tb_etnics";
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

<!DOCTYPE html>
  <html>
    <head>
    	<title>ASYLUM CMS</title>
    	<meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <!--CSS  -->
      <link rel="stylesheet" type="text/css" href="case_page.css">
      <!--  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
      <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous"> -->
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
      <!-- DataTables CSS library -->
      <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css"/>
      <link rel="preconnect" href="https://fonts.gstatic.com">
      <link href="https://fonts.googleapis.com/css2?family=Indie+Flower&family=Karantina:wght@300;700&display=swap" rel="stylesheet">
      <!--JS  -->
      <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> -->
       <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script> 
      <script src="https://kit.fontawesome.com/78ca84cc9f.js" crossorigin="anonymous"></script>
      <!-- jQuery library -->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
      <!-- DataTables JS library -->
      <script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
      <!-- Old Datatable scripts  -->
      <script  src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>
      <script  src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.flash.min.js"></script>
      <script  src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
      <script  src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
      <script  src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
      <script  src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>

      <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha512-rMGGF4wg1R73ehtnxXBt5mbUfN9JUJwbk21KMlnLZDJh7BkPmeovBuddZCENJddHYYMkCh9hPFnPmS9sspki8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" integrity="sha512-yVvxUQV0QESBt1SyZbNJMAwyKvFTLMyXSyBHDO4BG5t7k/Lw34tyqlSDlKIrIENIzCl+RVUNjmCPG+V/GMesRw==" crossorigin="anonymous" referrerpolicy="no-referrer" />


    </head>

    <style>
    	input[type="radio"] {
    -ms-transform: scale(1.5); /* IE 9 */
    -webkit-transform: scale(1.5); /* Chrome, Safari, Opera */
    transform: scale(1.5);
}
    </style>
    <body>
  <form method="POST" action="action_case.php">    
  <div class="btn_area"> 
  <div class="row">
    <div class="left_btns">
      <h5>Ապաստան հայցողի տվյալների մուտքագրում</h5>
    </div>   
    <div class="right_btns">  
      
      <button name="save_person" class="btn btn-success btn-sm r_btns" type="submit">ՊԱՀՊԱՆԵԼ</button>
      <button name="add_new_person" class="btn btn-success btn-sm r_btns" type="submit">ՆՈՐ ԱՆՁ</button>
      <button name="cancel" class="btn btn-secondary btn-sm r_btns" type="submit">ՉԵՂԱՐԿԵԼ</button>
    </div>  
  </div>
  </div>

  <div class="case_area" style="border: solid;">
   <div class="col-md-12" style="margin-top: 10px; margin-bottom: 10px; padding-bottom: 5px; padding-top: 5px;">
        <div class="row">
          <div class="col-md-3" >
            <div class="photo">
            <p><input type="file"  accept="image/*" name="image" id="file"  onchange="loadFile(event)" style="display: none;"></p>
			<p><label for="file" style="cursor: pointer;"> upload</label></p>
			<p><img id="output" width="200" /></p>

				


            </div> <!--close col-md-3-->
          </div> <!--close photo row-->
          <div class="col-md-9" >
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
                 <select class="form-control form-control-sm"  id="select_role" name="select_role" required="required"> 
                    <option>Ընտրե՛ք դերը</option>
                    <?php echo $opt_role?>
                  </select>
                
              </div>
              <div class="col-md-4">
                <label class="label_pers_page">ծննդյան ամսաթիվ</label>
                <div class="form-inline">
                <input type="number" class="form-control form-control-sm col-md-3 mr-3" min="1" max="31" placeholder="օր" name="bday">
                <input type="number" class="form-control form-control-sm col-md-3 mr-3" min="1" max="12" placeholder="ամիս" name="bmonth">
                <input type="number" class="form-control form-control-sm col-md-5" min="1900" max="2100" placeholder="տարի" name="byear" required="required">
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
                <label class="label_pers_page">անուն (հայատառ)</label>
                <input type="text" class="form-control form-control-sm" name="f_name_arm">
              </div>
              <div class="col-md-2">
                <label class="label_pers_page">ազգանուն (հայատառ)</label>
                <input type="text" class="form-control form-control-sm" name="l_name_arm">
              </div>
              <div class="col-md-2">
                <label class="label_pers_page">հայրանուն (հայատառ)</label>
                <input type="text" class="form-control form-control-sm" name="m_name_arm">
              </div>
          
              <div class="col-md-2">
                <label class="label_pers_page">անուն (լատինատառ)</label>
                <input type="text" class="form-control form-control-sm" name="f_name_eng">
              </div>
              <div class="col-md-2">
                <label class="label_pers_page">ազգանուն (լատինատառ)</label>
                <input type="text" class="form-control form-control-sm" name="l_name_eng">
              </div>
              <div class="col-md-2">
                <label class="label_pers_page">հայրանուն (լատինատառ)</label>
                <input type="text" class="form-control form-control-sm" name="m_name_eng">
              </div>
            </div> <!--close eng row-->
    
            <div class="row">
              <div class="col-md-4">
                <label class="label_pers_page">կրոն</label>
                 <select class="form-control form-control-sm" id="select_religion" name="select_religion" required="required">
                	<option>Ընտրե՛ք կրոնը</option>
                	<?php echo $opt_religia?>
                </select>
              </div>
              <div class="col-md-4">
                <label class="label_pers_page">ազգություն</label>
                <select class="form-control form-control-sm" id="select_etnic" name="select_etnic" required="required">
                	<option>Ընտրե՛ք ազգությունը</option>
                	<?php echo $opt_etnic?>
                </select>

              </div>
              <div class="col-md-4">
                <label class="label_pers_page">անձնագիր</label>
                <input type="text" class="form-control form-control-sm" name="doc_num">
              </div>
            </div> <!--close personal row-->
          </div> <!--close general row-->
          
        </div> <!--close col-md-9-->
        </div> <!--close col-md-12-->

        <div class="col-md-12" style="margin-top: 5px;">
          <div class="row">
              <div class="col-md-6">
              <h5 class="sub_title">Քաղաքացիություն || մշտական բնակության երկիր</h5>
                <div class="row">
                <div class="col-md-6">
                  <label class="label_pers_page">Քաղաքացիություն</label>
                  <select class="form-control form-control-sm" id="select_citizenship" name="select_citizenship" required="required">
                	<option>Ընտրե՛ք քաղաքացիությունը</option>
                	<?php echo $opt_citizenship?>
                	</select>

                </div>
                <div class="col-md-6">
                  <label class="label_pers_page">Նախկին մշտ․ բնակ․ երկիր</label>
                  <select class="form-control form-control-sm" id="select_residence" name="select_residence">
                	<option>Ընտրե՛ք բնակության երկիրը</option>
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
                    <th class="pers_table">Անօրինական սահմանահատում</th>
                    <th class="pers_table">Հետախուզում (դատախազ․)</th>
                    <th class="pers_table">Հետախուզում (դատարան)</th>
                  </tr>
                  <tr>
                    <td><input type="checkbox" class="form-control form-control-sm" name="illegal"></td>
                    <td><input type="checkbox" class="form-control form-control-sm" name="wanted_prosecutor"></td>
                    <td><input type="checkbox" class="form-control form-control-sm" name="wanted_court"></td>
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
    </div> <!--close case area-->
    </form>

<script>
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