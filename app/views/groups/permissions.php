<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/sidebar.php'; ?>
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Gerenciar Permissões do Grupo: <?= htmlspecialchars($data['group']->nome_grupo); ?></h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= URLROOT; ?>/groups">Grupos</a></li>
                <li class="breadcrumb-item active">Permissões</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Selecione as permissões para este grupo</h5>

                <?php
                // Agrupa as permissões por categoria
                $category_map = [
                    'user' => 'Usuários do Sistema',
                    'users' => 'Usuários do Sistema',
                    'collaborator' => 'Colaboradores',
                    'collaborators' => 'Colaboradores',
                    'pdf' => 'Documentos (PDFs)',
                    'pdfs' => 'Documentos (PDFs)',
                    'group' => 'Grupos',
                    'groups' => 'Grupos',
                    'dashboard' => 'Painel Principal',
                    'report' => 'Relatórios',
                    'reports' => 'Relatórios'
                ];
                $grouped_permissions = [];

                foreach($data['all_permissions'] as $permission) {
                    $parts = explode('_', $permission->chave_permissao);
                    $category_key = $parts[1] ?? $parts[0];
                    $category = $category_map[$category_key] ?? 'Outras Permissões';
                    $grouped_permissions[$category][] = $permission;
                }
                ksort($grouped_permissions); // Ordena as categorias alfabeticamente
                ?>

                <form action="<?= URLROOT; ?>/groups/permissions/<?= $data['group']->id; ?>" method="POST">
                    <?php foreach($grouped_permissions as $category => $permissions): ?>
                        <fieldset class="mb-4 border p-3 rounded">
                            <legend class="card-title pb-0 fs-6 float-none w-auto px-2">
                                <?= $category; ?> <button type="button" class="btn btn-link btn-sm py-0 toggle-all" data-category-id="cat-<?= md5($category); ?>">Marcar/Desmarcar Todos</button>
                            </legend>
                            <div class="row">
                                <?php foreach($permissions as $permission): ?>
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="permissions[]" value="<?= $permission->id; ?>" id="perm_<?= $permission->id; ?>"
                                                <?= in_array($permission->id, $data['group_permissions'] ?? []) ? 'checked' : ''; ?>
                                            >
                                            <label class="form-check-label small" for="perm_<?= $permission->id; ?>">
                                                <?= htmlspecialchars($permission->chave_permissao); ?>
                                                <small class="d-block text-muted"><?= htmlspecialchars($permission->descricao ?? 'Sem descrição'); ?></small>
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </fieldset>
                    <?php endforeach; ?>

                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary">Salvar Permissões</button>
                        <a href="<?= URLROOT; ?>/groups" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>
<?php require APPROOT . '/views/inc/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.toggle-all').forEach(function(button) {
        button.addEventListener('click', function() {
            const categoryId = this.getAttribute('data-category-id');
            const fieldset = this.closest('fieldset');
            const checkboxes = fieldset.querySelectorAll('.form-check-input');
            
            if (checkboxes.length === 0) {
                return;
            }

            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = !allChecked;
            });
        });
    });
});
</script>