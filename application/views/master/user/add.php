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
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-title">
                                        <h4><?= $judul; ?></h4>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="post" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <label for="nama">Nama</label>
                                                <input type="text" class="form-control" id="nama" name="nama" value="<?= set_value('nama'); ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="username">Username</label>
                                                <input type="text" class="form-control" id="username" name="username" value="<?= set_value('username'); ?>">
                                                <?php echo form_error('username', '<div class="text-danger">', '</div>'); ?>
                                            </div>
                                            <div class="form-group">
                                                <label for="department">Department</label>
                                                <input type="text" class="form-control" id="department" name="department" value="<?= set_value('department'); ?>">
                                                <?php echo form_error('department', '<div class="text-danger">', '</div>'); ?>
                                            </div>
                                            <div class="form-group">
                                                <label for="divisi">Divisi</label>
                                                <input type="text" class="form-control" id="divisi" name="divisi" value="<?= set_value('divisi'); ?>">
                                                <?php echo form_error('divisi', '<div class="text-danger">', '</div>'); ?>
                                            </div>
                                            <div class="form-group">
                                                <label for="posisi">Posisi</label>
                                                <input type="text" class="form-control" id="posisi" name="posisi" value="<?= set_value('posisi'); ?>">
                                                <?php echo form_error('posisi', '<div class="text-danger">', '</div>'); ?>
                                            </div>
                                            <div class="form-group">
                                                <label for="level">Level</label>
                                                <select name="level" id="level" class="form-control">
                                                    <option value="" selected disabled>-- Pilih Level --</option>
                                                    <?php
                                                    foreach ($level as $row) {
                                                    ?>
                                                        <option value="<?= $row['id_level']; ?>" <?= set_select('level', $row['id_level']); ?>><?= $row['nama_level']; ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                                <?php echo form_error('level', '<div class="text-danger">', '</div>'); ?>
                                            </div>
                                            <div class="form-group">
                                                <label for="password">Password</label>
                                                <input type="password" class="form-control" id="password" name="password">
                                                <?php echo form_error('password', '<div class="text-danger">', '</div>'); ?>
                                            </div>
                                            <div class="form-group">
                                                <label for="password2">Konfirmasi Password</label>
                                                <input type="password" class="form-control" id="password2" name="password2">
                                                <?php echo form_error('password2', '<div class="text-danger">', '</div>'); ?>
                                            </div>
                                            <div class="form-group">
                                                <label for="ttd">Tanda Tangan</label>
                                                <input type="file" accept="image/*" class="form-control" id="ttd" name="ttd">
                                                <img src="" id="preview" class="img-fluid mt-3" alt="Preview" style="display: none;" width="150">
                                                <script>
                                                    function previewImage() {
                                                        document.getElementById("preview").style.display = "block";
                                                        var oFReader = new FileReader();
                                                        oFReader.readAsDataURL(document.getElementById("ttd").files[0]);
                                                        oFReader.onload = function(oFREvent) {
                                                            document.getElementById("preview").src = oFREvent.target.result;
                                                        };
                                                    };

                                                    document.getElementById("ttd").addEventListener("change", previewImage, false);
                                                </script>
                                            </div>
                                            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                            <button type="submit" class="btn btn-info">Submit</button>
                                        </form>
                                    </div>
                                </div>
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

</body>

</html>