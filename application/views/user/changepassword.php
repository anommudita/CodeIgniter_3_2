<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <div class="row">
        <div class="col-lg-8">
            <?= $this->session->flashdata('message'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <form action="<?= base_url('user/changepassword') ?>" method="post">
                <div class="form-group">
                    <label for="passwordcurrent">Current Password</label>
                    <input type="password" class="form-control" id="passwordcurrent" name="passwordcurrent">
                    <?= form_error('passwordcurrent', '<small class="text-danger pl-3">', '</small>'); ?>
                </div>

                <div class="form-group">
                    <label for="password1">New Password</label>
                    <input type="password" class="form-control" id="password1" name="newpassword1">
                    <?= form_error('newpassword1', '<small class="text-danger pl-3">', '</small>'); ?>
                </div>


                <div class="form-group">
                    <label for="password2">Confirm Password</label>
                    <input type="password" class="form-control" id="password2" name="newpassword2">
                    <?= form_error('newpassword2', '<small class="text-danger pl-3">', '</small>'); ?>
                </div>

                <div class="form-group">
                    <button class="btn btn-primary" type="submit">Change</button>
                </div>


            </form>
        </div>
    </div>



    <!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
</div>