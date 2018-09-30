<?php

/*
$loginvar = ['uname', 'psw'];
$signupvar = ['name', 'email', 'verificationCode', 'psw', 'psw-repeat'] ;
*/



session_start();

include_once "show_query_result.php";
include_once "tablename_var.php";
include_once "config.php";

$infocolumns = ["email", "username", "password"];

$conn = connect_to_server($servername, $username, $password); // debug == false
mysqli_select_db($conn, $dbname);                      /* Connect to database */

$fromtype = $_POST['fromtype'];

// echo $fromtype;		

if ($fromtype == "login"){
// login page	
    
    $email_form = $_POST['uname'];
	$sql = "SELECT password FROM $infotablename 
			WHERE email='$email_form' ;";

    $result = $conn->query($sql);
	if ($result->num_rows > 0) {
		$psws = array();
		while($row = $result->fetch_assoc()) {
		 	array_push($psws, $row['password']);
		}
		if (in_array($_POST['psw'], $psws)){
			$_SESSION['user_email'] = $email_form;
		    header('Location: '. $rootpath . $board );
		    // no message, login success.
		} else {
		    header('Location: '. $rootpath . $face . '?message=1');
		    // message = 1, login failed.
		}
	} else {
	     header('Location: '. $rootpath . $face . '?message=4');
	     // message = 4, username not registered.
	}

} else if ($fromtype == "signup") {
// signup page

    $email_form = $_POST['email'];
	$username_form = $_POST['name'];
    $password_form = $_POST['psw'];

    $sql = "SELECT * FROM $infotablename 
    		WHERE email='$email_form' ;";

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        header('Location: '. $rootpath . $face . '?message=3');
        // message = 3; sign up failure, email already exists. 
	} else {
    	$sql = "INSERT INTO `$infotablename` (email, username, password) 
    			VALUES ( '$email_form', '$username_form', '$password_form');";
        $result = $conn->query($sql);
        header('Location: '. $rootpath . $face . '?message=2');
        // message = 2, sign up success.
	}

} else {

}


$conn->close();

?>