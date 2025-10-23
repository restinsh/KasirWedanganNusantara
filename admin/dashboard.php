<?php
session_start();
if (!isset($_SESSION['level']) || $_SESSION['level'] != 'admin') {
  header("Location: ../welcome.php");
  exit;
}

include '../koneksi.php';
include 'sidebar.php';

$search   = $_GET['search'] ?? '';
$kategori = $_GET['kategori'] ?? '';

$where = "is_deleted = 0";
if ($search != '') {
  $search = mysqli_real_escape_string($koneksi, $search);
  $where .= " AND nama_produk LIKE '%$search%'";
}
if ($kategori != '') {
  $kategori = mysqli_real_escape_string($koneksi, $kategori);
  $where .= " AND kategori = '$kategori'";
}

$sql = "SELECT * FROM produk WHERE $where ORDER BY id_produk DESC";
$qProduk = mysqli_query($koneksi, $sql);

?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Dashboard - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#f8f9fa; }
    .content { margin-left:260px; padding:20px; }
    .card-produk {
      border:none; border-radius:12px; overflow:hidden;
      box-shadow:0 2px 8px rgba(0,0,0,0.08);
      transition: all .2s ease;
    }
    .card-produk:hover { transform: translateY(-3px); }
    .card-produk img {
      width:100%; height:150px; object-fit:cover; background:#eee;
    }
    .card-body { padding:12px; }
    .harga { font-weight:600; color:#000; margin-top:6px; }
    .stok { font-size:13px; color:#555; }
  </style>
</head>

<body>
  <?php include 'sidebar.php'; ?>

  <div class="content">
    <h3 class="mb-4"> Daftar Produk</h3>

    <!-- Filter dan Pencarian -->
    <form class="row g-2 mb-4">
      <div class="col-md-4">
        <input type="text" name="search" class="form-control"
               placeholder="Cari produk..." value="<?= htmlspecialchars($search) ?>">
      </div>
      <div class="col-md-3">
        <select name="kategori" class="form-select">
          <option value="">Semua Kategori</option>
          <option value="Dingin" <?= $kategori=='Dingin'?'selected':'' ?>>Minuman Dingin</option>
          <option value="Panas" <?= $kategori=='Panas'?'selected':'' ?>>Minuman Panas</option>
        </select>
      </div>
      <div class="col-md-2">
        <button class="btn btn-dark w-100" type="submit"> cari</button>
      </div>
      <div class="col-md-2">
        <a href="dashboard.php" class="btn btn-secondary w-100"> Reset</a>
      </div>
    </form>

    <!-- Daftar Produk -->
    <div class="row g-3">
      <?php if (mysqli_num_rows($qProduk) > 0): ?>
        <?php while ($p = mysqli_fetch_assoc($qProduk)): ?>
          <?php
            // Coba 2 kemungkinan lokasi folder uploads
            $fotoPath1 = "uploads/" . $p['foto'];
            $fotoPath2 = "uploads/" . $p['foto'];
            if (!empty($p['foto']) && file_exists($fotoPath1)) {
              $src = $fotoPath1;
            } elseif (!empty($p['foto']) && file_exists($fotoPath2)) {
              $src = $fotoPath2;
            } else {
              $src = "uploads/default.png"; // fallback
            }
          ?>
          <div class="col-6 col-md-4 col-lg-3">
            <div class="card card-produk h-100">
              <img src="<?= $fotoPath1 ?>" alt="<?= htmlspecialchars($p['nama_produk']) ?>">
              <div class="card-body text-center">
                <h6 class="card-title text-truncate"><?= htmlspecialchars($p['nama_produk']) ?></h6>
                <small class="text-muted d-block"><?= htmlspecialchars($p['kategori']) ?></small>
                <p class="harga">Rp <?= number_format($p['harga'],0,',','.') ?></p>
                <p class="stok">Stok: <?= $p['stok'] ?></p>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <div class="col-12 text-center text-muted">
          <p>Tidak ada produk ditemukan.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
