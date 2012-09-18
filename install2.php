<?php

// Handle running this file by using SSI.php
if (!defined('SMF') && file_exists(dirname(__FILE__) . '/SSI.php'))
	require_once(dirname(__FILE__) . '/SSI.php');
elseif (!defined('SMF'))
	die('<b>Error:</b> Cannot install - please verify you put this in the same place as SMF\'s index.php.');
elseif ((SMF == 'SSI') && !$user_info['is_admin'])
	die('Admin privileges required.');

global $modSettings;

$hooks = array(
	'integrate_pre_include' => '$sourcedir/Subs-Gravatar.php',
	'integrate_load_theme' => 'gravatar_load_theme',
	'integrate_menu_buttons' => 'gravatar_menu_buttons',
	'integrate_general_mod_settings' => 'gravatar_general_mod_settings',
	'integrate_modify_modifications' => 'gravatar_modify_modifications',
	'integrate_load_permissions' => 'gravatar_load_permissions',
	'integrate_admin_areas' => 'gravatar_admin_areas',
	'integrate_profile_areas' => 'gravatar_profile_areas',
);

// Let's setup some standard settings.
$defaults = array(
	'gravatar_transfer_protocol' => 'http',
	'gravatar_rating' => 'g',
	'gravatar_default_face' => 'monsterid',
	'gravatar_max_size' => '120',
	'gravatar_enable' => '1',
);

$updates = array();

foreach ($defaults as $index => $value)
{
	if (!isset($modSettings[$index]))
		$updates[$index] = $value;
}

updateSettings($updates);

$call = 'add_integration_function';

foreach ($hooks as $hook => $function)
{
	$call($hook, $function);
}