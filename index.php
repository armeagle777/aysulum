<?php
session_start();
$activepage='';
if(isset($_SESSION['username'])  && $_SESSION['user_status']==1  ){    
	if($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'operator' || $_SESSION['role'] === 'officer' || $_SESSION['role'] === 'devhead' || $_SESSION['role'] === 'coispec' || $_SESSION['role'] === 'nss'|| $_SESSION['role'] === 'fin' || $_SESSION['role'] === 'secretary' || $_SESSION['role'] === 'dorm' || $_SESSION['role'] === 'police' || $_SESSION['role'] === 'head' && $_SESSION['role'] ==="general")
	{
		$activepage = 'cases';
	}elseif($_SESSION['role'] === 'archiver'){
		$activepage = 'archive';
	}
    header("location:user.php?page=$activepage");
}
require("./config/connect.php");
$msg="";
if(isset($_POST['login'])){
    $username=$_POST['username'];
    $password=$_POST['password'];
    $password = sha1($password);
    $sql = "SELECT * FROM users WHERE username=? AND password=?";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("ss",$username,$password);
    $stmt->execute();
    $result = $stmt->get_result();    
    $row=$result->fetch_assoc();
    session_regenerate_id();
    if(mysqli_num_rows($result) > 0)
	{
        $_SESSION['username']=$row['username'];
        $_SESSION['user_status']=$row['user_status'];
        $_SESSION['role']=$row['user_type'];
        $_SESSION['user_id']=$row['id'];
        $_SESSION['user_fName']=$row['f_name'];
        $_SESSION['user_lName']=$row['l_name'];
    }    
    session_write_close();
	if($result->num_rows === 1 && $_SESSION['user_status'] === 1 )
	{
		if($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'operator' || $_SESSION['role'] === 'officer' || $_SESSION['role'] === 'devhead' || $_SESSION['role'] === 'coispec' || $_SESSION['role'] === 'head' || $_SESSION['role'] === 'nss'|| $_SESSION['role'] === 'fin' || $_SESSION['role'] === 'secretary' || $_SESSION['role'] === 'dorm' || $_SESSION['role'] === 'police' || $_SESSION['role'] === "general"){
			header("location:user.php?page=cases&homepage=notifications");
		}elseif($_SESSION['role'] === 'archiver')
		{
			header("location:user.php?page=archive");
		}
        

	}elseif($result->num_rows === 1 && $_SESSION['user_status'] === 0){
		$msg="Ձեր լիազորությունները կասեցված են";
	}        
    else {
		$msg="Մուտքանունը կամ Գաղտնաբառը սխալ է մուտքագրված";   
    }
	$conn->close();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>ՄԾ հաշվառման համակարգ</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100" style="background-image: url('images/img-01.jpg');">
			<div class="wrap-login100 p-t-190 p-b-30">
				<form class="login100-form validate-form" action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
					<div class="login100-form-avatar">
						<img src="images/logo.png" alt="AVATAR">
					</div>

					<span class="login100-form-title p-t-20 p-b-45">
						ԱՊԱՍՏԱՆԻ ԳՈՐԾԵՐԻ ԿԱՌԱՎԱՐՄԱՆ ՀԱՄԱԿԱՐԳ
					</span>
					<div class="wrap-input100 validate-input m-b-10" data-validate = "Պարտադիր լրացման դաշտ">
						<input class="input100" type="text"  name="username" placeholder="Մուտքանուն">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-user"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input m-b-10" data-validate = "Պարտադիր լրացման դաշտ">
						<input class="input100" type="password" name="password" placeholder="Գաղտնաբառ">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock"></i>
						</span>
					</div>

					<div class="container-login100-form-btn p-t-10">
						<button class="login100-form-btn" type="submit" name="login">
							Մուտք
						</button>
					</div>

					<div class="text-center w-full">
						<h5><?= $msg; ?></h5>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	

	
<!--===============================================================================================-->	
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>

</body>
</html>