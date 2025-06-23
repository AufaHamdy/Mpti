<?php 
include 'koneksi.php';

session_start();

$id_customer = $_SESSION['customer_id'];

$tanggal = date('Y-m-d');

$nama = $_POST['nama'];
$hp = $_POST['hp'];
$alamat = $_POST['alamat'];

// Jika masih ingin simpan provinsi dan kabupaten (opsional)
$provinsi = isset($_POST['provinsi2']) ? $_POST['provinsi2'] : '';
$kabupaten = isset($_POST['kabupaten2']) ? $_POST['kabupaten2'] : '';

// Hapus atau kosongkan field ongkir
// $kurir = $_POST['kurir'] ." - ". $_POST['service'];  // Hapus baris ini
// $berat = $_POST['berat'];  // Hapus baris ini jika tidak diperlukan
// $ongkir = $_POST['ongkir2'];  // Hapus baris ini

$kurir = '';  // Kosongkan atau hapus field ini
$berat = 0;   // Set ke 0 atau hapus
$ongkir = 0;  // Set ongkir ke 0

// Total bayar langsung dari form tanpa tambahan ongkir
$total_bayar = (float)$_POST['total_bayar'];

// Insert ke database - sesuaikan field yang diperlukan
mysqli_query($koneksi,"insert into invoice values(NULL,'$tanggal','$id_customer','$nama','$hp','$alamat','$provinsi','$kabupaten','$kurir','$berat','$ongkir','$total_bayar','0','','')")or die(mysqli_error($koneksi));

$last_id = mysqli_insert_id($koneksi);

// transaksi
$invoice = $last_id;

$jumlah_isi_keranjang = count($_SESSION['keranjang']);

for($a = 0; $a < $jumlah_isi_keranjang; $a++){
	$id_produk = $_SESSION['keranjang'][$a]['produk'];
	$jml = $_SESSION['keranjang'][$a]['jumlah'];

	$isi = mysqli_query($koneksi,"select * from produk where produk_id='$id_produk'");
	$i = mysqli_fetch_assoc($isi);

	$produk = $i['produk_id'];
	$jumlah = $_SESSION['keranjang'][$a]['jumlah'];
	$harga = (float)$i['produk_harga'];  // Konversi ke float untuk keamanan
	
	mysqli_query($koneksi,"insert into transaksi values(NULL,'$invoice','$produk','$jumlah','$harga')");

	unset($_SESSION['keranjang'][$a]);
}

header("location:customer_pesanan.php?alert=sukses");
?>