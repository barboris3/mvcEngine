<div class="page-content">
	<div class="page-header">
		Dialog
		<hr class="hr-fat">
	</div>
	
	
	<?php if(!empty($data)): ?>
		<div id="chat">
			<?php foreach($data as $value): extract($value); ?>
				<div class="message">
				<b><?=$_SESSION['user']['login'] == $sender ? $sender : $sender;?></b> : <?=$date?><br>
				<?=$message?>
				</div>
			<?php endforeach; ?>
		</div>	
		<form method="post">
			<input type="text" name="message">
			<input type="submit" value="send">
		</form>
	<?php else: ?>
		No such dialog
	<?php endif; ?>
	
</div>
