<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '/home/escapemgm/public_html/phpmailer/src/Exception.php';
require '/home/escapemgm/public_html/phpmailer/src/PHPMailer.php';
require '/home/escapemgm/public_html/phpmailer/src/SMTP.php';

class Mail
{
function sendMail($to='',$msg='')
{
	try {

	$mail = new PHPMailer(true);
	$mail->SMTPDebug = 2;
	$mail->isSMTP();
	$mail->Host	 = 'smtp-relay.brevo.com';
	$mail->SMTPAuth = true;
	$mail->Username = 'bharathac7@gmail.com';
	$mail->Password = 'SDCtQNnda4FVW6rq';
	$mail->SMTPSecure = 'tls';
	$mail->Port	 = 587;
	$mail->setFrom('ESCAPEROOM', 'escapemgm@escapemgm.com');
	$mail->addAddress($to);
	$mail->isHTML(true);
	$mail->Subject = 'Payment Successfully Completed';
	$mail->Body = $msg;
	// $mail->AltBody = 'Body in plain text for non-HTML mail clients';
	return $mail->send();

} catch (Exception $e) {
	echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
}

}
?>