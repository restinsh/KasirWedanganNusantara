<?php
session_start();
if (!isset($_SESSION['level']) || !in_array($_SESSION['level'], ['admin','kasir'])) {
  header("Location: ../../welcome.php");
  exit;
}
include '../koneksi.php';

// Ambil data produk
$produk = mysqli_query($koneksi, "SELECT * FROM produk ORDER BY nama_produk");

// Proses simpan transaksi
if (isset($_POST['simpan'])) {
  $id_user = $_SESSION['id_user'];
  $total_harga = (int)$_POST['total_harga'];
  $bayar = (int)$_POST['bayar'];
  $kembalian = $bayar - $total_harga;
  $metode_pembayaran = mysqli_real_escape_string($koneksi, $_POST['metode_pembayaran']);
  $kode_transaksi = 'TRX' . date('YmdHis');

  // Simpan transaksi utama
  mysqli_query($koneksi, "INSERT INTO transaksi (kode_transaksi, tgl_transaksi, id_user, total_harga, bayar, kembalian, metode_pembayaran)
                          VALUES ('$kode_transaksi', NOW(), '$id_user', '$total_harga', '$bayar', '$kembalian', '$metode_pembayaran')");
  $id_transaksi = mysqli_insert_id($koneksi);

  // Simpan detail transaksi
  foreach ($_POST['produk'] as $i => $id_produk) {
    $qty = (int)$_POST['qty'][$i];
    $harga = (int)$_POST['harga'][$i];
    $subtotal = $qty * $harga;

    mysqli_query($koneksi, "INSERT INTO detail_transaksi (id_transaksi, id_produk, qty, subtotal)
                            VALUES ('$id_transaksi', '$id_produk', '$qty', '$subtotal')");
    mysqli_query($koneksi, "UPDATE produk SET stok = stok - $qty WHERE id_produk = '$id_produk'");
  }

  $_SESSION['id_transaksi_cetak'] = $id_transaksi;
  header("Location: cetak_struk.php");
  exit;
}
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>🧾 Transaksi Kasir</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
  background-color: #f8f9fa;
  font-family: 'Poppins', sans-serif;
}
.content {
  margin-left: 260px;
  padding: 25px;
}
.card {
  border-radius: 12px;
  box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}
.scroll-area {
  max-height: 300px;
  overflow-y: auto;
}
.table thead {
  background-color: #e9ecef;
}
</style>
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="content">
  <h4 class="mb-4 fw-bold text-secondary"> Transaksi Penjualan</h4>

  <div class="row g-4">
    <!-- KIRI: Daftar Produk -->
    <div class="col-md-7">
      <div class="card">
        <div class="card-body">
          <h6 class="fw-bold mb-3 text-secondary"> Daftar Produk</h6>
          <table class="table table-bordered table-hover align-middle text-center">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php $no=1; while($p = mysqli_fetch_assoc($produk)): ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($p['nama_produk']) ?></td>
                <td>Rp <?= number_format($p['harga'],0,',','.') ?></td>
                <td><?= $p['stok'] ?></td>
                <td>
                  <!-- Ganti warna tombol jadi hijau -->
                  <button type="button" class="btn btn-success btn-sm tambahKeranjang"
                          data-id="<?= $p['id_produk'] ?>"
                          data-nama="<?= htmlspecialchars($p['nama_produk']) ?>"
                          data-harga="<?= $p['harga'] ?>">Tambah</button>
                </td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- KANAN: Keranjang -->
    <div class="col-md-5">
      <form method="post">
        <div class="card">
          <div class="card-body">
            <h6 class="fw-bold text-center text-secondary mb-3"> Keranjang Belanja</h6>
            <div class="scroll-area">
              <table class="table table-sm text-center" id="tabelCart">
                <thead>
                  <tr><th>Produk</th><th>Qty</th><th>Subtotal</th><th></th></tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>

            <div class="mt-3">
              <label class="form-label">Metode Pembayaran</label>
              <select name="metode_pembayaran" class="form-select" required>
                <option value="Tunai">Tunai</option>
              </select>
            </div>

            <div class="mt-3">
              <label class="form-label">Total Harga</label>
              <input type="number" name="total_harga" id="total_harga" class="form-control" readonly>
            </div>

            <div class="mt-3">
              <label class="form-label">Bayar</label>
              <input type="number" name="bayar" id="bayar" class="form-control" required>
            </div>

            <div class="mt-3">
              <label class="form-label">Kembalian</label>
              <input type="number" name="kembalian" id="kembalian" class="form-control" readonly>
            </div>

            <div class="text-center mt-4">
              <!-- Ganti warna tombol jadi biru -->
              <button type="submit" name="simpan" class="btn btn-primary px-4 py-2">
                 Simpan Transaksi
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
const cartBody = document.querySelector('#tabelCart tbody');
const totalInput = document.getElementById('total_harga');

document.querySelectorAll('.tambahKeranjang').forEach(btn=>{
  btn.addEventListener('click',()=>{
    const id = btn.dataset.id;
    const nama = btn.dataset.nama;
    const harga = parseInt(btn.dataset.harga);

    let row = cartBody.querySelector(`tr[data-id="${id}"]`);
    if(row){
      let qtyInput = row.querySelector('.qty');
      qtyInput.value = parseInt(qtyInput.value) + 1;
      updateSubtotal(row);
    } else {
      let tr = document.createElement('tr');
      tr.dataset.id = id;
      tr.innerHTML = `
        <td>${nama}<input type="hidden" name="produk[]" value="${id}">
            <input type="hidden" name="harga[]" value="${harga}"></td>
        <td><input type="number" name="qty[]" class="form-control form-control-sm qty text-center" value="1" min="1" style="width:70px;"></td>
        <td><input type="number" name="subtotal[]" class="form-control form-control-sm subtotal text-center" value="${harga}" readonly style="width:100px;"></td>
        <td><button type="button" class="btn btn-sm btn-outline-danger hapus">x</button></td>`;
      cartBody.appendChild(tr);
    }
    hitungTotal();
  });
});

cartBody.addEventListener('input', e=>{
  if(e.target.classList.contains('qty')){
    updateSubtotal(e.target.closest('tr'));
  }
});
cartBody.addEventListener('click', e=>{
  if(e.target.classList.contains('hapus')){
    e.target.closest('tr').remove();
    hitungTotal();
  }
});

function updateSubtotal(row){
  const harga = parseInt(row.querySelector('input[name="harga[]"]').value);
  const qty = parseInt(row.querySelector('.qty').value);
  row.querySelector('.subtotal').value = harga * qty;
  hitungTotal();
}
function hitungTotal(){
  let total = 0;
  document.querySelectorAll('.subtotal').forEach(s=> total += parseInt(s.value||0));
  totalInput.value = total;
  const bayar = parseInt(document.getElementById('bayar').value)||0;
  document.getElementById('kembalian').value = bayar - total;
}
document.getElementById('bayar').addEventListener('input', hitungTotal);
</script>
</body>
</html>
