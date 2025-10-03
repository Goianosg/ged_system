<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/sidebar.php'; ?>
<main id="main" class="main">
    <div class="pagetitle">
        <h1><?= isset($data['id']) ? 'Editar Departamento' : 'Adicionar Departamento'; ?></h1>
    </div>
    <section class="section"><div class="card"><div class="card-body">
        <h5 class="card-title">Dados do Departamento</h5>
        <form action="<?= isset($data['id']) ? (URLROOT . '/departamentos/edit/' . $data['id']) : (URLROOT . '/departamentos/create'); ?>" method="POST">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome do Departamento</label>
                <input type="text" name="nome" class="form-control <?= !empty($data['error_nome']) ? 'is-invalid' : ''; ?>" value="<?= htmlspecialchars($data['nome'] ?? ''); ?>" required>
                <div class="invalid-feedback"><?= $data['error_nome'] ?? ''; ?></div>
            </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
            <a href="<?= URLROOT; ?>/departamentos" class="btn btn-secondary">Cancelar</a>
        </form>
    </div></div></section>
</main>
<?php require APPROOT . '/views/inc/footer.php'; ?>