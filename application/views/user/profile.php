<!-- Additonal Icons -->
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/3.6.95/css/materialdesignicons.css">
<!-- Custom CSS -->
<style>
body {
    background-color: #f9f9fa;
}

.padding {
    padding: 3rem !important;
}

.user-card-full {
    overflow: hidden;
}

.card {
    box-shadow: 0 1px 20px 0 rgba(69, 90, 100, 0.08);
    border: none;
    margin-bottom: 30px;
}

.user-profile {
    padding: 20px 0;
    background: #4B49AC;
}

.card-block {
    padding: 1.25rem;
}

.img-radius {
    border-radius: 5px;
}

h6 {
    font-size: 14px;
}

.card .card-block p {
    line-height: 25px;
}

.text-muted {
    color: #919aa3 !important;
}

.f-w-600 {
    font-weight: 600;
}
</style>
</head>

<body>

    <div class="container-scroller">
        <!-- Navbar -->
        <?php $this->load->view('__partials/_navbar') ?>

        <div class="container-fluid page-body-wrapper">
            <!-- Sidebar -->
            <?php $this->load->view('__partials/_sidebar') ?>

            <div class="main-panel">
                <div class="content-wrapper">
                    <h3 class="font-weight-bold"><?= $title ?></h3>

                    <div class="row">
                        <div class="col-xl-6 col-md-12">
                            <div class="card user-card-full">
                                <div class="row m-l-0 m-r-0">
                                    <div class="col-sm-4 user-profile">
                                        <div class="card-block text-center text-white">
                                            <div class="m-b-25">
                                                <img src="<?= base_url('assets/images/profile/') . $user['image'] ?>"
                                                    class="img-radius" style="max-width: 150px;"
                                                    alt="User-Profile-Image">
                                            </div>
                                            <h6 class="f-w-600"><?= $user['name'] ?></h6>
                                            <p><?= ucfirst($user['role']) ?></p>
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="card-block">
                                            <h6 class="m-b-20 p-b-5 b-b-default f-w-600">Informasi</h6>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <p class="m-b-10 f-w-600">ID Fingerprint</p>
                                                    <h6 class="text-muted f-w-400"><?= $user['id_fingerprint'] ?></h6>
                                                </div>
                                                <div class="col-sm-6">
                                                    <p class="m-b-10 f-w-600">Prodi</p>
                                                    <h6 class="text-muted f-w-400"><?= $user['prodi'] ?></h6>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <p class="m-b-10 f-w-600">Semester</p>
                                                    <h6 class="text-muted f-w-400"><?= $user['semester'] ?></h6>
                                                </div>
                                                <div class="col-sm-6">
                                                    <p class="m-b-10 f-w-600">Kelas</p>
                                                    <h6 class="text-muted f-w-400"><?= $user['kelas'] ?></h6>
                                                </div>
                                            </div>
                                            <!-- <div class="joined mt-3">
                                                <p class="card-text"><small class="text-muted">Bergabung sejak
                                                        <?= date('d F Y', strtotime($user['date_created'])) ?></small>
                                                </p>
                                            </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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

    <!-- Page specific script -->
    <script>
    </script>
</body>

</html>