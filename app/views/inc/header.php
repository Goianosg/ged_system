<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title><?= SITENAME; ?></title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <link href="<?= URLROOT; ?>/assets/img/favicon.png" rel="icon">
    <link href="<?= URLROOT; ?>/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <link href="<?= URLROOT; ?>/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= URLROOT; ?>/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= URLROOT; ?>/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="<?= URLROOT; ?>/assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="<?= URLROOT; ?>/assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="<?= URLROOT; ?>/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="<?= URLROOT; ?>/assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="<?= URLROOT; ?>/assets/css/style.css" rel="stylesheet">
    <link href="<?= URLROOT; ?>/assets/css/chat.css" rel="stylesheet">
</head>
<body 
  data-urlroot="<?= URLROOT ?>" data-user-id="<?= $_SESSION['user_id'] ?? '' ?>">
   <header id="header" class="header fixed-top d-flex align-items-center">   
     <div class="d-flex align-items-center justify-content-between">
      <a href="<?= URLROOT; ?>/dashboard" class="logo d-flex align-items-center">
        <img src="<?= URLROOT; ?>/assets/img/logo.png" alt="">
        <span class="d-none d-lg-block"><?= SITENAME; ?></span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    
    </div><!-- End Logo -->


       <nav class="header-nav ms-auto">
          <ul class="d-flex align-items-center">
                <!-- O ícone de chat foi movido para o canto inferior direito como um widget -->

                <li class="nav-item dropdown pe-3">

                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                        <img src="<?= URLROOT . ($_SESSION['user_foto_path'] ?? '/assets/img/profile-img.jpg'); ?>" alt="Profile" class="rounded-circle">
                        <span class="d-none d-md-block dropdown-toggle ps-2"><?= $_SESSION['user_name'] ?? 'Visitante'; ?></span>
                    </a>
                    
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6><?= $_SESSION['user_name'] ?? 'Visitante'; ?></h6>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="<?= URLROOT; ?>/auth/logout">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Sair</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </header>
    
