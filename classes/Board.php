<?php
class Board {
	public $mysqli;

	public function hasAccess($boardID){
		return  ($this->getBoardOwnerID($boardID) == $_SESSION['userID']) ||
				($this->isParticipant($boardID, $_SESSION['userID'])) ||
				($this->isBoardPrivate($boardID) == '0');
	}

	public function create($board_data){
		$fields = "`" . implode(array_keys($board_data), "`,`") . "`";
		$values = "'" . implode(array_values($board_data), "','") . "'";

		$sql = "INSERT INTO `boards` ({$fields}) VALUES ({$values})";
		$GLOBALS['db']->mysqli->query($sql);
		return $GLOBALS['db']->mysqli->insert_id;
	}

	public function getBoardsList($userID){
		$sql = "SELECT `boards`.`id`, `title`, `is_private`, `color`, `created_time`, (IF(`boards`.`userID` = {$userID}, 1, 0)) as `is_owner` 
				FROM `boards`
				LEFT JOIN `boardparticipants` ON boardparticipants.boardID = boards.id
				WHERE (`boards`.`userID` = {$userID} OR `boardparticipants`.`userID` = {$userID}) AND `boards`.`status` = 'active'
				ORDER BY `boards`.`id` DESC";

		// echo $sql;

		$result = $GLOBALS['db']->mysqli->query($sql);
		if ($result->num_rows === 0) {
			return false;
		}

		$boards = array();

		while($row = $result->fetch_assoc()){
			array_push($boards, $row);
		}

		return $boards;
	}

	public function getBoardTitle($boardID){
		$sql = "SELECT `title` FROM `boards` WHERE `id` = {$boardID} AND `boards`.`status` = 'active'";

		$result = $GLOBALS['db']->mysqli->query($sql);
		if ($result->num_rows === 0) {
			return false;
		}
		return $result->fetch_assoc()['title'];
	}

	public function isBoardPrivate($boardID){
		$sql = "SELECT `is_private` FROM `boards` WHERE `id` = {$boardID} AND `boards`.`status` = 'active'";

		$result = $GLOBALS['db']->mysqli->query($sql);
		if ($result->num_rows === 0) {
			return false;
		}
		return $result->fetch_assoc()['is_private'];
	}

	public function getCards($boardID, $get_all = false){
		if ($get_all) {
			$sql = "SELECT `id`,`title`,`order_pos` as `order` FROM `boardcards` WHERE `boardID` = {$boardID}";
		} else {
			$sql = "SELECT `id`,`title`,`order_pos` as `order` FROM `boardcards` WHERE `boardID` = {$boardID} AND `status` = 'active'";
		}

		$result = $GLOBALS['db']->mysqli->query($sql);
		if ($result->num_rows === 0) {
			return false;
		}

		$cards = array();

		while($row = $result->fetch_assoc()){
			array_push($cards, $row);
		}

		return $cards;
	}

	public function sortCards($new_positions){
		for ($i = 0, $count = count($new_positions); $i < $count; $i++) { 
			$cardID = $new_positions[$i]['cardID'];
			$order = $new_positions[$i]['order'];
			$sql = "UPDATE `boardcards` SET `order_pos` = {$order} WHERE `id` = {$cardID};";
			$result = $GLOBALS['db']->mysqli->query($sql);
			if (!$result) {
				return false;
			}
		}
		return true;
	}

	public function createCard($board_data){
		
		$sql = "SELECT COUNT(`id`) as `cards_count` FROM `boardcards` WHERE `boardID` = {$board_data['boardID']}";
		$cards_count = $GLOBALS['db']->mysqli->query($sql);
		$cards_count = $cards_count->fetch_assoc()['cards_count'];

		// echo $cards_count;

		$board_data['order_pos'] = $cards_count + 1;

		$fields = "`" . implode(array_keys($board_data), "`,`") . "`";
		$values = "'" . implode(array_values($board_data), "','") . "'";

		$sql = "INSERT INTO `boardcards` ({$fields}) VALUES ({$values})";
		$GLOBALS['db']->mysqli->query($sql);
		return $GLOBALS['db']->mysqli->insert_id;
	}

	public function getTasks($boardID, $get_all = false){
		if ($get_all) {
			$sql = "SELECT `id`,`cardID`,`title`,`deadline`, `done`, (IF(`checkList` != '', 1, '')) as `checkList`,`order_pos` as `order`
							FROM `boardtasks` 
							WHERE `boardID` = {$boardID}
							ORDER BY `id` DESC";
		} else {
			$sql = "SELECT `id`,`cardID`,`title`,`deadline`, `done`, (IF(`checkList` != '', 1, '')) as `checkList`,`order_pos` as `order`
							FROM `boardtasks` 
							WHERE `boardID` = {$boardID} AND `status` = 'active'
							ORDER BY `id` DESC";
		}

		$result = $GLOBALS['db']->mysqli->query($sql);
		if ($result->num_rows === 0) {
			return false;
		}

		$cards = array();

		while($row = $result->fetch_assoc()){
			array_push($cards, $row);
		}

		return $cards;
	}

	public function getSingleTask($taskID){
		$sql = "SELECT `id`,`title`,`description`,`deadline`,`checkList`,`done`
						FROM `boardtasks` 
						WHERE `id` = {$taskID} 
						ORDER BY `id` DESC";

		$result = $GLOBALS['db']->mysqli->query($sql);
		if ($result->num_rows === 0) {
			return false;
		}

		return $result->fetch_assoc();
	}

	public function getSingleBoard($boardID){
		$sql = "SELECT `id`, `userID`, `title`, `is_private`, `color`, `created_time`, (IF(`userID` != $_SESSION[userID], 0, 1)) as `is_owner` FROM `boards` WHERE `id` = {$boardID} AND `boards`.`status` = 'active'";
		// $sql = "SELECT `id`, `userID`, `title`, `is_private`, `color`, `created_time` FROM `boards` WHERE `id` = {$boardID} AND `boards`.`status` = 'active'";

		$result = $GLOBALS['db']->mysqli->query($sql);
		if ($result->num_rows === 0) {
			return false;
		}
		return $result->fetch_assoc();
	}

	public function getBoardOwnerID($boardID){
		$sql = "SELECT `userID` FROM `boards` WHERE `id` = {$boardID} AND `boards`.`status` = 'active'";

		$result = $GLOBALS['db']->mysqli->query($sql);
		if ($result->num_rows === 0) {
			return false;
		}
		return $result->fetch_assoc()['userID'];
	}

	public function createTask($board_data){
		$fields = "`" . implode(array_keys($board_data), "`,`") . "`";
		$values = "'" . implode(array_values($board_data), "','") . "'";

		$sql = "INSERT INTO `boardtasks` ({$fields}) VALUES ({$values})";
		$GLOBALS['db']->mysqli->query($sql);
		return $GLOBALS['db']->mysqli->insert_id;
	}

	public function changeTaskField($taskID, $field, $value){
		$sql = "UPDATE `boardtasks`
					SET `{$field}` = '{$value}'
					WHERE `id` = {$taskID};";
		$result = $GLOBALS['db']->mysqli->query($sql);
		if ($result) {
			$sql = "SELECT `{$field}`
						FROM `boardtasks` 
						WHERE `id` = {$taskID}";
			$result = $GLOBALS['db']->mysqli->query($sql);
			if ($result->num_rows === 0) {
				return false;
			}

			return $result->fetch_assoc();
		}
		return false;
	}

	public function changeCardField($cardID, $field, $value){
		$sql = "UPDATE `boardcards`
					SET `{$field}` = '{$value}'
					WHERE `id` = {$cardID};";
		$result = $GLOBALS['db']->mysqli->query($sql);
		if ($result) {
			$sql = "SELECT `{$field}`
						FROM `boardcards` 
						WHERE `id` = {$cardID}";
			$result = $GLOBALS['db']->mysqli->query($sql);
			if ($result->num_rows === 0) {
				return false;
			}

			return $result->fetch_assoc();
		}
		return false;
	}

	public function changeBoardField($boardID, $field, $value){
		$sql = "UPDATE `boards`
					SET `{$field}` = '{$value}'
					WHERE `id` = {$boardID} AND `boards`.`status` = 'active';";
		$result = $GLOBALS['db']->mysqli->query($sql);
		if ($result) {
			$sql = "SELECT `{$field}`
						FROM `boards` 
						WHERE `id` = {$boardID} AND `boards`.`status` = 'active'";
			$result = $GLOBALS['db']->mysqli->query($sql);
			if ($result->num_rows === 0) {
				return false;
			}

			return $result->fetch_assoc();
		}
		return false;
	}

	public function addComment($taskID, $comment){
		$time = time();
		$sql = "INSERT INTO `boardcomments`
					(`userID`,`taskID`, `comment`, `time`)
				VALUES
					({$_SESSION['userID']},{$taskID},'{$comment}','{$time}')";
		$result = $GLOBALS['db']->mysqli->query($sql);
		if ($result) {
			return true;
		}
		return false;
	}

	public function loadComments($taskID){
		$sql = "SELECT `boardcomments`.`id`, `login`, `fullName`, `comment`, `time`
				FROM users
				LEFT JOIN boardcomments ON boardcomments.userID = users.id
				WHERE `boardcomments`.`taskID` = {$taskID}
				ORDER BY `boardcomments`.`id` DESC";

		$result = $GLOBALS['db']->mysqli->query($sql);
		if ($result->num_rows === 0) {
			return false;
		}

		$comments = array();

		while($row = $result->fetch_assoc()){
			array_push($comments, $row);
		}

		return $comments;
	}

	public function isParticipant($boardID, $new_userID){
		$sql = "SELECT `id` 
				FROM `boardparticipants` 
				WHERE `boardID` = {$boardID} AND `userID` = {$new_userID}";
		// echo $sql;
		// exit();

		$result = $GLOBALS['db']->mysqli->query($sql);
		if ($result->num_rows === 0) {
			return false;
		}
		return $result->fetch_assoc();
	}

	public function addParticipant($boardID, $new_userID){
		$sql = "INSERT INTO `boardparticipants`
					(`boardID`,`userID`)
				VALUES
					({$boardID}, {$new_userID})";
		// echo $sql;
		// exit();

		$result = $GLOBALS['db']->mysqli->query($sql);
		if ($result) {
			return true;
		}
		return false;
	}

	public function loadParticipants($boardID){
		$sql = "SELECT `users`.`id`,`login`, `fullName`
				FROM users
				LEFT JOIN boardparticipants ON boardparticipants.userID = users.id
				WHERE `boardparticipants`.`boardID` = {$boardID}
				ORDER BY `boardparticipants`.`id` DESC";

		$result = $GLOBALS['db']->mysqli->query($sql);
		if ($result->num_rows === 0) {
			return false;
		}

		$cards = array();

		while($row = $result->fetch_assoc()){
			array_push($cards, $row);
		}

		return $cards;
	}

	public function addAction($boardID, $type, $actionID){
		$time = time();
		$sql = "INSERT INTO `boardactions`
					(`boardID`, `userID`, `actionType`, `actionID`, `time`)
				VALUES
					({$boardID}, {$_SESSION['userID']}, '{$type}', {$actionID}, {$time})";
		$result = $GLOBALS['db']->mysqli->query($sql);
		if ($result) {
			return true;
		}
		return false;
	}

	public function getActions($boardID){
		$sql = "SELECT `id`,`boardID`,`userID`,`actionType`,`actionID`,`time`
				FROM `boardactions`
				WHERE `boardID` = {$boardID}
				ORDER BY `id` DESC LIMIT 0, 100
				";

		$result = $GLOBALS['db']->mysqli->query($sql);
		if ($result->num_rows === 0) {
			return false;
		}

		$actions = array();

		while($row = $result->fetch_assoc()){
			array_push($actions, $row);
		}

		return $actions;
	}

	public function getLastActionID($boardID){
		$sql = "SELECT `id`
				FROM `boardactions`
				WHERE `boardID` = {$boardID}
				ORDER BY `id` DESC LIMIT 1";

		$result = $GLOBALS['db']->mysqli->query($sql);
		if ($result->num_rows === 0) {
			return false;
		}
		return $result->fetch_assoc()['id'];
	}

	public function removeBoard($boardID){
		$sql = "UPDATE `boards`
					SET `status` = 'removed'
					WHERE `id` = {$boardID}";
		
		return !!$GLOBALS['db']->mysqli->query($sql);
	}

	public function removeCard($cardID){
		$sql = "UPDATE `boardcards`
					SET `status` = 'removed'
					WHERE `id` = {$cardID}";
		
		return !!$GLOBALS['db']->mysqli->query($sql);
	}

	public function removeTask($taskID){
		$sql = "UPDATE `boardtasks`
					SET `status` = 'removed'
					WHERE `id` = {$taskID}";
		
		return !!$GLOBALS['db']->mysqli->query($sql);
	}
}