<?php
require_once 'config/connect.php';
$u_id = $_SESSION['user_id'];

$query_request_inbox = "SELECT  * FROM tb_translate 
LEFT JOIN (SELECT * FROM tb_person WHERE role = 1) AS P  ON tb_translate.case_id = P.case_id
LEFT JOIN users ON tb_translate.user_from = users.id
LEFT JOIN tb_case ON tb_translate.case_id = tb_case.case_id
WHERE translate_type = 3 AND sign_status = 3 ORDER BY tb_translate.translate_id DESC";
$request_inbox_result = $conn->query($query_request_inbox);

$query_request_outbox = "SELECT  * FROM tb_translate 
LEFT JOIN (SELECT * FROM tb_person WHERE role = 1) AS P  ON tb_translate.case_id = P.case_id
LEFT JOIN tb_country ON P.citizenship = tb_country.country_id 
LEFT JOIN users ON tb_translate.user_from = users.id
LEFT JOIN tb_case ON tb_translate.case_id = tb_case.case_id
WHERE translate_type = 3 AND sign_status IN (4,5) ORDER BY tb_translate.translate_id DESC";
$request_outbox_result = $conn->query($query_request_outbox);



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
		<table class="table table-stripped table-bordered" id="request_inboxes">
			<thead>
				<tr style="font-size: 0.8em; font-weight: normal; color: #828282; text-align: center;">
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

					<tr style="font-size: 1em; color:#324157; text-align: center;">				
						<td><?= $row["case_id"] ?></td>
						<td><?php echo $asylum_seeker ?></td>
						<td><?= $process_date ; ?></td>				
						<td><?= $case_manager; ?></td>
						<td><?= $row['prefered_language'] ?></td>
						<td>
							<button type="button" class="btn btn-sm btn-outline-success save_translation" translation_id="<?php echo $row["translate_id"]; ?>"><i class="fa fa-save"></i></button>	
							<button type="button" class="btn btn-sm btn-outline-danger delete_translation"  translation_id="<?php echo $row["translate_id"]; ?>"><i class="fas fa-trash-alt"></i></button>					
						</td>
					</tr>
			
				<?php 
				} 
				?>
			</tbody>
		</table>
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
					<th width="15%">ավելին...</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					while ($row_out = $request_outbox_result->fetch_assoc()) {
						$asylum_seeker  = $row_out['f_name_arm'] .' '. $row_out['l_name_arm'];
						$case_manager   = $row_out['f_name'] .' '. $row_out['l_name'];
						$process_date 	= date('d.m.Y', strtotime($row_out['filled_in_date']));
				?>

				<tr style="font-size: 1em; color:#324157; text-align: center;">				
					<td><?= $row_out["case_id"] ?></td>
					<td><?= $row_out['translate_id'] ?></td>
					<td><?php echo $asylum_seeker ?></td>
					<td><?= $row_out['country_arm'] ?></td>
					<td><?php echo $case_manager; ?></td>
					<td><?php echo $process_date ?></td>
					<td>
						<?php
							if($row_out["sign_status"] === '4'):
						?>
							<button class="btn btn-sm btn-outline-success approve_translation" translation_id="<?php echo $row_out["translate_id"]; ?>"><i class="fa fa-check"></i></button>	
							<button class="btn btn-sm btn-outline-danger delete_translation" translation_id="<?php echo $row_out["translate_id"]; ?>"><i class="fas fa-trash-alt"></i></button>
						<?php
							elseif($row_out["sign_status"] === '5'):
						?>
							<button type="button" class="btn btn-sm btn-outline-warning pay_translation" translation_id="<?php echo $row_out["translate_id"]; ?>"><i class="fas fa-dollar-sign"></i></button>
						<?php
							endif;
						?>
					</td>
				</tr>
			
				<?php 
				} 
				?>
			</tbody>
		</table>
	</div>	<!-- close second tab -->
    </div>  


<script>
	

	 $(document).ready(function () {  

		//*! translation delete, save, pay and approve buttons functions
		$(document).on('click','.save_translation', function(){
			var translate_id = $(this).attr('translation_id');
			$.ajax(
				{
					url: "config/config.php?cmd=save_translation",
					method: "POST",
					data: {translate_id},
					success: function (data) {
						if(data === 'Translation modified'){
							location.reload();
						}
						// $('#approve_special_type').html(data);
						// $("#approve_special_type").modal({backdrop: "static"});
					}
				});
		})
		$(document).on('click','.delete_translation', function(){
			var translate_id = $(this).attr('translation_id');
			$.ajax(
				{
					url: "config/config.php?cmd=delete_translation",
					method: "POST",
					data: {translate_id},
					success: function (data) {
						if(data === 'Translation modified'){
							location.reload();
						}
						// console.log(data);
						// $('#approve_special_type').html(data);
						// $("#approve_special_type").modal({backdrop: "static"});
					}
				});
		})
		$(document).on('click','.pay_translation', function(){
			var translate_id = $(this).attr('translation_id');
			$.ajax(
				{
					url: "config/config.php?cmd=pay_translation",
					method: "POST",
					data: {translate_id},
					success: function (data) {
						console.log(data);
						// $('#approve_special_type').html(data);
						// $("#approve_special_type").modal({backdrop: "static"});
					}
				});
		})
		$(document).on('click','.approve_translation', function(){
			var translate_id = $(this).attr('translation_id');
			$.ajax(
				{
					url: "config/config.php?cmd=approve_translation",
					method: "POST",
					data: {translate_id},
					success: function (data) {
						if(data === 'Translation modified'){
							location.reload();
						}
						// $('#approve_special_type').html(data);
						// $("#approve_special_type").modal({backdrop: "static"});
					}
				});
		})
      });

	
	
	//  var table = $('#request_inboxes').DataTable({
	
    // 		"pageLength": 25,
    //       	"lengthChange": false,
    //       	"bInfo": true,
    //       	"pagingType": 'full_numbers',
    //   		"responsive": true,
    //   		 "order": [[ 7, "desc" ]],
          	
    //       "language": {
    //       	 "search": "_INPUT_",            // Removes the 'Search' field label
    //           "searchPlaceholder": "ՈՐՈՆԵԼ",   // Placeholder for the search box
    //       	"paginate": {
    //   			"next": '<i class="fas fa-arrow-right"></i>', // or '→'
    //   			"previous": '<i class="fas fa-arrow-left"></i>', // or '←' 
    //   			"first": '<i class="fas fa-chevron-left"></i>',
    //   			"last": '<i class="fas fa-chevron-right"></i>'
    // },
    // 				"info": " _PAGE_ էջ _PAGES_ ից",
    // 				"infoEmpty": "",
    //   			"zeroRecords": "Ձեզ հասցեագրված հարցումներ համակարգում առկա չեն։",
    //       }
    // 	});
       
    //   $('#request_inboxes').on( 'click', 'td:not(.special-td)', function () {
    //       var inbox_case_id = table.row( this ).data()[1];
    //       var request_id = table.row (this).data()[2];
    //       location.replace(`user.php?page=cases&homepage=body_page&case=${inbox_case_id}&request=${request_id}`);
    //       //alert( 'Clicked row id '+read+'\'s row' );
    //   } );

    
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

       
       
    //   $('#request_waiting').on( 'click', 'tr', function () {
    //       var data = table_waiting.row( this ).data()[0];
    //       var request_id = table_waiting.row (this).data()[1];
    //       location.replace(`user.php?page=cases&homepage=body_page&case=${data}&request=${request_id}`);
    //       //alert( 'Clicked row id '+data[0]+'\'s row' );
    //   } );


       $('.delbtn').prop('hidden', true);

</script>



</body>
