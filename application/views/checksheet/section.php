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
    
    <!-- Custom CSS untuk Status Pemeriksaan -->
    <style>
        /* Status Badge Styling */
        .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-weight: 500;
            display: inline-block;
            min-width: 120px;
            text-align: center;
        }
        
        .badge-danger {
            background-color: #dc3545;
            color: white;
        }
        
        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }
        
        .badge-info {
            background-color: #17a2b8;
            color: white;
        }
        
        .badge-success {
            background-color: #28a745;
            color: white;
        }
        
        /* Animasi berkedip untuk status urgent */
        .blink-animation {
            animation: blink 1s infinite;
        }
        
        @keyframes blink {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0.3; }
        }
        
        /* Notification Card Styling */
        .notification-card {
            border-left: 4px solid #dc3545;
            background-color: #f8f9fa;
            margin-bottom: 1rem;
            padding: 1rem;
            border-radius: 0.25rem;
        }
        
        .notification-card.warning {
            border-left-color: #ffc107;
        }
        
        .notification-card.info {
            border-left-color: #17a2b8;
        }
        
        /* Status Details Styling */
        .status-details {
            font-size: 0.8rem;
            color: #6c757d;
            margin-top: 0.25rem;
        }
        
        /* Alert Notification Styling */
        .alert-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        /* Table responsive improvements */
        .table-responsive {
            overflow-x: auto;
        }
        
        .table th, .table td {
            white-space: nowrap;
            vertical-align: middle;
        }
        
        .table th:nth-child(3), .table td:nth-child(3) {
            min-width: 200px;
        }
        
        /* Summary Cards */
        .summary-card {
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            padding: 1rem;
            margin-bottom: 1rem;
            text-align: center;
        }
        
        .summary-card.urgent {
            border-color: #dc3545;
            background-color: #f8f9fa;
        }
        
        .summary-card.warning {
            border-color: #ffc107;
            background-color: #fffbf0;
        }
        
        .summary-card.info {
            border-color: #17a2b8;
            background-color: #f0f8ff;
        }
        
        .summary-card.success {
            border-color: #28a745;
            background-color: #f8fff8;
        }
        
        .summary-number {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        
        .summary-text {
            font-size: 0.9rem;
            color: #6c757d;
        }
        
        /* Modal improvements */
        .modal-header {
            border-bottom: 1px solid #dee2e6;
        }
        
        .modal-footer {
            border-top: 1px solid #dee2e6;
        }
        
        /* History table in modal */
        .history-table {
            margin-top: 1rem;
        }
        
        .history-table th, .history-table td {
            font-size: 0.85rem;
            padding: 0.5rem;
        }
    </style>
    
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
                    
                    <!-- Summary Cards -->
                    <div class="row mb-4" id="summary-cards">
                        <div class="col-md-3">
                            <div class="summary-card urgent">
                                <div class="summary-number text-danger" id="urgent-count">0</div>
                                <div class="summary-text">Urgent / Terlambat</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="summary-card warning">
                                <div class="summary-number text-warning" id="warning-count">0</div>
                                <div class="summary-text">Segera Jatuh Tempo</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="summary-card info">
                                <div class="summary-number text-info" id="info-count">0</div>
                                <div class="summary-text">Akan Jatuh Tempo</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="summary-card success">
                                <div class="summary-number text-success" id="success-count">0</div>
                                <div class="summary-text">Aman</div>
                            </div>
                        </div>
                    </div>
                    
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
                                                <button class="btn btn-warning btn-sm" id="btn-show-summary">
                                                    <i class="bx bx-info-circle"></i> Lihat Ringkasan Status
                                                </button>
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
                                            <th>Status Pemeriksaan</th>
                                            <th>Pemeriksaan Terakhir</th>
                                            <th>Pemeriksaan Berikutnya</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; ?>
                                        <?php
                                        // Mengurutkan data berdasarkan prioritas status, kemudian nama machine
                                        if(isset($data) && is_array($data)) {
                                            usort($data, function($a, $b) {
                                                $priority = [
                                                    'terlambat' => 1,
                                                    'belum_pernah' => 2,
                                                    'segera_jatuh_tempo' => 3,
                                                    'akan_jatuh_tempo' => 4,
                                                    'aman' => 5
                                                ];
                                                
                                                $aPriority = $priority[$a['inspection_status']['status']] ?? 6;
                                                $bPriority = $priority[$b['inspection_status']['status']] ?? 6;
                                                
                                                if ($aPriority == $bPriority) {
                                                    return strcmp($a['machine_name'], $b['machine_name']);
                                                }
                                                
                                                return $aPriority - $bPriority;
                                            });
                                        }
                                        ?>
                                        <?php if(isset($data) && is_array($data)) : ?>
                                            <?php foreach ($data as $row) : ?>
                                                <tr>
                                                    <td><?= $no++; ?></td>
                                                    <td><?= $row['machine_name']; ?></td>
                                                    <td>
                                                        <span class="status-badge <?= $row['inspection_status']['status_class']; ?>">
                                                            <?= $row['inspection_status']['status_text']; ?>
                                                        </span>
                                                        <?php if ($row['inspection_status']['status'] !== 'belum_pernah') : ?>
                                                            <div class="status-details">
                                                                <small>
                                                                    <?php if ($row['inspection_status']['days_remaining'] < 0) : ?>
                                                                        <i class="bx bx-error-circle text-danger"></i> Lewat <?= abs($row['inspection_status']['days_remaining']); ?> hari
                                                                    <?php elseif ($row['inspection_status']['days_remaining'] <= 30) : ?>
                                                                        <i class="bx bx-time-five text-warning"></i> Sisa <?= $row['inspection_status']['days_remaining']; ?> hari
                                                                    <?php else : ?>
                                                                        <i class="bx bx-check-circle text-success"></i> Sisa <?= $row['inspection_status']['days_remaining']; ?> hari
                                                                    <?php endif; ?>
                                                                </small>
                                                            </div>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($row['inspection_status']['last_inspection']) : ?>
                                                            <span class="badge badge-light">
                                                                <?= $row['inspection_status']['last_inspection']; ?>
                                                            </span>
                                                        <?php else : ?>
                                                            <span class="text-muted">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($row['inspection_status']['next_inspection']) : ?>
                                                            <span class="badge badge-light">
                                                                <?= $row['inspection_status']['next_inspection']; ?>
                                                            </span>
                                                        <?php else : ?>
                                                            <span class="text-muted">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($this->session->userdata('level') == 1) : ?>
                                                            <a href="javascript:;" onclick="deleteEq('<?= $row['id'] ?>')" class="btn btn-danger btn-sm">Hapus</a>
                                                        <?php endif; ?>
                                                        <a href="<?= site_url('checksheet/show/') . $row['section_id'] . '/' . $row['machine_id']; ?>" class="btn btn-info btn-sm">Lihat</a>
                                                        <button class="btn btn-secondary btn-sm" onclick="showMachineDetail(<?= $row['section_id']; ?>, <?= $row['machine_id']; ?>)">
                                                            <i class="bx bx-info-circle"></i> Detail
                                                        </button>
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

            <!-- Modal untuk detail status mesin -->
            <div class="modal fade" id="modal-machine-detail" tabindex="-1" role="dialog" aria-labelledby="machineDetailLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="machineDetailLabel">Detail Status Pemeriksaan</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="machine-detail-content">
                            <p>Loading...</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal untuk ringkasan status -->
            <div class="modal fade" id="modal-summary" tabindex="-1" role="dialog" aria-labelledby="summaryLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="summaryLabel">Ringkasan Status Pemeriksaan</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="summary-content">
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

    // Ganti bagian JavaScript di view section.php dengan kode berikut
// PERBAIKAN: Menambahkan CSRF token dan error handling yang lebih baik

<script>
$(document).ready(function() {
    console.log('Document ready - initializing components');
    
    // CSRF Token Configuration
    var csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
    var csrfHash = '<?= $this->security->get_csrf_hash(); ?>';
    
    // Variables untuk data yang dikirim dari controller
    var urgentMachines = <?= json_encode($urgent_machines ?? []); ?>;
    var warningMachines = <?= json_encode($warning_machines ?? []); ?>;
    var sectionId = <?= $section['id']; ?>;
    
    // Check if DataTable is available
    if (typeof $.fn.DataTable === 'undefined') {
        console.error('DataTable plugin is not loaded!');
        return;
    }
    
    // Inisialisasi komponen
    initializeDataTable();
    initializeSelect2();
    loadInspectionSummary();
    showInitialNotifications();
    
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
                "paging": false,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": false,
                "autoWidth": false,
                "responsive": true,
                "destroy": true,
                "language": {
                    "decimal": "",
                    "emptyTable": "Tidak ada data tersedia",
                    "loadingRecords": "Loading...",
                    "processing": "Processing...",
                    "search": "Cari:",
                    "searchPlaceholder": "Cari machine...",
                    "zeroRecords": "Tidak ada data yang cocok ditemukan",
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
                        "width": "20%"
                    },
                    { 
                        "targets": [2], // Kolom Status
                        "orderable": true,
                        "searchable": true,
                        "width": "25%"
                    },
                    { 
                        "targets": [3], // Kolom Pemeriksaan Terakhir
                        "orderable": true,
                        "searchable": false,
                        "width": "15%"
                    },
                    { 
                        "targets": [4], // Kolom Pemeriksaan Berikutnya
                        "orderable": true,
                        "searchable": false,
                        "width": "15%"
                    },
                    { 
                        "targets": [5], // Kolom Aksi
                        "orderable": false,
                        "searchable": false,
                        "width": "20%"
                    }
                ],
                "order": [[2, 'asc'], [1, 'asc']], // Urutkan berdasarkan status, kemudian nama machine
                "dom": '<"row"<"col-sm-12 col-md-6"><"col-sm-12 col-md-6"f>>' +
                       '<"row"<"col-sm-12"tr>>',
                "initComplete": function(settings, json) {
                    console.log('DataTable initialized successfully');
                    $('.dataTables_filter input').addClass('form-control').attr('placeholder', 'Cari machine...');
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
                if ($('.select2-search').hasClass('select2-hidden-accessible')) {
                    $('.select2-search').select2('destroy');
                }
                
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
    
    // Fungsi untuk load summary - DIPERBAIKI dengan CSRF token
    function loadInspectionSummary() {
        var requestData = {
            section_id: sectionId
        };
        
        // Tambahkan CSRF token
        requestData[csrfName] = csrfHash;
        
        $.ajax({
            url: '<?= site_url('checksheet/get_inspection_summary') ?>',
            type: 'POST',
            data: requestData,
            dataType: 'json',
            timeout: 10000,
            success: function(response) {
                if (response && typeof response === 'object') {
                    // Update summary cards
                    if (response.urgent !== undefined) {
                        $('#urgent-count').text(response.urgent + (response.never_inspected || 0));
                    }
                    if (response.warning !== undefined) {
                        $('#warning-count').text(response.warning);
                    }
                    if (response.info !== undefined) {
                        $('#info-count').text(response.info);
                    }
                    if (response.success !== undefined) {
                        $('#success-count').text(response.success);
                    }
                    
                    console.log('Summary loaded successfully');
                } else {
                    console.warn('Invalid response format for summary');
                    setDefaultSummaryValues();
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading summary:', error);
                console.error('Status:', status);
                console.error('Response:', xhr.responseText);
                
                // Set default values jika gagal
                setDefaultSummaryValues();
                
                // Tampilkan error hanya jika dalam development mode
                if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
                    showToast('Gagal memuat summary: ' + error, 'warning');
                }
            }
        });
    }
    
    // Fungsi untuk set default summary values
    function setDefaultSummaryValues() {
        $('#urgent-count').text('-');
        $('#warning-count').text('-');
        $('#info-count').text('-');
        $('#success-count').text('-');
    }
    
    // Fungsi untuk menampilkan notifikasi awal
    function showInitialNotifications() {
        var notifications = [];
        
        // Tambahkan urgent machines
        if (urgentMachines && urgentMachines.length > 0) {
            urgentMachines.forEach(function(machine) {
                notifications.push({
                    type: 'urgent',
                    title: 'Pemeriksaan Urgent!',
                    message: 'Mesin ' + machine.machine_name + ' ' + machine.inspection_status.status_text.toLowerCase(),
                    icon: 'bx-error-circle'
                });
            });
        }
        
        // Tambahkan warning machines
        if (warningMachines && warningMachines.length > 0) {
            warningMachines.forEach(function(machine) {
                notifications.push({
                    type: 'warning',
                    title: 'Perhatian!',
                    message: 'Mesin ' + machine.machine_name + ' ' + machine.inspection_status.status_text.toLowerCase(),
                    icon: 'bx-time-five'
                });
            });
        }
        
        // Tampilkan notifikasi
        if (notifications.length > 0) {
            setTimeout(function() {
                showNotificationSummary(notifications);
            }, 1000);
        }
    }
    
    // Fungsi untuk menampilkan summary notifikasi
    function showNotificationSummary(notifications) {
        var urgentCount = notifications.filter(n => n.type === 'urgent').length;
        var warningCount = notifications.filter(n => n.type === 'warning').length;
        
        if (urgentCount > 0 || warningCount > 0) {
            var message = '';
            if (urgentCount > 0) {
                message += urgentCount + ' mesin memerlukan pemeriksaan segera. ';
            }
            if (warningCount > 0) {
                message += warningCount + ' mesin akan jatuh tempo dalam 1 bulan. ';
            }
            
            Swal.fire({
                title: 'Notifikasi Pemeriksaan',
                html: message + '<br><br>Klik "Lihat Detail" untuk informasi lengkap.',
                icon: urgentCount > 0 ? 'error' : 'warning',
                showCancelButton: true,
                confirmButtonText: 'Lihat Detail',
                cancelButtonText: 'Tutup',
                confirmButtonColor: urgentCount > 0 ? '#dc3545' : '#ffc107',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#btn-show-summary').click();
                }
            });
        }
    }
    
    // Handler untuk tombol lihat mesin yang sudah diperiksa
    $('#btn-show-inspected').on('click', function() {
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
    
    // Handler untuk tombol show summary - DIPERBAIKI dengan CSRF token
    $('#btn-show-summary').on('click', function() {
        var summaryContent = $('#summary-content');
        summaryContent.html('<div class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading...</div>');
        $('#modal-summary').modal('show');
        
        var requestData = {
            section_id: sectionId
        };
        
        // Tambahkan CSRF token
        requestData[csrfName] = csrfHash;
        
        $.ajax({
            url: '<?= site_url('checksheet/get_inspection_summary') ?>',
            type: 'POST',
            data: requestData,
            dataType: 'json',
            timeout: 15000,
            success: function(response) {
                if (response && typeof response === 'object' && !response.status) {
                    var html = '<div class="row">';
                    html += '<div class="col-md-6">';
                    html += '<div class="card border-danger">';
                    html += '<div class="card-header bg-danger text-white">';
                    html += '<h6 class="mb-0"><i class="bx bx-error-circle"></i> Urgent / Terlambat (' + ((response.urgent || 0) + (response.never_inspected || 0)) + ')</h6>';
                    html += '</div>';
                    html += '<div class="card-body">';
                    
                    if (response.urgent_list && response.urgent_list.length > 0) {
                        response.urgent_list.forEach(function(machine) {
                            html += '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
                            html += '<strong>' + machine.machine_name + '</strong><br>';
                            html += '<small>' + machine.status_text + '</small>';
                            html += '</div>';
                        });
                    } else {
                        html += '<p class="text-muted">Tidak ada mesin yang urgent</p>';
                    }
                    
                    html += '</div></div></div>';
                    html += '<div class="col-md-6">';
                    html += '<div class="card border-warning">';
                    html += '<div class="card-header bg-warning text-dark">';
                    html += '<h6 class="mb-0"><i class="bx bx-time-five"></i> Segera Jatuh Tempo (' + (response.warning || 0) + ')</h6>';
                    html += '</div>';
                    html += '<div class="card-body">';
                    
                    if (response.warning_list && response.warning_list.length > 0) {
                        response.warning_list.forEach(function(machine) {
                            html += '<div class="alert alert-warning alert-dismissible fade show" role="alert">';
                            html += '<strong>' + machine.machine_name + '</strong><br>';
                            html += '<small>' + machine.status_text + '</small>';
                            html += '</div>';
                        });
                    } else {
                        html += '<p class="text-muted">Tidak ada mesin yang segera jatuh tempo</p>';
                    }
                    
                    html += '</div></div></div>';
                    html += '</div>';
                    
                    html += '<div class="row mt-3">';
                    html += '<div class="col-md-12">';
                    html += '<div class="alert alert-info">';
                    html += '<h6><i class="bx bx-info-circle"></i> Informasi</h6>';
                    html += '<ul>';
                    html += '<li>Total Mesin: ' + (response.total_machines || 0) + '</li>';
                    html += '<li>Akan Jatuh Tempo (3 bulan): ' + (response.info || 0) + '</li>';
                    html += '<li>Aman: ' + (response.success || 0) + '</li>';
                    html += '</ul>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';
                    
                    summaryContent.html(html);
                } else {
                    // Handle error response
                    var errorMsg = 'Gagal memuat data summary';
                    if (response && response.message) {
                        errorMsg += ': ' + response.message;
                    }
                    summaryContent.html('<div class="alert alert-danger">' + errorMsg + '</div>');
                }
            },
            error: function(xhr, status, error) {
                console.error('Summary Error:', error);
                console.error('Status:', status);
                console.error('Response:', xhr.responseText);
                
                var errorMsg = 'Gagal memuat data summary';
                if (xhr.status === 403) {
                    errorMsg += ' (Akses ditolak - periksa CSRF token)';
                } else if (xhr.status === 404) {
                    errorMsg += ' (Endpoint tidak ditemukan)';
                } else if (xhr.status === 500) {
                    errorMsg += ' (Server error)';
                }
                
                summaryContent.html('<div class="alert alert-danger">' + errorMsg + '</div>');
            }
        });
    });
    
    // Reset form ketika modal ditutup
    $('#machine_add').on('hidden.bs.modal', function(e) {
        if ($('#machine_id').hasClass('select2-hidden-accessible')) {
            $('#machine_id').val(null).trigger('change');
        } else {
            $('#machine_id').val('');
        }
        
        $('#id').val('');
        $('.modal-title').text('Tambah <?= $judul; ?>');
        $('.modal-footer button[type=submit]').text('Submit');
        $('form').attr('action', '<?= site_url('checksheet/add_eq_inspection') ?>');
        
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
        
        $('#machine_id').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        if (!machineId || machineId === '' || machineId === null) {
            e.preventDefault();
            
            $('#machine_id').addClass('is-invalid');
            $('#machine_id').parent().append('<div class="invalid-feedback">Silakan pilih machine terlebih dahulu.</div>');
            
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

// Fungsi untuk menampilkan detail mesin - DIPERBAIKI dengan CSRF token
function showMachineDetail(sectionId, machineId) {
    var detailContent = $('#machine-detail-content');
    detailContent.html('<div class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading...</div>');
    $('#modal-machine-detail').modal('show');
    
    var csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
    var csrfHash = '<?= $this->security->get_csrf_hash(); ?>';
    
    var requestData = {
        section_id: sectionId,
        machine_id: machineId
    };
    
    // Tambahkan CSRF token
    requestData[csrfName] = csrfHash;
    
    $.ajax({
        url: '<?= site_url('checksheet/get_machine_inspection_detail') ?>',
        type: 'POST',
        data: requestData,
        dataType: 'json',
        timeout: 10000,
        success: function(response) {
            if (response.status === 'success') {
                var status = response.inspection_status;
                var html = '<div class="row">';
                html += '<div class="col-md-12">';
                html += '<h5>' + response.machine_name + '</h5>';
                html += '<hr>';
                html += '<div class="row">';
                html += '<div class="col-md-6">';
                html += '<h6>Status Pemeriksaan</h6>';
                html += '<span class="status-badge ' + status.status_class + '">' + status.status_text + '</span>';
                html += '</div>';
                html += '<div class="col-md-6">';
                html += '<h6>Informasi Jadwal</h6>';
                if (status.last_inspection) {
                    html += '<p><strong>Pemeriksaan Terakhir:</strong> ' + status.last_inspection + '</p>';
                    html += '<p><strong>Pemeriksaan Berikutnya:</strong> ' + status.next_inspection + '</p>';
                    
                    if (status.days_remaining !== null) {
                        if (status.days_remaining < 0) {
                            html += '<p><strong>Status:</strong> <span class="text-danger">Terlambat ' + Math.abs(status.days_remaining) + ' hari</span></p>';
                        } else {
                            html += '<p><strong>Status:</strong> <span class="text-info">Sisa ' + status.days_remaining + ' hari</span></p>';
                        }
                    }
                } else {
                    html += '<p class="text-muted">Belum pernah diperiksa</p>';
                }
                html += '</div>';
                html += '</div>';
                
                if (response.history && response.history.length > 0) {
                    html += '<h6 class="mt-4">Riwayat Pemeriksaan</h6>';
                    html += '<div class="table-responsive">';
                    html += '<table class="table table-sm table-bordered history-table">';
                    html += '<thead><tr><th>Tanggal</th><th>Inspector</th><th>Status</th></tr></thead>';
                    html += '<tbody>';
                    
                    response.history.forEach(function(item) {
                        html += '<tr>';
                        html += '<td>' + item.tgl_checksheet + '</td>';
                        html += '<td>' + (item.inspector_name || '-') + '</td>';
                        html += '<td>';
                        if (item.step_proses >= 4) {
                            html += '<span class="badge badge-success">Selesai</span>';
                        } else if (item.step_proses == 3) {
                            html += '<span class="badge badge-warning">Review</span>';
                        } else {
                            html += '<span class="badge badge-info">Proses</span>';
                        }
                        html += '</td>';
                        html += '</tr>';
                    });
                    
                    html += '</tbody></table>';
                    html += '</div>';
                }
                
                html += '</div>';
                html += '</div>';
                
                detailContent.html(html);
            } else {
                var errorMsg = 'Gagal memuat detail mesin';
                if (response.message) {
                    errorMsg += ': ' + response.message;
                }
                detailContent.html('<div class="alert alert-danger">' + errorMsg + '</div>');
            }
        },
        error: function(xhr, status, error) {
            console.error('Detail Error:', error);
            var errorMsg = 'Gagal memuat detail mesin';
            if (xhr.status === 403) {
                errorMsg += ' (Akses ditolak)';
            }
            detailContent.html('<div class="alert alert-danger">' + errorMsg + '</div>');
        }
    });
}

// Fungsi untuk delete dengan konfirmasi
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
            
            window.location.href = '<?= base_url('checksheet/delete_eq_inspection/') ?>' + id + 
                '?_csrf=' + '<?= $this->security->get_csrf_hash() ?>' + 
                '&section_name=' + encodeURIComponent(section_name) + 
                '&rank=' + encodeURIComponent(rank);
        }
    });
}

// Auto refresh summary setiap 5 menit
setInterval(function() {
    if (typeof loadInspectionSummary === 'function') {
        loadInspectionSummary();
    }
}, 300000); // 5 menit

// Fungsi untuk menampilkan toast notification
function showToast(message, type = 'info') {
    var toastClass = 'alert-' + type;
    var iconClass = type === 'success' ? 'bx-check-circle' : 
                   type === 'warning' ? 'bx-time-five' : 
                   type === 'danger' ? 'bx-error-circle' : 'bx-info-circle';
    
    var toast = $('<div class="alert ' + toastClass + ' alert-notification" role="alert">' +
                 '<i class="bx ' + iconClass + '"></i> ' + message +
                 '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                 '<span aria-hidden="true">&times;</span>' +
                 '</button>' +
                 '</div>');
    
    $('body').append(toast);
    
    // Auto dismiss after 5 seconds
    setTimeout(function() {
        toast.fadeOut(function() {
            $(this).remove();
        });
    }, 5000);
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
