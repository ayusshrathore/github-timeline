<?php
include "config/db_connect.php";
include "mail.php";

$name = $email = "";
$errors = array('name' => '', 'email' => '');
$is_submit = false;
$is_submit_error = false;

if (isset($_POST['submit'])) {

	if (empty($_POST['name'])) {
		$errors['name'] = "A name is required! <br />";
	} else {
		$name = $_POST['name'];
		if (!preg_match('/^[a-zA-Z\s]+$/', $name)) {
			$errors['name'] = 'Name must be letters and spaces only<br />';
		}
	}

	if (empty($_POST['email'])) {
		$errors['email'] = "An email is required! <br />";
	} else {
		$email = $_POST['email'];
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$errors['email'] = 'A valid email is required!<br />';
		}
	}

	if (array_filter($errors)) {
		echo 'Error occured.';
	} else {
		$name = mysqli_real_escape_string($conn, $_POST['name']);
		$email = mysqli_real_escape_string($conn, $_POST['email']);

		$sql = "INSERT INTO timeline(name,email) VALUES('$name','$email')";

		if (mysqli_query($conn, $sql)) {
			$sub = "Verification Mail || GitHub Timeline Updates";
			$server = $_SERVER['SERVER_NAME'];
			$url = "http://$server/github/verify.php?email=$email";
			$msg = "<b>Hello there, $name! </b> <br /> Kindly verify your email using the below link. <br /> <a href=\"$url\">Verify</a> <br /> <br /> <br /> <b>Regards,</b> <br /> <b>GitHub Timeline Updates</b>";
			if (sendMail($email, $sub, $msg)) {
				$is_submit = true;
			} else {
				$is_submit_error = true;
			}
		} else {
			$errors['email'] = 'This email is already in use.';
		}
	}
}

?>


<!DOCTYPE html>
<html>

<?php include "views/header.php" ?>

<section class="container grey-text">
	<h5 class="center">Subscribe to our updates</h5>
	<?php if ($is_submit) { ?>
	<p class="center">(We have sent you a verification link to your email. Kindly verify your email to receive updates)
	</p>
	<?php } ?>
	<?php if ($is_submit_error) { ?>
	<p class="center red-text">(There was an error sending the verification mail. Please check if the email exists)</p>
	<?php } ?>
	<form class="white" action="index.php" method="POST">
		<label>Name:</label>
		<input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>">
		<div class="red-text">
			<?php echo $errors['name']; ?>
		</div>
		<label>Email:</label>
		<input type="text" name="email" value="<?php echo htmlspecialchars($email); ?>">
		<div class="red-text">
			<?php echo $errors['email']; ?>
		</div>
		<div class="center btn-submit">
			<input type="submit" name="submit" value="subscribe" class="btn brand z-depth-0">
		</div>
	</form>
</section>

<?php include "views/footer.php" ?>

</html>