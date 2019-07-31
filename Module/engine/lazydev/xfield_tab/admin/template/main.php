<?php
/**
* Дизайн админ панель
*
* @link https://lazydev.pro/
* @author LazyDev <email@lazydev.pro>
**/

if (!defined('DATALIFEENGINE') || !defined('LOGGED_IN')) {
	header('HTTP/1.1 403 Forbidden');
	header('Location: ../../');
	die('Hacking attempt!');
}

echo <<<HTML
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{$langVar['admin']['title']}</title>
        <link href="engine/skins/fonts/fontawesome/styles.min.css" rel="stylesheet" type="text/css">
        <link href="engine/skins/stylesheets/application.css" rel="stylesheet" type="text/css">
        <link href="{$config['http_home_url']}engine/lazydev/{$modLName}/admin/template/assets/style.css" rel="stylesheet" type="text/css">
        <script src="engine/skins/javascripts/application.js"></script>
    </head>
    <body>
        <script>
            var dle_act_lang = [{$langVar['admin']['other']['jslang']}];
            var cal_language = {
                en: {
                    months: [{$langVar['admin']['other']['jsmonth']}],
                    dayOfWeekShort: [{$langVar['admin']['other']['jsday']}]
                }
            };
            var filedefaulttext = '{$langVar['admin']['other']['jsnotgot']}';
            var filebtntext = '{$langVar['admin']['other']['jschoose']}';
            var dle_login_hash = '{$dle_login_hash}';
        </script>
        <div id="loading-layer" class="shadow-depth3"><i class="fa fa-spinner fa-spin"></i></div>
        <div class="navbar navbar-inverse">
            <div class="navbar-header">
                <a class="navbar-brand" href="?mod={$modLName}">{$langVar['name']}</a>
                <ul class="nav navbar-nav visible-xs-block">
                    <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="fa fa-angle-double-down"></i></a></li>
                    <li><a class="sidebar-mobile-main-toggle"><i class="fa fa-bars"></i></a></li>
                </ul>
            </div>
            <div class="navbar-collapse collapse" id="navbar-mobile">
                <div class="navbar-right">	
                    <ul class="nav navbar-nav">
                        <li><a href="{$PHP_SELF}?mod={$modLName}" title="{$langVar['admin']['other']['main']}">{$langVar['admin']['other']['main']}</a></li>
                        <li><a href="{$PHP_SELF}" title="{$langVar['admin']['other']['all_menu_dle']}">{$langVar['admin']['other']['all_menu_dle']}</a></li>
                        <li><a href="{$config['http_home_url']}" title="{$langVar['admin']['other']['site']}" target="_blank">{$langVar['admin']['other']['site']}</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="page-container">
            <div class="page-content">
                
                <div class="content-wrapper">
                    <div class="content">
HTML;

?>