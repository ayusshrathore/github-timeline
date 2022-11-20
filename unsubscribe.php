<?php

include "config/db_connect.php";

$email = "";

if (isset($_GET['email'])) {
  $email = mysqli_real_escape_string($conn, $_GET['email']);
}

$sql = "UPDATE timeline SET is_verified='0' WHERE email='$email'";

if (mysqli_query($conn, $sql)) {
  echo 'Your email has been unsubscribed.';
} else {
  // echo 'Query error: ' . mysqli_error($conn) . '<br />';
  echo 'An error occured while unsubscribing your email.';
}

?>