<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/sidebar.php'; ?>
  <main id="main" class="main">
    <div class="pagetitle">
        <h1>Meu Perfil</h1>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Editar Perfil</h5>
                <form action="<?= URLROOT; ?>/users/profile" method="POST" enctype="multipart/form-data">
                    <div class="text-center mb-4">
                        <img src="<?= URLROOT . ($data['user']->foto_path ?? '/assets/img/profile-img.jpg'); ?>" alt="Profile" class="rounded-circle" width="120">
                        <label for="foto" class="form-label mt-2">Alterar Foto</label>
                        <input type="file" name="foto" id="foto" class="form-control">
                      <input type="hidden" name="foto_existente" value="<?= htmlspecialchars($data['user']->foto_path ?? ''); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="nome_usuario" class="form-label">Nome de Usuário</label>
                        <input type="text" name="nome_usuario" id="nome_usuario" class="form-control" value="<?= htmlspecialchars($data['user']->nome_usuario ?? ''); ?>" disabled readonly>
                        <small class="form-text text-muted">O nome de usuário não pode ser alterado.</small>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control <?= !empty($data['errors']['email']) ? 'is-invalid' : ''; ?>" value="<?= htmlspecialchars($data['user']->email ?? ''); ?>" required>
                      <div class="invalid-feedback"><?= $data['errors']['email'] ?? ''; ?></div>
                   </div>

                     <div class="mb-3">
                        <label for="senha_atual" class="form-label">Senha Antiga</label>
                        <input type="password" name="senha_atual" id="senha_atual" class="form-control <?= !empty($data['errors']['senha_atual']) ? 'is-invalid' : ''; ?>">
                         <div class="invalid-feedback"><?= $data['errors']['senha_atual'] ?? ''; ?></div>
                   </div>

                    <div class="mb-3">
                        <label for="senha" class="form-label">Nova Senha</label>
                        <input type="password" name="newPassword" id="newPassword" class="form-control <?= !empty($data['errors']['newPassword']) ? 'is-invalid' : ''; ?>">
                        <small class="form-text text-muted">Deixe em branco para não alterar a senha.</small>
                        <div class="invalid-feedback"><?= $data['errors']['newPassword'] ?? ''; ?></div>
                    </div>

                     <div class="mb-3">
                        <label for="confirm_senha" class="form-label">Confirmar Senha</label>
                        <input type="password" name="confirmPassword" id="confirmPassword" class="form-control <?= !empty($data['errors']['confirmPassword']) ? 'is-invalid' : ''; ?>">
                         <div class="invalid-feedback"><?= $data['errors']['confirmPassword'] ?? ''; ?></div>
                      </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                        <a href="<?= URLROOT; ?>/dashboard" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>
  <?php require APPROOT . '/views/inc/footer.php'; ?>
