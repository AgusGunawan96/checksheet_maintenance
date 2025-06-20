<div class="table-responsive" style="height: 400px !important">
    <?php
    // Inisialisasi array rowspan
    $partRowspan = array();
    $inspectionPartRowspan = array();

    // Hitung rowspan untuk parts dan inspection parts
    $jumlah = count($data);
    foreach ($data as $row) {
        $part_id = $row['part_id'];
        $inspection_part_id = $row['inspection_part_id'];

        if (!isset($partRowspan[$part_id])) {
            $partRowspan[$part_id] = 1;
        } else {
            $partRowspan[$part_id]++;
        }

        if (!isset($inspectionPartRowspan[$part_id][$inspection_part_id])) {
            $inspectionPartRowspan[$part_id][$inspection_part_id] = 1;
        } else {
            $inspectionPartRowspan[$part_id][$inspection_part_id]++;
        }
    }

    echo '<form id="updateForm" method="post" action="' . base_url('checksheet/edit_all_details') . '">';
    echo '<input type="hidden" name="' . $this->security->get_csrf_token_name() . '" value="' . $this->security->get_csrf_hash() . '">';
    echo '<table class="table table-bordered table-sm">';
    echo '<tr><th>Part</th><th>Inspection Part</th><th>Item</th><th>Method</th><th>Determination Standard</th><th>Measure Data</th><th>Judgement</th><th>Measure</th><th>Aksi</th></tr>';
    $no = 0;
    $printedParts = array(); // Untuk melacak parts yang sudah dicetak
    foreach ($data as $row) {
        $part_id = $row['part_id'];
        $inspection_part_id = $row['inspection_part_id'];

        echo '<tr>';
        // Output kolom Part dengan rowspan
        if (!in_array($part_id, $printedParts)) {
            echo '<td rowspan="' . $partRowspan[$part_id] . '">' . $row['part'] . '</td>';
            $printedParts[] = $part_id;
        }

        // Output kolom Inspection Part dengan rowspan
        if ($inspectionPartRowspan[$part_id][$inspection_part_id] !== false) {
            echo '<td rowspan="' . $inspectionPartRowspan[$part_id][$inspection_part_id] . '">' . $row['inspection_part'] . '</td>';
            $inspectionPartRowspan[$part_id][$inspection_part_id] = false; // Setel ke false untuk melewati rowspan untuk baris berikutnya dengan part_id dan inspection_part_id yang sama
        }

        echo '<td>' . $row['item'] . '</td>';
        echo '<td>' . $row['method'] . '</td>';
        echo '<td>' . $row['determination_standard'] . '</td>';

        // Measure Data input
        echo '<td><input type="text" class="form-control form-control-sm measure-data" data-id="' . $row['id'] . '" value="' . htmlspecialchars($row['measure_data']) . '"></td>';

        // Judgement select
        echo '<td>
                <select class="form-control form-control-sm judgement" data-id="' . $row['id'] . '">
                    <option value="No Abnormality"' . ($row['judgement'] == 'No Abnormality' ? ' selected' : '') . '>O</option>
                    <option value="Cautious"' . ($row['judgement'] == 'Cautious' ? ' selected' : '') . '>&#955;</option>
                    <option value="Abnormal"' . ($row['judgement'] == 'Abnormal' ? ' selected' : '') . '>X</option>
                </select>
              </td>';

        // Measure input
        echo '<td><input type="text" class="form-control form-control-sm measure" data-id="' . $row['id'] . '" value="' . htmlspecialchars($row['measure']) . '"></td>';

        echo '<td><a href="javascript:void(0)" onclick="detailPart(' . $row['id'] . ', ' . $section_id . ', ' . $machine_id . ', ' . $no . ', ' . $jumlah . ')">Detail</a>';
        if ($this->session->userdata('level') == 1) {
            echo ' | <a href="javascript:void(0)" onclick="deletePart(' . $row['id'] . ')">Delete</a>';
        }
        echo '</td>';
        echo '</tr>';
        $no++;
    }
    echo '</table>';
    echo '<button type="button" id="saveButton" class="btn btn-primary">Save</button>';
    echo '</form>';
    ?>
</div>

<!-- Notifikasi -->
<div class="alert" style="display: none;"></div>

<script src="<?= base_url(); ?>assets/plugins/sweetalert2/sweetalert2.min.js"></script>
<script>
    $(document).ready(function() {
        var csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
        var csrfHash = '<?= $this->security->get_csrf_hash(); ?>';

        $('#saveButton').on('click', function() {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang telah diubah akan disimpan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, simpan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    var changes = [];
                    $('.measure-data, .judgement, .measure').each(function() {
                        var id = $(this).data('id');
                        var measureData = $('.measure-data[data-id="' + id + '"]').val();
                        var judgement = $('.judgement[data-id="' + id + '"]').val();
                        var measure = $('.measure[data-id="' + id + '"]').val();
                        changes.push({
                            id: id,
                            measure_data: measureData,
                            judgement: judgement,
                            measure: measure
                        });
                    });

                    if (changes.length === 0) {
                        Swal.fire('Tidak ada perubahan', 'Tidak ada data yang perlu disimpan.', 'info');
                        return;
                    }

                    var requestData = {
                        changes: JSON.stringify(changes)
                    };
                    requestData[csrfName] = csrfHash; // Include CSRF token in the request

                    $.ajax({
                        type: "post",
                        url: "<?= base_url('checksheet/edit_all_details') ?>",
                        data: requestData,
                        success: function(response) {
                            try {
                                var jsonResponse = JSON.parse(response);
                                if (jsonResponse.status === 'success') {
                                    Swal.fire('Berhasil', 'Data berhasil diupdate', 'success');
                                } else {
                                    Swal.fire('Gagal', 'Terjadi kesalahan: ' + jsonResponse.message, 'error');
                                }
                            } catch (e) {
                                console.error("Invalid JSON response:", response);
                                Swal.fire('Gagal', 'Invalid server response', 'error');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Update failed:", error);
                            Swal.fire('Gagal', 'Terjadi kesalahan saat menyimpan data', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
