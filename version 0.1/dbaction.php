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
include "utility_function.php";
include "tablename_var.php";



$conn = connect_to_server($servername, $username, $password, False); // debug == false
mysqli_select_db($conn, $dbname);                      /* Connect to database */

/* only difference */
$fromwhere = "";
if (!(array_key_exists("fromwhere", $_GET))){
	$fromwhere = "board";
} else {
	$fromwhere = $_GET["fromwhere"];
}
$sql = "SELECT * FROM $fromwhere WHERE 1;";
$result = $conn->query($sql);
$columns = _fetch_fields($result);

/* handle drop or insert action */
if (array_key_exists("database_action", $_POST)){
	action_on_row_if_ok($conn, $columns, $fromwhere, $_POST["database_action"]);
	header("Location: /CURIDE-src/index.php?username=asdf");
} else {
    echo "Fatal Internal Server Error! by Sharick.";
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