<?php
    session_start();
	include("../settings/connect_datebase.php");

    if(isset($_SESSION['code'])==false) exit;
    if(isset($_POST['code'])==false) exit;

    if($_SESSION['code']==$_POST['code']){
        $_SESSION['user'] = $_SESSION['preuser'];

        //генерация токена

        $token = password_hash($_SESSION['user'], PASSWORD_DEFAULT);
        $mysqli->query("UPDATE `users` SET `token` = '{$token}' WHERE `id` = ". $_SESSION['user']);
        $_SESSION["token"] = $token;

        unset($_SESSION['code']);
        unset($_SESSION['preuser']);
    }else{
        unset($_SESSION['code']);
        unset($_SESSION['preuser']);
        header("Location: login.php");
    }
?>