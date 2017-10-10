-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Окт 10 2017 г., 11:02
-- Версия сервера: 5.7.16
-- Версия PHP: 7.1.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `dummy`
--

-- --------------------------------------------------------

--
-- Структура таблицы `event`
--

CREATE TABLE `event` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `content` text COLLATE utf8_unicode_ci
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `mail`
--

CREATE TABLE `mail` (
  `id` int(11) NOT NULL,
  `participant_id` int(11) NOT NULL COMMENT 'ID анкеты',
  `message` longtext NOT NULL COMMENT 'Сообщение',
  `created_at` int(11) DEFAULT NULL COMMENT 'Дата создания',
  `sent` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Флаг отправлен',
  `sent_at` timestamp NULL DEFAULT NULL,
  `title` varchar(255) NOT NULL COMMENT 'Заголовок',
  `approved` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Одобрен',
  `locked` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Заблокирован',
  `type_id` int(11) NOT NULL COMMENT 'ID типа'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Письма' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Структура таблицы `mail_types`
--

CREATE TABLE `mail_types` (
  `id` bigint(20) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT 'название типа шаблона',
  `template` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT 'название файла с шаблоном',
  `title` varchar(255) NOT NULL COMMENT 'Заголовок письма для этого шаблона',
  `auto_approve` tinyint(1) NOT NULL COMMENT 'признак, что письмо будет утверждено по умолчанию',
  `comment` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT 'пояснения к типу шаблона'
) ENGINE=InnoDB AVG_ROW_LENGTH=2730 DEFAULT CHARSET=utf8mb4 COMMENT='Таблица шаблонов для писем' ROW_FORMAT=DYNAMIC;

--
-- Дамп данных таблицы `mail_types`
--

INSERT INTO `mail_types` (`id`, `name`, `template`, `title`, `auto_approve`, `comment`) VALUES
(1, 'welcome', 'welcome', 'Ваша анкета участвует в конкурсе!', 1, 'Comment');

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int(11) UNSIGNED NOT NULL,
  `status` tinyint(1) DEFAULT '1' COMMENT 'Флаг активности',
  `created_at` int(11) DEFAULT NULL COMMENT 'Дата создания',
  `updated_at` int(11) DEFAULT NULL COMMENT 'Дата изменения',
  `email` varchar(255) NOT NULL,
  `username` varchar(255) DEFAULT NULL COMMENT 'Имя',
  `auth_key` varchar(32) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=16384 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `status`, `created_at`, `updated_at`, `email`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `role`) VALUES
(1, 1, 1477473954, 1506068184, 'admin@admin.ru', 'Admin', 'X0rUK31OylgXYc6', '$2y$13$d9UcPUrgeyYegJBupZ15ZOPGuifnjSa7mzxWSCOP5Ih0KIDXJhQc6', NULL, 'Admin');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`);

--
-- Индексы таблицы `mail`
--
ALTER TABLE `mail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `participant_id` (`participant_id`),
  ADD KEY `mail_locked_approved_sent` (`locked`,`approved`,`sent`),
  ADD KEY `mail_type_id` (`type_id`);

--
-- Индексы таблицы `mail_types`
--
ALTER TABLE `mail_types`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `reset_token_unuique_idx` (`password_reset_token`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `event`
--
ALTER TABLE `event`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `mail`
--
ALTER TABLE `mail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `mail_types`
--
ALTER TABLE `mail_types`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
