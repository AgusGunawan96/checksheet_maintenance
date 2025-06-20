<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div class="">
            <img src="<?= base_url(); ?>assets/images/logo.jpg" class="logo-icon-2" alt="" />
        </div>
        <div>
            <h6 class="logo-text"><?= $this->config->item('_APP'); ?></h6>
        </div>
        <a href="javascript:;" class="toggle-btn ml-auto"> <i class="bx bx-menu"></i>
        </a>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        <li>
            <a href="<?= site_url('dashboard'); ?>">
                <div class="parent-icon icon-color-1"><i class="bx bx-home-alt"></i>
                </div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>
        <li>
            <a href="javascript:;" class="has-arrow" aria-expanded="true">
                <div class="parent-icon icon-color-3"><i class="bx bx-cog"></i>
                </div>
                <div class="menu-title">Mechanical</div>
            </a>
            <ul class="mm-collapse">
                <?php
                $this->db->where('category', 'Maintenance');
                $this->db->group_by('section_name');
                $section = $this->db->get('section')->result_array();
                foreach ($section as $row) {
                ?> 
                    <li>
                        <a href="<?= site_url('checksheet/section/' . $row['section_name']); ?>">
                            <i class="bx bx-right-arrow-alt"></i>
                            <?= $row['section_name']; ?>
                        </a>
                    </li>
                <?php
                }
                ?>
            </ul>
        </li>
        <li>
            <a href="javascript:;" class="has-arrow" aria-expanded="true">
                <div class="parent-icon icon-color-4"><i class="bx bx-pulse"></i> 
                </div>
                <div class="menu-title">Electrical</div>
            </a>
            <ul class="mm-collapse">
                <?php
                $this->db->where('category', 'Electrical');
                $this->db->group_by('section_name');
                $sections_elektrik = $this->db->get('section')->result_array();
                foreach ($sections_elektrik as $row) {
                ?>
                    <li>
                        <a href="<?= site_url('checksheet/section/' . $row['section_name']); ?>">
                            <i class="bx bx-right-arrow-alt"></i>
                            <?= $row['section_name']; ?>
                        </a>
                    </li>
                <?php
                }
                ?>
            </ul>
        </li>

        <li>
            <a href="javascript:;" class="has-arrow" aria-expanded="true">
                <div class="parent-icon icon-color-5"><i class="bx bx-wrench"></i>
                </div>
                <div class="menu-title">Utility</div>
            </a>
            <ul class="mm-collapse">
                <?php
                $this->db->where('category', 'Utility');
                $this->db->group_by('section_name');
                $sections_utility = $this->db->get('section')->result_array();
                foreach ($sections_utility as $row) {
                ?>
                    <li>
                        <a href="<?= site_url('checksheet/section/' . $row['section_name']); ?>">
                            <i class="bx bx-right-arrow-alt"></i>
                            <?= $row['section_name']; ?>
                        </a>
                    </li>
                <?php
                }
                ?>
            </ul>
        </li>
        
        

        <li class="menu-label">Section</li>
        <li>
            <a href="<?= site_url('progress_checksheet'); ?>">
                <div class="parent-icon icon-color-2"><i class="bx bx-spreadsheet"></i>
                </div>
                <div class="menu-title">Inspection Progress Checksheet</div>
            </a>
        </li>
        <?php
        if ($this->session->userdata('level') == 1) {
        ?>
            <li class="menu-label">Master</li>
            <li>
                <a href="<?= site_url('master/users'); ?>">
                    <div class="parent-icon icon-color-2"><i class="bx bx-group"></i>
                    </div>
                    <div class="menu-title">Users</div>
                </a>
            </li>
            <li>
                <a href="<?= site_url('user_management'); ?>">
                    <div class="parent-icon icon-color-2"><i class="bx bx-group"></i>
                    </div>
                    <div class="menu-title">User Management</div>
                </a>
            </li>
            <li>
                <a href="javascript:;" class="has-arrow" aria-expanded="true">
                    <div class="parent-icon icon-color-1"><i class="bx bx-data"></i>
                    </div>
                    <div class="menu-title">Master Data</div>
                </a>
                <ul class="mm-collapse">
                    <li><a href="<?= site_url('section'); ?>" aria-expanded="true"><i class="bx bx-right-arrow-alt"></i>Section</a></li>
                    <li><a href="<?= site_url('machine'); ?>" aria-expanded="true"><i class="bx bx-right-arrow-alt"></i>Machine</a></li>
                    <li><a href="<?= site_url('part'); ?>" aria-expanded="true"><i class="bx bx-right-arrow-alt"></i>Part</a></li>
                    <li><a href="<?= site_url('inspection_part'); ?>" aria-expanded="true"><i class="bx bx-right-arrow-alt"></i>Inspection Part</a></li>
                    <li><a href="<?= site_url('item'); ?>" aria-expanded="true"><i class="bx bx-right-arrow-alt"></i>Item</a></li>
                    <li><a href="<?= site_url('method'); ?>" aria-expanded="true"><i class="bx bx-right-arrow-alt"></i>Method</a></li>
                    <li><a href="<?= site_url('determination_standard'); ?>" aria-expanded="true"><i class="bx bx-right-arrow-alt"></i>Determination Standard</a></li>
                    <li><a href="<?= site_url('master/satuan'); ?>" aria-expanded="true"><i class="bx bx-right-arrow-alt"></i>Satuan</a></li>
                </ul>
            </li>

        <?php } ?>
    </ul>
    <!--end navigation-->
</div>
