<?php  
// All in one. 
set_time_limit(30);
$time_start = microtime(true);// Testing script runing time. 
// sleep(1);

include_once "config.php";
include_once "utility_function.php";
include_once "show_query_result.php";
include_once "show_input_form.php";
 
$page = $_SERVER['PHP_SELF'];
$sec = "300";
echo '
<html>
	<head>
		<meta http-equiv="refresh" content="'. $sec .' URL=\''.$page.'?username='.$_GET['username'].'\'">
		<link rel="stylesheet" href="table.css">
		<h1>' . $heading . '</h1>
	</head>
<body>
	<script type="text/javascript" src="./sharicknote.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<p style="text-align: center;">The website is refreshed every '.$sec.' seconds. You may press F5 to get most up to date result.</p>';

$conn = connect_to_server($servername, $username, $password); // debug in cofig.php
mysqli_select_db($conn, $dbname);                      /* Connect to database */

/* only difference */

$sql = "SELECT * FROM $datatablename WHERE 0;";
$result = $conn->query($sql);
$columns = _fetch_fields($result);

// print_r($columns);

/* handle drop or insert action */
if (array_key_exists("database_action", $_POST)){
	action_on_row_if_ok($conn, $columns, $datatablename, $_POST["database_action"]);
}

/* print new result and new form. */
echo input_form_by_name_attribute($columns, ("index.php?username=".$_GET['username']), $datatablename);/* Input form */

/* customized column grab for database. */
$ca = array();
foreach($columns as $v){
    if ($v == "SubmitTime" and False){
        array_push($ca, "CONVERT_TZ(`SubmitTime`,'+00:00','-04:00') AS SubmitTime"); // convert to U.S. timezone.
    }else{
        array_push($ca, "$v");         
    }
}
$ca = join(",", $ca);


// echo "<div>
//   <table style=\"float: left\">
//     <tr>
//       <td>..</td>
//     </tr>
//   </table>
//   <table style=\"float: left\">
//     <tr>
//       <td>..</td>
//     </tr>
//   </table>
// </div>";

echo "<div>";
$sql = "SELECT $ca FROM `$datatablename` WHERE Type='ask' ORDER BY SubmitTime DESC";
echo show_query_result_html_table($conn, $sql, "Ask $datatablename");

// echo p(2);

$sql = "SELECT $ca FROM `$datatablename` WHERE Type='offer' ORDER BY SubmitTime DESC";
echo show_query_result_html_table($conn, $sql, "Offer $datatablename");
echo "</div>";




// 
// Step 5: Leave database
// 
$conn->close();


// Testing script runing time. 
$time_end = microtime(true);
$time = $time_end - $time_start;
echo "<br>Process Time: {$time}";


?>


