<?php

/**
 * List satuan
 */
function list_satuan()
{
    $CI = &get_instance();
    $satuan = $CI->db->get('satuan')->result_array();
    // only return satuan_name
    $result = [];
    foreach ($satuan as $row) {
        $result[] = $row['satuan'];
    }
    return $result;
}
