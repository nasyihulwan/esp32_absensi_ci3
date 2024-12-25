<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>
        <?php
        if ($this->uri->segment(2) !== null) {
            echo SITE_NAME . " : " . ucfirst($this->uri->segment(1)) . " - " . ucfirst($this->uri->segment(2));
        } else {
            echo SITE_NAME . " : " . ucfirst($this->uri->segment(1));
        }
        ?>
    </title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="<?= base_url('assets/vendors/feather/feather.css') ?> ">
    <link rel="stylesheet" href="<?= base_url('assets/vendors/ti-icons/css/themify-icons.css') ?> ">
    <link rel="stylesheet" href="<?= base_url('assets/vendors/css/vendor.bundle.base.css') ?> ">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="<?= base_url('assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css') ?> ">
    <link rel="stylesheet" href="<?= base_url('assets/vendors/ti-icons/css/themify-icons.css') ?> ">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/js/select.dataTables.min.css') ?> ">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- Dropify -->
    <link rel="stylesheet" href="<?= base_url('assets/css/dropify.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/fonts') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/vertical-layout-light/style.css') ?>  ">
    <!-- endinject -->
    <link rel="shortcut icon" href="<?= base_url('assets/images/favicon.png') ?> " />