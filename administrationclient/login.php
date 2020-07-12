<?php 
class user{
	public $password;
	public $username;
}
$retrievedata=new user();
$retrievedata->username = $_POST["username"];
$retrievedata->password = $_POST["password"];
$curl = curl_init();
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($retrievedata));
	curl_setopt($curl, CURLOPT_URL, "http://localhost:3000/api/admin/login");
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
   ));
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
   $result = curl_exec($curl);
   $resultobject= json_decode($result);
   if($resultobject->message =="login succesvol"){
	   setcookie("token",$resultobject->token);
	   header('Location: http://localhost/administrationclient/reservationmanagement.html');
   }
?>