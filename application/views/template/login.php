<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title><?= $judul; ?> - <?= $this->config->item('_APP'); ?></title>
    <!--favicon-->
    <link rel="icon" href="<?= base_url(); ?>assets/images/favicon-32x32.png" type="image/png" />

 <!-- Manifest -->
 <link rel="manifest" href="<?= base_url(); ?>assets/manifest.json">


    <!-- loader-->
    <link href="<?= base_url(); ?>assets/css/pace.min.css" rel="stylesheet" />
    <script src="<?= base_url(); ?>assets/js/pace.min.js"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/bootstrap.min.css?t=<?= time() ?>" />
    <!-- Icons CSS -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/icons.css" />
    <!-- App CSS -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/app.css?t=<?= time(); ?>" />
</head>

<body class="bg-login">
    <!-- wrapper -->
    <div class="wrapper">
        <div class="section-authentication-login d-flex align-items-center justify-content-center">
            <div class="row">
                <div class="col-12 col-lg-10 mx-auto">
                    <div class="card radius-15">
                        <div class="row no-gutters">
                            <div class="col-lg-6">
                                <div class="card-body p-md-4">
                                    <div class="text-center">
                                        <img src="<?= base_url(); ?>assets/images/logo.jpg" width="80" alt="">
                                        <h3 class="mt-3 font-weight-bold">Welcome</h3>
                                    </div>

                                    <?= $this->session->flashdata('message'); ?>
                                    <form action="<?= site_url('login'); ?>" method="post">
                                        <div class="form-group mt-4">
                                            <label>Username</label>
                                            <input type="text" class="form-control" name="username" placeholder="Enter your email address" autofocus />
                                        </div>
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password" class="form-control" name="password" placeholder="Enter your password" />
                                        </div>
                                        <!-- csrf -->
                                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
                                        <div class="btn-group mt-3 w-100">
                                            <button type="submit" class="btn btn-info btn-block">Log In</button>
                                            <button type="button" class="btn btn-info"><i class="lni lni-arrow-right"></i>
                                            </button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <img src="<?= base_url(); ?>assets/images/login-images/login-front-img.png" class="card-img login-img h-100" alt="...">
                            </div>
                        </div>
                        <!--end row-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end wrapper -->

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