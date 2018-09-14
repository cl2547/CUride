// import {} from 'tablename_var';


// Can not copy the others' name.
function copy_js(intt, tablename, arr){
	for (var i = arr.length - 1; i >= 0; i--) {
		// console.log(intt); 
		// console.log(tablename);  
		// console.log(arr[i]);
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



// Part 4
// 
// Added on 2018/09/14, copied from the website
// At Cornell University 
// For CUride project
// Solo
// 
// ---------------------------------------------
// <table id="myTable2">
// <tr>
// <!--When a header is clicked, run the sortTable function, with a parameter,
// 0 for sorting by names, 1 for sorting by country: -->
// <th onclick="sortTable(0)">Name</th>
// <th onclick="sortTable(1)">Country</th>
// </tr>
// ...

// <script>
function sortTable(tableid, n) {
	var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
	table = document.getElementById(tableid);
	switching = true;
	// Set the sorting direction to ascending:
	dir = "asc";
	/* Make a loop that will continue until
	no switching has been done: */
	while (switching) {
		// Start by saying: no switching is done:
		switching = false;
		rows = table.rows;
		/* Loop through all table rows (except the
		first, which contains table headers): */
		for (i = 1; i < (rows.length - 1); i++) {
			// Start by saying there should be no switching:
			shouldSwitch = false;
			/* Get the two elements you want to compare,
			one from current row and one from the next: */
			x = rows[i].getElementsByTagName("TD")[n];
			y = rows[i + 1].getElementsByTagName("TD")[n];
			/* Check if the two rows should switch place,
			based on the direction, asc or desc: */
			if (dir == "asc") {
				if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
					// If so, mark as a switch and break the loop:
					shouldSwitch = true;
					break;
				}
			} else if (dir == "desc") {
				if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
					// If so, mark as a switch and break the loop:
					shouldSwitch = true;
					break;
				}
			}
		}
		if (shouldSwitch) {
			/* If a switch has been marked, make the switch
			and mark that a switch has been done: */
			rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
			switching = true;
			// Each time a switch is done, increase this count by 1:
			switchcount ++;
		} else {
			/* If no switching has been done AND the direction is "asc",
			set the direction to "desc" and run the while loop again. */
			if (switchcount == 0 && dir == "asc") {
				dir = "desc";
				switching = true;
			}
		}
	}
}


function harold(standIn) {
	if (standIn < 10) {
		standIn = '0' + standIn;
	}
	return standIn;
}
function clock() {// We create a new Date object and assign it to a variable called "time".
	var 
		time = new Date(),
		day = time.getDate(), 				// 1 - 31
		month = time.getMonth(),				// 0 - 11
		year = time.getFullYear(),			// 
		hours = time.getHours(),		// 0 - 23
		minutes = time.getMinutes(),	// 0 - 59
		seconds = time.getSeconds();	// 0 - 59

	document.getElementById('SubmitTime').value = 
		year + "/" + harold(month) + "/" + harold(day) + " " + 
		harold(hours) + ":" + harold(minutes) + ":" + harold(seconds);
	// console.log(seconds);
  

}
setInterval(clock, 1000);