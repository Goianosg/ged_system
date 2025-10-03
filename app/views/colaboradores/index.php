<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/sidebar.php'; ?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Colaboradores</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= URLROOT; ?>/dashboard">Home</a></li>
                <li class="breadcrumb-item active">Colaboradores</li>
            </ol>
        </nav>
    </div><section class="section">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Lista de Colaboradores</h5>
                <div class="card p-3 mb-4">
                    <h5 class="card-title pb-0">Filtros de Busca</h5>
                    <form action="<?= URLROOT; ?>/colaboradores" method="GET" class="row g-3">
                        <div class="col-md-4"><label for="nome" class="form-label">Nome</label><input type="text" name="nome" id="nome" class="form-control" value="<?= htmlspecialchars($data['filters']['nome'] ?? ''); ?>"></div>
                        <div class="col-md-3"><label for="cpf" class="form-label">CPF</label><input type="text" name="cpf" id="cpf" class="form-control" value="<?= htmlspecialchars($data['filters']['cpf'] ?? ''); ?>"></div>
                        <div class="col-md-2"><label for="data_inicio" class="form-label">De</label><input type="date" name="data_inicio" id="data_inicio" class="form-control" value="<?= htmlspecialchars($data['filters']['data_inicio'] ?? ''); ?>"></div>
                        <div class="col-md-2"><label for="data_fim" class="form-label">Até</label><input type="date" name="data_fim" id="data_fim" class="form-control" value="<?= htmlspecialchars($data['filters']['data_fim'] ?? ''); ?>"></div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-secondary w-100">Buscar</button>
                        </div>
                    </form>
                    <?php if(!empty(array_filter($data['filters']))): ?>
                        <a href="<?= URLROOT; ?>/colaboradores" class="btn btn-link mt-2">Limpar Filtros</a>
                    <?php endif; ?>
                </div>


                <?php if (in_array('create_collaborator', $_SESSION['user_permissions'])): ?>
                    <a href="<?= URLROOT; ?>/colaboradores/create" class="btn btn-primary mb-3">Adicionar Novo</a>
                <?php endif; ?>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nome Completo</th>
                            <th>Cargo</th>
                            <th>Departamento</th>
                            <th>Status</th>
                            <th scope="col" width="25%">Ações</th>
                        </tr>
                    </thead>
                    
                    <tbody id="colaboradores-tbody">
                        <?php if (empty($data['colaboradores'])): ?>
                            <tr><td colspan="5" class="text-center">Nenhum colaborador encontrado.</td></tr>
                        <?php else: ?>
                            <?php foreach ($data['colaboradores'] as $c): ?>
                                <tr>
                                    <td><?= htmlspecialchars($c->nome_completo ?? ''); ?></td>
                                    <td><?= htmlspecialchars($c->cargo ?? ''); ?></td>
                                    <td><?= htmlspecialchars($c->depto_nome ?? 'N/A'); ?></td>
                                    <td>
                                        <?php
                                            $statusClass = 'secondary';
                                            if ($c->status == 'Ativo') { $statusClass = 'success'; }
                                            elseif ($c->status == 'Inativo') { $statusClass = 'danger'; }
                                            elseif ($c->status == 'Suspenso') { $statusClass = 'warning'; }
                                        ?>
                                        <span class="badge bg-<?= $statusClass; ?>"><?= $c->status; ?></span>
                                    </td>
                                    
                                    <td>
                                        <?php if (in_array('view_collaborator_details', $_SESSION['user_permissions'])): ?>
                                            <a href="<?= URLROOT; ?>/colaboradores/show/<?= $c->id; ?>" class="btn btn-outline-primary btn-sm" title="Ver Detalhes"><i class="bi bi-eye-fill"></i></a>
                                        <?php endif; ?>

                                        <?php if (in_array('view_pdfs_list', $_SESSION['user_permissions'])): ?>
                                            <a href="<?= URLROOT; ?>/pdfs/colaborador/<?= $c->id; ?>" class="btn btn-outline-info btn-sm" title="Ver Documentos"><i class="bi bi-folder-fill"></i></a>
                                        <?php endif; ?>

                                        <?php if (in_array('edit_collaborator', $_SESSION['user_permissions'])): ?>
                                            <a href="<?= URLROOT; ?>/colaboradores/edit/<?= $c->id; ?>" class="btn btn-outline-warning btn-sm" title="Editar"><i class="bi bi-pencil-fill"></i></a>
                                        <?php endif; ?>

                                        <?php if (in_array('delete_collaborator', $_SESSION['user_permissions'])): ?>
                                            <form action="<?= URLROOT; ?>/colaboradores/delete/<?= $c->id; ?>" method="post" class="d-inline">
                                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Tem certeza?');" title="Excluir"><i class="bi bi-trash-fill"></i></button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
             
                <?php if (($data['page'] * $data['limit']) < $data['total']): ?>
                    <div class="text-center">
                        <a href="?page=<?= $data['page'] + 1; ?>&<?= http_build_query($data['filters']); ?>" class="btn btn-outline-primary">Carregar Mais</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<?php require APPROOT . '/views/inc/footer.php'; ?>