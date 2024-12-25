<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('__partials/_head') ?>
</head>

<body>
    <div class="container-scroller">
        <!-- partial:partials/_navbar.php -->
        <?php $this->load->view('__partials/_navbar') ?>
        <!-- partial -->

        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_settings-panel.php -->
            <?php $this->load->view('__partials/_settings-panel') ?>
            <!-- partial -->

            <!-- partial:partials/_sidebar.php -->
            <?php $this->load->view('__partials/_sidebar') ?>
            <!-- partial -->

            <div class="main-panel">
                <div class="content-wrapper">
                    <!-- Header Section -->
                    <div class="row">
                        <div class="col-md-12 grid-margin">
                            <div class="row">
                                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                                    <h3 class="font-weight-bold">
                                        <?= htmlspecialchars($title) . $this->session->userdata('name') ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Connected Device Section -->
                    <div class="row">
                        <div class="col-md-6 grid-margin stretch-card">
                            <div class="card tale-bg">
                                <div class="card-people mt-auto">
                                    <img src="<?= base_url('assets/images/dashboard/people.svg') ?>" alt="people">
                                    <?php if ($this->session->userdata('role') == 'admin' && !empty($alat)): ?>
                                    <div class="weather-info">
                                        <div class="d-flex">
                                            <div>
                                                <h3 class="mb-0 font-weight-normal"><i class="icon-sun mr-2"></i>ESP32
                                                    Connected!</h3>
                                            </div>
                                            <div class="ml-2">
                                                <h4 class="location font-weight-normal">SSID:
                                                    <?= htmlspecialchars($alat['ssid']) ?></h4>
                                                <h6 class="font-weight-normal">IP:
                                                    <?= htmlspecialchars($alat['ip_address']) ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                    <?php elseif ($this->session->userdata('role') == 'mahasiswa'): ?>
                                    <div class="weather-info">
                                    </div>
                                    <?php else: ?>
                                    <div class="weather-info">
                                        <div class="d-flex">
                                            <h6 class="font-weight-normal">No connected device.</h6>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>

                            </div>
                        </div>

                        <?php if ($this->session->userdata('role') == 'admin'): ?>
                        <div class="col-md-6 grid-margin stretch-card">
                            <?php if (!empty($alat_list)): ?>
                            <?php $is_any_connected = array_reduce($alat_list, fn($carry, $item) => $carry || $item->is_connected == 1, false); ?>
                            <?php foreach ($alat_list as $item): ?>
                            <div class="col-md-12">
                                <div class="card h-100 text-bg-primary">
                                    <div class="card-header"
                                        style="background-color: <?= $item->is_connected == 1 ? 'green' : 'inherit'; ?>;">
                                        <h5
                                            class="card-title mb-0 <?= $item->is_connected == 1 ? 'text-white' : ''; ?>">
                                            <?= htmlspecialchars($item->nama_alat) ?>
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text">Kode Alat:
                                            <strong><?= htmlspecialchars($item->kode_alat) ?></strong>
                                        </p>
                                        <p class="card-text">IP Address:
                                            <strong><?= htmlspecialchars($item->ip_address) ?></strong>
                                        </p>
                                        <p class="card-text">SSID:
                                            <strong><?= htmlspecialchars($item->ssid) ?></strong>
                                        </p>
                                        <p class="card-text">Last Heartbeat:
                                            <strong><?= htmlspecialchars($item->last_heartbeat) ?></strong>
                                        </p>

                                        <?php if ($item->is_connected == 1): ?>
                                        <a href="<?= site_url('dashboard/putuskan/' . $item->kode_alat) ?>"
                                            class="btn btn-danger mt-3">Putuskan</a>
                                        <?php else: ?>
                                        <a href="<?= site_url('dashboard/hubungkan/' . $item->kode_alat) ?>"
                                            class="btn btn-light mt-3"
                                            <?= $is_any_connected ? 'disabled' : ''; ?>>Hubungkan</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <div class="col-12">
                                <div class="alert alert-warning text-center" role="alert">
                                    Tidak ada alat yang aktif saat ini.
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                    </div>

                    <!-- Alat List Section -->
                    <div class="row">

                    </div>

                    <!-- Statistic Section -->
                    <div class="row">
                        <?php if ($this->session->userdata('role') != 'mahasiswa') { ?>
                        <div class="col-md-12 grid-margin transparent">
                            <div class="row">
                                <div class="col-md-6 mb-4 stretch-card transparent">
                                    <div class="card card-tale">
                                        <div class="card-body">
                                            <p class="mb-4">Jadwal Matakuliah Hari Ini</p>
                                            <p class="fs-30 mb-2"><?= $jadwalHariIni ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4 stretch-card transparent">
                                    <div class="card card-dark-blue">
                                        <div class="card-body">
                                            <p class="mb-4">Jumlah Admin</p>
                                            <p class="fs-30 mb-2"><?= $admin ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4 stretch-card transparent">
                                    <div class="card card-light-blue">
                                        <div class="card-body">
                                            <p class="mb-4">Jumlah Mahasiswa</p>
                                            <p class="fs-30 mb-2"><?= $mahasiswa ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4 stretch-card transparent">
                                    <div class="card card-light-danger">
                                        <div class="card-body">
                                            <p class="mb-4">Jumlah Dosen</p>
                                            <p class="fs-30 mb-2"><?= $dosen ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>


                        <?php if ($this->session->userdata('role') == 'mahasiswa') { ?>
                        <div class="col-md-6 grid-margin transparent">
                            <div class="row">
                                <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
                                    <div class="card card-light-blue">
                                        <div class="card-body">
                                            <p class="mb-4">Absen Tepat Waktu</p>
                                            <p class="fs-30 mb-2"><?= $absenTepatWaktu ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 stretch-card transparent">
                                    <div class="card card-light-danger">
                                        <div class="card-body">
                                            <p class="mb-4">Absen Terlambat</p>
                                            <p class="fs-30 mb-2"><?= $absenTerlambat ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>

                    </div>
                </div>
                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.php -->
                <?php $this->load->view('__partials/_footer') ?>
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->

    <!-- partial:partials/_js.php -->
    <?php $this->load->view('__partials/_js') ?>
    <!-- partial -->
</body>

</html>