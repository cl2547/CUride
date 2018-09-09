<?php

include_once "utility_function.php";

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



	<fieldset style="float:right; display:none;"><legend><h3>What to do?</h3></legend>
		<input type="radio" name="database_action" value="insert" checked />
		<input type="radio" name="database_action" value="drop" /> Drop From ... <br>
	</fieldset>
	<fieldset>
	    <legend>
	    	<button type="button" onclick="clear_js('.strtolower($tablename).');">Clear Entry</button>
	    </legend>
    <br>';
  
	foreach ($columns as $key => $value) {
		$toReturn .= '<label>' . $value . ': </label><input type="text" name="' . $value . '" id="'. $value .'"';
		if (array_key_exists("username", $_GET) and $value == "Name"){
		    $toReturn .= ' value="'.$_GET['username'].'" ';
		}

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



?>