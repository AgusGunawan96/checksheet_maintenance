  <?php
    // Initialize rowspan arrays
    // $id = 19;
    // $data = $this->M_checksheet->getDetail($id);
    $partRowspan = array();
    $inspectionPartRowspan = array();

    // Count rowspan for parts and inspection parts
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
    echo '<tr><th>Part</th><th>Inspection Part</th><th>Item</th><th>Method</th><th>Determination Standard</th><th>Measure Data</th><th>Judgement</th><th>Measure</th></tr>';
    // $no = 1;
    $printedParts = array(); // To keep track of printed parts
    foreach ($data as $row) {
        $part_id = $row['part_id'];
        $inspection_part_id = $row['inspection_part_id'];

        echo '<tr>';
        // echo '<td>' . $no++ . '</td>';
        // Output Part column with rowspan
        if (!in_array($part_id, $printedParts)) {
            echo '<td rowspan="' . $partRowspan[$part_id] . '">' . $row['part'] . '</td>';
            $printedParts[] = $part_id;
        }

        // Output Inspection Part column with rowspan
        if ($inspectionPartRowspan[$part_id][$inspection_part_id] !== false) {
            echo '<td rowspan="' . $inspectionPartRowspan[$part_id][$inspection_part_id] . '">' . $row['inspection_part'] . '</td>';
            $inspectionPartRowspan[$part_id][$inspection_part_id] = false; // Set to false to skip rowspan for subsequent rows with the same part_id and inspection_part_id
        }

        echo '<td>' . $row['item'] . '</td>';
        echo '<td>' . $row['method'] . '</td>';
        echo '<td>' . $row['determination_standard'] . '</td>';
        if ($row['measure_data'] == 'Abnormal' || $row['measure_data'] == 'Cautious' || $row['measure_data'] == 'No Abnormality') {
            echo '<td>' . '' . '</td>';
        } else {
            echo '<td>' . $row['measure_data'] . '</td>';
        }
        echo '<td>';
        if ($row['judgement'] == 'No Abnormality') {
            echo 'O';
        } else if ($row['judgement'] == 'Cautious') {
            echo '<span class="text-warning">&#955;</span>';
        } else if ($row['judgement'] == 'Abnormal') {
            echo '<span class="text-danger">X</span>';
        }
        echo '</td>';
        echo '<td>' . $row['measure'] . '</td>';
        echo '</tr>';
    }

    echo '</table>';
    ?>