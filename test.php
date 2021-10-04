<?php 

//Database Connection
$conn = mysqli_connect('localhost', 'root', '', 'asylum');
//Check for connection error
if($conn->connect_error){
  die("Error in DB connection: ".$conn->connect_errno." : ".$conn->connect_error);    
}


 $sql_reciever_change_special = "SELECT id, f_name, l_name, FROM users WHERE user_type = devhead";
  $result_reciever = $conn->query($sql_reciever_change_special);
  if($result_reciever -> num_rows > 0) {
    $rec_row  = $result_reciever->fetch_assoc();
    $rec_id   = $rec_row['id'];
    $rec_name = $rec_row['f_name'] .' '. $rec_row['l_name'];
  }

  echo $rec_name ;