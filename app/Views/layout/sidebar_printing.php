<?php
$printing_link = [
  'printing/kategori',
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
            <a href="<?= url_to('printing/kategori') ?>" class="nav-link<?= (current_url() == base_url('printing/kategori')) ? ' active' : '' ?>">
                <i class="nav-icon bi bi-circle"></i>
                <p>Kategori Mesin</p>
            </a>
        </li>

    </ul>
</li>