<?php

$DEBUG = False; 
include "utility_function.php";
include "tablename_var.php";

// $infoservername = "localhost";
// $infousername = "id6885213_sx243";
// $infopassword = "asdf1234";
// $infodbname = "id6885213_user_info";
$infotablename = "user_info";
$infocolumns = ["username", "password"];

// $conn = connect_to_server($infoservername, $infousername, $infopassword, True); // debug == false
// mysqli_select_db($conn, $infodbname);                      /* Connect to database */
$conn = connect_to_server($servername, $username, $password, False); // debug == false
mysqli_select_db($conn, $dbname);                      /* Connect to database */

$fromtype = $_POST['fromtype'];
if ($DEBUG){	echo $fromtype;		}

if ($fromtype == "login"){
// login page	
    
    $uname = $_POST['uname'];
	$sql = "SELECT password FROM $infotablename WHERE username='$uname' ;";

    $result = $conn->query($sql);
	if ($result->num_rows > 0) {
		$psws = array();
		while($row = $result->fetch_assoc()) {
		 	array_push($psws, $row['password']);
		}
		if (in_array($_POST['psw'], $psws)){
		  //  echo p(1) . "login success!";
		    header('Location: /CURIDE-src/index.php?username='.$uname);
		} else {
		    header('Location: /CURIDE-src/login_and_signup.html?message=1');
		    // message = 1, login failed.
		}
	}

} else if ($fromtype == "signup") {
// signup page
    
    $uname = $_POST['email'];
    $psw = $_POST['psw'];
	$sql = "INSERT INTO `$infotablename` (username, password) VALUES ( '$uname', '$psw');";

    $result = $conn->query($sql);

    header('Location: /CURIDE-src/login_and_signup.html?message=2');
    // message = 2, sign up success.



} else {

}



$sql = "SELECT * FROM $fromwhere WHERE 0;";
$result = $conn->query($sql);
$columns = _fetch_fields($result);






$loginvar = ['uname', 'psw'];
$signupvar = ['email', 'psw', 'psw-repeat'] ;


echo "
<pre>


Copyright Issue.
Sharick Xiang
2017-02-08


</pre>
";

// 
// Step 5: Leave database
// 
$conn->close();


?>