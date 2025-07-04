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

    <!-- Custom CSS untuk enhanced modal -->
    <style>
        .existing-dates-container {
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #e3e6f0;
            border-radius: 0.35rem;
            padding: 15px;
            background-color: #f8f9fc;
        }

        .date-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 12px;
            margin-bottom: 8px;
            background-color: white;
            border: 1px solid #e3e6f0;
            border-radius: 0.25rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .date-item:last-child {
            margin-bottom: 0;
        }

        .date-info {
            flex: 1;
        }

        .date-text {
            font-weight: 600;
            color: #5a5c69;
            margin-bottom: 2px;
        }

        .inspector-text {
            font-size: 0.85rem;
            color: #858796;
        }

        .date-badge {
            background-color: #e74a3b;
            color: white;
            padding: 4px 8px;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .no-dates-message {
            text-align: center;
            color: #858796;
            font-style: italic;
            padding: 20px;
        }

        .duplicate-warning {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 0.25rem;
            padding: 10px;
            margin-top: 10px;
            display: none;
        }

        .loading-dates {
            text-align: center;
            padding: 20px;
            color: #858796;
        }

        .modal-dialog-enhanced {
            max-width: 650px;
        }

        .form-control.is-invalid {
            border-color: #e74a3b;
        }

        .invalid-feedback {
            display: block;
            color: #e74a3b;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
    </style>
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
                                    <button class="btn btn-info" data-toggle="modal" data-target="#machine_add">Tambah Pemeriksaan</button>
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
                                                        <a href="javascript:;" onclick="deleteEq('<?= $row['id'] ?>')" class="btn btn-danger btn-sm">Hapus</a>
                                                        
                                                    <?php endif; ?>
                                                    <a href="<?= site_url('checksheet/detail/') . $row['id']; ?>" class="btn btn-info btn-sm">Detail</a>
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

            <!-- Enhanced Modal Form Copy Checksheet dengan validasi tanggal -->
            <div class="modal" tabindex="-1" id="machine_add">
                <div class="modal-dialog modal-dialog-enhanced">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="bx bx-calendar-plus"></i>
                                Tambah Pemeriksaan Checksheet
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="post" action="<?= site_url('checksheet/copy_checksheet'); ?>" id="copyChecksheetForm">
                            <div class="modal-body">
                                <!-- Info Machine -->
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="alert alert-info">
                                            <i class="bx bx-info-circle"></i>
                                            <strong>Machine:</strong> <?= $machine['machine_name'] ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Input Tanggal -->
                                <div class="form-group">
                                    <label for="tgl_checksheet">
                                        <i class="bx bx-calendar"></i>
                                        Tanggal Checksheet <span class="text-danger">*</span>
                                    </label> 
                                    <input type="date" class="form-control" id="tgl_checksheet" name="tgl_checksheet" required>
                                    <div class="invalid-feedback" id="date-error"></div>
                                    
                                    <!-- Warning untuk duplikat -->
                                    <div class="duplicate-warning" id="duplicate-warning">
                                        <i class="bx bx-error-circle"></i>
                                        <strong>Peringatan!</strong> Tanggal ini sudah pernah diperiksa.
                                        <div id="duplicate-details"></div>
                                    </div>
                                </div>

                                <!-- Existing Dates Section -->
                                <div class="form-group">
                                    <label>
                                        <i class="bx bx-history"></i>
                                        Riwayat Pemeriksaan
                                        <small class="text-muted">(Tanggal yang sudah diperiksa)</small>
                                    </label>
                                    <div class="existing-dates-container" id="existing-dates-container">
                                        <div class="loading-dates">
                                            <i class="bx bx-loader-alt bx-spin"></i> 
                                            Memuat data...
                                        </div>
                                    </div>
                                </div>

                                <!-- Hidden inputs -->
                                <input type="hidden" name="section_id" id="section_id" value="<?= $this->uri->segment(3); ?>">
                                <input type="hidden" name="machine_id" id="machine_id" value="<?= $this->uri->segment(4); ?>">
                                <input type="hidden" name="marked_rows" id="marked_rows" value="">
                                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                            </div>
                            <div class="modal-footer">
                                <!-- <button type="button" class="btn btn-warning mr-auto" onclick="clearMarkedRowsForNewChecksheet()" title="Hapus semua marking sebelum membuat pemeriksaan baru">
                                    <i class="bx bx-trash"></i> Clear Marks
                                </button> -->
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                    <i class="bx bx-x"></i> Batal
                                </button>
                                <button type="submit" class="btn btn-info" id="submit-btn" disabled>
                                    <i class="bx bx-check"></i> Buat Pemeriksaan
                                </button>
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
    <script src="<?= base_url(); ?>assets/js/jquery.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/popper.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/bootstrap.min.js"></script>
    <!--plugins-->
    <script src="<?= base_url(); ?>assets/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="<?= base_url(); ?>assets/plugins/metismenu/js/metisMenu.min.js"></script>
    <script src="<?= base_url(); ?>assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
    <!-- App JS -->
    <script src="<?= base_url(); ?>assets/js/app.js?t=<?= time(); ?>"></script>
    <!--Data Tables js-->
    <script src="<?= base_url(); ?>assets/plugins/datatable/js/jquery.dataTables.min.js"></script>

    <!-- Enhanced JavaScript dengan validasi tanggal -->
    <script>
    $(document).ready(function() {
        // Variabel global
        let existingDates = [];
        let isDateValid = false;

        // Load existing dates ketika modal dibuka
        $('#machine_add').on('shown.bs.modal', function() {
            loadExistingDates();
            
            // Set tanggal minimum ke hari ini
            const today = new Date().toISOString().split('T')[0];
            $('#tgl_checksheet').attr('min', today);
        });

        // Reset form ketika modal ditutup
        $('#machine_add').on('hidden.bs.modal', function() {
            resetForm();
        });

        // Validasi tanggal saat user mengubah input
        $('#tgl_checksheet').on('change', function() {
            const selectedDate = $(this).val();
            if (selectedDate) {
                validateSelectedDate(selectedDate);
            } else {
                resetValidation();
            }
        });

        // Submit form dengan validasi
        $('#copyChecksheetForm').on('submit', function(e) {
            e.preventDefault();
            
            const selectedDate = $('#tgl_checksheet').val();
            
            if (!selectedDate) {
                showError('Silakan pilih tanggal checksheet terlebih dahulu.');
                return;
            }

            if (!isDateValid) {
                showError('Tanggal yang dipilih tidak valid atau sudah ada.');
                return;
            }

            // Proses submit
            processFormSubmit();
        });

        // Function untuk load existing dates
        function loadExistingDates() {
            const sectionId = $('#section_id').val();
            const machineId = $('#machine_id').val();

            $.ajax({
                url: '<?= site_url('checksheet/get_existing_checksheet_dates'); ?>',
                type: 'POST',
                data: {
                    section_id: sectionId,
                    machine_id: machineId,
                    '<?= $this->security->get_csrf_token_name(); ?>': '<?= $this->security->get_csrf_hash(); ?>'
                },
                dataType: 'json',
                success: function(response) {
                    existingDates = response.data || [];
                    displayExistingDates(response);
                },
                error: function() {
                    $('#existing-dates-container').html(
                        '<div class="no-dates-message">' +
                        '<i class="bx bx-error-circle"></i> Gagal memuat data riwayat' +
                        '</div>'
                    );
                }
            });
        }

        // Function untuk menampilkan existing dates
        function displayExistingDates(response) {
            const container = $('#existing-dates-container');
            
            if (response.data && response.data.length > 0) {
                let html = '<div class="mb-2"><strong>Total: ' + response.total + ' pemeriksaan</strong></div>';
                
                response.data.forEach(function(item) {
                    html += '<div class="date-item">' +
                                '<div class="date-info">' +
                                    '<div class="date-text">' + item.formatted_date + '</div>' +
                                    '<div class="inspector-text">Inspector: ' + item.inspector_name + '</div>' +
                                '</div>' +
                                '<div class="date-badge">Sudah Ada</div>' +
                            '</div>';
                });
                
                container.html(html);
            } else {
                container.html(
                    '<div class="no-dates-message">' +
                    '<i class="bx bx-calendar-check"></i><br>' +
                    'Belum ada pemeriksaan untuk mesin ini.<br>' +
                    '<small>Anda dapat membuat pemeriksaan pertama.</small>' +
                    '</div>'
                );
            }
        }

        // Function untuk validasi tanggal yang dipilih
        function validateSelectedDate(selectedDate) {
            // Reset state
            resetValidation();
            
            // Cek apakah tanggal sudah ada
            const isDuplicate = existingDates.some(item => item.tgl_checksheet === selectedDate);
            
            if (isDuplicate) {
                const existingItem = existingDates.find(item => item.tgl_checksheet === selectedDate);
                showDuplicateWarning(existingItem);
                isDateValid = false;
            } else {
                // Validasi tanggal tidak boleh masa lalu
                const today = new Date().toISOString().split('T')[0];
                if (selectedDate < today) {
                    showError('Tanggal tidak boleh lebih lama dari hari ini.');
                    isDateValid = false;
                } else {
                    showSuccess('Tanggal tersedia untuk pemeriksaan.');
                    isDateValid = true;
                }
            }
            
            // Enable/disable submit button
            $('#submit-btn').prop('disabled', !isDateValid);
        }

        // Function untuk menampilkan peringatan duplikat
        function showDuplicateWarning(existingItem) {
            const warningDiv = $('#duplicate-warning');
            const detailsDiv = $('#duplicate-details');
            
            detailsDiv.html(
                '<small>Tanggal: <strong>' + existingItem.formatted_date + '</strong><br>' +
                'Inspector: <strong>' + existingItem.inspector_name + '</strong></small>'
            );
            
            warningDiv.show();
            $('#tgl_checksheet').addClass('is-invalid');
        }

        // Function untuk menampilkan error
        function showError(message) {
            $('#date-error').text(message);
            $('#tgl_checksheet').addClass('is-invalid');
            $('#duplicate-warning').hide();
        }

        // Function untuk menampilkan success
        function showSuccess(message) {
            $('#date-error').text(message).removeClass('invalid-feedback').addClass('text-success');
            $('#tgl_checksheet').removeClass('is-invalid').addClass('is-valid');
            $('#duplicate-warning').hide();
        }

        // Function untuk reset validasi
        function resetValidation() {
            $('#tgl_checksheet').removeClass('is-invalid is-valid');
            $('#date-error').text('').removeClass('text-success').addClass('invalid-feedback');
            $('#duplicate-warning').hide();
            isDateValid = false;
            $('#submit-btn').prop('disabled', true);
        }

        // Function untuk reset form
        function resetForm() {
            $('#tgl_checksheet').val('');
            resetValidation();
            existingDates = [];
        }

        // Function untuk proses submit form
        function processFormSubmit() {
            // Ambil data marked rows
            const markedRows = JSON.parse(localStorage.getItem('markedRows') || '[]');
            $('#marked_rows').val(JSON.stringify(markedRows));
            
            if (markedRows.length > 0) {
                Swal.fire({
                    title: 'Konfirmasi Pembuatan Checksheet',
                    html: 'Terdapat <strong>' + markedRows.length + '</strong> item yang sudah di-mark.<br>' +
                          'Item tersebut akan tetap ter-mark di pemeriksaan baru.<br><br>' +
                          '<strong>Lanjutkan membuat pemeriksaan?</strong>',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#17a2b8',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Buat Pemeriksaan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitFormWithLoading();
                    }
                });
            } else {
                submitFormWithLoading();
            }
        }

        // Function untuk submit dengan loading
        function submitFormWithLoading() {
            Swal.fire({
                title: 'Membuat Pemeriksaan Baru...',
                html: 'Sedang menyalin data checksheet dan marking...<br>' +
                      '<div class="progress mt-2">' +
                      '<div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 100%"></div>' +
                      '</div>',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Submit form
            $('#copyChecksheetForm')[0].submit();
        }

        // Cek new marked rows dari session flashdata
        <?php if ($this->session->flashdata('new_marked_rows')): ?>
        var newMarkedRows = <?= $this->session->flashdata('new_marked_rows'); ?>;
        if (newMarkedRows && newMarkedRows.length > 0) {
            localStorage.setItem('markedRows', JSON.stringify(newMarkedRows));
            
            Swal.fire({
                title: 'Pemeriksaan Berhasil Dibuat!',
                html: 'Pemeriksaan baru berhasil dibuat dengan <strong>' + newMarkedRows.length + '</strong> item yang tetap ter-mark.',
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#28a745'
            });
        }
        <?php endif; ?>

        // Initialize DataTable
        $('.table').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            },
            "order": [[ 4, "desc" ]] // Sort by tanggal checksheet descending
        });
    });

    // Function untuk clear marked rows
    function clearMarkedRowsForNewChecksheet() {
        const markedRows = JSON.parse(localStorage.getItem('markedRows') || '[]');
        
        if (markedRows.length === 0) {
            Swal.fire('Info', 'Tidak ada item yang di-mark saat ini.', 'info');
            return;
        }
        
        Swal.fire({
            title: 'Hapus Semua Marking?',
            html: 'Semua marking (<strong>' + markedRows.length + '</strong> item) akan dihapus.<br>' +
                  'Aksi ini tidak dapat dibatalkan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus Semua!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                localStorage.removeItem('markedRows');
                Swal.fire('Berhasil!', 'Semua marking telah dihapus.', 'success');
            }
        });
    }

    // Function untuk delete equipment
    function deleteEq(id) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: "Data checksheet akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= base_url('checksheet/delete_eq/') ?>' + id + 
                    '?_csrf=' + '<?= $this->security->get_csrf_hash() ?>' + 
                    '&section_id=<?= $this->uri->segment(3); ?>&machine_id=<?= $this->uri->segment(4); ?>';
            }
        });
    }
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
