<?php
ob_start();
include_once 'config/base_url.php';
$assetPath = asset_url('public/assets/');
$baseHref = rtrim($base_url, '/') . '/';
?>
<!DOCTYPE html>

<!-- =========================================================
* Sneat - Bootstrap 5 HTML Admin Template - Pro | v1.0.0
==============================================================

* Product Page: https://themeselection.com/products/sneat-bootstrap-html-admin-template/
* Created by: ThemeSelection
* License: You must have a valid license purchased in order to legally use the theme for your project.
* Copyright ThemeSelection (https://themeselection.com)

=========================================================
 -->
<!-- beautify ignore:start -->
<html
    lang="en"
    class="light-style layout-menu-fixed"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="<?php echo $assetPath; ?>"
    data-template="vertical-menu-template-free">

<head>
    <base href="<?php echo $baseHref; ?>">
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Dashboard - Analytics | Sneat - Bootstrap 5 HTML Admin Template - Pro</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo $assetPath; ?>img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />
    <!-- Datatable -->
    <link rel="stylesheet" href="<?php echo $assetPath; ?>DataTables/datatables.min.css" rel="stylesheet">
    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="<?php echo $assetPath; ?>vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="<?php echo $assetPath; ?>vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="<?php echo $assetPath; ?>vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="<?php echo $assetPath; ?>css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="<?php echo $assetPath; ?>vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <link rel="stylesheet" href="<?php echo $assetPath; ?>vendor/libs/apex-charts/apex-charts.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="<?php echo $assetPath; ?>vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="<?php echo $assetPath; ?>js/config.js"></script>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            <?php include 'view/component/sidebar.php'; ?>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <?php include 'view/component/navbar.php'; ?>

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->


                    <div class="container-xxl flex-grow-1 container-p-y">
                        <?php include $content; ?>
                    </div>

                </div>
                <!-- / Content -->

                <!-- Footer -->
                <?php include 'view/component/footer.php'; ?>
                <!-- / Footer -->

                <div class="content-backdrop fade"></div>
            </div>
            <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <div class="buy-now">
        <a
            href="https://themeselection.com/products/sneat-bootstrap-html-admin-template/"
            target="_blank"
            class="btn btn-danger btn-buy-now">Upgrade to Pro</a>
    </div>

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="<?php echo $assetPath; ?>vendor/libs/jquery/jquery.js"></script>
    <script src="<?php echo $assetPath; ?>vendor/libs/popper/popper.js"></script>
    <script src="<?php echo $assetPath; ?>vendor/js/bootstrap.js"></script>
    <script src="<?php echo $assetPath; ?>vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="<?php echo $assetPath; ?>vendor/js/menu.js"></script>

    <!-- endbuild -->
    <!-- Datatable -->
    <script src="<?php echo $assetPath; ?>DataTables/datatables.min.js"></script>
    <script>
        $(document).ready(function() {
            const tableSelectors = ['#mahasiswa-table', '#dosen-table', '#matakuliah-table', '#krs-table', '#khs-table', '#ktm-table'];
            tableSelectors.forEach(function(selector) {
                if ($(selector).length) {
                    $(selector).DataTable({
                        pageLength: 10,
                        lengthChange: true,
                        searching: true,
                        ordering: true
                    });
                }
            });
        });
    </script>

    <!-- Di bagian bawah file layout utama atau di halaman laporan -->
    <script src="<?php echo $assetPath; ?>vendor/libs/html2pdf/html2pdf.bundle.min.js"></script>


    <!-- Vendors JS -->
    <script src="<?php echo $assetPath; ?>vendor/libs/apex-charts/apexcharts.js"></script>

    <!-- Page JS -->
    <script src="<?php echo $assetPath; ?>js/dashboards-analytics.js"></script>

    <!-- Main JS -->
    <script src="<?php echo $assetPath; ?>js/main.js"></script>
</body>

</html>
<?php ob_end_flush(); ?>
