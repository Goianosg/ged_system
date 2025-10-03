<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/sidebar.php'; ?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Gerenciar Arquivos de: <?= htmlspecialchars($data['colaborador']->nome_completo ?? ''); ?></h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= URLROOT; ?>/colaboradores">Colaboradores</a></li>
                <?php if (in_array('view_collaborator_details', $_SESSION['user_permissions'])): ?>
                    <li class="breadcrumb-item"><a href="<?= URLROOT; ?>/colaboradores/show/<?= $data['colaborador']->id; ?>">Perfil</a></li>
                <?php endif; ?>
                <li class="breadcrumb-item active">Gerenciar PDFs</li>
            </ol>
        </nav>
    </div><section class="section">
        <div class="row">

            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Resumo do Colaborador</h5>
                        <div class="row">
                            <div class="col-lg-2 col-md-3 text-center">
                                <img src="<?= URLROOT . ($data['colaborador']->foto_path ?? '/assets/img/profile-img.jpg'); ?>" alt="Profile" class="rounded-circle" width="120">
                            </div>
                            <div class="col-lg-10 col-md-9">
                                <div class="row"><div class="col-sm-4 label fw-bold">Nome Completo</div><div class="col-sm-8"><?= htmlspecialchars($data['colaborador']->nome_completo ?? ''); ?></div></div>
                                <div class="row"><div class="col-sm-4 label fw-bold">Cargo</div><div class="col-sm-8"><?= htmlspecialchars($data['colaborador']->cargo ?? ''); ?></div></div>
                                <div class="row"><div class="col-sm-4 label fw-bold">Departamento</div><div class="col-sm-8"><?= htmlspecialchars($data['departamento']->nome ?? 'N/A'); ?></div></div>
                                <div class="row"><div class="col-sm-4 label fw-bold">Email</div><div class="col-sm-8"><?= htmlspecialchars($data['colaborador']->email ?? ''); ?></div></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Ações de Arquivos</h5>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <?php if (in_array('upload_pdf', $_SESSION['user_permissions'])): ?>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadPdfModal">
                                    <i class="bi bi-upload"></i> Enviar Novo PDF
                                </button>
                            <?php endif; ?>
                            <div class="w-50">
                                <form action="<?= URLROOT; ?>/pdfs/colaborador/<?= $data['colaborador']->id; ?>" method="GET">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Pesquisar por nome do PDF..." name="q" value="<?= htmlspecialchars($data['searchTerm'] ?? ''); ?>">
                                        <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <hr>
                        <h5 class="card-title mt-4">Arquivos Associados</h5>
                        <?php if (!empty($data['searchTerm'])): ?>
                            <div class="alert alert-info d-flex justify-content-between align-items-center">
                                <span>Resultados para: <strong><?= htmlspecialchars($data['searchTerm']); ?></strong></span>
                                <a href="<?= URLROOT; ?>/pdfs/colaborador/<?= $data['colaborador']->id; ?>" class="btn btn-sm btn-outline-info">Limpar Pesquisa</a>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (empty($data['files'])): ?>
                            <div class="alert alert-secondary" role="alert">Nenhum arquivo encontrado.</div>
                        <?php else: ?>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nome do Arquivo</th>
                                        <th>Enviado por</th>
                                        <th>Data</th>
                                        <th width="20%">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['files'] as $file): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($file->nome_exibicao); ?></td>
                                            <td><?= htmlspecialchars($file->nome_usuario); ?></td>
                                            <td><?= date('d/m/Y H:i', strtotime($file->data_upload)); ?></td>
                                            <td>
                                                <?php if (in_array('view_pdf', $_SESSION['user_permissions'])): ?>
                                                    <a href="<?= URLROOT; ?>/pdfs/viewPdf/<?= $file->id; ?>" target="_blank" class="btn btn-info btn-sm">Ver</a>
                                                <?php endif; ?> 
                                                <?php if (in_array('delete_pdf', $_SESSION['user_permissions'])): ?>
                                                    <form action="<?= URLROOT; ?>/pdfs/deletePdf/<?= $file->id; ?>" method="POST" class="d-inline">
                                                        <input type="hidden" name="id_colaborador" value="<?= $data['colaborador']->id; ?>">
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este arquivo?');">Excluir</button>
                                                    </form>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<div class="modal fade" id="uploadPdfModal" tabindex="-1" aria-labelledby="uploadPdfModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= URLROOT; ?>/pdfs/upload" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadPdfModalLabel">Enviar Novo Arquivo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_colaborador" value="<?= $data['colaborador']->id; ?>">
                    <div class="mb-3">
                        <label for="file_name_modal" class="form-label">Nome de Exibição do Arquivo</label>
                        <input type="text" class="form-control" id="file_name_modal" name="file_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="pdf_file_modal" class="form-label">Arquivo PDF</label>
                        <input class="form-control" type="file" id="pdf_file_modal" name="pdf_file" accept="application/pdf" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>