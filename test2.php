<?php 

require_once 'config/connect.php';



$sql_drafts = "SELECT a.draft_id, a.case_id, a.draft_file, a.autor, a.uploaded, a.deadline, a.receiver, a.draft_comment, a.actual, b.f_name AS AUTOR_FNAME, b.l_name AS AUTOR_LNAME, c.f_name AS RECEIVER_FNAME, c.l_name AS RECEIVER_LNAME, d.f_name_arm, d.l_name_arm, d.m_name_arm FROM tb_draft a INNER JOIN users b ON a.autor = b.id INNER JOIN users c ON a.receiver = c.id INNER JOIN tb_person d ON a.case_id = d.case_id WHERE a.actual = 1 AND d.role = 1 AND a.draft_id = 28 ";
$result_draft = $conn->query($sql_drafts);

if ($result_draft->num_rows > 0) 
    {
      $row = $result_draft->fetch_assoc();
       $case_id = $row['case_id'];
        $asylum_seeker = $row['f_name_arm'] . ' ' . $row['l_name_arm'] .' '. $row['m_name_arm'];
        $author = $row['AUTOR_FNAME'] . ' ' . $row['AUTOR_LNAME'];
        $sdate  = date("Y-m-d", strtotime($row['uploaded']));
        $ddate  = $row['deadline'];
        $actual_date = date("Y-m-d");

        $deadline = new DateTime($ddate);
        $today = new DateTime($actual_date);
        
        $abs_diff = $today->diff($deadline)->format("%r%a");
     



echo $abs_diff .'<br>';



 }

?>