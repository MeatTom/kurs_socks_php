-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Дек 18 2023 г., 14:59
-- Версия сервера: 10.11.4-MariaDB-1:10.11.4+maria~ubu2004
-- Версия PHP: 8.1.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `kosinov_kurs_bd`
--

-- --------------------------------------------------------

--
-- Структура таблицы `Cart`
--

CREATE TABLE `Cart` (
  `id` int(11) NOT NULL,
  `itemId` int(11) DEFAULT NULL,
  `amount` int(11) NOT NULL,
  `userId` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `Cart`
--

INSERT INTO `Cart` (`id`, `itemId`, `amount`, `userId`) VALUES
(31, 3, 7, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `Orders`
--

CREATE TABLE `Orders` (
  `id` int(11) NOT NULL,
  `customerId` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `order_time` datetime NOT NULL DEFAULT current_timestamp(),
  `address` text DEFAULT NULL,
  `idItem` int(10) NOT NULL,
  `amount` int(10) NOT NULL,
  `status` varchar(100) NOT NULL DEFAULT 'В обработке'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `Orders`
--

INSERT INTO `Orders` (`id`, `customerId`, `total_price`, `order_time`, `address`, `idItem`, `amount`, `status`) VALUES
(15, 4, 67.83, '2023-12-11 23:49:00', 'ул. Будапештская, дом 9, к2, кв. 92', 3, 17, 'В обработке'),
(19, 2, 39.90, '2023-12-16 19:46:51', 'ул. Плесецкая 34', 3, 10, 'В обработке'),
(20, 2, 39.90, '2023-12-16 19:47:33', 'ул. Плесецкая 34', 3, 10, 'В обработке'),
(21, 2, 39.90, '2023-12-16 19:47:33', 'ул. Плесецкая 34', 3, 10, 'В обработке'),
(22, 2, 23.94, '2023-12-16 19:48:57', 'ул. Плесецкая 34', 4, 6, 'В обработке'),
(23, 2, 63.84, '2023-12-16 19:48:57', 'ул. Плесецкая 34', 3, 16, 'В обработке'),
(24, 2, 63.84, '2023-12-16 20:14:49', 'ул. Плесецкая 34', 3, 16, 'Завершён'),
(25, 2, 39.90, '2023-12-16 20:14:49', 'ул. Плесецкая 34', 3, 10, 'В обработке'),
(26, 2, 35.91, '2023-12-16 20:14:49', 'ул. Плесецкая 34', 4, 9, 'В обработке'),
(27, 2, 3.99, '2023-12-16 21:20:33', 'ул. Плесецкая 39', 3, 1, 'В обработке'),
(28, 2, 39.90, '2023-12-16 21:27:03', 'ул. Плесецкая 9', 3, 10, 'В обработке');

-- --------------------------------------------------------

--
-- Структура таблицы `Tovar`
--

CREATE TABLE `Tovar` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `image` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `Tovar`
--

INSERT INTO `Tovar` (`id`, `name`, `description`, `price`, `stock`, `image`) VALUES
(3, 'SinSay Polar socks', 'Хлопковые мужские носки', 3.99, 100, '/product_photo/image_product_I28mnih4mSJf2Bnpv8WU1LNRWO6xnR3HFj6c5MjL.jpg'),
(4, 'SinSay Polar socks', 'Хлопковые мужские носки', 3.99, 111, '/product_photo/image_product_AF1h0iFDa265Fsi0xenrZ8YdvmckjP_6CQm7dhG8.jpg'),
(6, 'St. Friday socks', 'Крутые носки', 5.32, 300, '/product_photo/image_product_dBkkKuMuypvxJNFS9WoW04TLSBdLrYOlnNE_2O0t.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `Users`
--

CREATE TABLE `Users` (
  `id` int(11) NOT NULL,
  `fio` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `access_token` varchar(250) DEFAULT NULL,
  `isAdmin` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `Users`
--

INSERT INTO `Users` (`id`, `fio`, `email`, `password`, `phone`, `access_token`, `isAdmin`) VALUES
(2, 'Вася', 'meattom@yandex.com', '$2y$13$/eh8cFF1o3pZ8KrVJVxTNuKJvoguSAaD5smmasgicLc74NwC2K2g6', '89119682237', 'Tql1IaQvqc8bdtVoPaQUYxRPApAKTIh9', NULL),
(4, 'Про', '12@a.com', '$2y$13$sPbdaM1IpUs2cwdMu6ocsesA/Gocn0XA3Y.mMIqfpAc3P7UQE/zPy', '89119682237', 'lKBfxuT8j5KTwr3LrpL4zg3q-_et_FA1', 1),
(5, 'Жека', '123@gmail.ru', '$2y$13$10YWig10bGkBfGm4CxXQaeLGQTqJER5SYLzAXek9ZCor6U5ppWwlO', '8910237809', NULL, NULL),
(6, 'Жека', '123@gmail.com', '$2y$13$TR6ZFwQXOHpvUoTh6YmlDeSkE1udrHHPSOMgEbzPdmdci3qNk5.yC', '8910237809', NULL, NULL),
(7, 'Жека', '123@mail.com', '$2y$13$VXSswV0kgFPuydS4BG1LBe3fH31sYf/PhdKG7egB.x.MPgiCtnPeS', '89697123335', '4JcT6gYTM2qnQbMm7UqpU6RIu8uR0saA', NULL);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `Cart`
--
ALTER TABLE `Cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cart_item` (`itemId`),
  ADD KEY `fk_cart_user` (`userId`);

--
-- Индексы таблицы `Orders`
--
ALTER TABLE `Orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_order` (`customerId`),
  ADD KEY `fk_order_item` (`idItem`);

--
-- Индексы таблицы `Tovar`
--
ALTER TABLE `Tovar`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `Cart`
--
ALTER TABLE `Cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT для таблицы `Orders`
--
ALTER TABLE `Orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT для таблицы `Tovar`
--
ALTER TABLE `Tovar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `Users`
--
ALTER TABLE `Users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `Cart`
--
ALTER TABLE `Cart`
  ADD CONSTRAINT `fk_cart_item` FOREIGN KEY (`itemId`) REFERENCES `Tovar` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cart_user` FOREIGN KEY (`userId`) REFERENCES `Users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `Orders`
--
ALTER TABLE `Orders`
  ADD CONSTRAINT `fk_order_customer` FOREIGN KEY (`customerId`) REFERENCES `Users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_order_item` FOREIGN KEY (`idItem`) REFERENCES `Tovar` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
