<?php 

	$conn = mysqli_connect('localhost', 'user', '12345', 'github');

	if (!$conn) {
		echo 'Connection error: ' . mysqli_connect_error();
	}

?>