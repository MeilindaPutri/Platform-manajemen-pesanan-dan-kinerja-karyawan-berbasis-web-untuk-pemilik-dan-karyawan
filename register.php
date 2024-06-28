<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style/stylee.css">
    <title>Register</title>
</head>
<body>
    <br>
      <div class="container">
        <div class="box form-box">

        <?php 
         
         include("php/config.php");
         if(isset($_POST['submit'])){
            $username = $_POST['username'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $password = $_POST['password'];

         //verifying the unique email

         $verify_query = mysqli_query($con,"SELECT Username FROM users WHERE Username='$username'");

         if(mysqli_num_rows($verify_query) !=0 ){
            echo "<div class='message'>
                      <p>Username ini sudah terpakai, coba tulis ulang yang baru!</p>
                  </div> <br>";
            echo "<a href='javascript:self.history.back()'><button class='btn'>Kembali</button>";
         }
         else{

            mysqli_query($con,"INSERT INTO users(Username,Email,Phone,Password) VALUES('$username','$email','$phone','$password')") or die("Erroe Occured");

            echo "<div class='message'>
                      <p>Registrasi Berhasil!</p>
                  </div> <br>";
            echo "<a href='index.php'><button class='btn'>Masuk Sekarang</button>";
         

         }

         }else{
         
        ?>

            <header>Buat Akun</header>
            <form action="" method="post">
                <div class="field input">
                    <label for="username">Username</label>
                    <input type="text"class="form-control" name="username" id="username" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="email">Email</label>
                    <input type="email"class="form-control" name="email" id="email" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="phone">Nomor Handphone</label>
                    <input type="tel" id="phone" name="phone"class="form-control" autocomplete="off" required>
                </div>
                <div class="field input">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password"class="form-control" autocomplete="off" required>
                </div>

                <div class="field">
                    
                    <input type="submit" class="btn" name="submit" value="Registrasi" required>
                </div>
                <div class="links">
                    Sudah Punya Akun? <a href="index.php">Masuk!</a>
                </div>
            </form>
        </div>
        <?php } ?>
      </div>
</body>
</html>