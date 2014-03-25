<?php
$modExtra = $modx->getService('modextra', 'modExtra', $modx->getOption('modextra_core_path', null, $modx->getOption('core_path') . 'components/modextra/') . 'model/modextra/', $scriptProperties);
if ( ! $modExtra instanceof modExtra) return '';

$action = $modx->getOption('action', $scriptProperties, 'index');

return $modExtra->controller($action);
