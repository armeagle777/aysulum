<?php
require_once 'config/connect.php';

$u_id = $_SESSION['user_id'];

$sql_inters = "SELECT a.inter_id, a.case_id, a.author_id, a.inter_status, a.inter_reciever, a.inter_type, a.out_num, a.send_type, b.inter_reciever_text, c.inter_process_id, c.sender, c.rec_id, c.actual, c.actioned, c.action_type, c.inter_msg, d.inter_type AS INTER_TYPE_TEXT, e.inter_send_type AS SEND_TYPE_TEXT, g.inter_file_id, g.inter_file, g.inter_process_id, g.inter_file_actual, g.uploaded 
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

$sql_inters_out = "SELECT a.inter_id, a.case_id, a.author_id, a.inter_status, a.inter_reciever, a.inter_type, a.send_type, a.out_num, b.inter_reciever_text, c.inter_process_id, c.sender, c.rec_id, c.actual, c.actioned, c.action_type, c.inter_msg, d.inter_type AS INTER_TYPE_TEXT, e.inter_send_type AS SEND_TYPE_TEXT, PERSON.f_name_arm, PERSON.l_name_arm, LAWYER.lawyer_name, LAWYER.lawyer_surname 
FROM tb_inter a 
INNER JOIN tb_inter_recivers b ON a.inter_reciever = b.inter_reciever_id 
INNER JOIN tb_inter_process c ON a.inter_id = c.inter_id
INNER JOIN tb_inter_type d ON a.inter_type = d.inter_type_id
INNER JOIN tb_inter_send_type e ON a.send_type = e.inter_send_type_id
INNER JOIN (SELECT personal_id, case_id, f_name_arm, l_name_arm FROM tb_person WHERE role = 1) AS PERSON ON PERSON.case_id = a.case_id 
LEFT JOIN (SELECT lawyer_id, lawyer_name, lawyer_surname, case_id FROM tb_lawyer WHERE actual = 1) AS LAWYER ON LAWYER.case_id = a.case_id
INNER JOIN tb_inter_action_types f ON c.action_type = f.inter_action_type_id
WHERE
c.actual = 1 AND c.rec_id = 0 AND c.action_type = 3";

$result_inter_out = $conn->query($sql_inters_out);



$overs_where = ' ';

if ($_SESSION['role'] === 'officer' || $_SESSION['role'] === 'lawyer' || $_SESSION['role'] === 'coi' ) {
  $overs_where = " AND a.author_id = " .$_SESSION['user_id'];
}


$sql_inters_over = "SELECT a.inter_id, a.case_id, a.author_id, a.inter_status, a.inter_reciever, a.inter_type, a.send_type, a.out_num, b.inter_reciever_text, c.inter_process_id, c.sender, c.rec_id, c.actual, c.actioned, c.action_type, c.inter_msg, d.inter_type AS INTER_TYPE_TEXT, e.inter_send_type AS SEND_TYPE_TEXT, PERSON.f_name_arm, PERSON.l_name_arm, LAWYER.lawyer_name, LAWYER.lawyer_surname, k.inter_notified_id, k.notified_date, k.file_name
FROM tb_inter a 
INNER JOIN tb_inter_recivers b ON a.inter_reciever = b.inter_reciever_id 
INNER JOIN tb_inter_process c ON a.inter_id = c.inter_id
INNER JOIN tb_inter_type d ON a.inter_type = d.inter_type_id
INNER JOIN tb_inter_send_type e ON a.send_type = e.inter_send_type_id
INNER JOIN (SELECT personal_id, case_id, f_name_arm, l_name_arm FROM tb_person WHERE role = 1) AS PERSON ON PERSON.case_id = a.case_id 
LEFT JOIN (SELECT lawyer_id, lawyer_name, lawyer_surname, case_id FROM tb_lawyer WHERE actual = 1) AS LAWYER ON LAWYER.case_id = a.case_id
INNER JOIN tb_inter_action_types f ON c.action_type = f.inter_action_type_id
INNER JOIN tb_inter_notified k ON k.inter_id = a.inter_id
WHERE
c.actual = 1 AND c.rec_id = 0 AND c.action_type = 5 $overs_where";

$result_inter_over = $conn->query($sql_inters_over);


?>


<body>
	<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link active" data-toggle="tab" href="#home"><i class="fas fa-arrow-down"></i> Իմ վարույթում</a>
    
  </li>

  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#menu1"><i class="fas fa-arrow-up"></i> Առաքվածներ</a>
  </li>

  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#menu2"><i class="fas fa-clipboard-check"></i> Ավարտված</a>
  </li>
  
  
 </ul>
 
 <!-- Tab panes -->
    <div class="tab-content">
      
      <div id="home" class="tab-pane active"><br>
             	
      	<table class="table" id="inter_in">
      		<thead>
      		<tr style="font-size: 0.9em; font-weight: normal; color: #828282; text-align: center; vertical-align: middle;">
      			<th width="10%">Ծանուցագիր #</th>
      			<th width="10%">Գործ #</th>
            <th width="10%">Ելից #</th>
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
      			$case_id 			  = $row_all['case_id'];
            $out_num        = '';
            if (!empty($row_all['out_num'])) {
              $out_num = $row_all['out_num'];
            }
            else
            {
              $out_num = 'Ն/ք';
            }
      			$inter_id 			= $row_all['inter_id'];
      			$in_date 			  = date('d.m.Y', strtotime($row_all['actioned']));
      			$rec_text 			= $row_all['inter_reciever_text'];
      			$msg_text 			= $row_all['inter_msg'];
      			$send_type 			= $row_all['SEND_TYPE_TEXT']
      			
      		
      		?>
      		<tr class="curs_pointer" style="text-align:center;">
      			<td><?php echo $inter_id ?></td>
      			<td><?php echo $case_id ?></td>
            <td><?php echo $out_num ?></td>
      			<td><?php echo $in_date ?></td>
      			<td><?php echo $rec_text ?></td>
      			<td><?php echo $send_type ?></td>
      			<td><?php echo $msg_text ?></td>
            <td>
            <?php

            if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'general') {

            ?>  


      			<a href="#" case_id="<?php echo $case_id ?>" inter_id="<?php echo $inter_id ?>" class="btn btn-success btn-sm send_inter " ><i class="fas fa-file-import" style="color: white;"></i></a>
            <a href="#" case_id="<?php echo $case_id ?>" inter_id="<?php echo $inter_id ?>" class="btn btn-success btn-sm send_retutn " ><i class="fas fa-undo" style="color: red;"></i></a>

          <?php } 

            if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'devhead') {

            ?>  


            <a href="#" case_id="<?php echo $case_id ?>" inter_id="<?php echo $inter_id ?>" class="btn btn-success btn-sm devhead_app_inter " ><i class="fas fa-check" style="color: white;"></i></a>

             <a href="#" case_id="<?php echo $case_id ?>" inter_id="<?php echo $inter_id ?>" class="btn btn-warning btn-sm return_from_list" ><i class="fas fa-undo" style="color: white;"></i></a>

          <?php } 

          if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'officer' || $_SESSION['role'] === 'coispec' || $_SESSION['role'] === 'lawyer'){ ?>

            <a href="#" case_id="<?php echo $case_id ?>" inter_id="<?php echo $inter_id ?>" class="btn btn-danger btn-sm officer_cancel " ><i class="fas fa-times" style="color: white;"></i></a>

            <a href="#" case_id="<?php echo $case_id ?>" inter_id="<?php echo $inter_id ?>" class="btn btn-warning btn-sm officer_edit" ><i class="fas fa-pen-square" style="color: white;"></i></a>
        <?php
        }
        ?>

            </td>
      			

      		</tr>
      		

      		
      	<?php } ?>
      	</tbody>
    </table>
      </div>	
      
      <div id="menu1" class="tab-pane fade"><br>
        <table class="table" id="inter_out">
          <thead>
          <tr style="font-size: 0.9em; font-weight: normal; color: #828282; text-align: center; vertical-align: middle;">
            <th width="10%">Ծանուցագիր #</th>
            <th width="10%">Գործ #</th>
            <th width="10%">Ելից #</th>
            <th width="15%">Ամսաթիվ</th>
            <th width="15%">Ստացող</th>
            <th width="15%">Ստացողի ա.ա.հ.</th>
            <th width="15%">Առաքման եղանակը</th>
            <th width="25%">Հաղորդագրություն</th>
            <th width="10%">...</th>
            
            
            
            
          </tr>
          </thead>
          <tbody>
          <?php 
          while($row_all = $result_inter_out->fetch_assoc()){
            $case_id        = $row_all['case_id'];
            $inter_id       = $row_all['inter_id'];
             $out_num        = '';
            if (!empty($row_all['out_num'])) {
              $out_num = $row_all['out_num'];
            }
            else
            {
              $out_num = 'Ն/ք';
            }

            $out_date       = date('d.m.Y', strtotime($row_all['actioned']));
            $rec_text       = $row_all['inter_reciever_text'];
            $rec_name       = '';
            if ($row_all['inter_reciever'] == 1) {
              $rec_name = $row_all['f_name_arm'] . ' ' . $row_all['l_name_arm'];
            }
            if ($row_all['inter_reciever'] == 2) {
              $rec_name = $row_all['lawyer_name'] . ' ' . $row_all['lawyer_surname'];
            }

            if ($row_all['inter_reciever'] == 3) {
              $rec_name = $row_all['f_name_arm'] . ' ' . $row_all['l_name_arm'] .'<br/>'. $row_all['lawyer_name'] . ' ' . $row_all['lawyer_surname'];
            }


            $msg_text       = $row_all['inter_msg'];
            $send_type      = $row_all['SEND_TYPE_TEXT'];
            
            if($_SESSION['role'] === 'devhead' || $_SESSION['role'] === 'officer' || $_SESSION['role'] === 'lawyer' || $_SESSION['role'] === 'coispec'){
              $general_btn = ' ';
            }
            
            if($_SESSION['role'] === 'general'){

            $general_btn = '<a href="#" case_id="'. $case_id .'" inter_id="'.$inter_id.'" class="btn btn-success btn-sm inter_approve " ><i class="fas fa-file-import" style="color: white;"></i></a>';
            
            }
          ?>
          <tr class="curs_pointer" style="text-align:center;">
            <td><?php echo $inter_id ?></td>
            <td><?php echo $case_id ?></td>
            <td><?php echo $out_num ?></td>
            <td><?php echo $out_date ?></td>
            <td><?php echo $rec_text ?></td>
            <td><?php echo $rec_name ?></td>
            <td><?php echo $send_type ?></td>
            <td><?php echo $msg_text ?></td>
            

            <td><?php echo $general_btn ?> </td>
            
       

          </tr>
          

          
        <?php } ?>
        </tbody>
    </table>

      </div>


      <div id="menu2" class="tab-pane fade"><br>
        <table class="table" id="inter_over">
          <thead>
          <tr style="font-size: 0.9em; font-weight: normal; color: #828282; text-align: center; vertical-align: middle;">
            <th width="10%">Ծանուցագիր #</th>
            <th width="10%">Գործ #</th>
            <th width="10%">Ելից #</th>
            <th width="15%">Առաքման ամսաթիվ</th>
            <th width="15%">Ծանուցման ամսաթիվ</th>
            <th width="15%">Ստացող</th>
            <th width="15%">Ստացողի ա.ա.հ.</th>
            <th width="15%">Առաքման եղանակը</th>
            <th width="25%">Հաղորդագրություն</th>
            <th width="10%">...</th>
            
          </tr>
          </thead>
          <tbody>
          <?php 
          while($row_all = $result_inter_over->fetch_assoc()){
            $case_id        = $row_all['case_id'];
             $out_num        = '';
            if (!empty($row_all['out_num'])) {
              $out_num = $row_all['out_num'];
            }
            else
            {
              $out_num = 'Ն/ք';
            }

            $inter_id       = $row_all['inter_id'];
            $out_date       = date('d.m.Y', strtotime($row_all['actioned']));
            $approve_date   = date('d.m.Y', strtotime($row_all['notified_date']));
            $rec_text       = $row_all['inter_reciever_text'];
            $rec_name       = '';
            if ($row_all['inter_reciever'] == 1) {
              $rec_name = $row_all['f_name_arm'] . ' ' . $row_all['l_name_arm'];
            }
            if ($row_all['inter_reciever'] == 2) {
              $rec_name = $row_all['lawyer_name'] . ' ' . $row_all['lawyer_surname'];
            }

            if ($row_all['inter_reciever'] == 3) {
              $rec_name = $row_all['f_name_arm'] . ' ' . $row_all['l_name_arm'] .'<br/>'. $row_all['lawyer_name'] . ' ' . $row_all['lawyer_surname'];
            }


            $msg_text       = $row_all['inter_msg'];
            $send_type      = $row_all['SEND_TYPE_TEXT']
            
          
          ?>
          <tr class="curs_pointer" style="text-align:center;">
            <td><?php echo $inter_id ?></td>
            <td><?php echo $case_id ?></td>
            <td><?php echo $out_num ?></td>
            <td><?php echo $out_date ?></td>
            <td><?php echo $approve_date ?></td>
            <td><?php echo $rec_text ?></td>
            <td><?php echo $rec_name ?></td>
            <td><?php echo $send_type ?></td>
            <td><?php echo $msg_text ?></td>

            <td>
              
              <a href="#" case_id="<?php echo $case_id ?>" inter_id="<?php echo $inter_id ?>" class="btn btn-success btn-sm view_inter " ><i class="fas fa-eye" style="color: white;"></i></a></td>
            
           

          </tr>
          

          
        <?php } ?>
        </tbody>
    </table>

      </div>

</div>
</body>

<div class="modal fade" id="general_send_modal">
 
</div>






<script>
  
  $(document).ready(function () {

   //  $(document).on('submit', '#semi_edit_note', function () {
   //    $('#disabledSpinner').attr("disabled", true);
   //    $('#disabledSpinner').html('<i class="fa fa-spinner fa-spin"></i>Loading');
			// })

      $(".return_from_list").click(function () {

        var list_inter = $(this).attr('inter_id');
        var list_case = $(this).attr('case_id');
        
        $.ajax({
          url: "config/config.php",
          method: "POST",
          data: { return_from_list:list_case, inter_id: list_inter},
          success: function (data) {
            $('#general_send_modal').html(data);
            $("#general_send_modal").modal({backdrop: "static"});

          }
        });
      });   


      $(".send_retutn").click(function () {

        var general_inter = $(this).attr('inter_id');
        var general_case = $(this).attr('case_id');

        $.ajax({
          url: "config/config.php",
          method: "POST",
          data: {general_return: general_inter, case_id: general_case},
          success: function (data) {
            $('#general_send_modal').html(data);
            $("#general_send_modal").modal({backdrop: "static"});

          }
        });
      });  




     $(".officer_edit").click(function () {

        var general_inter = $(this).attr('inter_id');
        var general_case = $(this).attr('case_id');

        $.ajax({
          url: "config/config.php",
          method: "POST",
          data: {eidt_from_list: general_inter, edit_inter:general_case, inter_id: general_inter},
          success: function (data) {
            $('#general_send_modal').html(data);
            $("#general_send_modal").modal({backdrop: "static"});

          }
        });
      });  




    $(".devhead_app_inter").click(function () {
        var general_inter = $(this).attr('inter_id');
        var general_case = $(this).attr('case_id');
        $.ajax({
          url: "config/config.php",
          method: "POST",
          data: {dev_approve_inter:general_case, general_inter: general_inter},
          success: function (data) {
            $('#general_send_modal').html(data);
            $("#general_send_modal").modal({backdrop: "static"});

          }
        });
      });


    $(".officer_cancel").click(function () {
        var general_inter = $(this).attr('inter_id');
        var general_case = $(this).attr('case_id');
        $.ajax({
          url: "config/config.php",
          method: "POST",
          data: {close_from_list: general_case, close_inter:general_case, inter_id: general_inter},
          success: function (data) {
            $('#general_send_modal').html(data);
            $("#general_send_modal").modal({backdrop: "static"});

          }
        });
      });


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

    $(".inter_approve").click(function () {
        var general_inter = $(this).attr('inter_id');
        var general_case = $(this).attr('case_id');
        $.ajax({
          url: "config/config.php",
          method: "POST",
          data: {general_case_approve:general_case, general_inter: general_inter},
          success: function (data) {
            $('#general_send_modal').html(data);
            $("#general_send_modal").modal({backdrop: "static"});

          }
        });
      });

    });


  $('.dataTables_filter input[type="search"]').css(
          {'width':'500px','display':'inline-block'}
      );  
      
  var table = $('#inter_in').DataTable({
        "pageLength": 25,
            "lengthChange": false,
            "bInfo": true,
            "pagingType": 'full_numbers',
          "responsive": true,
            
          "language": {
             "search": "_INPUT_",            // Removes the 'Search' field label
              "searchPlaceholder": "ՈՐՈՆԵԼ",   // Placeholder for the search box
            "paginate": {
            "next": '<i class="fas fa-arrow-right"></i>', // or '→'
            "previous": '<i class="fas fa-arrow-left"></i>', // or '←' 
            "first": '<i class="fas fa-chevron-left"></i>',
            "last": '<i class="fas fa-chevron-right"></i>'
    },
        "info": " _PAGE_ էջ _PAGES_ ից",
        "infoEmpty": "",
          "zeroRecords": "Մտից ծանուցումներ առկա չեն։",
          }
      });
   
   var table_out = $('#inter_out').DataTable({
        "pageLength": 25,
            "lengthChange": false,
            "bInfo": true,
            "pagingType": 'full_numbers',
          "responsive": true,
            
          "language": {
             "search": "_INPUT_",            // Removes the 'Search' field label
              "searchPlaceholder": "ՈՐՈՆԵԼ",   // Placeholder for the search box
            "paginate": {
            "next": '<i class="fas fa-arrow-right"></i>', // or '→'
            "previous": '<i class="fas fa-arrow-left"></i>', // or '←' 
            "first": '<i class="fas fa-chevron-left"></i>',
            "last": '<i class="fas fa-chevron-right"></i>'
    },
        "info": " _PAGE_ էջ _PAGES_ ից",
        "infoEmpty": "",
          "zeroRecords": "Ելից ծանուցումներ առկա չեն։",
          }
      });    
      $(document).on("change", ".custom-file-input", function () {
      var fileName = $(this).val().split("\\").pop();
      $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });


   var table_over = $('#inter_over').DataTable({
        "pageLength": 25,
            "lengthChange": false,
            "bInfo": true,
            "pagingType": 'full_numbers',
          "responsive": true,
            
          "language": {
             "search": "_INPUT_",            // Removes the 'Search' field label
              "searchPlaceholder": "ՈՐՈՆԵԼ",   // Placeholder for the search box
            "paginate": {
            "next": '<i class="fas fa-arrow-right"></i>', // or '→'
            "previous": '<i class="fas fa-arrow-left"></i>', // or '←' 
            "first": '<i class="fas fa-chevron-left"></i>',
            "last": '<i class="fas fa-chevron-right"></i>'
    },
        "info": " _PAGE_ էջ _PAGES_ ից",
        "infoEmpty": "",
          "zeroRecords": "Ավարտված ծանուցումներ առկա չեն։",
          }
      });        

</script>

