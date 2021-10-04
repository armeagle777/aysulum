 <?php



 require('../config/connect.php');
date_default_timezone_set('Asia/Yerevan');

//Person Modal Echo

  $sql_main_person = "SELECT a.personal_id, a.case_id, a.f_name_arm AS ANUN, a.f_name_eng, a.l_name_arm, a.l_name_eng, a.m_name_arm, a.m_name_eng, 
a.b_day, a.b_month, a.b_year, a.sex, a.citizenship, a.previous_residence,  
a.citizen_adr, a.residence_adr, a.departure_from_citizen, a.departure_from_residence, a.arrival_date, a.doc_num, a.etnicity, a.religion, a.preferred_traslator_sex, a.preferred_interviewer_sex, a.invalid, a.pregnant, 
a.seriously_ill, a.trafficking_victim, a.violence_victim, a.comment, a.illegal_border, a.wanted_moj, a.wanted_court, a.role
FROM tb_person a 
WHERE a.personal_id = 9";
  $modal_response='';
  $result_main_person = $conn->query($sql_main_person);
  
  if ($result_main_person->num_rows > 0) 
{
      $row_p = $result_main_person->fetch_assoc();
      $case_id = $row_p['case_id'];   
      $f_name_arm = $row_p['ANUN'];
      $f_name_eng = $row_p['f_name_eng'];
      $l_name_arm = $row_p['l_name_arm'];
      $l_name_eng = $row_p['l_name_eng'];
      $m_name_arm = $row_p['m_name_arm'];
      $m_name_eng = $row_p['m_name_eng'];
      $b_day = $row_p['b_day'];
      $b_month = $row_p['b_month'];
      $b_year = $row_p['b_year'];
      $role = $row_p['role'];

      echo $case_id . ' ' . $f_name_arm;
  }
?>