<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Login - <?= SITENAME; ?></title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="<?= URLROOT; ?>/assets/img/favicon.png" rel="icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="<?= URLROOT; ?>/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= URLROOT; ?>/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="<?= URLROOT; ?>/assets/css/style.css" rel="stylesheet">
  <link href="<?= URLROOT; ?>/assets/css/custom.css" rel="stylesheet">
</head>

<body>

  <main>
    <div class="container">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-4">
                <a href="#" class="logo d-flex align-items-center w-auto">
                  <img src="<?= URLROOT; ?>/assets/img/logo.png" alt="">
                  <span class="d-none d-lg-block"><?= SITENAME; ?></span>
                </a>
              </div><!-- End Logo -->

              <div class="card mb-3">

                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Login com sua conta</h5>
                    <p class="text-center small">Digite seu nome de usuário e senha para entrar</p>
                  </div>

                  <form class="row g-3 needs-validation" novalidate method="POST" action="<?= URLROOT; ?>/auth/login">

                    <div class="col-12">
                      <label for="nome_usuario" class="form-label">Usuário</label>
                      <div class="input-group has-validation">
                        <span class="input-group-text" id="inputGroupPrepend">@</span>
                        <input type="text" name="nome_usuario" class="form-control" id="nome_usuario" required>
                        <div class="invalid-feedback">Por favor, insira seu nome de usuário.</div>
                      </div>
                    </div>

                    <div class="col-12">
                      <label for="senha" class="form-label">Senha</label>
                      <input type="password" name="senha" class="form-control" id="senha" required><?php if (!empty($data['error'])): ?>
                        <div class="text-danger mt-2"><?= htmlspecialchars($data['error']); ?></div>
                      <?php endif; ?>
                      <div class="invalid-feedback">Por favor, insira sua senha.</div>
                    </div>

                    <div class="col-12">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" value="true" id="rememberMe">
                        <label class="form-check-label" for="rememberMe">Lembrar de mim</label>
                      </div>
                    </div>
                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit">Login</button>
                    </div>
                   
                  </form>

                </div>
              </div>

              <div class="credits">
                <!-- All the links in the footer should remain intact. -->
                Designed by dev  <a href="#">Goianognu, Moitabrava</a>
              </div>

            </div>
          </div>
        </div>

      </section>

    </div>
  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="<?= URLROOT; ?>/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Template Main JS File -->
  <script src="<?= URLROOT; ?>/assets/js/main.js"></script>

</body>

</html>