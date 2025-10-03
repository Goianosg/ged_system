<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/sidebar.php'; ?>

<?php
$isEdit = isset($data['colaborador']);
$colaborador = $data['colaborador'] ?? null;
$pageTitle = $isEdit ? 'Editar Colaborador' : 'Adicionar Colaborador';
$pageAction = $isEdit ? 'Editar' : 'Adicionar';
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1><?= $pageTitle; ?></h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= URLROOT; ?>/colaboradores">Colaboradores</a></li>
                <li class="breadcrumb-item active"><?= $pageAction; ?></li>
            </ol>
        </nav>
    </div>
    <section class="section profile">
        <form action="<?= URLROOT; ?>/colaboradores/<?= isset($data['colaborador']) ? 'edit/' . $data['colaborador']->id : 'create'; ?>" method="POST" enctype="multipart/form-data" novalidate>
            <div class="row">
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                            <img src="<?= URLROOT . ($colaborador->foto_path ?? '/assets/img/profile-img.jpg'); ?>" alt="Profile" class="rounded-circle mb-3">
                            <h2 class="mb-1"><?= htmlspecialchars($colaborador->nome_completo ?? 'Novo Colaborador'); ?></h2>
                            <h3><?= htmlspecialchars($colaborador->cargo ?? 'Cargo a ser definido'); ?></h3>
                        </div>
                        <div class="card-body">
                             <label for="foto" class="form-label">Foto do Perfil</label>
                             <input type="file" name="foto" id="foto" class="form-control">
                             <?php if ($isEdit): ?>
                                <input type="hidden" name="foto_existente" value="<?= htmlspecialchars($colaborador->foto_path ?? ''); ?>">
                             <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-body pt-3">
                            <ul class="nav nav-tabs nav-tabs-bordered" role="tablist">
                                <li class="nav-item" role="presentation"><button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#edit-personal" role="tab" aria-selected="true">Dados Pessoais</button></li>
                                <li class="nav-item" role="presentation"><button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#edit-contact" role="tab" aria-selected="false">Contato e Endereço</button></li>
                                <li class="nav-item" role="presentation"><button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#edit-professional" role="tab" aria-selected="false">Dados Profissionais</button></li>
                            </ul>
                            <div class="tab-content pt-2">
                                <!-- Aba Dados Pessoais -->
                                <div class="tab-pane fade show active pt-3" id="edit-personal">
                                    <div class="row g-3">
                                        <div class="col-md-8"><label for="nome_completo" class="form-label">Nome Completo</label><input type="text" name="nome_completo" id="nome_completo" class="form-control <?= !empty($data['error_nome_completo']) ? 'is-invalid' : ''; ?>" value="<?= htmlspecialchars($data['nome_completo'] ?? $colaborador->nome_completo ?? ''); ?>" required><div class="invalid-feedback"><?= $data['error_nome_completo'] ?? ''; ?></div></div>
                                        <div class="col-md-4"><label for="data_nascimento" class="form-label">Data de Nascimento</label><input type="date" name="data_nascimento" id="data_nascimento" class="form-control" value="<?= $data['data_nascimento'] ?? $colaborador->data_nascimento ?? ''; ?>"></div>
                                        <div class="col-md-6"><label for="cpf" class="form-label">CPF</label><input type="text" name="cpf" id="cpf" class="form-control <?= !empty($data['error_cpf']) ? 'is-invalid' : ''; ?>" value="<?= htmlspecialchars($data['cpf'] ?? $colaborador->cpf ?? ''); ?>" required><div class="invalid-feedback"><?= $data['error_cpf'] ?? ''; ?></div></div>
                                        <div class="col-md-6"><label for="rg" class="form-label">RG</label><input type="text" name="rg" id="rg" class="form-control <?= !empty($data['error_rg']) ? 'is-invalid' : ''; ?>" value="<?= htmlspecialchars($data['rg'] ?? $colaborador->rg ?? ''); ?>"><div class="invalid-feedback"><?= $data['error_rg'] ?? ''; ?></div></div>
                                        <div class="col-md-4"><label for="sexo" class="form-label">Sexo</label><select name="sexo" id="sexo" class="form-select"><option value="Masculino" <?= (($data['sexo'] ?? $colaborador->sexo ?? '') == 'Masculino') ? 'selected' : ''; ?>>Masculino</option><option value="Feminino" <?= (($data['sexo'] ?? $colaborador->sexo ?? '') == 'Feminino') ? 'selected' : ''; ?>>Feminino</option><option value="Outro" <?= (($data['sexo'] ?? $colaborador->sexo ?? '') == 'Outro') ? 'selected' : ''; ?>>Outro</option></select></div>
                                        <div class="col-md-4"><label for="nacionalidade" class="form-label">Nacionalidade</label><input type="text" name="nacionalidade" id="nacionalidade" class="form-control" value="<?= htmlspecialchars($data['nacionalidade'] ?? $colaborador->nacionalidade ?? ''); ?>"></div>
                                        <div class="col-md-4"><label for="estado_civil" class="form-label">Estado Civil</label><select name="estado_civil" id="estado_civil" class="form-select"><option value="Solteiro(a)" <?= (($data['estado_civil'] ?? $colaborador->estado_civil ?? '') == 'Solteiro(a)') ? 'selected' : ''; ?>>Solteiro(a)</option><option value="Casado(a)" <?= (($data['estado_civil'] ?? $colaborador->estado_civil ?? '') == 'Casado(a)') ? 'selected' : ''; ?>>Casado(a)</option><option value="Divorciado(a)" <?= (($data['estado_civil'] ?? $colaborador->estado_civil ?? '') == 'Divorciado(a)') ? 'selected' : ''; ?>>Divorciado(a)</option><option value="Viúvo(a)" <?= (($data['estado_civil'] ?? $colaborador->estado_civil ?? '') == 'Viúvo(a)') ? 'selected' : ''; ?>>Viúvo(a)</option></select></div>
                                        <div class="col-md-4"><label for="numero_dependentes" class="form-label">Dependentes</label><input type="number" name="numero_dependentes" id="numero_dependentes" class="form-control" value="<?= $data['numero_dependentes'] ?? $colaborador->numero_dependentes ?? 0; ?>"></div>
                                    </div>
                                </div>

                                <!-- Aba Contato e Endereço -->
                                <div class="tab-pane fade pt-3" id="edit-contact">
                                    <div class="row g-3">
                                        <div class="col-md-6"><label for="email" class="form-label">Email</label><input type="email" name="email" id="email" class="form-control <?= !empty($data['error_email']) ? 'is-invalid' : ''; ?>" value="<?= htmlspecialchars($data['email'] ?? $colaborador->email ?? ''); ?>" required><div class="invalid-feedback"><?= $data['error_email'] ?? ''; ?></div></div>
                                        <div class="col-md-6"><label for="telefone" class="form-label">Telefone</label><input type="text" name="telefone" id="telefone" class="form-control" value="<?= htmlspecialchars($data['telefone'] ?? $colaborador->telefone ?? ''); ?>"></div>
                                        <div class="col-md-8"><label for="endereco" class="form-label">Endereço</label><input type="text" name="endereco" id="endereco" class="form-control" value="<?= htmlspecialchars($data['endereco'] ?? $colaborador->endereco ?? ''); ?>"></div>
                                        <div class="col-md-4"><label for="cep" class="form-label">CEP</label><input type="text" name="cep" id="cep" class="form-control" value="<?= htmlspecialchars($data['cep'] ?? $colaborador->cep ?? ''); ?>"></div>
                                        <div class="col-md-4"><label for="cidade" class="form-label">Cidade</label><input type="text" name="cidade" id="cidade" class="form-control" value="<?= htmlspecialchars($data['cidade'] ?? $colaborador->cidade ?? ''); ?>"></div>
                                        <div class="col-md-4"><label for="estado" class="form-label">Estado</label><input type="text" name="estado" id="estado" class="form-control" value="<?= htmlspecialchars($data['estado'] ?? $colaborador->estado ?? ''); ?>"></div>
                                        <div class="col-md-4"><label for="pais" class="form-label">País</label><input type="text" name="pais" id="pais" class="form-control" value="<?= htmlspecialchars($data['pais'] ?? $colaborador->pais ?? 'Brasil'); ?>"></div>
                                    </div>
                                </div>

                                <!-- Aba Dados Profissionais -->
                                <div class="tab-pane fade pt-3" id="edit-professional">
                                    <div class="row g-3">
                                        <div class="col-md-4"><label for="cargo" class="form-label">Cargo</label><input type="text" name="cargo" id="cargo" class="form-control <?= !empty($data['error_cargo']) ? 'is-invalid' : ''; ?>" value="<?= htmlspecialchars($data['cargo'] ?? $colaborador->cargo ?? ''); ?>" required><div class="invalid-feedback"><?= $data['error_cargo'] ?? ''; ?></div></div>
                                        <div class="col-md-4"><label for="guarda" class="form-label">Função Guarda</label><input type="text" name="guarda" id="guarda" class="form-control <?= !empty($data['error_guarda']) ? 'is-invalid' : ''; ?>" value="<?= htmlspecialchars($data['guarda'] ?? $colaborador->guarda ?? ''); ?>" required><div class="invalid-feedback"><?= $data['error_guarda'] ?? ''; ?></div></div>
                                        <div class="col-md-4"><label for="status" class="form-label">Status</label><select name="status" id="status" class="form-select"><option value="Ativo" <?= (($data['status'] ?? $colaborador->status ?? 'Ativo') == 'Ativo') ? 'selected' : ''; ?>>Ativo</option><option value="Inativo" <?= (($data['status'] ?? $colaborador->status ?? '') == 'Inativo') ? 'selected' : ''; ?>>Inativo</option><option value="Suspenso" <?= (($data['status'] ?? $colaborador->status ?? '') == 'Suspenso') ? 'selected' : ''; ?>>Suspenso</option></select></div>
                                        <div class="col-md-6"><label for="data_admissao" class="form-label">Data de Admissão</label><input type="date" name="data_admissao" id="data_admissao" class="form-control" value="<?= $data['data_admissao'] ?? $colaborador->data_admissao ?? ''; ?>" required></div>
                                        <div class="col-md-6"><label for="data_demissao" class="form-label">Data de Demissão (opcional)</label><input type="date" name="data_demissao" id="data_demissao" class="form-control" value="<?= $data['data_demissao'] ?? $colaborador->data_demissao ?? ''; ?>"></div>
                                        <div class="col-md-6"><label for="departamento_id" class="form-label">Departamento</label><select name="departamento_id" id="departamento_id" class="form-select <?= !empty($data['error_departamento_id']) ? 'is-invalid' : ''; ?>" required><option selected disabled value="">Escolha...</option><?php foreach($data['departamentos'] as $depto): ?><option value="<?= $depto->id; ?>" <?= (($data['departamento_id'] ?? $colaborador->departamento_id ?? '') == $depto->id) ? 'selected' : ''; ?>><?= htmlspecialchars($depto->nome); ?></option><?php endforeach; ?></select><div class="invalid-feedback"><?= $data['error_departamento_id'] ?? ''; ?></div></div>
                                        <div class="col-md-6"><label for="unidade_id" class="form-label">Unidade</label><select name="unidade_id" id="unidade_id" class="form-select <?= !empty($data['error_unidade_id']) ? 'is-invalid' : ''; ?>" required><option selected disabled value="">Escolha...</option><?php foreach($data['unidades'] as $unidade): ?><option value="<?= $unidade->id; ?>" <?= (($data['unidade_id'] ?? $colaborador->unidade_id ?? '') == $unidade->id) ? 'selected' : ''; ?>><?= htmlspecialchars($unidade->nome); ?></option><?php endforeach; ?></select><div class="invalid-feedback"><?= $data['error_unidade_id'] ?? ''; ?></div></div>
                                        <div class="col-md-6"><label for="nivel_educacao" class="form-label">Nível de Educação</label><select name="nivel_educacao" id="nivel_educacao" class="form-select"><option value="Fundamental" <?= (($data['nivel_educacao'] ?? $colaborador->nivel_educacao ?? '') == 'Fundamental') ? 'selected' : ''; ?>>Fundamental</option><option value="Médio" <?= (($data['nivel_educacao'] ?? $colaborador->nivel_educacao ?? '') == 'Médio') ? 'selected' : ''; ?>>Médio</option><option value="Superior" <?= (($data['nivel_educacao'] ?? $colaborador->nivel_educacao ?? '') == 'Superior') ? 'selected' : ''; ?>>Superior</option><option value="Pós-graduação" <?= (($data['nivel_educacao'] ?? $colaborador->nivel_educacao ?? '') == 'Pós-graduação') ? 'selected' : ''; ?>>Pós-graduação</option><option value="Mestrado" <?= (($data['nivel_educacao'] ?? $colaborador->nivel_educacao ?? '') == 'Mestrado') ? 'selected' : ''; ?>>Mestrado</option><option value="Doutorado" <?= (($data['nivel_educacao'] ?? $colaborador->nivel_educacao ?? '') == 'Doutorado') ? 'selected' : ''; ?>>Doutorado</option></select></div>
                                        <div class="col-md-6"><label for="formacao" class="form-label">Formação</label><input type="text" name="formacao" id="formacao" class="form-control" value="<?= htmlspecialchars($data['formacao'] ?? $colaborador->formacao ?? ''); ?>"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Atualizar' : 'Salvar'; ?> Colaborador</button>
                                <a href="<?= $isEdit ? (URLROOT . '/colaboradores/show/' . $colaborador->id) : (URLROOT . '/colaboradores'); ?>" class="btn btn-secondary">Cancelar</a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
</main>

<?php require APPROOT . '/views/inc/footer.php'; ?>
