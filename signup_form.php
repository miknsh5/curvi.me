<?php
//email signup ajax call
if($_GET['action'] == 'signup'){
	
	mysql_connect('mysql.curvi.me','miknsh5','bogofish');  
	mysql_select_db('curvi_signup');
	
	//sanitize data
	$email = mysql_real_escape_string($_POST['signup-email']);
	
	//validate email address - check if input was empty
	if(empty($email)){
		$status = "error";
		$message = "You did not enter an email address!";
	}
	else if(!preg_match('/^[^\W][a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,4}$/', $email)){ //validate email address - check if is a valid email address
			$status = "error";
			$message = "You have entered an invalid email address!";
	}
	else {
		$existingSignup = mysql_query("SELECT * FROM signups WHERE signup_email_address='$email'");   
		if(mysql_num_rows($existingSignup) < 1){
			
			$date = date('Y-m-d');
			$time = date('H:i:s');
			
			$insertSignup = mysql_query("INSERT INTO signups (signup_email_address, signup_date, signup_time) VALUES ('$email','$date','$time')");
			if($insertSignup){ //if insert is successful
				$status = "success";
				$message = "You have been signed up!";	
			}
			else { //if insert fails
				$status = "error";
				$message = "Ooops, Theres been a technical error!";	
			}
		}
		else { //if already signed up
			$status = "error";
			$message = "This email address has already been registered!";
		}
	}
	
	//return json response
	$data = array(
		'status' => $status,
		'message' => $message
	);
	
	echo json_encode($data);
	exit;
}
?>