<?php 
 require_once 'config/connect.php';

 $u_id = $_POST['user'];


	if (isset($_POST["view"])) {
		$sql = "SELECT * FROM tb_notifications a INNER JOIN users b ON a.comment_from = b.id WHERE comment_to = $u_id AND comment_status = 0 ORDER BY comment_id DESC LIMIT 10";
		$result = $conn->query($sql);

		$output = '<ul class="dropdowns">';

		if ($result->num_rows > 0) {
			$case = 0;
			while ($row = mysqli_fetch_array($result)) {
				if ($row['note_type'] == 1) {
					$output .= '
                            <li class="none_padding_li">
                                   <a href="user.php?page=cases&homepage=case_page&case=' . $row["case_id"] . '&notification_id=' . $row['comment_id'] . '" style="display: inline-block; padding: 20px 16px;width:100%;">
                                          <strong> ' . $row["comment_subject"] . ' </strong> <br />
                                          <strong> </span>  ' . $row["f_name"] . ' ' . $row["l_name"] . 'ից </strong> <br />
                                          <em>' . $row["comment_text"] . '</em>
                                   </a>
                            </li>
                     ';
				}
				if ($row['note_type'] == 2) {
					$output .= '
                            <li class="none_padding_li">
                                   <a href="user.php?page=cases&homepage=coi_page&coi=' . $row["coi_id"] . '&notification_id=' . $row['comment_id'] . '&case=' . $row["case_id"] . '" style="display: inline-block; padding: 20px 16px;width:100%;">
                                          <strong> ' . $row["comment_subject"] . ' </strong> <br />
                                          <strong> </span>  ' . $row["f_name"] . ' ' . $row["l_name"] . 'ից </strong> <br />
                                          <em>' . $row["comment_text"] . '</em>
                                   </a>
                            </li>
                     ';
				}
				if ($row['note_type'] == 3) {
					$output .= '
                            <li class="none_padding_li">
                                   <a href="user.php?page=cases&homepage=body_page&request=' . $row["request_id"] . '&notification_id=' . $row['comment_id'] . '&case=' . $row["case_id"] . '" style="display: inline-block; padding: 20px 16px;width:100%;">
                                          <strong> ' . $row["comment_subject"] . ' </strong> <br />
                                          <strong> </span>  ' . $row["f_name"] . ' ' . $row["l_name"] . 'ից </strong> <br />
                                          <em>' . $row["comment_text"] . '</em>
                                   </a>
                            </li>
                     ';
				}

				if ($row['note_type'] == 4) {
					$output .= '
                            <li class="none_padding_li">
                                   <a href="user.php?page=cases&homepage=draft_page&draft=' . $row["draft_id"] . '&notification_id=' . $row['comment_id'] . '&case=' . $row["case_id"] . '" style="display: inline-block; padding: 20px 16px;width:100%;">
                                          <strong> ' . $row["comment_subject"] . ' </strong> <br />
                                          <strong> </span>  ' . $row["f_name"] . ' ' . $row["l_name"] . 'ից </strong> <br />
                                          <em>' . $row["comment_text"] . '</em>
                                   </a>
                            </li>
                     ';
				}

				if ($row['note_type'] == 5) {
					$output .= '
                            <li class="none_padding_li">
                                   <a href="user.php?page=cases&homepage=order_page&order=' . $row["order_id"] . '&notification_id=' . $row['comment_id'] . '&case=' . $row["case_id"] . '" style="display: inline-block; padding: 20px 16px;width:100%;">
                                          <strong> ' . $row["comment_subject"] . ' </strong> <br />
                                          <strong> </span>  ' . $row["f_name"] . ' ' . $row["l_name"] . 'ից </strong> <br />
                                          <em>' . $row["comment_text"] . '</em>
                                   </a>
                            </li>
                     ';
				}

				$case = $row['case_id'];
			}
			$output .= '</ul>';
		} else {
			$output = '<p href="#" class="font-weight-bold text-left mt-2 ml-2 font-italic">Նոր ծանուցումներ չկան</p>';
		}

		$sql2 = "SELECT * FROM tb_notifications WHERE comment_status = 0 AND comment_to = $u_id ";
		$result_1 = mysqli_query($conn, $sql2);
		$count = mysqli_num_rows($result_1);
		$data = array(
			'notification' => $output,
			'unseen_notification' => $count
		);

		echo json_encode($data);
	}

 if(isset($_POST['requests'])){

       $sql_request = "SELECT a.request_id, a.case_id, a.author, a.body, a.request_date, a.request_status, b.request_process_id, b.request_id, b.user_from, b.request_user_to, b.request_actual, b.process_date, b.process_date, b.process_status, b.process_comment
FROM tb_request_out a 
INNER JOIN tb_request_process b ON a.request_id = b.request_id
WHERE b.request_actual = 1 AND b.request_user_to = $u_id AND b.request_read = 0";
        $result_request = mysqli_query($conn, $sql_request);
        $count_requests = mysqli_num_rows($result_request);
        $data = array(
              
              'unseen_requests'           => $count_requests
        );

       echo json_encode($data);

}

if(isset($_POST['inboxes'])){

       $sql_inboxes = "SELECT a.case_id, c.sign_status, c.sign_date, a.input_date, c.sign_by
       FROM tb_case a 
       INNER JOIN tb_process c ON a.case_id = c.case_id 
       WHERE  c.actual = 1 AND c.processor = $u_id AND a.case_status IN (1,2,5) AND c.sign_status != 16";
        $result_inbox = mysqli_query($conn, $sql_inboxes);
        $count_inbox = mysqli_num_rows($result_inbox);
        $data = array(
              
              'unseen_inbox'           => $count_inbox
        );

       echo json_encode($data);

}


if(isset($_POST['drafts'])){

       $sql_draft = "SELECT * FROM tb_draft a WHERE a.actual = '1' AND a.receiver = $u_id";
        $result_drafts = mysqli_query($conn, $sql_draft);
        $count_drafts = mysqli_num_rows($result_drafts);
        $data = array(
              
              'unseen_draft'           => $count_drafts
        );

       echo json_encode($data);

}

if(isset($_POST['orders'])){

       $sql_orders = "SELECT * FROM tb_order_process a
INNER JOIN tb_orders b ON a.order_id = b.order_id
 WHERE a.order_actual = 1 AND a.order_to = $u_id AND a.order_status != 3";
        $result_orders = mysqli_query($conn, $sql_orders);
        $count_orders = mysqli_num_rows($result_orders);
        $data = array(
              
              'unseen_order'           => $count_orders
        );

       echo json_encode($data);

}


if(isset($_POST['inters'])){

       $sql_inters = "SELECT * FROM tb_inter_process a WHERE a.actual = '1' AND a.rec_id = $u_id";
        $result_inters = mysqli_query($conn, $sql_inters);
        $count_inters = mysqli_num_rows($result_inters);
        $data = array(
              
              'unseen_interns'           => $count_inters
        );

       echo json_encode($data);

}






?>


