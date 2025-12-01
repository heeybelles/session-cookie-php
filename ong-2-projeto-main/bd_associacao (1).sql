-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 12/11/2025 às 03:07
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `bd_associacao`
--
DROP DATABASE IF EXISTS bd_associacao;
CREATE DATABASE bd_associacao CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE bd_associacao;

-- --------------------------------------------------------

--
-- Estrutura para tabela `adocao`
--

CREATE TABLE `adocao` (
  `id` int(11) NOT NULL,
  `animal_id` int(11) NOT NULL,
  `adotante_nome` varchar(100) NOT NULL,
  `adotante_contato` varchar(100) DEFAULT NULL,
  `data_adocao` date NOT NULL,
  `processo_adaptacao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `agenda_eventos`
--

CREATE TABLE `agenda_eventos` (
  `id` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `data_inicio` date NOT NULL,
  `data_termino` date DEFAULT NULL,
  `horario_inicio` time NOT NULL,
  `horario_termino` time DEFAULT NULL,
  `status` enum('Concluido','Em breve','A concluir') DEFAULT 'A concluir',
  `local` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `animal`
--

CREATE TABLE `animal` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `tipo_animal` varchar(100) NOT NULL,
  `raca` varchar(100) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `status` enum('Chegou','Em avaliação','Em adoção','Adotado') DEFAULT 'Chegou',
  `vacinado` enum('Sim','Não') DEFAULT 'Não',
  `observacao_animal` text DEFAULT NULL,
  `data_chegada` date DEFAULT NULL,
  `porte` enum('Pequeno','Médio','Grande') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `doacoes`
--

CREATE TABLE `doacoes` (
  `id` int(11) NOT NULL,
  `tipo` enum('Coberta','Ração','Higienicos','Medicamento','Outro') NOT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `quantidade` int(11) NOT NULL,
  `data_doacao` date NOT NULL,
  `doador_nome` varchar(100) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `observacao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `estoque`
--

CREATE TABLE `estoque` (
  `id` int(11) NOT NULL,
  `item_nome` varchar(100) NOT NULL,
  `tipo` enum('Ração','Medicamento','Higienicos','Coberta','Outro') NOT NULL,
  `quantidade` int(11) NOT NULL,
  `validade` date DEFAULT NULL,
  `observacao` text DEFAULT NULL,
  `doacao_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `observacoes_animal`
--

CREATE TABLE `observacoes_animal` (
  `id` int(11) NOT NULL,
  `animal_id` int(11) NOT NULL,
  `data_observacao` datetime NOT NULL DEFAULT current_timestamp(),
  `tipo` enum('Saúde','Comportamento','Adaptação','Administrativa') NOT NULL,
  `observacao` text NOT NULL,
  `funcionario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `padrinho`
--

CREATE TABLE `padrinho` (
  `id` int(11) NOT NULL,
  `animal_id` int(11) NOT NULL,
  `padrinho_nome` varchar(100) NOT NULL,
  `padrinho_contato` varchar(100) DEFAULT NULL,
  `data_inicio` date NOT NULL,
  `observacao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `nivel` enum('Administrador','Funcionário') NOT NULL,
  `cargo_funcionario` varchar(100) DEFAULT NULL,
  `data_admissao_funcionario` date DEFAULT NULL,
  `idade` int(11) DEFAULT NULL,
  `horario_inicio` time DEFAULT NULL,
  `horario_termino` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `voluntario`
--

CREATE TABLE `voluntario` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `idade` int(11) DEFAULT NULL,
  `horario_inicio` time DEFAULT NULL,
  `horario_termino` time DEFAULT NULL,
  `data_inicio` date DEFAULT NULL,
  `disponibilidade` enum('Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo','Variável') DEFAULT NULL,
  `observacao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `adocao`
--
ALTER TABLE `adocao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `animal_id` (`animal_id`);

--
-- Índices de tabela `agenda_eventos`
--
ALTER TABLE `agenda_eventos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `animal`
--
ALTER TABLE `animal`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `doacoes`
--
ALTER TABLE `doacoes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `estoque`
--
ALTER TABLE `estoque`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doacao_id` (`doacao_id`);

--
-- Índices de tabela `observacoes_animal`
--
ALTER TABLE `observacoes_animal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `animal_id` (`animal_id`),
  ADD KEY `funcionario_id` (`funcionario_id`);

--
-- Índices de tabela `padrinho`
--
ALTER TABLE `padrinho`
  ADD PRIMARY KEY (`id`),
  ADD KEY `animal_id` (`animal_id`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `voluntario`
--
ALTER TABLE `voluntario`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `adocao`
--
ALTER TABLE `adocao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `agenda_eventos`
--
ALTER TABLE `agenda_eventos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `animal`
--
ALTER TABLE `animal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `doacoes`
--
ALTER TABLE `doacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `estoque`
--
ALTER TABLE `estoque`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `observacoes_animal`
--
ALTER TABLE `observacoes_animal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `padrinho`
--
ALTER TABLE `padrinho`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `voluntario`
--
ALTER TABLE `voluntario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `adocao`
--
ALTER TABLE `adocao`
  ADD CONSTRAINT `adocao_ibfk_1` FOREIGN KEY (`animal_id`) REFERENCES `animal` (`id`);

--
-- Restrições para tabelas `estoque`
--
ALTER TABLE `estoque`
  ADD CONSTRAINT `estoque_ibfk_1` FOREIGN KEY (`doacao_id`) REFERENCES `doacoes` (`id`);

--
-- Restrições para tabelas `observacoes_animal`
--
ALTER TABLE `observacoes_animal`
  ADD CONSTRAINT `observacoes_animal_ibfk_1` FOREIGN KEY (`animal_id`) REFERENCES `animal` (`id`),
  ADD CONSTRAINT `observacoes_animal_ibfk_2` FOREIGN KEY (`funcionario_id`) REFERENCES `usuario` (`id`);

--
-- Restrições para tabelas `padrinho`
--
ALTER TABLE `padrinho`
  ADD CONSTRAINT `padrinho_ibfk_1` FOREIGN KEY (`animal_id`) REFERENCES `animal` (`id`);
COMMIT;


ALTER TABLE voluntario MODIFY disponibilidade VARCHAR(255);
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
