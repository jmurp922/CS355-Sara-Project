<?php
	$servername = '149.4.211.180';
	$username = 'muja2383';
	$password = '23022383';
	// Create connection
	$conn = new mysqli($servername, $username, $password);
	// Check connection
	if ($conn->connect_error) {
   	 die("Connection failed: " . $conn->connect_error);
	}
	$sql = "USE muja2383";
	if ($conn->query($sql) === TRUE) {
	} else {
 	echo $conn->error;
	}
	
	echo "<br>";
	if (isset($_POST['submit'])) {
		if (!empty($_POST['hotel'])) {
			//Counting number of checked boxes 
			echo " You have selected " .$checked_count. " options. </br>";
			foreach($_POST['hotel'] as $selected) {
				echo "<p>" .selected. "</p>;
				}
			}
		}
	
	echo "<br>";

echo "Connected successfully \n";
if(isset($_POST['submit'])){
	if(!empty($_POST['hotel'])) {
		// Counting number of checked checkboxes.
		$checked_count = count($_POST['hotel']);
		echo "You have selected following ".$checked_count." option(s): <br/>";
		// Loop to store and display values of individual checked checkbox.
		foreach($_POST['hotel'] as $selected) {
		echo "<p>".$selected ."</p>";
		}
		echo "<br/><b>Note :</b> <span>Similarily, You Can Also Perform CRUD Operations using These Selected Values.</span>";
	}
	else{
	echo "<b>Please Select Atleast One Option.</b>";
	}
}
?>
