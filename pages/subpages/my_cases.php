<?php
require_once 'config/connect.php';

$u_id = $_SESSION['user_id'];

$query_cases = "SELECT a.case_id, b.f_name_arm, b.l_name_arm, c.deadline, a.officer, d.request_status, f.deadline_type, h.sign_status, 
	max(case when d.body=1 then d.body end) NSS, 
	max(case when d.body=2 then d.body end) AVV, 
	max(case when d.request_status=1 and d.body = 2 then d.request_status end) AVVR, 
	max(case when d.request_status=1 and d.body = 1 then d.request_status end) NSSR, 
	max(case when e.case_id = a.case_id THEN 5 end) SCOI, 
	max(case when e.coi_status=1 then e.coi_status end) RCOI 
    
	FROM tb_case a 
	INNER JOIN tb_person b ON a.case_id = b.case_id 
	LEFT JOIN tb_deadline c ON a.case_id = c.case_id 
	LEFT JOIN tb_request_out d ON a.case_id = d.case_id
	INNER JOIN tb_deadline_types f ON c.deadline_type = f.deadline_type_id
	LEFT JOIN tb_coi e ON a.case_id = e.case_id
	INNER JOIN tb_process h ON a.case_id = h.case_id

		WHERE b.role = 1 AND  c.actual_dead = 1 AND a.officer = $u_id AND h.actual = 1 AND h.sign_status <> 16 AND a.case_status IN (1,2,4) GROUP BY a.case_id";
		$query_cases_result = $conn->query($query_cases);

	 $role_base = '';
if ($_SESSION['role']==="officer" || $_SESSION['role']==="coispec" || $_SESSION['role']==="lawyer") {
  $role_base.= " AND a.officer = $u_id";
}

$query_ceased = "SELECT a.case_id, b.f_name_arm, b.l_name_arm, a.application_date, a.mul_date, c.sign_status, d.status, c.sign_date, e.case_status, a.input_date, c.sign_by, k.deadline, 
g.l_name AS OFFICER_LNAME, g.f_name AS OFFICER_NAME
FROM tb_case a 
INNER JOIN tb_person b ON a.case_id = b.case_id 
INNER JOIN tb_process c ON a.case_id = c.case_id 
INNER JOIN tb_sign_status d ON d.status_id = c.sign_status 
INNER JOIN tb_case_status e ON a.case_status = e.case_status_id 
LEFT JOIN users g ON a.officer = g.id 
LEFT JOIN tb_deadline k ON a.case_id = k.case_id 
WHERE b.role = 1 AND c.actual = 1 AND k.actual_dead = 1 AND c.sign_status = 16 $role_base";
$query_ceased_result = $conn->query($query_ceased);	

?>




<body>
	<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link active" data-toggle="tab" href="#home"><i class="fas fa-arrow-circle-down"></i> Ընթացիկ</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#menu1"><i class="fas fa-pause"></i> Կասեցվածներ</a>
  </li>
  
 </ul>

  <!-- Tab panes -->
    <div class="tab-content">

      <div id="home" class="tab-pane active"><br>
			
			<table class="table table-stripped table-bordered" id="my_cases">
			<thead>
			<tr style="font-size: 0.9em; font-weight: normal; color: #828282; text-align: center; vertical-align: middle;">
				<th width="3%" rowspan="2">...</th>
				
				<th width="14%" rowspan="2">ապաստան հայցողի ա․ա․հ․</th>
				<th width="15%" rowspan="2">վերջնաժամկետի <br> տեսակ</th>
				<th width="9%" rowspan="2">վերջնաժամկետ</th>
				<th width="10%" colspan="2">ԾԵՏ հարցում</th>
				<th width="10%" colspan="2">ԱԱԾ հարցում</th>
				<th width="10%" colspan="2">ԱՎՎ հարցում</th>
				
			</tr>
			<tr style="font-size: 1.3em; font-weight:bold; text-align: center;">
				<th style="color: #324157;"><i class="fas fa-arrow-circle-up"></i> </th>
				<th style="color: #324157;"><i class="fas fa-arrow-circle-down"></i></th>
				<th style="color: #324157;"><i class="fas fa-arrow-circle-up"></i></th>
				<th style="color: #324157;"><i class="fas fa-arrow-circle-down"></i></th>
				<th style="color: #324157;"><i class="fas fa-arrow-circle-up"></i></th>
				<th style="color: #324157;"><i class="fas fa-arrow-circle-down"></i></th>
			</tr>
			</thead>
			<tbody>
			<?php 
			while ($row = mysqli_fetch_array($query_cases_result)) {
				
				$ddate  = $row["deadline"];
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
		          else 
		          {
		          	$color = 'black';
		          }


				$AVV_sent = '';
				if($row['AVV'] == "2"){
					$AVV_sent = 'checked';
				}
				$NSS_sent = '';
				if($row['NSS'] == "1"){
					$NSS_sent = 'checked';
				}
				$COI_sent = '';
				if($row['SCOI'] == "5"){
					$COI_sent = 'checked';
				}

				$avv_response = '';
				if($row['AVVR'] == "1"){
					$avv_response = 'checked';
				}

				$nss_response = '';
				if($row['NSSR'] == "1"){
					$nss_response = 'checked';
				}

				$COI_response = '';
				if($row['RCOI'] == "1"){
					$COI_response = 'checked';
				}
				$deadline_type = $row['deadline_type'];
			?>

			<tr style="font-size: 1.1em; color:#324157; text-align: center; " class="curs_pointer">
				<td><?= $row["case_id"] ?></td>
				<td><?= $row["f_name_arm"] .' '. $row["l_name_arm"] ?></td>
				<td><font color="<?php echo $color?>" > <?php echo $deadline_type ?> </font></td>
				<td><font color="<?php echo $color?>" > <?php echo $formated_deadline ?> </font></td>
				<td class="family_members_td"><input type="checkbox" name="COI_sent" class="form-control" onclick="return false;" <?php echo $COI_sent ?>></td>
				<td class="family_members_td"> <input type="checkbox" name="COI_response" class="form-control" onclick="return false;" <?php echo $COI_response ?>></td>
				<td class="family_members_td"><input type="checkbox" name="NSS_sent" class="form-control" onclick="return false;" <?php echo $NSS_sent ?>></td>
				<td class="family_members_td"> <input type="checkbox" name="NSS_response" class="form-control" onclick="return false;"<?php echo $nss_response ?>></td>
				<td class="family_members_td"><input type="checkbox" name="AVV_sent" class="form-control" onclick="return false;" <?php echo $AVV_sent ?>></td>
				<td class="family_members_td"> <input type="checkbox" name="AVV_response" class="form-control" onclick="return false;" <?php echo $avv_response ?>></td>

			</tr>
			
			<?php 
			} 
			?>
			</tbody>
	</table>

</div>
 <div id="menu1" class="tab-pane fade"><br>
 	<table class="table table-stripped table-bordered" id="inboxTable">
    <thead>
      <tr style="font-size: 0.9em; font-weight: normal; color: #828282; text-align: center; vertical-align: middle;">
        <th width="7%">Գործ #</th>
        <th width="14%">ապաստան հայցողի ա․ա․հ․</th>
        <th width="15%">դիմումի ամսաթիվ</th>
        <th width="15%">գրանցման ամսաթիվ</th>
        <th width="15%">կասեցման ամսաթիվ</th>
        <th width="15%">վերջնաժամկետ </th>
        <th width="10%">գործը վարող</th>        
      </tr>
    </thead>
    <tbody>
      <?php 
      while ($row = mysqli_fetch_array($query_ceased_result)) {
        $app_date  = date("d.m.Y", strtotime($row['application_date']));
        $mul_date  = date("d.m.Y", strtotime($row['mul_date']));
        $sign_date = $row["sign_date"];
        $new_sign_date = date("d.m.Y", strtotime($sign_date));
        
        $ddate  = $row["deadline"];
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

      ?>

      <tr style="font-size: 1em; color:#324157; text-align: center; " class="curs_pointer">
        <td><?= $row["case_id"] ?></td>
        <td><?= $row["f_name_arm"] .' '. $row["l_name_arm"] ?></td>
        <td><?php echo $app_date; ?></td>
        <td><?php echo $mul_date; ?></td>
        <td><?php echo $new_sign_date; ?></td>
        <td><font color="<?php echo $color?>" > <?php echo $formated_deadline ?> </font></td>
        <td><?= $row["OFFICER_NAME"] .' '. $row["OFFICER_LNAME"] ?></td>
        
        
      </tr>
      
      <?php 
        } 
      ?>
    </tbody>
  </table>
 </div>	
</div>

	<script>
	$(document).ready(function () {             
          $('.dataTables_filter input[type="search"]').css(
          {'width':'500px','display':'inline-block'}
      );  
      });

	 var table = $('#my_cases').DataTable({
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
      			"zeroRecords": "Ձեր վարույթում գործեր չկան։",
          }
    	});
       
      $('#my_cases').on( 'click', 'tr', function () {
          var data = table.row( this ).data()[0];
          location.replace(`user.php?page=cases&homepage=case_page&case=${data}`);
          //alert( 'Clicked row id '+data[0]+'\'s row' );
      } );

      var table_ceased = $('#inboxTable').DataTable({
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
      			"zeroRecords": "Ձեր վարույթում կասեցված գործեր չկան։",
          }
      });
       
      $('#inboxTable').on( 'click', 'tr', function () {
          var data = table_ceased.row( this ).data()[0];
          location.replace(`user.php?page=cases&homepage=case_page&case=${data}`);
          //alert( 'Clicked row id '+data[0]+'\'s row' );
      } );
	</script>
</body>
