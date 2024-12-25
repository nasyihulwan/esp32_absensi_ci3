<!-- Custom css for dataTables -->
<style>
div.dataTables_wrapper div.dataTables_filter {
    width: 50%;
    float: right;
}

.icon-fix {
    padding: 20rem;
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
                    <div><?= $this->session->flashdata('message') ?></div>
                    <div class="row">
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <button type="button" class="btn btn-primary float-right mb-3" data-toggle="modal"
                                        data-target="#exampleModalCenter">
                                        Tambah Jadwal
                                    </button>

                                    <div class="table-responsive">
                                        <table id="table-user-list" class="table table-striped table-hover"
                                            style="width: 100%; overflow: scroll;">
                                            <thead style="width: 10%; text-align: center;">
                                                <tr>
                                                    <th>No</th>
                                                    <th>Dosen</th>
                                                    <th>Prodi</th>
                                                    <th>Nama Matakuliah</th>
                                                    <th>Pertemuan</th>
                                                    <th>Kelas</th>
                                                    <th>Tanggal</th>
                                                    <th>Jam Masuk</th>
                                                    <th>Jam Keluar</th>
                                                    <th>Toleransi Keterlambatan</th>
                                                    <th>Absen Keluar Ditutup</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody style="width: 10%; text-align: center;">
                                                <?php $no = 1; foreach ($querySemuaJadwal as $row) { ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><b><?= $row->nama_dosen ?></b></td>

                                                    <td><b><?= $row->prodi ?></b></td>
                                                    <td>
                                                        <?= $row->nama_matakuliah ?>
                                                    </td>
                                                    <td><button
                                                            class=" btn btn-info btn-sm"><?= $row->pertemuan ?></button>

                                                    </td>
                                                    <td><?= $row->semester . $row->kelas ?></td>
                                                    <td><?= $row->tanggal ?></td>
                                                    <td>
                                                        <button
                                                            class=" btn btn-success btn-sm"><?= $row->jam_masuk ?></button>
                                                    </td>
                                                    <td><button
                                                            class="btn btn-danger btn-sm"><?= $row->jam_keluar ?></button>
                                                    </td>
                                                    <td style="width: 10%; text-align: center;">

                                                        <button
                                                            class="btn btn-info btn-sm"><?= $row->buka_absen_masuk ?></button>

                                                    </td>
                                                    <td style="width: 10%; text-align: center;"><button
                                                            class="btn btn-warning btn-sm">

                                                            <?= (new DateTime($row->jam_keluar))->add(new DateInterval('PT15M'))->format('H:i:s'); ?></button>
                                                    </td>
                                                    <td align="center">
                                                        <?php if ($row->status == "Dibuka") { ?>
                                                        <button class="btn btn-warning btn-sm">Berjalan</button>
                                                        <?php } else { ?>
                                                        <button class="btn btn-danger btn-sm">Selesai</button>
                                                        <?php } ?>
                                                    </td>
                                                    <td align="center">

                                                        <a href="<?= site_url('main/kehadiran/' . $row->id) ?>"
                                                            class="btn btn-info btn-sm">Monitor
                                                            Kehadiran</a>
                                                        <!-- <a href="#" class="mr-2">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                height="20" viewBox="0 0 24 24" fill="none"
                                                                stroke="#4B49AC" stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round" class="feather feather-edit">
                                                                <path
                                                                    d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7">
                                                                </path>
                                                                <path
                                                                    d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z">
                                                                </path>
                                                            </svg>
                                                        </a> -->
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
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


    <!-- Modal -->
    <div class=" modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Tambah Jadwal
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <a href="<?= base_url('assets/excel-template/New User Template.xlsx') ?>"
                        class="btn btn-primary btn-block mb-3 col-12" download>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-download">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4">
                            </path>
                            <polyline points="7 10 12 15 17 10">
                            </polyline>
                            <line x1="12" y1="15" x2="12" y2="3">
                            </line>
                        </svg>
                        <h5>Download template excel</h5>
                    </a>

                    <?= form_open_multipart('user/import_excel'); ?>
                    <div class="custom-file mb-3 col-auto">
                        <input type="file" name="filexls" class="custom-file-input" id="formFile">
                        <label class="custom-file-label" for="filexls">Upload File
                            XLS/XLSX</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <a href="<?= site_url('user/') ?>add" class="btn btn-primary">Tambah secara
                        manual</a>
                    <input class="btn btn-primary" type="submit" name="submit" id="formFile" value="Import data">
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- partial:partials/_js.php -->
    <?php $this->load->view('__partials/_js') ?>
    <!-- partial -->

    <!-- Page specific script -->
    <script>
    $(document).ready(function() {
        $('#table-user-list').DataTable({
            "responsive": false,
            "scrollX": true,
            "lengthChange": false,
            "autoWidth": false,
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'copy',
                    text: 'Copy',
                    className: 'btn btn-primary',
                    exportOptions: {
                        columns: ":visible th:not(:nth-child(2), :last-child)",
                    }
                },
                {
                    extend: 'excel',
                    text: 'Export',
                    className: 'btn btn-primary',
                    exportOptions: {
                        columns: ":visible th:not(:nth-child(2), :last-child)",
                    }
                },
                {
                    extend: 'pdf',
                    text: 'PDF',
                    className: 'btn btn-primary',
                    exportOptions: {
                        columns: ":visible th:not(:nth-child(2), :last-child)",
                    }
                },
                {
                    extend: 'print',
                    text: 'Print',
                    className: 'btn btn-primary',
                    exportOptions: {
                        columns: ":visible th:not(:nth-child(2), :last-child)",
                    }
                }
            ],
        }).buttons().container().appendTo(
            '#example1_wrapper .col-md-6:eq(0)'
        );

        $(".deleteUser").click(function() {
            nik = $(this).attr("data-nik");

            Swal.fire({
                icon: "warning",
                text: "Anda yakin? Anda tidak dapat mengembalikan ini!",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        type: 'POST',
                        url: '<?= site_url() ?>user/delete',
                        data: {
                            nik: nik,
                        },
                        cache: false,
                        success: function(respond) {
                            Swal.fire({
                                icon: 'success',
                                text: 'Berhasil dihapus!',
                                confirmButtonText: 'OK',
                            }).then((
                                result) => {
                                if (result
                                    .isConfirmed
                                ) {
                                    location
                                        .reload();
                                }
                            })
                        }
                    });

                }
            })
        })
    });
    </script>
</body>

</html>