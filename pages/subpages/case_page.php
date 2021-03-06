<?php

	require_once 'config/connect.php';
	require_once 'config/query_case.php';

	$case = $_GET['case'];
	$u_id = $_SESSION['user_id'];
	$role = $_SESSION['role'];

	if (isset($_GET['notification_id'])) {
		$note_id = $_GET['notification_id'];
		$query = "UPDATE tb_notifications SET comment_status = 1 WHERE comment_id = $note_id";
		$result_notify = $conn->query($query);
	}

	if (isset($_GET['case'])) {
		$change_process_read = "UPDATE `tb_process` SET `comment_status`='1' WHERE `case_id` = $case";
		$result_process_read = $conn->query($change_process_read);
	}


	$sql_coi = "SELECT * FROM tb_coi a INNER JOIN users b ON a.to_coispec = b.id WHERE case_id = $case";
	$result_coi = $conn->query($sql_coi);
	$decision_type_id = '';
	$decession_files = "SELECT * FROM tb_decisions a INNER JOIN tb_decision_types b On a.decision_type = b.decision_type_id INNER JOIN tb_decision_status c ON a.decision_status = c.decision_status_id WHERE a.case_id = $case";
	if ($_SESSION['role'] === 'devhead' || $_SESSION['role'] === 'head' || $_SESSION['role'] === 'officer') {
		$decession_files .= " AND actual = 1";

	}

	$result_decision_file = $conn->query($decession_files);

	$sql_draft_list = "SELECT a.draft_id, a.case_id, a.draft_file, a.autor, a.uploaded, a.deadline, a.receiver, a.draft_comment, a.actual, CONCAT(b.f_name, ' ', b.l_name) AS AUTOR_NAME, CONCAT(c.f_name, ' ', c.l_name) AS RECEIVER_NAME FROM tb_draft a INNER JOIN users b ON a.autor = b.id INNER JOIN users c ON a.receiver = c.id WHERE case_id = $case";
	$result_list = $conn->query($sql_draft_list);

	$sql_personal_files = "SELECT a.id, a.file_name, a.uploaded_on, a.file_type, a.uploader, a.case_id, a.person_id, b.file_type AS FILE_TEXT, c.f_name_arm, c.l_name_arm 
        FROM files a 
        LEFT JOIN tb_file_type b ON a.file_type = b.file_type_id
        LEFT JOIN tb_person c ON a.person_id = c.personal_id 
        WHERE a.case_id = $case AND b.file_filter = 2";
	$result_personal_files = $conn->query($sql_personal_files);


	$sql_request = "SELECT a.request_process_id, a.request_id, a.user_from, a.request_user_to, a.request_actual, a.process_date, a.process_status, a.process_comment, b.case_id, b.author, b.body, b.request_date, b.request_status, c.body_id, c.body, d.request_process_status_id, d.request_process_status, e.file_name, e.file_type, f.file_type 
FROM tb_request_process a 
INNER JOIN tb_request_out b ON a.request_id = b.request_id 
INNER JOIN tb_request_bodies c ON b.body = c.body_id
INNER JOIN tb_request_process_status d ON a.process_status = d.request_process_status_id
INNER JOIN files e ON a.request_process_id = e.request_process_id
INNER JOIN tb_file_type f ON e.file_type = f.file_type_id
WHERE a.request_actual = 1 AND b.case_id = $case";
	$result_request = $conn->query($sql_request);

	$select_files = "SELECT * FROM files a INNER JOIN tb_file_type b ON a.file_type = b.file_type_id WHERE a.case_id = $case AND b.file_filter = 1";
	$result_file = $conn->query($select_files);

	$sql_out_members = "SELECT a.member_id, a.case_id, a.f_name_arm, a.f_name_eng, a.l_name_arm, a.l_name_eng, a.m_name_arm, a.m_name_eng, a.b_day, a.b_month, a.b_year, a.sex, a.citizenship, a.residence, a.role, b.country_arm AS CITIZEN, c.country_arm AS RES, d.der AS DER FROM tb_members a LEFT JOIN tb_country b ON a.citizenship = b.country_id LEFT JOIN tb_country c ON a.residence = c.country_id INNER JOIN tb_role d ON a.role = d.role_id WHERE a.case_id = $case";

	$result_out_members = $conn->query($sql_out_members);


	$lawyer_name = '';
	$lawyer_surname = '';
	$lawyer_organization = '';
	$lawyer_tel = '';
	$lawyer_email = '';
	$lawyer_address = '';
	$sql_lawyer = "SELECT * FROM tb_lawyer WHERE case_id = $case AND actual = 1";
	$result_lawyer = $conn->query($sql_lawyer);

	if ($result_lawyer->num_rows > 0) {
		$row_lawyer = $result_lawyer->fetch_assoc();
		$lawyer_name = $row_lawyer['lawyer_name'];
		$lawyer_surname = $row_lawyer['lawyer_surname'];
		$lawyer_organization = $row_lawyer['lawyer_organization'];
		$lawyer_tel = $row_lawyer['lawyer_tel'];
		$lawyer_email = $row_lawyer['lawyer_email'];
		$lawyer_address = $row_lawyer['lawyer_address'];
	}


	$sql_process = "SELECT a.process_id, a.case_id, a.sign_status, a.sign_date, a.sign_by, a.processor, a.comment_to, b.status_id, b.status, c.f_name AS SENDER_NAME, c.l_name AS SENDER_L_NAME, d.f_name AS REC_NAME, d.l_name AS REC_L_NAME FROM tb_process a INNER JOIN tb_sign_status b ON a.sign_status = b.status_id LEFT JOIN users c ON a.sign_by = c.id LEFT JOIN users d ON a.processor = d.id WHERE a.case_id = $case";
	$result_sql_process = $conn->query($sql_process);

	$let_in_order = 0;
	$sql_order_for_status = "SELECT * FROM tb_orders a WHERE a.case_id = $case";
	$result_order_status = $conn->query($sql_order_for_status);

	if ($result_order_status->num_rows > 0) {
		$let_in_order = 1;
	}

	$sql_court = "SELECT * FROM tb_case a 
        LEFT JOIN tb_court_claim b ON a.case_id = b.case_id 
        LEFT JOIN tb_appeals c ON b.claim_id = c.claim_id 
        INNER JOIN tb_appeal_types d ON d.appeal_type_id = c.appeal_type 
        WHERE a.case_id = $case AND b.claim_actual = 1";
	$result_sql_court = $conn->query($sql_court);
	if ($result_sql_court->num_rows > 0) {
		$claim_row = $result_sql_court->fetch_assoc();
		$claim_id = $claim_row['claim_id'];
		$appeal_id = $claim_row['appeal_id'];
	}


?>

<input type="hidden" name="user" id="user_id" value="<?php echo $u_id ?>">
<input type="hidden" id="case" value="<?php echo $case ?>">


<div class="btn_area">
	<div class="row">

		<div class="dropdown ml-3">
			<button class="btn btn-primary btn-sm">???????????????? ??????????????????????????</button>
			<div class="dropdown-content">
				<?php
					if (($sign_status_id == 1 || $sign_status_id == 9) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'operator')) {
						?>
						<a href="#" id="to_asign" modal_id="<?php echo $case ?>" modal_case="<?php echo $u_id ?>"> <i
									class="fas fa-share-square first_menu"></i> ???????????????? ??????????????????</a>

						<?php
					}
				?>

				<?php
					$query_decision_for_status = "SELECT * FROM tb_decisions WHERE case_id = $case and actual = 1";
					$result_status = $conn->query($query_decision_for_status);
					if ($result_status->num_rows > 0) {
						$row = $result_status->fetch_assoc();
						$decision_status = $row['decision_status'];
					}
					if (($sign_status_id == 2 && $_SESSION['role'] === 'operator') || ($sign_status_id == 13 && ($_SESSION['role'] === 'officer' || $_SESSION['role'] === 'coispec' || $_SESSION['role'] === 'lawyer') && $decision_status == 1) || ($sign_status_id == 13 && $_SESSION['role'] === 'devhead' && $decision_status == 3) || ($sign_status_id == 3 && $_SESSION['role'] === 'devhead') && ($case_status != 4 && $case_status != 3)) {
						?>

						<a href="#" id="call_modal" modal_id="<?php echo $case ?>" modal_case="<?php echo $u_id ?>"
						   modal_role="<?php echo $role ?>"><i class="fas fa-undo-alt first_menu"></i> ?????? ????????????</a>
						<?php
					}

				?>



				<?php


					if (($sign_status_id === '2' || $sign_status_id === '19' || $sign_status_id === '12') && $_SESSION['role'] === 'devhead') {
						?>
						<a href="#" id="myBtn3" modal_id="<?php echo $case ?>" modal_case="<?php echo $u_id ?>"><i
									class="fas fa-user-plus"></i> ???????????????? ??????????????</a>


						<?php
					}
				?>
				
				<?php
					if (($sign_status_id === '23') && $_SESSION['role'] === 'devhead') {
						?>
    					<a href="#" id="asign_lawer" modal_id="<?php echo $case ?>" modal_case="<?php echo $u_id ?>"><i class="fas fa-user-plus first_menu"> </i> ???????????????? ????????????????</a>

						<?php
					}
				?>

				<?php
					if (($holder_id == $u_id && $case_status != 4 && $case_status != 3 && $sign_status_id != 8) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'devhead' || $_SESSION['role'] === 'head')) {
						?>

						<a href="#" id="return_redev" re_case="<?php echo $case ?>" sender_role="<?php echo $_SESSION['role'] ?>" proc_status="<?php echo $sign_status_id?>"><i class="fas fa-exchange-alt first_menu"></i>	?????????????????????? ????????????????????</a>


						<?php
					}
				?>

				<?php
					if (($sign_status_id != '16' && $sign_status_id != 12 && $sign_status_id != '13' && $sign_status_id != '9' && $case_status != 4 && $case_status != 3) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'officer' || $_SESSION['role'] === 'coispec' || $_SESSION['role'] === 'coispec')) {
						?>
						<a href="#" id="re_sign" modal_id="<?php echo $case ?>" modal_case="<?php echo $u_id ?>"><i
									class="fas fa-undo-alt first_menu"></i> ?????????????????????? </a>


						<?php
					}
				?>
				<?php
					if (($sign_status_id == 3 || $sign_status_id == 14 || $sign_status_id == 7 || $sign_status_id == 16 || $sign_status_id == 20 || $sign_status_id == 9 || $sign_status_id == 29) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'officer' || $_SESSION['role'] === 'coispec' || $_SESSION['role'] === 'lawyer')) {
						?>

						<a href="#" id="decision" modal_id="<?php echo $case ?>" modal_user="<?php echo $u_id ?>" proc_status="<?php echo $sign_status_id ?>"> <i
									class="far fa-paper-plane first_menu"></i> ???????????????? ??????????????????</a>
						<?php
					}
				?>

				<?php
					if (($sign_status_id != '15' && $sign_status_id != '14' && $sign_status_id != '24' && $sign_status_id != 12 && $sign_status_id != '13' && $sign_status_id != '16' && $sign_status_id != '8') && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'officer' || $_SESSION['role'] === 'coispec' || $_SESSION['role'] === 'lawyer')) {
						?>

						<a href="#" id="draft" modal_id="<?php echo $case ?>" modal_case="<?php echo $u_id ?>"><i
									class="fas fa-highlighter first_menu"></i> ?????????????? ?????????????? </a>
						<?php
					}
				?>

				<?php


					$inter_allow = '0';
					if ($inter_receiver_id == $_SESSION['user_id'] && $inter_action_id == 2) {
						$inter_allow = '1';
					}

					if ($result_inter->num_rows > 0 && $inter_status_id != 0) {
						$inter_allow = '15';
					}

					if ($result_inter->num_rows < 1) {
						$inter_allow = '1';
					}


					if (($inter_allow == 1 || $inter_allow == 0) && ($sign_status_id != '15' && $sign_status_id != '14' && $sign_status_id != 12 && $sign_status_id != '24' && $sign_status_id != '13' && $sign_status_id != '8') && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'officer' || $_SESSION['role'] === 'coispec' || $_SESSION['role'] === 'lawyer')) {
						?>

						<a href="#" id="intermediate" modal_id="<?php echo $case ?>" modal_case="<?php echo $u_id ?>"><i
									class="fas fa-file-export first_menu"></i> ?????????????????? </a>
						<?php
					}
				?>


				<?php
					if (($sign_status_id == 13 && $case_status != 4 && $case_status != 3 && $holder_id == $_SESSION['user_id']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'devhead')) {
						?>

						<a href="#" id="decision_to_head_1" modal_id="<?php echo $case ?>"
						   modal_user="<?php echo $u_id ?>"><i class="far fa-paper-plane first_menu"></i> ???????????????? ??????????????????</a>

						<?php
					}
				?>

				<?php
					if (($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'head') && ($case_status != 4 && $case_status != 3 && $sign_status_id != 8 && $sign_status_id != 16 && $sign_status_id != 7) && ($sign_status_id == 13 && $holder_id == $_SESSION['user_id'])) {
						?>

						<a href="#" id="approve_head" casenum="<?php echo $case ?>"><i
									class="fas fa-file-signature first_menu"></i> ????????????????</a>

						<?php
					}
				?>
				<?php
					if (($reopened_case == 1) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'devhead')) {
						?>

						<a href="#" id="change_deadline" modal_id="<?php echo $case ?>"><i
									class="far fa-calendar-times first_menu"></i> ?????????? ??????????????????????????</a>

						<?php
					}
				?>



				<?php
					if (($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'operator' || $_SESSION['role'] === 'officer' || $_SESSION['role'] === 'lawyer' || $_SESSION['role'] === 'coispec') && ($sign_status_id != 16 && $sign_status_id != '13' && $sign_status_id != 12 && $case_status != 4 && $case_status != 3)) {
						?>

						<a href="#" id="change_case_type" modal_id="<?php echo $case ?>"><i
									class="fas fa-text-width first_menu"></i> ?????????? ?????????? ????????</a>

						<?php
					}
				?>

				<?php
					if (($sign_status_id == 21) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'devhead')) {
						?>

						<a href="#" id="approve_special_change" modal_id="<?php echo $case ?>"><i
									class="fas fa-text-width first_menu"></i> ?????????? ?????????? ????????</a>

						<?php
					}
				?>

				<?php
					if (($case_status == 4 && $sign_status_id != 22 && $sign_status_id != 23) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'operator' || $_SESSION['role'] === 'lawyer')) {
						?>

						<a href="#" id="court_claim" modal_id="<?php echo $case ?>"><i
									class="fas fa-gavel first_menu"></i> ?????????????? ????????</a>

						<?php
					}
				?>

				<?php
					if (($case_status == 4 && $sign_status_id == 22) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'operator' || $_SESSION['role'] === 'lawyer')) {
						?>

						<a href="#" id="court_accept" modal_id="<?php echo $case ?>"><i
									class="fas fa-vote-yea first_menu"></i> ?????????????????? ?? ??????????????</a>

						<?php
					}
				?>

				<?php
					if (($case_status == 2 && $sign_status_id == 24) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'operator' || $_SESSION['role'] === 'lawyer')) {
						?>

						<a href="#" id="court_decission" modal_id="<?php echo $case ?>" claim="<?php echo $claim_id ?>"
						   appeal="<?php echo $appeal_id ?>"><i class="fas fa-vote-yea first_menu"></i> ????????????????
							????????</a>

						<?php
					}
				?>


			</div>
		</div>

		<div class="dropdown ml-2">
			<button class="btn btn-secondary btn-sm">?????????????????? ??????????????????????????</button>
			<div class="dropdown-content">
				<?php
					if (($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'coispec') && ($case_status != 4 && $case_status != 3)) {
						if ($result_coi->num_rows > 0) {
							$row = $result_coi->fetch_assoc();
							$coi_num = $row['coi_id'];
						}
						?>
						<a href="#" id="answer_coi" coi_id="<?php echo $coi_num ?>"><i
									class="fas fa-reply second_menu"></i> ????????????????????</a>

						<?php
					}
				?>

				<?php
					if (($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'officer' || $_SESSION['role'] === 'coispec' || $_SESSION['role'] === 'lawyer') && ($case_status != 4 && $case_status != 3)) {
						?>

						<a href="#" id="myBtn4" modal_id="<?php echo $case ?>" modal_case="<?php echo $u_id ?>"> <i
									class="fas fa-info second_menu"></i> ?????? ??????????????</a>
						<a href="#" id="request" modal_id="<?php echo $case ?>" modal_case="<?php echo $u_id ?>"> <i
									class="fas fa-file-export second_menu"></i>????????????????????</a>
						<a href="?page=calendar&case_id=<?php echo $case ?>"><i
									class="fas fa-calendar-day second_menu"></i> ???????????????? ??????????????????????</a>


						<?php
					}
				?>

				<?php
					if (($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'officer' || $_SESSION['role'] === 'operator' || $_SESSION['role'] === 'coispec' || $_SESSION['role'] === 'lawyer') && ($case_status != 4 && $case_status != 3)) {
						?>
						<a href="#" id="member_out" modal_id="<?php echo $case ?>"><i
									class="fas fa-users second_menu"></i> ???????????? ?????????? ??????????</a>
						<a href="user.php?page=cases&homepage=add_person&case=<?php echo $case ?>"><i
									class="fas fa-plus second_menu"></i> ?????? ?????????? </a>
						<a href="#" id="myBtn5" modal_id="<?php echo $case ?>"><i
									class="far fa-keyboard second_menu"></i> ????????????????</a>
						<a href="#" id="attach_lawyer" casenum1="<?php echo $case ?>"><i
									class="fas fa-balance-scale-right second_menu"></i> ???????? ????????????????</a>

						<?php
					}
				?>

				<?php
					if (($let_in_order == '0') && ($_SESSION['role'] === 'officer' || $_SESSION['role'] === 'operator' || $_SESSION['role'] === 'lawyer' || $_SESSION['role'] === 'coispec')) {
						?>
						<a href="#" id="ref_to_center" ref_case="<?php echo $case ?>"><i
									class="fas fa-concierge-bell second_menu"></i> ???????????????? ?????????????? </a>


						<?php
					}
				?>

				<?php
					if (($holder_id == $_SESSION['user_id']) && ($_SESSION['role'] === 'officer' || $_SESSION['role'] === 'lawyer' || $_SESSION['role'] === 'coispec')) {
						?>

						<a href="#" id="mail_translate" case="<?php echo $case ?>"
						   language="<?php echo $prefered_language ?>"><i class="fas fa-at second_menu"></i>
							???????????????????????????? </a>

						<?php
					}
				?>
			</div>

		</div>
		<div style="width: 50%;" class="ml-auto mr-3">
			<?php if (isset($special_case) && $special_case == 1) { ?>
				<span style="float: right; color:red; font-size: 1em;"><i class="fas fa-exclamation-triangle ml-2"></i> ???????????? ????????</span>
			<?php }
			?>

			<?php if (isset($reopened_case) && $reopened_case == 1) { ?>
				<span style="float: right; color:red; font-size: 1em;"><i class="fas fa-exclamation-triangle ml-2"></i> ?????????????? ????????</span>
			<?php }
			?>

			<?php if (isset($single_parent_case) && $single_parent_case == 1) { ?>
				<span style="float: right; color:red; font-size: 1em;"><i class="fas fa-exclamation-triangle ml-2"></i> ?????????????? ????????</span>
			<?php }
			?>

			<?php if (isset($separated_child_case) && $separated_child_case == 1) { ?>
				<span style="float: right; color:red; font-size: 1em;"><i class="fas fa-exclamation-triangle ml-2"></i> ?????????? ??????????</span>
			<?php }
			?>

			<?php if (isset($unaccompanied_child_case) && $unaccompanied_child_case == 1) { ?>
				<span style="float: right; color:red; font-size: 1em;"><i class="fas fa-exclamation-triangle ml-2"></i> ?????????? ?????????????????? ??????????</span>
			<?php }
			?>




			<?php if (isset($reopened_case) && isset($special_case) && $reopened_case == 0 && $special_case == 0) { ?>
				<span style="float: right; color:green; font-size: 1em;"><i
							class="fas fa-exclamation-triangle ml-1"></i> ?????????????????? ????????????????????</span>
			<?php }
			?>


		</div>
	</div>
</div>


<div class="case_area">

	<div class="tab">
		<button class="tablinks" onclick="openCity(event, 'functions')" id="defaultOpen">??????????????????????????</button>
		<button class="tablinks" onclick="openCity(event, 'reason_sequence')">?????????? ??????????</button>
		<button class="tablinks" onclick="openCity(event, 'main_applicant')">?????????????? ????????????????????</button>
		<button class="tablinks" onclick="openCity(event, 'family')" id="familyTab">???????????????? ????????????????</button>
		<button class="tablinks" onclick="openCity(event, 'additional_data')">?????????? ????????????????????</button>
		<button class="tablinks" onclick="openCity(event, 'coi')">????????????????????</button>
		<button class="tablinks" onclick="openCity(event, 'case_history')">?????????????? ??????????????????????</button>
		<button class="tablinks" onclick="openCity(event, 'files')">?????????????? ?????????????? ????????????????????</button>
		<button class="tablinks" onclick="openCity(event, 'translations')">??????????????????????????????????</button>
		<button class="tablinks" onclick="openCity(event, 'inter_msgs')">????????????????????????</button>
		<button class="tablinks" onclick="openCity(event, 'dec_history')">?????????????????????? ??????????????????????</button>
	</div>


	<div id="inter_msgs" class="tabcontent">
		<div class="row">
			<div class="col-md-12">
				<h5 class="sub_title" style="margin-top: 5px;">?????????????????? ????????????????????????</h5>
				<table id="inter_msg_table" class="display" style="width:100%">
					<thead>
					<tr>
						<th class="table_a2"></th>
						<th class="table_a2">N</th>
						<th class="table_a2">??????????</th>
						<th class="table_a2">????????????</th>
						<th class="table_a2">?????????????? ??????????????</th>
						<th class="table_a2">????????????????????</th>
					</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>


	<div id="translations" class="tabcontent">
		<div class="row">
			<div class="col-md-12">
				<h5 class="sub_title" style="margin-top: 5px;">?????????????????????????? ????????????????????</h5>
				<table id="translations_table" class="display" style="width:100%">
					<thead>
					<tr>
						<th class="table_a2"></th>
						<th class="table_a2">??????????</th>
						<th class="table_a2">????????????????????????????????</th>
						<th class="table_a2">?????????????????????????? ??/??</th>
						<th class="table_a2">??????????</th>
						<th class="table_a2">??????????</th>
						<th class="table_a2">????????????????????</th>
					</tr>
					</thead>
				</table>
			</div>

			<div class="col-md-6">
			</div>
		</div>
	</div>


	<div id="case_history" class="tabcontent">
		<h5 class="sub_title" style="margin-top: 5px;">?????????? ?????????????? ??????????????????????</h5>
		<table class="table table-bordered">
			<tr>
				<th class="label_pers_page">??????????????</th>
				<th class="label_pers_page">????????????????????</th>
				<th class="label_pers_page">??????????????</th>
				<th class="label_pers_page">????????????</th>
				<th class="label_pers_page">????????????????????????????????</th>
			</tr>

			<?php
				while ($row_process = $result_sql_process->fetch_assoc()) {
					$process_status = $row_process['status'];
					$process_date = date('d.m.Y', strtotime($row_process['sign_date']));
					$process_sender = $row_process['SENDER_NAME'] . ' ' . $row_process['SENDER_L_NAME'];
					$process_rec = $row_process['REC_NAME'] . ' ' . $row_process['REC_L_NAME'];
					$process_comment = $row_process['comment_to'];
					?>
					<tr>
						<td><?php echo $process_date ?></td>
						<td><?php echo $process_status ?></td>
						<td><?php echo $process_sender ?></td>
						<td><?php echo $process_rec ?></td>
						<td><?php echo $process_comment ?></td>
					</tr>

					<?php
				}

			?>
		</table>
	</div>


	<div id="coi" class="tabcontent">
		<div class="col-md-12">
			<div class="row">
				<h5 class="sub_title" style="margin-top: 5px;">?????? ?????????????????? ?????????????????????? ?????????????????????? </h5>

				<?php
					$msg = '
      <div class="col-md-12" style="align-text: center;">
        <br>
        <h5> ???????????????????? ?????? ????????????????</h5>
      </div>
     ';
					if ($result_request->num_rows > 0) {
				?>
				<table class="table table-stripped table-bordered">
					<tr>
						<th class="coi_table" width="15%">????????????????????</th>
						<th class="coi_table" width="15%">????????????</th>
						<th class="coi_table" width="15%">?????????????? ??????????????</th>
						<th class="coi_table" width="15%">?????????????????????? ??????????????</th>
						<th class="coi_table" width="15%">?????????????????? ??????????</th>
						<th class="coi_table">?????? ??????????</th>
					</tr>
					<?php

						while ($row_request = mysqli_fetch_array($result_request)) {
							$request_date = date("d.m.Y", strtotime($row_request['request_date']));


							$response_date = date("d.m.Y", strtotime($row_request['process_date']));

							$request_status = $row_request['request_process_status'];


							$body = $row_request['body'];


							$file_type = $row_request['file_type'];
							$attach = $row_request['file_name'];

							?>
							<tr>
								<td><?php echo $request_status ?></td>
								<td><?php echo $body ?></td>
								<td><?php echo $request_date ?></td>
								<td><?php echo $response_date ?></td>
								<td><?php echo $file_type ?>
								<td>
									<a href="uploads/<?= $row['case_id'] . '/' . 'requests/' . $row_request['file_name'] ?>"
									   download> <i class="fa fa-download" aria-hidden="true"></i>
									</a></td>
							</tr>

							<?php
						}
						}
						else {
							echo $msg;
						}
					?>
				</table>


			</div>
		</div>


		<div class="col-md-12">
			<div class="row">
				<h5 class="sub_title" style="margin-top: 5px;">?????? ?????????????????????? ?????????????????????? </h5>
				<?php
					$msg = '
      <div class="col-md-12" style="align-text: center;">
        <br>
        <h5> ?????? ???????????????????? ?????? ????????????????</h5>
      </div>
     ';
					if ($result_coi->num_rows > 0) {
				?>
				<table class="table table-stripped table-bordered">
					<tr>
						<th class="coi_table" width="15%">????????????????????</th>
						<th class="coi_table" width="15%">?????? ????????????????</th>
						<th class="coi_table" width="15%">?????????????? ??????????????</th>
						<th class="coi_table" width="15%">????????????????????????</th>
						<th class="coi_table" width="15%">?????????????????? ??????????????</th>
						<th class="coi_table">?????? ??????????</th>
					</tr>
					<?php

						while ($row_coi = mysqli_fetch_array($result_coi)) {
							$coi_request_date = date("d.m.Y", strtotime($row_coi['request_date']));
							$coi_deadlie_date = date("d.m.Y", strtotime($row_coi['request_deadline']));

							if (empty($row_coi['response_date'])) {
								$coi_response_date = "";
							} else {
								$coi_response_date = date("d.m.Y", strtotime($row_coi['response_date']));
							}

							$coi_status = '';
							if ($row_coi['coi_status'] == '0') {
								$coi_status = '??????????????';
							} else {
								$coi_status = '????????????????';
							}

							$attach_coi = $row_coi['file_name'];

							?>
							<tr>
								<td><?php echo $coi_status ?></td>
								<td><?= $row_coi['f_name'] . ' ' . $row_coi['l_name'] ?></td>
								<td><?php echo $coi_request_date ?></td>
								<td><?php echo $coi_deadlie_date ?></td>
								<td><?php echo $coi_response_date ?></td>
								<td>
									<a href="uploads/<?= $row_coi['case_id'] . '/' . 'coi' . '/' . $row_coi['file_name'] ?>"
									   download> <i class="fa fa-download"
									                aria-hidden="true"></i>
									</a>
								</td>
							</tr>

							<?php
						}
						}
						else {
							echo $msg;
						}
					?>
				</table>
			</div>
		</div>
	</div>


	<div id="functions" class="tabcontent">


		<div class="col-md-12">
			<div class="row">
				<div class="col-md-6">

					<?php
						if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'operator' || $_SESSION['role'] === 'officer' || $_SESSION['role'] === 'coispec' || $_SESSION['role'] === 'lawyer') {
							?>


							<h5 class="sub_title" style="margin-top: 5px;">???????? ?????? ???????????? </h5>
							<div class="row">
								<div class="col-md-5 mt-1">
									<form method="POST" action="config/config.php" enctype="multipart/form-data"
									      id="file_upload_form">
										<input type="hidden" name="case_num" value="<?php echo $case ?>">
										<select class="form-control" name="file_person_case" id="file_type_select">
											<option value="" selected disabled hidden>?????????? ????????</option>
											<option value="1">?????????? ????????????????????</option>
											<option value="2">???????? ????????????????????</option>
										</select>
										<div id="dropdown_container">

										</div>
								</div>
								<div class="form-group custom-file col-md-4 mt-2">
									<input type="file" name="file" class="custom-file-input" id="customFile"
									       required="required"/>
									<label class="custom-file-label" for="customFile">?????????????? ??????????</label>
								</div>
								<div class="col-md-3 mt-2">
									<button class="btn btn-success" type="submit" name="upload_file" id="upload_file">
										??????????????????
									</button>
								</div>
								</form>
							</div>
						<?php } ?>


					<?php

						if ($_SESSION['role'] === 'devhead' && $holder_id == $_SESSION['user_id'] && $cover_status_id == '2' && $cover_actual == '1') {
							$approveTranslateContent = '<h5 class="sub_title" style="margin-top: 5px;">?????????????????????????? ?????????????? </h5> ';
							$sql_translation_request = "SELECT * FROM tb_translate a INNER JOIN tb_cover_files b ON a.translate_id = b.translation_id WHERE b.cover_status = '2' AND a.case_id =  $case ORDER BY a.translate_id DESC LIMIT 1";
							$result_sql_translation_request = $conn->query($sql_translation_request);


							if ($result_sql_translation_request->num_rows > 0) {
								while ($cover_row = $result_sql_translation_request->fetch_assoc()) {
									$translation_id = $cover_row['translate_id'];
									$translation_type_id = $cover_row['translate_type'];
									$cover_file = $cover_row['file_name'];
									$file_ids = $cover_row['file_ids'];
									if ($translation_type_id == 1) {
										$translation_type = '????????????????????????????????';
									}
									if ($translation_type_id == 4) {
										$translation_type = '???????????????????????????? ??????????????????';
									}
									if ($translation_type_id == 3) {
										$translation_type = '??????????????????????';
									}


									if ($translation_type_id == 2) {

										$translation_type = '????????????';
										$sql_sending_files = "SELECT a.file_name,a.case_id,a.file_path,b.file_type FROM files a 
                                                        INNER JOIN tb_file_type b ON a.file_type = b.file_type_id WHERE a.id IN ($file_ids)";
										$result_sent_files = $conn->query($sql_sending_files);

										$table_rows = '';
										if ($result_sent_files->num_rows > 0) {
											while ($row_sent_files = $result_sent_files->fetch_assoc()) {
												$file_persId = '';
												$thisCase_id = $row_sent_files['case_id'];
												$file_type = $row_sent_files['file_type'];
												$file_name = $row_sent_files['file_name'];
												$file_path = $row_sent_files['file_path'];
												$table_rows .= '
                                                        <tr style="font-size: 0.8em; color:#324157;">
                                                            <td>' . $file_type . '</td>
                                                            <td><a href="' . $file_path . '" class="form-control form-control-sm" download>' . $file_name . '</a></td>                                                    
                                                        </tr>';
											}
										}
									}
									$approveTranslateContent .= '<div class="row">
                                <div class="col-md-6 mt-1">
                                  <label class="label_pers_page">??????????</label>              
                                  <input type="text" class="form-control form-control-sm" value=" ' . $translation_type . '" />
                                </div> 
                                <div class="col-md-6 mt-1">
                                  <label class="label_pers_page">???????????????? ??????????</label>
                                  <a href="uploads/' . $case . '/cover/' . $cover_file . '" class="form-control form-control-sm" download> <i class="fas fa-download"></i>?????????????????? ???????????????? ???????????? </a>          
                                </div>
                                <form method="POST"  action="config/config.php?cmd=send_mail" id="send_mail_form" enctype="multipart/form-data">          
                                  <input type="hidden" name="case_id_for_email" value="' . $case . '">   
                                  <input type="hidden" name="hidden_officer" value="' . $officer_id . '" />
                                  <input type="hidden" name="hidden_translate" value="' . $translation_id . '" />
                                  <div class="col-md-12">
                                    <label class="label_pers_page">?????????????????? ???????????????????? ??????????????????</label>
                                    <div class="row">
                                      <div class="form-group custom-file col-md-6">
                                        <input type="file" id="signed_cover" name="signed_cover" class="custom-file-input signed_cover" required="required" onchange="checkextension()"/>
                                        <label class="custom-file-label" for="customFile">?????????????? ??????????</label>
                                      </div>   
                                      <div class="form-group col-md-6">
                                        <button type="submit" name="send_email" id="send_email_button" class="btn btn-success" >????????????????</button>
                                      </div> 
                                    </div>    
                                  </div>              
                                </form>';
									if ($translation_type_id == 2) {
										$approveTranslateContent .= '<div class="col-md-12">
								                                  <label class="label_pers_page">?????????? ????????????????????</label>
								                                  <table class="table">
								                                    <tr style=" font-size: 0.8em; color: #324157; text-align: center; vertical-align: middle;">
								                                        <th>??????????</th>
								                                        <th>????????????????</th>
								                                    </tr>
								                                   ' . $table_rows . '
								                                  </table>
								                                </div>';
									}


									$approveTranslateContent .= '</div>';

								}

							}


							echo $approveTranslateContent;
						}
					?>

					<?php

						if ($_SESSION['role'] === 'devhead' && $inter_receiver_id == $u_id && $inter_status_id == 1) {

							$approve_inter_div = '';
							$approve_inter_div .= '
							<h5 class="sub_title" style="margin-top: 5px;">????????????????????</h5>
							<div class="row">

                                <div class="col-md-6 mt-1">
                                  <label class="label_pers_page">????????????</label>              
                                  <input type="text" class="form-control form-control-sm" value=" ' . $inter_type_text . '" readonly/>
                                </div> 
                                <div class="col-md-6 mt-1">
                                  <label class="label_pers_page">?????????? ????????</label>
                                  <a href="uploads/' . $case . '/inters/' . $filename . '" class="form-control form-control-sm" download> <i class="fas fa-download"></i>?????????????????? ???????????????????? </a>          
                                </div>

                                <div class="col-md-6 mt-1">
                                  <label class="label_pers_page">????????????</label>              
                                  <input type="text" class="form-control form-control-sm" value=" ' . $addresat . '" readonly/>	
                                </div>

                                <div class="col-md-6 mt-1">
                                  <label class="label_pers_page">?????????????? ??????????????</label>              
                                  <input type="text" class="form-control form-control-sm" value=" ' . $send_type_text . '" readonly/>	
                                </div>

                                <div class="col-md-12">
                                	 <label class="label_pers_page">?????????????? ????????????????????????????????</label>
                                	 <textarea class="form-control" rows="2" readonly> ' . $inter_msg . ' </textarea> 
                                </div>

                                </div>
                               

                                

                                <hr>

                                 <div class="row">
                                <form method="POST"  action="config/config.php" id="approve_inter" enctype="multipart/form-data">          
                                  <input type="hidden" name="case_id_inter" value="' . $case . '">   
                                  <input type="hidden" name="hidden_inter_id" value="' . $inter_id . '">   
                                  <input type="hidden" name="hidden_type_id" value="' . $inter_type_id . '">   


                                  <div class="col-md-12">
                                  <div class="row">
                                    	<div class="col-md-12"> 
	                                    	 <label class="label_pers_page">?????????????????? ???????????????????? ??????????????????</label>
	                                         <div class="form-group custom-file">
		                                        <input type="file" id="intern_approve_by_dev" name="file" class="custom-file-input signed_cover" required />
		                                        <label class="custom-file-label" for="customFile">?????????????? ??????????</label>
		                                      </div>   
		                                </div>

		                                <div class="col-md-12">
                                	 		<label class="label_pers_page">????????????????????????????????</label>
                                			 <textarea class="form-control" rows="2" name="inter_msg" > ' . $inter_msg_out . ' </textarea> 
                             		   </div>

		                                    <div class="col-md-12 mt-1">
		                                       <button type="submit" name="approve_send_inter"  class="btn btn-success btn-sm" >????????????????</button>
		                                       <input type="submit" name="cancel_inter" id="return_intern"  class="btn btn-warning btn-sm" value="??????????????????????" >
		                                    </div> 
                                    	</div>    
                                  </div>              
                                </form>
                                </div>
                                


                                ';

							echo $approve_inter_div;

						}


						if (($_SESSION['role'] === 'officer' || $_SESSION['role'] === 'coispec' || $_SESSION['role'] === 'lawyer') && $inter_receiver_id == $_SESSION['user_id']) {
							$resend_inter_div = '';
							$resend_inter_div .= '
							<hr>
							<h5 class="sub_title" style="margin-top: 5px;">????????????????????</h5>
							<div class="row">
                                
								<table class="table">
										<tr>
											<th class="table_a">????????????</th>
											<td style="height: 10px;">' . $inter_type_text . '</td>
										</tr>
										<tr>
											<th class="table_a">????????????</th>
											<td style="height: 10px;">' . $addresat . '</td>
										</tr>
										<tr>
											<th class="table_a">?????????????? ??????????????</th>
											<td style="height: 10px;">' . $send_type_text . '</td>
										</tr>
										
										<tr>
											<th class="table_a">?????????????????????? ??????????????????????</th>
											<td style="height: 10px;">' . $action_type_text . '</td>
										</tr>
										<tr>
											<th class="table_a">?????????? ????????</th>
											<td ><a href="uploads/' . $case . '/inters/' . $filename . '" download> <i class="fas fa-download"></i>?????????????????? ???????????????????? </a></td>
										</tr>						
								</table>  
	                                <div class="col-md-12">
	                                	 <label class="label_pers_page">????????????????????????????????</label>
	                                	 <textarea class="form-control" rows="2" readonly> ' . $inter_msg . ' </textarea> 
	                                </div>

                                	
                                	<div class="col-md-12 mt-1">
		                                       <a name="edit_inter" id="edit_inter" case_id="' . $case . '" inter_id="' . $inter_id . '" class="btn btn-success btn-sm" >????????????????</a>
		                                       <button type="submit" name="close_inter" id="close_inter" case_id="' . $case . '" inter_id="' . $inter_id . '" class="btn btn-warning btn-sm" >??????????????</button>
		                            </div> 
                                	
                                	

                                </div>';
							echo $resend_inter_div;
						}


					?>
				</div> <!--close 1st col-md-6-->
				<div class="col-md-6">
					<h5 class="sub_title" style="margin-top: 5px;">?????????? ??????????????????????</h5>
					<table class="table">
						<tr>
							<th class="table_a">?????????? #</th>
							<td style="height: 10px;"><?php echo $case ?></td>
						</tr>
						<tr>
							<th class="table_a">?????????? ????????????????????</th>
							<td><?php if (isset($case_status_text)) {
									echo $case_status_text;
								} ?></td>
						</tr>
						<tr>
							<th class="table_a">?????????????????????? ????????????????????</th>
							<td><?php if (isset($sign_status)) {
									echo $sign_status;
								} ?></td>
						</tr>
						<tr>
							<th class="table_a">????????????????????????</th>
							<td><?php if (isset($deadline_1)) {
									echo $deadline_1;
								} ?></td>
						</tr>

						<tr>
							<th class="table_a">???????? ??????????</th>
							<?php
								if (isset($case_status) && $case_status == 2) {
									?>
									<td><?php echo $lawyer ?></td>
								<?php } else {
									?>
									<td><?php if (isset($officer)) {
											echo $officer;
										} ?></td>
									<?php
								}
							?>
						</tr>
					</table>


					<?php

						if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'officer' || $_SESSION['role'] === 'devhead' || $_SESSION['role'] === 'head' || $_SESSION['role'] === 'operator' || $_SESSION['role'] === 'operator' || $_SESSION['role'] === 'lawyer' || $_SESSION['role'] === 'coispec') {
							?>

							<h5 class="sub_title" style="margin-top: 5px;">???????????????????? </h5>

							<table class="table">
								<tr>
									<th class="table_a1">??????????</th>
									<th class="table_a1">??????????????</th>
									<th class="table_a1">??????????????????</th>
									<th class="table_a1">???????? #</th>
									<th class="table_a1">????????????????????</th>

								</tr>

								<?php
									while ($row_files = mysqli_fetch_array($result_decision_file)) {
										$file_type = $row_files['decision_type'];
										$uploaded = $row_files['decison_date'];
										$uploaded = date("d.m.Y", strtotime($row_files['decison_date']));
										$file_name = $row_files['decision_file'];
										$case_id = $row_files['case_id'];
										$file_id = $row_files['decision_id'];
										$decision_status = $row_files['decision_status'];
										$out_num = $row_files['decision_out_num'];
										?>
										<tr>
											<td><?php echo $file_type ?></td>
											<td><?php echo $uploaded ?></td>
											<td>
												<a href="uploads/<?= $row_files['case_id'] . '/' . $file_name ?>"
												   download> <i class="fa fa-download"
												                aria-hidden="true"></i> <?php echo $file_name ?>
												</a>
											</td>
											<td><?php echo $out_num ?></td>
											<td> <?php echo $decision_status ?> </td>
										</tr>

									<?php } ?>
							</table>


						<?php } ?>

				</div> <!--close 2nd col-md-6-->
			</div> <!--close row-->
		</div> <!--close col-md-12-->

	</div> <!--close tab-->

	<?php
		require_once 'config/query_case.php';
	?>


	<div id="main_applicant" class="tabcontent">

		<div class="col-md-12">
			<div class="row">
				<div class="col-md-6">
					<h5 class="sub_title" style="margin-top: 5px;">???????????????? ????????????????</h5>
					<table class="table ">
						<tr>
							<th class="pers_table">?????????? (??????????????)</th>
							<th class="pers_table">???????????????? (??????????????)</th>
							<th class="pers_table">?????????????????? (??????????????)</th>
						</tr>
						<tr>
							<td><input type="text" class="form-control form-control-sm" name="f_name_arm"
							           value="<?php echo $f_name_arm ?>" readonly></td>
							<td><input type="text" class="form-control form-control-sm" name="l_name_arm"
							           value="<?php echo $l_name_arm ?>" readonly></td>
							<td><input type="text" class="form-control form-control-sm" name="m_name_arm"
							           value="<?php echo $m_name_arm ?>" readonly></td>
						</tr>
						<tr>
							<th class="pers_table">?????????? (??????????????????)</th>
							<th class="pers_table">???????????????? (??????????????????)</th>
							<th class="pers_table">?????????????????? (??????????????????)</th>
						</tr>
						<tr>
							<td><input type="text" class="form-control form-control-sm" name="f_name_eng"
							           value="<?php echo $f_name_eng ?>" readonly></td>
							<td><input type="text" class="form-control form-control-sm" name="l_name_eng"
							           value="<?php echo $l_name_eng ?>" readonly></td>
							<td><input type="text" class="form-control form-control-sm" name="m_name_eng"
							           value="<?php echo $m_name_eng ?>" readonly></td>
						</tr>

						<tr>
							<th class="pers_table">?????????????? ???? ???????? ????????</th>
							<th class="pers_table">??????</th>
							<th class="pers_table">????????????????</th>
						</tr>
						<tr>
							<td><input type="text" class="form-control form-control-sm" name="b_day"
							           value="<?php echo $b_day . '.' . $b_month . '.' . $b_year ?>" readonly></td>
							<td><input type="text" class="form-control form-control-sm" name="sex"
							           value="<?php echo $sex ?>" readonly></td>
							<td><input type="text" class="form-control form-control-sm" name="doc_num"
							           value="<?php echo $doc_num ?>" readonly></td>
						</tr>

						<tr>
							<th class="pers_table">????????????????????</th>
							<th class="pers_table">????????</th>
							<th class="pers_table">???? ?????????????????? ??????????????</th>
						</tr>
						<tr>
							<td><input type="text" class="form-control form-control-sm" name="etnic"
							           value="<?php echo $etnicity ?>" readonly></td>
							<td><input type="text" class="form-control form-control-sm" name="religion"
							           value="<?php echo $religion ?>" readonly></td>
							<td><input type="text" class="form-control form-control-sm" name="a_date"
							           value="<?php echo $arrival_date ?>" readonly></td>
						</tr>

					</table>


				</div> <!--close col-md-6-->
				<div class="col-md-6">
					<h5 class="sub_title" style="margin-top: 5px;">???????????????????????????? ?? ???????????????????? ??????????????</h5>
					<table class="table">
						<tr>
							<th class="table_a">???????????????????????????? ??????????</th>
							<th class="table_a">???????????? ???????????????????? ??????????</th>
						</tr>
						<tr>
							<td><input type="text" class="form-control form-control-sm" name="citizenship"
							           value="<?php echo $citizenship ?>" readonly></td>
							<td><input type="text" class="form-control form-control-sm" name="previous_residence"
							           value="<?php echo $previous_residence ?>" readonly></td>
						</tr>

						<tr>
							<th class="table_a">???????????? ???????????????????????????? ??????????????</th>
							<th class="table_a">???????????? ???????????? ???????????????????? ??????????????</th>
						</tr>
						<tr>
							<td><input type="text" class="form-control form-control-sm" name="adr_citizen"
							           value="<?php echo $citizen_adr ?>" readonly></td>
							<td><input type="text" class="form-control form-control-sm" name="residence_adr"
							           value="<?php echo $residence_adr ?>" readonly></td>
						</tr>

						<tr>
							<th class="table_a">???????????????????????????? ???????????? ???????????? ??????????????</th>
							<th class="table_a">???????????? ???????????????????? ???????????? ???????????? ??????????????</th>
						</tr>
						<tr>
							<td><input type="text" class="form-control form-control-sm" name="citizen_departure_date"
							           value="<?php echo $departure_from_citizen ?>" readonly></td>
							<td><input type="text" class="form-control form-control-sm" name="residence_departure"
							           value="<?php echo $departure_from_residence ?>" readonly></td>
						</tr>
					</table>

					<h5 class="sub_title">???????????? ????????????????</h5>
					<table class="table">
						<tr>
							<th class="special_needs_person">??????????????????</th>
							<th class="special_needs_person">?????? ??????</th>
							<th class="special_needs_person">???????? ????????????</th>
							<th class="special_needs_person">???????????????????? ??????</th>
							<th class="special_needs_person">?????????????????? ??????</th>
						</tr>
						<tr>
							<td><input type="checkbox" name="invalid" <?php echo $invalid ?> class="form-control"
							           onclick="return false;"></td>
							<td><input type="checkbox" name="invalid" <?php echo $pregnant ?> class="form-control"
							           onclick="return false;"></td>
							<td><input type="checkbox" name="invalid" <?php echo $seriously_ill ?> class="form-control"
							           onclick="return false;"></td>
							<td><input type="checkbox" name="invalid" <?php echo $trafficking_victim ?>
							           class="form-control" onclick="return false;"></td>
							<td><input type="checkbox" name="invalid" <?php echo $violence_victim ?>class="form-control"
							           onclick="return false;"></td>
						</tr>
					</table>
				</div>
			</div>
		</div> <!--close 12-->
	</div>

	<?php
		$sql_all_person = "SELECT a.personal_id, a.case_id, a.f_name_arm, a.f_name_eng, a.l_name_arm, a.l_name_eng, a.m_name_arm, a.m_name_eng, a.b_day, a.b_month, a.b_year, a.sex, a.citizenship, a.previous_residence, a.citizen_adr, a.residence_adr, a.departure_from_citizen, a.departure_from_residence, a.arrival_date, a.doc_num, a.etnicity, a.religion,  a.preferred_traslator_sex, a.preferred_interviewer_sex, a.invalid, a.pregnant, a.seriously_ill, a.trafficking_victim, a.violence_victim, a.comment, a.illegal_border, a.transfer_moj, a.deport_prescurator, a.role, b.der 
FROM tb_person a 
INNER JOIN tb_role b ON b.role_id = a.role

WHERE a.case_id = $case";

		$result_all_person = $conn->query($sql_all_person);


	?>

	<div id="family" class="tabcontent">
		<div class="col-md-12">
			<div class="row">
				<h5 class="sub_title" style="margin-top: 5px;">???????????????? ??????????</h5>
				<table class="table table-stripped table-bordered">
					<tr>

						<th class="role">????????</th>
						<th class="fam_members">???????????????</th>
						<th class="role">??????</th>
						<th class="role">??????????</th>
						<th class="role">??????????????????</th>
						<th class="role">????????????</th>
						<th class="role">??????</th>
						<th class="role">????????. ??????</th>
						<th class="role">??????. ??????</th>
						<th class="role"><i class="fas fa-ellipsis-h"></i></th>
					</tr>

					<?php
						while ($row_all = mysqli_fetch_array($result_all_person)) {
							$person_id = $row_all['personal_id'];
							$sex = '';
							if ($row_all['sex'] == '1') {
								$sex = '????????????';
							} else {
								$sex = '????????????';
							}

							$invalid = '';
							if ($row_all['invalid'] == '1') {
								$invalid = 'checked';
							}

							$pregnant = '';
							if ($row_all['pregnant'] == '1') {
								$pregnant = 'checked';
							}

							$ill = '';
							if ($row_all['seriously_ill'] == '1') {
								$ill = 'checked';
							}

							$trafficking_victim = '';
							if ($row_all['trafficking_victim'] == '1') {
								$trafficking_victim = 'checked';
							}

							$violence_victim = '';
							if ($row_all['violence_victim'] == '1') {
								$violence_victim = 'checked';
							}

							$curent_year = date("Y");
							$b_year = $row_all['b_year'];
							if ($b_year != "0000") {
								$age = $curent_year - $b_year;
							} else {
								$age = '????????????';
							}
							?>

							<tr>

								<td class="family_members_td"><?= $row_all['der'] ?></td>
								<td class="family_members_td"><?= $row_all['l_name_arm'] . ' ' . $row_all['f_name_arm'] ?></td>
								<td class="family_members_td"><?php echo $sex ?></td>
								<td class="family_members_td"><?php echo $age ?></td>
								<td class="family_members_td"><input type="checkbox" class="form-check-input"
								                                     name="invalid" <?php echo $invalid ?>
								                                     onclick="return false;"></td>
								<td class="family_members_td"><input type="checkbox" class="form-check-input"
								                                     name="pregnant" <?php echo $ill ?>
								                                     onclick="return false;"></td>
								<td class="family_members_td"><input type="checkbox" class="form-check-input"
								                                     name="ill" <?php echo $pregnant ?>
								                                     onclick="return false;"></td>
								<td class="family_members_td"><input type="checkbox" class="form-check-input"
								                                     name="trafiking" <?php echo $trafficking_victim ?>
								                                     onclick="return false;"></td>
								<td class="family_members_td"><input type="checkbox" class="form-check-input"
								                                     name="violence_victim" <?php echo $violence_victim ?>
								                                     onclick="return false;"></td>
								<td style="text-align: center;">

									<a href="#" class="pers_modal" modalid="<?php echo $row_all['personal_id'] ?>"> <i
												class="far fa-edit" style="color: green; font-size: 1.5em;"></i></a>

								</td>
							</tr>
						<?php } ?>
				</table>
			</div>
			<br/>
			<hr/>
			<br/>
			<div class="row">
				<h5 class="sub_title" style="margin-top: 5px;">???????????????? ???????????????? ?????????? ?????????????? ?????????????????? ?????? </h5>


				<table class="table table-stripped table-bordered">
					<tr>
						<th class="role">????????</th>
						<th class="role">??????????</th>
						<th class="role">????????????????</th>
						<th class="role">??????????????????</th>
						<th class="role">?????????????? ??????????????</th>
						<th class="role">????????????????????????????????</th>
						<th class="role">???????????????????? ??????????</th>
						<th class="role"><i class="fas fa-ellipsis-h"></i></th>
					</tr>
					<?php
						while ($row_out_member = mysqli_fetch_array($result_out_members)) {

							$omrole = $row_out_member['DER'];
							$omfname = $row_out_member['f_name_arm'];
							$omlname = $row_out_member['l_name_arm'];
							$ommname = $row_out_member['m_name_arm'];
							$ombday = $row_out_member['b_day'] . '.' . $row_out_member['b_month'] . '.' . $row_out_member['b_year'];
							$omcitizen = $row_out_member['CITIZEN'];
							$omres = $row_out_member['RES'];
							?>
							<tr>
								<td><?php echo $omrole ?></td>
								<td><?php echo $omfname ?></td>
								<td><?php echo $omlname ?></td>
								<td><?php echo $ommname ?></td>
								<td><?php echo $ombday ?></td>
								<td><?php echo $omcitizen ?></td>
								<td><?php echo $omres ?></td>
								<td style="text-align: center;">
									<?php
										if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'operator' || $_SESSION['role'] === 'officer' || $_SESSION['role'] === 'coispec' || $_SESSION['role'] === 'lawyer') {
											?>
											<a href="#" class="edit_mo" mo_id="<?= $row_out_member['member_id'] ?>"
											   mo_case="<?= $row_out_member['case_id'] ?>"><i class="far fa-edit mr-3"
											                                                  style="color: green; font-size: 1.5em;"></i></a>
											<a href="#" class="del_mo" mo_id="<?= $row_out_member['member_id'] ?>"
											   mo_case="<?= $row_out_member['case_id'] ?>"><i class="far fa-trash-alt "
											                                                  style="color: red; font-size: 1.5em;"></i></a>
											<?php
										}
									?>


									<a href="#" class="view_mo" mo_id="<?= $row_out_member['member_id'] ?>"
									   mo_case="<?= $row_out_member['case_id'] ?>"><i class="far fa-eye ml-3"
									                                                  style="color: blue; font-size: 1.5em;"></i></a>

								</td>
							</tr>
						<?php } ?>

				</table>

			</div>
		</div>
	</div>

	<div id="reason_sequence" class="tabcontent">
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-6">
					<h5 class="sub_title" style="margin-top: 5px;">?????????????? ?????????????????????? </h5>

					<table class="table">
						<tr>
							<th class="table_a">?????????????? ??????????????</th>
							<td><?php echo $application_date ?></td>
						</tr>
						<tr>
							<th class="table_a">?????????????????????? ??????????????</th>
							<td><?php echo $input ?></td>
						</tr>
						<tr>
							<th class="table_a">???????????????????? ??????????????????</th>
							<td><?php echo $reg_by ?></td>
						</tr>
						<tr>
							<th class="table_a">???????????? ???????? #</th>
							<td><?php echo $mul_num ?></td>
						</tr>
						<tr>
							<th class="table_a">???????????? ???????? ??????????????</th>
							<td><?php echo $mul_date ?></td>
						</tr>
						<tr>
							<th class="table_a">?????????? #</th>
							<td style="height: 10px;"><?php echo $case ?></td>
						</tr>
						<tr>
							<th class="table_a">?????????? ????????????????????</th>
							<td><?php echo $case_status_text ?></td>
						</tr>
						<tr>
							<th class="table_a">?????????????????????? ????????????????????</th>
							<td><?php echo $sign_status ?></td>
						</tr>
						<tr>
							<th class="table_a">????????????????????????</th>
							<td><?php echo $deadline_1 ?></td>
						</tr>
						<tr>
							<th class="table_a">???????? ??????????</th>
							<td><?php echo $officer ?></td>
						</tr>
						<tr>
							<th class="table_a">?????????? ????????????????</th>
							<td><?php echo $processor ?></td>
						</tr>

					</table>
				</div>

				<div class="col-md-6">

					<h5 class="sub_title" style="margin-top: 5px;">???????????????????? ???????????? ????-??????</h5>
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-4">
								<label class="label_pers_page">????????</label>
								<input type="text" name="l_f_name" class="form-control form-control-sm"
								       value="<?php echo $RA_marz ?>" readonly>
							</div>

							<div class="col-md-4">
								<label class="label_pers_page">??????????????</label>
								<input type="text" name="l_l_name" class="form-control form-control-sm"
								       value="<?php echo $RA_community ?>" readonly>
							</div>

							<div class="col-md-4">
								<label class="label_pers_page">??????????????????</label>
								<input type="text" name="l_organization" class="form-control form-control-sm"
								       value="<?php echo $RA_settlement ?>" readonly>
							</div>

							<div class="col-md-4">
								<label class="label_pers_page">??????????</label>
								<input type="text" name="l_tel" class="form-control form-control-sm"
								       value="<?php echo $RA_street ?>" readonly>
							</div>

							<div class="col-md-4">
								<label class="label_pers_page"> ????????</label>
								<input type="text" name="l_email" class="form-control form-control-sm"
								       value="<?php echo $RA_building ?>" readonly>
							</div>

							<div class="col-md-4">
								<label class="label_pers_page">????????????????</label>
								<input type="text" name="l_address" class="form-control form-control-sm"
								       value="<?php echo $RA_apartment ?>" readonly>
							</div>

							<div class="col-md-4">
								<label class="label_pers_page">??????????????????????????</label>
								<input type="text" name="l_email" class="form-control form-control-sm"
								       value="<?php echo $contact_tel ?>" readonly>
							</div>

							<div class="col-md-4">
								<label class="label_pers_page">????.????????</label>
								<input type="text" name="l_address" class="form-control form-control-sm"
								       value="<?php echo $contact_email ?>" readonly>
							</div>

						</div>
					</div>
					<h5 class="sub_title" style="margin-top: 5px;">?????????????????? ???????????????? </h5>
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-6">
								<label class="label_pers_page">??????????</label>
								<input type="text" name="l_f_name" class="form-control form-control-sm"
								       value="<?php echo $lawyer_name ?>" readonly>
							</div>

							<div class="col-md-6">
								<label class="label_pers_page">????????????????</label>
								<input type="text" name="l_l_name" class="form-control form-control-sm"
								       value="<?php echo $lawyer_surname ?>" readonly>
							</div>

							<div class="col-md-12">
								<label class="label_pers_page">????????????????????????????????</label>
								<input type="text" name="l_organization" class="form-control form-control-sm"
								       value="<?php echo $lawyer_organization ?>" readonly>
							</div>

							<div class="col-md-6">
								<label class="label_pers_page">??????????????????????????</label>
								<input type="text" name="l_tel" class="form-control form-control-sm"
								       value="<?php echo $lawyer_tel ?>" readonly>
							</div>

							<div class="col-md-6">
								<label class="label_pers_page">????. ????????</label>
								<input type="text" name="l_email" class="form-control form-control-sm"
								       value="<?php echo $lawyer_email ?>" readonly>
							</div>

							<div class="col-md-12">
								<label class="label_pers_page">??????????</label>
								<input type="text" name="l_address" class="form-control form-control-sm"
								       value="<?php echo $lawyer_address ?>" readonly>
							</div>


						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


	<div id="additional_data" class="tabcontent">
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-6">
					<h5 class="sub_title" style="margin-top: 5px;">???????????? ?????????????? ???????????????????? </h5>
					<table class="table">
						<thead>
						<tr>
							<th class="table_a2">??????????</th>
							<th class="table_a2">???????????????????? ??????????????</th>
							<th class="table_a2">??????????????????</th>
							<th class="table_a2">...</th>
						</tr>
						</thead>
						<?php
							while ($row_files = mysqli_fetch_array($result_file)) {
								$file_type = $row_files['file_type'];
								$uploaded = $row_files['uploaded_on'];
								$uploaded = date("d.m.Y", strtotime($row_files['uploaded_on']));
								$file_name = $row_files['file_name'];
								$case_id = $row_files['case_id'];
								$file_id = $row_files['id'];
								?>
								<tr>
									<td><?php echo $file_type ?></td>
									<td><?php echo $uploaded ?></td>
									<td><a href="uploads/<?= $row_files['case_id'] . '/' . $row_files['file_name'] ?>"
									       download> <i class="fa fa-download"
									                    aria-hidden="true"></i></a></td>
									<td><a href="#" class="delete_file" modal_id="<?php echo $case_id ?>"
									       delete_id="<?php echo $file_id ?>"> <i class="fas fa-trash-alt"></i></a></td>
								</tr>

							<?php } ?>
					</table>

				</div> <!--close col-md-6-->
				<div class="col-md-6">
					<h5 class="sub_title" style="margin-top: 5px;">???????????? ?????????????? ????????????????????</h5>
					<table class="table" id="person_file_table">
						<thead>
						<tr>
							<th class="table_a2">??.??.??.</th>
							<th class="table_a2">??????</th>
							<th class="table_a2">????????????????????</th>
							<th class="table_a2">...</th>
						</tr>
						</thead>

						<?php
							while ($row_person_file = $result_personal_files->fetch_assoc()) {
								$file_id = $row_person_file['id'];
								$full_name = $row_person_file['f_name_arm'] . ' ' . $row_person_file['l_name_arm'];
								$file_type = $row_person_file['FILE_TEXT'];
								$file_name = $row_person_file['file_name'];
								$case_id = $row_person_file['case_id'];
								$pers_id = $row_person_file['person_id'];
								?>
								<tr>
									<td><?php echo $full_name ?>  </td>
									<td><?php echo $file_type ?>  </td>
									<td><a href="uploads/<?= $case_id . '/' . $pers_id . '/' . $file_name ?>" download>
											<i class="fa fa-download" aria-hidden="true"></i>
										</a></td>
									<td><a href="#" class="delete_file" modal_id="<?php echo $case_id ?>"
									       delete_id="<?php echo $file_id ?>"> <i class="fas fa-trash-alt"></i></a></td>
								</tr>
							<?php } ?>
					</table>

				</div><!--close col-md-6-->
			</div> <!--close row-->
		</div> <!--close col-md-12-->

	</div>


	<div id="files" class="tabcontent">
		<h5 class="sub_title" style="margin-top: 5px;">?????????????? ???????????????????? ??????????????????????</h5>
		<div class="row">
			<div class="col-md-12">
				<table class="table">
					<tr>
						<th class="table_a1">???????????????????? ??????????????</th>
						<th class="table_a1">??????????????</th>
						<th class="table_a1">????????????</th>
						<th class="table_a1">??????????????</th>
					</tr>
					<?php
						while ($row_draft = mysqli_fetch_array($result_list)) {

							$uploaded = date("Y-m-d", strtotime($row_draft['uploaded']));
							$autor = $row_draft['AUTOR_NAME'];
							$reciver = $row_draft['RECEIVER_NAME'];
							$draft_file = $row_draft['draft_file'];

							?>
							<tr>
								<td><?php echo $uploaded ?></td>
								<td><?php echo $autor ?></td>
								<td><?php echo $reciver ?></td>
								<td>
									<a href="uploads/draft/<?= $row_draft['case_id'] . '/' . $row_draft['draft_file'] ?>"><?php echo $draft_file ?></a>
								</td>

							</tr>
						<?php } ?>
				</table>
			</div>
		</div>
	</div>


	<!-- Modal my_btn_3 ASIGN -->
	<div class="modal fade" id="modal_asign" role="dialog">

	</div>

	<!-- Modal my_btn_4_COI -->
	<div class="modal fade" id="modal_coi" role="dialog">

	</div>


	<!-- The Modal -->
	<div class="modal fade" id="myModal">


	</div>

	<!-- Modal edit case myBtn5 -->
	<div class="modal fade" id="edit_case">

	</div>


	<!-- Modal send to asign to_asign -->
	<div class="modal fade" id="send_a_sign">

	</div>

	<!-- Modal send to resign re_asign -->
	<div class="modal fade" id="to_re_sign">

	</div>


	<!-- Modal send to body -->
	<div class="modal fade" id="request_body">

	</div>

	<!-- Modal coi response -->
	<div class="modal fade" id="coi_answer">

	</div>

	<!-- Modal coi response -->
	<div class="modal fade" id="decision_final">

	</div>

	<!-- Modal delete -->
	<div class="modal fade" id="delete_files">

	</div>

	<!-- Modal delete -->
	<div class="modal fade" id="decision_to_head">

	</div>

	<!-- Call back  -->
	<div class="modal fade" id="call_back">

	</div>

	<!-- Draft cerculation  -->
	<div class="modal fade" id="draft_modal">

	</div>

	<!-- Decision approve by head  -->
	<div class="modal fade" id="head_aprove">

	</div>

	<div class="modal fade" id="prolongation_modal">

	</div>

	<div class="modal fade" id="lawyer_attach_modal">

	</div>

	<div class="modal fade" id="new_out_member">

	</div>

	<div class="modal fade" id="om_edit">

	</div>

	<div class="modal fade" id="om_view">

	</div>

	<div class="modal fade" id="om_delete">

	</div>

	<div class="modal fade" id="order_center">

	</div>

	<div class="modal fade" id="return_to_redev">

	</div>

	<div class="modal fade" id="change_deadline_special">

	</div>

	<div class="modal fade" id="change_special_type">

	</div>

	<div class="modal fade" id="approve_special_type">
	</div>


	<script>
		$(document).ready(function () {

			//Checking file extention for uploaded file to be pdf
			$("#file_upload_form").submit(function () {
				var file_type_select = $('#file_type_select').val();
				var case_file_types = $('#case_file_types').val();
				var verify_mp3 = ["mp3"].includes(/[^.]+$/.exec($("#customFile").val())[0]);
				var verify_pdf = ["pdf"].includes(/[^.]+$/.exec($("#customFile").val())[0]);
				if (file_type_select == 1) {
					if (case_file_types == 17) {
						if (!verify_mp3) {
							alert('?????????? ?????????????????????? ???????? ?? ????????  mp3 ????????????');
							return verify_mp3;
						}

					} else {
						if (!verify_pdf) {
							alert('?????????? ?????????????????????? ???????? ?? ???????? pdf ????????????');
							return verify_pdf;
						}
					}
				} else if (file_type_select == 2) {
					if (case_file_types == 16) {
						if (!verify_mp3) {
							alert('?????????? ?????????????????????? ???????? ?? ????????  mp3 ????????????');
							return verify_mp3;

						}
					} else {
						if (!verify_pdf) {
							alert('?????????? ?????????????????????? ???????? ?? ???????? pdf ????????????');
							return verify_pdf;
						}
					}
				}

			});


			$(document).on('click', '#return_intern', function () {
				var value = $('#intern_approve_by_dev')
				value.removeAttr("required")
			})


			function format(d) {
				let documents = d.documents.map(doc => `<a href= "${doc.doc_href}" download class="dt_download_a">${doc.doc_name}</a>`);

				// `d` is the original data object for the row
				return '<div class="dt_content">' +
						'<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;margin-right: 30px;">' +
						'<tr>' +
						'<th>???????????????? ??</th>' +
						'<th>?????????????????? ??</th>' +
						'</tr>' +
						'<tr>' +
						'<td>' + d.create_date + '</td>' +
						'<td>' + d.approve_date + '</td>' +
						'</tr>' +
						'<tr>' +
						'<td><a href="' + d.a_create_href + '" download class="dt_download_a">' + d.a_create_value + '</a></td>' +
						'<td><a href="' + d.a_approve_href + '" download class="dt_download_a">' + d.a_approve_value + '</a></td>' +
						'</tr>' +
						'</table>' +
						'<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">' +
						'<tr>' +
						'<th>?????????????????????????? ?????????????????? ????????????????????</th>' +
						'</tr>' +
						'<tr>' +
						'<td class="translate_documents_td">' + documents + '</td>' +
						'</tr>' +
						'</table>' +
						'</div>';
			}
			function formatInter(d) {
				let processTbody = d.inter_processes.map(process => `<tr><td>${process.action_type_text}</td><td>${process.process_actioned}</td><td class="text-center"><a download href="${process.inter_file}" ><i class="fa fa-download" aria-hidden="true"></i></a></td></tr>`);

				// `d` is the original data object for the row
				return '<div class="dt_content">' +
						'<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;margin-right: 30px;">' +
						'<tr>' +
						'<th>????????????????????????????????</th>' +
						'<th>??????????????</th>' +
						'<th>????????????????????</th>' +
						'</tr>' +
						processTbody +
						'</table>' +
						'</div>';
			}

			$(document).ready(function () {
				var table = $('#translations_table').DataTable({
					"searching": false,
					"lengthChange": false,
					"language": {
						"emptyTable": "?????????????????? ???????????????????? ????",
					},
					"ajax": "config/config.php?cmd=translation_info&case_id=<?php echo $case; ?>",
					"columns": [
						{
							"className": 'details-control',
							"orderable": false,
							"data": null,
							"defaultContent": ''
						},
						{"data": "type"},
						{"data": "company"},
						{"data": "date"},
						{"data": "time_from"},
						{"data": "time_to"},
						{"data": "sign_status"}
					]
				});


				var inter_msg_table = $('#inter_msg_table').DataTable({
					"searching": false,
					"lengthChange": false,
					"language": {
						"emptyTable": "?????????????????? ???????????????????? ????",
					},
					"ajax": "config/config.php?cmd=get_hystory_table&case_id=<?php echo $case; ?>",
					"columns": [
						{
							"className": 'details-control',
							"orderable": false,
							"data": null,
							"defaultContent": ''
						},
						{"data": "inter_id"},
						{"data": "type"},
						{"data": "receiver"},
						{"data": "send_type"},
						{"data": "status"},
					]
				})


				// Add event listener for opening and closing details
				$('#translations_table tbody').on('click', 'td.details-control', function () {
					var tr = $(this).closest('tr');
					var row = table.row(tr);

					if (row.child.isShown()) {
						// This row is already open - close it
						row.child.hide();
						tr.removeClass('shown');
					} else {
						// Open this row
						row.child(format(row.data())).show();
						tr.addClass('shown');
					}
				});

				// Add event listener for opening and closing details
				$('#inter_msg_table tbody').on('click', 'td.details-control', function () {
					var tr = $(this).closest('tr');
					var row = inter_msg_table.row(tr);

					if (row.child.isShown()) {
						row.child.hide();
						tr.removeClass('shown');
					} else {
						row.child(formatInter(row.data())).show();
						tr.addClass('shown');
					}
				});


			});


			//Send translate to approve button on click
			$(document).on('submit', '#translation_modal', function () {
				$('#send_approve_translate').attr("disabled", true);
				$('#send_approve_translate').html('<i class="fa fa-spinner fa-spin"></i>Loading');
			})

			//Approving translate by devhead button on click
			$(document).on('submit', '#send_mail_form', function () {
				$('#send_email_button').attr("disabled", true);
				$('#send_email_button').html('<i class="fa fa-spinner fa-spin"></i>Loading');
			})


			$('#mail_translate').on('click', function (e) {
				e.preventDefault();
				var translate_caseId = $(this).attr('case');
				var language = $(this).attr('language');
				$.ajax(
						{
							url: "config/config.php",
							method: "POST",
							data: {translate_case: translate_caseId, language: language},
							success: function (data) {
								$('#approve_special_type').html(data);
								$("#approve_special_type").modal('show');
							}
						});
			})


			$("#approve_special_change").click(function () {
				var case_id = $(this).attr('modal_id');
				$.ajax(
						{
							url: "config/config.php",
							method: "POST",
							data: {special_change_devhead: case_id},
							success: function (data) {
								//console.log(appeal_id);
								$('#approve_special_type').html(data);
								$("#approve_special_type").modal({backdrop: "static"});
							}
						});
			});

			$("#edit_inter").click(function () {
				var case_id = $(this).attr('case_id');
				var inter_id = $(this).attr('inter_id');

				$.ajax(
						{
							url: "config/config.php",
							method: "POST",
							data: {edit_inter: case_id, inter_id: inter_id},
							success: function (data) {

								$('#approve_special_type').html(data);
								$("#approve_special_type").modal({backdrop: "static"});
							}
						});
			});

			$("#close_inter").click(function () {
				var case_id = $(this).attr('case_id');
				var inter_id = $(this).attr('inter_id');

				$.ajax(
						{
							url: "config/config.php",
							method: "POST",
							data: {close_inter: case_id, inter_id: inter_id},
							success: function (data) {

								$('#approve_special_type').html(data);
								$("#approve_special_type").modal({backdrop: "static"});
							}
						});
			});


			$("#court_decission").click(function () {
				var case_id = $(this).attr('modal_id');
				var claim_id = $(this).attr('claim');
				var appeal_id = $(this).attr('appeal');
				$.ajax(
						{
							url: "config/config.php",
							method: "POST",
							data: {decision_3: case_id, claim_id: claim_id, appeal_id: appeal_id},
							success: function (data) {
								//console.log(appeal_id);
								$('#approve_special_type').html(data);
								$("#approve_special_type").modal({backdrop: "static"});
							}
						});
			});

			$("#intermediate").click(function () {
				var case_id = $(this).attr('modal_id');

				$.ajax(
						{
							url: "config/config.php",
							method: "POST",
							data: {inter_note: case_id},
							success: function (data) {
								console.log(case_id);
								$('#approve_special_type').html(data);
								$("#approve_special_type").modal({backdrop: "static"});
							}
						});
			});


			$("#asign_lawer").click(function () {
				var case_id = $(this).attr('modal_id');
				$.ajax(
						{
							url: "config/config.php",
							method: "POST",
							data: {ms_lawyer: case_id},
							success: function (data) {
								$('#approve_special_type').html(data);
								$("#approve_special_type").modal({backdrop: "static"});
							}
						});
			});

			$("#court_accept").click(function () {
				var case_id = $(this).attr('modal_id');
				$.ajax(
						{
							url: "config/config.php",
							method: "POST",
							data: {accept_claim: case_id},
							success: function (data) {
								$('#approve_special_type').html(data);
								$("#approve_special_type").modal({backdrop: "static"});
							}
						});
			});

			$("#court_claim").click(function () {
				var case_id = $(this).attr('modal_id');
				$.ajax(
						{
							url: "config/config.php",
							method: "POST",
							data: {claim_court: case_id},
							success: function (data) {
								$('#approve_special_type').html(data);
								$("#approve_special_type").modal({backdrop: "static"});
							}
						});
			});


			$("#change_case_type").click(function () {
				var case_id = $(this).attr('modal_id');
				$.ajax(
						{
							url: "config/config.php",
							method: "POST",
							data: {request_change_special: case_id},
							success: function (data) {
								$('#change_special_type').html(data);
								$("#change_special_type").modal({backdrop: "static"});
							}
						});
			});


			$("#change_deadline").click(function () {
				var case_id = $(this).attr('modal_id');
				$.ajax(
						{
							url: "config/config.php",
							method: "POST",
							data: {change_deadline: case_id},
							success: function (data) {
								$('#change_deadline_special').html(data);
								$("#change_deadline_special").modal({backdrop: "static"});
							}
						});
			});


			$("#return_redev").click(function () {
				var case_id = $(this).attr('re_case');
				var srole = $(this).attr('sender_role');
				var proc_status = $(this).attr('proc_status');
				$.ajax(
						{
							url: "config/config.php",
							method: "POST",
							data: {re_case: case_id, role: srole, proc_status:proc_status},
							success: function (data) {
								console.log(srole);
								$('#return_to_redev').html(data);
								$("#return_to_redev").modal({backdrop: "static"});
							}
						});
			});

			$("#upload_file").on("click", function (event) {
				var file_type = $("#file_type_select").val();
				if (!file_type) {
					alert("\"?????????? ????????\" ???????????????? ???????? ??");
					event.preventDefault()
					return;
				} else if (file_type == 1) {
					var case_file_types = $("#case_file_types").val();
					if (!case_file_types) {
						alert("\"?????????? ????????????\" ???????????????? ???????? ??");
						event.preventDefault()
						return;
					}
				} else {
					var case_file_types = $("#case_file_types").val();
					var select_member = $("#select_member").val();
					if (!case_file_types || !select_member) {
						alert("<?????????? ????????????> ?? <???????????????? ????????????> ???????????????? ?????????????? ???????? ????");
						event.preventDefault()
						return;
					}
				}


			})

			$("#file_type_select").on("change", function () {
				var file_type_select = $("#file_type_select").val();
				var my_case = $("#case").val();
				$.ajax(
						{
							url: "config/config.php",
							method: "POST",
							data: {file_type_select, my_case},
							success: function (data) {
								$('#dropdown_container').html(data);

							}
						});
			})


			var url_string = window.location.href;
			var url = new URL(url_string);
			var active_tab = url.searchParams.get("active_tab");
			if (active_tab) {

				var i, tabcontent, tablinks;
				tabcontent = document.getElementsByClassName("tabcontent");
				for (i = 0; i < tabcontent.length; i++) {
					tabcontent[i].style.display = "none";
				}
				tablinks = document.getElementsByClassName("tablinks");
				for (i = 0; i < tablinks.length; i++) {
					tablinks[i].className = tablinks[i].className.replace(" active", "");
				}
				document.getElementById("family").style.display = "block";

				$(`#${active_tab}`).addClass("active");


			}

			$("#ref_to_center").click(function () {
				var case_id = $(this).attr('ref_case');
				$.ajax(
						{
							url: "config/config.php",
							method: "POST",
							data: {order: case_id},
							success: function (data) {
								console.log(case_id);
								$('#order_center').html(data);
								$("#order_center").modal({backdrop: "static"});

							}
						});
			});

			$(".del_mo").click(function () {
				var pers_id = $(this).attr('mo_id');
				var case_id = $(this).attr('mo_case');
				$.ajax({
					url: "config/config.php",
					method: "POST",
					data: {mo_del_info: pers_id, case_mo: case_id},
					success: function (data) {
						//console.log(pers_id);
						$('#om_edit').html(data);
						$("#om_edit").modal({backdrop: "static"});

					}
				});
			});

			$(".view_mo").click(function () {
				var pers_id = $(this).attr('mo_id');
				var case_id = $(this).attr('mo_case');
				$.ajax({
					url: "config/config.php",
					method: "POST",
					data: {mo_view_info: pers_id, case_mo: case_id},
					success: function (data) {
						//console.log(pers_id);
						$('#om_edit').html(data);
						$("#om_edit").modal({backdrop: "static"});

					}
				});
			});

			$(".edit_mo").click(function () {
				var pers_id = $(this).attr('mo_id');
				var case_id = $(this).attr('mo_case');
				$.ajax({
					url: "config/config.php",
					method: "POST",
					data: {mo_edit_info: pers_id, case_mo: case_id},
					success: function (data) {
						//console.log(pers_id);
						$('#om_edit').html(data);
						$("#om_edit").modal({backdrop: "static"});

					}
				});
			});


			$("#member_out").click(function () {

				var case_id = $(this).attr('modal_id');


				$.ajax({
					url: "config/config.php",
					method: "POST",
					data: {out_member: case_id},
					success: function (data) {
						$('#new_out_member').html(data);
						$("#new_out_member").modal({backdrop: "static"});

					}
				});
			});

			$(document).on("keyup", ".validation", function () {
				var this_id = $(this).attr("id");
				if ($(this).attr("type") === "text") {
					$(`#${this_id}`).css("border", "1px solid #ced4da");
					$(`#error-message${this_id}`).css("visibility", "hidden");
					return;
				}
				if ($(this).attr("type") === "number") {
					$(`#${this_id}`).css("border", "1px solid #ced4da");
					$(`#error-messagebdate`).css("visibility", "hidden");
				}

			})
			$(document).on("change", ".validation", function () {
				var this_id = $(this).attr("id");
				if ($(this).attr("type") === "number") {
					$(`#${this_id}`).css("border", "1px solid #ced4da");
					$(`#error-messagebdate`).css("visibility", "hidden");
					return;
				}
				$(`#${this_id}`).css("border", "1px solid #ced4da");
				$(`#error-message${this_id}`).css("visibility", "hidden");

			})
			$(document).on("click", ".save_open_close", function () {
				var command = $(this).attr("id");
				var modal_case_id = $("#modal_case_id").val();
				var select_out_role = $("#select_out_role").val();
				var bday = $("#bday").val().trim();
				var bmonth = $("#bmonth").val().trim();
				var byear = $("#byear").val().trim();
				var o_fname_arm = $("#o_fname_arm").val().trim();
				var o_lname_arm = $("#o_lname_arm").val().trim();
				var o_mname_arm = $("#o_mname_arm").val().trim();
				var o_fname_eng = $("#o_fname_eng").val().trim();
				var o_lname_eng = $("#o_lname_eng").val().trim();
				var o_mname_eng = $("#o_mname_eng").val().trim();
				var select_out_citizenship = $("#select_out_citizenship").val();
				var select_out_res = $("#select_out_res").val();
				var out_gender = $("#out_gender").val();
				if (o_fname_eng == '' || o_lname_eng == '' || o_fname_arm == '' || o_lname_arm == '' || bday == '' || bmonth == '' || byear == '' || !select_out_role || !select_out_citizenship || !out_gender) {
					if (!out_gender) {
						$("#out_gender").css("border", "1px solid red");
						$(`#error-messageout_gender`).css("visibility", "visible");
					}
					if (!select_out_citizenship) {
						$("#select_out_citizenship").css("border", "1px solid red");
						$(`#error-messageselect_out_citizenship`).css("visibility", "visible");
					}
					if (!select_out_role) {
						$("#select_out_role").css("border", "1px solid red");
						$(`#error-messageselect_out_role`).css("visibility", "visible");
					}
					if (o_fname_eng == '') {
						$("#o_fname_eng").css("border", "1px solid red");
						$(`#error-messageo_fname_eng`).css("visibility", "visible");
					}
					if (o_lname_eng == '') {
						$("#o_lname_eng").css("border", "1px solid red");
						$(`#error-messageo_lname_eng`).css("visibility", "visible");
					}
					if (o_fname_arm == '') {
						$("#o_fname_arm").css("border", "1px solid red");
						$(`#error-messageo_fname_arm`).css("visibility", "visible");
					}
					if (o_lname_arm == '') {
						$("#o_lname_arm").css("border", "1px solid red");
						$(`#error-messageo_lname_arm`).css("visibility", "visible");
					}
					if (bday == '') {
						$("#bday").css("border", "1px solid red");
						$(`#error-messagebdate`).css("visibility", "visible");
					}
					if (bmonth == '') {
						$("#bmonth").css("border", "1px solid red");
						$(`#error-messagebdate`).css("visibility", "visible");
					}
					if (byear == '') {
						$("#byear").css("border", "1px solid red");
						$(`#error-messagebdate`).css("visibility", "visible");
					}

					return;
				}
				$.ajax(
						{
							url: "config/config.php",
							method: "POST",
							data: {
								save_open_close: command,
								modal_case_id,
								select_out_role,
								bday,
								bmonth,
								byear,
								o_fname_arm,
								o_lname_arm,
								o_mname_arm,
								o_fname_eng,
								o_lname_eng,
								o_mname_eng,
								select_out_citizenship,
								select_out_res,
								out_gender
							},
							success: function (data) {
								var modal_case_id = $("#modal_case_id").val('');
								var select_out_role = $("#select_out_role").val('');
								var bday = $("#bday").val('');
								var bmonth = $("#bmonth").val('');
								var byear = $("#byear").val('');
								var o_fname_arm = $("#o_fname_arm").val('');
								var o_lname_arm = $("#o_lname_arm").val('');
								var o_mname_arm = $("#o_mname_arm").val('');
								var o_fname_eng = $("#o_fname_eng").val('');
								var o_lname_eng = $("#o_lname_eng").val('');
								var o_mname_eng = $("#o_mname_eng").val('');
								var select_out_citizenship = $("#select_out_citizenship").val('');
								var select_out_res = $("#select_out_res").val('');
								var out_gender = $("#out_gender").val('');

								if (data === 'saved_close') {
									$("#new_out_member").modal("hide");
								} else {
									var resp = JSON.parse(data);
									var new_case_id = resp.out_member_save_and_open;
									$.ajax(
											{
												url: "config/config.php",
												method: "POST",
												data: {out_member: new_case_id},
												success: function (data) {
													$('#new_out_member').html(data);
													$("#new_out_member").modal({backdrop: "static"});

												}
											});
								}

							}
						});
			})


			$("#attach_lawyer").click(function () {

				var case_id = $(this).attr('casenum1');


				$.ajax({
					url: "config/config.php",
					method: "POST",
					data: {lawyer: case_id},
					success: function (data) {

						$('#lawyer_attach_modal').html(data);
						$("#lawyer_attach_modal").modal({backdrop: "static"});

					}
				});
			});


			$("#approve_head").click(function () {

				var case_id = $(this).attr('casenum');


				$.ajax({
					url: "config/config.php",
					method: "POST",
					data: {aprove_case: case_id},
					success: function (data) {
						// console.log(case_id);
						$('#head_aprove').html(data);
						$("#head_aprove").modal({backdrop: "static"});

					}
				});
			});


			$("#draft").click(function () {

				var case_id = $(this).attr('modal_id');
				var sender = $(this).attr('modal_case');

				$.ajax({
					url: "config/config.php",
					method: "POST",
					data: {case_draft: case_id, sender: sender},
					success: function (data) {
						$('#draft_modal').html(data);
						$("#draft_modal").modal({backdrop: "static"});

					}
				});
			});


			$("#call_modal").click(function () {

				var case_id = $(this).attr('modal_id');
				var caller = $(this).attr('modal_case');
				var role = $(this).attr('modal_role');
				$.ajax({
					url: "config/config.php",
					method: "POST",
					data: {case_call: case_id, caller: caller, role: role},
					success: function (data) {
						console.log(role);
						$('#call_back').html(data);
						$("#call_back").modal({backdrop: "static"});

					}
				});
			});


			$(".delete_file").click(function () {

				var case_id = $(this).attr('modal_id');
				var file_id = $(this).attr('delete_id');

				$.ajax({
					url: "config/config.php",
					method: "POST",
					data: {file_id: file_id, case_id: case_id},
					success: function (data) {
						$('#delete_files').html(data);
						$("#delete_files").modal({backdrop: "static"});

					}
				});
			});


			$("#decision_to_head_1").click(function () {
				var decision_2 = $(this).attr('modal_id');
				var user_devhead = $(this).attr('modal_user');

				$.ajax({
					url: "config/config.php",
					method: "POST",
					data: {decision_2: decision_2, user: user_devhead},
					success: function (data) {
						$('#decision_to_head').html(data);
						$("#decision_to_head").modal({backdrop: "static"});

					}
				});
			});


			$("#decision").click(function () {
				//$("#coi_answer").modal({backdrop: "static"});
				var decision_1 = $(this).attr('modal_id');
				var user_officer = $(this).attr('modal_user');
				var proc_status = $(this).attr('proc_status');

				$.ajax({
					url: "config/config.php",
					method: "POST",
					data: {decision_1: decision_1, user: user_officer, proc_status:proc_status},
					success: function (data) {
						$('#decision_final').html(data);
						$("#decision_final").modal({backdrop: "static"});

					}
				});
			});


			$("#answer_coi").click(function () {
				//$("#coi_answer").modal({backdrop: "static"});
				var coi_num = $(this).attr('coi_id');


				$.ajax({
					url: "config/config.php",
					method: "POST",
					data: {coi_answer: coi_num},
					success: function (data) {
						$('#coi_answer').html(data);
						$("#coi_answer").modal({backdrop: "static"});

					}
				});
			});

			$("#re_sign").click(function () {
				// $("#myModal3").modal({backdrop: "static"});
				var case_id = $(this).attr('modal_id');
				var user = $(this).attr('modal_case');
				$.ajax({
					url: "config/config.php",
					method: "POST",
					data: {resign_case: case_id, resender: user},
					success: function (data) {
						$('#to_re_sign').html(data);
						$("#to_re_sign").modal({backdrop: "static"});

					}
				});
			});


			$("#request").click(function () {
				// $("#myModal3").modal({backdrop: "static"});
				var case_id = $(this).attr('modal_id');
				var user = $(this).attr('modal_case');
				$.ajax({
					url: "config/config.php",
					method: "POST",
					data: {request_case: case_id, officer: user},
					success: function (data) {

						$('#request_body').html(data);
						$("#request_body").modal({backdrop: "static"});

					}
				});
			});

			$("#to_asign").click(function () {
				// $("#myModal3").modal({backdrop: "static"});
				var case_id = $(this).attr('modal_id');
				var user = $(this).attr('modal_case');
				$.ajax({
					url: "config/config.php",
					method: "POST",
					data: {asign_case: case_id, sender: user},
					success: function (data) {

						$('#send_a_sign').html(data);
						$("#send_a_sign").modal({backdrop: "static"});

					}
				});
			});


			$("#myBtn5").click(function () {
				// $("#myModal3").modal({backdrop: "static"});
				var case_id = $(this).attr('modal_id');

				$.ajax({
					url: "config/config.php",
					method: "POST",
					data: {edit_case: case_id},
					success: function (data) {

						$('#edit_case').html(data);
						$("#edit_case").modal({backdrop: "static"});

					}
				});
			});


			$("#myBtn3").click(function () {
				// $("#myModal3").modal({backdrop: "static"});
				var case_id = $(this).attr('modal_id');
				var user = $(this).attr('modal_case');

				$.ajax({
					url: "config/config.php",
					method: "POST",
					data: {case: case_id, user: user},
					success: function (data) {

						$('#modal_asign').html(data);
						$("#modal_asign").modal({backdrop: "static"});

					}
				});
			});

			$("#myBtn4").click(function () {
				// $("#myModal3").modal({backdrop: "static"});
				var case_id = $(this).attr('modal_id');
				var user = $(this).attr('modal_case');

				$.ajax({
					url: "config/config.php",
					method: "POST",
					data: {coi_case: case_id, user: user},
					success: function (data) {

						$('#modal_coi').html(data);
						$("#modal_coi").modal({backdrop: "static"});

					}
				});
			});


			$("#myBtn3").click(function () {
				// $("#myModal3").modal({backdrop: "static"});
				var case_id = $(this).attr('modal_id');
				var user = $(this).attr('modal_case');

				$.ajax({
					url: "config/config.php",
					method: "POST",
					data: {case: case_id, user: user},
					success: function (data) {

						$('#myModal2').html(data);
						$("#myModal2").modal({backdrop: "static"});

					}
				});
			});


			$(".pers_modal").click(function () {
				// $("#myModal").modal({backdrop: "static"});
				var pers_id = $(this).attr('modalid');

				console.log(pers_id);
				$.ajax({
					url: "config/config.php",
					method: "POST",
					data: {person_modal: pers_id, pers_id: pers_id},
					success: function (data) {

						$('#myModal').html(data);
						$("#myModal").modal({backdrop: "static"});

					}
				});
			});

		});

		$(document).on("change", ".custom-file-input", function () {
			var fileName = $(this).val().split("\\").pop();
			$(this).siblings(".custom-file-label").addClass("selected").html(fileName);
		});

		function openCity(evt, cityName) {
			var i, tabcontent, tablinks;
			tabcontent = document.getElementsByClassName("tabcontent");
			for (i = 0; i < tabcontent.length; i++) {
				tabcontent[i].style.display = "none";
			}
			tablinks = document.getElementsByClassName("tablinks");
			for (i = 0; i < tablinks.length; i++) {
				tablinks[i].className = tablinks[i].className.replace(" active", "");
			}
			document.getElementById(cityName).style.display = "block";
			evt.currentTarget.className += " active";
		}

		// Get the element with id="defaultOpen" and click on it
		document.getElementById("defaultOpen").click();

		$(document).ready(function () {
			// Country dependent ajax
			$(document).on("change", "#select_marz", function () {
				var marzId = $(this).val();

				if (marzId) {
					$.ajax({
						url: "pages/subpages/action.php",
						type: "POST",
						cache: false,
						data: {marzId: marzId},
						success: function (data) {
							$("#select_community").html(data);
							$('#select_setl').html('<option value="">?????????? ????????????????</option>');
						}
					});
				} else {
					$('#select_community').html('<option value="">?????????? ??????????</option>');
					$('#select_setl').html('<option value="">?????????????? ????????????????????</option>');
				}
			});

// state dependent ajax
			$(document).on("change", "#select_community", function () {
				var bnakId = $(this).val();

				if (bnakId) {
					$.ajax({
						url: "pages/subpages/action.php",
						type: "POST",
						cache: false,
						data: {bnakId: bnakId},
						success: function (data) {
							$("#select_setl").html(data);
						}
					});
				} else {
					$('#select_setl').html('<option value="">?????????? ????????????????/option>');
				}
			});
		});

		function checkextension(e) {
			var file = document.querySelector("#signed_cover");
			if (/\.(pdf)$/i.test(file.files[0].name) === false) {
				alert("???????????????? ???????? ?? ???????? ??pdf?? ??????????????");
				file.value = '';
			}
		}

		var loadFile = function (event) {
			var image = document.getElementById('output');
			image.src = URL.createObjectURL(event.target.files[0]);
		};
	</script>