<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function sendMail($to, $subject, $message)
{
	$mail = new PHPMailer(true);

	try {
		$mail->IsSMTP();
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = 'ssl';
		$mail->Host = "smtp.gmail.com";
		$mail->Port = 465;
		$mail->IsHTML(true);
		$mail->Username = "heyfreaker@gmail.com";
		$mail->Password = "rormumkpjczsxesa";
		$mail->SetFrom("heyfreaker@gmail.com");
		$mail->Subject = $subject;
		$mail->Body = $message;
		$mail->AddAddress("$to");

		if (!$mail->Send()) {
			echo "Mailer Error: " . $mail->ErrorInfo;
			return false;
		} else {
			return true;
		}
	} catch (Exception $e) {
		echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		return false;
	}
}