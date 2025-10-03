document.addEventListener('DOMContentLoaded', function() {
    const chatDropdownToggle = document.getElementById('chat-dropdown-toggle');
    const messagesDropdownMenu = document.querySelector('.dropdown-menu.messages');
    const urlRoot = document.body.dataset.urlroot;
    const currentUserId = document.body.dataset.userId;
    
    if (!chatDropdownToggle || !urlRoot || !currentUserId) {
        // Se não houver botão de chat ou dados essenciais, não faz nada.
        return;
    }

    let unreadPollingInterval;

    function startUnreadPolling() {
        if (unreadPollingInterval) {
            clearInterval(unreadPollingInterval);
        }
        unreadPollingInterval = setInterval(fetchUnreadCounts, 5000); // Verifica a cada 5 segundos
        fetchUnreadCounts(); // Busca imediatamente ao iniciar
    }

    function fetchUnreadCounts() {
        fetch(`${urlRoot}/chatpage/unreadCounts`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateUnreadBadge(data.counts.total);
                }
            })
            .catch(error => console.error('Erro ao buscar contagens de não lidas:', error));
    }

    function fetchDropdownContent() {
        // Esta função agora é chamada pelo evento de clique
        fetch(`${urlRoot}/chatpage/fetchDropdownContent`)
            .then(response => response.json())
            .then(data => {
                if (data.success && messagesDropdownMenu) {
                    messagesDropdownMenu.innerHTML = data.html;
                    updateTimestamps(); // Atualiza os timestamps com timeago.js
                }
            })
            .catch(error => console.error('Erro ao buscar conteúdo do dropdown:', error));
    }

    // Adiciona um listener para quando o dropdown for aberto
    chatDropdownToggle.addEventListener('click', function() {
        fetchDropdownContent();
    });

    function updateTimestamps() {
        const timeagoNodes = document.querySelectorAll('.timeago');
        if (timeagoNodes.length > 0) {
            timeago.render(timeagoNodes, 'pt_BR');
        }
    }

    function updateUnreadBadge(totalCount) {
        const totalBadge = chatDropdownToggle.querySelector('.badge');
        if (totalCount > 0) {
            if (totalBadge) {
                totalBadge.textContent = totalCount;
            } else {
                const newBadge = document.createElement('span');
                newBadge.className = 'badge bg-success badge-number';
                newBadge.textContent = totalCount;
                chatDropdownToggle.appendChild(newBadge);
            }
        } else if (totalBadge) {
            totalBadge.remove();
        }
    }

    // Inicia o polling para contagem de mensagens não lidas
    startUnreadPolling(); // Para o dropdown do header
    updateTimestamps(); // Para a carga inicial da página
});
