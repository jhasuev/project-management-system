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
				WHERE `boards`.`userID` = {$userID} OR `boardparticipants`.`userID` = {$userID}
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
		$sql = "SELECT `title` FROM `boards` WHERE `id` = {$boardID}";

		$result = $GLOBALS['db']->mysqli->query($sql);
		if ($result->num_rows === 0) {
			return false;
		}
		return $result->fetch_assoc()['title'];
	}

	public function isBoardPrivate($boardID){
		$sql = "SELECT `is_private` FROM `boards` WHERE `id` = {$boardID}";

		$result = $GLOBALS['db']->mysqli->query($sql);
		if ($result->num_rows === 0) {
			return false;
		}
		return $result->fetch_assoc()['is_private'];
	}

	public function getCards($boardID){
		$sql = "SELECT `id`,`title` FROM `boardcards` WHERE `boardID` = {$boardID}";

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

	public function createCard($board_data){
		$fields = "`" . implode(array_keys($board_data), "`,`") . "`";
		$values = "'" . implode(array_values($board_data), "','") . "'";

		$sql = "INSERT INTO `boardcards` ({$fields}) VALUES ({$values})";
		$GLOBALS['db']->mysqli->query($sql);
		return $GLOBALS['db']->mysqli->insert_id;
	}

	public function getTasks($boardID){
		$sql = "SELECT `id`,`cardID`,`title`,`deadline`, (IF(`checkList` != '', 1, '')) as `checkList`
						FROM `boardtasks` 
						WHERE `boardID` = {$boardID} 
						ORDER BY `id` DESC";

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
		$sql = "SELECT `id`, `userID`, `title`, `is_private`, `color`, `created_time`, (IF(`userID` != $_SESSION[userID], 0, 1)) as `is_owner` FROM `boards` WHERE `id` = {$boardID}";
		// $sql = "SELECT `id`, `userID`, `title`, `is_private`, `color`, `created_time` FROM `boards` WHERE `id` = {$boardID}";

		$result = $GLOBALS['db']->mysqli->query($sql);
		if ($result->num_rows === 0) {
			return false;
		}
		return $result->fetch_assoc();
	}

	public function getBoardOwnerID($boardID){
		$sql = "SELECT `userID` FROM `boards` WHERE `id` = {$boardID}";

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

	public function changeBoardField($boardID, $field, $value){
		$sql = "UPDATE `boards`
					SET `{$field}` = '{$value}'
					WHERE `id` = {$boardID};";
		$result = $GLOBALS['db']->mysqli->query($sql);
		if ($result) {
			$sql = "SELECT `{$field}`
						FROM `boards` 
						WHERE `id` = {$boardID}";
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
}