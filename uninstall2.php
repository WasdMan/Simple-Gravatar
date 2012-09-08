<?php

// Handle running this file by using SSI.php
if (!defined('SMF') && file_exists(dirname(__FILE__) . '/SSI.php'))
	require_once(dirname(__FILE__) . '/SSI.php');
elseif (!defined('SMF'))
	die('<b>Error:</b> Cannot install - please verify you put this in the same place as SMF\'s index.php.');
elseif ((SMF == 'SSI') && !$user_info['is_admin'])
	die('Admin privileges required.');

// Хуки
$hooks = array(
	'integrate_pre_include' => '$sourcedir/Subs-Gravatar.php', // файл с рычагами
	// 'integrate_load_theme' => 'gravatar_load_theme', // css, js, lang - убрано 
	'integrate_general_mod_settings' => 'gravatar_general_mod_settings', // админ настройки
	'integrate_load_permissions' => 'gravatar_load_permissions', // Права доступа
	'integrate_admin_areas' => 'gravatar_admin_areas',
	// 'integrate_helpadmin' => 'gravatar_helpadmin', // SMF 2.1
	'integrate_profile_areas' => 'gravatar_profile_areas',
);

$call = 'remove_integration_function';

foreach ($hooks as $hook => $function)
{
	$call($hook, $function);
}