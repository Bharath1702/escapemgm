<?php
// Check if the form is submitted
if(isset($_POST['name']) && isset($_POST['mail']) && isset($_POST['subject'])){
    echo"all set";
    $name = $_POST['name'];
    $email = $_POST['mail'];
    $subject = $_POST['subject'];

    include 'db.php';

    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert data into the contacts table
    $sql = "INSERT INTO contacts (name, email, subject) VALUES ('$name', '$email', '$subject')";
    if ($conn->query($sql) === TRUE) {
        echo"<script>alert('Sent successfully');</script>";
        header('Location:index.html');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
}else{
    echo"not set";
}
?>
