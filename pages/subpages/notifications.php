<?php
require_once 'config/connect.php';

$u_id = $_SESSION['user_id'];

$query_request_inbox = "SELECT a.comment_id, a.comment_subject, a.comment_text, a.comment_status, a.comment_from, a.comment_to, a.case_id, a.coi_id, a.request_id, a.note_type, a.draft_id, a.order_id, a.readed, a.note_date, b.case_status, c.case_status AS CASE_STATUS_TEXT, f.personal_id, f.f_name_arm, f.l_name_arm,  g.l_name AS SENDER_LNAME, g.f_name AS SENDER_NAME
FROM tb_notifications a 
INNER JOIN tb_case b ON a.case_id = b.case_id
INNER JOIN tb_case_status c ON b.case_status = c.case_status_id
INNER JOIN tb_person f ON f.case_id = a.case_id
INNER JOIN users g ON a.comment_from = g.id

WHERE
f.role = 1 AND a.readed = 0 AND a.comment_to = $u_id AND b.case_status != 3 ORDER BY a.comment_id DESC";

$request_inbox_result = $conn->query($query_request_inbox);

?>

<body>
	<form method="POST" action="config/config.php">

	
 	<table class="table table-stripped table-bordered" id="cases_notify">
			<thead>
			<tr style="font-size: 0.8em; font-weight: normal; color: #828282; text-align: center;">
				<th width="3%"><button class="delbtn" id="del_btn"  name="delete_note" style="color: red;"><i class="fas fa-trash-alt fa-lg"></i></button></th>
				<th width="15%">Տեսակ</th>
				<th width="5%">Գործ №</th>
				<th width="15%">Վերնագիր</th>
				<th width="10%">Ուղարկող</th>
				<th width="10%">Ամսաթիվ</th>
				<th width="15%">Ապաստան հայցողի ա.ա.հ.</th>
				<th width="15%">Գործի կարգավիճակ</th>
				
			</tr>
			</thead>
			<tbody>
			<?php 
			while ($row = $request_inbox_result->fetch_assoc()) {
				$doc_type 			= '';
				if ($row['note_type'] == 1) {
					$doc_type 			= 'Ընդհանուր ընթացակարգ';
				}
				if ($row['note_type'] == 2) {
					$doc_type 			= 'ԾԵՏ հարցումներ';
				}
				if ($row['note_type'] == 3) {
					$doc_type 			= 'Հարցումներ այլ մարմիններ';
				}
				if ($row['note_type'] == 4) {
					$doc_type 			= 'Որոշման նախագիծ';
				}
				if ($row['note_type'] == 5) {
					$doc_type 			= 'Կացարանի ուղեգիր';
				}
				$case_id 					= $row['case_id'];
				$coi_id 					= $row['coi_id'];
				$request_id 			= $row['request_id'];
				$draft_id 				= $row['draft_id'];
				$order_id 				= $row['order_id'];
				$subject					= $row['comment_subject'];
				$sender 					= $row['SENDER_NAME'] . ' ' . $row['SENDER_LNAME'];
				$note_date 				= date('d.m.Y', strtotime($row['note_date']));
				$case_status 			= $row['CASE_STATUS_TEXT'];
				
				$asylum_seeker  	= $row['f_name_arm'] .' '. $row['l_name_arm'];
				$note_id 					= $row['comment_id'];
				
			?>

			<tr style="font-size: 1em; color:#324157; text-align: center;"  class="curs_pointer row-links">
				<td class="special-td"><input type="checkbox" class="checkbox" name="note_check[]" id="check_all" value="<?php echo $note_id ?>" /></td>
				<td case_id="<?php echo $case_id ?>" request_id="<?php echo $request_id ?>" draft_id="<?php echo $draft_id ?>" order_id="<?php echo $order_id ?>" coi_id="<?php echo $coi_id ?>" note_type="<?= $row['note_type'] ?>"><?php echo $doc_type ?> </td>
				<td case_id="<?php echo $case_id ?>" request_id="<?php echo $request_id ?>" draft_id="<?php echo $draft_id ?>" order_id="<?php echo $order_id ?>" coi_id="<?php echo $coi_id ?>" note_type="<?= $row['note_type'] ?>"><?php echo $case_id ?></td>
				<td case_id="<?php echo $case_id ?>" request_id="<?php echo $request_id ?>" draft_id="<?php echo $draft_id ?>" order_id="<?php echo $order_id ?>" coi_id="<?php echo $coi_id ?>" note_type="<?= $row['note_type'] ?>"><?php echo $subject ?></td>
				<td case_id="<?php echo $case_id ?>" request_id="<?php echo $request_id ?>" draft_id="<?php echo $draft_id ?>" order_id="<?php echo $order_id ?>" coi_id="<?php echo $coi_id ?>" note_type="<?= $row['note_type'] ?>"><?php echo $sender ?></td>
				<td case_id="<?php echo $case_id ?>" request_id="<?php echo $request_id ?>" draft_id="<?php echo $draft_id ?>" order_id="<?php echo $order_id ?>" coi_id="<?php echo $coi_id ?>" note_type="<?= $row['note_type'] ?>"><?php echo $note_date ?></td>
				<td case_id="<?php echo $case_id ?>" request_id="<?php echo $request_id ?>" draft_id="<?php echo $draft_id ?>" order_id="<?php echo $order_id ?>" coi_id="<?php echo $coi_id ?>" note_type="<?= $row['note_type'] ?>"><?php echo $asylum_seeker ?></td>
				<td case_id="<?php echo $case_id ?>" request_id="<?php echo $request_id ?>" draft_id="<?php echo $draft_id ?>" order_id="<?php echo $order_id ?>" coi_id="<?php echo $coi_id ?>" note_type="<?= $row['note_type'] ?>"><?php echo $case_status ?></td>
				
   		</tr>
			
			<?php 
			} 
			?>
			</tbody>
		</table>
</form>
<script>
	$(document).ready(function () {             
          $('.dataTables_filter input[type="search"]').css(
          {'width':'500px','display':'inline-block'}
      );  
      });

	 var table = $('#cases_notify').DataTable({
    		"pageLength": 20,
          "lengthChange": false,
          "pagingType": 'full_numbers',
      		"responsive": true,
      		 "order": [[ 5, "acs" ]],
          	
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
      			"zeroRecords": "Նամականիում Ձեզ հասցեագրված գրություններ չկան։",
          }
    	

    	});

	 
       
      
	 $(document).on('click', 'td:not(.special-td)', function(){
		   var note_id = $(this).attr('note_type');
		   var case_id = $(this).attr('case_id');
   	if(note_id == 1){
   		location.replace(`user.php?page=cases&homepage=case_page&case=${case_id}`);
   	}
   	if(note_id == 2){
   		 var coi_id = $(this).attr('coi_id');	
   		location.replace(`user.php?page=cases&homepage=coi_page&case=${case_id}&coi=${coi_id}`);
   	}
   	if(note_id == 3){
   		 var request_id = $(this).attr('request_id');	
   		location.replace(`user.php?page=cases&homepage=body_page&case=${case_id}&request=${request_id}`);
   	}
   	if(note_id == 4){
   		 var draft_id = $(this).attr('draft_id');	
   		 location.replace(`user.php?page=cases&homepage=draft_page&draft=${draft_id}&case=${case_id}`);
   	}
   	if(note_id == 5){
   		 var order_id = $(this).attr('order_id');	
   		  location.replace(`user.php?page=cases&homepage=order_page&case=${case_id}&order=${order_id}`);
   	}
});

	 $('.delbtn').prop('hidden', true);

		$('.checkbox').change(function(){
    $('.delbtn').prop('hidden', $('.checkbox:checked').length == 0);
});

	</script>




</body>
