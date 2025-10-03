<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/sidebar.php'; ?>
<main id="main" class="main">
    <div class="pagetitle"><h1>Unidades</h1></div>
    <section class="section"><div class="card"><div class="card-body">
        <h5 class="card-title">Lista de Unidades</h5>
        <a href="<?= URLROOT; ?>/unidades/create" class="btn btn-primary mb-3">Adicionar Nova</a>
        <table class="table table-striped">
            <thead><tr><th>Nome da Unidade</th><th>Cidade</th><th width="20%">Ações</th></tr></thead>
            <tbody>
                <?php foreach($data['unidades'] as $unidade): ?>
                <tr>
                    <td><?= htmlspecialchars($unidade->nome); ?></td>
                    <td><?= htmlspecialchars($unidade->cidade); ?></td>
                    <td>
                        <a href="<?= URLROOT; ?>/unidades/edit/<?= $unidade->id; ?>" class="btn btn-warning btn-sm">Editar</a>
                        <form action="<?= URLROOT; ?>/unidades/delete/<?= $unidade->id; ?>" method="post" class="d-inline">
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza?');">Excluir</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div></div></section>
</main>
<?php require APPROOT . '/views/inc/footer.php'; ?>