<?php
include 'db.php';
if(isset($_POST['date']) && isset($_POST['game'])){
    $date = $_POST['date'];
    $game = $_POST['game'];
    $data = array(
        array('', 'blocked', '', '', '', $date, 1, 'blocked'),
        array('', 'blocked', '', '', '', $date, 2, 'blocked'),
        array('', 'blocked', '', '', '', $date, 3, 'blocked'),
        array('', 'blocked', '', '', '', $date, 4, 'blocked'),
        array('', 'blocked', '', '', '', $date, 5, 'blocked'),
        array('', 'blocked', '', '', '', $date, 6, 'blocked'),
        array('', 'blocked', '', '', '', $date, 7, 'blocked'),
        array('', 'blocked', '', '', '', $date, 8, 'blocked'),
    );
    
    // Prepare and bind SQL statement
    $stmt = $conn->prepare("INSERT INTO $game (id, name, email, mobile, date, no_of_players, timeslot_id, txnId) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
    // Bind parameters and execute the statement for each data entry
    foreach ($data as $row) {
        $stmt->bind_param("issssiis", $row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7]);
        $stmt->execute();
    }
    
    // Close statement and connection
    $stmt->close();
    $conn->close();
    
    echo "<script>alert('Blocked Successfully');window.location.href='./'</script>";
    
}
?>
