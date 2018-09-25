// import {} from 'tablename_var';


// 
function copy_js(intt, tablename, arr){
	for (var i = arr.length - 1; i >= 0; i--) {
		if (arr[i] == "Name"){
			continue;
		} else {
		document.getElementById(arr[i]).value = document.getElementById(intt+tablename+arr[i]).innerHTML;
			
		}
	}
}

function askofferchange(){
	var currentvalue = document.getElementById('Type').value ;
	if (currentvalue == "ask") {
		document.getElementById('Type').value = "offer";
		document.getElementById('typebtn').innerHTML = "Current Type :: Offer";

	} else if (currentvalue == "offer") {
		document.getElementById('Type').value = "ask";
		document.getElementById('typebtn').innerHTML = "Current Type :: Ask";
	} else {
		document.getElementById('Type').value = "ask";
		document.getElementById('typebtn').innerHTML = "Current Type :: Ask";
	}
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
function rowLargerThan(x, y){
	if (isNaN(x.innerHTML)){
		/* x is not a number, lexical comparison. */
		return x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase();
	} else {
		/* x is a number, parse to Integer. */
		return parseInt(x.innerHTML, 10) > parseInt(y.innerHTML, 10);
	}
}
function rowSmallerThan(x, y){
	if (isNaN(x.innerHTML)){
		/* x is not a number, lexical comparison. */
		return x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase();
	} else {
		/* x is a number, parse to Integer. */
		return parseInt(x.innerHTML, 10) < parseInt(y.innerHTML, 10);
	}
}
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
				if (  rowLargerThan(x, y)  ) {
					// If so, mark as a switch and break the loop:
					// window.alert(x.innerHTML.toLowerCase() +" > "+y.innerHTML.toLowerCase());
					shouldSwitch = true;
					break;
				}
			} else if (dir == "desc") {
				if (  rowSmallerThan(x, y)  ) {
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

