<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/sidebar.php'; ?>

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Perfil do Colaborador</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= URLROOT; ?>/colaboradores">Colaboradores</a></li>
                <li class="breadcrumb-item active">Perfil</li>
            </ol>
        </nav>
    </div><section class="section profile">
        <div class="row">
            <div class="col-xl-4">

                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                        <img src="<?= URLROOT . ($data['colaborador']->foto_path ?? '/assets/img/profile-img.jpg'); ?>" alt="Profile" class="rounded-circle">
                        <h2><?= htmlspecialchars($data['colaborador']->nome_completo ?? ''); ?></h2>
                        <h3><?= htmlspecialchars($data['colaborador']->cargo ?? ''); ?></h3>
                    </div>
                </div>

            </div>

            <div class="col-xl-8">

                <div class="card">
                    <div class="card-body pt-3">
                        <ul class="nav nav-tabs nav-tabs-bordered">
                            <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Visão Geral</button></li>
                            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-personal">Dados Pessoais</button></li>
                            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-professional">Dados Profissionais</button></li>
                            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-files">Arquivos</button></li>
                            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-system">Sistema</button></li>
                        </ul>
                        <div class="tab-content pt-2">

                            <div class="tab-pane fade show active profile-overview" id="profile-overview">
                                <div class="d-flex justify-content-end gap-2 mb-3">
                                    <?php if (in_array('edit_collaborator', $_SESSION['user_permissions'])): ?>
                                        <a href="<?= URLROOT; ?>/colaboradores/edit/<?= $data['colaborador']->id; ?>" class="btn btn-primary btn-sm" title="Editar Colaborador"><i class="bi bi-pencil"></i> Editar Perfil</a>
                                    <?php endif; ?>
                                </div>

                                <h5 class="card-title">Detalhes Principais</h5>
                                <div class="row"><div class="col-lg-3 col-md-4 label">Nome Completo</div><div class="col-lg-9 col-md-8"><?= htmlspecialchars($data['colaborador']->nome_completo ?? ''); ?></div></div>
                                <div class="row"><div class="col-lg-3 col-md-4 label">Cargo</div><div class="col-lg-9 col-md-8"><?= htmlspecialchars($data['colaborador']->cargo ?? ''); ?></div></div>
                                <div class="row"><div class="col-lg-3 col-md-4 label">Departamento</div><div class="col-lg-9 col-md-8"><?= htmlspecialchars($data['departamento']->nome ?? 'N/A'); ?></div></div>
                                <div class="row"><div class="col-lg-3 col-md-4 label">Unidade</div><div class="col-lg-9 col-md-8"><?= htmlspecialchars($data['unidade']->nome ?? 'N/A'); ?></div></div>
                                <div class="row"><div class="col-lg-3 col-md-4 label">Email</div><div class="col-lg-9 col-md-8"><?= htmlspecialchars($data['colaborador']->email ?? ''); ?></div></div>
                                <div class="row"><div class="col-lg-3 col-md-4 label">Telefone</div><div class="col-lg-9 col-md-8"><?= htmlspecialchars($data['colaborador']->telefone ?? ''); ?></div></div>
                            </div>

                            <div class="tab-pane fade pt-3" id="profile-personal">
                                 <h5 class="card-title">Dados Pessoais</h5>
                                 <div class="row"><div class="col-lg-3 col-md-4 label">CPF</div><div class="col-lg-9 col-md-8"><?= htmlspecialchars($data['colaborador']->cpf ?? ''); ?></div></div>
                                 <div class="row"><div class="col-lg-3 col-md-4 label">RG</div><div class="col-lg-9 col-md-8"><?= htmlspecialchars($data['colaborador']->rg ?? ''); ?></div></div>
                                 <div class="row"><div class="col-lg-3 col-md-4 label">Data de Nascimento</div><div class="col-lg-9 col-md-8"><?= !empty($data['colaborador']->data_nascimento) ? date('d/m/Y', strtotime($data['colaborador']->data_nascimento)) : ''; ?></div></div>
                                 <div class="row"><div class="col-lg-3 col-md-4 label">Sexo</div><div class="col-lg-9 col-md-8"><?= htmlspecialchars($data['colaborador']->sexo ?? ''); ?></div></div>
                                 <div class="row"><div class="col-lg-3 col-md-4 label">Estado Civil</div><div class="col-lg-9 col-md-8"><?= htmlspecialchars($data['colaborador']->estado_civil ?? ''); ?></div></div>
                                 <div class="row"><div class="col-lg-3 col-md-4 label">Dependentes</div><div class="col-lg-9 col-md-8"><?= $data['colaborador']->numero_dependentes ?? 0; ?></div></div>
                                 <div class="row"><div class="col-lg-3 col-md-4 label">Nacionalidade</div><div class="col-lg-9 col-md-8"><?= htmlspecialchars($data['colaborador']->nacionalidade ?? ''); ?></div></div>
                                 <div class="row"><div class="col-lg-3 col-md-4 label">Endereço</div><div class="col-lg-9 col-md-8"><?= htmlspecialchars(($data['colaborador']->endereco ?? '') . ', ' . ($data['colaborador']->cidade ?? '') . ' - ' . ($data['colaborador']->estado ?? '')); ?></div></div>
                                 <div class="row"><div class="col-lg-3 col-md-4 label">CEP</div><div class="col-lg-9 col-md-8"><?= htmlspecialchars($data['colaborador']->cep ?? ''); ?></div></div>
                                 <div class="row"><div class="col-lg-3 col-md-4 label">País</div><div class="col-lg-9 col-md-8"><?= htmlspecialchars($data['colaborador']->pais ?? ''); ?></div></div>
                            </div>

                            <div class="tab-pane fade pt-3" id="profile-professional">
                                <h5 class="card-title">Dados Profissionais e Educacionais</h5>
                                <div class="row"><div class="col-lg-3 col-md-4 label">Status</div><div class="col-lg-9 col-md-8"><span class="badge bg-<?= ($data['colaborador']->status == 'Ativo') ? 'success' : 'danger'; ?>"><?= $data['colaborador']->status; ?></span></div></div>
                                <div class="row"><div class="col-lg-3 col-md-4 label">Função Guarda</div><div class="col-lg-9 col-md-8"><?= htmlspecialchars($data['colaborador']->guarda ?? ''); ?></div></div>
                                <div class="row"><div class="col-lg-3 col-md-4 label">Data de Admissão</div><div class="col-lg-9 col-md-8"><?= !empty($data['colaborador']->data_admissao) ? date('d/m/Y', strtotime($data['colaborador']->data_admissao)) : ''; ?></div></div>
                                <div class="row"><div class="col-lg-3 col-md-4 label">Data de Demissão</div><div class="col-lg-9 col-md-8"><?= !empty($data['colaborador']->data_demissao) ? date('d/m/Y', strtotime($data['colaborador']->data_demissao)) : 'N/A'; ?></div></div>
                                <div class="row"><div class="col-lg-3 col-md-4 label">Nível de Educação</div><div class="col-lg-9 col-md-8"><?= htmlspecialchars($data['colaborador']->nivel_educacao ?? ''); ?></div></div>
                                <div class="row"><div class="col-lg-3 col-md-4 label">Formação</div><div class="col-lg-9 col-md-8"><?= htmlspecialchars($data['colaborador']->formacao ?? ''); ?></div></div>
                            </div>
                            
                            <div class="tab-pane fade pt-3" id="profile-files">
                                <h5 class="card-title">Arquivos Associados</h5>
                                <?php if (empty($data['files'])): ?>
                                    <div class="alert alert-secondary">Nenhum arquivo associado a este colaborador.</div>
                                <?php else: ?>
                                    <table class="table table-sm table-striped">
                                        <thead><tr><th>Nome do Arquivo</th><th>Enviado por</th><th>Data</th><th>Ação</th></tr></thead>
                                        <tbody>
                                    
                                            <?php foreach($data['files'] as $file): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($file->nome_exibicao); ?></td>
                                                <td><?= htmlspecialchars($file->nome_usuario); ?></td>
                                                <td><?= date('d/m/Y', strtotime($file->data_upload)); ?></td>
                                                <td>
                                                    <?php if (in_array('view_pdfs', $_SESSION['user_permissions'])): ?>
                                                        <a href="<?= URLROOT; ?>/pdfs/viewPdf/<?= $file->id; ?>" target="_blank" class="btn btn-info btn-sm">Ver</a>
                                                    <?php endif; ?>
                                                    <?php if (in_array('delete_pdf', $_SESSION['user_permissions'])): ?>
                                                    <form action="<?= URLROOT; ?>/pdfs/deletePdf/<?= $file->id; ?>" method="POST" class="d-inline">
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este arquivo?');">Excluir</button>
                                                    </form>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php endif; ?>

                                <?php if (in_array('upload_pdf', $_SESSION['user_permissions'])): ?>
                                <a href="<?= URLROOT; ?>/pdfs/colaborador/<?= $data['colaborador']->id; ?>" class="btn btn-primary mt-3"><i class="bi bi-upload"></i> Gerenciar Arquivos</a>
                                <?php endif; ?>
                            </div>

                            <div class="tab-pane fade pt-3" id="profile-system">
                                <h5 class="card-title">Informações do Registro</h5>
                                <div class="row"><div class="col-lg-3 col-md-4 label">Data de Criação</div><div class="col-lg-9 col-md-8"><?= !empty($data['colaborador']->criado_em) ? date('d/m/Y H:i:s', strtotime($data['colaborador']->criado_em)) : ''; ?></div></div>
                                <div class="row"><div class="col-lg-3 col-md-4 label">Última Atualização</div><div class="col-lg-9 col-md-8"><?= !empty($data['colaborador']->atualizado_em) ? date('d/m/Y H:i:s', strtotime($data['colaborador']->atualizado_em)) : ''; ?></div></div>
                            </div>

                        </div></div>
                </div>
            </div>
        </div>
    </section>

</main><?php require APPROOT . '/views/inc/footer.php'; ?>