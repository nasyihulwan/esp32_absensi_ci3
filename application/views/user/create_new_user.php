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

<script>
function validateForm() {
    const passwordAbsen = document.getElementById('password_absen').value;
    const konfirPasswordAbsen = document.getElementById('konfir_password_absen').value;

    return passwordAbsen && konfirPasswordAbsen;
}

function updateEnrollButton() {
    const enrollButton = document.getElementById('enrollButton');
    enrollButton.disabled = !validateForm();
}

// Dapatkan IP alat yang terhubung dari PHP
const ipAddressConnected = <?= json_encode($ipAddressConnected); ?>;

function enrollFingerprint(idFingerprint) {
    if (!ipAddressConnected) {
        alert('Tidak ada perangkat yang terhubung.');
        return;
    }

    const enrollUrl = `http://${ipAddressConnected}/enroll?id_fingerprint=${idFingerprint}`;
    console.log('Enroll URL:', enrollUrl);

    fetch(enrollUrl)
        .then(response => response.json())
        .then(result => {
            if (result.status === 'success') {
                alert('Enroll fingerprint berhasil!');
                document.getElementById('userForm').submit();
            } else {
                alert('Enroll fingerprint gagal: ' + result.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan: ' + error.message);
        });
}
</script>

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

                    <?php if (!$connected_status): ?>
                    <div class="alert alert-warning" role="alert">
                        Mohon hubungkan alat terlebih dahulu!
                    </div>
                    <?php else: ?>
                    <div class="row">
                        <div class="col-md-6 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <form id="userForm" class="forms-sample" method="POST">
                                        <div class="form-group">
                                            <label>ID Fingerprint</label>
                                            <input type="text" class="form-control" id="id_fingerprint"
                                                name="id_fingerprint"
                                                value="<?= isset($last_fingerprint_id) ? $last_fingerprint_id + 1 : 1 ?>"
                                                disabled>
                                            <small class="form-text text-muted">ID ini dihasilkan secara otomatis dan
                                                tidak dapat diubah.</small>
                                        </div>
                                        <div class="form-group">
                                            <label>Nama</label>
                                            <input type="text" name="name" class="form-control" placeholder="Nama"
                                                value="<?= set_value('name') ?>">
                                            <?= form_error('name', '<small class="text-danger">', '</small>') ?>
                                        </div>

                                        <div class="form-group">
                                            <label>Username</label>
                                            <input type="text" name="username" class="form-control"
                                                placeholder="Username" value="<?= set_value('username') ?>">
                                            <?= form_error('username', '<small class="text-danger">', '</small>') ?>
                                        </div>

                                        <div class="form-group">
                                            <label>Password Login</label>
                                            <input type="text" name="password1" class="form-control"
                                                placeholder="Password" value="<?= set_value('password1') ?>">
                                            <?= form_error('password1', '<small class="text-danger">', '</small>') ?>
                                        </div>

                                        <div class="form-group">
                                            <label>Konfirmasi Password Login</label>
                                            <input type="text" name="password2" class="form-control"
                                                placeholder="Konfirmasi Password" value="<?= set_value('password2') ?>">
                                            <?= form_error('password2', '<small class="text-danger">', '</small>') ?>
                                        </div>

                                        <div class="form-group">
                                            <label>Role</label>
                                            <select class="form-control" id="selectrole" name="role"
                                                value="<?= set_value('role') ?>" onchange="toggleEnrollButton()">
                                                <option value="admin">Admin</option>
                                                <option value="dosen">Dosen</option>
                                                <option value="mahasiswa">Mahasiswa</option>
                                            </select>
                                            <?= form_error('role', '<small class="text-danger">', '</small>') ?>
                                        </div>

                                        <div class="form-group" id="prodiField" style="display:none;">
                                            <label>Prodi</label>
                                            <select class="form-control" name="prodi">
                                                <option value="-">-</option>
                                                <option value="TEKKOM">TEKKOM</option>
                                            </select>
                                            <?= form_error('prodi', '<small class="text-danger">', '</small>') ?>
                                        </div>

                                        <div class="form-group" id="semesterField" style="display:none;">
                                            <label>Semester</label>
                                            <select class="form-control" name="semester">
                                                <option value="-">-</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                            </select>
                                            <?= form_error('semester', '<small class="text-danger">', '</small>') ?>
                                        </div>

                                        <div class="form-group" id="kelasField" style="display:none;">
                                            <label>Kelas</label>
                                            <select class="form-control" name="kelas">
                                                <option value="-">-</option>
                                                <option value="A">A</option>
                                                <option value="B">B</option>
                                                <option value="C">C</option>
                                                <option value="D">D</option>
                                            </select>
                                            <?= form_error('kelas', '<small class="text-danger">', '</small>') ?>
                                        </div>

                                        <div class="form-group" id="passwordAbsenField" style="display:none;">
                                            <label>Password Absen</label>
                                            <input id="password_absen" type="number" name="password_absen"
                                                class="form-control" placeholder="6 Digit Angka" maxlength="6"
                                                oninput="if(this.value.length > 6) this.value = this.value.slice(0, 6); updateEnrollButton()"
                                                value="<?= set_value('password_absen') ?>">
                                            <?= form_error('password_absen', '<small class="text-danger">', '</small>') ?>
                                        </div>

                                        <div class="form-group" id="confirmPasswordAbsenField" style="display:none;">
                                            <label>Konfirmasi Password Absen</label>
                                            <input id="konfir_password_absen" type="number"
                                                name="password_absen_confirm" class="form-control"
                                                placeholder="Konfirmasi Password Absen" maxlength="6"
                                                oninput="if(this.value.length > 6) this.value = this.value.slice(0, 6); updateEnrollButton()"
                                                value="<?= set_value('password_absen_confirm') ?>">
                                            <?= form_error('password_absen_confirm', '<small class="text-danger">', '</small>') ?>
                                        </div>


                                        <div class="float-right">
                                            <button type="reset" class="btn btn-light">Cancel</button>
                                            <button type="submit" class="btn btn-primary"
                                                id="submitButton">Submit</button>
                                            <button type="button" class="btn btn-primary" id="enrollButton" disabled
                                                onclick="enrollFingerprint(<?php echo $last_fingerprint_id + 1; ?>)">Enroll
                                                Fingerprint</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php endif; ?>
                </div>
                <?php $this->load->view('__partials/_footer') ?>
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->


    <div class="modal fade" id="teacherposition">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Jabatan Guru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <?php
                    foreach ($teacher_position as $row) { ?>
                    <a href="#" class="btn btn-outline-primary btn-block mb-3 pilih" data-id="<?= $row->id ?>"
                        data-position="<?= $row->position ?>">
                        <?= $row->position ?>
                    </a>
                    <?php } ?>
                    <!-- <p class="card-description ml-2">*(2) Guru pengajar mata pelajaran</p> -->
                </div>
            </div>
        </div>
    </div>

    <!-- partial:partials/_js.php -->
    <?php $this->load->view('__partials/_js') ?>
    <!-- partial -->

    <!-- Page specific script -->
    <script>
    // JavaScript untuk mengontrol tampilan tombol Enroll
    function toggleEnrollButton() {
        const role = document.getElementById('selectrole').value;
        const submitButton = document.getElementById('submitButton');
        const enrollButton = document.getElementById('enrollButton');

        // Tampilkan tombol "Enroll Fingerprint" hanya jika role bukan "admin"
        if (role === 'admin') {
            enrollButton.style.display = 'none';
            enrollButton.disabled = true;
        } else {
            submitButton.style.display = 'none';
            submitButton.disabled = true;
            enrollButton.style.display = 'inline-block';
            enrollButton.disabled = false;
        }
    }

    // Panggil toggleEnrollButton() saat halaman pertama kali dimuat
    document.addEventListener('DOMContentLoaded', toggleEnrollButton);
    </script>

    <script type="text/javascript">
    $(function() {



        $("#position").hide();

        $("#selectrole").change(function() {
            positionVal = $("#positionId").val();
            role_id = $("#selectrole").val();

            if (role_id == 3) {
                $("#position").show();

                $("#submit").click(function() {
                    positionVal = $("#positionId").val();
                    if (positionVal == "") {
                        swal.fire('', 'User position cannot be null!', 'warning')
                        return false;
                    } else {
                        return true;
                    }
                });
            } else {
                $("#position").hide();
            }
        });

        $("#positionInput").click(function() {
            $("#teacherposition").modal("show");
        });

        $('.pilih').click(function() {
            id = $(this).attr('data-id');
            position = $(this).attr('data-position');

            $("#positionId").val(id);
            $("#positionInput").val(position);
            $("#teacherposition").modal("hide");
        });

        $(document).ready(function() {
            $('#selectrole').change(function() {
                const role = $(this).val();

                if (role === 'dosen' || role === 'mahasiswa') {
                    $('#prodiField').show();
                    $('#passwordAbsenField').show();
                    $('#confirmPasswordAbsenField').show();
                } else {
                    $('#prodiField').hide();
                    $('#passwordAbsenField').hide();
                }

                if (role === 'mahasiswa') {
                    $('#semesterField').show();
                    $('#kelasField').show();
                } else {
                    $('#semesterField').hide();
                    $('#kelasField').hide();
                }
            });
        });
    });
    </script>
</body>

</html>