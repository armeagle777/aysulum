<?php
require_once 'config/connect.php';

$u_id = $_SESSION['user_id'];

$sql_claims = "SELECT a.claim_id, a.claim_date, a.uploaded_by, a.upload_date, a.case_id, a.court_id, a.claim_actual, b.court_title, d.status, e.f_name_arm, e.l_name_arm, a.initiator 
FROM tb_court_claim a 
INNER JOIN tb_courts b ON a.court_id = b.court_id
INNER JOIN tb_process c ON a.case_id = c.case_id
INNER JOIN tb_sign_status d ON c.sign_status = d.status_id
INNER JOIN tb_person e ON a.case_id = e.case_id
WHERE a.claim_id NOT IN (SELECT claim_id FROM tb_appeals) AND c.actual = 1 AND e.role = 1 AND a.initiator = 1";
$result_sql_claims = $conn->query($sql_claims);

$sql_claims_out = "SELECT a.claim_id, a.claim_date, a.uploaded_by, a.upload_date, a.case_id, a.court_id, a.claim_actual, b.court_title, d.status, e.f_name_arm, e.l_name_arm, a.initiator 
FROM tb_court_claim a 
INNER JOIN tb_courts b ON a.court_id = b.court_id
INNER JOIN tb_process c ON a.case_id = c.case_id
INNER JOIN tb_sign_status d ON c.sign_status = d.status_id
INNER JOIN tb_person e ON a.case_id = e.case_id
WHERE a.claim_id NOT IN (SELECT claim_id FROM tb_appeals) AND c.actual = 1 AND e.role = 1 AND a.initiator = 2";
$result_sql_claims_out = $conn->query($sql_claims_out);

$sql_appeals = "SELECT a.appeal_id, a.case_id, a.claim_id, a.court_accept_date, a.apeal_status, a.filled_in, a.filled_by, a.court_decision, a.court_level, a.court_name, a.appeal_type, b.f_name_arm, b.l_name_arm, c.court_title, d.appeal_type AS APPEAL_TYPE_TEXT, f.status
FROM tb_appeals a 
INNER JOIN tb_person b ON a.case_id = b.case_id
INNER JOIN tb_courts c ON a.court_level = c.court_id
INNER JOIN tb_appeal_types d ON d.appeal_type_id = a.appeal_type
INNER JOIN tb_process e ON a.case_id = e.case_id
INNER JOIN tb_sign_status f ON e.sign_status = f.status_id
WHERE a.apeal_status = 0 AND b.role = 1 AND e.actual = 1";
$result_appeals = $conn->query($sql_appeals);

$sql_appeals_first_instanse = "SELECT a.appeal_id, a.case_id, a.claim_id, a.court_accept_date, a.apeal_status, a.filled_in, a.filled_by, a.court_decision, a.court_level, a.court_name, a.appeal_type, b.f_name_arm, b.l_name_arm, c.court_title, d.appeal_type AS APPEAL_TYPE_TEXT, f.status, h.MS_lawyer, i.f_name, i.l_name
FROM tb_appeals a 
INNER JOIN tb_person b ON a.case_id = b.case_id
INNER JOIN tb_case h ON a.case_id = h.case_id
INNER JOIN users i ON h.MS_lawyer = i.id
INNER JOIN tb_courts c ON a.court_level = c.court_id
INNER JOIN tb_appeal_types d ON d.appeal_type_id = a.appeal_type
INNER JOIN tb_process e ON a.case_id = e.case_id
INNER JOIN tb_sign_status f ON e.sign_status = f.status_id
WHERE a.apeal_status = 0 AND b.role = 1 AND e.actual = 1 AND court_level = 1";
$result_appeals_first_instance = $conn->query($sql_appeals_first_instanse);

?>


<body>
	 <!-- Nav tabs -->
<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link active" data-toggle="tab" href="#home"><i class="fas fa-arrow-circle-down"></i> Ստացվել է հայցադիմում</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#menu_outbox"><i class="fas fa-arrow-circle-up"></i> Ներկայացվել է հայցադիմում</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#menu1"><i class="fas fa-gavel"></i> Ընդունվել է վարույթ</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#menu2"><i class="fas fa-angle-double-down"></i> Ընդհանուր իրավասության վարչական դատարան</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#menu2"><i class="fas fa-angle-double-down"></i> Վարչական վերաքննիչ դատարան</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#menu2"><i class="fas fa-angle-double-down"></i> Վճռաբեկ դատարան</a>
  </li>
 </ul>
 
 <!-- Tab panes -->
  	<div class="tab-content">
      
      <div id="home" class="tab-pane active"><br>

	<table class="table table-stripped table-bordered" id="inbox">
			<thead>
			<tr style="font-size: 0.9em; font-weight: normal; color: #828282; text-align: center; vertical-align: middle;">
				<th width="7%">Գործ #</th>
				<th width="10%">ծանուցագրի ամսաթիվ</th>
				<th width="15%">ապաստան հայցողի ա․ա․հ․</th>
				<th width="15%">ատյան</th>
				<th width="15%">գործառույթ</th>
				
				
			</tr>
			</thead>
			<?php 
			while ($row = $result_sql_claims->fetch_assoc()) {
				
			$notification_date = date('d.m.Y', strtotime($row['claim_date']));

				
			?>
			<tbody>
			<tr style="font-size: 1em; color:#324157; text-align: center; " class="curs_pointer">
				<td><?= $row["case_id"] ?></td>
				<td><?php echo $notification_date; ?></td>
				<td><?= $row["f_name_arm"] .' '. $row["l_name_arm"] ?></td>
				<td><?= $row["court_title"] ?></td>
				<td><?= $row["status"] ?></td>
				
			</tr>
			</tbody>
			<?php 
			} 
			?>

	</table>
	</div> <!-- close home tab  -->

	<div id="menu_outbox" class="tab-pane fade"><br>

	<table class="table table-stripped table-bordered" id="outbox">
			<thead>
			<tr style="font-size: 0.9em; font-weight: normal; color: #828282; text-align: center; vertical-align: middle;">
				<th width="7%">Գործ #</th>
				<th width="10%">ծանուցագրի ամսաթիվ</th>
				<th width="15%">ապաստան հայցողի ա․ա․հ․</th>
				<th width="15%">ատյան</th>
				<th width="15%">գործառույթ</th>
				
				
			</tr>
			</thead>
			<?php 
			while ($row_outbox = $result_sql_claims_out->fetch_assoc()) {
			

			$notification_date = date('d.m.Y', strtotime($row_outbox['claim_date']));

				
			?>
			<tbody>
			<tr style="font-size: 1em; color:#324157; text-align: center; " class="curs_pointer">
				<td><?= $row_outbox["case_id"] ?></td>
				<td><?php echo $notification_date; ?></td>
				<td><?= $row_outbox["f_name_arm"] .' '. $row_outbox["l_name_arm"] ?></td>
				<td><?= $row_outbox["court_title"] ?></td>
				<td><?= $row_outbox["status"] ?></td>
				
			</tr>
			</tbody>
			<?php 
			} 
			?>

	</table>
	</div> <!-- close menu 0 -->


      <div id="menu1" class="tab-pane fade"><br>
      	<table class="table table-stripped table-bordered" id="accepted">
			<thead>
			<tr style="font-size: 0.9em; font-weight: normal; color: #828282; text-align: center; vertical-align: middle;">
				<th width="10%">Գործ #</th>
				<th width="5%"> վարույթ ընդունելու ամսաթիվ</th>
				<th width="15%">ապաստան հայցողի ա․ա․հ․</th>
				<th width="20%">ատյան</th>
				<th width="20%">դատարան</th>
				<th width="20%">գործառույթ</th>
				
				

			</tr>
			</thead>
			<?php 
			while ($row = $result_appeals->fetch_assoc()) {
				
			$accept_date = date('d.m.Y', strtotime($row['court_accept_date']));

				
			?>
			<tbody>
			<tr style="font-size: 1em; color:#324157; text-align: center; " class="curs_pointer">
				<td><?= $row["case_id"] ?></td>
				<td><?php echo $accept_date; ?></td>
				<td><?= $row["f_name_arm"] .' '. $row["l_name_arm"] ?></td>
				<td><?= $row["court_title"] ?></td>
				<td><?= $row["court_name"] ?></td>
				<td><?= $row["status"] ?></td>
			
			</tr>
			</tbody>
			<?php 
			} 
			?>

	</table>
      </div>

      <div id="menu2" class="tab-pane fade"><br>
      	<table class="table table-stripped table-bordered" id="first_instance">
			<thead>
			<tr style="font-size: 0.9em; font-weight: normal; color: #828282; text-align: center; vertical-align: middle;">
				<th width="10%">Գործ #</th>
				<th width="5%"> վարույթ ընդունելու ամսաթիվ</th>
				<th width="15%">ապաստան հայցողի ա․ա․հ․</th>
				<th width="20%">դատարան</th>
				<th width="20%">գործառույթ</th>
				<th>ՄԾ ներկայացուցիչ</th>
				
				

			</tr>
			</thead>
			<?php 
			while ($row = $result_appeals_first_instance->fetch_assoc()) {
				
			$accept_date = date('d.m.Y', strtotime($row['court_accept_date']));
			$ms_representative = $row['f_name'] .' '. $row['l_name'];			
			?>
			<tbody>
			<tr style="font-size: 1em; color:#324157; text-align: center; " class="curs_pointer">
				<td><?= $row["case_id"] ?></td>
				<td><?php echo $accept_date; ?></td>
				<td><?= $row["f_name_arm"] .' '. $row["l_name_arm"] ?></td>
				<td><?= $row["court_name"] ?></td>
				<td><?= $row["status"] ?></td>
				<td><?php echo $ms_representative ?></td>
			
			</tr>
			</tbody>
			<?php 
			} 
			?>

	</table>
      </div>	

<script>
	

	 $(document).ready(function () {             
          $('.dataTables_filter input[type="search"]').css(
          {'width':'500px','display':'inline-block'}
      );  
      });

	 var table = $('#outbox').DataTable({
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
      			"zeroRecords": "Ծանուցագրեր չկան։",
          }
    	});
       
      $('#outbox').on( 'click', 'tr', function () {
          var data = table.row( this ).data()[0];
          location.replace(`user.php?page=cases&homepage=case_page&case=${data}`);
          //alert( 'Clicked row id '+data[0]+'\'s row' );
      } );

      var table_inbox = $('#inbox').DataTable({
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
      			"zeroRecords": "Ծանուցագրեր չկան։",
          }
    	});
       
      $('#inbox').on( 'click', 'tr', function () {
          var data = table_inbox.row( this ).data()[0];
          location.replace(`user.php?page=cases&homepage=case_page&case=${data}`);
          //alert( 'Clicked row id '+data[0]+'\'s row' );
      } );

			

      var table_accepted = $('#accepted').DataTable({
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
      			"zeroRecords": "Վարույթ ընդունված հայցադիմումներ չկան։",
          }
    	});
       
      $('#accepted').on( 'click', 'tr', function () {
          var data = table_accepted.row( this ).data()[0];
          location.replace(`user.php?page=cases&homepage=case_page&case=${data}`);
          //alert( 'Clicked row id '+data[0]+'\'s row' );
      } );


      var table_first_instance = $('#first_instance').DataTable({
    		"pageLength": 25,
          	"lengthChange": false,
          	"bInfo": true,
          	"pagingType": 'full_numbers',
      		"responsive": false,
          	
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
      			"zeroRecords": "Ընդհանուր իրավասության վարչական դատարանում վարույթ ընդունված գործեր չկան։",
          }
    	});
       
      $('#first_instance').on( 'click', 'tr', function () {
          var data = table_first_instance.row( this ).data()[0];
          location.replace(`user.php?page=cases&homepage=case_page&case=${data}`);
          //alert( 'Clicked row id '+data[0]+'\'s row' );
      } );

</script>
</body>



