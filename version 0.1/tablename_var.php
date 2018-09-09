<?php
/**
 * Utility Functions for tablename_var.js complementary
 * 
 *
 * For Step 2 and Step 3
 *
 * PHP version 5
 *
 * @author     Xiang Shiyi, Sharick <sharick@connect.hku.hk>
 * @since      13/08/2017
 */

function _swap_array($arr, $ind1, $ind2){
	$tmp = $arr[$ind1];
	$arr[$ind1] = $arr[$ind2];
	$arr[$ind2] = $tmp;
	return $arr;
}
function _update_js_name_col($arr, $point, $left_or_right){
	$index = array_search($point, $arr);
	if ($index !== FALSE && is_array($arr)){
		if (($index == 0 && $left_or_right == "left") || ($index == count($arr)-1 && $left_or_right == "right")){ 
												return $arr; 								  } 
		else if ($left_or_right == "left"){ 	return _swap_array($arr, $index, $index - 1); } 
		else if ($left_or_right == "right"){	return _swap_array($arr, $index, $index + 1); }
		else {  								return "-1";								  }
	}else{										return "-1";								  }
}


function _addinto_arr($arr, $to_add, $pos_value){
	$index = array_search($pos_value, $arr);
	array_splice($arr, $index, 0, $to_add); // If it's only one element, it can be a string, and does not have to be an array.
	return $arr;
}

function _line_from_tablename_var_js($tablename){
	$c = file_get_contents("tablename_var.js");
	$lines = explode("\n", $c);
	foreach ($lines as $key => $line) {
		$line = trim($line);
		if( $line and ($line == "" or startsWith($line,"var $tablename") ) ){
			return $line;
		}
	}
	return "-1";

}

function _line2array_tablename_var_js($value){
	$a = strpos($value, "["); 
	$b = strrpos($value, "]");
	$_matches = explode(",", substr($value, $a + 1, $b - $a - 1));
	$_matches = array_map('_remove_quotes', $_matches);
	return $_matches;
}



/* the important high-level application part */

function _modify_tablename_var_js($action, $tablename, $attr_name, $left_or_right="None"){
	$c = file_get_contents("tablename_var.js");
	$lines = explode("\n", $c);
	foreach ($lines as $key => $line) {
		$line = trim($line);
		if( $line and ($line == "" or startsWith($line,"var $tablename") ) ){
			$arr = _line2array_tablename_var_js($line);

			switch ($action) {
				case 'update':
					$arr = _update_js_name_col($arr, $attr_name, $left_or_right);			
					break;

				case 'add':
					$wherea = $left_or_right;
					$arr = _addinto_arr($arr, $attr_name, $wherea);
					// not the same sequence as stored in the database
					break;

				case 'delete':
					$arr = array_diff($arr, [$attr_name]);
					break;
				
				default:
					# code...
					break;
			}
			
			if (is_array($arr)){
				$arr = array_map('_add_quotes_doublescript', $arr);
				$lines[$key] = "var $tablename = [" . join(",", $arr) . "];";				
			}
		}
	}

	$commands = join("\n", $lines);
	$_file = fopen("tablename_var.js", "w") or die ("Unable to open file!");
	fwrite($_file, $commands);
	fclose($_file);	
}
function _add_tablename_var_js($tablename, $attr_name, $wherea){
	_modify_tablename_var_js('add', $tablename, $attr_name, $wherea);
}
function _remove_col_tablename_var_js($tablename, $attr_name){
	_modify_tablename_var_js('delete', $tablename, $attr_name);
}


?>