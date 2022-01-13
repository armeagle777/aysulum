<?php
include('config/connect.php');
    date_default_timezone_set('Asia/Yerevan');



    $query_cases = "SELECT a.case_id, a.application_date, a.input_date, a.mul_num, a.mul_date, b.f_name_arm, b.l_name_arm, b.citizenship, i.country_id, i.country_eng, i.country_arm, c.sign_status, d.status, c.sign_date, e.case_status, a.input_date, c.sign_by, f.f_name AS SIGNER_NAME, f.l_name AS SIGNER_LNAME, a.officer, g.f_name AS OFFICER_NAME, g.l_name AS OFFICER_LNAME, c.processor, h.f_name AS PROCESSOR_NAME, h.l_name AS PROCESSOR_LNAME, j.decison_date, j.decision_type, k.decision_type AS DECISION_TYPE_TEXT, j.decision_status, l.decision_status AS DECISION_STATUS_TEXT 
		FROM tb_case a 
		INNER JOIN tb_person b ON a.case_id = b.case_id 
		INNER JOIN tb_country i ON b.citizenship = i.country_id
		INNER JOIN tb_process c ON a.case_id = c.case_id 
		INNER JOIN tb_sign_status d ON d.status_id = c.sign_status 
		INNER JOIN tb_case_status e ON a.case_status = e.case_status_id 
		INNER JOIN users f ON c.sign_by = f.id 
		LEFT JOIN users g ON a.officer = g.id 
		INNER JOIN users h ON c.processor = h.id 
		INNER JOIN tb_decisions j ON j.case_id = a.case_id
		INNER JOIN tb_decision_types k ON j.decision_type = k.decision_type_id
		INNER JOIN tb_decision_status l ON j.decision_status = l.decision_status_id
		WHERE b.role = 1 AND c.actual = 1 AND a.case_status = 3 AND j.actual = 1";

		$query_cases_result = $conn->query($query_cases);


?>


<div class="case_area mt-2">

	<table class="table table-stripped">
		<thead>
			<tr  style="font-size: 0.8em; font-weight: normal; color: #828282; text-align: center;">
				<th>Գործ #</th>
				<th>Մտից ամսաթիվ</th>
				<th>Մտից /Mul/#</th>
				<th>Ապաստան հայցող</th>
				<th>Քաղաքացիությունը</th>
				<th>Գործ վարող</th>
				<th>Որոշման ամսաթիվ</th>
				<th>Որոշման տեսակ</th>
				<th>...</th>
			</tr>
		</thead>

		<tbody>
			
		 <?php 
        while($row = $query_cases_result->fetch_assoc()){
            $case_id = $row['case_id'];
            $application_date = date('d.m.Y', strtotime($row['mul_date']));
            $mul_num 		  = $row['mul_num'];
            $main_applicant   = $row['f_name_arm'] .' '. $row['l_name_arm'];
            $citizenship      = $row['country_arm'];
            $ms_decision      = $row['DECISION_TYPE_TEXT'];
            $decision_date 	  = date('d.m.Y', strtotime($row['decison_date']));
            $officer 		  = $row['OFFICER_NAME'] .' '. $row['OFFICER_LNAME'];
        ?>
       
            <tr style="font-size: 1em; color:#324157; text-align: center;">
                <td class="b_table_modal"><?php echo $case_id?></td>
                <td><?php echo $application_date ?></td>
                <td><?php echo $mul_num ?></td>
                <td><?php echo $main_applicant ?></td>
                <td><?php echo $citizenship ?></td>
                <td><?php echo $officer ?></td>
                <td><?php echo $decision_date ?></td>
                <td><?php echo $ms_decision ?></td>
                <td><a href="#" id="archive_case" class="archive_case" archive_case="<?php echo $case_id?>" ><i class="fas fa-info"></i> </a> </td>

            </tr>
        
    <?php } ?>
    </tbody>


	</table>


</div>

<div class="modal fade" id="archive_case_modal">
</div>

<script> 
	$('.archive_case').on('click', function (e) {
				e.preventDefault();
				var archive_case_id = $(this).attr('archive_case');
				
				$.ajax(
						{
							url: "config/config.php",
							method: "POST",
							data: {archive_case_id: archive_case_id},
							success: function (data) {
								$('#archive_case_modal').html(data);
								$("#archive_case_modal").modal('show');
							}
						});
			})

</script>


