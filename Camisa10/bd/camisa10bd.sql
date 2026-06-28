-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 28/06/2026 às 22:58
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
-- Banco de dados: `camisa10bd`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `categorias`
--

CREATE TABLE `categorias` (
  `idCategoria` bigint(20) UNSIGNED NOT NULL,
  `tituloCategoria` varchar(100) DEFAULT NULL,
  `imgCategoria` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `categorias`
--

INSERT INTO `categorias` (`idCategoria`, `tituloCategoria`, `imgCategoria`) VALUES
(1, 'Fitness', 'assets/categorias/academiaFitness.jpg'),
(2, 'Futebol', 'assets/categorias/futebol.jpg'),
(3, 'Corrida', 'assets/categorias/corrida.jpg'),
(4, 'Vôlei', 'assets/categorias/volei.jpg'),
(5, 'Tennis', 'assets/categorias/tennis.jpg'),
(6, 'Tennis de Mesa', 'assets/categorias/tennisDeMesa.jpg'),
(7, 'Ciclismo', 'assets/categorias/ciclismo.jpg'),
(8, 'Badminton', 'assets/categorias/badminton.jpg'),
(9, 'Sem Categoria', 'assets/categorias/academiaFitness.jpg');

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes`
--

CREATE TABLE `clientes` (
  `idCliente` bigint(20) UNSIGNED NOT NULL,
  `nomeCliente` varchar(100) NOT NULL,
  `cpfCliente` char(11) NOT NULL,
  `telefoneCliente` char(11) NOT NULL,
  `emailCliente` varchar(100) NOT NULL,
  `senhaCliente` varchar(100) NOT NULL,
  `fotoCliente` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `clientes`
--

INSERT INTO `clientes` (`idCliente`, `nomeCliente`, `cpfCliente`, `telefoneCliente`, `emailCliente`, `senhaCliente`, `fotoCliente`) VALUES
(1, 'Edson Arantes do Nascimento', '84755770084', '6138368982', 'pele@gmail.com', '202cb962ac59075b964b07152d234b70', 'assets/clientes/pele.png'),
(2, 'Emilly Ferreira', '40028922000', '43991484498', 'emiscae@gmail.com', 'f5aa66ea28c60c3c68b52e0226956a56', 'assets/clientes/gato-legal-fresco-bestcat-gatos-160932690.webp'),
(4, 'Anne Ferreira', '74953132009', '8232278238', 'anne@gmail.com', '202cb962ac59075b964b07152d234b70', 'assets/clientes/Leghorn-e-uma-das-racas-de-galinhas-poedeiras-mais-famosas-1.webp'),
(6, 'Marianna Fernandes', '09270680010', '2836784860', 'marianna@gmail.com', '202cb962ac59075b964b07152d234b70', 'assets/clientes/gato-legal-fresco-bestcat-gatos-160932690.webp');

-- --------------------------------------------------------

--
-- Estrutura para tabela `enderecosclientes`
--

CREATE TABLE `enderecosclientes` (
  `idEndereco` int(11) NOT NULL,
  `idCliente` bigint(20) UNSIGNED NOT NULL,
  `cep` varchar(9) NOT NULL,
  `rua` varchar(255) NOT NULL,
  `numero` varchar(20) NOT NULL,
  `bairro` varchar(100) NOT NULL,
  `cidade` varchar(100) NOT NULL,
  `estado` char(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `enderecosclientes`
--

INSERT INTO `enderecosclientes` (`idEndereco`, `idCliente`, `cep`, `rua`, `numero`, `bairro`, `cidade`, `estado`) VALUES
(1, 1, '85722366', 'Rua Laranja Rosa', '74', 'Jardim Bandeirantes', 'Telêmaco Borba', 'PR'),
(2, 2, '84280000', 'Rua Ali', '123', 'Limoeiro', 'Curiúva', 'PR');

-- --------------------------------------------------------

--
-- Estrutura para tabela `formcontato`
--

CREATE TABLE `formcontato` (
  `idContato` int(11) NOT NULL,
  `nomeContato` varchar(100) DEFAULT NULL,
  `emailContato` varchar(100) DEFAULT NULL,
  `dataEnvio` timestamp NOT NULL DEFAULT current_timestamp(),
  `mensagemContato` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `formcontato`
--

INSERT INTO `formcontato` (`idContato`, `nomeContato`, `emailContato`, `dataEnvio`, `mensagemContato`) VALUES
(1, 'Marianna Fernandes', 'mariannafernandes697@gmail.com', '2026-06-23 13:18:46', 'Teste de envio do form Contato.'),
(2, 'Sofia Valle', 'vallesofia@gmail.com', '2026-06-23 13:35:26', 'Teste formContato 2'),
(3, 'Lucas Zanoni', 'lucaszanoni8@outlook.com', '2026-06-27 00:08:55', 'Comprei uma camiseta e me mandaram XXXS, quero meu dinheiro de volta!!!');

-- --------------------------------------------------------

--
-- Estrutura para tabela `itenspedido`
--

CREATE TABLE `itenspedido` (
  `idItem` int(11) NOT NULL,
  `idPedido` int(11) NOT NULL,
  `idProduto` bigint(20) UNSIGNED NOT NULL,
  `quantidade` int(11) NOT NULL,
  `precoUnitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `itenspedido`
--

INSERT INTO `itenspedido` (`idItem`, `idPedido`, `idProduto`, `quantidade`, `precoUnitario`) VALUES
(1, 1, 20, 1, 229.99),
(2, 1, 23, 1, 449.99),
(3, 2, 21, 1, 349.99);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `idPedido` int(11) NOT NULL,
  `idCliente` bigint(20) UNSIGNED NOT NULL,
  `idEndereco` int(11) NOT NULL,
  `dataPedido` datetime DEFAULT current_timestamp(),
  `formaPagamento` varchar(50) NOT NULL,
  `totalPedido` decimal(10,2) NOT NULL,
  `statusPedido` varchar(50) DEFAULT 'Pendente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pedidos`
--

INSERT INTO `pedidos` (`idPedido`, `idCliente`, `idEndereco`, `dataPedido`, `formaPagamento`, `totalPedido`, `statusPedido`) VALUES
(1, 1, 1, '2026-06-24 09:11:06', 'Pix', 649.98, 'Pendente'),
(2, 2, 2, '2026-06-26 21:22:56', 'Cartão de Crédito', 319.99, 'Pendente');

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `idProduto` bigint(20) UNSIGNED NOT NULL,
  `tituloProduto` varchar(100) NOT NULL,
  `idCategoria` bigint(20) UNSIGNED NOT NULL,
  `precoProduto` decimal(10,2) NOT NULL,
  `descricaoProduto` varchar(300) NOT NULL,
  `imgProduto` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`idProduto`, `tituloProduto`, `idCategoria`, `precoProduto`, `descricaoProduto`, `imgProduto`) VALUES
(3, 'Adidas Pro 2025', 1, 149.90, 'Malha respirável, corte anatômico para treino intenso.', 'assets/produtos/prod1.png'),
(4, 'Bola Futebol Elite', 2, 199.90, 'Costura TPU de alta resistência e voo previsível.', 'assets/produtos/prod3.png'),
(5, 'Chuteira Nike Society', 2, 219.90, 'Chuteira Society Nike Mercurial Vapor 16 Club', 'assets/produtos/prod5.png'),
(7, 'Bola de Vôlei Penalty Pro', 4, 169.90, 'Costura térmica para maior precisão e durabilidade.', 'assets/produtos/prod13.png'),
(8, 'Raquete Yonex ArcSaber', 8, 299.90, 'Equilíbrio perfeito entre velocidade e controle.', 'assets/produtos/prod15.png'),
(10, 'Kit Tênis de Mesa Starter', 6, 89.90, '2 raquetes + 3 bolas para iniciantes.', 'assets/produtos/prod24.png'),
(14, 'Camisa Seleção Costa do Marfim', 2, 449.99, 'Camisa Seleção Costa do Marfim Home 25/26 s/n° Torcedor Puma Masculina Camisa Seleção Costa do Marfim Home 25/26 s/n° Torcedor Puma Masculina - Laranja', 'assets/produtos/Copa10.png'),
(15, 'Camisa Seleção do Japão', 2, 379.99, 'Camisa Seleção do Japão I 2025/2026 Adidas Sem Número Azul Masculina', 'assets/produtos/Copa9.png'),
(16, 'Camisa Seleção Portugal', 2, 427.99, 'Camisa Seleção Portugal Home Torcedor 2026 s/n Puma Masculina Camisa Seleção Portugal Home Torcedor 2026 s/n Puma Masculina Camisa Seleção Portugal Home Torcedor 2026 s/n Puma Masculina Camisa Seleção Portugal Home Torcedor 2026 s/n Puma Masculina Camisa Seleção Portugal Home Torcedor 2026 s/n Puma ', 'assets/produtos/Copa8.png'),
(17, 'Camisa Seleção Espanha', 2, 799.99, 'Camisa I Jogador Espanha 26 2026 s/n Adidas', 'assets/produtos/Copa7.png'),
(18, 'Camisa Seleção Alemanha', 2, 799.99, 'Camisa I Jogador Alemanha 2026 s/n Adidas', 'assets/produtos/Copa6.png'),
(19, 'Camisa Seleção Argentina', 2, 799.99, 'Camisa I Jogador Argentina 2026 s/n Adidas', 'assets/produtos/Copa5.png'),
(20, 'Camisa Seleção Brasileira Preta 2022', 2, 229.99, 'Camisa Seleção Brasileira Masculina – Preta – Copa do Mundo 2022', 'assets/produtos/Copa4.png'),
(21, 'Camisa Brasil Adidas', 2, 349.99, 'Camisa Brasil Torcedor - Adidas Azul', 'assets/produtos/Copa3.png'),
(22, 'Camisa Brasil Copa 2014', 2, 259.99, 'Camisa Oficial Brasil copa 2014 Amarela - Nike Amarelo/Verde', 'assets/produtos/Copa2.png'),
(23, 'Camisa Brasil Copa 2026', 2, 449.99, 'Camisa Brasil Nike I 2026/27 Torcedor Pro Masculina', 'assets/produtos/Copa1.png'),
(24, 'Copa do Mundo 2026 Álbum de Figurinhas Capa Dura Ouro FIFA WORLD 2026', 2, 48.00, 'O álbum tem 980 cromos, sendo 68 deles especiais, e contempla as 48 seleções que participam do Mundial de 2026', 'assets/produtos/Copa11.png'),
(25, 'Kit com 12 Envelopes de Figurinhas FIFA WORLD CUP 2026', 2, 69.90, 'Envelopes no formato 80 x 100 mm com 7 cromos.', 'assets/produtos/Copa15.png'),
(26, 'Mascotes da Copa 2026 Pelúcia Zayu México', 2, 142.50, 'brinquedo de pelúcia com tema de leopardo mexicano.', 'assets/produtos/Copa13.png'),
(27, 'Mascotes da Copa 2026 Pelúcia Maple Canadá', 2, 149.90, 'Pelúcia 8 polegadas Copa do Mundo FIFA® 2026 Canadá - Mascote oficial Maple', 'assets/produtos/Copa12.png'),
(28, 'Mascote de Pelúcia da Copa do Mundo da FIFA 2026 Clutch EUA', 2, 152.60, 'Mascote de Pelúcia de 25 cm da Copa do Mundo da FIFA 2026 - EUA - One Size', 'assets/produtos/Copa14.png');

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtospromocao`
--

CREATE TABLE `produtospromocao` (
  `idPromocao` int(11) NOT NULL,
  `idProduto` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtospromocao`
--

INSERT INTO `produtospromocao` (`idPromocao`, `idProduto`) VALUES
(1, 3),
(2, 14),
(2, 15),
(2, 16),
(2, 17),
(2, 18),
(2, 19),
(2, 20),
(2, 21),
(2, 22),
(2, 23),
(2, 24),
(2, 25),
(2, 26),
(2, 27),
(2, 28);

-- --------------------------------------------------------

--
-- Estrutura para tabela `promocoes`
--

CREATE TABLE `promocoes` (
  `idPromocao` int(11) NOT NULL,
  `dataInicio` date NOT NULL,
  `dataFim` date NOT NULL,
  `tituloPromocao` varchar(100) DEFAULT NULL,
  `imgPromocao` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `promocoes`
--

INSERT INTO `promocoes` (`idPromocao`, `dataInicio`, `dataFim`, `tituloPromocao`, `imgPromocao`) VALUES
(1, '2026-05-05', '2026-06-15', 'Promoção Dia Dos Namorados Camisa 10', 'assets/promocoes/bannerDiaDosNamorados.png'),
(2, '2026-06-01', '2026-08-01', 'Promoção Copa do Mundo 2026 Camisa 10', 'assets/promocoes/bannerFifa.png');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`idCategoria`);

--
-- Índices de tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`idCliente`);

--
-- Índices de tabela `enderecosclientes`
--
ALTER TABLE `enderecosclientes`
  ADD PRIMARY KEY (`idEndereco`),
  ADD KEY `idCliente` (`idCliente`);

--
-- Índices de tabela `formcontato`
--
ALTER TABLE `formcontato`
  ADD PRIMARY KEY (`idContato`);

--
-- Índices de tabela `itenspedido`
--
ALTER TABLE `itenspedido`
  ADD PRIMARY KEY (`idItem`),
  ADD KEY `idPedido` (`idPedido`),
  ADD KEY `idProduto` (`idProduto`);

--
-- Índices de tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`idPedido`),
  ADD KEY `idCliente` (`idCliente`),
  ADD KEY `idEndereco` (`idEndereco`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`idProduto`),
  ADD KEY `idCategoria` (`idCategoria`);

--
-- Índices de tabela `produtospromocao`
--
ALTER TABLE `produtospromocao`
  ADD PRIMARY KEY (`idPromocao`,`idProduto`),
  ADD KEY `idProduto` (`idProduto`);

--
-- Índices de tabela `promocoes`
--
ALTER TABLE `promocoes`
  ADD PRIMARY KEY (`idPromocao`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `categorias`
--
ALTER TABLE `categorias`
  MODIFY `idCategoria` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `idCliente` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `enderecosclientes`
--
ALTER TABLE `enderecosclientes`
  MODIFY `idEndereco` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `formcontato`
--
ALTER TABLE `formcontato`
  MODIFY `idContato` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `itenspedido`
--
ALTER TABLE `itenspedido`
  MODIFY `idItem` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `idPedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `idProduto` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de tabela `promocoes`
--
ALTER TABLE `promocoes`
  MODIFY `idPromocao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `enderecosclientes`
--
ALTER TABLE `enderecosclientes`
  ADD CONSTRAINT `enderecosclientes_ibfk_1` FOREIGN KEY (`idCliente`) REFERENCES `clientes` (`idCliente`);

--
-- Restrições para tabelas `itenspedido`
--
ALTER TABLE `itenspedido`
  ADD CONSTRAINT `itenspedido_ibfk_1` FOREIGN KEY (`idPedido`) REFERENCES `pedidos` (`idPedido`),
  ADD CONSTRAINT `itenspedido_ibfk_2` FOREIGN KEY (`idProduto`) REFERENCES `produtos` (`idProduto`);

--
-- Restrições para tabelas `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`idCliente`) REFERENCES `clientes` (`idCliente`),
  ADD CONSTRAINT `pedidos_ibfk_2` FOREIGN KEY (`idEndereco`) REFERENCES `enderecosclientes` (`idEndereco`);

--
-- Restrições para tabelas `produtos`
--
ALTER TABLE `produtos`
  ADD CONSTRAINT `produtos_ibfk_1` FOREIGN KEY (`idCategoria`) REFERENCES `categorias` (`idCategoria`);

--
-- Restrições para tabelas `produtospromocao`
--
ALTER TABLE `produtospromocao`
  ADD CONSTRAINT `produtospromocao_ibfk_1` FOREIGN KEY (`idPromocao`) REFERENCES `promocoes` (`idPromocao`) ON DELETE CASCADE,
  ADD CONSTRAINT `produtospromocao_ibfk_2` FOREIGN KEY (`idProduto`) REFERENCES `produtos` (`idProduto`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
