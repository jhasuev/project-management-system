<?php
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Credentials: true");
session_start();

require_once "configs.php";
require_once "classes/DB.php";
require_once "classes/Auth.php";
require_once "classes/Board.php";
$db = new DB();

error_reporting(E_ALL);

function debug($data){
	echo "<pre>";
	print_r($data);
	echo "</pre>";
}
// $_SESSION['asd'] = 'asd';
// var_dump($_SESSION);
// var_dump($_GET);
// echo 'REQUEST_METHOD ------- ' . $_SERVER['REQUEST_METHOD'];
// exit();

if (isset($_GET['data']) && $_GET['data']) {
	// $data = json_decode(urldecode($_GET['data']), true);
	$data = json_decode(($_GET['data']), true);
}
$messages = array();
$result = array();

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
			$result = array('status' => 'success');
		} else {
			$result = array('status' => 'fail', 'messages' => $messages);
		}

		break;
	case 'checkAuth':
		// проверка, сохранен ли ID'шник в сессии. грубо говоря, проверяет, авторизован пользователь или нет
		if (isset($_SESSION['userID'])) {
			$result = array('status' => 'success');
		} else {
			$result = array('status' => 'fail');
		}

		break;
	case 'logout':
		// выход из страницы пользователя
		unset($_SESSION['userID']);
		$result = array('status' => 'success');

		break;
	case 'login':
		// авторизация / логин
		$login = trim($data['login']);
		$password = md5(trim($data['password']));

		$Auth = new Auth();
		if ($userID = $Auth->checkUser($login, $password)) {
			// сохраняем пользователя как авторизованного
			$_SESSION['userID'] = $userID;
			$result = array('status' => 'success');
		} else {
			$result = array('status' => 'fail', 'messages' => array('Пароль и/или логин введен неверно'));
		}

		break;
	case 'getProfileData':
		// возвращаем данные по профилю
		if (isset($_SESSION['userID'])) {
			$Auth = new Auth();
			$data = $Auth->getProfileData($_SESSION['userID'], array('login', 'fullName', 'email'));
			$result = array('status' => 'success', 'user' => $data);
		} else {
			$result = array('status' => 'fail');
		}

		break;


	case 'createBoard':
		// Создаем доску
		if (isset($_SESSION['userID'])) {
			$title = trim($data['title']);
			$color = $data['color'];
			$is_private = !!$data['is_private'];

			if ($title) {
				$Board = new Board();
				$boardID = $Board->create(array(
					'userID' => $_SESSION['userID'],
					'title' => addslashes($title),
					'color' => $color,
					'is_private' => $is_private,
					'created_time' => time(),
				));
				$result = array('status' => 'success', 'BoardID' => $boardID);

				// $Board->addAction($boardID, 'board_created', $actionID);
				$Board->addAction($boardID, 'board_created', $boardID);

			} else {
				$result = array('status' => 'fail');
			}
			
		} else {
			$result = array('status' => 'fail');
		}

		break;

	case 'getBoardList':
		// возвращаем список досок
		if (isset($_SESSION['userID'])) {
			$Board = new Board();
			$boards = $Board->getBoardsList($_SESSION['userID']);

			$result = array('status' => 'success', 'boards' => $boards);
		} else {
			$result = array('status' => 'fail');
		}

		break;

	case 'createBoardCard':
		// создаем карточку в доске
		if (isset($_SESSION['userID'])) {
			$title = trim($data['title']);
			$boardID = $data['boardID'] * 1;

			if ($title && $boardID) {
				$Board = new Board();
				if ($Board->hasAccess($boardID)) {
					
					$cardID = $Board->createCard(array(
						'title' => addslashes($title),
						'boardID' => $boardID
					));
					$result = array('status' => 'success', 'cardID' => $cardID);
					$Board->addAction($boardID, 'card_created', $cardID);
				} else {
					$result = array('status' => 'no_access');
				}
			} else {
				$result = array('status' => 'fail');
			}
			
		} else {
			$result = array('status' => 'fail');
		}

		break;

	case 'removeBoard':
		// создаем карточку в доске
		if (isset($_SESSION['userID'])) {
			$boardID = $data['boardID'] * 1;

			if ($boardID) {
				$Board = new Board();
				if ($Board->hasAccess($boardID)) {
					if ($Board->removeBoard($boardID)) {
						$result = array('status' => 'success');
					} else {
						$result = array('status' => 'fail');
					}
				} else {
					$result = array('status' => 'no_access');
				}
			} else {
				$result = array('status' => 'fail');
			}
			
		} else {
			$result = array('status' => 'fail');
		}

		break;

	case 'removeCard':
		// создаем карточку в доске
		if (isset($_SESSION['userID'])) {
			$boardID = $data['boardID'] * 1;
			$cardID = $data['cardID'] * 1;

			if ($boardID) {
				$Board = new Board();
				if ($Board->hasAccess($boardID)) {
					if ($Board->removeCard($cardID)) {
						$result = array('status' => 'success');
						$Board->addAction($boardID, 'card_removed', $cardID);
					} else {
						$result = array('status' => 'fail');
					}
				} else {
					$result = array('status' => 'no_access');
				}
			} else {
				$result = array('status' => 'fail');
			}
			
		} else {
			$result = array('status' => 'fail');
		}

		break;

	case 'removeTask':
		// создаем карточку в доске
		if (isset($_SESSION['userID'])) {
			$boardID = $data['boardID'] * 1;
			$taskID = $data['taskID'] * 1;

			if ($boardID) {
				$Board = new Board();
				if ($Board->hasAccess($boardID)) {
					if ($Board->removeTask($taskID)) {
						$result = array('status' => 'success');
						$Board->addAction($boardID, 'task_removed', $taskID);
					} else {
						$result = array('status' => 'fail');
					}
				} else {
					$result = array('status' => 'no_access');
				}
			} else {
				$result = array('status' => 'fail');
			}
			
		} else {
			$result = array('status' => 'fail');
		}

		break;

	case 'getBoardTitle':
		// получаем название доски
		if (isset($_SESSION['userID'])) {
			$boardID = $data['boardID'] * 1;

			if ($boardID) {
				$Board = new Board();
				if ($Board->hasAccess($boardID)) {
					
					$boardTitle = $Board->getBoardTitle($boardID);
					$result = array('status' => 'success', 'boardTitle' => $boardTitle);
				} else {
					$result = array('status' => 'no_access');
				}
			} else {
				$result = array('status' => 'fail');
			}
			
		} else {
			$result = array('status' => 'fail');
		}

		break;

	case 'createTask':
		// создаем задачу в карточке
		if (isset($_SESSION['userID'])) {
			$title = trim($data['title']);
			$boardID = $data['boardID'] * 1;
			$cardID = $data['cardID'] * 1;

			if ($title && $boardID) {
				$Board = new Board();
				if ($Board->hasAccess($boardID)) {
					
					$taskID = $Board->createTask(array(
						'boardID' => $boardID,
						'cardID' => $cardID,
						'title' => addslashes($title),
					));
					$result = array('status' => 'success', 'taskID' => $taskID);
					$Board->addAction($boardID, 'task_created', $taskID);
				} else {
					$result = array('status' => 'no_access');
				}
			} else {
				$result = array('status' => 'fail');
			}
			
		} else {
			$result = array('status' => 'fail');
		}

		break;

	case 'getTasks':
		// получаем задачи
		if (isset($_SESSION['userID'])) {
			$boardID = $data['boardID'] * 1;


			if ($boardID) {
				$Board = new Board();
				
				if ($Board->hasAccess($boardID)) {
					
					$tasks = $Board->getTasks($boardID);
					$result = array('status' => 'success', 'tasks' => $tasks);
				} else {
					$result = array('status' => 'no_access');
				}
			} else {
				$result = array('status' => 'fail');
			}
			
		} else {
			$result = array('status' => 'fail');
		}

		break;

	case 'getSingleTask':
		// получаем задачу
		if (isset($_SESSION['userID'])) {
			$taskID = $data['taskID'] * 1;

			if ($taskID) {
				$Board = new Board();
				$task = $Board->getSingleTask($taskID);
				$result = array('status' => 'success', 'task' => $task);
			} else {
				$result = array('status' => 'fail');
			}
			
		} else {
			$result = array('status' => 'fail');
		}

		break;

	case 'getSingleBoard':
		// получаем задачу
		if (isset($_SESSION['userID'])) {
			$boardID = $data['boardID'] * 1;

			if ($boardID) {
				$Board = new Board();

				if ($Board->hasAccess($boardID)) {

					$singleBoard = $Board->getSingleBoard($boardID);
					$result = array('status' => 'success', 'board' => $singleBoard);
				} else {
					$result = array('status' => 'no_access');
				}

			} else {
				$result = array('status' => 'fail');
			}
			
		} else {
			$result = array('status' => 'fail');
		}

		break;

	case 'getCards':
		// получаем карты / колонки 
		if (isset($_SESSION['userID'])) {
			$boardID = $data['boardID'] * 1;

			if ($boardID) {
				$Board = new Board();
				
				if ($Board->hasAccess($boardID)) {
					$cards = $Board->getCards($boardID);
					$result = array('status' => 'success', 'cards' => $cards);
				} else {
					$result = array('status' => 'no_access');
				}

			} else {
				$result = array('status' => 'fail');
			}
			
		} else {
			$result = array('status' => 'fail');
		}

		break;

	case 'sortCards':
		// сортируем карточки
		if (isset($_SESSION['userID'])) {
			$boardID = $data['boardID'] * 1;
			$new_positions = $data['new_positions'];

			if ($boardID) {
				$Board = new Board();
				
				if ($Board->hasAccess($boardID)) {
					if ($Board->sortCards($new_positions)) {
						$cards = $Board->getCards($boardID);
						$result = array('status' => 'success', 'cards' => $cards);
					} else {
						$result = array('status' => 'sorting_errors');
					}
				} else {
					$result = array('status' => 'no_access');
				}

			} else {
				$result = array('status' => 'fail');
			}
			
		} else {
			$result = array('status' => 'fail');
		}

		break;

	case 'changeTaskField':
		// меняем значение в поле у задачи 

		if (isset($_SESSION['userID'])) {
			$taskID = $data['taskID'] * 1;
			$boardID = $data['boardID'] * 1;

			$field = trim($data['field']);
			$value = addslashes(trim($data['value']));

			if ($taskID) {
				$Board = new Board();
				if ($Board->hasAccess($boardID)) {
					
					$new_value = $Board->changeTaskField($taskID, $field, $value);
					$result = array('status' => 'success', 'new_value' => $new_value);

					$Board->addAction($boardID, 'task_field_' . $field . '_changed', $taskID);
				} else {
					$result = array('status' => 'no_access');
				}
			} else {
				$result = array('status' => 'fail');
			}
			
		} else {
			$result = array('status' => 'fail');
		}

		break;

	case 'changeCardField':
		// меняем значение в поле у задачи 

		if (isset($_SESSION['userID'])) {
			$cardID = $data['cardID'] * 1;
			$boardID = $data['boardID'] * 1;

			$field = trim($data['field']);
			$value = addslashes(trim($data['value']));

			if ($cardID) {
				$Board = new Board();
				if ($Board->hasAccess($boardID)) {
					
					$new_value = $Board->changeCardField($cardID, $field, $value);
					$result = array('status' => 'success', 'new_value' => $new_value);
					$Board->addAction($boardID, 'card_field_' . $field . '_changed', $cardID);
				} else {
					$result = array('status' => 'no_access');
				}
			} else {
				$result = array('status' => 'fail');
			}
			
		} else {
			$result = array('status' => 'fail');
		}

		break;

	case 'changeBoardField':
		// меняем значение в поле у задачи 

		if (isset($_SESSION['userID'])) {
			$boardID = $data['boardID'] * 1;
			$field = trim($data['field']);
			$value = addslashes(trim($data['value']));

			// echo $value;

			if ($boardID) {
				$Board = new Board();
				if ($Board->hasAccess($boardID)) {
					
					$new_value = $Board->changeBoardField($boardID, $field, $value);
					$result = array('status' => 'success', 'new_value' => $new_value);
					$Board->addAction($boardID, 'board_field_' . $field . '_changed', $boardID);
				} else {
					$result = array('status' => 'no_access');
				}
			} else {
				$result = array('status' => 'fail');
			}
			
		} else {
			$result = array('status' => 'fail');
		}

		break;

	case 'saveCheckList':
		// сохраняем чек-лист

		if (isset($_SESSION['userID'])) {
			$taskID = $data['taskID'] * 1;
			$boardID = $data['boardID'] * 1;
			$checkList = addslashes(json_encode($data['checkList']));

			if ($taskID) {
				$Board = new Board();
				if ($Board->hasAccess($boardID)) {
					
					$new_value = $Board->changeTaskField($taskID, 'checkList', $checkList);
					$result = array('status' => 'success', 'new_value' => $new_value);
					$Board->addAction($boardID, 'task_checkList_changed', $taskID);
				} else {
					$result = array('status' => 'no_access');
				}
			} else {
				$result = array('status' => 'fail');
			}
			
		} else {
			$result = array('status' => 'fail');
		}

		break;

	case 'addComment':
		// добавялем комментарий в задачу

		if (isset($_SESSION['userID']) && trim($data['comment'])) {
			$taskID = $data['taskID'] * 1;
			$boardID = $data['boardID'] * 1;
			$comment = addslashes($data['comment']);

			if ($taskID) {
				$Board = new Board();
				if ($Board->hasAccess($boardID)) {
					
					if ($Board->addComment($taskID, $comment)) {
						$result = array('status' => 'success');
						$Board->addAction($boardID, 'task_comment_added', $taskID);
					} else {
						$result = array('status' => 'fail');
					}
				} else {
					$result = array('status' => 'no_access');
				}
			} else {
				$result = array('status' => 'fail');
			}
			
		} else {
			$result = array('status' => 'fail');
		}

		break;

	case 'loadComments':
		// загружаем комментарии

		if (isset($_SESSION['userID'])) {
			$taskID = $data['taskID'] * 1;
			$boardID = $data['boardID'] * 1;

			if ($taskID) {
				$Board = new Board();
				if ($Board->hasAccess($boardID)) {
					
					$comments = $Board->loadComments($taskID);
					$result = array('status' => 'success', 'comments' => $comments);
				} else {
					$result = array('status' => 'no_access');
				}
			} else {
				$result = array('status' => 'fail');
			}
			
		} else {
			$result = array('status' => 'fail');
		}

		break;

	case 'addParticipant':
		// добавялем нового участника

		if (isset($_SESSION['userID'])) {
			$boardID = $data['boardID'] * 1;

			if ($boardID) {
				$Board = new Board();

				if (($Board->getBoardOwnerID($boardID) == $_SESSION['userID'])) {
					$Auth = new Auth();
					$login = addslashes($data['login']);
					
					if ($Auth->isCorrectLogin($login)) {
						$new_userID = $Auth->getIDByLogin($login);
						if ($new_userID) {
							if ($Board->isParticipant($boardID, $new_userID) || $Board->getBoardOwnerID($boardID) == $new_userID) {
								$result = array('status' => 'already_added');
							} else {
								$adding_result = $Board->addParticipant($boardID, $new_userID);
								$result = array('status' => 'success', 'result' => $adding_result);
								$Board->addAction($boardID, 'board_participant_added', $new_userID);
							}
						} else {
							$result = array('status' => 'login_not_exists');
						}
					} else {
						$result = array('status' => 'login_incorrect');
					}
				} else {
					$result = array('status' => 'no_access');
				}

			} else {
				$result = array('status' => 'fail');
			}

		} else {
			$result = array('status' => 'fail');
		}

		break;

	case 'loadParticipants':
		// добавялем нового участника

		if (isset($_SESSION['userID'])) {
			$boardID = $data['boardID'] * 1;

			if ($boardID) {
				$Board = new Board();
				if (($Board->getBoardOwnerID($boardID) == $_SESSION['userID']) ||
					($Board->isParticipant($boardID, $_SESSION['userID']))) {
					
					$Auth = new Auth();
					$participants = $Board->loadParticipants($boardID);

					if (!is_array($participants)) {
						$participants = array();
					}

					$boardOwnerID = $Board->getBoardOwnerID($boardID);
					$owner_data = $Auth->getProfileData($boardOwnerID, array('id', 'login', 'fullName'));

					array_unshift($participants, $owner_data);

					$result = array('status' => 'success', 'participants' => $participants);
				} else {
					$result = array('status' => 'no_access');
				}
			} else {
				$result = array('status' => 'fail');
			}

			
		} else {
			$result = array('status' => 'fail');
		}

		break;

	case 'getActions':
		// получаем действия
		if (isset($_SESSION['userID'])) {
			$boardID = $data['boardID'] * 1;
			$lastActionID = '';
			if (isset($data['lastActionID'])) {
				$lastActionID = $data['lastActionID'];
			}

			if ($boardID) {
				$Board = new Board();
				
				if ($Board->hasAccess($boardID)) {

					if ($lastActionID && $Board->getLastActionID($boardID) == $lastActionID) {
						$result = array('status' => 'success');
					} else {
						// все действия
						$actions = $Board->getActions($boardID);

						// все участники доски
						$Auth = new Auth();
						$participants = $Board->loadParticipants($boardID);

						if (!is_array($participants)) {
							$participants = array();
						}

						$boardOwnerID = $Board->getBoardOwnerID($boardID);
						$owner_data = $Auth->getProfileData($boardOwnerID, array('id', 'login', 'fullName'));

						array_unshift($participants, $owner_data);

						// все карты данной доски
						$cards = $Board->getCards($boardID, true);

						// все задачки всех карт данной доски
						$tasks = $Board->getTasks($boardID, true);

						$result = array(
							'status' => 'success',
							'actions' => $actions,
							'users' => $participants,
							'cards' => $cards,
							'tasks' => $tasks,
							'lastActionID' => $lastActionID,
						);
					}


				} else {
					$result = array('status' => 'no_access');
				}

			} else {
				$result = array('status' => 'fail');
			}
			
		} else {
			$result = array('status' => 'fail');
		}
		break;
}

if (isset($_SESSION['userID'])) {
	$result['is_authed'] = true;
} else {
	$result['is_authed'] = false;
}

echo json_encode($result);


exit();