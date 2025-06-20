<table class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Mesin</th>
            <th>Kejanggalan</th>
            <th>Tindakan</th>
            <th>PIC</th>
            <th>Status</th>
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
                <td><?= $row['user_id']; ?></td>
                <td><?= $row['status']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>