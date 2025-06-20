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
                    <!--breadcrumb-->
                    <div class="page-breadcrumb d-none d-md-flex align-items-center mb-3">
                        <div class="breadcrumb-title pr-3">User Profile</div>
                        <div class="pl-3">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 p-0">
                                    <li class="breadcrumb-item">
                                        <a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        User Profile
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <!--end breadcrumb-->
                    <div class="user-profile-page">
                        <div class="card radius-15">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-lg-7 border-right">
                                        <div class="d-md-flex align-items-center">
                                            <div class="mb-md-0 mb-3">
                                                <!-- <img src="https://via.placeholder.com/110x110" class="rounded-circle shadow" width="130" height="130" alt="" /> -->
                                            </div>
                                            <div class="ml-md-4 flex-grow-1">
                                                <div class="d-flex align-items-center mb-1">
                                                    <h4 class="mb-0"><?= $data['nama']; ?></h4>
                                                </div>
                                                <!-- <p class="mb-0 text-muted">Sr. Web Developer</p>
                                                <p class="text-primary">
                                                    <i class="bx bx-buildings"></i> Epic Coders
                                                </p>
                                                <button type="button" class="btn btn-info">
                                                    Connect
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary ml-2">
                                                    Resume
                                                </button> -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-5">
                                        <table class="table table-sm table-borderless mt-md-0 mt-3">
                                            <tbody>
                                                <tr>
                                                    <th>Username:</th>
                                                    <td>
                                                        <?= $data['username']; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Email:</th>
                                                    <td><?= $data['email']; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Level:</th>
                                                    <td><?= $data['level']; ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!--end row-->
                                <div class="tab-content mt-3">
                                    <div class="tab-pane show active" id="Edit-Profile">
                                        <div class="card shadow-none border mb-0 radius-15">
                                            <div class="card-body">
                                                <form method="POST" action="<?= site_url('master/profil'); ?>">
                                                    <div class="form-body">
                                                        <div class="row">
                                                            <div class="col-12 col-lg-12">

                                                                <?= $this->session->flashdata('message'); ?>
                                                                <div class="form-group">
                                                                    <label>Nama</label>
                                                                    <input type="text" value="<?= $data['nama']; ?>" name="nama" class="form-control" readonly onfocus="this.removeAttribute('readonly');" required />
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Username</label>
                                                                    <input type="text" value="<?= $data['username']; ?>" class="form-control" name="username" autocomplete="false" readonly onfocus="this.removeAttribute('readonly');" required />
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Password</label>
                                                                    <span class="text-danger">* Leave blank if you don't want to change it</span>
                                                                    <input type="password" name="password" value="" class="form-control" autocomplete="false" aria-autocomplete="none" readonly onfocus="this.removeAttribute('readonly');" />
                                                                </div>
                                                                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                                                <div class="form-group">
                                                                    <button class="btn btn-info">
                                                                        Update
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
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
        <a href="javaScript:;" class="back-to-top"><i class="bx bxs-up-arrow-alt"></i></a>
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