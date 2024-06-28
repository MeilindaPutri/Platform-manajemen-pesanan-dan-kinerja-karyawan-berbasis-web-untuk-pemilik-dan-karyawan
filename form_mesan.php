  <?php 
    session_start();

    include("php/config.php");
    if(!isset($_SESSION['valid'])){
      header("Location: index.php");
    }
  ?>

  <!DOCTYPE html>
  <html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible"="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Memesan Jersey</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
      <link rel="stylesheet" href="style/stylee.css">
          <style>
        .container {
          margin-bottom: 25px;
          width: 800px;
        }
        .box {
          width: 100%;
        }
        .aa {
          height: 35px;
          width: 33%;
          border: 0;
          border-radius: 5px;
          font-size: 15px;
          cursor: pointer;
          transition: all .3s;
          margin-top: 10px;
          padding: 0px 10px;
          background-color: #213555;
          text-align: center;
          border: black solid 1px;
          display: flex;
          align-items: center;
          justify-content: center;
        }
        .aa:hover {
          background-color: teal;
        }
        .aa a {
          text-decoration: none;
          color: white;
        }
        .player-box {
          margin-bottom: 15px;
          border: 1px solid #ddd;
          padding: 15px;
          border-radius: 5px;
        }
        .remove-player {
          cursor: pointer;
          color: red;
          font-weight: bold;
        }
      </style>

  </head>
  <body>
      
  <div class="nav">
          <div class="logo">
              <p><a href="home.php"> EXECUTIVE</a></p>
          </div>

          <div class="right-links">
              <a href="php/logout.php"> <button class="btn">Keluar</button> </a>
          </div>
      </div>

  <div class="container mt-5">
    <div class="box form-box">
      <form action="submit_order.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
          <label for="jumlah">Jumlah Pesanan</label>
          <input type="number" class="form-control" id="jumlah" name="jum" min="1" required>
        </div>
        <div class="mb-3">
          <label for="design">Desain Dan Pembayaran :</label>
          <br>
          <div class="aa">
          <a href="https://api.whatsapp.com/qr/BG4WEME6FWU5G1?autoload=1&app_absent=0" target="_blank">Hubungi Admin di whatsap!</a>
          </div>
        </div>

        <div id="players">
          <div class="player-box">
            <div class="row g-3">
              <div class="col">
                <label for="namee">Nama Pemain</label>
                <input type="text" class="form-control" name="player_names[]" placeholder="Nama Pemain" required>
              </div>
              <div class="col">
                <label for="number">Nomor Pemain</label>
                <input type="text" class="form-control" name="player_numbers[]" placeholder="Nomor Pemain" required>
              </div>
              <div class="col">
                <label for="size">Ukuran jersey</label>
                <select class="form-control" name="player_sizes[]" required>
                  <option value="S">S</option>
                  <option value="M">M</option>
                  <option value="L">L</option>
                  <option value="XL">XL</option>
                  <option value="XXL">XXL</option>
                </select>
              </div>
              <div class="col-1 d-flex align-items-end">
            <span class="remove-player" onclick="removePlayer(this)"><i class="bi bi-trash"></i></span>
          </div>
            </div>
          </div>
        </div>

        <button type="button" class="btn btn-success mb-3" onclick="addPlayer()">Tambah Pemain</button>
        <hr>
        <div class="mb-3">
          <label for="alamat" class="form-label">Alamat Pengiriman</label>
          <textarea id="alamat" name="address" rows="3" row="3" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
    <label for="exampleFormControlFile1">Bukti Pembayaran pertama (setengah harga total)</label>
    <input type="file" class="form-control" id="exampleFormControlFile1" name="payment_proof" required>
</div>

                  <div class="mb-3">
                      <label for="total-harga">Harga per jersey</label>
                      <input type="text" class="form-control" id="total-harga" name="total_harga" placeholder="Rp.125.000" readonly>
                  </div>


        <div class="text-center">
          <button type="submit" class="btn btn-primary">Pesan Sekarang</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function addPlayer() {
      const playerBox = document.createElement('div');
      playerBox.classList.add('player-box');
      playerBox.innerHTML = `
        <div class="row g-3">
          <div class="col">
            <label for="namee">Nama Pemain</label>
            <input type="text" class="form-control" name="player_names[]" placeholder="Nama Pemain" required>
          </div>
          <div class="col">
            <label for="number">Nomor Pemain</label>
            <input type="text" class="form-control" name="player_numbers[]" placeholder="Nomor Pemain" required>
          </div>
          <div class="col">
            <label for="size">Ukuran</label>
            <select class="form-control" name="player_sizes[]" required>
              <option value="S">S</option>
              <option value="M">M</option>
              <option value="L">L</option>
              <option value="XL">XL</option>
              <option value="XXL">XXL</option>
            </select>
          </div>
          <div class="col-1 d-flex align-items-end">
            <span class="remove-player" onclick="removePlayer(this)"><i class="bi bi-trash"></i></span>
          </div>
        </div>
      `;
      document.getElementById('players').appendChild(playerBox);
    }

    function removePlayer(element) {
      element.closest('.player-box').remove();
    }
  </script>

  </body>
  </html>





