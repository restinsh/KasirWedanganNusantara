<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Selamat Datang | Resti Wedangan</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
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

.container {
    width: 450px;
    padding: 40px 30px;
    background: rgba(255,255,255,0.25);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    box-shadow: 0 10px 35px rgba(0,0,0,0.3);
    text-align: center;
    color: #fff;
}

.container img {
    width: 180px; /* diperbesar */
    height: auto;
    margin-bottom: 20px;
    filter: drop-shadow(0 0 8px rgba(255, 200, 0, 0.6));
}

h1 {
    font-size: 32px;
    margin-bottom: 10px;
    background: linear-gradient(90deg, #000000ff, #555555ff 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.text-gradient {
    font-size: 18px;
    font-weight: 600;
    line-height: 1.4;
    margin-bottom: 30px;
    background: linear-gradient(90deg, #cccccc, #ffffff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    display: inline-block;
    text-align: center;
}

a.button {
    display: inline-block;
    text-decoration: none;
    background: #555; /* abu-abu */
    color: #fff;      /* teks putih */
    padding: 12px 30px;
    border-radius: 50px;
    font-weight: 600;
    box-shadow: 0 3px 10px rgba(0,0,0,0.2);
    transition: 0.3s;
    margin-top: 20px;
}

a.button:hover {
    background: #777; /* abu-abu lebih terang saat hover */
    transform: scale(1.05);
    box-shadow: 0 4px 14px rgba(14, 14, 14, 0.3);
}
</style>
</head>
<body>

<div class="container">
  <img src="admin/g.png" alt="Logo Resti Wedangan">
  <h1>Selamat Datang</h1>
  <p class="text-gradient">di Sistem Kasir <strong>Resti Wedangan</strong><br>Tempat nikmatnya rasa dan pelayanan hangat</p>
  <!-- Button dipindah ke dalam container -->
  <a href="login_admin.php" class="button">Masuk ke Login Admin</a>
</div>

</body>
</html>
