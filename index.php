<?php 
   session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible"="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style/styles.css">
    <title>Login</title>
</head>
<body>
    <div class="container">
        <div class="box form-box">
            <?php 
             
              include("php/config.php");

              if (isset($_COOKIE['username']) && isset($_COOKIE['password'])) {
                  $username = $_COOKIE['username'];
                  $password = $_COOKIE['password'];
              }

              if (isset($_POST['submit'])) {
                $username = mysqli_real_escape_string($con, $_POST['username']);
                $password = mysqli_real_escape_string($con, $_POST['password']);
                $remember = isset($_POST['remember']);

                $result = mysqli_query($con, "SELECT * FROM users WHERE Username='$username' AND Password='$password'") or die("Select Error");
                $row = mysqli_fetch_assoc($result);

                if (is_array($row) && !empty($row)) {
                    $_SESSION['valid'] = $row['Username'];
                    $_SESSION['email'] = $row['Email'];
                    $_SESSION['phone'] = $row['Phone'];
                    $_SESSION['id'] = $row['Id'];
                    $_SESSION['role'] = $row['role']; // Menyimpan role pengguna dalam sesi

                    if ($remember) {
                        setcookie('username', $username, time() + (86400 * 30), "/"); // 30 hari
                        setcookie('password', $password, time() + (86400 * 30), "/"); // 30 hari
                    } else {
                        if (isset($_COOKIE['username'])) {
                            setcookie('username', '', time() - 3600, "/");
                        }
                        if (isset($_COOKIE['password'])) {
                            setcookie('password', '', time() - 3600, "/");
                        }
                    }

                    // Redirect berdasarkan role pengguna
                    switch ($row['role']) {
                        case 'karyawan':
                            header("Location: home.php");
                            break;
                        case 'owner':
                            header("Location: owner_dashboard.php");
                            break;
                        default:
                            header("Location: home.php");
                            break;
                    }
                    exit();
                } else {
                    echo "<div class='message'>
                      <p>Username/Password yang dimasukkan salah</p>
                       </div> <br>";
                    echo "<a href='index.php'><button class='btn'>Kembali</button>";
                }
                
              } else {
            ?>
            <header>Login</header>
            <form action="" method="post">
                <div class="field input">
                    <label for="username">Username</label>
                    <input type="text"class="form-control" name="username" id="username" value="<?php if (isset($_COOKIE['username'])) { echo $_COOKIE['username']; } ?>" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="password">Password</label>
                    <input type="password"class="form-control" name="password" id="password" value="<?php if (isset($_COOKIE['password'])) { echo $_COOKIE['password']; } ?>" autocomplete="off" required>
                </div>

                <div class="field">
                <label for="remember">Remember Me</label>
                <input type="checkbox" name="remember" id="remember" <?php if (isset($_COOKIE['username'])) { echo "checked"; } ?>>
                
                </div>

                <div class="field">
                    <input type="submit" class="btn btn-primary" name="submit" value="Login" required>
                </div>
                <div class="links">
                    Belum punya Akun? <a href="register.php">Buat Akun</a>
                </div>
            </form>
        </div>
        <?php } ?>
    </div>
</body>
</html>
