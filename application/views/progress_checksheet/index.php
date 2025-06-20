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
    <script src="<?= base_url(); ?>assets/plugins/sweetalert2/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/select2/css/select2-bootstrap4.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/select2/css/select2.min.css">
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
                                        <label for="user_id">User</label> <br>
                                        <select class="form-control" id="user_id" name="user_id" multiple>
                                            <?php foreach ($user as $row) : ?>
                                                <option value="<?= $row['id_user']; ?>"><?= $row['nama']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="tgl_checksheet">Tanggal Checksheet</label> <br>
                                        <select name="tgl_checksheet" id="tgl_checksheet" class="form-control" multiple>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="section">Section</label> <br>
                                        <select class="form-control" id="section" name="section" multiple>
                                            <!-- <option value="">Pilih Section</option> -->
                                            <?php foreach ($section as $row) : ?>
                                                <option value="<?= $row['id']; ?>"><?= $row['section_name'] . '(' . $row['rank'] . ')'; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group mt-1">
                                        <button type="button" class="btn btn-info mt-4" id="btn_tampil">Tampilkan</button>
                                    </div>
                                </div>
                                <!-- <div class="col-md-2">
                                    <button class="btn btn-success mt-4 float-right" id="export_report">Export</button>
                                </div> -->
                            </div>
                            <?= $this->session->flashdata('message'); ?>
                            <hr />
                            <div id="tabel_progress_telegram">

                            </div>
                        </div>
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

    <script src="<?= base_url(); ?>assets/plugins/select2/js/select2.min.js"></script>
    <!-- Latest compiled and minified CSS -->
    <!-- <link rel="stylesheet" href="https://unpkg.com/multiple-select@1.7.0/dist/multiple-select.min.css"> -->

    <!-- Latest compiled and minified JavaScript -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/1.1.2/css/bootstrap-multiselect.min.css" integrity="sha512-fZNmykQ6RlCyzGl9he+ScLrlU0LWeaR6MO/Kq9lelfXOw54O63gizFMSD5fVgZvU1YfDIc6mxom5n60qJ1nCrQ==" crossorigin="anonymous" referrerpolicy="no-referrer" /> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/1.1.2/js/bootstrap-multiselect.min.js" integrity="sha512-lxQ4VnKKW7foGFV6L9zlSe+6QppP9B2t+tMMaV4s4iqAv4iHIyXED7O+fke1VeLNaRdoVkVt8Hw/jmZ+XocsXQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function() {
            $('#user_id').change(function() {
                var user_id = $(this).val();
                $.ajax({
                    url: '<?= site_url('Progress_checksheet/get_tgl_checksheet'); ?>',
                    type: 'post',
                    data: {
                        user_id: user_id
                    },
                    dataType: 'json',
                    success: function(data) {

                        $('#tgl_checksheet').multiselect('destroy');
                        var html = '';
                        for (var i = 0; i < data.length; i++) {
                            html += '<option value="' + data[i].tgl_checksheet + '">' + data[i].tgl_checksheet + '</option>';
                        }
                        $('#tgl_checksheet').html(html);
                        $('#tgl_checksheet').multiselect({
                            

                        });
                        // $('#tgl_checksheet').attr('multiple', 'multiple');

                        // $('#tgl_checksheet').multipleSelect({
                        //     filter: true,
                        //     placeholder: 'Pilih Tanggal'
                        // });
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
                        url: '<?= site_url('progress_checksheet/get_progress_checksheet'); ?>',
                        type: 'post',
                        data: {
                            user_id: user_id,
                            tgl_checksheet: tgl_checksheet,
                            section: $('#section').val()
                        },
                        success: function(data) {
                            $('#tabel_progress_telegram').html(data);
                        }
                    });
                }
            });
            $('#user_id').multiselect();
            $('#tgl_checksheet').multiselect();
            $('#section').multiselect();

            // $('#user_id').multipleSelect({
            //     filter: true,
            //     placeholder: 'Pilih User',
            //     maxHeight: 200,
            //     width: 200,
            // });
            // $('#tgl_checksheet').multipleSelect({
            //     filter: true,
            //     placeholder: 'Pilih Tanggal',
            //     width: 200,
            // });
        });
    </script>
</body>

</html>