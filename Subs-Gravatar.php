<?php

if (!defined('SMF'))
	die('Hacking attempt...');

/**
 * Get either a Gravatar URL or complete image tag for a specified email address.
 *
 * @param string $ary['email'] The email address
 * @param string $size Size in pixels, defaults to 80px [ 1 - 2048 ]
 * @param string $face Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
 * @param string $rating Maximum rating (inclusive) [ g | pg | r | x ]
 * @param bool $ary['img'] True to return a complete IMG tag False for just the URL
 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
 * @param string $protocol
 * @return String containing either just a URL or a complete image tag
 * @source http://gravatar.com/site/implement/images/php/
 * @remix Inter http://tiraspol.me/
 */
function getGravatar($ary)
{
	global $modSettings, $context;

	if (!$modSettings['gravatar_enable']) /*or !allowedTo('profile_gravatar_avatar')*/
		return;

	if (!is_array($ary) or !isset($ary['email']))
		fatal_error('Error Param!');

	if (empty($context['gravatar'][$ary['email']]))
	{
		$size = isset($ary['size']) ? (int) $ary['size'] : isset($modSettings['gravatar_max_size']) ? (int) $modSettings['gravatar_max_size'] : 80;
		$face = isset($ary['face']) ? $ary['face'] : isset($modSettings['gravatar_default_face']) && in_array($modSettings['gravatar_default_face'], array('404', 'mm', 'identicon', 'monsterid', 'wavatar', 'retro')) ? $modSettings['gravatar_default_face'] : 'monsterid';
		$rating = isset($ary['rating']) ? $ary['rating'] : isset($modSettings['gravatar_rating']) ? $modSettings['gravatar_rating'] : 'g';
		$atts = isset($ary['atts']) ? $ary['atts'] : array();
		$protocol = isset($ary['protocol']) ? $ary['protocol'] === 'http' ? 'http://www.gravatar.com/avatar/' : 'https://secure.gravatar.com/avatar/' : $modSettings['gravatar_transfer_protocol'] === 'http' ? 'http://www.gravatar.com/avatar/' : 'https://secure.gravatar.com/avatar/';

		$context['gravatar'][$ary['email']]['url'] = $protocol;
		$context['gravatar'][$ary['email']]['url'] .= md5(strtolower(trim($ary['email'])));
		$context['gravatar'][$ary['email']]['url'] .= '?s=' . $size . '&d=' . $face . '&r=' . $rating;
	}

	$ary['show_img'] = isset($ary['show_img']) ? (bool) $ary['show_img'] : TRUE;
	if ($ary['show_img'] !== TRUE)
		return $context['gravatar'][$ary['email']]['url'];
	else
	{
		$context['gravatar'][$ary['email']]['image'] = '<img src="' . $context['gravatar'][$ary['email']]['url'] . '"';
		// $context['gravatar'][$ary['email']]['image'] .= ' ' . implode(' ', array_values($atts));
		if (!empty($atts))
		{
			foreach ($atts as $key => $val)
				$context['gravatar'][$ary['email']]['image'] .= ' ' . $key . '="' . $val . '"';
		}
		$context['gravatar'][$ary['email']]['image'] .= ' />';
		return $context['gravatar'][$ary['email']]['image'];
	}
}

function gravatar_profile_areas(&$profile_areas)
{
	loadLanguage('Gravatar');
}

function gravatar_admin_areas(&$admin_areas)
{
	loadLanguage('AdminGravatar');
}

function gravatar_general_mod_settings(&$config_vars)
{
	global $txt;
	$config_vars[] = array('title', 'gravatar_title');
	$config_vars[] = array('permissions', 'profile_gravatar_avatar', 0, $txt['gravatar_groups_description']);
	$config_vars[] = array('check', 'gravatar_enable');
	$config_vars[] = array('text', 'gravatar_max_size', 6, 'subtext' => $txt['gravatar_max_size_subtext']);
	$config_vars[] = array('select', 'gravatar_default_face', $txt['gravatar_default_faces']);
	$config_vars[] = array('select', 'gravatar_rating', $txt['gravatar_ratings']);
	$config_vars[] = array('select', 'gravatar_transfer_protocol', $txt['gravatar_transfer_protocols']);
}

function gravatar_load_permissions(&$permissionGroups, &$permissionList, &$leftPermissionGroups, &$hiddenPermissions, &$relabelPermissions)
{
	$permissionList['membergroup'] += array('profile_gravatar_avatar' => array(false, 'profile', 'use_avatar'));
}

// SMF 2.1
/*function gravatar_helpadmin()
{
	loadLanguage('HelpGravatar');
}*/