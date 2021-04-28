<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title></title>
		<link rel="stylesheet" href="\materials\css\styles.css">
		<script src="\materials\scripts\default.js"></script>
	</head>

	<body>
		<div class="sidenav">
		  <a href="/">Home</a>
		  <a href="/about">About</a>
		  <a href="/messages">Messages</a>
		  <?=!isset($_SESSION['user']) ? '<a href="/login">Login</a>' : '<a href="/logout">Logout</a>';?>
		</div>
		
<?php include 'application/views/'.$view.'.php';?>
		
	</body>
</html>