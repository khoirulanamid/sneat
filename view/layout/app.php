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
    <style>
        /* Custom dark mode */
        body.custom-dark {
            background-color: #0f172a;
            color: #e2e8f0;
        }
        body.custom-dark .card {
            background-color: #111827;
            color: #e2e8f0;
            border-color: rgba(255, 255, 255, 0.08);
        }
        body.custom-dark .bg-navbar-theme,
        body.custom-dark .layout-navbar {
            background-color: #0b1220 !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.4);
        }
        body.custom-dark .layout-menu,
        body.custom-dark .layout-menu .menu-inner {
            background-color: #0b1220 !important;
        }
        body.custom-dark .layout-menu .menu-item.open > .menu-link,
        body.custom-dark .layout-menu .menu-link {
            color: #cbd5e1;
        }
        body.custom-dark .layout-menu .menu-link:hover {
            background-color: rgba(148, 163, 184, 0.1);
        }
        body.custom-dark .navbar-nav .nav-link,
        body.custom-dark .navbar-nav .dropdown-item {
            color: #e2e8f0;
        }
        body.custom-dark .table {
            color: #e2e8f0;
        }
        body.custom-dark .table thead {
            background-color: #0f172a;
        }
        body.custom-dark .table-bordered > :not(caption) > * > * {
            border-color: rgba(226, 232, 240, 0.18);
        }
        body.custom-dark .alert-info {
            background: rgba(59, 130, 246, 0.1);
            color: #bfdbfe;
            border-color: rgba(59, 130, 246, 0.2);
        }
        body.custom-dark .btn-outline-secondary {
            color: #cbd5e1;
            border-color: rgba(226, 232, 240, 0.3);
        }
        body.custom-dark .btn-outline-secondary:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(226, 232, 240, 0.5);
        }
        body.custom-dark .bg-footer-theme,
        body.custom-dark .content-footer.footer {
            background-color: #0b1220 !important;
            color: #e2e8f0 !important;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }
        body.custom-dark .content-footer .footer-link,
        body.custom-dark .content-footer a {
            color: #e2e8f0 !important;
        }
        body.custom-dark .content-footer .footer-link:hover,
        body.custom-dark .content-footer a:hover {
            color: #22d3ee !important;
        }
        body.custom-dark .form-control,
        body.custom-dark .form-select {
            background-color: #0b1220;
            border-color: rgba(226, 232, 240, 0.18);
            color: #e2e8f0;
            caret-color: #22d3ee;
        }
        body.custom-dark .form-control:focus,
        body.custom-dark .form-select:focus {
            border-color: #22d3ee;
            box-shadow: 0 0 0 0.15rem rgba(34, 211, 238, 0.25);
        }
        body.custom-dark .form-control::placeholder,
        body.custom-dark input::placeholder {
            color: rgba(226, 232, 240, 0.55);
        }
        /* DataTables pagination in dark mode */
        body.custom-dark .dataTables_wrapper .dataTables_paginate .paginate_button {
            background: #1f2937 !important;
            color: #f8fafc !important;
            border: 1px solid #334155 !important;
        }
        body.custom-dark .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #0ea5e9 !important;
            color: #0b1220 !important;
            border-color: #0ea5e9 !important;
        }
        body.custom-dark .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        body.custom-dark .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: #6366f1 !important;
            color: #fff !important;
            border-color: #6366f1 !important;
        }
        body.custom-dark .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
        body.custom-dark .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover,
        body.custom-dark .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:active {
            color: #94a3b8 !important;
            background: #0f172a !important;
            border-color: #1f2937 !important;
            cursor: not-allowed !important;
        }
        body.custom-dark input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(0.9);
        }
    </style>

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
    <script>
        (function() {
            const STORAGE_KEY = 'sneat-theme-mode';
            const body = document.body;
            const toggleBtn = () => document.getElementById('theme-toggle');
            const toggleIcon = () => document.getElementById('theme-toggle-icon');
            const applyMode = (mode) => {
                const isDark = mode === 'dark';
                body.classList.toggle('custom-dark', isDark);
                const iconEl = toggleIcon();
                if (iconEl) {
                    iconEl.className = isDark ? 'bx bx-sun' : 'bx bx-moon';
                }
                localStorage.setItem(STORAGE_KEY, mode);
            };
            const saved = localStorage.getItem(STORAGE_KEY);
            const initial = saved || (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            applyMode(initial);

            const btn = toggleBtn();
            if (btn) {
                btn.addEventListener('click', () => {
                    const next = body.classList.contains('custom-dark') ? 'light' : 'dark';
                    applyMode(next);
                });
            }
        })();
    </script>
</body>

</html>
<?php ob_end_flush(); ?>
