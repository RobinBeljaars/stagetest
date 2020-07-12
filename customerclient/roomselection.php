<!DOCTYPE html>
<html>
<body>


<form action="roomform.php" method="post">
  <p>Please select your room:</p>
   <?php 
   require_once 'config.php';
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "http://".$base_api."/api/rooms");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$output = curl_exec($curl);
$decoded =json_decode($output);
foreach($decoded as $value){
	echo '<label for='."room".$value->roomnumber->low.'><input type="radio" id='."room".$value->roomnumber->low.' name="room" value='.$value->roomnumber->low.'>Room '.$value->roomnumber->low.' price: '.$value->price.' euro</label><br><input type="hidden" name="price" value='.$value->price.'';
}
?> 
  <input type="button" value="cancel">
  <input type="submit" value="Submit">
</form>

</body>
</html>