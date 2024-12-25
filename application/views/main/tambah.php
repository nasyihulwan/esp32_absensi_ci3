<!-- Additonal Icons -->
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/3.6.95/css/materialdesignicons.css">
<!-- Custom CSS -->
<style>
@import url(https://fonts.googleapis.com/css?family=Source+Sans+Pro);


.select-box {
    cursor: pointer;
    position: absolute;
    max-width: 50em;
    width: 192.1%;
}

.select,
.label {
    color: #4B49AC;
    display: block;
}

.select {
    width: 100%;
    position: absolute;
    top: 0;
    padding: 5px 0;
    height: 40px;
    opacity: 0;
    background: none transparent;
    border: 0 none;
}

.select-box1 {
    background: #ececec;
}

.label {
    position: relative;
    padding: 5px 10px;
    cursor: pointer;
}

.open .label::after {
    content: "▲";
}

.label::after {
    content: "▼";
    font-size: 12px;
    position: absolute;
    right: 0;
    top: 0;
    padding: 5px 15px;
    border-left: 5px solid #fff;
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
                    <h3 class="font-weight-bold mb-4"><?= $title ?></h3>
                    <div><?= $this->session->flashdata('message') ?></div>
                    <div class="row">
                        <div class="col-md-6 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <form id="jadwalAbsenForm" class="forms-sample" method="POST">
                                        <div class="form-group">
                                            <label for="id_dosen">Dosen</label>
                                            <input type="text" name="id_dosen" id="id_dosen" class="form-control"
                                                placeholder="ID Dosen" value="<?= $user['id'] ?>" required>
                                            <?= form_error('id_dosen', '<small class="text-danger">', '</small>') ?>
                                        </div>

                                        <div class="form-group">
                                            <label for="prodi">Program Studi</label>
                                            <select name="prodi" id="prodi" class="form-control">
                                                <option value="">-- Pilih Program Studi --</option>
                                                <option value="TEKKOM">TEKKOM</option>
                                                <option value="RPL">RPL</option>
                                                <option value="PMM">PMM</option>
                                                <option value="PGSD">PGSD</option>
                                                <option value="PGPAUD">PGPAUD</option>
                                            </select>
                                            <?= form_error('prodi', '<small class="text-danger">', '</small>') ?>
                                        </div>

                                        <div class="form-group">
                                            <label for="nama_matakuliah">Nama Mata Kuliah</label>
                                            <input type="text" name="nama_matakuliah" id="nama_matakuliah"
                                                class="form-control" placeholder="Nama Mata Kuliah">
                                            <?= form_error('nama_matakuliah', '<small class="text-danger">', '</small>') ?>
                                        </div>

                                        <div class="form-group">
                                            <label for="pertemuan">Pertemuan</label>
                                            <select name="pertemuan" id="pertemuan" class="form-control">
                                                <option value="">-- Pilih Pertemuan --</option>
                                                <?php 
                                                // Daftar enum pertemuan sesuai tabel
                                                $pertemuan_enum = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16'];
                                                foreach ($pertemuan_enum as $pertemuan): ?>
                                                <option value="<?= $pertemuan ?>"><?= $pertemuan ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <?= form_error('pertemuan', '<small class="text-danger">', '</small>') ?>
                                        </div>

                                        <div class="form-group">
                                            <label for="semester">Semester</label>
                                            <select name="semester" id="semester" class="form-control">
                                                <option value="">-- Pilih Semester --</option>
                                                <?php 
                                                // Daftar enum semester sesuai tabel
                                                $semester_enum = ['1', '2', '3', '4', '5', '6', '7', '8'];
                                                foreach ($semester_enum as $semester): ?>
                                                <option value="<?= $semester ?>"><?= $semester ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <?= form_error('semester', '<small class="text-danger">', '</small>') ?>
                                        </div>

                                        <div class="form-group">
                                            <label for="kelas">Kelas</label>
                                            <select name="kelas" id="kelas" class="form-control">
                                                <option value="">-- Pilih Kelas --</option>
                                                <?php foreach (['A', 'B', 'C', 'D', 'E', 'F'] as $kelas): ?>
                                                <option value="<?= $kelas ?>"><?= $kelas ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <?= form_error('kelas', '<small class="text-danger">', '</small>') ?>
                                        </div>

                                        <div class="form-group">
                                            <label for="tanggal">Tanggal</label>
                                            <input type="date" name="tanggal" id="tanggal" class="form-control">
                                            <?= form_error('tanggal', '<small class="text-danger">', '</small>') ?>
                                        </div>

                                        <div class="form-group">
                                            <label for="jam_masuk">Jam Masuk (JAM:MENIT)</label>
                                            <input type="time" name="jam_masuk" id="jam_masuk" class="form-control">
                                            <?= form_error('jam_masuk', '<small class="text-danger">', '</small>') ?>
                                        </div>

                                        <div class="form-group">
                                            <label for="jam_keluar">Jam Keluar (JAM:MENIT)</label>
                                            <input type="time" name="jam_keluar" id="jam_keluar" class="form-control">
                                            <?= form_error('jam_keluar', '<small class="text-danger">', '</small>') ?>
                                        </div>

                                        <div class="form-group">
                                            <label for="buka_absen_masuk">Toleransi Keterlambatan (JAM:MENIT)</label>
                                            <input type="time" name="buka_absen_masuk" id="buka_absen_masuk"
                                                class="form-control">
                                            <?= form_error('buka_absen_masuk', '<small class="text-danger">', '</small>') ?>
                                        </div>

                                        <div class="form-group">
                                            <label for="buka_absen_keluar">Batas Absen Keluar
                                                (JAM:MENIT)</label>
                                            <input type="time" name="buka_absen_keluar" id="buka_absen_keluar"
                                                class="form-control">
                                            <?= form_error('buka_absen_keluar', '<small class="text-danger">', '</small>') ?>
                                        </div>

                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <input type="text" name="status" id="status" class="form-control"
                                                value="Dibuka">
                                            <?= form_error('status', '<small class="text-danger">', '</small>') ?>
                                        </div>

                                        <div class="float-right">
                                            <button type="reset" class="btn btn-light">Cancel</button>
                                            <button type="submit" class="btn btn-primary" id="submit">Submit</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $this->load->view('__partials/_footer') ?>
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>

    <!-- partial:partials/_js.php -->
    <?php $this->load->view('__partials/_js') ?>
    <!-- partial -->

    <!-- Page specific script -->

    <script type="text/javascript">
    </script>
</body>

</html>