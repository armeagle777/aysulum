<?php 

//Database Connection
$conn = mysqli_connect('localhost', 'root', '', 'asylum');
//Check for connection error
if($conn->connect_error){
  die("Error in DB connection: ".$conn->connect_errno." : ".$conn->connect_error);    
}

if(isset($_POST['submit'])){
 // Count total uploaded files
 $totalfiles = count($_FILES['file']['name']);
 $total_types = count($_POST['file_type']);


 // Looping over all files
 for($i=0;$i<$totalfiles;$i++){
 $filename = $_FILES['file']['name'][$i];
 $filetypes = $_POST['file_type'][$i];
 

// Upload files and store in database
// if(move_uploaded_file($_FILES["file"]["tmp_name"][$i],'uploads/'.$filename)){
//     // Image db insert sql
//     $insert = "INSERT into files(file_name,uploaded_on,status) values('$filename',now(),1)";
//     if(mysqli_query($conn, $insert)){
//       echo 'Data inserted successfully';
//     }
//     else{
//       echo 'Error: '.mysqli_error($conn);
//     }
//   }else{
//     echo 'Error in uploading file - '.$_FILES['file']['name'][$i].'<br/>';
//   }
 
 }
 echo $filename, $filetypes;
} 


?>