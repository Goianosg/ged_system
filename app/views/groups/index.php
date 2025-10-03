<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/sidebar.php'; ?>
<main id="main" class="main">
    <div class="pagetitle"><h1>Grupos e Permissões</h1></div>
    <section class="section">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Grupos de Usuários</h5>
                <a href="<?= URLROOT; ?>/groups/create" class="btn btn-primary mb-3">Adicionar Novo Grupo</a>
                <table class="table table-striped">
                    <thead><tr><th>Nome do Grupo</th><th width="40%">Ações</th></tr></thead>
                    <tbody>
                        <?php foreach($data['groups'] as $group): ?>
                        <tr>
                            <td><?= htmlspecialchars($group->nome_grupo); ?></td>
                            <td>
                                <a href="<?= URLROOT; ?>/groups/permissions/<?= $group->id; ?>" class="btn btn-info btn-sm">Permissões</a>
                                <a href="<?= URLROOT; ?>/groups/edit/<?= $group->id; ?>" class="btn btn-warning btn-sm">Renomear</a>
                                <form action="<?= URLROOT; ?>/groups/delete/<?= $group->id; ?>" method="post" class="d-inline">
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza?');">Excluir</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</main>
<?php require APPROOT . '/views/inc/footer.php'; ?>