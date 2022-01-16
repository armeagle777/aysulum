<?php
require_once 'config/connect.php';

$u_id = $_SESSION['user_id'];

$query_cases = "SELECT a.case_id, b.f_name_arm, b.l_name_arm, c.sign_status, d.status, c.sign_date, e.case_status, a.input_date, c.sign_by, c.comment_status, f.f_name AS SIGNER_NAME, f.l_name AS SIGNER_LNAME, a.officer, g.f_name AS OFFICER_NAME, g.l_name AS OFFICER_LNAME, c.processor, h.f_name AS PROCESSOR_NAME, h.l_name AS PROCESSOR_LNAME, k.deadline 
	FROM tb_case a 
	INNER JOIN tb_person b ON a.case_id = b.case_id 
	INNER JOIN tb_process c ON a.case_id = c.case_id 
	INNER JOIN tb_sign_status d ON d.status_id = c.sign_status 
	INNER JOIN tb_case_status e ON a.case_status = e.case_status_id 
	INNER JOIN users f ON c.sign_by = f.id 
	LEFT JOIN users g ON a.officer = g.id 
	INNER JOIN users h ON c.processor = h.id 
	LEFT JOIN tb_deadline k ON a.case_id = k.case_id 
	WHERE b.role = 1 AND c.actual = 1 AND k.actual_dead = 1 AND c.processor = $u_id AND a.case_status IN (1,2,5) AND c.sign_status != 16";
$query_cases_result = $conn->query($query_cases);

?>


<body>
	<table class="table table-stripped table-bordered" id="inboxTable">
		<thead>
			<tr style="font-size: 0.9em; font-weight: normal; color: #828282; text-align: center; vertical-align: middle;">
				<th width="7%">Գործ #</th>
				<th width="10%">ապաստան հայցողի ա․ա․հ․</th>
				<th width="15%">գործառույթ</th>
				<th width="7%">գործառույթի ամսաթիվ</th>
				<th width="10%">գրանցման ամսաթիվ</th>
				<th width="10%">վերջնաժամկետ</th>
				<th width="10%">ուղարկող</th>
				<th width="10%">գործը վարող</th>
				<th width="10%">գործը տնօրինող</th>
				
			</tr>
		</thead>
		<tbody>
			<?php 
			while ($row = mysqli_fetch_array($query_cases_result)) {
				$sign_date = $row["sign_date"];
				$new_sign_date = date("d.m.Y", strtotime($sign_date));

				$input = $row["input_date"];
				$new_input = date("d.m.Y", strtotime($input));

				$deadline = date('d.m.Y', strtotime($row["deadline"]));

				$read           = $row['comment_status'];
				$row_class 			= ' ';
				if ($read == 0) 
				  {
				 	$row_class.= 'bold_tr';	
				 	} 				
			?>

			<tr style="font-size: 1em; color:#324157; text-align: center; " class="curs_pointer <?php echo $row_class?>">
				<td><?= $row["case_id"] ?></td>
				<td><?= $row["f_name_arm"] .' '. $row["l_name_arm"] ?></td>
				<td><?= $row["status"] ?></td>
				<td><?php echo $new_sign_date; ?></td>
				<td><?php echo $new_input; ?></td>
				<td><?php echo $deadline ?></td>
				<td><?= $row["SIGNER_NAME"] .' '. $row["SIGNER_LNAME"] ?></td>
				<td><?= $row["OFFICER_NAME"] .' '. $row["OFFICER_LNAME"] ?></td>
				<td><?= $row["PROCESSOR_NAME"] .' '.$row["PROCESSOR_LNAME"] ?></td>
				
			</tr>
			
			<?php 
				} 
			?>
		</tbody>
	</table>

<script>
	

	 $(document).ready(function () {             
          $('.dataTables_filter input[type="search"]').css(
          {'width':'500px','display':'inline-block'}
      );  
      });

	 var table = $('#inboxTable').DataTable({
    		"pageLength": 25,
          	"lengthChange": false,
          	"bInfo": true,
          	"pagingType": 'full_numbers',
          	 "order": [[ 0, "desc" ]],
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
      			"zeroRecords": "Մտից գրություններ չկան։",
          }
    	});
       
      $('#inboxTable').on( 'click', 'tr', function () {
          var data = table.row( this ).data()[0];
          location.replace(`user.php?page=cases&homepage=case_page&case=${data}`);
          //alert( 'Clicked row id '+data[0]+'\'s row' );
      } );

</script>
</body>
