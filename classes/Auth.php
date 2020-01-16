<?php

class Auth {
	// public $login = 'admin2';
	// public $email = 'admin@site.domain';
	// public $password = 'qwerty';
	public $mysqli;

	public function isCorrectLogin($login){
		$errors = 0;
		if (strlen($login) < 4) {
			$errors++;
		}
		if ((strpos($login, '.') != 0) ||
			(strpos($login, '<') != 0) ||
			(strpos($login, '>') != 0) ||
			(strpos($login, '"') != 0) ||
			(strpos($login, '\'') != 0) ||
			(strpos($login, '\\') != 0) ||
			(strpos($login, '/') != 0)) {
			$errors++;
		}

		return !$errors;
	}
	public function isCorrectEmail($email){
		return !!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email);
	}
	public function isCorrectPassword($password){
		return (strlen(trim($password)) >= 6) ? true : false;
	}

	public function isLoginExist($login){
		$sql = "SELECT `id` FROM `users` WHERE `login` = '{$login}'";
		$result = $GLOBALS['db']->mysqli->query($sql);
		if ($result->num_rows === 0) {
			return false;
		}
		return true;
	}
	
	public function isEmailExist($email){
		$sql = "SELECT `id` FROM `users` WHERE `email` = '{$email}'";
		$result = $GLOBALS['db']->mysqli->query($sql);
		if ($result->num_rows === 0) {
			return false;
		}
		return true;
	}

	public function addUser($user_data){
		$fields = "`" . implode(array_keys($user_data), "`,`") . "`";
		$values = "'" . implode(array_values($user_data), "','") . "'";

		$sql = "INSERT INTO `users` ({$fields}) VALUES ({$values})";
		$GLOBALS['db']->mysqli->query($sql);
		return $GLOBALS['db']->mysqli->insert_id;
	}

	public function checkUser($login, $password){
		// $password = md5($password);

		$sql = "SELECT `id` FROM `users` WHERE `login` = '{$login}' AND `password` = '{$password}'";
		// echo $sql;
		$result = $GLOBALS['db']->mysqli->query($sql);
		if ($result->num_rows === 0) {
			return false;
		}
		return $result->fetch_assoc()['id'];
	}
}