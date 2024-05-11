<?php
include 'db.php';
if(isset($_POST['fromdate']) && isset($_POST['todate'])){
    $fromdate = strtotime($_POST['fromdate']);
    $todate   = strtotime($_POST['todate']);
    

    // Prepare SQL statements for each game
    $stmt_deadly_chamber = $conn->prepare("INSERT INTO deadly_chamber (id, name, email, mobile, date, no_of_players, timeslot_id, txnId) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt_nuclear_bunker = $conn->prepare("INSERT INTO the_nuclear_bunker (id, name, email, mobile, date, no_of_players, timeslot_id, txnId) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt_ruins_of_hampi = $conn->prepare("INSERT INTO ruins_of_hampi (id, name, email, mobile, date, no_of_players, timeslot_id, txnId) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt_killbill = $conn->prepare("INSERT INTO killbill (id, name, email, mobile, date, no_of_players, timeslot_id, txnId) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt_ransom = $conn->prepare("INSERT INTO ransom (id, name, email, mobile, date, no_of_players, timeslot_id, txnId) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

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
            $stmt_deadly_chamber->bind_param("issssiis", $row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7]);
            $stmt_deadly_chamber->execute();

            $stmt_nuclear_bunker->bind_param("issssiis", $row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7]);
            $stmt_nuclear_bunker->execute();

            $stmt_ruins_of_hampi->bind_param("issssiis", $row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7]);
            $stmt_ruins_of_hampi->execute();

            $stmt_killbill->bind_param("issssiis", $row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7]);
            $stmt_killbill->execute();

            $stmt_ransom->bind_param("issssiis", $row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7]);
            $stmt_ransom->execute();
        }
    }

    // Close statements
    $stmt_deadly_chamber->close();
    $stmt_nuclear_bunker->close();
    $stmt_ruins_of_hampi->close();
    $stmt_killbill->close();
    $stmt_ransom->close();

    // Close connection
    $conn->close();

    echo "<script>alert('Blocked Successfully');window.location.href='./'</script>";

} else {
    echo "error";
}
?>
