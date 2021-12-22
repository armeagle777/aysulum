<?php
require_once 'config/connect.php';

$u_id = $_SESSION['user_id'];

$sql_inters = "SELECT a.inter_id, a.case_id, a.author_id, a.inter_status, a.inter_reciever, a.inter_type, a.send_type, b.inter_reciever_text, c.inter_process_id, c.sender, c.rec_id, c.actual, c.actioned, c.action_type, c.inter_msg, d.inter_type AS INTER_TYPE_TEXT, e.inter_send_type AS SEND_TYPE_TEXT, g.inter_file_id, g.inter_file, g.inter_process_id, g.inter_file_actual, g.uploaded 
FROM tb_inter a 
INNER JOIN tb_inter_recivers b ON a.inter_reciever = b.inter_reciever_id 
INNER JOIN tb_inter_process c ON a.inter_id = c.inter_id
INNER JOIN tb_inter_type d ON a.inter_type = d.inter_type_id
INNER JOIN tb_inter_send_type e ON a.send_type = e.inter_send_type_id
LEFT JOIN (SELECT * FROM tb_inter_file WHERE inter_file_actual = 1) AS g ON g.inter_id = a.inter_id
INNER JOIN tb_inter_action_types f ON c.action_type = f.inter_action_type_id
WHERE
c.actual = 1 AND c.rec_id = $u_id";

$result_inter_list = $conn->query($sql_inters);


?>


<body>
	<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link active" data-toggle="tab" href="#home"><i class="fas fa-circle-notch"></i> Մտից</a>
    
  </li>

  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#menu1"><i class="fas fa-random"></i> Ելից</a>
  </li>
  
  
 </ul>
 
 <!-- Tab panes -->
    <div class="tab-content">
      
      <div id="home" class="tab-pane active"><br>
             	
      	<table class="table" id="all_special">
      		<thead>
      		<tr style="font-size: 0.9em; font-weight: normal; color: #828282; text-align: center; vertical-align: middle;">
      			<th width="10%">Ծանուցագիր #</th>
      			<th width="10%">Գործ #</th>
      			<th width="15%">Ամսաթիվ</th>
      			<th width="15%">Ստացող</th>
      			<th width="15%">Առաքման եղանակը</th>
      			<th width="25%">Հաղորդագրություն</th>
      			<th width="10%">Գործողություն</th>
      			
      		</tr>
      		</thead>
      		<tbody>
      		<?php 
      		while($row_all = $result_inter_list->fetch_assoc()){
      			$case_id 			= $row_all['case_id'];
      			$inter_id 			= $row_all['inter_id'];
      			$in_date 			= date('d.m.Y', strtotime($row_all['actioned']));
      			$rec_text 			= $row_all['inter_reciever_text'];
      			$msg_text 			= $row_all['inter_msg'];
      			$send_type 			= $row_all['SEND_TYPE_TEXT']
      			
      		
      		?>
      		<tr class="curs_pointer" style="text-align:center;">
      			<td><?php echo $inter_id ?></td>
      			<td><?php echo $case_id ?></td>
      			<td><?php echo $in_date ?></td>
      			<td><?php echo $rec_text ?></td>
      			<td><?php echo $send_type ?></td>
      			<td><?php echo $msg_text ?></td>
      			<td><a href="#" case_id="<?php echo $case_id ?>" inter_id="<?php echo $inter_id ?>" class="btn btn-success btn-sm send_inter " ><i class="fas fa-file-import" style="color: white;"></i></a></td>
      			

      		</tr>
      		

      		
      	<?php } ?>
      	</tbody>
    </table>
      </div>	
      
      <div id="menu1" class="tab-pane fade"><br>
       <h5>elicner</h5>
      </div>

</div>
</body>

<div class="modal fade" id="general_send_modal">
 
</div>

<script>
  
  $(document).ready(function () {
    $(".send_inter").click(function () {
        var general_inter = $(this).attr('inter_id');
        var general_case = $(this).attr('case_id');
        $.ajax({
          url: "config/config.php",
          method: "POST",
          data: {general_case:general_case, general_inter: general_inter},
          success: function (data) {
            $('#general_send_modal').html(data);
            $("#general_send_modal").modal({backdrop: "static"});

          }
        });
      });

    });


</script>

