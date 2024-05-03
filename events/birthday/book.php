<?php
include '../db.php';
if(isset($_POST['name'])){
    $name    = $_POST['name'];
    $mobile  = $_POST['mobile'];
    $email   = $_POST['email'];
    $date    = $_POST['date'];    
    $event   = 'BIRTHDAY';
    $qty     = $_POST['qty'];
    $details = $_POST['details'];
    
    // Prepare the SQL statement using placeholders
    $sql = "INSERT INTO events (name, email, date, mobile, event, details, qty) VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);
    
    // Bind parameters and execute the statement
    $stmt->bind_param("ssssssi", $name, $email, $date, $mobile, $event, $details, $qty);
    
    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
    // Close the statement
    $stmt->close();
}else{
    echo "<script>alert('ohoh something went wrong')</script>";
}
?>
