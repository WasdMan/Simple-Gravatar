<?php

if (!defined('SMF'))
	die('Hacking attempt...');

/**
 *
 * Get either a Gravatar URL or complete image tag for a specified email address.
 *
 * @Param string $ary['email'] The email address
 * @Param string $ary['size'] Size in pixels, defaults to 80px [ 1 - 2048 ]
 * @Param string $ary['face'] Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
 * @Param string $ary['rating'] Maximum rating (inclusive) [ g | pg | r | x ]
 * @Param bool $ary['img'] True to return a complete IMG tag False for just the URL
 * @Param array $ary['atts'] Optional, additional key/value attributes to include in the IMG tag
 * @Param string $ary['protocol']
 * @Return String containing either just a URL or a complete image tag
 * @Source http://gravatar.com/site/implement/images/php/
 * @Remix Inter http://tiraspol.me/
 * @Russian Support http://wedge.su/index.php?topic=14.0
 * @Version 24.09.2012
 * @Time 24.09.2012 13:00
 * @License Attribution 3.0 Unported (CC BY 3.0) - http://creativecommons.org/licenses/by/3.0/
 *
 */
function getGravatar($ary)
{
	global $modSettings;

	if (!$modSettings['gravatar_enable'])
		return '';

	if (!is_array($ary) or !isset($ary['email']))
		fatal_error('Error Param!');

	$protocol = isset($ary['protocol']) ? ($ary['protocol'] === 'http' ? 'http' : 'https') : $modSettings['gravatar_transfer_protocol'];
	$protocol = $protocol === 'http' ? 'http://www.gravatar.com/avatar/' : 'https://secure.gravatar.com/avatar/';

	$size = isset($ary['size']) ? (is_int($ary['size']) && $ary['size'] > 1 && $ary['size'] < 2048 ? $ary['size'] : 80) : $modSettings['gravatar_max_size'];

	$face = isset($ary['face']) ? (in_array($ary['face'], array('404', 'mm', 'identicon', 'monsterid', 'wavatar', 'retro')) ? $ary['face'] : 'monsterid') : $modSettings['gravatar_default_face'];

	$rating = isset($ary['rating']) ? (in_array($ary['rating'], array('g', 'pg', 'r', 'x')) ? $ary['rating'] : 'g') : $modSettings['gravatar_rating'];

	$url = $protocol;
	$url .= md5(strtolower(trim($ary['email'])));
	$url .= '?s=' . $size . '&amp;d=' . $face . '&amp;r=' . $rating;

	if (empty($ary['show_img']))
		return $url;
	else
	{
		$image = '<img src="' . $url . '"';
		if (!empty($ary['atts']) and is_array($ary['atts']))
		{
			$atts = array_unique($atts);
			foreach ($atts as $key => $val)
				$image .= ' ' . htmlspecialchars($key, ENT_QUOTES) . '="' . htmlspecialchars($val, ENT_QUOTES) . '"';
		}
		$image .= ' alt="" />';
		return $image;
	}
}

function gravatar_profile_areas(&$profile_areas)
{
	loadLanguage('ProfileGravatar');
}

function gravatar_admin_areas(&$admin_areas)
{
	global $txt;

	loadLanguage('AdminGravatar');
	loadLanguage('HelpGravatar');

	$admin_areas['config']['areas']['modsettings']['subsections']['gravatar'] = array($txt['gravatar_title']);
}

function gravatar_modify_modifications(&$subActions)
{
	$subActions['gravatar'] = 'ModifyGravatarSettings';
}

function ModifyGravatarSettings($return_config = FALSE)
{
	global $txt, $scripturl, $context;

	$config_vars = array(
		array('title', 'gravatar_title'),
		array('permissions', 'profile_gravatar_avatar', 0, $txt['gravatar_groups_description']),
		array('check', 'gravatar_enable'),
		array('int', 'gravatar_max_size', 6, 'subtext' => $txt['gravatar_max_size_subtext'], 'postinput' => $txt['gravatar_unit']),
		array('select', 'gravatar_default_face', $txt['gravatar_default_faces']),
		array('select', 'gravatar_rating', $txt['gravatar_ratings']),
		array('select', 'gravatar_transfer_protocol', $txt['gravatar_transfer_protocols'],
	));

	if ($return_config)
		return $config_vars;

	// Saving?
	if (isset($_GET['save']))
	{
		checkSession();

		foreach (array('gravatar_transfer_protocol', 'gravatar_max_size', 'gravatar_default_face', 'gravatar_rating') as $val)
		{
			if (!isset($_POST[$val]))
				fatal_lang_error('Field ' . $val . ' Not Found!');
		}

		$_POST['gravatar_transfer_protocol'] = $_POST['gravatar_transfer_protocol'] === 'http' ? 'http' : 'https';

		$_POST['gravatar_max_size'] = (int) $_POST['gravatar_max_size'];
		$_POST['gravatar_max_size'] = empty($_POST['gravatar_max_size']) ? 80 : max(1, min($_POST['gravatar_max_size'], 2048));

		$_POST['gravatar_default_face'] = !empty($_POST['gravatar_default_face']) && in_array($_POST['gravatar_default_face'], array('404', 'mm', 'identicon', 'monsterid', 'wavatar', 'retro')) ? $_POST['gravatar_default_face'] : 'monsterid';

		$_POST['gravatar_rating'] = !empty($_POST['gravatar_rating']) && in_array($_POST['gravatar_rating'], array('g', 'pg', 'r', 'x')) ? $_POST['gravatar_rating'] : 'g';

		saveDBSettings($config_vars);

		writeLog();
		redirectexit('action=admin;area=modsettings;sa=gravatar');
	}

	$context['post_url'] = $scripturl . '?action=admin;area=modsettings;save;sa=gravatar';
	// $context['settings_title'] = $txt['gravatar_title'];

	prepareDBSettingContext($config_vars);
}

function gravatar_load_permissions(&$permissionGroups, &$permissionList, &$leftPermissionGroups, &$hiddenPermissions, &$relabelPermissions)
{
	$permissionList['membergroup'] += array('profile_gravatar_avatar' => array(FALSE, 'profile', 'use_avatar'));
}

function gravatar_menu_buttons(&$buttons)
{
	global $context;
	
	if (isset($context['current_action']) && $context['current_action'] === 'credits')
		$context['copyrights']['mods'][] = '<a href="http://tiraspol.me/" title="Simple Gravatar" target="_blank" class="new_win">Simple Gravatar &copy; 2012</a>';
}

function gravatar_load_theme()
{
	if (isset($_GET['help']))
		loadLanguage('HelpGravatar');
}