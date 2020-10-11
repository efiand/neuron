<?php

$id = (int) $payload['id'];
$login = prepare_post_data($payload['login']);
$first_name = prepare_post_data($payload['first_name']);
$last_name = prepare_post_data($payload['last_name']);
$middle_name = prepare_post_data($payload['middle_name']);
$password = isset($payload['password']) ? prepare_post_data($payload['password']) : '';

$queryBody = ' login = "' . $login . '", first_name = "' . $first_name . '", last_name = "'
	. $last_name . '", middle_name = "' . $middle_name . '"';

if ($password) {
	$queryBody .= ', password_hash = "' . password_hash($password, PASSWORD_DEFAULT) . '"';
}

if ($id > 0) {
	$query = 'UPDATE users SET' . $queryBody . ' WHERE id = ' . $id;
	$_['user'] = get_db_data('SELECT * FROM users_v WHERE id = ' . $id, 'single');
	unset($_['user']['password_hash']);
	$_['user']['allowFlag'] = !$password;
	$_['user']['status'] = 'Изменения сохранены';
} else {
	$query = 'INSERT INTO users SET' . $queryBody . ', role_id = 1';
	$_['user'] = [
		'status' => 'Пользователь добавлен'
	];
}

set_db_data($query);
