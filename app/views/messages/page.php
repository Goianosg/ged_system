<main id="main" class="main">
    <div class="pagetitle">
        <h1>ZUT</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= URLROOT; ?>/dashboard">Dashboard</a></li>
                <li class="breadcrumb-item active">Chat ZuT</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card" id="chat-page-container">
                    <div class="card-body d-flex p-0">

                        <!-- Lista de Usuários -->
                        <div class="chat-users-list-page border-end">
                            <div class="p-3 border-bottom">
                                <h5 class="card-title p-0 m-0">Usuários</h5>
                            </div>
                            <ul class="list-group list-group-flush">
                                <?php foreach($data['users'] as $user): ?>
                                    <?php if ($user->id != $_SESSION['user_id']): ?>
                                        <a href="<?= URLROOT; ?>/messages/conversation/<?= $user->id; ?>" class="list-group-item list-group-item-action d-flex align-items-center <?= ($data['current_partner_id'] == $user->id) ? 'active' : ''; ?>">
                                            <img src="<?= URLROOT . ($user->foto_path ?? '/assets/img/profile-img.jpg'); ?>" alt="Profile" class="rounded-circle me-2" width="40" height="40">
                                            <div class="flex-grow-1">
                                                <?= htmlspecialchars($user->nome_usuario); ?>
                                            </div>
                                        </a>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <!-- Área da Conversa -->
                        <div class="chat-conversation-page d-flex flex-column">
                            <?php if ($data['partner']): ?>
                                <!-- Cabeçalho da Conversa -->
                                <div class="chat-header-page p-3 border-bottom d-flex align-items-center">
                                    <button id="toggle-users-sidebar" class="btn btn-sm me-3" title="Mostrar/Esconder Usuários">
                                        <i class="bi bi-people"></i>
                                    </button>
                                    <img src="<?= URLROOT . ($data['partner']->foto_path ?? '/assets/img/profile-img.jpg'); ?>" alt="Profile" class="rounded-circle me-2" width="40" height="40">
                                    <h5 class="m-0"><?= htmlspecialchars($data['partner']->nome_usuario); ?></h5>
                                </div>

                                <!-- Caixa de Mensagens -->
                                <div class="chat-box-page flex-grow-1 p-3">
                                    <?php if (empty($data['messages'])): ?>
                                        <p class="text-center text-muted mt-3">Inicie a conversa!</p>
                                    <?php else: ?>
                                        <?php foreach($data['messages'] as $message): ?>
                                            <div class="d-flex align-items-start mb-3 message-row <?= $message->id_remetente == $_SESSION['user_id'] ? 'justify-content-end' : '' ?>" data-message-id="<?= $message->id; ?>">
                                                <?php if ($message->id_remetente != $_SESSION['user_id']): ?>
                                                    <img src="<?= URLROOT . ($message->foto_remetente ?? '/assets/img/profile-img.jpg') ?>" alt="Profile" class="rounded-circle me-2" width="40" height="40">
                                                <?php endif; ?>
                                                <div class="d-inline-block p-2 rounded message-bubble" style="background-color: <?= $message->id_remetente == $_SESSION['user_id'] ? '#dcf8c6' : '#f1f0f0' ?>; max-width: 70%;">
                                                    <p class="mb-1"><?= htmlspecialchars($message->conteudo) ?></p>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <small class="text-muted" style="font-size: 0.75rem;"><?= date('H:i', strtotime($message->timestamp)) ?></small>
                                                        <?php if ($message->id_remetente == $_SESSION['user_id']): ?>
                                                            <i class="bi bi-trash text-danger ms-2 delete-message-btn" style="cursor: pointer; font-size: 0.8rem;" title="Deletar mensagem"></i>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>

                                <!-- Formulário de Envio -->
                                <div class="chat-form-container-page p-3 border-top">
                                    <form id="chat-form-page" action="<?= URLROOT; ?>/chatpage/send" method="POST">
                                        <input type="hidden" name="id_destinatario" value="<?= $data['current_partner_id']; ?>">
                                        <div class="input-group">
                                            <input type="text" name="conteudo" class="form-control" placeholder="Digite sua mensagem..." required autocomplete="off">
                                            <button class="btn btn-primary" type="submit">Enviar</button>
                                        </div>
                                    </form>
                                </div>
                            <?php else: ?>
                                <!-- Mensagem para selecionar uma conversa -->
                                <div class="d-flex flex-grow-1 justify-content-center align-items-center text-muted">
                                    <h5>Selecione um usuário para começar a conversar</h5>
                                </div>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Adicione este CSS ao seu chat.css ou em uma tag <style> na página -->
<style>
    #chat-page-container .card-body {
        height: 75vh;
    }
    .chat-users-list-page {
        flex: 0 0 300px;
        overflow-y: auto;
        transition: all 0.3s ease-in-out;
        width: 300px;
    }
    .chat-users-list-page.collapsed {
        flex-basis: 0;
        width: 0;
        padding: 0;
        border-right: none !important;
        overflow: hidden;
    }
    .chat-conversation-page { flex-grow: 1; overflow: hidden; }
    .chat-box-page { overflow-y: auto; } 
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatForm = document.getElementById('chat-form-page');
    const messageInput = chatForm ? chatForm.querySelector('input[name="conteudo"]') : null;
    const chatBox = document.querySelector('.chat-box-page');
    const currentUserId = document.body.dataset.userId;
    const urlRoot = document.body.dataset.urlroot;
    const toggleUsersSidebarBtn = document.getElementById('toggle-users-sidebar');
    const usersListSidebar = document.querySelector('.chat-users-list-page');

    // Função para rolar para o final da caixa de chat
    function scrollToBottom() {
        if (chatBox) {
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    }

    // Rola para o final ao carregar a página
    scrollToBottom();

    // Event listener para o botão de recolher a sidebar de usuários
    if (toggleUsersSidebarBtn && usersListSidebar) {
        toggleUsersSidebarBtn.addEventListener('click', () => {
            usersListSidebar.classList.toggle('collapsed');
        });
    }

    if (chatForm) {
        chatForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Impede o recarregamento da página

            const formData = new FormData(chatForm);
            const content = formData.get('conteudo').trim();

            if (content === '') {
                return;
            }

            // Limpa o campo de input imediatamente
            messageInput.value = '';

            // Adiciona a mensagem otimisticamente à UI
            const tempMessageRow = appendMessage({
                id_remetente: currentUserId,
                conteudo: content,
                timestamp: new Date().toISOString()
            });
            scrollToBottom();

            // Envia os dados para o servidor
            fetch(chatForm.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.message) {
                    // Atualiza o ID da mensagem temporária com o ID real do banco de dados
                    if (tempMessageRow) {
                        tempMessageRow.dataset.messageId = data.message.id;
                    }
                } else {
                    console.error('Erro ao enviar mensagem:', data.error);
                    // Opcional: Adicionar lógica para mostrar erro na UI
                }
            })
            .catch(error => console.error('Erro na requisição:', error));
        });
    }

    // Event listener para deletar mensagens
    if (chatBox) {
        chatBox.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('delete-message-btn')) {
                const messageRow = e.target.closest('.message-row');
                const messageId = messageRow.dataset.messageId;

                if (confirm('Tem certeza que deseja deletar esta mensagem?')) {
                    fetch(`${urlRoot}/chatpage/deleteMessage/${messageId}`, {
                        method: 'POST'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            messageRow.remove();
                        } else {
                            alert('Erro ao deletar a mensagem.');
                        }
                    }).catch(error => console.error('Erro:', error));
                }
            }
        });
    }

    // Função para adicionar uma nova mensagem na caixa de chat
    function appendMessage(message) {
        // Remove a mensagem "Inicie a conversa" se existir
        const startConversationP = chatBox.querySelector('p.text-center');
        if (startConversationP) {
            startConversationP.remove();
        }

        const isSender = message.id_remetente == currentUserId;
        const date = new Date(message.timestamp);
        const timeString = date.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });

        const messageRow = document.createElement('div');
        messageRow.className = `d-flex align-items-start mb-3 message-row ${isSender ? 'justify-content-end' : ''}`;
        messageRow.dataset.messageId = message.id || 'temp-' + Date.now(); // ID temporário para a mensagem otimista

        const messageBubble = document.createElement('div');
        messageBubble.className = 'd-inline-block p-2 rounded message-bubble';
        messageBubble.style.backgroundColor = isSender ? '#dcf8c6' : '#f1f0f0';
        messageBubble.style.maxWidth = '70%';
        messageBubble.innerHTML = `
            <p class="mb-1">${escapeHtml(message.conteudo)}</p> 
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted" style="font-size: 0.75rem;">${timeString}</small>
                ${isSender ? `
                    <i class="bi bi-trash text-danger ms-2 delete-message-btn" style="cursor: pointer; font-size: 0.8rem;" title="Deletar mensagem"></i>
                ` : ''}
            </div>
        `;

        messageRow.appendChild(messageBubble);
        chatBox.appendChild(messageRow);
        return messageRow; // Retorna o elemento criado
    }

    function escapeHtml(unsafe) {
        if (typeof unsafe !== 'string') return '';
        return unsafe.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
    }
});
</script>