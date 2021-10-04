<?php
require_once 'config/connect.php';

$u_id = $_SESSION['user_id'];

  $role_base = '';
if ($_SESSION['role']==="officer" || $_SESSION['role']==="coispec" || $_SESSION['role']==="lawyer") {
  $role_base.= " AND a.officer = $u_id";
}

$query_cases = "SELECT a.case_id, b.f_name_arm, b.l_name_arm, a.application_date, a.mul_date, c.sign_status, d.status, c.sign_date, e.case_status, a.input_date, c.sign_by, k.deadline, 
g.l_name AS OFFICER_LNAME, g.f_name AS OFFICER_NAME
FROM tb_case a 
INNER JOIN tb_person b ON a.case_id = b.case_id 
INNER JOIN tb_process c ON a.case_id = c.case_id 
INNER JOIN tb_sign_status d ON d.status_id = c.sign_status 
INNER JOIN tb_case_status e ON a.case_status = e.case_status_id 
LEFT JOIN users g ON a.officer = g.id 
LEFT JOIN tb_deadline k ON a.case_id = k.case_id 
WHERE b.role = 1 AND c.actual = 1 AND k.actual_dead = 1 AND c.sign_status = 16 $role_base";
$query_cases_result = $conn->query($query_cases);

?>


<body>
  <table class="table table-stripped table-bordered" id="inboxTable">
    <thead>
      <tr style="font-size: 0.9em; font-weight: normal; color: #828282; text-align: center; vertical-align: middle;">
        <th width="7%">Գործ #</th>
        <th width="14%">ապաստան հայցողի ա․ա․հ․</th>
        <th width="15%">դիմումի ամսաթիվ</th>
        <th width="15%">գրանցման ամսաթիվ</th>
        <th width="15%">կասեցման ամսաթիվ</th>
        <th width="15%">վերջնաժամկետ </th>
        <th width="10%">գործը վարող</th>        
      </tr>
    </thead>
    <tbody>
      <?php 
      while ($row = mysqli_fetch_array($query_cases_result)) {
        $app_date  = date("d.m.Y", strtotime($row['application_date']));
        $mul_date  = date("d.m.Y", strtotime($row['mul_date']));
        $sign_date = $row["sign_date"];
        $new_sign_date = date("d.m.Y", strtotime($sign_date));
        
        $ddate  = $row["deadline"];
				$actual_date = date("Y-m-d");

        		$deadline = new DateTime($ddate);
        		$today = new DateTime($actual_date);
				
				$formated_deadline = $today->diff($deadline)->format("%r%a");
          
		          if($formated_deadline > 1000 || $formated_deadline < -1000){
		            $formated_deadline = 'N/A';
		          } 

		          if ($formated_deadline < 0) {
		            $color = 'red';
		          }

      ?>

      <tr style="font-size: 1em; color:#324157; text-align: center; " class="curs_pointer">
        <td><?= $row["case_id"] ?></td>
        <td><?= $row["f_name_arm"] .' '. $row["l_name_arm"] ?></td>
        <td><?php echo $app_date; ?></td>
        <td><?php echo $mul_date; ?></td>
        <td><?php echo $new_sign_date; ?></td>
        <td><font color="<?php echo $color?>" > <?php echo $formated_deadline ?> </font></td>
        <td><?= $row["OFFICER_NAME"] .' '. $row["OFFICER_LNAME"] ?></td>
        
        
      </tr>
      
      <?php 
        } 
      ?>
    </tbody>
  </table>

<script>
  

   $(document).ready(function () {             
          $('.dataTables_filter input[type="search"]').css(
          {'width':'500px','display':'inline-block'}
      );  
      });

   var table = $('#inboxTable').DataTable({
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
          }
      });
       
      $('#inboxTable').on( 'click', 'tr', function () {
          var data = table.row( this ).data()[0];
          location.replace(`user.php?page=cases&homepage=case_page&case=${data}`);
          //alert( 'Clicked row id '+data[0]+'\'s row' );
      } );

</script>
</body>
