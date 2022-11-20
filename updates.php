<?php

include "config/db_connect.php";
include "mail.php";


$sql = 'SELECT name, email FROM timeline WHERE is_verified = 1';

$result = mysqli_query($conn, $sql);

$users = mysqli_fetch_all($result, MYSQLI_ASSOC);

mysqli_free_result($result);

mysqli_close($conn);

// while (true) {

$response = file_get_contents("https://github.com/timeline");
$xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
$array = json_decode(json_encode((array) $xml), TRUE);
$data = $array['entry'];
$message = "";

for ($i = 0; $i < count($data); $i++) {
  $msg = (
    "<html>" .
    "<head>" .
    "<meta charset=\"utf-8\">" .
    "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">" .
    "</head>" .
    "<body>" .
    "<div>" .
    $data[$i]['content'] .
    "</div>" .
    "</body>" .
    "</html>" .
    "-------------------------------------------------------------------------" . "<br /> <br />"
  );
  $message .= $msg;
}
$subject = "GitHub Timeline Updates";
if (count($users) > 0) {
  foreach ($users as $user) {
    $name = $user['name'];
    $email = $user['email'];
    $server = $_SERVER['SERVER_NAME'];
    $url = "http://$server/github/unsubscribe.php?email=$email";
    $message .= "<a href=\"$url\">Unsubscribe</a> to stop receiving updates.";
    if (sendMail($email, $subject, $message)) {
      echo "Mail sent to $name ($email)";
    } else {
      echo "Mail not sent to $name ($email)";
    }
  }
} else {
  echo "No users found.";
}
// sleep(5);
// }
?>