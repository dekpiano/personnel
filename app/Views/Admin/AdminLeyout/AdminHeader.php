<!DOCTYPE html>
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="<?=base_url()?>/assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title><?=$title;?> | SKJ Personnel Manage</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?=base_url()?>/assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@200;300&display=swap" rel="stylesheet">

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="<?=base_url()?>/assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="<?=base_url()?>/assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="<?=base_url()?>/assets/vendor/css/theme-blue.css?v=1" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="<?=base_url()?>/assets/css/select2.css?v=3" />
    <link rel="stylesheet" href="<?=base_url()?>/assets/css/demo.css?v=1" />

    <!-- <link rel="stylesheet" href="<?=base_url()?>/assets/css/flatpickr.css?v=1" /> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="<?=base_url()?>/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <link rel="stylesheet" href="<?=base_url()?>/assets/vendor/libs/apex-charts/apex-charts.css" />

        <!-- Datatable css -->       
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="<?=base_url()?>/assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="<?=base_url()?>/assets/js/config.js"></script>
  </head>

  <style>
    /* ------------------ */
.light-style .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 4.60rem;
    color: #697a8d;
    padding-left: .875rem;

}

.light-style .select2-container--default .select2-selection--single {
    height: calc(2.60em + 0.875rem + 2px);
}

/* ฟอร์มที่มี .was-validated */
.was-validated select:valid+.select2 .select2-selection,
.was-validated .is-valid+.select2 .select2-selection {
    border-color: #71dd37 !important;
    /* สีเขียวของ BS 5 */
    /*padding-right: calc(1.5em + 0.75rem);*/
}

.was-validated select:invalid+.select2 .select2-selection,
.was-validated .is-invalid+.select2 .select2-selection {
    border-color: #dc3545 !important;
    /* สีแดง */
   /* padding-right: calc(1.5em + 0.75rem);*/
}
  </style>

  <body style="font-family:'Sarabun'">