<?php
session_start();

include("php/config.php");
if (!isset($_SESSION['valid'])) {
    header("Location: index.php");
    exit();
}

if (!isset($_SESSION['valid']) || $_SESSION['role'] !== 'owner') {
    header("Location: index.php");
    exit();
}

// Query untuk mengambil data pesanan dari tabel orders dengan informasi karyawan dari tabel users
$orderQuery = "SELECT o.order_id, o.jumlah_pesanan, o.alamat_pengiriman, o.tanggal_pesanan, u.username AS karyawan_nama, o.employee_id
               FROM orders o
               LEFT JOIN users u ON o.employee_id = u.id";

$orderResult = mysqli_query($con, $orderQuery);

if (!$orderResult) {
    die('Query Error: ' . mysqli_error($con));
}

// Query untuk mengambil data karyawan dari tabel users
$employeeQuery = "SELECT * FROM users WHERE role='karyawan'";
$employeeResult = mysqli_query($con, $employeeQuery);

if (!$employeeResult) {
    die('Query Error: ' . mysqli_error($con));
}

// Proses form jika ada permintaan POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id']) && isset($_POST['employee_id'])) {
    $orderId = $_POST['order_id'];
    $employeeId = $_POST['employee_id'];

    $updateQuery = "UPDATE orders SET employee_id='$employeeId' WHERE order_id='$orderId'";
    if (!mysqli_query($con, $updateQuery)) {
        die('Query Error: ' . mysqli_error($con));
    }
    // Refresh halaman setelah update
    header("Location: owner.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Owner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style/stylee.css">
    <style>
        .task-box {
            margin: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 20px;
            background-color: white;
            width: 500px;
            margin-right: 70px;
        }
        .task-list {
            list-style-type: none;
            padding: 0;
        }
        .task-item {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 20px;
            background-color: #f9f9f9;
            cursor: pointer;
        }
        .task-item h4 {
            margin-bottom: 5px;
        }
        .task-item p {
            margin-bottom: 0;
        }
        .task-box ul {
            list-style-type: none;
            padding: 0;
        }

        .task-box ul li {
            list-style-type: none;
        }
    </style>
</head>
<body>

<div class="nav">
    <div class="logo">
        <p><a href="home.php">EXECUTIVE</a></p>
    </div>
    <div class="right-links">
        <a href="php/logout.php"><button class="btn">Keluar</button></a>
    </div>
</div>

<div class="container mt-8">
    <div class="row">
        <!-- Daftar Pesanan -->
        <div class="col-md-5">
            <div class="task-box">
                <h2>Daftar Pesanan</h2>
                <?php while ($row = mysqli_fetch_assoc($orderResult)) { ?>
                    <li class="task-item table" data-order-id="<?php echo $row['order_id']; ?>">
                        <h4>Pesanan ID: <?php echo $row['order_id']; ?></h4>
                        <p>Jumlah Pesanan: <?php echo $row['jumlah_pesanan']; ?></p>
                        <p>Alamat Pengiriman: <?php echo $row['alamat_pengiriman']; ?></p>
                        <p>Tanggal Pesanan: <?php echo $row['tanggal_pesanan']; ?></p>
                        <p>Karyawan yang Ditugaskan: <?php echo $row['karyawan_nama']; ?></p>
                        <form method="POST" action="owner.php" class="mt-2">
                            <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                            <select name="employee_id" class="form-select" required>
                                <option value="">Pilih Karyawan</option>
                                <?php 
                                mysqli_data_seek($employeeResult, 0); // Reset pointer untuk mengulang pemilihan
                                while ($employee = mysqli_fetch_assoc($employeeResult)) {
                                    $selected = isset($row['employee_id']) && $row['employee_id'] == $employee['Id'] ? 'selected' : '';
                                ?>
                                    <option value="<?php echo $employee['Id']; ?>" <?php echo $selected; ?>>
                                        <?php echo $employee['Username']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <button type="submit" class="btn btn-primary mt-2">Tugaskan</button>
                        </form>
                        <button class="btn btn-danger mt-2 delete-order" data-order-id="<?php echo $row['order_id']; ?>">Hapus</button>
                    </li>
                <?php } ?>
            </div>
        </div>

        <!-- Detail Pemain -->
        <div class="col-md-7">
            <div class="task-box">
                <h2>Detail Pemain</h2>
                <div id="player-details"></div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const taskItems = document.querySelectorAll('.task-item');
    taskItems.forEach(item => {
        item.addEventListener('click', function () {
            const orderId = this.getAttribute('data-order-id');
            fetchPlayerDetails(orderId);
        });
    });

    const deleteButtons = document.querySelectorAll('.delete-order');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const orderId = this.getAttribute('data-order-id');
            if (confirm('Apakah Anda yakin ingin menghapus pesanan ini?')) {
                deleteOrder(orderId);
            }
        });
    });
});

function fetchPlayerDetails(orderId) {
    fetch(`order_details.php?order_id=${orderId}`)
        .then(response => response.text())
        .then(data => {
            document.getElementById('player-details').innerHTML = data;
        })
        .catch(error => console.error('Error:', error));
}

function deleteOrder(orderId) {
    fetch(`delete_order.php?order_id=${orderId}`)
        .then(response => response.text())
        .then(data => {
            if (data === 'success') {
                alert('Pesanan berhasil dihapus.');
                location.reload();
            } else {
                alert('Gagal menghapus pesanan.');
            }
        })
        .catch(error => console.error('Error:', error));
}
</script>

</body>
</html>
