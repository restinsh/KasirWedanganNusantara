<?php
session_start();
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Logout Berhasil</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Roboto', sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        background-color: #121212; /* Hitam gelap */
        color: #e0e0e0; /* Abu terang untuk teks */
        text-align: center;
    }
    .container {
        background-color: #1e1e1e; /* Abu lebih gelap untuk card */
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.5);
        animation: fadeIn 1s ease;
    }
    h1 {
        font-size: 36px;
        margin-bottom: 20px;
        color: #ffffff;
    }
    p {
        font-size: 18px;
        margin-bottom: 30px;
        color: #cccccc;
    }
    a {
        text-decoration: none;
        background-color: #333333; /* Tombol abu gelap */
        color: #ffffff;
        padding: 12px 25px;
        border-radius: 50px;
        font-weight: bold;
        transition: 0.3s;
    }
    a:hover {
        background-color: #555555; /* Tombol hover lebih terang */
        transform: scale(1.05);
    }
    @keyframes fadeIn {
        from {opacity: 0; transform: translateY(-20px);}
        to {opacity: 1; transform: translateY(0);}
    }
</style>
</head>
<body>
<div class="container">
    <h1>✔ Berhasil Logout</h1>
    <p>Terimaksih telah menggunkan sistem kasir.<br>semoga hari hari mu menyenangkan 
see you..</p>
    <a href="login_admin.php">Login Kembali</a>
</div>
</body>
</html>
