<?php
session_start();
include "koneksi.php";

// pastikan tidak ada spasi/echo sebelum session_start

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

            // redirect aman pakai JS
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
   background: linear-gradient(145deg, #d7d7d7, #c4c4c4); 
    display:flex; 
    justify-content:center; 
    align-items:center; 
    height:100vh; 
}
.card-login { 
    width:350px; 
    padding:20px; 
    background:#dee2e6; 
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,0.2); }
</style>
</head>
<body>

<div class="card card-login">
    <h3 class="text-center mb-3">Login Admin</h3>
    <?php if(isset($error)){ echo "<div class='alert alert-danger'>$error</div>"; } ?>
    <form method="POST">
        <div class="mb-3"><input type="text" name="username" class="form-control" placeholder="Username" required></div>
        <div class="mb-3"><input type="password" name="password" class="form-control" placeholder="Password" required></div>
        <button name="login" class="btn btn-secondary w-100">Masuk</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
