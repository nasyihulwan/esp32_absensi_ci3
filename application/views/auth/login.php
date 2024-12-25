<div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth px-0">
            <div class="row w-100 mx-0">
                <div class="col-lg-4 mx-auto">
                    <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                        <div class="brand-logo">
                            <img src="<?= base_url('assets/images/logo.svg') ?>" alt="logo">
                        </div>
                        <h4>Hello! let's get started</h4>
                        <h6 class="font-weight-light mb-3">Sign in to continue.</h6>

                        <?= $this->session->flashdata('message') ?>

                        <form action="<?= site_url('auth') ?>" method="POST">
                            <div class="form-group">
                                <input type="text" name="username" class="form-control form-control-lg"
                                    placeholder="Username">
                                <?= form_error('username', '<small class="text-danger pl-2">', '</small>') ?>
                            </div>
                            <div class="form-group">
                                <input type="password" name="password" class="form-control form-control-lg"
                                    placeholder="Password">
                                <?= form_error('password', '<small class="text-danger pl-2">', '</small>') ?>
                            </div>
                            <div class="mt-3">
                                <button type="submit"
                                    class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">SIGN
                                    IN</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
</div>