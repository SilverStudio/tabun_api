<?php
/*

Скрипт для работы с типом цели "user".

*/

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
?>