<form action="" method="post">
    <div>
      <label><b>Username</b></label>
      <input name="username" type="text" placeholder="Enter Username" required>
  
      <label><b>Password</b></label>
      <input name="password" type="password" placeholder="Enter Password"required>
          
      <button type="submit" name="submit">Login</button>
    </div>
  </form>
  <?php 
  include_once 'config.php';
class user{
	public $password;
	public $username;
}
if(isset($_POST['submit']))
{
   $retrievedata=new user();
$retrievedata->username = $_POST["username"];
$retrievedata->password = $_POST["password"];
$curl = curl_init();
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($retrievedata));
	curl_setopt($curl, CURLOPT_URL, "http://".$base_api."/api/admin/login");
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
   ));
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
   $result = curl_exec($curl);
   $resultobject= json_decode($result);
   if($resultobject->message =="login succesvol"){
	   setcookie("token",$resultobject->token);
	   header('Location: http://localhost/administrationclient/reservationmanagement.php');
   }else{
	   echo "<script type='text/javascript'>alert('$resultobject->message');</script>";
}
}
?>