<?php
include("php/config.php");

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Debugging: Tampilkan order_id yang diterima

    // Query untuk mengambil data pemain berdasarkan order_id
    $query = "SELECT * FROM players WHERE order_id = $order_id";
    $result = mysqli_query($con, $query);

    if (!$result) {
        die('Query Error: ' . mysqli_error($con));
    }

    // Debugging: Tampilkan jumlah baris hasil query
    $num_rows = mysqli_num_rows($result);

    if ($num_rows > 0) {
        echo '<form action="update_task_status.php" method="POST">';
        echo '<table class="table">';
        echo '<thead><tr><th>Nama Pemain</th><th>Nomor Pemain</th><th>Ukuran</th><th>Status</th></tr></thead>';
        echo '<tbody>';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td>' . $row['nama_pemain'] . '</td>';
            echo '<td>' . $row['nomor_pemain'] . '</td>';
            echo '<td>' . $row['ukuran'] . '</td>';
            echo '<td>';
            echo '<select name="status[' . $row['player_id'] . ']" class="form-control">';
            $statuses = ['pending', 'di press', 'di jahit', 'selesai'];
            foreach ($statuses as $status) {
                $selected = ($row['status'] == $status) ? 'selected' : '';
                echo '<option value="' . $status . '" ' . $selected . '>' . ucfirst($status) . '</option>';
            }
            echo '</select>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
        echo '<input type="hidden" name="order_id" value="' . $order_id . '">';
        echo '<button type="submit" class="btn btn-primary">Update Status</button>';
        echo '</form>';
    } else {
        echo '<p>Tidak ada pemain untuk pesanan ini.</p>';
    }
}
?>
