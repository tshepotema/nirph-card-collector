<?php

/**
 * Nirph Online
 *
 * @package  Card Collector serve json
 * @author   Tshepo Tema <tshepo927@gmail.com>
 */

include_once("db.php");

class Serve extends DB {
	function listSubs () {
		$username = $data->username;
		$aSubsAll = $this->query("SELECT S.subscription_id, S.subscribe_date, U.username, U.email FROM subscriptions S LEFT JOIN users U on S.user_id = U.user_id");
		for ($i = 0; $i < Count($aSubsAll); $i++) {
			$response[] = array("sub_id" => $aSubsAll[$i]['subscription_id'], "username" => $aSubsAll[$i]['username'], "email" => $aSubsAll[$i]['email'], "dt" => $aSubsAll[$i]['subscribe_date']);
		}
		return json_encode($response);
	}

	function viewSub ($subID) {
		$username = $data->username;
		$aSubsAll = $this->query("SELECT U.username, U.email, P.product_name, P.price, P.downloads FROM subscriptions S LEFT JOIN users U on S.user_id = U.user_id LEFT JOIN products P ON S.product_id = P.product_id WHERE S.subscription_id = '".$subID."'");
		for ($i = 0; $i < Count($aSubsAll); $i++) {
			$response = array("price" => $aSubsAll[$i]['price'], "downloads" => $aSubsAll[$i]['downloads'], "username" => $aSubsAll[$i]['username'], "email" => $aSubsAll[$i]['email'], "pack" => $aSubsAll[$i]['product_name']);
		}
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
	$subs = new Serve();
	$action = $data->action;
	switch ($action) {
		case 'listSubs':
			print $subs->listSubs();		
			break;
		case 'viewSub':
			print $subs->viewSub($data->id);		
			break;		
		default:
			break;
	}
	
} else {
	$response = array("msg" => "Invalid request");
    print json_encode($response);
}

?>