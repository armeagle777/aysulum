<?php
require_once 'config/connect.php';

$u_id = $_SESSION['user_id'];

$no_msg_msg = 'Մտից գրություններ չկան';
$no_out_msg = 'Ելից գրություններ չկան';

 $sql_order_inbox = "SELECT a.order_process_id, a.order_id, a.order_from, a.order_to, a.process_date, a.order_status, a.order_actual, a.order_comment, b.order_id, b.case_id, b.order_status, b.date, c.personal_id, c.f_name_arm, c.l_name_arm, c.m_name_arm, c.sex, c.role, c.citizenship,  e.f_name AS SENDER_NAME, e.l_name AS SENDER_L_NAME, f.f_name AS REC_NAME, f.l_name AS REC_L_NAME, h.order_status_id, h.order_status_arm 
FROM tb_order_process a
INNER JOIN tb_orders b ON a.order_id = b.order_id
INNER JOIN tb_person c ON b.case_id = c.case_id
INNER JOIN users e ON a.order_from = e.id
INNER JOIN users f ON a.order_to = f.id
INNER JOIN tb_order_process_status h ON a.order_status = h.order_status_id

 WHERE c.role = 1 AND a.order_actual = 1 AND a.order_to = $u_id AND a.order_status != 3 ORDER BY a.order_id DESC";
    $result_order_inbox    = $conn->query($sql_order_inbox);

$sql_order_outbox = "SELECT a.order_process_id, a.order_id, a.order_from, a.order_to, a.process_date, a.order_status, a.order_actual, a.order_comment, b.order_id, b.case_id, b.order_status, b.date, c.personal_id, c.f_name_arm, c.l_name_arm, c.m_name_arm, c.sex, c.role, c.citizenship, d.country_arm AS CITIZENSHIP, e.f_name AS SENDER_NAME, e.l_name AS SENDER_L_NAME, f.f_name AS REC_NAME, f.l_name AS REC_L_NAME, h.order_status_id, h.order_status_arm 
FROM tb_order_process a
INNER JOIN tb_orders b ON a.order_id = b.order_id
INNER JOIN tb_person c ON b.case_id = c.case_id
INNER JOIN tb_country d ON c.citizenship = d.country_id
INNER JOIN users e ON a.order_from = e.id
INNER JOIN users f ON a.order_to = f.id
INNER JOIN tb_order_process_status h ON a.order_status = h.order_status_id
WHERE  c.role = 1 AND a.order_actual = 1 AND a.order_from = $u_id ORDER BY a.order_process_id DESC";

$result_order_outbox   = $conn->query($sql_order_outbox);

$sql_order_in = "SELECT a.order_process_id, a.order_id, a.order_from, a.order_to, a.process_date, a.order_status, a.order_actual, a.order_comment, b.order_id, b.case_id, b.order_status, b.date, c.personal_id, c.f_name_arm, c.l_name_arm, c.m_name_arm, c.sex, c.role, c.citizenship, h.order_status_id, h.order_status_arm, f.case_status,  g.case_status AS CASE_STATUS_TEXT
FROM tb_order_process a
INNER JOIN tb_orders b ON a.order_id = b.order_id
INNER JOIN tb_person c ON b.case_id = c.case_id
INNER JOIN tb_case f ON c.case_id   = f.case_id
INNER JOIN tb_case_status g ON g.case_status_id = f.case_status
INNER JOIN tb_order_process_status h ON a.order_status = h.order_status_id

 WHERE  c.role = 1 AND a.order_actual = 1 AND a.order_status = 3 ORDER BY a.order_process_id DESC";

    $result_order_in   = $conn->query($sql_order_in);   

?>
<body>
  <!-- Nav tabs -->
<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link active" data-toggle="tab" href="#home"><i class="fas fa-arrow-circle-down"></i> Մտից</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#menu1"><i class="fas fa-arrow-circle-up"></i> Ելից</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#menu2"><i class="fas fa-angle-double-down"></i> Տեղավորված</a>
  </li>
 </ul>
 
 <!-- Tab panes -->
  	<div class="tab-content">
      
      <div id="home" class="tab-pane active"><br>

			      <table class="table table-stripped table-bordered" id="order_inbox">
			      	<thead>
			      		<tr style="font-size: 0.9em; font-weight: normal; color: #828282; text-align: center; vertical-align: middle;"> 
					         <th width="10%">ուղեգիր #</th>
					         <th width="10%">գործ #</th>
					         <th width="15%">ուղարկող</th>
					         <th width="20%">ապաստան հայցողի ա.ա.հ</th>
					         <th width="15%">կարգավիճակ</th>
					         <th width="15%">ուղեգրի ամսաթիվ</th>
					         <th width="10%">ավելին</th>
			      		</tr>
			        </thead>
			        <tbody>
			      <?php  
			      if ($result_order_inbox->num_rows > 0 ) {
			      	while($row_inbox = $result_order_inbox->fetch_assoc()){
			      		$case_id 		= $row_inbox['case_id'];
			      		$order_id 		= $row_inbox['order_id'];
			      		$from 			= $row_inbox['SENDER_NAME'] .' '.$row_inbox['SENDER_NAME'];
			      		$name 			= $row_inbox['l_name_arm'] . ' ' .$row_inbox['f_name_arm'];
			      		$status 			= $row_inbox['order_status_arm'];
			      		$send_date 		= date('d.m.Y', strtotime($row_inbox['process_date']));
			      ?>
			      
			         <tr style="font-size: 1em; color:#324157; text-align: center;" class="curs_pointer">
					        <td><?php echo $order_id ?></td>
					        <td><?php echo $case_id ?></td>
					        <td><?php echo $from ?></td>
					        <td><?php echo $name ?></td>
					        <td><?php echo $status ?></td>
					        <td><?php echo $send_date ?></td>
					        <td><a href="user.php?page=cases&homepage=order_page&case=<?php echo $case_id ?>&order=<?php echo $order_id?>" case_id="<?php echo $case_id ?>" style="font-size: 1.4em;"> <i class="fas fa-search-plus"></i></a>  </td>
     					</tr>

			      <!-- closing while -->
			      <?php		
			      	}
			      }
			  		elseif ($result_order_inbox->num_rows == 0) {
			  		    	echo $no_msg_msg ;
			  		    }    
			  
			      ?>
			      </tbody>
      	</table>
	   </div> <!-- close home tab  -->


      <div id="menu1" class="tab-pane fade"><br>

      	<table class="table table-stripped table-bordered" id="order_outbox">
      		<thead>
      		   <tr style="font-size: 0.9em; font-weight: normal; color: #828282; text-align: center; vertical-align: middle;"> 
					        <th width="10%">ուղեգիր #</th>
					        <th width="10%">գործ #</th>
					        <th width="15%">ստացող</th>
					        <th width="20%">ապաստան հայցողի ա.ա.հ</th>
					        <th width="15%">կարգավիճակ</th>
					        <th width="15%">ուղեգրի ամսաթիվ</th>
					        <th width="10%">ավելին</th>
     				</tr>
     			</thead>
     			<tbody>	
		<?php 
      if ($result_order_outbox->num_rows > 0) {
      while ($row_out = $result_order_outbox->fetch_assoc()) {
        $order_id       = $row_out['order_id'];
        $case_id        = $row_out['case_id'];
        $from         = $row_out['SENDER_NAME'] . ' ' . $row_out['SENDER_L_NAME'];
        $name         = $row_out['f_name_arm'] . ' ' . $row_out['l_name_arm'];
        $status         = $row_out['order_status_arm'];
        $send_date      = date('d.m.Y', strtotime($row_out['date']));
        $order_status     = $row_out['order_status_arm'];
      
      
      ?>

      <tr style="font-size: 1em; color:#324157; text-align: center;" class="curs_pointer">
      
        <td><?php echo $order_id ?></td>
        <td><?php echo $case_id ?></td>
        <td><?php echo $from ?></td>
        <td><?php echo $name ?></td>
        <td><?php echo $status ?></td>
        <td><?php echo $send_date ?></td>
        
        <td><a href="user.php?page=cases&homepage=order_page&case=<?php echo $case_id ?>&order=<?php echo $order_id?>" case_id="<?php echo $case_id ?>" style="font-size: 1.4em;"> <i class="fas fa-search-plus"></i></a>  </td>

      </tr>

		<!-- closing while -->
			      <?php		
			      	}
			      }
			  		elseif ($result_order_outbox->num_rows == 0) {
			  		    	echo $no_out_msg ;
			  		    }    
			  
			      ?>
  	      </tbody>
      	</table>

      	

      </div> <!-- close menu1 tab -->



      <div id="menu2" class="tab-pane fade"> <br>
      	<table class="table table-stripped table-bordered" id="order_in">
    			<thead>
      			<tr style="font-size: 0.9em; font-weight: normal; color: #828282; text-align: center; vertical-align: middle;"> 
					         <th width="10%">ուղեգիր #</th>
					         <th width="10%">գործ #</th>
					         <th width="20%">ապաստան հայցողի ա.ա.հ</th>
					         <th width="15%">կարգավիճակ</th>
					         <th width="15%">ուղեգրի ամսաթիվ</th>
					         <th width="15%">գործի կարգավիճակ</th>
					         <th width="10%"></th>
      			</tr>
   			  </thead>  			
					<tbody>
 <?php 
      
      while ($row_in = $result_order_in->fetch_assoc()) 
      {
        $order_id         = $row_in['order_id'];
        $name             = $row_in['f_name_arm'] . ' ' . $row_in['l_name_arm'];
        $status           = $row_in['order_status_arm'];
        $send_date        = date('d.m.Y', strtotime($row_in['date']));      
        $case_id          = $row_in['case_id'];	
        $case_status      = $row_in['case_status'];
        $case_status_text = $row_in['CASE_STATUS_TEXT'];

        $color 						= '';
        $status_sign  		= '';

        if($case_status == 1){
        	$status_sign = '<i class="fas fa-circle"></i>';
        	$color 			 = 'green';
        }
 				if($case_status == 2){
        	$status_sign = '<i class="fas fa-gavel"></i>';
        	$color 			 = 'green';
        }
        if($case_status == 3){
        	$status_sign = '<i class="far fa-times-circle"></i>';
        	$color       = 'red';
        }
        if($case_status == 4){
        	$status_sign = '<i class="fas fa-minus-circle"></i>';
        	$color       = 'yellow';
        }       
 
      ?>

      <tr style="font-size: 1em; color:#324157; text-align: center;" class="curs_pointer">
        <td><?php echo $order_id ?></td>
        <td><?php echo $case_id ?></td>
        <td><?php echo $name ?></td>
        <td><?php echo $status ?></td>
        <td><?php echo $send_date ?></td>
        <td><font color="<?php echo $color?>" ><?php echo $case_status_text ?>  </font> </td>
        <td><font color="<?php echo $color?>" > <?php echo $status_sign ?> </font>  </td>

      </tr>

      <!-- closing while -->
			      <?php		
			      	}
			      ?>
      		</tbody>
      	</table>

      	
		      	
      </div> <!-- close menu2 tab -->
     
</div> <!-- close tab panes -->  	



<script>
	$(document).ready(function () {             
          $('.dataTables_filter input[type="search"]').css(
          {'width':'500px','display':'inline-block'}
      );  
      });

	 var table_inbox = $('#order_inbox').DataTable({
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
          }
    	});
       
      $('#order_inbox').on( 'click', 'tr', function () {
       var data = table_inbox.row( this ).data()[0];
       var case_id = table_inbox.row( this ).data()[1];
       location.replace(`user.php?page=cases&homepage=order_page&case=${case_id}&order=${data}`);
          //alert( 'Clicked row id '+data[0]+'\'s row' );
      } );


	 var table_in = $('#order_in').DataTable({
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
          }
    	});
       
      $('#order_in').on( 'click', 'tr', function () {
         var data = table_in.row( this ).data()[0];
         var case_id = table_in.row( this ).data()[1];
         location.replace(`user.php?page=cases&homepage=order_page&case=${case_id}&order=${data}`);
          //alert( 'Clicked row id '+data[0]+'\'s row' );
      } );


       var table = $('#order_outbox').DataTable({
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
          }
    	});
       
      $('#order_outbox').on( 'click', 'tr', function () {
          var data = table.row( this ).data()[0];
          var case_id = table.row( this ).data()[1];
          location.replace(`user.php?page=cases&homepage=order_page&case=${case_id}&order=${data}`);
          //alert( 'Clicked row id '+data[0]+'\'s row' );
      } );
</script>
	





</body>
