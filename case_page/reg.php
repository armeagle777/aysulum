<?php
require_once '../config/connect.php';

$query_marz = "SELECT * FROM tb_marz";
$marzes = mysqli_query($conn, $query_marz);
$opt_marz = '';
while ($row_marz = mysqli_fetch_array($marzes)) {  
$opt_marz = $opt_marz."<option value=$row_marz[marz_id]> $row_marz[ADM1_ARM]</option>";}


?>



<!DOCTYPE html>
  <html>
    <head>
    	<title>ASYLUM CMS</title>
    	<meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <!--CSS  -->
      <link rel="stylesheet" type="text/css" href="case_page.css">
      <!--  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
      <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous"> -->
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
      <!-- DataTables CSS library -->
      <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css"/>
      <link rel="preconnect" href="https://fonts.gstatic.com">
      <link href="https://fonts.googleapis.com/css2?family=Indie+Flower&family=Karantina:wght@300;700&display=swap" rel="stylesheet">
      <!--JS  -->
      <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> -->
       <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script> 
      <script src="https://kit.fontawesome.com/78ca84cc9f.js" crossorigin="anonymous"></script>
      <!-- jQuery library -->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
      <!-- DataTables JS library -->
      <script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
      <!-- Old Datatable scripts  -->
      <script  src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>
      <script  src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.flash.min.js"></script>
      <script  src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
      <script  src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
      <script  src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
      <script  src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>

    </head>

    <body>
  <form method="POST" action="action_case.php">    
  <div class="btn_area"> 
  <div class="row">
    <div class="left_btns">
      <h5>Ապաստանի հայցի գրանցում</h5>
    </div>   
    <div class="right_btns">  
      
      <button name="save_new_case" class="btn btn-success btn-sm r_btns" type="submit">ՊԱՀՊԱՆԵԼ</button>
      <button name="cancel" class="btn btn-secondary btn-sm r_btns" type="submit">ՉԵՂԱՐԿԵԼ</button>
    </div>  
  </div>
  </div>

<div class="case_area" style="border: solid;">
   <div class="col-md-12">
      <div class="row">
      <div class="col-md-6">
       <h5 class="sub_title">Գրանցման մանրամասներ </h5> 
              <table class="table">
              <tr>
                <th class="table_a">Դիմումի ամսաթիվ</th>
                <td><input type="date" name="application_date" class="form-control form-control-sm" required="required"></td>
              </tr>
              <tr>
                <th class="table_a">Մուտքագրող աշխատակից</th>
                <td><input type="text" name="registrar" class="form-control form-control-sm" required="required"></td>
              </tr>
              
              </table>
      <h5 class="sub_title" style="margin-top: 10px;">Լրացուցիչ տվյալներ</h5>        
      <textarea class="form-control form-control-sm" rows="10" name="comment_1"></textarea>

      </div> <!--close 1st col-md-6-->
      <div class="col-md-6">
        <h5 class="sub_title">Հատուկ նշումներ </h5> 
       <table class="table">
                  <tr>
                    <th class="b_table">Առանց ուղեկցողի երեխա</th>
                    <th class="b_table">Ընտանիքից անջատված երեխա</th>
                    <th class="b_table">Միայնակ ծնող</th>
                    <th class="b_table">Ցանկանում է իրավաբան</th>
                  </tr>
                  <tr>
                    <td><input type="checkbox" class="form-control form-control-sm" name="unaccompanied_child" id="unaccompanied_child"> </td>
                    <td><input type="checkbox" class="form-control form-control-sm" name="separated_child" id="separated_child"> </td>
                    <td><input type="checkbox" class="form-control form-control-sm" name="single_parent" id="single_parent"> </td>
                    <td><input type="checkbox" class="form-control form-control-sm" name="prefere_lawyer" id="prefere_lawyer"> </td>                    
                  </tr>
              </table>
              <div class="row">
               <div class="col-md-6">
                <label class="label_pers_page">Նախընտրելի լեզուն</label>
                <input type="text" name="pref_language" class="form-control form-control-sm">
              </div>
              <div class="col-md-6">
                <label class="label_pers_page">Հեռախոսահամար</label>
                <input type="text" name="tel" class="form-control form-control-sm">
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
                  <select class="form-control form-control-sm"  id="select_marz" name="select_marz" required="required"> 
                    <option>Ընտրե՛ք մարզը</option>
                    <?php echo $opt_marz?>
                  </select>
                </td>

                <td><select class="form-control form-control-sm" id="select_community" name="select_community" required="required">
                    <option>Ընտրե՛ք համայնքը</option>
                    <?php echo $opt_community?>
                  </select></td>
                <td>
                  <select class="form-control form-control-sm" id="select_setl" name="select_bnakavayr" required="required">
                    <option>Ընտրե՛ք բնակավայրը</option>
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
    

    <div class="col-md-12">
      <div class="row">
      <div class="col-md-6">
         <h5 class="sub_title">Քաղաքացիության երկիրը լքելու պատճառները և վերադարձի հետևանքները</h5>
         <div class="col-md-12">
         <label class="label_pers_page">Քաղաքացիության երկիրը լքելու պատճառները </label>
         <textarea class="form-control form-control-sm" rows="10" name="reason_1"></textarea>
         </div>
         <div class="col-md-12">
           <label class="label_pers_page">Քաղաքացիությա երկիր վերադառնալու հետևաքները</label>
           <textarea class="form-control form-control-sm" rows="10" name="sequence_1"></textarea>
         </div>
      </div> <!--close 1st col-md-6 of 2nd col-md-12 -->

      <div class="col-md-6">
          <h5 class="sub_title">Նախկին բնակության երկիրը լքելու պատճառները և վերադարձի հետևանքները</h5>
          <div class="col-md-12">
         <label class="label_pers_page">Նախկին բնակության երկիրը լքելու պատճառները </label>
         <textarea class="form-control form-control-sm" rows="10" name="reason_2"></textarea>
         </div>
         <div class="col-md-12">
           <label class="label_pers_page">Նախկին բնակության երկիր վերադառնալու հետևաքները</label>
           <textarea class="form-control form-control-sm" rows="10" name="sequence_2"></textarea>
         </div>
      </div> <!--close 2նդ col-md-6 of 2nd col-md-12 -->

      </div> <!--close row-->
    </div> <!--close 2nd col-md-12-->
</div>

</form>

<script type="text/javascript">
   $(document).ready(function(){
   // Country dependent ajax
   $("#select_marz").on("change",function(){
   var marzId = $(this).val();
   //console.log(marzId);
   if (marzId) {
    $.ajax({
      url :"action.php",
    type:"POST",
    cache:false,
    data:{marzId:marzId},
    success:function(data){
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
    url :"action.php",
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
            $('#prefere_lawyer').prop('disabled', true)
        }
        else
        {
            $('#separated_child').prop('disabled', false);
            $('#single_parent').prop('disabled', false);
            $('#prefere_lawyer').prop('disabled', false)
        }
    });

   $('#separated_child').change(function() {
        if($(this).is(":checked"))
        { 
            $('#unaccompanied_child ').prop('disabled', true);
            $('#single_parent').prop('disabled', true);
            $('#prefere_lawyer').prop('disabled', true)
        }
        else
        {
            $('#unaccompanied_child').prop('disabled', false);
            $('#single_parent').prop('disabled', false);
            $('#prefere_lawyer').prop('disabled', false)
        }
    });

   $('#single_parent').change(function() {
        if($(this).is(":checked"))
        { 
            $('#unaccompanied_child ').prop('disabled', true);
            $('#separated_child').prop('disabled', true);
            $('#prefere_lawyer').prop('disabled', true)
        }
        else
        {
            $('#unaccompanied_child').prop('disabled', false);
            $('#separated_child').prop('disabled', false);
            $('#prefere_lawyer').prop('disabled', false)
        }
    });

   $('#prefere_lawyer').change(function() {
        if($(this).is(":checked"))
        { 
            $('#unaccompanied_child ').prop('disabled', true);
            $('#separated_child').prop('disabled', true);
            $('#single_parent').prop('disabled', true)
        }
        else
        {
            $('#unaccompanied_child').prop('disabled', false);
            $('#separated_child').prop('disabled', false);
            $('#single_parent').prop('disabled', false)
        }
    });
</script>


</body>


