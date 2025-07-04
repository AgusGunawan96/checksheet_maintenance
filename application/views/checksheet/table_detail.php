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

        .sticky-aksi {
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

        /* New CSS for marking functionality */
        .marked-row {
            background-color: #fff3cd !important;
            color: #856404 !important;
        }

        .marked-row td {
            background-color: #fff3cd !important;
            color: #856404 !important;
            border-color: #ffeaa7 !important;
        }

        .mark-btn {
            padding: 2px 6px;
            font-size: 11px;
            border-radius: 3px;
            margin-left: 5px;
        }

        .mark-btn.marked {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #212529;
        }

        .mark-btn.unmarked {
            background-color: #6c757d;
            border-color: #6c757d;
            color: #fff;
        }

        /* CSS tambahan untuk satuan measure data */
        .measure-input-with-unit {
            position: relative;
        }

        .measure-unit-display {
            font-size: 11px;
            color: #6c757d;
            font-style: italic;
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
// Always show Aksi column for all users
if ($this->session->userdata('level') == 1) {
echo '<th class="sticky-aksi">Aksi</th>';
} else {
    
}
echo '</tr>';
    $no = 0;
    $printedParts = array(); // To keep track of printed parts

    foreach ($data as $row) {
        $part_id = $row['part_id'];
        $inspection_part_id = $row['inspection_part_id'];
        $is_text = !preg_match('/\d/', $row['determination_standard']); // Check if determination standard has no numbers

        echo '<tr id="row-' . $row['id'] . '">';
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
        
        // Kolom Measure Data dengan dukungan satuan
        echo '<td class="sticky-measure-data">';
        echo '<input type="text" class="form-control measure-input" data-id="' . $row['id'] . '" data-determination="' . htmlspecialchars($row['determination_standard']) . '" value="' . htmlspecialchars($row['measure_data']) . '" ' . ($is_text ? '' : '') . '>';
        echo '<div class="measure-unit-display" id="unit-display-' . $row['id'] . '"></div>';
        echo '</td>';

// Kolom Judgement
echo '<td class="sticky-judgement judgement-cell">';

if ($is_text) {
    // Dropdown untuk judgement jika determination standard adalah teks
    echo '<select class="form-control judgement-select" data-id="' . $row['id'] . '">';
    echo '<option value="" selected></option>'; // Opsi kosong sebagai default
    echo '<option value="No Abnormality"' . ($row['judgement'] == 'No Abnormality' ? ' selected' : '') . '>O</option>'; // Opsi O untuk No Abnormality
    echo '<option value="Cautious"' . ($row['judgement'] == 'Cautious' ? ' selected' : '') . '>';
echo '<span style="color: orange; font-size: 16px; font-weight: bold;">&#9650;</span>'; // Segitiga orange
echo '</option>';

    echo '<option value="Abnormal"' . ($row['judgement'] == 'Abnormal' ? ' selected' : '') . '>X</option>'; // Opsi untuk Abnormal
    echo '<option value="Repaired Fix" ' . ($row['judgement'] == 'Repaired Fix' ? ' selected' : '') . '>⊘</option>';
    echo '</select>';
} else {
    // Judgement untuk determination standard selain teks
    if ($row['judgement'] == 'No Abnormality') {
        echo 'O'; // Tampilkan O untuk No Abnormality
    } elseif ($row['judgement'] == 'Cautious') {
        echo '<span style="color: orange; font-size: 16px; font-weight: bold;">&#9650;</span>'; // Tampilkan simbol Cautious
    } elseif ($row['judgement'] == 'Abnormal') {
        echo '<span class="text-danger">X</span>'; // Tampilkan X untuk Abnormal
    } elseif ($row['judgement'] == 'Repaired Fix') {
    echo '<span class="text-info" style="font-size: 16px; font-weight: bold;">⊘</span>';
    } else {
        echo ''; // Tampilkan kosong jika judgement tidak ada
    }
}
echo '</td>';

// Kolom Measure
echo '<td class="sticky-measure"><input type="text" class="form-control form-control-sm measure" data-id="' . $row['id'] . '" value="' . htmlspecialchars($row['measure']) . '"></td>';

        // Kolom Aksi
        if ($this->session->userdata('level') == 1) {
        echo '<td class="sticky-aksi">';

    echo '<a href="javascript:void(0)" onclick="detailPart(' . $row['id'] . ', ' . $section_id . ', ' . $machine_id . ', ' . $no . ', ' . $jumlah . ')">Detail</a>';
    echo ' | <a href="javascript:void(0)" onclick="deletePart(' . $row['id'] . ')">Delete</a>';
    // New Mark button - only for admin
    echo ' | <button type="button" class="btn btn-sm mark-btn unmarked" onclick="toggleMark(' . $row['id'] . ')" id="mark-btn-' . $row['id'] . '">Mark</button>';
    // For non-admin users, show Detail link only
    echo '<a href="javascript:void(0)" onclick="detailPart(' . $row['id'] . ', ' . $section_id . ', ' . $machine_id . ', ' . $no . ', ' . $jumlah . ')">Detail</a>';
} else {
    
}
echo '</td>';

        echo '</tr>';
        $no++;
    }

    echo '</table>';
    echo '</form>';
    
    ?>
</div>
<div class="d-flex justify-content-end mr-4">
<button type="button" id="saveButton" class="btn btn-primary btn-lg">Save</button>
<?php if ($this->session->userdata('level') == 1): ?>
<button type="button" id="clearAllMarks" class="btn btn-warning btn-lg ml-2">Clear All Marks</button>
<?php endif; ?>
</div>
<div class="alert" style="display: none;"></div>
<script src="<?= base_url(); ?>assets/plugins/sweetalert2/sweetalert2.min.js"></script>

<script>
   $(document).ready(function () {
    // Mengambil csrf_token yang sudah didefinisikan di PHP
    var csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
    var csrfHash = '<?= $this->security->get_csrf_hash(); ?>';
    
    // Load marked rows from localStorage on page load - for ALL users
    loadMarkedRows();
    
    // Initialize unit display for existing measure data
    initializeUnitDisplay();
    
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

    // Event listener untuk input measure data dengan dukungan satuan
    $('.measure-input').on('input', function () {
        var input = $(this).val();
        var determination = $(this).data('determination');
        var id = $(this).data('id');
        var judgement = calc_measure(determination, input);

        // Update judgement cell
        $(this).closest('tr').find('.judgement-cell').html(getJudgementDisplay(judgement));
        
        // Update unit display
        updateUnitDisplay(id, input, determination);
    });

    // Event listener tambahan untuk blur (ketika user selesai input)
    $('.measure-input').on('blur', function () {
        var input = $(this).val();
        var determination = $(this).data('determination');
        var id = $(this).data('id');
        
        // Update unit display dan title attribute
        updateUnitDisplay(id, input, determination);
        
        if (input && !isNaN(input)) {
            const inputWithUnit = addUnitToMeasureData(input, determination);
            $(this).attr('title', 'Nilai dengan satuan: ' + inputWithUnit);
        }
    });

    // Clear all marks button handler
    $('#clearAllMarks').on('click', function () {
        Swal.fire({
            title: 'Clear All Marks?',
            text: "Semua baris yang ditandai akan dikembalikan ke normal.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Clear All!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                clearAllMarks();
                Swal.fire('Cleared!', 'Semua marking telah dihapus.', 'success');
            }
        });
    });

    // Fungsi untuk menghitung judgement berdasarkan determination dan measure_data
    function calc_measure(determination, input) {
        // STEP 1: Normalisasi input - Handle koma dan titik sebagai pemisah desimal
        function normalizeDecimal(value) {
            if (typeof value === 'string') {
                // Replace koma dengan titik untuk parsing yang benar
                value = value.replace(',', '.');
            }
            return parseFloat(value);
        }

        // STEP 2: Normalisasi input measure data
        input = normalizeDecimal(input);
        
        // Validasi input harus berupa angka
        if (isNaN(input)) {
            return 'Data tidak valid';
        }

        // STEP 3: Tangani toleransi ±
        if (determination.includes("±")) {
            // Regex untuk menangkap angka dengan koma atau titik
            const matches = determination.match(/(\d+[,.]?\d*)\s*±\s*(\d+[,.]?\d*)/);
            if (matches) {
                const input_value = normalizeDecimal(matches[1]);
                const tolerance = normalizeDecimal(matches[2]);
                
                if (input === input_value - tolerance || input === input_value + tolerance) {
                    return "Cautious";
                } else if (input >= input_value - tolerance && input <= input_value + tolerance) {
                    return "No Abnormality";
                } else {
                    return "Abnormal";
                }
            }
        }
        
        // STEP 4: Tangani rentang nilai (misalnya: 15 mm - 20 mm atau 0.2 ~ 0.3 Mpa)
        else if (determination.match(/(\d+[,.]?\d*)\s*(mm|Mpa|A|V|°C)?\s*[-~]\s*(\d+[,.]?\d*)/i)) {
            const rangeMatch = determination.match(/(\d+[,.]?\d*)\s*(mm|Mpa|A|V|°C)?\s*[-~]\s*(\d+[,.]?\d*)/i);
            if (rangeMatch) {
                const lower_bound = normalizeDecimal(rangeMatch[1]);
                const upper_bound = normalizeDecimal(rangeMatch[3]);

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
        
        // STEP 5: Tangani batas "Max" dan "Min" secara bersamaan
        else if (determination.toLowerCase().includes("max") && determination.toLowerCase().includes("min")) {
            const minMatch = determination.match(/min\s*(\d+[,.]?\d*)/i);
            const maxMatch = determination.match(/max\s*(\d+[,.]?\d*)/i);
            const min_value = minMatch ? normalizeDecimal(minMatch[1]) : null;
            const max_value = maxMatch ? normalizeDecimal(maxMatch[1]) : null;

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
        
        // STEP 6: Tangani frasa "Tidak kurang" (misalnya: Tidak kurang 1,0 Mpa)
        else if (determination.toLowerCase().includes("tidak kurang")) {
            const minMatch = determination.match(/tidak kurang\s*(\d+[,.]?\d*)/i);
            const min_value = minMatch ? normalizeDecimal(minMatch[1]) : null;

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
        
        // STEP 7: Tangani hanya batas minimum (misalnya: Min 0,5 Kpa)
        else if (determination.toLowerCase().includes("min")) {
            const minMatch = determination.match(/min\s*(\d+[,.]?\d*)/i);
            const min_value = minMatch ? normalizeDecimal(minMatch[1]) : null;

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
        
        // STEP 8: Tangani hanya batas maksimum - FIXED LOGIC
        // Contoh: "Max 0,3 A", "Max 0,4 A", "Max 1,5 V"
        else if (determination.toLowerCase().includes("max")) {
            // Regex yang diperbaiki untuk menangkap angka dengan koma atau titik
            const maxMatch = determination.match(/max\s*(\d+[,.]?\d*)/i);
            const max_value = maxMatch ? normalizeDecimal(maxMatch[1]) : null;

            if (max_value !== null) {
                // LOGIKA YANG BENAR untuk Max value:
                if (input < max_value) {
                    return 'No Abnormality';  // Di bawah max = OK
                } else if (input === max_value) {
                    return 'Cautious';        // Sama dengan max = Hati-hati
                } else {
                    return 'Abnormal';        // Di atas max = Abnormal
                }
            }
        }
        
        // STEP 9: Tangani perbedaan (misalnya: IN dan OUT perbedaan temperatur 50 C)
        else if (determination.toLowerCase().includes("perbedaan")) {
            const diffMatch = determination.match(/perbedaan temperatur\s*(\d+[,.]?\d*)/i);
            const diff_value = diffMatch ? normalizeDecimal(diffMatch[1]) : null;

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
        
        // STEP 10: Fallback untuk determination standard lainnya
        else {
            // Tangani determination yang hanya berisi angka
            const numberMatch = determination.match(/(\d+[,.]?\d*)/);
            if (numberMatch) {
                const standard_value = normalizeDecimal(numberMatch[1]);
                
                if (input < standard_value) {
                    return 'No Abnormality';
                } else if (input === standard_value) {
                    return 'Cautious';
                } else {
                    return 'Abnormal';
                }
            }
        }
        
        // Jika tidak ada kondisi yang terpenuhi
        return 'Data tidak valid';
    }

    // Fungsi getJudgementDisplay yang diperbaiki dengan dukungan Repaired Fix
    function getJudgementDisplay(judgement) {
        if (judgement == 'No Abnormality') {
            return 'O';
        } else if (judgement == 'Cautious') {
            return '<span style="color: orange; font-size: 16px; font-weight: bold;">&#9650;</span>';
        } else if (judgement == 'Abnormal') {
            return '<span class="text-danger">X</span>';
        } else if (judgement == 'Repaired Fix') {
    return '<span class="text-info" style="font-size: 16px; font-weight: bold;">⊘</span>';
        }
        return '';
    }

    // === FUNGSI BARU UNTUK DUKUNGAN SATUAN ===
    
    // Fungsi untuk ekstraksi satuan dari determination standard
    function extractUnit(determinationStandard) {
    if (!determinationStandard) return '';
    
    // Pattern untuk mencari satuan yang umum digunakan - DIPERLUAS UNTUK CELSIUS
    const unitPatterns = [
        // === DERAJAT CELSIUS (SEMUA VARIASI) ===
        { pattern: /(\d+[,.]?\d*)\s*(°C|oC|ºC|degrees?\s*C|celsius)(?!\w)/i, unit: '°C' },
        { pattern: /Max\s*(\d+[,.]?\d*)\s*(°C|oC|ºC|degrees?\s*C|celsius)(?!\w)/i, unit: '°C' },
        { pattern: /Min\s*(\d+[,.]?\d*)\s*(°C|oC|ºC|degrees?\s*C|celsius)(?!\w)/i, unit: '°C' },
        
        // === AMPERE (SEMUA VARIASI) ===
        { pattern: /(\d+[,.]?\d*)\s*(A|Ampere|ampere|amp)(?!\w)/i, unit: 'A' },
        { pattern: /Max\s*(\d+[,.]?\d*)\s*(A|Ampere|ampere|amp)(?!\w)/i, unit: 'A' },
        { pattern: /Min\s*(\d+[,.]?\d*)\s*(A|Ampere|ampere|amp)(?!\w)/i, unit: 'A' },
        
        // === HERTZ ===
        { pattern: /(\d+[,.]?\d*)\s*(Hz|hertz|kHz|kilohertz|MHz|megahertz)(?!\w)/i, unit: function(match) {
            if (/kHz|kilohertz/i.test(match[2])) return 'kHz';
            if (/MHz|megahertz/i.test(match[2])) return 'MHz';
            return 'Hz';
        }},
        { pattern: /Max\s*(\d+[,.]?\d*)\s*(Hz|hertz|kHz|kilohertz|MHz|megahertz)(?!\w)/i, unit: function(match) {
            if (/kHz|kilohertz/i.test(match[2])) return 'kHz';
            if (/MHz|megahertz/i.test(match[2])) return 'MHz';
            return 'Hz';
        }},
        { pattern: /Min\s*(\d+[,.]?\d*)\s*(Hz|hertz|kHz|kilohertz|MHz|megahertz)(?!\w)/i, unit: function(match) {
            if (/kHz|kilohertz/i.test(match[2])) return 'kHz';
            if (/MHz|megahertz/i.test(match[2])) return 'MHz';
            return 'Hz';
        }},
        
        // === VOLT ===
        { pattern: /(\d+[,.]?\d*)\s*(V|volt|volts|voltage)(?!\w)/i, unit: 'V' },
        { pattern: /Max\s*(\d+[,.]?\d*)\s*(V|volt|volts|voltage)(?!\w)/i, unit: 'V' },
        { pattern: /Min\s*(\d+[,.]?\d*)\s*(V|volt|volts|voltage)(?!\w)/i, unit: 'V' },
        
        // === WATT ===
        { pattern: /(\d+[,.]?\d*)\s*(W|watt|watts|kW|kilowatt)(?!\w)/i, unit: function(match) {
            if (/kW|kilowatt/i.test(match[2])) return 'kW';
            return 'W';
        }},
        { pattern: /Max\s*(\d+[,.]?\d*)\s*(W|watt|watts|kW|kilowatt)(?!\w)/i, unit: function(match) {
            if (/kW|kilowatt/i.test(match[2])) return 'kW';
            return 'W';
        }},
        { pattern: /Min\s*(\d+[,.]?\d*)\s*(W|watt|watts|kW|kilowatt)(?!\w)/i, unit: function(match) {
            if (/kW|kilowatt/i.test(match[2])) return 'kW';
            return 'W';
        }},
        
        // === PANJANG ===
        { pattern: /(\d+[,.]?\d*)\s*(mm|millimeter|cm|centimeter|m|meter)(?!\w)/i, unit: function(match) {
            if (/millimeter/i.test(match[2])) return 'mm';
            if (/centimeter/i.test(match[2])) return 'cm';
            if (/\bm\b|meter/i.test(match[2])) return 'm';
            return match[2].toLowerCase();
        }},
        { pattern: /Max\s*(\d+[,.]?\d*)\s*(mm|millimeter|cm|centimeter|m|meter)(?!\w)/i, unit: function(match) {
            if (/millimeter/i.test(match[2])) return 'mm';
            if (/centimeter/i.test(match[2])) return 'cm';
            if (/\bm\b|meter/i.test(match[2])) return 'm';
            return match[2].toLowerCase();
        }},
        { pattern: /Min\s*(\d+[,.]?\d*)\s*(mm|millimeter|cm|centimeter|m|meter)(?!\w)/i, unit: function(match) {
            if (/millimeter/i.test(match[2])) return 'mm';
            if (/centimeter/i.test(match[2])) return 'cm';
            if (/\bm\b|meter/i.test(match[2])) return 'm';
            return match[2].toLowerCase();
        }},
        
        // === BERAT ===
        { pattern: /(\d+[,.]?\d*)\s*(kg|kilogram|g|gram)(?!\w)/i, unit: function(match) {
            if (/kilogram/i.test(match[2])) return 'kg';
            if (/\bg\b|gram/i.test(match[2])) return 'g';
            return match[2].toLowerCase();
        }},
        { pattern: /Max\s*(\d+[,.]?\d*)\s*(kg|kilogram|g|gram)(?!\w)/i, unit: function(match) {
            if (/kilogram/i.test(match[2])) return 'kg';
            if (/\bg\b|gram/i.test(match[2])) return 'g';
            return match[2].toLowerCase();
        }},
        { pattern: /Min\s*(\d+[,.]?\d*)\s*(kg|kilogram|g|gram)(?!\w)/i, unit: function(match) {
            if (/kilogram/i.test(match[2])) return 'kg';
            if (/\bg\b|gram/i.test(match[2])) return 'g';
            return match[2].toLowerCase();
        }},
        
        // === TEKANAN ===
        { pattern: /(\d+[,.]?\d*)\s*(bar|Bar|psi|PSI|Mpa|MPa|mpa|Kpa|KPa|kpa|pascal)(?!\w)/i, unit: function(match) {
            if (/pascal/i.test(match[2])) return 'Pa';
            return match[2];
        }},
        { pattern: /Max\s*(\d+[,.]?\d*)\s*(bar|Bar|psi|PSI|Mpa|MPa|mpa|Kpa|KPa|kpa|pascal)(?!\w)/i, unit: function(match) {
            if (/pascal/i.test(match[2])) return 'Pa';
            return match[2];
        }},
        { pattern: /Min\s*(\d+[,.]?\d*)\s*(bar|Bar|psi|PSI|Mpa|MPa|mpa|Kpa|KPa|kpa|pascal)(?!\w)/i, unit: function(match) {
            if (/pascal/i.test(match[2])) return 'Pa';
            return match[2];
        }},
        
        // === RPM ===
        { pattern: /(\d+[,.]?\d*)\s*(rpm|RPM|revolution)(?!\w)/i, unit: 'rpm' },
        { pattern: /Max\s*(\d+[,.]?\d*)\s*(rpm|RPM|revolution)(?!\w)/i, unit: 'rpm' },
        { pattern: /Min\s*(\d+[,.]?\d*)\s*(rpm|RPM|revolution)(?!\w)/i, unit: 'rpm' },
        
        // === MILLI AMPERE ===
        { pattern: /(\d+[,.]?\d*)\s*(mA|milliampere|microA|μA)(?!\w)/i, unit: function(match) {
            if (/milliampere/i.test(match[2])) return 'mA';
            if (/microA|μA/i.test(match[2])) return 'μA';
            return match[2];
        }},
        { pattern: /Max\s*(\d+[,.]?\d*)\s*(mA|milliampere|microA|μA)(?!\w)/i, unit: function(match) {
            if (/milliampere/i.test(match[2])) return 'mA';
            if (/microA|μA/i.test(match[2])) return 'μA';
            return match[2];
        }},
        { pattern: /Min\s*(\d+[,.]?\d*)\s*(mA|milliampere|microA|μA)(?!\w)/i, unit: function(match) {
            if (/milliampere/i.test(match[2])) return 'mA';
            if (/microA|μA/i.test(match[2])) return 'μA';
            return match[2];
        }},
        
        // === VOLUME ===
        { pattern: /(\d+[,.]?\d*)\s*(L|liter|litre|ml|milliliter|cc)(?!\w)/i, unit: function(match) {
            if (/liter|litre/i.test(match[2])) return 'L';
            if (/milliliter/i.test(match[2])) return 'ml';
            return match[2].toLowerCase();
        }},
        { pattern: /Max\s*(\d+[,.]?\d*)\s*(L|liter|litre|ml|milliliter|cc)(?!\w)/i, unit: function(match) {
            if (/liter|litre/i.test(match[2])) return 'L';
            if (/milliliter/i.test(match[2])) return 'ml';
            return match[2].toLowerCase();
        }},
        { pattern: /Min\s*(\d+[,.]?\d*)\s*(L|liter|litre|ml|milliliter|cc)(?!\w)/i, unit: function(match) {
            if (/liter|litre/i.test(match[2])) return 'L';
            if (/milliliter/i.test(match[2])) return 'ml';
            return match[2].toLowerCase();
        }}
    ];
    
    for (let i = 0; i < unitPatterns.length; i++) {
        const match = determinationStandard.match(unitPatterns[i].pattern);
        if (match) {
            if (typeof unitPatterns[i].unit === 'function') {
                return unitPatterns[i].unit(match);
            } else {
                return unitPatterns[i].unit;
            }
        }
    }
    
    return '';
}

    // Fungsi untuk menambahkan satuan pada measure data
    function addUnitToMeasureData(measureData, determinationStandard) {
        if (!measureData || isNaN(measureData)) {
            return measureData;
        }
        
        const unit = extractUnit(determinationStandard);
        if (unit) {
            return measureData + ' ' + unit;
        }
        
        return measureData;
    }

    // Fungsi untuk update unit display
    function updateUnitDisplay(id, input, determination) {
        const unitDisplay = $('#unit-display-' + id);
        
        if (input && !isNaN(input)) {
            const unit = extractUnit(determination);
            if (unit) {
                unitDisplay.html('(' + input + ' ' + unit + ')').show();
            } else {
                unitDisplay.hide();
            }
        } else {
            unitDisplay.hide();
        }
    }

    // Fungsi untuk inisialisasi unit display saat halaman dimuat
    function initializeUnitDisplay() {
        $('.measure-input').each(function() {
            const input = $(this).val();
            const determination = $(this).data('determination');
            const id = $(this).data('id');
            
            updateUnitDisplay(id, input, determination);
            
            if (input && !isNaN(input)) {
                const inputWithUnit = addUnitToMeasureData(input, determination);
                $(this).attr('title', 'Nilai dengan satuan: ' + inputWithUnit);
            }
        });
    }

});

    // New functions for marking functionality
    function toggleMark(rowId) {
        // Only admin can toggle marks, but all users can see the visual result
        const row = $('#row-' + rowId);
        const markBtn = $('#mark-btn-' + rowId);
        
        if (row.hasClass('marked-row')) {
            // Remove mark
            row.removeClass('marked-row');
            if (markBtn.length) {
                markBtn.removeClass('marked').addClass('unmarked');
                markBtn.text('Mark');
            }
            removeMarkedRow(rowId);
        } else {
            // Add mark
            row.addClass('marked-row');
            if (markBtn.length) {
                markBtn.removeClass('unmarked').addClass('marked');
                markBtn.text('Unmark');
            }
            saveMarkedRow(rowId);
        }
    }

    function saveMarkedRow(rowId) {
        let markedRows = JSON.parse(localStorage.getItem('markedRows') || '[]');
        if (!markedRows.includes(rowId)) {
            markedRows.push(rowId);
            localStorage.setItem('markedRows', JSON.stringify(markedRows));
        }
    }

    function removeMarkedRow(rowId) {
        let markedRows = JSON.parse(localStorage.getItem('markedRows') || '[]');
        markedRows = markedRows.filter(id => id !== rowId);
        localStorage.setItem('markedRows', JSON.stringify(markedRows));
    }

    function loadMarkedRows() {
        const markedRows = JSON.parse(localStorage.getItem('markedRows') || '[]');
        markedRows.forEach(rowId => {
            const row = $('#row-' + rowId);
            if (row.length) {
                // Apply marking visual for ALL users
                row.addClass('marked-row');
                
                // Update button state only if button exists (admin only)
                const markBtn = $('#mark-btn-' + rowId);
                if (markBtn.length) {
                    markBtn.removeClass('unmarked').addClass('marked');
                    markBtn.text('Unmark');
                }
            }
        });
    }

    function clearAllMarks() {
        // Remove visual marking from all rows (visible to all users)
        $('.marked-row').removeClass('marked-row');
        // Update button states only if buttons exist (admin only)
        $('.mark-btn.marked').removeClass('marked').addClass('unmarked').text('Mark');
        // Clear localStorage
        localStorage.removeItem('markedRows');
    }
</script>
