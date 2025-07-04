<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipment Inspection Sheet - Print</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0.2in; /* Margin dikurangi dari 0.3in menjadi 0.2in */
        }
        
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
                font-size: 9px; /* Sesuaikan dengan body font size */
            }
            
            .no-print {
                display: none !important;
            }
            
            .print-container {
                width: 100%;
                height: 100vh;
                overflow: hidden;
            }
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 9px; /* Dikurangi sedikit dari 10px menjadi 9px */
            margin: 0;
            padding: 3px; /* Dikurangi dari 5px menjadi 3px */
            color: #000;
            line-height: 1.1; /* Dikurangi sedikit untuk menghemat ruang */
        }
        
        .print-container {
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
            overflow: hidden; /* Mencegah overflow horizontal */
        }
        
        /* Header Section - Kompak */
        .header-section {
            margin-bottom: 5px; /* Dikurangi untuk spacing yang lebih rapat */
        }
        
        .main-title {
            font-size: 15px; /* Dikurangi dari 16px menjadi 15px */
            font-weight: bold;
            text-decoration: underline;
            font-style: italic;
            text-align: left;
            margin-bottom: 6px;
        }
        
        /* Top section dengan layout yang dimodifikasi */
        .top-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 5px;
            position: relative;
            width: 100%;
            max-width: 100%;
            min-height: 48px; /* Diperbesar dari 42px untuk menampung font yang lebih besar */
        }
        
        .equipment-info {
            flex: 0 0 auto;
            width: auto;
            max-width: none;
            margin-top: 25px; /* Turunkan equipment info ke bawah */
            margin-bottom: 0px; /* Hilangkan margin bawah */
        }
        
        .inspection-info {
            position: absolute;
            right: 0;
            top: 0px;
            width: 320px; /* Dikurangi dari 350px menjadi 320px */
            margin-left: auto;
            padding-right: 0;
            padding-bottom: 0px;
        }
        
        /* PERBAIKAN UTAMA: Right side info dengan struktur table yang rapi */
        .right-info {
            font-size: 9px;
            line-height: 1.1;
            width: 100%;
            display: block;
        }
        
        .right-info-table {
            width: 320px; /* Sesuaikan dengan parent width */
            border-collapse: collapse;
            font-size: 9px; /* Dikurangi dari 10px menjadi 9px */
            margin-left: 0;
            table-layout: fixed;
        }
        
        .right-info-table td {
            padding: 2px 2px; /* Padding dikurangi sedikit */
            vertical-align: top;
            border: none;
        }
        
        .right-info-label {
            width: 110px; /* Dikurangi sedikit */
            font-weight: bold;
            text-align: right;
            padding-right: 6px; /* Padding dikurangi */
            white-space: nowrap;
        }
        
        .right-info-content {
            width: 210px; /* Disesuaikan dengan total width */
            text-align: left;
            padding-right: 0;
        }
        
        .bordered-line {
            border-bottom: 1px solid #000;
            display: block;
            width: 100%;
            max-width: 100%; /* Pastikan tidak melebihi container */
            padding-bottom: 1px;
            text-align: left;
            margin-right: 0;
            box-sizing: border-box;
            overflow: hidden; /* Cegah overflow text */
            white-space: nowrap; /* Jangan wrap text */
            text-overflow: ellipsis; /* Tampilkan ... jika terlalu panjang */
        }
        
        /* Judgement List - disesuaikan untuk alignment yang lebih baik */
        .judgement-list {
            display: flex;
            flex-direction: column;
            gap: 1px; /* Dikurangi dari 2px */
            margin-top: 2px;
            margin-bottom: 0px;
            align-items: flex-start;
            text-align: left;
            min-height: 28px; /* Dikurangi dari 30px */
        }
        
        .judgement-list div {
            display: flex;
            align-items: center;
            font-size: 8px; /* Dikurangi dari 9px menjadi 8px */
            line-height: 1.1; /* Dikurangi dari 1.2 */
        }
        
        .judgement-symbol {
            width: 12px; /* Dikurangi dari 14px */
            text-align: center;
            margin-right: 4px;
            font-weight: bold;
            font-size: 9px; /* Dikurangi dari 10px menjadi 9px */
        }
        
        .judgement-text {
            font-size: 8px; /* Dikurangi dari 9px menjadi 8px */
        }
        
        /* Left side info table - diperbesar untuk readability */
        .left-info-table {
            border-collapse: collapse;
            width: 100%;
            font-size: 11px; /* Diperbesar dari 9px menjadi 11px */
            margin-bottom: 0px;
        }
        
        .left-info-table td {
            border: 1px solid #000;
            padding: 3px 5px; /* Padding diperbesar untuk menampung font yang lebih besar */
            font-weight: bold;
        }
        
        .info-label {
            background-color: #B4C6E7;
            width: 100px;
            font-size: 10px; /* Diperbesar dari 8px menjadi 10px */
        }
        
        .info-value {
            background-color: #B4C6E7;
            min-width: 120px;
            font-size: 10px; /* Diperbesar dari 8px menjadi 10px */
        }
        
        .info-label-2 {
            background-color: #B4C6E7;
            width: 80px;
            font-size: 10px; /* Diperbesar dari 8px menjadi 10px */
        }
        
        .info-value-2 {
            background-color: #B4C6E7;
            min-width: 80px;
            font-size: 10px; /* Diperbesar dari 8px menjadi 10px */
        }
        
        /* Table Section - dioptimalkan untuk 1 halaman */
        .table-container {
            margin: 0px 0 5px 0;
            clear: both;
            flex: 1;
            width: 100%;
            max-width: 100%;
            overflow: hidden; /* Cegah overflow horizontal */
        }
        
        .print-table {
            width: 100%;
            max-width: 100%; /* Pastikan tidak melebihi container */
            border-collapse: collapse;
            font-size: 8px;
            table-layout: fixed; /* Tetap menggunakan fixed untuk kontrol width yang tepat */
            margin-top: -2px;
            box-sizing: border-box; /* Pastikan border tidak menambah lebar total */
        }
        
        .print-table th,
        .print-table td {
            border: 1px solid #000;
            padding: 1px 2px;
            text-align: left;
            vertical-align: top;
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: normal; /* Pastikan teks bisa wrap */
            overflow: hidden; /* Cegah overflow dari cell */
        }
        
        .print-table th {
            background-color: #B4C6E7;
            font-weight: bold;
            text-align: center;
            font-size: 10px; /* Diperbesar dari 8px menjadi 10px */
            padding: 3px 2px; /* Padding diperbesar untuk menampung font yang lebih besar */
            height: 30px; /* Height diperbesar untuk menampung font yang lebih besar */
            border-top: 2px solid #000;
        }
        
        .print-table td {
            font-size: 8px; /* Dikurangi dari 9px menjadi 8px */
            line-height: 1.0; /* Line height dikurangi */
            max-height: 20px; /* Height dikurangi */
        }
        
        /* Column widths - dioptimalkan untuk menghindari pemotongan */
        .col-part { width: 9%; }
        .col-inspection { width: 13%; }
        .col-item { width: 25%; }
        .col-method { width: 15%; }
        .col-standard { width: 14%; }
        .col-measure-data { width: 7%; }
        .col-judgement { width: 7%; }
        .col-measure { width: 10%; }
        
        /* Bottom section - hanya untuk bagian yang diperlukan - diperkecil */
        .bottom-section {
            display: flex;
            justify-content: flex-end;
            margin-top: 2px; /* DIKURANGI: dari 5px menjadi 2px */
            gap: 8px; /* DIKURANGI: dari 12px menjadi 8px */
            width: 100%;
            max-width: 100%;
        }
        
        .bottom-left {
            flex: 1;
            max-width: 35%; /* DIKURANGI: dari 42% menjadi 35% */
        }
        
        /* Additional sections - diperkecil untuk menghemat ruang */
        .additional-section {
            font-size: 8px; /* DIKURANGI: dari 9px menjadi 8px */
            margin-bottom: 3px; /* DIKURANGI: dari 5px menjadi 3px */
        }
        
        /* MODIFIKASI: Header dengan garis diperpendek - diperkecil */
        .additional-header {
            font-weight: bold;
            /* Hapus border-bottom yang lama */
            /* border-bottom: 1px solid #000; */
            margin-bottom: 1px; /* DIKURANGI: dari 2px menjadi 1px */
            padding-bottom: 1px; /* DIKURANGI: dari 2px menjadi 1px */
            font-size: 9px; /* DIKURANGI: dari 11px menjadi 9px */
            position: relative; /* Diperlukan untuk pseudo-element */
        }
        
        /* Tambahkan garis pendek untuk header */
        .additional-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50%; /* Garis header hanya 50% dari lebar total */
            height: 1px;
            background-color: #000;
            border: none;
        }
        
        .bullet-list {
            margin: 1px 0;
            padding-left: 0;
        }
        
        /* PERBAIKAN: Bullet item dengan garis sama panjang seperti header (50%) - diperkecil */
        .bullet-item {
            /* Hapus border-bottom yang lama */
            /* border-bottom: 1px solid #000; */
            padding: 1px 0; /* DIKURANGI: dari 2px menjadi 1px */
            margin-bottom: 1px;
            min-height: 10px; /* DIKURANGI: dari 12px menjadi 10px */
            font-size: 8px; /* DIKURANGI: dari 9px menjadi 8px */
            position: relative; /* Diperlukan untuk pseudo-element */
        }       
        /* PERBAIKAN: Garis pendek sama dengan header (50%) */
        .bullet-item::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50%; /* DIUBAH: Sama dengan header - dari 65% menjadi 50% */
            height: 1px;
            background-color: #000;
            border: none;
        }        
        /* MODIFIKASI UTAMA: Signature section - diperkecil untuk muat 1 halaman */
        .signature-section {
            margin-top: 1px; /* DIKURANGI: dari 3px menjadi 1px */
            width: 160px; /* DIKURANGI LAGI: dari 190px menjadi 160px */
             border: 1px solid #000;
            font-size: 6px; /* DIKURANGI: dari 7px menjadi 6px */
            margin-left: auto;
        }
        
        .signature-header {
            display: flex;
            border-bottom: 1px solid #000;
        }
        
        .signature-header-cell {
            flex: 1;
            padding: 1px; /* Tetap 1px */
            text-align: center;
            font-weight: bold;
            border-right: 1px solid #000;
            font-size: 7px; /* Sedikit diperbesar untuk readability */
            min-height: 12px;
        }
        
        .signature-header-cell:last-child {
            border-right: none;
        }
        
        .signature-body {
            display: flex;
           /* border-bottom: 1px solid #000; */
        }
        
        .signature-cell {
             flex: 1; /* Menggunakan flex untuk pembagian yang rata */
            padding: 2px 1px;
            text-align: center;
            border-right: 1px solid #000; /* Border kanan antar kolom */
            vertical-align: middle;
            position: relative;
            height: 50px;
            min-height: 45px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end; /* Nama akan berada di bawah */
        }
        
        .signature-cell:last-child {
            border-right: none;
        }
        
        .signature-image {
            max-width: 15px; /* DIKURANGI: dari 20px menjadi 15px */
            max-height: 8px; /* DIKURANGI: dari 10px menjadi 8px */
            margin: 1px auto;
        }
        
        /* MODIFIKASI: Sembunyikan image untuk inspector dan supervisor */
        .signature-cell:nth-child(1) .signature-image,
        .signature-cell:nth-child(2) .signature-image { 
            display: none; /* Sembunyikan image untuk inspector dan supervisor */
        }
        
        .signature-name {
            font-size: 6px;
            margin-top: auto; /* Push ke bawah */
            padding-top: 2px;
            border-top: 1px solid #000; /* Garis pemisah untuk nama */
            background-color: white;
        }
        
        .print-button {
            position: fixed;
            top: 10px;
            right: 10px;
            z-index: 1000;
            padding: 8px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }
        
        .print-button:hover {
            background-color: #0056b3;
        }
        
        /* Judgement symbols styling with color */
        .judgement-triangle {
            color: #ffc107;
            font-weight: bold;
        }
        
        .judgement-x {
            color: #dc3545;
            font-weight: bold;
        }
        
        .judgement-circle {
            color: #28a745;
            font-weight: bold;
        }
        
        .judgement-o {
            color: #000;
            font-weight: bold;
        }
        
        /* Compact spacing untuk seluruh halaman */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box; /* Pastikan border tidak menambah ukuran total */
        }
        
        table {
            border-spacing: 0;
        }
        
        /* Responsif untuk berbagai ukuran konten */
        .flexible-container {
            display: flex;
            flex-direction: column;
            height: 100vh;
            max-height: 100vh;
            width: 100%;
            max-width: 100vw; /* Pastikan tidak melebihi viewport width */
            overflow: hidden; /* Mencegah scrollbar */
        }
        
        /* Content area yang fleksibel */
        .content-area {
            flex: 1;
            overflow: hidden;
            width: 100%;
            max-width: 100%;
        }
        
        .footer-area {
            flex-shrink: 0;
            width: 100%;
            max-width: 100%;
        }
    </style>
</head>
<body>
    <button class="print-button no-print" onclick="window.print()">🖨️ Print</button>
    
    <div class="print-container flexible-container">
        <!-- Header Section -->
        <div class="header-section">
            <div class="main-title">Equipment Inspection Sheet</div>
            
            <!-- Top Section dengan layout yang dioptimalkan -->
            <div class="top-section">
                <!-- Equipment Info Left -->
                <div class="equipment-info">
                    <table class="left-info-table">
                        <tr>
                            <td class="info-label">Rank</td>
                            <td class="info-value"><?= $eq['rank']; ?></td>
                        </tr>
                        <tr>
                            <td class="info-label">Section</td>
                            <td class="info-value"><?= $eq['section_name']; ?></td>
                        </tr>
                        <tr>
                            <td class="info-label">Machine Name</td>
                            <td class="info-value"><?= $eq['machine_name']; ?></td>
                            <td class="info-label-2">Equipment No</td>
                            <td class="info-value-2"><?= $eq['equipment_no']; ?></td>
                            <td class="info-label-2">Cycle</td>
                            <td class="info-value-2"><?= $eq['cycle']; ?></td>
                        </tr>
                    </table>
                </div>
                
                <!-- PERBAIKAN: Inspection Info Right dengan struktur table yang rapi -->
                <div class="inspection-info">
                    <div class="right-info">
                        <table class="right-info-table">
                            <tr>
                                <td class="right-info-label">Inspection Day:</td>
                                <td class="right-info-content">
                                    <span class="bordered-line"><?= date('d F Y', strtotime($eq['tgl_checksheet'])); ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="right-info-label">Inspector:</td>
                                <td class="right-info-content">
                                    <span class="bordered-line"><?= $eq['nama']; ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="right-info-label">Judgement:</td>
                                <td class="right-info-content">
                                    <div class="judgement-list">
                                        <div>
                                            <span class="judgement-symbol judgement-o">O</span>
                                            <span class="judgement-text">: No Abnormality</span>
                                        </div>
                                        <div>
                                            <span class="judgement-symbol judgement-triangle">▲</span>
                                            <span class="judgement-text">: Cautious</span>
                                        </div>
                                        <div>
                                            <span class="judgement-symbol judgement-x">X</span>
                                            <span class="judgement-text">: Abnormal</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Table Section - Area konten utama -->
        <div class="content-area">
            <div class="table-container">
                <?php
                // Generate table dengan format yang dioptimalkan
                $data = $checksheet_data;
                
                if (!empty($data)) {
                    // Initialize rowspan arrays
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
                    
                    echo '<table class="print-table">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th class="col-part">Part</th>';
                    echo '<th class="col-inspection">Inspection Part</th>';
                    echo '<th class="col-item">Item</th>';
                    echo '<th class="col-method">Method</th>';
                    echo '<th class="col-standard">Determination Standard</th>';
                    echo '<th class="col-measure-data">Measure Data</th>';
                    echo '<th class="col-judgement">Judgement</th>';
                    echo '<th class="col-measure">Measure</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    
                    $printedParts = array(); // To keep track of printed parts
                    
                    foreach ($data as $row) {
                        $part_id = $row['part_id'];
                        $inspection_part_id = $row['inspection_part_id'];
                        
                        echo '<tr>';
                        
                        // Part column with rowspan
                        if (!in_array($part_id, $printedParts)) {
                            echo '<td rowspan="' . $partRowspan[$part_id] . '">' . htmlspecialchars($row['part']) . '</td>';
                            $printedParts[] = $part_id;
                        }
                        
                        // Inspection Part column with rowspan
                        if ($inspectionPartRowspan[$part_id][$inspection_part_id] !== false) {
                            echo '<td rowspan="' . $inspectionPartRowspan[$part_id][$inspection_part_id] . '">' . htmlspecialchars($row['inspection_part']) . '</td>';
                            $inspectionPartRowspan[$part_id][$inspection_part_id] = false; // Skip rowspan
                        }
                        
                        echo '<td>' . htmlspecialchars($row['item']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['method']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['determination_standard']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['measure_data']) . '</td>';
                        
                        // Format judgement display
                        $judgement_display = '';
                        if ($row['judgement'] == 'No Abnormality') {
                            $judgement_display = 'O';
                        } elseif ($row['judgement'] == 'Cautious') {
                            $judgement_display = '<span class="judgement-triangle">▲</span>';
                        } elseif ($row['judgement'] == 'Abnormal') {
                            $judgement_display = '<span class="judgement-x">X</span>';
                        } elseif ($row['judgement'] == 'Repaired Fix') {
                            $judgement_display = '<span class="judgement-circle">⊗</span>';
                        }
                        echo '<td>' . $judgement_display . '</td>';
                        
                        echo '<td>' . htmlspecialchars($row['measure']) . '</td>';
                        echo '</tr>';
                    }
                    
                    echo '</tbody>';
                    echo '</table>';
                }
                ?>
            </div>
        </div>
        
        <!-- Footer Area -->
        <div class="footer-area">
            <!-- Bottom Section -->
            <div class="bottom-section">
                <!-- Additional Information Section -->
                <div class="bottom-left">
                    <div class="additional-section">
                        <div class="additional-header">Inspection item of addition</div>
                        <div class="bullet-list">
                            <?php
                            if (!empty($additional_item)) {
                                $inspection_lines = explode("\n", $additional_item);
                                for ($i = 0; $i < 3; $i++) {
                                    $content = isset($inspection_lines[$i]) ? "• " . trim($inspection_lines[$i]) : "•";
                                    echo '<div class="bullet-item">' . htmlspecialchars($content) . '</div>';
                                }
                            } else {
                                for ($i = 0; $i < 3; $i++) {
                                    echo '<div class="bullet-item">•</div>';
                                }
                            }
                            ?>
                        </div>
                        
                        <div class="additional-header" style="margin-top: 3px;">Purchase a necessary part</div>
                        <div class="bullet-list">
                            <?php
                            if (!empty($purchase_part)) {
                                $purchase_lines = explode("\n", $purchase_part);
                                for ($i = 0; $i < 3; $i++) {
                                    $content = isset($purchase_lines[$i]) ? "• " . trim($purchase_lines[$i]) : "•";
                                    echo '<div class="bullet-item">' . htmlspecialchars($content) . '</div>';
                                }
                            } else {
                                for ($i = 0; $i < 3; $i++) {
                                    echo '<div class="bullet-item">•</div>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
                
                <!-- Signature Section -->
                <div class="signature-section">
                    <div class="signature-header">
                        <div class="signature-header-cell">Inspector</div>
                        <div class="signature-header-cell">Supervisor</div>
                        <div class="signature-header-cell">Manager</div>
                    </div>
                    <div class="signature-body">
                        <div class="signature-cell">
                            <div class="signature-name"><?= $ttd['nama_inspector'] ?? ''; ?></div>
                        </div>
                        <div class="signature-cell">
                            <div class="signature-name"><?= $ttd['nama_supervisor'] ?? ''; ?></div>
                        </div>
                        <div class="signature-cell">
                            <div class="signature-name"><?= $ttd['nama_manager'] ?? ''; ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Auto-focus print dialog ketika halaman dimuat
        window.addEventListener('load', function() {
            // Delay kecil untuk memastikan halaman sudah dimuat sepenuhnya
            setTimeout(function() {
                // Optional: Auto-buka dialog print ketika halaman dimuat
                // window.print();
            }, 500);
        });
        
        // Handle klik tombol print
        function printPage() {
            window.print();
        }
        
        // Tutup window setelah print (optional)
        window.addEventListener('afterprint', function() {
            // Optional: Tutup window setelah printing
            // window.close();
        });
        
        // Optimasi untuk print - pastikan konten muat dalam 1 halaman
        window.addEventListener('beforeprint', function() {
            // Adjust ukuran font jika konten terlalu panjang
            const table = document.querySelector('.print-table');
            const container = document.querySelector('.print-container');
            
            if (table && container) {
                const tableHeight = table.offsetHeight;
                const containerHeight = container.offsetHeight;
                
                // Jika tabel terlalu tinggi, kurangi ukuran font
                if (tableHeight > containerHeight * 0.75) {
                    document.body.style.fontSize = '8px'; /* Dari 9px menjadi 8px */
                    table.style.fontSize = '7px'; /* Dari 8px menjadi 7px */
                }
            }
        });
    </script>
</body>
</html>