<?php
	if(!isset($_COOKIE["token"])){ header('Location: http://localhost/administrationclient/');}
	$token = $_COOKIE["token"];
	$headers = array(
    'Content-Type: application/json',
    'Authorization: '. $token
);
$curl = curl_init();
curl_setopt($curl,CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_URL, "http://localhost:3000/api/");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$output = curl_exec($curl);
$decoded =json_decode($output);
?>
<button>logout</button>
<table>
    <tr>
      <th>Name</th>
      <th>Email</th>
      <th>Birthday</th>
      <th>telephone</th>
      <th>number of people</th>
      <th>Date</th>
      <th>extra information</th>
      <th></th>
      <th></th>

    </tr>
	<?php
	
include_once 'config.php';
	foreach($decoded as $value){
		echo "<tr>
      <td>".$value->name."</td>
      <td>".$value->email."</td>
      <td>".$value->birthday->day->low."/".$value->birthday->month->low."/".$value->birthday->year->low."</td>
      <td>".$value->tel."</td>
      <td>".$value->amount."</td>
      <td>".$value->startdate->day->low."/".$value->startdate->month->low."/".$value->startdate->year->low."-".$value->enddate->day->low."/".$value->enddate->month->low."/".$value->enddate->year->low."</td>
      <td>".$value->comments."</td>
      <td><form method='post'>
	  <input type='hidden' name='name' value='".$value->name."'>
	  <input type='hidden' name='email' value='".$value->email."'>
	  <input type='hidden' name='datestart' value='".$value->startdate->year->low."-".$value->startdate->month->low."-".$value->startdate->day->low."'>
	  <input type='hidden' name='dateend' value='".$value->enddate->year->low."-".$value->enddate->month->low."-".$value->enddate->day->low."'>
	  <input type='hidden' name='roomnr' value='".$value->roomnr."'>
	  <input type='submit' name='accept' value='accepted'>
	  <input type='submit' name='denied' value='denied'>
	  </form> </td>
    </tr>";
	}
	class reservation{
		
	}
	if(isset($_POST['accept'])){
		$res =new reservation();
		$res->state=$_POST['accept'];
		$res->name=$_POST['name'];
		$res->email=$_POST['email'];
		$res->startdate=$_POST['datestart'];
		$res->enddate=$_POST['dateend'];
		$res->roomnr=$_POST['roomnr'];
		curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($res));
	curl_setopt($curl, CURLOPT_URL, "http://".$base_api."/api/state");
	
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
   $result = curl_exec($curl);
   $resultobject= json_decode($result);
   echo "<script type='text/javascript'>alert('$result');</script>";
	}
	if(isset($_POST['denied'])){
		$res =new reservation();
		$res->state=$_POST['denied'];
		$res->name=$_POST['name'];
		$res->email=$_POST['email'];
		$res->startdate=$_POST['datestart'];
		$res->enddate=$_POST['dateend'];
		$res->roomnr=$_POST['roomnr'];
		curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($res));
	curl_setopt($curl, CURLOPT_URL, "http://".$base_api."/api/state");
	
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
   $result = curl_exec($curl);
   $resultobject= json_decode($result);
   echo "<script type='text/javascript'>alert('$result');</script>";
	}
	?>
  </table>