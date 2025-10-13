<nav class="navbar navbar-expand-lg bg-cintaraja navbar-dark shadow-sm">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="<?= base_url('/'); ?>">
      <img src="<?= base_url('assets/img/logo-bps.png'); ?>" alt="Logo BPS" class="logo-bps">
      <strong>e-WPDP</strong>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarMenu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="<?= base_url('/'); ?>">Beranda</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= base_url('wpdp'); ?>">WPDP</a>
        </li>

        <!-- Dropdown Master Data -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="masterDataDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Master Data
          </a>
          <ul class="dropdown-menu" aria-labelledby="masterDataDropdown">
            <li><a class="dropdown-item" href="<?= base_url('program'); ?>">Program</a></li>
            <li><a class="dropdown-item" href="<?= base_url('kegiatan'); ?>">Kegiatan</a></li>
            <li><a class="dropdown-item" href="<?= base_url('kro'); ?>">KRO</a></li>
            <li><a class="dropdown-item" href="<?= base_url('ro'); ?>">RO</a></li>
            <li><a class="dropdown-item" href="<?= base_url('detil'); ?>">Detil</a></li>
            <li><hr class="dropdown-divider"></li> <!-- Pemisah -->
            <li><a class="dropdown-item" href="<?= base_url('masterdata/total_pagu'); ?>">Total Pagu</a></li> <!-- Menu baru -->
          </ul>
        </li>
        <!-- Dropdown RPD -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="rpdDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            RPD
          </a>
          <ul class="dropdown-menu" aria-labelledby="rpdDropdown">
            <li><a class="dropdown-item" href="<?= base_url('masterdata/ro'); ?>">Input RPD</a></li>
            <li><a class="dropdown-item" href="<?= base_url('rpd'); ?>">Laporan RPD</a></li>
          </ul>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="<?= base_url('tentang'); ?>">Tentang</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
