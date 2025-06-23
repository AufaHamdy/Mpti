<?php include 'header.php'; ?>

<!-- BREADCRUMB -->
<div id="breadcrumb">
  <div class="container">
    <ul class="breadcrumb">
      <li><a href="index.php">Home</a></li>
      <li class="active">Katalog</li>
    </ul>
  </div>
</div>
<!-- /BREADCRUMB -->

<!-- SECTION -->
<div class="section">
  <div class="container">
    <div class="row">
      
      <!-- SIDEBAR KATEGORI -->
    
          <div class="col-md-3">
            <div class="kategori-wrapper">
              <h5 class="kategori-title">Kategori Produk</h5>
              <ul class="kategori-list">
                <?php 
                  $kategori = mysqli_query($koneksi,"SELECT * FROM kategori ORDER BY kategori_nama ASC");
                  while($k = mysqli_fetch_array($kategori)){
                ?>
                  <li>
                    <a href="katalog.php?kategori=<?php echo $k['kategori_id']; ?>">
                      <?php echo $k['kategori_nama']; ?>
                    </a>
                  </li>
                <?php } ?>
              </ul>
            </div>
          </div>

      <!-- /SIDEBAR KATEGORI -->

      <!-- MAIN -->
      <div class="col-md-9">
        <!-- KATALOG DAN FILTER DALAM SATU ROW -->
        <div class="row">
  <!-- KATALOG -->
        <div class="col-md-9 col-sm-12">
            <div class="row">
            <?php
                $where = "WHERE kategori_id=produk_kategori";
                if(isset($_GET['cari'])){
                $cari = mysqli_real_escape_string($koneksi, $_GET['cari']);
                $where .= " AND produk_nama LIKE '%$cari%'";
                }

                if(isset($_GET['kategori'])){
                $kategori_id = mysqli_real_escape_string($koneksi, $_GET['kategori']);
                $where .= " AND produk_kategori = '$kategori_id'";
                }

                $urutan = isset($_GET['urutan']) && $_GET['urutan'] == 'harga' ? "ORDER BY produk_harga ASC" : "ORDER BY produk_id DESC";

                $data = mysqli_query($koneksi,"SELECT * FROM produk, kategori $where $urutan");
                if(mysqli_num_rows($data) == 0){
                echo "<center><h4>Produk tidak ditemukan.</h4></center>";
                }

                while($d = mysqli_fetch_array($data)){
            ?>
                <div class="col-md-4 col-sm-6 mb-4">
                <div class="product product-single">
                    <div class="product-thumb">
                    <div class="product-label"><span><?php echo $d['kategori_nama'] ?></span></div>
                    <a href="produk_detail.php?id=<?php echo $d['produk_id'] ?>" class="main-btn quick-view"><i class="fa fa-search-plus"></i> Lihat</a>
                    <img src="<?php echo $d['produk_foto1'] ? 'gambar/produk/'.$d['produk_foto1'] : 'gambar/sistem/produk.png'; ?>" style="height: 250px;">
                    </div>
                    <div class="product-body">
                    <h3 class="product-price"><?php echo "Rp. ".number_format($d['produk_harga']).",-"; ?></h3>
                    <h2 class="product-name"><a href="produk_detail.php?id=<?php echo $d['produk_id'] ?>"><?php echo $d['produk_nama']; ?></a></h2>
                    <div class="product-btns">
                        <a class="main-btn btn-block text-center" href="produk_detail.php?id=<?php echo $d['produk_id'] ?>"><i class="fa fa-search"></i> Detail</a>
                        <a class="primary-btn add-to-cart btn-block text-center" href="keranjang_masukkan.php?id=<?php echo $d['produk_id']; ?>&redirect=katalog"><i class="fa fa-shopping-cart"></i> Keranjang</a>
                    </div>
                    </div>
                </div>
                </div>
            <?php } ?>
            </div>
        </div>
        <!-- /KATALOG -->

             <!-- FILTER -->
            <div class="col-md-3 col-sm-12 d-flex justify-content-end">
        <form action="katalog.php" method="get" class="d-flex align-items-center justify-content-end w-100" style="gap: 8px;">
          <?php if (isset($_GET['cari'])): ?>
            <input type="hidden" name="cari" value="<?php echo htmlspecialchars($_GET['cari']); ?>">
          <?php endif; ?>

          <?php if (isset($_GET['kategori'])): ?>
            <input type="hidden" name="kategori" value="<?php echo htmlspecialchars($_GET['kategori']); ?>">
          <?php endif; ?>

          <label class="mb-0" style="font-weight: 600;">URUTKAN :</label>
          <select name="urutan" class="form-control border-orange" onchange="this.form.submit()" style="max-width: 150px;">
            <option value="terbaru" <?php if(isset($_GET['urutan']) && $_GET['urutan'] == "terbaru") echo "selected"; ?>>Terbaru</option>
            <option value="harga" <?php if(isset($_GET['urutan']) && $_GET['urutan'] == "harga") echo "selected"; ?>>Harga</option>
          </select>
        </form>
      </div>
      <!-- /FILTER -->

        </div>

      </div>
      <!-- /MAIN -->

    </div>
  </div>
</div>
<!-- /SECTION -->

<?php include 'footer.php'; ?>
