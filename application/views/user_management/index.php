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
    \<!-- select2 -->
    <link href="<?= base_url(); ?>assets/plugins/select2/css/select2.min.css" rel="stylesheet" />
    <link href="<?= base_url(); ?>assets/plugins/select2/css/select2-bootstrap4.css" rel="stylesheet" />

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
                                <button class="btn btn-info" data-toggle="modal" data-target="#user_management_add">Tambah <?= $judul; ?></button>
                            </div>

                            <?= $this->session->flashdata('message'); ?>
                            <hr />
                            <div class="table-responsive">
                                <table class="table table-bordered" id="table_user_management">
                                    <tr>
                                        <th>Manager</th>
                                        <th>Supervisor</th>
                                        <th>Inspector</th>
                                        <th>Aksi</th>
                                    </tr>
                                    <?php
                                    $partRowspan = array();
                                    $inspectionPartRowspan = array();

                                    // Count rowspan for parts and inspection parts
                                    foreach ($data as $row) {
                                        $id_manager = $row['id_manager'];
                                        $id_supervisor = $row['id_supervisor'];

                                        if (!isset($partRowspan[$id_manager])) {
                                            $partRowspan[$id_manager] = 1;
                                        } else {
                                            $partRowspan[$id_manager]++;
                                        }

                                        if (!isset($inspectionPartRowspan[$id_manager][$id_supervisor])) {
                                            $inspectionPartRowspan[$id_manager][$id_supervisor] = 1;
                                        } else {
                                            $inspectionPartRowspan[$id_manager][$id_supervisor]++;
                                        }
                                    }
                                    $printedParts = array(); // To keep track of printed parts
                                    foreach ($data as $row) :
                                        $id_manager = $row['id_manager'];
                                        $id_supervisor = $row['id_supervisor'];

                                    ?>
                                        <tr>
                                            <?php
                                            if (!in_array($id_manager, $printedParts)) {
                                                echo '<td rowspan="' . $partRowspan[$id_manager] . '">' . $row['nama_manager'] . '</td>';
                                                $printedParts[] = $id_manager;
                                            }

                                            // Output Inspection Part column with rowspan
                                            if ($inspectionPartRowspan[$id_manager][$id_supervisor] !== false) {
                                                echo '<td rowspan="' . $inspectionPartRowspan[$id_manager][$id_supervisor] . '">' . $row['nama_supervisor'] . '</td>';
                                                $inspectionPartRowspan[$id_manager][$id_supervisor] = false;
                                            }
                                            ?>
                                            <td><?= $row['nama_inspector']; ?></td>
                                            <td>
                                                <a href="javascript:;" onclick="deleteMethod('<?= $row['id'] ?>')" class="btn btn-danger">Hapus</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal" tabindex="-1" id="user_management_add">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah <?= $judul; ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="post" action="<?= site_url('user_management/add'); ?>">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="manager">Manager</label>
                                    <select name="manager" id="manager" class="form-control select2">
                                        <option value="" selected disabled>Pilih Manager</option>
                                        <?php foreach ($managers as $row) : ?>
                                            <option value="<?= $row['id_user']; ?>"><?= $row['nama']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="supervisor">Supervisor</label>
                                    <select name="supervisor" id="supervisor" class="form-control select2">
                                        <option value="" selected disabled>Pilih Supervisor</option>
                                        <?php foreach ($supervisors as $row) : ?>
                                            <option value="<?= $row['id_user']; ?>"><?= $row['nama']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="inspector">Inspector</label>
                                    <select name="inspector" id="inspector" class="form-control select2">
                                        <option value="" selected disabled>Pilih Inspector</option>
                                        <?php foreach ($inspectors as $row) : ?>
                                            <option value="<?= $row['id_user']; ?>"><?= $row['nama']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
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

    <script src="<?= base_url(); ?>assets/plugins/select2/js/select2.min.js"></script>
    <script>
        function deleteMethod(id) {
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
                    window.location.href = '<?= base_url('user_management/delete/') ?>' + id + '?_csrf=' + '<?= $this->security->get_csrf_hash() ?>';
                }
            })
        }
        // $('#table_user_management').DataTable();
        $('.select2').select2({
            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
        });
    </script>
</body>

</html>