<?php
class PluginTabunAPI_ActionTabunAPI extends ActionPlugin {
	public function Init() {
		// Дефолтная страница на /test_api
		$this->SetDefaultEvent('description');
	}

	protected function RegisterEvent() {
		$this->AddEvent('description','EventDescription');
		$this->AddEventPreg('/^v\d+$/', 'EventAPICall');
	}

	protected function EventDescription() {
		$this->Viewer_AddHtmlTitle('API');
	}
	
	// Этот ивент вызывается при обращении к API
	protected function EventAPICall() {
		// Коды ошибок
		$TAPI_ERR_OK                  = 0;
		$TAPI_ERR_VERSION_UNAVAILABLE = 1;
		$TAPI_ERR_UNKNOWN_TARGET_TYPE = 2;
		$TAPI_ERR_UNKNOWN_METHOD      = 3;
		$TAPI_ERR_TARGET_NOT_FOUND    = 4;
		$TAPI_ERR_MISSING_ARGUMENTS   = 5;
		$TAPI_ERR_AUTH_REQUIRED       = 6;
		$TAPI_ERR_AUTH_FAILED         = 7;
		$TAPI_ERR_METHOD_ERROR        = 8;
		
		// Переменная для кода ошибки
		$api_error = $TAPI_ERR_OK;
		
		// Узнаём каталог с описанием API (где находятся определения и форматы)
		$api_dir = rtrim(Config::Get('tabunapi.api_dir'), '/');
		
		// Узнаём запрошенный формат данных. По умолчанию используется XML - 'xml'.
		if (isset($_GET['format'])) {
			$data_format = preg_replace('/[^A-Za-z0-9_]/', '', $_GET['format']);
		} else {
			$data_format = 'xml';
		}
		
		// Проверяем доступность запрошенного формата
		$format_script = $api_dir . '/formats/' . $data_format . '.php';
		if (file_exists($format_script)) {
			// Есть такой формат - загружаем
			require_once ($format_script);
		} else {
			// Нет такого формата - ошибка
			// Так как формат данных не определён, ничего не остаётся, кроме как выдать неотформатированную ошибку.
			// В нормальной ситуации эта ошибка не может возникнуть у клиента и не должна специально обрабатываться.
			die('Unknown format');
		}
		
		// Массив для ответа API
		$response = array();
		
		// Узнаём запрошенную версию API
		$api_version = Router::getInstance()->GetActionEvent();
		
		// Задаём пути к определению запрошенной версии API
		$api_definition_dir    = $api_dir . '/definitions/' . $api_version;
		$api_definition_script = $api_definition_dir . '/api.php';
		
		// Проверяем доступность определения
		if ((file_exists($api_definition_dir)) && (file_exists($api_definition_script))) {
			// Есть такая версия - загружаем
			require_once ($api_definition_script);
		} else {
			// Нет такой версии - ошибка
			$api_error = $TAPI_ERR_VERSION_UNAVAILABLE;
		}
	
		// Говорим модулю Viewer делать вывод в plain text
		$this->Viewer_SetResponseAjax('ajax', true, false);
		
		// Добавляем код ошибки в ответ
		$response['api_err'] = $api_error;
		
		// Форматируем и выводим ответ
		echo api_format_encode($response);
	}

	public function EventShutdown() {
	}
}
?>