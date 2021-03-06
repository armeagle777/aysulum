<?php
	session_start();
	if (!isset($_SESSION['username']) || ($_SESSION['role'] !== "admin" && $_SESSION['role'] !== "operator" && $_SESSION['role'] !== "statist" && $_SESSION['role'] !== "viewer" && $_SESSION['role'] !== "lawyer" && $_SESSION['role'] !== "officer" && $_SESSION['role'] !== "devhead" && $_SESSION['role'] !== "coispec" && $_SESSION['role'] !== "head" && $_SESSION['role'] !== "police" && $_SESSION['role'] !== "un" && $_SESSION['role'] !== "nss" && $_SESSION['role'] !== "dorm" && $_SESSION['role'] !== "general")) {
		exit;
	}
	require('connect.php');
	include('functions.php');
	date_default_timezone_set('Asia/Yerevan');


	//*! modify translations(delete, save, approve, pay)
	if (isset($_GET['cmd']) && ($_GET['cmd'] === 'save_translation' || $_GET['cmd'] === 'delete_translation' || $_GET['cmd'] === 'pay_translation' || $_GET['cmd'] === 'approve_translation')) {
		$translate_id = $_POST['translate_id'];
		if ($_GET['cmd'] === 'save_translation'):
			$translation_query = "UPDATE `tb_translate` SET  `sign_status`='4' WHERE translate_id = $translate_id";
		elseif ($_GET['cmd'] === 'delete_translation'):
			$translation_query = "UPDATE `tb_translate` SET  `sign_status`='0' WHERE translate_id = $translate_id";
		elseif ($_GET['cmd'] === 'pay_translation'):
			$translation_query = "";
		elseif ($_GET['cmd'] === 'approve_translation'):
			$translation_query = "UPDATE `tb_translate` SET  `sign_status`='5' WHERE translate_id = $translate_id";
		endif;

		if ($conn->query($translation_query) === TRUE):
			echo 'Translation modified';
		else:
			echo "Error: " . $translation_query . "<br>" . $conn->error;
		endif;

	}

	//Add new calendar event
	if (isset($_POST["addNewEvent"])) {
		$event_user_id = $_SESSION["user_id"];
		$event_case_id = $_POST["caseId"];
		$date_from = $_POST["eventFrom"];
		$date_to = $_POST["eventTo"];
		$description = $_POST["description"];
		$text_color = '#FFFF00';
		$border_color = '#FF0000';
		$signer = $_SESSION['user_id'];
		$sql_addEVent = "INSERT INTO `tb_calendar`(`case_id`, `user_id`,  `inter_comment`, `inter_date_from`, `inter_date_to`, `text_color`, `border_color`) VALUES ($event_case_id,$event_user_id,'$description',  '$date_from', '$date_to', '$text_color', '$border_color')";

		if ($conn->query($sql_addEVent) === TRUE) {
			if ($_SESSION['role'] !== 'operator') {
				$operatorId_array = [];
				$sql_getOperators = "SELECT id FROM users WHERE user_type='operator' AND user_status = 1";
				$result_getOperators = $conn->query($sql_getOperators);
				if ($result_getOperators->num_rows > 0) {
					while ($row_events = $result_getOperators->fetch_assoc()) {
						$operatorId_array[] = $row_events['id'];
					}
				}
				if (count($operatorId_array) > 0) {
					foreach ($operatorId_array as $value) {
						$sql_notify = "INSERT INTO `tb_notifications`(`comment_subject`, `comment_text`, `comment_status`, `comment_from`, `comment_to`, `case_id`, `note_type`) 
          VALUES ('???????????????????????? ??????????????????????????', NULLIF('$description', ''), '0', '$signer', '$value', '$event_case_id', '1')";
						if ($conn->query($sql_notify) === TRUE) {
							continue;
						}
					}
				}

			}
			echo "success adding new event";
		} else {
			echo "Error: " . $sql_addEVent . "<br>" . $conn->error;
		}
		$conn->close();
		exit;
	}

	//Get calendar events
	if (isset($_POST["get_calendar_events"])) {
		$event_sql = "SELECT * FROM tb_calendar 
  LEFT JOIN users ON users.id=tb_calendar.user_id";
		$result_events = $conn->query($event_sql);
		if ($result_events->num_rows > 0) {
			$emparray = array();
			while ($row_events = $result_events->fetch_assoc()) {
				$newArray = array();
				$fromDate = $row_events["inter_date_from"];
				$toDate = $row_events["inter_date_to"];
				$newArray["from"] = $fromDate;
				$newArray["to"] = $toDate;
				$newArray["title"] = $row_events["f_name"] . ' ' . $row_events["l_name"];
				$newArray["description"] = $row_events["inter_comment"];
				$newArray["location"] = "???????????? N1";
				$newArray["isAllDay"] = false;
				$newArray["color"] = "#111222";
				$newArray["colorText"] = $row_events["text_color"];
				$newArray["colorBorder"] = $row_events["border_color"];
				$newArray["repeatEvery"] = 5;
				$newArray["id"] = $row_events["interview_id"];
				$newArray["group"] = "Group 1";

				$emparray[] = $newArray;
			}
		}
		echo json_encode($emparray);
	}

	//Uploading file type select on change
	if (isset($_POST["file_type_select"])) {
		$file_type_select = $_POST["file_type_select"];
		$case = $_POST["my_case"];
		$file_type_select_response = "";
		if ($file_type_select == 2) {
			$sql_case_members = "SELECT * FROM tb_person WHERE case_id = $case";
			$result_sql_members = $conn->query($sql_case_members);
			$file_type_select_response .= '<select name="select_member" id="select_member" class="form-control">
                                    <option selected disabled hidden>?????????????? ???????????????? ?????????????? </option>';
			while ($row5 = $result_sql_members->fetch_assoc()) {
				$file_type_select_response .= "<option value=" . $row5['personal_id'] . ">" . $row5['f_name_arm'] . ' ' . $row5['l_name_arm'] . "</option>";
			}
			$file_type_select_response .= '</select>';
		}
		$file_type_query = "SELECT * FROM tb_file_type WHERE file_filter=$file_type_select";
		$result_file_type_select = $conn->query($file_type_query);
		if ($result_file_type_select->num_rows > 0) {
			$file_type_select_response .= '<select class="form-control" name="file_type" id="case_file_types">
                                  <option value="" selected disabled hidden>?????????? ????????????</option>';
			while ($row_file_type_select = $result_file_type_select->fetch_assoc()) {
				$file_type_select_response .= '<option value="' . $row_file_type_select["file_type_id"] . '">' . $row_file_type_select["file_type"] . '</option>';

			}
			$file_type_select_response .= '</select>';
		}
		echo $file_type_select_response;
	}

	//Person Modal Echo
	if (isset($_POST['person_modal']) || isset($_POST['person_modal_1'])) {

		$personal_id = $_POST['pers_id'];

		$go_to_identificator = '';
		if (isset($_POST['person_modal'])) {
			$go_to_identificator = '1';
		}
		if (isset($_POST['person_modal_1'])) {
			$go_to_identificator = '2';
		}

		$query_person_file = "SELECT a.id, a.file_name, a.uploaded_on, a.file_type, a.uploader, a.case_id, a.person_id, b.file_type_id, b.file_type AS FILE_TYPE_TEXT, b.file_filter, c.f_name, c.l_name 
    FROM files a 
    INNER JOIN tb_file_type b ON a.file_type = b.file_type_id
    INNER JOIN users c ON a.uploader = c.id 
    WHERE a.person_id = $personal_id";

		$result_query_person_file = $conn->query($query_person_file);

		$files_tab = '';
		if ($result_query_person_file->num_rows > 0) {
			$files_tab .= '<table class="table table-bordered">
                <tr>
                  <th class="label_pers_page">??????</th>
                  <th class="label_pers_page">????????????????????</th>
                  <th class="label_pers_page">?????????? ??????????????</th>
                  <th class="label_pers_page">???????? ??????????????????</th>
                </tr>';

			while ($row_person_file = $result_query_person_file->fetch_assoc()) {
				$file_type = $row_person_file['FILE_TYPE_TEXT'];
				$filename = $row_person_file['file_name'];
				$uploaded_date = '';
				$attach_date = date('d.m.Y', strtotime($row_person_file['uploaded_on']));
				$attached_by = $row_person_file['f_name'] . ' ' . $row_person_file['l_name'];

				$files_tab .= '
                <tr>
                  <td>' . $file_type . '</td>
                  <td>' . $filename . '</td>
                  <td>' . $attach_date . '</td>
                  <td>' . $attached_by . '</td>
                <tr>';
			}
			$files_tab .= '</table>';
		} else {
			$files_tab = '<span class="label_pers_page">?????????? ???????????????????? ?????????? </span>';
		}

		$sql_main_person = "SELECT a.personal_id, a.case_id, a.f_name_arm AS anun, a.f_name_eng, a.l_name_arm, a.l_name_eng, a.m_name_arm, a.m_name_eng, a.image,
a.b_day, a.b_month, a.b_year, a.sex, a.citizenship, a.previous_residence,  
a.citizen_adr, a.residence_adr, a.departure_from_citizen, a.departure_from_residence, a.arrival_date, a.doc_num, a.etnicity, a.religion, a.preferred_traslator_sex, a.preferred_interviewer_sex, a.invalid, a.pregnant, 
a.seriously_ill, a.trafficking_victim, a.violence_victim, a.comment, a.illegal_border, a.transfer_moj, a.deport_prescurator, a.prison, a.role
FROM tb_person a 
WHERE a.personal_id = $personal_id";
		$modal_response = '';
		$result_main_person = $conn->query($sql_main_person);

		if ($result_main_person->num_rows > 0) {
			$row_p = $result_main_person->fetch_assoc();
			$case_id = $row_p['case_id'];
			$image = $row_p['image'];
			$show_image = "uploads/" . $row_p['case_id'] . "/" . $personal_id . "/" . $row_p['image'];
			$f_name_arm = $row_p['anun'];
			$f_name_eng = $row_p['f_name_eng'];
			$l_name_arm = $row_p['l_name_arm'];
			$l_name_eng = $row_p['l_name_eng'];
			$m_name_arm = $row_p['m_name_arm'];
			$m_name_eng = $row_p['m_name_eng'];
			$b_day = $row_p['b_day'];
			$b_month = $row_p['b_month'];
			$b_year = $row_p['b_year'];
			$role = $row_p['role'];


			$doc_num = $row_p['doc_num'];
			$citizenship = $row_p['citizenship'];
			$citizen_adr = $row_p['citizen_adr'];
			$departure_from_citizen = $row_p['departure_from_citizen'];
			$previous_residence = $row_p['previous_residence'];
			$residence_adr = $row_p['residence_adr'];
			$departure_from_residence = $row_p['departure_from_residence'];
			$arrival_date = $row_p['arrival_date'];
			$etnicity = $row_p['etnicity'];
			$religion = $row_p['religion'];

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

			$transfer_moj = '';
			if ($row_p['transfer_moj'] == '1') {
				$transfer_moj = 'checked';
			}

			$illegal_border = '';
			if ($row_p['illegal_border'] == '1') {
				$illegal_border = 'checked';
			}

			$deport_prescurator = '';
			if ($row_p['deport_prescurator'] == '1') {
				$deport_prescurator = 'checked';
			}

			$prison = '';
			if ($row_p['prison'] == '1') {
				$prison = 'checked';
			}

			$query_citizenship = "SELECT * FROM tb_country";
			$state = mysqli_query($conn, $query_citizenship);
			$optcitizen = '<select name="select_state" id="select_state" class="form-control form-control-sm">';
			$optcitizen .= '<option selected disabled hidden>?????????? ????????????</option>';
			while ($row1 = mysqli_fetch_array($state)) {
				if ($row1['country_id'] == $citizenship) {
					$optcitizen .= "<option selected=\"selected\" value=" . $row1['country_id'] . ">" . $row1['country_arm'] . "</option>";
				} else {
					$optcitizen .= "<option value=" . $row1['country_id'] . ">" . $row1['country_arm'] . "</option>";
				}
			}
			$optcitizen .= "</select>";

			$query_prev_res = "SELECT * FROM tb_country";
			$state2 = mysqli_query($conn, $query_prev_res);
			$optprev = '<select name="select_prev" id="select_prev" class="form-control form-control-sm">';
			$optprev .= '<option selected disabled hidden>?????????? ????????????</option>';
			while ($row2 = mysqli_fetch_array($state2)) {

				if ($row2['country_id'] == $previous_residence) {
					$optprev .= "<option selected=\"selected\" value=" . $row2['country_id'] . ">" . $row2['country_arm'] . "</option>";
				} else {
					$optprev .= "<option value=" . $row2['country_id'] . ">" . $row2['country_arm'] . "</option>";
				}

			}
			$optprev .= "</select>";

			$query_religion = "SELECT * FROM tb_religions";
			$religion2 = mysqli_query($conn, $query_religion);
			$optpreligion = '<select name="select_religion" id="select_religion" class="form-control form-control-sm">';
			$optpreligion .= "<option> -- ?????????? ???? -- </option>";
			while ($row3 = mysqli_fetch_array($religion2)) {

				if ($row3['religion_id'] == $religion) {
					$optpreligion .= "<option selected=\"selected\" value=" . $row3['religion_id'] . ">" . $row3['religion_arm'] . "</option>";
				} else {
					$optpreligion .= "<option value=" . $row3['religion_id'] . ">" . $row3['religion_arm'] . "</option>";
				}
			}
			$optpreligion .= "</select>";

			$query_etnic = "SELECT * FROM tb_etnics";
			$etnicity2 = mysqli_query($conn, $query_etnic);
			$opt_etnic = '<select name="select_etnic" id="select_etnic" class="form-control form-control-sm">';
			while ($row4 = mysqli_fetch_array($etnicity2)) {

				if ($row4['etnic_id'] == $etnicity) {
					$opt_etnic .= "<option selected=\"selected\" value=" . $row4['etnic_id'] . ">" . $row4['etnic_eng'] . "</option>";
				} elseif (empty($row_p['etnicity'])) {
					$opt_etnic .= "<option> -- ?????????? ???? -- </option>";
				} else {
					$opt_etnic .= "<option value=" . $row4['etnic_id'] . ">" . $row4['etnic_eng'] . "</option>";
				}
			}
			$opt_etnic .= "</select>";

			$query_role = "SELECT * FROM tb_role";
			$role2 = mysqli_query($conn, $query_role);
			$opt_role = '<select name="select_role" id="select_role" class="form-control form-control-sm">';
			$opt_role .= "<option> -- ?????????? ???? -- </option>";
			while ($row5 = mysqli_fetch_array($role2)) {

				if ($row5['role_id'] == $role) {
					$opt_role .= "<option selected=\"selected\" value=" . $row5['role_id'] . ">" . $row5['der'] . "</option>";
				} else {
					$opt_role .= "<option value=" . $row5['role_id'] . ">" . $row5['der'] . "</option>";
				}
			}
			$opt_role .= "</select>";

			$optsex = '<select name="select_sex" id="select_sex" class="form-control form-control-sm">';
			if ($row_p['sex'] == '1') {
				$optsex .= '<option selected=\"selected\" value="1">????????????</option> <option value="2">????????????</option>';
			} else {
				$optsex .= '<option selected=\"selected\" value="2">????????????</option> <option value="1">????????????</option>';
			}
			$optsex .= "</select>";

			$opt_inter_sex = '<select name="select_inter_sex" id="select_inter_sex" class="form-control form-control-sm">';
			if ($row_p['preferred_interviewer_sex'] == '1') {
				$opt_inter_sex .= '<option selected=\"selected\" value="1">????????????</option> <option value="2">????????????</option> <option value="3">????????????????</option>';
			} elseif ($row_p['preferred_interviewer_sex'] == '2') {
				$opt_inter_sex .= '<option selected=\"selected\" value="2">????????????</option> <option value="1">????????????</option> <option value="3">????????????????</option>';
			} else {
				$opt_inter_sex .= '<option selected=\"selected\" value="3">????????????????</option> <option value="1">????????????</option>  <option value="2">????????????</option>';
			}

			$opt_inter_sex .= "</select>";

			$opt_translator_sex = '<select name="select_translator_sex" id="select_translator_sex" class="form-control form-control-sm">';
			if ($row_p['preferred_traslator_sex'] == '1') {
				$opt_translator_sex .= '<option selected=\"selected\" value="1">????????????</option> <option value="2">????????????</option> <option value="3">????????????????</option>';
			} elseif ($row_p['preferred_traslator_sex'] == '2') {
				$opt_translator_sex .= '<option selected=\"selected\" value="2">????????????</option> <option value="1">????????????</option> <option value="3">????????????????</option>';
			} else {
				$opt_translator_sex .= '<option selected=\"selected\" value="3">????????????????</option> <option value="1">????????????</option>  <option value="2">????????????</option>';
			}

			$opt_translator_sex .= "</select>";

			$modal_response .= '
        <div class="modal-dialog modal-xl">
          <div class="modal-content">      
            <!-- Modal Header -->
            <div class="modal-header">
              <h4 class="modal-title">???????????????? ?????????????????? ??????????????????</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>        
            <!-- Modal body -->
            <div class="modal-body">        
              <ul class="nav nav-tabs">
                <li class="nav-item">
                  <a class="nav-link active" data-toggle="tab" href="#person_info">???????????????? ????????????????</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" data-toggle="tab" href="#menu1">?????????? ????????????</a>
                </li>
              </ul>
              <div class="tab-content">      
                <div id="person_info" class="tab-pane active"><br>
                  <div class="container" >
                <form method="POST" action="config/config.php" id="person" enctype="multipart/form-data"> 
                <div class="col-md-12" style="margin-top: 10px; margin-bottom: 10px; padding-bottom: 5px; padding-top: 5px;">
                  <div class="row">
                    <div class="col-md-3" >
                      <div class="photo">		
                        <p><img id="output" width="180px" height="218px" src="' . $show_image . '"/></p>
                        <label for="photo_upload" style="cursor: pointer;" id="pPhoto_label"><i class="fas fa-edit"></i></label>				
                        <p><input type="file" name="file" id="photo_upload" accept="image/*" id="file"  onchange="loadFile(event)" style="display: none;"></p>
                      </div> 
                       <input type="hidden" class="form-control form-control-sm" name="go_to_identificator" value="' . $go_to_identificator . '" >

                    </div> 
                    <div class="col-md-9" >
                      <h5 class="sub_title">?????????????????? ??????????????????????????????</h5>  
                      <div class="row">
                        <div class="col-md-4">
                            <label class="label_pers_page">???????? #</label>
                            <input type="text" class="form-control form-control-sm" name="case_id" value="' . $case_id . '" >
                        </div> 
                        <div class="col-md-4">
                          <label class="label_pers_page">???????????????? #</label>
                          <input type="text" class="form-control form-control-sm" name="personal_id" value="' . $personal_id . '">
                        </div> 
                        <div class="col-md-4">
                          <label class="label_pers_page">???? ???????????????? ??????????????</label>
                          <input type="text" class="form-control form-control-sm" name="arrival_date" value="' . $arrival_date . '">
                        </div> 
                      </div>
                      <h5 class="sub_title">???????????????? ????????????????</h5>
                      <div class="row">
                        <div class="col-md-4">
                          <label class="label_pers_page">????????</label>
                          ' . $opt_role . '
                        </div>
                        <div class="col-md-4">
                          <label class="label_pers_page">?????????????? ??????????????</label>                
                          <div class="form-inline">
                          <input type="number" class="form-control form-control-sm col-md-3 mr-2" min="00" minlength="2" max="31" placeholder="????" name="bday" onchange="if(parseInt(this.value,10)<10>1)this.value="0"+this.value;" value="' . $b_day . '">
                          <input type="number" class="form-control form-control-sm col-md-4 mr-2" min="00" minlength="2" max="12" placeholder="????????" name="bmonth" onchange="if(parseInt(this.value,10)<10>1)this.value="0"+this.value;" value="' . $b_month . '">
                          <input type="number" class="form-control form-control-sm col-md-4" min="0000" max="2100" placeholder="????????" name="byear" required="required" value="' . $b_year . '">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <label class="label_pers_page">??????</label>
                        ' . $optsex . '
                      </div>
                    </div>
            <div class="row">
              <div class="col-md-4">
                <label class="label_pers_page">?????????? (??????????????)</label>
                <input type="text" class="form-control form-control-sm" name="f_name_arm1" value="' . $f_name_arm . '">
              </div>
              <div class="col-md-4">
                <label class="label_pers_page">???????????????? (??????????????)</label>
                <input type="text" class="form-control form-control-sm" name="l_name_arm" value="' . $l_name_arm . '">
              </div>
              <div class="col-md-4">
                <label class="label_pers_page">?????????????????? (??????????????)</label>
                <input type="text" class="form-control form-control-sm" name="m_name_arm" value="' . $m_name_arm . '">
              </div>
            </div> <!--close arm row-->
            <div class="row">
              <div class="col-md-4">
                <label class="label_pers_page">?????????? (??????????????????)</label>
                <input type="text" class="form-control form-control-sm" name="f_name_eng" value="' . $f_name_eng . '">
              </div>
              <div class="col-md-4">
                <label class="label_pers_page">???????????????? (??????????????????)</label>
                <input type="text" class="form-control form-control-sm" name="l_name_eng" value="' . $l_name_eng . '">
              </div>
              <div class="col-md-4">
                <label class="label_pers_page">?????????????????? (??????????????????)</label>
                <input type="text" class="form-control form-control-sm" name="m_name_eng" value="' . $m_name_eng . '">
              </div>
            </div> <!--close eng row-->    
            <div class="row">
              <div class="col-md-4">
                <label class="label_pers_page">????????</label>
                ' . $optpreligion . '
              </div>
              <div class="col-md-4">
                <label class="label_pers_page">????????????????????</label>
                ' . $opt_etnic . '
              </div>
              <div class="col-md-4">
                <label class="label_pers_page">????????????????</label>
                <input type="text" class="form-control form-control-sm" name="doc_num" value="' . $doc_num . '">
              </div>
            </div> <!--close personal row-->
          </div> <!--close general row-->          
            </div> <!--close col-md-9-->
            </div> <!--close col-md-12-->
            <div class="col-md-12" style="margin-top: 5px;">
          <div class="row">
              <div class="col-md-6">
              <h5 class="sub_title">?????????????????????????????? || ?????????????? ???????????????????? ??????????</h5>
                <div class="row">
                <div class="col-md-6">
                  <label class="label_pers_page">??????????????????????????????</label>
                  ' . $optcitizen . '
                </div>
                <div class="col-md-6">
                  <label class="label_pers_page">???????????? ????????? ??????????? ??????????</label>
                  ' . $optprev . '
                </div>
                <div class="col-md-6">
                  <label class="label_pers_page">???????????? ????????? ??????????????</label>
                  <input type="text" class="form-control form-control-sm" name="adr_citizen" value="' . $citizen_adr . '">
                </div>
                <div class="col-md-6">
                  <label class="label_pers_page">???????????? ????????? ??????????? ??????????????</label>
                  <input type="text" class="form-control form-control-sm" name="adr_res" value="' . $residence_adr . '">
                </div>
                <div class="col-md-6">
                  <label class="label_pers_page">????????? ???????????? ???????????? ??????????????</label>
                  <input type="text" class="form-control form-control-sm" name="citizen_departure_date" value="' . $departure_from_citizen . '">
                </div>
                <div class="col-md-6">
                  <label class="label_pers_page">??????????? ???????????? ???????????? ??????????????</label>
                  <input type="text" class="form-control form-control-sm" name="prev_departure_date" value="' . $departure_from_residence . '">
                </div>
                </div>
              <h5 class="sub_title" style="margin-top: 10px;">???????????? ????????????????</h5> 
                <table class="table">
                  <tr>
                    <th class="table_a">?????????????? ??????????????????????????</th>
                    <td><input type="checkbox" class="form-control form-control-sm" name="illegal" ' . $illegal_border . '></td>
                  </tr>                   
                  <tr>
                    <th class="table_a">???????????????????????? (????????????????????????????)</th>
                    <td><input type="checkbox" class="form-control form-control-sm" name="deport_prescurator" ' . $deport_prescurator . '></td>
                  </tr>                  
                  <tr>
                    <th class="table_a">?????????????????? (??????????????????)</th>
                    <td><input type="checkbox" class="form-control form-control-sm" name="transfer_moj" ' . $transfer_moj . '></td>
                  </tr>                 
                  <tr>
                    <th class="table_a">??????</th>
                    <td> <input type="checkbox" class="form-control form-control-sm" name="prison" ' . $prison . ' ></td>
                  </tr> 
                </table>      
              </div>
              <div class="col-md-6">
                <h5 class="sub_title">???????????? ????????????????</h5>
                <table class="table">
                  <tr>
                    <th class="table_a">??????????????????</th>
                    <td><input type="checkbox" class="form-control form-control-sm" name="invalid" ' . $invalid . '></td>
                  </tr>  
                  <tr>
                    <th class="table_a">?????? ??????</th>
                    <td><input type="checkbox" class="form-control form-control-sm" name="pregnant" ' . $pregnant . '></td>
                  </tr>   
                  <tr>
                    <th class="table_a">???????? ????????????</th>
                    <td><input type="checkbox" class="form-control form-control-sm" name="ill" ' . $seriously_ill . '></td>
                  </tr>
                  <tr>
                    <th class="table_a">???????????????????? ??????</th>
                    <td><input type="checkbox" class="form-control form-control-sm" name="trafficking_victim" ' . $trafficking_victim . '></td>
                  </tr> 
                  <tr>
                    <th class="table_a">?????????????????? ??????</th>
                    <td><input type="checkbox" class="form-control form-control-sm" name="violence_victim" ' . $violence_victim . '></td>
                  </tr>
                  <tr>
                    <th class="table_a">???????????????????? ?????????????????????????????? ????????</th>
                    <td>' . $opt_inter_sex . '</td>
                  </tr> 
                  <tr>
                    <th class="table_a">???????????????????? ?????????????????? ????????</th>
                    <td> ' . $opt_translator_sex . '</td>
                  </tr>                          
                </table>
              </div> 
              </div>
            </div><!--close col-md-12-->
          </div> <!--close container-->
          </div> <!-- close tab menu home -->         
          <div id="menu1" class="tab-pane fade"><br>
            <div class="container">
            <h5 class="sub_title">???????? ????????????????????</h5>
              ' . $files_tab . '
            </div>
          </div>
          </div> <!-- close all tabs -->
          </div>         
            <!-- Modal footer -->
            <div class="modal-footer">';

			if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'lawyer' || $_SESSION['role'] === 'officer' || $_SESSION['role'] === 'operator' || $_SESSION['role'] === 'un') {

				$modal_response .= '<input type="submit" name="update_person" class="btn btn-primary" form="person" value="????????????????">';
			}

			$modal_response .= '  <button type="button" class="btn btn-secondary" data-dismiss="modal">??????????</button>
            </div>
            </form>
          </div>
        </div>';


		}


		echo $modal_response;
	}


	//update person via modal

	if (isset($_POST['update_person'])) {
		$go_to_identificator = $_POST['go_to_identificator'];
		$personal_id = $_POST['personal_id'];
		$arrival_date = $_POST['arrival_date'];
		$select_role = $_POST['select_role'];
		$bday = $_POST['bday'];
		$bmonth = $_POST['bmonth'];
		$byear = $_POST['byear'];
		$select_sex = $_POST['select_sex'];
		$f_name_arm = $_POST['f_name_arm1'];
		$l_name_arm = $_POST['l_name_arm'];
		$m_name_arm = $_POST['m_name_arm'];
		$f_name_eng = $_POST['f_name_eng'];
		$l_name_eng = $_POST['l_name_eng'];
		$m_name_eng = $_POST['m_name_eng'];
		$select_religion = $_POST['select_religion'];
		$select_etnic = 480;
		if (is_numeric($_POST['select_etnic'])) {
			$select_etnic = $_POST['select_etnic'];
		}
		$doc_num = $_POST['doc_num'];
		$select_state = $_POST['select_state'];
		$select_prev = $_POST['select_prev'];
		$adr_citizen = $_POST['adr_citizen'];
		$adr_res = $_POST['adr_res'];
		$citizen_departure_date = $_POST['citizen_departure_date'];
		$prev_departure_date = $_POST['prev_departure_date'];
		$case_id = $_POST['case_id'];

		$illegal = '0';
		if (isset($_POST['illegal'])) {
			$illegal = '1';
			if ($select_role == 1) {
				$update_case_special = "UPDATE tb_case SET special = 1 WHERE case_id = $case_id";
				$result_update_special = $conn->query($update_case_special);
			}
		}

		$transfer_moj = '0';
		if (isset($_POST['transfer_moj'])) {
			$transfer_moj = '1';
			if ($select_role == 1) {
				$update_case_special = "UPDATE tb_case SET special = 1 WHERE case_id = $case_id";
				$result_update_special = $conn->query($update_case_special);
			}
		}

		$deport_prescurator = '0';
		if (isset($_POST['deport_prescurator'])) {
			$deport_prescurator = '1';
			if ($select_role == 1) {
				$update_case_special = "UPDATE tb_case SET special = 1 WHERE case_id = $case_id";
				$result_update_special = $conn->query($update_case_special);
			}
		}

		$prison = '0';
		if (isset($_POST['prison'])) {
			$prison = '1';
			if ($select_role == 1) {
				$update_case_special = "UPDATE tb_case SET special = 1 WHERE case_id = $case_id";
				$result_update_special = $conn->query($update_case_special);
			}
		}

		$invalid = '0';
		if (isset($_POST['invalid'])) {
			$invalid = '1';
		}

		$pregnant = '0';
		if (isset($_POST['pregnant'])) {
			$pregnant = '1';
		}

		$ill = '0';
		if (isset($_POST['ill'])) {
			$ill = '1';
		}

		$trafficking_victim = '0';
		if (isset($_POST['trafficking_victim'])) {
			$trafficking_victim = '1';
		}

		$violence_victim = '0';
		if (isset($_POST['violence_victim'])) {
			$violence_victim = '1';
		}
		$unique_number = uniqid();
		$select_translator_sex = $_POST['select_translator_sex'];
		$select_inter_sex = $_POST['select_inter_sex'];
		if (isset($_FILES['file']['name'])) {
			# Get file name
			$filename = $_FILES['file']['name'];
			$fileNameArray = explode(".", $filename);
			$filename = "profile_" . $unique_number . "." . end($fileNameArray);
		}


		$sql_update_person = "UPDATE `tb_person` SET 
    `f_name_arm`='$f_name_arm',
    `f_name_eng`='$f_name_eng',
    `l_name_arm`='$l_name_arm',
    `l_name_eng`='$l_name_eng',
    `m_name_arm`='$m_name_arm',
    `m_name_eng`='$m_name_eng',
    `b_day`='$bday',
    `b_month`='$bmonth',
    `b_year`='$byear',
    `sex`='$select_sex',
    `citizenship`='$select_state',
    `previous_residence`= NULLIF('$select_prev', ''),
    `citizen_adr`= NULLIF('$adr_citizen', ''),
    `residence_adr`= NULLIF('$adr_res', ''),
    `departure_from_citizen`= '$citizen_departure_date',
    `departure_from_residence`= '$prev_departure_date',
    `arrival_date`= '$arrival_date',
    `doc_num`='$doc_num',
	  `etnicity`= NULLIF('$select_etnic', ''),
    `religion`= NULLIF('$select_religion', ''),
    `preferred_traslator_sex`='$select_translator_sex',
    `preferred_interviewer_sex`='$select_inter_sex',
    `invalid`='$invalid',
    `pregnant`='$pregnant',
    `seriously_ill`='$ill',
    `trafficking_victim`='$trafficking_victim',
    `violence_victim`='$violence_victim',
    `illegal_border`='$illegal',
    `transfer_moj`='$transfer_moj',
    `deport_prescurator`= '$deport_prescurator',
    `prison` = '$prison',
    `role`= '$select_role',
    `image`='$filename'
    WHERE personal_id = $personal_id";

		if ($conn->query($sql_update_person) === TRUE) {
			# Location
			$location = "../uploads/" . $case_id . "/" . $personal_id;
			$location .= "/" . $filename;
			# Upload file
			move_uploaded_file($_FILES['file']['tmp_name'], $location);

			header('location: ../user.php?page=cases&homepage=case_page&case=' . $case_id);
		} else {
			echo "Error: " . $sql_update_person . "<br>" . $conn->error;
		}
	}

	//sign_case to action modal

	if (isset($_POST['case'])) {

		$modal_sign = "";
		$case = $_POST['case'];
		$user = $_POST['user'];

		$sql = "SELECT * FROM tb_case WHERE case_id = $case";
		$result = $conn->query($sql);


		$msg_asign = '?? ????????????????';

		if ($result->num_rows > 0) {
			$row_s = $result->fetch_assoc();
			$officer = $row_s['officer'];

		}


		$query_user_to_sign = "SELECT * from users WHERE user_type = 'officer' OR user_type = 'coispec'";
		$officer1 = $conn->query($query_user_to_sign);
		$opt_officer = '<select name="select_officer" id="select_officer" class="form-control form-control-sm">';
		$opt_officer .= "<option> -- ?????????? ???? -- </option>";
		while ($row5 = mysqli_fetch_array($officer1)) {

			if ($row5['id'] == $officer) {
				$opt_officer .= "<option selected=\"selected\" value=" . $row5['id'] . ">" . $row5['f_name'] . "</option>";
			} else {
				$opt_officer .= "<option value=" . $row5['id'] . ">" . $row5['f_name'] . ' ' . $row5['l_name'] . "</option>";
			}
		}
		$opt_officer .= "</select>";


		$modal_sign .= '<div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">???????????????? ??????????????</h4>
          <button type="button" class="close" data-dismiss="modal">??</button>
          
        </div>
        <div class="modal-body">
        <form method="POST" action="config/config.php" id="asign_save">
          <div class="col-md-12">
            <input type="hidden" class="form-control" name="cases" value="' . $case . '">
            <input type="hidden" class="form-control" name="user_signer" value="' . $user . '">
            
            <label class="label_pers_page">????????????</label>
            ' . $opt_officer . '
            <label class="label_pers_page">????????????????????????????????</label>
            <input type="text" class="form-control form-control-sm" name="comment_to" value="' . $msg_asign . '">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">??????????</button>
          <input type="submit" name="save_a_sign" class="btn btn-primary" form="asign_save" value="????????????????">
        </div>
      </div>
      </form>
    </div>
    ';

		echo $modal_sign;
	}


	//Modal send to a sign

	if (isset($_POST['asign_case'])) {
		$modal_a_sign = '';
		$to_sign_msg = '';
		$sender_id = $_POST['sender'];
		$case_id = $_POST['asign_case'];

		$sql_sender = "SELECT * FROM users WHERE id = $sender_id";
		$result_sender = $conn->query($sql_sender);
		if ($result_sender->num_rows > 0) {
			$row_sender = $result_sender->fetch_assoc();
			$sender_f_name = $row_sender['f_name'];
			$sender_l_name = $row_sender['l_name'];
		}

		$query_family_for_msg = "SELECT * FROM tb_person WHERE case_id = $case_id and role = 1";
		$result_head_for_msg = $conn->query($query_family_for_msg);

		if ($result_head_for_msg->num_rows > 0) {
			$row = $result_head_for_msg->fetch_assoc();
			$name = $row['f_name_arm'];
			$surname = $row['l_name_arm'];
			$full_head = $name . ' ' . $surname;

			$to_sign_msg .= $full_head . "?? ?????????????? ?????????????? ?????????????????????? ????????????";
		}


		$sql_reciver = "SELECT * FROM users WHERE user_type = 'devhead'";
		$resutl_reciver = $conn->query($sql_reciver);
		$opt_reciver = '';

		while ($row_reciver = mysqli_fetch_array($resutl_reciver)) {

			$opt_reciver = $opt_reciver . "<option value=" . $row_reciver['id'] . ">" . $row_reciver['f_name'] . ' ' . $row_reciver['l_name'] . "</option>";

		}


		$modal_a_sign .= '<div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">???????????????? ??????????????????</h5>
          <button type="button" class="close" data-dismiss="modal">??</button>
          
        </div>
        <div class="modal-body">
        <form method="POST" action="config/config.php" id="asign_a_save">
          <div class="col-md-12">
                        
            <label class="label_pers_page">??????</label> 
            <select class="form-control" name="reciver_id"> 
            ' . $opt_reciver . '
            </select>
            <label class="label_pers_page">????????????????????????????????</label>
            <textarea class="form-control" rows="3" name="sign_comment"> ' . $to_sign_msg . ' </textarea>



            <input type="hidden" class="form-control form-control-sm" name="from" value="' . $sender_id . '">
            <input type="hidden" class="form-control form-control-sm" name="case_to_sign" value="' . $case_id . '">
        
           



          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">????????????????</button>
          <input type="submit" name="asign_a_save" class="btn btn-success" form="asign_a_save" value="????????????????">
        </div>
      </div>
      </form>
    </div>
    ';

		echo $modal_a_sign;
	}

	//Send to asign process

	if (isset($_POST['asign_a_save'])) {

		$signing_case = $_POST['case_to_sign'];
		$signer = $_POST['from'];
		$reciver = $_POST['reciver_id'];
		$comment_a_sign = $_POST['sign_comment'];


		$sql_change_actual = "UPDATE `tb_process` SET `actual`= '0' WHERE case_id = $signing_case";

		if ($conn->query($sql_change_actual) === TRUE) {
			$sql_process = "INSERT INTO `tb_process`(`case_id`, `sign_status`, `sign_by`, `processor`, `comment_to`, `actual`, `comment_status`) VALUES ('$signing_case', '2', '$signer', '$reciver', NULLIF('$comment_a_sign', ''), '1', '0')";

			if ($conn->query($sql_process) === TRUE) {
				$sql_notify = "INSERT INTO `tb_notifications`(`comment_subject`, `comment_text`, `comment_status`, `comment_from`, `comment_to`, `case_id`, `note_type`) VALUES ('?????? ????????', NULLIF('$comment_a_sign', ''), '0', '$signer', '$reciver', '$signing_case', '1')";
				if ($conn->query($sql_notify) === TRUE) {
					header('location: ../user.php?page=cases&homepage=inbox');
				} else {
					echo "Error: " . $sql_notify . "<br>" . $conn->error;
				}


			} else {
				echo "Error: " . $sql_process . "<br>" . $conn->error;
			}
		} else {
			echo "Error: " . $sql_change_actual . "<br>" . $conn->error;
		}
	}

	// Request modal to body

	if (isset($_POST['request_case'])) {

		$modal_request = "";
		$case = $_POST['request_case'];
		$user = $_SESSION['user_id'];


		$sql_body = "SELECT `body_id`, `body` FROM `tb_request_bodies`";
		$res_body = $conn->query($sql_body);
		$optbody = '<select name="select_body" id="select_body" class="form-control form-control-sm">';
		$optbody .= '<option selected disabled hidden>???????????? ??????????????</option>';
		while ($row = $res_body->fetch_assoc()) {
			$optbody .= "<option value=" . $row['body_id'] . ">" . $row['body'] . "</option>";
		}
		$optbody .= "</select>";

		$sql_reciver = "SELECT id, f_name, l_name, user_type, user_status FROM users WHERE user_type = 'devhead'";
		$res_devhead = $conn->query($sql_reciver);
		$optrec = '<select name="select_rec" id="select_rec" class="form-control form-control-sm">';
		while ($row2 = $res_devhead->fetch_assoc()) {
			$optrec .= "<option value=" . $row2['id'] . ">" . $row2['f_name'] . ' ' . $row2['l_name'] . "</option>";
		}
		$optrec .= "</select>";


		$msg_request = "?????????????? ???? ???????????????? ??????????????????";


		$modal_request .= '<div class="modal-dialog modal-xl">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="far fa-arrow-alt-circle-right first_menu"></i> ?????????????? ?????? ????????????</h5>
          <button type="button" class="close" data-dismiss="modal">??</button>
           
        </div>
        <div class="modal-body">
        <form method="POST" action="config/config.php" id="request_b" enctype="multipart/form-data">
          <div class="row">
            <div class="col-md-12">
              <label class="label_pers_page">????????????</label>
              ' . $optrec . '
            </div>

            <div class="col-md-12">
              <label class="label_pers_page">????????????</label>
              ' . $optbody . '
            </div>

            <div class="col-md-12">
              <div class="form-group custom-file">
                <input type="file" name="file" class="custom-file-input" id="customFile" required="required" />
                <label class="custom-file-label" for="customFile">?????????????? ??????????</label>
              </div>
            </div> 

             <div class="col-md-12">
              <label class="label_pers_page">????????????????????????????????</label>
              <textarea name="request_msg" class="form-control">' . $msg_request . '</textarea>
            </div>

 
          <input type="hidden" name="autor" value="' . $user . '" /> 
          <input type="hidden" name="case_num_b" value="' . $case . '" />
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">????????????????</button>
          <input type="submit" name="request_b" class="btn btn-success" form="request_b" value="???????????????? ??????????????????">
        </div>
      </div>
      </form>
    </div>
    ';

		echo $modal_request;


	}

	//INSERT request

	if (isset($_POST['request_b'])) {
		$case_id_request = $_POST['case_num_b'];
		$body = $_POST['select_body'];
		$request_text = trim($_POST['request_msg']);
		$reciver = $_POST['select_rec'];
		$request_proc_status = '2';
		$author = $_POST['autor'];
		$proc_msg = $_POST['request_msg'];
		$user_from = $_SESSION['user_id'];
		$filename = $_FILES['file']['name'];


		# Location
		$location = "../uploads/" . $case_id_request . "/requests";

		# create directoy if not exists in upload/ directory
		if (!is_dir($location)) {
			mkdir($location, 0755);
		}

		$location .= "/" . $filename;


		$sql_request = "INSERT INTO `tb_request_out`(`case_id`, `author`, `body`, `request_status`) VALUES ('$case_id_request', '$author', '$body', '0')";

		if ($conn->query($sql_request) === TRUE) {
			$last_request_id = $conn->insert_id;
			$sql_insert_request_process = "INSERT INTO `tb_request_process`(`request_id`, `user_from`, `request_user_to`, `request_actual`, `process_status`, `process_comment`) VALUES ('$last_request_id', '$user_from', '$reciver', '1', '$request_proc_status', '$proc_msg')";

			if ($conn->query($sql_insert_request_process) === TRUE) {
				$last_request_process = $conn->insert_id;
				if (move_uploaded_file($_FILES['file']['tmp_name'], $location)) {
					$sql_insert_request_file = "INSERT INTO `files`(`file_name`, `file_type`, `uploader`, `case_id`, `request_process_id`) VALUES ('$filename', '20','$author','$case_id_request', '$last_request_process')";

					if ($conn->query($sql_insert_request_file) === TRUE) {
						$sql_notify = "INSERT INTO `tb_notifications`(`comment_subject`, `comment_text`, `comment_status`, `comment_from`, `comment_to`, `case_id`, `request_id`, `note_type`) VALUES ('?????? ??????????????', NULLIF('$proc_msg', ''), '0', '$author', '$reciver', '$case_id_request', '$last_request_id', '3')";

						if ($conn->query($sql_notify) === TRUE) {
							header('location: ../user.php?page=cases&homepage=case_page&case=' . $case_id_request);
						} else {
							echo "Error: " . $sql_notify . "<br>" . $conn->error;
						}
					}

				} else {
					echo "Error: " . $sql_insert_request_file . "<br>" . $conn->error;
				}


			} else {
				echo "Error: " . $sql_insert_request_process . "<br>" . $conn->error;
			}

		} else {
			echo "Error: " . $sql_request . "<br>" . $conn->error;
		}


	}
	##########################
	// approve request

	if (isset($_POST['request_appr'])) {
		$request_id = $_POST['request_appr'];
		$request_body = $_POST['request_body'];
		$case_id = $_POST['case_id'];
		$modal_aprove_request = '';
		$body_type = '';
		if ($request_body == 1) {
			$body_type = 'nss';
		}
		if ($request_body == 2) {
			$body_type = 'police';
		}

		$msg_request = '?????????????? ???? ?????????????????? ?????????? ?????? ?????????????????????? ??????????????????';
		$sql_reciver = "SELECT id, f_name, l_name, user_type, user_status FROM `users` WHERE `user_type` = '$body_type' ORDER BY `user_type` ASC";
		$res_rec_body = $conn->query($sql_reciver);
		$optrec = '<select name="select_rec" id="select_rec" class="form-control form-control-sm">';
		while ($row2 = $res_rec_body->fetch_assoc()) {
			$optrec .= "<option value=" . $row2['id'] . ">" . $row2['f_name'] . ' ' . $row2['l_name'] . "</option>";
		}
		$optrec .= "</select>";

		$modal_aprove_request = '<div class="modal-dialog modal-xl">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="far fa-arrow-alt-circle-right first_menu"></i> ???????????????? ?????? ???????????? ?????????????????? ????????????????</h5>
          <button type="button" class="close" data-dismiss="modal">??</button>
           
        </div>
        <div class="modal-body">
        <form method="POST" action="config/config.php" id="request_b" enctype="multipart/form-data">
          <div class="row">
            <div class="col-md-12">
              <label class="label_pers_page">????????????</label>
              ' . $optrec . '
            </div>

            <div class="col-md-12">
             <label class="label_pers_page">???????????????????? ???????????????????? ??????????</label>
              <div class="form-group custom-file">
                <input type="file" name="file" class="custom-file-input" id="customFile" required="required" />
                <label class="custom-file-label" for="customFile">?????????????? ??????????</label>
              </div>
            </div> 

             <div class="col-md-12">
              <label class="label_pers_page">????????????????????????????????</label>
              <textarea name="request_msg" class="form-control">' . $msg_request . '</textarea>
            </div>

 
          <input type="hidden" name="request_id" value="' . $request_id . '" />
          <input type="hidden" name="case_num_b" value="' . $case_id . '" />
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">????????????????</button>
          <input type="submit" name="request_b_approve" class="btn btn-success" form="request_b" value="????????????????">
        </div>
      </div>
      </form>
    </div>

  ';

		echo $modal_aprove_request;
	}

	if (isset($_POST['request_b_approve'])) {
		$case_id_request = $_POST['case_num_b'];
		$request_id = $_POST['request_id'];
		// $body = $_POST['select_rec'];
		$request_text = trim($_POST['request_msg']);
		$reciver = $_POST['select_rec'];
		$request_proc_status = '3';
		$author = $_SESSION['user_id'];
		$proc_msg = $_POST['request_msg'];
		$user_from = $_SESSION['user_id'];
		$filename = $_FILES['file']['name'];


		# Location
		$location = "../uploads/" . $case_id_request . "/requests";

		# create directoy if not exists in upload/ directory
		if (!is_dir($location)) {
			mkdir($location, 0755);
		}

		$location .= "/" . $filename;

		$sql_update_process_actual = "UPDATE tb_request_process SET request_actual = 0 WHERE request_id = $request_id";
		$result_update_actual = $conn->query($sql_update_process_actual);

		$sql_insert_request_process = "INSERT INTO `tb_request_process`(`request_id`, `user_from`, `request_user_to`, `request_actual`, `process_status`, `process_comment`) VALUES ('$request_id', 
     '$user_from', '$reciver', '1', '$request_proc_status', '$proc_msg')";

		if ($conn->query($sql_insert_request_process) === TRUE) {
			$last_request_process = $conn->insert_id;
			if (move_uploaded_file($_FILES['file']['tmp_name'], $location)) {
				$sql_insert_request_file = "INSERT INTO `files`(`file_name`, `file_type`, `uploader`, `case_id`, `request_process_id`) VALUES ('$filename', '21','$author','$case_id_request', '$last_request_process')";

				if ($conn->query($sql_insert_request_file) === TRUE) {
					$sql_notify = "INSERT INTO `tb_notifications`(`comment_subject`, `comment_text`, `comment_status`, `comment_from`, `comment_to`, `case_id`, `request_id`, `note_type`) VALUES ('?????? ??????????????', NULLIF('$proc_msg', ''), '0', '$author', '$reciver', '$case_id_request', '$request_id', '3')";

					if ($conn->query($sql_notify) === TRUE) {
						header('location: ../user.php?page=cases&homepage=case_page&case=' . $case_id_request);
					} else {
						echo "Error: " . $sql_notify . "<br>" . $conn->error;
					}
				}

			} else {
				echo "Error: " . $sql_insert_request_file . "<br>" . $conn->error;
			}


		} else {
			echo "Error: " . $sql_insert_request_process . "<br>" . $conn->error;
		}

	}


	########################
	//INSERT asign modal i katarum

	if (isset($_POST['save_a_sign'])) {

		$officer2 = $_POST['select_officer'];
		$case_id_s = $_POST['cases'];
		$user = $_POST['user_signer'];
		$comment_to = $_POST['comment_to'];


		$update_case = "UPDATE tb_case SET `officer` = '$officer2' WHERE case_id = $case_id_s";

		if ($conn->query($update_case) === TRUE) {

			$sql_chk_actual = "SELECT * FROM tb_process WHERE case_id = $case_id_s";
			$result_chk = $conn->query($sql_chk_actual);

			if ($result_chk->num_rows > 0) {
				$sql_update_process = "UPDATE tb_process SET `actual` = 0 WHERE case_id = $case_id_s";

				if ($conn->query($sql_update_process) === TRUE) {
					$sql_insert_process = "INSERT INTO `tb_process` 
              (`case_id`, `sign_status`, `sign_by`, `processor`, `comment_to`, `actual`) VALUES ($case_id_s, '3', '$user', '$officer2', NULLIF('$comment_to', ''), '1')";
					if ($conn->query($sql_insert_process) === TRUE) {

						$sql_notify = "INSERT INTO `tb_notifications`(`comment_subject`, `comment_text`, `comment_status`, `comment_from`, `comment_to`, `case_id`, `note_type`) VALUES ('?? ????????????????', NULLIF('$request_msg', ''), '0', '$user', '$officer2', '$case_id_s', '1')";
						if ($conn->query($sql_notify) === TRUE) {
							header('location: ../user.php?page=cases&homepage=case_page&case=' . $case_id_s);
						} else {
							echo "Error: " . $sql_notify . "<br>" . $conn->error;
						}
					} else {
						echo "Error: " . $sql_insert_process . "<br>" . $conn->error;
					}

				} else {
					echo "Error: " . $sql_update_process . "<br>" . $conn->error;
				}
			} else {
				echo "Error: " . $update_case . "<br>" . $conn->error;
			}

		}
	}


	//Send to re_sign process

	if (isset($_POST['resign_case'])) {
		$modal_re_sign = '';
		$sender_id = $_POST['resender'];
		$case_id = $_POST['resign_case'];
		$msg = "?????????????? ???? ???????????????????????? ?????? ????????????????????";
		$sql_sender = "SELECT * FROM users WHERE id = $sender_id";
		$result_sender = $conn->query($sql_sender);
		if ($result_sender->num_rows > 0) {
			$row_sender = $result_sender->fetch_assoc();
			$sender_f_name = $row_sender['f_name'];
			$sender_l_name = $row_sender['l_name'];
		}

		$sql_reciver = "SELECT * FROM users WHERE user_type = 'devhead'";
		$resutl_reciver = $conn->query($sql_reciver);
		$opt_reciver = '';

		while ($row_reciver = mysqli_fetch_array($resutl_reciver)) {

			$opt_reciver = $opt_reciver . "<option value=" . $row_reciver['id'] . ">" . $row_reciver['f_name'] . ' ' . $row_reciver['l_name'] . "</option>";

		}


		$modal_re_sign .= '<div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">?????????????????????? ??????????????????????????</h5>
          <button type="button" class="close" data-dismiss="modal">??</button>
          
        </div>
        <div class="modal-body">
        <form method="POST" action="config/config.php" id="resign">
          <div class="col-md-12">
           
           
            <label class="label_pers_page">??????</label> 
            <select class="form-control" name="reciver_id"> 
            ' . $opt_reciver . '
            </select>
            <label class="label_pers_page">????????????????????????????????????</label>
            <textarea class="form-control" rows="3" name="resign_comment">'.$msg.'</textarea>



            <input type="hidden" class="form-control form-control-sm" name="from" value="' . $sender_id . '">
            <input type="hidden" class="form-control form-control-sm" name="case_re_sign" value="' . $case_id . '">
        
           



          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">??????</button>
          <input type="submit" name="resign_save" class="btn btn-success" form="resign" value="?????? ??">
        </div>
      </div>
      </form>
    </div>
    ';

		echo $modal_re_sign;
	}


	//Update re modal i katarum

	if (isset($_POST['resign_save'])) {

		$resigning_case = $_POST['case_re_sign'];
		$resigner = $_POST['from'];
		$comment_to = $_POST['resign_comment'];
		$resign_to = $_POST['reciver_id'];


		$update_case = "UPDATE tb_case SET `officer`= 'NULL' ";

		if ($conn->query($update_case) === TRUE) {

			$sql_chk_actual = "SELECT * FROM tb_process WHERE case_id = $resigning_case";
			$result_chk = $conn->query($sql_chk_actual);

			if ($result_chk->num_rows > 0) {
				$sql_update_process = "UPDATE tb_process SET `actual` = 0 WHERE case_id = $resigning_case";

				if ($conn->query($sql_update_process) === TRUE) {
					$sql_insert_process = "INSERT INTO `tb_process` 
              (`case_id`, `sign_status`, `sign_by`, `processor`, `comment_to`, `actual`) 
              VALUES ($resigning_case, '12', '$resigner', '$resign_to', NULLIF('$comment_to', ''), '1')";
					if ($conn->query($sql_insert_process) === TRUE) {
						header('location: ../user.php?page=cases&homepage=case_page&case=' . $resigning_case);
					} else {
						echo "Error: " . $sql_insert_process . "<br>" . $conn->error;
					}

				} else {
					echo "Error: " . $sql_update_process . "<br>" . $conn->error;
				}
			} else {
				echo "Error: " . $update_case . "<br>" . $conn->error;
			}

		}
	}


	//COI request modal

	if (isset($_POST['coi_case'])) {

		$modal_coi = "";
		$case = $_POST['coi_case'];
		$user = $_SESSION['user_id'];

		$user_fname = $_SESSION['user_fName'];
		$user_lname = $_SESSION['user_lName'];


		$sql = "SELECT * FROM tb_case a INNER JOIN tb_process b ON a.case_id = b.case_id INNER JOIN tb_person c ON a.case_id = c.case_id WHERE a.case_id = $case AND b.actual = 1 AND c.role = 1";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
			$row_s = $result->fetch_assoc();
			$officer = $row_s['officer'];
			$processor = $row_s['processor'];
			$date_now = date('Y-m-d');
			$family_head_citizenship = $row_s['citizenship'];
		}

		$query_user_for_coi = "SELECT * FROM users WHERE user_type = 'coispec'";
		$coi_spec = $conn->query($query_user_for_coi);
		$opt_coi_spec = '<select name="select_coi_spec" id="select_coi_spec" class="form-control form-control-sm" required>';
		$opt_coi_spec .= "<option> -- ?????????? ???? -- </option>";
		while ($row5 = mysqli_fetch_array($coi_spec)) {


			$opt_coi_spec .= "<option value=" . $row5['id'] . ">" . $row5['f_name'] . ' ' . $row5['l_name'] . "</option>";
		}

		$opt_coi_spec .= "</select>";


		$query_state_for_coi = "SELECT * FROM tb_country";
		$state_coi = $conn->query($query_state_for_coi);
		$opt_coi_state = '<select name="select_coi_state" id="select_coi_state" class="form-control form-control-sm" required>';
		while ($row6 = mysqli_fetch_array($state_coi)) {
			if ($row6['country_id'] == $family_head_citizenship) {
				$opt_coi_state .= "<option selected=\"selected\" value=" . $row6['country_id'] . ">" . $row6['country_arm'] . "</option>";
			} else {
				$opt_coi_state .= "<option value=" . $row6['country_id'] . ">" . $row6['country_arm'] . "</option>";
			}
		}

		$opt_coi_state .= "</select>";


		$modal_coi .= '<div class="modal-dialog modal-xl">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="far fa-arrow-alt-circle-right first_menu"></i> ?????? ??????????????</h4>
          <button type="button" class="close" data-dismiss="modal">??</button>
          
        </div>
        <div class="modal-body">
        <form method="POST" action="config/config.php" id="asign_coi">
        <input type="hidden" name="coi_from" value="' . $user . '" /> 

<div class="col-md-12">    
    <div class="row">
        <div class="col-md-4">
              <label for="coi_to" class="control-label label_pers_page">??????</label>
              ' . $opt_coi_spec . '
        </div>

        <div class="col-md-4">
        <label for="password" class="control-label label_pers_page">???????????? ??????????</label>
         ' . $opt_coi_state . '
        </div>

        <div class="col-md-4">
        <label for="request_deadline" class="control-label label_pers_page">????????????????????????</label>
         <input type="date" class="form-control form-control-sm" id="request_deadline" name="request_deadline" required >
        </div>
    </div>
    <hr>
    <div class="row">
      <div class="col-md-12">
        <label for="description" class="control-label label_pers_page">????????????????????????????</label>
        <textarea class="form-control" rows="4" id="description" name="description"> </textarea>
      </div>

      <div class="col-md-12">
        <label for="request_text" class="control-label label_pers_page">??????????????????????????</label>
        <textarea class="form-control" rows="4" id="request_text" name="request_text" required> </textarea>
      </div>

      <div class="col-md-12">
        <label for="request_text" class="control-label label_pers_page">????????????????????????????????</label>
        <textarea class="form-control" rows="3" id="request_msg" name="request_msg" required> </textarea>
      </div>
    </div>

   <hr>
    <div class="f row">
    <div class="col-md-4">
        <label for="coi_from" class="control-label label_pers_page">??????????</label>
        <input type="text" class="form-control form-control-sm" id="coi_from" name="coi_from" value="' . $user_fname . ' ' . $user_lname . '"  readonly>
    </div>
    <div class="col-md-4">
        <label for="case_num" class="control-label label_pers_page">???????? #</label>
         <input type="text" class="form-control form-control-sm" id="case_num" name="case_num" value="' . $case . '" readonly>
    </div>
    <div class="col-md-4">
        <label for="now_date" class="control-label label_pers_page">?????????????? ??????????????</label>
        <input type="date" class="form-control form-control-sm" id="now_date" name="request_date" value=' . $date_now . ' readonly>
    </div>
         

    </div>

   

   
        
        </div>
   
    
 
    
           
        
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">????????????????</button>
          <input type="submit" name="save_coi" class="btn btn-primary" form="asign_coi" value="????????????????">
        </div>
      </div>
      </form>
    </div>


    ';


		echo $modal_coi;

	}


	//INSERT INTO tb_coi

	if (isset($_POST['save_coi'])) {

		$coi_to = $_POST['select_coi_spec'];
		$coi_from = $_SESSION['user_id'];
		$case_id = $_POST['case_num'];
		$request_date = $_POST['request_date'];
		$request_deadline = $_POST['request_deadline'];
		$description = $_POST['description'];
		$request_text = $_POST['request_text'];
		$coi_state = $_POST['select_coi_state'];
		$request_type = '1';
		$request_msg = $_POST['request_msg'];

		$insert_coi_request = "INSERT INTO `tb_coi`(`from_officer`, `to_coispec`, `case_id`, `request_date`, `request_deadline`, `description`, `request_text`, `coi_state`, `request_type`) VALUES ('$coi_from', '$coi_to', ' $case_id', '$request_date', '$request_deadline', NULLIF('$description', ''), '$request_text', '$coi_state', '$request_type')";

		if ($conn->query($insert_coi_request) === TRUE) {
			$last_id = $conn->insert_id;
			$sql_notify = "INSERT INTO `tb_notifications`(`comment_subject`, `comment_text`, `comment_status`, `comment_from`, `comment_to`, `case_id`, `coi_id`, `note_type`) VALUES ('?????? ??????????????', NULLIF('$request_msg', ''), '0', '$coi_from', '$coi_to', '$case_id', '$last_id', '2')";
			if ($conn->query($sql_notify) === TRUE) {
				header('location: ../user.php?page=cases&homepage=case_page&case=' . $case_id);
			} else {
				echo "Error: " . $sql_notify . "<br>" . $conn->error;
			}
		} else {
			echo "Error: " . $insert_coi_request . "<br>" . $conn->error;
		}

	}


	//Editing case info

	if (isset($_POST['edit_case'])) {
		$case = $_POST['edit_case'];
		$user = $_SESSION['user_id'];
		$sql_case = "SELECT a.case_id, a.comment, b.f_name_arm, b.l_name_arm, c.sign_status, d.status, a.application_date, a.unaccompanied_child, a.separated_child, a.single_parent, a.prefered_language, a.preferred_lawyer, a.contact_tel, a.contact_email, a.RA_marz, a.RA_community, a.RA_settlement, a.RA_street, a.RA_building, a.RA_apartment, j.ADM1_ARM AS MARZ, k.ADM3_ARM AS COMMUNITY, l.ADM4_ARM AS SETTLEMENT,
c.sign_date, e.case_status, a.input_date,  
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

		if ($result_case->num_rows > 0) {
			$row = $result_case->fetch_assoc();
			$sign_status = $row['status'];
			$case_status = $row['case_status'];
			$application_date = $row['application_date'];
			$input = date("d.m.Y", strtotime($row['input_date']));
			$reg_by = $row['REG_NAME'];
			$officer = $row['OFFICER_NAME'];
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
			while ($row1 = mysqli_fetch_array($marzz)) {

				if ($row1['marz_id'] == $marz) {
					$optmarz .= "<option selected=\"selected\" value=" . $row1['marz_id'] . ">" . $row1['ADM1_ARM'] . "</option>";
				} else {
					$optmarz .= "<option value=" . $row1['marz_id'] . ">" . $row1['ADM1_ARM'] . "</option>";
				}
			}
			$optmarz .= "</select>";

			$query_community = "SELECT * FROM tb_arm_com";
			$communit = mysqli_query($conn, $query_community);
			$optcom = '<select name="select_community" id="select_community" class="form-control form-control-sm">';
			while ($row2 = mysqli_fetch_array($communit)) {

				if ($row2['community_id'] == $community) {
					$optcom .= "<option selected=\"selected\" value=" . $row2['community_id'] . ">" . $row2['ADM3_ARM'] . "</option>";
				} else {
					$optcom .= "<option value=" . $row2['community_id'] . ">" . $row2['ADM3_ARM'] . "</option>";
				}
			}
			$optcom .= "</select>";

			$query_settl = "SELECT * FROM tb_settlement";
			$settl = mysqli_query($conn, $query_settl);
			$optsettl = '<select name="select_setl" id="select_setl" class="form-control form-control-sm">';
			while ($row3 = mysqli_fetch_array($settl)) {

				if ($row3['settlement_id'] == $satl) {
					$optsettl .= "<option selected=\"selected\" value=" . $row3['settlement_id'] . ">" . $row3['ADM4_ARM'] . "</option>";
				} else {
					$optsettl .= "<option value=" . $row3['settlement_id'] . ">" . $row3['ADM4_ARM'] . "</option>";
				}
			}
			$optsettl .= "</select>";


		}

		$modal_edit_case = "";


		$user_fname = $_SESSION['user_fName'];
		$user_lname = $_SESSION['user_lName'];

		$modal_edit_case .= '
  <div class="modal-dialog modal-xl">    
    <!-- Modal content-->
    <div class="modal-content">
      <form method="POST" action="config/config.php" id="edit_case" >
        <input name="edit_case_number" type="hidden" value="' . $case . '" />
        <div class="modal-header">
          <h4 class="modal-title">?????????? ??????????????????</h4>
          <button type="button" class="close" data-dismiss="modal">??</button>          
        </div>
        <div class="modal-body">      
          <div class="col-md-12">            
            <div class="row">
              <div class="col-md-8">
                <h5 class="sub_title" style="margin-top: 5px;"> ???????????? ???????????????? </h5>

                <table class="table">
                 <thead>
                  <tr>
                    <th class="b_table"> ?????????? ?????????????????? ?????????? </th>
                    <th class="b_table"> ?????????????????? ???????????????? ?????????? </th>
                    <th class="b_table"> ?????????????? ???????? </th>
                    <th class="b_table"> ?????????????????? ?? ???????????????? </th>
                  </tr>
                 </thead>
                  <tr>
                    <td> <input type="checkbox" class="form-control form-control-sm" name="unaccompanied_child" ' . $unaccompanied_child . '> </td>
                    <td> <input type="checkbox" class="form-control form-control-sm" name="separated_child" ' . $separated_child . '> </td>
                    <td> <input type="checkbox" class="form-control form-control-sm" name="single_parent" ' . $single_parent . '> </td>
                    <td> <input type="checkbox" class="form-control form-control-sm" name="preferred_lawyer" ' . $preferred_lawyer . '> </td>                    
                  </tr>
                
                </table>
                
                <table class="table">
                  <tr>
                    <th class="b_table">???????????????????? ??????????</th>
                    <th class="b_table">??????????????????????????</th>
                    <th class="b_table">????.????????</th>
                  </tr>
                  <tr>
                    <td><input type="text" class="form-control form-control-sm" name="prefered_language" value="' . $prefered_language . '"></td>
                    <td><input type="text" class="form-control form-control-sm" name="contact_tel" value="' . $contact_tel . '"></td>
                    <td><input type="text" class="form-control form-control-sm" name="contact_email" value="' . $contact_email . '"></td>

                  </tr>
                </table>

                <h5 class="sub_title">?????????????????? ??????????????????????????????</h5>
                <textarea name="comment" class="form-control" rows="6"  >' . $comment_case . '</textarea>
              </div>     
              <div class="col-md-4">
                <h5 class="sub_title" style="margin-top: 5px;">???????????????????? ???????????? ????-??????</h5>      
                <div class="col-md-12">
                    <label class="label_pers_page"> ???????? </label>
                    ' . $optmarz . '
                </div>
                <div class="col-md-12">
                  <label class="label_pers_page"> ?????????????? </label>
                  ' . $optcom . '
                </div>
                <div class="col-md-12">
                  <label class="label_pers_page"> ?????????????????? </label>
                  ' . $optsettl . '
                </div>
                <div class="col-md-12">
                  <label class="label_pers_page"> ?????????? </label>
                  <input type="text" name="RA_street" class="form-control form-control-sm" value= "' . $RA_street . '">
                </div>
                <div class="col-md-12">
                  <label class="label_pers_page">????????</label>
                  <input type="text" name="RA_building" class="form-control form-control-sm" value=" ' . $RA_building . '">
                </div>
                <div class="col-md-12">
                  <label class="label_pers_page"> ???????????????? </label>
                  <input type="text" name="RA_apartment" class="form-control form-control-sm" value=" ' . $RA_apartment . ' ">
                </div>                   
              </div>            
              <div class="col-md-6">
                <h5 class="sub_title" style="margin-top: 5px;">?????????? ???????????????????? </h5> 
                <table class="table">
                  <tr>
                    <th class="table_a">?????????? #</th>
                    <td><input type="text" name="case_status" class="form-control form-control-sm" value="' . $case . '" readonly></td>
                  </tr>
                  <tr>
                    <th class="table_a">?????????? ????????????????????</th>
                    <td><input type="text" name="case_status" class="form-control form-control-sm" value="' . $case_status . '" readonly></td>
                  </tr>
                    <tr>
                    <th class="table_a">?????????????????????? ????????????????????</th>
                    <td><input type="text" name="sign_status" class="form-control form-control-sm" value="' . $sign_status . '" readonly></td>
                  </tr>
                  <tr>    
                    <th class="table_a">???????????????? ????????????????????????</th>
                    <td><input type="text" name="deadline_1" class="form-control form-control-sm" value="" readonly ></td>
                  </tr>
                  <tr>  
                    <th class="table_a" >?????????????????????? ????????????????????????</th>
                    <td><input type="text" name="deadline_2" class="form-control form-control-sm" value="" readonly></td>
                  </tr>
                </table>       
              </div>
              <div class="col-md-6">
                <h5 class="sub_title" style="margin-top: 5px;">?????????? ?????????????????????? </h5> 
                <table class="table">
                  <tr>
                    <th class="table_a">?????????????? ??????????????</th>
                    <td><input type="date" name="application_date" class="form-control form-control-sm" value="' . $application_date . '" ></td>
                  </tr>
                  <tr>  
                    <th class="table_a">?????????????????????? ??????????????</th>
                    <td><input type="text" name="input_date" class="form-control form-control-sm" value="' . $input . '" readonly></td>
                  </tr>
                  <tr>
                    <th class="table_a">???????????????????? ??????????????????</th>
                    <td><input type="text" name="reg_by" class="form-control form-control-sm" value="' . $reg_by . '" readonly></td>
                  </tr>
                  <tr>
                    <th class="table_a">???????? ??????????</th>
                    <td><input type="text" name="officer" class="form-control form-control-sm" value="' . $officer . '" readonly></td>
                  </tr>
                  <tr>  
                    <th class="table_a">?????????? ????????????????</th>
                    <td><input type="text" name="processor" class="form-control form-control-sm" value="' . $processor . '" readonly></td>
                  </tr>
                </table>
              </div>         
            </div>           
          </div>       
        </div> 
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">??????????</button>
          <input type="submit" name="save_edit_case" class="btn btn-primary" value="????????????????" />                   
        </div>
      </form> 
    </div>  
  </div>


  


    ';


		echo $modal_edit_case;


	}

	//Saveing case edit
	if (isset($_POST["save_edit_case"])) {
		$unaccompanied_child = 0;
		$separated_child = 0;
		$single_parent = 0;
		$preferred_lawyer = 0;
		$prefered_language = "";
		$contact_tel = "";
		$contact_email = "";
		$select_marz = 0;
		$select_community = 0;
		$select_setl = 0;
		$RA_street = "";
		$RA_building = "";
		$RA_apartment = "";
		$application_date = "";
		$comment = "";
		$edit_case_number = 0;

		if (isset($_POST["unaccompanied_child"])) {
			$unaccompanied_child = 1;
		}
		if (isset($_POST["separated_child"])) {
			$separated_child = 1;
		}
		if (isset($_POST["single_parent"])) {
			$single_parent = 1;
		}
		if (isset($_POST["preferred_lawyer"])) {
			$preferred_lawyer = 1;
		}
		if (isset($_POST["prefered_language"])) {
			$prefered_language = $_POST["prefered_language"];
		}
		if (isset($_POST["contact_tel"])) {
			$contact_tel = $_POST["contact_tel"];
		}
		if (isset($_POST["contact_email"])) {
			$contact_email = $_POST["contact_email"];
		}
		if (isset($_POST["select_marz"])) {
			$select_marz = $_POST["select_marz"];
		}
		if (isset($_POST["select_community"])) {
			$select_community = $_POST["select_community"];
		}
		if (isset($_POST["select_setl"])) {
			$select_setl = $_POST["select_setl"];
		}
		if (isset($_POST["RA_street"])) {
			$RA_street = $_POST["RA_street"];
		}
		if (isset($_POST["RA_building"])) {
			$RA_building = $_POST["RA_building"];
		}
		if (isset($_POST["RA_apartment"])) {
			$RA_apartment = $_POST["RA_apartment"];
		}
		if (isset($_POST["application_date"])) {
			$application_date = $_POST["application_date"];
		}
		if (isset($_POST["comment"])) {
			$comment = $_POST["comment"];
		}
		if (isset($_POST["edit_case_number"])) {
			$edit_case_number = $_POST["edit_case_number"];
		}
		$sql_update_case = "UPDATE `tb_case` SET `application_date`='$application_date',`preferred_lawyer`='$preferred_lawyer',`unaccompanied_child`='$unaccompanied_child',
  `separated_child`='$separated_child',`single_parent`='$single_parent',`prefered_language`='$prefered_language',`RA_marz`=$select_marz,`RA_community`=$select_community,
  `RA_settlement`=$select_setl,`RA_street`='$RA_street',`RA_building`='$RA_building',`RA_apartment`='$RA_apartment',`contact_tel`='$contact_tel',`contact_email`='$contact_email',`comment`='$comment' WHERE  case_id=$edit_case_number";
		if ($conn->query($sql_update_case) === TRUE) {
			header('location: ../user.php?page=cases&homepage=case_page&case=' . $edit_case_number);
		} else {
			echo "Error: " . $sql_update_case . "<br>" . $conn->error;
		}
		$conn->close();
		exit;
	}


	//Updateing user Online status
	if (isset($_POST['updateActivity'])) {
		$now = getdate()[0];
		$userId = $_SESSION['user_id'];
		$queryLogin = "UPDATE users SET last_activity =  $now WHERE id=$userId";
		if ($conn->query($queryLogin) === TRUE) {
			echo 'success';
		} else {
			echo "Error: " . "<br>" . $conn->error;
		}
	}

	//Editing User Status
	if (isset($_POST['editUserStatus'])) {
		$userStatusId = $_POST['editUserStatus'];
		$userNewStatus = $_POST['newStatus'];
		$sql_status = "UPDATE  users SET user_status = $userNewStatus WHERE id = $userStatusId";
		if ($conn->query($sql_status) === TRUE) {
			echo 'status_change_success';
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}

	}

	//Edit user Form
	if (isset($_POST['editUserId'])) {
		$editUserId = $_POST['editUserId'];
		$userQuery = "SELECT * FROM users WHERE id = '$editUserId'";
		$userInfo = $conn->query($userQuery);
		if ($userInfo->num_rows === 1) {
			$selectedAdmin = '';
			$selectedOperator = '';
			$selectedViewer = '';
			$selectedOfficer = '';
			$selectedCoiSpec = '';
			$selectedLawyer = '';
			$selectedhead = '';
			$selecteddevhead = '';
			$selectedstatist = '';
			$UserRow = $userInfo->fetch_assoc();
			if ($UserRow["user_type"] === 'admin') {
				$selectedAdmin = ' selected';
			}
			if ($UserRow["user_type"] === 'operator') {
				$selectedOperator = ' selected';
			}
			if ($UserRow["user_type"] === 'officer') {
				$selectedOfficer = ' selected';
			}
			if ($UserRow["user_type"] === 'coispec') {
				$selectedCoiSpec = ' selected';
			}
			if ($UserRow["user_type"] === 'lawyer') {
				$selectedLawyer = ' selected';
			}
			if ($UserRow["user_type"] === 'head') {
				$selectedhead = ' selected';
			}
			if ($UserRow["user_type"] === 'devhead') {
				$selecteddevhead = ' selected';
			}
			if ($UserRow["user_type"] === 'statist') {
				$selectedstatist = ' selected';
			}
			if ($UserRow["user_type"] === 'viewer') {
				$selectedViewer = ' selected';
			}
			$userEditModal = '  <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title text-center" id="myModalLabel">???????????????? ??????????????????</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>
                            <form method="" id="newUserForm" >
                                <div class="modal-body">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <label class="control-label" style="position:relative; top:7px;">??????????</label>
                                            <input type="text" class="form-control" id="firstname"  value="' . $UserRow['f_name'] . '">
                                        </div>
                                        <div class="row">
                                            <label class="control-label" style="position:relative; top:7px;">????????????????:</label>
                                            <input type="text" class="form-control" id="lastname" value="' . $UserRow['l_name'] . '">
                                        </div>
                                        <div class="row">
                                            <label class="control-label" style="position:relative; top:7px;">??????????</label>
                                            <select class="form-control" name="user_type" id="user_type">
                                                <option value="admin"' . $selectedAdmin . '>??????????????????????????</option>
                                                <option value="operator"' . $selectedOperator . '>????????????????????</option>
                                                <option value="officer"' . $selectedOfficer . '>???????? ??????????</option>
                                                <option value="coispec"' . $selectedCoiSpec . '>?????? ??????????????????</option>
                                                <option value="lawyer"' . $selectedLawyer . '>????????????????</option>
                                                <option value="devhead"' . $selecteddevhead . '>?????????? ??????</option>
                                                <option value="statist"' . $selectedstatist . '>??????????????????????</option>
                                                <option value="viewer"' . $selectedEditor . '>?????? ??????????????????</option>
                                                <option value="head" ' . $selectedhead . '>???? ??????</option>
                                            </select>
                                        </div>
                                        <div class="row">
                                            <label class="control-label" style="position:relative; top:5px;">????????????????????<span id="usernameError">* ?????????????????????? ???????????????? ??</span></label>
                                            <input type="text" class="form-control" id="user_login" value="' . $UserRow['username'] . '">
                                        </div>
                                        <div class="row">
                                            <label class="control-label" style="position:relative; top:7px;">??????????????????</label>
                                            <input type="text" class="form-control" id="pass" >
                                        </div>';


			$userEditModal .= '                                    
                                    </div> 
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glypicon-remove"></span> Cancel</button>
                                    <button type="button" class="btn  btn-outline-success" uid="' . $UserRow['id'] . '" id="userEditSubmit"><span class="glyphicon glyphicon-floppy-disk"></span> ????????????????</button>
                                </div>
                            </form>
                        </div>
                      </div>';
			echo $userEditModal;
		}
	}

	//Editing user info
	if (isset($_POST['edit_user'])) {
		$uid = $_POST['uid'];
		$firstname = $_POST['fname'];
		$lastname = $_POST['lname'];
		$user_type = $_POST['userType'];
		$user_login = $_POST['login'];
		$user_pass = '';
		if (strlen(trim($_POST['pass'])) > 0) {
			$user_pass = sha1(trim($_POST['pass']));
		}
		$sql_chk = "SELECT * FROM users WHERE username = '$user_login' AND id <> $uid";
		$res = $conn->query($sql_chk);
		if ($res->num_rows > 0) {
			echo 'change';
			exit;
		} else {
			$sql = "UPDATE `users` SET `username`='$user_login',`f_name`='$firstname',`l_name`='$lastname',`user_type`='$user_type'";
			if (strlen($user_pass) > 0) {
				$sql .= ",`password`='$user_pass' ";
			}
			$sql .= " WHERE id=$uid";
			if ($conn->query($sql) === TRUE) {
				echo 'success';
			} else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
		}
	}

	//Adding new user
	if (isset($_POST['new_user'])) {
		$firstname = $_POST['fname'];
		$lastname = $_POST['lname'];
		$user_type = $_POST['userType'];
		$user_login = $_POST['login'];
		$user_pass = $_POST['pass'];
		$user_pass = sha1($user_pass);

		$sql_chk = "SELECT * FROM users WHERE username = '$user_login'";
		$res = $conn->query($sql_chk);
		if ($res->num_rows > 0) {
			echo 'change';
			exit;
		} else {
			$sql = "insert into users (username, password, f_name, l_name, user_type, user_status) 
    values ('$user_login', '$user_pass', '$firstname', '$lastname', '$user_type', 1)";
			if ($conn->query($sql) === TRUE) {
				echo 'success';
			} else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
		}
	}


	if (isset($_POST['save_new_case'])) {


		$case_status = '1';
		$application_date = $_POST['application_date'];
		$registrar = $_POST['registrar'];

		$special_case = '0';
		if (isset($_POST['special_case'])) {
			$special_case = '1';
		}

		$preferred_lawyer = '0';
		if (isset($_POST['prefere_lawyer'])) {
			$preferred_lawyer = '1';
		};

		$unaccompanied_child = '0';
		if (isset($_POST['unaccompanied_child'])) {
			$unaccompanied_child = '1';
		}

		$separated_child = '0';
		if (isset($_POST['separated_child'])) {
			$separated_child = '1';
		}

		$single_parent = '0';
		if (isset($_POST['single_parent'])) {
			$single_parent = '1';
		}

		$reopened_status = '0';
		if (isset($_POST['reopened_case'])) {
			$reopened_status = '1';
		}

		$comment_1 = $_POST['comment_1'];
		$prefered_language = $_POST['pref_language'];
		$contact_tel = $_POST['tel'];
		$contact_email = $_POST['case_email'];
		$RA_marz = $_POST['select_marz'];
		$RA_community = $_POST['select_community'];
		$RA_settlement = $_POST['select_bnakavayr'];
		$RA_street = $_POST['street'];
		$RA_building = $_POST['building'];
		$RA_apartment = $_POST['flat'];
		$mul_num = $_POST['mul_num'];
		$mul_date = $_POST['mul_date'];

		$former_case_id = $_POST['attached_case'];

		$possessor = $_SESSION['user_id'];
		$sign_status = '1';
		$sign_date = date("Y-m-d");
		$sign_by = $_SESSION['user_id'];

		$sql = "INSERT INTO `tb_case`(`application_date`, `reg_by`, `preferred_lawyer`, `unaccompanied_child`, `separated_child`, `single_parent`, `prefered_language`, `RA_marz`, `RA_community`, `RA_settlement`, `RA_street`, `RA_building`, `RA_apartment`, `contact_tel`, `contact_email`, `comment`, `case_status`, `mul_num`, `mul_date`, `special`, `reopened`, `attached_case`) VALUES ('$application_date', 
  '$registrar', 
  '$preferred_lawyer', 
  '$unaccompanied_child', 
  '$separated_child', 
  '$single_parent', 
  NULLIF('$prefered_language', ''), 
  '$RA_marz', 
  '$RA_community', 
  '$RA_settlement', 
  NULLIF('$RA_street', ''), 
  NULLIF('$RA_building', ''),  
  NULLIF('$RA_apartment', ''), 
  NULLIF('$contact_tel', ''), 
  NULLIF('$contact_email', ''),
  NULLIF('$comment_1', ''), 
  '$case_status',
  '$mul_num',
  '$mul_date',
  '$special_case',
  '$reopened_status',
  NULLIF('$former_case_id', '')
)";


		if ($conn->query($sql) === TRUE) {
			$last_id = $conn->insert_id;

			# Location
			$location = "../uploads/" . $last_id;

			# create directoy if not exists in upload/ directory
			if (!is_dir($location)) {
				mkdir($location, 0755);
			}

			$sql_process = "INSERT INTO `tb_process`(`case_id`, `sign_status`, `sign_date`, `sign_by`, `processor`, `actual`) VALUES ('$last_id', '$sign_status', 
    '$sign_date', '$sign_by', '$possessor', '1')";

			if ($conn->query($sql_process) === TRUE) {

				$deadline_type = '1';
				$deadline_1 = date('Y-m-d', strtotime("+3 months", strtotime($mul_date)));

				$sql_deadline = "INSERT INTO `tb_deadline`(`case_id`,`deadline_type`, `deadline`, `actual_dead`) VALUES ('$last_id', '$deadline_type', '$deadline_1', '1')";

				if ($conn->query($sql_deadline) === TRUE) {
					header('location: ../user.php?page=cases&homepage=add_person&case=' . $last_id);
				} else {
					echo "Error: " . $sql_deadline . "<br>" . $conn->error;
				}


			} else echo "Error: " . $sql_process . "<br>" . $conn->error;

		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}

	if (isset($_POST['save_person']) || isset($_POST['new_person'])) {

		$case_id_p = $_POST['case_id'];
		$f_name_arm = mb_strtoupper($_POST['f_name_arm'], 'UTF-8');
		$l_name_arm = mb_strtoupper($_POST['l_name_arm'], 'UTF-8');
		$m_name_arm = mb_strtoupper($_POST['m_name_arm'], 'UTF-8');
		$f_name_eng = strtoupper($_POST['f_name_eng']);
		$l_name_eng = strtoupper($_POST['l_name_eng']);
		$m_name_eng = strtoupper($_POST['m_name_eng']);
		$arrival_date = '0000-00-00';
		$role = $_POST['select_role'];
		$b_day = $_POST['bday'];
		$b_month = $_POST['bmonth'];
		$b_year = $_POST['byear'];
		$sex = $_POST['select_sex'];
		$citizenship = $_POST['select_citizenship'];
		$previous_residence = $_POST['select_residence'];
		$citizen_adr = $_POST['adr_citizen'];
		$residence_adr = $_POST['adr_res'];
		$departure_from_citizen = $_POST['citizen_departure_date'];
		$departure_from_residence = $_POST['res_departure_date'];

		$doc_num = $_POST['doc_num'];
		$etnicity = $_POST['select_etnic'];
		$religion = $_POST['select_religion'];
		$preferred_traslator_sex = $_POST['translator_sex'];
		$preferred_interviewer_sex = $_POST['interview_sex'];
		$comment_2 = $_POST['comment_2'];
		$invalid = '0';
		if (isset($_POST['invalid'])) {
			$invalid = '1';
		};
		if ($_POST['arrival_date'] != '') {
			$arrival_date = $_POST['arrival_date'];
		}
		$pregnant = '0';
		if (isset($_POST['pregnant'])) {
			$pregnant = '1';
		}

		$seriously_ill = '0';
		if (isset($_POST['ill'])) {
			$seriously_ill = '1';
		}

		$trafficking_victim = '0';
		if (isset($_POST['trafiking'])) {
			$trafficking_victim = '1';
		}

		$violence_victim = '0';
		if (isset($_POST['violence_victim'])) {
			$violence_victim = '1';
		}

		$illegal_border = '0';
		if (isset($_POST['illegal'])) {
			$illegal_border = '1';
		}

		$deport_prosecutor = '0';
		if (isset($_POST['deport_prosecutor'])) {
			$deport_prosecutor = '1';
		}

		$prison = '0';
		if (isset($_POST['prison'])) {
			$prison = '1';
		}

		$transfer_moj = '0';
		if (isset($_POST['transfer_moj'])) {
			$transfer_moj = '1';
		}
		if (isset($_FILES['file']['name'])) {
			# Get file name
			$filename = $_FILES['file']['name'];
			$fileNameArray = explode(".", $filename);
			$filename = "profile." . end($fileNameArray);
		}


		$sql1 = "INSERT INTO `tb_person`(`case_id`, `f_name_arm`, `f_name_eng`, `l_name_arm`, `l_name_eng`, `m_name_arm`, `m_name_eng`, `b_day`, `b_month`, `b_year`, `sex`, `citizenship`, `previous_residence`, `citizen_adr`, `residence_adr`, `departure_from_citizen`, `departure_from_residence`, `arrival_date`, `doc_num`, `etnicity`, `religion`, `preferred_traslator_sex`, `preferred_interviewer_sex`, `invalid`, `pregnant`, `seriously_ill`, `trafficking_victim`, `violence_victim`, `comment`, `illegal_border`, `transfer_moj`, `deport_prescurator`, `prison`, `role`, `image`) 
  VALUES ('$case_id_p', 
  '$f_name_arm', 
  '$f_name_eng', 
  '$l_name_arm', 
  '$l_name_eng', 
  NULLIF('$m_name_arm', ''), 
  NULLIF('$m_name_eng', ''), 
  NULLIF('$b_day', ''), 
  NULLIF('$b_month', ''), 
  '$b_year', '$sex', 
  '$citizenship', 
  NULLIF('$previous_residence', ''), 
  '$citizen_adr', 
  NULLIF('$residence_adr', ''), 
  '$departure_from_citizen', 
  NULLIF('$departure_from_residence', ''),
  '$arrival_date', 
  '$doc_num', 
  '$etnicity', 
  '$religion', 
  '$preferred_traslator_sex', 
  '$preferred_interviewer_sex', 
  '$invalid', '$pregnant', 
  '$seriously_ill', 
  '$trafficking_victim', 
  '$violence_victim', 
  NULLIF('$comment_2', ''), 
  '$illegal_border', 
  '$transfer_moj', 
  '$deport_prosecutor', 
  '$prison',
  '$role', 
  '$filename')";

		if ($conn->query($sql1) === TRUE) {
			$last_pers_id = $conn->insert_id;
			# Location
			$location = "../uploads/" . $case_id_p . "/" . $last_pers_id;

			# create directoy if not exists in upload/ directory
			if (!is_dir($location)) {
				mkdir($location, 0755);
			}

			$location .= "/" . $filename;
			# Upload file
			move_uploaded_file($_FILES['file']['tmp_name'], $location);
			if (!isset($_POST['new_person'])) {
				header('location: ../user.php?page=cases&homepage=case_page&case=' . $case_id_p);
			} else {
				header('location: ../user.php?page=cases&homepage=add_person&case=' . $case_id_p);
			}
		} else {
			echo "Error: " . $sql1 . "<br>" . $conn->error;
		}
	}


	// COI response body modal
	if (isset($_POST['coi_answer'])) {

		$modal_ansqer_coi = "";
		$coi_id = $_POST['coi_answer'];
		$user = $_SESSION['user_id'];

		$user_fname = $_SESSION['user_fName'];
		$user_lname = $_SESSION['user_lName'];
		$msg_coi = '';
		$sql_coi = "SELECT * FROM tb_coi WHERE coi_id = $coi_id";
		$result_coi = $conn->query($sql_coi);

		if ($result_coi->num_rows > 0) {
			$row = $result_coi->fetch_assoc();
			$case = $row['case_id'];
			$officer = $row['from_officer'];

		}
		$msg_coi = "?? ???????????????? # $case ?????????? ?????????????????????????? ?????????????? ?????? ????????????????";
		$modal_ansqer_coi .= '<div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">?????? ?????????????? ????????????????</h4>
          <button type="button" class="close" data-dismiss="modal">??</button>

        </div>
        <div class="modal-body">
        <form method="POST" action="config/config.php" id="response_coi" enctype="multipart/form-data">
         <input type="hidden" name="case_num" value="' . $case . '">
        <input type="hidden" name="coi_num" value="' . $coi_id . '">
        <input type="hidden" name="coi_spec" value="' . $user . '" >
        <input type="hidden" name="coi_requester" value="' . $officer . '">
        <div class="form-group custom-file">
           
            <input type="file" name="file" class="custom-file-input" id="customFile" required="required" />
            <label class="custom-file-label" for="customFile">?????????????? ??????????</label>
          </div>
      
  
    
    <hr>
   
    <div class="form-group">
        <label>????????????????????????????????</label>
        <textarea class="form-control" rows="3" id="coi_response_msg" name="coi_response_msg" required>' . $msg_coi . ' </textarea>
    </div>
          
        

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">????????????????</button>
          <input type="submit" name="response_coi" class="btn btn-primary" form="response_coi" value="????????????????????">
        </div>
      </div>
      </form>
    </div>


   
 ';

		$modal_ansqer_coi .= "<script>
  $('.custom-file-input').on('change', function() {
  var fileName = $(this).val();
  $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
});
</script>";


		echo $modal_ansqer_coi;

	}


	// //INSERT INTO tb_coi

	if (isset($_POST['response_coi'])) {


		if (isset($_FILES['file']['name'])) {
			#Coi_id
			$case_id = $_POST['case_num'];
			$coi_id = $_POST['coi_num'];
			$response_day = date("Y/m/d");
			$comment_coi = $_POST['coi_response_msg'];
			$coi_spec = $_POST['coi_spec'];
			$requester = $_POST['coi_requester'];
			# Get file name
			// $filename = $_FILES['file']['name'];

			$filename = $_FILES['file']['name'];


			# Location
			$location = "../uploads/" . $case_id . "/coi";

			# create directoy if not exists in upload/ directory
			if (!is_dir($location)) {
				mkdir($location, 0755);
			}

			$location .= "/" . $filename;

			# Upload file
			if (move_uploaded_file($_FILES['file']['tmp_name'], $location)) {


				$sql = "UPDATE tb_coi SET file_name = '$filename', response_date = '$response_day', coi_status = '1' WHERE coi_id = $coi_id";

				if ($conn->query($sql) === TRUE) {
					$sql_notify = "INSERT INTO `tb_notifications`(`comment_subject`, `comment_text`, `comment_status`, `comment_from`, `comment_to`, `case_id`, `note_type`) VALUES ('?????? ????????????????', NULLIF('$coi_response_msg', ''), '0', '$coi_spec', '$requester', '$case_id', '1')";
					if ($conn->query($sql_notify) === TRUE) {
						header('location: ../user.php?page=cases&homepage=coi_page&case=' . $case_id . '&coi=' . $coi_id);
					} else {
						echo "Error: " . $sql_notify . "<br>" . $conn->error;
					}
				} 
				else 
				{
					echo "failed to uplpad";
				}
			}
		}
	}


	//Send decision modal

	if (isset($_POST['decision_1'])) {
		$modal_send = '';
		$case_id = $_POST['decision_1'];
		$sender_id = $_POST['user'];
		
		$a='';

		$proc_status = $_POST['proc_status'];

		if ($proc_status == 9) {
			$sql_decision = "SELECT * FROM tb_decisions WHERE case_id = $case_id AND actual = 1";
			$res_dec = $conn->query($sql_decision);
			if($res_dec->num_rows > 0){
				$row_dec = $res_dec->fetch_assoc();
				$dec_type = $row_dec['decision_type'];

				if ($dec_type == 9) {
					$a = ',9';
				}
			}
		}


		$sql_reciver = "SELECT * FROM users WHERE user_type = 'devhead' AND user_status = '1'";

		$resutl_reciver = $conn->query($sql_reciver);
		$opt_reciver = '';

		while ($row_reciver = mysqli_fetch_array($resutl_reciver)) 
		{
			$opt_reciver = $opt_reciver . "<option value=" . $row_reciver['id'] . ">" . $row_reciver['f_name'] . ' ' . $row_reciver['l_name'] . "</option>";
		}






		$sql_decision_types = "SELECT * FROM tb_decision_types";

		$check_for_special = "SELECT a.case_id, a.special, c.sign_status, c.actual FROM tb_case a INNER JOIN tb_process c ON a.case_id = c.case_id WHERE c.actual = 1 AND a.special = 1 AND a.case_id = $case_id";
		$result_special_case = $conn->query($check_for_special);
		if ($result_special_case->num_rows > 0) {
			$sql_decision_types .= " WHERE decision_type_id IN (2,3,4,5)";
		} else {

			$chk_ceased = "SELECT a.case_id, a.special, a.reopened, c.sign_status, c.actual FROM tb_case a INNER JOIN tb_process c ON a.case_id = c.case_id WHERE c.actual = 1 AND c.sign_status IN (16,17$a) AND a.case_id = $case_id";
			$result_chk_ceased = $conn->query($chk_ceased);
			if ($result_chk_ceased->num_rows > 0) {
				$sql_decision_types .= " WHERE decision_type_id IN (5,9)";
			} else {
				$sql_decision_types .= " WHERE decision_type_id NOT IN (5,9)";
			}
			$chk_prolongations = "SELECT * FROM tb_deadline WHERE case_id = $case_id AND actual_dead = '1'";
			$result_chk_prolongation = $conn->query($chk_prolongations);

			$deadline_type = '';
			if ($result_chk_prolongation->num_rows > 0) {
				$row = $result_chk_prolongation->fetch_assoc();
				$deadline_type = $row['deadline_type'];


				if ($deadline_type == 4) {
					$sql_decision_types .= " WHERE decision_type_id != '1'";
				}
			}
		}
		$resutl_decisions_types = $conn->query($sql_decision_types);
		
		$opt_decisions = '';
		$opt_decisions = '<select name="decision_type"  class="form-control" required>';
		$opt_decisions .= '<option selected disabled hidden value="">?????????????? ????????????????</option>';
		while ($row_decisions = mysqli_fetch_array($resutl_decisions_types)) {
			$opt_decisions.="<option value=" . $row_decisions['decision_type_id'] . ">" . $row_decisions['decision_type'] . "</option>";
		}

		$opt_decisions .='</select>';


		$msg1 = '?????????????? ???? ???????????????? ????????????????????';


		$modal_send .= '<div class="modal-dialog modal-xl">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-share first_menu"></i> ???????????????? ??????????????????</h5>
          <button type="button" class="close" data-dismiss="modal">??</button>
          
        </div>
        <div class="modal-body">
        <form method="POST" action="config/config.php" id="decision_file" enctype="multipart/form-data">
          <div class="col-md-12">
           
          
            <label class="label_pers_page">??????</label> 
            <select class="form-control" name="reciver_id"> 
            ' . $opt_reciver . '
            </select>
            <label class="label_pers_page">???????? ?????????????? ????????????</label> 
                     
            ' . $opt_decisions . '
           
            
            <label class="label_pers_page">???????? ?????????????? ????????????????</label> 
            <div class="form-group custom-file">
           
            <input type="file" name="file" class="custom-file-input" id="customFile" required="required" />
            <label class="custom-file-label" for="customFile">?????????????? ??????????</label>
            </div>

            <input type="hidden" class="form-control form-control-sm" name="from" value="' . $sender_id . '">
            <input type="hidden" class="form-control form-control-sm" name="dacision_case" value="' . $case_id . '">
     
        
           
           <label class="label_pers_page">????????????????????????????????</label>
            <textarea class="form-control" rows="3" name="decision_comment">' . $msg1 . '</textarea>



          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">????????????????</button>
          <input type="submit" name="decision_save" class="btn btn-success" form="decision_file" value="???????????????? ??????????????????">
        </div>
      </div>
      </form>
    </div>
    ';

		echo $modal_send;
	}


	//Insert decision modal

	if (isset($_POST['decision_save'])) {


		if (isset($_FILES['file']['name'])) {
			#Coi_id
			$decision_case = $_POST['dacision_case'];
			$decision_type = $_POST['decision_type'];
			$sign_by = $_POST['from'];
			$comment_to = $_POST['decision_comment'];
			$decision_to = $_POST['reciver_id'];
			$sign_status = '13';
			$sign_date = date("Y-m-d");
			# Get file name


			$filename = $_FILES['file']['name'];


			# Location
			$location = "../uploads/" . $decision_case;

			# create directoy if not exists in upload/ directory
			if (!is_dir($location)) {
				mkdir($location, 0755);
			}

			$location .= "/" . $filename;

			$query_decisions = "SELECT * FROM tb_decisions WHERE case_id = $decision_case";
			$result_query_decisions = $conn->query($query_decisions);

			$sql_set_draft_actual_null = "UPDATE `tb_draft` SET `actual`= '0' WHERE case_id = $decision_case";
			$result_set_draft_actual_null = $conn->query($sql_set_draft_actual_null);

			if ($result_query_decisions->num_rows > 0) {
				$set_dec_actual = "UPDATE tb_decisions SET actual = '0' WHERE case_id = $decision_case";
				$res_set_dec_actual = $conn->query($set_dec_actual);
			}

			$sql_drafts = "SELECT * FROM tb_drafts WHERE case_id = $decision_case AND actual = '1'";
			$res_draft = $conn->query($sql_drafts);
			if ($res_draft->num_rows > 0) {
				$sql_update_draft = "UPDATE tb_draft SET actual = '0' WHERE case_id = $decision_case";
				$result_update_draft = $conn->query($sql_update_draft);
			}

			# Upload file
			if (move_uploaded_file($_FILES['file']['tmp_name'], $location)) {
				$sql_decision = "INSERT INTO `tb_decisions`(`case_id`, `decision_file`, `decision_type`, `decision_status`, `actual`) VALUES ('$decision_case', '$filename', '$decision_type', '1', '1')";

				if ($conn->query($sql_decision) === TRUE) {

					$sql_change_actual = "UPDATE `tb_process` SET `actual`= '0' WHERE case_id = $decision_case";

					if ($conn->query($sql_change_actual) === TRUE) {
						$sql_process = "INSERT INTO `tb_process`(`case_id`, `sign_status`, `sign_date`, `sign_by`, `processor`, `actual`) VALUES ('$decision_case', '$sign_status', '$sign_date', '$sign_by', '$decision_to', '1')";

						if ($conn->query($sql_process) === TRUE) {
							$sql_notify = "INSERT INTO `tb_notifications`(`comment_subject`, `comment_text`, `comment_status`, `comment_from`, `comment_to`, `case_id`, `note_type`) VALUES ('?????????????? ??????????????', NULLIF('$comment_a_sign', ''), '0', '$sign_by', '$decision_to', '$decision_case', '1')";
							if ($conn->query($sql_notify) === TRUE) {
								header('location: ../user.php?page=cases&homepage=my_cases');
							} else {
								echo "Error: " . $sql_notify . "<br>" . $conn->error;
							}
						} else {
							echo "Error: " . $sql_process . "<br>" . $conn->error;
						}
					} else {
						echo "Error: " . $sql_change_actual . "<br>" . $conn->error;
					}

				} else {
					echo "Error: " . $sql_decision . "<br>" . $conn->error;
				}

			} else {
				echo "failed to uplpad";
			}
		}
	}


	//Final decision - MS head

	if (isset($_POST['decision_2'])) {
		$modal_final_decision_head = '';
		$case_id = $_POST['decision_2'];
		$sender_id = $_POST['user'];

		$sql_reciver = "SELECT * FROM users WHERE user_type = 'head'";
		$check_decision_status = "SELECT * from tb_decisions a INNER JOIN tb_decision_types b ON a.decision_type = b.decision_type_id 
  WHERE a.case_id = $case_id AND a.actual = 1";
		$result_decision_status = $conn->query($check_decision_status);

		if ($result_decision_status->num_rows > 0) {
			$row = $result_decision_status->fetch_assoc();
			$decision_type = $row['decision_type'];
			$decision_type_id = $row['decision_type_id'];
			$decision_id = $row['decision_id'];
		}

		$resutl_reciver = $conn->query($sql_reciver);
		$opt_reciver = '';

		while ($row_reciver = mysqli_fetch_array($resutl_reciver)) {

			$opt_reciver = $opt_reciver . "<option value=" . $row_reciver['id'] . ">" . $row_reciver['f_name'] . ' ' . $row_reciver['l_name'] . "</option>";

		}


		$modal_final_decision_head .= '<div class="modal-dialog modal-xl">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-share first_menu"></i> ???????????????? ??????????????????</h5>
          <button type="button" class="close" data-dismiss="modal">??</button>
          
        </div>
        <div class="modal-body">
        <form method="POST" action="config/config.php" id="decision_file_head" enctype="multipart/form-data">
          <div class="col-md-12">
           
           	<input type="hidden" name="decision_id" value="'.$decision_id.'" />
            <label class="label_pers_page">??????</label> 
            <select class="form-control" name="reciver_id"> 
            ' . $opt_reciver . '
            </select>
            <label class="label_pers_page">???????????? ????????????</label> 
            <input type="text" name="decision_type" class="form-control form-control-sm" value="' . $decision_type . '"  readonly>
            
            
            <input type="hidden" class="form-control form-control-sm" name="from" value="' . $sender_id . '">
            <input type="hidden" class="form-control form-control-sm" name="dacision_case" value="' . $case_id . '">
        
           
           <label class="label_pers_page">????????????????????????????????????</label>
            <textarea class="form-control" rows="3" name="decision_comment">?????????????? ???? ???????????????? ?????????????? ????????????????</textarea>



          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">??????</button>
          <input type="submit" name="decision_head" class="btn btn-success" form="decision_file_head" value="???????????????? ??????????????????">
        </div>
      </div>
      </form>
    </div>
    ';

		echo $modal_final_decision_head;
	}

	// Insert decision to head

	if (isset($_POST['decision_head'])) {
		$case_id = $_POST['dacision_case'];
		$comment_decision_head = $_POST['decision_comment'];
		$sign_by = $_POST['from'];
		$decision_to = $_POST['reciver_id'];
		$decision_id = $_POST['decision_id'];

		$update_decision = "UPDATE tb_decisions SET decision_status = 3 WHERE decision_id = $decision_id";

		if ($conn->query($update_decision) === TRUE) {

			$sql_change_actual = "UPDATE `tb_process` SET `actual`= '0' WHERE case_id = $case_id";

			if ($conn->query($sql_change_actual) === TRUE) {
				$sql_process = "INSERT INTO `tb_process`(`case_id`, `sign_status`, `sign_by`, `processor`, `actual`, `comment_status`, `comment_to`) VALUES ('$case_id', '13', '$sign_by', '$decision_to', '1', '0', '$comment_decision_head')";

				if ($conn->query($sql_process) === TRUE) {
					$sql_notify = "INSERT INTO `tb_notifications`(`comment_subject`, `comment_text`, `comment_status`, `comment_from`, `comment_to`, `case_id`, `note_type`) VALUES ('?????????????? ??????????????', NULLIF('$comment_decision_head', ''), '0', '$sign_by', '$decision_to', '$case_id', '1')";

					if ($conn->query($sql_notify) === TRUE) {
						header('location: ../user.php?page=cases&homepage=case_page&case=' . $case_id);
					} else {
						echo "Error: " . $sql_notify . "<br>" . $conn->error;
					}
				} else {
					echo "Error: " . $sql_process . "<br>" . $conn->error;
				}
			} else {
				echo "Error: " . $sql_change_actual . "<br>" . $conn->error;
			}
		} else {
			echo "Error: " . $update_decision . "<br>" . $conn->error;
		}
	}
	//Body response modal

	if (isset($_POST['request'])) {
		$modal_response = '';
		$request_id = $_POST['request'];
		$request_process_id = $_POST['request_process'];
		$case_id = $_POST['case_id'];

		$receiver_id = '';
		$req_rec = "SELECT * FROM tb_case WHERE case_id = $case_id";
		$result_req_rec = $conn->query($req_rec);

		if ($result_req_rec->num_rows > 0) {
			$row_req_rec = $result_req_rec->fetch_assoc();
			$receiver_id = $row_req_rec['officer'];
		}

		$msg = '?? ???????????????? ?????? ??????????????';


		$modal_response .= '<div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"> <i class="fas fa-reply-all"></i> ?????????????? ????????????????</h5>
          <button type="button" class="close" data-dismiss="modal">??</button>
          
        </div>
        <div class="modal-body">
        <form method="POST" action="config/config.php" id="opinion_file" enctype="multipart/form-data">
          <div class="col-md-12">
           
           
            
           
            <input type="hidden" class="form-control form-control-sm" name="response_note" value="' . $msg . '">
            
            <label class="label_pers_page">???????????????????? ?????????????????? ??????????</label> 
           
            <div class="form-group custom-file">
           
            <input type="file" name="file" class="custom-file-input" id="customFile" required="required" />
            <label class="custom-file-label" for="customFile">?????????????? ??????????</label>
            </div>

            
           
           <label class="label_pers_page mt-3" >????????????????????????????????????</label>
            <textarea class="form-control" rows="3" name="opinion_text">' . $msg . '</textarea>

            <input type="hidden" value="' . $case_id . '" name="case_num_b" />
            <input type="hidden" value="' . $request_id . '" name="request_id" />
            <input type="hidden" value="' . $receiver_id . '" name="receiver_id" />

          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">????????????????</button>
          <input type="submit" name="opinion_save" class="btn btn-success" form="opinion_file" value="????????????????????">
        </div>
      </div>
      </form>
    </div>
    ';

		echo $modal_response;
	}

	//Insert opinion modal

	if (isset($_POST['opinion_save'])) {

		$case_id_request = $_POST['case_num_b'];
		$request_id = $_POST['request_id'];
		$request_text = trim($_POST['opinion_text']);
		$reciver = $_POST['receiver_id'];
		$request_proc_status = '4';
		$author = $_SESSION['user_id'];
		$proc_msg = $_POST['opinion_text'];
		$user_from = $_SESSION['user_id'];
		$filename = $_FILES['file']['name'];


		# Location
		$location = "../uploads/" . $case_id_request . "/requests";

		# create directoy if not exists in upload/ directory
		if (!is_dir($location)) {
			mkdir($location, 0755);
		}

		$location .= "/" . $filename;

		$sql_update_process_actual = "UPDATE tb_request_process SET request_actual = 0 WHERE request_id = $request_id";
		if ($conn->query($sql_update_process_actual) === TRUE) {
			$sql_close_order = "UPDATE tb_request_out SET request_status = 1 WHERE request_id = $request_id";
			$result_close_order = $conn->query($sql_close_order);
		};

		$sql_insert_request_process = "INSERT INTO `tb_request_process`(`request_id`, `user_from`, `request_user_to`, `request_actual`, `process_status`, `process_comment`) VALUES ('$request_id', '$user_from', '$reciver', '1', '$request_proc_status', '$proc_msg')";

		if ($conn->query($sql_insert_request_process) === TRUE) {
			$last_request_process = $conn->insert_id;
			if (move_uploaded_file($_FILES['file']['tmp_name'], $location)) {
				$sql_insert_request_file = "INSERT INTO `files`(`file_name`, `file_type`, `uploader`, `case_id`, `request_process_id`) VALUES ('$filename', '22','$author','$case_id_request', '$last_request_process')";

				if ($conn->query($sql_insert_request_file) === TRUE) {
					$sql_notify = "INSERT INTO `tb_notifications`(`comment_subject`, `comment_text`, `comment_status`, `comment_from`, `comment_to`, `case_id`, `request_id`, `note_type`) VALUES ('?????????????? ????????????????', NULLIF('$proc_msg', ''), '0', '$author', '$reciver', '$case_id_request', '$request_id', '3')";

					if ($conn->query($sql_notify) === TRUE) {
						header('location: ../user.php?page=cases&homepage=body');

					} else {
						echo "Error: " . $sql_notify . "<br>" . $conn->error;
					}
				}

			} else {
				echo "Error: " . $sql_insert_request_file . "<br>" . $conn->error;
			}


		} else {
			echo "Error: " . $sql_insert_request_process . "<br>" . $conn->error;
		}


	}


	####################################################
	if (isset($_POST['upload_file'])) {

		if (isset($_FILES['file']['name'])) {
			$persId = NULL;
			$now = date_create()->format('Y-m-d H:i:s');
			if (isset($_POST["select_member"])) {
				$persId = $_POST["select_member"];
			}
			#opinion_id
			$case_id = $_POST['case_num'];
			$uploader = $_SESSION['user_id'];
			$file_type = $_POST['file_type'];
			$file_person_case = $_POST["file_person_case"];
			# Get file name
			$filename = date('dmYHis') . str_replace(" ", "", basename($_FILES["file"]["name"]));
			# Location
			$location = "../uploads/" . $case_id;
			if ($file_person_case == 2) {
				$location .= "/" . $persId;
			}
			# create directoy if not exists in upload/ directory
			if (!is_dir($location)) {
				mkdir($location, 0755);
			}

			$location .= "/" . $filename;
			$file_path = trim($location, "../");
			# Upload file
			if (move_uploaded_file($_FILES['file']['tmp_name'], $location)) {
				$sql_application = "INSERT INTO `files`(`file_name`, `file_type`, `uploader`, `case_id`, `person_id`,`file_path`) VALUES ('$filename', '$file_type', '$uploader', '$case_id', " . var_export($persId, true) . ",'$file_path')";

				if ($conn->query($sql_application) === TRUE) {
					header('location: ../user.php?page=cases&homepage=case_page&case=' . $case_id);
				} else {
					echo "Error: " . $sql_application . "<br>" . $conn->error;
				}
			} else {
				echo "failed to uplpad";
			}
		}
	}

	//delete modal
	if (isset($_POST['file_id'])) {

		$file_id = $_POST['file_id'];
		$case_id = $_POST['case_id'];
		$modal_delete = '';


		$modal_delete .= '
  <div class="modal-dialog modal-confirm">
    <div class="modal-content">
      <div class="modal-header flex-column">
        <div class="icon-box">
          <i class="fa fa-trash-alt" style="color: #f15e5e; font-size: 46px; display: inline-block; margin-top: 13px;"></i>
        </div>            
        <h4 class="modal-title w-100">????????????????</h4>  
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
     <form action="config/config.php" method="POST" id="delete_m">
      <div class="modal-body">
        <input type="hidden" name="d_id" id="delete_case" value="' . $file_id . '">
        <input type="hidden" name="c_id" id="delete_case_id" value="' . $case_id . '">
        
        <p>???????????????????????????? ?????????????????? ????
          ???????????????????? ?????????????? ?????????????? ?????????????????????? ??????????????????
        </p>

      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-danger" name="f_delete" form="delete_m">Delete</button>
      </div>
      </form>
    </div>
  </div>
  ';

		echo $modal_delete;
	}

	if (isset($_POST['f_delete'])) {
		$fid = $_POST['d_id'];
		$cid = $_POST['c_id'];

		$sql_delete_file = "DELETE FROM `files` WHERE id = $fid";

		if ($conn->query($sql_delete_file) === TRUE) {
			header('location: ../user.php?page=cases&homepage=case_page&case=' . $cid);
		} else {
			echo "Error: " . $sql_delete_file . "<br>" . $conn->error;
		}
	}


	//Modal send call back

	if (isset($_POST['case_call'])) {
		$modal_call_back = '';
		$reciver_id = $_POST['caller'];
		$case_id = $_POST['case_call'];
		$sender_id = $_POST['caller'];
		$role = $_SESSION['role'];
		$case_sign_status = '';

		$sql_sign_status = "SELECT * FROM tb_process WHERE case_id = $case_id and actual = 1";
		$result_sign_status = $conn->query($sql_sign_status);
		if ($result_sign_status->num_rows > 0) {
			$row_sign_status = $result_sign_status->fetch_assoc();
			$case_sign_status = $row_sign_status['sign_status'];
		}

		$query_family_for_msg = "SELECT * FROM tb_person WHERE case_id = $case_id and role = 1";
		$result_head_for_msg = $conn->query($query_family_for_msg);

		if ($result_head_for_msg->num_rows > 0) {
			$row = $result_head_for_msg->fetch_assoc();
			$name = $row['f_name_arm'];
			$surname = $row['l_name_arm'];
			$full_head = $name . ' ' . $surname;

			$to_sign_msg = $full_head . "??` ?????????????? ?????????????????????? ?????????? ?????????????? ?????? ??????????????????";
		}


		$modal_call_back .= '<div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-undo-alt first_menu"></i> ?????? ???????????? ??????????</h5>
          <button type="button" class="close" data-dismiss="modal">??</button>
          
        </div>
        <div class="modal-body">
        <form method="POST" action="config/config.php" id="call_back_case">
          <div class="col-md-12">
                        
            <p>???????????????? ?????? ???? ?????????????????? ???? ?????? ???????????? ' . $full_head . '-?? ??????????????</p>
           
            <input type="hidden" name="reciver_id" value="' . $reciver_id . '">
            <input type="hidden" name="roles" value="' . $role . '">
            <label class="label_pers_page">????????????????????????????????????</label>
            <textarea class="form-control" rows="3" name="sign_comment"> ' . $to_sign_msg . ' </textarea>



            <input type="hidden" class="form-control form-control-sm" name="from" value="' . $sender_id . '">
            <input type="hidden" class="form-control form-control-sm" name="case_to_sign" value="' . $case_id . '">
            <input type="hidden" name="case_ongoing_sign_status" value="' . $case_sign_status . '" /> 
           



          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">????????????????</button>
          <input type="submit" name="call_back_save" class="btn btn-success" form="call_back_case" value="?????? ????????????">
        </div>
      </div>
      </form>
    </div>
    ';

		echo $modal_call_back;
	}

	//call back process

	if (isset($_POST['call_back_save'])) {

		$signing_case = $_POST['case_to_sign'];
		$signer = $_POST['from'];
		$reciver = $_POST['reciver_id'];
		$comment_a_sign = $_POST['sign_comment'];
		$action_role = $_SESSION['role'];
		$case_ongoing_sign_status = $_POST['case_ongoing_sign_status'];


		$chk_decisions = "SELECT * FROM tb_decisions WHERE case_id = $signing_case AND actual = 0";
		$res_chk_decisions = $conn->query($chk_decisions);


		$sign_status = '';

		if ($action_role == 'operator') {
			$sign_status = '1';
		}
		if ($action_role == 'officer') {
			$sign_status = '3';
		}
		if ($action_role == 'devhead' && $case_ongoing_sign_status == 3) {
			$sign_status = '19';
			$update_case_officer = "UPDATE tb_case SET officer = 'NULL'";
			$result_update_officer = $conn->query($update_case_officer);
		}

		if ($action_role == 'devhead' && $case_ongoing_sign_status == 13) {
			$sign_status = '13';

		}


		$sql_change_actual = "UPDATE `tb_process` SET `actual`= '0' WHERE case_id = $signing_case";


		if ($conn->query($sql_change_actual) === TRUE) {
			$sql_process = "INSERT INTO `tb_process`(`case_id`, `sign_status`, `sign_by`, `processor`, `comment_to`, `actual`) VALUES ('$signing_case', '$sign_status', '$reciver', '$reciver', NULLIF('$comment_a_sign', ''), '1')";

			if ($conn->query($sql_process) === TRUE) {
				$sql_notify = "INSERT INTO `tb_notifications`(`comment_subject`, `comment_text`, `comment_status`, `comment_from`, `comment_to`, `case_id`, `note_type`) VALUES ('?????????? ?????? ????????', NULLIF('$comment_a_sign', ''), '0', '$reciver', '$reciver', '$signing_case', '1')";
				if ($conn->query($sql_notify) === TRUE && $_SESSION['role'] === 'operator') {
					header('location: ../user.php?page=cases&homepage=case_page&case=' . $signing_case);
				} elseif ($conn->query($sql_notify) === TRUE && $_SESSION['role'] === 'officer') {
					$update_decision_file = "UPDATE tb_decisions SET decision_status= '6', actual = '0' WHERE case_id = $signing_case";

					if ($conn->query($update_decision_file) === TRUE) {
						header('location: ../user.php?page=cases&homepage=case_page&case=' . $signing_case);
					} else {
						echo "Error: " . $update_decision_file . "<br>" . $conn->error;
					}
				} elseif ($conn->query($sql_notify) === TRUE && $_SESSION['role'] === 'devhead') {
					$update_decision_file2 = "UPDATE tb_decisions SET decision_status = '1' WHERE case_id = $signing_case AND actual = 1";

					if ($conn->query($update_decision_file2) === TRUE) {
						header('location: ../user.php?page=cases&homepage=case_page&case=' . $signing_case);
					} else {
						echo "Error: " . $update_decision_file2 . "<br>" . $conn->error;
					}


				} else {
					echo "Error: " . $sql_notify . "<br>" . $conn->error;
				}
			} else {
				echo "Error: " . $sql_process . "<br>" . $conn->error;
			}
		} else {
			echo "Error: " . $sql_change_actual . "<br>" . $conn->error;
		}
	}

	//Send draft modal

	if (isset($_POST['case_draft'])) {
		$modal_draft = '';
		$case_id = $_POST['case_draft'];
		$sender_id = $_POST['sender'];

		$officer = '';
		$sql_officer = "SELECT * FROM tb_case WHERE case_id = $case_id";
		$result_officer = $conn->query($sql_officer);

		if ($result_officer->num_rows > 0) {
			$row = $result_officer->fetch_assoc();
			$officer = $row['officer'];
		}

		$today = date('Y-m-d');
		$msg = '';

		$sign_status = '';
		$deadline = '';
		$res_id = '';

		$sql_drafts = "SELECT * FROM tb_draft WHERE case_id = $case_id AND actual = 1";
		$result_drafts = $conn->query($sql_drafts);

		if (($result_drafts->num_rows == 0) && ($_SESSION['role'] === 'officer' || $_SESSION['role'] === 'coispec' || $_SESSION['role'] === 'lawyer')) {

			$res_id = ' WHERE a.user_type = "viewer"';
			$deadline = date('Y-m-d', strtotime("+3 days", strtotime($today)));
			$sign_status = '8';
			$msg = "???????????????? ?????????????????? ?????? ???????????????????????? ???? ???????????????? ?????? " . $case_id . ' ' . " ?????????????? ???????????????????? ?????????????? ?????????????? ?????????????????? ?????????????? ???? ???????????? ?????????????????????????????? ?????????????????????? ?????? ??????????????????????????";
		}
		if (($result_drafts->num_rows > 0) && ($_SESSION['role'] === 'officer' || $_SESSION['role'] === 'coispec' || $_SESSION['role'] === 'lawyer')) {
			$res_id = ' WHERE a.user_type = "head"';
			$deadline = date('Y-m-d', strtotime("+10 days", strtotime($today)));
			$sign_status = '8';
			$msg = "???????????????? ?????????? ???????????????? ?????? ???????????????????????? ???? ???????????????? ?????? " . $case_id . ' ' . " ?????????????? ???????????????????? ?????????????? ?????????????? ?????????????????? ?????????????? ???? ?????? ??????????????????????????";
		}

		if ($_SESSION['role'] === 'viewer') {
			$res_id = ' WHERE a.user_type = "devhead"';
			$deadline = date('Y-m-d', strtotime("+5 days", strtotime($today)));
			$sign_status = '8';
			$msg = "???????????????? ?????????????????? ?????? ???????????????????????? ???? ???????????????? ?????? " . $case_id . ' ' . " ?????????????? ???????????????????? ?????????????? ?????????????? ???????????????? ???? ??????????????????????????????";
		}

		if ($_SESSION['role'] === 'devhead') {

			$res_id = ' WHERE (a.user_type = "officer" OR a.user_type = "coispec")';
			$deadline = date('Y-m-d', strtotime("+1 days", strtotime($today)));
			$sign_status = '8';
			$msg = "???????????????? ???????? ??????????, ???????????????????????? ???? ???????????????? ?????? " . $case_id . ' ' . " ?????????????? ???????????????????? ?????????????? ???????????????????????????????? ?? ???? ???????????????????????????? ?????????????? ???? ???????????? ?????????????????????????????? ?????????????????????? ?????????????? ???????????????? ?????????????????????? ????????????";
		}
		if ($_SESSION['role'] === 'head') {
			$res_id = ' WHERE (a.user_type = "officer" OR a.user_type = "coispec")';
			$deadline = date('Y-m-d', strtotime("+5 days", strtotime($today)));
			$sign_status = '14';
			$msg = "???????????????? ???????? ???????????? ?????? ?????????????????? ???? ?????????????? ?????????????? ???????????????????? ???? ???????????????????????????? ?????????????? ???? ?????????????????? ?????????????????? ?????????????????????? ?????????????????? ?????????????????????? ";
		}


		$sql_reciver = "SELECT * FROM users a $res_id";
		if ($_SESSION['role'] === 'head' || $_SESSION['role'] === 'devhead') {
			$sql_reciver .= " AND a.id = $officer";
		}

		$resutl_reciver = $conn->query($sql_reciver);
		$opt_reciver = '';

		while ($row_reciver = mysqli_fetch_array($resutl_reciver)) {

			$opt_reciver = $opt_reciver . "<option value=" . $row_reciver['id'] . ">" . $row_reciver['f_name'] . ' ' . $row_reciver['l_name'] . "</option>";

		}


		$modal_draft .= '<div class="modal-dialog modal-xl">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">?????????????? ??????????????</h5>
          <button type="button" class="close" data-dismiss="modal">??</button>
          
        </div>
        <div class="modal-body">
        <form method="POST" action="config/config.php" id="draft_file" enctype="multipart/form-data">
          <div class="col-md-12">
           
           
            <label class="label_pers_page">??????</label> 
            <select class="form-control" name="reciver_id"> 
            ' . $opt_reciver . '
            </select>
           
           
            

            

            <label class="label_pers_page">????????????????????????????????????</label>
            <textarea class="form-control" rows="10" name="comment"> ' . $msg . ' </textarea>
            <input type="hidden" class="form-control form-control-sm" name="draft_from" value="' . $sender_id . '">
            <input type="hidden" class="form-control form-control-sm" name="draft_case" value="' . $case_id . '">
            <input type="hidden" class="form-control form-control-sm" name="draft_date" value="' . $today . '">
            <input type="hidden" class="form-control form-control-sm" name="draft_deadline" value="' . $deadline . '">
            <input type="hidden" class="form-control form-control-sm" name="draft_sign" value="' . $sign_status . '">

        <div class="form-group custom-file">
           
            <input type="file" name="file" class="custom-file-input" id="customFile" required="required" />
            <label class="custom-file-label" for="customFile">?????????? ??????????</label>
            </div>
           
           



          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">????????????????</button>
          <input type="submit" name="draft_save" class="btn btn-success" form="draft_file" value="????????????????">
        </div>
      </div>
      </form>
    </div>
    ';

		echo $modal_draft;
	}

	//insert draft
	if (isset($_POST['draft_save'])) {

		if (isset($_FILES['file']['name'])) {
			#optional_inputs
			$case_id = $_POST['draft_case'];
			$autor = $_SESSION['user_id'];
			$draft_deadline = $_POST['draft_deadline'];
			$reciver = $_POST['reciver_id'];
			$draft_comment = $_POST['comment'];
			$comment_to = "?????????????? ?????????????? ??????????????????????????";
			$sign_status_draft = $_POST['draft_sign'];

			$note_type = '4';

			if ($_SESSION['role'] === 'head') {
				$note_type = '1';
			}


			# Get file name
			// $filename = $_FILES['file']['name'];

			$filename = $_FILES['file']['name'];


			# Location
			$location = "../uploads/draft/" . $case_id;

			# create directoy if not exists in upload/ directory
			if (!is_dir($location)) {
				mkdir($location, 0755);
			}

			$location .= "/" . $filename;


			# Upload file
			if (move_uploaded_file($_FILES['file']['tmp_name'], $location)) {

				$update_draft = "UPDATE tb_draft SET actual = '0' WHERE case_id = $case_id";

				if ($conn->query($update_draft) === TRUE) {

					$sql_draft = "INSERT INTO `tb_draft`(`case_id`, `draft_file`, `autor`, `deadline`, `receiver`, `draft_comment`, `actual`) VALUES ('$case_id', '$filename', '$autor', '$draft_deadline', '$reciver', '$draft_comment', '1')";

					if ($conn->query($sql_draft) === TRUE) {
						$last_draft_id = $conn->insert_id;
						$sql_update_process = "UPDATE tb_process SET `actual` = 0 WHERE case_id = $case_id";

						if ($conn->query($sql_update_process) === TRUE) {
							$sql_insert_process = "INSERT INTO `tb_process`
                          (`case_id`, `sign_status`, `sign_by`, `processor`, `comment_to`, `actual`) 
                            VALUES ('$case_id', '$sign_status_draft', '$autor', '$reciver', NULLIF('$comment_to', ''), '1')";

							if ($conn->query($sql_insert_process) === TRUE) {

								$sql_notify = "INSERT INTO `tb_notifications`(`comment_subject`, `comment_text`, `comment_status`, `comment_from`, `comment_to`, `case_id`, `draft_id`, `note_type`) VALUES ('?????? ??????????????', NULLIF('$comment_to', ''), '0', '$autor', '$reciver', '$case_id', '$last_draft_id', '4')";
								if ($conn->query($sql_notify) === TRUE) {
									header('location: ../user.php?page=cases&homepage=draft');
								}
							} else {
								echo "Error: " . $sql_notify . "<br>" . $conn->error;
							}
						} else {
							echo "Error: " . $sql_insert_process . "<br>" . $conn->error;
						}

					} else {
						echo "Error: " . $sql_draft . "<br>" . $conn->error;
					}

				}
			} else {
				echo "failed to uplpad";
			}
		}
	}

	//Approve decision modal

	if (isset($_POST['aprove_case'])) {
		$modal_aprove = '';
		$case_id = $_POST['aprove_case'];
		$sender_id = $_SESSION['role'];
		$msg = '';

		$sql_reciver = "SELECT * FROM users a INNER JOIN tb_case b ON a.id = b.officer WHERE b.case_id = $case_id";
		$resutl_reciver = $conn->query($sql_reciver);
		$opt_reciver = '';

		while ($row_reciver = mysqli_fetch_array($resutl_reciver)) {

			$opt_reciver = $opt_reciver . "<option value=" . $row_reciver['id'] . ">" . $row_reciver['f_name'] . ' ' . $row_reciver['l_name'] . "</option>";

		}

		$devhead_id = "";
		$devhead_for_notify = "SELECT id, f_name, l_name FROM users WHERE user_type = 'devhead' AND user_status = '1' ";
		$result_devhead = $conn->query($devhead_for_notify);
		if ($result_devhead->num_rows > 0) {
			$row = $result_devhead->fetch_assoc();
			$devhead_id = $row['id'];
		}

		$check_decision_status = "SELECT * from tb_decisions a INNER JOIN tb_decision_types b ON a.decision_type = b.decision_type_id 
  WHERE a.case_id = $case_id AND a.actual = 1";
		$result_decision_status = $conn->query($check_decision_status);


		$decision_type_id = '';
		if ($result_decision_status->num_rows > 0) {
			$row = $result_decision_status->fetch_assoc();
			$decision_type = $row['decision_type'];
			$decision_type_id = $row['decision_type_id'];


			if ($decision_type_id == 1) {

				$chk_prolongations = "SELECT * FROM tb_deadline WHERE case_id = $case_id AND actual_dead = '1'";
				$result_chk_prolongation = $conn->query($chk_prolongations);


				if ($result_chk_prolongation->num_rows > 0) {
					$row = $result_chk_prolongation->fetch_assoc();
					$actual_deadline = $row['deadline'];
					$deadline_type = $row['deadline_type'];


					if ($deadline_type == 1) {
						$prolongation_period = "+3 months";
						$msg = "???????????? ???????????????????? (+3 ????????)";
					}
					if ($deadline_type == 2) {
						$prolongation_period = "+1 months";
						$msg = "?????????????? ???????????????????? (+1 ????????)";
					}
					if ($deadline_type == 3) {
						$prolongation_period = "+10 days";
						$msg = "???????????? ???????????????????? (+10 ????)";
					}

					if ($deadline_type == 7) {
						$check_prev_prolongations = "SELECT * FROM tb_deadline WHERE case_id = $case_id AND actual_dead = 0 AND deadline_type != 5 ORDER BY deadline_id DESC LIMIT 1";
						$result_check_prev_prolongations = $conn->query($check_prev_prolongations);

						if ($result_check_prev_prolongations->num_rows > 0) {
							$row_former_prolongation_row = $result_check_prev_prolongations->fetch_assoc();
							$former_last_prolongation_type = $row_former_prolongation_row['deadline_type'];

							if ($former_last_prolongation_type == 1)
								$prolongation_period = "+3 months";
							$msg = "???????????? ???????????????????? (+3 ????????)";
						}
						if ($former_last_prolongation_type == 2) {
							$prolongation_period = "+3 months";
							$msg = "???????????? ???????????????????? (+1 ????????)";
						}
						if ($former_last_prolongation_type == 3) {
							$prolongation_period = "+3 months";
							$msg = "???????????? ???????????????????? (10 ????)";
						}
					}
				}

			}

		}

		$modal_aprove .= '<div class="modal-dialog modal-xl">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">????????????????</h5>
          <button type="button" class="close" data-dismiss="modal">??</button>
          
        </div>
        <div class="modal-body">
        <form method="POST" action="config/config.php" id="decision_file" enctype="multipart/form-data">
          <div class="col-md-12">
           
           
            <label class="label_pers_page">????????????</label> 
            <select class="form-control" name="reciver_id"> 
            ' . $opt_reciver . '
            </select>

            <label class="label_pers_page">?????????????? ????????????</label> 
            <input type="text" class="form-control" name="decision_type" value="' . $decision_type . '" readonly />
            <input type="hidden" class="form-control" name="decision_type_id" value="' . $decision_type_id . '">';

		if ($decision_type_id == 1) {
			$modal_aprove .= '
             <label class="label_pers_page">?????????? ???????????????? ?????????????????????????? ?????????????????????? ????????????</label>
             <input type="text" class="form-control" name="prolong_msg1" value="' . $msg . '" >';
		}

		$modal_aprove .= '
            <label class="label_pers_page">???????????????????? ???????????????????? ????????????????</label>
            <div class="form-group custom-file">
            <input type="file" name="file" class="custom-file-input" id="customFile" required="required" />
            <label class="custom-file-label" for="customFile">?????????????? ??????????</label>
            </div>

            <input type="hidden" class="form-control form-control-sm" name="from" value="' . $sender_id . '">
            <input type="hidden" class="form-control form-control-sm" name="dacision_case" value="' . $case_id . '">
        
           
           <label class="label_pers_page">????????????????????????????????</label>
            <textarea class="form-control" rows="3" name="decision_comment"></textarea>



          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">????????????????</button>
          <input type="submit" name="aprove_decision" class="btn btn-success" form="decision_file" value="????????????????">
        </div>
      </div>
      </form>
    </div>
    ';

		echo $modal_aprove;
	}


	//Aprove decision modal

	if (isset($_POST['aprove_decision'])) {

		if (isset($_FILES['file']['name'])) {

			$decision_case = $_POST['dacision_case'];
			$decision_type = $_POST['decision_type_id'];
			$sign_by = $_SESSION['user_id'];
			$comment_to = $_POST['decision_comment'];
			$decision_to = $_POST['reciver_id'];
			$sign_status = '';
			$case_status = '';
			$sign_date = date("Y-m-d");
			$decision_type_id = $_POST['decision_type_id'];
			$decision_out_num = '';
			$decision_letter  = '';



			$new_deadline = '';
			$new_deadline_type = '';


			$query_decisions_count = "SELECT * FROM tb_decisions WHERE case_id = $case_id";
			$result_count_decisions = $conn->query($query_decisions_count);
			$dec_count = '1';
			if($result_count_decisions->num_rows > 0){
				$dec_count = mysqli_num_rows($result_count_decisions);
				$dec_count++;
			}


			if ($decision_type_id == 1) {
				$decision_letter = '??';
				$sign_status = '7';
				$chk_prolongations = "SELECT * FROM tb_deadline WHERE case_id = $decision_case AND actual_dead = '1'";
				$result_chk_prolongation = $conn->query($chk_prolongations);

				if ($result_chk_prolongation->num_rows > 0) {
					$row = $result_chk_prolongation->fetch_assoc();
					$actual_deadline = $row['deadline'];
					$deadline_type = $row['deadline_type'];


					if ($deadline_type == 1) {
						$new_deadline = date('Y-m-d', strtotime("+3 months", strtotime($actual_deadline)));
						$new_deadline_type = '2';
					}
					if ($deadline_type == 2) {
						$new_deadline = date('Y-m-d', strtotime("+1 month", strtotime($actual_deadline)));
						$new_deadline_type = '3';
					}
					if ($deadline_type == 3) {
						$new_deadline = date('Y-m-d', strtotime("+10 weekdays", strtotime($actual_deadline)));
						$new_deadline_type = '4';
					}

					if ($deadline_type == 7) {
						$check_prev_prolongation = "SELECT * FROM tb_deadline WHERE case_id = $decision_case AND actual_dead = 0 AND deadline_type != 5 ORDER BY deadline_id DESC LIMIT 1";
						$result_check_prev_prolongations = $conn->query($check_prev_prolongation);

						if ($result_check_prev_prolongations->num_rows > 0) {
							$row_former_prolongation = $result_check_prev_prolongations->fetch_assoc();
							$former_last_prolongation_type = $row_former_prolongation['deadline_type'];

							if ($former_last_prolongation_type == 1)
								$new_deadline = date('Y-m-d', strtotime("+3 months", strtotime($actual_deadline)));
							$new_deadline_type = '2';
						}
						if ($former_last_prolongation_type == 2) {
							$new_deadline = date('Y-m-d', strtotime("+1 month", strtotime($actual_deadline)));
							$new_deadline_type = '3';
						}
						if ($former_last_prolongation_type == 3) {
							$new_deadline = date('Y-m-d', strtotime("+10 weekdays", strtotime($actual_deadline)));
							$new_deadline_type = '4';
						}
					}
				}


			}

			if ($decision_type_id == 9) {
				$decision_letter = '??';
				$sign_status = '20';
				$new_deadline_type = '7';
				$prev_deadline = '';

				$check_previouse_deadline = "SELECT * FROM tb_deadline a WHERE a.case_id = $decision_case AND a.actual_dead = 0 ORDER BY deadline_id DESC LIMIT 1";
				$result_check_previouse_deadline = $conn->query($check_previouse_deadline);
				if ($result_check_previouse_deadline->num_rows > 0) {
					$row_prev_deadline = $result_check_previouse_deadline->fetch_assoc();
					$prev_deadline = $row_prev_deadline['deadline'];
				}

				$check_ceased_date = "SELECT * FROM tb_process a WHERE case_id = $decision_case AND sign_status = 16 ORDER BY process_id DESC LIMIT 1";
				$result_check_ceased_deadline = $conn->query($check_ceased_date);

				$ceased_date = '';
				if ($result_check_ceased_deadline->num_rows > 0) {
					$row_ceased_date = $result_check_ceased_deadline->fetch_assoc();
					$ceased_date = $row_ceased_date['sign_date'];
				}
				$actual_date = date("Y-m-d");
				$today = new DateTime($actual_date);
				$deadline = new DateTime($prev_deadline);
				$ceased_date_1 = new DateTime($ceased_date);

				$count_days = $ceased_date_1->diff($deadline)->format("%r%a");

				$new_deadline = date('Y-m-d', strtotime("+ $count_days days", strtotime($actual_date)));

			}

			if ($decision_type_id == 2) {
				$decision_letter = '????';
				$sign_status = '16';
				$new_deadline = date('Y-m-d', strtotime("+3  months", strtotime($sign_date)));
				$new_deadline_type = '5';

			}

			if ($decision_type_id == 3) {
				$decision_letter = '??';
				$sign_status = '11';
				$case_status = '5';
				$new_deadline = 'NULL';
				$new_deadline_type = '15';

				$update_case = "UPDATE tb_case SET `case_status` = $case_status WHERE case_id = $decision_case";
				if($conn->query($update_case) === TRUE){
					$update_person_status = "UPDATE tb_person SET person_status = '2' WHERE case_id = $decision_case";
					$result_person_status = $conn->query($update_person_status);
				}
			}


			if ($decision_type_id == 5) {
				$decision_letter = '????';
				$sign_status = '11';
				$case_status = '3';
				$new_deadline = 'NULL';
				$new_deadline_type = '15';

				$update_case = "UPDATE tb_case SET `case_status` = '5' WHERE case_id = $decision_case";
				if($conn->query($update_case) === TRUE){
					$update_person_status = "UPDATE tb_person SET person_status = '5' WHERE case_id = $decision_case";
					$result_person_status = $conn->query($update_person_status);
				}
			}

			if ($decision_type_id == 4){
				$decision_letter = '??';
			}
			
			if ($decision_type_id == 10){
				$decision_letter = '??';
			}

			if ($decision_type_id == 4 || $decision_type_id == 10) {
				$sign_status = '15';
				$case_status = '4';
				$new_deadline = date('Y-m-d', strtotime("+2 months", strtotime($sign_date)));

				$new_deadline_type = '6';
				$update_case = "UPDATE tb_case SET `case_status` = '4' WHERE case_id = $decision_case";
				if($conn->query($update_case) === TRUE){
					$update_person_status = "UPDATE tb_person SET person_status = '5' WHERE case_id = $decision_case";
					$result_person_status = $conn->query($update_person_status);
				}

			}

			$decision_out_num = "????-" . $decision_letter . '/' . $decision_case .'('. $dec_count . ')';

			# Get file name


			$filename = $_FILES['file']['name'];


			# Location
			$location = "../uploads/" . $decision_case;

			# create directoy if not exists in upload/ directory
			if (!is_dir($location)) {
				mkdir($location, 0755);
			}

			$location .= "/" . $filename;

			$query_decisions = "SELECT * FROM tb_decisions WHERE case_id = $decision_case";
			$result_query_decisions = $conn->query($query_decisions);


			if ($result_query_decisions->num_rows > 0) {
				$set_dec_actual = "UPDATE tb_decisions SET actual = 0 WHERE case_id = $decision_case";
				$res_set_dec_actual = $conn->query($set_dec_actual);
			}

			# Upload file
			if (move_uploaded_file($_FILES['file']['tmp_name'], $location)) {
				$sql_decision = "INSERT INTO `tb_decisions`(`case_id`, `decision_file`, `decision_type`, `decision_status`, `actual`, `decision_out_num`) VALUES ('$decision_case', '$filename', '$decision_type', '5', '1', '$decision_out_num')";

				if ($conn->query($sql_decision) === TRUE) {

					$set_deadline_actual = "UPDATE tb_deadline SET actual_dead = 0 WHERE case_id = $decision_case";

					if ($conn->query($set_deadline_actual) === TRUE) {
						$insert_new_deadline = "INSERT INTO `tb_deadline`(`case_id`, `deadline_type`, `deadline`, `actual_dead`) VALUES ('$decision_case', '$new_deadline_type', '$new_deadline', '1')";

						if ($conn->query($insert_new_deadline) === TRUE) {
							$sql_change_actual = "UPDATE `tb_process` SET `actual`= '0' WHERE case_id = $decision_case";

							if ($conn->query($sql_change_actual) === TRUE) {
								$sql_process = "INSERT INTO `tb_process`(`case_id`, `sign_status`, `sign_date`, `sign_by`, `processor`, `actual`) VALUES ('$decision_case', '$sign_status', '$sign_date', '$sign_by', '$decision_to', '1')";

								if ($conn->query($sql_process) === TRUE) {
									$sql_notify = "INSERT INTO `tb_notifications`(`comment_subject`, `comment_text`, `comment_status`, `comment_from`, `comment_to`, `case_id`, `note_type`) VALUES ('?????????????? ??????????????????', NULLIF('$comment_a_sign', ''), '0', '$sign_by', '$decision_to', '$decision_case', '1')";
									if ($conn->query($sql_notify) === TRUE) {
										

										header('location: ../user.php?page=cases&homepage=case_page&case=' . $decision_case);
									} else {
										echo "Error: " . $sql_notify . "<br>" . $conn->error;
									}
								} else {
									echo "Error: " . $sql_notify . "<br>" . $conn->error;
								}
							} else {
								echo "Error: " . $sql_process . "<br>" . $conn->error;
							}
						} else {
							echo "Error: " . $sql_change_actual . "<br>" . $conn->error;
						}

					} else {
						echo "Error: " . $insert_new_deadline . "<br>" . $conn->error;
					}
				} else {
					echo "Error: " . $set_deadline_actual . "<br>" . $conn->error;
				}
			} else {
				echo "failed to uplpad";
			}

		}
	}

	######################################################################################

	// ADD lawyer modal


	if (isset($_POST['lawyer'])) {
		$modal_lawyer = '';

		$case_id = $_POST['lawyer'];


		$modal_lawyer = '<div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">?????????????????? ????????????????</h5>
          <button type="button" class="close" data-dismiss="modal">??</button>
          
        </div>
        <div class="modal-body">
        <form method="POST" action="config/config.php" id="lawyer">
         
        
         <input type="hidden" value="' . $case_id . '" name="case_id"/>

          <div class="col-md-12">
            <div class="row">
              <div class="col-md-6">
                <label class="label_pers_page">?????????????????? ???????????? </label>
                <input type="text" class="form-control form-control-sm" name="lawyer_name"  required />
              </div>
              <div class="col-md-6">
                <label class="label_pers_page">?????????????????? ?????????????????? </label>
                <input type="text" class="form-control form-control-sm" name="lawyer_surname" required />
              </div>
              <div class="col-md-12">
                <label class="label_pers_page">?????????????????????????????? </label>
                <input type="text" class="form-control form-control-sm" name="lawyer_organization" />
              </div>
              <div class="col-md-6">
                <label class="label_pers_page">?????????????????? h???????????????????????? </label>
                <input type="text" class="form-control form-control-sm" name="lawyer_tel" />
              </div>
               <div class="col-md-6">
                <label class="label_pers_page">?????????????????? ????.?????????? </label>
                <input type="email" class="form-control form-control-sm" name="lawyer_email" />
              </div>

              <div class="col-md-12">
                <label class="label_pers_page">?????????????????? ?????????? </label>
                <input type="text" class="form-control form-control-sm" name="lawyer_address" />
              </div>
            </div>
          </div>
         
        </div> 
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">????????????????</button>
          
          <input type="submit" name="save_lawyer" class="btn btn-success" form="lawyer" value="??????????????????">
        </div>
      </div>
      </form>
    </div>
    ';

		echo $modal_lawyer;

	}

	//insert lawyer process

	if (isset($_POST['save_lawyer'])) {

		$case_id = $_POST['case_id'];
		$lawyer_name = $_POST['lawyer_name'];
		$lawyer_surname = $_POST['lawyer_surname'];
		$lawyer_organization = $_POST['lawyer_organization'];
		$lawyer_tel = $_POST['lawyer_tel'];
		$lawyer_email = $_POST['lawyer_email'];
		$lawyer_address = $_POST['lawyer_address'];


		$sql_set_lawyer_actual = "UPDATE `tb_lawyer` SET `actual` = '0' WHERE case_id = $case_id";
		$result_actual = $conn->query($sql_set_lawyer_actual);


		$sql_add_lawyer = "INSERT INTO `tb_lawyer` (`lawyer_name`, `lawyer_surname`, `lawyer_organization`, `lawyer_tel`, `lawyer_address`, `lawyer_email`, `case_id`, `actual`) VALUES ('$lawyer_name', '$lawyer_surname', '$lawyer_organization', '$lawyer_tel', '$lawyer_address', '$lawyer_email', '$case_id', '1')";


		if ($conn->query($sql_add_lawyer) === TRUE) {
			header('location: ../user.php?page=cases&homepage=case_page&case=' . $case_id);
		} else {
			echo "Error: " . $sql_add_lawyer . "<br>" . $conn->error;
		}


	}

	######################################################################################

	// ADD family member out of application

	if (isset($_POST['out_member'])) {
		$modal_out = '';

		$case_id = $_POST['out_member'];

		$sql_out_citizenship = "SELECT * FROM tb_country";
		$result_out_citizenship = $conn->query($sql_out_citizenship);
		$result_out_res = $conn->query($sql_out_citizenship);

		$opt_out_citizen = '';
		$opt_out_citizen = '<select name="select_out_citizenship" id="select_out_citizenship" class="validation form-control form-control-sm">';
		$opt_out_citizen .= '<option selected disabled hidden  value="0">?????????????? ????????????????????????????????</option>';
		while ($row_o_citizenship = mysqli_fetch_array($result_out_citizenship)) {
			$opt_out_citizen = $opt_out_citizen . "<option value=$row_o_citizenship[country_id]> $row_o_citizenship[country_arm]</option>";
		}

		$opt_out_citizen .= "</select>";


		$opt_out_res = '';
		$opt_out_res = '<select name="select_out_res" id="select_out_res" class="validation  form-control form-control-sm">';
		$opt_out_res .= '<option selected disabled hidden value="0">?????????????? ???????????????????? ????????????</option>';
		while ($row_o_res = mysqli_fetch_array($result_out_res)) {
			$opt_out_res = $opt_out_res . "<option value=$row_o_res[country_id]> $row_o_res[country_arm]</option>";
		}

		$opt_out_res .= "</select>";


		$sql_out_role = "SELECT * FROM tb_role WHERE role_id != 1";
		$result_out_role = $conn->query($sql_out_role);

		$opt_out_role = '';
		$opt_out_role = '<select name="select_out_role" id="select_out_role" class="validation  form-control form-control-sm" required>';
		$opt_out_role .= '<option selected disabled hidden value="0">?????????????? ????????</option>';
		while ($row_o_role = mysqli_fetch_array($result_out_role)) {
			$opt_out_role = $opt_out_role . "<option value=$row_o_role[role_id]> $row_o_role[der]</option>";
		}

		$opt_out_role .= "</select>";


		$modal_out = '<div class="modal-dialog modal-xl">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">?????????????????? ???????????? ?????????? ???????????????? ??????????</h5>
          <button type="button" class="close" data-dismiss="modal">??</button>
          
        </div>
        <div class="modal-body">
         

          <div class="col-md-12">
            <div class="row">
              <div class="col-md-4">
                <label class="label_pers_page"><i class="fas fa-house-user"></i> ???????? ??? </label>
                <input type="text" class="form-control form-control-sm" id="modal_case_id" value="' . $case_id . '" readonly />
              </div>
              <div class="col-md-4">
              <label class="label_pers_page"><i class="fas fa-link"></i> ???????? ?????????????????????? ?????? </label>
              ' . $opt_out_role . '
              <label class="error-message"  id="error-messageselect_out_role">???????????? ????????</label>
              </div>
              <div class="col-md-4">
              <label class="label_pers_page"><i class="fas fa-calendar-day"></i> ?????????????? ??????????????</label>
              <div class="form-inline">
                  <input type="number" class="validation form-control form-control-sm col-md-3 mr-2" min="00" minlength="2" max="31" placeholder="????" id="bday" onchange="if(parseInt(this.value,10)<10>1)this.value="0"+this.value;">
                  <input type="number" class="validation form-control form-control-sm col-md-3 mr-2" min="00" minlength="2" max="12" placeholder="????????" id="bmonth" onchange="if(parseInt(this.value,10)<10>1)this.value="0"+this.value;">
                  <input type="number" class="validation form-control form-control-sm col-md-5" min="0000" max="2100" placeholder="????????" id="byear" required="required">
              </div>
              <label class="error-message"  id="error-messagebdate">?????????????? ??????????? ??????????????</label>
            </div> 
            </div>
            <div class="row">  
              <div class="col-md-4">
                <label class="label_pers_page"><i class="fas fa-signature"></i>?????????? (??????????????) </label>
                <input type="text" class="validation form-control form-control-sm" id="o_fname_arm" required />
                <label class="error-message"  id="error-messageo_fname_arm">?????????????? ????????????</label>
              </div>
              <div class="col-md-4">
                <label class="label_pers_page"><i class="fas fa-signature"></i>???????????????? (??????????????) </label>
                <input type="text" class="validation form-control form-control-sm" id="o_lname_arm" required />
                <label class="error-message"  id="error-messageo_lname_arm">?????????????? ??????????????????</label>
              </div>
              <div class="col-md-4">
                <label class="label_pers_page"><i class="fas fa-signature"></i>?????????????????? (??????????????) </label>
                <input type="text" class="validation form-control form-control-sm" id="o_mname_arm"  />
              </div>   

              <div class="col-md-4">
                <label class="label_pers_page"><i class="fas fa-signature"></i>?????????? (??????????????????) </label>
                <input type="text" class="validation form-control form-control-sm" id="o_fname_eng" required />
                <label class="error-message"  id="error-messageo_fname_eng">?????????????? ????????????</label>
              </div>
              <div class="col-md-4">
                <label class="label_pers_page"><i class="fas fa-signature"></i>???????????????? (??????????????????) </label>
                <input type="text" class="validation form-control form-control-sm" id="o_lname_eng" required />
                <label class="error-message" id="error-messageo_lname_eng">?????????????? ??????????????????</label>
              </div>
              <div class="col-md-4">
                <label class="label_pers_page"><i class="fas fa-signature"></i>?????????????????? (??????????????????) </label>
                <input type="text" class="form-control form-control-sm" id="o_mname_eng" />
              </div>   

     

            

            <div class="col-md-4">
                <label class="label_pers_page"><i class="fas fa-passport"></i>?????????????????????????????? </label>
                ' . $opt_out_citizen . '
                <label class="error-message" id="error-messageselect_out_citizenship">???????????? ????????????????????????????????</label>
            </div>   
             <div class="col-md-4">
                <label class="label_pers_page"><i class="fas fa-home"></i>?????????????? ???????????????????? ?????????? </label>
                ' . $opt_out_res . '
                <label class="error-message" id="error-messageselect_out_res">???????????? ????????????</label>
            </div>   

            <div class="col-md-4">
              <label class="label_pers_page"><i class="fas fa-venus-mars"></i>????????</label>
              <select class="validation form-control form-control-sm" id="out_gender" required>
                <option selected disabled hidden  value="0">?????????? ????????</option>
                <option value="1">????????????</option>
                <option value="2">????????????</option>
              </select>
              <label class="error-message" id="error-messageout_gender">???????????? ????????</label>
            </div>
       

            </div>

             
            </div>
          
         
        </div> 
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">????????????????</button>
          <button id="save_open" name="save_and_open" class="btn btn-primary save_open_close">???????????????? ?? ?????????????????? ?????? ??????</button>
          <button id="save_close" name="save_close" class="btn btn-success save_open_close" form="lawyer">???????????????? ?? ??????????</button>
        </div>
      </div>
    </div>
    ';

		echo $modal_out;

	}

	//insert out member process

	if (isset($_POST['save_open_close'])) {
		$command = $_POST['save_open_close'];
		$case_id = $_POST['modal_case_id'];
		$o_fname_arm = $_POST['o_fname_arm'];
		$o_lname_arm = $_POST['o_lname_arm'];
		$o_mname_arm = $_POST['o_mname_arm'];
		$o_fname_eng = $_POST['o_fname_eng'];
		$o_lname_eng = $_POST['o_lname_eng'];
		$o_mname_eng = $_POST['o_mname_eng'];
		$role = $_POST['select_out_role'];
		$citizenship = $_POST['select_out_citizenship'];
		$res = $_POST['select_out_res'];
		$bday = $_POST['bday'];
		$bmonth = $_POST['bmonth'];
		$byear = $_POST['byear'];
		$sex = $_POST['out_gender'];

		$sql_out_member = "INSERT INTO `tb_members`(`case_id`, `f_name_arm`, `f_name_eng`, `l_name_arm`, `l_name_eng`, `m_name_arm`, `m_name_eng`, `b_day`, `b_month`, `b_year`, `sex`, `citizenship`, `residence`, `role`) VALUES ('$case_id', '$o_fname_arm', '$o_fname_eng', '$o_lname_arm', '$o_lname_eng', '$o_mname_arm', '$o_mname_eng', '$bday', '$bmonth', '$byear', '$sex', '$citizenship', '$res', '$role')";

		if ($conn->query($sql_out_member) === TRUE) {
			//  header('location: ../user.php?page=cases&homepage=case_page&case='.$case_id);

			if ($command === 'save_open') {
				$resp = array("out_member_save_and_open" => $case_id);
				echo json_encode($resp);
			}
			if ($command === 'save_close') {
				echo 'saved_close';
			}
		} else {
			echo "Error: " . $sql_out_member . "<br>" . $conn->error;
		}


	}


	######################################################################################

	// EDIT family member out of application

	if (isset($_POST['mo_edit_info'])) {
		$modal_out_edit = '';

		$pers_id = $_POST['mo_edit_info'];
		$case_id = $_POST['case_mo'];

		$sql_mo = "SELECT * FROM tb_members WHERE member_id = $pers_id";
		$result_mo = $conn->query($sql_mo);


		if ($result_mo->num_rows > 0) {
			$row_mo = $result_mo->fetch_assoc();
			$f_name_arm = $row_mo['f_name_arm'];
			$f_name_eng = $row_mo['f_name_eng'];
			$l_name_arm = $row_mo['l_name_arm'];
			$l_name_eng = $row_mo['l_name_eng'];
			$m_name_arm = $row_mo['m_name_arm'];
			$m_name_eng = $row_mo['m_name_eng'];
			$bday = $row_mo['b_day'];
			$bmonth = $row_mo['b_month'];
			$byear = $row_mo['b_year'];
			$gender = $row_mo['sex'];
			$citizen_id = $row_mo['citizenship'];
			$res_id = $row_mo['residence'];
			$mo_role = $row_mo['role'];

			$sql_out_citizenship = "SELECT * FROM tb_country";
			$result_out_citizenship = $conn->query($sql_out_citizenship);
			$result_out_res = $conn->query($sql_out_citizenship);
			$sql_out_role = "SELECT * FROM tb_role WHERE role_id != 1";
			$res_role = $conn->query($sql_out_role);


			$opt_mo_role = '<select name="select_mo_role" id="select_mo_role" class="form-control form-control-sm">';
			while ($row_mo_role = mysqli_fetch_array($res_role)) {

				if ($row_mo_role['role_id'] == $mo_role) {
					$opt_mo_role .= "<option selected=\"selected\" value=" . $row_mo_role['role_id'] . ">" . $row_mo_role['der'] . "</option>";
				} else {
					$opt_mo_role .= "<option value=" . $row_mo_role['role_id'] . ">" . $row_mo_role['der'] . "</option>";
				}
			}
			$opt_mo_role .= "</select>";


			$opt_mo_cit = '<select name="select_mo_cit" id="select_mo_cit" class="form-control form-control-sm">';
			while ($row_mo_cit = mysqli_fetch_array($result_out_citizenship)) {

				if ($row_mo_cit['country_id'] == $citizen_id) {
					$opt_mo_cit .= "<option selected=\"selected\" value=" . $row_mo_cit['country_id'] . ">" . $row_mo_cit['country_arm'] . "</option>";
				} else {
					$opt_mo_cit .= "<option value=" . $row_mo_cit['country_id'] . ">" . $row_mo_cit['country_arm'] . "</option>";
				}
			}
			$opt_mo_cit .= "</select>";

			$opt_mo_res = '<select name="select_mo_res" id="select_mo_res" class="form-control form-control-sm">';
			$opt_mo_res .= "<option selected=\"selected\" disabled=\"disabled\" hidden=\"hidden\">" . "?????????? ????????????" . "</option>";
			while ($row_mo_res = mysqli_fetch_array($result_out_res)) {

				if ($row_mo_res['country_id'] == $res_id) {
					$opt_mo_res .= "<option selected=\"selected\" value=" . $row_mo_res['country_id'] . ">" . $row_mo_res['country_arm'] . "</option>";
				} else {

					$opt_mo_res .= "<option value=" . $row_mo_res['country_id'] . ">" . $row_mo_res['country_arm'] . "</option>";
				}
			}
			$opt_mo_res .= "</select>";

			$opt_sex = '<select name="select_mo_gender" id="select_mo_gender" class="form-control form-control-sm">';

			if ($gender == 1) {
				$opt_sex .= "<option selected=\"selected\" value='1'>" . "????????????" . "</option>" .
					"<option value='2'>" . '????????????' . "</option>";

			} elseif ($gender == 2) {
				$opt_sex .= "<option selected=\"selected\" value='2'>" . "????????????" . "</option>" .
					"<option value='1'>" . '????????????' . "</option>";

			} else {
				$opt_sex .= "<option selected=\"selected\" disabled=\"disabled\" hidden=\"hidden\">" . "?????????? ????????" . "</option>" .
					"<option value='1'>" . "????????????" . "</option>" .
					"<option value='2'>" . "????????????" . "</option>";

			}
			$opt_sex .= '</select>';

		}


		$modal_out_edit = '<div class="modal-dialog modal-xl">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">???????????????? ???????????? ?????????? ???????????????? ???????????? ??????????????????</h5>
          <button type="button" class="close" data-dismiss="modal">??</button>
          
        </div>
        <div class="modal-body">
        <form method="POST" action="config/config.php" id="out_member_edit_modal">
         
          <input type="hidden" value="' . $pers_id . '" name="mo_person" />
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-4">
                <label class="label_pers_page"><i class="fas fa-house-user"></i> ???????? ??? </label>
                <input type="text" class="form-control form-control-sm" name="case_mo" value="' . $case_id . '" readonly />
              </div>
              <div class="col-md-4">
              <label class="label_pers_page"><i class="fas fa-link"></i> ???????? ?????????????????????? ?????? </label>
              ' . $opt_mo_role . '
              </div>
              <div class="col-md-4">
              <label class="label_pers_page"><i class="fas fa-calendar-day"></i> ?????????????? ??????????????</label>
              <div class="form-inline">
                  <input type="number" class="form-control form-control-sm col-md-3 mr-2" min="00" minlength="2" max="31" placeholder="????" name="bday" onchange="if(parseInt(this.value,10)<10>1)this.value="0"+this.value;" value="' . $bday . '">
                  <input type="number" class="form-control form-control-sm col-md-3 mr-2" min="00" minlength="2" max="12" placeholder="????????" name="bmonth" onchange="if(parseInt(this.value,10)<10>1)this.value="0"+this.value;" value="' . $bmonth . '">
                  <input type="number" class="form-control form-control-sm col-md-5" min="0000" max="2100" placeholder="????????" name="byear" required="required" value="' . $byear . '">
              </div>
            </div> 
            </div>
            <div class="row">  
              <div class="col-md-4">
                <label class="label_pers_page"><i class="fas fa-signature"></i>?????????? (??????????????) </label>
                <input type="text" class="form-control form-control-sm" name="o_fname_arm" required value="' . $f_name_arm . '" />
              </div>
              <div class="col-md-4">
                <label class="label_pers_page"><i class="fas fa-signature"></i>???????????????? (??????????????) </label>
                <input type="text" class="form-control form-control-sm" name="o_lname_arm" required value="' . $l_name_arm . '" />
              </div>
              <div class="col-md-4">
                <label class="label_pers_page"><i class="fas fa-signature"></i>?????????????????? (??????????????) </label>
                <input type="text" class="form-control form-control-sm" name="o_mname_arm" value="' . $m_name_arm . '" />
              </div>   

              <div class="col-md-4">
                <label class="label_pers_page"><i class="fas fa-signature"></i>?????????? (??????????????????) </label>
                <input type="text" class="form-control form-control-sm" name="o_fname_eng" required value="' . $f_name_eng . '" />
              </div>
              <div class="col-md-4">
                <label class="label_pers_page"><i class="fas fa-signature"></i>???????????????? (??????????????????) </label>
                <input type="text" class="form-control form-control-sm" name="o_lname_eng" required value="' . $l_name_eng . '" />
              </div>
              <div class="col-md-4">
                <label class="label_pers_page"><i class="fas fa-signature"></i>?????????????????? (??????????????????) </label>
                <input type="text" class="form-control form-control-sm" name="o_mname_eng" value="' . $m_name_eng . '" />
              </div>   

     

            

            <div class="col-md-4">
                <label class="label_pers_page"><i class="fas fa-passport"></i>?????????????????????????????? </label>
                ' . $opt_mo_cit . '
            </div>   
             <div class="col-md-4">
                <label class="label_pers_page"><i class="fas fa-home"></i>?????????????? ???????????????????? ?????????? </label>
                ' . $opt_mo_res . '
            </div>   

            <div class="col-md-4">
              <label class="label_pers_page"><i class="fas fa-venus-mars"></i>????????</label>
                ' . $opt_sex . '
            </div>
       

            </div>

             
            </div>
          
         
        </div> 
        <div class="modal-footer">
         <input type="submit" name="update_close" class="btn btn-success" form="out_member_edit_modal" value="???????????????? ?? ??????????" />
        </div>
      </div>
      </form>
    </div>
    ';

		echo $modal_out_edit;

	}

	//edit out member process

	if (isset($_POST['update_close'])) {

		$case_id = $_POST['case_mo'];
		$mem_id = $_POST['mo_person'];
		$o_fname_arm = $_POST['o_fname_arm'];
		$o_lname_arm = $_POST['o_lname_arm'];
		$o_mname_arm = $_POST['o_mname_arm'];
		$o_fname_eng = $_POST['o_fname_eng'];
		$o_lname_eng = $_POST['o_lname_eng'];
		$o_mname_eng = $_POST['o_mname_eng'];
		$role = $_POST['select_mo_role'];
		$citizenship = $_POST['select_mo_cit'];
		$res = $_POST['select_mo_res'];
		$bday = $_POST['bday'];
		$bmonth = $_POST['bmonth'];
		$byear = $_POST['byear'];
		$sex = $_POST['select_mo_gender'];

		$sql_out_member_update = "UPDATE `tb_members` SET 
     `f_name_arm`='$o_fname_arm',
     `f_name_eng`='$o_fname_eng',
     `l_name_arm`='$o_lname_arm',
     `l_name_eng`='$o_lname_eng',
     `m_name_arm`= NULLIF('$o_mname_arm', ''),
     `m_name_eng`= NULLIF('$o_mname_eng', ''),
     `b_day`=  NULLIF('$bday', ''),
     `b_month`= NULLIF('$bmonth', ''),
     `b_year`= NULLIF('$byear', ''),
     `sex`='$sex',
     `citizenship`='$citizenship',
     `residence`= NULLIF('$res', ''), 
     `role`='$role' 
     WHERE member_id = $mem_id";

		if ($conn->query($sql_out_member_update) === TRUE) {
			header('location: ../user.php?active_tab=familyTab&page=cases&homepage=case_page&case=' . $case_id);
		} else {
			echo "Error: " . $sql_out_member_update . "<br>" . $conn->error;
		}


	}

	######################################################################################

	// VIEW family member out of application

	if (isset($_POST['mo_view_info'])) {
		$modal_out_edit = '';

		$pers_id = $_POST['mo_view_info'];
		$case_id = $_POST['case_mo'];

		$sql_mo = "SELECT a.member_id, a.case_id, a.f_name_arm, a.f_name_eng, a.l_name_arm, a.l_name_eng, a.m_name_arm, a.m_name_eng, a.b_day, a.b_month, a.b_year, a.sex, a.citizenship, a.residence, a.role, b.role_id, b.der, c.country_id, c.country_arm as CITIZEN, d.country_arm AS RES FROM tb_members a INNER JOIN tb_role b ON a.role = b.role_id LEFT JOIN tb_country c ON a.citizenship = c.country_id LEFT JOIN tb_country d ON a.residence = d.country_id WHERE member_id = $pers_id";
		$result_mo = $conn->query($sql_mo);


		if ($result_mo->num_rows > 0) {
			$row_mo = $result_mo->fetch_assoc();
			$f_name_arm = $row_mo['f_name_arm'];
			$f_name_eng = $row_mo['f_name_eng'];
			$l_name_arm = $row_mo['l_name_arm'];
			$l_name_eng = $row_mo['l_name_eng'];
			$m_name_arm = $row_mo['m_name_arm'];
			$m_name_eng = $row_mo['m_name_eng'];
			$bday = $row_mo['b_day'];
			$bmonth = $row_mo['b_month'];
			$byear = $row_mo['b_year'];
			$gender = $row_mo['sex'];
			$citizen_id = $row_mo['citizenship'];
			$res_id = $row_mo['residence'];
			$mo_role = $row_mo['role'];
			$mo_role_text = $row_mo['der'];
			$citizen_text = $row_mo['CITIZEN'];
			$res_text = $row_mo['RES'];


			$opt_mo_role = "";
			if ($mo_role > 0) {
				$opt_mo_role = '<input type="text" class="form-control form-control-sm" value="' . $mo_role_text . '" readonly/>';
			}

			$opt_mo_cit = "";
			if ($citizen_id > 0) {
				$opt_mo_cit = '<input type="text" class="form-control form-control-sm" value="' . $citizen_text . '" readonly/>';
			}

			$opt_mo_res = "";
			if ($res_id > 0) {
				$opt_mo_res = '<input type="text" class="form-control form-control-sm" value="' . $res_text . '" readonly/>';
			}


			if ($gender == 1) {
				$opt_sex = '<input type="text" class="form-control form-control-sm" value="????????????" readonly/>';

			}
			if ($gender == 2) {
				$opt_sex = '<input type="text" class="form-control form-control-sm" value="????????????" readonly/>';
			}


		}


		$modal_out_edit = '<div class="modal-dialog modal-xl">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">???????????????? ?????????????? ???????????????????? ???????????????? ???????????? ?????????????????? ????????????</h5>
          <button type="button" class="close" data-dismiss="modal">??</button>
          
        </div>
        <div class="modal-body">
               <input type="hidden" value="' . $pers_id . '" name="mo_person" />
    <div class="col-md-12">
            <div class="row">
                        <div class="col-md-4">
                          <label class="label_pers_page"><i class="fas fa-house-user"></i> ???????? ??? </label>
                          <input type="text" class="form-control form-control-sm" name="case_mo" value="' . $case_id . '" readonly/>
                        </div>
                        <div class="col-md-4">
                        <label class="label_pers_page"><i class="fas fa-link"></i> ???????? ?????????????????????? ?????? </label>
                        ' . $opt_mo_role . '
                        </div>
                        <div class="col-md-4">
                                    <label class="label_pers_page"><i class="fas fa-calendar-day"></i> ?????????????? ??????????????</label>
                                    <div class="form-inline">
                                        <input type="number" class="form-control form-control-sm col-md-3 mr-2" min="00" minlength="2" max="31" placeholder="????" name="bday" onchange="if(parseInt(this.value,10)<10>1)this.value="0"+this.value;" value="' . $bday . '" readonly>
                                        <input type="number" class="form-control form-control-sm col-md-3 mr-2" min="00" minlength="2" max="12" placeholder="????????" name="bmonth" onchange="if(parseInt(this.value,10)<10>1)this.value="0"+this.value;" value="' . $bmonth . '" readonly>
                                        <input type="number" class="form-control form-control-sm col-md-5" min="0000" max="2100" placeholder="????????" name="byear" required="required" value="' . $byear . '" readonly>
                                    </div>
                        </div> 
            </div>
            <div class="row">  
                      <div class="col-md-4">
                        <label class="label_pers_page"><i class="fas fa-signature"></i>?????????? (??????????????) </label>
                        <input type="text" class="form-control form-control-sm" name="o_fname_arm" required value="' . $f_name_arm . '" readonly/>
                      </div>
                      <div class="col-md-4">
                        <label class="label_pers_page"><i class="fas fa-signature"></i>???????????????? (??????????????) </label>
                        <input type="text" class="form-control form-control-sm" name="o_lname_arm" required value="' . $l_name_arm . '" readonly/>
                      </div>
                      <div class="col-md-4">
                        <label class="label_pers_page"><i class="fas fa-signature"></i>?????????????????? (??????????????) </label>
                        <input type="text" class="form-control form-control-sm" name="o_mname_arm" value="' . $m_name_arm . '" readonly/>
                      </div>   

                      <div class="col-md-4">
                        <label class="label_pers_page"><i class="fas fa-signature"></i>?????????? (??????????????????) </label>
                        <input type="text" class="form-control form-control-sm" name="o_fname_eng" required value="' . $f_name_eng . '" readonly/>
                      </div>
                      <div class="col-md-4">
                        <label class="label_pers_page"><i class="fas fa-signature"></i>???????????????? (??????????????????) </label>
                        <input type="text" class="form-control form-control-sm" name="o_lname_eng" required value="' . $l_name_eng . '" readonly/>
                      </div>
                      <div class="col-md-4">
                        <label class="label_pers_page"><i class="fas fa-signature"></i>?????????????????? (??????????????????) </label>
                        <input type="text" class="form-control form-control-sm" name="o_mname_eng" value="' . $m_name_eng . '" readonly/>
                      </div>   
                      <div class="col-md-4">
                          <label class="label_pers_page"><i class="fas fa-passport"></i>?????????????????????????????? </label>
                          ' . $opt_mo_cit . '
                      </div>   
                      <div class="col-md-4">
                          <label class="label_pers_page"><i class="fas fa-home"></i>?????????????? ???????????????????? ?????????? </label>
                          ' . $opt_mo_res . '
                      </div>   
                      <div class="col-md-4">
                          <label class="label_pers_page"><i class="fas fa-venus-mars"></i>????????</label>
                          ' . $opt_sex . '
                      </div>
            </div>

             
     </div>

     
  </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">??????????</button>
              <a href="config/makepdf.php?non_family_person=' . $pers_id . '" class="btn btn-primary">PDF</a>
            </div>
      </div>
      
    </div>';

		echo $modal_out_edit;

	}
	###################

	// DELETE family member out of application

	if (isset($_POST['mo_del_info'])) {
		$modal_out_del = '';

		$pers_id = $_POST['mo_del_info'];
		$case_id = $_POST['case_mo'];

		$sql_mo = "SELECT a.member_id, a.case_id, a.f_name_arm, a.f_name_eng, a.l_name_arm, a.l_name_eng, a.m_name_arm, a.m_name_eng, a.b_day, a.b_month, a.b_year, a.sex, a.citizenship, a.residence, a.role, b.role_id, b.der, c.country_id, c.country_arm as CITIZEN, d.country_arm AS RES FROM tb_members a INNER JOIN tb_role b ON a.role = b.role_id LEFT JOIN tb_country c ON a.citizenship = c.country_id LEFT JOIN tb_country d ON a.residence = d.country_id WHERE member_id = $pers_id";
		$result_mo = $conn->query($sql_mo);


		if ($result_mo->num_rows > 0) {
			$row_mo = $result_mo->fetch_assoc();
			$f_name_arm = $row_mo['f_name_arm'];
			$f_name_eng = $row_mo['f_name_eng'];
			$l_name_arm = $row_mo['l_name_arm'];
			$m_name_arm = $row_mo['m_name_arm'];
			$bday = $row_mo['b_day'];
			$bmonth = $row_mo['b_month'];
			$byear = $row_mo['b_year'];
			$gender = $row_mo['sex'];
			$citizen_id = $row_mo['citizenship'];
			$res_id = $row_mo['residence'];
			$mo_role = $row_mo['role'];
			$mo_role_text = $row_mo['der'];
			$citizen_text = $row_mo['CITIZEN'];
			$res_text = $row_mo['RES'];
		}


		$modal_out_del = '<div class="modal-dialog modal-confirm modal-lg">
    <div class="modal-content">
      <div class="modal-header flex-column">
        <div class="icon-box">
          <i class="fa fa-trash-alt" style="color: #f15e5e; font-size: 46px; display: inline-block; margin-top: 13px;"></i>
        </div>            
        <h4 class="modal-title w-100">???????? ?????????????????????? ???? ???????????????? ?????????????? ??????????</h4>  
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
     <form action="config/config.php" method="POST" id="delete_person_mo">
      <div class="modal-body">
      <input type="hidden" value="' . $pers_id . '" name="delete_person_id"/>
      <input type="hidden" value="' . $case_id . '" name="case"/>
       <div class="col-md-12">
       
           <div class="row">
            <div class="col-md-4"> 
             <label class="label_pers_page"> ?????????? </label>
             <input type="text" class="form-control" value="' . $f_name_arm . '">
            </div>
            <div class="col-md-4"> 
             <label class="label_pers_page"> ???????????????? </label>
             <input type="text" class="form-control" value="' . $l_name_arm . '">
            </div>
            <div class="col-md-4"> 
             <label class="label_pers_page"> ?????????????????? </label>
             <input type="text" class="form-control" value="' . $m_name_arm . '">
            </div>
           </div>
       </div> 
        <p>???????????????????????????? ?????????????????? ???? <br> ???????????????????? ?????????????? ?????????????? ???????? ?????????????????? ?????????????????? ?????????????????? ??????????????????????
        </p>

      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-danger" name="mo_delete_pers" form="delete_person_mo">Delete</button>
      </div>
      </form>
    </div>
  </div>
  ';

		echo $modal_out_del;

	}

	if (isset($_POST['mo_delete_pers'])) {

		$delete_person_id = $_POST['delete_person_id'];
		$case_id = $_POST['case'];

		$sql_delete_mo_person = "DELETE FROM `tb_members` WHERE member_id = $delete_person_id";

		if ($conn->query($sql_delete_mo_person) === TRUE) {
			header('location: ../user.php?active_tab=familyTab&page=cases&homepage=case_page&case=' . $case_id);
		} else {
			echo "Error: " . $sql_delete_mo_person . "<br>" . $conn->error;
		}

	}
	###################

	// order to accept at reception center operator -> devhead

	if (isset($_POST['order'])) {
		$modal_order = '';

		$today = date('Y-m-d');
		$case_id = $_POST['order'];
		$msg_order = "?????????????? ???? ???????????????? ????????????????";


		$sql_order_case = "SELECT a.case_id, b.f_name_arm, b.personal_id, b.l_name_arm, b.sex, b.m_name_arm, b.citizenship, b.role, b.b_day, b.b_month, b.b_year, c.country_arm, d.der 
FROM tb_case a 
INNER JOIN tb_person b ON a.case_id = b.case_id 
LEFT JOIN  tb_country c ON b.citizenship = c.country_id
LEFT JOIN  tb_role d ON b.role = d.role_id
WHERE a.case_id = $case_id";
		$result_order_case = $conn->query($sql_order_case);
		$result_family = $conn->query($sql_order_case);

		if ($result_order_case->num_rows > 0) {
			$row = $result_order_case->fetch_assoc();
			$f_name_arm = $row['f_name_arm'];
			$l_name_arm = $row['l_name_arm'];
			$m_name_arm = $row['m_name_arm'];
			$bday = $row['b_day'];
			$bmonth = $row['b_month'];
			$byear = $row['b_year'];
			$gender = $row['sex'];
			$citizen_id = $row['citizenship'];
			$role = $row['role'];
			$role_text = $row['der'];
			$citizen_text = $row['country_arm'];

			$fam_head = '';
			$head_bday = '';
			$head_citizenship = '';

			if ($row['role'] == 1) {
				$fam_head_id = $row['personal_id'];
				$fam_head = $row['f_name_arm'] . ' ' . $row['l_name_arm'];
				$head_bday = $row['b_day'] . '.' . $row['b_month'] . '.' . $row['b_year'] . '' . "??.";
				$head_citizenship = $row['country_arm'];
			}

		}

		$sql_reciver = "SELECT * FROM users WHERE user_type = 'devhead' AND user_status = 1";
		$result_resiver = $conn->query($sql_reciver);

		if ($result_resiver->num_rows > 0) {
			$reciver_row = $result_resiver->fetch_assoc();
			$res_id = $reciver_row['id'];
			$res_name = $reciver_row['f_name'] . ' ' . $reciver_row['l_name'];
		}

		$check_orders = "SELECT * FROM tb_orders a INNER JOIN tb_checkin b ON a.order_id = b.order_id WHERE a.case_id = $case_id and a.order_status = 1";
		$result_check_orders = $conn->query($check_orders);


		if ($result_check_orders->num_rows == 0) {

			$modal_order = '<div class="modal-dialog modal-xl">
        
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title"><i class="far fa-paper-plane first_menu"></i> ?????????????? ?????????????? </h5>
              <button type="button" class="close" data-dismiss="modal">??</button>
              
            </div>
            <div class="modal-body">
              <form method="POST" action="config/config.php" id="order" enctype="multipart/form-data">
              <div class="col-md-12">
                

                <div class="row">
                <div class="col-md-10">
                  <label class="label_pers_page">??????</label>
                  <input type="text" class="form-control form-control-sm" value="' . $res_name . '" readonly/>
                </div>
                  <div class="col-md-2">
                    <label class="label_pers_page"> ???????? # </label>
                    <input type="text" class="form-control form-control-sm" value="' . $case_id . '" name="order_case" readonly />
                  </div>
                  <div class="col-md-4">
                    <label class="label_pers_page"> ???????????? ?? </label>
                    <input type="text" class="form-control form-control-sm" value="' . $fam_head . '" readonly />
                  </div>

                  <div class="col-md-2">
                    <label class="label_pers_page"> ?????????? </label>
                    <input type="text" class="form-control form-control-sm" value="' . $head_bday . '" readonly />
                  </div>

                   <div class="col-md-3">
                    <label class="label_pers_page"> ???????????????????????????????? </label>
                    <input type="text" class="form-control form-control-sm" value="' . $head_citizenship . '" readonly />
                  </div>

                  <div class="col-md-3">
                        <label class="label_pers_page">???????????????????? ?????????????? </label>
                        <input type="date" name="order_date" class="form-control form-control-sm" value="' . $today . '" readonly />
                  </div>    

                  <hr>';

			if ($result_family->num_rows > 1) {
				$modal_order .= '
             
                 <h5 class="sub_title">???????????????? ??????????</h5>
                      <div class="col-md-12">
                      <table class="table">
                        <tr>
                          <th class="label_pers_page" >????????</th>
                          <th class="label_pers_page">??????????</th>
                          <th class="label_pers_page">????????????????</th>
                          <th class="label_pers_page">??????????????????</th>                                   
                          <th class="label_pers_page">????????</th>
                          <th class="label_pers_page">?????????????? ??????????????</th>
                        </tr>';

				while ($row_fame = $result_family->fetch_assoc()) {
					$anun = '';
					$azganun = '';
					$hayranun = '';
					$sex = '';
					$bday = '';
					$frole = '';


					if ($row_fame['role'] != 1) {
						$anun = $row_fame['f_name_arm'];
						$azganun = $row_fame['l_name_arm'];
						$hayranun = $row_fame['m_name_arm'];
						$bday = $row_fame['b_day'] . '.' . $row_fame['b_month'] . '.' . $row_fame['b_year'];
						$frole = $row_fame['der'];
						if ($row_fame['sex'] == 1) {
							$sex = '????????????';
						} else {
							$sex = '????????????';
						}

						$modal_order .= '
                          
                         <tr>
                            <td>' . $frole . '</td>
                            <td>' . $anun . '</td>
                            <td>' . $azganun . '</td>
                            <td>' . $hayranun . '</td>
                            <td>' . $sex . '</td>
                            <td>' . $bday . '</td>          
                          </tr>';
					}
				}

				$modal_order .= '
                              </table>
                              <hr>
                              </div>';
			}


			$modal_order .= '              
                     
               
                      <div class="col-md-12">
                          <label class="label_pers_page">????????????????????????????????</label>
                          <textarea class="form-control" rows="3" name="comment_order">' . $msg_order . '</textarea>
                      </div>       

                            </div>
                          </div>
                 
                     
                      </div>
                        <div class="modal-footer">
                              <button type="button" class="btn btn-default" data-dismiss="modal">????????????????</button>
                              <input type="submit" class="btn btn-success" form="order" name="save_order" value="???????????????? ??????????????????" />
                              </form>
                        </div>
                      </div>
                      
                    </div>';
		} else {

			$user_id = $_SESSION['user_id'];
			$uf_name = $_SESSION['user_fName'];
			$ul_name = $_SESSION['user_lName'];

			$row_orders = $result_check_orders->fetch_assoc();
			$order_id = $row_orders['order_id'];
			$order_date = $row_orders['date'];
			$order_date_formated = date('d.m.Y', strtotime($order_date));


			$sql_chekin = "SELECT * FROM tb_checkin a INNER JOIN tb_person b ON a.personal_id = b.personal_id INNER JOIN tb_role c ON b.role = c.role_id INNER JOIN tb_doss d ON a.doss_id = d.doss_id WHERE a.order_id = $order_id";
			$result_chekins = $conn->query($sql_chekin);

			$table = '
        <table class="table">
          <tr>
            <th class="label_pers_page">????????</th>
            <th class="label_pers_page">?????????????? ?????????????? ??.??.??.</th>
            <th class="label_pers_page">??????</th>
            <th class="label_pers_page">???????????????????? ??????????????</th>
            <th class="label_pers_page">????????????</th>
            <th class="label_pers_page">??????</th>
          </tr>

       ';

			while ($row_checked_family = $result_chekins->fetch_assoc()) {
				$cf_role = $row_checked_family['der'];
				$cf_name = $row_checked_family['f_name_arm'] . ' ' . $row_checked_family['l_name_arm'];
				$gender = '';
				$cf_sex = $row_checked_family['sex'];
				if ($cf_sex == 1) {
					$gedner = '????????????';
				} else {
					$gender = '????????????';
				}

				$thisyear = date('Y');
				$cf_b_year = $row_checked_family['b_year'];
				$cf_age = $thisyear - $cf_b_year;
				$cf_chekin_date = $row_checked_family['checkin_date'];
				$cf_chekin_date_formated = date('d.m.Y', strtotime($cf_chekin_date));
				$cf_room = $row_checked_family['room_num'];
				$cd_place = $row_checked_family['doss_type'];

				$table .= '
          
          <tr>
            <td>' . $cf_role . '</td>
            <td>' . $cf_name . '</td>
            <td>' . $gender . '</td>
            <td>' . $cf_chekin_date_formated . '</td>
            <td>' . $cf_room . '</td>
            <td>' . $cd_place . '</td>
          </tr>

        ';


			}

			$table .= '</table>';

			$modal_order .= '
        <div class="modal-dialog modal-xl">
          <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">?????????????? ???????????????????? ?????????????????????? ???????????????????? ???????????????????? ?????????????????????? ?????????????? ??????????</h5>
                <button type="button" class="close" data-dismiss="modal">??</button>
                
              </div>
              <div class="modal-body">
              <p class="label_pers_page">???????????????? ' . $uf_name . ' ' . ' ' . ' ' . $ul_name . ' </p> <br />
              <p class="label_pers_page">  
              ' . $fam_head . '-????  ' . $order_date_formated . ' -???? ???????????????????? ?? ?????? ' . $order_id . ' ????????????????, ?????? ???????????? ' . $cf_chekin_date_formated . ' ???????????????? ?? ???????????????????? ???????????? ?????????????????????? 
              </p>
              ' . $table . '


          

              </div>

              <div class="modal-footer">

                    <button type="button" class="btn btn-default" data-dismiss="modal">??????????</button>
              </div>
            </div>
            
          </div>';
		}

		echo $modal_order;

	}

	if (isset($_POST['save_order'])) {
		$case_id = $_POST['order_case'];
		$order_date = $_POST['order_date'];
		$order_status = '1';
		$comment_a_sign = $_POST['comment_order'];
		$sender = $_SESSION['user_id'];
		$reciver_id = '';
		$sql_reciver = "SELECT * FROM users WHERE user_type = 'devhead'";
		$resutl_reciver = $conn->query($sql_reciver);
		if ($resutl_reciver->num_rows > 0) {
			$row_r = $resutl_reciver->fetch_assoc();
			$reciver_id = $row_r['id'];
		}


		# Upload file


		$sql_order = "INSERT INTO `tb_orders`(`case_id`, `order_status`, `date`) VALUES ('$case_id','$order_status','$order_date')";

		if ($conn->query($sql_order) === TRUE) {
			$last_order_id = $conn->insert_id;

			$sql_insert_order_process = "INSERT INTO `tb_order_process`(`order_id`, `order_from`, `order_to`, `order_status`, `order_actual`, `order_comment`) VALUES ('$last_order_id', '$sender', '$reciver_id', '$order_status', '1', '$comment_a_sign')";

			if ($conn->query($sql_insert_order_process) === TRUE) {
				$last_order_process = $conn->insert_id;
				$sql_notify = "INSERT INTO `tb_notifications`(`comment_subject`, `comment_text`, `comment_status`, `comment_from`, `comment_to`, `case_id`, `order_id`, `note_type`) VALUES 
                 ('?????? ??????????????', NULLIF('$comment_a_sign', ''), '0', '$sender', '$reciver_id', '$case_id', '$last_order_id', '5')";

				if ($conn->query($sql_notify) === TRUE) {
					header('location: ../user.php?page=cases&homepage=case_page&case=' . $case_id);
				} else {
					echo "Error: " . $sql_notify . "<br>" . $conn->error;
				}

			} else {
				echo "Error: " . $sql_insert_order_process . "<br>" . $conn->error;
			}


		} else {
			echo "Error: " . $sql_order . "<br>" . $conn->error;
		}

	}

	###################


	//chekin modal

	if (isset($_POST['chekin_pers'])) {

		$person = $_POST['chekin_pers'];
		$order = $_POST['order_id'];
		$case_id = $_POST['case_id'];
		$number_of_person = $_POST['pers_num'];

		$chekin_modal = '';
		$single = '';
		$double = '';
		$family = '';
		$gender = '';
		$sql_person_to_checkin = "SELECT f_name_arm, l_name_arm, m_name_arm, sex, count(personal_id) AS PNUM FROM tb_person WHERE personal_id = $person";
		$result_person_to_checkin = $conn->query($sql_person_to_checkin);

		if ($result_person_to_checkin->num_rows > 0) {
			$row_person_to_checkin = $result_person_to_checkin->fetch_assoc();
			$fname = $row_person_to_checkin['f_name_arm'];
			$lname = $row_person_to_checkin['l_name_arm'];
			$mname = $row_person_to_checkin['m_name_arm'];
			$gender = $row_person_to_checkin['sex'];

		}

		$sql_free_doss = "SELECT a.room_num, max(case when a.doss_type='A' then a.doss_id end) A, max(case when a.doss_type='B' then a.doss_id end) B, max(case when a.doss_type='C' then a.doss_id end) C, max(case when a.doss_type='D' then a.doss_id end) D FROM tb_doss a WHERE a.doss_status = 0 GROUP BY room_num";
		$result_free_doss = $conn->query($sql_free_doss);

		//query single
		$sql_free_doss_single = "SELECT a.room_num, max(case when a.doss_type='A' then a.doss_id end) A FROM tb_doss a INNER JOIN tb_drooms b ON a.room_num = b.room_num WHERE a.doss_status = 0 AND b.capacity = 1 GROUP BY a.room_num";
		$result_free_doss_single = $conn->query($sql_free_doss_single);
		if ($result_free_doss_single->num_rows > 0) {
			while ($row_singles = $result_free_doss_single->fetch_assoc()) {
				$room_num_single = $row_singles['room_num'];
				$doss_1_A = '';
				if (!empty ($row_singles['A'])) {
					$doss_1_A = '<input type="radio" class="form-control form-control-sm check_icon" name="doss" value="' . $row_singles["A"] . '" />';
				}


				$single .= '
    <tr>
        <td style="font-size:0.9em; color: #324157; text-align: center; font-weight: bold;">' . $room_num_single . ' </td>
        <td> ' . $doss_1_A . ' </td>
    </tr>
  ';

			}
		}

		$sql_free_doss_double = '';
		$persons_in_case = "SELECT personal_id FROM tb_person WHERE case_id = $case_id";
		$result_in_case = $conn->query($persons_in_case);
		$id_array = array();
		while ($row_in_case = $result_in_case->fetch_assoc()) {
			$id_array[] = $row_in_case['personal_id'];
		}
		$checked_member = '';
		$check_if_checked = "SELECT * FROM tb_checkin a INNER JOIN tb_doss b ON a.doss_id = b.doss_id WHERE a.personal_id IN (" . implode(", ", $id_array) . ")";
		$result_if_checked = $conn->query($check_if_checked);
		if ($result_if_checked->num_rows > 0) {
			$row_result_if_checked = $result_if_checked->fetch_assoc();
			$checked_room = $row_result_if_checked['room_num'];
			$checked_member = '1';
		}


		if ($number_of_person == 2 && $checked_member == '1') {
			$sql_free_doss_double = "SELECT a.room_num, max(case when a.doss_type='A' then a.doss_id end) A, max(case when a.doss_type='B' then a.doss_id end) B FROM tb_doss a INNER JOIN tb_drooms b ON a.room_num = b.room_num WHERE a.doss_status = 0 AND b.room_num = $checked_room GROUP BY a.room_num";
		} else {
			$sql_free_doss_double = "SELECT a.room_num, max(case when a.doss_type='A' then a.doss_id end) A, max(case when a.doss_type='B' then a.doss_id end) B FROM tb_doss a INNER JOIN tb_drooms b ON a.room_num = b.room_num WHERE a.doss_status = 0 AND b.type = 'Double' GROUP BY a.room_num";
		}

		//query double

		$result_free_doss_double = $conn->query($sql_free_doss_double);
		if ($result_free_doss_double->num_rows > 0) {
			while ($row_double = $result_free_doss_double->fetch_assoc()) {
				$room_num_double = $row_double['room_num'];
				$doss_2_A = '';
				if (!empty ($row_double['A'])) {
					$doss_2_A = '<input type="radio" class="form-control form-control-sm check_icon" name="doss" value="' . $row_double["A"] . '" />';
				}

				$doss_2_B = '';
				if (!empty ($row_double['B'])) {
					$doss_2_B = '<input type="radio" class="form-control form-control-sm check_icon" name="doss" value="' . $row_double["B"] . '" ';
				}


				$double .= '
    <tr>
        <td style="font-size:0.9em; color: #324157; text-align: center; font-weight: bold;">' . $room_num_double . ' </td>
        <td> ' . $doss_2_A . ' </td>
        <td> ' . $doss_2_B . ' </td>
    </tr>
  ';

			}
		}


		//query family
		$sql_free_doss_family = "SELECT a.room_num, max(case when a.doss_type='A' then a.doss_id end) A, max(case when a.doss_type='B' then a.doss_id end) B, max(case when a.doss_type='C' then a.doss_id end) C, max(case when a.doss_type='D' then a.doss_id end) D FROM tb_doss a INNER JOIN tb_drooms b ON a.room_num = b.room_num WHERE a.doss_status = 0 AND b.type = 'Family'  GROUP BY a.room_num";
		$result_free_doss_family = $conn->query($sql_free_doss_family);

		if ($result_free_doss_family->num_rows > 0) {
			while ($row_family = $result_free_doss_family->fetch_assoc()) {
				$room_num_family = $row_family['room_num'];
				$doss_3_A = '';
				if (!empty ($row_family['A'])) {
					$doss_3_A = '<input type="radio" class="form-control form-control-sm check_icon" name="doss" value="' . $row_family["A"] . '" />';
				}

				$doss_3_B = '';
				if (!empty ($row_family['B'])) {
					$doss_3_B = '<input type="radio" class="form-control form-control-sm check_icon" name="doss" value="' . $row_family["B"] . '" ';
				}

				$doss_3_C = '';
				if (!empty ($row_family['C'])) {
					$doss_3_C = '<input type="radio" class="form-control form-control-sm check_icon" name="doss" value="' . $row_family["C"] . '" ';
				}

				$doss_3_D = '';
				if (!empty ($row_family['D'])) {
					$doss_3_D = '<input type="radio" class="form-control form-control-sm check_icon" name="doss" value="' . $row_family["D"] . '" ';
				}


				$family .= '
    <tr>
        <td style="font-size:0.9em; color: #324157; text-align: center; font-weight: bold;">' . $room_num_single . ' </td>
        <td> ' . $doss_3_A . ' </td>
        <td> ' . $doss_3_B . ' </td>
        <td> ' . $doss_3_C . ' </td>
        <td> ' . $doss_3_D . ' </td>
    </tr>
  ';

			}
		}


		$chekin_modal = '<div class="modal-dialog modal-xl">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-building"></i> <i class="fas fa-sign-in-alt"></i> ???????????????????? ???????????? ????????????????????</h5>
          <button type="button" class="close" data-dismiss="modal">??</button>
          
        </div>
        <div class="modal-body">
        <form method="POST" action="config/config.php" id="chekin_person">
        <p class="label_pers_page">?????????????????????? ??.??.??. </p> 
        <span class="label_pers_page">' . $fname . ' ' . $lname . ' ' . $mname . ' </span>
        <input type="hidden" name="personal_id" value="' . $person . '" />
        <input type="hidden" name="order_id" value="' . $order . '" />
        <input type="hidden" name="case_id" value="' . $case_id . '" />
        <input type="hidden" name="checkin_sex" value="' . $gender . '" />
        <input type="text" name="person_sum" value="' . $number_of_person . '" />
        <hr>
        
      
       <ul class="nav nav-tabs">
  <li class="active mr-2"><a data-toggle="tab" href="#home">????????????????</a></li>
  <li class="mr-2"><a data-toggle="tab" href="#menu1">??????????????????</a></li>
  <li><a data-toggle="tab" href="#menu2">??????????????????</a></li>
</ul>

<div class="tab-content">
  <div id="home" class="tab-pane active">
    <h5 class="sub_title mt-2">???????? ???????????????????? ???????? </h5>
          <table class="table table-bordered">
      <tr>
        <th class="room_num_th">???????????? #</th>
        <th><span class="bed_icon_2"><i class="fas fa-bed"></i> A </span></th>
      </tr>
      ' . $single . '
      </table>  
  </div>

  <div id="menu1" class="tab-pane fade">
    <h5 class="sub_title mt-2">???????? ???????????????????? ???????? </h5>
    <table class="table table-bordered">
      <tr style="text-align: center;">
        <th>???????????? #</th>
        <th><span class="bed_icon_2"><i class="fas fa-bed"></i> A </span></th>
        <th><span class="bed_icon_2"><i class="fas fa-bed"></i> B</th>     
      </tr>
      ' . $double . '
    </table>  

    
  </div>
  <div id="menu2" class="tab-pane fade">
   <h5 class="sub_title mt-2">???????? ???????????????????? ???????? </h5>
    <table class="table table-bordered">
      <tr style="text-align: center;">
        <th>???????????? #</th>
        <th><span class="bed_icon_2"><i class="fas fa-bed"></i> A </span></th>
        <th><span class="bed_icon_2"><i class="fas fa-bed"></i> B</th>
        <th><span class="bed_icon_2"><i class="fas fa-bed"></i> C</th>
        <th><span class="bed_icon_2"><i class="fas fa-bed"></i> D</th>
      </tr>
      ' . $family . '
    </table>  
    
  </div>
</div>



     
         
        </div> 
        <div class="modal-footer">
         <input type="submit" name="chekin_reg" class="btn btn-success" form="chekin_person" value="????????????????" />
        </div>
      </div>
      </form>
    </div>

  ';
		echo $chekin_modal;
	}

	if (isset($_POST['chekin_reg'])) {
		$order = $_POST['order_id'];
		$person = $_POST['personal_id'];
		$doss_num = $_POST['doss'];
		$chekin_date = date('Y-m-d');
		$case_id = $_POST['case_id'];
		$checkin_sex = $_POST['checkin_sex'];

		$query_room_num = "SELECT * FROM tb_drooms a INNER JOIN tb_doss b ON a.room_num = b.room_num WHERE b.doss_id = $doss_num";
		$result_query_room_num = $conn->query($query_room_num);
		if ($result_query_room_num->num_rows > 0) {
			$row_room_num = $result_query_room_num->fetch_assoc();
			$room_num_for_sex = $row_room_num['room_num'];
			$room_id_for_sex = $row_room_num['room_id'];
		} else {

		}


		$insert_chekin = "INSERT INTO `tb_checkin`(`checkin_date`, `personal_id`, `order_id`, `status`, `doss_id`) VALUES ('$chekin_date','$person','$order','1','$doss_num')";

		if ($conn->query($insert_chekin) === TRUE) {
			$sql_update_doss_status = "UPDATE tb_doss SET doss_status = 1, doss_sex = $checkin_sex WHERE doss_id = $doss_num";
			if ($conn->query($sql_update_doss_status) === TRUE) {

				$sql_update_address = "UPDATE tb_case SET RA_marz = '1', RA_community = '1001', RA_settlement = '1001083', RA_street = '????????????????????', RA_building = '29/1', RA_apartment = $doss_num";

				if ($conn->query($sql_update_address)) {
					$update_room_sex = "UPDATE tb_drooms SET room_sex = $checkin_sex WHERE room_id = $room_id_for_sex";

					if ($conn->query($update_room_sex) === TRUE) {
						header('location: ../user.php?page=cases&homepage=order_page&case=' . $case_id . '&order=' . $order);
					} else {
						echo "Error: " . $update_room_sex . "<br>" . $conn->error;
					}

				} else {
					echo "Error: " . $sql_update_address . "<br>" . $conn->error;
				}

			} else {
				echo "Error: " . $sql_update_doss_status . "<br>" . $conn->error;
			}
		} else {
			echo "Error: " . $insert_chekin . "<br>" . $conn->error;
		}
	}


	##############################################################
	// check out modal

	if (isset($_POST['check_out_pers'])) {

		$person = $_POST['check_out_pers'];
		$order = $_POST['order_id'];
		$case_id = $_POST['case_id'];

		$checkout_modal = '';

		$sql_person_to_checkout = "SELECT f_name_arm, l_name_arm, m_name_arm FROM tb_person WHERE personal_id = $person";
		$result_person_to_checkout = $conn->query($sql_person_to_checkout);

		if ($result_person_to_checkout->num_rows > 0) {
			$row_person_to_checkout = $result_person_to_checkout->fetch_assoc();
			$fname = $row_person_to_checkout['f_name_arm'];
			$lname = $row_person_to_checkout['l_name_arm'];
			$mname = $row_person_to_checkout['m_name_arm'];
		}

		$sql_doss_num = "SELECT * FROM `tb_checkin` WHERE personal_id = $person";
		$res_doss_num = $conn->query($sql_doss_num);

		if ($res_doss_num->num_rows > 0) {
			$row_doss_num = $res_doss_num->fetch_assoc();
			$doss_num = $row_doss_num['doss_id'];
		}

		$checkout_modal = '<div class="modal-dialog modal-xl">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"> <i class="fas fa-building"></i> <i class="fas fa-sign-out-alt"></i> ???????????????????? ???????????? ????????????????????</h5>
          <button type="button" class="close" data-dismiss="modal">??</button>
          
        </div>
        <div class="modal-body">
        <form method="POST" action="config/config.php" id="checkout_person">
        <p class="warning">???????? ?????????????????????? ???? ?????????? ????????</p> 
        <p class="warning2">' . $fname . ' ' . $lname . ' ' . $mname . ' </p>
        <input type="hidden" name="personal_id" value="' . $person . '" />
        <input type="hidden" name="order_id" value="' . $order . '" />
        <input type="hidden" name="case_id" value="' . $case_id . '" />
        <input type="hidden" name="doss_num" value="' . $doss_num . '" />
        <hr>
       

           
        </div> 
        <div class="modal-footer">
         <input type="submit" name="chekout_reg" class="btn btn-success" form="checkout_person" value="????????????????" />
        </div>
      </div>
      </form>
    </div>

  ';
		echo $checkout_modal;
	}

	if (isset($_POST['chekout_reg'])) {
		$order = $_POST['order_id'];
		$person = $_POST['personal_id'];
		$doss_num = $_POST['doss_num'];
		$chekout_date = date('Y-m-d');
		$case_id = $_POST['case_id'];

		$update_chekin = "UPDATE tb_checkin SET checkout_date = '$chekout_date', status='0' WHERE personal_id = $person";

		if ($conn->query($update_chekin) === TRUE) {
			$sql_update_doss_status = "UPDATE tb_doss SET doss_status = 0, doss_sex = 0 WHERE doss_id = $doss_num";

			if ($conn->query($sql_update_doss_status) === TRUE) {
				header('location: ../user.php?page=cases&homepage=order_page&case=' . $case_id . '&order=' . $order);
			} else {
				echo "Error: " . $sql_update_doss_status . "<br>" . $conn->error;
			}
		} else {
			echo "Error: " . $update_chekin . "<br>" . $conn->error;
		}
	}


	################################################################

	// order to accept at reception center devhead -> dorm

	if (isset($_POST['dev_approve_order'])) {
		$modal_order1 = '';
		$order_id = $_POST['dev_approve_order'];
		$case_id = $_POST['case_id_order'];

		$chk_process_status = "SELECT a.order_process_id, a.order_id, a.order_from, a.order_to, a.process_date, a.order_status, a.order_actual FROM tb_order_process a WHERE a.order_id = $order_id AND a.order_actual = 1";
		$res_check_process = $conn->query($chk_process_status);
		if ($res_check_process->num_rows > 0) {
			$row_chk_process = $res_check_process->fetch_assoc();
			$order_process_status = $row_chk_process['order_status'];

		}

		if ($order_process_status == 1) {
			$msg_order = "?????????????? ???? ?????????????????? ???????????????????? ?? ???????????????????? ???????????????????????? ????????????";
			$modal_title = "?????????????? ??????????????";
		}

		if ($order_process_status == 4) {
			$msg_order = "?? ???????????????? ?????? " . $order_id . " ?????????????? ???????????????????? ???????????????????? ??????????????????";
			$modal_title = "?????????????? ?????????????????? ??????????????";
		}


		$sql_reciver = "SELECT * FROM users WHERE user_type = 'dorm' AND user_status = 1";
		$result_resiver = $conn->query($sql_reciver);

		if ($result_resiver->num_rows > 0) {
			$reciver_row = $result_resiver->fetch_assoc();
			$res_id = $reciver_row['id'];
			$res_name = $reciver_row['f_name'] . ' ' . $reciver_row['l_name'];
		}


		$sql_order_head = "SELECT a.order_process_id, a.order_id, a.order_from, a.order_to, a.process_date, a.order_status, a.order_actual, a.order_comment, b.order_id, b.case_id, b.order_status, b.date AS ORDER_DATE, c.case_id, c.case_status, d.personal_id, d.f_name_arm, d.l_name_arm, d.m_name_arm, d.b_day, d.b_month, d.b_year, d.sex, d.citizenship, d.role, e.country_id, e.country_arm, f.role_id, f.der 
    FROM tb_order_process a 
    INNER JOIN tb_orders b ON a.order_id = b.order_id
    INNER JOIN tb_case c ON b.case_id = c.case_id
    INNER JOIN tb_person d ON b.case_id = d.case_id
    INNER JOIN tb_country e ON d.citizenship = e.country_id
    INNER JOIN tb_role f ON d.role = f.role_id
    WHERE a.order_id = $order_id AND d.role = 1";

		$result_order_1 = $conn->query($sql_order_head);

		$sql_family = "SELECT * FROM tb_person a INNER JOIN tb_role b ON a.role = b.role_id WHERE a.case_id = $case_id";
		$result_family = $conn->query($sql_family);

		if ($result_order_1->num_rows > 0) {
			$row = $result_order_1->fetch_assoc();
			$f_name_arm = $row['f_name_arm'];
			$l_name_arm = $row['l_name_arm'];
			$m_name_arm = $row['m_name_arm'];
			$bday = $row['b_day'];
			$bmonth = $row['b_month'];
			$byear = $row['b_year'];
			$gender = $row['sex'];
			$citizen_id = $row['citizenship'];
			$role = $row['role'];
			$role_text = $row['der'];
			$citizen_text = $row['country_arm'];
			$order_date = date('Y-m-d', strtotime($row['ORDER_DATE']));
			$fam_head = '';
			$head_bday = '';
			$head_citizenship = '';


			$fam_head_id = $row['personal_id'];
			$fam_head = $f_name_arm . ' ' . $l_name_arm;
			$head_bday = $row['b_day'] . '.' . $row['b_month'] . '.' . $row['b_year'] . '' . "??.";
			$head_citizenship = $row['country_arm'];


		}


		$modal_order1 = '<div class="modal-dialog modal-xl">
        
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title"><i class="far fa-paper-plane first_menu"></i> ' . $modal_title . ' </h5>
              <button type="button" class="close" data-dismiss="modal">??</button>
              
            </div>
            <div class="modal-body">
              <form method="POST" action="config/config.php" id="signed_order" enctype="multipart/form-data">
              <div class="col-md-12">
                
              <input type="hidden" name="order_process_status" value="' . $order_process_status . '" />
                <div class="row">
                <div class="col-md-8">
                  <label class="label_pers_page">??????</label>
                  <input type="text" class="form-control form-control-sm" value="' . $res_name . '" readonly/>
                </div>
                  <div class="col-md-2">
                    <label class="label_pers_page"> ?????????????? # </label>
                    <input type="text" class="form-control form-control-sm" value="' . $order_id . '" name="order_order_id" readonly />
                  </div>
                  <div class="col-md-2">
                    <label class="label_pers_page"> ???????? # </label>
                    <input type="text" class="form-control form-control-sm" value="' . $case_id . '" name="order_case" readonly />
                  </div>
                  <div class="col-md-4">
                    <label class="label_pers_page"> ???????????? ?? </label>
                    <input type="text" class="form-control form-control-sm" value="' . $fam_head . '" readonly />
                  </div>

                  <div class="col-md-2">
                    <label class="label_pers_page"> ?????????? </label>
                    <input type="text" class="form-control form-control-sm" value="' . $head_bday . '" readonly />
                  </div>

                   <div class="col-md-2">
                    <label class="label_pers_page"> ???????????????????????????????? </label>
                    <input type="text" class="form-control form-control-sm" value="' . $head_citizenship . '" readonly />
                  </div>

                   <div class="col-md-4">
                        <label class="label_pers_page">???????????????????? ?????????????? </label>
                        <input type="date" name="order_date" class="form-control form-control-sm" value="' . $order_date . '" readonly />
                      </div>    

                  <hr>';

		if ($result_family->num_rows > 1) {
			$modal_order1 .= '
             
                 <h5 class="sub_title">???????????????? ??????????</h5>
                      <div class="col-md-12">
                      <table class="table">
                        <tr>
                          <th class="label_pers_page" >????????</th>
                          <th class="label_pers_page">??????????</th>
                          <th class="label_pers_page">????????????????</th>
                          <th class="label_pers_page">??????????????????</th>                                   
                          <th class="label_pers_page">????????</th>
                          <th class="label_pers_page">?????????????? ??????????????</th>
                        </tr>';

			while ($row_fame = $result_family->fetch_assoc()) {
				$anun = '';
				$azganun = '';
				$hayranun = '';
				$sex = '';
				$bday = '';
				$frole = '';


				if ($row_fame['role'] != 1) {
					$anun = $row_fame['f_name_arm'];
					$azganun = $row_fame['l_name_arm'];
					$hayranun = $row_fame['m_name_arm'];
					$bday = $row_fame['b_day'] . '.' . $row_fame['b_month'] . '.' . $row_fame['b_year'];
					$frole = $row_fame['der'];
					if ($row_fame['sex'] == 1) {
						$sex = '????????????';
					} else {
						$sex = '????????????';
					}

					$modal_order1 .= '
                          
                         <tr>
                            <td>' . $frole . '</td>
                            <td>' . $anun . '</td>
                            <td>' . $azganun . '</td>
                            <td>' . $hayranun . '</td>
                            <td>' . $sex . '</td>
                            <td>' . $bday . '</td>          
                          </tr>';
				}
			}

			$modal_order1 .= '
                              </table>
                              <hr>
                              </div>';
		}


		$modal_order1 .= '              
                     
                      
                      <div class="col-md-12">
                          <label class="label_pers_page">????????????????????????????????</label>
                          <textarea class="form-control" rows="3" name="comment_order">' . $msg_order . '</textarea>
                      </div>       

                            </div>
                          </div>
                      
                     
                      </div>
                        <div class="modal-footer">
                              <button type="button" class="btn btn-default" data-dismiss="modal">????????????????</button>
                              <input type="submit" class="btn btn-success" form="signed_order" name="save_signed_order" value="????????????????" />
                              </form>
                        </div>
                      </div>
                      
                    </div>';

		echo $modal_order1;
	}

	//insert signed order

	if (isset($_POST['save_signed_order'])) {
		$case_id = $_POST['order_case'];
		$order_id = $_POST['order_order_id'];
		$order_date = $_POST['order_date'];
		$comment_a_sign = $_POST['comment_order'];
		$sender = $_SESSION['user_id'];
		$reciver_id = '';
		$sql_reciver = "SELECT * FROM users WHERE user_type = 'dorm' AND user_status = 1";
		$resutl_reciver = $conn->query($sql_reciver);
		if ($resutl_reciver->num_rows > 0) {
			$row_r = $resutl_reciver->fetch_assoc();
			$reciver_id = $row_r['id'];
		}
		$order_process_status = $_POST['order_process_status'];
		$file_type = '';

		if ($order_process_status == 1) {
			$order_status = '2';

		}

		if ($order_process_status == 4) {
			$order_status = '5';

		}


		$sql_update_actual = "UPDATE tb_order_process SET order_actual = 0 WHERE order_id = $order_id";

		if ($conn->query($sql_update_actual) === TRUE) {
			$sql_insert_order_process = "INSERT INTO `tb_order_process`(`order_id`, `order_from`, `order_to`, `order_status`, `order_actual`, `order_comment`) VALUES ('$order_id', '$sender', '$reciver_id', '$order_status', '1', '$comment_a_sign')";

			if ($conn->query($sql_insert_order_process) === TRUE) {
				$last_order_process = $conn->insert_id;
				$sql_notify = "INSERT INTO `tb_notifications`(`comment_subject`, `comment_text`, `comment_status`, `comment_from`, `comment_to`, `case_id`, `order_id`, `note_type`) VALUES 
                 ('?????????????? ??????????????????', NULLIF('$comment_a_sign', ''), '0', '$sender', '$reciver_id', '$case_id', '$order_id', '5')";

				if ($conn->query($sql_notify) === TRUE) {
					header('location: ../user.php?page=cases&homepage=order_page&order=' . $order_id . '&case=' . $case_id);
				} else {
					echo "Error: " . $sql_notify . "<br>" . $conn->error;
				}

			} else {
				echo "Error: " . $sql_insert_order_process . "<br>" . $conn->error;
			}


		} else {
			echo "Error: " . $sql_update_actual . "<br>" . $conn->error;
		}

	}

	###################
	// report checkin done

	if (isset($_POST['report_order'])) {
		$modal_order_done = '';
		$order_id = $_POST['report_order'];
		$case_id = $_POST['case_id_report'];
		$msg_order = "?? ???????????????? ?????? " . $order_id . " ??????????????";


		$opt_answer_type = '<select name="select_order_answer" id="select_order_answer" class="form-control form-control-sm" required>';
		$opt_answer_type .= '<option selected disabled hidden>???????????? ??????????????????</option>';
		$opt_answer_type .= '<option value="3">???????????????????? ??</option>';
		$opt_answer_type .= '<option value="4">?????????????? ??????????????</option>';
		$opt_answer_type .= '<option value="6">?????????? ?? ??????????</option>';
		$opt_answer_type .= '<option value="8">???????????????????? ??????????????</option>';
		$opt_answer_type .= "</select>";

		$sql_reciver = "SELECT * FROM users WHERE user_type = 'devhead' AND user_status = 1";
		$resutl_reciver = $conn->query($sql_reciver);
		$opt_reciver = '';

		$opt_reciver = '<select name="select_receiver" id="select_receiver" class="form-control form-control-sm" required>';

		while ($row_reciver = mysqli_fetch_array($resutl_reciver)) {
			$opt_reciver .= "<option value=" . $row_reciver['id'] . ">" . $row_reciver['f_name'] . ' ' . $row_reciver['l_name'] . "</option>";
			$opt_reciver .= '</select>';
		}

		$count_order_members = "SELECT COUNT(*) AS NUM_OF_MEMBERS FROM tb_checkin WHERE order_id = $order_id";
		$res_count_order_members = $conn->query($count_order_members);

		if ($res_count_order_members->num_rows > 0) {
			$row_num_members = $res_count_order_members->fetch_assoc();
			$num_of_members = $row_num_members['NUM_OF_MEMBERS'];
		}


		$modal_order_done = '<div class="modal-dialog modal-xl">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" style="color: #324157;"><i class="fas fa-reply"></i> ????????????????????</h5>
          <button type="button" class="close" data-dismiss="modal">??</button>
        </div>
        <div class="modal-body">
        <form method="POST" action="config/config.php" id="answer_order_dorm" enctype="multipart/form-data">
        <input type="hidden" name="count_members" value="' . $num_of_members . '" />
          <div class="col-md-12">
            <label class="label_pers_page">?????? </label> 
            ' . $opt_reciver . '  
          </div>

           <div class="col-md-12">
            <label class="label_pers_page">?????????????? ???????????????? ???????????? </label> 
            ' . $opt_answer_type . ' 
          </div>

          <div class="col-md-12">
            <label class="label_pers_page">????????????????????????????????</label>
            <textarea class="form-control" rows="3" name="comment_order_answer">' . $msg_order . '</textarea>
          </div>       

          <input type="hidden" value="' . $order_id . '" name="answer_order_id" />
          <input type="hidden" value="' . $case_id . '"  name="answer_case_id" />
           
        
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">????????????????</button>   
          <input type="submit" name="answer_order" class="btn btn-success" form="answer_order_dorm" value="????????????????">
        </div>
      </div>
      </form>
    </div>

';

		echo $modal_order_done;

	}


	// insert_answer

	if (isset($_POST['answer_order'])) {
		$case_id = $_POST['answer_case_id'];
		$order_id = $_POST['answer_order_id'];
		$order_status = $_POST['select_order_answer'];
		$comment_a_sign = $_POST['comment_order_answer'];
		$sender = $_SESSION['user_id'];
		$reciver_id = $_POST['select_receiver'];
		$file_type = '';
		$family_count = $_POST['count_members'];

		$count_outs_status = "SELECT COUNT(*) AS CHECKOUTED FROM tb_checkin WHERE order_id = $order_id AND status = 0";
		$res_count_checkouted = $conn->query($count_outs_status);

		if ($res_count_checkouted->num_rows > 0) {
			$row_res_count_checkouted = $res_count_checkouted->fetch_assoc();
			$num_of_checkouted = $row_res_count_checkouted['CHECKOUTED'];
		}

		if ($order_status == 3) {
			$file_type = '11';
		}

		if ($order_status == 4) {
			$file_type = '8';
		}

		if ($order_status == 6) {
			$file_type = '12';

		}

		if ($order_status == 8) {
			$file_type = '13';
		}


		$errormsg_cheked_out = '
          <div style="width: 70%; margin-left: 15%; margin-top: 30px;"> 
            <p style=" font-size: 1.3em;  text-align: center; font-weight: bold; margin-top: 5%;"> 
              
              <input type="text" value="' . $family_count . '" />
              <input type="text" value="' . $num_of_checkouted . '" />

              ???????????????? ?????????? ?????????????????? ?????????? ?????????? ??????:

             </p>
             <a class="btn btn-success" href="../user.php?page=cases&homepage=order_page&order=' . $order_id . '&case=' . $case_id . '"> ???????????????????? ?????????? ???? </a>
          </div>
     ';


		if ($order_status == 6 && $family_count != $num_of_checkouted) {
			echo "$errormsg_cheked_out";
			exit();
		}
		if ($order_status == 6) {
			$sql_update_tb_orders = "UPDATE tb_orders SET order_status = 2 WHERE order_id = $order_id";
			$res_update_tb_orders = $conn->query($sql_update_tb_orders);
		}


		# Upload file


		$sql_update_actual = "UPDATE tb_order_process SET order_actual = 0 WHERE order_id = $order_id";

		if ($conn->query($sql_update_actual) === TRUE) {
			$sql_insert_order_process = "INSERT INTO `tb_order_process`(`order_id`, `order_from`, `order_to`, `order_status`, `order_actual`, `order_comment`) VALUES ('$order_id', '$sender', '$reciver_id', '$order_status', '1', '$comment_a_sign')";

			if ($conn->query($sql_insert_order_process) === TRUE) {
				$last_order_process = $conn->insert_id;
				$sql_notify = "INSERT INTO `tb_notifications`(`comment_subject`, `comment_text`, `comment_status`, `comment_from`, `comment_to`, `case_id`, `order_id`, `note_type`) VALUES 
                 ('??????????????', NULLIF('$comment_a_sign', ''), '0', '$sender', '$reciver_id', '$case_id', '$order_id', '5')";

				if ($conn->query($sql_notify) === TRUE) {
					header('location: ../user.php?page=cases&homepage=order_page&order=' . $order_id . '&case=' . $case_id);
				} else {
					echo "Error: " . $sql_notify . "<br>" . $conn->error;
				}

			} else {
				echo "Error: " . $sql_insert_order_process . "<br>" . $conn->error;
			}


		} else {
			echo "Error: " . $sql_update_actual . "<br>" . $conn->error;
		}

	}

	###################################

	// modal asign order

	if (isset($_POST['asign_order'])) {
		$modal_order_asign = '';
		$order_id = $_POST['asign_order'];
		$case_id = $_POST['case_id_report'];
		$msg_order = "?????????????????? ?????? " . $order_id . " ?????????????? ?????????????????? ???????????????????? ??????????????????";


		$sql_reciver = "SELECT * FROM users WHERE user_type = 'operator' AND user_status = 1";
		$resutl_reciver = $conn->query($sql_reciver);
		$opt_reciver = '';

		$opt_reciver = '<select name="select_receiver" id="select_receiver" class="form-control form-control-sm" required>';
		$opt_reciver .= '<option selected disabled hidden>???????????? ????????????????</option>';
		while ($row_reciver = mysqli_fetch_array($resutl_reciver)) {
			$opt_reciver .= "<option value=" . $row_reciver['id'] . ">" . $row_reciver['f_name'] . ' ' . $row_reciver['l_name'] . "</option>";
			$opt_reciver .= '</select>';
		}


		$modal_order_asign = '<div class="modal-dialog modal-xl">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" style="color: #324157;"><i class="fas fa-user-plus"></i> ???????????????? ??????????????</h5>
          <button type="button" class="close" data-dismiss="modal">??</button>
        </div>
        <div class="modal-body">
        <form method="POST" action="config/config.php" id="asign_order_operator" enctype="multipart/form-data">
          <div class="col-md-12">
            <label class="label_pers_page">?????? </label> 
            ' . $opt_reciver . '  
          </div>
          


          <div class="col-md-12">
            <label class="label_pers_page">????????????????????????????????</label>
            <textarea class="form-control" rows="3" name="comment_order_answer">' . $msg_order . '</textarea>
          </div>       

          <input type="hidden" value="' . $order_id . '" name="answer_order_id" />
          <input type="hidden" value="' . $case_id . '"  name="answer_case_id" />
           
        
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">????????????????</button>   
          <input type="submit" name="save_asign_order" class="btn btn-success" form="asign_order_operator" value="????????????????">
        </div>
      </div>
      </form>
    </div>

';

		echo $modal_order_asign;

	}


	// insert_answer

	if (isset($_POST['save_asign_order'])) {
		$case_id = $_POST['answer_case_id'];
		$order_id = $_POST['answer_order_id'];
		$comment_a_sign = $_POST['comment_order_answer'];
		$sender = $_SESSION['user_id'];
		$reciver_id = $_POST['select_receiver'];
		$order_status = '9';


		$sql_update_actual = "UPDATE tb_order_process SET order_actual = 0 WHERE order_id = $order_id";

		if ($conn->query($sql_update_actual) === TRUE) {
			$sql_insert_order_process = "INSERT INTO `tb_order_process`(`order_id`, `order_from`, `order_to`, `order_status`, `order_actual`, `order_comment`) VALUES ('$order_id', '$sender', '$reciver_id', '$order_status', '1', '$comment_a_sign')";

			if ($conn->query($sql_insert_order_process) === TRUE) {
				$last_order_process = $conn->insert_id;
				$sql_notify = "INSERT INTO `tb_notifications`(`comment_subject`, `comment_text`, `comment_status`, `comment_from`, `comment_to`, `case_id`, `order_id`, `note_type`) VALUES 
                 ('?????? ??????????????', NULLIF('$comment_a_sign', ''), '0', '$sender', '$reciver_id', '$case_id', '$order_id', '5')";

				if ($conn->query($sql_notify) === TRUE) {
					header('location: ../user.php?page=cases&homepage=order_page&order=' . $order_id . '&case=' . $case_id);
				} else {
					echo "Error: " . $sql_notify . "<br>" . $conn->error;
				}

			} else {
				echo "Error: " . $sql_insert_order_process . "<br>" . $conn->error;
			}


		} else {
			echo "Error: " . $sql_update_actual . "<br>" . $conn->error;
		}

	}

	###################################

	###################
	// create cencel

	if (isset($_POST['create_cencel'])) {
		$modal_order_done = '';
		$order_id = $_POST['create_cencel'];
		$case_id = $_POST['case_id_report'];
		$msg_order = "?? ???????????????? ?????? " . $order_id . " ???????????????? ?????????????? ?????????????????? ???????????????????? ??????????";


		$sql_reciver = "SELECT * FROM users WHERE user_type = 'devhead' AND user_status = 1";
		$resutl_reciver = $conn->query($sql_reciver);
		$opt_reciver = '';

		$opt_reciver = '<select name="select_receiver" id="select_receiver" class="form-control form-control-sm" required>';
		$opt_reciver .= '<option selected disabled hidden>???????????? ????????????????</option>';
		while ($row_reciver = mysqli_fetch_array($resutl_reciver)) {
			$opt_reciver .= "<option value=" . $row_reciver['id'] . ">" . $row_reciver['f_name'] . ' ' . $row_reciver['l_name'] . "</option>";
			$opt_reciver .= '</select>';
		}


		$modal_order_done = '<div class="modal-dialog modal-xl">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" style="color: #324157;"><i class="fas fa-reply"></i> ???????????????? ??????????????????</h5>
          <button type="button" class="close" data-dismiss="modal">??</button>
        </div>
        <div class="modal-body">
        <form method="POST" action="config/config.php" id="answer_order_cencel_draft" enctype="multipart/form-data">
          <div class="col-md-12">
            <label class="label_pers_page">?????? </label> 
            ' . $opt_reciver . '  
          </div>

          <div class="col-md-12">
                        <label class="label_pers_page">?????????? ???????????????? ?????????????????? </label>
                        <div class="form-group custom-file">
                            <input type="file" name="file" class="custom-file-input" id="customFile" required="required" />
                            <label class="custom-file-label" for="customFile">?????????????? ??????????</label>
                        </div>
                      </div> 
                      <div class="col-md-12">
                          <label class="label_pers_page">????????????????????????????????</label>
                          <textarea class="form-control" rows="3" name="comment_order_answer">' . $msg_order . '</textarea>
                      </div>       

          <input type="hidden" value="' . $order_id . '" name="answer_order_id" />
          <input type="hidden" value="' . $case_id . '"  name="answer_case_id" />
           
        
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">????????????????</button>   
          <input type="submit" name="cencel_order" class="btn btn-success" form="answer_order_cencel_draft" value="????????????????">
        </div>
      </div>
      </form>
    </div>

';

		echo $modal_order_done;

	}


	// insert_answer_cencel

	if (isset($_POST['cencel_order'])) {
		$case_id = $_POST['answer_case_id'];
		$order_id = $_POST['answer_order_id'];
		$order_status = '8';
		$comment_a_sign = $_POST['comment_order_answer'];
		$sender = $_SESSION['user_id'];
		$reciver_id = $_POST['select_receiver'];
		$file_type = '9';


		# Get file name


		$filename = $_FILES['file']['name'];


		# Location
		$location = "../uploads/" . $case_id . "/order";

		# create directoy if not exists in upload/ directory
		if (!is_dir($location)) {
			mkdir($location, 0755);
		}

		$location .= "/" . $filename;


		# Upload file


		$sql_update_actual = "UPDATE tb_order_process SET order_actual = 0 WHERE order_id = $order_id";

		if ($conn->query($sql_update_actual) === TRUE) {
			$sql_insert_order_process = "INSERT INTO `tb_order_process`(`order_id`, `order_from`, `order_to`, `order_status`, `order_actual`, `order_comment`) VALUES ('$order_id', '$sender', '$reciver_id', '$order_status', '1', '$comment_a_sign')";

			if ($conn->query($sql_insert_order_process) === TRUE) {
				$last_order_process = $conn->insert_id;
				$sql_notify = "INSERT INTO `tb_notifications`(`comment_subject`, `comment_text`, `comment_status`, `comment_from`, `comment_to`, `case_id`, `order_id`, `note_type`) VALUES 
                 ('?????????????? ?????????????????? ??????????????', NULLIF('$comment_a_sign', ''), '0', '$sender', '$reciver_id', '$case_id', '$order_id', '5')";

				if ($conn->query($sql_notify) === TRUE) {

					if (move_uploaded_file($_FILES['file']['tmp_name'], $location)) {
						$sql_insert_file = "INSERT INTO `files`(`file_name`, `file_type`, `uploader`, `case_id`, `order_process_id`) VALUES ('$filename', '$file_type','$sender','$case_id', '$last_order_process')";

						if ($conn->query($sql_insert_file) === TRUE) {
							header('location: ../user.php?page=cases&homepage=order_page&order=' . $order_id . '&case=' . $case_id);

						}
					} else {
						echo "Error: " . $sql_insert_file . "<br>" . $conn->error;
					}

				} else {
					echo "Error: " . $sql_notify . "<br>" . $conn->error;
				}

			} else {
				echo "Error: " . $sql_insert_order_process . "<br>" . $conn->error;
			}


		} else {
			echo "Error: " . $sql_update_actual . "<br>" . $conn->error;
		}

	}

	###################################
	//return to redevelope modal

	if (isset($_POST['re_case'])) {
		$case_id = $_POST['re_case'];
		$proc_status = $_POST['proc_status'];
		$sender_id = '';


		$query_process_for_sender = "SELECT * FROM tb_process WHERE case_id = $case_id AND actual = '1'";
		$result_process_for_sender = $conn->query($query_process_for_sender);


		if ($result_process_for_sender->num_rows > 0) {
			$row_sender = $result_process_for_sender->fetch_assoc();
			$sender_id = $row_sender['sign_by'];

			$sql_find_sender_role = "SELECT id, user_type FROM users WHERE id = $sender_id";
			$result_find_sender_role = $conn->query($sql_find_sender_role);

			if ($result_find_sender_role->num_rows > 0) {
				$row_sender_role = $result_find_sender_role->fetch_assoc();
				$sender_role = $row_sender_role['user_type'];

				if ($sender_role === 'head') {
					$sql_find_officer = "SELECT * FROM tb_case WHERE case_id = $case_id";
					$result_find_officer = $conn->query($sql_find_officer);

					if ($result_find_officer->num_rows > 0) {
						$row_officer = $result_find_officer->fetch_assoc();
						$sender_id = $row_officer['officer'];
					}
				}
			}
		}

		$decision_type = '';

		$check_decision = "SELECT * FROM tb_decisions WHERE case_id = $case_id AND actual = 1";
		$result_chk_dec = $conn->query($check_decision);

		if ($result_chk_dec -> num_rows > 0) {
			$row = $result_chk_dec->fetch_assoc();
			$decision_type = $row['decision_type'];
		}


		$modal_return = '';

		$sql_reciver = "SELECT id, f_name, l_name FROM users WHERE id = $sender_id";
		$resutl_reciver = $conn->query($sql_reciver);
		$opt_reciver = '';

		$opt_reciver = '<select name="select_receiver" id="select_receiver" class="form-control form-control-sm" required>';
		while ($row_reciver = mysqli_fetch_array($resutl_reciver)) {
			$opt_reciver .= "<option value=" . $row_reciver['id'] . ">" . $row_reciver['f_name'] . ' ' . $row_reciver['l_name'] . "</option>";
			$opt_reciver .= '</select>';
		}


		$msg_return = '?????????????? ???????? ???? ??????????????????????????, ?????????????? ???? ??????????????';

		$modal_return = '<div class="modal-dialog modal-xl">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" style="color: #324157;"><i class="fas fa-reply"></i> ???????????????? ??????????????????????</h5>
          <button type="button" class="close" data-dismiss="modal">??</button>
        </div>
        <div class="modal-body">
        <form method="POST" action="config/config.php" id="redev">
          <input type="text" name="decision_type" value="'.$decision_type.'" />

          <div class="col-md-12">
            <label class="label_pers_page">?????? </label> 
            ' . $opt_reciver . '  
          </div>

          <div class="col-md-12">
            <label class="label_pers_page">????????????????????????????????</label>
            <textarea class="form-control" rows="3" name="comment_return_dev">' . $msg_return . '</textarea>
          </div>       

          
          <input type="hidden" value="' . $case_id . '"  name="return_case_id" />
          <input type="hidden" value="'.$proc_status.'" name="proc_status" />
        
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">????????????????</button>   
          <input type="submit" name="redev_case" class="btn btn-success" form="redev" value="?????????????????????? ????????????????????">
        </div>
      </div>
      </form>
    </div>

';

		echo $modal_return;
	}

	//insert redev modal

	if (isset($_POST['redev_case'])) {
		$case_id = $_POST['return_case_id'];
		$comment_a_sign = $_POST['comment_return_dev'];
		$sender = $_SESSION['user_id'];
		$reciver_id = $_POST['select_receiver'];
		$sign_status = '9';
		$proc_status = $_POST['proc_status'];
		$decision_type = $_POST['decision_type'];

		if ($proc_status == 13) {
			$update_decision = "UPDATE tb_decisions SET decision_status = 2 WHERE case_id = $case_id AND actual = 1";
			$result = $conn->query($update_decision);
		}



		$sql_update_actual = "UPDATE tb_process SET actual = 0 WHERE case_id = $case_id";

		if ($conn->query($sql_update_actual) === TRUE) {
			$sql_insert_process = "INSERT INTO `tb_process`(`case_id`, `sign_status`, `sign_by`, `processor`, `comment_to`, `actual`, `comment_status`) VALUES ('$case_id', '$sign_status', '$sender', '$reciver_id', '$comment_a_sign', '1', '0')";

			if ($conn->query($sql_insert_process) === TRUE) {
				$last_process = $conn->insert_id;
				$sql_notify = "INSERT INTO `tb_notifications`(`comment_subject`, `comment_text`, `comment_status`, `comment_from`, `comment_to`, `case_id`,  `note_type`) VALUES 
                 ('?????????????????????? ?? ????????????????????', NULLIF('$comment_a_sign', ''), '0', '$sender', '$reciver_id', '$case_id', '1')";

				if ($conn->query($sql_notify) === TRUE) {
					header('location: ../user.php?page=cases&homepage=case_page&case=' . $case_id);
				} else {
					echo "Error: " . $sql_notify . "<br>" . $conn->error;
				}

			} else {
				echo "Error: " . $sql_insert_order_process . "<br>" . $conn->error;
			}


		} else {
			echo "Error: " . $sql_update_actual . "<br>" . $conn->error;
		}

	}


	if (isset($_POST['change_deadline'])) {
		$case_id = $_POST['change_deadline'];


		$sql_case = "SELECT a.case_id, a.application_date, a.input_date, b.deadline_type, b.deadline
              FROM tb_case a 
              INNER JOIN tb_deadline b ON a.case_id = b.case_id
              WHERE a.case_id = $case_id AND b.actual_dead = 1";
		$result_change_dead = $conn->query($sql_case);

		$application_date = '';
		$input_date = '';
		$actual_deadline = '';
		if ($result_change_dead->num_rows > 0) {
			$change_row = $result_change_dead->fetch_assoc();
			$application_date = date('d.m.Y', strtotime($change_row['application_date']));
			$input_date = date('d.m.Y', strtotime($change_row['input_date']));
			$actual_deadline = date('d.m.Y', strtotime($change_row['deadline']));
		}
		$change_deadline = '';

		$change_deadline = '<div class="modal-dialog modal-xl">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" style="color: #324157;"><i class="fas fa-reply"></i> ?????????????????????????? ????????????????????????</h5>
          <button type="button" class="close" data-dismiss="modal">??</button>
        </div>
        <div class="modal-body">
        <form method="POST" action="config/config.php" id="change_deadline_modal">

          <div class="row">

              <div class="col-md-3">
                <label class="label_pers_page">???????? #</label>
                <input type="text" class="form-control" name="case_id" value="' . $case_id . '" readonly/>
              </div>

              <div class="col-md-3">
                <label class="label_pers_page">?????????????? ??????????????</label>
                <input type="text" class="form-control"  value="' . $application_date . '" readonly/>
              </div>

              <div class="col-md-3">
                <label class="label_pers_page">?????????????????????? ??????????????</label>
                <input type="text" class="form-control"  value="' . $input_date . '" readonly/>
              </div>

               <div class="col-md-3">
                <label class="label_pers_page">?????????????????????? ??????????????</label>
                <input type="text" class="form-control" value="' . $actual_deadline . '" readonly/>
              </div>

              <div class="col-md-12">
                <label class="label_pers_page" style="color: red;">???????????????? ?????? ????????????????????????</label>
                <input type="date" class="form-control" name="new_dead" />
              </div>       

          </div>  
          
          
          <p style="text-align: center; color: blue; font-size: 1.25em;">???????????????????????????? ?????????????????? ???? <br> ???????????????????? ?????????????? ?????????????? ?????????? ?????????????????????????? ?????????????????????????? ?????????????????? ????
        </p> 
        
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">????????????????</button>   
          <input type="submit" name="change_deadline_save" class="btn btn-success" form="change_deadline_modal" value="????????????????">
        </div>
      </div>
      </form>
    </div>';

		echo $change_deadline;
	}


	if (isset($_POST['change_deadline_save'])) {

		$case_id = $_POST['case_id'];
		$new_deadline = $_POST['new_dead'];
		$deadline_comment = '???????????? ?????????? ?????????? ?????????????????? ????????????????????????';

		$sql_update_deadline = "UPDATE tb_deadline SET actual_dead = 0 WHERE case_id = $case_id";
		if ($conn->query($sql_update_deadline) === TRUE) {
			$sql_insert_new_update = "INSERT INTO `tb_deadline` (`case_id`, `deadline_type`, `deadline_comment`, `deadline`, `actual_dead`) VALUES ('$case_id', '10', '$deadline_comment', '$new_deadline', '1')";
			if ($conn->query($sql_insert_new_update) === TRUE) {
				header('location: ../user.php?page=cases&homepage=case_page&case=' . $case_id);
			} else {
				echo "Error: " . $sql_insert_new_update . "<br>" . $conn->error;
			}


		}


	}
	#########################

	if (isset($_POST['delete_note'])) {
		$all_ids = $_POST['note_check'];
		$separate_note_id = implode(',', $all_ids);
		$read = '1';

		$sql_delete_note = "UPDATE tb_notifications SET readed = $read WHERE comment_id IN ($separate_note_id)";

		if ($conn->query($sql_delete_note) === TRUE) {
			header('location: ../user.php?page=cases&homepage=notifications');
		} else {
			echo "Error: " . $sql_delete_note . "<br>" . $conn->error;
		}
	}

	##########################


	if (isset($_POST['delete_request'])) {
		$all_ids = $_POST['request_check'];
		$separate_request_id = implode(',', $all_ids);
		$read = '2';

		$sql_delete_request = "UPDATE tb_request_process SET request_read = $read WHERE request_id IN ($separate_request_id)";

		if ($conn->query($sql_delete_request) === TRUE) {
			header('location: ../user.php?page=cases&homepage=body');
		} else {
			echo "Error: " . $sql_delete_request . "<br>" . $conn->error;
		}
	}

	##########################


	if (isset($_POST['request_change_special'])) {
		$case_id = $_POST['request_change_special'];

		$sql_reciever_change_special = "SELECT id, f_name, l_name FROM users WHERE user_type = 'devhead'";
		$result_reciever = $conn->query($sql_reciever_change_special);
		if ($result_reciever->num_rows > 0) {
			$rec_row = $result_reciever->fetch_assoc();
			$rec_id = $rec_row['id'];
			$rec_name = $rec_row['f_name'] . ' ' . $rec_row['l_name'];
		}

		$request_change_type = '';

		$request_change_type = '<div class="modal-dialog modal-xl">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" style="color: #324157;"><i class="fas fa-reply"></i> ?????????????? ???????? ???????????????? ????????</h5>
          <button type="button" class="close" data-dismiss="modal">??</button>
        </div>
        <div class="modal-body">
        <form method="POST" action="config/config.php" id="change_type_modal">

          <div class="row">
              <div class="col-md-3">
                <label class="label_pers_page">???????? #</label>
                <input type="text" class="form-control" name="case_id" value="' . $case_id . '" readonly/>
              </div>

              <div class="col-md-9">
                <label class="label_pers_page">????????????</label>
                 <input type="text" class="form-control" name="" value="' . $rec_name . '" readonly/>
              </div>       

              <input type="hidden" class="form-control" name="rec_id" value="' . $rec_id . '" readonly/>
            
            
          </div>  
          
          <textarea class="form-control" name="comment_a_sign"> ?????????????? ' . $rec_name . ' ?????????????? ???? ?????????????? ?????????? ?????????? </textarea>         
        
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">????????????????</button>   
          <input type="submit" name="request_change_special_save" class="btn btn-success" form="change_type_modal" value="????????????????">
        </div>
      </div>
      </form>
    </div>';

		echo $request_change_type;
	}

	if (isset($_POST['request_change_special_save'])) {
		$case_id = $_POST['case_id'];
		$reciver_id = $_POST['rec_id'];
		$sign_status = '21';
		$sender = $_SESSION['user_id'];
		$comment_a_sign = $_POST['comment_a_sign'];


		$sql_update_actual = "UPDATE tb_process SET actual = 0 WHERE case_id = $case_id";

		if ($conn->query($sql_update_actual) === TRUE) {
			$sql_insert_process = "INSERT INTO `tb_process`(`case_id`, `sign_status`, `sign_by`, `processor`, `comment_to`, `actual`) VALUES ('$case_id', '$sign_status', '$sender', '$reciver_id', '$comment_a_sign', '1')";

			if ($conn->query($sql_insert_process) === TRUE) {
				$last_process = $conn->insert_id;
				$sql_notify = "INSERT INTO `tb_notifications`(`comment_subject`, `comment_text`, `comment_status`, `comment_from`, `comment_to`, `case_id`,  `note_type`) VALUES 
                 ('?????????????????????? ?? ???????? ????????????????', NULLIF('$comment_a_sign', ''), '0', '$sender', '$reciver_id', '$case_id', '1')";

				if ($conn->query($sql_notify) === TRUE) {
					header('location: ../user.php?page=cases&homepage=case_page&case=' . $case_id);
				} else {
					echo "Error: " . $sql_notify . "<br>" . $conn->error;
				}

			} else {
				echo "Error: " . $sql_insert_order_process . "<br>" . $conn->error;
			}


		} else {
			echo "Error: " . $sql_update_actual . "<br>" . $conn->error;
		}

	}

	##########################

	if (isset($_POST['approve_change_special'])) {
		$case_id = $_POST['approve_change_special'];

		$sql_reciever_change_special = "SELECT a.id, a.f_name, a.l_name, b.officer FROM users a INNER JOIN tb_case b ON a.id = b.officer WHERE b.case_id = $case_id";
		$result_reciever = $conn->query($sql_reciever_change_special);
		if ($result_reciever->num_rows > 0) {
			$rec_row = $result_reciever->fetch_assoc();
			$rec_id = $rec_row['id'];
			$rec_name = $rec_row['f_name'] . ' ' . $rec_row['l_name'];
		}
		$sql_person = "SELECT personal_id, f_name_arm, l_name_arm, m_name_arm, illegal_border, transfer_moj, deport_prescurator, prison FROM tb_person WHERE case_id = $case_id AND role = 1";
		$result_person = $conn->query($sql_person);
		if ($result_person->num_rows > 0) {
			$row_person = $result_person->fetch_assoc();
			$name = $row_person['f_name_arm'] . ' ' . $row_person['l_name_arm'];
			$illegal = '';
			if ($row_person['illegal_border'] == 1) {
				$illegal = 'checked';
			}
			$transfer = '';
			if ($row_person['transfer_moj'] == 1) {
				$transfer = 'checked';
			}
			$deport_prescurator = '';
			if ($row_person['deport_prescurator'] == 1) {
				$deport_prescurator = 'checked';
			}
			$prison = '';
			if ($row_person['prison'] == 1) {
				$prison = 'checked';
			}

		}

		$request_change_type = '';

		$request_change_type = '<div class="modal-dialog modal-xl">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" style="color: #324157;"><i class="fas fa-reply"></i> ?????????????? ???????? ???????????????? ????????</h5>
          <button type="button" class="close" data-dismiss="modal">??</button>
        </div>
        <div class="modal-body">
        <form method="POST" action="config/config.php" id="change_type_modal">

          <div class="row">
              <div class="col-md-3">
                <label class="label_pers_page">???????? #</label>
                <input type="text" class="form-control" name="case_id" value="' . $case_id . '"  readonly/>
              </div>
               <div class="col-md-9">
                <label class="label_pers_page">????????????</label>
                 <input type="text" class="form-control" name="" value="' . $rec_name . '" readonly/>
              </div>  

              <div class="col-md-12">
                <label class="label_pers_page">?????????????? ?????????????????????? ??.??.??.</label>
                 <input type="text" class="form-control" name="" value="' . $name . '" readonly/>
              </div>

              <div class="col-md-3">
                <label class="label_pers_page">?????????????? ??????????????????????????</label>
                <input type="checkbox" class="form-control" name="" ' . $illegal . ' onclick="return false;" readonly/>
              </div>     
               <div class="col-md-3">
                <label class="label_pers_page">???????????????????????? (????????????????????????????)</label>
                <input type="checkbox" class="form-control" name="" ' . $deport_prescurator . ' onclick="return false;" readonly/>
              </div>     
               <div class="col-md-3">
                <label class="label_pers_page">?????????????????? (??????????????????)</label>
                <input type="checkbox" class="form-control" name="" ' . $transfer . ' onclick="return false;" readonly/>
              </div>     
               <div class="col-md-3">
                <label class="label_pers_page" >??????</label>
                <input type="checkbox" class="form-control" name="" ' . $prison . ' onclick="return false;" readonly/>
              </div>       

             

              <input type="hidden" class="form-control" name="rec_id" value="' . $rec_id . '" readonly/>
              

            
          </div>  
          <label class="label_pers_page">????????????????????????????????</label>
          <textarea class="form-control" name="comment_a_sign"> ?????????? ???????? ???????????????? ?????????? ?????????????????? </textarea>         
        
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">????????????????</button>   
          <input type="submit" name="approve_change_special_save" class="btn btn-success" form="change_type_modal" value="????????????????">
          <input type="submit" name="decline_change_special_save" class="btn btn-success" form="change_type_modal" value="????????????">
        </div>
      </div>
      </form>
    </div>';

		echo $request_change_type;
	}

	if (isset($_POST['approve_change_special_save'])) {
		$case_id = $_POST['case_id'];
		$reciver_id = $_POST['rec_id'];
		$sign_status = '';
		$sender = $_SESSION['user_id'];
		$comment_a_sign = $_POST['comment_a_sign'];
		$personal_id = '';

		$find_prev_sign_status = "SELECT * FROM tb_process WHERE case_id = $case_id AND actual = 0 ORDER BY process_id DESC LIMIT 1";
		$result_prev_sign_status = $conn->query($find_prev_sign_status);
		if ($result_prev_sign_status->num_rows > 0) {
			$row_prev_status = $result_prev_sign_status->fetch_assoc();
			$sign_status = $row_prev_status['sign_status'];
		}

		$find_personal_id = "SELECT * FROM tb_person WHERE case_id = $case_id AND role = 1";
		$result_find_personal_id = $conn->query($find_personal_id);
		if ($result_find_personal_id->num_rows > 0) {
			$row_find_personal_id = $result_find_personal_id->fetch_assoc();
			$personal_id = $row_find_personal_id['personal_id'];
		}

		$update_case_special = "UPDATE tb_case SET special = 0 WHERE case_id = $case_id";
		if ($conn->query($update_case_special) === TRUE) {
			$update_peron_specials = "UPDATE tb_person SET illegal_border = 0,  transfer_moj = 0, deport_prescurator = 0, prison = 0 WHERE personal_id = $personal_id";

			if ($conn->query($update_peron_specials) === TRUE) {
				$sql_update_actual = "UPDATE tb_process SET actual = 0 WHERE case_id = $case_id";

				if ($conn->query($sql_update_actual) === TRUE) {
					$sql_insert_process = "INSERT INTO `tb_process`(`case_id`, `sign_status`, `sign_by`, `processor`, `comment_to`, `actual`) VALUES ('$case_id', '$sign_status', '$sender', '$reciver_id', '$comment_a_sign', '1')";

					if ($conn->query($sql_insert_process) === TRUE) {
						$last_process = $conn->insert_id;
						$sql_notify = "INSERT INTO `tb_notifications`(`comment_subject`, `comment_text`, `comment_status`, `comment_from`, `comment_to`, `case_id`,  `note_type`) VALUES 
                 ('???????? ?????????????????? ?????????????????? ??', NULLIF('$comment_a_sign', ''), '0', '$sender', '$reciver_id', '$case_id', '1')";

						if ($conn->query($sql_notify) === TRUE) {
							header('location: ../user.php?page=cases&homepage=case_page&case=' . $case_id);
						} else {
							echo "Error: " . $sql_notify . "<br>" . $conn->error;
						}
					} else {
						echo "Error: " . $sql_insert_order_process . "<br>" . $conn->error;
					}
				} else {
					echo "Error: " . $sql_update_actual . "<br>" . $conn->error;
				}
			} else {
				echo "Error: " . $update_peron_specials . "<br>" . $conn->error;
			}
		} else {
			echo "Error: " . $update_case_special . "<br>" . $conn->error;
		}
	}


	if (isset($_POST['decline_change_special_save'])) {
		$case_id = $_POST['case_id'];
		$reciver_id = $_POST['rec_id'];
		$sign_status = '';
		$sender = $_SESSION['user_id'];
		$comment_a_sign = $_POST['comment_a_sign'];

		$find_prev_sign_status = "SELECT * FROM tb_process WHERE case_id = $case_id AND actual = 0 ORDER BY process_id DESC LIMIT 1";
		$result_prev_sign_status = $conn->query($find_prev_sign_status);
		if ($result_prev_sign_status->num_rows > 0) {
			$row_prev_status = $result_prev_sign_status->fetch_assoc();
			$sign_status = $row_prev_status['sign_status'];
		}

		$sql_update_actual = "UPDATE tb_process SET actual = 0 WHERE case_id = $case_id";

		if ($conn->query($sql_update_actual) === TRUE) {
			$sql_insert_process = "INSERT INTO `tb_process`(`case_id`, `sign_status`, `sign_by`, `processor`, `comment_to`, `actual`) VALUES ('$case_id', '$sign_status', '$sender', '$reciver_id', '$comment_a_sign', '1')";

			if ($conn->query($sql_insert_process) === TRUE) {
				$last_process = $conn->insert_id;
				$sql_notify = "INSERT INTO `tb_notifications`(`comment_subject`, `comment_text`, `comment_status`, `comment_from`, `comment_to`, `case_id`,  `note_type`) VALUES 
                 ('???????? ?????????????????? ?????????????? ??', NULLIF('$comment_a_sign', ''), '0', '$sender', '$reciver_id', '$case_id', '1')";

				if ($conn->query($sql_notify) === TRUE) {
					header('location: ../user.php?page=cases&homepage=case_page&case=' . $case_id);
				} else {
					echo "Error: " . $sql_notify . "<br>" . $conn->error;
				}

			} else {
				echo "Error: " . $sql_insert_order_process . "<br>" . $conn->error;
			}


		} else {
			echo "Error: " . $sql_update_actual . "<br>" . $conn->error;
		}

	}


	if (isset($_POST['claim_court'])) {
		$case_id = $_POST['claim_court'];
		$default_msg = '?????????????? ???????????????????????? ??????????';


		$query_user_inform = "SELECT id, f_name, l_name FROM users WHERE user_type = 'officer' OR user_type = 'lawyer' OR user_type = 'devhead'";
		$rec_info = $conn->query($query_user_inform);
		$opt_rec = '<select name="select_info" id="select_info" class="form-control">';

		while ($row5 = $rec_info->fetch_assoc()) {
			$opt_rec .= "<option value=" . $row5['id'] . ">" . $row5['f_name'] . ' ' . $row5['l_name'] . "</option>";
		}
		$opt_rec .= "</select>";

		$query_court_type = "SELECT court_id, court_title FROM tb_courts";
		$res_court = $conn->query($query_court_type);
		$opt_court = '<select name="select_court" id="select_court" class="form-control">';

		while ($row = $res_court->fetch_assoc()) {
			$opt_court .= "<option value=" . $row['court_id'] . ">" . $row['court_title'] . "</option>";
		}
		$opt_court .= "</select>";

		$request_change_type = '';

		$request_change_type = '<div class="modal-dialog modal-xl">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" style="color: #324157;"><i class="fas fa-gavel"></i> ?????????????? ?????????? ??????????????????????</h5>
          <button type="button" class="close" data-dismiss="modal">??</button>
        </div>
        <div class="modal-body">
        <form method="POST" action="config/config.php" id="change_type_modal" enctype="multipart/form-data">

          <div class="row">
              <div class="col-md-4">
                <label class="label_pers_page">????????????????????</label>
                ' . $opt_rec . '
              </div>

              <div class="col-md-4">
                <label class="label_pers_page">?????????? ???????????????? ????????</label>
                 ' . $opt_court . '
              </div>
                            
              <div class="col-md-2">
                <label class="label_pers_page">?????????????????? ??????????????</label>
                 <input type="date" class="form-control" name="claim_date" required="required" />
              </div>  
              <div class="col-md-2">
                <label class="label_pers_page">???????? #</label>
                <input type="text" class="form-control" name="case_id" value="' . $case_id . '"  readonly/>
              </div>

              <div class="col-md-12">
              <label class="label_pers_page">?????????? ????????????????????</label>
              <div class="form-group custom-file">
                <input type="file" name="file" class="custom-file-input" id="customFile" required="required" />
                <label class="custom-file-label" for="customFile">?????????????? ??????????</label>
              </div>
            </div> 
            <div class="col-md-12">
              <label class="label_pers_page">????????????????????????????????</label>
              <textarea class="form-control" name="claim_comment">' . $default_msg . '</textarea>
            </div>
                  
          </div>  
          
        
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">????????????????</button>   
          <input type="submit" name="claim_save" class="btn btn-success" form="change_type_modal" value="????????????????">
        </div>
      </div>
      </form>
    </div>';

		echo $request_change_type;
	}

	if (isset($_POST['claim_save'])) {
		$case_id = $_POST['case_id'];
		$claim_date = $_POST['claim_date'];
		$user_id = $_SESSION['user_id'];
		$comment = $_POST['claim_comment'];
		$sign_status = '22';
		$file_type = '18';
		$notify_id = $_POST['select_info'];
		$court_id = $_POST['select_court'];
		$initiator = '1';
		# Get file name
		$filename = date('dmYHis') . str_replace(" ", "", basename($_FILES["file"]["name"]));


		# Location
		$location = "../uploads/" . $case_id;

		# create directoy if not exists in upload/ directory
		if (!is_dir($location)) {
			mkdir($location, 0755);
		}

		$location .= "/" . $filename;


		$sql_claim_info = "INSERT INTO `tb_court_claim` (`claim_date`, `uploaded_by`, `case_id`, `court_id`, `claim_actual`, `initiator` ) VALUES ('$claim_date', '$user_id', '$case_id', '$court_id', '1', '$initiator')";
		if ($conn->query($sql_claim_info) === TRUE) {
			$last_claim_id = $conn->insert_id;
			# Upload file
			if (move_uploaded_file($_FILES['file']['tmp_name'], $location)) {
				$sql_claim_file = "INSERT INTO `files`(`file_name`, `file_type`, `uploader`, `case_id`, `claim_id`) VALUES ('$filename', '$file_type', '$user_id', '$case_id', '$last_claim_id')";
				if ($conn->query($sql_claim_file) === TRUE) {
					$sql_update_actual = "UPDATE tb_process SET actual = 0 WHERE case_id = $case_id";
					if ($conn->query($sql_update_actual) === TRUE) {
						$sql_insert_process = "INSERT INTO `tb_process`(`case_id`, `sign_status`, `sign_by`, `processor`, `comment_to`, `actual`) VALUES ('$case_id', '$sign_status', '$user_id', '$user_id', '$comment', '1')";

						if ($conn->query($sql_insert_process) === TRUE) {
							$sql_notify = "INSERT INTO `tb_notifications`(`comment_subject`, `comment_text`, `comment_status`, `comment_from`, `comment_to`, `case_id`,  `note_type`) VALUES 
                           ('?????????????? ???????????????????????? ??????????????????????', NULLIF('$comment', ''), '0', '$user_id', '$notify_id', '$case_id', '1')";

							if ($conn->query($sql_notify) === TRUE) {
								header('location: ../user.php?page=cases&homepage=case_page&case=' . $case_id);
							} else {
								echo "Error: " . $sql_notify . "<br>" . $conn->error;
							}
						} else {
							echo "Error: " . $sql_insert_process . "<br>" . $conn->error;
						}
					} else {
						echo "Error: " . $sql_update_actual . "<br>" . $conn->error;
					}
				} else {
					echo "Error: " . $sql_claim_file . "<br>" . $conn->error;
				}
			}
		} else {
			echo "Error: " . $sql_claim_info . "<br>" . $conn->error;
		}

	}

	#########################

	if (isset($_POST['accept_claim'])) {
		$case_id = $_POST['accept_claim'];
		$claim_id = '';
		$court_id = '';
		$sql_find_claim_id = "SELECT * FROM tb_court_claim WHERE case_id = $case_id AND claim_actual = 1";
		$result_find_claim_id = $conn->query($sql_find_claim_id);
		if ($result_find_claim_id->num_rows > 0) {
			$row_claim_id = $result_find_claim_id->fetch_assoc();
			$claim_id = $row_claim_id['claim_id'];
			$court_id = $row_claim_id['court_id'];
		}
		$default_msg = '???????????????????????? ?????????????????? ?? ??????????????';

		$query_user_inform = "SELECT id, f_name, l_name FROM users WHERE  user_type = 'devhead' AND user_status = 1";
		$rec_info = $conn->query($query_user_inform);
		$opt_rec = '<select name="select_info" id="select_info" class="form-control">';
		while ($row5 = $rec_info->fetch_assoc()) {
			$opt_rec .= "<option value=" . $row5['id'] . ">" . $row5['f_name'] . ' ' . $row5['l_name'] . "</option>";
		}
		$opt_rec .= "</select>";

		$query_appeal_type = "SELECT appeal_type_id, appeal_type FROM tb_appeal_types";
		$rec_query_appeal_type = $conn->query($query_appeal_type);
		$opt_appeal = '<select name="select_appeal" id="select_appeal" class="form-control">';
		while ($row1 = $rec_query_appeal_type->fetch_assoc()) {
			$opt_appeal .= "<option value=" . $row1['appeal_type_id'] . ">" . $row1['appeal_type'] . "</option>";
		}
		$opt_appeal .= "</select>";


		$query_court_type = "SELECT court_id, court_title FROM tb_courts";
		$res_court = $conn->query($query_court_type);
		$opt_court = '<select name="select_court" id="select_court" class="form-control">';

		while ($row = $res_court->fetch_assoc()) {
			if ($row['court_id'] == $court_id) {
				$opt_court .= "<option selected=\"selected\" value=" . $row['court_id'] . ">" . $row['court_title'] . "</option>";
			} else {
				$opt_court .= "<option value=" . $row['court_id'] . ">" . $row['court_title'] . "</option>";
			}
		}
		$opt_court .= "</select>";


		$modal_accept = '';

		$modal_accept = '<div class="modal-dialog modal-xl">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" style="color: #324157;"><i class="fas fa-gavel"></i> ?????????????? ?????????? ?????????????????? ?? ??????????????</h5>
          <button type="button" class="close" data-dismiss="modal">??</button>
        </div>
        <div class="modal-body">
        <form method="POST" action="config/config.php" id="change_type_modal" enctype="multipart/form-data">

          <div class="row">
              <div class="col-md-4">
                <label class="label_pers_page">????????????????????</label>
                ' . $opt_rec . '
              </div>
                                          
              <div class="col-md-4">
                <label class="label_pers_page">?????????????? ?????????????????? ??????????????</label>
                <input type="date" class="form-control" name="court_accept_date" required="required" />
              </div>  

               <div class="col-md-2">
                <label class="label_pers_page">???????? #</label>
                <input type="text" class="form-control" name="case_id" value="' . $case_id . '"  readonly/>
              </div>

              <div class="col-md-2">
                <label class="label_pers_page">?????????????????????? #</label>
                <input type="text" class="form-control" name="claim_id" value="' . $claim_id . '"  readonly/>
              </div>
              
              <div class="col-md-6">
                <label class="label_pers_page">?????????? ???????????????? ????????</label>
                 ' . $opt_court . '
              </div>
              
              <div class="col-md-6">
                <label class="label_pers_page">???????????????????????? ????????????</label>
                ' . $opt_appeal . '
              </div>

              <div class="col-md-12">
                <label class="label_pers_page">???????????????? ??????????????????</label>
                <input type="text" class="form-control" name="court_name" value="">
              </div>

              <div class="col-md-12">
              <label class="label_pers_page">?????????? ?????????????? ???????????????????? ?????????? ????????????????</label>
              <div class="form-group custom-file">
                <input type="file" name="file" class="custom-file-input" id="customFile" required="required" />
                <label class="custom-file-label" for="customFile">?????????????? ??????????</label>
              </div>
            </div> 

            

            <div class="col-md-12">
              <label class="label_pers_page">????????????????????????????????</label>
              <textarea class="form-control" name="claim_comment">' . $default_msg . '</textarea>
            </div>
                  
          </div>  
          
        
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">????????????????</button>   
          <input type="submit" name="save_accept_appeal" class="btn btn-success" form="change_type_modal" value="????????????????">
        </div>
      </div>
      </form>
    </div>';

		echo $modal_accept;
	}

	if (isset($_POST['save_accept_appeal'])) {
		$case_id = $_POST['case_id'];
		$court_accept_date = $_POST['court_accept_date'];
		$user_id = $_SESSION['user_id'];
		$comment = $_POST['claim_comment'];
		$sign_status = '23';
		$file_type = '19';
		$notify_id = $_POST['select_info'];
		$court_id = $_POST['select_court'];
		$claim_id = $_POST['claim_id'];
		$court_name = $_POST['court_name'];
		$appeal_type = $_POST['select_appeal'];

		# Get file name
		$filename = date('dmYHis') . str_replace(" ", "", basename($_FILES["file"]["name"]));


		# Location
		$location = "../uploads/" . $case_id;

		# create directoy if not exists in upload/ directory
		if (!is_dir($location)) {
			mkdir($location, 0755);
		}

		$location .= "/" . $filename;


		$sql_appeals = "INSERT INTO `tb_appeals`(`case_id`, `claim_id`, `court_accept_date`, `filled_by`, `court_level`, `court_name`, `appeal_type`) 
                                    VALUES ('$case_id', '$claim_id', '$court_accept_date', '$user_id', '$court_id', '$court_name', '$appeal_type')";
		if ($conn->query($sql_appeals) === TRUE) {
			$last_appeal_id = $conn->insert_id;
			# Upload file
			if (move_uploaded_file($_FILES['file']['tmp_name'], $location)) {
				$sql_claim_file = "INSERT INTO `files`(`file_name`, `file_type`, `uploader`, `case_id`, `appeal_id`) VALUES ('$filename', '$file_type', '$user_id', '$case_id', '$last_appeal_id')";
				if ($conn->query($sql_claim_file) === TRUE) {
					$sql_update_actual = "UPDATE tb_process SET actual = 0 WHERE case_id = $case_id";
					if ($conn->query($sql_update_actual) === TRUE) {
						$sql_insert_process = "INSERT INTO `tb_process`(`case_id`, `sign_status`, `sign_by`, `processor`, `comment_to`, `actual`) VALUES ('$case_id', '$sign_status', '$user_id', '$notify_id', '$comment', '1')";

						if ($conn->query($sql_insert_process) === TRUE) {
							$sql_notify = "INSERT INTO `tb_notifications`(`comment_subject`, `comment_text`, `comment_status`, `comment_from`, `comment_to`, `case_id`,  `note_type`) VALUES 
                           ('?????????????? ???????????????????????? ??????????????????????', NULLIF('$comment', ''), '0', '$user_id', '$notify_id', '$case_id', '1')";

							if ($conn->query($sql_notify) === TRUE) {
								$sql_update_case_status = "UPDATE tb_case SET case_status = '2'";

								if ($conn->query($sql_update_case_status)) {
									header('location: ../user.php?page=cases&homepage=case_page&case=' . $case_id);
								} else {
									echo "Error: " . $sql_update_case_status . "<br>" . $conn->error;
								}
							} else {
								echo "Error: " . $sql_notify . "<br>" . $conn->error;
							}
						} else {
							echo "Error: " . $sql_insert_process . "<br>" . $conn->error;
						}
					} else {
						echo "Error: " . $sql_update_actual . "<br>" . $conn->error;
					}
				}

			} else {
				echo "Error: " . $sql_claim_file . "<br>" . $conn->error;
			}
		} else {
			echo "Error: " . $sql_appeals . "<br>" . $conn->error;
		}

	}


	##########################

	if (isset($_POST['ms_lawyer'])) {

		$modal_sign = "";
		$case = $_POST['ms_lawyer'];


		$msg_asign = '?? ????????????????: ?????????????????? ?? ?????????????????????? ?????????????????????????? ???? ?????????????? ???????????? ?????????? ????????????????????????';


		$query_user_to_sign = "SELECT * from users WHERE user_type = 'officer' OR user_type = 'lawyer'";
		$officer1 = $conn->query($query_user_to_sign);
		$opt_mslawyer = '<select name="select_officer" id="select_officer" class="form-control form-control-sm">';
		$opt_mslawyer .= "<option> -- ?????????? ???? -- </option>";
		while ($row5 = mysqli_fetch_array($officer1)) {
			$opt_mslawyer .= "<option value=" . $row5['id'] . ">" . $row5['f_name'] . ' ' . $row5['l_name'] . "</option>";
		}
		$opt_mslawyer .= "</select>";


		$modal_sign .= '<div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">???????????????? ??????????????</h4>
          <button type="button" class="close" data-dismiss="modal">??</button>
          
        </div>
        <div class="modal-body">
        <form method="POST" action="config/config.php" id="asign_save">
          <div class="col-md-12">
            <input type="hidden" class="form-control" name="cases" value="' . $case . '">
            
            
            <label class="label_pers_page">????????????</label>
            ' . $opt_mslawyer . '
            <label class="label_pers_page">????????????????????????????????</label>
            <input type="text" class="form-control form-control-sm" name="comment_to" value="' . $msg_asign . '">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">??????????</button>
          <input type="submit" name="save_ms_lawyer" class="btn btn-primary" form="asign_save" value="????????????????">
        </div>
      </div>
      </form>
    </div>
    ';

		echo $modal_sign;
	}

	if (isset($_POST['save_ms_lawyer'])) {

		$officer2 = $_POST['select_officer'];
		$case_id_s = $_POST['cases'];
		$user = $_SESSION['user_id'];
		$comment_to = $_POST['comment_to'];


		$update_case = "UPDATE tb_case SET `MS_lawyer` = '$officer2' WHERE case_id = $case_id_s";

		if ($conn->query($update_case) === TRUE) {

			$sql_chk_actual = "SELECT * FROM tb_process WHERE case_id = $case_id_s";
			$result_chk = $conn->query($sql_chk_actual);

			if ($result_chk->num_rows > 0) {
				$sql_update_process = "UPDATE tb_process SET `actual` = 0 WHERE case_id = $case_id_s";

				if ($conn->query($sql_update_process) === TRUE) {
					$sql_insert_process = "INSERT INTO `tb_process` 
              (`case_id`, `sign_status`, `sign_by`, `processor`, `comment_to`, `actual`, `comment_status`) 
              VALUES ($case_id_s, '24', '$user', '$officer2', NULLIF('$comment_to', ''), '1', '0')";
					if ($conn->query($sql_insert_process) === TRUE) {

						$sql_notify = "INSERT INTO `tb_notifications`(`comment_subject`, `comment_text`, `comment_status`, `comment_from`, `comment_to`, `case_id`, `note_type`) VALUES ('?? ????????????????', NULLIF('$request_msg', ''), '0', '$user', '$officer2', '$case_id_s', '1')";
						if ($conn->query($sql_notify) === TRUE) {
							$sql_deadline_update = "UPDATE tb_deadline SET actual_dead = 0 WHERE case_id = $case_id_s";

							if ($conn->query($sql_deadline_update) === TRUE) {
								$sql_new_deadline = "INSERT INTO `tb_deadline`(`case_id`, `deadline_type`, `deadline_comment`, `deadline`, `actual_dead`) VALUES ('$case_id_s', '11', '?????????????? ??????????????????', 'NULL', '1')";
								if ($conn->query($sql_new_deadline) === TRUE) {
									header('location: ../user.php?page=cases&homepage=case_page&case=' . $case_id_s);
								} else {
									echo "Error: " . $sql_new_deadline . "<br>" . $conn->error;
								}
							} else {
								echo "Error: " . $sql_new_deadline . "<br>" . $conn->error;
							}
						} else {
							echo "Error: " . $sql_notify . "<br>" . $conn->error;
						}
					} else {
						echo "Error: " . $sql_insert_process . "<br>" . $conn->error;
					}

				} else {
					echo "Error: " . $sql_update_process . "<br>" . $conn->error;
				}
			} else {
				echo "Error: " . $update_case . "<br>" . $conn->error;
			}

		}
	}

	#################################

	if (isset($_POST['decision_3'])) {

		$case_id = $_POST['decision_3'];
		$court_id = '';
		$claim_id = $_POST['claim_id'];
		$appeal_id = $_POST['appeal_id'];

		$msg_asign = '???????????????? ?????????? ???????????????????? ??';

		$sql_find_court = "SELECT a.claim_id, a.claim_date, a.uploaded_by, a.upload_date, a.case_id, a.court_id, a.claim_actual, a.initiator, b.appeal_id, b.case_id, b.claim_id, b.court_accept_date, b.apeal_status, b.filled_in, b.filled_by, b.court_level, b.court_name, b.appeal_type, c.appeal_type_id, c.appeal_type AS APPEAL_TYPE_TEXT, d.court_id, d.court_title 
FROM tb_court_claim a 
INNER JOIN tb_appeals b ON a.claim_id = b.claim_id 
INNER JOIN tb_appeal_types c ON b.appeal_type = c.appeal_type_id 
INNER JOIN tb_courts d ON d.court_id = a.court_id
WHERE a.case_id = $case_id AND a.claim_actual = 1 AND b.apeal_status = 0 AND b.appeal_id = $appeal_id AND a.claim_id = $claim_id";
		$result_find_court = $conn->query($sql_find_court);
		if ($result_find_court->num_rows > 0) {
			$row_claim = $result_find_court->fetch_assoc();
			$court_id = $row_claim['court_id'];
			$appeal_type_id = $row_claim['appeal_type_id'];
			$appeal_type_text = $row_claim['APPEAL_TYPE_TEXT'];
			$court_type_text = $row_claim['court_title'];
			$court_name = $row_claim['court_name'];
			$claim_date = date('d.m.Y', strtotime($row_claim['claim_date']));
			$accept_date = date('d.m.Y', strtotime($row_claim['court_accept_date']));
			$initiator = $row_claim['initiator'];

			if ($initiator == 1) {
				$initiator_txt = '?????????????? ????????????';
			}
			if ($initiator == 2) {
				$initiator_txt = '?????????????????? ????????????????????????';
			}
		}

		$modal_claim = '';

		$query_coutr_decisions = "SELECT * FROM tb_court_decision_types WHERE court_type = $court_id";
		$res_query_coutr_decisions = $conn->query($query_coutr_decisions);
		$opt_court_decission = '<select name="select_decission" id="select_decission" class="form-control form-control-sm">';
		$opt_court_decission .= "<option> -- ?????????? ???? -- </option>";
		while ($row5 = mysqli_fetch_array($res_query_coutr_decisions)) {
			$opt_court_decission .= "<option value=" . $row5['court_decision_type_id'] . ">" . $row5['court_decision'] . "</option>";
		}
		$opt_court_decission .= "</select>";


		$query_user_to_sign = "SELECT * from users WHERE user_type = 'devhead'";
		$officer1 = $conn->query($query_user_to_sign);
		$opt_mslawyer = '<select name="select_receiver" id="select_receiver" class="form-control form-control-sm">';
		$opt_mslawyer .= "<option> -- ?????????? ???? -- </option>";
		while ($row5 = mysqli_fetch_array($officer1)) {
			$opt_mslawyer .= "<option value=" . $row5['id'] . ">" . $row5['f_name'] . ' ' . $row5['l_name'] . "</option>";
		}
		$opt_mslawyer .= "</select>";

		$modal_claim = '<div class="modal-dialog modal-xl">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">?????????????? ???????? ????????????????????</h4>
          <button type="button" class="close" data-dismiss="modal">??</button>
          
        </div>
        <div class="modal-body">
        <form method="POST" action="config/config.php" id="asign_save">
          <div class="col-md-12">
          <div class="row">
            <div class="col-md-12">
            <label class="label_pers_page">????????????</label>
            ' . $opt_mslawyer . '
            </div>

            <div class="col-md-3">
              <label class="label_pers_page">???????? #</label>
              <input type="text" class="form-control form-control-sm" name="case_id" value="' . $case_id . '" readonly>
            </div>

            <div class="col-md-3">
              <label class="label_pers_page">???????????????????????? ??????????????</label>
              <input type="text" class="form-control form-control-sm" name="claim_date" value="' . $claim_date . '" readonly>
            </div>

            <div class="col-md-3">
              <label class="label_pers_page">?????????????? ???????????????????? ??????????????</label>
              <input type="text" class="form-control form-control-sm" name="accept_date" value="' . $accept_date . '" readonly>
            </div>
            <div class="col-md-3">
              <label class="label_pers_page">????????????????????</label>
              <input type="text" class="form-control form-control-sm" name="" value="' . $initiator_txt . '" readonly>
              <input type="hidden" class="form-control form-control-sm" name="initiator" value="' . $initiator . '" readonly>
            </div>

            <div class="col-md-6">
              <label class="label_pers_page">?????????????? ??????????</label>
              <input type="text" class="form-control form-control-sm" name="court_type_text" value="' . $court_type_text . '" readonly>
            </div>

             <div class="col-md-6">
              <label class="label_pers_page">?????????? ??????????</label>
              <input type="text" class="form-control form-control-sm" name="appeal_type_text" value="' . $appeal_type_text . '" readonly>
              <input type="hidden" class="form-control form-control-sm" name="appeal_type_id" value="' . $appeal_type_id . '" readonly>

            </div>

            <div class="col-md-12">
              <label class="label_pers_page">??????????????</label>
              <input type="text" class="form-control form-control-sm" value="' . $court_name . '" readonly />
            </div>

            <div class="col-md-3">
             <label class="label_pers_page">?????????? ??????????????</label>
             <input type="date" class="form-control form-control-sm" name="court_decision_date" required="required" />
            </div>

            <div class="col-md-3">
              <label class="label_pers_page">?????????? ?????????????? ??????????????</label>
             <input type="date" class="form-control form-control-sm" name="court_decision_notification_date"required="required" />
            </div>

            <div class="col-md-6">
             <label class="label_pers_page">?????????? ??????????</label>
            ' . $opt_court_decission . '
            </div>

             <div class="col-md-12">
             <label class="label_pers_page">?????????? ???????????????? ??????????</label>
              <div class="form-group custom-file">
                <input type="file" name="file" class="custom-file-input" id="customFile" required="required" />
                <label class="custom-file-label" for="customFile">?????????????? ??????????</label>
              </div>
            </div> 

            <div class="col-md-12">
              <label class="label_pers_page">????????????????????????????????</label>
              <input type="text" class="form-control form-control-sm" name="comment_to" value="' . $msg_asign . '">
            </div>

          </div>
           
            <input type="hidden" class="form-control form-control-sm" name="apeeal_id" value="' . $appeal_id . '">   
            
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">??????????</button>
          <input type="submit" name="save_court_decission" class="btn btn-primary" form="asign_save" value="????????????????">
        </div>
      </div>
      </form>
    </div>';


		echo $modal_claim;
	}

	################################

	if (isset($_POST['special_change_devhead'])) {

		$case_id = $_POST['special_change_devhead'];
		$modal_aprove_special = '';
		$msg_asign = '?????????? ???????? ?????????????????????????? ?????????????????? ??';
		$chk_special = "SELECT * FROM tb_person a INNER JOIN tb_case b ON a.case_id = b.case_id WHERE a.case_id = $case_id AND a.role = 1";
		$result = $conn->query($chk_special);
		$officer_id = '';
		$special = '';
		$illegal = '';
		$transfer_moj = '';
		$deport_prescurator = '';
		$prison = '';

		if ($result->num_rows > 0) {
			$row_special = $result->fetch_assoc();
			$special = $row_special['special'];
			$illegal = $row_special['illegal_border'];
			$transfer_moj = $row_special['transfer_moj'];
			$deport_prescurator = $row_special['deport_prescurator'];
			$prison = $row_special['prison'];

			$officer_id = $row_special['officer'];

		}

		$special_chk = '';
		if ($special == '1') {
			$special_chk = 'checked';
		}

		$illegal_chk = '';
		if ($illegal == '1') {
			$illegal_chk = 'checked';
		}

		$transfer_chk = '';
		if ($transfer_moj == '1') {
			$transfer_chk = 'checked';
		}

		$deport_chk = '';
		if ($deport_prescurator == '1') {
			$deport_chk = 'checked';
		}

		$prison_chk = '';
		if ($prison == '1') {
			$prison_chk = 'checked';
		}


		$query_user_to_sign = "SELECT * from users WHERE id = $officer_id";
		$officer1 = $conn->query($query_user_to_sign);
		$opt_mslawyer = '<select name="select_officer" id="select_officer" class="form-control form-control-sm">';
		while ($row5 = mysqli_fetch_array($officer1)) {
			$opt_mslawyer .= "<option value=" . $row5['id'] . ">" . $row5['f_name'] . ' ' . $row5['l_name'] . "</option>";
		}
		$opt_mslawyer .= "</select>";

		$modal_aprove_special = '<div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">???????????????? ?????????? ???????? ??????????????????????????</h4>
          <button type="button" class="close" data-dismiss="modal">??</button>
          
        </div>
        <div class="modal-body">
        <form method="POST" action="config/config.php" id="asign_save">
          <div class="col-md-12">
            <input type="hidden" class="form-control" name="cases" value="' . $case_id . '">
            <div class="row">
              <div class="col-md-12">
                <label class="label_pers_page">????????????</label>
                ' . $opt_mslawyer . '
              </div>

              <div class="col-md-12">
                <label class="label_pers_page">????????????????????????????????</label>
                <input type="text" class="form-control form-control-sm" name="comment_to" value="' . $msg_asign . '">
              </div>

              <div class="col-md-3">
                <label class="label_pers_page">?????????????? ?????????????????????????? </label>
                <input type="checkbox" class="form-control" ' . $illegal_chk . ' onclick="return false;" />
              </div>

              <div class="col-md-3">
               <label class="label_pers_page">???????????????????????? (????????????????????????????)  </label>
               <input type="checkbox" class="form-control" ' . $deport_chk . ' onclick="return false;" />
              </div>

              <div class="col-md-3">
               <label class="label_pers_page">?????????????????? (??????????????????) </label>
               <input type="checkbox" class="form-control" ' . $transfer_chk . ' onclick="return false;"/>
              </div>

              <div class="col-md-3">
              <label class="label_pers_page">?????? </label>
              <input type="checkbox" class="form-control" ' . $prison_chk . ' onclick="return false;"/>
              </div>
            </div>
            
            
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">??????????</button>';
		if ($special == '1' && $illegal == '0' && $transfer_moj == '0' && $deport_prescurator == '0' && $prison == '0') {
			$modal_aprove_special .= '<input type="submit" name="save_special_change" class="btn btn-primary" form="asign_save" value="????????????????">';
		}
		$modal_aprove_special .= '</div>
      </div>
      </form>
    </div>

  ';
		echo $modal_aprove_special;

	}

	if (isset($_POST['save_special_change'])) {

		$case_id = $_POST['cases'];
		$user = $_SESSION['user_id'];
		$officer2 = $_POST['select_officer'];
		$comment_to = $_POST['comment_to'];

		$update_case = "UPDATE tb_case SET `special` = '0' WHERE case_id = $case_id";

		if ($conn->query($update_case) === TRUE) {

			$sql_update_process = "UPDATE tb_process SET `actual` = 0 WHERE case_id = $case_id";

			if ($conn->query($sql_update_process) === TRUE) {
				$sql_insert_process = "INSERT INTO `tb_process` 
              (`case_id`, `sign_status`, `sign_by`, `processor`, `comment_to`, `actual`) 
              VALUES ($case_id, '29', '$user', '$officer2', NULLIF('$comment_to', ''), '1')";
				if ($conn->query($sql_insert_process) === TRUE) {

					$sql_notify = "INSERT INTO `tb_notifications`(`comment_subject`, `comment_text`, `comment_status`, `comment_from`, `comment_to`, `case_id`, `note_type`) VALUES ('?????????? ???????? ???????????????? ??', NULLIF('$comment_to', ''), '0', '$user', '$officer2', '$case_id', '1')";

					if ($conn->query($sql_notify) === TRUE) {
						header('location: ../user.php?page=cases&homepage=case_page&case=' . $case_id);
					} else {
						echo "Error: " . $sql_notify . "<br>" . $conn->error;
					}
				} else {
					echo "Error: " . $sql_insert_process . "<br>" . $conn->error;
				}
			} else {
				echo "Error: " . $sql_update_process . "<br>" . $conn->error;
			}
		} else {
			echo "Error: " . $update_case . "<br>" . $conn->error;
		}


	}


	############## translation button onclick ###############
	if (isset($_POST['translate_case'])) {
		$case_id = $_POST['translate_case'];
		$language = $_POST['language'];

		//Creating translation type dropdown
		$query_translation_type = "SELECT * FROM tb_translation_type WHERE trans_type != '????????????????????????????????'";
		$result_translation_type = $conn->query($query_translation_type);
		$opt_translation_type = '<select class="form-control form-control-sm" id="translation_type_select" name="translation_type_select">
                                <option value="0" selected disabled hidden>?????????????? ?????????????????????????? ????????????</option>';
		while ($row_translation_type = $result_translation_type->fetch_assoc()) {
			$opt_translation_type .= '<option value=" ' . $row_translation_type["ttype_id"] . ' "> ' . $row_translation_type["trans_type"] . ' </option>';
		}
		$opt_translation_type .= '</select>';

		//Creating translators' dropdown
		$sql_translator = "SELECT * FROM `tb_translators` WHERE active_status = 1";
		$result_translator = $conn->query($sql_translator);
		$opt_translator = '<select class="form-control form-control-sm" id="select_translator" name="select_translator">';
		while ($row_translator = $result_translator->fetch_assoc()) {
			$opt_translator .= "<option value=" . $row_translator['translator_id'] . ">" . $row_translator['translator_name_arm'] . "</option>";
		}
		$opt_translator .= '</select>';


		$sql_file = "SELECT a.id, a.file_name, a.file_type, a.case_id, a.person_id, b.file_type AS f_type_text, c.f_name_arm, c.l_name_arm, k.f_name_arm AS refugee_name, k.l_name_arm AS refugee_lname FROM files a 
              INNER JOIN tb_file_type b ON a.file_type = b.file_type_id 
              LEFT JOIN tb_person c ON a.person_id = c.personal_id
              LEFT JOIN tb_person k ON a.case_id = k.case_id
              WHERE a.case_id = $case_id AND b.file_filter IN (1,2) AND k.role=1";

		$result_sql_file = $conn->query($sql_file);


		$send_translate = '<div class="modal-dialog modal-xl">    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">???????????????? ??????????????????????????</h4>
          <button type="button" class="close" data-dismiss="modal">??</button>          
        </div>
        <div class="modal-body">
        <form method="POST" action="config/config.php?cmd=send_approve_translate" id="translation_modal">
        <div class="col-md-6">'
			. $opt_translation_type .
			'</div>
        <div class="col-md-12">
        <div class="row">
          <input type="hidden" class="form-control" name="cases" value="' . $case_id . '">
          <div class="col-md-4">
            <label class="label_pers_page">????????????</label>
            ' . $opt_translator . '  
          </div>
          <div class="col-md-4">
            <label class="label_pers_page"> ?????????? </label>
            <input type="text" class="form-control form-control-sm" name="translate_language" id="translate_language" value="' . $language . '" required="required" />
          </div>
          <div class="col-md-2">
            <label class="label_pers_page">?????????????????????? ??/??</label>
            <input type="date" class="form-control form-control-sm" name="service_date" id="service_date" required="required"/>
          </div>
          <div class="form-group col-md-1" id="timeFromDiv">
                <label for="timeFrom" class="label_pers_page">????????????</label>
                <input class="form-control form-control-sm timepicker text-center" jt-timepicker="" time="model.time" time-string="model.timeString"
                       default-time="model.options.defaultTime" time-format="model.options.timeFormat"
                       start-time="model.options.startTime" min-time="model.options.minTime"
                       max-time="model.options.maxTime" interval="model.options.interval"
                       dynamic="model.options.dynamic" scrollbar="model.options.scrollbar"
                       dropdown="model.options.dropdown" id="timeFrom" name="adviceTimeFrom"  placeholder="hh:mm">
                </div>
                <div class="form-group col-md-1" id="timeToDiv">
                    <label for="timeTo" class="label_pers_page">????????????</label>
                    <input class="form-control form-control-sm timepicker text-center" jt-timepicker="" time="model.time" time-string="model.timeString"
                           default-time="model.options.defaultTime" time-format="model.options.timeFormat"
                           start-time="model.options.startTime" min-time="model.options.minTime"
                           max-time="model.options.maxTime" interval="model.options.interval"
                           dynamic="model.options.dynamic" scrollbar="model.options.scrollbar"
                           dropdown="model.options.dropdown" id="timeTo" name="adviceTimeTo"  placeholder="hh:mm">
                </div>
          </div>              
              <div class="col-md-12" id="file_content">
                  <h5 class="sub_title" style="margin-top: 5px; margin-bottom: 5px;"> ???????????? ?????????????????? ?????????????????????? </h5>
                  <table class="table">
                        <tr style=" font-size: 0.8em; color: #324157; text-align: center; vertical-align: middle;">
                          <th>...</th>
                          <th>?????????????????? ??????????</th>
                          <th>?????????????????? ??????????????????</th>
                          <th>??.??.??.</th>
                        </tr>';

		while ($row = $result_sql_file->fetch_assoc()) {
			$file_id = $row['id'];
			$file_type = $row['f_type_text'];
			$file_name = $row['file_name'];
			$personal_id = $row['person_id'];
			$full_name = '';
			$file_path = '../uploads/' . $case_id . '/' . $file_name;
			if (!empty($row['person_id'])) {
				$full_name = $row['f_name_arm'] . ' ' . $row['l_name_arm'];
				$refugeeFullName = $row['refugee_name'] . ' ' . $row['refugee_lname'];
				$file_path = '../uploads/' . $case_id . '/' . $personal_id . '/' . $file_name;
			} else {
				$full_name = '?????????? ????????????????????';
				$query_refugee_full_name = "SELECT f_name_arm, l_name_arm FROM `tb_person` WHERE case_id=$case_id AND role=1";
				$result_full_name = $conn->query($query_refugee_full_name);
				if ($result_full_name->num_rows > 0) {
					$row_full_name = $result_full_name->fetch_assoc();
					$refugeeFullName = $row_full_name['f_name_arm'] . ' ' . $row_full_name['l_name_arm'];
				}
			}


			$send_translate .= '
                <tr style="font-size: 1em; color:#324157; text-align: center;">
                  <td><input type="checkbox" name="send_file[]" value="' . $file_id . '"/></td>
                  <td>' . $file_type . '</td>
                  <td>' . $file_name . '</td>
                  <td>' . $full_name . '</td>
                </tr>';
		}


		$send_translate .= '</table>
         <input type="text" name="caseFullName" hidden value="' . $refugeeFullName . '" />
		 
            </div>
            
                
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">??????????</button>          
          <button type="submit" name="send_approve_translate" translate_type="written" id="send_approve_translate" class="btn btn-primary" form="translation_modal" >????????????????</button>
        </div>
      </div>
      </form>
    </div>

  ';
		echo $send_translate;

	}

	########### Registration of advice translation by officer  ##############
	if (isset($_POST['sendToapproveAdvice'])) {
		//INSERT INTO `tb_translate`(`translate_type`, `user_from`, `user_to`, `translator_company`)
		//INSERT INTO `tb_cover_files`(`type`, `file_name`, `case_id`, `cover_status`, `translation_id`)
		//($language, $serviceDate, '11:40', '12:30', $devHeadFullName, $createrFullName);
		$user_from = $_SESSION['user_id'];
		$receiver_id = '';
		$devHeadFullName = '';
		$createrFullName = mb_substr($_SESSION['user_fName'], 0, 1) . '. ' . $_SESSION['user_lName'];
		$sql_devhead = "SELECT * FROM users WHERE user_type = 'devhead' AND user_status = '1'";
		$result_sql_devhead = $conn->query($sql_devhead);
		if ($result_sql_devhead->num_rows > 0) {
			$roq = $result_sql_devhead->fetch_assoc();
			$receiver_id = $roq['id'];
			$devHeadFName = mb_substr($roq['f_name'], 0, 1) . '. ';
			$devHeadLName = $roq['l_name'];
			$devHeadFullName = $devHeadFName . $devHeadLName;
		}

	}

	########### Request  for  translation   ##############
	if (isset($_GET['cmd']) && $_GET['cmd'] == 'send_approve_translate') {
		//Variables
		$separate_file_id = null;
		$translator = $_POST['select_translator'];
		$language = $_POST['translate_language'];
		$serviceDate = $_POST['service_date'];
		$case_id = $_POST['cases'];
		$caseFullName = $_POST['caseFullName'];
		$newPdfName = '_unsigned.pdf';
		$user_from = $_SESSION['user_id'];
		$receiver_id = '';
		$devHeadFullName = '';
		$adviceTimeFrom = null;
		$adviceTimeTo = null;
		$createrFullName = mb_substr($_SESSION['user_fName'], 0, 1) . '. ' . $_SESSION['user_lName'];
		$translation_type_select = trim($_POST['translation_type_select']);
		if ($_POST['translation_type_select'] == 2) {
			$all_ids = $_POST['send_file'];
			$sheetsCount = count($all_ids);
			$separate_file_id = implode(',', $all_ids);
		} else {
			$adviceTimeFrom = $_POST['adviceTimeFrom'];
			$adviceTimeTo = $_POST['adviceTimeTo'];
		}

		//Selecting Devhead
		$sql_devhead = "SELECT * FROM users WHERE user_type = 'devhead' AND user_status = '1'";
		$result_sql_devhead = $conn->query($sql_devhead);
		if ($result_sql_devhead->num_rows > 0) {
			$roq = $result_sql_devhead->fetch_assoc();
			$receiver_id = $roq['id'];
			$devHeadFName = mb_substr($roq['f_name'], 0, 1) . '. ';
			$devHeadLName = $roq['l_name'];
			$devHeadFullName = $devHeadFName . $devHeadLName;
		}

		//Inserting into tb_translate
		$sql_insert_translate = "INSERT INTO `tb_translate`(`case_id`, `translate_type`, `user_from`, `user_to`, `translator_company`, `file_ids`, `translate_date`, `translate_time_from`, `translate_time_to`, `sign_status`) VALUES ('$case_id', '$translation_type_select', '$user_from', '$receiver_id', '$translator', '$separate_file_id','$serviceDate','$adviceTimeFrom','$adviceTimeTo', '2')";
		if ($conn->query($sql_insert_translate) === TRUE) {
			$last_translation_id = $conn->insert_id;
			$newPdfName = $last_translation_id . $newPdfName;

			//Creating cover PDF file
			require_once __DIR__ . '/vendor/autoload.php';
			// Create an instance of the class:
			$mpdf = new \Mpdf\Mpdf();
			// Write some HTML code:
			$templateName = 'template_' . $translator;
			require('templates/khndragir/' . $translation_type_select . '/' . $templateName . '.php');
			if ($_POST['translation_type_select'] == 2) {
				$pdfPage = $$templateName($language, $caseFullName, $serviceDate, $sheetsCount, $devHeadFullName, $createrFullName);
			} else if ($_POST['translation_type_select'] == 3) {
				$pdfPage = $$templateName($language, $caseFullName, $serviceDate, $adviceTimeFrom, $adviceTimeTo, $devHeadFullName, $createrFullName);
			} else if ($_POST['translation_type_select'] == 4) {
				$pdfPage = $$templateName($language, $caseFullName, $serviceDate, $adviceTimeFrom, $adviceTimeTo, $devHeadFullName, $createrFullName);
			}
			$mpdf->WriteHTML($pdfPage);
			// Output a PDF file directly to the browser
			if (!file_exists('../uploads/' . $case_id . '/cover')) {
				mkdir('../uploads/' . $case_id . '/cover', 0777, true);
			}
			$newCoverFileName = "../uploads/" . $case_id . "/cover/$newPdfName";
//			$mpdf->Output();
//			exit;
			$mpdf->Output("$newCoverFileName", \Mpdf\Output\Destination::FILE);


			$sql_tb_cover = "INSERT INTO `tb_cover_files`(`type`, `file_name`, `case_id`, `cover_status`, `translation_id`, `cover_actual`) 
                          VALUES ('1','$newPdfName', '$case_id', '2', '$last_translation_id', '1')";
			if ($conn->query($sql_tb_cover) === TRUE) {
				$change_process_actual = "UPDATE tb_process SET actual = 0 WHERE case_id = $case_id";
				if ($conn->query($change_process_actual) === TRUE) {
					$sql_new_process = "INSERT INTO `tb_process`(`case_id`, `sign_status`, `sign_by`, `processor`, `comment_to`, `actual`, `comment_status`) VALUES ('$case_id', '30', '$user_from', '$receiver_id', '?????????????? ???? ??????????????????', '1', '1')";
					if ($conn->query($sql_new_process) === TRUE) {
						// $last_request_id = $conn->insert_id;

						if ($_POST['translation_type_select'] != 2) {
							$text_color = '#FFFF00';
							$border_color = '#FF0000';
							$date_from = $_POST['service_date'] . ' ' . $adviceTimeFrom . ':00';
							$date_to = $_POST['service_date'] . ' ' . $adviceTimeTo . ':00';
							$query_translation_calendar = "INSERT INTO `tb_calendar`(`case_id`, `user_id`,  `inter_comment`, `inter_date_from`, `inter_date_to`, `text_color`, `border_color`) VALUES ($case_id,$user_from,'$language', '$date_from', '$date_to', '$text_color', '$border_color')";
							$conn->query($query_translation_calendar);

						}

						notify($conn, '????????????????????????????', '?????????????? ???? ??????????????????', 0, $user_from, $receiver_id, $case_id, '', 1, 0, 'changeLocation', array('cases', 'case_page', 'case', $case_id));

					} else {
						echo "Error: " . $sql_new_process . "<br>" . $conn->error;
					}
				} else {
					echo "Error: " . $change_process_actual . "<br>" . $conn->error;
				}
			} else {
				echo "Error: " . $sql_insert_translate . "<br>" . $conn->error;
			}

		} else {
			echo "Error: " . $sql_tb_cover . "<br>" . $conn->error;
		}


	}

	################## Approving written translation by Devhead #######################

	if (isset($_GET['cmd']) && $_GET['cmd'] == 'send_mail') {

		$case_id = $_POST['case_id_for_email'];
		$officer = $_POST['hidden_officer'];
		$user_from = $_SESSION['user_id'];
		$translation_id = $_POST['hidden_translate'];
		$attachmentFilesArray = [];
		$mail_receiver = '';


		# Get file name
		$newFilename = $translation_id . '_signed.pdf';
		$location = "../uploads/" . $case_id . "/cover";
		$location .= "/" . $newFilename;

		$update_unsign_cover_actual = "UPDATE tb_cover_files SET cover_actual = '0' WHERE case_id = $case_id";
		$result_update_unsign_cover_actual = $conn->query($update_unsign_cover_actual);

		# Upload file
		if (move_uploaded_file($_FILES['signed_cover']['tmp_name'], $location)) {
			$insert_tb_cover = "INSERT INTO `tb_cover_files`(`type`, `file_name`, `case_id`, `cover_status`, `translation_id`, `cover_actual`) VALUES ('1','$newFilename', '$case_id', '3', '$translation_id', '0')";
			if ($conn->query($insert_tb_cover) === TRUE) {
				$change_process_actual = "UPDATE tb_process SET actual = 0 WHERE case_id = $case_id";
				if ($conn->query($change_process_actual) === TRUE) {
					$sql_new_process = "INSERT INTO `tb_process`(`case_id`, `sign_status`, `sign_by`, `processor`, `comment_to`, `actual`, `comment_status`) VALUES ('$case_id', '3', '$user_from', '$officer', '?????????????????????????? ???????????????? ?????????????????? ??', '1', '1')";
					if ($conn->query($sql_new_process) === TRUE) {
						$sql_select_attachments = "SELECT file_ids, translator_company  FROM tb_translate WHERE translate_id = $translation_id";
						$result_select_attachments = $conn->query($sql_select_attachments);
						if ($result_select_attachments->num_rows > 0) {
							$attachments_row = mysqli_fetch_array($result_select_attachments);
							$attachmentFilesIds = $attachments_row['file_ids'];
							$translatorCompany = $attachments_row['translator_company'];

							$sqlReceiverMail = "SELECT email from tb_translators WHERE translator_id = $translatorCompany";
							$resultReceiverMail = $conn->query($sqlReceiverMail);
							if ($resultReceiverMail->num_rows > 0) {
								$mail_receiverRow = mysqli_fetch_assoc($resultReceiverMail);
								$mail_receiver = $mail_receiverRow['email'];
							}

							$sql_attached_files = "SELECT file_path FROM files WHERE id IN($attachmentFilesIds)";
							$result_attached_files = $conn->query($sql_attached_files);
							if ($result_attached_files->num_rows > 0) {
								$attachedFilePaths = [];
								while ($attached_files_row = mysqli_fetch_assoc($result_attached_files)) {
									$attachedFilePaths[] = '../' . $attached_files_row['file_path'];
								}

							}
							array_unshift($attachedFilePaths, $location);
						}
						sendMail($gmail_login, $gmail_pass, $gmail_host, $gmail_port, $mail_receiver, $mail_subject, $mail_body, $attachedFilePaths);
						$query_update_tbtranslate = "UPDATE `tb_translate` SET sign_status = '3', mailed_to_translators = NOW()  WHERE translate_id = $translation_id";
						$conn->query($query_update_tbtranslate);
						notify($conn, '??????????????????????????', '?????????????????????????? ???????????????? ?????????????????? ????', 0, $user_from, $officer, $case_id, '', 1, 0, 'changeLocation', array('cases', 'case_page', 'case', $case_id));
					} else {
						echo "Error: " . $sql_new_process . "<br>" . $conn->error;
					}
				} else {
					echo "Error: " . $change_process_actual . "<br>" . $conn->error;
				}
			} else {
				echo "Error: " . $insert_tb_cover . "<br>" . $conn->error;
			}
		}


	}

	## Single translation info
	######################################
	if (isset($_GET['cmd']) && $_GET['cmd'] === 'translation_info') {
		$translation_case_id = $_GET['case_id'];
		$myObj = new stdClass();
		$data = [];


		$query_translations = " SELECT * FROM `tb_translate` AS T
							    LEFT JOIN 
								(
									SELECT  file_name AS unsigned_file, translation_id 
									FROM `tb_cover_files`
									WHERE cover_status = '2' AND type='1'
								) AS CU	ON T.translate_id = CU.translation_id 
     							LEFT JOIN 
     							(
     							    SELECT  file_name AS signed_file, translation_id 
									FROM `tb_cover_files`
									WHERE cover_status = '3' AND type='1'
     							) AS SC ON T.translate_id = SC.translation_id 
								LEFT JOIN `tb_translation_type` AS TT ON T.translate_type = TT.ttype_id 
								LEFT JOIN `tb_translators` AS TR ON T.translator_company = TR.translator_id
								LEFT JOIN `cover_sign_status` AS CS ON T.sign_status = CS.sign_status_id
								WHERE T.case_id=$translation_case_id ORDER BY T.translate_id DESC ";

		$result_translations = $conn->query($query_translations);
		if ($result_translations->num_rows > 0) {
			while ($row_translations = $result_translations->fetch_assoc()) {
				$translation_info = new stdClass();
				$documents = [];
				$href_unsigned = 'uploads/' . $translation_case_id . '/cover/' . $row_translations['unsigned_file'];
				$href_signed = '';

				//echo $row_translations['file_name']
				$translate_time_from = '-';
				$translate_time_to = '-';
				$approve_date = '-';
				$a_create_value = "?????????????????????? ????????????????";
				$a_approve_value = '';
				if ($row_translations['sign_status'] == '3') {
					$a_approve_value = '???????????????????? ????????????????';
					$href_signed = 'uploads/' . $translation_case_id . '/cover/' . $row_translations['signed_file'];
				}
				if (!empty($row_translations['translate_time_from'])) {
					$translate_time_from = $row_translations['translate_time_from'];
				}
				if (!empty($row_translations['translate_time_to'])) {
					$translate_time_to = $row_translations['translate_time_to'];
				}
				if (!is_null($row_translations['mailed_to_translators'])) {
					list($year, $month, $day) = explode('-', explode(' ', $row_translations['mailed_to_translators'])[0]);
					$approve_date = $day . '.' . $month . '.' . $year;
				}
				list($year, $month, $day) = explode('-', explode(' ', $row_translations['translate_date'])[0]);
				$translate_date = $day . '.' . $month . '.' . $year;
				list($year, $month, $day) = explode('-', explode(' ', $row_translations['filled_in_date'])[0]);
				$filled_in_date = $day . '.' . $month . '.' . $year;


				$translation_info->id = $row_translations['translate_id'];
				$translation_info->type = $row_translations['trans_type'];
				$translation_info->create_date = $filled_in_date;
				$translation_info->approve_date = $approve_date;
				$translation_info->company = $row_translations['translator_name_arm'];
				$translation_info->time_from = $translate_time_from;
				$translation_info->time_to = $translate_time_to;
				$translation_info->sign_status = $row_translations['sign_status_name'];
				$translation_info->date = $translate_date;
				$translation_info->a_create_value = $a_create_value;
				$translation_info->a_create_href = $href_unsigned;
				$translation_info->a_approve_value = $a_approve_value;
				$translation_info->a_approve_href = $href_signed;

				if ($row_translations['file_ids'] != '') {
					$files_array = $row_translations['file_ids'];
					$query_attached_files = "SELECT a.file_name,a.file_path,b.file_type FROM files a 
											 INNER JOIN tb_file_type b ON a.file_type = b.file_type_id WHERE a.id IN ($files_array)";
					$result_attached_files = $conn->query($query_attached_files);
					if ($result_attached_files->num_rows > 0) {
						while ($row_attached_files = $result_attached_files->fetch_assoc()) {
							$single_doc = new stdClass();
							$file_type = $row_attached_files['file_type'];
							$file_path = $row_attached_files['file_path'];

							$single_doc->doc_name = $file_type;
							$single_doc->doc_href = $file_path;
							$documents[] = $single_doc;
						}
					}
				}

				$translation_info->documents = $documents;
				$data[] = $translation_info;
			}

		}
		$myObj->data = $data;
		echo json_encode($myObj);
		exit;
	}


	###########
	//Modal for intermediat note

	if (isset($_POST['inter_note'])) {
		$case_id = $_POST['inter_note'];
			$dec_type = '';

		// $note_type_where = ' ';

		


		// $query_decisions = "SELECT * FROM tb_decisions WHERE case_id = $case_id AND actual = '1'";
		// if($conn->query($query_decisions) -> num_rows > 0){
		// 	$res = $conn->query($query_decisions);
		// 	$row_res = $res->fetch_assoc();
		// 	$dec_type = $row_res['decision_type'];
		// }

		// if ($dec_type == 1) {
		// 	$note_type_where = " WHERE inter_type_id = '1'";
		// }
		// if ($dec_type == 2) {
		// 	$note_type_where = " WHERE inter_type_id = '2'";
		// }
		// 	if ($dec_type == 3) {
		// 	$note_type_where = " WHERE inter_type_id = '3' ";
		// }
		// if ($dec_type == 4) {
		// 	$note_type_where = " WHERE inter_type_id = '4' ";
		// }
		// if ($dec_type == 5) {
		// 	$note_type_where = " WHERE inter_type_id = '5' ";
		// }
		// 	if ($dec_type == 6) {
		// 	$note_type_where = " WHERE inter_type_id = '6' ";
		// }


		$note_type = "SELECT * FROM tb_inter_type";
		$note_type_1 = $conn->query($note_type);
		$opt_note_type = '<select name="inter_type" id="inter_type" class="form-control form-control-sm">
											<option selected disabled hidden value="">?????????????? ?????????????????? ???????????? </option>
		';
		while ($row5 = mysqli_fetch_array($note_type_1)) {
			$opt_note_type .= "<option value=" . $row5['inter_type_id'] . ">"  . $row5['inter_type'] . "</option>";
		}
		$opt_note_type .= "</select>";


		$rec_sql = "SELECT * FROM users WHERE user_type = 'devhead' AND user_status = '1'";
		$res_rec_sql = $conn->query($rec_sql);
		$opt_reciver = '<select name="select_receiver" id="select_receiver" class="form-control form-control-sm">';
		while ($row5 = mysqli_fetch_array($res_rec_sql)) {
			$opt_reciver .= "<option value=" . $row5['id'] . ">" . $row5['f_name'] . ' ' . $row5['l_name'] . "</option>";
		}
		$opt_reciver .= "</select>";

		$msg = "?????????????? ???? ???????????????? ?????????????????? ????????????????????";


		$inter_modal = '';

		$inter_modal .= '<div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">?????????????? ?????????????????? ??????????????????</h5>
          <button type="button" class="close" data-dismiss="modal">??</button>
          
        </div>
        <div class="modal-body">
        <form method="POST" action="config/config.php" id="semi_note" enctype="multipart/form-data">
          <div class="col-md-12">
                      
            <label class="label_pers_page">????????????</label> 
           	<select class="form-control" name="reciver" id="reciver" required=required>
           		<option selected disabled hidden value="">?????????????? ???????????????? </option>
           		<option value="1">?????????????? ????????????????</option>
           		<option value="2">????????????????????</option>
           		<option value="3">?????????????? ???????????????? ?? ????????????????????</option>
           	</select>
           

           	<div class="row">
           	<div class="col-md-6">            
            <label class="label_pers_page">??????????</label> 
           '.$opt_note_type.'
          	</div>
          	<div class="col-md-6">            
          	 <label class="label_pers_page">?????????????? ??????????????</label> 
           	<select class="form-control" name="send_type" id="send_type" required=required>
           		<option selected disabled hidden value="">?????????????? ?????????????? ?????????????? </option>
           		<option value="1">????????</option>
           		<option value="2">????. ????????</option>
           		<option value="3">????????????</option>
           		<option value="4">????????????????????</option>
          	</select>
            </div>
            </div>

            <label class="label_pers_page">?????????? ???????????????????? </label>
            <div class="form-group custom-file">
                <input type="file" name="file" class="custom-file-input" id="customFile" required="required" />
                <label class="custom-file-label" for="customFile">?????????????? ??????????</label>
            </div>

            <label class="label_pers_page">????????????????????????????????</label>
            <textarea class="form-control" rows="3" name="msg_comment"> ' . $msg . ' </textarea>



           
            <input type="hidden" class="form-control form-control-sm" name="case_id" value="' . $case_id . '">
        
           



          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">????????????????</button>
          <input type="submit" name="save_semi" class="btn btn-success" form="semi_note" value="???????????????? ??????????????????">
        </div>
      </div>
      </form>
    </div>
    ';

		echo $inter_modal;

	}


	if (isset($_POST['save_semi'])) {
		$case_id = $_POST['case_id'];
		$adressat = $_POST['addressat'];
		$msg = trim($_POST['msg_comment']);
		$reciver = $_POST['reciver'];
		$reciver_id = '';
		$author_id = $_SESSION['user_id'];
		$inter_type = $_POST['inter_type'];
		$send_type = $_POST['send_type'];
		$inter_msg = $_POST['msg_comment'];


		$rec_sql = "SELECT * FROM users WHERE user_type = 'devhead' AND user_status = '1'";
		$res_rec_sql = $conn->query($rec_sql);
		if ($res_rec_sql->num_rows > 0) {
			$rec_id = $res_rec_sql->fetch_assoc();
			$reciver_id = $rec_id['id'];
		}

		$inter_status = '1';

		$user_from = $_SESSION['user_id'];
		$filename = $_FILES['file']['name'];


		# Location
		$location = "../uploads/" . $case_id . "/inters";

		# create directoy if not exists in upload/ directory
		if (!is_dir($location)) {
			mkdir($location, 0755);
		}

		$location .= "/" . $filename;


		$sql_request = "INSERT INTO `tb_inter`(`case_id`, `author_id`, `inter_status`, `inter_reciever`, `inter_type`, `send_type`) VALUES ('$case_id', '$author_id', '$inter_status', '$reciver', '$inter_type', '$send_type' )";

		if ($conn->query($sql_request) === TRUE) {
			$last_request_id = $conn->insert_id;
			$sql_insert_request_process = "INSERT INTO `tb_inter_process`(`inter_id`, `sender`, `rec_id`, `actual`, `action_type`, `inter_msg`) VALUES ('$last_request_id','$author_id','$reciver_id','1','1', 
				NULLIF('$inter_msg', ''))";

			if ($conn->query($sql_insert_request_process) === TRUE) {
				$last_request_process = $conn->insert_id;

				if (move_uploaded_file($_FILES['file']['tmp_name'], $location)) {
					$sql_insert_request_file = "INSERT INTO `tb_inter_file`( `inter_file`, `inter_process_id`, `inter_file_actual`, `inter_id`) VALUES ('$filename','$last_request_process','1', '$last_request_id')";

					if ($conn->query($sql_insert_request_file) === TRUE) {
						$sql_notify = "INSERT INTO `tb_notifications`(`comment_subject`, `comment_text`, `comment_status`, `comment_from`, `comment_to`, `case_id`, `note_type`) VALUES ('?????? ??????????????????', NULLIF('$inter_msg', ''), '0', '$author_id', '$reciver_id', '$case_id', '1')";

						if ($conn->query($sql_notify) === TRUE) {
							header('location: ../user.php?page=cases&homepage=case_page&case=' . $case_id);
						} else {
							echo "Error: " . $sql_notify . "<br>" . $conn->error;
						}
					}

				} else {
					echo "Error: " . $sql_insert_request_file . "<br>" . $conn->error;
				}
			} else {
				echo "Error: " . $sql_insert_request_process . "<br>" . $conn->error;
			}

		} else {
			echo "Error: " . $sql_request . "<br>" . $conn->error;
		}


	}
	##########################

	if(isset($_POST['return_from_list'])) {

		$case_id  		= $_POST['return_from_list'];
		$inter_id 		= $_POST['inter_id'];
		$msg 					= '?????????????????????? ?? ??????????????????????????';
		$action_type 	= '4';


		$sender_id = $_SESSION['user_id'];
		$reciver_id_sql = "SELECT * FROM tb_inter WHERE inter_id = $inter_id";
		$result_recicer_id_sql = $conn->query($reciver_id_sql);

		if ($result_recicer_id_sql->num_rows > 0) {
			$row_rec_id_sql = $result_recicer_id_sql->fetch_assoc();
			$reciver_id = $row_rec_id_sql['author_id'];

		} else {
			echo "ERROR: reciever not found ";
		}


		$modal_return_from_list = '
			<div class="modal-dialog modal-confirm">
    <div class="modal-content">
      <div class="modal-header flex-column">
        <div class="icon-box">
          <i class="fas fa-undo" style="color: #f15e5e; font-size: 46px; display: inline-block; margin-top: 13px;"></i>
        </div>            
       					<h4 class="modal-title w-100">?????????????????????? ?????????????????? ??????????????????</h4>  
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
     <form action="config/config.php" method="POST" id="return_from_list">
      <div class="modal-body">
        <input type="hidden" name="hidden_inter_id"  value="' . $inter_id . '">
        <input type="hidden" name="case_id_inter" id="delete_case_id" value="' . $case_id . '">
        
            <label class="label_pers_page">????????????????????????????????</label>
            <textarea class="form-control" rows="3" name="inter_msg"> ' . $msg . ' </textarea>
      </div>
      <div class="modal-footer justify-content-center">
       		<button type="button" class="btn btn-default" data-dismiss="modal">????????????????</button>
          <input type="submit" name="dev_return_from_list" class="btn btn-success" form="return_from_list" value="??????????????????????">
      </div>
      </form>
    </div>
  </div>';

    echo $modal_return_from_list;
	}



	##########################

	//cancel inter and return to author.

	if (isset($_POST['cancel_inter']) || isset($_POST['dev_return_from_list'])) {

		$case_id = $_POST['case_id_inter'];
		$inter_id = $_POST['hidden_inter_id'];
		$inter_msg = $_POST['inter_msg'];
		$action_type = '4';


		$sender_id = $_SESSION['user_id'];
		$reciver_id_sql = "SELECT * FROM tb_inter_process WHERE inter_id = $inter_id AND actual = 1";
		$result_recicer_id_sql = $conn->query($reciver_id_sql);

		if ($result_recicer_id_sql->num_rows > 0) {
			$row_rec_id_sql = $result_recicer_id_sql->fetch_assoc();
			$reciver_id = $row_rec_id_sql['sender'];

		} else {
			echo "ERROR: reciever not found ";
		}

		$update_inter_process_actual = "UPDATE tb_inter_process SET actual = '0' WHERE inter_id = $inter_id";
		if ($conn->query($update_inter_process_actual) === TRUE) {
			$insert_new_inter_process = "INSERT INTO `tb_inter_process`(`inter_id`, `sender`, `rec_id`, `actual`, `action_type`, `inter_msg`) VALUES ('$inter_id','$sender_id','$reciver_id','1','$action_type', 
				NULLIF('$inter_msg', ''))";
			if ($conn->query($insert_new_inter_process) === TRUE) {
				$sql_notify = "INSERT INTO `tb_notifications`(`comment_subject`, `comment_text`, `comment_status`, `comment_from`, `comment_to`, `case_id`, `note_type`) VALUES ('???????????????????? ?????????????????????? ??', NULLIF('$inter_msg', ''), '0', '$sender_id', '$reciver_id', '$case_id', '1')";
				if ($conn->query($sql_notify) === TRUE ) {
					header('location: ../user.php?page=cases&homepage=case_page&case=' . $case_id);
				} else {
					echo "Error: " . $sql_notify . "<br>" . $conn->error;
				}

			} else {
				echo "Error: " . $insert_new_inter_process . "<br>" . $conn->error;
			}
		} else {
			echo "Error: " . $update_inter_process_actual . "<br>" . $conn->error;
		}

	}


	###########
	// edit returned inter

	if (isset($_POST['edit_inter']) || isset($_POST['eidt_from_list'])) {
		$case_id = $_POST['edit_inter'];
		$inter_id = $_POST['inter_id'];

		$inter_type_id = '';
		$inter_receiver_id = '';
		$inter_send_type_id = '';
		$inter_edit_msg = '???????????????????????? ???? ???????????????????? ?????????????????? ??????????????????';

		$sql_inter = "SELECT a.inter_id, a.case_id, a.author_id, a.inter_status, a.inter_reciever, a.inter_type, a.send_type, b.inter_send_type, c.inter_reciever_text, d.inter_process_id, d.sender, d.rec_id, d.actual, d.actioned, d.action_type, d.inter_msg, e.action_type, e.inter_action_type_id, f.inter_type_id, f.inter_type, g.inter_file_id, g.inter_file, g.inter_process_id, g.inter_file_actual, g.uploaded FROM tb_inter a INNER JOIN tb_inter_send_type b ON a.send_type = b.inter_send_type_id INNER JOIN tb_inter_recivers c ON a.inter_reciever = c.inter_reciever_id INNER JOIN tb_inter_process d ON a.inter_id = d.inter_id INNER JOIN tb_inter_action_types e ON d.action_type = e.inter_action_type_id INNER JOIN tb_inter_type f ON a.inter_type = f.inter_type_id LEFT JOIN (SELECT * FROM tb_inter_file WHERE inter_file_actual = 1) AS g ON g.inter_id = a.inter_id WHERE a.inter_id = $inter_id AND d.actual = 1";

		$result_inter = $conn->query($sql_inter);

		if ($result_inter->num_rows > 0) {
			$row_inter = $result_inter->fetch_assoc();
			$inter_type_id = $row_inter['inter_type_id'];
			$inter_receiver_id = $row_inter['inter_reciever'];
			$inter_send_type_id = $row_inter['send_type'];
		}


		$sql_reciver = "SELECT * FROM tb_inter_recivers";
		$result_reciever = $conn->query($sql_reciver);
		$optreciver = '<select name="select_receiver" id="select_receiver" class="form-control form-control-sm" required=required>';
		while ($row = $result_reciever->fetch_assoc()) {

			if ($row['inter_reciever_id'] == $inter_receiver_id) {
				$optreciver .= "<option selected=\"selected\" value=" . $row['inter_reciever_id'] . ">" . $row['inter_reciever_text'] . "</option>";
			} else {
				$optreciver .= "<option value=" . $row['inter_reciever_id'] . ">" . $row['inter_reciever_text'] . "</option>";
			}
		}
		$optreciver .= "</select>";

		$sql_inter_type = "SELECT * FROM tb_inter_type";
		$result_inter_type = $conn->query($sql_inter_type);
		$opt_inter_type = '<select name="select_inter_type" id="select_inter_type" class="form-control form-control-sm" required=required>';
		while ($row1 = $result_inter_type->fetch_assoc()) {

			if ($row1['inter_type_id'] == $inter_type_id) {
				$opt_inter_type .= "<option selected=\"selected\" value=" . $row1['inter_type_id'] . ">" . $row1['inter_type'] . "</option>";
			} else {
				$opt_inter_type .= "<option value=" . $row1['inter_type_id'] . ">" . $row1['inter_type'] . "</option>";
			}
		}
		$opt_inter_type .= "</select>";

		$sql_inter_send_type = "SELECT * FROM tb_inter_send_type";
		$result_inter_send_type = $conn->query($sql_inter_send_type);
		$opt_inter_send_type = '<select name="select_inter_send_type" id="select_inter_send_type" class="form-control form-control-sm" required=required>';
		while ($row2 = $result_inter_send_type->fetch_assoc()) {

			if ($row2['inter_send_type_id'] == $inter_send_type_id) {
				$opt_inter_send_type .= "<option selected=\"selected\" value=" . $row2['inter_send_type_id'] . ">" . $row2['inter_send_type'] . "</option>";
			} else {
				$opt_inter_send_type .= "<option value=" . $row2['inter_send_type_id'] . ">" . $row2['inter_send_type'] . "</option>";
			}
		}
		$opt_inter_send_type .= "</select>";


		$edit_inter_modal = '<div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">???????????????? ?????????????????? ??????????????????</h5>
          <button type="button" class="close" data-dismiss="modal">??</button>
          
        </div>
        <div class="modal-body">
        <form method="POST" action="config/config.php" id="semi_edit_note" enctype="multipart/form-data">
          <div class="col-md-12">
                      
            <label class="label_pers_page">????????????</label> 
           	' . $optreciver . '
           

           	<div class="row">
           	<div class="col-md-6">            
            <label class="label_pers_page">??????????</label> 
           	' . $opt_inter_type . '
          	</div>
          	<div class="col-md-6">            
          	 <label class="label_pers_page">?????????????? ??????????????</label> 
           	' . $opt_inter_send_type . '
            </div>
            </div>

            <label class="label_pers_page">?????????? ???????????????????? </label>
            <div class="form-group custom-file">
                <input type="file" name="file" class="custom-file-input" id="customFile" required="required" />
                <label class="custom-file-label" for="customFile">?????????????? ??????????</label>
            </div>

            <label class="label_pers_page">????????????????????????????????</label>
            <textarea class="form-control" rows="3" name="msg_comment">' . $inter_edit_msg . ' </textarea>



           
            <input type="hidden" class="form-control form-control-sm" name="case_id" value="' . $case_id . '">
        		<input type="hidden" class="form-control form-control-sm" name="inter_id" value="' . $inter_id . '">
           



          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">????????????????</button>
          <input type="submit" name="save_edit_semi" class="btn btn-success" form="semi_edit_note" value="???????????????? ??????????????????">
        </div>
      </div>
      </form>
    </div>';

		echo $edit_inter_modal;


	}

	//save and send inter edit

	if (isset($_POST['save_edit_semi'])) {
		$case_id = $_POST['case_id'];
		$inter_id = $_POST['inter_id'];
		$adressat = $_POST['select_receiver'];
		$msg = trim($_POST['msg_comment']);
		$reciver_id = '';
		$author_id = $_SESSION['user_id'];
		$inter_type = $_POST['select_inter_type'];
		$send_type = $_POST['select_inter_send_type'];


		$rec_sql = "SELECT * FROM users WHERE user_type = 'devhead' AND user_status = '1'";
		$res_rec_sql = $conn->query($rec_sql);
		if ($res_rec_sql->num_rows > 0) {
			$rec_id = $res_rec_sql->fetch_assoc();
			$reciver_id = $rec_id['id'];
		}

		$filename = $_FILES['file']['name'];


		# Location
		$location = "../uploads/" . $case_id . "/inters";

		# create directoy if not exists in upload/ directory
		if (!is_dir($location)) {
			mkdir($location, 0755);
		}

		$location .= "/" . $filename;


		$updtate_inter_process = "UPDATE tb_inter_process SET actual = 0 WHERE inter_id = $inter_id";

		if ($conn->query($updtate_inter_process) === TRUE) {
			$update_file_actual = "UPDATE tb_inter_file SET inter_file_actual = 0 WHERE inter_id = $inter_id";

			if ($conn->query($update_file_actual) === TRUE) {
				$sql_insert_request_process = "INSERT INTO `tb_inter_process`(`inter_id`, `sender`, `rec_id`, `actual`, `action_type`, `inter_msg`) VALUES ('$inter_id','$author_id','$reciver_id','1','1', 
				NULLIF('$msg', ''))";

				if ($conn->query($sql_insert_request_process) === TRUE) {
					$last_request_process = $conn->insert_id;
					if (move_uploaded_file($_FILES['file']['tmp_name'], $location)) {
						$sql_insert_request_file = "INSERT INTO `tb_inter_file`( `inter_file`, `inter_process_id`, `inter_file_actual`, `inter_id`) VALUES ('$filename','$last_request_process', '1', '$inter_id')";

						if ($conn->query($sql_insert_request_file) === TRUE) {
							$sql_notify = "INSERT INTO `tb_notifications`(`comment_subject`, `comment_text`, `comment_status`, `comment_from`, `comment_to`, `case_id`, `note_type`) VALUES ('?????????????????? ??????????????????', NULLIF('$msg', ''), '0', '$author_id', '$reciver_id', '$case_id', '1')";

							if ($conn->query($sql_notify) === TRUE) {
								$update_inter_info = "UPDATE tb_inter SET `inter_reciever` = '$adressat', `inter_type` = '$inter_type', `send_type` = '$send_type' WHERE inter_id = $inter_id";
								if ($conn->query($update_inter_info) === TRUE) {
									header('location: ../user.php?page=cases&homepage=case_page&case=' . $case_id);
								} else {
									echo "Error: " . $sql_notify . "<br>" . $conn->error;
								}
							} else {
								echo "Error: " . $sql_notify . "<br>" . $conn->error;
							}


						}

					}

				} else {
					echo "Error: " . $sql_insert_request_file . "<br>" . $conn->error;
				}
			} else {
				echo "Error: " . $update_file_actual . "<br>" . $conn->error;
			}
		} else {
			echo "Error: " . $updtate_inter_process . "<br>" . $conn->error;
		}


	}
	##########################

	//cancel inter


	if (isset($_POST['close_inter']) || isset($_POST['close_from_list'])) {
		$case_id = $_POST['close_inter'];
		$inter_id = $_POST['inter_id'];


		$inter_cancel_modal = '
<div class="modal-dialog modal-confirm">
    <div class="modal-content">
      <div class="modal-header flex-column">
        <div class="icon-box">
          <i class="fa fa-trash-alt" style="color: #f15e5e; font-size: 46px; display: inline-block; margin-top: 13px;"></i>
        </div>            
       					<h4 class="modal-title w-100">?????????????? ????????????????????</h4>  
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
     <form action="config/config.php" method="POST" id="delete_inter">
      <div class="modal-body">
        <input type="hidden" name="over_inter_inter_id"  value="' . $inter_id . '">
        <input type="hidden" name="over_inter_case_id" id="delete_case_id" value="' . $case_id . '">
        
        <p>???????????????????????????? ?????????????????? ????
          ???????????????????? ?????????????? ???????????????????? ?????????????????? ???????????????????? ???????? ????????????????
        </p>

      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">????????????????</button>
        <button type="submit" class="btn btn-danger" name="over_inter" form="delete_inter">??????????????</button>
      </div>
      </form>
    </div>
  </div>

';


		echo $inter_cancel_modal;

	}

	if (isset($_POST['over_inter'])) {
		$inter_id = $_POST['over_inter_inter_id'];
		$case_id = $_POST['over_inter_case_id'];

		$update_tb_inter = "UPDATE tb_inter SET inter_status = 2 WHERE inter_id = $inter_id";
		if ($conn->query($update_tb_inter) === TRUE) {
			$update_inter_process = "UPDATE tb_inter_process SET actual = 0 WHERE inter_id = $inter_id";
			if ($conn->query($update_inter_process) === TRUE) {

				$update_inter_file = "UPDATE tb_inter_file SET inter_file_actual = 0 WHERE inter_id = $inter_id";

				if ($conn->query($update_inter_file) === TRUE) {
					header('location: ../user.php?page=cases&homepage=case_page&case=' . $case_id);
				} else {
					echo "Error: " . $update_inter_file . "<br>" . $conn->error;
				}

			} else {
				echo "Error: " . $update_inter_process . "<br>" . $conn->error;
			}
		} else {
			echo "Error: " . $update_tb_inter . "<br>" . $conn->error;
		}

	}


	########################
	// approve inter by devhead

	if (isset($_POST['approve_send_inter'])) {
		$case_id = $_POST['case_id_inter'];
		$inter_id = $_POST['hidden_inter_id'];
		$msg = $_POST['inter_msg'];
		$sender_id = $_SESSION['user_id'];
		$inter_type_id = $_POST['hidden_type_id'];
		$inter_type_letter = '';

			if($inter_type_id == 1){
				$inter_type_letter = '??';				
			}
			if($inter_type_id == 2){
				$inter_type_letter = '??';				
			}
			if($inter_type_id == 3){
				$inter_type_letter = '??';				
			}
			if($inter_type_id == 4){
				$inter_type_letter = '????';				
			}
			if($inter_type_id == 5){
				$inter_type_letter = '????';				
			}
			if($inter_type_id == 6){
				$inter_type_letter = '??';				
			}

		$rec_sql = "SELECT * FROM users WHERE user_type = 'general' AND user_status = 1";
		$res_rec_sql = $conn->query($rec_sql);
		if ($res_rec_sql->num_rows > 0) {
			$rec_id = $res_rec_sql->fetch_assoc();
			$reciver_id = $rec_id['id'];
		}


		$uot_num = '';

	 $check_inters = "SELECT * FROM tb_inter WHERE case_id = $case_id AND inter_status IN (0,2)";
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

    $out_num = "????/??-" . $inter_type_letter . '-' . $inter_id . '(' . $final_id . ')';




		$filename = $_FILES['file']['name'];


		# Location
		$location = "../uploads/" . $case_id . "/inters/" . $inter_id;

		# create directoy if not exists in upload/ directory
		if (!is_dir($location)) {
			mkdir($location, 0755);
		}

		$location .= "/" . $filename;

		$update_inter_process = "UPDATE tb_inter_process SET actual = '0' WHERE inter_id = $inter_id";
		if ($conn->query($update_inter_process) === TRUE) {

			$update_inter_file = "UPDATE tb_inter_file SET inter_file_actual = '0' WHERE inter_id = $inter_id";

			if ($conn->query($update_inter_file) === TRUE) {
				$insert_new_inter_process = "INSERT INTO `tb_inter_process`(`inter_id`, `sender`, `rec_id`, `actual`, `action_type`, `inter_msg`) VALUES ('$inter_id','$sender_id','$reciver_id','1','2', 
				NULLIF('$msg', ''))";

				if ($conn->query($insert_new_inter_process) === TRUE) {
					$last_request_process = $conn->insert_id;
					if (move_uploaded_file($_FILES['file']['tmp_name'], $location)) {
						$sql_insert_request_file = "INSERT INTO `tb_inter_file`( `inter_file`, `inter_process_id`, `inter_file_actual`, `inter_id`) VALUES ('$filename','$last_request_process', '1', '$inter_id')";

						if ($conn->query($sql_insert_request_file) === TRUE) {

							$sql_notify = "INSERT INTO `tb_notifications` (`comment_subject`, `comment_text`, `comment_status`, `comment_from`, `comment_to`, `case_id`, `note_type`) VALUES ('?????? ????????????????????', NULLIF('$msg', ''), '0', '$sender_id', '$reciver_id', '$case_id', '1')";

							if ($conn->query($sql_notify) === TRUE) {
								
								$update_tb_inter = "UPDATE tb_inter SET out_num = '$out_num' WHERE inter_id = $inter_id";

								if($conn->query($update_tb_inter) === TRUE){
								 header('location: ../user.php?page=cases&homepage=case_page&case=' . $case_id);	
								}
								else
								{
									echo "Error: " . $update_tb_inter . "<br>" . $conn->error;
								}
								
							} else {
								echo "Error: " . $sql_notify . "<br>" . $conn->error;
							}


						} else {
							echo "Error: " . $sql_insert_request_file . "<br>" . $conn->error;
						}

					}
				} else {
					echo "Error: " . $insert_new_inter_process . "<br>" . $conn->error;
				}

			} else {
				echo "Error: " . $update_inter_file . "<br>" . $conn->error;
			}

		} else {
			echo "Error: " . $update_inter_process . "<br>" . $conn->error;
		}


	}


	#######################
	//send inter by general

	if (isset($_POST['general_case'])) {


		$case_id = $_POST['general_case'];
		$inter_id = $_POST['general_inter'];


		$sql_inter = "SELECT a.inter_id, a.case_id, a.author_id, a.inter_status, a.inter_reciever, a.inter_type, a.send_type, a.out_num, b.inter_reciever_text, c.inter_process_id, c.sender, c.rec_id, c.actual, c.actioned, c.action_type AS ACTION_TYPE_ID, c.inter_msg, d.inter_type AS INTER_TYPE_TEXT, f.action_type AS ACTION_TYPE_TEXT, e.inter_send_type AS SEND_TYPE_TEXT, g.inter_file_id, g.inter_file, g.inter_process_id, g.inter_file_actual, g.uploaded, h.RA_marz, h.RA_community, h.RA_settlement, h.RA_street, h.RA_building, h.RA_apartment, h.contact_tel, h.contact_email, i.ADM1_ARM, j.ADM3_ARM, k.ADM4_ARM, l.lawyer_id, l.lawyer_name, l.lawyer_surname, l.lawyer_tel, l.lawyer_address, l.lawyer_email 
FROM tb_inter a 
INNER JOIN tb_inter_recivers b ON a.inter_reciever = b.inter_reciever_id 
INNER JOIN tb_inter_process c ON a.inter_id = c.inter_id
INNER JOIN tb_inter_type d ON a.inter_type = d.inter_type_id
INNER JOIN tb_inter_send_type e ON a.send_type = e.inter_send_type_id
LEFT JOIN (SELECT * FROM tb_inter_file WHERE inter_file_actual = 1) AS g ON g.inter_id = a.inter_id
INNER JOIN tb_inter_action_types f ON c.action_type = f.inter_action_type_id
INNER JOIN tb_case h ON a.case_id = h.case_id
INNER JOIN tb_marz i ON h.RA_marz = i.marz_id
INNER JOIN tb_arm_com j ON h.RA_community = j.community_id
INNER JOIN tb_settlement k ON h.RA_settlement = k.settlement_id
LEFT JOIN tb_lawyer l ON l.case_id = a.case_id
WHERE
c.actual = 1 AND a.inter_id = $inter_id";

		$result_inter = $conn->query($sql_inter);

		if ($result_inter->num_rows > 0) {
			$row_inter = $result_inter->fetch_assoc();

			$inter_sender_id = $row_inter['sender'];
			$inter_receiver_id = $row_inter['rec_id'];
			$filename = $row_inter['inter_file'];
			$action_type_id = $row_inter['ACTION_TYPE_ID'];
			$action_type_text = $row_inter['ACTION_TYPE_TEXT'];
			$inter_msg = $row_inter['inter_msg'];
			$inter_status_id = $row_inter['inter_status'];
			$out_num = $row_inter['out_num'];
			if ($inter_status_id == 1) {
				$inter_status_text = '??????????????';
			}
			if ($inter_status_id == 2) {
				$inter_status_text = '????????????????';
			}

			$author = $row_inter['author_id'];

			$inter_addresat_id = $row_inter['inter_reciever'];
			$inter_addresat_text = $row_inter['inter_reciever_text'];

			$marz = $row_inter['ADM1_ARM'];
			$community = $row_inter['ADM3_ARM'];
			$bnakavayr = $row_inter['ADM4_ARM'];
			$street = $row_inter['RA_street'];
			$building = $row_inter['RA_building'];
			$aprt = $row_inter['RA_apartment'];

			if ($row_inter['RA_marz'] == 1) {
				$community = ' ';
				$bnakavayr = ' ';
			}


			$case_address = $marz . ' ' . $community . ' ' . $bnakavayr . ' ' . $street . ' ' . $building . ' ' . $aprt;
			if (!empty($row_inter['contact_tel'])) {
				$contact_tel = $row_inter['contact_tel'];
			} else {
				$contact_tel = "?????????? ????";
			}


			if (!empty($row_inter['contact_email'])) {
				$contact_email = $row_inter['contact_email'];
			} else {
				$contact_email = "?????????? ????";
			}


			$lawyer_address = ' ';
			$lawyer_name = ' ';
			$lawyer_surname = ' ';
			$lawyer_tel = ' ';
			$lawyer_email = ' ';

			if (!empty($row_inter['lawyer_id'])) {
				$lawyer_address = $row_inter['lawyer_address'];
				$lawyer_name = $row_inter['lawyer_name'] . ' ' . $row_inter['lawyer_surname'];
				$lawyer_tel = $row_inter['lawyer_tel'];
				$lawyer_email = $row_inter['lawyer_email'];
			}


			$inter_type_id = $row_inter['inter_type'];
			$inter_type_text = $row_inter['INTER_TYPE_TEXT'];
			$over_case = '';

			if($inter_type_id == 3){
				$sql_dec_type = "SELECT * FROM tb_decisions WHERE case_id = $case_id AND actual = '1'";
				$result = $conn->query($sql_dec_type);
				$row = $result->fetch_assoc();
				$dec_type_id = $row['decision_type'];

				if ($dec_type_id == 3) {
					$over_case = '1';
				}
			}

			$send_type_id = $row_inter['send_type'];

		  $send_type_text = $row_inter['SEND_TYPE_TEXT'];

		  if ($send_type_id == 2) 
		  {
				$send_button = '<input type="submit" name="send_semi_email" id="disabledSpinner" class="btn btn-success" form="semi_edit_note" value="???????????? ????????????????????????">';
			} 
			else 
			{
				$send_button = '<input type="submit" name="send_semi_usual" id="disabledSpinner" class="btn btn-success" form="semi_edit_note" value="????????????">';
			}

			$inter_msg_out = $row_inter['inter_msg'];
		}


		$inter_sent_modal = '
<div class="modal-dialog modal-xl">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">????????????????????</h5>
          <button type="button" class="close" data-dismiss="modal">??</button>
          
        </div>
        <div class="modal-body">
        <form method="POST" action="config/config.php" id="semi_edit_note" enctype="multipart/form-data">
          <div class="col-md-12">
                      
            <input type="hidden" value="' . $author . '" name="author" />
            <input type="hidden" value="' . $over_case . '" name="over_case" />
            <div class ="row">

            	<div class="col-md-2">
            		<label class="label_pers_page">???????? # </label>
            		<input  class="form-control form-control-sm" name="case_id" value="' . $case_id . '" readonly />
            	</div>

            	<div class="col-md-2">
            		<label class="label_pers_page">???????? # </label>
            		<input  class="form-control form-control-sm" name="out_num" value="' . $out_num . '" readonly />
            		<input  type="hidden" class="form-control form-control-sm" name="inter_id" value="' . $inter_id . '" readonly />
            	</div>

            	<div class="col-md-8">
            		<label class="label_pers_page">?????????????????? ???????????? </label>
            		<input type="text" class="form-control form-control-sm" name="" value="' . $inter_type_text . '" readonly />
            		<input type="hidden" name="inter_type_id" value="' . $inter_type_id . '" />
            	</div>

            	<div class="col-md-4">
            		<label class="label_pers_page">???????????????????? </label>
            		<input type="text" class="form-control form-control-sm" name="action_type_text" value="' . $action_type_text . '" readonly />
            		<input type="hidden" name="action_type_id" value="' . $action_type_id . '">
            	</div>

            	<div class="col-md-8">
            		<label class="label_pers_page">???????????? </label>
            		<input  class="form-control form-control-sm" name="inter_addressat_text" value="' . $inter_addresat_text . '" readonly />
            	</div>

            	<div class="col-md-4">
            		<label class="label_pers_page">?????????????? ?????????????? </label>
            		<input  class="form-control form-control-sm" name="send_type_id" value="' . $send_type_text . '" readonly />
            	</div>


				<div class="col-md-8">
            		<label class="label_pers_page">???????????????????? </label>
            		<a href="uploads/' . $case_id . '/inters/'. $inter_id . '/' . $filename . '" class="form-control form-control-sm" download readonly > <i class="fas fa-download"></i>?????????????????? ???????????????????? </a>
            	    <input type="hidden" name="mail_notification_file" value="uploads/' . $case_id . '/inters/' .$inter_id. '/' . $filename . '" />
            	</div>
             </div>	
            	<h5 class="sub_title">?????????????? ?????????????? ?????????????????????? ????????????????</h5>

            <div class="row">
            		<div class="col-md-3">
            		  <label class="label_pers_page">?????????????????????????? </label>
            		  <input  class="form-control form-control-sm" name="contact_tel" value="' . $contact_tel . '" readonly />
            	  </div>

            	  <div class="col-md-3">
            		  <label class="label_pers_page">????.???????? </label>
            		  <input  class="form-control form-control-sm" name="contact_email" value="' . $contact_email . '">
            	  </div>

            	  <div class="col-md-6">
            		  <label class="label_pers_page">?????????? </label>
            		  <input  class="form-control form-control-sm" name="case_address" value="' . $case_address . '">
            	  </div>

            </div>	

            <h5 class="sub_title">?????????????????? ????????????????????</h5>

            <div class="row">
            		<div class="col-md-12">
            		  <label class="label_pers_page">?????????????????? ??.??.??. </label>
            		  <input  class="form-control form-control-sm" name="lawyer_name" value="' . $lawyer_name . '">
            	  </div>

            	  <div class="col-md-3">
            		  <label class="label_pers_page"> ??????????????????????????</label>
            		  <input  class="form-control form-control-sm" name="lawyer_tel" value="' . $lawyer_tel . '">
            	  </div>

            	  <div class="col-md-3">
            		  <label class="label_pers_page"> ????.???????? </label>
            		  <input  class="form-control form-control-sm" name="lawyer_email" value="' . $lawyer_email . '">
            	  </div>

            	  <div class="col-md-6">
            		  <label class="label_pers_page"> ??????????</label>
            		  <input  class="form-control form-control-sm" name="lawyer_address" value="' . $lawyer_address . '">
            	  </div>

            </div>	
           
           
     
           



          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">????????????????</button>' . $send_button;

		$inter_sent_modal .= '</div>
      </div>
      </form>
    </div>

';


		echo $inter_sent_modal;


	}

	if (isset($_POST['send_semi_usual']) || isset($_POST['send_semi_email'])) {
		$inter_id = $_POST['inter_id'];
		$case_id = $_POST['case_id'];
		$rec_id = '0';
		$sender = $_SESSION['user_id'];
		$msg = '?????????????? ??';
		$reciver_id = $_POST['author'];
    $attachment_file =[];
    $action_type = '3';
    $over_case = $_POST['over_case'];

    $sql_decision_type = "SELECT * FROM tb_decisions WHERE case_id = $case_id AND actual = 1";
    $result_decisions = $conn->query($sql_decision_types);
    	if ($result_decisions -> num_rows > 0) {
    		$row_decisions = $result_decisions->fetch_assoc();
    		$decision_type = $row_decisions['decision_type'];
    	}


		if (isset($_POST['send_semi_email'])) {
			$mail_subject='?????????????????? ???? ?????????????????? ?????????????????????? ????????????';
			$mail_body = '?????????????? ?????? ???????????????? ?????????? ??????????????????????';
			$attachment_file[] = '../'.$_POST['mail_notification_file'];
			$note_date = date('Y-m-d');
			if(!empty($_POST['contact_email'])){
				$contact_email[] = $_POST['contact_email'];
				if(!empty($_POST['lawyer_email'])){
					$contact_email[]=$_POST['lawyer_email'];
				}

				sendMail($gmail_login, $gmail_pass, $gmail_host, $gmail_port, $contact_email, $mail_subject, $mail_body, $attachment_file);

				$sql_inter_notified = "INSERT INTO `tb_inter_notified`(`notified_date`, `inter_id`) VALUES ('$note_date', '$inter_id')";
  			if($conn->query($sql_inter_notified) === TRUE){
				$update_inter_status = "UPDATE tb_inter SET inter_status = '2' WHERE inter_id = $inter_id";
					if($conn->query($update_inter_status) === TRUE)
					{
						$update_tb_case = "UPDATE tb_case SET case_status = '3' WHERE case_id = $case_id";
						$result = $conn->query($update_tb_case);
					}
				$action_type = '5';
				$msg = '?????????????????? ??';
				}
				else
				{
					echo "Error: " . $sql_inter_notified . "<br>" . $conn->error;
				}
			}

		}else {
				$update_inter_status = "UPDATE tb_inter SET inter_status = '0' WHERE inter_id = $inter_id";
		}

		if($over_case == 1){
			$update_tb_case = "UPDATE tb_case SET case_status = '3' WHERE case_id = $case_id";
			$result = $conn->query($update_tb_case);
		}


		
		if ($conn->query($update_inter_status) === TRUE) {
			$update_inter_process = "UPDATE tb_inter_process SET actual = '0' WHERE inter_id = $inter_id";

			if ($conn->query($update_inter_process) === TRUE) {

				$insert_new_inter_process = "INSERT INTO `tb_inter_process`(`inter_id`, `sender`, `rec_id`, `actual`, `action_type`, `inter_msg`) VALUES ('$inter_id','$sender','$rec_id','1', '$action_type','$msg')";

				if ($conn->query($insert_new_inter_process) === TRUE) {
					$sql_notify = "INSERT INTO `tb_notifications` (`comment_subject`, `comment_text`, `comment_status`, `comment_from`, `comment_to`, `case_id`, `note_type`) VALUES ('?????????????????????? ?????????????? ??', NULLIF('$msg', ''), '0', '$sender', '$reciver_id', '$case_id', '1')";

					if ($conn->query($sql_notify) === TRUE) {

						if ($conn->query($sql_notify) === TRUE) {
							
							header('location: ../user.php?page=cases&homepage=general_list');
						} else {
							echo "Error: " . $sql_notify . "<br>" . $conn->error;
						}
					} else {
						echo "Error: " . $sql_notify . "<br>" . $conn->error;
					}

				} else {
					echo "Error: " . $insert_new_inter_process . "<br>" . $conn->error;
				}

			} else {
				echo "Error: " . $update_inter_process . "<br>" . $conn->error;
			}

		} else {
			echo "Error: " . $update_inter_status . "<br>" . $conn->error;
		}


	}




if(isset($_POST['general_case_approve'])){

	$case_id = $_POST['general_case_approve'];
	$inter_id = $_POST['general_inter'];

	$sql_inter = "SELECT a.inter_id, a.case_id, a.author_id, a.inter_status, a.inter_reciever, a.inter_type, a.send_type, b.inter_reciever_text, c.inter_process_id, c.sender, c.rec_id, c.actual, c.actioned, c.action_type AS ACTION_TYPE_ID, c.inter_msg, d.inter_type AS INTER_TYPE_TEXT, f.action_type AS ACTION_TYPE_TEXT, e.inter_send_type AS SEND_TYPE_TEXT, g.inter_file_id, g.inter_file, g.inter_process_id, g.inter_file_actual, g.uploaded, h.RA_marz, h.RA_community, h.RA_settlement, h.RA_street, h.RA_building, h.RA_apartment, h.contact_tel, h.contact_email, i.ADM1_ARM, j.ADM3_ARM, k.ADM4_ARM, l.lawyer_id, l.lawyer_name, l.lawyer_surname, l.lawyer_tel, l.lawyer_address, l.lawyer_email 
FROM tb_inter a 
INNER JOIN tb_inter_recivers b ON a.inter_reciever = b.inter_reciever_id 
INNER JOIN tb_inter_process c ON a.inter_id = c.inter_id
INNER JOIN tb_inter_type d ON a.inter_type = d.inter_type_id
INNER JOIN tb_inter_send_type e ON a.send_type = e.inter_send_type_id
LEFT JOIN (SELECT * FROM tb_inter_file WHERE inter_file_actual = 1) AS g ON g.inter_id = a.inter_id
INNER JOIN tb_inter_action_types f ON c.action_type = f.inter_action_type_id
INNER JOIN tb_case h ON a.case_id = h.case_id
INNER JOIN tb_marz i ON h.RA_marz = i.marz_id
INNER JOIN tb_arm_com j ON h.RA_community = j.community_id
INNER JOIN tb_settlement k ON h.RA_settlement = k.settlement_id
LEFT JOIN tb_lawyer l ON l.case_id = a.case_id
WHERE
c.actual = 1 AND a.inter_id = $inter_id";

		$result_inter = $conn->query($sql_inter);

		if ($result_inter->num_rows > 0) {
			$row_inter = $result_inter->fetch_assoc();

			$inter_sender_id = $row_inter['sender'];
			$inter_receiver_id = $row_inter['rec_id'];
			$filename = $row_inter['inter_file'];
			$action_type_id = $row_inter['ACTION_TYPE_ID'];
			$action_type_text = $row_inter['ACTION_TYPE_TEXT'];
			$inter_id = $row_inter['inter_id'];
			$inter_msg = $row_inter['inter_msg'];
			$inter_status_id = $row_inter['inter_status'];
			if ($inter_status_id == 1) {
				$inter_status_text = '??????????????';
			}
			if ($inter_status_id == 2) {
				$inter_status_text = '????????????????';
			}

			$author = $row_inter['author_id'];

			$inter_addresat_id = $row_inter['inter_reciever'];
			$inter_addresat_text = $row_inter['inter_reciever_text'];

			$marz = $row_inter['ADM1_ARM'];
			$community = $row_inter['ADM3_ARM'];
			$bnakavayr = $row_inter['ADM4_ARM'];
			$street = $row_inter['RA_street'];
			$building = $row_inter['RA_building'];
			$aprt = $row_inter['RA_apartment'];

			if ($row_inter['RA_marz'] == 1) {
				$community = ' ';
				$bnakavayr = ' ';
			}


			$case_address = $marz . ' ' . $community . ' ' . $bnakavayr . ' ' . $street . ' ' . $building . ' ' . $aprt;
			if (!empty($row_inter['contact_tel'])) {
				$contact_tel = $row_inter['contact_tel'];
			} else {
				$contact_tel = "?????????? ????";
			}


			if (!empty($row_inter['contact_email'])) {
				$contact_email = $row_inter['contact_email'];
			} else {
				$contact_email = "?????????? ????";
			}


			$lawyer_address = ' ';
			$lawyer_name = ' ';
			$lawyer_surname = ' ';
			$lawyer_tel = ' ';
			$lawyer_email = ' ';

			if (!empty($row_inter['lawyer_id'])) {
				$lawyer_address = $row_inter['lawyer_address'];
				$lawyer_name = $row_inter['lawyer_name'] . ' ' . $row_inter['lawyer_surname'];
				$lawyer_tel = $row_inter['lawyer_tel'];
				$lawyer_email = $row_inter['lawyer_email'];
			}


			$inter_type_id = $row_inter['inter_type'];
			$inter_type_text = $row_inter['INTER_TYPE_TEXT'];


			$send_type_id = $row_inter['send_type'];

			
			$send_type_text = $row_inter['SEND_TYPE_TEXT'];


			$inter_msg_out = $row_inter['inter_msg'];
		}


		$inter_approve_msg_modal = '
<div class="modal-dialog modal-xl">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">???????????????????? ??????????????????</h5>
          <button type="button" class="close" data-dismiss="modal">??</button>
          
        </div>
        <div class="modal-body">
        <form method="POST" action="config/config.php" id="inter_msg_approve" enctype="multipart/form-data">
          <div class="col-md-12">
                      
            <input type="hidden" value="' . $author . '" name="author" />
            <div class ="row">

            	<div class="col-md-2">
            		<label class="label_pers_page">???????? # </label>
            		<input  class="form-control form-control-sm" name="case_id" value="' . $case_id . '" readonly />
            	</div>

            	<div class="col-md-2">
            		<label class="label_pers_page">???????? # </label>
            		<input  class="form-control form-control-sm" name="inter_id" value="' . $inter_id . '" readonly />
            	</div>

            	<div class="col-md-8">
            		<label class="label_pers_page">?????????????????? ???????????? </label>
            		<input type="text" class="form-control form-control-sm" name="inter_type_id" value="' . $inter_type_text . '" readonly />
            		<input type="hidden" name="inter_type_id" value="' . $inter_type_id . '" />
            	</div>

            	<div class="col-md-4">
            		<label class="label_pers_page">???????????????????? </label>
            		<input type="text" class="form-control form-control-sm" name="action_type_text" value="' . $action_type_text . '" readonly />
            		<input type="hidden" name="action_type_id" value="' . $action_type_id . '">
            	</div>

            	<div class="col-md-8">
            		<label class="label_pers_page">???????????? </label>
            		<input  class="form-control form-control-sm" name="inter_addressat_text" value="' . $inter_addresat_text . '" readonly />
            	</div>

            	<div class="col-md-4">
            		<label class="label_pers_page">?????????????? ?????????????? </label>
            		<input  class="form-control form-control-sm" name="send_type_id" value="' . $send_type_text . '" readonly />
            	</div>


							<div class="col-md-8">
            		<label class="label_pers_page">???????????????????? </label>
            		<a href="uploads/' . $case_id . '/inters/' . $inter_id . '/' . $filename . '" class="form-control form-control-sm" download readonly > <i class="fas fa-download"></i>?????????????????? ???????????????????? </a>
            	    <input type="hidden" name="mail_notification_file" value="uploads/' . $case_id . '/inters/' . $inter_id . '/' . $filename . '" />
            	</div>
             </div>

             <hr>

             <div class="row">
             	<div class="col-md-4">
             		<label class="label_pers_page">?????????????????? ??????????????</label>
             		<input type="date" class="form-control form-control-sm" name="nitified_date" required />
             	</div>

             	<div class="col-md-8">
             		<label class="label_pers_page">?????????????????? ????????????????????</label>
             			<div class="form-group custom-file">
                		<input type="file" name="file" class="custom-file-input" id="customFile" required="required" />
                		<label class="custom-file-label" for="customFile">?????????? ??????????</label>
            			</div>
             	</div>
             </div>	       
          </div>
        </div>
        <div class="modal-footer">
         	<input type="submit" name="approve_inter_rec_not" class="btn btn-danger" form="inter_msg_approve" value="???? ??????????????????">
          <input type="submit" name="approve_inter_rec" class="btn btn-success" form="inter_msg_approve" value="?????????????????? ??">
				</div>
      </div>
      </form>
    </div>

';


		echo $inter_approve_msg_modal;

}

if(isset($_POST['approve_inter_rec'])) {
	$inter_id   = $_POST['inter_id'];
	$case_id    = $_POST['case_id'];
	$note_date  = $_POST['nitified_date'];
	$sender     = $_SESSION['user_id'];
	$rec_id     = '0';
	$msg        = '?????????????????? ??';

	$filename = $_FILES['file']['name'];


		# Location
		$location = "../uploads/" . $case_id . "/inters/" . $inter_id ;

		# create directoy if not exists in upload/ directory
		if (!is_dir($location)) {
			mkdir($location, 0755);
		}

		$location .= "/" . $filename;



		$update_inter_process = "UPDATE tb_inter_process SET actual = '0' WHERE inter_id = $inter_id";

			if ($conn->query($update_inter_process) === TRUE) {

				$insert_new_inter_process = "INSERT INTO `tb_inter_process`(`inter_id`, `sender`, `rec_id`, `actual`, `action_type`, `inter_msg`) VALUES ('$inter_id','$sender','$rec_id','1','5','$msg')";

				if ($conn->query($insert_new_inter_process)) {
					if (move_uploaded_file($_FILES['file']['tmp_name'], $location)){
						$sql_inter_notified = "INSERT INTO `tb_inter_notified`(`notified_date`, `file_name`, `inter_id`) VALUES ('$note_date', '$filename','$inter_id')";

						if ($conn->query($sql_inter_notified) === TRUE) {

							$update_inter_status = "UPDATE tb_inter SET inter_status = '2' WHERE inter_id = $inter_id";

							if($conn->query($update_inter_status))
							{
							 header('location: ../user.php?page=cases&homepage=general_list');	
							}
							else
							{
								echo "Error: " . $update_inter_status . "<br>" . $conn->error;
							}						
						} 
						else 
						{
							echo "Error: " . $sql_inter_notified . "<br>" . $conn->error;
						}
					}
				}
				else
				{
				echo "Error: " . $insert_new_inter_process . "<br>" . $conn->error;	
				}	
			}
			else
			{
				echo "Error: " . $update_inter_process . "<br>" . $conn->error;
			}	
}



if (isset($_POST['dev_approve_inter'])) {


		$case_id = $_POST['dev_approve_inter'];
		$inter_id = $_POST['general_inter'];




		$sql_inter = "SELECT a.inter_id, a.case_id, a.author_id, a.inter_status, a.inter_reciever, a.inter_type, a.send_type, b.inter_reciever_text, c.inter_process_id, c.sender, c.rec_id, c.actual, c.actioned, c.action_type AS ACTION_TYPE_ID, c.inter_msg, d.inter_type AS INTER_TYPE_TEXT, f.action_type AS ACTION_TYPE_TEXT, e.inter_send_type AS SEND_TYPE_TEXT, g.inter_file_id, g.inter_file, g.inter_process_id, g.inter_file_actual, g.uploaded, h.RA_marz, h.RA_community, h.RA_settlement, h.RA_street, h.RA_building, h.RA_apartment, h.contact_tel, h.contact_email, i.ADM1_ARM, j.ADM3_ARM, k.ADM4_ARM, l.lawyer_id, l.lawyer_name, l.lawyer_surname, l.lawyer_tel, l.lawyer_address, l.lawyer_email, PERSON.f_name_arm, PERSON.l_name_arm 
FROM tb_inter a 
INNER JOIN tb_inter_recivers b ON a.inter_reciever = b.inter_reciever_id 
INNER JOIN tb_inter_process c ON a.inter_id = c.inter_id
INNER JOIN tb_inter_type d ON a.inter_type = d.inter_type_id
INNER JOIN tb_inter_send_type e ON a.send_type = e.inter_send_type_id
LEFT JOIN (SELECT * FROM tb_inter_file WHERE inter_file_actual = 1) AS g ON g.inter_id = a.inter_id
INNER JOIN tb_inter_action_types f ON c.action_type = f.inter_action_type_id
INNER JOIN tb_case h ON a.case_id = h.case_id
INNER JOIN tb_marz i ON h.RA_marz = i.marz_id
INNER JOIN tb_arm_com j ON h.RA_community = j.community_id
INNER JOIN tb_settlement k ON h.RA_settlement = k.settlement_id
INNER JOIN (SELECT personal_id, case_id, f_name_arm, l_name_arm FROM tb_person WHERE role = 1) AS PERSON ON PERSON.case_id = a.case_id 

LEFT JOIN tb_lawyer l ON l.case_id = a.case_id
WHERE
c.actual = 1 AND a.inter_id = $inter_id";

		$result_inter = $conn->query($sql_inter);

		if ($result_inter->num_rows > 0) {
			$row_inter = $result_inter->fetch_assoc();

			$inter_sender_id = $row_inter['sender'];
			$inter_receiver_id = $row_inter['rec_id'];
			$filename = $row_inter['inter_file'];
			$action_type_id = $row_inter['ACTION_TYPE_ID'];
			$action_type_text = $row_inter['ACTION_TYPE_TEXT'];
			$inter_id = $row_inter['inter_id'];
			$inter_msg = $row_inter['inter_msg'];
			$inter_status_id = $row_inter['inter_status'];
			if ($inter_status_id == 1) {
				$inter_status_text = '??????????????';
			}
			if ($inter_status_id == 2) {
				$inter_status_text = '????????????????';
			}

			$author = $row_inter['author_id'];

			$inter_addresat_id = $row_inter['inter_reciever'];
			$inter_addresat_text = $row_inter['inter_reciever_text'];

			$marz = $row_inter['ADM1_ARM'];
			$community = $row_inter['ADM3_ARM'];
			$bnakavayr = $row_inter['ADM4_ARM'];
			$street = $row_inter['RA_street'];
			$building = $row_inter['RA_building'];
			$aprt = $row_inter['RA_apartment'];

			if ($row_inter['RA_marz'] == 1) {
				$community = ' ';
				$bnakavayr = ' ';
			}

			$rec_name = $row_inter['f_name_arm'] . ' ' . $row_inter['l_name_arm'];
			$case_address = $marz . ' ' . $community . ' ' . $bnakavayr . ' ' . $street . ' ' . $building . ' ' . $aprt;
			if (!empty($row_inter['contact_tel'])) {
				$contact_tel = $row_inter['contact_tel'];
			} else {
				$contact_tel = "?????????? ????";
			}


			if (!empty($row_inter['contact_email'])) {
				$contact_email = $row_inter['contact_email'];
			} else {
				$contact_email = "?????????? ????";
			}


			$lawyer_address = ' ';
			$lawyer_name = ' ';
			$lawyer_surname = ' ';
			$lawyer_tel = ' ';
			$lawyer_email = ' ';

			if (!empty($row_inter['lawyer_id'])) {
				$lawyer_address = $row_inter['lawyer_address'];
				$lawyer_name = $row_inter['lawyer_name'] . ' ' . $row_inter['lawyer_surname'];
				$lawyer_tel = $row_inter['lawyer_tel'];
				$lawyer_email = $row_inter['lawyer_email'];
			}


			  if ($row_inter['inter_reciever'] == 1) {
              $rec_name = $row_inter['f_name_arm'] . ' ' . $row_inter['l_name_arm'];
            }
            if ($row_inter['inter_reciever'] == 2) {
              $rec_name = $row_inter['lawyer_name'] . ' ' . $row_inter['lawyer_surname'];
            }

            if ($row_inter['inter_reciever'] == 3) {
              $rec_name = '?????????????? ??????????????' . $row_inter['f_name_arm'] . ' ' . $row_inter['l_name_arm'] .', ????????????????'. $row_inter['lawyer_name'] . ' ' . $row_inter['lawyer_surname'];
            }


			$inter_type_id = $row_inter['inter_type'];
			$inter_type_text = $row_inter['INTER_TYPE_TEXT'];

			$send_type_text = $row_inter['SEND_TYPE_TEXT'];
			$send_type_id = $row_inter['send_type'];

			$inter_msg_out = $row_inter['inter_msg'];
		}




		$inter_approve_modal = '
<div class="modal-dialog modal-xl">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">????????????????????</h5>
          <button type="button" class="close" data-dismiss="modal">??</button>
          
        </div>
        <div class="modal-body">
        <form method="POST" action="config/config.php" id="semi_edit_note" enctype="multipart/form-data">
          <div class="col-md-12">
                      
            <input type="hidden" value="' . $author . '" name="author" />
            <div class ="row">

            	<div class="col-md-2">
            		<label class="label_pers_page">???????? # </label>
            		<input  class="form-control form-control-sm" name="case_id" value="' . $case_id . '" readonly />
            	</div>

            	<div class="col-md-2">
            		<label class="label_pers_page">???????? # </label>
            		<input  class="form-control form-control-sm" name="inter_id" value="' . $inter_id . '" readonly />
            	</div>

            	<div class="col-md-4">
            		<label class="label_pers_page">?????????????????? ???????????? </label>
            		<input type="text" class="form-control form-control-sm" name="inter_type_text" value="' . $inter_type_text . '" readonly />
            		<input type="hidden" name="inter_type_id" value="' . $inter_type_id . '" />
            	</div>

            	<div class="col-md-4">
            		<label class="label_pers_page">???????????????????? </label>
            		<input type="text" class="form-control form-control-sm" name="action_type_text" value="' . $action_type_text . '" readonly />
            		<input type="hidden" name="action_type_id" value="' . $action_type_id . '">
            	</div>

            	<div class="col-md-4">
            		<label class="label_pers_page">???????????? </label>
            		<input  class="form-control form-control-sm" name="inter_addressat_text" value="' . $inter_addresat_text . '" readonly />
            	</div>

            	<div class="col-md-4">
            		<label class="label_pers_page">?????????????? ?????????????? </label>
            		<input  class="form-control form-control-sm" name="send_type_id" value="' . $send_type_text . '" readonly />
            	</div>


				<div class="col-md-4">
            		<label class="label_pers_page">???????????????????? </label>
            		<a href="uploads/' . $case_id . '/inters/' . $filename . '" class="form-control form-control-sm" download readonly > <i class="fas fa-download"></i>?????????????????? ???????????????????? </a>
            	    <input type="hidden" name="mail_notification_file" value="uploads/' . $case_id . '/inters/' . $filename . '" />
            	</div>
             </div>	
            	
            <div class="row">
       

            	  <div class="col-md-12">
            		  <label class="label_pers_page">???????????????????????????????? </label>
            		  <input  class="form-control form-control-sm" name="case_address" value="' . $inter_msg_out . '" readonly />
            	  </div>

             		<div class="col-md-12">
            		  <label class="label_pers_page">?????????????? ??.??.??. </label>
            		  <input  class="form-control form-control-sm" name="lawyer_name" value="' . $rec_name . '" readonly>
            	  </div>

            	
            	<hr>

            	<div class="col-md-12">
            		<label class="label_pers_page">???????????????????????????????? </label>
            		<textarea class="form-control" rows="3" name="new_msg">?????????????? ???? ???????????? ?????????????????????????? </textarea>

            	</div>

            	<div class="col-md-12">
             		<label class="label_pers_page">???????????????????? ???????????????????? ??????????</label>
             			<div class="form-group custom-file">
                		<input type="file" name="file" class="dev_approve_inter custom-file-input" id="customFile" required="required" />
                		<label class="custom-file-label" for="customFile">?????????? ??????????</label>
            			</div>
             	</div>


            </div>	
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">????????????????</button>
          <input type="submit" name="approve_send_general" class="btn btn-success" form="semi_edit_note" value="????????????????">
				</div>
      </div>
      </form>
    </div>

';


		echo $inter_approve_modal;


}


	if(isset($_POST['approve_send_general'])){


		$case_id = $_POST['case_id'];
		$inter_id = $_POST['inter_id'];
		$msg = $_POST['new_msg'];
		$sender_id = $_SESSION['user_id'];

		$rec_sql = "SELECT * FROM users WHERE user_type = 'general' AND user_status = '1'";
		$res_rec_sql = $conn->query($rec_sql);
		if ($res_rec_sql->num_rows > 0) {
			$rec_id = $res_rec_sql->fetch_assoc();
			$reciver_id = $rec_id['id'];
		}


		$inter_type_id = $_POST['inter_type_id'];
   	$inter_type_letter = '';

			if($inter_type_id == 1){
				$inter_type_letter = '??';				
			}
			if($inter_type_id == 2){
				$inter_type_letter = '??';				
			}
			if($inter_type_id == 3){
				$inter_type_letter = '??';				
			}
			if($inter_type_id == 4){
				$inter_type_letter = '????';				
			}
			if($inter_type_id == 5){
				$inter_type_letter = '????';				
			}
			if($inter_type_id == 6){
				$inter_type_letter = '??';				
			}

		$rec_sql = "SELECT * FROM users WHERE user_type = 'general' AND user_status = 1";
		$res_rec_sql = $conn->query($rec_sql);
		if ($res_rec_sql->num_rows > 0) {
			$rec_id = $res_rec_sql->fetch_assoc();
			$reciver_id = $rec_id['id'];
		}


		$uot_num = '';

	 $check_inters = "SELECT * FROM tb_inter WHERE case_id = $case_id AND inter_status IN (0,2)";
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

    $out_num = "????/??-" . $inter_type_letter . '-' . $inter_id . '(' . $final_id . ')';

		$filename = $_FILES['file']['name'];


		# Location
		$location = "../uploads/" . $case_id . "/inters/" .$inter_id;

		# create directoy if not exists in upload/ directory
		if (!is_dir($location)) {
			mkdir($location, 0755);
		}

		$location .= "/" . $filename;

		$update_inter_process = "UPDATE tb_inter_process SET actual = 0 WHERE inter_id = $inter_id";
		if ($conn->query($update_inter_process) === TRUE) {

			$update_inter_file = "UPDATE tb_inter_file SET inter_file_actual = 0 WHERE inter_id = $inter_id";

			if ($conn->query($update_inter_file) === TRUE) {
				$insert_new_inter_process = "INSERT INTO `tb_inter_process`(`inter_id`, `sender`, `rec_id`, `actual`, `action_type`, `inter_msg`) VALUES ('$inter_id','$sender_id','$reciver_id','1','2', 
				NULLIF('$msg', ''))";

				if ($conn->query($insert_new_inter_process) === TRUE) {
					$last_request_process = $conn->insert_id;
					if (move_uploaded_file($_FILES['file']['tmp_name'], $location)) {
						$sql_insert_request_file = "INSERT INTO `tb_inter_file`( `inter_file`, `inter_process_id`, `inter_file_actual`, `inter_id`) VALUES ('$filename','$last_request_process', '1', '$inter_id')";

						if ($conn->query($sql_insert_request_file) === TRUE) {

							$sql_notify = "INSERT INTO `tb_notifications` (`comment_subject`, `comment_text`, `comment_status`, `comment_from`, `comment_to`, `case_id`, `note_type`) VALUES ('?????? ????????????????????', NULLIF('$msg', ''), '0', '$sender_id', '$reciver_id', '$case_id', '1')";

							if ($conn->query($sql_notify) === TRUE) {
								
								$update_tb_inter = "UPDATE tb_inter SET out_num = '$out_num' WHERE inter_id = $inter_id";
								if ($conn->query($update_tb_inter) === TRUE) {
									header('location: ../user.php?page=cases&homepage=general_list');
								}
								else
								{
								echo "Error: " . $update_tb_inter . "<br>" . $conn->error;
								}	

								
							} 
							else 
							{
								echo "Error: " . $sql_notify . "<br>" . $conn->error;
							}


						} else {
							echo "Error: " . $sql_insert_request_file . "<br>" . $conn->error;
						}

					}
				} else {
					echo "Error: " . $insert_new_inter_process . "<br>" . $conn->error;
				}

			} else {
				echo "Error: " . $update_inter_file . "<br>" . $conn->error;
			}

		} else {
			echo "Error: " . $update_inter_process . "<br>" . $conn->error;
		}

	}


#############################

	if (isset($_POST['general_return'])) {
		$inter_id = $_POST['general_return'];
		$case_id  = $_POST['case_id'];

    $rec_sql = "SELECT * FROM users WHERE user_type = 'devhead' AND user_status = '1'";
    $res_rec_sql = $conn->query($rec_sql);

    $opt_reciver = '<select name="select_receiver" id="select_receiver" class="form-control form-control-sm">';
    while ($row5 = mysqli_fetch_array($res_rec_sql)) {
      $opt_reciver .= "<option value=" . $row5['id'] . ">" . $row5['f_name'] . ' ' . $row5['l_name'] . "</option>";
    }
    $opt_reciver .= "</select>";

    $msg = "???????????????????? ???????? ????";

       $inter_modal = '';

    $inter_modal .= '<div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">?????????????????????? ??????????????????????</h5>
          <button type="button" class="close" data-dismiss="modal">??</button>
          
        </div>
        <div class="modal-body">
        <form method="POST" action="config/config.php" id="semi_note_return" enctype="multipart/form-data">
          <div class="col-md-12">
                      
         
           

						<label class="label_pers_page">????????????</label>           
						'.$opt_reciver.'


            <label class="label_pers_page">????????????????????????????????</label>
            <textarea class="form-control" rows="3" name="msg_comment"> ' . $msg . ' </textarea>



           
            <input type="hidden" class="form-control form-control-sm" name="case_id"  value="' . $case_id . '">
            <input type="hidden" class="form-control form-control-sm" name="inter_id" value="' . $inter_id . '">
        
           



          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">????????????????</button>
          <input type="submit" name="save_semi_return" class="btn btn-success" form="semi_note_return" value="??????????????????????">
        </div>
      </div>
      </form>
    </div>
    ';

    echo $inter_modal;

	}


	if(isset($_POST['save_semi_return'])) {
		$case_id = $_POST['case_id'];
		$inter_id = $_POST['inter_id'];
		$rec_id = $_POST['select_receiver'];
		$sender_id = $_SESSION['user_id'];
		$msg = $_POST['msg_comment'];


		$inter_status = '1';


			$update_inter_process = "UPDATE tb_inter_process SET actual = '0' WHERE inter_id = $inter_id";

			if($conn->query($update_inter_process) === TRUE){

				$insert_inter_process = "INSERT INTO `tb_inter_process`(`inter_id`, `sender`, `rec_id`, `actual`, `action_type`, `inter_msg`) VALUES ('$inter_id','$sender_id','$rec_id','1','4', 
        NULLIF('$msg', ''))";

        if($conn->query($insert_inter_process) === TRUE){
        $sql_notify = "INSERT INTO `tb_notifications`(`comment_subject`, `comment_text`, `comment_status`, `comment_from`, `comment_to`, `case_id`, `note_type`) VALUES ('???????????????????? ????????????????', NULLIF('$msg', ''), '0', '$sender_id', '$rec_id', '$case_id', '1')";

        	if($conn->query($sql_notify))
        	{
        		header('location: ../user.php?page=cases&homepage=general_list');
        	}
        	else
        	{
        		echo "Error: " . $sql_notify . "<br>" . $conn->error;
        	}

        }
        else
        {
        	echo "Error: " . $insert_inter_process . "<br>" . $conn->error;	
        }

			}
			else
			{
			echo "Error: " . $update_inter_process . "<br>" . $conn->error;	
			}	





	}


	##################################




	if (isset($_GET['cmd']) && $_GET['cmd'] === 'get_hystory_table') {
		$case_id = $_GET['case_id'];
		$myObj = new stdClass();
		$data = [];


		$query_inter_info = " SELECT a.inter_id, a.case_id, a.author_id, a.inter_status, a.inter_reciever, c.inter_reciever_text, a.inter_type, d.inter_type AS INTER_TYPE_TEXT, a.send_type, b.inter_send_type, e.notified_date, e.file_name AS NOTE_FILE
								FROM tb_inter a 
								INNER JOIN tb_inter_send_type b ON a.send_type = b.inter_send_type_id
								INNER JOIN tb_inter_recivers c ON a.inter_reciever = c.inter_reciever_id								
								INNER JOIN tb_inter_type d ON a.inter_type = d.inter_type_id								    
								LEFT JOIN tb_inter_notified e ON a.inter_id = e.inter_id								
								WHERE a.case_id = $case_id";

		$result_inter_info = $conn->query($query_inter_info);
		if ($result_inter_info->num_rows > 0) {
			while ($row_inter_info = $result_inter_info->fetch_assoc()) {
				$inter_info = new stdClass();
				$inter_processes=[];
				$inter_status = '?????????????? ?? ??????????????';
				if ($row_inter_info['inter_status'] == 1) {
					$inter_status = '?????????????? ?? ??????????????????';
				} elseif ($row_inter_info['inter_status'] == 2) {
					$inter_status = '??????????????????';
				}
				$query_inter_id = $row_inter_info['inter_id'];
				$inter_info->inter_id = $query_inter_id;
				$inter_info->type = $row_inter_info['INTER_TYPE_TEXT'];
				$inter_info->receiver = $row_inter_info['inter_reciever_text'];
				$inter_info->status = $inter_status;
				$inter_info->send_type = $row_inter_info['inter_send_type'];

					$query_inter_processes = "SELECT f.actioned AS PROCESS_ACTIONED, f.action_type,f.inter_msg,g.action_type AS ACTION_TYPE_TEXT, FL.inter_file_actual, FL.inter_file
					FROM tb_inter_process f
					INNER JOIN tb_inter_action_types g ON f.action_type = g.inter_action_type_id
					INNER JOIN (SELECT p.inter_file_id, p.inter_file, p.inter_process_id, p.inter_file_actual, p.uploaded, p.inter_id FROM tb_inter_file p WHERE p.inter_file_actual = 1) AS FL ON FL.inter_id = f.inter_id 
					WHERE f.inter_id = $query_inter_id";
					$result_inter_processes = $conn->query($query_inter_processes);
					if ($result_inter_processes->num_rows > 0) {
						while ($row_inter_processes = $result_inter_processes->fetch_assoc()) {
							$single_process = new stdClass();

							$process_actioned = $row_inter_processes['PROCESS_ACTIONED'];
							$inter_msg = $row_inter_processes['inter_msg'];
							$action_type_text = $row_inter_processes['ACTION_TYPE_TEXT'];
							$inter_file = 'uploads/' .$case_id. '/inters/' . $query_inter_id . '/' . $row_inter_processes['inter_file'];

							$single_process->process_actioned = $process_actioned;
							$single_process->inter_msg = $inter_msg;
							$single_process->action_type_text = $action_type_text;
							$single_process->inter_file = $inter_file;

							$inter_processes[] = $single_process;
						}
					}


				$inter_info->inter_processes = $inter_processes;
				$data[] = $inter_info;
			}

		}
		$myObj->data = $data;
		echo json_encode($myObj);
		exit;
	}

	$conn->close();
	exit;
?>







