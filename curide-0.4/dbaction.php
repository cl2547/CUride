<?php
/**
 * CU Ride project, handling database actions. 
 *
 * Called by _______.php
 *
 * Built upon _______.php
 *
 * PHP version 5
 *
 * @author     Xiang Shiyi, Sharick <sharick@connect.hku.hk>
 * @since      22/12/2016
 */


error_reporting( E_ALL ); 
include_once "config.php";
include_once "utility_function.php";
include_once "tablename_var.php";



$conn = connect_to_server($servername, $username, $password); // debug == false
mysqli_select_db($conn, $dbname);                      /* Connect to database */

/* only difference */

$sql = "SELECT * FROM $datatablename WHERE 1;";
$result = $conn->query($sql);
$columns = _fetch_fields($result);


/* handle drop or insert action */
if (array_key_exists("database_action", $_POST)){
	$sssqqqlll = action_on_row_if_ok($conn, $columns, $datatablename, $_POST["database_action"]);
	$s = $_GET['username'];
	header("Location: ". $rootpath . $board . "?username=$s");
}

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