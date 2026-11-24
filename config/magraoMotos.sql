-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 23/11/2026 às 23:34
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `magraoMotos`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `actions`
--

CREATE TABLE `actions` (
  `action_id` int(11) NOT NULL,
  `action_title` varchar(100) NOT NULL,
  `action_description` varchar(500) NOT NULL,
  `action_type` enum('in','out') NOT NULL,
  `action_method` varchar(15) NOT NULL,
  `action_value` decimal(10,2) NOT NULL,
  `action_datetime` datetime NOT NULL,
  `services_service_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `actions`
--

INSERT INTO `actions` (`action_id`, `action_title`, `action_description`, `action_type`, `action_method`, `action_value`, `action_datetime`, `services_service_id`) VALUES
(6, '1', 'teste', 'in', 'pix', 12.00, '2026-11-21 23:08:40', NULL),
(7, '2', 'teste', 'out', 'pix', 345.00, '2026-11-21 23:08:53', NULL),
(8, '3', 'teste', 'out', 'dinheiro', 342.00, '2026-11-21 23:09:01', NULL),
(9, 'Teste 4', 'A barata diz que tem 7 saias de filo, é mentira da barata, ela tem é uma só, hahaha, hohoho, ela tem é uma só', 'in', 'dinheiro', 56.00, '2026-11-21 23:09:17', NULL),
(10, '5', 'teste', 'in', 'outra', 2344.00, '2026-11-21 23:09:31', NULL),
(11, '6', 'teste teste teste teste teste teste teste', 'out', 'dinheiro', 343.00, '2026-11-21 23:09:44', NULL),
(12, '7', 'asdf', 'in', 'dinheiro', 34.00, '2026-11-21 23:09:59', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `services`
--

CREATE TABLE `services` (
  `service_id` int(11) NOT NULL,
  `service_title` varchar(100) NOT NULL,
  `service_description` varchar(500) NOT NULL,
  `service_date` date NOT NULL,
  `service_status` varchar(20) NOT NULL DEFAULT 'Pendente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `services`
--

INSERT INTO `services` (`service_id`, `service_title`, `service_description`, `service_date`, `service_status`) VALUES
(4, 'Serviço 1', 'O melhor serviço do brasil', '2026-11-22', 'Pendente'),
(5, 'Serviço 1 o melhor serviço do brasil, desparado o primeiro, não tem como', '', '2026-11-22', 'Pendente');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `actions`
--
ALTER TABLE `actions`
  ADD PRIMARY KEY (`action_id`),
  ADD KEY `fk_actions_services` (`services_service_id`);

--
-- Índices de tabela `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`service_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `actions`
--
ALTER TABLE `actions`
  MODIFY `action_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `services`
--
ALTER TABLE `services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `actions`
--
ALTER TABLE `actions`
  ADD CONSTRAINT `fk_actions_services` FOREIGN KEY (`services_service_id`) REFERENCES `services` (`service_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
