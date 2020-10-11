<?php

if ($payload['login']) {
	$login = trim($payload['login']);
	$user = get_db_data('SELECT * FROM users_v WHERE login = "' . $login . '"', 'single');
	if (isset($user['login'])) {
		if (password_verify($payload['password'], $user['password_hash'])) {
			$user['status'] = 'Пользователь авторизован.';
			$user['allowFlag'] = true;
		} else {
			$user['status'] = 'Неверный пароль.';
		}
		unset($user['password_hash']);
	} else {
		$user['status'] = 'Пользователь не найден.';
	}
} else {
	$user['status'] = 'Пользователь не найден.';
}
$user['task_history'] = $env['TASK_HISTORY'];
$_['user'] = $user;
