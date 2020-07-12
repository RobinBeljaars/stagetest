<?php
class Reservation{
	public $firstname = "";
	public $lastname = "";
	public $email="";
	public $birthday="";
	public $telephone="";
	public $amount="";
	public $startdate="";
	public $enddate="";
	public function createProperty($name, $value){
	$this->{$name} = $value;
	}
}
$reservation = new Reservation();
$reservation->createProperty('firstname',$_POST["firstname"]);
$reservation->createProperty('lastname',$_POST["lastname"]);
$reservation->createProperty('email',$_POST["email"]);
$reservation->createProperty('birthday',$_POST["birthday"]);
$reservation->createProperty('extra',$_POST["comments"]);
$reservation->createProperty('telephone',$_POST["telephone"]);
$reservation->createProperty('amount',$_POST["amount"]);
$reservation->createProperty('startdate',$_POST["startdate"]);
$reservation->createProperty('enddate',$_POST["enddate"]);
setcookie("object",json_encode(get_object_vars($reservation)));
echo json_encode(get_object_vars($reservation));
header('Location: http://localhost/customerclient/roomselection.php')
?>