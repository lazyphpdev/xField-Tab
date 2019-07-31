<?php
/**
* Работа модуля
*
* @link https://lazydev.pro/
* @author LazyDev <email@lazydev.pro>
**/

use LazyDev\xFieldTab\Data;

include_once __DIR__ . '/class/Data.php';
Data::load();

$tabsConfig = Data::receive('config');
$listTabxField = $listDivxField = '';

if ($tabsConfig) {
	$indexTab = 0;
	foreach ($tabsConfig as $tabConfig) {
		$activeTab = $indexTab == 0 ? 'active' : '';
		$listTabxField .= '<li class="' . $activeTab . '"><a href="#' . $tabConfig['altName'] . '" data-toggle="tab" class="legitRipple">' . $tabConfig['name'] . '</a></li>';
		
$listDivxField .= <<<HTML
	<div class="tab-pane {$activeTab}" id="{$tabConfig['altName']}">
		<div class="panel-body">
HTML;
	foreach ($tabConfig['xfield'] as $xfieldInTab) {
		if (isset($xFieldTabs[$xfieldInTab])) {
			if (!$config['allow_admin_wysiwyg']) {
				$xFieldTabs[$xfieldInTab] = str_replace('<!--panel-->', $bb_panel, $xFieldTabs[$xfieldInTab]);
			}
			$listDivxField .= $xFieldTabs[$xfieldInTab];
		}
	}
$listDivxField .= <<<HTML
		</div>
	</div>
HTML;
		$indexTab++;
	}
echo <<<HTML
<ul class="nav nav-tabs">
	{$listTabxField}
</ul>
<div class="panel-tab-content tab-content" style="border: 1px solid #ddd;border-top: none;margin-bottom: 30px;">
	{$listDivxField}
</div>
HTML;
}
?>