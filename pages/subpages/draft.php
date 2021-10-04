<?php
require_once 'config/connect.php';

$u_id = $_SESSION['user_id'];

$sql_drafts = "SELECT a.draft_id, a.case_id, a.draft_file, a.autor, a.uploaded, a.deadline, a.receiver, a.draft_comment, a.actual, b.f_name AS AUTOR_FNAME, b.l_name AS AUTOR_LNAME, c.f_name AS RECEIVER_FNAME, c.l_name AS RECEIVER_LNAME, d.f_name_arm, d.l_name_arm, d.m_name_arm FROM tb_draft a INNER JOIN users b ON a.autor = b.id INNER JOIN users c ON a.receiver = c.id INNER JOIN tb_person d ON a.case_id = d.case_id INNER JOIN tb_case e ON a.case_id = e.case_id WHERE a.actual = '1' AND d.role = 1 AND a.receiver = $u_id AND e.case_status IN (1,2,3) ORDER BY a.draft_id DESC";
$result_draft = $conn->query($sql_drafts);

$sql_drafts_out = "SELECT a.draft_id, a.case_id, a.draft_file, a.autor, a.uploaded, a.deadline, a.receiver, a.draft_comment, a.actual, b.f_name AS AUTOR_FNAME, b.l_name AS AUTOR_LNAME, c.f_name AS RECEIVER_FNAME, c.l_name AS RECEIVER_LNAME, d.f_name_arm, d.l_name_arm, d.m_name_arm FROM tb_draft a INNER JOIN users b ON a.autor = b.id INNER JOIN users c ON a.receiver = c.id INNER JOIN tb_person d ON a.case_id = d.case_id INNER JOIN tb_case e ON a.case_id = e.case_id WHERE d.role = 1 AND a.autor = $u_id AND e.case_status IN (1,2,3) ORDER BY a.draft_id DESC";
$result_draft_out = $conn->query($sql_drafts_out);


?>




<body>
  <!-- Nav tabs -->
<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link active" data-toggle="tab" href="#home"><i class="fas fa-arrow-circle-down"></i> Մտից</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#menu1"><i class="fas fa-arrow-circle-up"></i> Ելից</a>
  </li>
  
 </ul>
 
 <!-- Tab panes -->
    <div class="tab-content">
      
      <div id="home" class="tab-pane active"><br>
         <table class="table table-stripped " id="draft_inbox">
      <thead>
      <tr style="font-size: 0.9em; font-weight: normal; color: #828282; text-align: center; vertical-align: middle;">
        <th width="5%">Նախագիծ #</th>
        <th width="5%">Գործ #</th>
        <th width="25%">Ապաստան հայցողի ա․ա․հ․</th>
        <th width="15%">Ուղարկող</th>
        <th width="15%">Ամսաթիվ</th>
        <th width="15%">Վերջնաժամկետ</th> 
      </tr>
      
      </thead>
      <tbody>
      <?php 
      while($row = mysqli_fetch_array($result_draft)){
        $case_id = $row['case_id'];
        $asylum_seeker = $row['f_name_arm'] . ' ' . $row['l_name_arm'] .' '. $row['m_name_arm'];
        $author = $row['AUTOR_FNAME'] . ' ' . $row['AUTOR_LNAME'];
        $sdate  = date("d.m.Y", strtotime($row['uploaded']));
        $ddate  = $row['deadline'];
        $actual_date = date("Y-m-d");

        $deadline = new DateTime($ddate);
        $today = new DateTime($actual_date);
        
        $abs_diff = $today->diff($deadline)->format("%r%a");
          
          if($abs_diff > 1000 || $abs_diff < -1000){
            $abs_diff = 'N/A';
          } 

          if ($abs_diff < 0) {
            $color = 'red';
          }
          $draft_id = $row['draft_id'];
      ?>
      
      <tr style="font-size: 1.1em; color:#324157; text-align: center; " class="curs_pointer">
       <td><?php echo $draft_id ?></td> 
       <td><?= $row['case_id'] ?></td>
       <td><?php echo $asylum_seeker ?></td>
       <td><?= $row['AUTOR_FNAME'] . ' ' . $row['AUTOR_LNAME'] ?></td>
       <td><?php echo $sdate ?></td>
       <td><font color="<?php echo $color?>" > <?php echo $abs_diff ?> </font></td>
      </tr>
      
      <?php  }?>
      </tbody>
  </table>
      </div>



     <div id="menu1" class="tab-pane fade"><br>
         <table class="table table-stripped" id="draft_out">
      <thead>
      <tr style="font-size: 0.9em; font-weight: normal; color: #828282; text-align: center; vertical-align: middle;">
        <th width="5%">Նախագիծ #</th>
        <th width="5%">Գործ #</th>
        <th width="25%">Ապաստան հայցողի ա․ա․հ․</th>
        <th width="15%">Ստացող</th>
        <th width="15%">Ամսաթիվ</th>        
      </tr>
      
      </thead>
      <tbody>
      <?php 
      while($row_out = $result_draft_out->fetch_assoc()){
        $case_id = $row_out['case_id'];
        $asylum_seeker = $row_out['f_name_arm'] . ' ' . $row_out['l_name_arm'];
        $draft_to = $row_out['RECEIVER_FNAME'] . ' ' . $row_out['RECEIVER_LNAME'];
        $sdate  = date("d.m.Y", strtotime($row_out['uploaded']));
        $draft_id = $row_out['draft_id'];
      ?>

      <tr style="font-size: 1.1em; color:#324157; text-align: center; " class="curs_pointer">
       <td><?php echo $draft_id ?></td> 
       <td><?= $row_out['case_id'] ?></td>
       <td><?php echo $asylum_seeker ?></td>
       <td><?php echo $draft_to ?></td>
       <td><?php echo $sdate ?></td> 
      </tr>
      
      <?php  }?>
      </tbody>
  </table>
      </div>  

<script>
  

   $(document).ready(function () {             
          $('.dataTables_filter input[type="search"]').css(
          {'width':'500px','display':'inline-block'}
      );  
      });

   var table = $('#draft_out').DataTable({
        "pageLength": 25,
            "lengthChange": false,
            "bInfo": true,
            "pagingType": 'full_numbers',
          "responsive": true,
           "order": [[ 0, "desc" ]],
            
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
          "zeroRecords": "Ձեզ ուղարկված մտից որոշման նախագծեր չի հայտնաբերվել։",
          }
      });
       
      $('#draft_out').on( 'click', 'tr', function () {
          var draft_id_in = table.row( this ).data()[0];
          var case_id = table.row( this ).data()[1];
          location.replace(`user.php?page=cases&homepage=draft_page&draft=${draft_id_in}&case=${case_id}`);
          //alert( 'Clicked row id '+data[0]+'\'s row' );
      } );

      var table_out = $('#draft_inbox').DataTable({
        "pageLength": 25,
            "lengthChange": false,
            "bInfo": true,
            "pagingType": 'full_numbers',
          "responsive": true,
           "order": [[ 0, "desc" ]],
            
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
          "zeroRecords": "Դուք չունեք ելից որոշման նախագծեր։",
          }
      });
       
      $('#draft_inbox').on( 'click', 'tr', function () {
          var draft_id_out = table_out.row( this ).data()[0];
          var case_id_out = table_out.row( this ).data()[1];
          location.replace(`user.php?page=cases&homepage=draft_page&draft=${draft_id_out}&case=${case_id_out}`);
          //alert( 'Clicked row id '+data[0]+'\'s row' );
      } );

</script> 



</body>
