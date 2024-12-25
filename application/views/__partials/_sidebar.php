<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="<?= site_url('dashboard') ?>">
                <i class="icon-grid menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#master" aria-expanded="false" aria-controls="master">
                <i class="icon-grid-2 menu-icon"></i>
                <span class="menu-title">Main</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="master">
                <ul class="nav flex-column sub-menu">

                    <?php if ($this->session->userdata('role') == "mahasiswa") { ?>
                    <li class="nav-item"> <a class="nav-link" href="<?= site_url('main/riwayat/' . $this->session->userdata('id_fingerprint')) ?>
">
                            Riwayat Absen
                        </a>
                    </li>
                    <?php } else if ($this->session->userdata('role') == "dosen") { ?>
                    <li class="nav-item"> <a class="nav-link" href="<?= site_url('main/') ?>tambah"> Tambah
                            Jadwal</a>
                    </li>
                    <?php } else if ($this->session->userdata('role') != "mahasiswa")?>

                    <li class="nav-item"> <a class="nav-link" href="<?= site_url('main/') ?>jadwal"> List Jadwal
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#user_manager" aria-expanded="false"
                aria-controls="user_manager">
                <i class="icon-head menu-icon"></i>
                <span class="menu-title">User Manager</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="user_manager">
                <ul class="nav flex-column sub-menu">
                    <?php if ($this->session->userdata('role') != "admin") { ?>
                    <li class="nav-item"> <a class="nav-link" href="<?= site_url('user/') ?>profile"> Profil </a></li>
                    <?php } else { ?>
                    <li class="nav-item"> <a class="nav-link" href="<?= site_url('user/') ?>profile"> Profil </a></li>
                    <li class="nav-item"> <a class="nav-link" href="<?= base_url('user/') ?>edit"> Edit Profil </a></li>
                    <li class="nav-item"> <a class="nav-link" href="<?= base_url('user/') ?>list"> List User </a></li>
                    <li class="nav-item"> <a class="nav-link" href="<?= base_url('user/') ?>add"> Buat User Baru </a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </li>
    </ul>
</nav>