<?php

include_once "utility_function.php";

// USING php timestamp.

function input_form_by_name_attribute($columns, $submitto, $tablename="\$tablename"){
	$toReturn = "";
	$toReturn .= '
	<script>
	$(document).ready(function(){
	    $("#ask_button").click(function(){
	        $("#Offer_board").fadeOut();
	        $("#Ask_board").fadeIn();
	        $("#Ask_board").css("width", "100%");
	    });
	    $("#overall_button").click(function(){
	        $("#Offer_board").fadeIn();
	        $("#Offer_board").css("width","49.9%");
	        $("#Ask_board").fadeIn();
	        $("#Ask_board").css("width","49.9%");
	    });
	    $("#offer_button").click(function(){
	        $("#Ask_board").fadeOut();
	        $("#Offer_board").fadeIn();
	        $("#Offer_board").css("width","100%");
	    });
	});

	</script>
	<div style="text-align:center;">
		<button class="upabove" type="button" id="ask_button">Ask board</button>	
		<button class="upabove" type="button" id="overall_button">Overall</button>	
		<button class="upabove" type="button" id="offer_button">Offer board</button>
    </div>';
	$toReturn .= '<div id="hideshow">';
	$toReturn .= '
	
	<form action="'.$submitto.'" id="inputform" method="post">

	<fieldset style="float:right; display:none;">
		<input type="radio" name="database_action" value="insert" checked />
	</fieldset>

	<fieldset>
	    <legend>
	    	<button type="button" onclick="document.getElementById(\'inputform\').reset();">Reset</button>
	    </legend>
    <br>';
  
	foreach ($columns as $key => $value) {
		$toReturn .= '<label>' . $value . ': </label>';
		$toReturn .= '<input type="text" name="' . $value . '" id="'. $value .'"';
		
		if (array_key_exists("username", $_SESSION) and $value == "Name"){
		    $toReturn .= ' value="'.$_SESSION['username'].'" ';
		} 
		
		if ($value == "SubmitTime"){
		    $toReturn .= ' value="'.intval(microtime(true)).'" ';
		}
		$toReturn .= '/>';
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