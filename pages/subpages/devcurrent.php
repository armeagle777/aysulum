<?php
require_once 'config/connect.php';

$u_id = $_SESSION['user_id'];

$query_cases = "SELECT a.case_id, b.f_name_arm, b.l_name_arm, c.sign_status, d.status, c.sign_date, e.case_status, a.input_date, c.sign_by, f.f_name AS SIGNER_NAME, f.l_name AS SIGNER_LNAME, a.officer, g.f_name AS OFFICER_NAME, g.l_name AS OFFICER_LNAME, c.processor, h.f_name AS PROCESSOR_NAME, h.l_name AS PROCESSOR_LNAME, k.deadline FROM tb_case a INNER JOIN tb_person b ON a.case_id = b.case_id INNER JOIN tb_process c ON a.case_id = c.case_id INNER JOIN tb_sign_status d ON d.status_id = c.sign_status INNER JOIN tb_case_status e ON a.case_status = e.case_status_id INNER JOIN users f ON c.sign_by = f.id LEFT JOIN users g ON a.officer = g.id INNER JOIN users h ON c.processor = h.id LEFT JOIN tb_deadline k ON a.case_id = k.case_id WHERE b.role = 1 AND c.actual = 1 AND k.actual_dead = 1 AND a.case_status = 1 AND c.sign_status != 16 ORDER BY k.deadline ASC";
$query_cases_result = $conn->query($query_cases);



 $role_base = '';
if ($_SESSION['role']==="officer" || $_SESSION['role']==="coispec" ) {
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



$sql_wait_apeal = "SELECT a.case_id, b.f_name_arm, b.l_name_arm, a.application_date, a.mul_date, c.sign_status, d.status, c.sign_date, e.case_status, a.input_date, c.sign_by, k.deadline, 
g.l_name AS OFFICER_LNAME, g.f_name AS OFFICER_NAME
FROM tb_case a 
INNER JOIN tb_person b ON a.case_id = b.case_id 
INNER JOIN tb_process c ON a.case_id = c.case_id 
INNER JOIN tb_sign_status d ON d.status_id = c.sign_status 
INNER JOIN tb_case_status e ON a.case_status = e.case_status_id 
LEFT JOIN users g ON a.officer = g.id 
LEFT JOIN tb_deadline k ON a.case_id = k.case_id 
WHERE b.role = 1 AND c.actual = 1 AND k.actual_dead = 1 AND a.case_status = 4 $role_base";
$query_wait_result = $conn->query($sql_wait_apeal);

?>


<body>
	 <!-- Nav tabs -->
<ul class="nav nav-tabs">
   <li class="nav-item">
    <a class="nav-link active" data-toggle="tab" href="#home"><i class="fas fa-running"></i> Ընթացիկ</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#menu1"><i class="fas fa-pause"></i> Կասեցված</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#menu2"><i class="fas fa-history"></i> Սպասում են բողոքարկման</a>
  </li>
  
 </ul>
 
 <!-- Tab panes -->
  	<div class="tab-content">
      
      <div id="home" class="tab-pane active"><br>


	<table class="table table-stripped table-bordered" id="dev_curent_cases">
		<thead>
			<tr style="font-size: 0.8em; font-weight: normal; color: #828282; text-align: center;">
				<th width="7%">Գործ #</th>
				<th width="14%">ապաստան հայցողի ա․ա․հ․</th>
				<th width="9%">գործառույթ</th>
				<th width="7%">գործառույթի ամսաթիվ</th>
				<th width="10%">գրանցման ամսաթիվ</th>
				<th width="10%">վերջնաժամկետ</th>
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

				$deadline = $row["deadline"];
				$formated_deadline = date("d.m.Y", strtotime($deadline));
				$today = date('Y-m-d');
				if ($today > $deadline  ) {
					$color = 'red';
				}
				else {
					$color = 'green';
				}


			?>
		
			<tr style="font-size: 1em; color:#324157; text-align: center;" class="curs_pointer">
				<td><?= $row["case_id"] ?></td>
				<td><?= $row["f_name_arm"] .' '. $row["l_name_arm"] ?></td>
				<td><?= $row["status"] ?></td>
				<td><?php echo $new_sign_date; ?></td>
				<td><?php echo $new_input; ?></td>
				<td><font color="<?php echo $color?>" > <?php echo $formated_deadline?>  </font></td>
				
				<td><?= $row["OFFICER_NAME"] .' '. $row["OFFICER_LNAME"] ?></td>
				<td><?= $row["PROCESSOR_NAME"] .' '.$row["PROCESSOR_LNAME"] ?></td>

			</tr>
			
			<?php 
			} 
			?>
		</tbody>
	</table>
	</div> <!-- close home tab -->
      
  <div id="menu1" class="tab-pane fade"><br>
 		<table class="table table-stripped table-bordered" id="dev_ceased">
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

  <div id="menu2" class="tab-pane fade"><br>
  	<table class="table table-stripped table-bordered" id="dev_wait">
    <thead>
      <tr style="font-size: 0.9em; font-weight: normal; color: #828282; text-align: center; vertical-align: middle;">
        <th width="7%">Գործ #</th>
        <th width="14%">ապաստան հայցողի ա․ա․հ․</th>
        <th width="15%">դիմումի ամսաթիվ</th>
        <th width="15%">գրանցման ամսաթիվ</th>
        <th width="15%">մերժման ամսաթիվ</th>
        <th width="15%">բողոքարկման շրջանի ավարտ </th>
        <th width="10%">գործը վարող</th>        
      </tr>
    </thead>
    <tbody>
      <?php 
      while ($row = $query_wait_result->fetch_assoc()) {
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

</div> <!-- close tab panes -->  	




	<script>
		$(document).ready(function () {             
          $('.dataTables_filter input[type="search"]').css(
          {'width':'500px','display':'inline-block'}
      );  
      });

	 var table = $('#dev_curent_cases').DataTable({
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
      		"zeroRecords": "Ընթացիկ գործեր չկա։",
          }
    	});
       
      $('#dev_curent_cases').on( 'click', 'tr', function () {
          var data = table.row( this ).data()[0];
          location.replace(`user.php?page=cases&homepage=case_page&case=${data}`);
          //alert( 'Clicked row id '+data[0]+'\'s row' );
      } );

      var table_ceased = $('#dev_ceased').DataTable({
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
       
      $('#dev_ceased').on( 'click', 'tr', function () {
          var data = table_ceased.row( this ).data()[0];
          location.replace(`user.php?page=cases&homepage=case_page&case=${data}`);
          //alert( 'Clicked row id '+data[0]+'\'s row' );
      } );

      var table_wait = $('#dev_wait').DataTable({
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
       
      $('#dev_wait').on( 'click', 'tr', function () {
          var data = table_wait.row( this ).data()[0];
          location.replace(`user.php?page=cases&homepage=case_page&case=${data}`);
          //alert( 'Clicked row id '+data[0]+'\'s row' );
      } );

	</script>
</body>


