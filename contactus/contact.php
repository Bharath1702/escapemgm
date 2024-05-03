<?php
if(isset($_POST['submit'])){
    $to = 'wevdevgallery@gmial.com'; // Your email address
    $subject = $_POST['subject'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = "Name: $name\n\nEmail: $email\n\nPhone: $phone\n\nSubject: $subject";
    $headers = "From: $name <$email>";
    include 'db.php';
    $sql = "INSERT INTO contacts (name, email,phone,subject) VALUES ('$name', '$email','$phone','$subject')";
    if(mail($to, $subject, $message, $headers) || $conn->query($sql) === TRUE){
        echo "<script>alert('Sent successfully');window.location.href = 'index.html';</script>";
    } else{
        echo "<script>alert('Unsuccessful :-(');window.location.href = 'index.html';</script>";
    }
}
?>
