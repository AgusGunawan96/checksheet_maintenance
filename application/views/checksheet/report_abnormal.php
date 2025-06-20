<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title><?= $judul; ?> - <?= $this->config->item('_APP'); ?></title>
    <!--favicon-->
    <link rel="icon" href="<?= base_url(); ?>assets/images/favicon-32x32.png" type="image/png" />
    <!-- Vector CSS -->
    <link href="<?= base_url(); ?>assets/plugins/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet" />
    <!--Data Tables -->
    <link href="<?= base_url(); ?>assets/plugins/datatable/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url(); ?>assets/plugins/datatable/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css">
    <!--plugins-->
    <link href="<?= base_url(); ?>assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <link href="<?= base_url(); ?>assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
    <link href="<?= base_url(); ?>assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
    <!-- loader-->
    <link href="<?= base_url(); ?>assets/css/pace.min.css" rel="stylesheet" />
    <script src="<?= base_url(); ?>assets/js/pace.min.js"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/bootstrap.min.css" />
    <!-- Icons CSS -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/icons.css" />
    <!-- App CSS -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/app.css" />
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/dark-sidebar.css" />
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/dark-theme.css" />
    <link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/sweetalert2/sweetalert2.min.css">
     <!-- Manifest -->
 <link rel="manifest" href="<?= base_url(); ?>assets/manifest.json">
    <script src="<?= base_url(); ?>assets/plugins/sweetalert2/sweetalert2.min.js"></script>
</head>

<body>
    <!-- wrapper -->
    <div class="wrapper">
        <!--sidebar-wrapper-->
        <?php $this->load->view('template/sidebar'); ?>
        <!--end sidebar-wrapper-->
        <!--header-->
        <?php $this->load->view('template/header'); ?>
        <!--end header-->
        <!--page-wrapper-->
        <div class="page-wrapper">
            <!--page-content-wrapper-->
            <div class="page-content-wrapper">
                <div class="page-content">
                    <!--breadcrumb-->
                    <div class="page-breadcrumb d-none d-md-flex align-items-center mb-3">
                        <div class="breadcrumb-title pr-3"><?= $judul; ?>;</div>
                        <div class="pl-3">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 p-0">
                                    <li class="breadcrumb-item"><a href="javascript:;"><i class='bx bx-home-alt'></i></a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page"><?= $judul; ?> </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <!--end breadcrumb-->
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title">
                                <h4><?= $judul ?></h4>

                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="user_id">User</label>
                                        <select class="form-control" id="user_id" name="user_id">
                                            <option value="">Pilih User</option>
                                            <?php foreach ($user as $row) : ?>
                                                <option value="<?= $row['id_user']; ?>"><?= $row['nama']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="tgl_checksheet">Tanggal Checksheet</label>
                                        <select name="tgl_checksheet" id="tgl_checksheet" class="form-control">
                                            <option value="" selected disabled>Pilih Tanggal</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group mt-1">
                                        <button type="button" class="btn btn-info mt-4" id="btn_tampil">Tampilkan</button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <button class="btn btn-success mt-4 float-right" id="export_report">Export</button>
                                </div>
                            </div>
                            <?= $this->session->flashdata('message'); ?>
                            <hr />
                            <div id="tabel_report_abnormal">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal" tabindex="-1" id="report_edit">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Update <?= $judul; ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="post" action="">
                            <!-- <h3 class="text-center">UNDER DEVELOPMENT</h3> -->
                            <div class="modal-body">

                                <div class="form-group">
                                    <label for="kejanggalan">Kejanggalan</label>
                                    <input type="text" class="form-control" id="kejanggalan" name="kejanggalan" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="tindakan">Tindakan</label>
                                    <input type="text" class="form-control" id="tindakan" name="tindakan">
                                </div>
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="customRadio1" class="custom-control-input radio_input" name="status" value="&#955;">
                                        <label class="custom-control-label" for="customRadio1">&#955;</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="customRadio2" class="custom-control-input radio_input" name="status" value="X">
                                        <label class="custom-control-label" for="customRadio2">X</label>
                                    </div>
                                </div>
                                <input type="hidden" name="id" id="id" value="">
                                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-info" id="btn_update">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--end page-content-wrapper-->
        </div>
        <!--end page-wrapper-->
        <!--start overlay-->
        <div class="overlay toggle-btn-mobile"></div>
        <!--end overlay-->
        <!--Start Back To Top Button-->
        <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->
        <!--footer -->
        <?php $this->load->view('template/footer'); ?>
        <!-- end footer -->
    </div>
    <!-- end wrapper -->
    <!-- JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="<?= base_url(); ?>assets/js/jquery.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/popper.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/bootstrap.min.js"></script>
    <!--plugins-->
    <script src="<?= base_url(); ?>assets/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="<?= base_url(); ?>assets/plugins/metismenu/js/metisMenu.min.js"></script>
    <script src="<?= base_url(); ?>assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
    <script src="<?= base_url(); ?>assets/plugins/apexcharts-bundle/js/apexcharts.min.js"></script>
    <!-- App JS -->
    <script src="<?= base_url(); ?>assets/js/app.js?t=<?= time(); ?>"></script>
    <!--Data Tables js-->
    <script src="<?= base_url(); ?>assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#user_id').change(function() {
                var user_id = $(this).val();
                $.ajax({
                    url: '<?= site_url('checksheet/get_tgl_checksheet'); ?>',
                    type: 'post',
                    data: {
                        user_id: user_id
                    },
                    dataType: 'json',
                    success: function(data) {
                        var html = '<option value="" selected disabled>Pilih Tanggal</option>';
                        for (var i = 0; i < data.length; i++) {
                            html += '<option value="' + data[i].tgl_checksheet + '">' + data[i].tgl_checksheet + '</option>';
                        }
                        $('#tgl_checksheet').html(html);
                    }
                });
            });

            $('#btn_tampil').click(function() {
                var user_id = $('#user_id').val();
                var tgl_checksheet = $('#tgl_checksheet').val();
                if (user_id == '') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'User belum dipilih!',
                    });
                } else if (tgl_checksheet == null || tgl_checksheet == '') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Tanggal Checksheet belum dipilih!',
                    });
                } else {
                    $.ajax({
                        url: '<?= site_url('checksheet/get_report_abnormal'); ?>',
                        type: 'post',
                        data: {
                            user_id: user_id,
                            tgl_checksheet: tgl_checksheet
                        },
                        success: function(data) {
                            $('#tabel_report_abnormal').html(data);
                        }
                    });
                }
            });
        });
        $('.table').DataTable();

        function reportEdit(id) {
            // show modal
            var <?= $this->security->get_csrf_token_name(); ?> = '<?= $this->security->get_csrf_hash(); ?>';
            $.ajax({
                url: '<?= base_url('checksheet/get_report/') ?>' + id + '?<?= $this->security->get_csrf_token_name(); ?>=' + <?= $this->security->get_csrf_token_name(); ?>,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#report_edit').modal('show');
                    $('#kejanggalan').val(data.kejanggalan);
                    $('#tindakan').val(data.tindakan);
                    $('#id').val(id);
                    if (data.status == 'X') {
                        $('#customRadio2').prop('checked', true);
                    } else {
                        $('#customRadio1').prop('checked', true);
                    }
                }
            });
        }

        $('#btn_update').on('click', function(e) {
            e.preventDefault();
            var id = $('#id').val();
            var tindakan = $('#tindakan').val();
            var status = $('.radio_input:checked').val();
            var <?= $this->security->get_csrf_token_name(); ?> = '<?= $this->security->get_csrf_hash(); ?>';
            $.ajax({
                url: '<?= site_url('checksheet/update_report/') ?>' + id,
                type: 'POST',
                data: {
                    id: id,
                    tindakan: tindakan,
                    status: status,
                    <?= $this->security->get_csrf_token_name(); ?>: <?= $this->security->get_csrf_token_name(); ?>
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status == 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: data.message,
                        });
                        $('#report_edit').modal('hide');
                        $('#btn_tampil').click();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: data.message,
                        });
                    }
                }
            });
        })

        $('#export_report').on('click', function() {
            var user_id = $('#user_id').val();
            var tgl_checksheet = $('#tgl_checksheet').val();
            if (user_id == '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'User belum dipilih!',
                });
            } else if (tgl_checksheet == null || tgl_checksheet == '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Tanggal Checksheet belum dipilih!',
                });
            } else {
                window.location.href = '<?= site_url('checksheet/export_report/') ?>' + tgl_checksheet + '/' + user_id;
            }
        });
    </script>

    <!-- Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('<?= base_url(); ?>assets/service-worker.js').then(function(registration) {
                    console.log('ServiceWorker registration successful with scope: ', registration.scope);
                }, function(err) {
                    console.log('ServiceWorker registration failed: ', err);
                });
            });
        }
    </script>
</body>

</html>