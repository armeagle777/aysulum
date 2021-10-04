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
  <div class="btn_area"> 
  <div class="row">   
  <div class="dropdown">
      <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Հիմնական գործառույթներ </button>
      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item" href="#" id="myBtn3">Նշանակել կատարող</a>
        <a class="dropdown-item" href="#" id="myBtn4">Որոշման առաջարկ</a>
        <a class="dropdown-item" href="#">Վերադարձնել լրամշակման</a>
        <a class="dropdown-item" href="#">Հաստատել</a>
      </div>
</div>
<div class="">
      <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Լրացուցիչ գործառույթներ </button>
      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item" href="#">Ո</a>
        <a class="dropdown-item" href="#">Կարճել վարույթը</a>
        <a class="dropdown-item" href="#">Դադարեցնել վարույթը</a>
        <a class="dropdown-item" href="#">Երկարաձգել վերջնաժամկետը</a>
      </div>
</div>
</div>
</div>
        
<div class="case_area">
<div class="tab">
  <button class="tablinks" onclick="openCity(event, 'functions')" id="defaultOpen">Գործառույթներ</button>
  <button class="tablinks" onclick="openCity(event, 'main_applicant')">Գլխավոր դիմումատու</button>
  <button class="tablinks" onclick="openCity(event, 'family')">Ընտանիքի անդամներ</button>
  <button class="tablinks" onclick="openCity(event, 'reason_sequence')">Պատճառներ || հետևանքներ</button>
  <button class="tablinks" onclick="openCity(event, 'additional_data')">Լրացուցիչ տվյալներ</button>
</div>

<div id="functions" class="tabcontent">
  
  
    <div class="col-md-12">
      <div class="row">
      <div class="col-md-6">
       <h5 class="sub_title">Գրանցման մանրամասներ </h5> 
              <table class="table">
              <tr>
                <th class="table_a">Դիմումի ամսաթիվ</th>
                <td><input type="date" name="application_date" class="form-control form-control-sm"></td>
              </tr>
              <tr>  
                <th class="table_a">Մուտքագրման ամսաթիվ</th>
                <td><input type="date" name="input_date" class="form-control form-control-sm"></td>
              </tr>
              <tr>
                <th class="table_a">Մուտքագրող աշխատակից</th>
                <td><input type="text" name="registrar" class="form-control form-control-sm"></td>
              </tr>
              
              </table>


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
                    <td><input type="checkbox" class="form-control form-control-sm" name=""> </td>
                    <td><input type="checkbox" class="form-control form-control-sm" name=""> </td>
                    <td><input type="checkbox" class="form-control form-control-sm" name=""> </td>
                    <td><input type="checkbox" class="form-control form-control-sm" name=""> </td>                    
                  </tr>
              </table>

      </div> <!--close 2nd col-md-6-->
      </div> <!--close row-->
    </div> <!--close col-md-12-->
    

    <div class="col-md-12">
      <div class="row">
      <div class="col-md-6">
         
      </div> <!--close 1st col-md-6 of 2nd col-md-12 -->

      <div class="col-md-6">
       <h5 class="sub_title">Բնակության հասցեն ՀՀ-ում</h5>
          <table class="table">
            <tr>
                <th class="b_table"> Մարզ</th>
                <td><input type="text" name="case_num" class="form-control form-control-sm"></td>
              </tr>
              <tr>  
                <th class="b_table"> Համայնք</th>
                <td><input type="text" name="case_num" class="form-control form-control-sm"></td>
              </tr>
              <tr>
                <th class="b_table"> Բնակավայր</th>
                <td><input type="text" name="case_num" class="form-control form-control-sm"></td>
              </tr>
              <tr>
                <th class="b_table">Փողոց</th>
                <td><input type="text" name="case_num" class="form-control form-control-sm"></td>
              </tr> 
              <tr>
                <th class="b_table">Շենք</th>
                <td><input type="text" name="case_num" class="form-control form-control-sm"></td>
              </tr>  
              <tr>
                <th class="b_table">Բնակարան</th>
                <td><input type="text" name="case_num" class="form-control form-control-sm"></td>
              </tr>      
          </table>
      </div> <!--close 1st col-md-6 of 2nd col-md-12 -->
      </div> <!--close row-->
    </div> <!--close 2nd col-md-12-->
  

</div>

<div id="main_applicant" class="tabcontent">
  
  <div class="col-md-12">
    <div class="row">
      <div class="col-md-6">
        <h5 class="sub_title">Անձնական տվյալներ</h5>
        <table class="table ">
          <tr>
            <th class="pers_table">Անուն (հայատառ)</th>
            <th class="pers_table">Ազգանուն (հայատառ)</th>
            <th class="pers_table">Հայրանուն (հայատառ)</th>
          </tr>
          <tr>
            <td><input type="text" class="form-control form-control-sm" name="f_name_arm"></td>
            <td><input type="text" class="form-control form-control-sm" name="l_name_arm"></td>
            <td><input type="text" class="form-control form-control-sm" name="m_name_arm"></td>
          </tr>
           <tr>
            <th class="pers_table">Անուն (լատինատառ)</th>
            <th class="pers_table">Ազգանուն (լատինատառ)</th>
            <th class="pers_table">Հայրանուն (լատինատառ)</th>
          </tr>
          <tr>
            <td><input type="text" class="form-control form-control-sm" name="f_name_eng"></td>
            <td><input type="text" class="form-control form-control-sm" name="l_name_eng"></td>
            <td><input type="text" class="form-control form-control-sm" name="m_name_eng"></td>
          </tr>
          
          <tr>
            <th class="pers_table">ծննդյան օր ամիս տարի</th>
            <th class="pers_table">սեռ</th>
            <th class="pers_table">անձնագիր</th>
          </tr>
          <tr>
            <td><input type="text" class="form-control form-control-sm" name="b_day"></td>
            <td><input type="text" class="form-control form-control-sm" name="b_day"></td>
            <td><input type="text" class="form-control form-control-sm" name="b_day"></td>
          </tr>
          
          <tr>
            <th class="pers_table">ազգություն</th>
            <th class="pers_table">կրոն</th>
            <th class="pers_table">ՀՀ ժամանելու ամսաթիվ</th>
          </tr>
          <tr>
            <td><input type="text" class="form-control form-control-sm" name="b_day"></td>
            <td><input type="text" class="form-control form-control-sm" name="b_day"></td>
            <td><input type="text" class="form-control form-control-sm" name="b_day"></td>
          </tr>

        </table> 

        <h5 class="sub_title">Հատուկ կարիքներ</h5>
        <table class="table">
          <tr>
            <th class="special_needs_person">Հաշմանդամ</th>
            <th class="special_needs_person">Հղի կին</th>
            <th class="special_needs_person">Ծանր հիվանդ</th>
            <th class="special_needs_person">Թրաֆիքինգի զոհ</th>
            <th class="special_needs_person">Բռնության զոհ</th>
          </tr>
          <tr>
            <td><input type="checkbox" name="invalid" class="form-control"></td>
            <td><input type="checkbox" name="invalid" class="form-control"></td>
            <td><input type="checkbox" name="invalid" class="form-control"></td>
            <td><input type="checkbox" name="invalid" class="form-control"></td>
            <td><input type="checkbox" name="invalid" class="form-control"></td>
          </tr>          
        </table>

      </div> <!--close col-md-6-->
    <div class="col-md-6">
      <h5 class="sub_title">Քաղաքացիության և բնակության երկրներ</h5>
        <table class="table">
          <tr>
            <th class="table_a">Քաղաքացիության երկիր</th>
            <th class="table_a">Նախքին բնակության երկիր</th>
          </tr>
          <tr>
            <td><input type="text" class="form-control form-control-sm" name="b_day"></td>
            <td><input type="text" class="form-control form-control-sm" name="b_day"></td>
          </tr>

          <tr>
            <th class="table_a">Հասցեն քաղաքացիության երկրում</th>
            <th class="table_a">Հասցեն նախքին բնակության երկրում</th>
          </tr>
          <tr>
            <td><input type="text" class="form-control form-control-sm" name="b_day"></td>
            <td><input type="text" class="form-control form-control-sm" name="b_day"></td>
          </tr>

          <tr>
            <th class="table_a">Քաղաքացիության երկիրը լքելու ամսաթիվ</th>
            <th class="table_a">Նախկին բնակության երկիրը լքելու ամսաթիվ</th>
          </tr>
          <tr>
            <td><input type="text" class="form-control form-control-sm" name="b_day"></td>
            <td><input type="text" class="form-control form-control-sm" name="b_day"></td>
          </tr>
        </table>
      <h5 class="sub_title">Կցված ֆայլեր</h5>  
    </div>    
    </div>
  </div> <!--close 12-->
</div>

<div id="family" class="tabcontent">
  <div class="row">
    <table class="table table-stripped table-bordered">
      <tr>
        <th class="role">անձ. #</th>
        <th class="role">դերը</th>
        <th class="fam_members">ա․ա․հ․</th>
        <th class="born">ծննդյան ամսաթիվ</th>
        <th class="role">սեռ</th>
        <th class="role">հաշմանդամ</th>
        <th class="role">հիվանդ</th>
        <th class="role">հղի</th>
        <th class="role">թրաֆ. զոհ</th>
        <th class="role">բռն. զոհ</th>
       </tr>
       <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
    Open modal
  </button></td> 
    </table>
    
  </div>
</div>  

<div id="reason_sequence" class="tabcontent">
  <div class="col-md-12">
    <div class="row">
      <div class="col-md-6">
        <h5 class="sub_title">Քաղաքացիության երկիրը լքելու պատճառը </h5>
        <textarea class="form-control" rows="10"></textarea>
      </div>
      <div class="col-md-6">
        <h5 class="sub_title">Նախկին բնակության երկիրը լքելու պատճառը</h5>
        <textarea class="form-control" rows="10"></textarea>
      </div>

      
      <div class="col-md-6">
        <h5 class="sub_title">Ի՞նչ կարող է պատահել Ձեզ հետ, եթե վերադառնաք Ձեր քաղաքացիության երկիր </h5>
        <textarea class="form-control" rows="10"></textarea>
      </div>
      <div class="col-md-6">
        <h5 class="sub_title">Ի՞նչ կարող է պատահել Ձեզ հետ, եթե վերադառնաք Ձեր նախկին մշտական բնակության երկիր</h5>
        <textarea class="form-control" rows="10"></textarea>
      </div>

    </div>
  </div>

</div>  


<div id="additional_data" class="tabcontent">
 <div class="col-md-12">
  <div class="row">
    <div class="col-md-6">
     <h5 class="sub_title">Հատուկ նշումներ</h5>
              <table class="table">
                  <tr>
                    <th class="b_table">Առանց ուղեկցողի երեխա</th>
                    <th class="b_table">Ընտանիքից անջատված երեխա</th>
                    <th class="b_table">Միայնակ ծնող</th>
                    <th class="b_table">Ցանկանում է իրավաբան</th>
                  </tr>
                  <tr>
                    <td><input type="checkbox" class="form-control form-control-sm" name=""> </td>
                    <td><input type="checkbox" class="form-control form-control-sm" name=""> </td>
                    <td><input type="checkbox" class="form-control form-control-sm" name=""> </td>
                    <td><input type="checkbox" class="form-control form-control-sm" name=""> </td>                    
                  </tr>
              </table>

              <table class="table">
                  <tr>
                    <th class="b_table">Նախընտրելի լեզու</th>
                    <th class="b_table">Հեռախոսահամար</th>
                  </tr>
                  <tr>
                    <td><input type="text" class="form-control form-control-sm" name="prefered_language"></td>
                    <td><input type="text" class="form-control form-control-sm" name="tel"></td>
                  </tr>
              </table>

              <h5 class="sub_title">Լրացուցիչ տեղեկություններ</h5>
              <textarea class="form-control" rows="5"></textarea>
</div> <!--close col-md-6-->
    <div class="col-md-6">
      <h5 class="sub_title">Բնակության հասցեն ՀՀ-ում</h5>
          <table class="table">
            <tr>
                <th class="b_table"> Մարզ</th>
                <td><input type="text" name="case_num" class="form-control form-control-sm"></td>
              </tr>
              <tr>  
                <th class="b_table"> Համայնք</th>
                <td><input type="text" name="case_num" class="form-control form-control-sm"></td>
              </tr>
              <tr>
                <th class="b_table"> Բնակավայր</th>
                <td><input type="text" name="case_num" class="form-control form-control-sm"></td>
              </tr>
              <tr>
                <th class="b_table">Փողոց</th>
                <td><input type="text" name="case_num" class="form-control form-control-sm"></td>
              </tr> 
              <tr>
                <th class="b_table">Շենք</th>
                <td><input type="text" name="case_num" class="form-control form-control-sm"></td>
              </tr>  
              <tr>
                <th class="b_table">Բնակարան</th>
                <td><input type="text" name="case_num" class="form-control form-control-sm"></td>
              </tr>      
          </table>
    </div><!--close col-md-6-->
</div> <!--close row-->
</div> <!--close col-md-12-->
</div>




 <!-- Modal my_btn_3 -->
  <div class="modal fade" id="myModal3" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Նշանակել կատարող</h4>
          <button type="button" class="close" data-dismiss="modal">×</button>
          
        </div>
        <div class="modal-body">
          <div class="col-md-12">
            <input type="text" class="form-control" name="">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

   <!-- Modal my_btn_3 -->
  <div class="modal fade" id="myBtn4" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Նշանակել կատարող</h4>
          <button type="button" class="close" data-dismiss="modal">×</button>
          
        </div>
        <div class="modal-body">
          <div class="col-md-12">
            <input type="text" class="form-control" name="">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

 <!-- The Modal -->
  <div class="modal fade" id="myModal">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Անձնական տվյալների դիտում</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
          <?php
          include 'person_page.php';
          ?>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>
  


  <script>
$(document).ready(function(){
 
  $("#myBtn3").click(function(){
    $("#myModal3").modal({backdrop: "static"});
  });

  $("#myBtn4").click(function(){
    $("#myModal4").modal({backdrop: "static"});
  });



});

</script>




<script>
function openCity(evt, cityName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}

// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();
</script>

    </body>
    </html>