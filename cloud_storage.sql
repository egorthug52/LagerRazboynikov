-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: MariaDB-10.3
-- Время создания: Мар 25 2025 г., 19:42
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
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `insurance_num` varchar(255) NOT NULL,
  `first_name_char` text NOT NULL,
  `middle_name_char` text DEFAULT NULL,
  `last_name_char` text NOT NULL,
  `age` tinyint(4) NOT NULL,
  `register_num` varchar(255) NOT NULL,
  `diagnosis` int(11) NOT NULL,
  `confirmed_date` int(20) DEFAULT NULL,
  `cancellation_date` int(20) DEFAULT NULL,
  `disease_date` int(20) NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `creator_id` int(11) NOT NULL,
  `creator_name` varchar(255) NOT NULL,
  `region` varchar(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `patients`
--

INSERT INTO `patients` (`id`, `phone_number`, `first_name`, `middle_name`, `last_name`, `insurance_num`, `first_name_char`, `middle_name_char`, `last_name_char`, `age`, `register_num`, `diagnosis`, `confirmed_date`, `cancellation_date`, `disease_date`, `creation_date`, `creator_id`, `creator_name`, `region`) VALUES
(49, '+71234123412', 'Sdfgsd', 'Sdfg', 'Sdfg', '3242134', 'S**', 'S**', 'S**', 12, '43225435', 17, 1742504400, NULL, 1741640400, '2025-03-25 16:36:48', 6, '321', '77'),
(50, '+73425324523', 'Dfsgv', 'Zxcv', 'Zxcv', '435345435', 'D**', 'Z**', 'Z**', 23, '3244325', 19, 1742504400, NULL, 1741640400, '2025-03-25 16:37:34', 8, '234', '22');

-- --------------------------------------------------------

--
-- Структура таблицы `patient_files`
--

CREATE TABLE `patient_files` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `upload_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `regions`
--

CREATE TABLE `regions` (
  `id` int(11) NOT NULL,
  `region_name` varchar(100) NOT NULL,
  `region_code` varchar(3) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `regions`
--

INSERT INTO `regions` (`id`, `region_name`, `region_code`, `created_at`) VALUES
(1, 'Алтайский край', '22', '2025-03-16 08:10:29'),
(2, 'Амурская область', '28', '2025-03-16 08:10:29'),
(3, 'Архангельская область', '29', '2025-03-16 08:10:29'),
(4, 'Астраханская область', '30', '2025-03-16 08:10:29'),
(5, 'Белгородская область', '31', '2025-03-16 08:10:29'),
(6, 'Брянская область', '32', '2025-03-16 08:10:29'),
(7, 'Владимирская область', '33', '2025-03-16 08:10:29'),
(8, 'Волгоградская область', '34', '2025-03-16 08:10:29'),
(9, 'Вологодская область', '35', '2025-03-16 08:10:29'),
(10, 'Воронежская область', '36', '2025-03-16 08:10:29'),
(11, 'г. Москва', '77', '2025-03-16 08:10:29'),
(12, 'г. Санкт-Петербург', '78', '2025-03-16 08:10:29'),
(13, 'г. Севастополь', '92', '2025-03-16 08:10:29'),
(14, 'Донецкая Народная Республика', '80', '2025-03-16 08:10:29'),
(15, 'Еврейская автономная область', '79', '2025-03-16 08:10:29'),
(16, 'Забайкальский край', '75', '2025-03-16 08:10:29'),
(17, 'Запорожская область', '85', '2025-03-16 08:10:29'),
(18, 'Ивановская область', '37', '2025-03-16 08:10:29'),
(19, 'Иркутская область', '38', '2025-03-16 08:10:29'),
(20, 'Кабардино-Балкарская Республика', '07', '2025-03-16 08:10:29'),
(21, 'Калининградская область', '39', '2025-03-16 08:10:29'),
(22, 'Калужская область', '40', '2025-03-16 08:10:29'),
(23, 'Камчатский край', '41', '2025-03-16 08:10:29'),
(24, 'Карачаево-Черкесская Республика', '09', '2025-03-16 08:10:29'),
(25, 'Кемеровская область', '42', '2025-03-16 08:10:29'),
(26, 'Кировская область', '43', '2025-03-16 08:10:29'),
(27, 'Костромская область', '44', '2025-03-16 08:10:29'),
(28, 'Краснодарский край', '23', '2025-03-16 08:10:29'),
(29, 'Красноярский край', '24', '2025-03-16 08:10:29'),
(30, 'Курганская область', '45', '2025-03-16 08:10:29'),
(31, 'Курская область', '46', '2025-03-16 08:10:29'),
(32, 'Ленинградская область', '47', '2025-03-16 08:10:29'),
(33, 'Липецкая область', '48', '2025-03-16 08:10:29'),
(34, 'Луганская Народная Республика', '81', '2025-03-16 08:10:29'),
(35, 'Магаданская область', '49', '2025-03-16 08:10:29'),
(36, 'Московская область', '50', '2025-03-16 08:10:29'),
(37, 'Мурманская область', '51', '2025-03-16 08:10:29'),
(38, 'Ненецкий автономный округ', '83', '2025-03-16 08:10:29'),
(39, 'Нижегородская область', '52', '2025-03-16 08:10:29'),
(40, 'Новгородская область', '53', '2025-03-16 08:10:29'),
(41, 'Новосибирская область', '54', '2025-03-16 08:10:29'),
(42, 'Омская область', '55', '2025-03-16 08:10:29'),
(43, 'Оренбургская область', '56', '2025-03-16 08:10:29'),
(44, 'Орловская область', '57', '2025-03-16 08:10:29'),
(45, 'Пензенская область', '58', '2025-03-16 08:10:29'),
(46, 'Пермский край', '59', '2025-03-16 08:10:29'),
(47, 'Приморский край', '25', '2025-03-16 08:10:29'),
(48, 'Псковская область', '60', '2025-03-16 08:10:29'),
(49, 'Республика Адыгея', '01', '2025-03-16 08:10:29'),
(50, 'Республика Алтай', '04', '2025-03-16 08:10:29'),
(51, 'Республика Башкортостан', '02', '2025-03-16 08:10:29'),
(52, 'Республика Бурятия', '03', '2025-03-16 08:10:29'),
(53, 'Республика Дагестан', '05', '2025-03-16 08:10:29'),
(54, 'Республика Ингушетия', '06', '2025-03-16 08:10:29'),
(55, 'Республика Калмыкия', '08', '2025-03-16 08:10:29'),
(56, 'Республика Карелия', '10', '2025-03-16 08:10:29'),
(57, 'Республика Коми', '11', '2025-03-16 08:10:29'),
(58, 'Республика Крым', '82', '2025-03-16 08:10:29'),
(59, 'Республика Марий Эл', '12', '2025-03-16 08:10:29'),
(60, 'Республика Мордовия', '13', '2025-03-16 08:10:29'),
(61, 'Республика Саха (Якутия)', '14', '2025-03-16 08:10:29'),
(62, 'Республика Северная Осетия — Алания', '15', '2025-03-16 08:10:29'),
(63, 'Республика Татарстан', '16', '2025-03-16 08:10:29'),
(64, 'Республика Тыва', '17', '2025-03-16 08:10:29'),
(65, 'Республика Хакасия', '19', '2025-03-16 08:10:29'),
(66, 'Ростовская область', '61', '2025-03-16 08:10:29'),
(67, 'Рязанская область', '62', '2025-03-16 08:10:29'),
(68, 'Самарская область', '63', '2025-03-16 08:10:29'),
(69, 'Саратовская область', '64', '2025-03-16 08:10:29'),
(70, 'Сахалинская область', '65', '2025-03-16 08:10:29'),
(71, 'Свердловская область', '66', '2025-03-16 08:10:29'),
(72, 'Смоленская область', '67', '2025-03-16 08:10:29'),
(73, 'Ставропольский край', '26', '2025-03-16 08:10:29'),
(74, 'Тамбовская область', '68', '2025-03-16 08:10:29'),
(75, 'Тверская область', '69', '2025-03-16 08:10:29'),
(76, 'Томская область', '70', '2025-03-16 08:10:29'),
(77, 'Тульская область', '71', '2025-03-16 08:10:29'),
(78, 'Тюменская область', '72', '2025-03-16 08:10:29'),
(79, 'Удмуртская Республика', '18', '2025-03-16 08:10:29'),
(80, 'Ульяновская область', '73', '2025-03-16 08:10:29'),
(81, 'Хабаровский край', '27', '2025-03-16 08:10:29'),
(82, 'Ханты-Мансийский автономный округ — Югра', '86', '2025-03-16 08:10:29'),
(83, 'Херсонская область', '84', '2025-03-16 08:10:29'),
(84, 'Челябинская область', '74', '2025-03-16 08:10:29'),
(85, 'Чеченская Республика', '95', '2025-03-16 08:10:29'),
(86, 'Чувашская Республика', '21', '2025-03-16 08:10:29'),
(87, 'Чукотский автономный округ', '87', '2025-03-16 08:10:29'),
(88, 'Ямало-Ненецкий автономный округ', '89', '2025-03-16 08:10:29'),
(89, 'Ярославская область', '76', '2025-03-16 08:10:29');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `isAdmin` tinyint(1) NOT NULL DEFAULT 0,
  `superuser` tinyint(1) NOT NULL DEFAULT 0,
  `user_region` varchar(3) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `middle_name` varchar(30) DEFAULT NULL,
  `last_name` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`, `isAdmin`, `superuser`, `user_region`, `first_name`, `middle_name`, `last_name`, `email`) VALUES
(5, '123', '$2y$10$DizqDX1gIL/6e5dBJgjof.1DlB4DXyQ9mU8x78PVbXSwVYewbf0Si', '2025-03-16 08:31:53', 1, 1, '77', 'Имя', 'Отчество', 'Фамилия', 'terewgdfsgbdsfbst@mail.com'),
(6, '321', '$2y$10$laomIgqVpiSmyPPn2Cfzb.c2NP9PZ1183ZBfwEMSormfLLvYSZbMW', '2025-03-17 17:54:00', 1, 0, '22', 'Тестовый', 'Региональный', 'Админ', 'hjlj@gasldj.asd'),
(8, '234', '$2y$10$2QuGqyx.N3v6r/J3.ZcS1.RJ.txt2GdNkOPxk67Z3VzQlxWMA7Hle', '2025-03-25 16:30:25', 0, 0, '22', 'Fasdf', 'Asdf', 'Sdfg', 'sdfg@gfdas.consda');

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
-- Индексы таблицы `patient_files`
--
ALTER TABLE `patient_files`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `file_path` (`file_path`) USING BTREE,
  ADD KEY `patient_id` (`patient_id`);

--
-- Индексы таблицы `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT для таблицы `patient_files`
--
ALTER TABLE `patient_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT для таблицы `regions`
--
ALTER TABLE `regions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_ibfk_1` FOREIGN KEY (`diagnosis`) REFERENCES `diseases` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `patient_files`
--
ALTER TABLE `patient_files`
  ADD CONSTRAINT `patient_files_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
