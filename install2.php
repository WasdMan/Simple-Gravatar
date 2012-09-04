<?php

// Handle running this file by using SSI.php
if (!defined('SMF') && file_exists(dirname(__FILE__) . '/SSI.php'))
{
	$_GET['debug'] = 'Blue Dream!';
	require_once(dirname(__FILE__) . '/SSI.php');
}
// Hmm... no SSI.php and no SMF?
elseif (!defined('SMF'))
	die('<b>Error:</b> Cannot install - please verify you put this in the same place as SMF\'s index.php.');

// global $smcFunc, $db_prefix, $modSettings, $sourcedir, $boarddir, $settings, $db_package_log, $package_cache;
global $modSettings;

// Let's setup some standard settings.
$defaults = array(
	'gravatar_transfer_protocol' => 'http',
	'gravatar_rating' => 'g',
	'gravatar_default_face' => 'monsterid',
	'gravatar_max_size' => '120',
	'gravatar_enable' => '1',
);

$updates = array(
);

foreach ($defaults as $index => $value)
	if (!isset($modSettings[$index]))
		$updates[$index] = $value;

updateSettings($updates);

// Хуки
$hooks = array(
	'integrate_pre_include' => '$sourcedir/Subs-Gravatar.php', // файл с рычагами
	'integrate_load_theme' => 'gravatar_load_theme', // css, js
	'integrate_general_mod_settings' => 'gravatar_general_mod_settings', // админ настройки
	'integrate_load_permissions' => 'gravatar_load_permissions', // Права доступа
	'integrate_admin_areas' => 'gravatar_admin_areas',
	// 'integrate_helpadmin' => 'gravatar_helpadmin', // SMF 2.1
);

if (!empty($context['uninstalling']))
	$call = 'remove_integration_function';
else
	$call = 'add_integration_function';

foreach ($hooks as $hook => $function)
{
	$call($hook, $function);
}