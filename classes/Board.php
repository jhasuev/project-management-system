<?php
class Board {
	public $mysqli;

	public function create($board_data){
		$fields = "`" . implode(array_keys($board_data), "`,`") . "`";
		$values = "'" . implode(array_values($board_data), "','") . "'";

		$sql = "INSERT INTO `boards` ({$fields}) VALUES ({$values})";
		$GLOBALS['db']->mysqli->query($sql);
		return $GLOBALS['db']->mysqli->insert_id;
	}

	public function getBoardsList($userID, $fields){
		$fields = "`" . implode($fields, "`,`") . "`";

		$sql = "SELECT {$fields} FROM `boards` WHERE `userID` = {$userID} ORDER BY `id` DESC";

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
}