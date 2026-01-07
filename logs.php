<?php
	session_start();
	include("./settings/connect_datebase.php");
	
	if (isset($_SESSION['user'])) {
		if($_SESSION['user'] != -1) {
			$user_query = $mysqli->query("SELECT * FROM `users` WHERE `id` = ".$_SESSION['user']); // проверяем
			while($user_read = $user_query->fetch_row()) {
				if($user_read[3] == 0) header("Location: index.php");
			}
		} else header("Location: login.php");
	} else {
		header("Location: login.php");
		echo "Пользователя не существует";
	}
	include("./settings/session.php");
?>
<!DOCTYPE HTML>
<html>
	<head> 
		<script src="https://code.jquery.com/jquery-1.8.3.js"></script>
		<meta charset="utf-8">
		<title> Admin панель </title>
		
		<link rel="stylesheet" href="style.css">
		<style>
			table{
				width: 100%;
			}

			td{
				text-align:center;
				padding: 7px;
			}
		</style>
	</head>
	<body>
		<div class="top-menu">

			<a href=#><img src = "img/logo1.png"/></a>
			<div class="name">
				<a href="index.php">
					<div class="subname">БЗОПАСНОСТЬ  ВЕБ-ПРИЛОЖЕНИЙ</div>
					Пермский авиационный техникум им. А. Д. Швецова
				</a>
			</div>
		</div>
		<div class="space"> </div>
		<div class="main">
			<div class="content">
				<input type="button" class="button" value="Выйти" onclick="logout()"/>
				
				<div class="name">Журнал событий</div>
			
				<table border="1">
					<thead>
						<tr>
							<td style="width: 165px"><button onclick="date_sort()">Дата и время</button></td>
							<td style="width: 165px"><button onclick="ip_sort()">Ip</button></td>
							<td style="width: 165px"><button onclick="time_sort()">Время в сети</button></td>
							<td style="width: 165px">Статус</td>
							<td style="width: 165px">Событие</td>
						</tr>
					</thead>
					<tbody>

					</tbody>
				</table>
			
				<div class="footer">
					© КГАПОУ "Авиатехникум", 2020
					<a href=#>Конфиденциальность</a>
					<a href=#>Условия</a>
				</div>
			</div>
		</div>
		
		<script>
			GetEvents();
			function GetEvents(){
				$.ajax({
					url         : 'ajax/events/get.php',
					type        : 'POST', // важно!
					data        : null,
					cache       : false,
					dataType    : 'html',
					processData : false,
					contentType : false, 
					success: GetEventsAjax,
					// функция ошибки
					error: function( ){
						console.log('Системная ошибка!');
						
					}
				});
			}

			function GetEventsAjax(_data){
				console.log(_data);

				let $Table = $("tbody");
				let events = JSON.parse(_data);

				events.forEach((event)=>{
					$Table.append(`
					<tr>
						<td>${event["Date"]}</td>
						<td>${event["Ip"]}</td>
						<td>${event["TimeOnline"]}</td>
						<td>${event["Status"]}</td>
						<td style = "text-align: left;">${event["Event"]}</td>
					</tr>`)
				})
			}

			function logout() {
				$.ajax({
					url         : 'ajax/logout.php',
					type        : 'POST', // важно!
					data        : null,
					cache       : false,
					dataType    : 'html',
					processData : false,
					contentType : false, 
					success: function (_data) {
						location.reload();
					},
					error: function( ){
						console.log('Системная ошибка!');
					}
				});
			}

			function date_sort(){

				$.ajax({
					url         : 'ajax/events/get.php',
					type        : 'POST', // важно!
					data        : null,
					cache       : false,
					dataType    : 'html',
					processData : false,
					contentType : false, 
					success: function(_data){
						let Table = $("tbody");
						Table.empty();
						let events = JSON.parse(_data);

						events = events.sort((first, second)=>{
							return new Date(first["Date"])  - new Date(second["Date"]);
						})
						
						events.forEach((event)=>{
							Table.append(`
							<tr>
								<td>${event["Date"]}</td>
								<td>${event["Ip"]}</td>
								<td>${event["TimeOnline"]}</td>
								<td>${event["Status"]}</td>
								<td style = "text-align: left;">${event["Event"]}</td>
							</tr>`)
						})
					},
					// функция ошибки
					error: function( ){
						console.log('Системная ошибка!');
						
					}
				});
			}

			function ip_sort(){
				$.ajax({
					url         : 'ajax/events/get.php',
					type        : 'POST', // важно!
					data        : null,
					cache       : false,
					dataType    : 'html',
					processData : false,
					contentType : false, 
					success: function(_data){
						let Table = $("tbody");
						Table.empty();
						let events = JSON.parse(_data);

						events = events.sort((first, second)=>{
							let ipFirst= first["Ip"].split('.').map(Number);
							let ipSecond= second["Ip"].split('.').map(Number);
							for(let i = 0; i<4;i++){
								if(ipFirst[i]!==ipSecond[i]){
									return ipFirst[i]-ipSecond[i];
								}
							}
							return 0;
						})
						
						events.forEach((event)=>{
							Table.append(`
							<tr>
								<td>${event["Date"]}</td>
								<td>${event["Ip"]}</td>
								<td>${event["TimeOnline"]}</td>
								<td>${event["Status"]}</td>
								<td style = "text-align: left;">${event["Event"]}</td>
							</tr>`)
						})
					},
					// функция ошибки
					error: function( ){
						console.log('Системная ошибка!');
						
					}
				});
			}

			function time_sort(){
				$.ajax({
					url         : 'ajax/events/get.php',
					type        : 'POST', // важно!
					data        : null,
					cache       : false,
					dataType    : 'html',
					processData : false,
					contentType : false, 
					success: function(_data){
						let Table = $("tbody");
						Table.empty();
						let events = JSON.parse(_data);

						events = events.sort((first, second)=>{
							let ipFirst= first["TimeOnline"].split(':').map(Number);
							let ipSecond= second["TimeOnline"].split(':').map(Number);
							for(let i = 0; i<3;i++){
								if(ipFirst[i]!==ipSecond[i]){
									return ipFirst[i]-ipSecond[i];
								}
							}
							return 0;
						})
						
						events.forEach((event)=>{
							Table.append(`
							<tr>
								<td>${event["Date"]}</td>
								<td>${event["Ip"]}</td>
								<td>${event["TimeOnline"]}</td>
								<td>${event["Status"]}</td>
								<td style = "text-align: left;">${event["Event"]}</td>
							</tr>`)
						})
					},
					// функция ошибки
					error: function( ){
						console.log('Системная ошибка!');
						
					}
				});
			}
		</script>
	</body>
</html>