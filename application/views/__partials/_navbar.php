<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo mr-5" href="<?= base_url('dashboard') ?>"><b>ESP32 Absensi</b></a>
        <a class="navbar-brand brand-logo-mini" href="<?= base_url('dashboard') ?>"><b>ESP32</b></a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="icon-menu"></span>
        </button>
        <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item nav-profile dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                    <img src="<?= base_url('assets/images/profile/') . $user['image'] ?>" class="img-radius"
                        style="max-width: 100px;" alt="User-Profile-Image">
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                    <a class="dropdown-item">
                        <i class="ti-settings text-primary"></i>
                        Settings
                    </a>
                    <a class="dropdown-item" href="<?= site_url('auth/logout') ?>">
                        <i class="ti-power-off text-primary"></i>
                        Logout
                    </a>
                </div>
            </li>
        </ul>
    </div>
</nav>