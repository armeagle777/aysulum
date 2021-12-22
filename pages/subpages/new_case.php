<?php 
require_once 'config/connect.php';

$u_id = $_SESSION['user_id'];
$u_fname = $_SESSION['user_fName'];
$u_lname = $_SESSION['user_lName'];




$query_marz = "SELECT * FROM tb_marz";
$marzes = mysqli_query($conn, $query_marz);
$opt_marz = '';
while ($row_marz = mysqli_fetch_array($marzes)) {  
$opt_marz = $opt_marz."<option value=$row_marz[marz_id]> $row_marz[ADM1_ARM]</option>";}

?>


 <form method="POST" action="config/config.php" enctype="multipart/form-data">    
  <div class="btn_area"> 
  <div class="row">
    <div class="left_btns">
      <h5 style="font-weight: bold;">Ապաստանի հայցի գրանցում</h5>
    </div>   
    <div class="right_btns">  
      
      <button name="save_new_case" id="save_new_case" class="btn btn-success btn-sm r_btns" type="submit">ՊԱՀՊԱՆԵԼ</button>
      <button name="cancel" class="btn btn-secondary btn-sm r_btns" type="submit">ՉԵՂԱՐԿԵԼ</button>
    </div>  
  </div>
  </div>


   <div class="col-md-12">
      <div class="row">
      <div class="col-md-4">
       


       <h5 class="sub_title" >Գրանցման մանրամասներ </h5> 
              <table class="table">
              <tr>
                <th class="table_a">Դիմումի ամսաթիվ</th>
                <td><input type="date" name="application_date" class="form-control form-control-sm" required="required"></td>
              </tr>
              <tr>
                <th class="table_a">Մալբրի համակարգում դիմումի #</th>
                <td><input type="text" name="mul_num" class="form-control form-control-sm" required="required"></td>
              </tr>
              <tr>
                <th class="table_a">Մալբրի համակարգ մուտքագրման ամսաթիվ</th>
                <td><input type="date" name="mul_date" class="form-control form-control-sm" required="required"></td>
              </tr>
              <tr>
                <th class="table_a">Մուտքագրող աշխատակից</th>
                <td><input type="hidden" name="registrar"  value="<?php echo $u_id?>"> 
                  <input type="text" class="form-control form-control-sm" value="<?php echo $u_fname .' '. $u_lname?>" readonly>
                </td>

              </tr>
              
              </table>
      <h5 class="sub_title" style="margin-top: 10px;">Լրացուցիչ տեղեկություններ</h5>        
      <textarea class="form-control form-control-sm" rows="8" name="comment_1"></textarea>

      </div> <!--close 1st col-md-6-->
      <div class="col-md-8">
        <h5 class="sub_title">Հատուկ նշումներ </h5> 
       <table class="table">
                  <tr>
                    <th class="b_table1">Առանց ուղեկցողի երեխա</th>
                    <th class="b_table1">Ընտանիքից անջատված երեխա</th>
                    <th class="b_table1">Միայնակ ծնող</th>
                    <th class="b_table1">Հատուկ գործ</th>
                    <th class="b_table1">Կրկնակի</th>
                    <th class="b_table1">Ցանկանում է իրավաբան</th>
                  </tr>
                  <tr>
                    <td><input type="checkbox" class="form-control form-control-sm" name="unaccompanied_child" id="unaccompanied_child"> </td>
                    <td><input type="checkbox" class="form-control form-control-sm" name="separated_child" id="separated_child"> </td>
                    <td><input type="checkbox" class="form-control form-control-sm" name="single_parent" id="single_parent"> </td>
                    <td><input type="checkbox" class="form-control form-control-sm" name="special_case" id="special_case"> </td>
                    <td><input type="checkbox" class="form-control form-control-sm" name="reopened_case" id="reopened_case"> </td>
                    <td><input type="checkbox" class="form-control form-control-sm" name="prefere_lawyer" id="prefere_lawyer"> </td>                    
                  </tr>
              </table>
              <div class="row">
               <div class="col-md-3">
                <label class="label_pers_page">Նախընտրելի լեզուն</label>
                <input type="text" name="pref_language" class="form-control form-control-sm">
              </div>
              <div class="col-md-3">
                <label class="label_pers_page">Հեռախոսահամար</label>
                <input type="text" name="tel" class="form-control form-control-sm">
              </div>
              <div class="col-md-3">
                <label class="label_pers_page">Էլ.փոստ</label>
                <input type="text" name="case_email" class="form-control form-control-sm">
              </div>
              <div class="col-md-3">
                <label class="label_pers_page" id="former_case_label" style="display:none;">Նախկին գործի #</label>
                <input type="text" name="attached_case" id="attached_case" style="display:none;" class="form-control form-control-sm">
              </div>
              </div>
              <h5 class="sub_title" style="margin-top: 10px;">Բնակության հասցեն ՀՀ-ում</h5>
          <table class="table">
            <tr>
                <th class="pers_table"> Մարզ</th>
                <th class="pers_table"> Համայնք</th>
                <th class="pers_table"> Բնակավայր</th>
              </tr>
              <tr>  
                <td>
                  <select class="form-control form-control-sm"  id="select_marz" name="select_marz" required=required> 
                    <option value="0">Ընտրե՛ք մարզը</option>
                    <?php echo $opt_marz?>
                  </select>
                </td>

                <td><select class="form-control form-control-sm" id="select_community" name="select_community" required=required>
                    <option value="0">Ընտրե՛ք համայնքը</option>
                    <?php echo $opt_community?>
                  </select></td>
                <td>
                  <select class="form-control form-control-sm" id="select_setl" name="select_bnakavayr" required=required>
                    <option value="0">Ընտրե՛ք բնակավայրը</option>
                    <?php echo $opt_bnak?>
                  </select>

                </td>
              </tr>
              <tr>
                <th class="pers_table">Փողոց</th>
                <th class="pers_table">Շենք</th>
                <th class="pers_table">Բնակարան</th>
              </tr>
              <tr>
                <td><input type="text" name="street" class="form-control form-control-sm"></td>
                <td><input type="text" name="building" class="form-control form-control-sm"></td>
                <td><input type="text" name="flat" class="form-control form-control-sm"></td>
              </tr> 
                 
          </table>
      </div> <!--close 2nd col-md-6-->
      </div> <!--close row-->
    </div> <!--close col-md-12-->
    

   


</form>

<script type="text/javascript">
   $(document).ready(function(){
    
     $("#reopened_case").click(function () {
            if ($(this).is(":checked")) {
                $("#former_case_label ").show();
                $("#attached_case").show();
            
            } else {
                $("#former_case_label").hide();
                $("#attached_case").hide();
             
            }
        });




    $("#save_new_case").on("click", function(e)
    {
      var select_marz=$("#select_marz").val();
      var select_community=$("#select_community").val();
      var select_setl=$("#select_setl").val();
      if(select_marz==0 || select_community==0 || select_setl==0)
      {
        alert("Պարտադիր ընտրել մարզ, համայնք, բնակավայր");
        e.preventDefault();
      }
      
    })


   // Country dependent ajax
   $("#select_marz").on("change",function(){
   var marzId = $(this).val();
   //console.log(marzId);
   if (marzId) {
    $.ajax({
      url :"pages/subpages/action.php",
      type:"POST",
      cache:false,
      data:{marzId:marzId},
      success:function(data)
      {
        $("#select_community").html(data);
        $('#select_setl').html('<option value="">Նշե՛ք համայնքը</option>');
      }
    });
   }else{
  $('#select_community').html('<option value="">Նշե՛ք մարզը</option>');
    $('#select_setl').html('<option value="">Ընտրե՛ք բնակավայրը</option>');
   }
});

// state dependent ajax
 $("#select_community").on("change", function(){
   var bnakId = $(this).val();
   console.log(bnakId);
  if (bnakId) {
           $.ajax({
    url :"pages/subpages/action.php",
    type:"POST",
    cache:false,
    data:{bnakId:bnakId},
          success:function(data){
       $("#select_setl").html(data);
         }
     });
  }else{
           $('#select_setl').html('<option value="">Նշե՛ք համայնքը/option>');
  } 
     });
 });

   $('#unaccompanied_child').change(function() {
        if($(this).is(":checked"))
        { 
            $('#separated_child').prop('disabled', true);
            $('#single_parent').prop('disabled', true);
           
        }
        else
        {
            $('#separated_child').prop('disabled', false);
            $('#single_parent').prop('disabled', false);
           
        }
    });

   $('#separated_child').change(function() {
        if($(this).is(":checked"))
        { 
            $('#unaccompanied_child ').prop('disabled', true);
            $('#single_parent').prop('disabled', true);
            
        }
        else
        {
            $('#unaccompanied_child').prop('disabled', false);
            $('#single_parent').prop('disabled', false);
            
        }
    });

   $('#single_parent').change(function() {
        if($(this).is(":checked"))
        { 
            $('#unaccompanied_child ').prop('disabled', true);
            $('#separated_child').prop('disabled', true);
            
        }
        else
        {
            $('#unaccompanied_child').prop('disabled', false);
            $('#separated_child').prop('disabled', false);
           
        }
    });

  
   $(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});
</script>