<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Selamat Datang | Resti Wedangan</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
  body {
    margin: 0;
    padding: 0;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: linear-gradient(145deg, #d7d7d7, #c4c4c4); /* abu lembut */
    color: #333;
    font-family: 'Poppins', sans-serif;
  }

  .container {
    text-align: center;
    background: rgba(255, 255, 255, 0.85);
    padding: 50px 60px;
    border-radius: 20px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    animation: fadeIn 1.2s ease;
  }

  .container img {
    width: 130px;
    height: auto;
    margin-bottom: 20px;
    filter: drop-shadow(0 0 8px rgba(255, 200, 0, 0.6));
  }

  h1 {
    font-size: 32px;
    margin-bottom: 10px;
    background: linear-gradient(90deg, #ff7a18, #af002d 70%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  }

  p {
    color: #555;
    font-size: 16px;
    margin-bottom: 30px;
  }

  a {
    display: inline-block;
    text-decoration: none;
    background: linear-gradient(45deg, #393E46,);
    color: #000;
    padding: 10px 25px;
    border-radius: 50px;
    font-weight: 600;
    box-shadow: 0 3px 10px rgba(0,0,0,0.2);
    transition: 0.3s;
  }

  a:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 14px rgba(0,0,0,0.3);
  }

  @keyframes fadeIn {
    from {opacity: 0; transform: translateY(-20px);}
    to {opacity: 1; transform: translateY(0);}
  }
</style>
</head>
<body>

<div class="container">
  <img src="admin/g.png" alt="Logo Resti Wedangan">
  <h1>Selamat Datang</h1>
  <p>di Sistem Kasir <strong>Resti Wedangan</strong><br>Tempat nikmatnya rasa dan pelayanan hangat </p>
  <a href="login_admin.php">Masuk ke Login Admin</a>
</div>

</body>
</html>
