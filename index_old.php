<?php
session_start();
$activepage='';
if(isset($_SESSION['username'])  && $_SESSION['user_status']==1  ){    
	if($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'operator' || $_SESSION['role'] === 'officer' || $_SESSION['role'] === 'devhead' || $_SESSION['role'] === 'coispec' || $_SESSION['role'] === 'nss'|| $_SESSION['role'] === 'fin' || $_SESSION['role'] === 'secretary' || $_SESSION['role'] === 'dorm' || $_SESSION['role'] === 'police' || $_SESSION['role'] === 'head')
	{
		$activepage = 'cases';
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
	if($result->num_rows === 1 && $_SESSION['user_status'] ===1 )
	{
		if($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'operator' || $_SESSION['role'] === 'officer' || $_SESSION['role'] === 'devhead' || $_SESSION['role'] === 'coispec' || $_SESSION['role'] === 'head' || $_SESSION['role'] === 'nss'|| $_SESSION['role'] === 'fin' || $_SESSION['role'] === 'secretary' || $_SESSION['role'] === 'dorm' || $_SESSION['role'] === 'police'){
			$activepage = 'cases';
		}
        header("location:user.php?page=$activepage");

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
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <title>Համակարգ</title>
</head>
<body>
    <div >
        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
            <div data-validate = "Պարտադիր լրացման դաշտ">
                <input class="input100" type="text" name="username" >
                <span class="label-input100">մուտքանուն</span>
            </div>
            <div  data-validate="Պարտադիր լրացման դաշտ">
                <input class="input100" type="password" name="password" >
                <span class="label-input100">գաղտնաբառ</span>
            </div>
            <div >
                <button type="submit" name="login">
                    Մ ՈՒ Տ Ք
                </button>
            </div>
            <div >
                <h5><?= $msg; ?></h5>
            </div>
        </form>
    </div>
</body>
</html>
