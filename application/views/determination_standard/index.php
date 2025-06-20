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
                        <div class="breadcrumb-title pr-3"><?= $judul; ?></div>
                        <div class="pl-3">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 p-0">
                                    <li class="breadcrumb-item"><a href="javascript:;"><i class='bx bx-home-alt'></i></a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page"><?= $judul; ?></li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <!--end breadcrumb-->
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title">
                                <h4>Daftar <?= $judul; ?></h4>
                                <button class="btn btn-info" data-toggle="modal" data-target="#determination_standard_add">Tambah <?= $judul; ?></button>
                            </div>

                            <?= $this->session->flashdata('message'); ?>
                            <hr />
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Determinatin Standard Name</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; ?>
                                        <?php foreach ($data as $row) : ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td><?= $row['determination_name']; ?></td>
                                                <td>
                                                    <a href="javascript:;" onclick="editModal('<?= $row['id'] ?>')" class="btn btn-warning">Edit</a>
                                                    <a href="javascript:;" onclick="deleteInspectionPart('<?= $row['id'] ?>')" class="btn btn-danger">Hapus</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal" tabindex="-1" id="determination_standard_add">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah <?= $judul; ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="post" action="<?= site_url('determination_standard/add'); ?>">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="determination_name">Determinatin Standard Name</label>
                                    <input type="text" class="form-control" id="determination_name" name="determination_name" value="<?= set_value('determination_standard_name'); ?>" required>
                                </div>

                                <input type="hidden" name="id" id="id">
                                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-info">Submit</button>
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
        function deleteInspectionPart(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= base_url('determination_standard/delete/') ?>' + id + '?_csrf=' + '<?= $this->security->get_csrf_hash() ?>';
                }
            })
        }

        function editModal(id) {
            // show modal
            var <?= $this->security->get_csrf_token_name(); ?> = '<?= $this->security->get_csrf_hash(); ?>';
            $.ajax({
                url: '<?= base_url('determination_standard/get_determination_standard/') ?>' + id + '?<?= $this->security->get_csrf_token_name(); ?>=' + <?= $this->security->get_csrf_token_name(); ?>,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#determination_standard_add').modal('show');
                    $('#determination_standard_name').val(data.determination_standard_name);
                    $('#rank').val(data.rank);
                    $('#id').val(data.id);
                    // change title
                    $('.modal-title').text('Edit Determinatin Standard');
                    // change button
                    $('.modal-footer button[type=submit]').text('Update');
                    // change action
                    $('form').attr('action', '<?= base_url('determination_standard/update') ?>');
                }
            });
        }

        //create everytime modal hide clear form
        $('#determination_standard_add').on('hidden.bs.modal', function(e) {
            $('#determination_standard_name').val('');
            $('#rank').val('');
            $('#id').val('');
            // change title
            $('.modal-title').text('Tambah Determinatin Standard');
            // change button
            $('.modal-footer button[type=submit]').text('Submit');
            // change action
            $('form').attr('action', '<?= base_url('determination_standard/add') ?>');
        });


        $('.table').DataTable();
    </script>
</body>

</html>