<?php
require_once 'config/connect.php';

$sql_staff = "SELECT COUNT(DISTINCT a.case_id) AS COUNT_CASES, COUNT(DISTINCT c.personal_id) AS COUNT_PERSON, b.id, b.username FROM tb_case a INNER JOIN tb_person c ON a.case_id = c.case_id INNER JOIN users b ON a.officer = b.id GROUP BY b.username";
$result_sql_staff = $conn->query($sql_staff);

?>


<body>
	<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link active" data-toggle="tab" href="#home">Ապաստանի հայցեր</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#menu1">Դատական հայցեր</a>
  </li>

</ul>
	<div class="tab-content">
      <div id="home" class="tab-pane active"><br>
      	
      	<table class="table table-stripped table-bordered">
      		<tr>
      			<th>հ/հ</th>
      			<th>աշխատակցի ա.ա.հ.</th>
      			<th>աշխատակցի վարույթում գտնվող գործերի քանակը</th>
      			<th>աշխատակցի վարույթում գտնվող գործերում ընդգրկված ապաստան հայցողների թիվը</th>
      		</tr>
      		<?php 
      		while($row = $result_sql_staff->fetch_assoc()){
      			$name = $row['username'];
      			$count_case = $row['COUNT_CASES'];
      			$count_person = $row['COUNT_PERSON'];
      	
      		?>

      		<tr>
      			<td>1</td>
      			<td><?php echo $name?></td>
      			<td><?php echo $count_case?></td>
      			<td><?php echo $count_person?></td>	
      		</tr>

      	<?php 	} ?>
      	</table>



      </div>

      <div id="menu1" class="tab-pane fade"><br>

      </div>
       	
      
    </div> <!-- closing tabs -->  	
</body>