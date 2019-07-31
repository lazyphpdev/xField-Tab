<?php
/**
* AJAX обработчик
*
* @link https://lazydev.pro/
* @author LazyDev <email@lazydev.pro>
**/

@error_reporting(E_ALL ^ E_WARNING ^ E_DEPRECATED ^ E_NOTICE);
@ini_set('error_reporting', E_ALL ^ E_WARNING ^ E_DEPRECATED ^ E_NOTICE);
@ini_set('display_errors', true);
@ini_set('html_errors', false);

define('DATALIFEENGINE', true);
define('ROOT_DIR', substr(dirname(__FILE__), 0, -26));
define('ENGINE_DIR', ROOT_DIR . '/engine');

use LazyDev\xFieldTab\Data;

include_once ENGINE_DIR . '/classes/plugins.class.php';
include ENGINE_DIR . '/lazydev/xfield_tab/class/Data.php';

header('Content-type: text/html; charset=' . $config['charset']);
date_default_timezone_set($config['date_adjust']);
setlocale(LC_NUMERIC, 'C');

require_once DLEPlugins::Check(ROOT_DIR . '/language/' . $config['langs'] . '/website.lng');
require_once DLEPlugins::Check(ENGINE_DIR . '/modules/functions.php');

dle_session();

$user_group = get_vars('usergroup');
if (!$user_group) {
	$user_group = [];
	$db->query('SELECT * FROM ' . USERPREFIX . '_usergroups ORDER BY id ASC');
	while ($row = $db->get_row()) {
		$user_group[$row['id']] = [];
		foreach ($row as $key => $value) {
			$user_group[$row['id']][$key] = stripslashes($value);
		}
	}
	set_vars('usergroup', $user_group);
	$db->free();
}

if (file_exists(DLEPlugins::Check(ROOT_DIR . '/language/' . $config['lang_' . $config['skin']] . '/website.lng'))) {
    include_once(DLEPlugins::Check(ROOT_DIR . '/language/' . $config['lang_' . $config['skin']] . '/website.lng'));
} else {
    include_once(DLEPlugins::Check(ROOT_DIR . '/language/' . $config['langs'] . '/website.lng'));
}

$is_logged = false;

require_once DLEPlugins::Check(ENGINE_DIR . '/modules/sitelogin.php');
if (!$is_logged) {
	$member_id['user_group'] = 5;
}

$action = isset($_POST['action']) ? trim(strip_tags($_POST['action'])) : false;
$dle_hash = isset($_POST['dle_hash']) ? trim(strip_tags($_POST['dle_hash'])) : false;

Data::load();
$langVar = Data::receive('lang');

if (!$dle_hash || $dle_hash != $dle_login_hash) {
	echo json_encode(['text' => $langVar['admin']['ajax']['error'], 'error' => 'true'], JSON_UNESCAPED_UNICODE);
	exit;
}

if ($member_id['user_group'] != 1) {
	echo json_encode(['text' => $langVar['admin']['ajax']['error'], 'error' => 'true'], JSON_UNESCAPED_UNICODE);
	exit;
}

function saveTab($data, $action)
{
	$handler = fopen(ENGINE_DIR . '/lazydev/xfield_tab/data/config.php', 'w');
	fwrite($handler, "<?php\n\n//Xfield Tab by LazyDev\n\nreturn ");
	fwrite($handler, var_export($data, true));
	fwrite($handler, ";\n");
	fclose($handler);

	echo json_encode(['text' => Data::get(['admin', 'ajax', $action], 'lang')], JSON_UNESCAPED_UNICODE);
}

function errorJson($text)
{
	echo json_encode(['text' => $text, 'error' => 'true'], JSON_UNESCAPED_UNICODE);
}

if ($action == 'sortTab') {
	$list = json_decode(stripslashes($_POST['data']), true);

	if (!$list) {
		errorJson($langVar['admin']['ajax']['error']);
		exit;
	}
	
	$tabsConfig = Data::receive('config');
	$newTab = [];
	foreach ($list as $id) {
		$newTab[$id['id']] = $tabsConfig[$id['id']];
	}
	
	saveTab($newTab, $action);
}

if ($action == 'deleteTab') {
	$id = intval($_POST['data']);
	
	$tabsConfig = Data::receive('config');
	if ($id < 0 || !isset($tabsConfig[$id])) {
		errorJson($langVar['admin']['ajax']['error']);
		exit;
	}
	
	if (isset($tabsConfig[$id])) {
		unset($tabsConfig[$id]);
		saveTab($tabsConfig, $action);
	}
}

if ($action == 'saveTab') {
	$allXfield = xfieldsload();
	$xfieldArray = [];
	foreach ($allXfield as $value) {
		$xfieldArray[$value[0]] = $value[1];
	}
	
	parse_str($_POST['data'], $arrayTab);
	
	if (!trim($arrayTab['tabName'])) {
		errorJson($langVar['admin']['tab']['errorName']);
		exit;
	}
	
	if (!$arrayTab['xfieldTab'][0]) {
		errorJson($langVar['admin']['tab']['errorxField']);
		exit;
	}
	
	
	$tabsConfig = Data::receive('config');
	
	$edit = false;
	if (isset($arrayTab['id'])) {
		$id = intval($arrayTab['id']);
		if (isset($tabsConfig[$id])) {
			$edit = true;
		}
	}
	
	$altName = 'tab_' . totranslit($arrayTab['tabName'], true, true);

	$xfields = $arrayTab['xfieldTab'];
	$tempXfields = $xfields;
	foreach ($tempXfields as $key => $xfield) {
		if (!isset($xfieldArray[$xfield])) {
			unset($xfields[$key]);
		} else {
			$xfields[$key] = strip_tags(stripslashes($xfield));
		}
	}
	
	if ($tabsConfig) {
		$check = array_filter($tabsConfig, function($tab) use($xfields, $altName) {
			return $altName == $tab['altName'] || count(array_intersect($xfields, $tab['xfield'])) > 0;
		});
		
		if ($check && !$edit) {
			errorJson($langVar['admin']['ajax']['errorTab']);
			exit;
		} else if ($edit && (count($check) == 1 && !$check[$id] || count($check) > 1)) {
			errorJson($langVar['admin']['ajax']['errorTab']);
			exit;
		}
	}
	
	if ($edit) {
		$tabsConfig[$id] = [
			'altName' => $altName,
			'name' => htmlspecialchars(stripslashes($arrayTab['tabName'])),
			'xfield' => $xfields
		];
	} else {
		$tabsConfig[] = [
			'altName' => $altName,
			'name' => htmlspecialchars(stripslashes($arrayTab['tabName'])),
			'xfield' => $xfields
		];
	}

	saveTab($tabsConfig, $action);
}
