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


function p($intt){
	$toReturn = "";
	for ($i=0; $i < $intt; $i++) { 
		$toReturn .= "<br>";
	}
	return $toReturn;
}

// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "board";

$heading = "CU Ride Information Exchange";

$servername = "localhost";
$username = "id6885213_sx243";
$password = "asdf1234";
$dbname = "id6885213_curidedb";

/* modified 2018/09/03 - US EST */



// part 1
function connect_to_server($servername, $username, $password, $debug=True){
	$conn = new mysqli($servername, $username, $password); /* Connect to Server */
	if ($conn->connect_error) {                            /* Check connection */
	    die("Connection failed: " . $conn->connect_error);
	} 
	if ($debug) {
		echo "Connected successfully. <br>";
	}

	return $conn;
} 

// $columns is the all all the coumns names collected. 

// part 2
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
		query_and_Feedback($conn, $sql);
	}else{
		echo "Error in insert_row_if_ok(\$conn, \$columns) function.";
	}
}


// part 3

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
function _buttons($attr_name, $left_or_right=False){
	$toReturn = '';
	$toReturn .= '<button class="leftrightmove" type="button" onclick="move(\''.$attr_name.'\', \'left\');">'.'<'.'</button>';
	$toReturn .= $attr_name;
	$toReturn .= '<button class="leftrightmove" type="button" onclick="move(\''.$attr_name.'\', \'right\');">'.'>'.'</button>';
	return $toReturn;
}


/* 2017/08/13 */
/* multiple tablename_var.js functions see tablename_var.php */


function show_query_result_html_table($conn, $sql, $button, $manage, $userinput, $tabledisplayname="\$tablename"){

	$_tablename = _nth_word($sql, '3');
	$_tablename = trim($_tablename,";");
	$_tablename = trim($_tablename,"`");

	$result = $conn->query($sql);
	if ($result === FALSE) {
	// Returns FALSE on failure. 
		return "Request Failure!" . " from " . $sql . p(1);
	} elseif ($result === TRUE) {
	// For other successful queries mysqli_query() will return TRUE.
		$othernote = "";
			
		if ( strtolower(_nth_word($sql, '0')) === "create" ){
// update tablename_var.js file
			//load file
			$commands = file_get_contents("tablename_var.js");
	
			//delete previous definition
			$lines = explode("\n",$commands);


// time-testing
			$time_start = microtime(true);

// method 1
			foreach($lines as $key => $line){
				$line = trim($line);
				if( $line and ($line == "" or startsWith($line,"var $_tablename") ) ){
					unset($lines[$key]);
				}
			}
			$_txt = "var $_tablename = [". _js_name_col($sql) ."];";
			array_push($lines, $_txt);

// Method 2
			// $lines = array_reverse($lines); 
			// foreach($lines as $key => $line){
			// 	$line = trim($line);
			// 	if( $line and ($line == "" or startsWith($line,"var $_tablename") ) ){
			// 		unset($lines[$key]);
			// 		break;
			// 	}
			// }
			// $lines = array_reverse($lines);

			// $_txt = "var $_tablename = [". _js_name_col($sql) ."];";
			// array_push($lines, $_txt);
			

			$time_end = microtime(true);
			$time = $time_end - $time_start;
			echo "<br><h3>Process Time: {$time}<h3>";



			$commands = join("\n", $lines);
	
			$_file = fopen("tablename_var.js", "w") or die ("Unable to open file!");
			fwrite($_file, $commands);
			fclose($_file);
			
			
			/* debug */

			// echo $_txt;
			// create table `test1` (a VARCHAR(10), b VARCHAR(10), c TEXT);
			// var relatedcopy = ["a", "b", "c"];

			$othernote = "[tablename_var.js] file updated :: " . $_txt ;
		} elseif ( strtolower(_nth_word($sql, '0')) === "drop") {
			// $othernote = "[tablename_var.js] file updated :: Deleted." . p(2);
		}

		return "Request Success!" . " from " . $sql . "[".$othernote."]" . p(1);
	} else {
	// For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries mysqli_query() will return a mysqli_result object. 
		
		// 
		// $columns must be exist for select, ... statement. READ from tablename_var.js
		// 
		// $columns = _fetch_fields($result);
		$columns = [];
		$tmp_tablenames = file_get_contents("tablename_var.js");
		$tmp_tables = explode("\n",$tmp_tablenames);

		foreach ($tmp_tables as $key => $value) {
			if (strpos($value, $_tablename) !== false){
				$columns = _line2array_tablename_var_js($value);
				break;
			}
		}
		
		// $columns = $_matches;


		$toReturn = "";
		if ($userinput) {$toReturn = "Request Success!" . " from " . $sql . p(1); }
		
	// Print Tablename and Manage Hyperlink
		$toReturn .= '<h2>'. $tabledisplayname .' ('. $result->num_rows .' rows)';
		if ($manage) { $toReturn .= '---- <a href="HandleTable.php?fromwhere='.$_tablename.'">Manage</a> -- <a href="index.php?droptable='.$_tablename.'">Drop</a>'; }
		$toReturn .= '</h2>';

/************** scrollable method 1
	// Print table head
		$toReturn .= '<table cellspacing="0" cellpadding="0" border="0" width="325">
						<tr>
							<td>
								<table cellspacing="0" cellpadding="1" border="1" width="1200" >
         							<tr style="color:white;background-color:grey">';
    
    // Print Table Columns Names (Attributes)
        foreach ($columns as $key => $value) {
			if ($manage) { $toReturn .= '<th>' . $value .'</th>'; }
			else {$toReturn .= '<th>' . ($value) .'</th>'; }
		}

	// Print Table Content	
		$toReturn .= '				</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td>
								<div style="width:1200px; height:200px; overflow:auto;">
         							<table cellspacing="0" cellpadding="1" border="1" width="1200" >';
		if ($result->num_rows > 0) {
			$i = 1;
			while($row = $result->fetch_assoc()) {
				$toReturn .= '<tr id="'. $i .'">';
				foreach ($columns as $key => $value) {
					$toReturn .= '<td id="'.$i.$tablename.$value.'">'. $row[$value] . '</td>';
				}
				if ($button){ $toReturn .= '<td><button type="button" align="middle" onclick="copy_js('.$i.',\''.$tablename.'\','.strtolower($tablename).');">Copy Info</button></td>'; }
				$toReturn .= "</tr>";
				$i += 1;
			}
		} else {
			$toReturn .= '<tr>';
			for($i = 0; $i < count($columns); $i += 1) {
				$toReturn .= '<td>...</td>';
			}
			if ($button) { $toReturn .= '<td>...</td>'; }
			$toReturn .= "</tr>";
		}
		$toReturn .= '				</table>  
       							</div>
    						</td>
  						</tr>
					</table>';
*/


// old non-scrollable table layout. -----------------------------------

		
	// Print table head
		$toReturn .= '<table border="1" class="fixed_header" width="100%" style="table-layout: fixed;">
						<thead>';
	
	// Print Add / Erase buttons.
		// if ($manage == False){
		// 	$toReturn .= '<tr>';
		// 	foreach ($columns as $key => $value) {
		// 		$toReturn .= '<td>'.
		// 			'<button type="button" align="middle" onclick="add_column(\''.$value.'\');">Add</button>'.
		// 			'<button type="button" align="middle" onclick="erase_column(\''.$value.'\');">Erase</button>'.
		// 			'</td>';
		// 	}
		// 	$toReturn .= '</tr>';
		// }

	// Print Table Columns Names (Attributes)
		$toReturn .= '<tr id="0">';
		foreach ($columns as $key => $value) {
			$toReturn .= '<th width="5%" style="word-wrap: break-word">' . 
						 	'<button style="background: black; color:#fff;" type="button" align="middle" onclick=";">' . 
						 		$value .
						 	'</button>' . 
						 '</th>'; 

		}
		if ($button){ $toReturn .= '<th width="5%" style="word-wrap: break-word">CopyInfo</th>'; }
		$toReturn .= '</tr></thead>';//headline

	// Print Table Content
		$toReturn .= '<tbody>';
		if ($result->num_rows > 0) {
			$i = 1;

			//-- add scrollable
			//
			// $toReturn .= '<div style="width:320px; height:80px; overflow:auto;">';
			// $toReturn .= '<table cellspacing="0" cellpadding="1" border="1" width="300" >';
			while($row = $result->fetch_assoc()) {
				$toReturn .= '<tr id="'. $i .'">';
				foreach ($columns as $key => $value) {
					$toReturn .= '<td width="5%" style="word-wrap: break-word" id="'.$i.$_tablename.$value.'">'. $row[$value] . '</td>';
				}
				if ($button){ $toReturn .= '<td width="5%" style="word-wrap: break-word" ><button type="button" align="middle" onclick="copy_js('.$i.',\''.$_tablename.'\','.strtolower($_tablename).');">Copy Info</button></td>'; }
				$toReturn .= "</tr>";
				$i += 1;
			}
			// $toReturn .= '</table>';
			// $toReturn .= '</div>';
			//
			//-- add scrollable end, 20180903


		} else {
			$toReturn .= '<tr>';
			for($i = 0; $i < count($columns); $i += 1) {
				$toReturn .= '<td width="5%" style="word-wrap: break-word" >...</td>';
			}
			if ($button) { $toReturn .= '<td width="5%" style="word-wrap: break-word">...</td>'; }
			$toReturn .= "</tr>";
		}
		$toReturn .= '</tbody>';
		$toReturn .= '</table>';


//--------------------------------


		return $toReturn;
	}
}


// 
// Return the query result in an array, an 2D array is satisfactory. 
// 
function show_query_result_array($conn, $sql, $getheader=false, $tablename="\$tablename"){
	$result = $conn->query($sql);
	$gh = $getheader;
	
	if ($result->num_rows > 0) {
		$toReturn = array();
		while($row = $result->fetch_assoc()) {
			if ($gh and $getheader){
				array_push($toReturn, array_keys($row));
				$gh = false;
			}
			array_push($toReturn, array_values($row));
		}
		return $toReturn;
	}else{
		return array(array());
	}	

}




// part 4
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

function input_form_by_name_attribute($columns, $conn, $submitto, $tablename="\$tablename"){
	$toReturn = "";
	$toReturn .= '
	<script>
		$(document).ready(function(){
		    $("#hide").click(function(){
		        $("#hideshow").fadeOut();
		    });
		    $("#show").click(function(){
		        $("#hideshow").fadeIn();
		    });
		});
	</script>
	<div style="text-align:center;">
		<table>
		<th>
		<button class="enroll upabove observe" type="button" id="hide">Observe</button>	
		</th>
		<th>
		<button class="enroll upabove" type="button" id="show">Enroll</button>
		</th>
		</table>
	</div>';
	$toReturn .= '<div id="hideshow">';
	$toReturn .= '
	<fieldset style="float:left;"><legend><h3>What type to choose?</h3></legend>
		<button class="enroll" style="float:left;" type="button" onclick="inputtype(\'ask\');">_ Ask _</button>
		<button class="enroll" style="float:right;" type="button" onclick="inputtype(\'offer\');">_Offer_</button>
	</fieldset>

	<br>
	<form action="'.$submitto.'" method="post">
	<fieldset style="float:right;"><legend><h3>What to do?</h3></legend>
		<input type="radio" name="database_action" value="insert" checked /> Insert Into ... 
		<input type="radio" name="database_action" value="drop" /> Drop From ... <br>
	</fieldset>
	<fieldset>
	    <legend>
	    	<button type="button" onclick="clear_js('.strtolower($tablename).');">Clear Entry</button>
	    </legend>
    <br>';
  
	foreach ($columns as $key => $value) {
		$toReturn .= '<label>' . $value . ': </label><input type="text" name="' . $value . '" id="'. $value .'"';
		if ($value == "SharickID" or $value == "sharickid"){
			$toReturn .= 'value="'. (getMaxSharickID($conn, $tablename)+1).'"/>';
		} else {
			$toReturn .= '/>';
		}
		/* special arrangement for CU ride  2018/09/06*/
		if ($value == "Name" or 
			$value == "Email" or 
			$value == "Time" or 
			$value == "ToCity" or 
			$value == "Type"
		){
			$toReturn .= '<br><br>';
		}
	}
	$toReturn .= '
		<br><br>
		<input type="submit" value="Submit">
	
	<br>
	</form>
	</div>
	';
	return $toReturn;
}

// <select name="todo" size="1">
// 	<option value="europe">europe</option>
// 	<option value="namerica">n. america</option>
// 	<option value="samerica">s. america</option>
// 	<option value="asia">asia</option>
// 	<option value="africa">africa</option>
// 	<option value="oz">the other one</option>
// </select>







// Part 5 Add a query textbox to execute sql statement; One at a time at first. 
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
 * @param $debug 	-- Print query process if true.  
 * @param $detail 	-- Print detailed process if this argument is true AND $debug is also true.
 * @return true | false
 */
function query_and_Feedback($conn, $sql, $debug = true, $detail = true){
	if ($conn->query($sql) === TRUE) {
		if ($detail && $debug){		
		    echo "[Query] $sql ... succeeded.<br>";
		}else if ($debug){
			echo "[Query] ... succeeded. <br>";
		}
		return true;
	} else {
	    echo "Error: " . $conn->error;
	    return false;
	}
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



?>