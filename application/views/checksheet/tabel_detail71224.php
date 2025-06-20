<div class="table-responsive" style="height: 400px !important">
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
    echo '<table class="table table-bordered table-sm">';
    echo '<tr><th>Part</th><th>Inspection Part</th><th>Item</th><th>Method</th><th>Determination Standard</th><th>Measure Data</th><th>Judgement</th><th>Aksi</th></tr>';
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
        echo '<td><input type="text" class="form-control measure-input" data-determination="' . htmlspecialchars($row['determination_standard']) . '" value="' . $row['measure_data'] . '" ' . ($is_text ? 'disabled' : '') . '></td>';

        // Kolom Judgement
        echo '<td class="judgement-cell">'; 
        if ($is_text) {
            // Dropdown for judgement if determination standard is text
            echo '<select class="form-control judgement-select">';
            echo '<option value="No Abnormality">O</option>'; // Symbol for No Abnormality
            echo '<option value="Cautious">&#955;</option>'; // Symbol for Cautious
            echo '<option value="Abnormal">X</option>'; // Symbol for Abnormal
            echo '</select>';
        } else {
            // Judgement logic
            if ($row['judgement'] == 'No Abnormality') {
                echo 'O';
            } else if ($row['judgement'] == 'Cautious') {
                echo '<span class="text-warning">&#955;</span>';
            } else if ($row['judgement'] == 'Abnormal') {
                echo '<span class="text-danger">X</span>';
            }
        }
        echo '</td>';

        echo '<td><a href="javascript:void(0)" onclick="detailPart(' . $row['id'] . ', ' . $section_id . ', ' . $machine_id . ', ' . $no . ', ' . $jumlah . ')">Detail</a>';
        if ($this->session->userdata('level') == 1) {
            echo ' | <a href="javascript:void(0)" onclick="deletePart(' . $row['id'] . ')">Delete</a>';
        }
        echo '</td>';

        echo '</tr>';
        $no++;
    }

    echo '</table>';
    ?>
</div>

<div class="text-right">
    <button id="save-button" class="btn btn-primary">Save</button>
</div>

<script>
    $(document).ready(function() {
        // Event listener for input measure data
        $('.measure-input').on('input', function() {
            var input = $(this).val();
            var determination = $(this).data('determination');
            var judgement = calc_measure(determination, input);

            // Update judgement cell
            $(this).closest('tr').find('.judgement-cell').html(getJudgementDisplay(judgement));
        });

        $('#save-button').on('click', function() {
            var measureDataArray = [];

            $('.measure-input').each(function() {
                var inputVal = $(this).val();
                var determination = $(this).data('determination');
                var rowId = $(this).closest('tr').find('td:first').text(); // Misalkan ID ada di kolom pertama

                // Menyimpan data ke array
                measureDataArray.push({
                    id: rowId,
                    measure_data: inputVal,
                    determination: determination
                });
            });

            // Tampilkan pesan sukses
            alert('Data measure berhasil disimpan:\n' + JSON.stringify(measureDataArray, null, 2));
            // Anda dapat menambahkan logika lain di sini jika diperlukan
        });
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
                return '<span class="text-warning">&#955;</span>';
            } else if (judgement == 'Abnormal') {
                return '<span class="text-danger">X</span>';
            }
            return '';
        }
    });
</script>
