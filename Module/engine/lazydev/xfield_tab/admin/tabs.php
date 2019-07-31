<?php
/**
* Настройки модуля
*
* @link https://lazydev.pro/
* @author LazyDev <email@lazydev.pro>
**/

use LazyDev\xFieldTab\Data;

if ($action == 'edit') {
	$id = intval($_GET['id']);
	$tabsConfig = Data::receive('config');
	
	if ($id < 0 ||  !isset($tabsConfig[$id])) {
$content = <<<HTML
<tr>
	<td colspan="2">
		<div class="alert alert-danger alert-styled-left alert-arrow-left alert-component text-left">
			<h4>{$langVar['admin']['tab']['error']}</h4>
			{$langVar['admin']['tab']['errorText']}
		</div>
	</td>
</tr>
HTML;
	} else {
		$allxField = xfieldsload();
		$xfieldSelect = '';
		foreach ($allxField as $value) {
			if (in_array($value[0], $tabsConfig[$id]['xfield'])) {
				$xfieldSelect .= "<option value=\"{$value[0]}\" selected>" . htmlspecialchars($value[1]) . "</option>";
			} else {
				$checkxField = array_filter($tabsConfig, function($tabCfg) use ($value) {
					return in_array($value[0], $tabCfg['xfield']);
				});
				if (!$checkxField) {
					$xfieldSelect .= "<option value=\"{$value[0]}\">" . htmlspecialchars($value[1]) . "</option>";
				}
			}
		}
		$langVar['admin']['tab']['editTab'] .= $tabsConfig[$id]['name'];
$content = <<<HTML
<tr>
	<td class="col-xs-6 col-sm-6 col-md-7">
		<h6 class="media-heading text-semibold">{$langVar['admin']['tab']['tabName']}</h6>
	</td>
	<td class="col-xs-6 col-sm-6 col-md-5"><input name="tabName" type="text" value="{$tabsConfig[$id]['name']}" class="form-control"></td>
</tr>
<tr>
	<td class="col-xs-6 col-sm-6 col-md-7">
		<h6 class="media-heading text-semibold">{$langVar['admin']['tab']['xfieldTab']}</h6>
	</td>
	<td class="col-xs-6 col-sm-6 col-md-5">
		<select class="uniform" name="xfieldTab[]" data-width="100%" multiple>
			{$xfieldSelect}
		</select>
	</td>
</tr>
HTML;
$jsAdminScript[] = <<<HTML
$(function() {
    $('body').on('submit', 'form', function(e) {
        e.preventDefault();
        coreAdmin.ajaxSend($('form').serialize(), 'saveTab', false);
    });
});
HTML;
	}
echo <<<HTML
<form>
	<div class="panel panel-default">
		<div class="panel-heading">{$langVar['admin']['tab']['editTab']}</div>
		<div class="table-responsive">
			<table class="table">
HTML;
echo $content;
echo <<<HTML
			</table>
		</div>
		<div class="panel-footer">
			<button id="sortTab" class="btn bg-primary-600 btn-sm btn-raised position-left">{$langVar['admin']['save']}</button>
			<a href="?mod=xfield_tab" class="btn bg-warning-600 btn-sm btn-raised position-left">{$langVar['admin']['back']}</a>
		</div>
	</div>
	<input type="hidden" name="id" value="{$id}">
</form>
HTML;
} else {
	$tabsConfig = Data::receive('config');
	
	$allXfield = xfieldsload();
	$xfieldSelect = '';
	$xfieldArray = [];
	foreach ($allXfield as $value) {
		$checkxField = array_filter($tabsConfig, function($tabCfg) use ($value) {
			return in_array($value[0], $tabCfg['xfield']);
		});
		if (!$checkxField) {
			$xfieldSelect .= "<option value=\"{$value[0]}\">" . htmlspecialchars($value[1]) . "</option>";
		}
		$xfieldArray[$value[0]] = htmlspecialchars($value[1]);
	}

echo <<<HTML
<div class="modal fade" id="addTab" tabindex="-1" role="dialog" aria-labelledby="addTabLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<form action="" autocomplete="off" id="tabForm">
				<div class="modal-header ui-dialog-titlebar">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<span class="ui-dialog-title" id="addTabLabel">{$langVar['admin']['tab']['addNewTab']}</span>
				</div>
				<div class="modal-body">

				<div class="form-group">
					<div class="row">
						<div class="col-sm-12">
							<label>{$langVar['admin']['tab']['tabName']}</label>
							<div class="input-group">
								<input name="tabName" id="tabName" type="text" class="form-control">
							</div>
						</div>
					</div>
				</div>
				
				<div class="form-group">
					<div class="row">
						<div class="col-sm-12">
							<label>{$langVar['admin']['tab']['xfieldTab']}</label>
							<select class="uniform" id="xfieldTab" name="xfieldTab[]" data-width="100%" multiple>
								{$xfieldSelect}
							</select>
						</div>
					</div>
				</div>
				
				</div>
				<div class="modal-footer" style="margin-top:-20px;">
					<button type="submit" class="btn bg-teal btn-sm btn-raised position-left">
						<i class="fa fa-floppy-o position-left"></i> {$langVar['admin']['tab']['add']}
					</button>
					<button type="button" class="btn bg-slate-600 btn-sm btn-raised" data-dismiss="modal">{$langVar['admin']['tab']['cancel']}</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		{$langVar['admin']['tab']['tabList']}
		<div class="heading-elements">
			<ul class="icons-list">
				<li>
					<a href="#" data-toggle="modal" data-target="#addTab">
						<i class="fa fa-plus-circle position-left"></i>{$langVar['admin']['tab']['addTab']}
					</a>
				</li>
			</ul>
		</div>
	</div>
	<div class="panel-body">
		<div class="dd">
			<ol class="dd-list">
HTML;
if ($tabsConfig) {
	foreach ($tabsConfig as $key => $tabConfig) {
		$xfieldsName = [];
		foreach ($tabConfig['xfield'] as $xfieldKey) {
			$xfieldsName[] = $langVar['admin']['tab']['xfield'] . '<b>' . $xfieldArray[$xfieldKey] . '</b>';
		}
		$xfieldsName = implode('<br>', $xfieldsName);
echo <<<HTML
<li class="dd-item" data-id="{$key}">
	<div class="dd-handle"></div>
	<div class="dd-content">
		<b>{$tabConfig['name']}</b> <i class="help-button visible-lg-inline-block text-primary-600 fa fa-question-circle position-right" data-html="true" data-rel="popover" data-trigger="hover" data-placement="right" data-content="{$xfieldsName}"></i>
		<div class="pull-right">
			<a href="?mod=xfield_tab&action=edit&id={$key}"><i data-rel="popover" data-trigger="hover" data-placement="left" data-content="{$langVar['admin']['tab']['edit']}" class="fa fa-pencil-square-o"></i></a>
			&nbsp;&nbsp;
			<a onclick="deleteTab({$key}); return(false);" href="#"><i data-rel="popover" data-trigger="hover" data-placement="left" data-content="{$langVar['admin']['tab']['delete']}" class="fa fa-trash-o text-danger"></i></a>
		</div>
	</div>
</li>
HTML;
	}
} else {
	echo $langVar['admin']['tab']['noTab'];
}
echo <<<HTML
			</ol>
		</div>
	</div>
	<div class="panel-footer">
		<button id="sortTab" class="btn bg-primary-600 btn-sm btn-raised position-left">{$langVar['admin']['tab']['saveSort']}</button>
	</div>
</div>
HTML;
$jsAdminScript[] = <<<HTML
$(function() {
    $('body').on('submit', 'form', function(e) {
        e.preventDefault();
		let checkName = $('#tabName').val().trim();
		let checkTabxField = $('#xfieldTab').val();
		if (!checkName) {
			Growl.error({
                title: '{$langVar['admin']['tab']['error']}',
                text: '{$langVar['admin']['tab']['errorName']}'
            });
		} else if (!checkTabxField[0]) {
			Growl.error({
                title: '{$langVar['admin']['tab']['error']}',
                text: '{$langVar['admin']['tab']['errorxField']}'
            });
		} else {
			coreAdmin.ajaxSend($('form').serialize(), 'saveTab', function(info) {
				if (!info.error) {
					$('#tabForm')[0].reset();
					$('.uniform').selectpicker('refresh');
				}
			});
		}
    });
	
	$('.dd').nestable({
		maxDepth: 1
	});

	$('.dd').nestable('collapseAll');
	
	$('.dd-handle a').on('mousedown', function(e) {
		e.stopPropagation();
	});

	$('.dd-handle a').on('touchstart', function(e) {
		e.stopPropagation();
	});
	
	$('body').on('click', '#sortTab', function(e) {
		e.preventDefault();
        coreAdmin.ajaxSend(window.JSON.stringify($('.dd').nestable('serialize')), 'sortTab', false);
	});
});

function deleteTab(id) {
	let textDelete = '{$langVar['admin']['tab']['deleteTab']}';
	let getName = $('[data-id='+id+'] b').text();
	textDelete = textDelete.replace(/{tabName}/gi, '<b>' + getName + '</b>');
	
	DLEconfirm(textDelete, '{$langVar['admin']['try']}', function() {
		coreAdmin.ajaxSend(id, 'deleteTab', function() {
			$('li[data-id='+id+']').remove();
		});
	});
}
HTML;
}
?>