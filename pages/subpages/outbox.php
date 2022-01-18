<?php
require_once 'config/connect.php';

$u_id = $_SESSION['user_id'];

$query_cases = "SELECT a.case_id, b.f_name_arm, b.l_name_arm, c.sign_status, d.status, c.sign_date, e.case_status, a.input_date, c.sign_by, f.f_name AS SIGNER_NAME, f.l_name AS SIGNER_LNAME, a.officer, g.f_name AS OFFICER_NAME, g.l_name AS OFFICER_LNAME, c.processor, h.f_name AS PROCESSOR_NAME, h.l_name AS PROCESSOR_LNAME, k.deadline FROM tb_case a INNER JOIN tb_person b ON a.case_id = b.case_id INNER JOIN tb_process c ON a.case_id = c.case_id INNER JOIN tb_sign_status d ON d.status_id = c.sign_status INNER JOIN tb_case_status e ON a.case_status = e.case_status_id INNER JOIN users f ON c.sign_by = f.id LEFT JOIN users g ON a.officer = g.id INNER JOIN users h ON c.processor = h.id LEFT JOIN tb_deadline k ON a.case_id = k.case_id 
WHERE b.role = 1 AND c.actual = 1 AND k.actual_dead = 1 AND c.sign_by = $u_id ORDER BY c.sign_status DESC";
$query_cases_result = $conn->query($query_cases);

?>


<body>


	<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link active" data-toggle="tab" href="#home"><i class="fas fa-arrow-down"></i> Որոշումներ</a>
    
  </li>

  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#menu1"><i class="fas fa-arrow-up"></i> Ծանուցագրեր</a>
  </li>


  
  
 </ul>
 
 <!-- Tab panes -->
    <div class="tab-content">
      
      <div id="home" class="tab-pane active"><br> 
      	<table class="table" id="out_dec">
      		<thead>
      			<tr style="font-size: 0.9em; font-weight: normal; color: #828282; text-align: center; vertical-align: middle;">
      				<th>Գործի #</th>
      				<th>Մտից #</th>
      				<th>Ելից #</th>
      				<th>Տեսակ</th>
      				<th>Ելից ամսաթիվ</th>


      			</tr>
      		</thead>
      	</table>
      </div>

      <div id="menu1" class="tab-pane"><br>
      	
      </div>
      
    </div>  	
	



	 
</body>
