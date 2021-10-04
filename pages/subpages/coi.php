<?php
require_once 'config/connect.php';

$u_id = $_SESSION['user_id'];


$query_cases = "SELECT a.coi_id, a.from_officer, a.to_coispec, a.case_id, a.request_date, a.request_deadline, a.description, a.request_text, a.coi_state, a.request_type, a.file_name, a.response_date, a.coi_status,  b.f_name AS SENDER_NAME, b.l_name AS SENDER_FNAME, d.f_name_arm AS SEEKER_N, d.l_name_arm AS SEEKER_LN, e.country_arm 
FROM tb_coi a 
INNER JOIN users b ON a.from_officer = b.id
INNER JOIN tb_case c ON a.case_id = c.case_id 
INNER JOIN tb_person d ON c.case_id = d.case_id
INNER JOIN tb_country e ON a.coi_state = e.country_id
WHERE a.coi_status = '0' AND d.role = '1'";

$query_cases_result = $conn->query($query_cases);

$query_coi_outbox = "SELECT a.coi_id, a.from_officer, a.to_coispec, a.case_id, a.request_date, a.request_deadline, a.description, a.request_text, a.coi_state, a.request_type, a.file_name, a.response_date, a.coi_status, b.f_name AS SENDER_NAME, b.l_name AS SENDER_FNAME, d.f_name_arm AS SEEKER_N, d.l_name_arm AS SEEKER_LN, e.country_arm FROM tb_coi a INNER JOIN users b ON a.from_officer = b.id INNER JOIN tb_case c ON a.case_id = c.case_id INNER JOIN tb_person d ON c.case_id = d.case_id INNER JOIN tb_country e ON a.coi_state = e.country_id WHERE a.coi_status != '0' AND d.role = '1'";
$query_coi_outbox = $conn->query($query_coi_outbox);
?>


<body>

	 <!-- Nav tabs -->
<ul class="nav nav-tabs">
   <li class="nav-item">
    <a class="nav-link active" data-toggle="tab" href="#home"><i class="fas fa-arrow-circle-down"></i> Մտից</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#menu1"><i class="fas fa-arrow-circle-up"></i> Ելից</a>
  </li>
  
 </ul>
 
 <!-- Tab panes -->
  	<div class="tab-content">
      
      <div id="home" class="tab-pane active"><br>
      		<table class="table table-stripped table-bordered" id="coi_inbox">
		<thead>
			<tr style="font-size: 0.8em; font-weight: normal; color: #828282; text-align: center;">
				<th width="5%">hարցում #</th>
				<th width="5%">գործ #</th>
				<th width="20%">ապաստան հայցողի ա․ա․հ․</th>
				<th width="10%">ծագման երկիր</th>
				<th width="10%">հարցման ամսաթիվ</th>
				<th width="10%">վերջնաժամկետ</th>
				<th width="10%">ուղարկող</th>
				<th width="15%">հարցման տեսակ</th>
				<th width="15%">կարգավիճակ</th>
			</tr>
		</thead>	
			<?php 
			while ($row = mysqli_fetch_array($query_cases_result)) {
				$sign_date = $row["request_date"];
				$new_sign_date = date("d.m.Y", strtotime($sign_date));

				$coi_dead = $row["request_deadline"];
				$coi_deadline = date("d.m.Y", strtotime($coi_dead));

				$status = '';
				if($row['coi_status'] == 0){
					$status = 'սպասում է պատասխանի';
				}
				else{
					$status = 'ավարտված';
				}

				$request_type = '';
				if ($row['request_type'] == 1) {
					$request_type = 'առաջնային հարցում';
				}
				else {
					$request_type = 'կրկնակի հարցում';
				}

				$ddate  = $row["request_deadline"];
				$actual_date = date("Y-m-d");

        		$deadline = new DateTime($ddate);
        		$today = new DateTime($actual_date);
				
				$formated_deadline = $today->diff($deadline)->format("%r%a");
          
		          if($formated_deadline > 1000 || $formated_deadline < -1000){
		            $formated_deadline = 'N/A';
		          } 

		          if ($formated_deadline < 0) {
		            $color = 'red';
		          }
		    $coi_country = $row['country_arm'];      
			?>
			<tbody>
			<tr style="font-size: 1em; color:#324157; text-align: center;" class="curs_pointer">
				
				<td><?= $row["coi_id"] ?></td>
				<td><?= $row["case_id"] ?></td>
				<td><?= $row["SEEKER_N"] .' '. $row["SEEKER_LN"] ?></td>
				<td><?php echo $coi_country ?></td>
				<td><?php echo $new_sign_date; ?></td>
				<td><font color="<?php echo $color?>" > <?php echo $formated_deadline; ?> </font></td>
				<td><?= $row["SENDER_NAME"] .' '. $row["SENDER_FNAME"] ?></td>
				<td><?php echo $request_type; ?></td>
				<td><?php echo $status; ?></td>
				

			</tr>
			
			<?php 
			} 
			?>
			</tbody>
	</table>
      </div> <!-- close home tab -->
      
      <div id="menu1" class="tab-pane fade"><br>
      	<table class="table table-stripped table-bordered" id="coi_outbox">
		<thead>
			<tr style="font-size: 0.8em; font-weight: normal; color: #828282; text-align: center;">
				<th>hարցում #</th>
				<th>գործ #</th>
				<th>ապաստան հայցողի ա․ա․հ․</th>
				<th>ծագման երկիր</th>
				<th>հարցման ամսաթիվ</th>
				<th>պատասխանի ամսաթիվ</th>
				<th>ստացող</th>
				<th>վերջնաժամկետի խախտում</th>
			</tr>
		</thead>	
			<?php 
			while ($row_out = $query_coi_outbox->fetch_assoc()) {
				$sign_date_out = $row_out["request_date"];
				$new_sign_date_out = date("d.m.Y", strtotime($sign_date_out));
				$coi_dead = $row_out["request_deadline"];		
				$coi_deadline_out = date("d.m.Y", strtotime($coi_dead));
				$coi_response_date = date("d.m.Y", strtotime($row_out["response_date"]));				
				$ddate  = $row_out["request_deadline"];
				$rdate  = $row_out["response_date"];
				$actual_date = date("Y-m-d");

					  $ddate  = $row_out["request_deadline"];
        		$deadline = new DateTime($ddate);
        		$answer   = new DateTime($rdate);
        		$today = new DateTime($actual_date);
							
							$formated_deadline_count = $answer->diff($deadline)->format("%r%a");
          
		          if($formated_deadline_count > 0 || $formated_deadline_count < -1000){
		            $formated_deadline_count = 'N/A';
		          } 

		          if ($formated_deadline_count < 0) {
		            $color = 'red';
		          }
		    $coi_country_out = $row_out['country_arm'];      
			?>
			<tbody>
			<tr style="font-size: 1em; color:#324157; text-align: center;" class="curs_pointer">
				
				<td><?= $row_out["coi_id"] ?></td>
				<td><?= $row_out["case_id"] ?></td>
				<td><?= $row_out["SEEKER_N"] .' '. $row_out["SEEKER_LN"] ?></td>
				<td><?php echo $coi_country_out ?></td>
				<td><?php echo $new_sign_date_out; ?></td>
				<td><?php echo $coi_response_date ?></td>
				<td><?= $row_out["SENDER_NAME"] .' '. $row_out["SENDER_FNAME"] ?></td>
				<td><font color="<?php echo $color?>" > <?php echo $formated_deadline_count; ?> </font></td>
				

			</tr>
			
			<?php 
			} 
			?>
			</tbody>
	</table>
      </div>	<!-- close menu1 tab -->

    </div> <!-- close tab panes -->  



	

<script>
	

	 $(document).ready(function () {             
          $('.dataTables_filter input[type="search"]').css(
          {'width':'500px','display':'inline-block'}
      );  
      });

	 var table = $('#coi_inbox').DataTable({
    		"pageLength": 25,
          	"lengthChange": false,
          	"bInfo": true,
          	"pagingType": 'full_numbers',
      		"responsive": true,
          	
          "language": {
          	 "search": "_INPUT_",            // Removes the 'Search' field label
              "searchPlaceholder": "ՈՐՈՆԵԼ",   // Placeholder for the search box
          	"paginate": {
      			"next": '<i class="fas fa-arrow-right"></i>', // or '→'
      			"previous": '<i class="fas fa-arrow-left"></i>', // or '←' 
      			"first": '<i class="fas fa-chevron-left"></i>',
      			"last": '<i class="fas fa-chevron-right"></i>'
    },
    		"info": " _PAGE_ էջ _PAGES_ ից",
    		"infoEmpty": "",
      		"zeroRecords": "Մտից ԾԵՏ հարցումներ առկա չեն։",
          }
    	});
       
      $('#coi_inbox').on( 'click', 'tr', function () {
          var coi_id = table.row( this ).data()[0];
          var case_id = table.row( this ).data()[1];

          location.replace(`user.php?page=cases&homepage=coi_page&case=${case_id}&coi=${coi_id}`);
          //alert( 'Clicked row id '+data[0]+'\'s row' );
      } );

    var table_out = $('#coi_outbox').DataTable({
    		"pageLength": 25,
          	"lengthChange": false,
          	"bInfo": true,
          	"pagingType": 'full_numbers',
      		"responsive": true,
          	
          "language": {
          	 "search": "_INPUT_",            // Removes the 'Search' field label
              "searchPlaceholder": "ՈՐՈՆԵԼ",   // Placeholder for the search box
          	"paginate": {
      			"next": '<i class="fas fa-arrow-right"></i>', // or '→'
      			"previous": '<i class="fas fa-arrow-left"></i>', // or '←' 
      			"first": '<i class="fas fa-chevron-left"></i>',
      			"last": '<i class="fas fa-chevron-right"></i>'
    },
    		"info": " _PAGE_ էջ _PAGES_ ից",
    		"infoEmpty": "",
      		"zeroRecords": "Ելից ԾԵՏ հարցումներ առկա չեն։",
          }
    	});
       
      $('#coi_outbox').on( 'click', 'tr', function () {
          var coi_id_out = table_out.row( this ).data()[0];
          var case_id_out = table_out.row( this ).data()[1];

          location.replace(`user.php?page=cases&homepage=coi_page&case=${case_id_out}&coi=${coi_id_out}`);
          //alert( 'Clicked row id '+data[0]+'\'s row' );
      } );  

</script>






</body>
