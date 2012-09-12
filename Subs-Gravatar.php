<?php

if (!defined('SMF'))
	die('Hacking attempt...');

/**
 * Get either a Gravatar URL or complete image tag for a specified email address.
 *
 * @param string $ary['email'] The email address
 * @param string $ary['size'] Size in pixels, defaults to 80px [ 1 - 2048 ]
 * @param string $ary['face'] Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
 * @param string $ary['rating'] Maximum rating (inclusive) [ g | pg | r | x ]
 * @param bool $ary['img'] True to return a complete IMG tag False for just the URL
 * @param array $ary['atts'] Optional, additional key/value attributes to include in the IMG tag
 * @param string $ary['protocol']
 * @return String containing either just a URL or a complete image tag
 * @source http://gravatar.com/site/implement/images/php/
 * @remix Inter http://tiraspol.me/
 */
function getGravatar($ary)
{
	global $modSettings;

	if (!$modSettings['gravatar_enable'])
		return '';

	if (!is_array($ary) or !isset($ary['email']))
		fatal_error('Error Param!');

	$size = isset($ary['size']) ? (int) $ary['size'] : (isset($modSettings['gravatar_max_size']) ? (int) $modSettings['gravatar_max_size'] : 80);
	$size = max(1, min($size, 2048));
	$face = isset($ary['face']) ? $ary['face'] : (isset($modSettings['gravatar_default_face']) && in_array($modSettings['gravatar_default_face'], array('404', 'mm', 'identicon', 'monsterid', 'wavatar', 'retro')) ? $modSettings['gravatar_default_face'] : 'monsterid');
	$rating = isset($ary['rating']) ? $ary['rating'] : (isset($modSettings['gravatar_rating']) && in_array($modSettings['gravatar_rating'], array('g', 'pg', 'r', 'x')) ? $modSettings['gravatar_rating'] : 'g');
	$atts = isset($ary['atts']) ? $ary['atts'] : array();
	$protocol = isset($ary['protocol']) ? ($ary['protocol'] === 'http' ? 'http://www.gravatar.com/avatar/' : 'https://secure.gravatar.com/avatar/') : ($modSettings['gravatar_transfer_protocol'] === 'http' ? 'http://www.gravatar.com/avatar/' : 'https://secure.gravatar.com/avatar/');
	$url = $protocol;
	$url .= md5(strtolower(trim($ary['email'])));
	$url .= '?s=' . $size . '&d=' . $face . '&r=' . $rating;

	$ary['show_img'] = isset($ary['show_img']) ? (bool) $ary['show_img'] : TRUE;
	if ($ary['show_img'] !== TRUE)
		return $url;
	else
	{
		$image = '<img src="' . $url . '"';
		if (!empty($atts))
		{
			foreach ($atts as $key => $val)
				$image .= ' ' . $key . '="' . $val . '"';
		}
		$image .= ' />';
		return $image;
	}
}

function gravatar_profile_areas(&$profile_areas)
{
	loadLanguage('ProfileGravatar');
}

function gravatar_admin_areas(&$admin_areas)
{
	loadLanguage('AdminGravatar');
	loadLanguage('HelpGravatar');
}

function gravatar_general_mod_settings(&$config_vars)
{
	global $txt;

	$config_vars += array(
		array('title', 'gravatar_title'),
		array('permissions', 'profile_gravatar_avatar', 0, $txt['gravatar_groups_description']),
		array('check', 'gravatar_enable'),
		array('text', 'gravatar_max_size', 6, 'subtext' => $txt['gravatar_max_size_subtext']),
		array('select', 'gravatar_default_face', $txt['gravatar_default_faces']),
		array('select', 'gravatar_rating', $txt['gravatar_ratings']),
		array('select', 'gravatar_transfer_protocol', $txt['gravatar_transfer_protocols'],
	));
}

function gravatar_load_permissions(&$permissionGroups, &$permissionList, &$leftPermissionGroups, &$hiddenPermissions, &$relabelPermissions)
{
	$permissionList['membergroup'] += array('profile_gravatar_avatar' => array(FALSE, 'profile', 'use_avatar'));
}