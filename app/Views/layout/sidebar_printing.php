<?php
$printing_link = [
  'printing/mesin',
  'printing/bahan',
  'printing/produk',
  'printing/harga-level',
];
$printing_aktif = in_array(str_replace(base_url(), '', current_url()), $printing_link);
?>

<li class="nav-item<?= $printing_aktif ? ' menu-open' : ''  ?>">
    <a href="#" class="nav-link<?= $printing_aktif ? ' active' : ''  ?>">
        <i class="nav-icon bi bi-printer"></i>
        <p>Printing <i class="right bi bi-caret-left"></i></p>
    </a>
    <ul class="nav nav-treeview">

        <li class="nav-item">
            <a href="<?= url_to('printing/mesin') ?>" class="nav-link<?= (current_url() == base_url('printing/mesin')) ? ' active' : '' ?>">
                <i class="nav-icon bi bi-circle"></i>
                <p>Kategori Mesin</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= url_to('printing/bahan') ?>" class="nav-link<?= (current_url() == base_url('printing/bahan')) ? ' active' : '' ?>">
                <i class="nav-icon bi bi-circle"></i>
                <p>Kategori Bahan</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= url_to('printing/produk') ?>" class="nav-link<?= (current_url() == base_url('printing/produk')) ? ' active' : '' ?>">
                <i class="nav-icon bi bi-circle"></i>
                <p>Produk</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= url_to('printing/harga-level') ?>" class="nav-link<?= (current_url() == base_url('printing/harga-level')) ? ' active' : '' ?>">
                <i class="nav-icon bi bi-circle"></i>
                <p>Harga Level</p>
            </a>
        </li>

    </ul>
</li>