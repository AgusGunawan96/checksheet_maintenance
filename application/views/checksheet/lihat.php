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
                        <div class="breadcrumb-title pr-3">Checksheet Machine <?= $machine['machine_name']; ?></div>
                        <div class="pl-3">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 p-0">
                                    <li class="breadcrumb-item"><a href="javascript:;"><i class='bx bx-home-alt'></i></a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Checksheet Machine <?= $machine['machine_name'] ?></li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <!--end breadcrumb-->
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title">
                                <h4>Daftar Checksheet Machine <?= $machine['machine_name'] ?></h4>
                                <?php
                                if ($this->session->userdata('level') == 2) {
                                ?>
                                    <button class="btn btn-info" data-toggle="modal" data-target="#machine_add">Tambah </button>
                                <?php } ?>
                            </div>

                            <?= $this->session->flashdata('message'); ?>
                            <hr />
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Machine</th>
                                            <th>Equipment No</th>
                                            <th>Cycle</th>
                                            <th>Tanggal Checksheet</th>
                                            <th>Inspector</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; ?>
                                        <?php foreach ($data as $row) : ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td><?= $row['machine_name']; ?></td>
                                                <td><?= $row['equipment_no']; ?></td>
                                                <td><?= $row['cycle']; ?></td>
                                                <td><?= $row['tgl_checksheet']; ?></td>
                                                <td><?= $row['nama_user']; ?></td>
                                                <td>
                                                    <?php
                                                    if ($this->session->userdata('level') == 1) :
                                                    ?>
                                                        <a href="javascript:;" onclick="deleteEq('<?= $row['id'] ?>')" class="btn btn-danger">Hapus</a>
                                                        
                                                    <?php endif; ?>
                                                    <a href="<?= site_url('checksheet/detail/') . $row['id']; ?>" class="btn btn-info">Detail</a>
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
            <div class="modal" tabindex="-1" id="machine_add">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah <?= $judul; ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="post" action="<?= site_url('checksheet/copy_checksheet'); ?>">
                            <!-- <h3 class="text-center">UNDER DEVELOPMENT</h3> -->
                            <div class="modal-body">

                                <div class="form-group">
                                    <label for="tgl_checksheet">Tanggal Checksheet</label> 
                                    <input type="date" class="form-control" id="tgl_checksheet" name="tgl_checksheet">
                                </div>
                                <input type="hidden" name="section_id" id="section_id" value="<?= $this->uri->segment(3); ?>">
                                <input type="hidden" name="machine_id" id="machine_id" value="<?= $this->uri->segment(4); ?>">
                                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <!-- <button type = > -->
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
        function deleteEq(id) {
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
                    window.location.href = '<?= base_url('checksheet/delete_eq/') ?>' + id + '?_csrf=' + '<?= $this->security->get_csrf_hash() ?>' + '&section_id=<?= $this->uri->segment(3); ?>&machine_id=<?= $this->uri->segment(4); ?>';
                }
            })
        }

        function editModal(id) {
            // show modal
            var <?= $this->security->get_csrf_token_name(); ?> = '<?= $this->security->get_csrf_hash(); ?>';
            $.ajax({
                url: '<?= base_url('machine/get_machine/') ?>' + id + '?<?= $this->security->get_csrf_token_name(); ?>=' + <?= $this->security->get_csrf_token_name(); ?>,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#machine_add').modal('show');
                    $('#machine_name').val(data.machine_name);
                    $('#rank').val(data.rank);
                    $('#id').val(data.id);
                    // change title
                    $('.modal-title').text('Edit Machine');
                    // change button
                    $('.modal-footer button[type=submit]').text('Update');
                    // change action
                    $('form').attr('action', '<?= base_url('machine/update') ?>');
                }
            });
        }

        //create everytime modal hide clear form
        // $('#machine_add').on('hidden.bs.modal', function(e) {
        //     $('#machine_name').val('');
        //     $('#rank').val('');
        //     $('#id').val('');
        //     // change title
        //     $('.modal-title').text('Tambah Machine');
        //     // change button
        //     $('.modal-footer button[type=submit]').text('Submit');
        //     // change action
        //     $('form').attr('action', '<?= base_url('machine/add') ?>');
        // });

        $('#section_id').on('change', function() {
            var section_id = $(this).val();
            $.ajax({
                url: '<?= base_url('section/get_section/') ?>' + section_id,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var rank = data.rank;
                    $('#rank').val(rank);
                }
            });
        });

        $('.table').DataTable();
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