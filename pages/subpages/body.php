<?php
require_once 'config/connect.php';
$u_id = $_SESSION['user_id'];

$query_request_inbox = "SELECT a.request_id, a.case_id, a.author, a.body, a.request_date, a.request_status, b.request_read, b.request_process_id, b.request_id, b.user_from, b.request_user_to, b.request_actual, b.process_date, b.process_date, b.process_status, b.process_comment, c.request_process_status_id, c.request_process_status, f.personal_id, f.f_name_arm, f.l_name_arm, f.m_name_arm, f.citizenship, f.role, g.country_arm, h.f_name AS AUTOR_NAME, h.l_name AS AUTOR_LNAME, i.body AS AUTORITY
FROM tb_request_out a 
INNER JOIN tb_request_process b ON a.request_id = b.request_id
INNER JOIN tb_request_process_status c ON c.request_process_status_id = b.process_status
INNER JOIN tb_person f ON f.case_id = a.case_id
INNER JOIN tb_country g ON g.country_id = f.citizenship
INNER JOIN users h ON h.id = a.author
INNER JOIN tb_request_bodies i ON i.body_id = a.body

WHERE b.request_actual = 1 AND b.request_user_to = $u_id AND f.role = 1 AND (b.request_read = 0 OR b.request_read = 1)";

$request_inbox_result = $conn->query($query_request_inbox);

$query_request_outbox = "SELECT a.request_id, a.case_id, a.author, a.body, a.request_date, a.request_status, b.request_process_id, b.request_id, b.user_from, b.request_user_to, b.request_actual, b.process_date, b.process_date, b.process_status, b.process_comment, c.request_process_status_id, c.request_process_status, f.personal_id, f.f_name_arm, f.l_name_arm, f.m_name_arm, f.citizenship, f.role, g.country_arm, h.f_name AS AUTOR_NAME, h.l_name AS AUTOR_LNAME, i.body AS AUTORITY
FROM tb_request_out a 
INNER JOIN tb_request_process b ON a.request_id = b.request_id
INNER JOIN tb_request_process_status c ON c.request_process_status_id = b.process_status
INNER JOIN tb_person f ON f.case_id = a.case_id
INNER JOIN tb_country g ON g.country_id = f.citizenship
INNER JOIN users h ON h.id = a.author
INNER JOIN tb_request_bodies i ON i.body_id = a.body

WHERE b.request_actual = 1 AND b.user_from = $u_id AND f.role = 1";

$request_outbox = $conn->query($query_request_outbox);


$query_request_waiting = "SELECT a.request_id, a.case_id, a.author, a.body, a.request_date, a.request_status, b.request_process_id, b.request_id, b.user_from, b.request_user_to, b.request_actual, b.process_date, b.process_date, b.process_status, b.process_comment, c.request_process_status_id, c.request_process_status, f.personal_id, f.f_name_arm, f.l_name_arm, f.m_name_arm, f.citizenship, f.role, g.country_arm, h.f_name AS AUTOR_NAME, h.l_name AS AUTOR_LNAME, i.body AS AUTORITY, j.officer
FROM tb_request_out a 
INNER JOIN tb_request_process b ON a.request_id = b.request_id
INNER JOIN tb_request_process_status c ON c.request_process_status_id = b.process_status
INNER JOIN tb_person f ON f.case_id = a.case_id
INNER JOIN tb_country g ON g.country_id = f.citizenship
INNER JOIN users h ON h.id = a.author
INNER JOIN tb_request_bodies i ON i.body_id = a.body
INNER JOIN tb_case j ON a.case_id = j.case_id

WHERE b.request_actual = 1 AND f.role = 1 AND a.author = $u_id AND a.request_status = 0";

$request_waiting = $conn->query($query_request_waiting);


?>


<body>
	<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link active" data-toggle="tab" href="#home"><i class="fas fa-arrow-circle-down"></i> Մտից</a>
  </li>
   <?php 
  if($_SESSION['role'] === 'devhead' || $_SESSION['role'] === 'nss' || $_SESSION['role'] === 'nss'){
  ?>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#menu1"><i class="fas fa-arrow-circle-up"></i> Ելից</a>
  </li>
	<?php } ?>
  <?php 
  if($_SESSION['role'] === 'officer' || $_SESSION['role'] === 'head' || $_SESSION['role'] === 'lawyer' || $_SESSION['role'] === 'coispec' || $_SESSION['role'] === 'admin'){
  ?>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#menu2"><i class="fas fa-angle-double-down"></i> Սպասում է պատասխանի</a>
  </li>
  <?php 
	}
	?>
 </ul>
 
 <!-- Tab panes -->
  	<div class="tab-content">
      
      <div id="home" class="tab-pane active"><br>
      	<form method="POST" action="config/config.php" >
	<table class="table table-stripped table-bordered" id="request_inboxes">
		<thead>
			<tr style="font-size: 0.8em; font-weight: normal; color: #828282; text-align: center;">
				<th width="3%"><button class="delbtn" id="del_btn"  name="delete_request" style="color: red;"><i class="fas fa-trash-alt fa-lg"></i></button></th>
				<th width="5%">Գործ #</th>
				<th width="5%">Հարցում #</th>
				<th width="15%">ապաստան հայցողի ա․ա․հ․</th>
				<th width="15%">քաղաքացիությունը</th>
				<th width="15%">գործ վարող</th>
				<th width="20%">մարմին</th>
				<th width="15%">ուղարկման ամսաթիվ</th>
				<th width="15%">կարգավիճակ</th>
			</tr>
		</thead>	
		<tbody>
			<?php 
			while ($row = $request_inbox_result->fetch_assoc()) {
				$asylum_seeker  = $row['f_name_arm'] .' '. $row['l_name_arm'];
				$case_manager   = $row['AUTOR_NAME'] .' '. $row['AUTOR_LNAME'];
				$process_date 	= date('d.m.Y', strtotime($row['process_date']));
				$read           = $row['request_read'];
				$row_class 			= ' ';
				if ($read == 0) 
				  {
				 	$row_class.= 'bold_tr';	
				 	} 				
			?>

			<tr style="font-size: 1em; color:#324157; text-align: center;" class="curs_pointer <?php echo $row_class?>">
				<td class="special-td" read_status="<?= $row['request_read']?>"><input type="checkbox" class="checkbox" name="request_check[]" value="<?= $row['request_id']?>" /></td>
				<td><?= $row["case_id"] ?></td>
				<td><?= $row['request_id']?></td>
				<td><?php echo $asylum_seeker ?></td>
				<td><?= $row['country_arm'] ?></td>
				<td><?php echo $case_manager ?></td>
				<td><?= $row['AUTORITY'] ?></td>
				<td><?php echo $process_date ?></td>
				<td><?= $row['request_process_status'] ?></td>
				

			</tr>
			
			<?php 
			} 
			?>
			</tbody>
	</table>
</form>
      </div> <!-- close first tab -->

      <div id="menu1" class="tab-pane fade"><br>
      	<table class="table table-stripped table-bordered" id="request_outbox">
      		<thead>
			<tr style="font-size: 0.8em; font-weight: normal; color: #828282; text-align: center;">
				<th width="5%">Գործ #</th>
				<th width="5%">Հարցում #</th>
				<th width="15%">ապաստան հայցողի ա․ա․հ․</th>
				<th width="15%">քաղաքացիությունը</th>
				<th width="15%">գործ վարող</th>
				<th width="15%">ուղարկման ամսաթիվ</th>
				<th width="15%">կարգավիճակ</th>
				<?php 
					if($_SESSION['role'] === 'officer' || $_SESSION['role'] === 'devhead'){
						?>
						<th>Մարմին</th>
				<?php	}
				?>
			</tr>
			</thead>
			<tbody>
			<?php 
			while ($row_out = $request_outbox->fetch_assoc()) {
				$asylum_seeker  = $row_out['f_name_arm'] .' '. $row_out['l_name_arm'];
				$case_manager   = $row_out['AUTOR_NAME'] .' '. $row_out['AUTOR_LNAME'];
				$process_date 	= date('d.m.Y', strtotime($row_out['process_date']));
				$authority 			= $row_out['AUTORITY'];
			?>

			<tr style="font-size: 1em; color:#324157; text-align: center;" class="curs_pointer">
				
				<td><?= $row_out["case_id"] ?></td>
				<td><?= $row_out['request_id'] ?></td>
				<td><?php echo $asylum_seeker ?></td>
				<td><?= $row_out['country_arm'] ?></td>
				<td><?php echo $case_manager ?></td>
				<td><?php echo $process_date ?></td>
				<td><?= $row_out['request_process_status'] ?></td>
				<?php 
					if($_SESSION['role'] === 'officer' || $_SESSION['role'] === 'devhead'){
						?>
					<td><?php echo $authority ?></td>
				<?php	}
				?>

			</tr>
			
			<?php 
			} 
			?>
			</tbody>
	</table>
      </div>	<!-- close second tab -->

      <div id="menu2" class="tab-pane fade"><br>
      		<table class="table table-stripped table-bordered" id="request_waiting">
      			<thead>
			<tr style="font-size: 0.8em; font-weight: normal; color: #828282; text-align: center;">
				<th width="5%">Գործ #</th>
				<th width="5%">Հարցում #</th>
				<th width="15%">ապաստան հայցողի ա․ա․հ․</th>
				<th width="15%">քաղաքացիությունը</th>
				<th width="15%">գործ վարող</th>
				<th width="15%">ուղարկման ամսաթիվ</th>
				<th width="15%">կարգավիճակ</th>
				<?php 
					if($_SESSION['role'] === 'officer' || $_SESSION['role'] === 'devhead'){
						?>
						<th>Մարմին</th>
				<?php	}
				?>
			</tr>
			</thead>
			<tbody>
			<?php 
			while ($row_out = $request_waiting->fetch_assoc()) {
				$asylum_seeker  = $row_out['f_name_arm'] .' '. $row_out['l_name_arm'];
				$case_manager   = $row_out['AUTOR_NAME'] .' '. $row_out['AUTOR_LNAME'];
				$process_date 	= date('d.m.Y', strtotime($row_out['process_date']));
				$authority 			= $row_out['AUTORITY'];
			?>

			<tr style="font-size: 1em; color:#324157; text-align: center;" class="curs_pointer">
				
				<td><?= $row_out["case_id"] ?> </td>
				<td><?= $row_out['request_id'] ?></td>
				<td><?php echo $asylum_seeker ?></td>
				<td><?= $row_out['country_arm'] ?></td>
				<td><?php echo $case_manager ?></td>
				<td><?php echo $process_date ?></td>
				<td><?= $row_out['request_process_status'] ?></td>
				<?php 
					if($_SESSION['role'] === 'officer' || $_SESSION['role'] === 'devhead'){
						?>
					<td><?php echo $authority ?></td>
				<?php	}
				?>

			</tr>
			
			<?php 
			} 
			?>
			</tbody>
	</table>
      </div>	<!-- close 3rd tab -->
    </div>  


<script>
	

	 $(document).ready(function () {             
          $('.dataTables_filter input[type="search"]').css(
          {'width':'500px','display':'inline-block'}
      );  
      });

	
	
	 var table = $('#request_inboxes').DataTable({
	
    		"pageLength": 25,
          	"lengthChange": false,
          	"bInfo": true,
          	"pagingType": 'full_numbers',
      		"responsive": true,
      		 "order": [[ 7, "desc" ]],
          	
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
      			"zeroRecords": "Ձեզ հասցեագրված հարցումներ համակարգում առկա չեն։",
          }
    	});
       
      $('#request_inboxes').on( 'click', 'td:not(.special-td)', function () {
          var inbox_case_id = table.row( this ).data()[1];
          var request_id = table.row (this).data()[2];
          location.replace(`user.php?page=cases&homepage=body_page&case=${inbox_case_id}&request=${request_id}`);
          //alert( 'Clicked row id '+read+'\'s row' );
      } );

    
    var table_outbox = $('#request_outbox').DataTable({
    		"pageLength": 25,
          	"lengthChange": false,
          	"bInfo": true,
          	"pagingType": 'full_numbers',
      		"responsive": true,
      		 "order": [[ 0, "desc" ]],
          	
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
      			"zeroRecords": "Ելից հարցումներ առկա չեն։",
          }
    	});
       
      $('#request_outbox').on( 'click', 'tr', function () {
          var data = table_outbox.row( this ).data()[0];
          var request_id = table_outbox.row (this).data()[1];
          location.replace(`user.php?page=cases&homepage=body_page&case=${data}&request=${request_id}`);
          //alert( 'Clicked row id '+data[0]+'\'s row' );
      } );

       var table_waiting = $('#request_waiting').DataTable({
    		"pageLength": 25,
          	"lengthChange": false,
          	"bInfo": true,
          	"pagingType": 'full_numbers',
      			"responsive": true,
          	"order": [[ 5, "desc" ]],
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
      			"zeroRecords": "Ընթացիկ հարցումներ առկա չեն։",
          }
    	});
       
      $('#request_waiting').on( 'click', 'tr', function () {
          var data = table_waiting.row( this ).data()[0];
          var request_id = table_waiting.row (this).data()[1];
          location.replace(`user.php?page=cases&homepage=body_page&case=${data}&request=${request_id}`);
          //alert( 'Clicked row id '+data[0]+'\'s row' );
      } );


       $('.delbtn').prop('hidden', true);

		$('.checkbox').change(function(){
    $('.delbtn').prop('hidden', $('.checkbox:checked').length == 0);
});
</script>



</body>
