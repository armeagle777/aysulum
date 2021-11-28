<?php
require_once 'config/connect.php';
$u_id = $_SESSION['user_id'];

$query_request_inbox = "SELECT  * FROM tb_translate 
LEFT JOIN (SELECT * FROM tb_person WHERE role = 1) AS P  ON tb_translate.case_id = P.case_id
LEFT JOIN users ON tb_translate.user_from = users.id
WHERE translate_type = 3";

$request_inbox_result = $conn->query($query_request_inbox);




?>


<body>
	<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link active" data-toggle="tab" href="#home"><i class="fas fa-arrow-circle-down"></i> Նոր հարցումներ</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#menu1"><i class="fas fa-arrow-circle-up"></i> Հաստատված հարցազրույցներ</a>
  </li> 
 
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
				<th width="15%">ապաստան հայցողի ա․ա․հ․</th>
				<th width="15%">հարցազրույցի ամսաթիվ</th>
				<th width="15%">գործ վարող</th>
				<th width="20%">նախընտրելի լեզու</th>
				<th width="15%">ավելին...</th>
			</tr>
		</thead>	
		<tbody>
			<?php 
			while ($row = $request_inbox_result->fetch_assoc()) {
				$asylum_seeker  = $row['f_name_arm'] .' '. $row['l_name_arm'];
				$case_manager   = $row['f_name'] .' '. $row['l_name'];
				$process_date 	= date('d.m.Y', strtotime($row['filled_in_date']));
				$row_class 			= ' ';			
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
