

<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <?php if (in_array('view_dashboard', $_SESSION['user_permissions'])): ?>
            <li class="nav-item">
                <a class="nav-link " href="<?= URLROOT; ?>/dashboard">
                    <i class="bi bi-grid"></i><span>Dashboard</span>
                </a>
            </li>
        <?php endif; ?>

        <?php if (in_array('edit_own_profile', $_SESSION['user_permissions'])): ?>
            <li class="nav-item">
                <a class="nav-link collapsed" href="<?= URLROOT; ?>/users/profile">
                    <i class="bi bi-person"></i><span>Meu Perfil</span>
                </a>
            </li>
        <?php endif; ?>

        <?php if (in_array('use_chat', $_SESSION['user_permissions'])): ?>
            <li class="nav-item">
                <a class="nav-link collapsed" href="<?= URLROOT; ?>/messages">
                    <i class="bi bi-chat-dots"></i><span>Chat Zut</span>
                </a>
            </li>
        <?php endif; ?>


        <li class="nav-heading">Gerenciamento</li>

        <?php if (in_array('view_collaborators_list', $_SESSION['user_permissions'])): ?>

            <li class="nav-item">
                <a class="nav-link collapsed" href="<?= URLROOT; ?>/colaboradores">
                    <i class="bi bi-person-badge"></i><span>Colaboradores</span>
                </a>
            </li>
        <?php endif; ?>


        <?php if (in_array('manage_groups_permissions', $_SESSION['user_permissions'])): ?>
            <li class="nav-heading">Sistema</li>

            <li class="nav-item">
                <a class="nav-link collapsed" href="<?= URLROOT; ?>/users">
                    <i class="bi bi-people"></i><span>Usuários do Sistema</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link collapsed" href="<?= URLROOT; ?>/groups">
                    <i class="bi bi-shield-lock"></i><span>Grupos e Permissões</span>
                </a>
            </li>


            <li class="nav-item">
                <a class="nav-link collapsed" href="<?= URLROOT; ?>/departamentos">
                    <i class="bi bi-building"></i><span>Departamentos</span>
                </a>
            </li>


            <li class="nav-item">
                <a class="nav-link collapsed" href="<?= URLROOT; ?>/unidades">
                    <i class="bi bi-pin-map"></i><span>Unidades</span>
                </a>
            </li>


        <?php endif; ?>

        <?php if (in_array('view_reports', $_SESSION['user_permissions'])): ?>
            <li class="nav-item">
                <a class="nav-link collapsed" href="<?= URLROOT; ?>/reports/activityLog">
                    <i class="bi bi-bar-chart"></i><span>Relatório de Atividades</span>
                </a>
            </li>
        <?php endif; ?>

    </ul>
</aside>