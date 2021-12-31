<?php


	//Headers

	header('Access-Control-Allow-Origin: *');
	header('Content-Type: application/json');

	include_once 'config/Database.php';
	include_once 'models/Post.php';

	//Instantiate DB & connect
	$database = new Database();
	$db = $database->connect();

	//Instantiate post object
	$post = new Post($db);

	//Post array
	$posts_obj = new stdClass();


	if (isset($_GET['search']) && $_GET['search'] === 'all') {
		//Person post query
		$result = $post->read();

	} elseif (
		isset($_GET['f_name_arm']) ||
		isset($_GET['l_name_arm']) ||
		isset($_GET['m_name_arm']) ||
		isset($_GET['f_name_eng']) ||
		isset($_GET['l_name_eng']) ||
		isset($_GET['m_name_eng']) ||
		isset($_GET['pnum']) ||
		isset($_GET['document_num'])
	) {
		$getKeysArray = [];
		$getValuesArray=[];
		if(isset($_GET['f_name_arm'])){
			$getKeysArray[] = 'f_name_arm';
			$getValuesArray[] =  htmlspecialchars($_GET['f_name_arm']);
		}
		if(isset($_GET['l_name_arm'])){
			$getKeysArray[] = 'l_name_arm';
			$getValuesArray[] =  htmlspecialchars($_GET['l_name_arm']);
		}
		if(isset($_GET['m_name_arm'])){
			$getKeysArray[] = 'm_name_arm';
			$getValuesArray[] =  htmlspecialchars($_GET['m_name_arm']);
		}
		if(isset($_GET['f_name_eng'])){
			$getKeysArray[] = 'f_name_eng';
			$getValuesArray[] =  htmlspecialchars($_GET['f_name_eng']);
		}
		if(isset($_GET['l_name_eng'])){
			$getKeysArray[] = 'l_name_eng';
			$getValuesArray[] =  htmlspecialchars($_GET['l_name_eng']);
		}
		if(isset($_GET['m_name_eng'])){
			$getKeysArray[] = 'm_name_eng';
			$getValuesArray[] =  htmlspecialchars($_GET['m_name_eng']);
		}
		if(isset($_GET['pnum'])){
			$getKeysArray[] = 'pnum';
			$getValuesArray[] =  htmlspecialchars($_GET['pnum']);
		}
		if(isset($_GET['doc_num'])){
			$getKeysArray[] = 'doc_num';
			$getValuesArray[] =  htmlspecialchars($_GET['doc_num']);
		}


		$result = $post->read_single($getKeysArray,$getValuesArray);

	} else {
		$posts_obj->status = http_response_code();
		$posts_obj->message = 'Invalid search params';
		echo json_encode($posts_obj);
		die;
	}


	//Get rowCount
	$num = $result->rowCount();

	//Check if any person fount
	if ($num > 0) {
		$posts_obj->data = array();
		while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
			extract($row);
			if($image && file_exists("../uploads/$case_id/$personal_id/$image")){
				$path = "../uploads/$case_id/$personal_id/$image";
				$type = pathinfo($path, PATHINFO_EXTENSION);
				$data = file_get_contents($path);
				$image = 'data:image/' . $type . ';base64,' . base64_encode($data);
			}
			$invalid = !($invalid == 0);
			$pregnant = !($pregnant == 0);
			$seriously_ill = !($seriously_ill == 0);
			$trafficking_victim = !($trafficking_victim == 0);
			$violence_victim = !($violence_victim == 0);
			$illegal_border = !($illegal_border == 0);
			$transfer_moj = !($transfer_moj == 0);
			$deport_prescurator = !($deport_prescurator == 0);
			$prison = !($prison == 0);
			$bpr_address = !is_null($bpr_community) ? $bpr_community . ' ' . $bpr_bnakavayr . ' ' . $bpr_street . ' ' . $bpr_house . ' ' . $bpr_aprt : NULL;
			$current_address = trim($address_marz . ' ' . $address_community . ' ' . $address_settlement . ' ' . $address_street . ' ' . $address_building . ' ' . $address_appartment);
			$post_item = array(
				'f_name_arm' => $f_name_arm,
				'l_name_arm' => $l_name_arm,
				'm_name_arm' => $m_name_arm,
				'f_name_eng' => $f_name_eng,
				'l_name_eng' => $l_name_eng,
				'm_name_eng' => $m_name_eng,
				'birth_date' => $b_year . '-' . $b_month . '-' .$b_day ,
				'citizenship_country' => $citizenship_country,
				'residence_country' => $residence_country,
				'citizen_adr' => $citizen_adr,
				'residence_adr' => $residence_adr,
				'citizen_leaving_date' => $citizen_leaving_date,
				'residence_leaving_date' => $residence_leaving_date,
				'arrival_date' => $arrival_date,
				'doc_type' => $doc_type,
				'etnicity' => $etnicity,
				'religion_arm' => $religion_arm,
				'isInvalid' => $invalid,
				'isPregnant' => $pregnant,
				'isSeriously_ill' => $seriously_ill,
				'isTrafficking_victim' => $trafficking_victim,
				'isViolence_victim' => $violence_victim,
				'isIllegal_border' => $illegal_border,
				'isTransfer_moj' => $transfer_moj,
				'isDeport_prescurator' => $deport_prescurator,
				'isPrison' => $prison,
				'role' => $der,
				'pnum' => $pnum,
				'image' => $image,
				'doc_num' => $doc_num,
				'doc_issued_date' => $doc_issued_date,
				'doc_issued_by' => $doc_issued_by,
				'doc_valid' => $doc_valid,
				'bpr_address' => $bpr_address,
				'current_address' => $current_address
			);
			//Push to data
			$posts_obj->data[] = $post_item;

		}

		$posts_obj->status = http_response_code();
		//Turn to JSON & output
		echo json_encode($posts_obj);
	} else {
		$posts_obj->status = http_response_code();
		$posts_obj->message = 'No Person found';
		echo json_encode($posts_obj);
		die;
	}

