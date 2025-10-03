
<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/sidebar.php'; ?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1><?= isset($data['unidade']) ? 'Editar Unidade' : 'Adicionar Unidade'; ?></h1>
    </div>
    <section class="section"><div class="card"><div class="card-body">
        <h5 class="card-title">Dados da Unidade</h5>
        <form action="<?= isset($data['unidade']) ? (URLROOT . '/unidades/edit/' . $data['unidade']->id) : (URLROOT . '/unidades/create'); ?>" method="POST">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome da Unidade</label>
                <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($data['unidade']->nome ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="cidade" class="form-label">Cidade</label>
                <input type="text" name="cidade" class="form-control" value="<?= htmlspecialchars($data['unidade']->cidade ?? ''); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
            <a href="<?= URLROOT; ?>/unidades" class="btn btn-secondary">Cancelar</a>
        </form>
    </div></div></section>
</main
<?php require APPROOT . '/views/inc/footer.php'; ?>
