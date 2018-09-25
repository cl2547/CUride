<?php
/**
 * Utility Functions used in Step 2 and Step 3
 * 
 * Powerful Reference: http://www.w3schools.com/php/
 * 
 *
 * For Step 2 and Step 3
 *
 * PHP version 5
 *
 * @author     Xiang Shiyi, Sharick <sharick@connect.hku.hk>
 * @since      22/12/2016
 */

// include "tablename_var.php";

$DEBUG = False;
include_once "config.php";

function p($intt){ $toReturn="";for($i=0;$i<$intt;$i++){$toReturn.="<br>";} return $toReturn;}

// 
// $columns is all the columns names collected. 
// 
function _add_quotes($symbol, $strr){ return $symbol . $strr . $symbol; }
function _add_quotes_tablescript($strr){ return _add_quotes("`", $strr); }
function _add_quotes_valuescript($strr){ return _add_quotes("'", $strr); }
function _add_quotes_doublescript($strr){ return _add_quotes("\"", $strr); }
function _get_value_post($key){ return $_POST[$key]; }
function column_names($columns){ return join(",", array_map('_add_quotes_tablescript', $columns)); }
function column_values($columns){ 
	$v = array_map('_get_value_post', $columns); 
	return join(",", array_map('_add_quotes_valuescript', $v)); 
}
function action_on_row_if_ok($conn, $columns, $tablename, $action){
	if (0 != count($_POST)){                               /* insert data */
		$sql = "";
		switch ($action) {
			case "insert":
				$sql = "INSERT INTO `".$tablename."` (". column_names($columns) .") VALUES (". column_values($columns) .");";
				break;
			case "drop":
				$c = "";
				foreach ($columns as $key => $value) {
					$c .= "AND " . $value . " = \"" . $_POST[$value] . "\"";
				}
				$sql = "DELETE FROM `".$tablename."` WHERE 1 " . $c;
				break;
			default:
				echo "Default switch. ";
				break;
		}
		query_and_Feedback($conn, $sql, False);
	}else{
		echo "Error in insert_row_if_ok(\$conn, \$columns) function.";
	}
	return $sql;
}


// 
// return an array with field names
// 
function _fetch_fields($result){
	$fieldinfo = mysqli_fetch_fields($result);
	$toReturn = array();
	foreach ($fieldinfo as $value) {
		array_push($toReturn, $value->name);
	}
	return $toReturn;
}

// logs: 
// 20170501 -- When query [CREATE, DROP], update tablename_var.js file at the same time.
// 
// $button is action button;
// $manage is manage hyperlink;
// $userinput is whether show the debug information or not. 
// 
// key from 0 (indicating 1-st word)
function _nth_word($strr, $key){ $t = explode(" ", trim($strr)); return $t[$key]; } // a
function _first_word_with_quotes($strr){ return _add_quotes("\"", _nth_word($strr, '0')); } // "a"
function _remove_quotes($strr){ return trim($strr, "\""); } // "a" => a
function _js_name_col($sql){ 
	$a = strpos($sql, "("); 
	$b = strrpos($sql, ")");
	$_matches = explode(",", substr($sql, $a + 1, $b - $a - 1));
	$_matches = array_map('_first_word_with_quotes', $_matches);
	return join(",", $_matches);
}


function show_js_content($filepath){
	$toReturn = "<br><hr><h4>$filepath</h4><ol>";
	$t = file_get_contents($filepath);
	$lines = explode("\n",$t);
	foreach($lines as $line){ $line = trim($line); $toReturn .= "<li>$line</li>"; }
	$toReturn .= "</ol><hr><br>";
	return $toReturn;
}


/* 2017/08/13 */
/* multiple tablename_var.js functions see tablename_var.php */


/* return an integer */
function getMaxSharickID($conn, $tablename){
	$sql = "SELECT MAX(SharickID) AS 'max' FROM `".$tablename."`";
	$result = $conn->query($sql);
	if ($result->num_rows > 0){
		$row = $result->fetch_assoc();
		return intval($row['max']);
	}else{
		echo "Error in getMaxSharickID($conn).<br>";
		return None;
	}
}


// 
// Add a query textbox to execute sql statement; One at a time at first. 
// 
function add_sql_query_textbox($string){
	$toReturn = '';
	$toReturn .= '<form action="index.php" method="post"><fieldset><legend><input type="submit" value="Execute SQL" /></legend><br>
	<textarea name="sql_exe_textarea" rows="5" cols="50">'.$string.'</textarea>
	</fieldset></form>';

	// sql result

	$toReturn .= '<div id="sql_result"></div>';
	return $toReturn;
}










/*************************************************************/
/*                   Functions for Step 2                    */
/*************************************************************/

/**
 * query_and_Feedback
 *
 * Make sql-query(s) and debug easier.
 *
 * @param $conn 	-- Connection object.
 * @param $sql  	-- Sql query statement. 
 * @param $detail 	-- Print detailed process if this argument is true AND $DEBUG (config.php) is also true.
 * @return true | false
 */
function query_and_Feedback($conn, $sql, $detail = true){
	if ($conn->query($sql) === TRUE) {
	   // echo $sql . " query success!". p(1);
		return true;
	} else {
	    echo "Error: " . $conn->error;
	    return false;
	}
}


function connect_to_server($servername, $username, $password){
	$conn = new mysqli($servername, $username, $password); /* Connect to Server */
	if ($conn->connect_error) {                            /* Check connection */
	    die("Connection failed: " . $conn->connect_error);
	}
	return $conn;
} 


/**
 * startsWith -- Detect the lines in sql file starting with special characters. 
 *
 * This function is inspired from the website with modification. 
 * It is used to solve the problem that '/*' can not be found by strpos() function. 
 *
 * You are encouraged to write your own. ^_^
 *
 * @param $haystack -- The string to be search.
 * @param $needle  	-- Sql query statement.
 * @return true | false
 *
 * @link http://stackoverflow.com/a/10209702
 */
function startsWith($haystack, $needle){
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

/**
 * run_sql_file -- Remove the lines in sql file starting with special characters.
 * 
 * This function is inspired from the website with modification. 
 *
 * You are encouraged to write your own. ^_^
 *
 * @param $conn 	-- Connection object.
 * @param $location -- Sql query file path.
 * @return array    -- Return number of successful queries and total number of queries found.
 * 
 * @link http://stackoverflow.com/a/10209702
 */
function run_sql_file($conn, $location){
    //load file
    $commands = file_get_contents($location);

    //delete comments
    $lines = explode("\n",$commands);
    $commands = '';
    foreach($lines as $line){
        $line = trim($line);
        if( $line && !startsWith($line,'--')  ){
            $commands .= $line . "\n";
        }
    }

    //convert to array
    $commands = explode(";", $commands);

    //run commands
    $total = $success = 0;
    foreach($commands as $command){
        if(trim($command)){
            $success += (query_and_Feedback($conn, $command)==false ? 0 : 1);
            $total += 1;
        }
    }

    //return number of successful queries and total number of queries found
    return array(
        "success" => $success,
        "total" => $total
    );
}
function _semicolon($strr){ return (count(trim($strr)) == 0) ? "" : trim($strr) . ";"; }
function run_multi_stat_no_comment($conn, $commands){

    $commands = explode(";", $commands);
    $commands = array_map('_semicolon', $commands);

    //run commands
    $total = $success = 0;
    foreach($commands as $command){
        if($command and (count($command) != 0) ){
			$total += 1;
			echo "<br>---------------<br>";
        	$r = show_query_result_html_table($conn, $command, False, False, True, "Result -- $total");
			echo $r;
			$success += (startsWith( $r,"Request Failure!") ? 0 : 1);
        }
    }

    //return number of successful queries and total number of queries found
    return array(
        "success" => $success,
        "total" => $total
    );
}




/*************************************************************/
/*                   Functions for Step 3                    */
/*************************************************************/

/**
 * rowInfo_2_String 
 * 
 * Combine database row information into a string for display. 
 *
 * @param $row 		 -- A particular row from the query result.
 * @param $getheader -- Generate header (i.e. column name) string if true, data if false.
 * @return string    -- Query result or column names.
 */
function rowInfo_2_String($row, $getheader=false){
	$onerow = "";
	if ($getheader){ $row = array_keys($row); }
	$onerow = join(" | ", $row) . "End<br>";
	return $onerow;
}


/**
 * showResultInfo 
 * 
 * Display table result from query. The result should be the same as the one you have from 
 * 		localhost/phpmyadmin 
 *
 * @param $result 	 		-- Query result object.
 * @param $rowslimit 		-- An Integer specifying maximum rows of result to be shown.
 * @param $rowfractionlimit -- An Integer specifying maximum fraction (i.e. 1/$rowfractionlimit) of result rows to be shown. E.g. if $rowfractionlimit == 20, it means a maximum of 1/20 = 0.05 = 5% of the results will be shown. 
 * @return NULL    			-- It prints the result directly to the browswer.
 */
function showResultInfo($result, $rowslimit=20, $rowfractionlimit=1){
	if ($result->num_rows > 0) {
		$ct = 0;
		while($row = $result->fetch_assoc()) {
			if ($ct == 0){
				echo rowInfo_2_String($row, true);
			}
			echo rowInfo_2_String($row);

			$ct += 1;
			if ($ct >= $rowslimit || $ct >= floor( $result->num_rows / $rowfractionlimit) ){
				break;
			}
		}
	}else{
		echo "0 results";
	}	
}


function column_alias($name){
	switch ($name) {
		case "Name":
			return "Name";
			break;
		case "Phone":
			return "Phone";
			break;
		case "Wechat":
			return "Wechat";
			break;
		case "Email":
			return "Email";
			break;
		case "Date":
			return "Date";
			break;
		case "Time":
			return "Time";
			break;
		case "FromCity":
			return "From";
			break;
		case "ToCity":
			return "To";
			break;
		case "Price":
			return "Price";
			break;
		case "NoOfPpl":
			return "People #";
			break;
		case "Type":
			return "Type";
			break;
		case "SubmitTime":
			return "Submited Time";
			break;
		case "Textarea":
			return "Notes";
			break;
		default:
			echo "[Warning] Current Name has no alias." . p(1);
			return $name;
	}
	return $name; 
}


?>