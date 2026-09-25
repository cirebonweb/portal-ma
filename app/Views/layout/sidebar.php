<!-- Menu Data -->
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon bi bi-clipboard2-data"></i>
        <p>Data<i class="right bi bi-chevron-right"></i></p>
    </a>

    <ul class="nav nav-treeview">

        <li class="nav-item">
            <a href="<?= url_to('konsumen') ?>" class="nav-link<?= (current_url() == base_url('konsumen')) ? ' active' : '' ?>">
                <i class="nav-icon bi bi-circle"></i>
                <p>Data Konsumen</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= url_to('supplier') ?>" class="nav-link<?= (current_url() == base_url('supplier')) ? ' active' : '' ?>">
                <i class="nav-icon bi bi-circle"></i>
                <p>Data Supplier</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= url_to('mesin') ?>" class="nav-link<?= (current_url() == base_url('mesin')) ? ' active' : '' ?>">
                <i class="nav-icon bi bi-circle"></i>
                <p>Data Mesin</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= url_to('produk') ?>" class="nav-link<?= (current_url() == base_url('produk')) ? ' active' : '' ?>">
                <i class="nav-icon bi bi-circle"></i>
                <p>Data Produk</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= url_to('nota') ?>" class="nav-link<?= (current_url() == base_url('nota')) || (current_url() == base_url('nota/isi')) ? ' active' : '' ?>">
                <i class="nav-icon bi bi-circle"></i>
                <p>Data Nota</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= url_to('produk') ?>" class="nav-link<?= (current_url() == base_url('produk')) ? ' active' : '' ?>">
                <i class="nav-icon bi bi-circle"></i>
                <p>Data Cetak</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= url_to('produk') ?>" class="nav-link<?= (current_url() == base_url('produk')) ? ' active' : '' ?>">
                <i class="nav-icon bi bi-circle"></i>
                <p>Data Pembayaran</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= url_to('produk') ?>" class="nav-link<?= (current_url() == base_url('produk')) ? ' active' : '' ?>">
                <i class="nav-icon bi bi-circle"></i>
                <p>Data Laporan</p>
            </a>
        </li>
    </ul>
</li>

<!-- Menu Bahan -->
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon bi bi-box-seam"></i>
        <p>Bahan<i class="right bi bi-chevron-right"></i></p>
    </a>

    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="<?= url_to('bahan') ?>" class="nav-link<?= (current_url() == base_url('bahan')) ? ' active' : '' ?>">
                <i class="nav-icon bi bi-circle"></i>
                <p>Data Bahan</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= url_to('bahan-order') ?>" class="nav-link<?= (current_url() == base_url('bahan-order')) ||  (current_url() == base_url('bahan-order/isi')) ? ' active' : '' ?>">
                <i class="nav-icon bi bi-circle"></i>
                <p>Order Bahan</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= url_to('bahan-stok') ?>" class="nav-link<?= (current_url() == base_url('bahan-stok')) ? ' active' : '' ?>">
                <i class="nav-icon bi bi-circle"></i>
                <p>Stok Bahan</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= url_to('bahan-stok') ?>" class="nav-link<?= (current_url() == base_url('bahan-stok')) ? ' active' : '' ?>">
                <i class="nav-icon bi bi-circle"></i>
                <p>Sisa Bahan</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= url_to('bahan-stok') ?>" class="nav-link<?= (current_url() == base_url('bahan-stok')) ? ' active' : '' ?>">
                <i class="nav-icon bi bi-circle"></i>
                <p>Limbah Bahan</p>
            </a>
        </li>
    </ul>
</li>

<!-- Menu Master -->
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon bi bi-layers"></i>
        <p>Master<i class="right bi bi-chevron-right"></i></p>
    </a>

    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="<?= url_to('konsumen-tipe') ?>" class="nav-link<?= (current_url() == base_url('konsumen-tipe')) ? ' active' : '' ?>">
                <i class="nav-icon bi bi-circle"></i>
                <p>Tipe Konsumen</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= url_to('mesin-tipe') ?>" class="nav-link<?= (current_url() == base_url('mesin-tipe')) ? ' active' : '' ?>">
                <i class="nav-icon bi bi-circle"></i>
                <p>Tipe Mesin</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= url_to('harga-tipe') ?>" class="nav-link<?= (current_url() == base_url('harga-tipe')) ? ' active' : '' ?>">
                <i class="nav-icon bi bi-circle"></i>
                <p>Tipe Harga</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= url_to('harga-khusus') ?>" class="nav-link<?= (current_url() == base_url('harga-khusus')) ? ' active' : '' ?>">
                <i class="nav-icon bi bi-circle"></i>
                <p>Harga Khusus</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= url_to('finishing') ?>" class="nav-link<?= (current_url() == base_url('finishing')) ? ' active' : '' ?>">
                <i class="nav-icon bi bi-circle"></i>
                <p>Finishing</p>
            </a>
        </li>
    </ul>
</li>