<?php
 
require_once '../../config/connect.php';
date_default_timezone_set('Asia/Yerevan');
$table_body = '';
$today = date('Y-m-d');
$change_date = date('Y-m-d', strtotime("+5 days", strtotime($today)));

 $sql_case_waiting_appeal = "SELECT * FROM tb_case a INNER JOIN tb_deadline b ON a.case_id = b.case_id WHERE b.actual_dead = 1 AND b.deadline_type = 6 AND b.deadline <= '".$change_date."'";
 $result_case_waiting_appeal = $conn->query( $sql_case_waiting_appeal);




 if ($result_case_waiting_appeal->num_rows > 0) {
 	while($row = $result_case_waiting_appeal->fetch_assoc()){
 		$case_id = $row['case_id'];
 		$sql_uodate_case = "UPDATE tb_case SET case_status = 3 WHERE case_id = case_id";
 		if($conn->query($sql_uodate_case) === TRUE){
 			$update_processes = "UPDATE tb_process SET actual = 0 WHERE case_id = $case_id";
 			if($conn->query($update_processes) === TRUE){
 				$sql_update_deadline = "UPDATE tb_deadline SET actual_dead = 0 WHERE case_id = $case_id";
 				$result = $conn->query($sql_update_deadline);
 			}
 			else
 			{
 				echo "Error: " . $update_processes . "<br>" . $conn->error; 
 			}
 		}
 		else
 		{
 			echo "Error: " . $sql_uodate_case . "<br>" . $conn->error; 
 		}	
 	}
 }

 

?>


