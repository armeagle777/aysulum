<?php 

//Database Connection
$conn = mysqli_connect('localhost', 'root', '', 'asylum');
//Check for connection error
if($conn->connect_error){
  die("Error in DB connection: ".$conn->connect_errno." : ".$conn->connect_error);    
}

$case_id = 167;

    $check_inters = "SELECT * FROM tb_inter WHERE case_id = $case_id AND inter_status = 2";
    $result_check_inter = $conn->query($check_inters);
    $count_drafts = '';
    if($result_check_inter -> num_rows > 0){
      $count_inters = mysqli_num_rows($result_check_inter);
      $count_inters++;
      $final_id = $count_inters;
    }
    else {
      $final_id = '1';
    }



  echo  $final_id;