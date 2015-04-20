<?php

/**
* Class to handle all db operations
* This class will have CRUD methods for database tables
*
* @author Ravi Tamada
*/
class DbHandler {

	private $conn;

	function __construct() {
		require_once dirname(__FILE__) . '/DbConnect.php';
		// opening db connection
		$db = new DbConnect();
		$this->conn = $db->connect();
	}

	/* ------------- `users` table method ------------------ */

	/**
	* Checking user login
	* @param String $username
	* @param String $password
	* @return boolean User login status success/fail
	*/
	public function checkLogin($username, $password) {

		$stmt = $this->conn->prepare("SELECT password_hash FROM users WHERE username = ?");

		$stmt->bind_param("s", $username);

		$stmt->execute();

		$stmt->bind_result($password_hash);

		$stmt->store_result();

		if ($stmt->num_rows > 0) {

			$stmt->fetch();

			$stmt->close();

			if (sha1($password) == $password_hash) {
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			$stmt->close();
			return FALSE;
		}
	}

	/**
	* Checking for duplicate user by username
	* @param String $username
	* @return boolean
	*/
	private function isUserExists($username) {
		$stmt = $this->conn->prepare("SELECT id from users WHERE username = ?");
		$stmt->bind_param("s", $username);
		$stmt->execute();
		$stmt->store_result();
		$num_rows = $stmt->num_rows;
		$stmt->close();
		return $num_rows > 0;
	}

	/**
	* Fetching user by username
	* @param String $username
	*/
	public function getUserByUsername($username) {
		$stmt = $this->conn->prepare("SELECT id_user, username, api_key FROM users WHERE username = ?");
		$stmt->bind_param("s", $username);
		if ($stmt->execute()) {
			$user = array();
			$stmt->bind_result($user['user_id'], $user['username'], $user['api_key']);
			$stmt->fetch();
			$stmt->close();
			return $user;
		} else {
			return NULL;
		}
	}

	/**
	* Fetching user api key
	* @param String $user_id
	*/
	public function getApiKeyById($user_id) {
		$stmt = $this->conn->prepare("SELECT api_key FROM users WHERE id_user = ?");
		$stmt->bind_param("i", $user_id);
		if ($stmt->execute()) {
			$stmt->bind_result($api_key);
			$stmt->fetch();
			$stmt->close();
			return $api_key;
		} else {
			return NULL;
		}
	}

	/**
	* Fetching user id by api key
	* @param String $api_key
	*/
	public function getUserId($api_key) {
		$stmt = $this->conn->prepare("SELECT id_user FROM users WHERE api_key = ?");
		$stmt->bind_param("s", $api_key);
		if ($stmt->execute()) {
			$stmt->bind_result($user_id);
			$stmt->fetch();
			$stmt->close();
			return $user_id;
		} else {
			return NULL;
		}
	}

	/**
	* Validating user api key
	* If the api key is there in db, it is a valid key
	* @param String $api_key user api key
	* @return boolean
	*/
	public function isValidApiKey($api_key) {
		$stmt = $this->conn->prepare("SELECT id_user from users WHERE api_key = ?");
		$stmt->bind_param("s", $api_key);
		$stmt->execute();
		$stmt->store_result();
		$num_rows = $stmt->num_rows;
		$stmt->close();
		return $num_rows > 0;
	}

	/**
	* Generating random Unique MD5 String for user Api key
	*/
	private function generateApiKey() {
		return md5(uniqid(rand(), true));
	}


	/* ------------- `words` table method ------------------ */

	/**
	* Creating new word
	* @param String $user_id
	* @param String $statement
	* @param String $correct
	* @param String $wrong
	*/
	public function createWord($user_id, $statement, $correct, $wrong) {
		$now = new DateTime();
		$datetime = $now->format('Y-m-d H:i:s');
		$stmt = $this->conn->prepare("INSERT INTO words (statement, correct, wrong, datetime, id_user)
										VALUES(?, ?, ?, ?, ?)");
		$stmt->bind_param("ssssi", $statement, $correct, $wrong, $datetime, $user_id);
		$result = $stmt->execute();
		$stmt->close();

		if ($result) {
			$new_word_id = $this->conn->insert_id;
			$stmt = $this->conn->prepare("SELECT words.id_word, words.statement, words.correct, words.wrong, words.datetime, users.username FROM words
											INNER JOIN users ON words.id_user = users.id_user
											WHERE words.id_word = ?");
			$stmt->bind_param("i", $new_word_id);
			if ($stmt->execute()) {
				$word = array();
				$stmt->bind_result(
					$word['id'],
					$word['statement'],
					$word['correct'],
					$word['wrong'],
					$word['datetime'],
					$word['username']
				);
				$stmt->fetch();
				$stmt->close();
				return $word;
			} else {
				return NULL;
			}
		} else {
			return NULL;
		}
	}

	/**
	* Fetching all words
	*/
	public function getAllWords() {
		$stmt = $this->conn->prepare("SELECT words.id_word, words.statement, words.correct, words.wrong, words.datetime, users.username FROM words
										INNER JOIN users ON words.id_user = users.id_user
										ORDER BY words.datetime ASC");
		if ($stmt->execute()) {
			$word = array();
			$words = array();
			$stmt->bind_result(
				$word['id'],
				$word['statement'],
				$word['correct'],
				$word['wrong'],
				$word['datetime'],
				$word['username']
			);
			while ($stmt->fetch()) {
				$words[] = array(
					'id'		=> $word['id'],
					'statement'	=> $word['statement'],
					'correct'	=> $word['correct'],
					'wrong'		=> $word['wrong'],
					'datetime'	=> $word['datetime'],
					'username'	=> $word['username']
				);
			}
			$stmt->close();
			return $words;
		} else {
			return NULL;
		}
	}

	/**
	* Fetching a single word
	* @param String $word_id
	*/
	public function getWord($word_id) {
		$stmt = $this->conn->prepare("SELECT words.id_word, words.statement, words.correct, words.wrong, words.datetime, users.username FROM words
										INNER JOIN users ON words.id_user = users.id_user
										WHERE words.id_word = ?");
		$stmt->bind_param("i", $word_id);
		if ($stmt->execute()) {
			$word = array();
			$stmt->bind_result(
				$word['id'],
				$word['statement'],
				$word['correct'],
				$word['wrong'],
				$word['datetime'],
				$word['username']
			);
			$stmt->fetch();
			$stmt->close();
			return $word;
		} else {
			return NULL;
		}
	}

	/**
	* Updating Word
	* @param String $word_id
	* @param String $statement
	* @param String $correct
	* @param String $wrong
	*/
	public function updateWord($user_id, $word_id, $statement, $correct, $wrong) {
		$now = new DateTime();
		$datetime = $now->format('Y-m-d H:i:s');
		$stmt = $this->conn->prepare("UPDATE words SET
										statement = ?,
										correct = ?,
										wrong = ?,
										datetime = ?,
										id_user = ?
										WHERE id_word = ?");
		$stmt->bind_param("ssssii", $statement, $correct, $wrong, $datetime, $user_id, $word_id);
		$result = $stmt->execute();
		$stmt->close();

		if ($result) {
			$stmt = $this->conn->prepare("SELECT words.id_word, words.statement, words.correct, words.wrong, words.datetime, users.username FROM words
											INNER JOIN users ON words.id_user = users.id_user
											WHERE words.id_word = ?");
			$stmt->bind_param("i", $word_id);
			if ($stmt->execute()) {
				$word = array();
				$stmt->bind_result(
					$word['id'],
					$word['statement'],
					$word['correct'],
					$word['wrong'],
					$word['datetime'],
					$word['username']
				);
				$stmt->fetch();
				$stmt->close();
				return $word;
			} else {
				return NULL;
			}
		} else {
			return NULL;
		}
	}

	/**
	* Deleting a word
	* @param String $word_id id of the word to delete
	*/
	public function deleteWord($word_id) {
		$stmt = $this->conn->prepare("DELETE FROM words
										WHERE words.id_word = ?");
		$stmt->bind_param("i", $word_id);
		$result = $stmt->execute();
		$stmt->close();

		if ($result) {
			return $word_id;
		} else {
			return NULL;
		}
	}

	/**
	* Game Words
	*/
	public function gameWords() {
		$stmt = $this->conn->prepare("SELECT words.id_word, words.statement, words.correct, words.wrong, words.datetime, users.username FROM words
										INNER JOIN users ON words.id_user = users.id_user
										ORDER BY RAND()");
		if ($stmt->execute()) {
			$word = array();
			$words = array();
			$stmt->bind_result(
				$word['id'],
				$word['statement'],
				$word['correct'],
				$word['wrong'],
				$word['datetime'],
				$word['username']
			);
			while ($stmt->fetch()) {
				$words[] = array(
					'id'		=> $word['id'],
					'statement'	=> $word['statement'],
					'correct'	=> $word['correct'],
					'wrong'		=> $word['wrong'],
					'datetime'	=> $word['datetime'],
					'username'	=> $word['username']
				);
			}
			$stmt->close();
			return $words;
		} else {
			return NULL;
		}
	}

}

?>