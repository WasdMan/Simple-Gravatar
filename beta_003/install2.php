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