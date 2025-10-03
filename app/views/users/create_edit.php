<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/sidebar.php'; ?>
<main id="main" class="main">
    <div class="pagetitle">
        <h1><?= isset($data['user']) ? 'Editar Usuário' : 'Adicionar Usuário'; ?></h1>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Dados do Usuário</h5>
                    <?php if(isset($data['user'])): ?>
                        <a href="<?= URLROOT; ?>/groups/permissions/<?= $data['user']->id_grupo; ?>" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-shield-lock"></i> Ver Permissões do Grupo
                        </a>
                    <?php endif; ?>
                </div>
                <hr>
                <form action="<?= isset($data['user']) ? (URLROOT . '/users/edit/' . $data['user']->id) : (URLROOT . '/users/create'); ?>" method="POST" enctype="multipart/form-data">
                    <div class="text-center mb-4">
                        <img src="<?= URLROOT . ($data['user']->foto_path ?? '/assets/img/profile-img.jpg'); ?>" alt="Profile" class="rounded-circle" width="120">
                        <input type="file" name="foto" id="foto" class="form-control mt-2">
                        <input type="hidden" name="foto_existente" value="<?= htmlspecialchars($data['user']->foto_path ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="nome_usuario" class="form-label">Nome de Usuário</label>
                        <input type="text" name="nome_usuario" id="nome_usuario" class="form-control <?= !empty($data['errors']['nome_usuario']) ? 'is-invalid' : ''; ?>" value="<?= htmlspecialchars($data['nome_usuario'] ?? $data['user']->nome_usuario ?? ''); ?>" required>
                        <div class="invalid-feedback"><?= $data['errors']['nome_usuario'] ?? ''; ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control <?= !empty($data['errors']['email']) ? 'is-invalid' : ''; ?>" value="<?= htmlspecialchars($data['email'] ?? $data['user']->email ?? ''); ?>" required>
                        <div class="invalid-feedback"><?= $data['errors']['email'] ?? ''; ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" name="senha" id="senha" class="form-control <?= !empty($data['errors']['senha']) ? 'is-invalid' : ''; ?>" <?= !isset($data['user']) ? 'required' : ''; ?>>
                        <?php if(isset($data['user'])): ?><small class="form-text text-muted">Deixe em branco para não alterar a senha.</small><?php endif; ?>
                        <div class="invalid-feedback"><?= $data['errors']['senha'] ?? ''; ?></div>
                    </div>
                    <?php if(!isset($data['user'])): ?>
                    <div class="mb-3">
                        <label for="confirm_senha" class="form-label">Confirmar Senha</label>
                        <input type="password" name="confirm_senha" id="confirm_senha" class="form-control <?= !empty($data['errors']['confirm_senha']) ? 'is-invalid' : ''; ?>" required>
                        <div class="invalid-feedback"><?= $data['errors']['confirm_senha'] ?? ''; ?></div>
                    </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <label for="id_grupo" class="form-label">Grupo</label>
                        <select name="id_grupo" id="id_grupo" class="form-select">
                            <option value="">Nenhum</option>
                            <?php foreach($data['groups'] as $group): ?>
                                <option value="<?= $group->id; ?>" <?= ((isset($data['id_grupo']) && $data['id_grupo'] == $group->id) || (isset($data['user']) && $data['user']->id_grupo == $group->id)) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($group->nome_grupo); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <a href="<?= URLROOT; ?>/users" class="btn btn-secondary ">Cancelar</a>
                </form>
            </div>
        </div>
    </section>
</main>
<?php require APPROOT . '/views/inc/footer.php'; ?>