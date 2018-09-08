<?php

$DEBUG = True; 
include "utility_function.php";
include "tablename_var.php";

// $infoservername = "localhost";
// $infousername = "id6885213_sx243";
// $infopassword = "asdf1234";
// $infodbname = "id6885213_user_info";
$infotablename = "user_info";

// $conn = connect_to_server($infoservername, $infousername, $infopassword, True); // debug == false
// mysqli_select_db($conn, $infodbname);                      /* Connect to database */
$conn = connect_to_server($servername, $username, $password, True); // debug == false
mysqli_select_db($conn, $dbname);                      /* Connect to database */

$fromtype = $_POST['fromtype'];
if ($DEBUG){	echo $fromtype;		}

if ($fromtype == "login"){
// login page	
	$sql = "SELECT password FROM $infotablename WHERE username=;";

} else if ($fromtype == "signup") {
// signup page



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