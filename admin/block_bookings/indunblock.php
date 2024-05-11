<?php
include 'db.php';
if(isset($_POST['fromdate']) && isset($_POST['todate'])){
    $fromdate = strtotime($_POST['fromdate']);
    $todate   = strtotime($_POST['todate']);
    $game = $_POST['game'];

    // Prepare SQL statements for each game
    $stmt = $conn->prepare("DELETE FROM $game WHERE name = 'blocked' AND date BETWEEN ? AND ?");
    
    // Loop through each date from fromdate to todate
    for ($date = $fromdate; $date <= $todate; $date = strtotime('+1 day', $date)) {
        // Execute each prepared statement for each game
        $stmt->bind_param("ss", date('Y-m-d', $date), date('Y-m-d', $date));
        $stmt->execute();
    }

    // Close statements
    $stmt->close();

    // Close connection
    $conn->close();

    echo "<script>alert('Unblocked Successfully');window.location.href='./'</script>";

} else {
    echo "error";
}
?>
