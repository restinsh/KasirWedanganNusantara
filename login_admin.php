<?php
session_start();
include "koneksi.php";

if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = mysqli_query($koneksi, "SELECT * FROM user WHERE username='$username'");
    $data = mysqli_fetch_array($query);

    if($data){
        if(password_verify($password, $data['password'])){
            $_SESSION['id_user'] = $data['id_user'];
            $_SESSION['nama'] = $data['nama_lengkap'];
            $_SESSION['level'] = $data['level'];

            if($data['level'] == 'admin'){
                echo "<script>window.location.href='admin/dashboard.php';</script>";
                exit();
            } elseif($data['level'] == 'kasir'){
                echo "<script>window.location.href='kasir/index.php';</script>";
                exit();
            }
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    margin:0;
    padding:0;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    font-family: Arial, sans-serif;

    /* Background cover dengan overlay gelap */
    background: 
        linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)),
        url('admin/kk.jpg') no-repeat center center fixed;
    background-size: cover;
}

/* Card login dengan blur */
.card-login {
    width: 350px;
    padding: 30px 20px;
    background: rgba(255,255,255,0.25);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.3);
    text-align: center;
    color: #fff;
}

/* Logo */
.card-login img {
    width: 80px;
    display: block;
    margin: 0 auto 10px auto;
}

/* Nama toko */
.card-login h4 {
    font-size: 16px;
    margin-bottom: 20px;
    font-weight: 500;
}

/* Judul login */
.card-login h3 {
    font-size: 20px;
    margin-bottom: 20px;
    font-weight: 600;
}

/* Input form */
.card-login .form-control {
    border-radius: 10px;
    border: none;
    padding: 12px;
    margin-bottom: 15px;
    background: rgba(255,255,255,0.2);
    color: #fff;
}
.card-login .form-control::placeholder {
    color: #e0e0e0;
}

/* Tombol login abu-abu */
.card-login .btn {
    border-radius: 10px;
    background: #555;
    border: none;
    font-weight: 600;
    transition: 0.3s;
}
.card-login .btn:hover {
    background: #777;
}

/* Alert error */
.card-login .alert {
    font-size: 14px;
    background: rgba(255,0,0,0.7);
    border: none;
    color: #fff;
}
</style>
</head>
<body>

<div class="card card-login">
    <!-- Logo -->
    <img src="admin/g.png" alt="Logo Resti Wedangan">
    <!-- Nama Toko -->
    <h4>Resti Wedangan</h4>

    <h3>Login Admin</h3>
    <?php if(isset($error)){ echo "<div class='alert alert-danger'>$error</div>"; } ?>
    <form method="POST">
        <input type="text" name="username" class="form-control" placeholder="Username" required>
        <input type="password" name="password" class="form-control" placeholder="Password" required>
        <button name="login" class="btn w-100">Masuk</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
