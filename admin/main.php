<?php
require_once 'logincheck.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title><?php echo $headTitle ?></title>
   <!-- Font Awesome -->
   <link rel="stylesheet" href="../asset/fontawesome/css/all.min.css">
   <link rel="stylesheet" href="../asset/css/adminlte.min.css">
   <link rel="stylesheet" href="../asset/css/style.css">
   <!-- DataTables -->
   <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
   <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
   <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

   <!-- Select2 -->
   <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
   <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

   <link rel="stylesheet" href="../plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
   <link rel="stylesheet" href="../asset/js/leaflet/leaflet.css">
   <link rel="stylesheet" href="../asset/js/routing/leaflet-routing-mach.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">


    <style type="text/css">
      td a.btn {
         font-size: 0.7rem;
      }

      body {
          font-family: 'Poppins', sans-serif;
      }

      .nav-link:hover{
          color: white!important;
          transition: ease-in 200ms;
      }
      td p {
         padding-left: 0.5rem !important;
      }

      th {
         padding: 1rem !important;
      }

      table tr td {
         padding: 0.3rem !important;
         font-size: 13px;
      }

      .hide {
         display: none !important;
      }
   </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
   <div class="wrapper">

      <nav class="main-header navbar navbar-expand navbar-white navbar-light" style="background-color: rgba(62,88,113);">
         <ul class="navbar-nav">
            <li class="nav-item">
               <a class="nav-link text-white-50" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
         </ul>
         <ul class="navbar-nav ml-auto">
            <li class="nav-item">
               <a class="nav-link text-white-50" data-widget="fullscreen" href="#" role="button">
                  <i class="fas fa-expand-arrows-alt"></i>
               </a>
            </li>
            <li class="nav-item">
               <a class="nav-link text-white-50" data-widget="fullscreen" href="index.php">
                  <i class="fas fa-power-off"></i>
               </a>
            </li>
         </ul>
      </nav>

      <aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: rgba(44,62,80);">
         <a href="" class="brand-link animated swing p-4">
            <img src="../asset/img/MDRRMO logo.png" width="200" style="margin-top: 40px; margin-bottom: -10px;">
         </a>

         <div class="sidebar">

            <div class="user-panel d-flex p-3 align-items-center">
                  <img src="../asset/img/avatar.png" class="img-circle elevation-2 mr-2" alt="User Image">
               <div class="info">
                  <a href="#" class="d-block"><?php echo $admin['fullname'] ?></a>
                  <a>
                     <span class="brand-text badge badge-success"><?php echo $admin['role'] ?></span>
                     <?php if (!$isAdmin) { ?>
                        <span class="brand-text badge badge-success"><?php echo $adminEvacuationCenter['center'] ?></span>
                     <?php } ?>
                  </a>
               </div>
            </div>


            <nav class="mt-2">
               <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                  <li class="nav-item">
                     <a href="dashboard_data.php" class="nav-link <?php echo $menuSection === 'dashboard' ? 'active' : '' ?>">
                        <i class="nav-icon fa fa-tv"></i>
                        <p>
                           Dashboard
                        </p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="calamity.php" class="nav-link <?php echo $menuSection === 'calamity' ? 'active' : '' ?>">
                        <i class="nav-icon fa fa-bell"></i>
                        <p>
                           Disaster
                        </p>
                     </a>
                  </li>
                  <li class="nav-item  <?php echo $menuParentSection === 'barangay' ? 'menu-open' : '' ?>">
                     <a href="#" class="nav-link <?php echo $menuParentSection === 'barangay' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-dice-d6"></i>
                        <p>
                           Barangay
                        </p>
                        <i class="right fas fa-angle-left"></i>
                     </a>
                     <ul class="nav nav-treeview">
                        <li class="nav-item">
                           <a href="barangay.php" class="nav-link <?php echo $menuSection === 'brgy' ? 'active' : '' ?>">
                              <i class="nav-icon fa fa-info-circle"></i>
                              <p>
                                 Barangay Information
                              </p>
                           </a>
                        </li>
                        <li class="nav-item">
                           <a href="barangay_db.php" class="nav-link <?php echo $menuSection === 'brgy_db' ? 'active' : '' ?>">
                              <i class="nav-icon fa fa-address-book"></i>
                              <p>
                                 Barangay Database
                              </p>
                           </a>
                        </li>
                     </ul>
                  </li>
                  <?php if ($isAdmin) { ?>
                     <li class="nav-item">
                        <a href="evacuation-center.php" class="nav-link <?php echo $menuSection === 'evacuation' ? 'active' : '' ?>">
                           <i class="nav-icon fa fa-hotel"></i>
                           <p>
                              Evacuation Center
                           </p>
                        </a>
                     </li>
                  <?php } ?>
                  <li class="nav-item  <?php echo $menuParentSection === 'evacuee' ? 'menu-open' : '' ?>">
                     <a href="#" class="nav-link <?php echo $menuParentSection === 'evacuee' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-address-card"></i>
                        <p>
                           Evacuee Information
                        </p>
                        <i class="right fas fa-angle-left"></i>
                     </a>
                     <ul class="nav nav-treeview">
                        <li class="nav-item">
                           <a href="add-evacuees2.php" class="nav-link <?php echo $menuSection === 'add-evacuess' ? 'active' : '' ?>">
                              <i class="nav-icon fa fa-user-plus"></i>
                              <p>New</p>
                           </a>
                        </li>
                        <li class="nav-item">
                           <a href="manage-evacuees.php" class="nav-link <?php echo $menuSection === 'evacuess-list' ? 'active' : '' ?>">
                              <i class="nav-icon fa fa-address-book"></i>
                              <p>Manage</p>
                           </a>
                        </li>
                        <li class="nav-item">
                           <a href="heaf_of_family.php" class="nav-link <?php echo $menuSection === 'family_head' ? 'active' : '' ?>">
                              <i class="nav-icon fa fa-address-book"></i>
                              <p>Family Head</p>
                           </a>
                        </li>
                     </ul>
                  </li>
                  <li class="nav-item  <?php echo $menuParentSection === 'mp' ? 'menu-open' : '' ?>">
                     <a href="#" class="nav-link <?php echo $menuParentSection === 'mp' ? 'active' : '' ?>">
                        <i class="nav-icon fa fa-shuttle-van"></i>
                        <p>
                           MP Vehicle
                        </p>
                        <i class="right fas fa-angle-left"></i>
                     </a>
                     <ul class="nav nav-treeview">
                        <li class="nav-item">
                           <a href="mpv.php" class="nav-link <?php echo $menuSection === 'addmpv' ? 'active' : '' ?>">
                              <i class="nav-icon fa fa-plus"></i>
                              <p>Add MPV</p>
                           </a>
                        </li>
                        <li class="nav-item">
                           <a href="managempv.php" class="nav-link <?php echo $menuSection === 'managempv' ? 'active' : '' ?>">
                              <i class="nav-icon fa fa-address-book"></i>
                              <p>Manage MPV</p>
                           </a>
                        </li>
                     </ul>
                  </li>
                  <li class="nav-item  <?php echo $menuParentSection === 'user' ? 'menu-open' : '' ?>">
                     <a href="#" class="nav-link <?php echo $menuParentSection === 'user' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-user-lock"></i>
                        <p>
                           Users
                        </p>
                        <i class="right fas fa-angle-left"></i>
                     </a>
                     <ul class="nav nav-treeview">
                        <li class="nav-item">
                           <a href="add-user.php" class="nav-link <?php echo $menuSection === 'add-user' ? 'active' : '' ?>">
                              <i class="nav-icon fa fa-user-plus"></i>
                              <p>New</p>
                           </a>
                        </li>
                        <li class="nav-item">
                           <a href="manage-user.php" class="nav-link <?php echo $menuSection === 'userlist' ? 'active' : '' ?>">
                              <i class="nav-icon fa fa-address-book"></i>
                              <p>Manage</p>
                           </a>
                        </li>
                     </ul>
                  </li>
                  <li class="nav-item  <?php echo $menuParentSection === 'evacuee-report' ? 'menu-open' : '' ?>">
                     <a href="#" class="nav-link <?php echo $menuParentSection === 'evacuee-report' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>
                           Reports
                        </p>
                        <i class="right fas fa-angle-left"></i>
                     </a>
                     <ul class="nav nav-treeview">
                        <!-- <li class="nav-item">
                              <a href="evacuation_list.php" class="nav-link <?php echo $menuSection === 'evacuess-list-report' ? 'active' : '' ?>">
                                 <i class="nav-icon fa fa-users"></i>
                                 <p>Evacuees</p>
                              </a>
                           </li> -->
                        <!--<li class="nav-item">
                           <a href="gender-report.php" class="nav-link <?php echo $menuSection === 'report-gender' ? 'active' : '' ?>">
                              <i class="nav-icon fa fa-venus-mars"></i>
                              <p>Evacuees by Gender</p>
                           </a>
                        </li>-->
                        <li class="nav-item">
                           <a href="report.php" class="nav-link <?php echo $menuSection === 'report' ? 'active' : '' ?>">
                              <i class="nav-icon fa fa-folder-open"></i>
                              <p>Reports</p>
                           </a>
                        </li>
                        <!--<li class="nav-item">
                           <a href="barangay-report.php" class="nav-link <?php echo $menuSection === 'report-brgy' ? 'active' : '' ?>">
                              <i class="nav-icon fa fa-archway"></i>
                              <p>Evacuees by Brgy</p>
                           </a>
                        </li>
                        <li class="nav-item">
                           <a href="age-report.php" class="nav-link <?php echo $menuSection === 'report-age' ? 'active' : '' ?>">
                              <i class="nav-icon fa fa-sort-numeric-up-alt"></i>
                              <p>Evacuees by Age</p>
                           </a>
                        </li>-->

                        <li class="nav-item">
                           <a href="calamity-report.php" class="nav-link <?php echo $menuSection === 'report-calamity' ? 'active' : '' ?>">
                              <i class="nav-icon fa fa-address-book"></i>
                              <p>Evacuees by Disaster</p>
                           </a>
                        </li>
                        <!--<li class="nav-item">
                           <a href="center-report.php" class="nav-link <?php echo $menuSection === 'report-center' ? 'active' : '' ?>">
                              <i class="nav-icon fa fa-hospital-alt"></i>
                              <p>Evacuees by Center</p>
                           </a>
                        </li>-->
                        <!--<li class="nav-item">
                           <a href="status-report.php" class="nav-link <?php echo $menuSection === 'report-status' ? 'active' : '' ?>">
                              <i class="nav-icon fa fa-adjust"></i>
                              <p>Evacuees by Status</p>
                           </a>
                        </li>-->

                     </ul>
                  </li>
                  <li class="nav-item">
                     <a href="maps.php" class="nav-link <?php echo $menuSection === 'maps' ? 'active' : '' ?>">
                        <i class="fas fa-map-marked-alt nav-icon"></i>
                        <p>Maps</p>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a href="setting.php" class="nav-link <?php echo $menuSection === 'setting' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-user-cog"></i>
                        <p>
                           Account Settings
                        </p>
                     </a>
                  </li>
                  </a>
                  </li>
               </ul>
            </nav>
         </div>
      </aside>

      <div class="content-wrapper">
         <div class="content-header">
            <div class="container-fluid">
               <div class="row mb-2">
                  <div class="col-sm-6">
                     <h1 class="m-0" style="color: rgb(31,108,163);">
                        <span class="fa <?php echo $icon ?>"></span>
                        <?php echo $mainTitle ?>
                     </h1>
                  </div>

                  <div class="col-sm-6">
                     <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active"><?php echo $breadcrumbs ?></li>
                     </ol>
                  </div>
               </div>
            </div>
         </div>