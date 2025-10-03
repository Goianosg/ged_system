<?php if ($data['partner']) : ?>
    <div class="chat-box" id="chat-box">
        <?php if (!empty($data['messages'])) : ?>
            <?php foreach ($data['messages'] as $message) : ?>
                <div class="d-flex align-items-start mb-3 message-row <?= $message->id_remetente == $_SESSION['user_id'] ? 'justify-content-end' : '' ?>" data-message-id="<?= $message->id; ?>" data-sender-id="<?= $message->id_remetente; ?>">
                    <div class="d-inline-block p-2 rounded message-bubble" style="background-color: <?= $message->id_remetente == $_SESSION['user_id'] ? '#dcf8c6' : '#f1f0f0' ?>; max-width: 70%;">
                        <p class="mb-1"><?= htmlspecialchars($message->conteudo) ?></p>
                        <div class="d-flex justify-content-end align-items-center mt-1">
                            <small class="text-muted me-2" style="font-size: 0.75rem;"><?= date('H:i', strtotime($message->timestamp)) ?></small>
                            <?php if ($message->id_remetente == $_SESSION['user_id']): ?>
                                <i class="bi bi-trash text-danger delete-message-btn" style="cursor: pointer; font-size: 0.8rem;" title="Deletar mensagem"></i>
                            <?php endif; ?>
                        </div>
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