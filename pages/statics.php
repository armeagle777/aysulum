<?php
    if(!isset($_SESSION['username'])  || ($_SESSION['role']!=="admin" && $_SESSION['role']!=="operator" && $_SESSION['role']!=="statist" && $_SESSION['role']!=="viewer" && $_SESSION['role']!=="lawyer" && $_SESSION['role'] !== 'nss'&& $_SESSION['role'] !== 'fin' && $_SESSION['role'] !== 'secretary' && $_SESSION['role'] !== 'dorm' && $_SESSION['role'] !== 'police'&& $_SESSION['role']!=="officer" && $_SESSION['role']!=="devhead" && $_SESSION['role']!=="coispec" && $_SESSION['role']!=="head")){
        header("location: ../index.php");
    }
?>
<h1>statics</h1>