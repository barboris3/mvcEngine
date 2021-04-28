<div class="page-register">
	Profile
	<hr>
	<?php if(!empty($data)): extract($data); ?>
		<div>Id: <?=$id;?></div>
		<div>Login: <?=$login;?></div>
		<div>Email: <?=$email;?></div>
		<div>Registration: <?=$registration;?></div>
		<div>Visit: <?=$visit;?></div>
		<div>Block: <?=$block;?></div>
	<?php else: ?>
		<div>No such user</div>
	<?php endif; ?>
</div>