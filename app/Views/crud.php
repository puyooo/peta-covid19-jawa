<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold" id="editModalLabel">Tambah / Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEdit" action="<?= site_url('crud/savedata'); ?>" method="post">
                <div class="modal-body">
                    <input id="id" class="reset" name="id" type="hidden">
                    <div class="form-group">
                        <label for="nama_provinsi" class="form-l">Nama Provinsi</label>
                        <input id="nama-provinsi" type="text" name="nama_provinsi" class="form-control" maxlength="30" required="">
                    </div>

                    <div class="form-group">
                        <label for="polygon" class="form-l">Polygon</label>
                        <input id="polygon" type="text" name="polygon" class="form-control" required="">
                    </div>

                    <div class="form-group">
                        <label for="posisi-popup" class="form-l">Posisi Popup</label>
                        <input id="posisi-popup" type="text" name="posisi_popup" class="form-control" maxlength="50" required="">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="content" class="container-fluid">
    <h1 class="text-center mt-4">CRUD</h1>

    <button type="button" class="btn btn-success my-3 font-weight-bold" data-toggle="modal" data-target="#editModal" data-id="">Tambah</button>

    <!-- Tabel data -->
    <?php $no = 1; ?>
    <table class="table table-bordered table-striped table-hover" style="width:100%;">
        <thead style="white-space: nowrap;">
            <tr>
                <th>No</th>
                <th>Nama Provinsi</th>
                <th>Polygon</th>
                <th>Posisi Popup</th>
                <th><i class="fa fa-edit"></i> Aksi</th>
            </tr>
        </thead>
        <tbody style="white-space: nowrap;">
            <?php foreach ($provinsi as $provinsi_item): ?>
                <tr>
                    <td><?= $no; ?></td>
                    <td><?= esc($provinsi_item->nama_provinsi); ?></td>
                    <td style="max-width: 400px; overflow: hidden; text-overflow: ellipsis;"><?= esc($provinsi_item->polygon); ?></td>
                    <td><?= esc($provinsi_item->posisi_popup); ?></td>
                    <td>
                        <button type="button" class="btn btn-warning font-weight-bold" data-toggle="modal" data-target="#editModal" data-id="<?= $provinsi_item->id; ?>"><i class="fa fa-edit"></i> Edit</button>
                        <a href="<?= site_url('crud/deletedata/' . $provinsi_item->id); ?>" class="btn btn-danger font-weight-bold"><i class="fa fa-trash"></i> Delete</a>
                    </td>
                </tr>
                <?php $no++; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    $('#editModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var id = button.data('id'); // Extract info from data-* attributes
        var nama_provinsi = polygon = posisi_popup = "";

        if (id !== "") {
            nama_provinsi = event.relatedTarget.closest('tr').cells[1].innerHTML;
            polygon = event.relatedTarget.closest('tr').cells[2].innerHTML;
            posisi_popup = event.relatedTarget.closest('tr').cells[3].innerHTML;

            $('#id').val(id);
            $('#nama-provinsi').val(nama_provinsi);
            $('#polygon').val(polygon);
            $('#posisi-popup').val(posisi_popup);
        }
    });

    $('#editModal').on('hidden.bs.modal', function () {
        $('.reset').val(""); //reset value hidden input berisi id
        $('#formEdit')[0].reset(); //reset form
    });
</script>