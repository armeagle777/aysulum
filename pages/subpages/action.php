<?php 
include_once '../../config/connect.php';

  $marzId = $_POST['marzId'];

    if (!empty($marzId)) {
	// Fetch state name base on country id
	$query = "SELECT * FROM tb_arm_com WHERE marz_id = {$marzId}";

	$result = $conn->query($query);

	if ($result->num_rows > 0) {
		echo '<option value=""> Ընտրե՛ք համայնքը </option>'; 
 	   while ($row = $result->fetch_assoc()) {
 	   		
	        echo '<option value="'.$row['community_id'].'">'.$row['ADM3_ARM'].'</option>'; 
 	    }
	}else
	{
	    echo '<option value="">State not available</option>'; 
	}
	}
	if (!empty($_POST['bnakId'])) {
	$bnakId = $_POST['bnakId']; 
	// Fetch city name base on state id

	$sql = "SELECT * FROM tb_settlement WHERE com_id = {$bnakId}";

	$result1 = $conn->query($sql);

	if ($result1->num_rows > 0) {
		echo '<option value=""> Ընտրե՛ք բնակավայրը </option>'; 
	    while ($row1 = $result1->fetch_assoc()) 
	    {
	    echo '<option value="'.$row1['settlement_id'].'">'.$row1['ADM4_ARM'].'</option>'; 
	    }
	}
	else
	{
	    echo '<option value="">Բնակավայր չի հայտնաբերվել</option>'; 
	}
    }


?>