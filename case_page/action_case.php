<?php
require_once '../config/connect.php';


if (isset($_POST['save_new_case'])) {
	$application_date = $_POST['application_date'];
	$registrar = $_POST['registrar'];
	
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

	$prefered_language = $_POST['pref_language'];
	$contact_tel = $_POST['tel'];
	$RA_marz = $_POST['select_marz'];
	$RA_community = $_POST['select_community'];
	$RA_settlement = $_POST['select_bnakavayr'];
	$RA_street = $_POST['street'];
	$RA_building = $_POST['building'];
	$RA_apartment = $_POST['flat'];
	$reason_1 = $_POST['reason_1'];
	$reason_2 = $_POST['reason_2'];
	$sequence_1 = $_POST['sequence_1'];
	$sequence_2 = $_POST['sequence_2'];


	$sql = ""
	

}


?>