<?php 
session_start();

include("php/config.php");
if(!isset($_SESSION['valid'])){
    header("Location: index.php");
    exit();
}

$id = $_SESSION['id'];
$stmt = $con->prepare("SELECT Username, Email, Phone, Id, role FROM users WHERE Id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$res_Uname = $user['Username'];
$res_Email = $user['Email'];
$res_Phone = $user['Phone'];
$res_id = $user['Id'];
$role = $user['role'];

$stmt->close();

// Fetch user orders
$stmt = $con->prepare("SELECT * FROM orders WHERE user_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$orders_result = $stmt->get_result();
$orders = $orders_result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style/styles.css">
    <title>Home</title>
    <style>
    .a {
        display: inline-block;
        padding: 8px 12px;
        background-color: #B3C8CF;
        color: #fff;
        border: 1px solid #007bff;
        border-radius: 4px;
        text-decoration: none;
        text-align: center;
        transition: background-color 0.3s ease;
    }
</style>

</head>
<body>
    <div class="nav">
        <div class="logo">
            <p><a href="home.php">EXECUTIVE</a> </p>
        </div>
        <div class="right-links">
            <a href="php/logout.php"><button class="btn">Keluar</button></a>
        </div>
    </div>
    <main>
       <div class="main-box top">
          <div class="top">
            <div class="box">
                <p>Selamat datang  <b><?php echo htmlspecialchars($res_Uname); ?></b></p>            
            </div>
            <?php if ($role === 'pemesan' || $role === 'owner'): ?>
            <div class="box">
                <p>Buat Orderan Baru</p>
                <a href="form_mesan.php"><button type="button" class="btn btn-primary">Pesan Jersey</button></a>
            </div>
            <?php endif; ?>
          </div>
          <div class="bottom">
            <?php if ($role === 'pemesan' || $role === 'owner'): ?>
            <div class="box b">
                <h3>Pesanan Anda</h3>
                <?php if (empty($orders)): ?>
                    <p>Kamu Belum Memesan apapun...</p>
                <?php else: ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID Order</th>
                                <th>Jumlah</th>
                                <th>Alamat</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><a href="#" class="order-link a" data-order-id="<?php echo htmlspecialchars($order['order_id']); ?>"><?php echo htmlspecialchars($order['order_id']); ?></a></td>
                                    <td><?php echo htmlspecialchars($order['jumlah_pesanan']); ?></td>
                                    <td><?php echo htmlspecialchars($order['alamat_pengiriman']); ?></td>
                                    <td><?php echo htmlspecialchars($order['tanggal_pesanan']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
            <br>
            <div class="box" id="order-status-box" style="display:none;">
                <h3>Status Pesanan</h3>
                <div id="order-status-details"></div>
            </div>
            <?php elseif ($role === 'karyawan'): ?>
            <div class="box">
                <a href="employee_tasks.php"><button type="button" class="btn btn-secondary">Tugas Karyawan</button></a>
            </div>
            <?php elseif ($role === 'owner'): ?>
            <div class="box">
                <a href="owner_dashboard.php"><button type="button" class="btn btn-secondary">Dashboard Owner</button></a>
            </div>
            <?php endif; ?>
          </div>
       </div>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const orderLinks = document.querySelectorAll('.order-link');
            orderLinks.forEach(link => {
                link.addEventListener('click', function (event) {
                    event.preventDefault();
                    const orderId = this.getAttribute('data-order-id');
                    fetchOrderStatus(orderId);
                });
            });
        });

        function fetchOrderStatus(orderId) {
            fetch(`fetch_order_status.php?order_id=${orderId}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('order-status-details').innerHTML = data;
                    document.getElementById('order-status-box').style.display = 'block';
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>
