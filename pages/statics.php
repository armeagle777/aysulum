<?php
    if(!isset($_SESSION['username'])  || ($_SESSION['role']!=="admin" && $_SESSION['role']!=="operator" && $_SESSION['role']!=="statist" && $_SESSION['role']!=="viewer" && $_SESSION['role']!=="lawyer" && $_SESSION['role'] !== 'nss'&& $_SESSION['role'] !== 'fin' && $_SESSION['role'] !== 'secretary' && $_SESSION['role'] !== 'dorm' && $_SESSION['role'] !== 'police'&& $_SESSION['role']!=="officer" && $_SESSION['role']!=="devhead" && $_SESSION['role']!=="coispec" && $_SESSION['role']!=="head")){
        header("location: ../index.php");
    }
?>

<body>
    <div class="case_area mt-1">
     <!-- Nav tabs -->
<ul class="nav nav-tabs">
   <li class="nav-item">
    <a class="nav-link active" data-toggle="tab" href="#home"><i class="fas fa-running"></i> Ընդհանուր</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#menu1"><i class="fas fa-pause"></i> Որոշումներ</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#menu2"><i class="fas fa-history"></i> Սպասում են բողոքարկման</a>
  </li>
  
 </ul>
 
 <!-- Tab panes -->
    <div class="tab-content">
      
      <div id="home" class="tab-pane active"><br>
        <h5>1</h5>
      </div>

    </div>
   </div> 
</body>        