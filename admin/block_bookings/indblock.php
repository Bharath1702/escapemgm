<?php
include 'db.php';
if(isset($_POST['game']) && isset($_POST['fromdate']) && isset($_POST['todate'])){
    $fromdate = strtotime($_POST['fromdate']);
    $todate   = strtotime($_POST['todate']);
    $game = $_POST['game'];
    

    // Prepare SQL statements for each game
    $stmt = $conn->prepare("INSERT INTO $game (id, name, email, mobile, date, no_of_players, timeslot_id, txnId) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    // Loop through each date from fromdate to todate
    for ($date = $fromdate; $date <= $todate; $date = strtotime('+1 day', $date)) {
        // Data array for each timeslot
        $data = array(
            array('', 'blocked', '', '', date('Y-m-d', $date), '', 1, 'blocked'),
            array('', 'blocked', '', '', date('Y-m-d', $date), '', 2, 'blocked'),
            array('', 'blocked', '', '', date('Y-m-d', $date), '', 3, 'blocked'),
            array('', 'blocked', '', '', date('Y-m-d', $date), '', 4, 'blocked'),
            array('', 'blocked', '', '', date('Y-m-d', $date), '', 5, 'blocked'),
            array('', 'blocked', '', '', date('Y-m-d', $date), '', 6, 'blocked'),
            array('', 'blocked', '', '', date('Y-m-d', $date), '', 7, 'blocked'),
            array('', 'blocked', '', '', date('Y-m-d', $date), '', 8, 'blocked'),
        );

        foreach ($data as $row) {
            // Execute each prepared statement for each data entry
            $stmt->bind_param("issssiis", $row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7]);
            $stmt->execute();

        }
    }

    // Close statements
    $stmt->close();

    // Close connection
    $conn->close();

    echo "<script>alert('Blocked Successfully');window.location.href='./'</script>";

} else {
    echo "error";
}
?>
