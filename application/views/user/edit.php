<!-- Additonal Icons -->
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/3.6.95/css/materialdesignicons.css">
<!-- Custom CSS -->
<style>
body {
    font-weight: bold;
}
</style>
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
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <div class="row">
                                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                                    <h3 class="font-weight-bold"><?= $title ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div><?= $this->session->userdata('message') ?></div>
                    <div class="row">
                        <div class="col-md-6 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <?= form_open_multipart('user/edit'); ?>
                                    <div class="form-group" hidden>
                                        <label>ID</label>
                                        <input hidden type="text" class="form-control" name="id" readonly
                                            value="<?= $user['id'] ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Nama Lengkap</label>
                                        <input type="text" class="form-control" name="name" placeholder="Full Name"
                                            value="<?= $user['name'] ?>">
                                        <?= form_error('name', '<small class="text-danger pl-3">', '</small>') ?>
                                    </div>
                                    <div class="form-group">
                                        <label>Username</label>
                                        <input type="text" class="form-control" name="username" placeholder="Username"
                                            value="<?= $user['username'] ?>">
                                        <?= form_error('username', '<small class="text-danger pl-3">', '</small>') ?>
                                    </div>
                                    <?php if ($user['role'] === 'mahasiswa'): ?>
                                    <div class="form-group">
                                        <label>Semester</label>
                                        <select class="form-control" name="semester">
                                            <?php for ($i = 1; $i <= 8; $i++): ?>
                                            <option value="<?= $i ?>" <?= $user['semester'] == $i ? 'selected' : '' ?>>
                                                <?= $i ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Kelas</label>
                                        <select class="form-control" name="kelas">
                                            <option value="-" <?= $user['kelas'] == '-' ? 'selected' : '' ?>>-</option>
                                            <option value="A" <?= $user['kelas'] == 'A' ? 'selected' : '' ?>>A</option>
                                            <option value="B" <?= $user['kelas'] == 'B' ? 'selected' : '' ?>>B</option>
                                            <option value="C" <?= $user['kelas'] == 'C' ? 'selected' : '' ?>>C</option>
                                            <option value="D" <?= $user['kelas'] == 'D' ? 'selected' : '' ?>>D</option>
                                        </select>
                                    </div>
                                    <?php endif; ?>
                                    <div class="form-group">
                                        <label>Gambar</label>
                                        <input type="file" class="dropify" name="image"
                                            data-default-file="<?= base_url('assets/images/profile/') . $user['image'] ?>">
                                        <?= form_error('image', '<small class="text-danger pl-3">', '</small>') ?>
                                    </div>
                                    <div class="float-right">
                                        <button class="btn btn-light">Cancel</button>
                                        <button type="submit" class="btn btn-primary mr-2">Submit</button>
                                    </div>
                                    </form>
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
    $('.dropify').dropify();
    </script>
</body>

</html>