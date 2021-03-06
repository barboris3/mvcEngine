<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title></title>
		<link rel="stylesheet" href="\materials\css\styles.css">
		
		<?php if($files && array_key_exists('styles', $files)) foreach($files['styles'] as $style): ?>
		<link rel="stylesheet" href="\materials\css\<?=$style;?>.css">
		<?php endforeach; ?>
		
		<script src="\materials\scripts\default.js"></script>
		
		<?php if($files && array_key_exists('scripts', $files)) foreach($files['scripts'] as $script): ?>
		<script src="\materials\scripts\<?=$script;?>.js"></script>
		<?php endforeach; ?>
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