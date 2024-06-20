<?php
include("php/config.php");

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Query untuk mengambil data pemain berdasarkan order_id
    $query = "SELECT * FROM players WHERE order_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Menghitung jumlah pemain yang telah selesai
    $completed_count = 0;
    while ($row = $result->fetch_assoc()) {
        if ($row['status'] == 'selesai') {
            $completed_count++;
        }
    }

    $stmt->close();

    // Jika semua pemain telah selesai, hapus pesanan dari tabel orders
    if ($completed_count == 0) {
        $delete_query = "DELETE FROM orders WHERE order_id = ?";
        $stmt_delete = $con->prepare($delete_query);
        $stmt_delete->bind_param("i", $order_id);
        $stmt_delete->execute();

        if ($stmt_delete->affected_rows > 0) {
            echo 'success';
        } else {
            echo 'failed';
        }

        $stmt_delete->close();
    } else {
        echo 'failed';
    }
} else {
    echo 'failed';
}

$con->close();
?>
