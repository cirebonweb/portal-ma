<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="description" content="<?= setting('App.siteTagline') ?>" />
  <meta name="author" content="www.cirebonweb.com" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="robots" content="noindex,nofollow" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <meta name="csrf-token" content="<?= csrf_hash() ?>" />

  <title><?= esc($pageTitle) . ' | ' . setting('App.siteNama') ?></title>
  <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('upload/logo/' . (setting('App.logoIkon180') ?: 'crb-icon-180.png')) ?>">
  <link rel="icon" type="image/png" sizes="192x192" href="<?= base_url('upload/logo/' . (setting('App.logoIkon192') ?: 'crb-icon-192.png')) ?>">
  <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('upload/logo/' . (setting('App.logoIkon32') ?: 'crb-icon-32.png')) ?>">
  <link rel="shortcut icon" type="image/x-icon" href="<?= base_url('upload/logo/' . (setting('App.logoIkon') ?: 'crb-icon.ico')) ?>">
  <link rel="preload" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" as="style" />
  <link rel="preload" href="<?= base_url('dist/css/adminlte.min.css') ?>" as="style" />
  <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />
  <link rel="stylesheet" href="<?= base_url('plugin/fontawesome/css/all.min.css') ?>" />
  <link rel="stylesheet" href="<?= base_url('dist/css/adminlte.min.css') ?>" />
  <link rel="stylesheet" href="<?= base_url('plugin/sweetalert/sweetalert2.min.css') ?>" />
  <link rel="stylesheet" href="<?= base_url('vendor/css/custom_adminlte.min.css') ?>" />
  <?= $this->renderSection('css') ?>
</head>

<body class="hold-transition sidebar-mini">

  <!-- loading modal bootstrap -->
  <div class="modal fade" id="progressModal" tabindex="-1" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content text-center">
        <div class="modal-body">
          <div class="progress" style="height: 35px;font-size: 14px">
            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 100%">0 %</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="wrapper">
    <!-- Preloader https://icon-sets.iconify.design/svg-spinners -->
    <div class="preloader flex-column justify-content-center align-items-center">
      <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24">
        <rect width="10" height="10" x="1" y="1" fill="currentColor" rx="1">
          <animate id="SVG7WybndBt" fill="freeze" attributeName="x" begin="0;SVGo3aOUHlJ.end" dur="0.2s" values="1;13" />
          <animate id="SVGVoKldbWM" fill="freeze" attributeName="y" begin="SVGFpk9ncYc.end" dur="0.2s" values="1;13" />
          <animate id="SVGKsXgPbui" fill="freeze" attributeName="x" begin="SVGaI8owdNK.end" dur="0.2s" values="13;1" />
          <animate id="SVG7JzAfdGT" fill="freeze" attributeName="y" begin="SVG28A4To9L.end" dur="0.2s" values="13;1" />
        </rect>
        <rect width="10" height="10" x="1" y="13" fill="currentColor" rx="1">
          <animate id="SVGUiS2jeZq" fill="freeze" attributeName="y" begin="SVG7WybndBt.end" dur="0.2s" values="13;1" />
          <animate id="SVGU0vu2GEM" fill="freeze" attributeName="x" begin="SVGVoKldbWM.end" dur="0.2s" values="1;13" />
          <animate id="SVGOIboFeLf" fill="freeze" attributeName="y" begin="SVGKsXgPbui.end" dur="0.2s" values="1;13" />
          <animate id="SVG14lAaeuv" fill="freeze" attributeName="x" begin="SVG7JzAfdGT.end" dur="0.2s" values="13;1" />
        </rect>
        <rect width="10" height="10" x="13" y="13" fill="currentColor" rx="1">
          <animate id="SVGFpk9ncYc" fill="freeze" attributeName="x" begin="SVGUiS2jeZq.end" dur="0.2s" values="13;1" />
          <animate id="SVGaI8owdNK" fill="freeze" attributeName="y" begin="SVGU0vu2GEM.end" dur="0.2s" values="13;1" />
          <animate id="SVG28A4To9L" fill="freeze" attributeName="x" begin="SVGOIboFeLf.end" dur="0.2s" values="1;13" />
          <animate id="SVGo3aOUHlJ" fill="freeze" attributeName="y" begin="SVG14lAaeuv.end" dur="0.2s" values="1;13" />
        </rect>
      </svg>
    </div>

    <!-- auth_group -->
    <?php $auth_group = (auth()->user()->inGroup('klien')) ? 'klien' : 'admin'; ?>

    <!-- .navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link list" data-widget="pushmenu" href="#" role="button"><i class="bi bi-arrows-collapse-vertical"></i></a></li>
        <li class="nav-item d-none d-sm-inline-block"><a href="<?= url_to($auth_group) ?>" class="nav-link">Home</a></li>
        <!-- auth_group -->
        <?= $this->include('layout/navbar_umum') ?>
      </ul>

      <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="javascript:void(0)">Hai, <?= auth()->user()->username ?> <i class="bi bi-caret-down ml-1"></i></a>
          <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
            <a href="<?= url_to('profil') ?>" class="dropdown-item"><i class="bi bi-person mr-3"></i> Profil</a>
            <div class="dropdown-divider"></div>
            <a href="<?= url_to('logout') ?>" class="dropdown-item"><i class="bi bi-box-arrow-right mr-3"></i>Logout</a>
          </div>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- .sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <a href="<?= url_to($auth_group) ?>" class="brand-link"><img src="<?= base_url('upload/logo/') . setting('App.logoPutih') ?>" height="58"></a>

      <div class="sidebar">
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

            <!-- Dashboard -->
            <li class="nav-item">
              <a href="<?= url_to($auth_group) ?>" class="nav-link<?= (current_url() == base_url($auth_group)) ? ' active' : '' ?>">
                <i class="nav-icon bi bi-speedometer"></i>
                <p>Dashboard</p>
              </a>
            </li>

            <!-- Profil -->
            <li class="nav-item">
              <a href="<?= url_to('profil') ?>" class="nav-link<?= (current_url() == base_url('profil')) ? ' active' : '' ?>">
                <i class="nav-icon bi bi-person-vcard"></i>
                <p>Profil</p>
              </a>
            </li>

            <!-- auth_group -->
            <?= $this->include('layout/sidebar_umum') ?>

          </ul>
        </nav>
      </div>
    </aside>
    <!-- /.sidebar -->

    <div class="content-wrapper">
      <!-- awal judul halaman -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1><?= $pageTitle ?></h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">

                <li class="breadcrumb-item"><a href="<?= route_to($auth_group) ?>">Home</a></li>
                <li class="breadcrumb-item active"><?= ($navigasi ? $navigasi . '→ &nbsp;' : '') ?><?= ($navTitle ?? $pageTitle) ?></li>
              </ol>
            </div>
          </div>
        </div>
      </section>

      <!-- awal konten -->
      <?= $this->renderSection('konten') ?>
    </div>

    <footer class="main-footer d-flex flex-column flex-md-row justify-content-between align-items-center">
      <div class="text-center text-md-left">
        Copyright &copy; 2014 - <?= date("Y"); ?> | All rights reserved. <br>
        <a href="/"><?= setting('App.siteNama') . ' - ' . setting('App.siteTagline') ?></a>
      </div>

      <div class="text-center text-md-right">
        Versi: <?= CodeIgniter\CodeIgniter::CI_VERSION ?><br>
        Render: <span id="microtime"></span> detik.
      </div>
    </footer>
  </div>

  <script src="<?= base_url('plugin/jquery/jquery.min.js') ?>"></script>
  <script src="<?= base_url('plugin/bootstrap/bootstrap.bundle.min.js') ?>"></script>
  <script src="<?= base_url('dist/js/adminlte.min.js') ?>"></script>
  <script src="<?= base_url('plugin/sweetalert/sweetalert2.min.js') ?>" defer></script>
  <script>
    const Beranda = {
      refreshCsrf: function() {
        console.log("CSRF token refreshed (placeholder).");
        const e = document.querySelector('meta[name="csrf-token"]');
        e && $.ajaxSetup({
          headers: {
            "X-CSRF-TOKEN": e.getAttribute("content")
          }
        })
      },
      showPreloader: function() {
        const $pre = $('.preloader');
        $pre.css({
          'height': '100%',
          'display': 'flex',
          'background-color': 'rgba(0, 0, 0, 0.4)'
        });
        $pre.children().show();
      },
      hidePreloader: function() {
        const $pre = $('.preloader');
        $pre.css('height', 0);
        setTimeout(() => {
          $pre.children().hide();
        }, 500);
      }
    };
  </script>
  <?= $this->renderSection('js') ?>
  <script>
    <?php if (session()->getFlashdata('sukses')): ?>
      $(function() {
        Swal.fire({
          title: 'Sukses',
          html: '<?= session()->getFlashdata('sukses') ?>',
          icon: 'success'
        })
      });
    <?php elseif (session()->getFlashdata('error')): ?>
      $(function() {
        Swal.fire({
          title: 'Error',
          html: '<?= session()->getFlashdata('error') ?>',
          icon: 'error'
        })
      });
    <?php elseif (session()->getFlashdata('errors')): ?>
      <?php $errors = session()->getFlashdata('errors');
      if (!is_array($errors)) {
        $errors = [$errors];
      } ?>
      $(function() {
        let errors = '<?= implode('<br>', $errors) ?>';
        Swal.fire({
          title: 'Error',
          html: errors,
          icon: 'error'
        })
      });
    <?php endif; ?>
    window.addEventListener('load', (function() {
      const renderTime = performance.now();
      document.getElementById('microtime').textContent = (renderTime / 1e3).toFixed(2)
    }));
  </script>

</body>

</html>

<!-- Penggunaan memori: <?= number_format(memory_get_usage() / 1048576, 2) ?> mb -->
<!-- Waktu render halaman: {elapsed_time} detik -->
<!-- Waktu render framework: <?= number_format(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 4) ?> detik -->