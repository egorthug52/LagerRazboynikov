-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: MariaDB-10.3
-- Время создания: Янв 27 2025 г., 21:13
-- Версия сервера: 10.3.39-MariaDB
-- Версия PHP: 8.1.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `cloud_storage`
--

-- --------------------------------------------------------

--
-- Структура таблицы `diseases`
--

CREATE TABLE `diseases` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `mkb_kod` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `diseases`
--

INSERT INTO `diseases` (`id`, `name`, `mkb_kod`) VALUES
(1, 'Дерматофития', 'B35'),
(2, 'Лептоспироз', 'A27'),
(3, 'Шигеллез', 'A03'),
(4, 'Другие уточненные острые вирусные гепатиты ', 'B17.8'),
(5, 'Гастроэнтерит и колит неуточненного происхождения ', 'A09.9'),
(6, 'Токсическое действие метанола', 'T51.1'),
(7, 'Флегмона лица', 'L03.2'),
(8, 'Другие вирусные энтериты', 'A08.3'),
(9, 'Вирусный гепатит неуточненный', 'B19'),
(10, 'Туберкулез других органов', 'A18'),
(11, 'Сывороточная невропатия', 'G61.1'),
(12, 'Другие бактериальные пищевые отравления, не классифицированные в других рубриках', 'A05'),
(13, 'Острая гастроэнтеропатия, вызванная возбудителем Норволк', 'A08.1'),
(14, 'Вялая тетраплегия', 'G82.3'),
(15, 'Менингит неуточненный', 'G03.9'),
(16, 'Болезнь, вызванная вирусом Чикунгунья ', 'A92.0'),
(17, 'Менингококковая инфекция', 'A39'),
(18, 'Отравление другими и неуточненными психодислептиками [галлюциногенами]', 'T40.9'),
(19, 'Отравление бензодиазепинами', 'T42.4'),
(20, 'Кьясанурская лесная болезнь ', 'A98.2'),
(21, 'Крымская геморрагическая лихорадка (вызванная вирусом Конго)', 'A98.0'),
(22, 'Аденовирусный энцефалит (G05.1*)', 'A85.1');

-- --------------------------------------------------------

--
-- Структура таблицы `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) NOT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `insurance_num` varchar(255) NOT NULL,
  `first_name_char` text NOT NULL,
  `middle_name_char` text NOT NULL,
  `last_name_char` text NOT NULL,
  `age` tinyint(4) NOT NULL,
  `register_num` varchar(255) NOT NULL,
  `diagnosis` int(11) NOT NULL,
  `confirmed_date` int(11) DEFAULT NULL,
  `cancellation_date` int(11) DEFAULT NULL,
  `disease_date` int(11) NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `creator_id` int(11) NOT NULL,
  `creator_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `patients`
--

INSERT INTO `patients` (`id`, `phone_number`, `first_name`, `middle_name`, `last_name`, `insurance_num`, `first_name_char`, `middle_name_char`, `last_name_char`, `age`, `register_num`, `diagnosis`, `confirmed_date`, `cancellation_date`, `disease_date`, `creation_date`, `creator_id`, `creator_name`) VALUES
(16, '+73223423432', 'Shamann', '', 'Goldenboy', '12312', 'S**', '**', 'G**', 12, 'ssad', 13, 1737147600, NULL, 1737061200, '2025-01-26 17:08:23', 3, '123'),
(17, '+74123412341', 'Shamann', '', 'Goldenboy', '12341234123', 'S**', '**', 'G**', 12, '234324', 17, NULL, 1737234000, 1737061200, '2025-01-26 17:26:13', 4, '321');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `isAdmin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`, `isAdmin`) VALUES
(2, 'root', '$2y$10$QKw5bZXSDOmVdL3nVPs5cOjip4dnf/1pe6r2EDTNmcMmyRSxf6LEm', '2024-12-26 17:43:11', 0),
(3, '123', '$2y$10$C3WRE/mzUsMxEC4opc5Cxu1vyoLRTagdV.U4Ce8GwBV7/bHeloHwy', '2025-01-03 11:09:20', 1),
(4, '321', '$2y$10$kHtu/w5ow0G14dxc61XADepg.chzqb6vDVjRQcUy.zsiNGxKpF0xS', '2025-01-07 13:24:33', 0);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `diseases`
--
ALTER TABLE `diseases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Индексы таблицы `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `register_num` (`register_num`),
  ADD KEY `diagnosis` (`diagnosis`),
  ADD KEY `creator_id` (`creator_id`),
  ADD KEY `creator_name` (`creator_name`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `diseases`
--
ALTER TABLE `diseases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT для таблицы `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_ibfk_1` FOREIGN KEY (`diagnosis`) REFERENCES `diseases` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `patients_ibfk_2` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `patients_ibfk_3` FOREIGN KEY (`creator_name`) REFERENCES `users` (`username`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
