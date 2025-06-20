<?php
$level = $this->session->userdata('level');
$list_satuan = list_satuan();
//check if $list_satuan is in $detail['determination_standard']
$satuan = '';
foreach ($list_satuan as $key => $value) {
    // check if $detail['determination_standard'] is contain number [space] $value
    if (preg_match('/\d+\s' . preg_quote($value, '/') . '/', $detail['determination_standard'])) {
        $satuan = $value;
    }
}
$readonly = '';
$disabled = '';
if ($level == '3' || $level == '4') {
    $readonly = 'readonly';
    $disabled = 'disabled';
}
?>

<div class="detail_part">
    <form action="" method="post" id="checksheet_lama">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <span>Checksheet: </span>
                    <!-- <input type="date" name="tgl_checksheet" id="tgl_checksheet" class="form-control form-control-sm"> -->
                    <select name="tgl_checksheet" id="tgl_checksheet" class="form-control form-control-sm">
                        <option value="" selected disabled>Pilih Checksheet</option>
                        <?php
                        foreach ($list_last_eq as $row) {
                        ?>
                            <option value="<?= $row['tgl_checksheet']; ?>"><?= $row['tgl_checksheet']; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <input type="hidden" name="section_id" value="<?= $section_id; ?>">
                <input type="hidden" name="machine_id" value="<?= $machine_id; ?>">
            </div>
            <div class="col-md-4">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary btn-sm" id="cek_checksheet">Cek</button>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="measure_data">Measure Data</label>
                            <input type="text" name="measure_data" class="form-control form-control-sm" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="judgement">Judgement</label>
                            <input type="text" name="judgement" class="form-control form-control-sm" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="measure">Measure</label>
                            <input type="text" name="measure" class="form-control form-control-sm" readonly>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>
    <hr>
    <br>
    <div class="curr_pemeriksaan">
        <form action="" method="post">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="part">Part</label>
                        <input type="text" name="part" id="part" class="form-control form-control-sm" readonly value="<?= $detail['part']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="inspection_part">Inspection Part</label>
                        <input type="text" name="inspection_part" id="inspection_part" class="form-control form-control-sm" readonly value="<?= $detail['inspection_part']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="item">Item</label>
                        <input type="text" name="item" id="item" class="form-control form-control-sm" readonly value="<?= $detail['item']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="method">Method</label>
                        <input type="text" name="method" id="method" class="form-control form-control-sm" readonly value="<?= $detail['method']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="determination_standard">Determination Standard</label>
                        <input type="text" name="determination_standard" id="determination_standard" class="form-control form-control-sm" readonly value="<?= $detail['determination_standard']; ?>">
                    </div>
                    <input type="hidden" name="id_detail" id="id_detail" value="<?= $id_part; ?>">
                    <input type="hidden" name="id" id="id" value="<?= $id_part; ?>">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="measure_data">Measure Data</label>
                        <?php
                        if ($satuan == '') {
                        ?>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="customRadio1" class="custom-control-input radio_input" <?= ($detail['measure_data'] == 'No Abnormality') ? 'checked' : '' ?> name="radio_btn" value="No Abnormality" <?= $disabled; ?>>
                                <label class="custom-control-label" for="customRadio1">No Abnormality</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="customRadio2" class="custom-control-input radio_input" <?= ($detail['measure_data'] == 'Cautious') ? 'checked' : '' ?> name="radio_btn" value="Cautious" <?= $disabled; ?>>
                                <label class="custom-control-label" for="customRadio2">Cautious</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="customRadio3" class="custom-control-input radio_input" <?= ($detail['measure_data'] == 'Abnormal') ? 'checked' : '' ?> name="radio_btn" value="Abnormal" <?= $disabled; ?>>
                                <label class="custom-control-label" for="customRadio3">Abnormal</label>
                            </div>
                            <input type="hidden" name="measure_data" id="measure_data" value="<?= $detail['measure_data']; ?>">
                        <?php } else {
                            $measure_data = explode(' ', $detail['measure_data']);
                            $measure_data = $measure_data[0];
                            $measure_data = trim($measure_data);
                        ?>
                            <div class="input-group input-group-sm mb-3">
                                <input type="text" name="measure_data" id="measure_data" class="form-control form-control-sm form" value="<?php echo $measure_data; ?>" <?= $readonly; ?>>
                                <div class="input-group-append">
                                    <span class="input-group-text" id="basic-addon2"><?= $satuan; ?></span>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <label for="judgement">Judgement</label>
                        <input type="text" name="judgement" id="judgement" class="form-control form-control-sm" readonly value="<?= $detail['judgement']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="measure">Measure</label>
                        <input type="text" name="measure" id="measure" class="form-control form-control-sm" value="<?= $detail['measure']; ?>" <?= $readonly; ?>>
                    </div>
                    <div class="form-group">
                        <a href="javascript:;" class="btn btn-primary" id="update_detail_part">Update</a>
                    </div>
                    <div id="section_img">
                        <div class="form-group img_items">
                            <label for="image">Foto</label>
                            <div class="img-fluid">
                                <img src="<?= $detail['img_item']; ?>" alt="image" class="img_item_preview" width="200">
                            </div>
                            <div class="row">
                                <div class="col">
                                    <input type="file" id="img_item" class="form-control form-control-sm">
                                </div>
                                <input type="hidden" name="id_detail" value="<?= $detail['id']; ?>">
                                <input type="hidden" name="img_item">

                                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                <div class="col-3">
                                    <button type="button" class="btn btn-info pull-right btn-sm btn_upload_img_item"><i class='bx bxs-cloud-upload'></i></button>
                                </div>
                            </div>
                        </div>

                        <script>
                            //create onchange event for input file img_item and change to base64 and append base64 to input hidden img_item
                            $(document).on('change', '#img_item', function(e) {
                                e.preventDefault();
                                e.stopImmediatePropagation();
                                var file = e.target.files[0];
                                var reader = new FileReader();
                                reader.onload = function(e) {
                                    var base64 = e.target.result;
                                    console.log(base64);
                                    $('input[name="img_item"]').val(base64);
                                }
                                reader.readAsDataURL(file);
                            })
                            $(document).on('click', '.btn_upload_img_item', function(e) {
                                e.preventDefault();
                                e.stopImmediatePropagation();
                                // console.log($('#form_img_item').serializeArray());
                                $.ajax({
                                    method: "post",
                                    url: "<?= site_url('checksheet/upload_img_item') ?>",
                                    dataType: 'json',
                                    data: {
                                        img_item: $('input[name="img_item"]').val(),
                                        id_detail: $('input[name="id_detail"]').val(),
                                        _csrf: '<?= $this->security->get_csrf_hash(); ?>'
                                    },
                                    success: function(data) {
                                        $('.img_item_preview').attr('src', data.img_item);
                                    }
                                })
                            })

                            // window.onload = function() {
                            $(".img_items").imagePreviewer();
                            // };
                        </script>
                    </div>
                    <div class="alert">

                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between">
                                <a class="btn btn-outline-info btn-sm d-inline" href="javascript:;" onclick="prev_pemeriksaan('<?= $no ?>')"><i class='bx bx-left-arrow-alt'></i> Prev Pemeriksaan</a>
                                <span class="d-inline font-weight-bold current_pemeriksaan mt-1">
                                    <span class="current_pemeriksaan"><?= $no + 1; ?></span>
                                    <span class=""> / <?= $jumlah; ?></span>
                                </span>
                                <a class="btn btn-outline-info btn-sm d-inline" href="javascript:;" onclick="next_pemeriksaan('<?= $no ?>')"><i class='bx bx-right-arrow-alt'></i>Next Pemerikasaan</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <script>
            $('#update_detail_part').click(function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                var satuan = '<?= $satuan; ?>';
                var measure_data = $('#measure_data').val();
                if (satuan != '' && measure_data != '') {
                    measure_data += " " + satuan;

                }
                var judgement = $('#judgement').val();
                var measure = $('#measure').val();
                var id = $('input[name="id_detail"]').val();
                var csrf = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').val();
                $.ajax({
                    type: "post",
                    url: "<?= base_url('checksheet/edit_detail_part') ?>",
                    data: {
                        measure_data: measure_data,
                        judgement: judgement,
                        measure: measure,
                        id: id,
                        _csrf: csrf
                    },
                    success: function(response) {
                        // loadTableDetail(id_part);
                        // create alert in class .alert
                        $('.alert').html('<div class="alert alert-success" role="alert">Data berhasil diupdate</div>');
                        // disappear alert after 3 seconds
                        setTimeout(function() {
                            $('.alert').html('');
                        }, 1500);
                    }
                });
            })

            //perhitungan 
            $('#measure_data').on('change', function(e) {

                var measure_data = $('#measure_data').val();

                var judgement = calc_measure('<?= $detail['determination_standard']; ?>', measure_data);
                $('#judgement').val(judgement);
            })

            // find checkbox and filled judgement same as measure_data
            $('.radio_input').on('change', function(e) {
                var radio_btn = $('input[name="radio_btn"]:checked').val();
                // alert(radio_btn);
                var judgement = '';
                if (radio_btn == 'No Abnormality') {
                    judgement = 'No Abnormality';
                }
                if (radio_btn == 'Abnormal') {
                    judgement = 'Abnormal';
                }
                if (radio_btn == 'Cautious') {
                    judgement = 'Cautious';
                }
                $('#measure_data').val(radio_btn);
                $('#judgement').val(judgement);
            });

            function calc_measure(determination, input) {
                // input = parseFloat(input);
                if (determination.includes("±")) {
                    var matches = determination.match(/(\d+(\.\d+)?)\s*±\s*(\d+(\.\d+)?)/);
                    var input_value = parseFloat(matches[1]);
                    var tolerance = parseFloat(matches[3]);
                    console.log('input:', input);
                    console.log('tolerance:', tolerance);
                    console.log('input_value:', input_value);
                    input = parseFloat(input);
                    if (input === input_value - tolerance || input === input_value + tolerance) {
                        return "Cautious";
                    } else if (input >= input_value - tolerance && input <= input_value + tolerance) {
                        return "No Abnormality";
                    } else if (input < input_value - tolerance) {
                        return "Abnormal";
                    } else if (input > input_value + tolerance) {
                        return "Abnormal";
                    }
                } else if (determination.includes("~")) {
                    var range = determination.split("~");
                    var lower_bound = parseFloat(range[0]);
                    var upper_bound = parseFloat(range[1]);
                    // alert(input)
                    input = parseFloat(input);

                    if (input >= lower_bound && input <= upper_bound) {
                        return "Cautious";
                    } else if (input < lower_bound) {
                        return "No Abnormality";
                    } else if (input > upper_bound) {
                        return "Abnormal";
                    }
                } else {
                    determination = determination.match(/\d+(\.\d+)?\s/g);
                    if (determination.length == 1) {
                        determination_standard = {
                            0: parseFloat(determination[0].trim())
                        };
                    } else {
                        determination_standard = {
                            0: parseFloat(determination[0].trim()),
                            1: parseFloat(determination[1].trim())
                        };
                    }
                    //check if there is [1] in determination_standard
                    measure_data = parseFloat(input);
                    // console.log(determination_standard[0]);
                    var judgement = '';
                    if (determination_standard.hasOwnProperty(0) && determination_standard.hasOwnProperty(1)) {
                        if (measure_data >= determination_standard[0] && measure_data <= determination_standard[1]) {
                            return 'Cautious';
                        } else if (measure_data < determination_standard[0]) {
                            return 'No Abnormality';
                        } else {
                            return 'Abnormal';
                        }
                    } else if (determination_standard.hasOwnProperty(0)) {
                        if (measure_data == determination_standard[0]) {
                            return 'Cautious';
                        } else if (measure_data < determination_standard[0]) {
                            return 'No Abnormality';
                        } else {
                            return 'Abnormal';
                        }
                    }
                }
            }
        </script>
    </div>
</div>

<script>
    $('#cek_checksheet').click(function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        var tgl_checksheet = $('#tgl_checksheet').val();
        var csrf = $('input[name="<?= $this->security->get_csrf_token_name(); ?>"]').val();
        $.ajax({
            type: "post",
            url: "<?= base_url('checksheet/cek_checksheet') ?>",
            data: {
                tgl_checksheet: tgl_checksheet,
                section_id: '<?= $section_id; ?>',
                machine_id: '<?= $machine_id; ?>',
                item: '<?= $detail['item']; ?>',
                method: '<?= $detail['method']; ?>',
                determination_standard: '<?= $detail['determination_standard']; ?>',
                _csrf: csrf
            },
            success: function(response) {
                response = JSON.parse(response);
                var checksheet_lama = $('#checksheet_lama');
                checksheet_lama.find('input[name="measure_data"]').val(response.measure_data);
                checksheet_lama.find('input[name="judgement"]').val(response.judgement);
                checksheet_lama.find('input[name="measure"]').val(response.measure);
            }
        });
    })
</script>

<script>
    function next_pemeriksaan(no) {
        var arr_data = <?= json_encode($part_id); ?>;
        check_measure_data(arr_data[no])
            .then(function(response) {
                response = JSON.parse(response);
                if (response.status == false) {
                    alert('Measure Data tidak boleh kosong');
                    return false;
                } else {
                    no = parseInt(no) + 1;
                    //find data in arr_data with index no
                    //check if no is not in arr_data
                    if (no >= arr_data.length) {
                        return;
                    }

                    // check if input name measure_data is empty
                    var measure_data = $('#measure_data').val();
                    if (measure_data == '') {
                        alert('Measure Data tidak boleh kosong');
                        return;
                    }
                    var id = arr_data[no];
                    // alert(id);
                    $.ajax({
                        type: "get",
                        url: "<?= base_url('checksheet/pemeriksaan') ?>",
                        data: {
                            id: id,
                            no: no,
                            jumlah: '<?= $jumlah; ?>',
                        },
                        success: function(response) {
                            $('.curr_pemeriksaan').html(response);
                            // response = JSON.parse(response);
                            // if (response.status == 'success') {
                            // }
                        }
                    });
                }
            })
            .catch(function(error) {
                // Handle error if the AJAX call fails
                console.error('Error:', error);
            })
            .finally(function() {
                // Code to execute after the promise is resolved or rejected
            });

    }

    function prev_pemeriksaan(no) {
        var arr_data = <?= json_encode($part_id); ?>;
        no = parseInt(no) - 1;
        //find data in arr_data with index no
        if (no < 0) {
            return;
        }
        var id = arr_data[no];
        // alert(id);
        $.ajax({
            type: "get",
            url: "<?= base_url('checksheet/pemeriksaan') ?>",
            data: {
                id: id,
                no: no,
                jumlah: '<?= $jumlah; ?>',
            },
            success: function(response) {
                $('.curr_pemeriksaan').html(response);
                // response = JSON.parse(response);
                // if (response.status == 'success') {
                // }
            }
        });
    }

    function check_measure_data(id) {
        return new Promise(function(resolve, reject) {

            // alert(id);
            $.ajax({
                type: "post",
                url: "<?= site_url('checksheet/check_measure') ?>",
                data: {
                    id: id,
                    _csrf: '<?= $this->security->get_csrf_hash(); ?>'
                },
                success: function(response) {
                    resolve(response); // Resolve the promise with the response
                },
                error: function(xhr, status, error) {
                    reject(error); // Reject the promise with the error
                }
            });
        });
    }
</script>