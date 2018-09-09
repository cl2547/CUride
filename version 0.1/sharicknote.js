// import {} from 'tablename_var';

var servername = "localhost";
var username = "root";
var password = "";// usually no need a password for root;
var dbname = "SharickNote"; 

// Individual Table Actions. 

function copy_js(intt, tablename, arr){
	for (var i = arr.length - 1; i >= 0; i--) {
		document.getElementById(arr[i]).value = document.getElementById(intt+tablename+arr[i]).innerHTML;
	}
}
function clear_js(arr){
	for (var i = arr.length - 1; i >= 0; i--) {
		document.getElementById(arr[i]).value = "";
	} 
}




// handling url == current url

function _domain(current_url){
	// var current_url = window.location.href;
	var questionmark = "?";
	var _ind = current_url.indexOf(questionmark);
	return current_url.substring(0, _ind);
}
function _params(current_url, key){
	// var current_url = window.location.href;
	var questionmark = "?";
	var _ind = current_url.indexOf(questionmark);
	var params = current_url.substring(_ind + 1);
	var para_arr = params.split("&");
	for (var i = 0; i < para_arr.length; i++) {
		if (para_arr[i].indexOf(key) != -1){
			return para_arr[i];
		}
	}
	return "-1";
}

// eachtime for a new query, clean the url

function move(attr_name, left_or_right){
	var current_url = window.location.href;
	var domain = _domain(current_url);
	var fromwhere_attr = _params(current_url, "fromwhere");
	window.location = domain + '?' + fromwhere_attr + '&attr_name='+attr_name + '&left_or_right='+left_or_right;
}

function add_column(wherea){
	var current_url = window.location.href;
	var domain = _domain(current_url);
	var fromwhere_attr = _params(current_url, "fromwhere");
	current_url = domain + '?' + fromwhere_attr + '&column_action=add' + '&where=' + wherea;
	
	// print column type options and submit
	formhtml = "<br><fieldset><legend>New Column Form</legend>"+
			"<form action=\""+current_url+"\" method=\"POST\">"+
			"First name:<br>"+
			"<input type=\"text\" name=\"column_name\" value=\"Column1\"><br>"+
			"Last name:<br>"+
			"<input type=\"radio\" name=\"column_type\" value=\"INT\" checked>INT<br>"+
			"<input type=\"radio\" name=\"column_type\" value=\"TEXT\">TEXT<br>"+
			"<input type=\"radio\" name=\"column_type\" value=\"VARCHAR\">VARCHAR<br>"+
			"<input type=\"text\" name=\"varchar_n\" value=\"10\"><br>"+
			"<br>"+
			"<input type=\"submit\" value=\"Submit\">"+
			"</form></fieldset>";
	document.getElementById('show_form').innerHTML = formhtml;
}

function erase_column(column_name){
	var current_url = window.location.href;
	var domain = _domain(current_url);
	var fromwhere_attr = _params(current_url, "fromwhere");
	window.location = domain + '?' + fromwhere_attr + '&column_action=erase' + '&which=' + column_name;	
}

// Part 3
// 
// Added on 2018/09/06
// At Cornell University 
// For CUride project
// Solo
// 
// ---------------------------------------------
function show_and_hide_div(flag, div_id, div_id_btn){
	if (! flag){
	// flag==true, show the div
		document.getElementById(div_id).innerHTML = "";
		// $(document.getElementsByTagName(div_id)).attr("style", "display:none;"); 
		// $(document.getElementsByTagName(div_id_btn)).attr("onclick", "show_and_hide_div(\'false\', \'hideshow\')"); 

	} else {
	// flag==false, hide the div
		$(document.getElementsByTagName(div_id)).attr("style", "display:block;");	
		$(document.getElementsByTagName(div_id_btn)).attr("onclick", "show_and_hide_div(\'true\', \'hideshow\')"); 

	}
}

function inputtype(type){
	document.getElementById('Type').value = type;
}