<?php
require_once 'config/connect.php';

$u_id = $_SESSION['user_id'];

$query_seekers = "SELECT a.personal_id, a.case_id, a.f_name_arm, a.l_name_arm, a.m_name_arm, a.b_day, a.b_month, a.b_year, a.sex, a.illegal_border, a.transfer_moj, a.deport_prescurator, a.prison, b.case_status, b.officer, c.deadline_type, c.deadline, c.actual_dead, d.deadline_type, e.country_arm AS NATIONALITY, f.case_status AS STATUS
FROM tb_person a 
INNER JOIN tb_case b ON a.case_id = b.case_id
INNER JOIN tb_deadline c ON b.case_id = c.case_id
INNER JOIN tb_deadline_types d ON c.deadline_type = d.deadline_type_id
LEFT JOIN tb_country e ON a.citizenship = e.country_id
INNER JOIN tb_case_status f ON b.case_status = f.case_status_id
WHERE b.case_status IN (1, 2, 4) AND c.actual_dead = 1 ";
$query_seekers_result = $conn->query($query_seekers);

?>


<body>
	<table class="table table-stripped table-bordered" id="seekers">
			<thead>
			<tr style="font-size: 0.9em; font-weight: normal; color: #828282; text-align: center; vertical-align: middle;">
			    <th width="10%">անուն</th>
				<th width="10%">ազգանուն</th>
				<th width="10%">հայրանուն</th>
				<th width="10%">սեռ</th>
				<th width="5%">տարիք</th>
				<th width="10%">քաղաքացիություն</th>
				<th width="10%">կարգավիճակ</th>
				<th width="10%">Գործ # || Անձ #</th>
				


				
			</tr>
			</thead>
			<tbody>
			<?php 
			while ($row = mysqli_fetch_array($query_seekers_result)) {
				$seeker_id = $row['personal_id'];
				$case_id   = $row['case_id'];
				$name 		 = $row['f_name_arm'];
				$surname   = $row['l_name_arm'];
				$pname     = $row['m_name_arm'];
				$gender    = 'արական';
				if ($row['sex'] == 2) {
					$gender  = 'իգական';
				}

				$curent_year = date("Y");
      	$b_year = $row['b_year'];
      	if ($b_year != "0000")
      	{
      	$age = $curent_year - $b_year;
      	}
      	else {
        $age = 'անհայտ';
      	}
      	$nation    = $row['NATIONALITY'];

      	$status    = $row['STATUS'];
      	$deadline_t  = $row['deadline_type'];
				$deadline = date('d.m.Y', strtotime($row['deadline']));
			?>
			
			<tr style="font-size: 1em; color:#324157; text-align: center; ">
			
				<td><?php echo $name ?></td>
				<td><?php echo $surname ?></td>
				<td><?php echo $pname ?></td>
				<td><?php echo $gender ?></td>
				<td><?php echo $age ?></td>
				<td><?php echo $nation ?></td>
				<td><?php echo $status?></td>
			
				<td><a href="user.php?page=cases&homepage=case_page&case=<?php echo $case_id ?>" case_id="<?php echo $case_id ?>" style="font-size: 1.4em;"> <i class="fas fa-folder-open mr-2"></i></a> || <a href="user.php?page=cases&homepage=person_page&person=<?php echo $seeker_id ?>&case=<?php echo $case_id ?>" style="font-size: 1.4em;"><i class="fas fa-user ml-2"></i></a> </td>

			</tr>
			
			<?php 
			} 
			?>
			</tbody>
	</table>
</body>

<script>
	$(document).ready(function () {             
          $('.dataTables_filter input[type="search"]').css(
          {'width':'500px','display':'inline-block'}
      );  
      });

	 var table = $('#seekers').DataTable({
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
          }
    	});
       
     
</script>