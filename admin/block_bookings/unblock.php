<?php
include 'db.php';
if(isset($_POST['fromdate']) && isset($_POST['todate'])){
    $fromdate = strtotime($_POST['fromdate']);
    $todate   = strtotime($_POST['todate']);
    $game = $_POST['game'];

    // Prepare SQL statements for each game
    $stmt_deadly_chamber = $conn->prepare("DELETE FROM deadly_chamber WHERE name = 'blocked' AND date BETWEEN ? AND ?");
    $stmt_nuclear_bunker = $conn->prepare("DELETE FROM the_nuclear_bunker WHERE name = 'blocked' AND date BETWEEN ? AND ?");
    $stmt_ruins_of_hampi = $conn->prepare("DELETE FROM ruins_of_hampi WHERE name = 'blocked' AND date BETWEEN ? AND ?");
    $stmt_killbill = $conn->prepare("DELETE FROM killbill WHERE name = 'blocked' AND date BETWEEN ? AND ?");
    $stmt_ransom = $conn->prepare("DELETE FROM ransom WHERE name = 'blocked' AND date BETWEEN ? AND ?");

    // Loop through each date from fromdate to todate
    for ($date = $fromdate; $date <= $todate; $date = strtotime('+1 day', $date)) {
        // Execute each prepared statement for each game
        $stmt_deadly_chamber->bind_param("ss", date('Y-m-d', $date), date('Y-m-d', $date));
        $stmt_deadly_chamber->execute();

        $stmt_nuclear_bunker->bind_param("ss", date('Y-m-d', $date), date('Y-m-d', $date));
        $stmt_nuclear_bunker->execute();

        $stmt_ruins_of_hampi->bind_param("ss", date('Y-m-d', $date), date('Y-m-d', $date));
        $stmt_ruins_of_hampi->execute();

        $stmt_killbill->bind_param("ss", date('Y-m-d', $date), date('Y-m-d', $date));
        $stmt_killbill->execute();

        $stmt_ransom->bind_param("ss", date('Y-m-d', $date), date('Y-m-d', $date));
        $stmt_ransom->execute();
    }

    // Close statements
    $stmt_deadly_chamber->close();
    $stmt_nuclear_bunker->close();
    $stmt_ruins_of_hampi->close();
    $stmt_killbill->close();
    $stmt_ransom->close();

    // Close connection
    $conn->close();

    echo "<script>alert('Unblocked Successfully');window.location.href='./'</script>";

} else {
    echo "error";
}
?>
