<?php

/**
 * Function untuk mengecek session apakah login
 */
function is_login()
{
    // Getting CI class instance.
    $CI = get_instance();
    // mendapatkan class active
    $id_user = $CI->session->userdata('id_user');
    $app = $CI->session->userdata('APP_NAME');
    if (!$id_user) {
        return redirect('login');
    } elseif (!$app) {
        return redirect('login', 'refresh');
    }
}
/**
 * Function untuk pengecekan session tidak login
 */
function is_not_login()
{
    // Getting CI class instance.
    $CI = get_instance();
    // mendapatkan class active
    $id_user = $CI->session->userdata('id_user');
    $app = $CI->session->userdata('APP_NAME');
    if ($id_user && $app == $CI->config->item('_APP')) {
        return redirect('dashboard');
    }
}

/**
 * Function ini digunakan untuk membatasi akses user. ket:
 * 1 = Admin
 * 2 = NBA
 * 3 = BPR
 * 4 = Cabang
 * 5 = Other
 * @param int $level    level user yang akan di diperbolehkan mengakses
 * @param array $speciallevel    level user yang akan dikecualikan
 */
function is_access($level, $speciallevel = [])
{
    // Getting CI class instance.
    $CI = get_instance();
    // mendapatkan class active
    $level_user = $CI->session->userdata('level');
    if ($level_user <= $level) {
        return true;
    } elseif (in_array($level_user, $speciallevel)) {
        return true;
    } elseif ($level_user == null) {
        return redirect('login', 'refresh');;
    } else {
        return redirect('login', 'refresh');;
    }
}
