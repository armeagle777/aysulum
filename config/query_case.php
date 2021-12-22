<?php 
$case = $_GET['case'];
  $u_id = $_SESSION['user_id'];

$sql_case = "SELECT a.case_id,  a.comment, a.case_status AS CASE_STATUS_ID, b.f_name_arm, b.l_name_arm, c.sign_status, d.status, a.application_date, a.unaccompanied_child, a.separated_child, a.single_parent, a.prefered_language, a.preferred_lawyer, a.contact_tel, a.contact_email, a.RA_marz, a.RA_community, a.RA_settlement, a.mul_num, a.mul_date, a.RA_street, a.RA_building, a.RA_apartment, a.officer, j.ADM1_ARM AS MARZ, k.ADM3_ARM AS COMMUNITY, l.ADM4_ARM AS SETTLEMENT, m.deadline, d.status_id AS SIGN_STATUS_ID, a.special, a.reopened, c.sign_date, e.case_status, a.input_date,  a.reopened, a.attached_case, q.cover_status, q.cover_actual,
c.sign_by, CONCAT(f.f_name, ' ', f.l_name) AS SIGNER_NAME, 
a.officer, CONCAT(g.f_name, ' ', g.l_name) AS OFFICER_NAME,
a.MS_lawyer, CONCAT(o.f_name, ' ', o.l_name) AS LAWYER_NAME,
a.reg_by,  CONCAT(i.f_name, ' ', i.l_name) AS REG_NAME,
c.processor, CONCAT(h.f_name, ' ', h.l_name) AS PROCESSOR_NAME 
FROM tb_case a 
INNER JOIN tb_person b ON a.case_id = b.case_id 
INNER JOIN tb_process c ON a.case_id = c.case_id 
INNER JOIN tb_sign_status d ON d.status_id = c.sign_status 
INNER JOIN tb_case_status e ON a.case_status = e.case_status_id 
INNER JOIN users f ON c.sign_by = f.id 
LEFT JOIN users g ON a.officer = g.id    
INNER JOIN users h ON c.processor = h.id
INNER JOIN users i ON a.reg_by = i.id 
INNER JOIN tb_marz j ON a.RA_marz = j.marz_id
INNER JOIN tb_arm_com k ON a.RA_community = k.community_id
INNER JOIN tb_settlement l ON a.RA_settlement = l.settlement_id
LEFT JOIN tb_deadline m ON a.case_id = m.case_id
LEFT JOIN users o ON a.MS_lawyer = o.id
LEFT JOIN (SELECT * FROM tb_cover_files WHERE cover_actual = '1') AS q ON a.case_id = q.case_id
WHERE a.case_id = $case AND c.actual = '1' AND m.actual_dead = '1'";

$result_case = $conn->query($sql_case);
  
  if ($result_case->num_rows > 0) 
{
      $row = $result_case->fetch_assoc(); 
      $sign_status_id = $row['SIGN_STATUS_ID'];
      $sign_status = $row['status'];
      $case_status = $row['CASE_STATUS_ID'];
      $case_status_text = $row['case_status'];
      $deadline_1 = date("d.m.Y", strtotime($row['deadline']));
      $application_date = date("d.m.Y", strtotime($row['application_date']));
      $input = date("d.m.Y", strtotime($row['input_date']));
      $mul_date = date("d.m.Y", strtotime($row['mul_date']));
      $mul_num = $row['mul_num'];
      $reg_by = $row['REG_NAME'];
      $officer = $row['OFFICER_NAME'];
      $lawyer  = $row['LAWYER_NAME'];
      $processor = $row['PROCESSOR_NAME'];
      $prefered_language = $row['prefered_language'];
      $contact_tel = $row['contact_tel'];
      $contact_email = $row['contact_email'];
      $marz = $row['RA_marz'];
      $community = $row['RA_community'];
      $satl = $row['RA_settlement'];
      $RA_marz = $row['MARZ'];
      $RA_community = $row['COMMUNITY'];
      $RA_settlement = $row['SETTLEMENT'];
      $RA_street = $row['RA_street'];
      $RA_building = $row['RA_building'];
      $RA_apartment = $row['RA_apartment'];
      $case_comment = $row['comment'];

      $unaccompanied_child = '';
      if ($row['unaccompanied_child'] == '1') {
        $unaccompanied_child = 'checked';
      }

      $separated_child = '';
      if ($row['separated_child'] == '1') {
        $separated_child = 'checked';
      }

      $single_parent = '';
      if ($row['single_parent'] == '1') {
        $single_parent = 'checked';
      }

      $preferred_lawyer = '';
      if ($row['preferred_lawyer'] == '1') {
        $preferred_lawyer = 'checked';
      }
      
      $holder_id = $row['processor'];
      $officer_id = '';
      if(!empty($row['officer'])){
        $officer_id = $row['officer'];
      }
      $single_parent_case = $row['single_parent'];
      $special_case = $row['special'];
      $reopened_case = $row['reopened'];
      $attached_case = $row['attached_case'];
      $separated_child_case = $row['separated_child'];
      $unaccompanied_child_case = $row['unaccompanied_child'];
      $cover_status_id = $row['cover_status'];
      $cover_actual = $row['cover_actual'];

      if($deadline_1 == strtotime('0000-00-00')){
        $deadline_1 = 'N/A';
    }
    }




     $sql_main_person = "SELECT a.personal_id, a.case_id, a.f_name_arm, a.f_name_eng, a.l_name_arm, a.l_name_eng, a.m_name_arm, a.m_name_eng, 
a.b_day, a.b_month, a.b_year, a.sex, a.citizenship, b.country_arm AS CITIZENSHIP, a.previous_residence, e.country_arm AS PREV_RES, 
a.citizen_adr, a.residence_adr, a.departure_from_citizen, a.departure_from_residence, a.arrival_date, a.doc_num, a.etnicity, c.etnic_arm AS ETNIC, 
a.religion, d.religion_arm AS RELIGION, a.preferred_traslator_sex, a.preferred_interviewer_sex, a.invalid, a.pregnant, 
a.seriously_ill, a.trafficking_victim, a.violence_victim, a.comment, a.illegal_border, a.transfer_moj, a.deport_prescurator, a.role
FROM tb_person a 
LEFT JOIN tb_country b ON a.citizenship = b.country_id 
LEFT JOIN tb_etnics c ON a.etnicity = c.etnic_id 
LEFT JOIN tb_religions d ON a.religion=d.religion_id
LEFT JOIN tb_country e ON a.previous_residence = e.country_id 
WHERE a.role = 1 AND a.case_id = $case";
  
  $result_main_person = $conn->query($sql_main_person);
  
  if ($result_main_person->num_rows > 0) 
{
      $row_p = $result_main_person->fetch_assoc();   
      $f_name_arm = $row_p['f_name_arm'];
      $f_name_eng = $row_p['f_name_eng'];
      $l_name_arm = $row_p['l_name_arm'];
      $l_name_eng = $row_p['l_name_eng'];
      $m_name_arm = $row_p['m_name_arm'];
      $m_name_eng = $row_p['m_name_eng'];
      $b_day = $row_p['b_day'];
      $b_month = $row_p['b_month'];
      $b_year = $row_p['b_year'];

      $sex = '';
      if ($row_p['sex'] == '1') {
        $sex = 'արական';
      }
      else {
        $sex = 'իգական';
      }

      $doc_num = $row_p['doc_num'];
      $citizenship = $row_p['CITIZENSHIP'];
      $citizen_adr = $row_p['citizen_adr'];
      $departure_from_citizen = $row_p['departure_from_citizen'];
      $previous_residence = $row_p['PREV_RES'];
      $residence_adr = $row_p['residence_adr'];
      $departure_from_residence = $row_p['departure_from_residence'];
      $arrival_date = $row_p['arrival_date'];
      $etnicity = $row_p['ETNIC'];
      $religion = $row_p['RELIGION'];

      $invalid = '';
      if ($row_p['invalid'] == '1') {
        $invalid = 'checked';
      }

      $pregnant = '';
      if ($row_p['pregnant'] == '1') {
        $pregnant = 'checked';
      }

      $seriously_ill = '';
      if ($row_p['seriously_ill'] == '1') {
        $seriously_ill = 'checked';
      }

      $trafficking_victim = '';
      if ($row_p['trafficking_victim'] == '1') {
        $trafficking_victim = 'checked';
      }

      $violence_victim = '';
      if ($row_p['violence_victim'] == '1') {
        $violence_victim = 'checked';
      }
    }


$sql_lawyer = "SELECT * FROM tb_lawyer WHERE case_id = $case AND actual = 1";
$result_lawyer = $conn->query($sql_lawyer); 

 if ($result_lawyer->num_rows > 0) 
{
      $row_l = $result_lawyer->fetch_assoc();
      $lawyer_id = $row_l['lawyer_id'];
      $lawyer_name = $row_l['lawyer_name'];
      $lawyer_surname = $row_l['lawyer_surname'];
      $lawyer_organization = $row_l['lawyer_organization'];
      $lawyer_tel = $row_l['lawyer_tel'];
      $lawyer_address = $row_l['lawyer_address'];
      $lawyer_email = $row_l['lawyer_email'];
    

}      

$sql_inter = "SELECT a.inter_id, a.case_id, a.author_id, a.inter_status, a.inter_reciever, a.inter_type, a.send_type, b.inter_process_id, b.sender, b.rec_id, b.actual, b.actioned, b.action_type, b.inter_msg, c.inter_file_id, c.inter_file, c.inter_process_id, c.inter_file_actual, c.uploaded, d.inter_action_type_id, d.action_type AS ACTION_TYPE_TEXT 
FROM tb_inter a 
INNER JOIN tb_inter_process b ON a.inter_id = b.inter_id 
INNER JOIN tb_inter_action_types d ON b.action_type = d.inter_action_type_id 
LEFT JOIN (SELECT * FROM tb_inter_file WHERE inter_file_actual = 1) AS c ON c.inter_id = a.inter_id WHERE a.case_id = $case AND b.actual = 1 AND a.inter_status = 1";

          $result_inter = $conn->query($sql_inter);
          
          if($result_inter->num_rows > 0){
            $row_inter = $result_inter->fetch_assoc();
            
            $inter_sender_id = $row_inter['sender'];
            $inter_receiver_id = $row_inter['rec_id'];
            $filename    = $row_inter['inter_file'];
            $action_type_text = $row_inter['ACTION_TYPE_TEXT'];
            $inter_id = $row_inter['inter_id'];
            $inter_msg = $row_inter['inter_msg'];
            $inter_status_id = $row_inter['inter_status'];
            if ($inter_status_id == 1) {
              $inter_status_text = 'ընթացիկ';
             }
            if ($inter_status_id == 2) {
              $inter_status_text = 'ավարտված';
             } 

            $inter_addresat_id = $row_inter['inter_reciever'];
            
            if ($inter_addresat_id == 1) {
                $addresat = 'ապաստան հայցող';
              }  
            if ($inter_addresat_id == 2) {
                $addresat = 'փաստաբան';
              }  
            if ($inter_addresat_id == 3) {
                $addresat = 'ապաստան հայցող և փաստաբան';
              }

            $inter_type_id = $row_inter['inter_type'];        

            if ($inter_type_id == 1) {
              $inter_type_text = 'հարցազրույցի հրավեր';
            }
            if ($inter_type_id == 2) {
              $inter_type_text = 'երկարաձգման ծանուցագիր';
            }
            if ($inter_type_id == 3) {
              $inter_type_text = 'բավարարման / մերժման ծանուցագիր';
            }
            if ($inter_type_id == 4) {
              $inter_type_text = 'կասեցման ծանուցագիր';
            }
            if ($inter_type_id == 5) {
              $inter_type_text = 'կարճման ծանուցագիր';
            }
            if ($inter_type_id == 6) {
              $inter_type_text = 'այլ ծանուցագիր';
            }

            $send_type_id = $row_inter['send_type'];

            if ($send_type_id == 1) {
              $send_type_text = 'փոստ';
            }

            if ($send_type_id == 2) {
              $send_type_text = 'էլ. փոստ';
            }

            if ($send_type_id == 3) {
              $send_type_text = 'առձեռն';
            }

            if ($send_type_id == 4) {
              $send_type_text = 'սուրհանդակ';
            }

            $inter_msg_out = 'Խնդրում եմ առաքել հասցեատիրոջը։';

}
else{
  $inter_receiver_id = '';
}





?>