<?php

/*
returns list of avaliable actions of controller for every user category
higher level users have access to lower level actions
'user category' => [
		'action1 of controller',
		'action2 of controller'
	]
*/
return [
	'all' => [
		'index',
		'about'
	],
	
	'authorize' => [
		// 'index',
	],
	
	'guest' => [
		// 'index',
	],
	
	'admin' => [
		// 'admin'
	],
];
