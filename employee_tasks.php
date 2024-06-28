<?php
session_start();

include("php/config.php");
if(!isset($_SESSION['valid'])){
    header("Location: index.php");
    exit();
}

if(!isset($_SESSION['valid']) || $_SESSION['role'] !== 'karyawan') {
    header("Location: index.php");
    exit();
}

// Query untuk mengambil data pesanan dari tabel orders
$query = "SELECT * FROM orders WHERE employee_id=".$_SESSION['id'];
$result = mysqli_query($con, $query);

if (!$result) {
    die('Query Error: ' . mysqli_error($con));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Karyawan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style/stylee.css">
    <style>
        .task-box {
            margin: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #F0F0F0;
            border-radius: 20px;
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
            border-radius: 5px;
            background-color: #F0F0F0;
            cursor: pointer;
        }
        .task-item h4 {
            margin-bottom: 5px;
        }
        .task-item p {
            margin-bottom: 0;
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
                <ul class="task-list">
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <li class="task-item" data-order-id="<?php echo $row['order_id']; ?>">
                            <h4>Pesanan ID: <?php echo $row['order_id']; ?></h4>
                            <p>Jumlah Pesanan: <?php echo $row['jumlah_pesanan']; ?></p>
                            <p>Alamat Pengiriman: <?php echo $row['alamat_pengiriman']; ?></p>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>

        <!-- Detail Pemain -->
        <div class="col-md-7" ">
            <div class="task-box" >
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
});

function fetchPlayerDetails(orderId) {
    fetch(`fetch_player_details.php?order_id=${orderId}`)
        .then(response => response.text())
        .then(data => {
            document.getElementById('player-details').innerHTML = data;
        })
        .catch(error => console.error('Error:', error));
}


</script>

</body>
</html>
