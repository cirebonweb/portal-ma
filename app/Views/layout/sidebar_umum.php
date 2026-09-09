<?php
$umum_link = [
    'umum/kategori-konsumen',
    'umum/konsumen',
];
$umum_aktif = in_array(str_replace(base_url(), '', current_url()), $umum_link);
?>

<!-- Umum -->
<li class="nav-item<?= $umum_aktif ? ' menu-open' : ''  ?>">
    <a href="#" class="nav-link<?= $umum_aktif ? ' active' : ''  ?>">
        <i class="nav-icon bi bi-nut"></i>
        <p>Umum <i class="right bi bi-caret-left"></i></p>
    </a>
    <ul class="nav nav-treeview">

        <li class="nav-item">
            <a href="<?= url_to('umum/kategori-konsumen') ?>" class="nav-link<?= (current_url() == base_url('umum/kategori-konsumen')) ? ' active' : '' ?>">
                <i class="nav-icon bi bi-arrow-return-right"></i>
                <p>Kategori Konsumen</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= url_to('umum/konsumen') ?>" class="nav-link<?= (current_url() == base_url('umum/konsumen')) ? ' active' : '' ?>">
                <i class="nav-icon bi bi-arrow-return-right"></i>
                <p>Konsumen</p>
            </a>
        </li>

    </ul>
</li>