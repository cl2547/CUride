<?php

error_reporting( E_ALL ); 
$servername = "localhost";
$username = "id6885213_sx243";
$password = "asdf1234";
$dbname = "id6885213_curidedb";


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
	echo "successfully connected. <br>";
}

$index_s = ['Name','Phone','Wechat','Email','Date','Time','FromCity','ToCity','Price','NoOfPpl','Type','TextArea'];

$colnames = join(', ', $index_s); // insert index join
echo $colnames;

$values = array(); // insert value join
foreach ($index_s as $k){
	array_push($values, "'" . $_POST[$k] . "'");
    echo $_POST[$k];
    echo "<br>";
}
$valuesjoin = join(', ', $values);
echo $valuesjoin; 

$sql = "INSERT INTO Board (" . $colnames . ")
VALUES ( " . $valuesjoin ." )";



// echo $index_s;
//$sql = "INSERT INTO Board (" . $colnames . ")
//VALUES ('John', '12454613', 'sad123', 'john@example.com', '2018-08-28', '12:12:12', 'Ithaca', 'NewYork', '$123', '2', 'Order', 'dfsdafsdfsadfsdafsdafsdfsdafssfsd')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}


$conn->close();
?>


