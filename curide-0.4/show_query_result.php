<?php

// session_start();

include_once "utility_function.php";
// include_once "config.php";


function show_query_result_html_table($conn, $sql, $tabledisplayname="\$tablename"){
    // ------------ Database Action ----------------------
	$result = $conn->query($sql);

	$_tablename = str_replace(" ", "_", $tabledisplayname);  // The table name used in each <tr id=""> for copy_info js function.
	
	if ($result === FALSE) {
	// Returns FALSE on failure. 
		return "Request Failure!" . " from " . $sql . p(1);

	} elseif ($result === TRUE) {
	// For other successful queries mysqli_query() will return TRUE.
		return "Request Success!" . " from " . $sql . "[".$othernote."]" . p(1);

	} else {
	// For successful SELECT, SHOW, DESCRIBE or EXPLAIN queries mysqli_query() will return a mysqli_result object. 
		$toReturn = "";
		$columns = _fetch_fields($result); 		// $columns must be exist for select, ... statement.


		$columnsview = array("Name", "Email", "Date", "Time", "FromCity", "ToCity", "Price");
        // print_r($columnsview);

		
	// Print table name, head
		$toReturn .= '<table id="'.$_tablename.'"><thead>
						<caption>'.
						'<h2 id="'.$_tablename.'">'. $tabledisplayname .' ('. $result->num_rows .' rows) </h2>'.
						'</caption>';

	// Print Table Columns Names (Attributes)
		$toReturn .= '<tr id="0">';
		

		$j = 0; // -- Sort Table by Clicking the Headers
		foreach ($columnsview as $key => $value) {
			$toReturn .= '<th onclick="sortTable('._add_quotes_valuescript($_tablename).','.$j.')">' . $value . '</th>'; 
			$j += 1;
		}

	// Print copy info button.
		
		$toReturn .= '<th>CopyInfo</th>'; 

		$toReturn .= '</tr></thead>';//headline

	// Print Table Content
		$toReturn .= '<tbody>';
		if ($result->num_rows > 0) {
			$i = 1;
			while($row = $result->fetch_assoc()) {

                // Print each row and use dummy here used for Delete action -- connected to database.
                //
				$dummyinputform = '';	
				foreach ($columns as $key => $value) {
					$dummyinputform .= '<input type="text" name="'.$value.'" value="'.$row[$value].'" />';
				}

				$toReturn .= '<tr id="'. $i .'">';
				foreach ($columnsview as $key => $value) {
					$toReturn .= '<td id="'. $i.$_tablename.$value . '">'. $row[$value] . '</td>';
				}

				// add copy_info button
				$arr_val = "[".join(",", array_map('_add_quotes_valuescript', $columnsview))."]";
				$toReturn .= '<td width="5%">
					<img src="'.$GLOBALS['rootpath'].'/Edit.png" id="copy_action_img" align="left" onclick="
					                copy_js('.
											join(',', array(trim($i), _add_quotes_valuescript($_tablename), $arr_val) ).
					                ');" />'; 
				
				// add delete button
				if ($_SESSION['username'] == $row['Name']){
				    	    
				    $toReturn .= '	<form action="'.$GLOBALS['rootpath']. $GLOBALS['actionboard'].'" method="post">
                                	    <fieldset style="display:none;">';
                    $toReturn .=            $dummyinputform;
                    $toReturn .= '          <input type="radio" name="database_action" value="drop" checked/>
                                	    </fieldset>
                                	    <input type="image" id="delete_action_img" src="'.$GLOBALS['rootpath'].'Delete.png" alt="Submit Form" />
                                    </form>';                
				} 
				
				$toReturn .= "</td></tr>";
				$i += 1;
			}

		} else {
		// NO record
		
			$toReturn .= '<tr>';
			for($i = 0; $i < count($columns); $i += 1) {    $toReturn .= '<td>...</td>';    }
			$toReturn .= '<td>...</td>'; 
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
