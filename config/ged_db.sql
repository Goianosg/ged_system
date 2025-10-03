-- Estrutura do banco de dados para o sistema GED

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Tabela: atividades_log
--
CREATE TABLE `atividades_log` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `acao` varchar(50) NOT NULL,
  `detalhes` text,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tabela: grupos
--
CREATE TABLE `grupos` (
  `id` int(11) NOT NULL,
  `nome_grupo` varchar(50) NOT NULL,
  `descricao` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tabela: permissoes
--
CREATE TABLE `permissoes` (
  `id` int(11) NOT NULL,
  `chave_permissao` varchar(50) NOT NULL,
  `descricao` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Inserindo dados para a tabela `permissoes`
--
INSERT INTO `permissoes` (`id`, `chave_permissao`, `descricao`) VALUES
(1, 'view_dashboard', 'Visualizar o dashboard'),
(2, 'manage_users', 'Gerenciar usuários do sistema'),
(3, 'manage_groups_permissions', 'Gerenciar grupos e permissões'),
(4, 'view_reports', 'Visualizar relatórios'),
(5, 'manage_collaborators', 'Gerenciar colaboradores'),
(6, 'upload_pdf', 'Fazer upload de PDFs'),
(7, 'delete_pdf', 'Deletar PDFs'),
(8, 'view_pdf', 'Visualizar PDFs'),
(9, 'view_users_list', 'Visualizar lista de usuários'),
(10, 'create_user', 'Criar novo usuário'),
(11, 'edit_user', 'Editar usuário'),
(12, 'delete_user', 'Deletar usuário'),
(13, 'view_user_details', 'Visualizar detalhes do usuário'),
(14, 'view_collaborators_list', 'Visualizar lista de colaboradores'),
(15, 'create_collaborator', 'Criar novo colaborador'),
(16, 'edit_collaborator', 'Editar colaborador'),
(17, 'delete_collaborator', 'Deletar colaborador'),
(18, 'view_collaborator_details', 'Visualizar detalhes do colaborador'),
(19, 'view_pdfs_list', 'Visualizar lista de PDFs'),
(20, 'create_group', 'Criar novo grupo de permissões'),
(21, 'edit_own_profile', 'Editar o próprio perfil (senha, foto)'),
(22, 'use_chat', 'Permite usar o widget de chat');

--
-- Tabela: grupo_permissoes
--
CREATE TABLE `grupo_permissoes` (
  `id_grupo` int(11) NOT NULL,
  `id_permissao` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tabela: usuarios
--
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome_usuario` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `id_grupo` int(11) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `foto_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tabela: departamentos
--

CREATE TABLE `departamentos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tabela: unidades
--
CREATE TABLE `unidades` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tabela: colaboradores
--
CREATE TABLE `colaboradores` (
  `id` int(11) NOT NULL,
  `nome_completo` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `cpf` varchar(14) NOT NULL,
  `rg` varchar(20) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `sexo` enum('Masculino','Feminino','Outro') DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `cep` varchar(10) DEFAULT NULL,
  `pais` varchar(50) DEFAULT 'Brasil',
  `nacionalidade` varchar(50) DEFAULT NULL,
  `estado_civil` enum('Solteiro(a)','Casado(a)','Divorciado(a)','Viúvo(a)') DEFAULT NULL,
  `numero_dependentes` int(11) DEFAULT '0',
  `nivel_educacao` enum('Fundamental','Médio','Superior','Pós-graduação','Mestrado','Doutorado') DEFAULT NULL,
  `formacao` varchar(100) DEFAULT NULL,
  `foto_path` varchar(255) DEFAULT NULL,
  `cargo` varchar(100) DEFAULT NULL,
  `guarda` varchar(100) DEFAULT NULL,
  `data_demissao` date DEFAULT NULL,
  `data_admissao` date DEFAULT NULL,
  `status` enum('Ativo','Inativo','Suspenso') NOT NULL DEFAULT 'Ativo',
  `departamento_id` int(11) DEFAULT NULL,
  `unidade_id` int(11) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tabela: arquivos
--
CREATE TABLE `arquivos` (
  `id` int(11) NOT NULL,
  `nome_exibicao` varchar(255) NOT NULL,
  `nome_armazenado` varchar(255) NOT NULL,
  `caminho_arquivo` varchar(255) NOT NULL,
  `id_usuario_upload` int(11) NOT NULL,
  `id_grupo_pertence` int(11) NOT NULL,
  `id_colaborador` int(11) DEFAULT NULL,
  `data_upload` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tabela: mensagens
--
CREATE TABLE `mensagens` (
  `id` int(11) NOT NULL,
  `id_remetente` int(11) NOT NULL,
  `id_destinatario` int(11) NOT NULL,
  `conteudo` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lido` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para as tabelas
--
ALTER TABLE `atividades_log` ADD PRIMARY KEY (`id`), ADD KEY `id_usuario` (`id_usuario`);
ALTER TABLE `grupos` ADD PRIMARY KEY (`id`);
ALTER TABLE `permissoes` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `chave_permissao` (`chave_permissao`);
ALTER TABLE `grupo_permissoes` ADD PRIMARY KEY (`id_grupo`,`id_permissao`), ADD KEY `id_permissao` (`id_permissao`);
ALTER TABLE `usuarios` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `nome_usuario` (`nome_usuario`), ADD UNIQUE KEY `email` (`email`), ADD KEY `id_grupo` (`id_grupo`);
ALTER TABLE `departamentos` ADD PRIMARY KEY (`id`);
ALTER TABLE `unidades` ADD PRIMARY KEY (`id`);
ALTER TABLE `colaboradores` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `email` (`email`), ADD UNIQUE KEY `cpf` (`cpf`), ADD KEY `departamento_id` (`departamento_id`), ADD KEY `unidade_id` (`unidade_id`);
ALTER TABLE `mensagens` ADD PRIMARY KEY (`id`), ADD KEY `id_remetente` (`id_remetente`), ADD KEY `id_destinatario` (`id_destinatario`);
ALTER TABLE `arquivos` ADD PRIMARY KEY (`id`), ADD KEY `id_usuario_upload` (`id_usuario_upload`), ADD KEY `id_grupo_pertence` (`id_grupo_pertence`), ADD KEY `id_colaborador` (`id_colaborador`);

--
-- AUTO_INCREMENT para as tabelas
--
ALTER TABLE `atividades_log` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `grupos` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `permissoes` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
ALTER TABLE `usuarios` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `departamentos` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `unidades` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `colaboradores` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `mensagens` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `arquivos` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints (Chaves Estrangeiras)
--
ALTER TABLE `atividades_log` ADD CONSTRAINT `atividades_log_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;
ALTER TABLE `grupo_permissoes` ADD CONSTRAINT `grupo_permissoes_ibfk_1` FOREIGN KEY (`id_grupo`) REFERENCES `grupos` (`id`) ON DELETE CASCADE, ADD CONSTRAINT `grupo_permissoes_ibfk_2` FOREIGN KEY (`id_permissao`) REFERENCES `permissoes` (`id`) ON DELETE CASCADE;
ALTER TABLE `usuarios` ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_grupo`) REFERENCES `grupos` (`id`) ON DELETE SET NULL;
ALTER TABLE `colaboradores` ADD CONSTRAINT `colaboradores_ibfk_1` FOREIGN KEY (`departamento_id`) REFERENCES `departamentos` (`id`) ON DELETE SET NULL, ADD CONSTRAINT `colaboradores_ibfk_2` FOREIGN KEY (`unidade_id`) REFERENCES `unidades` (`id`) ON DELETE SET NULL;
ALTER TABLE `mensagens` ADD CONSTRAINT `mensagens_ibfk_1` FOREIGN KEY (`id_remetente`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE, ADD CONSTRAINT `mensagens_ibfk_2` FOREIGN KEY (`id_destinatario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
ALTER TABLE `arquivos` ADD CONSTRAINT `arquivos_ibfk_1` FOREIGN KEY (`id_usuario_upload`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE, ADD CONSTRAINT `arquivos_ibfk_2` FOREIGN KEY (`id_grupo_pertence`) REFERENCES `grupos` (`id`) ON DELETE CASCADE, ADD CONSTRAINT `arquivos_ibfk_3` FOREIGN KEY (`id_colaborador`) REFERENCES `colaboradores` (`id`) ON DELETE SET NULL;

-- Inserindo dados iniciais
-- usuario admin senha 123456
INSERT INTO `grupos` (`id`, `nome_grupo`, `descricao`) VALUES (1, 'Admin', 'Administradores do Sistema') ON DUPLICATE KEY UPDATE nome_grupo=nome_grupo, descricao=descricao;
INSERT INTO `grupo_permissoes` (`id_grupo`, `id_permissao`) VALUES (1, 1), (1, 2), (1, 3), (1, 4), (1, 5), (1, 6), (1, 7), (1, 8), (1, 9), (1, 10), (1, 11), (1, 12), (1, 13), (1, 14), (1, 15), (1, 16), (1, 17), (1, 18), (1, 19), (1, 20), (1, 21), (1, 22);
INSERT INTO `usuarios` (`id`, `nome_usuario`, `email`, `senha`, `id_grupo`, `criado_em`) VALUES (1, 'admin', 'admin@example.com', '$2y$10$65BfsIeUk/emoRgQNNJ2f.zodaFSqGwvhd7LRt/7uIr34qkJ3KYOS', 1, '2023-10-27 12:00:00');
COMMIT;
