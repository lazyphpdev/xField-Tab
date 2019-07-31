<?php
/**
* Админ панель
*
* @link https://lazydev.pro/
* @author LazyDev <email@lazydev.pro>
**/

if (!defined('DATALIFEENGINE') || !defined('LOGGED_IN')) {
	header('HTTP/1.1 403 Forbidden');
	header('Location: ../../');
	die('Hacking attempt!');
}
use LazyDev\xFieldTab\Data;

include realpath(__DIR__ . '/..') . '/class/Data.php';
Data::load();
$langVar = Data::receive('lang');
$configVar = Data::receive('config');

$modLName = 'xfield_tab';
$jsAdminScript = [];

include ENGINE_DIR . '/lazydev/' . $modLName . '/admin/template/main.php';
include ENGINE_DIR . '/lazydev/' . $modLName . '/admin/tabs.php';
include ENGINE_DIR . '/lazydev/' . $modLName . '/admin/template/footer.php';
?>