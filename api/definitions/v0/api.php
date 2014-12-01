<?php

/*

Определение API версии v0 (тестовая версия).

Этот код работает в контексте объекта PluginTabunAPI_ActionTabunAPI, поэтому обращения к $this относятся к нему.

*/

// Проверка на наличие необходимых аргументов
if ((!isset($_GET['type'])) || (!isset($_GET['target'])) || (!isset($_GET['method']))) {
	$api_error = $TAPI_ERR_MISSING_ARGUMENTS;
	return;
}

$target_type = preg_replace('/[^A-Za-z0-9_\-]/', '', $_GET['type']);
$target      = preg_replace('/[^A-Za-z0-9_\-]/', '', $_GET['target']);
$method      = preg_replace('/[^A-Za-z0-9_\-]/', '', $_GET['method']);

// Проверка существования типа цели через проверку существования соответствующего скрипта
$script = $api_definition_dir . '/api_' . $target_type . '.php';
if (file_exists($script))
{
	// Есть такой скрипт - загружаем
	require_once($script);
} else {
	// Нет такого скрипта - несуществующий тип цели
	$api_error = $TAPI_ERR_UNKNOWN_TARGET_TYPE;
}


?>