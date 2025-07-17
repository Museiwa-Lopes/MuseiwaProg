-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 29-Maio-2025 às 21:20
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `sigo`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbl_chefe_bairro`
--

CREATE TABLE `tbl_chefe_bairro` (
  `id` int(255) NOT NULL,
  `nome_completo` varchar(255) NOT NULL,
  `genero` varchar(255) NOT NULL,
  `contacto` bigint(9) NOT NULL,
  `bairro` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `tbl_chefe_bairro`
--

INSERT INTO `tbl_chefe_bairro` (`id`, `nome_completo`, `genero`, `contacto`, `bairro`) VALUES
(1, 'Amorim de Carvalho', 'Masculino', 868675856, '19 de Outubro'),
(2, 'Lidson André Pave', 'Masculino', 868675856, 'Nhamalonza'),
(3, 'Exarque Paulino Mambo', 'Masculino', 860655527, 'Central A'),
(4, 'Wagner Francisco Uache', 'Masculino', 874164703, 'Gamela'),
(5, 'Steice Jaime Chelene', 'Feminino', 862357290, 'Aeroporto'),
(6, 'Mbalane Francisco', 'Masculino', 876556756, 'Alto Macassa');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbl_cidadao`
--

CREATE TABLE `tbl_cidadao` (
  `id` int(255) NOT NULL,
  `nome_cidadao` text NOT NULL,
  `contacto_cidadao` int(11) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo_usuario` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `tbl_cidadao`
--

INSERT INTO `tbl_cidadao` (`id`, `nome_cidadao`, `contacto_cidadao`, `senha`, `tipo_usuario`) VALUES
(1, 'Museiwa Lopes', 846461845, '$2y$12$Jb1xg6MkQA86Y9.k5d/y3.wi49iBv313hZKhG/A00NNs/fGjsOTVW', 'Cidadao');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbl_notificacao`
--

CREATE TABLE `tbl_notificacao` (
  `nome_visado` varchar(255) NOT NULL,
  `data_comparecimento` date NOT NULL,
  `hora_notificar` date NOT NULL,
  `data_assinatura` date NOT NULL,
  `oficial_permanencia` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbl_ocorrencia`
--

CREATE TABLE `tbl_ocorrencia` (
  `id` int(255) NOT NULL,
  `nome_cidadao` varchar(255) NOT NULL,
  `sexo_cidadao` varchar(255) NOT NULL,
  `estado_civil` varchar(255) NOT NULL,
  `idade_cidadao` int(255) NOT NULL,
  `mae_cidadao` varchar(255) NOT NULL,
  `pai_cidadao` varchar(255) NOT NULL,
  `data_nascimento` varchar(255) NOT NULL,
  `naturalidade_distrito` varchar(255) NOT NULL,
  `provincia` varchar(255) NOT NULL,
  `nacionalidade` varchar(255) NOT NULL,
  `local_trabalho` varchar(255) NOT NULL,
  `bairro` varchar(255) NOT NULL,
  `chefe_bairro` varchar(255) NOT NULL,
  `contacto_chefe_bairro` int(255) NOT NULL,
  `contacto_cidadao` int(255) NOT NULL,
  `classificacao` varchar(255) NOT NULL,
  `data_ocorrido` date DEFAULT NULL,
  `hora_ocorrido` time DEFAULT NULL,
  `lugar_ocorrido` varchar(255) NOT NULL,
  `endereco_caso` varchar(255) NOT NULL,
  `nome_visado` varchar(255) NOT NULL,
  `contacto_visado` varchar(255) NOT NULL,
  `descricao_ocorrido` text NOT NULL,
  `estado` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `tbl_ocorrencia`
--

INSERT INTO `tbl_ocorrencia` (`id`, `nome_cidadao`, `sexo_cidadao`, `estado_civil`, `idade_cidadao`, `mae_cidadao`, `pai_cidadao`, `data_nascimento`, `naturalidade_distrito`, `provincia`, `nacionalidade`, `local_trabalho`, `bairro`, `chefe_bairro`, `contacto_chefe_bairro`, `contacto_cidadao`, `classificacao`, `data_ocorrido`, `hora_ocorrido`, `lugar_ocorrido`, `endereco_caso`, `nome_visado`, `contacto_visado`, `descricao_ocorrido`, `estado`) VALUES
(63, 'Fátima Nhanombe', 'Femenino', 'Casado(a)', 34, 'Paulina Manhice', 'Castro Nhanombe', '1993-02-28', 'Muanza', 'Sofala', 'Moçambicana', 'Vilankulo, Mercado novo', 'Gamela', 'Wagner Francisco Uache', 868675856, 874164703, 'Violência', '2025-04-26', '23:39:00', 'Na sua residência', 'Vilankulo, Gamela', 'Frans Ndeve', '846461845', 'Eu, Maria Júlia António, venho por este meio apresentar queixa contra o meu esposo, de nome Ernesto Manuel, de 38 anos de idade, com quem vivo há cerca de 12 anos, pai dos meus três filhos menores.\n\nNo dia 28 de Maio de 2025, por volta das 21 horas, o meu esposo chegou a casa visivelmente embriagado. Após um breve desentendimento relacionado com a gestão do dinheiro da casa, ele começou a proferir insultos, chamando-me de \"inútil\", \"vagabunda\" e acusando-me falsamente de infidelidade.\n\nDe seguida, partiu um copo de vidro na parede e ameaçou agredir-me com os estilhaços. Apesar de eu tentar manter a calma e evitar o confronto, ele aproximou-se de forma violenta e empurrou-me contra a parede, causando-me ferimentos ligeiros no braço esquerdo. As crianças presenciaram todo o acto e ficaram em estado de choque.\n\nEsta não é a primeira vez que sou vítima de maus-tratos físicos e psicológicos por parte do meu esposo. Contudo, por medo e vergonha, nunca tive coragem de apresentar queixa. Mas desta vez, temo pela minha integridade física e a dos meus filhos, pelo que decidi procurar apoio e protecção junto da PRM.\n\nSolicito a intervenção das autoridades competentes para que sejam tomadas as devidas providências legais, de forma a garantir a minha segurança e dos meus filhos, bem como responsabilizar o autor das agressões.', 'notificar');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbl_usuarios`
--

CREATE TABLE `tbl_usuarios` (
  `id` int(255) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `apelido` varchar(255) NOT NULL,
  `genero` varchar(255) NOT NULL,
  `distintivo` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo_usuario` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `tbl_usuarios`
--

INSERT INTO `tbl_usuarios` (`id`, `nome`, `apelido`, `genero`, `distintivo`, `senha`, `tipo_usuario`) VALUES
(4, 'Museiwa', 'Lopes', 'Masculino', '225018', '$2y$10$zHkbeKxYl8RGQcPA0xxpBubh6sFV3EG/7pfINrylqFlNt9dMmaWPi', 'Admin_Master'),
(5, 'Francisco', 'Siueia', 'Masculino', '225019', '$2y$10$SmLSzUn4YfQUQpr6srQ84O6vksHAFdFCqYtKw9JO2zytQhei0t1te', 'Agente'),
(6, 'Faustino', 'Guinge', 'Masculino', '225014', '$2y$10$6.HIXCRC1rViRmqVK1ObqOG8CAgPYsoWG3Vo2JOnyddG4hwQLgwU.', 'Agente'),
(7, 'Esménia', 'Uqueio', 'Feminino', '225017', '$2y$10$Hm5hE1/ZPluyT4B6TLmq8.bVDbsfRcUYBlmLwEl.6HDd/U7XZe2Ku', 'Agente'),
(8, 'Dercio', 'Hilário', 'Masculino', '225030', '$2y$10$5YI745417jUNjgIvlTFUoueE8KP.cN4V3fi/5bjdVQ2p8Py96wUK.', 'Agente'),
(9, 'Shelsy', 'Nicol', 'Feminino', '225040', '$2y$10$vXRRVnYE53hpp3zkAQoa4.ZtcQUMq3VcsvupLtDXMmCW4ousqiLeW', 'Agente'),
(10, 'Bernardete', 'Matsimbe', 'Feminino', '234567', '$2y$12$eV18DDO/tyqJWtaF5Sauoe56R1hpHxtlBuGvblwNu9n3eBnMyylRi', 'Agente'),
(12, 'Iolanda', 'Mauiango', 'Feminino', '225003', '$2y$12$E6zvRM5zN2rPx5nwtwmi7ugYFOOAN90FHwfh7jZZob3mSuKz1jE2S', 'Admin'),
(13, 'Saíde Paulo', 'Joaquim', 'Masculino', '024567', '$2y$12$xwuZgS98TRqNTKc8FLbMbek9nCKjPxVLxuDpIKWUV8EB3eDeTArNe', 'Admin');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `tbl_chefe_bairro`
--
ALTER TABLE `tbl_chefe_bairro`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tbl_cidadao`
--
ALTER TABLE `tbl_cidadao`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tbl_ocorrencia`
--
ALTER TABLE `tbl_ocorrencia`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tbl_usuarios`
--
ALTER TABLE `tbl_usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tbl_chefe_bairro`
--
ALTER TABLE `tbl_chefe_bairro`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `tbl_cidadao`
--
ALTER TABLE `tbl_cidadao`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `tbl_ocorrencia`
--
ALTER TABLE `tbl_ocorrencia`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT de tabela `tbl_usuarios`
--
ALTER TABLE `tbl_usuarios`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
