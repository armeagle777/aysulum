<?php 

$case = $_POST['edit_case'];
$user = $_SESSION['user_id'];
$sql_case = "SELECT a.case_id, a.reason_1, a.reason_2, a.sequence_1, a.sequence_2, a.comment, b.f_name_arm, b.l_name_arm, c.sign_status, d.status, a.application_date, a.unaccompanied_child, a.separated_child, a.single_parent, a.prefered_language, a.preferred_lawyer, a.contact_tel, a.RA_marz, a.RA_community, a.RA_settlement, a.RA_street, a.RA_building, a.RA_apartment, j.ADM1_ARM AS MARZ, k.ADM3_ARM AS COMMUNITY, l.ADM4_ARM AS SETTLEMENT,
c.sign_date, e.case_status, a.input_date, a.deadline_1, a.deadline_2, 
c.sign_by, CONCAT(f.f_name, ' ', f.l_name) AS SIGNER_NAME, 
a.officer, CONCAT(g.f_name, ' ', g.l_name) AS OFFICER_NAME,
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
WHERE a.case_id = $case AND c.actual = '1'";

  $result_case = $conn->query($sql_case);
  
  if ($result_case->num_rows > 0) 
{
      $row = $result_case->fetch_assoc(); 
      $sign_status = $row['status'];
      $case_status = $row['case_status'];
      $deadline_1 = $row['deadline_1'];
      $deadline_2 = $row['deadline_2'];
      $application_date = date("d.m.Y", strtotime($row['application_date']));
      $input = date("d.m.Y", strtotime($row['input_date']));
      $reg_by = $row['REG_NAME'];
      $officer = $row['OFFICER_NAME'];
      $processor = $row['PROCESSOR_NAME'];
      $prefered_language = $row['prefered_language'];
      $contact_tel = $row['contact_tel'];
      $marz = $row['RA_marz'];
      $community = $row['RA_community'];
      $satl = $row['RA_settlement'];
      $RA_marz = $row['MARZ'];
      $RA_community = $row['COMMUNITY'];
      $RA_settlement = $row['SETTLEMENT'];
      $RA_street = $row['RA_street'];
      $RA_building = $row['RA_building'];
      $RA_apartment = $row['RA_apartment'];
      $reason_1 = $row['reason_1'];
      $reason_2 = $row['reason_2'];
      $sequence_1 = $row['sequence_1'];
      $sequence_2 = $row['sequence_2'];
      $comment_case = $row['comment'];


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
      
      $query_marz = "SELECT * FROM tb_marz";
      $marzz = mysqli_query($conn, $query_marz);
      $optmarz = '<select name="select_marz" id="select_marz" class="form-control form-control-sm">';
      while($row1 = mysqli_fetch_array($marzz)) {

      if($row1['marz_id'] == $marz)
      {
      $optmarz.= "<option selected=\"selected\" value=".$row1['marz_id'].">".$row1['ADM1_ARM']."</option>";
      }
      else
      {
      $optmarz.= "<option value=".$row1['marz_id'].">".$row1['ADM1_ARM']."</option>";
      }
      }
      $optmarz.="</select>";

      $query_community = "SELECT * FROM tb_arm_com";
      $communit = mysqli_query($conn, $query_community);
      $optcom = '<select name="select_community" id="select_community" class="form-control form-control-sm">';
      while($row2 = mysqli_fetch_array($communit)) {

      if($row2['community_id'] == $community)
      {
      $optcom.= "<option selected=\"selected\" value=".$row2['community_id'].">".$row2['ADM3_ARM']."</option>";
      }
      else
      {
      $optcom.= "<option value=".$row2['community_id'].">".$row2['ADM3_ARM']."</option>";
      }
      }
      $optcom.="</select>";

      $query_settl = "SELECT * FROM tb_settlement";
      $settl = mysqli_query($conn, $query_settl);
      $optsettl = '<select name="select_setl" id="select_setl" class="form-control form-control-sm">';
      while($row3 = mysqli_fetch_array($settl)) {

      if($row3['settlement_id'] == $satl)
      {
      $optsettl.= "<option selected=\"selected\" value=".$row3['settlement_id'].">".$row3['ADM4_ARM']."</option>";
      }
      else
      {
      $optsettl.= "<option value=".$row3['settlement_id'].">".$row3['ADM4_ARM']."</option>";
      }
      }
      $optsettl.="</select>";


    }

  ?>