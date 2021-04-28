<?php

return [
	'' => [
		'controller'=>'main',
		'action'=>'index'],
		
	'about' => [
		'controller'=>'main',
		'action'=>'about'],
		
	'admin' => [
		'controller'=>'main',
		'action'=>'admin'],
		
	'\d+' => [
		'controller'=>'main',
		'action'=>'index'],	
		
	'\?page=\d+' => [
		'controller'=>'main',
		'action'=>'index'],
		
	'login' => [
		'controller'=>'login',
		'action'=>'index'],
		
	'logout' => [
		'controller'=>'logout',
		'action'=>'index'],
		
	'profile/\w+' => [
		'controller'=>'profile',
		'action'=>'index'],
		
	'messages' => [
		'controller'=>'messages',
		'action'=>'index'],
		
	'messages/new' => [
		'controller'=>'messages',
		'action'=>'new'],
		
	'messages/\d+' => [
		'controller'=>'messages',
		'action'=>'dialog'],
		
	'registration' => [
		'controller'=>'registration',
		'action'=>'index'],
		
	'profile/logout' => [
		'controller'=>'profile',
		'action'=>'logout'],
		
];
