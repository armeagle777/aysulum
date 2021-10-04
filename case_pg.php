<!DOCTYPE html>
  <html>
    <head>
    	<title>ՄԾ հաշվառման համակարգ</title>
    	<meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <!--CSS  -->
      <link rel="stylesheet" type="text/css" href="line.css">
      <!--  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
      <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous"> -->
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
      <!-- DataTables CSS library -->
      <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css"/>
      <link rel="preconnect" href="https://fonts.gstatic.com">
      <link href="https://fonts.googleapis.com/css2?family=Indie+Flower&family=Karantina:wght@300;700&display=swap" rel="stylesheet">
      <!--JS  -->
      <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> -->
      <!--  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script> -->
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
    <style>
     

      
    </style>
    <body>
      
        <div class="btn_line">
              <div class="right"> 
               
              </div>
              <div class="left">
                  <button type="button" class="btn btn-info btn-md l_btn" id="myBtn3">Նշանակել կատարող</button>
                  <button type="button" class="btn btn-success btn-md l_btn">Ողարկել լրամշակման</button>
                  <button type="button" class="btn btn-success btn-md l_btn">Ողարկել հաստատման</button>
                  <button type="button" class="btn btn-success btn-md l_btn">Հաստատել</button>
              </div>    
        </div>

       
      
    

      <div class="tab">
        <button class="tablinks" onclick="openCity(event, 'general_data')" id="defaultOpen"> Գործառույթներ </button>
        <button class="tablinks" onclick="openCity(event, 'personal_data')"> Հատուկ նշումներ</button>
      </div>
        
     
     
     <div id="general_data" class="tabcontent">
        <div class="row">
        <div class="col-md-12">
          <div class="col-md-6" >
            <h5 style="border-bottom: solid; margin-bottom: 3px;">Գործառույթի մանրամասներ </h5> 
            <br>
              <table class="table">
              <tr>
                <th class="a_table">Գործի կարգավիճակ</th>
                <td><input type="text" name="case_num" class="form-control form-control-sm"></td>
              </tr>
               <tr>
                <th class="a_table">Ընթացքի կարգավիճակ</th>
                <td><input type="text" name="case_num" class="form-control form-control-sm"></td>
              </tr>
              <tr>    
                <th class="a_table">Նախնական վերջնաժամկետ</th>
                <td><input type="text" name="case_num" class="form-control form-control-sm"></td>
              </tr>
              <tr>  
                <th class="a_table">Երկարաձգված վերջնաժամկետ</th>
                <td><input type="text" name="case_num" class="form-control form-control-sm"></td>
              </tr>
            </table>
          </div> <!-- close 1st md 6 -->
          
          <div class="col-md-6">
            <h5 style="border-bottom: solid; margin-bottom: 3px;">Գործի մանրամասներ </h5> 
            <br>
              <table class="table">
              
              <tr>
                <th class="a_table">Դիմումի ամսաթիվ</th>
                <td><input type="text" name="case_num" class="form-control form-control-sm"></td>
              </tr>
              <tr>  
                <th class="a_table">Մուտքագրման ամսաթիվ</th>
                <td><input type="text" name="case_num" class="form-control form-control-sm"></td>
              </tr>
              <tr>
                <th class="a_table">Մուտքագրող աշխատակից</th>
                <td><input type="text" name="case_num" class="form-control form-control-sm"></td>
              </tr>
              <tr>
                <th class="a_table">Գործ վարող</th>
                <td><input type="text" name="case_num" class="form-control form-control-sm"></td>
              </tr>
              <tr>  
                <th class="a_table">Գործը տնօրինող</th>
                <td><input type="text" name="case_num" class="form-control form-control-sm"></td>
              </tr>
              </table>
          </div><!-- close 2st md 6 -->
        </div> <!--close row div-->   
      </div> <!-- close 1st tab-->

      <div id="personal_data" class="tabcontent">
          <h5>Հատուկ նշումներ</h5>
               <br> 
                <table class="table table-bordered">
                  
                  <tr>
                    <th class="b_table">Առանց ուղեկցողի երեխա</th>
                    <td><input type="checkbox" class="form-control form-control-sm" name=""> </td>
                  </tr>
                  <tr>  
                    <th class="b_table">Ընտանիքից անջատված երեխա</th>
                    <td><input type="checkbox" class="form-control form-control-sm" name=""> </td>
                  </tr>
                  <tr>  
                    <th class="b_table">Միայնակ ծնող</th>
                    <td><input type="checkbox" class="form-control form-control-sm" name=""> </td>
                  </tr>
                  <tr>  
                    <th class="b_table">Ցանկանում է իրավաբան</th>
                    <td><input type="checkbox" class="form-control form-control-sm" name=""> </td>                    
                  </tr>
                  <tr>  
                    <th class="b_table">Նախընտրելի լեզուն</th>
                    <td><input type="input" class="form-control form-control-sm" name=""> </td>                    
                  </tr>
                  <tr>  
                    <th class="b_table">Հեռախոսահամար</th>
                    <td><input type="input" class="form-control form-control-sm" name=""> </td>                    
                  </tr>

                </table>
     
      </div>


      <!-- Modal -->
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


  <script>
$(document).ready(function(){
 
  $("#myBtn3").click(function(){
    $("#myModal3").modal({backdrop: "static"});
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