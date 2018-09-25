<?php session_start(); $myname = $_SESSION['username']; ?>
			
<html>
	<head>
		<meta http-equiv="refresh" content="200 URL=./board.php">
		<link rel="stylesheet" href="table.css">
		<h1>CU Ride Information Exchange [Still Testing]</h1>
	</head>
	<body>
	<script type="text/javascript" src="./sharicknote.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script>
		$(document).ready(function(){
		    // first three button
		    $("#ask_button").click(function(){
		        $("#Offer_board").fadeOut();
		        $("#Ask_board").fadeIn();
		        $("#Ask_board").css("width", "100%");
		        $("#Ask_board").css({ 'table-layout' : 'auto'});
		    });
		    $("#offer_button").click(function(){
		        $("#Ask_board").fadeOut();
		        $("#Offer_board").fadeIn();
		        $("#Offer_board").css("width","100%");
		        $("#Offer_board").css({ 'table-layout' : 'auto'});
		    });
		    $("#Ask_board").fadeOut();
			$("#Offer_board").fadeIn();
			$("#Offer_board").css("width","100%");
			$("#Offer_board").css({ 'table-layout' : 'auto'}); 
		});
	</script>
	
	<div>
		<p style="text-align: center;">
			The website is refreshed every 200 seconds. You may press F5 to get most up to date result.
		</p>
	</div>
	<div style="text-align:center;">
		<button class="upabove" type="button" id="ask_button">Ask board</button>	
		<button class="upabove" type="button" id="offer_button">Offer board</button>
    </div>
	
	<form action="board.php" id="inputform" method="post">

		<div id="inputfields">

			<fieldset><legend>Information</legend>
				<label>Name: </label><input name="Name" id="Name" value="<?php echo $myname  ?>" type="text" readonly=1>
				<label>Phone: </label><input name="Phone" id="Phone" type="text" />
				<label>Wechat: </label><input name="Wechat" id="Wechat" type="text" />
				<label>Email:* </label><input name="Email" id="Email" type="email" />
				<label>Date:* </label><input name="Date" id="Date" type="date" required=1 />
				<label>Time:* </label><input name="Time" id="Time" type="text" />
				<label>From:* </label><input name="FromCity" id="FromCity" type="text" required=1 />
				<label>To:* </label><input name="ToCity" id="ToCity" type="text" required=1 />
				<label>Price:* </label><input name="Price" id="Price" type="number" required=1 />
				<label># of People:* </label><input name="NoOfPpl" id="NoOfPpl" type="number" />
				<label>Type:* </label>
				<br><br>
				<div id="TypeChoice" >
					<input name="Type" id="Type" type="radio" value="ask" required=1 />ask 
					<input name="Type" id="Type" type="radio" value="offer" />offer	
				</div>
				<br>
				<input value="Submit" type="submit" />
			</fieldset>
			<!-- not shown -->
			<div style="display: none;">
				<input type="radio" name="database_action" value="insert" checked />
				<label>SubmitTime: </label>
				<input name="SubmitTime" id="SubmitTime" type="text" />
				<label>TextArea: </label>
				<input name="TextArea" id="TextArea" type="text" />
			</div>
		</div>
	</form>
		<?php  


			set_time_limit(30);
			$time_start = microtime(true);// Testing script runing time. 
			// sleep(1);

			include_once "config.php";
			include_once "utility_function.php";
			include_once "show_query_result.php";

			$conn = connect_to_server($servername, $username, $password); // debug in cofig.php
			mysqli_select_db($conn, $dbname);                      /* Connect to database */

			/* only difference */

			$sql = "SELECT * FROM $datatablename WHERE 0;";
			$result = $conn->query($sql);
			$columns = _fetch_fields($result);

			/* handle drop or insert action */
			if (array_key_exists("database_action", $_POST)){
				action_on_row_if_ok($conn, $columns, $datatablename, $_POST["database_action"]);
			}

			/* customized column grab for database. */
			$ca = array();
			foreach($columns as $v){
			    if ($v == "SubmitTime" and False){
			        array_push($ca, "CONVERT_TZ(`$v`,'+00:00','-04:00') AS $v"); // convert to U.S. timezone.
			    }else if ($v == "Price" or $v == "NoOfPpl") {
			    	array_push($ca, "CONVERT($v, UNSIGNED INTEGER) AS $v"); 
			    } else {
			        array_push($ca, "$v");         
			    }
			}
			$ca = join(",", $ca);

			// echo $ca . p(1);


			$columnsview = array("Name", "Email", "Date", "Time", "FromCity", "ToCity", "NoOfPpl", "Price");

			echo '<div id="result_area">';
			$sql = "SELECT $ca FROM `$datatablename` WHERE Type='ask' ORDER BY Date ASC, Time ASC";
			$result = $conn->query($sql);
			echo show_query_result_html_table($result, $columnsview, $myname, "Ask $datatablename");

			$sql = "SELECT $ca FROM `$datatablename` WHERE Type='offer' ORDER BY Date ASC, Time ASC, Price ASC";
			$result = $conn->query($sql);
			echo show_query_result_html_table($result, $columnsview, $myname, "Offer $datatablename");
			echo '</div>';


			// 
			// Step 5: Leave database
			// 
			$conn->close();


			// Testing script runing time. 
			$time_end = microtime(true);
			$time = $time_end - $time_start;
			echo "<br>Process Time: {$time}";
		?>

	</body>
</html>




