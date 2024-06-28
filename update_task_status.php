<?php
include("php/config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];
    $statuses = $_POST['status'];

    foreach ($statuses as $player_id => $status) {
        $query = "UPDATE players SET status = '$status' WHERE player_id = $player_id";
        mysqli_query($con, $query);
    }

    header("Location: employee_tasks.php");
    exit();
}
?>
