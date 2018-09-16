<?php

include_once "utility_function.php";

// USING php timestamp.

function input_form_by_name_attribute($columns, $submitto, $tablename="\$tablename"){
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
	
    		    <button class="enroll upabove observe" type="button" id="hide">Observe</button>	
        		<button class="enroll upabove" type="button" id="show">Enroll</button>
    </div>';
	$toReturn .= '<div id="hideshow">';
	$toReturn .= '
	
	<form action="'.$submitto.'" method="post">

	<fieldset style="float:right; display:none;">
		<input type="radio" name="database_action" value="insert" checked />
	</fieldset>

	<fieldset>
	    <legend>
	    	<button type="button" onclick="clear_js('.strtolower($tablename).');">Clear Entry</button>
	    </legend>
    <br>';
  
	foreach ($columns as $key => $value) {
		$toReturn .= '<label>' . $value . ': </label>';
		$toReturn .= '<input type="text" name="' . $value . '" id="'. $value .'"';
		
		if (array_key_exists("username", $_GET) and $value == "Name"){
		    $toReturn .= ' value="'.$_GET['username'].'" ';
		} 
		
		if ($value == "SubmitTime"){
		    $toReturn .= ' value="'.intval(microtime(true)).'" ';
		}
		$toReturn .= '/>';






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