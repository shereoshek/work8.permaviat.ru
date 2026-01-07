<?php
	session_start();
	include("../settings/connect_datebase.php");
	
	$login = $_POST['login'];
	$password = $_POST['password'];


	//проверка пароля на соответствие

	$CheckPassword = 
	preg_match('/(?=.*[0-9])(?=.*[!@#$^&?*\-_=])(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z!@#$%^&?*\-_=]{8,}/', 
	$password);

	if($CheckPassword == false)
		exit;
	
	// ищем пользователя
	$query_user = $mysqli->query("SELECT * FROM `users` WHERE `login`='".$login."'");
	$id = -1;
	
	if($user_read = $query_user->fetch_row()) {
		echo $id;
	} else {
		$mysqli->query("INSERT INTO `users`(`login`, `password`, `roll`) VALUES ('".$login."', '".$password."', 0)");
		
		$query_user = $mysqli->query("SELECT * FROM `users` WHERE `login`='".$login."' AND `password`= '".$password."';");
		$user_new = $query_user->fetch_row();
		$id = $user_new[0];
			
		if($id != -1) $_SESSION['user'] = $id; // запоминаем пользователя

		$Ip = $_SERVER["REMOTE_ADDR"];
		$DateStart = date("Y-m-d H:i:s");

		//событие регистрации
		$Sql = "INSERT INTO `logs`(`Ip`, `IdUser`, `Date`, `TimeOnline`, `Event`)
		VALUES ('{$Ip}',{$id},'{$DateStart}','00:00:00','Пользователь {$login} зарегистрировался')";
		$mysqli->query($Sql);

		$Sql = "INSERT INTO `session`(`IdUser`, `Ip`, `DateStart`, `DateNow`) 
			VALUES ({$id},'{$Ip}','{$DateStart}','{$DateStart}')";
		$mysqli->query($Sql);

		$Sql = "SELECT `Id` FROM `session` WHERE `DateStart`='{$DateStart}';";
		$Query = $mysqli->query($Sql);
		$Read = $Query->fetch_assoc();
		$_SESSION["IdSession"] = $Read["Id"];
		
		// событие авторизации

		$Sql = "INSERT INTO `logs`(`Ip`, `IdUser`, `Date`, `TimeOnline`, `Event`)
		VALUES ('{$Ip}',{$id},'{$DateStart}','00:00:00','Пользователь {$login} авторизовался')";
		$mysqli->query($Sql);
		
		echo $id;
	}
?>