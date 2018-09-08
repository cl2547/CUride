<?php  
// All in one. 
set_time_limit(30);
$time_start = microtime(true);// Testing script runing time. 
// sleep(1);

include "utility_function.php";
include "tablename_var.php";
 
$page = $_SERVER['PHP_SELF'];
$sec = "300";
echo '
<html>
<head>
<meta http-equiv="refresh" content="'. $sec .' URL='.$page.'?username='.$_GET['username'].'">
<link rel="stylesheet" href="table.css">
<h1 style="text-align: center">' . $heading . '</h1>
</head>
<body>
<script type="text/javascript" src="./sharicknote.js"></script>
<script type="text/javascript" src="./tablename_var.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<p style="text-align: center;">The website is refreshed every '.$sec.' seconds. You may press F5 to get most up to date result.</p>';
$conn = connect_to_server($servername, $username, $password, True); // debug == false
mysqli_select_db($conn, $dbname);                      /* Connect to database */

/* only difference */
$fromwhere = "";
if (!(array_key_exists("fromwhere", $_GET))){
	$fromwhere = "board";
} else {
	$fromwhere = $_GET["fromwhere"];
}
$sql = "SELECT * FROM $fromwhere WHERE 0;";
$result = $conn->query($sql);
$columns = _fetch_fields($result);




/* handle drop or insert action */
if (array_key_exists("database_action", $_POST)){
	action_on_row_if_ok($conn, $columns, $fromwhere, $_POST["database_action"]);
	echo "database_action key exists";
}

/* change attribute sequence if necessary */
if (array_key_exists("attr_name", $_GET)){
	_modify_tablename_var_js('update', $fromwhere, $_GET['attr_name'], $_GET['left_or_right']);
}

/* handle new column insertion if necessary */
if (array_key_exists("column_action", $_GET)){
	switch ($_GET['column_action']) {
		case 'add':
			$wherea = $_GET['where'];
			$sql = "ALTER TABLE ".$fromwhere." ADD ".$_POST['column_name']." ". $_POST['column_type'];
			if ($_POST['column_type'] == 'VARCHAR'){
				$sql .= "(".$_POST['varchar_n'].")";
			}
			$sql .= " FIRST;";
			query_and_Feedback($conn, $sql, true, true);
			_add_tablename_var_js($fromwhere, $_POST['column_name'], $wherea);
			break;

		case 'erase':
			$name_to_be_erased = $_GET['which'];
			$sql = "ALTER TABLE ".$fromwhere." DROP COLUMN ".$name_to_be_erased.";";
			query_and_Feedback($conn, $sql, true, true);
			_remove_col_tablename_var_js($fromwhere, $name_to_be_erased);
			break;
		
		default:
			break;
	}
}

/* print new result and new form. */
$columns = _line_from_tablename_var_js($fromwhere);
$columns = _line2array_tablename_var_js($columns);
echo input_form_by_name_attribute($columns, $conn, ("index.php?fromwhere=".$fromwhere), $fromwhere);/* Input form */

$sql = "SELECT * FROM $fromwhere WHERE Type='ask'";
echo show_query_result_html_table($conn, $sql, True, False, False, "Ask $fromwhere");

echo p(2);

$sql = "SELECT * FROM $fromwhere WHERE Type='offer'";
echo show_query_result_html_table($conn, $sql, True, False, False, "Offer $fromwhere");

echo p(2); 

$sql = "SELECT * FROM $fromwhere WHERE 1";
echo '<div id="show_form"></div>';
echo show_query_result_html_table($conn, $sql, True, False, False, "All $fromwhere");





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


// Testing script runing time. 
$time_end = microtime(true);
$time = $time_end - $time_start;
echo "<br>Process Time: {$time}";


?>


