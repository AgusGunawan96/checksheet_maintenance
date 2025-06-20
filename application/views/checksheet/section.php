<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title><?= $judul; ?> - <?= $this->config->item('_APP'); ?></title>
    <!--favicon-->
    <link rel="icon" href="<?= base_url(); ?>assets/images/favicon-32x32.png" type="image/png" />
    
    <!-- jQuery FIRST -->
    <script src="<?= base_url(); ?>assets/js/jquery.min.js"></script>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/bootstrap.min.css" />
    
    <!--Data Tables CSS - MUST be loaded before other CSS-->
    <link href="<?= base_url(); ?>assets/plugins/datatable/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url(); ?>assets/plugins/datatable/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css">
    
    <!-- Vector CSS -->
    <link href="<?= base_url(); ?>assets/plugins/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet" />
    
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap4-theme@1.0.0/dist/select2-bootstrap4.min.css" rel="stylesheet" />
    
    <!--plugins-->
    <link href="<?= base_url(); ?>assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <link href="<?= base_url(); ?>assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
    <link href="<?= base_url(); ?>assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
    <!-- loader-->
    <link href="<?= base_url(); ?>assets/css/pace.min.css" rel="stylesheet" />
    
    <!-- Icons CSS -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/icons.css" />
    <!-- App CSS -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/app.css" />
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/dark-sidebar.css" />
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/dark-theme.css" />
    <link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/sweetalert2/sweetalert2.min.css">
    
    <!-- Manifest -->
    <link rel="manifest" href="<?= base_url(); ?>assets/manifest.json">
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
                        <div class="breadcrumb-title pr-3"><?= $judul; ?> Section <?= $section['section_name']; ?></div>
                        <div class="pl-3">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 p-0">
                                    <li class="breadcrumb-item"><a href="javascript:;"><i class='bx bx-home-alt'></i></a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page"><?= $judul; ?> Section <?= $section['section_name'] ?></li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <!--end breadcrumb-->
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title">
                                <div class="row">
                                    <div class="col-md">
                                        <span class="h4">Daftar <?= $judul; ?> Section <?= $section['section_name'] ?></span>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <div class="float-right">
                                            <span class="h4">
                                                <a href="<?= site_url('checksheet/report_abnormal') ?>" class="btn btn-info">Report Abnormal</a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($this->session->userdata('level') == 1) : ?>
                                    <button class="btn btn-info" data-toggle="modal" data-target="#machine_add">Tambah </button>
                                    <button class="btn btn-success" id="btn-show-inspected">Lihat Mesin yang Sudah Diperiksa</button>
                                <?php endif; ?>
                            </div>

                            <?= $this->session->flashdata('message'); ?>
                            <hr />
                            <div class="table-responsive">
                                <table id="machineTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Machine</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; ?>
                                        <?php
                                        // Mengurutkan data berdasarkan nama machine sebelum perulangan
                                        if(isset($data) && is_array($data)) {
                                            usort($data, function($a, $b) {
                                                return strcmp($a['machine_name'], $b['machine_name']);
                                            });
                                        }
                                        ?>
                                        <?php if(isset($data) && is_array($data)) : ?>
                                            <?php foreach ($data as $row) : ?>
                                                <tr>
                                                    <td><?= $no++; ?></td>
                                                    <td><?= $row['machine_name']; ?></td>
                                                    <td>
                                                        <?php if ($this->session->userdata('level') == 1) : ?>
                                                            <a href="javascript:;" onclick="deleteEq('<?= $row['id'] ?>')" class="btn btn-danger btn-sm">Hapus</a>
                                                        <?php endif; ?>
                                                        <a href="<?= site_url('checksheet/show/') . $row['section_id'] . '/' . $row['machine_id']; ?>" class="btn btn-info btn-sm">Lihat</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal untuk menampilkan mesin yang sudah diperiksa -->
            <div class="modal fade" id="modal-inspected-machines" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalLabel">Mesin yang Sudah Diperiksa</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="inspected-machines-list">
                            <p>Loading...</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal untuk tambah equipment inspection dengan search -->
            <div class="modal fade" tabindex="-1" id="machine_add">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah <?= $judul; ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="post" action="<?= site_url('checksheet/add_eq_inspection'); ?>">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="section_id">Section</label>
                                    <select name="section_id" id="section_id" class="form-control" disabled>
                                        <option value="<?= $section['id']; ?>" selected><?= $section['section_name']; ?></option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="rank">Rank</label>
                                    <input type="text" class="form-control" id="rank" name="rank" value="<?= isset($rank) ? $rank : '1'; ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="machine_id">Machine <span class="text-muted">(Ketik untuk mencari)</span></label>
                                    <select name="machine_id" id="machine_id" class="form-control select2-search">
                                        <option value="" disabled selected>Pilih Machine</option>
                                        <?php if(isset($machine) && is_array($machine)): ?>
                                            <?php foreach ($machine as $row) : ?>
                                                <option value="<?= $row['id']; ?>"><?= $row['machine_name']; ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <input type="hidden" name="id" id="id">
                                <input type="hidden" name="section_name" value="<?= $section['section_name']; ?>">
                                <input type="hidden" name="section_id" value="<?= $section['id']; ?>">
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

    <!-- Load Scripts in correct order -->
    <script src="<?= base_url(); ?>assets/js/pace.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/popper.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/bootstrap.min.js"></script>
    <script src="<?= base_url(); ?>assets/plugins/sweetalert2/sweetalert2.min.js"></script>
    
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <!--Data Tables js - CRITICAL: Load after jQuery and Bootstrap-->
    <script src="<?= base_url(); ?>assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
    <script src="<?= base_url(); ?>assets/plugins/datatable/js/dataTables.bootstrap4.min.js"></script>
    
    <!--plugins-->
    <script src="<?= base_url(); ?>assets/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="<?= base_url(); ?>assets/plugins/metismenu/js/metisMenu.min.js"></script>
    <script src="<?= base_url(); ?>assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
    
    <!-- App JS -->
    <script src="<?= base_url(); ?>assets/js/app.js?t=<?= time(); ?>"></script>

    <script>
        $(document).ready(function() {
            console.log('Document ready - initializing DataTable');
            
            // Check if DataTable is available
            if (typeof $.fn.DataTable === 'undefined') {
                console.error('DataTable plugin is not loaded!');
                return;
            }
            
            // Fungsi untuk inisialisasi DataTable
            function initializeDataTable() {
                try {
                    // Hapus DataTable yang sudah ada jika ada
                    if ($.fn.DataTable.isDataTable('#machineTable')) {
                        $('#machineTable').DataTable().clear().destroy();
                        console.log('Destroyed existing DataTable');
                    }
                    
                    // Inisialisasi DataTable baru
                    var table = $('#machineTable').DataTable({
                        "processing": false,
                        "serverSide": false,
                        "paging": true,
                        "lengthChange": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": false,
                        "responsive": true,
                        "destroy": true,
                        "pageLength": 10,
                        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
                        "language": {
                            "decimal": "",
                            "emptyTable": "Tidak ada data tersedia",
                            "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                            "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                            "infoFiltered": "(difilter dari _MAX_ total data)",
                            "infoPostFix": "",
                            "thousands": ",",
                            "lengthMenu": "Tampilkan _MENU_ data",
                            "loadingRecords": "Loading...",
                            "processing": "Processing...",
                            "search": "Cari:",
                            "searchPlaceholder": "Cari machine...",
                            "zeroRecords": "Tidak ada data yang cocok ditemukan",
                            "paginate": {
                                "first": "Pertama",
                                "last": "Terakhir",
                                "next": "Selanjutnya",
                                "previous": "Sebelumnya"
                            },
                            "aria": {
                                "sortAscending": ": aktifkan untuk mengurutkan kolom ascending",
                                "sortDescending": ": aktifkan untuk mengurutkan kolom descending"
                            }
                        },
                        "columnDefs": [
                            { 
                                "targets": [0], // Kolom No
                                "orderable": false,
                                "searchable": false,
                                "width": "5%"
                            },
                            { 
                                "targets": [1], // Kolom Machine
                                "orderable": true,
                                "searchable": true,
                                "width": "70%"
                            },
                            { 
                                "targets": [2], // Kolom Aksi
                                "orderable": false,
                                "searchable": false,
                                "width": "25%"
                            }
                        ],
                        "order": [[1, 'asc']], // Urutkan berdasarkan nama machine
                        "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                               '<"row"<"col-sm-12"tr>>' +
                               '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                        "initComplete": function(settings, json) {
                            console.log('DataTable initialized successfully');
                            // Custom search styling
                            $('.dataTables_filter input').addClass('form-control').attr('placeholder', 'Cari machine...');
                            $('.dataTables_length select').addClass('form-control');
                        },
                        "drawCallback": function(settings) {
                            console.log('DataTable draw completed');
                        }
                    });
                    
                    return table;
                } catch (error) {
                    console.error('Error initializing DataTable:', error);
                    return null;
                }
            }

            // Fungsi untuk inisialisasi Select2
            function initializeSelect2() {
                try {
                    if ($('.select2-search').length > 0) {
                        // Destroy select2 yang sudah ada
                        if ($('.select2-search').hasClass('select2-hidden-accessible')) {
                            $('.select2-search').select2('destroy');
                        }
                        
                        // Inisialisasi select2 baru
                        $('.select2-search').select2({
                            theme: 'bootstrap4',
                            placeholder: 'Ketik untuk mencari machine...',
                            allowClear: true,
                            width: '100%',
                            dropdownParent: $('#machine_add'),
                            language: {
                                noResults: function() {
                                    return "Tidak ada machine yang ditemukan";
                                },
                                searching: function() {
                                    return "Mencari...";
                                }
                            }
                        });
                        
                        console.log('Select2 initialized successfully');
                    }
                } catch (error) {
                    console.error('Error initializing Select2:', error);
                }
            }

            // Inisialisasi komponen
            var dataTable = initializeDataTable();
            initializeSelect2();

            // Handler untuk tombol lihat mesin yang sudah diperiksa
            $('#btn-show-inspected').on('click', function() {
                var sectionId = <?= $section['id']; ?>;
                
                // Tampilkan loading
                var inspectedMachinesList = $('#inspected-machines-list');
                inspectedMachinesList.html('<div class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading...</div>');
                $('#modal-inspected-machines').modal('show');
                
                $.ajax({
                    url: '<?= site_url('checksheet/get_inspected_machines/') ?>' + sectionId,
                    type: 'GET',
                    dataType: 'json',
                    timeout: 10000,
                    success: function(response) {
                        inspectedMachinesList.empty();

                        if (response && response.length > 0) {
                            var listHtml = '<div class="table-responsive">';
                            listHtml += '<table class="table table-bordered table-sm table-striped">';
                            listHtml += '<thead class="thead-light"><tr><th>Nama User</th><th>Nama Mesin</th><th>Tanggal Checksheet</th><th>Section</th></tr></thead><tbody>';
                            
                            $.each(response, function(index, machine) {
                                listHtml += '<tr>';
                                listHtml += '<td>' + (machine.user_name || '-') + '</td>';
                                listHtml += '<td>' + (machine.machine_name || '-') + '</td>';
                                listHtml += '<td>' + (machine.tgl_checksheet || '-') + '</td>';
                                listHtml += '<td>' + (machine.section_name || '-') + '</td>';
                                listHtml += '</tr>';
                            });
                            
                            listHtml += '</tbody></table></div>';
                            inspectedMachinesList.html(listHtml);
                        } else {
                            inspectedMachinesList.html('<div class="alert alert-info"><i class="fa fa-info-circle"></i> Tidak ada mesin yang sudah diperiksa untuk section ini.</div>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Ajax Error:', error);
                        inspectedMachinesList.html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Gagal memuat data. Silakan coba lagi.</div>');
                    }
                });
            });

            // Reset form ketika modal ditutup
            $('#machine_add').on('hidden.bs.modal', function(e) {
                // Reset Select2
                if ($('#machine_id').hasClass('select2-hidden-accessible')) {
                    $('#machine_id').val(null).trigger('change');
                } else {
                    $('#machine_id').val('');
                }
                
                // Reset form fields
                $('#id').val('');
                $('.modal-title').text('Tambah <?= $judul; ?>');
                $('.modal-footer button[type=submit]').text('Submit');
                $('form').attr('action', '<?= site_url('checksheet/add_eq_inspection') ?>');
                
                // Hapus validasi error jika ada
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();
            });

            // Re-inisialisasi Select2 ketika modal dibuka
            $('#machine_add').on('shown.bs.modal', function() {
                initializeSelect2();
            });

            // Validasi form sebelum submit
            $('form').on('submit', function(e) {
                var machineId = $('#machine_id').val();
                
                // Hapus error sebelumnya
                $('#machine_id').removeClass('is-invalid');
                $('.invalid-feedback').remove();
                
                if (!machineId || machineId === '' || machineId === null) {
                    e.preventDefault();
                    
                    // Tambah class error
                    $('#machine_id').addClass('is-invalid');
                    $('#machine_id').parent().append('<div class="invalid-feedback">Silakan pilih machine terlebih dahulu.</div>');
                    
                    // Tampilkan SweetAlert
                    Swal.fire({
                        title: 'Perhatian!',
                        text: 'Silakan pilih machine terlebih dahulu.',
                        icon: 'warning',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#007bff'
                    });
                    
                    return false;
                }
            });
        });

        // Fungsi untuk delete dengan konfirmasi - FIXED VERSION
        function deleteEq(id) {
            var section_name = '<?= $section['section_name']; ?>';
            var rank = '<?= isset($rank) ? $rank : '1'; ?>';
            
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tampilkan loading
                    Swal.fire({
                        title: 'Menghapus...',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        allowEnterKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Redirect ke URL delete
                    window.location.href = '<?= base_url('checksheet/delete_eq_inspection/') ?>' + id + 
                        '?_csrf=' + '<?= $this->security->get_csrf_hash() ?>' + 
                        '&section_name=' + encodeURIComponent(section_name) + 
                        '&rank=' + encodeURIComponent(rank);
                }
            });
        }
    </script>

    <!-- Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('<?= base_url(); ?>service-worker.js').then(function(registration) {
                    console.log('ServiceWorker registration successful with scope: ', registration.scope);
                }, function(err) {
                    console.log('ServiceWorker registration failed: ', err);
                });
            });
        }
    </script>
</body>

</html>