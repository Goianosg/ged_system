document.addEventListener('DOMContentLoaded', function() {
    const chatToggleButton = document.getElementById('chat-toggle-button');
    const chatWidgetContainer = document.getElementById('chat-container');
    const urlRoot = document.body.dataset.urlroot;
    const currentUserId = document.body.dataset.userId;

    if (!chatToggleButton || !chatWidgetContainer || !urlRoot || !currentUserId) {
        return;
    }

    let isWidgetLoaded = false;
    let pollingInterval;
    let currentPartnerId = null;

    // Função para abrir/fechar o widget
    function toggleChatWidget(event) {
        if (event) event.preventDefault();
        
        const isOpen = chatWidgetContainer.classList.contains('open');
        
        if (!isOpen && !isWidgetLoaded) {
            loadChatWidget();
        }
        
        chatWidgetContainer.classList.toggle('open');

        if (chatWidgetContainer.classList.contains('open')) {
            // Se uma conversa estiver aberta, inicia o polling para ela.
            // Caso contrário, o polling iniciará quando uma conversa for aberta.
            if (currentPartnerId) {
                startPolling();
            }
        } else {
            stopPolling();
        }
    }

    // Carrega o conteúdo inicial do widget (lista de usuários)
    function loadChatWidget() {
        fetch(`${urlRoot}/chatpage/widget`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    chatWidgetContainer.innerHTML = data.html;
                    isWidgetLoaded = true;
                    initializeWidgetEventListeners();
                }
            })
            .catch(error => console.error('Erro ao carregar o widget de chat:', error));
    }

    // Adiciona os listeners aos elementos dentro do widget
    function initializeWidgetEventListeners() {
        const chatCloseBtn = document.getElementById('chat-close-btn');
        if (chatCloseBtn) {
            chatCloseBtn.addEventListener('click', toggleChatWidget);
        }

        const chatBackBtn = document.getElementById('chat-back-btn');
        if (chatBackBtn) {
            chatBackBtn.addEventListener('click', () => showConversationView(false));
        }

        chatWidgetContainer.addEventListener('click', function(event) {
            const userButton = event.target.closest('.chat-user-button');
            if (userButton) {
                event.preventDefault();
                const partnerId = userButton.dataset.partnerId;
                const partnerName = userButton.querySelector('.flex-grow-1').textContent.trim();
                if (partnerId) {
                    loadConversation(partnerId, partnerName);
                }
            }
        });

        // Adiciona o listener para deletar mensagens
        chatWidgetContainer.addEventListener('click', function(event) {
            if (event.target.classList.contains('delete-message-btn')) {
                const messageRow = event.target.closest('.message-row');
                const messageId = messageRow.dataset.messageId;

                if (messageId && messageId !== 'temp' && confirm('Tem certeza que deseja deletar esta mensagem?')) {
                    fetch(`${urlRoot}/chatpage/deleteMessage/${messageId}`, {
                        method: 'POST'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            messageRow.remove();
                        } else {
                            alert('Erro ao deletar a mensagem.');
                            console.error(data.error);
                        }
                    }).catch(error => console.error('Erro:', error));
                }
            }
        });

        // O listener do formulário é adicionado dinamicamente em `loadConversation`
    }

    // Carrega uma conversa específica
    function loadConversation(partnerId, partnerName) {
        stopPolling(); // Para o polling anterior antes de carregar uma nova conversa
        currentPartnerId = partnerId;
        const partnerPhoto = document.querySelector(`.chat-user-button[data-partner-id="${partnerId}"] img`)?.src;

        fetch(`${urlRoot}/chatpage/fetchConversation/${partnerId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const chatConversationDiv = chatWidgetContainer.querySelector('.chat-conversation');
                    if (chatConversationDiv) {
                        chatConversationDiv.innerHTML = data.html;
                        
                        const chatForm = document.getElementById('chat-form');
                        if (chatForm) {
                            chatForm.addEventListener('submit', handleSendMessage);
                        }
                        
                        showConversationView(true, partnerName, partnerPhoto);
                        scrollToBottom();
                        startPolling();
                    }
                }
            })
            .catch(error => console.error('Erro ao carregar a conversa:', error));
    }

    // Alterna a visibilidade entre a lista de usuários e a conversa
    function showConversationView(show, partnerName = '', partnerPhoto = '') {
        const usersList = chatWidgetContainer.querySelector('.chat-users-list');
        const conversation = chatWidgetContainer.querySelector('.chat-conversation');
        const backBtn = document.getElementById('chat-back-btn');
        const defaultTitle = document.getElementById('chat-header-default-title');
        const partnerInfo = document.getElementById('chat-header-partner-info');
        const partnerNameEl = document.getElementById('chat-header-partner-name');
        const partnerImgEl = document.getElementById('chat-header-partner-img');
        
        if (usersList && conversation && backBtn && defaultTitle && partnerInfo && partnerNameEl && partnerImgEl) {
            usersList.style.display = show ? 'none' : 'block';
            conversation.style.display = show ? 'flex' : 'none';
            backBtn.style.display = show ? 'block' : 'none';
            defaultTitle.style.display = show ? 'none' : 'flex'; // Esconde o título "Chat" na conversa
            partnerInfo.style.display = show ? 'flex' : 'none';

            if (!show) {
                // Ao voltar para a lista, para o polling e limpa o ID do parceiro
                stopPolling();
                currentPartnerId = null;
                conversation.innerHTML = '';
            } else {
                partnerNameEl.textContent = partnerName;
                partnerImgEl.src = partnerPhoto;
            }
        }
    }

    // Envia uma mensagem
    function handleSendMessage(e) {
        e.preventDefault();
        const chatForm = e.target;
        const messageInput = chatForm.querySelector('input[name="conteudo"]');
        const content = messageInput.value.trim();

        if (content === '') return;

        const formData = new FormData(chatForm);
        messageInput.value = '';

        appendMessage({
            id_remetente: currentUserId,
            conteudo: content,
            timestamp: new Date().toISOString()
        }, true); // Otimista
        scrollToBottom();

        fetch(`${urlRoot}/chatpage/send`, { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const tempMsgEl = chatWidgetContainer.querySelector(`[data-message-id="temp"]`);
                    if (tempMsgEl) {
                        tempMsgEl.dataset.messageId = data.message.id;
                    }
                } else {
                    console.error('Erro ao enviar mensagem:', data.error);
                }
            })
            .catch(error => console.error('Erro na requisição de envio:', error));
    }

    // Adiciona a mensagem na UI
    function appendMessage(message, isOptimistic = false) {
        const chatBox = document.getElementById('chat-box');
        if (!chatBox) return;

        const isSender = message.id_remetente == currentUserId;
        const timeString = new Date(message.timestamp).toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });

        const messageRow = document.createElement('div');
        messageRow.className = `d-flex align-items-start mb-3 message-row ${isSender ? 'justify-content-end' : ''}`;
        messageRow.dataset.messageId = isOptimistic ? 'temp' : message.id;
        
    
        const messageBubble = document.createElement('div');
        messageBubble.className = 'd-inline-block p-2 rounded message-bubble';
        messageBubble.style.backgroundColor = isSender ? '#dcf8c6' : '#f1f0f0';
        messageBubble.style.maxWidth = '80%';
        messageBubble.innerHTML = `
            <p class="mb-1">${escapeHtml(message.conteudo)}</p>
            <div class="d-flex justify-content-end align-items-center mt-1">
                <small class="text-muted me-2" style="font-size: 0.75rem;">${timeString}</small>
                ${isSender ? `
                    <i class="bi bi-trash text-danger delete-message-btn" style="cursor: pointer; font-size: 0.8rem;" title="Deletar mensagem"></i>
                ` : ''}
            </div>
        `;

        messageRow.appendChild(messageBubble);
        chatBox.appendChild(messageRow);
        
        const startConversationP = chatBox.querySelector('p.text-center');
        if (startConversationP) {
            startConversationP.remove();
        }
    }

    function scrollToBottom() {
        console.log('scrollToBottom');
        const chatBox = document.getElementById('chat-box');
        if (chatBox) {
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    }

    // Funções de polling para o widget
    function startPolling() {
        stopPolling();
        if (!currentPartnerId) return;

        pollingInterval = setInterval(() => {
            // Só busca novas mensagens se o widget estiver aberto
            if (!chatWidgetContainer.classList.contains('open')) return;

            fetch(`${urlRoot}/chatpage/fetchNewMessages/${currentPartnerId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.messages.length > 0) {
                        data.messages.forEach(msg => appendMessage(msg));
                        scrollToBottom();
                        fetch(`${urlRoot}/chatpage/markAsRead/${currentPartnerId}`, { method: 'POST' });
                    }
                })
                .catch(error => console.error('Erro ao buscar novas mensagens:', error));
        }, 3000);
    }

    function stopPolling() {
        if (pollingInterval) clearInterval(pollingInterval);
    }

    function escapeHtml(unsafe) {
        if (typeof unsafe !== 'string') return '';
        return unsafe.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
    }

    // Adiciona o evento de clique ao botão flutuante
    chatToggleButton.addEventListener('click', toggleChatWidget);

    // Atualiza o badge de mensagens não lidas no botão flutuante
    function updateUnreadBadge() {
        fetch(`${urlRoot}/chatpage/unreadCounts`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const totalCount = data.counts.total;
                    const badge = chatToggleButton.querySelector('.badge');
                    if (totalCount > 0) {
                        if (badge) {
                            badge.textContent = totalCount;
                        } else {
                            const newBadge = document.createElement('span');
                            newBadge.className = 'badge bg-danger rounded-pill';
                            newBadge.textContent = totalCount;
                            chatToggleButton.appendChild(newBadge);
                        }
                    } else if (badge) {
                        badge.remove();
                    }
                }
            });
    }
    // Verifica a cada 10 segundos
    setInterval(updateUnreadBadge, 10000);
    updateUnreadBadge();
});
