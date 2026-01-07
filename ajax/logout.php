<?php
	session_start();
	require_once("../settings/connect_datebase.php");

	$Id = $_SESSION["user"];
	$IdSession = $_SESSION["IdSession"];

	$Sql = "SELECT `session`.*, `users`.`login`". 
		"FROM `session`" .
		"JOIN `users` `users` ON `users`.`id` = `session`.`IdUser`".
		"WHERE `session`.`Id` = {$IdSession}";
	$Query = $mysqli->query($Sql);
	$Read = $Query->fetch_array();

	$TimeStart=strtotime($Read["DateStart"]);//unix time
	$TimeNow = time();
	$Ip=$Read["Ip"];
	$TimeD = gmdate("H:i:s", ($TimeNow - $TimeStart));
	$DateStart = date("Y-m-d H:i:s");
	$login = $Read["login"];

	$Sql = "INSERT INTO `logs`(`Ip`, `IdUser`, `Date`, `TimeOnline`, `Event`)
		VALUES ('{$Ip}',{$Id},'{$DateStart}','{$TimeD}','Пользователь {$login} вышел из системы')";
	$mysqli->query($Sql);

	session_destroy();
?>