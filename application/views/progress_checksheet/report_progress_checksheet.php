 <div class="table-responsive">
     <table class="table table-bordered table-striped table-hover">
         <thead>
             <tr>
                 <th>Section</th>
                 <th>Nama Mesin</th>
                 <th>No</th>
                 <th>Cycle (Month)</th>
                 <th>Document No</th>
                 <th>Equipment No</th>
                 <th>M or E or U</th>
                 <th>Inspection Date</th>
                 <th>Inspector</th>
                 <th>SPV</th>
                 <th>MGR</th>
             </tr>
         </thead>
         <tbody>
             <?php foreach ($data as $row) : ?>
                 <tr>
                     <td><?= $row['section_name']; ?></td>
                     <td><?= $row['machine_name']; ?></td>
                     <td>
                         <?php
                            //get number in machine name
                            $number = preg_replace('/[^0-9]/', '', $row['machine_name']);
                            echo $number;
                            ?>
                     </td>
                     <td><?= $row['cycle']; ?></td>
                     <td><?= $row['document_no']; ?></td>
                     <td><?= $row['equipment_no']; ?></td>
                     <td><?= substr($this->M_master->getUserId($row['user_id'])['divisi'], 0, 1); ?></td>
                     <td><?= $row['tgl_checksheet']; ?></td>
                     <td><?= $this->M_master->getUserId($row['user_id'])['nama']; ?></td>
                     <td>
                         <?php
                            if ($row['step_proses'] == '3' || $row['step_proses'] == '4') {
                                echo 'O';
                            }
                            ?>
                     </td>
                     <td>
                         <?php
                            if ($row['step_proses'] == '4') {
                                echo 'O';
                            }
                            ?>
                     </td>
                 </tr>
             <?php endforeach; ?>
         </tbody>
     </table>
 </div>
 <script>
     $('.table').DataTable({
         //export excel with title
         dom: 'Bfrtip',
         buttons: [{
             extend: 'excel',
             title: 'INSPECTION Progress Check Sheet',
             //add class to button
             className: 'btn btn-success',
             // add sub title in excel
             customize: function(xlsx) {
                 var sheet = xlsx.xl.worksheets['sheet1.xml'];
                 // how to add row before row number 2 using insert before
                 $('row c[r^="A1"]', sheet).each(function() {
                     //get the value of the row
                     var r = $('row', sheet).length;
                     //add row before row number 2
                     $(this).attr('r', r);
                 });

             }
         }]
     });
 </script>