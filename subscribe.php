<?php

/**
 * Nirph Online
 *
 * @package  Card Collector subscriptions
 * @author   Tshepo Tema <tshepo927@gmail.com>
 */

include_once("db.php");

class Subscribers extends DB {
	function subscribeUser($data) {
		$username = $data->username;
		$aCurrentUsers = $this->query("SELECT * FROM users WHERE username = '".$username."'");
		if (!empty($aCurrentUsers)) {
			$msg = "Username already exists";
		} else {
			$email = $data->email;
			$pwd = $data->pwd;
			$pwd2 = $data->pwd2;
			$package = $data->package;
			if ($pwd !== $pwd2) {
				$msg = "Passwords do not match";
			} else {
				$aValues = array($username, $email, $pwd);
				$aFields = array("username", "email", "password");
				$this->insertRows("users", $aValues, $aFields);
				$userID = $this->insertID();

				if (!empty($userID)) {
					$aProducts = $this->query("SELECT product_id FROM products WHERE product_name = '".$package."' LIMIT 1");
					$productID = $aProducts[0]['product_id'];
					if (!empty($productID)) {
						$this->query("INSERT INTO `subscriptions` (`subscription_id`, `user_id`, `product_id`, `subscribe_date`) VALUES (NULL, '".$userID."', '".$productID."', CURRENT_TIMESTAMP);");	
						$msg = "Congratulations!!! You have been Successfully Subscribed!";										
					} else {
						$msg = "Product not found";						
					}
				} else {
					$msg = "Failed to insert user record";
				}				
			}
		}
		$response = array("msg" => $msg);
		return json_encode($response);
	}
}

$data = "";
$req = file_get_contents("php://input");

try {
    $data = json_decode($req);
} catch (Exception $ex) {
	$response = array("msg" => "<pre>".print_r($ex,true)."</pre>");
    die(json_encode($response));
}

if (!empty($data)) {
	$subs = new Subscribers();
	print $subs->subscribeUser($data);
} else {
	$response = array("msg" => "Invalid request");
    print json_encode($response);
}

?>