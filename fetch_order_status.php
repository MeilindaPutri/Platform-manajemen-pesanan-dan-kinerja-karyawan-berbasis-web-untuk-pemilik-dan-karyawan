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

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            echo '<table class="table">';
            echo '<thead><tr><th>Nama Pemain</th><th>Nomor Pemain</th><th>Ukuran</th><th>Status</th></tr></thead>';
            echo '<tbody>';
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['nama_pemain']) . '</td>';
                echo '<td>' . htmlspecialchars($row['nomor_pemain']) . '</td>';
                echo '<td>' . htmlspecialchars($row['ukuran']) . '</td>';
                echo '<td>' . htmlspecialchars($row['status']) . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
        } else {
            echo '<p>Tidak ada pemain untuk pesanan ini.</p>';
        }
    } else {
        echo '<p>Gagal mengambil data pemain.</p>';
    }

    $stmt->close();
} else {
    echo '<p>Order ID tidak ditemukan.</p>';
}
?>
