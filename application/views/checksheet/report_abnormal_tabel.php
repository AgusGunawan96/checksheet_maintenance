 <div class="table-responsive">
     <table class="table table-bordered table-striped table-hover">
         <thead>
             <tr>
                 <th>No</th>
                 <th>Nama Mesin</th>
                 <th>Kejanggalan</th>
                 <th>Tindakan</th>
                 <th>PIC</th>
                 <th>Status</th>
                 <th>Aksi</th>
             </tr>
         </thead>
         <tbody>
             <?php $no = 1; ?>
             <?php foreach ($data as $row) : ?>
                 <tr>
                     <td><?= $no++; ?></td>
                     <td><?= $row['nama_mesin']; ?></td>
                     <td><?= $row['kejanggalan']; ?></td>
                     <td><?= $row['tindakan']; ?></td>
                     <td><?= $this->M_master->getUserId($row['user_id'])['nama']; ?></td>
                     <td><?= $row['status']; ?></td>
                     <td>
                         <a href="javascript:;" class="btn btn-info" onclick="reportEdit('<?= $row['id']; ?>')">Edit</a>
                     </td>
                 </tr>
             <?php endforeach; ?>
         </tbody>
     </table>
 </div>