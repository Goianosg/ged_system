<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/sidebar.php'; ?>

<main id="main" class="main">
    <div class="pagetitle"><h1>Usuários do Sistema</h1></div>
    <section class="section"><div class="card"><div class="card-body">
        <h5 class="card-title">Lista de Usuários</h5>
        <?php if (in_array('create_user', $_SESSION['user_permissions'])): ?>
        <a href="<?= URLROOT; ?>/users/create" class="btn btn-primary mb-3">Adicionar Novo Usuário</a>
        <?php endif; ?>
        <table class="table table-striped">
            <thead><tr><th>Nome de Usuário</th><th>Email</th><th>Grupo</th><th width="20%">Ações</th></tr></thead>
            <tbody>
                <?php foreach($data['users'] as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user->nome_usuario); ?></td>
                    <td><?= htmlspecialchars($user->email); ?></td>
                    <td><?= htmlspecialchars($user->nome_grupo ?? 'Nenhum'); ?></td>
                    <td>
                        <?php if (in_array('edit_user', $_SESSION['user_permissions'])): ?>
                        <a href="<?= URLROOT; ?>/users/edit/<?= $user->id; ?>" class="btn btn-warning btn-sm">Editar</a>
                        <?php endif; ?>
                        <?php if (in_array('delete_user', $_SESSION['user_permissions'])): ?>
                        <form action="<?= URLROOT; ?>/users/delete/<?= $user->id; ?>" method="post" class="d-inline">
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza?');">Excluir</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div></div></section>
</main>
<?php require APPROOT . '/views/inc/footer.php'; ?>