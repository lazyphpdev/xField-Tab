<?php
/**
 * Подключение админки
 *
 * @link https://lazydev.pro/
 * @author LazyDev <email@lazydev.pro>
 **/

if (!defined('DATALIFEENGINE') || !defined('LOGGED_IN')) {
    header('HTTP/1.1 403 Forbidden');
    header('Location: ../../');
    die('Hacking attempt!');
}

include ENGINE_DIR . '/lazydev/xfield_tab/admin/admin.php';
