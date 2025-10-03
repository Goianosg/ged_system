document.addEventListener('DOMContentLoaded', function() {
    // Máscara e Validação de CPF
    const cpfInput = document.getElementById('cpf');
    if (cpfInput) {
        cpfInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            e.target.value = value;

            // Adiciona validação em tempo real
            const cpfOnlyNumbers = value.replace(/\D/g, '');
            if (cpfOnlyNumbers.length === 11) {
                if (validateCPF(cpfOnlyNumbers)) {
                    cpfInput.classList.remove('is-invalid');
                    cpfInput.setCustomValidity('');
                } else {
                    cpfInput.classList.add('is-invalid');
                    cpfInput.setCustomValidity('CPF inválido.');
                }
            }
        });
    }

    function validateCPF(cpf) {
        let sum;
        let remainder;
        sum = 0;
        if (cpf == "00000000000") return false;

        for (let i = 1; i <= 9; i++) {
            sum = sum + parseInt(cpf.substring(i - 1, i)) * (11 - i);
        }
        remainder = (sum * 10) % 11;

        if ((remainder == 10) || (remainder == 11)) remainder = 0;
        if (remainder != parseInt(cpf.substring(9, 10))) return false;

        sum = 0;
        for (let i = 1; i <= 10; i++) {
            sum = sum + parseInt(cpf.substring(i - 1, i)) * (12 - i);
        }
        remainder = (sum * 10) % 11;

        if ((remainder == 10) || (remainder == 11)) remainder = 0;
        if (remainder != parseInt(cpf.substring(10, 11))) return false;
        return true;
    }

    // Máscara de Telefone
    const telInput = document.getElementById('telefone');
    if (telInput) {
        telInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/^(\d{2})(\d)/g, '($1) $2');
            value = value.replace(/(\d)(\d{4})$/, '$1-$2');
            e.target.value = value;
        });
    }

    // Focar na aba com erro de validação
    const firstInvalidField = document.querySelector('.is-invalid');
    if (firstInvalidField) {
        const tabPane = firstInvalidField.closest('.tab-pane');
        if (tabPane) {
            const tabId = tabPane.id;
            const tabButton = document.querySelector(`button[data-bs-target="#${tabId}"]`);
            if (tabButton) {
                const tab = new bootstrap.Tab(tabButton);
                tab.show();
            }
        }
    }
});