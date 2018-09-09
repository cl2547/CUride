<?php

include_once "utility_function.php";

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
		
		$toReturn = "";
		if ($userinput) {$toReturn = "Request Success!" . " from " . $sql . p(1); }
		
	// Print Tablename and Manage Hyperlink
		$toReturn .= '<h2>'. $tabledisplayname .' ('. $result->num_rows .' rows) </h2>';
		
	// Print table head
		$toReturn .= '<table id="'.$tabledisplayname.'" border="1" width="100%" style="table-layout: fixed;"><thead>';

	// Print Table Columns Names (Attributes)
		$toReturn .= '<tr id="0">';
		
		// -- Sort Table by Clicking the Headers 
		// 
		$j = 0;
		
		foreach ($columns as $key => $value) {
			$toReturn .= '<th width="5%" bgcolor="#FFFF00" onclick="sortTable(\''.$tabledisplayname.'\','.$j.')">' . $value . '</th>'; 
			$j += 1;
		}
		if ($button){ $toReturn .= '<th width="5%">CopyInfo</th>'; }
		$toReturn .= '</tr></thead>';//headline

	// Print Table Content
		$toReturn .= '<tbody>';
		if ($result->num_rows > 0) {
			$i = 1;

			while($row = $result->fetch_assoc()) {
				$toReturn .= '<tr id="'. $i .'">';
				
				// -- dummy here used for Delete action -- connected to database. 
				// 
				$dummyinputform = '';


				foreach ($columns as $key => $value) {
					$toReturn .= '<td width="5%" style="word-wrap: break-word" id="'.$i.$_tablename.$row['Type'].$value.'">'. $row[$value] . '</td>';
	
					$dummyinputform .= '<input type="text" name="'.$value.'" value="'.$row[$value].'" />';
				}

				// add copy_info button
				if ($button){ $toReturn .= '<td width="5%"><button type="button" align="middle" onclick="copy_js('.$i.',\''.$_tablename.$row['Type'].'\','.strtolower($_tablename).');">Copy Info</button>'; }
				
				// add delete button
				if ($_GET['username'] == $row['Name']){
				    	    
				    $toReturn .= '<form action="'.$rootpath.'dbaction.php?username='.$_GET['username'].'" method="post">
                                	    <fieldset style="float:right; display:none;"><legend><h3>What to do?</h3></legend>
                                	    ';
                    $toReturn .=        $dummyinputform;
                    $toReturn .= '      <input type="radio" name="database_action" value="drop" checked/>
                                	    </fieldset>
                                	    <input type="submit" value="Delete">
                                    </form>';                
				} 
				
				$toReturn .= "</td></tr>";
				$i += 1;
			}

		} else {
		// NO record

			$toReturn .= '<tr>';
			for($i = 0; $i < count($columns); $i += 1) {
				$toReturn .= '<td>...</td>';
			}
			if ($button) { $toReturn .= '<td>...</td>'; }
			$toReturn .= "</tr>";
		}
		$toReturn .= '</tbody>';
		$toReturn .= '</table>';

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
	// not empty result.
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

?>
