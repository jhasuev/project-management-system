CREATE DATABASE IF NOT EXISTS `pms_db`;

--
-- База данных: `pms_db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `boardactions`
--


CREATE TABLE `pms_db`.`boardactions` (
  `id` int(11) NOT NULL,
  `boardID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `actionType` varchar(50) NOT NULL,
  `actionID` int(11) NOT NULL,
  `time` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Структура таблицы `boardcards`
--

CREATE TABLE `pms_db`.`boardcards` (
  `id` int(11) NOT NULL,
  `boardID` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `order_pos` int(22) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Структура таблицы `boardcomments`
--

CREATE TABLE `pms_db`.`boardcomments` (
  `id` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `taskID` int(11) NOT NULL,
  `comment` text NOT NULL,
  `time` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Структура таблицы `boardparticipants`
--

CREATE TABLE `pms_db`.`boardparticipants` (
  `id` int(22) NOT NULL,
  `boardID` int(22) NOT NULL,
  `userID` int(22) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Структура таблицы `boards`
--

CREATE TABLE `pms_db`.`boards` (
  `id` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `is_private` tinyint(1) DEFAULT '1',
  `color` int(2) DEFAULT NULL,
  `created_time` int(22) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Структура таблицы `boardtasks`
--

CREATE TABLE `pms_db`.`boardtasks` (
  `id` int(11) NOT NULL,
  `boardID` int(11) NOT NULL,
  `cardID` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `deadline` int(20) NOT NULL,
  `checkList` text NOT NULL,
  `order_pos` int(10) NOT NULL DEFAULT '1',
  `done` int(1) NOT NULL DEFAULT '0',
  `status` varchar(20) NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Структура таблицы `users`
--

CREATE TABLE `pms_db`.`users` (
  `id` int(22) NOT NULL,
  `login` varchar(50) NOT NULL,
  `password` varchar(32) NOT NULL,
  `email` varchar(150) NOT NULL,
  `fullName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `boardactions`
--
ALTER TABLE `pms_db`.`boardactions`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `boardcards`
--
ALTER TABLE `pms_db`.`boardcards`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `boardcomments`
--
ALTER TABLE `pms_db`.`boardcomments`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `boardparticipants`
--
ALTER TABLE `pms_db`.`boardparticipants`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `boards`
--
ALTER TABLE `pms_db`.`boards`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `boardtasks`
--
ALTER TABLE `pms_db`.`boardtasks`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `pms_db`.`users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `boardactions`
--
ALTER TABLE `pms_db`.`boardactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `boardcards`
--
ALTER TABLE `pms_db`.`boardcards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `boardcomments`
--
ALTER TABLE `pms_db`.`boardcomments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `boardparticipants`
--
ALTER TABLE `pms_db`.`boardparticipants`
  MODIFY `id` int(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `boards`
--
ALTER TABLE `pms_db`.`boards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `boardtasks`
--
ALTER TABLE `pms_db`.`boardtasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `pms_db`.`users`
  MODIFY `id` int(22) NOT NULL AUTO_INCREMENT;


