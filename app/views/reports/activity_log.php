<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/sidebar.php'; ?>

<main id="main" class="main">
    <div class="pagetitle"><h1>Relatório de Atividades</h1></div>
    <section class="section"><div class="card"><div class="card-body">
        <h5 class="card-title">Trilha de Auditoria do Sistema</h5>
        <table class="table table-striped table-hover">
            <thead><tr><th>Data/Hora</th><th>Usuário</th><th>Ação</th><th>Detalhes</th><th>IP</th></tr></thead>
            <tbody>
                <?php foreach($data['logs'] as $log): ?>
                <tr>
                    <td><?= date('d/m/Y H:i:s', strtotime($log->timestamp)); ?></td>
                    <td><?= htmlspecialchars($log->nome_usuario ?? 'Sistema'); ?></td>
                    <td><span class="badge bg-primary"><?= htmlspecialchars($log->acao); ?></span></td>
                    <td><?= htmlspecialchars($log->detalhes); ?></td>
                    <td><?= htmlspecialchars($log->ip_address); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div></div></section>
</main>
<?php require APPROOT . '/views/inc/footer.php'; ?>

