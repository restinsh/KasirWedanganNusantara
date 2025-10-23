<?php
session_start();
if (!isset($_SESSION['level']) || $_SESSION['level'] != 'admin') {
  header("Location: ../welcome.php");
  exit;
}
include '../koneksi.php';
include 'sidebar.php';

// Ambil filter tanggal
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';

// Hitung total pendapatan
$sqlTotal = "SELECT COALESCE(SUM(total_harga), 0) AS total_pendapatan FROM transaksi WHERE 1";
if ($from && $to) {
  $sqlTotal .= " AND DATE(tgl_transaksi) BETWEEN '$from' AND '$to'";
}
$total = mysqli_fetch_assoc(mysqli_query($koneksi, $sqlTotal))['total_pendapatan'];

// Ambil daftar transaksi
$sql = "SELECT t.*, u.username 
        FROM transaksi t 
        LEFT JOIN user u ON t.id_user = u.id_user 
        WHERE 1";
if ($from && $to) {
  $sql .= " AND DATE(t.tgl_transaksi) BETWEEN '$from' AND '$to'";
}
$sql .= " ORDER BY t.tgl_transaksi DESC";
$res = mysqli_query($koneksi, $sql);
?>

<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Laporan Transaksi</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
  background: #f8f9fa;
}
@media print {
  body * {
    visibility: hidden;
  }
  #print-area, #print-area * {
    visibility: visible;
  }
  #print-area {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    background: white;
    padding: 20px;
  }
}
</style>
</head>
<body>
<div style="margin-left:260px; padding:20px">
  <h4 class="mb-3"> Laporan Transaksi</h4>

  <!-- Filter tanggal -->
  <form class="row g-2 mb-4" method="GET">
    <div class="col-md-3">
      <label class="form-label">Dari Tanggal</label>
      <input type="date" name="from" class="form-control" value="<?= htmlspecialchars($from) ?>">
    </div>
    <div class="col-md-3">
      <label class="form-label">Sampai Tanggal</label>
      <input type="date" name="to" class="form-control" value="<?= htmlspecialchars($to) ?>">
    </div>
    <div class="col-md-3 d-flex align-items-end">
      <button class="btn btn-primary w-100">Tampilkan</button>
    </div>
    <div class="col-md-3 d-flex align-items-end">
      <button type="button" class="btn btn-success w-100" onclick="printLaporan()"> Cetak Laporan</button>
    </div>
  </form>

  <!-- Area yang akan dicetak -->
  <div id="print-area">
    <div class="alert alert-success">
      <strong>Total Pendapatan:</strong> Rp <?= number_format($total, 0, ',', '.') ?>
    </div>

    <div class="card p-3 shadow-sm">
      <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
          <thead class="table-dark text-center">
            <tr>
              <th>No</th>
              <th>Kode Transaksi</th>
              <th>Tanggal</th>
              <th>Kasir</th>
              <th>Total Harga</th>
              <th>Bayar</th>
              <th>Kembalian</th>
              <th>Metode</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $no = 1;
            while ($r = mysqli_fetch_assoc($res)): ?>
              <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td><?= htmlspecialchars($r['kode_transaksi']) ?></td>
                <td><?= date('d-m-Y H:i', strtotime($r['tgl_transaksi'])) ?></td>
                <td><?= htmlspecialchars($r['username']) ?></td>
                <td>Rp <?= number_format($r['total_harga'], 0, ',', '.') ?></td>
                <td>Rp <?= number_format($r['bayar'], 0, ',', '.') ?></td>
                <td>Rp <?= number_format($r['kembalian'], 0, ',', '.') ?></td>
                <td><?= htmlspecialchars($r['metode_pembayaran']) ?></td>
                <td class="text-center">
                  <?php if (isset($r['status_struk']) && $r['status_struk'] == 'sudah'): ?>
                    <span class="badge bg-success">Sudah Disimpan</span>
                  <?php else: ?>
                    <a href="cetak_struk.php?id=<?= $r['id_transaksi'] ?>" class="btn btn-outline-primary btn-sm">
                      lihat  Struk
                    </a>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
function printLaporan() {
  window.print();
}
</script>
</body>
</html>
