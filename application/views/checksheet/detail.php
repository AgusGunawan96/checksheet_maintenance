<?php
$level = $this->session->userdata('level');
$section_id = $eq['section_id'];
$machine_id = $eq['machine_id'];
$check_measure_data = 0;
foreach ($data as $row) {
    if ($row['measure_data'] == '' || $row['measure_data'] == null) {
        $check_measure_data = 1;
    }
}
?>
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
    <!-- select2 -->
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
    <!-- Manifest -->
 <link rel="manifest" href="<?= base_url(); ?>assets/manifest.json">
    <!-- App CSS -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/app.css" />
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/dark-sidebar.css" />
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/dark-theme.css" />
    <link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/sweetalert2/sweetalert2.min.css">

    <script src="<?= base_url(); ?>assets/js/jquery.min.js"></script>
    <script src="<?= base_url(); ?>assets/plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- image preview -->
    <script src="<?= base_url(); ?>assets/js/jquery-imagepreviewer.js"></script>
    <style>
        .img-thumbnail {
            width: 100vh;
            height: 200px;
        }

        .modal-fullscreen {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content-fullscreen {
            width: 95%;
            max-width: 95%;
            height: 95%;
            max-height: 95%;
        }

        .modal-dialog-fullscreen {
            max-width: 95%;
            /* margin: 1.75rem auto;
            margin-left: 2rem !important; */
        }

        /* @media (min-width: 576px) {

            .modal-content {
                max-width: 100%;
                max-height: calc(100vh - 3.5rem);
                border-radius: 0;
            }
        } */
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
                        <div class="breadcrumb-title pr-3">Equipment Inspection Sheet
                        </div>
                        <div class="pl-3">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 p-0">
                                    <li class="breadcrumb-item"><a href="javascript:;"><i class='bx bx-home-alt'></i></a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page"><?= $judul; ?></li>
                                    <li class="breadcrumb-item active" aria-current="page"><?= $eq['section_name']; ?></li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <!--end breadcrumb-->
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title">
                                <div class="row">
                                    <div class="col-md-8 col-5">
                                        <table>
                                            <tr>
                                                <td class="h5 pt-2">Rank</td>
                                                <td class="h5">:</td>
                                                <td class="h5 pt-2"><?= $eq['rank']; ?></td>
                                            </tr>
                                            <tr>
                                                <td class="h5 pt-2">Section</td>
                                                <td class="h5">:</td>
                                                <td class="h5 pt-2"><?= $eq['section_name']; ?></td>
                                            </tr>
                                            <tr>
                                                <td class="h5 pt-2">Machine Name</td>
                                                <td class="h5">:</td>
                                                <td class="h5 pt-2"><?= $eq['machine_name']; ?></td>
                                            </tr>
                                            <tr>
                                                <td class="h5 pt-2">Equipment No</td>
                                                <td class="h5">:</td>
                                                <td class="h5 pt-2"><?= $eq['equipment_no']; ?></td>
                                            </tr>
                                            <tr>
                                                <td class="h5 pt-2">Cycle</td>
                                                <td class="h5">:</td>
                                                <td class="h5 pt-2"><?= $eq['cycle']; ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-2 col-4">
                                        <span class="font-weight-bold">Inspection Day: <?= date('d F Y', strtotime($eq['tgl_checksheet'])); ?></span> <br>
                                        <span class="font-weight-bold">Inspector: <?= $eq['nama']; ?></span> <br> <br>
                                        <span class="font-weight-bold">Judgement: </span><br>
                                        <span class="text-dark">O : No Abnormality</span><br>
                                        <span class="text-warning">&#9650; : Cautious</span><br>
                                        <span class="text-danger">X : Abnormal</span><br>
                                        <span class="text-dark">&#8855; : Repaired Fix</span>
                                        
                                    </div>
                                    <div class="col-md-2 col-3">
                                        <?php
                                        if ($eq['step_proses'] == 4) {
                                        ?>
                                            <a href="<?= site_url('checksheet/export/' . $this->uri->segment(3)); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-success mb-2"><i class='bx bx-export'></i> Export Checksheet</a>
                                        <?php } ?>
                                        <br>
                                        <?php
if ($this->session->userdata('level') != 1) {
?>
    <div class="border border-dark rounded p-2">
    <span class="h6 mb-2">Kirim Checksheet</span>
    <br>
    <form action="<?= site_url('checksheet/update_checksheet/step_proses'); ?>" method="post" id="form_kirim_checksheet">
        <div class="form-group">
            <?php
            if (!empty($users_management)) {
                // Ambil user pertama dari daftar
                $user = reset($users_management); // Mengambil user pertama
                echo '<input type="hidden" name="step_proses_user" id="step_proses_user" value="' . $user['id_user'] . '">'; 
                echo '<input type="text" class="form-control" value="' . htmlspecialchars($user['nama']) . '" readonly>';
            } else {
                echo '<input type="text" class="form-control" value="Tidak ada user tersedia" readonly>';
            }
            ?>
        </div>
        <input type="hidden" name="machine_id" value="<?= $eq['machine_id']; ?>">
        <input type="hidden" name="section_id" value="<?= $eq['section_id']; ?>">
        <input type="hidden" name="id" value="<?= $this->uri->segment(3); ?>">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
        <div class="form-group">
            <button type="button" class="btn btn-success" onclick="checkLengkap()">Kirim</button>
        </div>
    </form>
    <?= $this->session->flashdata('message_kirim_checksheet'); ?>
</div>
<?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body" style="min-height: 500px;">
                            <div class="card-title">
                                <h4>Daftar Part</h4>
                                <?php
                                // if ($level == '1' || $level == '2') {
                                    if ($level == '1') {
                                ?>
                                    <button class="btn btn-info" data-toggle="modal" data-target="#part_add">Tambah Part</button>
                                    <button class="btn btn-info" data-toggle="modal" data-target="#inspection_part_add">Tambah Inspection Part</button>
                                    <button class="btn btn-info" data-toggle="modal" data-target="#item_add">Tambah Item</button>

                                    <button class="btn btn-success" data-toggle="modal" data-target="#excel_import">Import dari CSV</button>
    <a href="<?= site_url('checksheet/download_template_csv'); ?>" class="btn btn-outline-success">Download Template CSV</a>

                                <?php } ?>
                            </div>

                            <!-- Tambahkan tombol untuk import Excel -->



                            <?= $this->session->flashdata('message'); ?>
                            <hr />
                            <div class="table_detail">
                                <?php
                                $this->load->view('checksheet/table_detail', ['data' => $data, 'section_id' => $section_id, 'machine_id' => $machine_id]);

                                ?>
                            </div>

                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title">
                                <h4>File Tambahan</h4>
                            </div>
                            <hr />
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row img_tambahan">
                                        <div class="col-md-4">
                                            <form method="post" action="<?= site_url('checksheet/update_checksheet/img1'); ?>" enctype="multipart/form-data">
                                                <div class="form-group">
                                                    <label for="image">Foto 1</label>
                                                    <div class="img-fluid">
                                                        <img src="<?= $eq['img_checksheet1']; ?>" alt="image" class="img-thumbnail">
                                                    </div>
                                                    <div class="row">
                                                        <!-- <div class="col">
                                                            <input type="file" name="img_checksheet1" id="img_checksheet1" class="form-control form-control-sm">
                                                        </div> -->
                                                        <div class="col">
                                                             <!-- Menambahkan atribut accept dan capture untuk membuka kamera di perangkat yang mendukung -->
                                                            <input type="file" name="img_checksheet1" id="img_checksheet1" class="form-control form-control-sm" accept="image/*" capture="camera">
                                                        </div>
                                                        <div class="col-3">
                                                            <button type="submit" class="btn btn-info pull-right btn-sm"><i class='bx bxs-cloud-upload'></i></button>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="id" value="<?= $this->uri->segment(3); ?>">
                                                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-md-4">
                                            <form method="post" action="<?= site_url('checksheet/update_checksheet/img2'); ?>" enctype="multipart/form-data">
                                                <div class="form-group">
                                                    <label for="image">Foto 2</label>
                                                    <div class="img-fluid">
                                                        <img src="<?= $eq['img_checksheet2']; ?>" alt="image" class="img-thumbnail">
                                                    </div>
                                                    <div class="row">
                                                        <div class="col">
                                                            <input type="file" name="img_checksheet2" id="img_checksheet2" class="form-control">
                                                        </div>
                                                        <div class="col-3">
                                                            <button type="submit" class="btn btn-info pull-right btn-sm mt-1"><i class='bx bxs-cloud-upload'></i></button>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="id" value="<?= $this->uri->segment(3); ?>">
                                                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-md-4">
                                            <form method="post" action="<?= site_url('checksheet/update_checksheet/img3'); ?>" enctype="multipart/form-data">
                                                <div class="form-group">
                                                    <label for="image">Foto 3</label>
                                                    <div class="img-fluid">
                                                        <img src="<?= $eq['img_checksheet3']; ?>" alt="image" class="img-thumbnail">
                                                    </div>
                                                    <div class="row">
                                                        <div class="col">
                                                            <input type="file" name="img_checksheet3" id="img_checksheet3" class="form-control">
                                                        </div>
                                                        <div class="col-3">
                                                            <button type="submit" class="btn btn-info pull-right btn-sm mt-1"><i class='bx bxs-cloud-upload'></i></button>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="id" value="<?= $this->uri->segment(3); ?>">
                                                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- show image -->

                                </div>
                                <div class="col-md-4">
                                    <form action="<?= site_url('checksheet/update_checksheet/additional'); ?>" method="post">
                                        <div class="form-group">
                                            <label for="additional_item">Inspection item of addition</label>
                                            <textarea name="additional_item" id="additional_item" class="form-control" rows="5"><?= $eq['additional_item']; ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="purchase_part">Purchase a necessary part
                                            </label>
                                            <textarea name="purchase_part" id="purchase_part" class="form-control" rows="5"><?= $eq['purchase_part']; ?></textarea>
                                        </div>
                                        <input type="hidden" name="id" value="<?= $this->uri->segment(3); ?>">
                                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                        
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-info">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- create form input image with upload button -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal" tabindex="-1" id="part_add">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Part</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="post" action="<?= site_url('checksheet/add_part'); ?>">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="part">Part</label>
                                    <select name="part" id="part" class="form-control select2">
                                        <option value="">Pilih Part</option>
                                        <?php foreach ($parts as $row) : ?>
                                            <option value="<?= $row['part_name']; ?>"><?= $row['part_name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <input type="hidden" name="eq_id" value="<?= $this->uri->segment(3); ?>">
                                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-info">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal" tabindex="-1" id="inspection_part_add">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Inspection Part</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="post" action="<?= site_url('checksheet/add_inspection_part'); ?>">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="part_id">Part</label>
                                            <select name="part_id" id="part_id" class="form-control">
                                                <option value="">Pilih Part</option>
                                                <?php foreach ($parts_machine as $row) : ?>
                                                    <option value="<?= $row['id']; ?>"><?= $row['part']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="inspection_part">Inspection Part</label>
                                            <select name="inspection_part" id="inspection_part" class="form-control select2">
                                                <option value="-" selected disabled>-- Inspection Part --</option>
                                                <?php foreach ($inspection_parts as $row) : ?>
                                                    <option value="<?= $row['inspection_part_name'] ?>"><?= $row['inspection_part_name'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

                                    </div>
                                </div>
                                <input type="hidden" name="eq_id" value="<?= $this->uri->segment(3); ?>">
                                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-info">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal" tabindex="-1" id="item_add">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Item</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="post" action="<?= site_url('checksheet/add_item'); ?>">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="eq_part_id">Part</label>
                                            <select name="eq_part_id" id="eq_part_id" class="form-control select2">
                                                <option value="">Pilih Part</option>
                                                <?php foreach ($parts_machine as $row) : ?>
                                                    <option value="<?= $row['id']; ?>"><?= $row['part']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="inspection_part_id">Inspection Part</label>
                                            <select name="inspection_part_id" id="inspection_part_id" class="form-control select2">
                                                <option value="-" selected disabled>-- Inspection Part --</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="item">Item</label>

                                            <select name="item" id="item" class="form-control select2">
                                                <option value="-" selected disabled>-- Item --</option>
                                                <?php foreach ($items as $row) : ?>
                                                    <option value="<?= $row['item_name'] ?>"><?= $row['item_name'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="method">Method</label>
                                            <select name="method" id="method" class="form-control select2">
                                                <option value="-" selected disabled>-- Method --</option>
                                                <?php foreach ($methods as $row) : ?>
                                                    <option value="<?= $row['method_name'] ?>"><?= $row['method_name'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="determination_standard">Determination Standard</label>
                                            <select name="determination_standard" id="determination_standard" class="form-control select2">
                                                <option value="-" selected disabled>-- Determination Standard --</option>
                                                <?php foreach ($determination_standards as $row) : ?>
                                                    <option value="<?= $row['determination_name'] ?>"><?= $row['determination_name'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

                                    </div>
                                </div>
                                <input type="hidden" name="eq_id" value="<?= $this->uri->segment(3); ?>">
                                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-info">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
           <!-- Modal Import CSV -->
<div class="modal" tabindex="-1" id="excel_import">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Data dari CSV</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="<?= site_url('checksheet/import_excel_csv'); ?>" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="excel_file">File CSV</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="excel_file" name="excel_file" accept=".csv">
                            <label class="custom-file-label" for="excel_file">Pilih file</label>
                        </div>
                        <small class="form-text text-muted">
                            Format: Part, Inspection Part, Item, Method, Determination Standard<br>
                            Silahkan download template terlebih dahulu untuk melihat format yang benar.
                        </small>
                    </div>
                    <input type="hidden" name="eq_id" value="<?= $this->uri->segment(3); ?>">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
            <div class="modal" tabindex="-1" id="part_detail">
                <div class="modal-dialog modal-dialog-scrollable modal-dialog-fullscreen modal-fullscreen">
                    <div class="modal-content modal-content-fullscreen">
                        <div class="modal-header">
                            <h5 class="modal-title">Detail Part</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="modal_body_detail">

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
    <!-- Script untuk menampilkan nama file yang dipilih -->
    <script>
$(document).ready(function() {
    // Menampilkan nama file setelah dipilih
    $('.custom-file-input').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
});
</script>

    <script>
        var eq_id = '<?= $this->uri->segment(3); ?>';
        var section_id = '<?= $section_id; ?>';
        var machine_id = '<?= $machine_id; ?>';

        function deletePart(id) {
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
                    window.location.href = '<?= base_url('checksheet/delete_part/') ?>' + id + '?_csrf=' + '<?= $this->security->get_csrf_hash() ?>' + '&eq_id=' + eq_id;
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
        $('#machine_add').on('hidden.bs.modal', function(e) {
            $('#machine_name').val('');
            $('#rank').val('');
            $('#id').val('');
            // change title
            $('.modal-title').text('Tambah Machine');
            // change button
            $('.modal-footer button[type=submit]').text('Submit');
            // change action
            $('form').attr('action', '<?= base_url('machine/add') ?>');
        });

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

        function detailPart(id, section_id, machine_id, no, jumlah) {
            // loop variable data and get id as array
            var part_id = [];
            var data = <?= json_encode($data); ?>;
            data.forEach(function(row) {
                part_id.push(row.id);
            });
            //convert array that can be able pass to url
            part_id = part_id.join(',');
            //open modal and call ajax
            $.ajax({
                url: '<?= base_url('checksheet/get_detail_part/') ?>' + id + '?section_id=' + section_id + '&machine_id=' + machine_id + '&no=' + no + '&jumlah=' + jumlah + '&part_id=' + part_id,
                type: 'GET',
                dataType: 'html',
                success: function(data) {
                    $('#part_detail').modal('show');
                    $('#modal_body_detail').html(data);
                }
            });
        }

        function convertImageToBase64(file, callback) {
            let reader = new FileReader();
            reader.onload = function(event) {
                callback(event.target.result);
            };
            reader.readAsDataURL(file);
        }

        $('#eq_part_id').on('change', function(e) {
            var eq_part_id = $(this).val();
            $.ajax({
                url: '<?= base_url('checksheet/get_inspection_part/') ?>' + eq_part_id,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var html = '';
                    html += '<option value="-" selected disabled>-- Inspection Part --</option>';
                    data.forEach(function(row) {
                        html += '<option value="' + row.id + '">' + row.inspection_part + '</option>';
                    });
                    $('#inspection_part_id').html(html);
                }
            });
        });

        function loadTableDetail(id, section_id, machine_id) {
            $.ajax({
                url: '<?= base_url('checksheet/get_table_detail/') ?>' + id + '?section_id=' + section_id + '&machine_id=' + machine_id,
                type: 'GET',
                dataType: 'html',
                success: function(data) {
                    $('.table_detail').html(data);
                }
            });
        }

        //when modal detail part hide load table detail
        $('#part_detail').on('hidden.bs.modal', function(e) {
            loadTableDetail(eq_id, section_id, machine_id);
        });

        $('.select2').select2({
            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
        });

        function checkLengkap() {
    // Pemeriksaan untuk memilih user
    var step_proses_user = $('#step_proses_user').val();
    if (step_proses_user == '-' || step_proses_user == null || step_proses_user == '') {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Pilih User Terlebih Dahulu!',
        });
        return false;
    }

    // Pemeriksaan untuk data measurement
    var check_measure_data = '<?= $check_measure_data; ?>';
    if (check_measure_data == 1) {
        Swal.fire({
            icon: 'warning',
            title: '',
            text: 'Apakah Anda yakin ingin mengirim data ini?',
            showCancelButton: true,
            confirmButtonText: 'Ya, Kirim',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika pengguna memilih untuk mengirim, maka form akan disubmit
                $('#form_kirim_checksheet').submit();
            }
        });
        return false;
    }

    // Pengiriman form tanpa memeriksa form tambahan
    $('#form_kirim_checksheet').submit();
}

window.onload = function() {
    $(".img_tambahan").imagePreviewer();
};
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