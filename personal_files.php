<?php
  require_once 'config/connect.php';
  

  $case = $_GET['case'];

$sql_personal_files = "SELECT a.id, a.file_name, a.uploaded_on, a.file_type, a.uploader, a.case_id, a.person_id, b.file_type AS FILE_TEXT, c.f_name_arm, c.l_name_arm 
        FROM files a 
        LEFT JOIN tb_file_type b ON a.file_type = b.file_type_id
        LEFT JOIN tb_person c ON a.person_id = c.personal_id 
        WHERE a.case_id = $case AND b.file_filter = 2";
    $result_personal_files = $conn->query($sql_personal_files);

    $person_files = $result_personal_files->fetch_assoc();

    echo json_encode($person_files)

?>