<div class="table-responsive" style="height: 400px !important">
<style>
        .fixed-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: auto;
        }

        .fixed-table th,
        .fixed-table td {
            padding: 10px;
            text-align: left;
            white-space: nowrap;
        }

        .sticky-col {
            position: sticky;
            right: 0;
            z-index: 1;
            background-color: #e2e6ea;
        }

        .sticky-measure-data {
            position: sticky;
            right: 170px; /* Adjust the value based on column width */
            z-index: 1;
            background-color: #e2e6ea;
        }

        .sticky-judgement {
            position: sticky;
            right: 80px; /* Adjust the value based on column width */
            z-index: 1;
            background-color: #e2e6ea;
        }

        .sticky-measure {
            position: sticky;
            right: 5px; /* Adjust the value based on column width */
            z-index: 1;
            background-color: #e2e6ea;
        }
    </style>
    <?php
    // Initialize rowspan arrays
    $partRowspan = array();
    $inspectionPartRowspan = array();
    // Count rowspan for parts and inspection parts
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

    echo '<table class="table table-bordered table-sm fixed-table">';
    echo '<tr>';
echo '<th>Part</th>';
echo '<th>Inspection Part</th>';
echo '<th>Item</th>';
echo '<th>Method</th>';
echo '<th>Determination Standard</th>';
echo '<th class="sticky-measure-data">Measure Data</th>';
echo '<th class="sticky-judgement">Judgement</th>';
echo '<th class="sticky-measure">Measure</th>';
if ($this->session->userdata('level') == 1) {
    echo '<th class="sticky-aksi">Aksi</th>';
}
echo '</tr>';
    $no = 0;
    $printedParts = array(); // To keep track of printed parts

    foreach ($data as $row) {
        $part_id = $row['part_id'];
        $inspection_part_id = $row['inspection_part_id'];
        $is_text = !preg_match('/\d/', $row['determination_standard']); // Check if determination standard has no numbers

        echo '<tr>';
        if (!in_array($part_id, $printedParts)) {
            echo '<td rowspan="' . $partRowspan[$part_id] . '">' . $row['part'] . '</td>';
            $printedParts[] = $part_id;
        }

        if ($inspectionPartRowspan[$part_id][$inspection_part_id] !== false) {
            echo '<td rowspan="' . $inspectionPartRowspan[$part_id][$inspection_part_id] . '">' . $row['inspection_part'] . '</td>';
            $inspectionPartRowspan[$part_id][$inspection_part_id] = false; // Skip rowspan
        }
        echo '<td>' . $row['item'] . '</td>';
        echo '<td>' . $row['method'] . '</td>';
        echo '<td>' . $row['determination_standard'] . '</td>';
        // Kolom Measure Data
echo '<td class="sticky-measure-data"><input type="text" class="form-control measure-input" data-id="' . $row['id'] . '" data-determination="' . htmlspecialchars($row['determination_standard']) . '" value="' . htmlspecialchars($row['measure_data']) . '" ' . ($is_text ? '' : '') . '></td>';

// Kolom Judgement
//  echo '<td class="sticky-judgement">';
//  echo '<td class="judgement-cell">';
echo '<td class="sticky-judgement judgement-cell">';

if ($is_text) {
    // Dropdown untuk judgement jika determination standard adalah teks
    echo '<select class="form-control judgement-select" data-id="' . $row['id'] . '">';
    echo '<option value="" selected></option>'; // Opsi kosong sebagai default
    echo '<option value="No Abnormality"' . ($row['judgement'] == 'No Abnormality' ? ' selected' : '') . '>O</option>'; // Opsi O untuk No Abnormality
    // echo '<option value="Cautious"' . ($row['judgement'] == 'Cautious' ? ' selected' : '') . '>&#955;</option>'; // Opsi untuk Cautious
    echo '<option value="Cautious"' . ($row['judgement'] == 'Cautious' ? ' selected' : '') . '>';
echo '<span style="color: yellow; font-size: 16px; font-weight: bold;">&#9650;</span>'; // Segitiga kuning
echo '</option>';

    echo '<option value="Abnormal"' . ($row['judgement'] == 'Abnormal' ? ' selected' : '') . '>X</option>'; // Opsi untuk Abnormal
    echo '<option value="Repaired Fix" ' . ($row['judgement'] == 'Repaired Fix' ? ' selected' : '') . '>&#8855;</option>';
    echo '</select>';
} else {
    // Judgement untuk determination standard selain teks
    if ($row['judgement'] == 'No Abnormality') {
        echo 'O'; // Tampilkan O untuk No Abnormality
    } elseif ($row['judgement'] == 'Cautious') {
        echo '<span style="color: yellow; font-size: 16px; font-weight: bold;">&#9650;</span>'; // Tampilkan simbol Cautious
    } elseif ($row['judgement'] == 'Abnormal') {
        echo '<span class="text-danger">X</span>'; // Tampilkan X untuk Abnormal
    } elseif ($row['judgement'] == 'Repaired Fix') {
        echo '<span class="text-danger">&#8855;</span>';
    } else {
        echo ''; // Tampilkan kosong jika judgement tidak ada
    }
}
echo '</td>';

// Kolom Measure
echo '<td class="sticky-measure"><input type="text" class="form-control form-control-sm measure" data-id="' . $row['id'] . '" value="' . htmlspecialchars($row['measure']) . '"></td>';

        // echo '<td><a href="javascript:void(0)" onclick="detailPart(' . $row['id'] . ', ' . $section_id . ', ' . $machine_id . ', ' . $no . ', ' . $jumlah . ')">Detail</a>';
         echo '<td>';
if ($this->session->userdata('level') == 1) {
    echo '<a href="javascript:void(0)" onclick="detailPart(' . $row['id'] . ', ' . $section_id . ', ' . $machine_id . ', ' . $no . ', ' . $jumlah . ')">Detail</a>';
    echo ' | <a href="javascript:void(0)" onclick="deletePart(' . $row['id'] . ')">Delete</a>';
}
echo '</td>';

        echo '</tr>';
        $no++;
    }

    echo '</table>';
    // echo '<button type="button" id="saveButton" class="btn btn-primary">Save</button>';
    echo '</form>';
    
    ?>
</div>
<div class="d-flex justify-content-end mr-4">
<button type="button" id="saveButton" class="btn btn-primary btn-lg">Save</button>
</div>
<div class="alert" style="display: none;"></div>
<script src="<?= base_url(); ?>assets/plugins/sweetalert2/sweetalert2.min.js"></script>

<script>
   $(document).ready(function () {
    // Mengambil csrf_token yang sudah didefinisikan di PHP
    var csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
    var csrfHash = '<?= $this->security->get_csrf_hash(); ?>';
    // Menangani klik tombol save
    $('#saveButton').on('click', function () {
        var changes = [];

        // Loop untuk mengambil data dari measure-input
        $('.measure-input').each(function () {
            var id = $(this).data('id');
            var determination = $(this).data('determination');
            var measureData = $(this).val();

            // Hitung judgement berdasarkan measure_data
            var judgement = calc_measure(determination, measureData);

            if (id && determination && measureData !== undefined) {
                changes.push({
                    id: id,
                    determination: determination,
                    measure_data: measureData,
                    judgement: judgement  // Menambahkan judgement ke dalam data
                });
            }
        });
        // Loop untuk mengambil data dari judgement-select jika ada
        $('.judgement-select').each(function () {
            var id = $(this).data('id');
            var judgement = $(this).val();

            if (id && judgement) {
                changes.push({
                    id: id,
                    judgement: judgement
                });
            }
        });

        // Loop untuk mengambil data dari measure
        $('.measure').each(function () {
            var id = $(this).data('id');
            var measure = $(this).val();

            if (id && measure) {
                changes.push({
                    id: id,
                    measure: measure
                });
            }
        });

        console.log("Data yang akan dikirim:", changes); // Debugging

        // Memeriksa apakah ada perubahan data yang perlu disimpan
        if (changes.length === 0) {
            Swal.fire('Tidak ada perubahan', 'Tidak ada data yang perlu disimpan.', 'info');
            return;
        }

        // Menyusun data request untuk dikirim
        var requestData = {
            changes: JSON.stringify(changes),
            [csrfName]: csrfHash // Menambahkan CSRF token ke data
        };

        // Melakukan request AJAX
        $.ajax({
            type: "POST",
            url: "<?= base_url('checksheet/edit_all_details') ?>",
            data: requestData,
            success: function (response) {
                try {
                    var jsonResponse = JSON.parse(response);
                    if (jsonResponse.status === 'success') {
                        Swal.fire('Berhasil', 'Data berhasil diupdate', 'success');
                    } else {
                        Swal.fire('Gagal', 'Terjadi kesalahan: ' + jsonResponse.message, 'error');
                    }
                } catch (e) {
                    console.error("Respon dari server tidak valid:", response);
                    Swal.fire('Berhasil', 'Data berhasil disimpan.', 'success');
                }
            },
            error: function (xhr, status, error) {
                console.error("Gagal menyimpan:", error);
                Swal.fire('Gagal', 'Terjadi kesalahan saat menyimpan data.', 'error');
            }
        });
    });

    // Event listener untuk input measure data
    $('.measure-input').on('input', function () {
        var input = $(this).val();
        var determination = $(this).data('determination');
        var judgement = calc_measure(determination, input);

        // Update judgement cell
        $(this).closest('tr').find('.judgement-cell').html(getJudgementDisplay(judgement));
    });

    // Fungsi untuk menghitung judgement berdasarkan determination dan measure_data
    function calc_measure(determination, input) {
    input = parseFloat(input); // Pastikan input adalah angka desimal

    // Tangani toleransi ±
    if (determination.includes("±")) {
        const matches = determination.match(/(\d+(\.\d+)?)\s*±\s*(\d+(\.\d+)?)/);
        if (matches) {
            const input_value = parseFloat(matches[1]);
            const tolerance = parseFloat(matches[3]);
            if (input === input_value - tolerance || input === input_value + tolerance) {
                return "Cautious";
            } else if (input >= input_value - tolerance && input <= input_value + tolerance) {
                return "No Abnormality";
            } else {
                return "Abnormal";
            }
        }
    }
    // Tangani rentang nilai (misalnya: 15 mm - 20 mm atau 0.2 ~ 0.3 Mpa)
    else if (determination.match(/(\d+(\.\d+)?)\s*(mm|Mpa)?\s*[-~]\s*(\d+(\.\d+)?)/i)) {
        const rangeMatch = determination.match(/(\d+(\.\d+)?)\s*(mm|Mpa)?\s*[-~]\s*(\d+(\.\d+)?)/i);
        if (rangeMatch) {
            const lower_bound = parseFloat(rangeMatch[1]);
            const upper_bound = parseFloat(rangeMatch[4]);

            if (input < lower_bound) {
                return 'Abnormal';
            } else if (input === lower_bound || input === upper_bound) {
                return 'Cautious';
            } else if (input > lower_bound && input < upper_bound) {
                return 'No Abnormality';
            } else {
                return 'Abnormal';
            }
        }
    }
    // Tangani batas "Max" dan "Min" secara bersamaan
    else if (determination.toLowerCase().includes("max") && determination.toLowerCase().includes("min")) {
        const minMatch = determination.match(/min\s*(\d+(\.\d+)?)/i);
        const maxMatch = determination.match(/max\s*(\d+(\.\d+)?)/i);
        const min_value = minMatch ? parseFloat(minMatch[1]) : null;
        const max_value = maxMatch ? parseFloat(maxMatch[1]) : null;

        if (min_value !== null && max_value !== null) {
            if (input < min_value) {
                return 'Abnormal'; 
            } else if (input >= min_value && input <= max_value) {
                return 'Cautious'; 
            } else {
                return 'Abnormal'; 
            }
        }
    } 
    // Tangani frasa "Tidak kurang" (misalnya: Tidak kurang 1.0 Mpa)
    else if (determination.toLowerCase().includes("tidak kurang")) {
        const minMatch = determination.match(/tidak kurang\s*(\d+(\.\d+)?)/i);
        const min_value = minMatch ? parseFloat(minMatch[1]) : null;

        if (min_value !== null) {
            if (input < min_value) {
                return 'Abnormal';
            } else if (input === min_value) {
                return 'Cautious';
            } else {
                return 'No Abnormality';
            }
        }
    } 
    // Tangani hanya batas minimum (misalnya: Min 0.5 Kpa)
    else if (determination.toLowerCase().includes("min")) {
        const minMatch = determination.match(/min\s*(\d+(\.\d+)?)/i);
        const min_value = minMatch ? parseFloat(minMatch[1]) : null;

        if (min_value !== null) {
            if (input < min_value) {
                return 'Abnormal';
            } else if (input === min_value) {
                return 'Cautious';
            } else {
                return 'No Abnormality';
            }
        }
    } 
    // Tangani hanya batas maksimum (misalnya: Max pengikisan 1 mm dan terlumasi)
    else if (determination.toLowerCase().includes("max")) {
        const maxMatch = determination.match(/max\s*(\d+(\.\d+)?)/i);
        const max_value = maxMatch ? parseFloat(maxMatch[1]) : null;

        if (max_value !== null) {
            if (input > max_value) {
                return 'Abnormal';
            } else if (input === max_value) {
                return 'Cautious';
            } else {
                return 'No Abnormality';
            }
        }
    }
    // Tangani perbedaan (misalnya: IN dan OUT perbedaan temperatur 50 C)
    else if (determination.toLowerCase().includes("perbedaan")) {
        const diffMatch = determination.match(/perbedaan temperatur\s*(\d+(\.\d+)?)/i);
        const diff_value = diffMatch ? parseFloat(diffMatch[1]) : null;

        if (diff_value !== null) {
            if (input > diff_value) {
                return 'Abnormal';
            } else if (input === diff_value) {
                return 'Cautious';
            } else {
                return 'No Abnormality';
            }
        }
    }
    // Jika tidak ada kondisi yang terpenuhi
    return 'Data tidak valid';
}
        function getJudgementDisplay(judgement) {
            if (judgement == 'No Abnormality') {
                return 'O';
            } else if (judgement == 'Cautious') {
                return '<span style="color: yellow; font-size: 20px; font-weight: bold;">&#9650;</span>';
            } else if (judgement == 'Abnormal') {
                return '<span class="text-danger">X</span>';
            }
            return '';
        }
    });
</script>
