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

$target_type = preg_replace('/[^A-Za-z0-9_]/', '', $_GET['type']);
$target      = preg_replace('/[^A-Za-z0-9_]/', '', $_GET['target']);
$method      = preg_replace('/[^A-Za-z0-9_]/', '', $_GET['method']);

// Выбираем нужный набор операций в зависимости от «типа цели»
switch ($target_type) {

	// Тип цели - пользователь.
	case 'user':
		// Проверяем доступность цели, т.е. существование пользователя
		if ($target_user = $this->User_GetUserByLogin($target)) {
			// Есть такой пользователь
			// Выбираем метод
			switch ($method) {
				// Метод «read_profile» - для чтения данных из профиля
				case 'read_profile':
					$response['user_RegistrationDate'] = $target_user->getDateRegister();
					$response['user_ProfileCountry']   = $target_user->getProfileCountry();
					$response['user_ProfileCity']      = $target_user->getProfileCity();
					$response['user_ProfileAbout']     = $target_user->getProfileAbout();
					$response['user_ProfileAvatar']    = $target_user->getProfileAvatar();
				break;
				// Несуществующий метод
				default:
					$api_error = $TAPI_ERR_UNKNOWN_METHOD;
			}
		} else {
			// Нет такого пользователя
			$api_error = $TAPI_ERR_TARGET_NOT_FOUND;
		}
		break;
		
	// Несуществующий тип цели.
	default:
		$api_error = $TAPI_ERR_UNKNOWN_TARGET_TYPE;
		
}

?>