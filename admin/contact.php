<?php
session_start();
if($_SESSION['loggedin']==true){
    echo"<script>alert('Login Success');</script>";
}
else{
    echo"<script>alert('You cant fool me');window.location.href = './index.html';</script>";
}
// Database connection
include 'db.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Add Contact
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_contact'])) {
    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $subject = sanitize_input($_POST['subject']);

    $sql = "INSERT INTO contacts (name, email, subject) VALUES ('$name', '$email', '$subject')";

    if ($conn->query($sql) === TRUE) {
        echo "New contact added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Delete Contact
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_contact'])) {
    $id = sanitize_input($_POST['id']);

    $sql = "DELETE FROM contacts WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "Contact deleted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Update Contact
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_contact'])) {
    $id = sanitize_input($_POST['id']);
    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $subject = sanitize_input($_POST['subject']);

    $sql = "UPDATE contacts SET name='$name', email='$email', subject='$subject' WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "Contact updated successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch all contacts
$sql = "SELECT * FROM contacts";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Contact Management</title>
    <!-- CSS styles -->
</head>
<body>
    <h1>Contact Management</h1>
    
    <!-- Add Contact Form -->
    <h2>Add Contact</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        Name: <input type="text" name="name" required><br>
        Email: <input type="email" name="email" required><br>
        Subject: <input type="text" name="subject" required><br>
        <input type="submit" name="add_contact" value="Add Contact">
    </form>
    
    <!-- List Contacts -->
    <h2>List of Contacts</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Subject</th>
            <th>Action</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["name"] . "</td>";
                echo "<td>" . $row["email"] . "</td>";
                echo "<td>" . $row["subject"] . "</td>";
                echo "<td>
                        <form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post'>
                            <input type='hidden' name='id' value='" . $row["id"] . "'>
                            <input type='submit' name='delete_contact' value='Delete'>
                        </form>
                        <form action='update_contact.php' method='get'>
                            <input type='hidden' name='id' value='" . $row["id"] . "'>
                            <input type='submit' value='Update'>
                        </form>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No contacts found</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>
