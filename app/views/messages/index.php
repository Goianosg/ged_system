<!-- O conteúdo do widget começa aqui. O container principal está no footer.php -->
<div class="chat-header">
    <div class="d-flex align-items-center flex-grow-1">
        <button id="chat-back-btn" class="btn btn-sm me-2" title="Voltar" style="display: none;"><i class="bi bi-arrow-left"></i></button>
        <div id="chat-header-partner-info" class="d-flex align-items-center" style="display: none !important;">
            <img id="chat-header-partner-img" src="" alt="Profile" class="rounded-circle me-2" width="30" height="30">
            <h5 id="chat-header-partner-name" class="chat-title mb-0"></h5>
        </div>
        <h5 id="chat-header-default-title" class="chat-title mb-0 d-flex align-items-center">
            <i class="bi bi-chat-left-text me-2"></i>
        </h5>
    </div>
    <div>
        <button id="chat-close-btn" class="btn-close" aria-label="Close"></button>
    </div>
</div>
<div class="chat-body">
    <div class="chat-users-list">
        <ul class="list-group list-group-flush">
            <?php
                // Cria um mapa de id_remetente => unread_count para fácil acesso
                $unread_map = [];
                if (isset($data['unread_counts'])) {
                    foreach ($data['unread_counts'] as $count_info) {
                        $unread_map[$count_info->id_remetente] = $count_info->unread_count;
                    }
                }
            ?>
            <?php foreach ($data['users'] as $user) : ?>
                <?php if ($user->id != ($_SESSION['user_id'] ?? '')) : ?>
                    <button type="button" data-partner-id="<?= $user->id; ?>" class="list-group-item list-group-item-action d-flex align-items-center chat-user-button">
                        <img src="<?= URLROOT . ($user->foto_path ?? '/assets/img/profile-img.jpg'); ?>" alt="Profile" class="rounded-circle me-3" width="30" height="30">
                        <div class="flex-grow-1 chat-user-name-container">
                            <div class="chat-user-name">
                                <?= htmlspecialchars($user->nome_usuario); ?>
                            </div>
                        </div>
                        <?php if (isset($unread_map[$user->id]) && $unread_map[$user->id] > 0): ?>
                            <span class="badge bg-danger rounded-pill ms-2 chat-user-unread-badge"><?= $unread_map[$user->id]; ?></span>
                        <?php endif; ?>
                    </button>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="chat-conversation" style="display: <?= $data['partner'] ? 'flex' : 'none'; ?>;">
        <?php if ($data['partner']) : ?>
            <div class="chat-box" id="chat-box">
                <?php if (!empty($data['messages'])) : ?>
                    <?php foreach ($data['messages'] as $message) : ?>
                        <div class="d-flex align-items-center mb-2 message-row <?= $message->id_remetente == $_SESSION['user_id'] ? 'justify-content-end' : '' ?>">
                            <?php if ($message->id_remetente != $_SESSION['user_id']) : ?>
                                <img src="<?= URLROOT . ($message->foto_remetente ?? '/assets/img/profile-img.jpg') ?>" alt="Profile" class="rounded-circle me-2" width="30" height="30">
                            <?php endif; ?>
                            <div class="d-inline-block p-2 rounded message-bubble" style="background-color: <?= $message->id_remetente == $_SESSION['user_id'] ? '#dcf8c6' : '#f1f0f0' ?>; max-width: 70%;">
                                <p class="mb-1"><?= htmlspecialchars($message->conteudo) ?></p>
                                <small class="text-muted" style="font-size: 0.75rem;"><?= date('H:i', strtotime($message->timestamp)) ?></small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p class="text-center text-muted mt-3">Inicie a conversa!</p>
                <?php endif; ?>
            </div>
            <div class="chat-form-container" id="chat-form-container">
                <form id="chat-form" action="<?= URLROOT; ?>/chatpage/send" method="POST" data-partner-id="<?= $data['current_partner_id'] ?? ''; ?>">
                    <input type="hidden" name="id_destinatario" value="<?= $data['current_partner_id'] ?? ''; ?>">
                    <div class="input-group">
                        <input type="text" name="conteudo" class="form-control" placeholder="Digite sua mensagem..." required autocomplete="off">
                        <button class="btn btn-primary" type="submit">Enviar</button>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>
<!-- O script do chat e o footer serão carregados globalmente -->
