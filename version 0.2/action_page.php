<?php

$DEBUG = False; 
include_once "show_query_result.php";
include_once "tablename_var.php";
include_once "config.php";

$infocolumns = ["username", "password"];

$conn = connect_to_server($servername, $username, $password); // debug == false
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
		    header('Location: '.$rootpath.'index.php?username='.$uname);
		} else {
		    header('Location: '.$rootpath.'login_and_signup.html?message=1');
		    // message = 1, login failed.
		}
	} else {
	     header('Location: '.$rootpath.'login_and_signup.html?message=4');
	     // message = 4, username not registered.
	}

} else if ($fromtype == "signup") {
// signup page, record info of name, email and psw and send to db

    $uname = $_POST['email'];  //this is email address
    $psw = $_POST['psw'];
    $name = $_POST['name'] //this is account name
    
    $sql = "SELECT * FROM $infotablename WHERE username='$uname' ;";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        header('Location: '.$rootpath.'login_and_signup.html?message=3');
        // message = 3; sign up failure 
	} else {
    	$sql = "INSERT INTO `$infotablename` (username, password) VALUES ( '$uname', '$psw');";
        $result = $conn->query($sql);
        header('Location: '.$rootpath.'login_and_signup.html?message=2');
        // message = 2, sign up success.
	}

   



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