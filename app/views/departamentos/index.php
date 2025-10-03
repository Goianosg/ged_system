<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/sidebar.php'; ?>
<main id="main" class="main">
    <div class="pagetitle"><h1>Departamentos</h1></div>
    <section class="section">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Lista de Departamentos</h5>
                <a href="<?= URLROOT; ?>/departamentos/create" class="btn btn-primary mb-3">Adicionar Novo</a>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nome do Departamento</th>
                            <th width="20%">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['departamentos'] as $depto): ?>
                        <tr>
                            <td><?= htmlspecialchars($depto->nome); ?></td>
                            <td>
                                <a href="<?= URLROOT; ?>/departamentos/edit/<?= $depto->id; ?>" class="btn btn-warning btn-sm">Editar</a>
                                <form action="<?= URLROOT; ?>/departamentos/delete/<?= $depto->id; ?>" method="post" class="d-inline">
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