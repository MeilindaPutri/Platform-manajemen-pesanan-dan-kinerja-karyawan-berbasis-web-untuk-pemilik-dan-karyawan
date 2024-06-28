<?php
include("php/config.php");

if (isset($_GET['order_id'])) {
    $orderId = $_GET['order_id'];

    // Query untuk menghapus data jersey berdasarkan order_id
    $deleteJerseyQuery = "DELETE FROM players WHERE order_id = $orderId";
    if (!mysqli_query($con, $deleteJerseyQuery)) {
        die('Query Error: ' . mysqli_error($con));
    }

    // Query untuk menghapus data pesanan berdasarkan order_id
    $deleteOrderQuery = "DELETE FROM orders WHERE order_id = $orderId";
    if (mysqli_query($con, $deleteOrderQuery)) {
        echo 'success';
    } else {
        die('Query Error: ' . mysqli_error($con));
    }
} else {
    echo 'error';
}
?>
