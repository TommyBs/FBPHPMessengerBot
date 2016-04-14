<?php
$token = 'XXXXXXXXXXXX'; // Your access token here
$verify_token ='xxxxxxxxxx'; // Your verify token here

//This first part of the code handles the initial verification
if(isset($_GET['hub_verify_token'])){
	if($verify_token ==  $_GET['hub_verify_token']){
		echo $_GET['hub_challenge'];
	}
}
	

// Handle the post request when a message is received
if(isset($_POST)){

	$json = file_get_contents('php://input');                   
	$payload = json_decode($json, true);
	$entry = $payload['entry'];
	$messaging_events = $entry[0]['messaging'];

	for($i = 0 ; $i < count($messaging_events); $i++){
		$event = $entry[0]['messaging'][$i];
		$sender = $event['sender']['id'];
		if($event['message'] && $event['message']['text']){
			$text = $event['message']['text'];                     
			$data_string = "recipient=".json_encode( ["id"=>$sender]); 
			$data_string.= "&message=".json_encode( ["text"=>"text received $text"]);          
			$data_string.="&access_token=".$token;
			$data_string.="&format=json";                                                      
			$ch = curl_init('https://graph.facebook.com/v2.6/me/messages');
			curl_setopt($ch, CURLOPT_POST, 1 );
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);               
			$result = curl_exec($ch);
		}//event
	}//for
}