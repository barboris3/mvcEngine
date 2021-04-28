<div class="page-content">
	<div class="page-header">
		<b>Messages</b>
		<div>
			Start <a href="/messages/new">new dialog</a>
		</div>
		<hr class="hr-fat">
	</div>
	<?php if($data) foreach($data as $value): ?>
		<div><a href="/messages/<?=$value['dialog'];?>"><?=$value['user'];?></a> : <?=$value['date'];?> [+<?=$value['unreadCount'];?>] <button class="removeDialog" id="<?=$value['dialog'];?>">delete</button></div>
	<?php endforeach; ?>
</div>