<?php
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Credentials: true");
session_start();

require_once "configs.php";
require_once "classes/DB.php";
require_once "classes/Auth.php";
$db = new DB();

error_reporting(E_ALL);

function debug($data){
	echo "<pre>";
	print_r($data);
	echo "</pre>";
}
// $_SESSION['asd'] = 'asd';
// debug($_SESSION);
// exit();

if (isset($_GET['data']) && $_GET['data']) {
	$data = json_decode($_GET['data'], true);
}
$messages = array();

switch ($_GET['cmd']) {
	case 'register':
		$login = trim($data['login']);
		$password = trim($data['password']);
		$email = trim($data['email']);
		$fullName = trim($data['fullName']);

		$Auth = new Auth();
		/*
		** проверка на корректность
		*/
		if (!$Auth->isCorrectLogin($login)) {
			array_push($messages, 'Не корректный логин');
		}
		if (!$Auth->isCorrectPassword($password)) {
			array_push($messages, 'Не корректный пароль');
		}
		if (!$Auth->isCorrectEmail($email)) {
			array_push($messages, 'Не корректный E-mail');
		}
		/*
		** проверка на уникальность
		*/
		if ($Auth->isLoginExist($login)) {
			array_push($messages, 'Такой логин уже существует');
		}
		if ($Auth->isEmailExist($email)) {
			array_push($messages, 'Такой E-mail адрес уже существует');
		}

		/*
		** создания пользователя
		*/
		if (count($messages) == 0) {
			// ошибок нет, регистрируем нового пользователя
			// userID - летит id последнего зарегистрировавшегося пользователя
			$userID = $Auth->addUser(array(
				'login' => $login,
				'password' => md5($password),
				'email' => $email,
				'fullName' => $fullName,
			));

			// сохраняем пользователя как авторизованного
			$_SESSION['userID'] = $userID;
			echo json_encode(array('status' => 'success'));
		} else {
			echo json_encode(array('status' => 'fail', 'messages' => $messages));
		}

		break;

	case 'checkAuth':
		// проверка, сохранен ли ID'шник в сессии. грубо говоря, проверяет, авторизован пользователь или нет
		if (isset($_SESSION['userID'])) {
			echo json_encode(array('status' => 'success'));
		}

		break;
	case 'logout':
		// выход из страницы пользователя
		unset($_SESSION['userID']);
		echo json_encode(array('status' => 'success'));

		break;
	case 'login':
		// авторизация / логин
		$login = trim($data['login']);
		$password = md5(trim($data['password']));

		$Auth = new Auth();
		if ($userID = $Auth->checkUser($login, $password)) {
			// сохраняем пользователя как авторизованного
			$_SESSION['userID'] = $userID;
			echo json_encode(array('status' => 'success'));
		} else {
			echo json_encode(array('status' => 'fail', 'messages' => array('Пароль и/или логин введен неверно')));
		}

		break;
	case 'getProfileData':
		// возвращаем данные по профилю
		if ($_SESSION['userID']) {
			$Auth = new Auth();
			$data = $Auth->getProfileData($_SESSION['userID'], array('login', 'fullName', 'email'));
			echo json_encode(array('status' => 'success', 'user' => $data));
		} else {
			echo json_encode(array('status' => 'fail'));
		}

		break;
	case 'createBoard':
		// возвращаем данные по профилю
		if ($_SESSION['userID']) {
			$Board = new Board();
			// $data = $Board->create();
			echo json_encode(array('status' => 'success', 'user' => $data));
		} else {
			echo json_encode(array('status' => 'fail'));
		}

		break;
	
	default:
		# code...
		break;
}

exit();














































function filter($str){
	$str = htmlspecialchars((stripslashes(trim($str))), ENT_QUOTES);
	$str = str_replace('/','',$str);
	$str = str_replace('.','',$str);
	$str = str_replace('`','',$str);
	return $str;
}

// echo json_encode(array('login' => '11111111','password' => '11111111','email' => '11111111', ));

// $Auth = new Auth();
// echo $Auth->isLoginExist('admin');
// echo $Auth->isEmailExist('admin@site.domain');
// echo $Auth->addUser(array(
// 	'login' => 'jamal_123',
// 	'password' => '123456qwertyu',
// 	'email' => 'jamal_123@mail.ru',
// 	'fullName' => 'Jamal Hasuev',
// ));





































exit();
header("Access-Control-Allow-Origin: http://localhost:8080");

if ($_GET['data']) {
	$data = json_decode($_GET['data']);
}

switch ($_GET['cmd']) {
	case 'register':
		
		break;
	default:
		echo "API works but no command matched !";
		break;
}