<?php 
include_once 'config.php';
setCookie("price",$_POST["price"]);
	$retrievedata=json_decode($_COOKIE["object"]);
	$retrievedata->roomnr = $_POST["room"];
	$price =$_COOKIE["price"];
	setCookie('object',json_encode($retrievedata));
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($retrievedata));
	curl_setopt($curl, CURLOPT_URL, "http://".$base_api."/api/checkdate");
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
   ));
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
   
   $result = curl_exec($curl);
   if(!$result){die("Connection Failure");}
   curl_close($curl);
   if($result === "Someone already placed a reservation on those dates"){
	   echo "<script type='text/javascript'>alert('$result');</script>";
	   header('Location: http://localhost/customerclient/roomselection.php');
   }
?>
<head>
<script src="https://js.stripe.com/v3/"></script>
</head>
<body>
<div>
	<h1>Stripe payment Gateway</h1>
	<div>
		<div>
			<h3>Charge <?php echo $price;?></h3>
		</div>
		<div>
			<div id="paymentResponse"></div>
			
			<form action="" method="POST" id="paymentfrm">
				<label>NAME</label>
				<input type="text" name="name" id="name" required value='<?php echo $retrievedata->firstname." ".$retrievedata->lastname;?>'/>
				<label>EMAIL</label>
				<input type="text" name="email" id="email" required value='<?php echo $retrievedata->email;?>'/>
				<label>CARD NUMBER</label>
				<div id="card_number"></div>
				<label>EXPIRY DATE</label>
				<div id="card_expiry"></div>
				<label>CVC CODE</label>
				<div id="card_cvc"></div>
				<button type="submit" id="payBTN">Submit Payment</button>
			</form>
		</div>
	</div>
</div>
<script>
var stripe = Stripe('<?php echo STRIPE_PUBLISHABLE_KEY;?>');
var elements = stripe.elements();
var cardElement= elements.create('cardNumber',{});
cardElement.mount('#card_number');
var exp = elements.create('cardExpiry',{});
exp.mount('#card_expiry');
var cvc = elements.create('cardCvc',{});
cvc.mount('#card_cvc');


var resultContainer = document.getElementById('paymentResponse');
cardElement.addEventListener('change',function(event){
	if (event.error){
		resultContainer.innerHTML='<p>'+event.error.message+'</p>';
	}else{
		resultContainer.innerHTML='';
	}
});
var form = document.getElementById('paymentfrm');
form.addEventListener('submit', function(e){
	e.preventDefault();
	createToken();
});

function createToken(){
	stripe.createToken(cardElement).then(function(result){
		if(result.error){
			resultContainer.innerHTML='<p>'+result.error.message+'</p>';
		}else{
			stripeTokenHandler(result.token);
		}
	});
}

function stripeTokenHandler(token){
	var hiddenInput = document.createElement('input');
	hiddenInput.setAttribute('type','hidden');
	hiddenInput.setAttribute('name','stripeToken');
	hiddenInput.setAttribute('value',token.id);
	form.appendChild(hiddenInput);
	form.submit();
}
</script>
</body>

<?php
require_once 'config.php';
$payment_id= $statusMsg='';
$ordStatus= 'error';
if(!empty($_POST['stripeToken'])){
	$token = $_POST['stripeToken'];
	$name = $_POST['name'];
	$email = $_POST['email'];
	require_once $stripeconnect;
	\Stripe\Stripe::setApiKey(STRIPE_API_KEY);
	try{
		$customer = \Stripe\Customer::create(array(
		'email'=> $email,
		'source'=>$token
		));
	}catch(Exception $e){
		$api_error = $e->getMessage();
	}
	
	if(empty($api_error) && $customer){
		$itemPriceCents = ($price*100);
		
		try{
		$charge =\Stripe\Charge::create(array(
			'customer'=> $customer->id,
			'amount' => $itemPriceCents,
			'currency'=>"EUR",
			'description'=>"Reservering bij hotel"
		));
	}catch(Exception $e){
		$api_error = $e->getMessage();
	}
	
	if(empty($api_error) && $charge){
		$chargeJson = $charge->jsonSerialize();
		
		if($chargeJson['amount_refunded'] == 0 && empty($chargeJson['failure_code']) && $chargeJson['paid'] == 1 && $chargeJson['captured'] == 1){
			$transactionID = $chargeJson['balance_transaction']; 
                $paidAmount = $chargeJson['amount']; 
                $paidAmount = ($paidAmount/100); 
                $paidCurrency = $chargeJson['currency']; 
                $payment_status = $chargeJson['status'];
				
				// Include database connection file 
				//convert to curl request
				$retrievedata->paidAmount=$paidAmount;
				$retrievedata->paidCurrency=$paidCurrency;
				$retrievedata->payment_status=$payment_status;
				
				$curl = curl_init();
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($retrievedata));
	curl_setopt($curl, CURLOPT_URL, "http://".$base_api."/api/");
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
   ));
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
   echo $price;
   
   $result = curl_exec($curl);
   
	   echo "<script type='text/javascript'>alert('$result');</script>";
   if(!$result){die("Connection Failure");}
   curl_close($curl);
   if($result === "succesfully placed reservation"){
	   header('Location: http://localhost/customerclient');
	   mail($retrievedata->email,"Reservation from hotelapp",wordwrap("Succesfully placed reservations on ".$retrievedata->startdate." to "..$retrievedata->enddate." price:".$price." you will hear later if you order is accepted",70));
   } else{
	   echo "<script type='text/javascript'>alert('$result');</script>";
   }
				//include_once 'dbConnect.php';
				if($payment_status == 'succeeded'){ 
                    $ordStatus = 'success'; 
                    $statusMsg = 'Your Payment has been Successful!'; 
                }else{ 
                    $statusMsg = "Your Payment has Failed!"; 
                }
		}else{ 
                $statusMsg = "Transaction has been failed!"; 
            }
	}else{ 
            $statusMsg = "Charge creation failed! $api_error";  
        } 
}else{  
        $statusMsg = "Invalid card details! $api_error";  
    }
}else{ 
    $statusMsg = "Error on form submission."; 
}
?>