<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <!-- Konten -->
    <div class="row">
        <div class="col-lg-6">

            <!-- div kosong untuk mengambil nilai flash data lalu dikirimkan ke JS! -->


            <?= form_error('menu', '<div class="alert alert-danger" role="alert">', '</div>'); ?>

            <?= $this->session->flashdata('role'); ?>

            <a href="" class="btn btn-primary mb-3" data-toggle="modal" data-target="#newRoleModal">Add New Role</a>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Role</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($role as $r) : ?>
                        <tr>
                            <th scope="row"><?= $i++; ?></th>
                            <td><?= $r['role'] ?></td>
                            <td>
                                <a href="<?= base_url('admin/roleaccess/').$r['id']; ?>" class="badge badge-warning ModalEdit"   data-id="<?= $r['id']; ?>">Access</a>
                                <a href="<?= base_url() ?>admin/roleedit/<?= $r['id']; ?>" class="badge badge-success ModalEdit" data-toggle="modal" data-target="#newRoleModal" data-id="<?= $r['id']; ?>">Edit</a>
                                <a href="<?= base_url() ?>admin/roledelete/<?= $r['id']; ?>" class="badge badge-danger tombol-rolehapus" data-id="<?= $r['id']; ?>">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- End Konten -->



    <!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
</div>


<!-- Modal -->
`
<!-- Modal -->
<div class="modal fade" id="newRoleModal" tabindex="-1" role="dialog" aria-labelledby="newRoleModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="labelRoleModal">Add Role Menu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('admin/role') ?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control" id="menu" name="role" placeholder="Role name">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- EndModal -->