<?php
    session_start();

    if(isset($_SESSION['code'])==false) exit;
    if(isset($_POST['code'])==false) exit;

    if($_SESSION['code']==$_POST['code']){
        $_SESSION['user'] = $_SESSION['preuser'];

        unset($_SESSION['code']);
        unset($_SESSION['preuser']);
    }else{
        unset($_SESSION['code']);
        unset($_SESSION['preuser']);
        header("Location: login.php");
    }
?>