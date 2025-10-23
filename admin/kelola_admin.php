<?php
session_start();
if (!isset($_SESSION['level']) || $_SESSION['level'] != 'admin') {
  header("Location: ../index.php");
  exit;
}

include '../koneksi.php';
include 'sidebar.php';



// ========== Tambah Admin ==========
if (isset($_POST['tambah'])) {
  $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
  $username = mysqli_real_escape_string($koneksi, $_POST['username']);
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $level = mysqli_real_escape_string($koneksi, $_POST['level']);

  $cek = mysqli_query($koneksi, "SELECT * FROM user WHERE username='$username'");
  if (mysqli_num_rows($cek) > 0) {
    $pesan = "<div class='alert alert-danger'>Username sudah digunakan!</div>";
  } else {
    mysqli_query($koneksi, "INSERT INTO user (nama, username, password, level) VALUES ('$nama','$username','$password','$level')");
    $pesan = "<div class='alert alert-success'>Data admin/kasir berhasil ditambahkan!</div>";
  }
}

// ========== Update Admin ==========
if (isset($_POST['update'])) {
  $id = $_POST['id_user'];
  $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
  $username = mysqli_real_escape_string($koneksi, $_POST['username']);
  $level = mysqli_real_escape_string($koneksi, $_POST['level']);

  // Jika password diisi baru, update juga password
  if (!empty($_POST['password'])) {
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $query = "UPDATE user SET nama='$nama', username='$username', password='$password', level='$level' WHERE id_user='$id'";
  } else {
    $query = "UPDATE user SET nama='$nama', username='$username', level='$level' WHERE id_user='$id'";
  }

  mysqli_query($koneksi, $query);
  $pesan = "<div class='alert alert-info'>Data admin berhasil diperbarui!</div>";
}

// ========== Hapus Admin ==========
if (isset($_GET['hapus'])) {
  $id = $_GET['hapus'];
  if ($id != $_SESSION['id_user']) {
    mysqli_query($koneksi, "DELETE FROM user WHERE id_user='$id'");
    $pesan = "<div class='alert alert-warning'>Data admin/kasir berhasil dihapus!</div>";
  } else {
    $pesan = "<div class='alert alert-danger'>Kamu tidak bisa menghapus akun kamu sendiri!</div>";
  }
}

// ========== Ambil Data Admin untuk Edit ==========
$editData = null;
if (isset($_GET['edit'])) {
  $id = $_GET['edit'];
  $res = mysqli_query($koneksi, "SELECT * FROM user WHERE id_user='$id'");
  $editData = mysqli_fetch_assoc($res);
}

// ========== Pencarian ==========
$cari = $_GET['cari'] ?? '';
$where = $cari ? "WHERE nama LIKE '%$cari%' OR username LIKE '%$cari%'" : '';
$data = mysqli_query($koneksi, "SELECT * FROM user $where ORDER BY level, nama");
?>

<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Kelola Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
<div style="margin-left:260px; padding:20px;">
  <h4 class="mb-3">Kelola Admin</h4>

  <?php if (!empty($pesan)) echo $pesan; ?>

  <!-- Form Tambah / Edit Admin -->
  <div class="card mb-4 p-3 shadow-sm">
    <h6><?= isset($editData) ? 'Edit Data Admin' : 'Tambah Admin Baru' ?></h6>
    <form method="POST" class="row g-3 mt-1">
      <input type="hidden" name="id_user" value="<?= $editData['id_user'] ?? '' ?>">

      <div class="col-md-3">
        <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap"
          value="<?= htmlspecialchars($editData['nama'] ?? '') ?>" required>
      </div>
      <div class="col-md-3">
        <input type="text" name="username" class="form-control" placeholder="Username"
          value="<?= htmlspecialchars($editData['username'] ?? '') ?>" required>
      </div>
      <div class="col-md-3">
        <input type="password" name="password" class="form-control" placeholder="Password (isi jika ingin ganti)">
      </div>
      <div class="col-md-2">
        <select name="level" class="form-select" required>
          <option value="">Level</option>
          <option value="admin" <?= (isset($editData) && $editData['level'] == 'admin') ? 'selected' : '' ?>>Admin</option>
          <option value="admin p" <?= (isset($editData) && $editData['level'] == 'admin p') ? 'selected' : '' ?>>Admin P</option>
        </select>
      </div>
      <div class="col-md-1 d-grid">
        <?php if (isset($editData)) { ?>
          <button type="submit" name="update" class="btn btn-success">Update</button>
        <?php } else { ?>
          <button type="submit" name="tambah" class="btn btn-primary">Tambah</button>
        <?php } ?>
      </div>
    </form>
  </div>

  <!-- Tombol Reset ke mode tambah -->
  <?php if (isset($editData)) { ?>
    <a href="kelola_admin.php" class="btn btn-secondary btn-sm mb-3">Batal Edit</a>
  <?php } ?>

  <!-- Pencarian -->
  <form class="mb-3" method="GET">
    <div class="input-group" style="max-width:300px;">
      <input type="text" name="cari" class="form-control" placeholder="Cari nama / username..." value="<?= htmlspecialchars($cari) ?>">
      <button class="btn btn-secondary">Cari</button>
    </div>
  </form>

  <!-- Tabel Data -->
  <div class="card p-3 shadow-sm">
    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark text-center">
          <tr>
            <th width="5%">No</th>
            <th>Nama</th>
            <th>Username</th>
            <th>Level</th>
            <th width="20%">Aksi</th>
          </tr>
        </thead>
        <tbody>
        <?php
        $no = 1;
        if (mysqli_num_rows($data) > 0) {
          while ($r = mysqli_fetch_assoc($data)) {
            echo "
            <tr>
              <td class='text-center'>$no</td>
              <td>{$r['nama']}</td>
              <td>{$r['username']}</td>
              <td class='text-center'>{$r['level']}</td>
              <td class='text-center'>
                <a href='?edit={$r['id_user']}' class='btn btn-sm btn-warning'>Edit</a>
                <a href='?hapus={$r['id_user']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Yakin hapus data ini?\")'>Hapus</a>
              </td>
            </tr>";
            $no++;
          }
        } else {
          echo "<tr><td colspan='5' class='text-center text-muted'>Tidak ada data ditemukan</td></tr>";
        }
        ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>
