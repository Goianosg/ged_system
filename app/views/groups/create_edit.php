<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/sidebar.php'; ?>
<main id="main" class="main">
    <div class="pagetitle">
        <h1><?= isset($data['group']) ? 'Editar Grupo' : 'Adicionar Grupo'; ?></h1>
    </div>
    <section class="section"><div class="card"><div class="card-body">
        <h5 class="card-title">Dados do Grupo</h5>
        <form action="<?= isset($data['group']) ? (URLROOT . '/groups/edit/' . $data['group']->id) : (URLROOT . '/groups/create'); ?>" method="POST">
            <div class="mb-3">
                <label for="nome_grupo" class="form-label">Nome do Grupo</label>
                <input type="text" name="nome_grupo" class="form-control" value="<?= htmlspecialchars($data['group']->nome_grupo ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea name="descricao" class="form-control"><?= htmlspecialchars($data['group']->descricao ?? ''); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
            <a href="<?= URLROOT; ?>/groups" class="btn btn-secondary">Cancelar</a>
        </form>
    </div></div></section>
</main>
<?php require APPROOT . '/views/inc/footer.php'; ?>