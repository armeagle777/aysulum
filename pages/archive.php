<?php
if(!isset($_SESSION['username'])  || ($_SESSION['role']!=="admin" && $_SESSION['role']!=="archiver"&& $_SESSION['role']!=="operator" && $_SESSION['role']!=="statist" && $_SESSION['role']!=="viewer" && $_SESSION['role']!=="lawyer" && $_SESSION['role'] !== 'nss'&& $_SESSION['role'] !== 'fin' && $_SESSION['role'] !== 'secretary' && $_SESSION['role'] !== 'dorm' && $_SESSION['role'] !== 'police'&& $_SESSION['role']!=="officer" && $_SESSION['role']!=="devhead" && $_SESSION['role']!=="coispec" && $_SESSION['role']!=="head")){
    header("location: ../index.php");
}
   include('config/connect.php');
    date_default_timezone_set('Asia/Yerevan');
    
    
      $sql_old_cases_by_head = "SELECT a.old_case_id, a.application_date, a.citizenship, b.old_person_id, b.f_name_arm, b.l_name_arm, b.role, c.country_arm, d.ms_decision, d.ms_decision_date, d.final_decision, d.final_decision_date, e.decision_type AS MS_DECISION_TEXT, f.decision_type AS FINAL_DECISION_TEXT
            FROM old_cases a 
            INNER JOIN old_case_person b ON a.old_case_id = b.old_case_id 
            INNER JOIN tb_country c ON a.citizenship = c.country_id
            INNER JOIN old_case_decisions d ON a.old_case_id = d.old_case_id
            INNER JOIN tb_decision_types e ON d.ms_decision = e.decision_type_id
            LEFT JOIN tb_decision_types f ON d.final_decision = f.decision_type_id
            WHERE b.role = 1";
      $result_sql_old_cases_by_head = $conn->query($sql_old_cases_by_head);

      $sql_application_year = "SELECT DISTINCT(YEAR(application_date)) AS APPLICATION_YEAR FROM old_cases ORDER BY APPLICATION_YEAR DESC";
      $result_sql_application_year = $conn->query($sql_application_year);
      $optyear =    '<select name="select_year" id="select_year" class="form-control form-control-sm">
                     <option selected disabled hidden>Ընտրե՛ք տարեթիվը </option>';
                     while($row = $result_sql_application_year->fetch_assoc())
      {
        $optyear.= "<option value=".$row['APPLICATION_YEAR'].">".$row['APPLICATION_YEAR']."</option>"; 
      }
        $optyear.="</select>";              

      $query_country = "SELECT * FROM tb_country ORDER BY country_eng DESC";
      $state = $conn->query($query_country);
      $optcitizen = '<select name="select_state" id="select_state" class="form-control form-control-sm">
                     <option selected disabled hidden>Ընտրե՛ք երկիրը </option>';
                     while($row1 = $state->fetch_assoc())
      {
        $optcitizen.= "<option value=".$row1['country_id'].">".$row1['country_arm']."</option>"; 
      }
      $optcitizen.="</select>";  

      $sql_ms_decision = "SELECT * FROM tb_decision_types";
      $result_ms_decision = $conn->query($sql_ms_decision);
      $optmsdecision = '<select name="select_ms_decision" id="select_ms_decision" class="form-control form-control-sm">
                     <option selected disabled hidden>Ընտրե՛ք ՄԾ որոշումը </option>';
                     while($row2 = $result_ms_decision->fetch_assoc())
      {
      $optmsdecision.= "<option value=".$row2['decision_type_id'].">".$row2['decision_type']."</option>"; 
      }
      $optmsdecision.="</select>";  

?>
<div class="case_area mt-2">

<div class="row">
    <div class="col-md-2">
        <label class="label_pers_page">Դիմումի տարեթիվը</label>
        <?php echo  $optyear ?>
     </div>

    <div class="col-md-2">
        <label class="label_pers_page">Քաղաքացիության երկիրը</label>
        <?php echo $optcitizen ?>
    </div>

    <div class="col-md-2">
        <label class="label_pers_page">ՄԾ որոշում</label>
        <?php echo $optmsdecision ?>
    </div>
    
</div>

    <table class="table mt-3" id="old_cases_by_head">
        <thead>
            <tr  style="font-size: 0.8em; font-weight: normal; color: #828282; text-align: center;">
                <th>Գործ #</th>
                <th>Դիմումի ամսաթիվ</th>
                <th>Գլխավոր դիմումատուի ա.ա.հ.</th>
                <th>Քաղաքացիություն</th>
                <th>ՄԾ որոշում</th>
                <th>Վերջնական որոշում</th>
                <th><i class="fas fa-ellipsis-h"></i></th>
            </tr>
        </thead>
         <tbody>
        <?php 
        while($row = $result_sql_old_cases_by_head->fetch_assoc()){
            $case_id = $row['old_case_id'];
            $application_date = date('d.m.Y', strtotime($row['application_date']));
            $main_applicant   = $row['f_name_arm'] .' '. $row['l_name_arm'];
            $citizenship      = $row['country_arm'];
            $ms_decision      = $row['MS_DECISION_TEXT'];
            $final_decision   = $row['FINAL_DECISION_TEXT'];
        ?>
       
            <tr style="font-size: 1em; color:#324157; text-align: center;">
                <td class="b_table_modal"><?php echo $case_id?></td>
                <td><?php echo $application_date ?></td>
                <td><?php echo $main_applicant ?></td>
                <td><?php echo $citizenship ?></td>
                <td><?php echo $ms_decision ?></td>
                <td><?php echo $final_decision ?></td>
                <td><a href="#" class="view_old" old_id="<?php echo $case_id?>" ><i class="far fa-eye ml-3" style="color: blue; font-size: 1.22em;"></i></a>
                    <a href="user.php?page=old_case_page&old_case=<?php echo $case_id?>"> gg</a>
                </td>

            </tr>
        
    <?php } ?>
    </tbody>
    </table>

</div>

<div class="modal fade" id="old_case_modal">  

</div>


<script>
     $(document).ready(function () {             
          $('.dataTables_filter input[type="search"]').css(
          {'width':'500px','display':'inline-block'}
      );  
      });

     var table = $('#old_cases_by_head').DataTable({
            "pageLength": 20,
            "lengthChange": false,
            "bInfo": true,
            "pagingType": 'full_numbers',
             "order": [[ 0, "desc" ]],
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
                "zeroRecords": "Մտից գրություններ չկան։",
          }
        });


    $("#select_state").chosen();
    $("#select_year").chosen();
    $("#select_ms_decision").chosen();
      

    $(".view_old").click(function(){
    var old_case_id = $(this).attr('old_id');
    $.ajax({
                url:"config/config_old.php",
                method:"POST",
                data:{old_case_id:old_case_id},
                success:function(data)
                {  
                   console.log(old_case_id);
                   $('#old_case_modal').html(data);
                   $("#old_case_modal").modal({backdrop: "static"});
                    
                } 
            });
      });  


</script>

