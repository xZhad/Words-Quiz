<?php

require_once '../include/DbHandler.php';
require '.././libs/Slim/Slim.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

$user_id = NULL;


function verifyRequiredParams($required_fields) {
	$error = false;
	$error_fields = "";
	$request_params = array();
	$request_params = $_REQUEST;

	if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
		$app = \Slim\Slim::getInstance();
		parse_str($app->request()->getBody(), $request_params);
	}

	foreach ($required_fields as $field) {
		if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
			$error = true;
			$error_fields .= $field . ', ';
		}
	}

	if ($error) {
		$response = array();
		$app = \Slim\Slim::getInstance();
		$response["error"] = true;
		$response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
		echoRespnse(400, $response);
		$app->stop();
	}
}

function validateEmail($email) {
	$app = \Slim\Slim::getInstance();
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$response["error"] = true;
		$response["message"] = 'Email address is not valid';
		echoRespnse(400, $response);
		$app->stop();
	}
}

/**
* Echoing json response to client
* @param String $status_code Http response code
* @param Int $response Json response
*/
function echoRespnse($status_code, $response) {
	$app = \Slim\Slim::getInstance();

	$app->status($status_code);

	$app->contentType('application/json');

	echo json_encode($response);
}

/**
* User Registration
* url - /register
* method - POST
* params - name, email, password
*/
$app->post('/register', function() use ($app) {

	verifyRequiredParams(array('name', 'email', 'password'));

	$response = array();

	$name = $app->request->post('name');
	$email = $app->request->post('email');
	$password = $app->request->post('password');

	validateEmail($email);

	$db = new DbHandler();
	$res = $db->createUser($name, $email, $password);

	if ($res == USER_CREATED_SUCCESSFULLY) {
		$response["error"] = false;
		$response["message"] = "You are successfully registered";
		echoRespnse(201, $response);
	} else if ($res == USER_CREATE_FAILED) {
		$response["error"] = true;
		$response["message"] = "Oops! An error occurred while registereing";
		echoRespnse(200, $response);
	} else if ($res == USER_ALREADY_EXISTED) {
		$response["error"] = true;
		$response["message"] = "Sorry, this email already existed";
		echoRespnse(200, $response);
	}
});

/**
* User Login
* url - /login
* method - POST
* params - email, password
*/
$app->post('/login', function() use ($app) {

	verifyRequiredParams(array('username', 'password'));

	$username = $app->request()->post('username');
	$password = $app->request()->post('password');
	$response = array();

	$db = new DbHandler();

	if ($db->checkLogin($username, $password)) {

		$user = $db->getUserByUsername($username);

		if ($user != NULL) {
			$response["error"] = false;
			$response['user_id'] = $user['user_id'];
			$response['username'] = $user['username'];
			$response['api_key'] = $user['api_key'];
			session_start();
			$_SESSION['user_id'] = $user['user_id'];
			$_SESSION['username'] = $user['username'];
			$_SESSION['api_key'] = $user['api_key'];
		} else {
			// unknown error occurred
			$response['error'] = true;
			$response['message'] = "An error occurred. Please try again";
		}
	} else {
		// user credentials are wrong
		$response['error'] = true;
		$response['message'] = 'Login failed. Incorrect credentials';
	}

	echoRespnse(200, $response);
});

/**
* User Logout
* url - /logout
* method - POST
*/
$app->post('/logout', function() use ($app) {
	session_start();
	session_unset();
	session_destroy();
	$response['error'] = false;
	echoRespnse(200, $response);
});

/**
* Adding Middle Layer to authenticate every request
* Checking if the request has valid api key in the 'Auth' header
*/
function authenticate(\Slim\Route $route) {
	$headers = apache_request_headers();
	$response = array();
	$app = \Slim\Slim::getInstance();

	if (isset($headers['Auth'])) {
		$db = new DbHandler();

		$api_key = $headers['Auth'];

		if (!$db->isValidApiKey($api_key)) {
			$response["error"] = true;
			$response["message"] = "Access Denied. Invalid Api key";
			echoRespnse(401, $response);
			$app->stop();
		} else {
			global $user_id;
			$user = $db->getUserId($api_key);
			if ($user != NULL)
				$user_id = $user["id"];
		}
	} else {
		$response["error"] = true;
		$response["message"] = "Api key is misssing";
		echoRespnse(400, $response);
		$app->stop();
	}
}

/**
* Creating new word in db
* method POST
* params - statement, correct, wrong
* url - /words/
*/
$app->post('/words', 'authenticate', function() use ($app) {
	verifyRequiredParams(array('statement', 'correct', 'wrong'));

	session_start();
	$statement = $app->request()->post('statement');
	$correct = $app->request()->post('correct');
	$wrong = $app->request()->post('wrong');

	$response = array();
	$db = new DbHandler();

	$result = $db->createWord($_SESSION['user_id'], $statement, $correct, $wrong);

	if ($result != NULL) {
		$response["error"] = false;
		$response["message"] = "Word created successfully";
		$response["data"] = $result;
	} else {
		$response["error"] = true;
		$response["message"] = "Failed to create word. Please try again";
	}

	echoRespnse(201, $response);
});

/**
* Listing all words
* method GET
* url /words
*/
$app->get('/words', 'authenticate', function() {
	$response = array();
	$db = new DbHandler();

	$result = $db->getAllWords();

	$response["error"] = false;
	$response["data"] = array();

	foreach ($result as $key => $val) {
		array_push($response["data"], $val);
	}

	echoRespnse(200, $response);
});

/**
* Listing single word
* method GET
* url /words/id
* Will return 404 if the word doesn't exists
*/
$app->get('/words/:id', 'authenticate', function($word_id) {
	$response = array();
	$db = new DbHandler();

	$result = $db->getWord($word_id);

	if ($result != NULL) {
		$response["error"] = false;
		$response["data"] = $result;
		echoRespnse(200, $response);
	} else {
		$response["error"] = true;
		$response["message"] = "The requested resource doesn't exists";
		echoRespnse(404, $response);
	}
});

/**
* Updating existing word
* method PUT
* params word_id
* url - /words/id
*/
$app->put('/words/:id', 'authenticate', function($word_id) use($app) {
	verifyRequiredParams(array('statement', 'correct', 'wrong'));

	session_start();
	$statement = $app->request()->post('statement');
	$correct = $app->request()->post('correct');
	$wrong = $app->request()->post('wrong');

	$response = array();
	$db = new DbHandler();

	$result = $db->updateWord($_SESSION['user_id'], $word_id, $statement, $correct, $wrong);

	if ($result != NULL) {
		$response["error"] = false;
		$response["message"] = "Word updated successfully";
		$response["data"] = $result;
	} else {
		$response["error"] = true;
		$response["message"] = "Word failed to update. Please try again";
	}

	echoRespnse(200, $response);
});

/**
* Deleting word
* method DELETE
* url /words/id
*/
$app->delete('/words/:id', 'authenticate', function($word_id) use($app) {
	$response = array();
	$db = new DbHandler();

	$result = $db->deleteWord($word_id);

	if ($result != NULL) {
		$response["error"] = false;
		$response["message"] = "Word deleted successfully";
		$response["data"] = $result;
	} else {
		$response["error"] = true;
		$response["message"] = "Word failed to be deleted. Please try again";
	}

	echoRespnse(200, $response);
});

/**
* Game Words
*/
$app->get('/game/words', function() {
	$response = array();
	$db = new DbHandler();

	$result = $db->gameWords();

	$response["error"] = false;
	$response["data"] = array();

	foreach ($result as $key => $val) {
		array_push($response["data"], $val);
	}

	echoRespnse(200, $response);
});

$app->run();
?>