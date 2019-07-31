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

$jsAdminScript = implode($jsAdminScript);
$additionalJsAdminScript = implode($additionalJsAdminScript);
echo '
                        <div class="panel" style="margin-top: 20px;">
                            <div class="panel-content">
                                <div class="panel-body">
                                    &copy; <a href="https://lazydev.pro/" target="_blank">LazyDev</a> ' . date('Y', time()) . ' All rights reserved.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="' . $config['http_home_url'] . 'engine/lazydev/' . $modLName . '/admin/template/assets/core.js"></script>
        <script>let coreAdmin = new Admin("' . $modLName . '"); ' . $jsAdminScript . '</script>
        ' . $additionalJsAdminScript . '
    </body>
</html>';

?>