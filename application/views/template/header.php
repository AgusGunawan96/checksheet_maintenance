<?php
$this->db->select('notifikasi.*');
$this->db->join('equipment_inspection as eq', 'eq.id = notifikasi.eq_id');
$this->db->where('notifikasi.user_id', $this->session->userdata('id_user'));
$this->db->where('eq.step_proses_user', $this->session->userdata('id_user'));
$this->db->where('is_read', '0');
$notifikasi = $this->db->get('notifikasi')->result_array();
// $notifikasi = $this->db->get_where('notifikasi', ['user_id' => $this->session->userdata('id_user'), 'is_read' => 0])->result_array();
?>
<header class="top-header">
    <nav class="navbar navbar-expand">
        <div class="left-topbar d-flex align-items-center">
            <a href="javascript:;" class="toggle-btn"> <i class="bx bx-menu"></i>
            </a>
        </div>
        <div class="right-topbar ml-auto">
            <ul class="navbar-nav">
                <li class="nav-item dropdown dropdown-lg">
                    <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="javascript:;" data-toggle="dropdown"> <i class="bx bx-bell vertical-align-middle"></i>
                        <span class="msg-count"><?= count($notifikasi); ?></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="javascript:;">
                            <div class="msg-header">
                                <h6 class="msg-header-title"><?= count($notifikasi); ?> New</h6>
                                <p class="msg-header-subtitle">Application Notifications</p>
                            </div>
                        </a>
                        <div class="header-notifications-list">
                            <?php
                            foreach ($notifikasi as $row) {
                            ?>
                                <a class="dropdown-item" href="<?= $row['link']; ?>">
                                    <div class="media align-items-center">
                                        <div class="notify text-cyne"><i class="bx bx-send"></i>
                                        </div>
                                        <div class="media-body">
                                            <h6 class="msg-name">Checksheet Diterima
                                                <!-- <span class="msg-time float-right">28 min
                                                    ago</span> -->
                                            </h6>
                                            <p class="msg-info"><?= $row['deskripsi']; ?></p>
                                        </div>
                                    </div>
                                </a>
                            <?php
                            } ?>
                        </div>
                        <!-- <a href="javascript:;">
                            <div class="text-center msg-footer">View All Notifications</div>
                        </a> -->
                    </div>
                </li>
                <li class="nav-item dropdown dropdown-user-profile">
                    <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:;" data-toggle="dropdown">
                        <div class="media user-box align-items-center">
                            <div class="media-body user-info">
                                <p class="user-name mb-0"><?= $this->session->userdata('nama'); ?></p>
                                <p class="designattion mb-0">Available</p>
                            </div>
                            <!-- <img src="https://via.placeholder.com/110x110" class="user-img" alt="user avatar"> -->
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right"> <a class="dropdown-item" href="<?= site_url('master/profil'); ?>"><i class="bx bx-user"></i><span>Profile</span></a>

                        <div class="dropdown-divider mb-0"></div> <a class="dropdown-item" href="<?= site_url('dashboard/logout'); ?>"><i class="bx bx-power-off"></i><span>Logout</span></a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</header>


<div class="modal fade" id="LoadingModal" tabindex="-1" aria-labelledby="LoadingModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="spinner-border text-warning font-weight-bold" style="width: 5rem; height: 5rem;" role="status">
                    <!-- <span class="sr-only">Loading...</span> -->
                </div> <br> <br> <br>
                <span id="pesan"></span>
            </div>

        </div>
    </div>
</div>

<script>
    function LoadingModal(pesan) {
        $('#LoadingModal').modal('toggle', {
            backdrop: 'static',
            keyboard: false
        })
        $('#pesan').html(pesan);
    }

    function LoadingModalTimer(pesan, timer) {
        $('#LoadingModal').modal('toggle', {
            backdrop: 'static',
            keyboard: false
        })
        $('#pesan').html(pesan);

        setTimeout(function() {
            $('#LoadingModal').modal('hide');
        }, timer);
    }

    function hideLoadingModal() {
        $('#LoadingModal').modal('hide');
    }
</script>