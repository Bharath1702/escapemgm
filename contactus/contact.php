<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
 
require '/home/escapemgm/public_html/phpmailer/src/Exception.php';
require '/home/escapemgm/public_html/phpmailer/src/PHPMailer.php';
require '/home/escapemgm/public_html/phpmailer/src/SMTP.php';
 
// Check if form is submitted
if(isset($_POST['submit'])){
    // Include database connection
    
    
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $subject = $_POST['subject'];
    
    // Prepare SQL statement
   
    
    // Instantiate PHPMailer
    $mail = new PHPMailer(true);
    
    try {
        //Server settings
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host       = 'smtp.gmail.com';          // Specify main and backup SMTP servers
        $mail->SMTPAuth   = true;                             // Enable SMTP authentication
        $mail->Username   = 'brackets.developer17@gmail.com';           // SMTP username
        $mail->Password   = 'nzrlvzmsdobatsfn';                // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption
        $mail->Port       = 587;                              // TCP port to connect to
 
        //Recipients
    $mail->setFrom('escapemgm@escapemgm.com', 'escapemgm');
    $mail->addAddress('escaperoombangalore@gmail.com', 'escaperoom');     // Add a recipient
    $mail->addAddress('escapemgm@escapemgm.com');               // Name is optional
    $mail->addReplyTo($email, $name);
    $mail->addCC('cc@example.com');
    $mail->addBCC('bcc@example.com');
 
        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Wants to contact';
        $mail->Body    = "Name: $name<br>Email: $email<br>Phone: $phone<br>Subject: $subject";
        // Send email
        $mail->send();
         include 'db.php';
        $sql = "INSERT INTO contacts (name, email,phone,subject) VALUES (?,?,?,?)";
        // Bind parameters and execute the statement
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $email, $phone, $subject);
    
    if ($stmt->execute()) {
        echo "<script>alert('Sent successfully');window.location.href = 'index.html';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
        
        
        
        
        
    } catch (Exception $e) {
        // Error message
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    // If form is not submitted
    echo "<script>alert('Form submission error');window.location.href = 'index.html';</script>";
}
?>
