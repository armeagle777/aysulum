<?php
require_once 'config/connect.php';

$u_id = $_SESSION['user_id'];

$query_all = "SELECT a.case_id, b.f_name_arm, b.l_name_arm, a.application_date, c.deadline, a.officer, f.deadline_type, h.sign_status, i.f_name, i.l_name, a.officer, b.illegal_border, b.transfer_moj, b.deport_prescurator, b.prison FROM tb_case a INNER JOIN tb_person b ON a.case_id = b.case_id LEFT JOIN tb_deadline c ON a.case_id = c.case_id INNER JOIN tb_deadline_types f ON c.deadline_type = f.deadline_type_id INNER JOIN tb_process h ON a.case_id = h.case_id LEFT JOIN users i ON i.id = a.officer 
WHERE b.role = 1 AND c.actual_dead = 1 AND h.actual = 1 AND a.case_status IN (1,2,4) AND a.special = 1";
if ($_SESSION['role'] === 'officer' || $_SESSION['role'] === 'coispec' || $_SESSION['role'] === 'lawyer' ) {
  $query_all.= " AND a.officer = $u_id";
}
$query_all_result = $conn->query($query_all);

$query_transfer = "SELECT a.case_id, b.f_name_arm, b.l_name_arm, a.application_date, c.deadline, a.officer, f.deadline_type, h.sign_status, i.f_name AS OFFICER_NAME, i.l_name AS OFFICER_LNAME, a.officer, b.illegal_border, h.processor, j.f_name AS PROCESSOR_NAME, j.l_name AS PROCESSOR_LNAME, k.status
	FROM tb_case a 
	INNER JOIN tb_person b ON a.case_id = b.case_id 
	LEFT JOIN tb_deadline c ON a.case_id = c.case_id 
	INNER JOIN tb_deadline_types f ON c.deadline_type = f.deadline_type_id 
	INNER JOIN tb_process h ON a.case_id = h.case_id 
	LEFT JOIN users i ON i.id = a.officer 
	LEFT JOIN users j ON h.processor = j.id
    INNER JOIN tb_sign_status k ON h.sign_status = k.status_id
	WHERE b.role = 1 AND c.actual_dead = 1 AND h.actual = 1 AND a.case_status IN (1,2,4) AND b.transfer_moj = 1";
  if ($_SESSION['role'] === 'officer' || $_SESSION['role'] === 'coispec' || $_SESSION['role'] === 'lawyer' ) {
  $query_transfer.= " AND a.officer = $u_id";
}
$query_transfer = $conn->query($query_transfer);		

$query_deport = "SELECT a.case_id, b.f_name_arm, b.l_name_arm, a.application_date, c.deadline, a.officer, f.deadline_type, h.sign_status, i.f_name AS OFFICER_NAME, i.l_name AS OFFICER_LNAME, a.officer, b.illegal_border, h.processor, j.f_name AS PROCESSOR_NAME, j.l_name AS PROCESSOR_LNAME, k.status
	FROM tb_case a 
	INNER JOIN tb_person b ON a.case_id = b.case_id 
	LEFT JOIN tb_deadline c ON a.case_id = c.case_id 
	INNER JOIN tb_deadline_types f ON c.deadline_type = f.deadline_type_id 
	INNER JOIN tb_process h ON a.case_id = h.case_id 
	LEFT JOIN users i ON i.id = a.officer 
	LEFT JOIN users j ON h.processor = j.id
    INNER JOIN tb_sign_status k ON h.sign_status = k.status_id
	WHERE b.role = 1 AND c.actual_dead = 1 AND h.actual = 1 AND a.case_status IN (1,2,4) AND b.deport_prescurator = 1";
 if ($_SESSION['role'] === 'officer' || $_SESSION['role'] === 'coispec' || $_SESSION['role'] === 'lawyer' ) {
  $query_deport.= " AND a.officer = $u_id";
}
$query_deport = $conn->query($query_deport);		

$query_prison = "SELECT a.case_id, b.f_name_arm, b.l_name_arm, a.application_date, c.deadline, a.officer, f.deadline_type, h.sign_status, i.f_name AS OFFICER_NAME, i.l_name AS OFFICER_LNAME, a.officer, b.illegal_border, h.processor, j.f_name AS PROCESSOR_NAME, j.l_name AS PROCESSOR_LNAME, k.status
	FROM tb_case a 
	INNER JOIN tb_person b ON a.case_id = b.case_id 
	LEFT JOIN tb_deadline c ON a.case_id = c.case_id 
	INNER JOIN tb_deadline_types f ON c.deadline_type = f.deadline_type_id 
	INNER JOIN tb_process h ON a.case_id = h.case_id 
	LEFT JOIN users i ON i.id = a.officer 
	LEFT JOIN users j ON h.processor = j.id
    INNER JOIN tb_sign_status k ON h.sign_status = k.status_id
	WHERE b.role = 1 AND c.actual_dead = 1 AND h.actual = 1 AND a.case_status IN (1,2,4) AND b.prison = 1";
  if ($_SESSION['role'] === 'officer' || $_SESSION['role'] === 'coispec' || $_SESSION['role'] === 'lawyer' ) {
  $query_prison.= " AND a.officer = $u_id";
}
$query_prison = $conn->query($query_prison);		

$query_ilegal = "SELECT a.case_id, b.f_name_arm, b.l_name_arm, a.application_date, c.deadline, a.officer, f.deadline_type, h.sign_status, i.f_name AS OFFICER_NAME, i.l_name AS OFFICER_LNAME, a.officer, b.illegal_border, h.processor, j.f_name AS PROCESSOR_NAME, j.l_name AS PROCESSOR_LNAME, k.status
	FROM tb_case a 
	INNER JOIN tb_person b ON a.case_id = b.case_id 
	LEFT JOIN tb_deadline c ON a.case_id = c.case_id 
	INNER JOIN tb_deadline_types f ON c.deadline_type = f.deadline_type_id 
	INNER JOIN tb_process h ON a.case_id = h.case_id 
	LEFT JOIN users i ON i.id = a.officer 
	LEFT JOIN users j ON h.processor = j.id
    INNER JOIN tb_sign_status k ON h.sign_status = k.status_id
	WHERE b.role = 1 AND c.actual_dead = 1 AND h.actual = 1 AND a.case_status IN (1,2,4) AND b.illegal_border = 1";
  if ($_SESSION['role'] === 'officer' || $_SESSION['role'] === 'coispec' || $_SESSION['role'] === 'lawyer' ) {
  $query_ilegal.= " AND a.officer = $u_id";
}
$query_ilegal = $conn->query($query_ilegal);		


?>




<body>
	<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link active" data-toggle="tab" href="#home"><i class="fas fa-circle-notch"></i> Հատուկ գործեր</a>
    
  </li>

  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#menu1"><i class="fas fa-random"></i> Փոխանցում</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#menu2"><i class="fas fa-plane-departure"></i> Արտահանձնում</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#menu3"><i class="fas fa-border-all"></i> ՔԿՀ</a>
  </li>
   <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#menu4"><i class="fas fa-ban"></i> Ապօրինի սահմանահատում</a>
  </li>
  
 </ul>
 
 <!-- Tab panes -->
    <div class="tab-content">
      
      <div id="home" class="tab-pane active"><br>
        <?php 
         if ($_SESSION['role'] === 'officer' || $_SESSION['role'] === 'lawyer' || $_SESSION['role'] === 'coispec') {
        ?>
        <h5 class="mt-3">Իմ վարույթում գտնվող հատուկ գործերի ցանկ</h5>
        <?php 
        }
        else{ 
        ?>
         <h5 class="mt-3">Հատուկ գործերի ցանկ</h5>
        <?php }?>
      	
      	<table class="table" id="all_special">
      		<thead>
      		<tr style="font-size: 0.9em; font-weight: normal; color: #828282; text-align: center; vertical-align: middle;">
      			<th width="5%">Գործ #</th>
      			<th width="15%">Ապաստան հայցողի ա.ա.հ.</th>
      			<th width="15%">դիմումի ամսաթիվ</th>
      			<th width="15%">վերջնաժամկետ</th>
      			<th width="15%">գործ վարող</th>
      			<th width="10%">անօրինական սահմանահատում</th>
      			<th width="10%">փոխանցում</th>
      			<th width="10%">արտահանձնում</th>
      			<th width="10%">ՔԿՀ</th>
      		</tr>
      		</thead>
      		<tbody>
      		<?php 
      		while($row_all = $query_all_result->fetch_assoc()){
      			$case_id 			= $row_all['case_id'];
      			$asylum_seeker 		= $row_all['f_name_arm'] .' '. $row_all['l_name_arm'];
      			$application_date   = date('d.m.Y', strtotime($row_all['application_date']));
      			$application_dead   = date('d.m.Y', strtotime($row_all['deadline']));
      			$case_officer 			= 'նշանակված չէ'; 
      			if(!empty($row_all['officer'])){
      			$case_officer 			= $row_all['f_name'] .' '. $row_all['l_name']; 
      			}
      			$ilegal_border = '';
      			if($row_all['illegal_border'] == 1){
      				$ilegal_border = 'checked';
      			}

      			$transfer = '';
      			if($row_all['transfer_moj'] == 1){
      				$transfer = 'checked';
      			}

      			$deport = '';
      			if($row_all['deport_prescurator'] == 1){
      				$deport = 'checked';
      			}

      			$prison = '';
      			if($row_all['prison']){
      				$prison = 'checked';
      			}
      		
      		?>
      		<tr class="curs_pointer" style="text-align:center;">
      			<td><?php echo $case_id ?></td>
      			<td><?php echo $asylum_seeker ?></td>
      			<td><?php echo $application_date ?></td>
      			<td><?php echo $application_dead ?></td>
      			<td><?php echo $case_officer ?></td>
      			<td><input type="checkbox" name="" <?php echo $ilegal_border ?>> </td>
      			<td><input type="checkbox" name="" <?php echo $transfer ?>></td>
      			<td><input type="checkbox" name="" <?php echo $deport ?>></td>
      			<td><input type="checkbox" name="" <?php echo $prison ?>></td>

      		</tr>
      		

      		
      	<?php } ?>
      	</tbody>
    </table>
      </div>	
      
      <div id="menu1" class="tab-pane fade"><br>
         <?php 
        if ($_SESSION['role'] === 'officer' || $_SESSION['role'] === 'lawyer' || $_SESSION['role'] === 'coispec') {
        ?>
        <h5 class="mt-3">Իմ վարույթում գտնվող Փոխանցման (ԱրդարաԴատ) գործերի ցանկ</h5>
        <?php 
        }
        else{ 
        ?>
        <h5 class="mt-3">Փոխանցման (ԱրդարաԴատ) գործերի ցանկ</h5>
        <?php }?>

      	
      		<table class="table" id="transfer">
      		<thead>
      		<tr style="font-size: 0.9em; font-weight: normal; color: #828282; text-align: center; vertical-align: middle;">
      			<th>Գործ #</th>
      			<th>ապաստան հայցողի ա.ա.հ.</th>
      			<th>դիմումի ամսաթիվ</th>
      			<th>վերջնաժամկետ</th>
      			<th>գործ վարող</th>
      			<th>գործառույթ</th>
      			<th>գործը տնօրինող</th>

      		</tr>
      		</thead>
      		<tbody>
      		<?php 
      		while($row_transfer = $query_transfer->fetch_assoc()){
      			$case_id 			= $row_transfer['case_id'];
      			$asylum_seeker 		= $row_transfer['f_name_arm'] .' '. $row_transfer['l_name_arm'];
      			$application_date   = date('d.m.Y', strtotime($row_transfer['application_date']));
      			$application_dead   = date('d.m.Y', strtotime($row_transfer['deadline']));
      			$case_officer 			= 'նշանակված չէ'; 
      			if(!empty($row_transfer['officer'])){
      			$case_officer 			= $row_transfer['OFFICER_NAME'] .' '. $row_transfer['OFFICER_LNAME']; 
      			}
      			$process = $row_transfer['status'];
      			$processor = $row_transfer['PROCESSOR_NAME'] .' '. $row_transfer['PROCESSOR_LNAME'];
      		
      		?>
      		<tr class="curs_pointer" style="text-align:center;">
      			<td><?php echo $case_id ?></td>
      			<td><?php echo $asylum_seeker ?></td>
      			<td><?php echo $application_date ?></td>
      			<td><?php echo $application_dead ?></td>
      			<td><?php echo $case_officer ?></td>
      			<td><?php echo $process ?> </td>
      			<td><?php echo $processor ?></td>
      			

      		</tr>
      		

      		
      	<?php } ?>
      	</tbody>
      	</table>
      </div>

      <div id="menu2" class="tab-pane fade"><br>
      	 <?php 
        if ($_SESSION['role'] === 'officer' || $_SESSION['role'] === 'lawyer' || $_SESSION['role'] === 'coispec') {
        ?>
        <h5 class="mt-3">Իմ վարույթում գտնվող Արտահանձման (Դատախազություն) գործերի ցանկ</h5>
        <?php 
        }
        else{ 
        ?>
       <h5 class="mt-3">Արտահանձման (Դատախազություն) գործերի ցանկ</h5>
        <?php }?> 


        
      		<table class="table" id="deport">
      		<thead>
      		<tr style="font-size: 0.9em; font-weight: normal; color: #828282; text-align: center; vertical-align: middle;">
      			<th>Գործ #</th>
      			<th>ապաստան հայցողի ա.ա.հ.</th>
      			<th>դիմումի ամսաթիվ</th>
      			<th>վերջնաժամկետ</th>
      			<th>գործ վարող</th>
      			<th>գործառույթ</th>
      			<th>գործը տնօրինող</th>

      		</tr>
      		</thead>
      		<tbody>
      		<?php 
      		while($row_deport = $query_deport->fetch_assoc()){
      			$case_id 			= $row_deport['case_id'];
      			$asylum_seeker 		= $row_deport['f_name_arm'] .' '. $row_deport['l_name_arm'];
      			$application_date   = date('d.m.Y', strtotime($row_deport['application_date']));
      			$application_dead   = date('d.m.Y', strtotime($row_deport['deadline']));
      			$case_officer 			= 'նշանակված չէ'; 
      			if(!empty($row_deport['officer'])){
      			$case_officer 			= $row_deport['OFFICER_NAME'] .' '. $row_deport['OFFICER_LNAME']; 
      			}
      			$process = $row_deport['status'];
      			$processor = $row_deport['PROCESSOR_NAME'] .' '. $row_deport['PROCESSOR_LNAME'];
      		
      		?>
      		<tr class="curs_pointer" style="text-align:center;">
      			<td><?php echo $case_id ?></td>
      			<td><?php echo $asylum_seeker ?></td>
      			<td><?php echo $application_date ?></td>
      			<td><?php echo $application_dead ?></td>
      			<td><?php echo $case_officer ?></td>
      			<td><?php echo $process ?> </td>
      			<td><?php echo $processor ?></td>
      			

      		</tr>
      		

      		
      	<?php } ?>
      	</tbody>
      	</table>
      </div>

      <div id="menu3" class="tab-pane fade"><br>
      	<?php 
        if ($_SESSION['role'] === 'officer' || $_SESSION['role'] === 'lawyer' || $_SESSION['role'] === 'coispec') {
        ?>
        <h5 class="mt-3">Իմ վարույթում գտնվող ՔԿՀ-ում գտնվողների գործերի ցանկ</h5>
        <?php 
        }
        else{ 
        ?>
       <<h5 class="mt-3">ՔԿՀ-ում գտնվողների գործերի ցանկ</h5>
        <?php }?>  


        
      		<table class="table" id="prison">
      		<thead>
      		<tr style="font-size: 0.9em; font-weight: normal; color: #828282; text-align: center; vertical-align: middle;">
      			<th>Գործ #</th>
      			<th>ապաստան հայցողի ա.ա.հ.</th>
      			<th>դիմումի ամսաթիվ</th>
      			<th>վերջնաժամկետ</th>
      			<th>գործ վարող</th>
      			<th>գործառույթ</th>
      			<th>գործը տնօրինող</th>

      		</tr>
      		</thead>
      		<tbody>
      		<?php 
      		while($row_prison = $query_prison->fetch_assoc()){
      			$case_id 			= $row_prison['case_id'];
      			$asylum_seeker 		= $row_prison['f_name_arm'] .' '. $row_prison['l_name_arm'];
      			$application_date   = date('d.m.Y', strtotime($row_prison['application_date']));
      			$application_dead   = date('d.m.Y', strtotime($row_prison['deadline']));
      			$case_officer 			= 'նշանակված չէ'; 
      			if(!empty($row_prison['officer'])){
      			$case_officer 			= $row_prison['OFFICER_NAME'] .' '. $row_prison['OFFICER_LNAME']; 
      			}
      			$process = $row_prison['status'];
      			$processor = $row_prison['PROCESSOR_NAME'] .' '. $row_prison['PROCESSOR_LNAME'];
      		
      		?>
      		<tr class="curs_pointer" style="text-align:center;">
      			<td><?php echo $case_id ?></td>
      			<td><?php echo $asylum_seeker ?></td>
      			<td><?php echo $application_date ?></td>
      			<td><?php echo $application_dead ?></td>
      			<td><?php echo $case_officer ?></td>
      			<td><?php echo $process ?> </td>
      			<td><?php echo $processor ?></td>
      			

      		</tr>
      		

      		
      	<?php } ?>
      	</tbody>
      	</table>
      </div>

      <div id="menu4" class="tab-pane fade"><br>
      	<?php 
        if ($_SESSION['role'] === 'officer' || $_SESSION['role'] === 'lawyer' || $_SESSION['role'] === 'coispec') {
        ?>
        <h5 class="mt-3">Իմ վարույթում գտնվող Ապօրինի սահմանահատում կատարած ապաստան հայցողների գործերի ցանկ</h5>
        <?php 
        }
        else{ 
        ?>
        <h5 class="mt-3">Ապօրինի սահմանահատում կատարած ապաստան հայցողների գործերի ցանկ</h5>
        <?php }?> 

      		<table class="table" id="ilegal">
      		<thead>
      		<tr style="font-size: 0.9em; font-weight: normal; color: #828282; text-align: center; vertical-align: middle;">
      			<th>Գործ #</th>
      			<th>ապաստան հայցողի ա.ա.հ.</th>
      			<th>դիմումի ամսաթիվ</th>
      			<th>վերջնաժամկետ</th>
      			<th>գործ վարող</th>
      			<th>գործառույթ</th>
      			<th>գործը տնօրինող</th>

      		</tr>
      		</thead>
      		<tbody>
      		<?php 
      		while($row_ilegal = $query_ilegal->fetch_assoc()){
      			$case_id 			= $row_ilegal['case_id'];
      			$asylum_seeker 		= $row_ilegal['f_name_arm'] .' '. $row_ilegal['l_name_arm'];
      			$application_date   = date('d.m.Y', strtotime($row_ilegal['application_date']));
      			$application_dead   = date('d.m.Y', strtotime($row_ilegal['deadline']));
      			$case_officer 			= 'նշանակված չէ'; 
      			if(!empty($row_prison['officer'])){
      			$case_officer 			= $row_ilegal['OFFICER_NAME'] .' '. $row_ilegal['OFFICER_LNAME']; 
      			}
      			$process = $row_ilegal['status'];
      			$processor = $row_ilegal['PROCESSOR_NAME'] .' '. $row_ilegal['PROCESSOR_LNAME'];
      		
      		?>
      		<tr class="curs_pointer" style="text-align:center;">
      			<td><?php echo $case_id ?></td>
      			<td><?php echo $asylum_seeker ?></td>
      			<td><?php echo $application_date ?></td>
      			<td><?php echo $application_dead ?></td>
      			<td><?php echo $case_officer ?></td>
      			<td><?php echo $process ?> </td>
      			<td><?php echo $processor ?></td>
      			

      		</tr>
      		

      		
      	<?php } ?>
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

	 var table = $('#all_special').DataTable({
	 	"dom": 'Bfrtip',
        "buttons": [
           		{ extend: 'copy', className: 'btn btn-primary btn-sm' },
			    { extend: 'csv', className: 'btn btn-primary btn-sm' },
			    { extend: 'excel', className: 'btn btn-primary btn-sm'}
        ],
        columnDefs: [
                    {
                        "render": function (data, type, row) {
                            var i = $(data).prop("checked")===true?"1":"0";
                            return i;
                        },
                        "targets": [5,6,7,8]
                    }
                ],
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
      			"zeroRecords": "Համակարգում առկա չեն ընթացիկ հատուկ գործեր։",
          }
    	});
       
      $('#all_special').on( 'click', 'tr', function () {
          var data = table.row( this ).data()[0];
          location.replace(`user.php?page=cases&homepage=case_page&case=${data}`);
          //alert( 'Clicked row id '+data[0]+'\'s row' );
      } );

       var table_transfer = $('#transfer').DataTable({
	 	"dom": 'Bfrtip',
        "buttons": [
           		{ extend: 'copy', className: 'btn btn-primary btn-sm' },
			    { extend: 'csv', className: 'btn btn-primary btn-sm' },
			    { extend: 'excel', className: 'btn btn-primary btn-sm'}
        ],
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
      		"zeroRecords": "Համակարգում առկա չեն ընթացիկ Փոխանցման գործեր։",
          }
    	});
       
      $('#transfer').on( 'click', 'tr', function () {
          var data = table_transfer.row( this ).data()[0];
          location.replace(`user.php?page=cases&homepage=case_page&case=${data}`);
          //alert( 'Clicked row id '+data[0]+'\'s row' );
      } );


      var table_deport = $('#deport').DataTable({
	 	"dom": 'Bfrtip',
        "buttons": [
           		{ extend: 'copy', className: 'btn btn-primary btn-sm' },
			    { extend: 'csv', className: 'btn btn-primary btn-sm' },
			    { extend: 'excel', className: 'btn btn-primary btn-sm'}
        ],
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
      	"zeroRecords": "Համակարգում առկա չեն ընթացիկ Արտահանձնման գործեր։",
          }
    	});
       
      $('#deport').on( 'click', 'tr', function () {
          var data = table_deport.row( this ).data()[0];
          location.replace(`user.php?page=cases&homepage=case_page&case=${data}`);
          //alert( 'Clicked row id '+data[0]+'\'s row' );
      } );

      var table_prison = $('#prison').DataTable({
	 	"dom": 'Bfrtip',
        "buttons": [
           		{ extend: 'copy', className: 'btn btn-primary btn-sm' },
			    { extend: 'csv', className: 'btn btn-primary btn-sm' },
			    { extend: 'excel', className: 'btn btn-primary btn-sm'}
        ],
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
      			"zeroRecords": "Համակարգում առկա չեն ընթացիկ ՔԿՀ գործեր։",
          }
    	});
       
      $('#prison').on( 'click', 'tr', function () {
          var data = table_prison.row( this ).data()[0];
          location.replace(`user.php?page=cases&homepage=case_page&case=${data}`);
          //alert( 'Clicked row id '+data[0]+'\'s row' );
      } );

       var table_ilegal = $('#ilegal').DataTable({
	 	"dom": 'Bfrtip',
        "buttons": [
           		{ extend: 'copy', className: 'btn btn-primary btn-sm' },
			    { extend: 'csv', className: 'btn btn-primary btn-sm' },
			    { extend: 'excel', className: 'btn btn-primary btn-sm'}
        ],
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
      		"zeroRecords": "Համակարգում առկա չեն ընթացիկ Ապօրինի սահմանահատման գործեր։",
          }
    	});
       
      $('#ilegal').on( 'click', 'tr', function () {
          var data = table_ilegal.row( this ).data()[0];
          location.replace(`user.php?page=cases&homepage=case_page&case=${data}`);
          //alert( 'Clicked row id '+data[0]+'\'s row' );
      } );

	</script>
</body>



