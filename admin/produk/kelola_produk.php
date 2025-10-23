<?php
session_start();
if(!isset($_SESSION['level']) || $_SESSION['level'] != 'admin'){
  header("Location: ../welcome.php");
  exit;
}
include '../../koneksi.php';
include '../sidebar.php';

// ===================== FILTER & PENCARIAN =====================
$where = "1";
if(!empty($_GET['search'])) {
  $s = mysqli_real_escape_string($koneksi, $_GET['search']);
  $where .= " AND nama_produk LIKE '%$s%'";
}
if(!empty($_GET['kategori'])) {
  $k = mysqli_real_escape_string($koneksi, $_GET['kategori']);
  $where .= " AND kategori = '$k'";
}

// ===================== AMBIL DATA PRODUK =====================
$q = mysqli_query($koneksi, "SELECT * FROM produk WHERE is_deleted = 0 ORDER BY id_produk DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>🛍️ Kelola Produk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .content { margin-left: 260px; padding: 25px; }
    .card-produk img { height: 160px; object-fit: cover; border-bottom: 1px solid #ddd; }
    .card-produk { transition: 0.2s; }
    .card-produk:hover { transform: translateY(-5px); box-shadow: 0 4px 10px rgba(0,0,0,0.2); }
  </style>
</head>
<body>

  <div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">Manajemen Produk</h4>
      <a href="tambah_produk.php" class="btn btn-success">+ Tambah Produk</a>
    </div>

    <!-- Form Pencarian dan Filter -->
    <form class="row g-2 mb-4" method="get">
      <div class="col-md-5">
        <input type="text" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" class="form-control" placeholder=" Cari nama produk...">
      </div>
      <div class="col-md-3">
        <select name="kategori" class="form-select">
          <option value="">Semua Kategori</option>
          <option value="Dingin" <?= (($_GET['kategori'] ?? '')=='Dingin')?'selected':'' ?>>Minuman Dingin</option>
          <option value="Panas" <?= (($_GET['kategori'] ?? '')=='Panas')?'selected':'' ?>>Minuman Panas</option>
          <option value="Panas/Dingin" <?= (($_GET['kategori'] ?? '')=='Panas/Dingin')?'selected':'' ?>>Minuman Panas/ Minuman Dingin</option>
        </select>
      </div>
      <div class="col-md-2"><button class="btn btn-dark w-100">cari</button></div>
      <div class="col-md-2"><a href="kelola_produk.php" class="btn btn-outline-secondary w-100">Reset</a></div>
    </form>

    <!-- Daftar Produk -->
    <div class="row g-3">
      <?php if(mysqli_num_rows($q) > 0): ?>
        <?php while($p = mysqli_fetch_assoc($q)): ?>
          <div class="col-6 col-md-4 col-lg-3">
            <div class="card card-produk h-100 border-0 shadow-sm">
              <?php 
                $path_file = '../uploads/'.$p['foto'];
                if(!empty($p['foto']) && file_exists($path_file)): ?>
                <img src="../uploads/<?= htmlspecialchars($p['foto']) ?>" class="card-img-top" alt="<?= htmlspecialchars($p['nama_produk']) ?>">
              <?php else: ?>
                <img src="https://via.placeholder.com/300x160?text=No+Image" class="card-img-top" alt="No Image">
              <?php endif; ?>

              <div class="card-body text-center">
                <h6 class="card-title text-truncate"><?= htmlspecialchars($p['nama_produk']) ?></h6>
                <small class="text-muted"><?= htmlspecialchars($p['kategori']) ?></small>
                <p class="fw-bold mt-2 mb-1 text-dark">Rp <?= number_format($p['harga'],0,',','.') ?></p>
                <p class="small text-secondary mb-3">Stok: <?= htmlspecialchars($p['stok']) ?></p>
                <div class="d-flex justify-content-center gap-2">
                  <a href="edit_produk.php?id=<?= $p['id_produk'] ?>" class="btn btn-warning btn-sm">✏️ Edit</a>
                  <a href="hapus_produk.php?id=<?= $p['id_produk'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin hapus produk ini?')">🗑️ Hapus</a>
                </div>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <div class="col-12 text-center text-muted mt-4">
          <p>⚠️ Belum ada produk ditemukan.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>

</body>
</html>
