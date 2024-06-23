<?php
session_start();
include("php/config.php");

if(!isset($_SESSION['valid'])){
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['id'];
$jumlah_pesanan = $_POST['jum'];
$alamat_pengiriman = $_POST['address'];
$player_names = $_POST['player_names'];
$player_numbers = $_POST['player_numbers'];
$player_sizes = $_POST['player_sizes'];

// Insert the order into the orders table
$stmt = $con->prepare("INSERT INTO orders (user_id, jumlah_pesanan, alamat_pengiriman, tanggal_pesanan) VALUES (?, ?, ?, NOW())");
if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($con->error));
}
$stmt->bind_param("iis", $user_id, $jumlah_pesanan, $alamat_pengiriman);
$stmt->execute();
$order_id = $stmt->insert_id;
$stmt->close();

// Insert each player into the players table
$stmt = $con->prepare("INSERT INTO players (order_id, nama_pemain, nomor_pemain, ukuran) VALUES (?, ?, ?, ?)");
if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($con->error));
}
foreach ($player_names as $index => $player_name) {
    $player_number = $player_numbers[$index];
    $player_size = $player_sizes[$index];
    $stmt->bind_param("isss", $order_id, $player_name, $player_number, $player_size);
    $stmt->execute();
}
$stmt->close();

header("Location: home.php");
exit();
?>
