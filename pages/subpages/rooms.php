<?php
require_once 'config/connect.php';
require_once 'config/query_doss.php';

$u_id = $_SESSION['user_id'];

 $sql_in = "SELECT a.doss_id, a.room_num, a.doss, a.doss_status, a.doss_type, b.checkin_id, b.checkin_date, b.checkout_date, b.personal_id, b.order_id, b.status, b.doss_id, c.personal_id, c.case_id, c.f_name_arm, c.l_name_arm, c.m_name_arm, d.room_id, d.room_num, d.floor, d.type, d.capacity, e.order_id, e.order_status, e.date AS ORDER_DATE, f.case_id AS CASE_ID, f.case_status AS CASE_STATUS_ID, g.case_status AS CASE_STATUS_TEXT 
FROM tb_doss a 
LEFT JOIN tb_checkin b ON a.doss_id = b.doss_id 
INNER JOIN tb_person c ON b.personal_id = c.personal_id
INNER JOIN tb_drooms d ON a.room_num = d.room_num
INNER JOIN tb_orders e ON b.order_id = e.order_id
INNER JOIN tb_case   f ON f.case_id  = e.case_id
INNER JOIN tb_case_status g ON g.case_status_id = f.case_status
WHERE b.status = 1
";
    $result_in = $conn->query($sql_in);



$sql_free_doss = "SELECT a.room_num, max(case when a.doss_type='A' then a.doss_id end) A, max(case when a.doss_type='B' then a.doss_id end) B, max(case when a.doss_type='C' then a.doss_id end) C, max(case when a.doss_type='D' then a.doss_id end) D FROM tb_doss a  
	


 GROUP BY room_num";
  $result_free_doss = $conn->query($sql_free_doss);

$counter = 1;

?>



<body>

<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link active" data-toggle="tab" href="#home">Կացարանում բնակվողների ցանկ</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#menu1">Սենյակների զբաղվածությունը</a>
  </li>

</ul>
	<div class="tab-content">
      <div id="home" class="tab-pane active"><br>
      
      	<div class="">

						<table class="table table-stripped" id="dorm_in">
							<thead>
							<tr>
								<th class="label_pers_page">հ/հ</th>
								<th class="label_pers_page">Բնակչի ա.ա.հ.</th>
								<th class="label_pers_page">Ուղեգիր # </th>
								<th class="label_pers_page">Ուղեգրի ամսաթիվ</th>
								<th class="label_pers_page">Սենյակ #</th>
								<th class="label_pers_page">Տեղ #</th>
								<th class="label_pers_page">Տեղավորման ամսաթիվ</th>
								<th class="label_pers_page">Բնակչի կարգավիճակ</th>
								<th class="label_pers_page">...</th>

							</tr>
						</thead> 
						<tbody>
							<?php 
							
							while($row = $result_in->fetch_assoc()){
								$order_date 						= $row['ORDER_DATE'];
								$order_date_formated 		= date('d.m.Y', strtotime($order_date));

								$chekin_date 						= $row['checkin_date'];
								$chekin_date_formated 	= date('d.m.Y', strtotime($chekin_date));
								$case_status 						= $row['CASE_STATUS_ID'];
								$case_status_text 			= $row['CASE_STATUS_TEXT'];
								
								$color 									= '';
				        $status_sign  					= '';
				        $person_status					= '';

				        if($case_status == 1){
				        	$status_sign     			= '<i class="fas fa-circle mr-2"></i>';
				        	$person_status				= 'ապաստան հայցող';
				        	$color 								= 'green';
				        }
				 				if($case_status == 2){
				        	$status_sign 					= '<i class="fas fa-gavel mr-2"></i>';
				        	$person_status				= 'ապաստան հայցող';
				        	$color 							  = 'green';
				        }
				        if($case_status == 3){
				        	$decision_type_id 		= '';
				        	$sql_decision_type 		= "SELECT * FROM tb_decisions a WHERE case_id = $case_id";
				        	$result_decision_type = $conn->query($sql_decision_type);

				        	if($result_decision_type->num_rows > 0){
				        		$row_decision_type  = $result_decision_type->fetch_assoc();
				        		$decision_type_id   = $row_decision_type['decision_type'];
				        		
				        		if($decision_type_id == 3){
				        				$status_sign 					= '<i class="fas fa-gavel mr-2"></i>';
							        	$person_status				= 'ճանաչվել է փախստական';
							        	$color 			 					= 'red';
				        		}
				        		if($decision_type_id == 4){
				        				$status_sign 					= '<i class="fas fa-gavel mr-2"></i>';
							        	$person_status				= 'Վերջնական մերժում';
							        	$color 			 					= 'red';
				        		}
				        		if($decision_type_id == 5){
				        				$status_sign 					= '<i class="fas fa-gavel mr-2"></i>';
							        	$person_status				= 'Կարճվել է';
							        	$color 			 					= 'red';
				        		}
				        	}  	
				        }
				        if($case_status == 4){
				        	$status_sign 					= '<i class="fas fa-minus-circle"></i>';
				        	$person_status				= 'ապաստան հայցող';
				        	$color      					= 'yellow';
				        }       

							?>
								<tr>
									<td><?php echo $counter ?></td>
									<td><?= $row['f_name_arm'] . ' ' . $row['l_name_arm']?></td>
									<td><?= $row['order_id'] ?></td>
									<td><?php echo $order_date_formated ?></td>
									<td><?= $row['room_num'] ?></td>
									<td><?= $row['doss'] ?></td>
									<td><?php echo $chekin_date_formated ?></td>
									<td><font color="<?php echo $color?>"><?php echo $status_sign ?> <?php echo $person_status ?>  </font></td>		
									<td><a href="#" class="change_doss mr-2" check_id="<?= $row['checkin_id'] ?>" check_person="<?= $row['personal_id'] ?>" check_case="<?= $row['case_id'] ?>" pending_doss="<?= $row['doss_id'] ?>"><i class="fas fa-retweet"></i> </a> 
											
									</td>


								</tr>
							<?php 
						$counter++;
					} ?>
					</tbody>
						</table>
				</div>

      </div>
   	

  
      <div id="menu1" class="tab-pane fade"><br>
      	<div class="doss_scheme">
      		<h5 class="sub_title_room">Մի տեղանոց սենյակներ</h5>
      		<div class="row mt-1">

      			<div class="single" >
      				<div class="room_num_single">
      					<i class="fas fa-door-open ml-2"></i>
      					<a href="#" class="room_modal" modal_v="103"><span class="room_num_text">103</span></a>
      				</div>

      				<div class="doss_check">
      					<?php echo $r103_A ?>
      				</div>
      				<div class="doss_check">
      					<?php echo $r103_A_sex ?> 
      				</div>
      			</div>

      			<div class="single" >
      				<div class="room_num_single">
      					<i class="fas fa-door-open ml-2"></i>
      					<a href="#" class="room_modal" modal_v="104"><span class="room_num_text">104</span></a>
      				</div>

      				<div class="doss_check">
      					<?php echo $r104_A ?>
      				</div>
      				<div class="doss_check">
      					<?php echo $r104_A_sex ?> 
      				</div>
      			</div>

      			<div class="single" >
      				<div class="room_num_single">
      					<i class="fas fa-door-open ml-2"></i>
      					<a href="#" class="room_modal" modal_v="105"><span class="room_num_text">105</span></a>
      				</div>

      				<div class="doss_check">
      					<?php echo $r105_A ?>
      				</div>
      				<div class="doss_check">
      					<?php echo $r105_A_sex ?> 
      				</div>
      			</div>
      		</div>

				<h5 class="sub_title_room mt-3" >Երկտեղանոց սենյակներ</h5>
      		<div class="row mt-2">
      			<div class="double" >
      				<div class="room_num_double">
      					<i class="fas fa-door-open ml-2"></i>
      					<a href="#" class="room_modal" modal_v="203"><span class="room_num_text">203</span></a>
      				</div>

      				<div class="doss_area">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>A</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r203_A ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r203_A_sex ?> 
		      				</div>
		      		</div>

		      		<div class="doss_area">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>B</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r203_B ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r203_B_sex ?> 
		      				</div>
		      		</div>
		      				
      			</div>

      			<div class="double" >
      				<div class="room_num_double">
      					<i class="fas fa-door-open ml-2"></i>
      					<a href="#" class="room_modal" modal_v="204"><span class="room_num_text">204</span></a>
      				</div>

      				<div class="doss_area">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>A</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r204_A ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r204_A_sex ?> 
		      				</div>
		      		</div>

		      		<div class="doss_area">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>B</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r204_B ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r204_B_sex ?> 
		      				</div>
		      		</div>
		      				
      			</div>

      			<div class="double">
      				<div class="room_num_double">
      					<i class="fas fa-door-open ml-2"></i>
      					<a href="#" class="room_modal" modal_v="205"><span class="room_num_text">205</span></a>
      				</div>

      				<div class="doss_area">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>A</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r205_A ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r205_A_sex ?> 
		      				</div>
		      		</div>

		      		<div class="doss_area">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>B</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r205_B ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r205_B_sex ?> 
		      				</div>
		      		</div>
		      				
      			</div>
      		</div>	

      		
      		<div class="row mt-2">
      			<div class="double" >
      				<div class="room_num_double">
      					<i class="fas fa-door-open ml-2"></i>
      					<a href="#" class="room_modal" modal_v="206"><span class="room_num_text">206</span></a>
      				</div>

      				<div class="doss_area">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>A</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r206_A ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r206_A_sex ?> 
		      				</div>
		      		</div>

		      		<div class="doss_area">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>B</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r206_B ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r206_B_sex ?> 
		      				</div>
		      		</div>
		      				
      			</div>
      		
      			<div class="double" >
      				<div class="room_num_double">
      					<i class="fas fa-door-open ml-2"></i>
      					<a href="#" class="room_modal" modal_v="208"><span class="room_num_text">208</span></a>
      				</div>

      				<div class="doss_area">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>A</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r208_A ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r208_A_sex ?> 
		      				</div>
		      		</div>

		      		<div class="doss_area">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>B</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r208_B ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r208_B_sex ?> 
		      				</div>
		      		</div>
		      				
      			</div>

      			<div class="double" >
      				<div class="room_num_double">
      					<i class="fas fa-door-open ml-2"></i>
      					<a href="#" class="room_modal" modal_v="209"><span class="room_num_text">209</span></a>
      				</div>

      				<div class="doss_area">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>A</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r209_A ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r209_A_sex ?> 
		      				</div>
		      		</div>

		      		<div class="doss_area">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>B</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r209_B ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r209_B_sex ?> 
		      				</div>
		      		</div>
		      				
      			</div>
      		</div>	
      		

      	

      	<div class="row mt-2">
      			<div class="double" >
      				<div class="room_num_double">
      					<i class="fas fa-door-open ml-2"></i>
      					<a href="#" class="room_modal" modal_v="210"><span class="room_num_text">210</span></a>
      				</div>

      				<div class="doss_area">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>A</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r210_A ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r210_A_sex ?> 
		      				</div>
		      		</div>

		      		<div class="doss_area">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>B</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r210_B ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r210_B_sex ?> 
		      				</div>
		      		</div>
		      				
      			</div>

      			<div class="double" >
      				<div class="room_num_double">
      					<i class="fas fa-door-open ml-2"></i>
      					<a href="#" class="room_modal" modal_v="211"><span class="room_num_text">211</span></a>
      				</div>

      				<div class="doss_area">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>A</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r211_A ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r211_A_sex ?> 
		      				</div>
		      		</div>

		      		<div class="doss_area">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>B</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r211_B ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r211_B_sex ?> 
		      				</div>
		      		</div>
		      				
      			</div>

      			<div class="double" >
      				<div class="room_num_double">
      					<i class="fas fa-door-open ml-2"></i>
      					<a href="#" class="room_modal" modal_v="212"><span class="room_num_text">212</span></a>
      				</div>

      				<div class="doss_area">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>A</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r212_A ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r212_A_sex ?> 
		      				</div>
		      		</div>

		      		<div class="doss_area">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>B</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r212_B ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r212_B_sex ?> 
		      				</div>
		      		</div>
		      				
      			</div>
      		</div>	
      		
				   		

				<div class="row mt-2">
      			<div class="double" >
      				<div class="room_num_double">
      					<i class="fas fa-door-open ml-2"></i>
      					<a href="#" class="room_modal" modal_v="213"><span class="room_num_text">213</span></a>
      				</div>

      				<div class="doss_area">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>A</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r213_A ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r213_A_sex ?> 
		      				</div>
		      		</div>

		      		<div class="doss_area">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>B</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r213_B ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r213_B_sex ?> 
		      				</div>
		      		</div>
		      				
      			</div>
      		
      			<div class="double" >
      				<div class="room_num_double">
      					<i class="fas fa-door-open ml-2"></i>
      					<a href="#" class="room_modal" modal_v="214"><span class="room_num_text">214</span></a>
      				</div>

      				<div class="doss_area">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>A</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r214_A ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r214_A_sex ?> 
		      				</div>
		      		</div>

		      		<div class="doss_area">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>B</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r214_B ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r214_B_sex ?> 
		      				</div>
		      		</div>
		      				
      			</div>

      			<div class="double ml-3" >
      				<div class="room_num_double">
      					<i class="fas fa-door-open ml-2"></i>
      					<a href="#" class="room_modal" modal_v="215"><span class="room_num_text">215</span></a>
      				</div>

      				<div class="doss_area">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>A</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r215_A ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r215_A_sex ?> 
		      				</div>
		      		</div>

		      		<div class="doss_area">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>B</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r215_B ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r215_B_sex ?> 
		      				</div>
		      		</div>
		      				
      			</div>
      		</div>	
      		
				

				<div class="row mt-2">
      			<div class="double" >
      				<div class="room_num_double">
      					<i class="fas fa-door-open ml-2"></i>
      					<a href="#" class="room_modal" modal_v="216"><span class="room_num_text">216</span></a>
      				</div>

      				<div class="doss_area">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>A</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r216_A ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r216_A_sex ?> 
		      				</div>
		      		</div>

		      		<div class="doss_area">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>B</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r216_B ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r216_B_sex ?> 
		      				</div>
		      		</div>
		      				
      			</div>

      			<div class="double ml-3" >
      				<div class="room_num_double">
      					<i class="fas fa-door-open ml-2"></i>
      					<a href="#" class="room_modal" modal_v="217"><span class="room_num_text">217</span></a>
      				</div>

      				<div class="doss_area">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>A</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r217_A ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r217_A_sex ?> 
		      				</div>
		      		</div>

		      		<div class="doss_area">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>B</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r217_B ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r217_B_sex ?> 
		      				</div>
		      		</div>
		      				
      			</div>
      			<div class="double" >
      				<div class="room_num_double">
      					<i class="fas fa-door-open ml-2"></i>
      					<a href="#" class="room_modal" modal_v="218"><span class="room_num_text">218</span></a>
      				</div>

      				<div class="doss_area">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>A</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r218_A ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r218_A_sex ?> 
		      				</div>
		      		</div>

		      		<div class="doss_area">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>B</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r218_B ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r218_B_sex ?> 
		      				</div>
		      		</div>
		      				
      			</div>
      		</div>	
      		
				  		
				<div class="row mt-2">
      			<div class="double" >
      				<div class="room_num_double">
      					<i class="fas fa-door-open ml-2"></i>
      					<a href="#" class="room_modal" modal_v="219"><span class="room_num_text">219</span></a>
      				</div>

      				<div class="doss_area">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>A</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r219_A ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r219_A_sex ?> 
		      				</div>
		      		</div>

		      		<div class="doss_area">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>B</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r219_B ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r219_B_sex ?> 
		      				</div>
		      		</div>
		      				
      			</div>
      	
      			<div class="double" >
      				<div class="room_num_double">
      					<i class="fas fa-door-open ml-2"></i>
      					<a href="#" class="room_modal" modal_v="220"><span class="room_num_text">220</span></a>
      				</div>

      				<div class="doss_area">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>A</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r220_A ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r220_A_sex ?> 
		      				</div>
		      		</div>

		      		<div class="doss_area">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>B</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r220_B ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r220_B_sex ?> 
		      				</div>
		      		</div>
		      				
      			</div>

      			<div class="double" >
      				<div class="room_num_double">
      					<i class="fas fa-door-open ml-2"></i>
      					<a href="#" class="room_modal" modal_v="221"><span class="room_num_text">221</span></a>
      				</div>
      				<div class="doss_area">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>A</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r221_A ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r221_A_sex ?> 
		      				</div>
		      		</div>

		      		<div class="doss_area">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>B</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r221_B ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r221_B_sex ?> 
		      				</div>
		      		</div>
		      				
      			</div>
      		</div>
      		  		
				<div class="row mt-2">
      			<div class="double" >
      				<div class="room_num_double">
      					<i class="fas fa-door-open ml-2"></i>
      					<a href="#" class="room_modal" modal_v="222"><span class="room_num_text">222</span></a>
      				</div>

      				<div class="doss_area">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>A</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r222_A ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r222_A_sex ?> 
		      				</div>
		      		</div>

		      		<div class="doss_area">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>B</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r222_B ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r222_B_sex ?> 
		      				</div>
		      		</div>
		      				
      			</div>
      		</div>	
      		
      		<h5 class="sub_title_room mt-3" >Ընտանեկան սենյակներ</h5>
					<div class="row mt-2">
      			<div class="family_room" >
      				<div class="room_num_family">
      					<i class="fas fa-door-open ml-2"></i>
      					<a href="#" class="room_modal" modal_v="202"><span class="room_num_text">202</span></a>
      				</div>

      				<div class="doss_area_family">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>A</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r202_A ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r202_A_sex ?> 
		      				</div>
		      		</div>

		      		<div class="doss_area_family">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>B</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r202_B ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r202_B_sex ?> 
		      				</div>
		      		</div>
		      		<div class="doss_area_family">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>C</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r202_C ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r202_C_sex ?> 
		      				</div>
		      		</div>

		      		<div class="doss_area_family">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>D</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r202_D ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r202_D_sex ?> 
		      				</div>
		      		</div>
		      				
      			</div>
      		</div>

					<div class="row mt-2">
      			<div class="family_room" >
      				<div class="room_num_family">
      					<i class="fas fa-door-open ml-2"></i>
      					<a href="#" class="room_modal" modal_v="207"><span class="room_num_text">207</span></a>
      				</div>

      				<div class="doss_area_family">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>A</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r207_A ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r207_A_sex ?> 
		      				</div>
		      		</div>

		      		<div class="doss_area_family">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>B</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r207_B ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r207_B_sex ?> 
		      				</div>
		      		</div>
		      		<div class="doss_area_family">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>C</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r207_C ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r207_C_sex ?> 
		      				</div>
		      		</div>

		      		<div class="doss_area_family">
		      				<div class="doss_check_bed">
		      					<i class="fas fa-bed"></i>
		      					<span>D</span>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r207_D ?>
		      				</div>
		      				<div class="doss_check_d">
		      					<?php echo $r207_D_sex ?> 
		      				</div>
		      		</div>
		      				
      			</div>
      		</div>
      	


      	</div>
      </div>



     
  </div>

  

<div class="modal fade" id="change_doss_modal">
  
</div>

<div class="modal fade" id="room_info">
  
</div>


<script >
	$(document).ready(function(){


		$(".change_doss").click(function(){
   	  var checkin_id = $(this).attr('check_id');
   	  var pers_id    = $(this).attr('check_person');
   	  var case_id    = $(this).attr('check_case');
   	  var from_doss  = $(this).attr('pending_doss')
    $.ajax({
                url:"config/config_update.php",
                method:"POST",
                data:{change_doss:checkin_id, pers_id:pers_id, case_id:case_id, from_doss:from_doss},
                success:function(data)
                {  
                	
                   $('#change_doss_modal').html(data);
                   $("#change_doss_modal").modal({backdrop: "static"});
                    
                } 
            });
      });

});


	$(".room_modal").click(function(){
		var room_num     = $(this).attr('modal_v');

		$.ajax({
							url:"config/config_update.php",
							method:"POST",
							data:{room_modal:room_num},
							success:function(data)
							{
								console.log(room_num);
								 $('#room_info').html(data);
                 $("#room_info").modal({backdrop: "static"});
							}
		})

	})

</script>

<script>
	

	 $(document).ready(function () {             
          $('.dataTables_filter input[type="search"]').css(
          {'width':'500px','display':'inline-block'}
      );  
      });

	 var table = $('#dorm_in').DataTable({
	 			"dom": 'Bfrtip',
        "buttons": [
           { extend: 'copy', className: 'btn btn-primary btn-sm' },
			    { extend: 'csv', className: 'btn btn-primary btn-sm ' },
			    { extend: 'excel', className: 'btn btn-primary btn-sm ' }
        ],


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
       


</script>
	


</body>
